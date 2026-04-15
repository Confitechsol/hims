<?php

namespace App\Http\Controllers;

use App\Models\IpdDetail;
use App\Models\IpdDaywiseBedCharge;
use App\Models\IpdCharges;
use App\Models\IpdPackage;
use App\Models\PathologyBilling;
use App\Models\RadiologyBilling;
use App\Models\Transaction;
use App\Models\DoctorVisit;
use App\Models\Hospital;
use App\Models\DischargeCard;
use App\Models\PatientBedHistory;
use App\Models\BedGroup;
use App\Models\Doctor;
use App\Services\IpdPackageService;
use App\Support\BedBillingPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
// use App\Helpers\NumberToWords; // Commented out to force full namespace usage

class IpdBillingController extends Controller
{
    /**
     * Search IPD patients
     */
    public function search(Request $request)
    {
        try {
            $search = trim($request->get('search', ''));
            
            // Include all IPD patients (both discharged and non-discharged) for billing purposes
            $query = IpdDetail::with(['patient', 'doctor']);

            // Apply search filter if provided
            if ($search !== '') {
                $query->where(function($q) use ($search) {
                    $q->where('ipd_no', 'LIKE', "%{$search}%")
                        ->orWhereHas('patient', function($subQ) use ($search) {
                            $subQ->where('patient_name', 'LIKE', "%{$search}%")
                                 ->orWhere('mobileno', 'LIKE', "%{$search}%");
                        });
                });
            }

            $ipdPatients = $query->orderBy('date', 'desc')->limit(100)->get();

            $data = $ipdPatients->map(function($ipd) {
                $patient = $ipd->patient;
                $isDischarged = $ipd->discharged == 'yes';
                $dischargeStatus = $isDischarged ? ' (Discharged)' : '';

                $patientName = $patient ? ($patient->patient_name ?? 'N/A') : 'N/A';
                $phone = $patient ? ($patient->mobileno ?? '') : '';
                
                return [
                    'id' => $ipd->id,
                    'ipd_no' => $ipd->ipd_no ?? 'N/A',
                    'patient_name' => $patientName,
                    'phone' => $phone,
                    'discharged' => $isDischarged,
                    'discharged_date' => $ipd->discharged_date,
                    'display_text' => ($ipd->ipd_no ?? 'N/A') . ' - ' . $patientName . $dischargeStatus,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            \Log::error('IPD billing search failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error while searching IPD patients.',
            ], 500);
        }
    }

    /**
     * Get breakup bill for IPD patient
     */
    public function breakup($ipdId)
    {
        $ipd = IpdDetail::with(['patient', 'doctor', 'bedGroup', 'bedDetail', 'duePatientPartyDoctor'])
            ->findOrFail($ipdId);

        $doctors = Doctor::orderBy('name')->get(['id', 'name', 'surname']);

        // Calculate all charges; for discharged IPD, lock to discharge date.
        $endDate = $this->resolveBillingEndDateForIpd($ipd);
        $breakup = $this->calculateBreakup($ipdId, $endDate);

        // Discount and due patient party for display (final bill logic)
        $totalDiscount = (float) ($ipd->mou_discount ?? 0) + (float) ($ipd->special_discount ?? 0);
        $duePatientPartyAmount = (float) ($ipd->due_patient_party_amount ?? 0);
        $outstanding = $breakup['outstanding'] ?? 0;
        $netBalance = max(0, $outstanding - $totalDiscount - $duePatientPartyAmount);
        $breakup['total_discount'] = $totalDiscount;
        $breakup['due_patient_party_amount'] = $duePatientPartyAmount;
        $breakup['net_balance'] = $netBalance;

        // Get detailed date-wise breakdown with same boundary as summary.
        $detailedBreakup = $this->getDetailedBreakup($ipdId, $ipd, $endDate);

        return view('admin.billing.ipd_breakup', compact('ipd', 'breakup', 'detailedBreakup', 'doctors'));
    }

    /**
     * Update discount for final bill (MOU Discount + Special/Hospital Discount).
     * Affects only the final bill; estimate bill is unchanged.
     */
    public function updateDiscount(Request $request, $ipdId)
    {
        $request->validate([
            'mou_discount' => 'nullable|numeric|min:0',
            'special_discount' => 'nullable|numeric|min:0',
        ]);

        $ipd = IpdDetail::findOrFail($ipdId);
        $ipd->mou_discount = (float) ($request->input('mou_discount') ?? 0);
        $ipd->special_discount = (float) ($request->input('special_discount') ?? 0);
        $ipd->save();

        $totalDiscount = $ipd->mou_discount + $ipd->special_discount;
        $duePatientPartyAmount = (float) ($ipd->due_patient_party_amount ?? 0);
        $endDate = $this->resolveBillingEndDateForIpd($ipd);
        $breakup = $this->calculateBreakup($ipdId, $endDate);
        $outstanding = $breakup['outstanding'] ?? 0;
        $outstandingAfterDiscount = max(0, $outstanding - $totalDiscount);
        $netBalance = max(0, $outstanding - $totalDiscount - $duePatientPartyAmount);

        return response()->json([
            'success' => true,
            'message' => 'Discount saved. It will apply to the final bill only.',
            'mou_discount' => $ipd->mou_discount,
            'special_discount' => $ipd->special_discount,
            'total_discount' => $totalDiscount,
            'due_patient_party_amount' => $duePatientPartyAmount,
            'outstanding' => $outstanding,
            'outstanding_after_discount' => $outstandingAfterDiscount,
            'net_balance' => $netBalance,
        ]);
    }

    /**
     * Update Due on Account of Patient Party (doctor + amount). Deducts from outstanding on final bill.
     */
    public function updateDuePatientParty(Request $request, $ipdId)
    {
        $request->validate([
            'due_patient_party_doctor_id' => 'nullable|exists:doctor,id',
            'due_patient_party_amount' => 'nullable|numeric|min:0',
            'due_patient_party_receipt_type' => 'nullable|string|in:Current,Patient Due,Corporate Due,In Admissible,Booking,Refund',
        ]);

        $ipd = IpdDetail::findOrFail($ipdId);
        $ipd->due_patient_party_doctor_id = $request->input('due_patient_party_doctor_id') ?: null;
        $ipd->due_patient_party_amount = (float) ($request->input('due_patient_party_amount') ?? 0);
        $ipd->due_patient_party_receipt_type = $request->input('due_patient_party_receipt_type') ?: null;
        $ipd->save();

        $totalDiscount = (float) ($ipd->mou_discount ?? 0) + (float) ($ipd->special_discount ?? 0);
        $duePatientPartyAmount = (float) ($ipd->due_patient_party_amount ?? 0);
        $endDate = $this->resolveBillingEndDateForIpd($ipd);
        $breakup = $this->calculateBreakup($ipdId, $endDate);
        $outstanding = $breakup['outstanding'] ?? 0;
        $netBalance = max(0, $outstanding - $totalDiscount - $duePatientPartyAmount);

        return response()->json([
            'success' => true,
            'message' => 'Due on account of patient party saved. It will reflect on the final bill.',
            'due_patient_party_doctor_id' => $ipd->due_patient_party_doctor_id,
            'due_patient_party_amount' => $duePatientPartyAmount,
            'outstanding' => $outstanding,
            'net_balance' => $netBalance,
        ]);
    }

    /**
     * Calculate bed charges dynamically from PatientBedHistory
     * 
     * @param int $ipdId
     * @param string|null $endDate End date for calculation (Y-m-d format). If null, uses current date.
     * @return array ['total' => float, 'details' => array]
     */
    private function calculateBedChargesFromHistory($ipdId, $endDate = null)
    {
        $ipd = IpdDetail::find($ipdId);
        if (!$ipd) {
            return ['total' => 0, 'details' => []];
        }

        $admissionAt = Carbon::parse($ipd->date);
        // For non-discharged preview/breakup, calculate up to current moment.
        // Date-only end values are treated as end-of-day; datetime values are respected.
        if ($endDate === null) {
            $endAt = Carbon::now();
        } else {
            $rawEnd = trim((string) $endDate);
            $endAt = preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawEnd)
                ? Carbon::parse($rawEnd)->endOfDay()
                : Carbon::parse($rawEnd);
        }

        // Get all bed history records for this IPD (primary source)
        $bedHistories = PatientBedHistory::where('ipd_id', $ipdId)
            ->with(['bedGroup', 'bed'])
            ->orderBy('from_date', 'asc')
            ->get();

        // Fallback: daywise bed charges (created on admission/transfer) if no history or to fill gaps
        $daywiseCharges = IpdDaywiseBedCharge::where('ipd_id', $ipdId)
            ->with(['bedGroup', 'bed'])
            ->orderBy('charge_date', 'asc')
            ->get()
            ->keyBy(function ($row) {
                $d = $row->charge_date;
                return $d instanceof \DateTimeInterface ? $d->format('Y-m-d') : (\Carbon\Carbon::parse($d)->format('Y-m-d'));
            });

        $totalCharges = 0;
        $totalCgst = 0;
        $totalSgst = 0;
        $details = [];
        $currentDate = Carbon::parse($ipd->date)->startOfDay();
        $lastChargeDay = $this->resolveChargeLabelDayForMoment($endAt);
        $admissionCalendarDay = $admissionAt->copy()->startOfDay();

        // Pre-compute the most recent custom bed charge for each bed group during its history periods
        // This ensures a custom charge set on admission/transfer applies to all subsequent days in that period
        $bedGroupCustomCharges = []; // Format: ['bed_group_id|history_index' => daywise_charge_object]
        foreach ($bedHistories as $historyIndex => $history) {
            $bedHistoryFromDate = Carbon::parse($history->from_date);
            $bedHistoryToDate = $history->to_date ? Carbon::parse($history->to_date) : Carbon::now();
            
            // Find the most recent custom bed charge for this bed group within this history period
            $mostRecentDaywise = null;
            $mostRecentDate = null;
            
            foreach ($daywiseCharges as $date => $daywise) {
                if ((int)$daywise->bed_group_id === (int)$history->bed_group_id) {
                    $daywiseDateCarbon = Carbon::parse($daywise->charge_date)->startOfDay();
                    
                    if ($daywiseDateCarbon->gte($bedHistoryFromDate->copy()->startOfDay()) && $daywiseDateCarbon->lte($bedHistoryToDate->copy()->endOfDay())) {
                        if ($mostRecentDate === null || $daywiseDateCarbon->gt($mostRecentDate)) {
                            $mostRecentDate = $daywiseDateCarbon;
                            $mostRecentDaywise = $daywise;
                        }
                    }
                }
            }
            
            if ($mostRecentDaywise && isset($mostRecentDaywise->bed_charge) && (float)$mostRecentDaywise->bed_charge > 0) {
                $bedGroupCustomCharges[$history->bed_group_id . '|' . $historyIndex] = $mostRecentDaywise;
            }
        }

        // Calculate charges for each calendar day from admission to end date (charge_date = label day).
        // Billing cycle remains boundary-based (default 11:00 -> next day 11:00).
        // We also clamp by actual discharge datetime so final bill does not over-count.
        while ($currentDate->lte($lastChargeDay)) {
            $chargeDate = $currentDate->format('Y-m-d');

            [$periodStart, $periodEnd] = BedBillingPeriod::windowForChargeCalendarDay($currentDate->copy()->startOfDay());

            // Never bill a window whose start date is before admission calendar date.
            // Example: admitted 22nd => skip 21st->22nd window.
            if ($periodStart->copy()->startOfDay()->lt($admissionCalendarDay)) {
                $currentDate->addDay();
                continue;
            }

            // Respect requested end moment (discharge datetime for final bill).
            if ($periodEnd->lte($admissionAt) || $periodStart->gte($endAt)) {
                $currentDate->addDay();
                continue;
            }

            $effectiveStart = $periodStart->copy()->max($admissionAt);
            $effectiveEnd = $periodEnd->copy()->min($endAt);
            if ($effectiveStart->gte($effectiveEnd)) {
                $currentDate->addDay();
                continue;
            }

            // Find which bed was assigned during this period using exact datetime overlap.
            // Compare history to [effectiveStart, effectiveEnd) (admission/discharge-clamped window).
            $activeBed = null;
            $activeBedHistoryIndex = null;
            foreach ($bedHistories as $historyIndex => $history) {
                $fromDate = Carbon::parse($history->from_date);
                $toDate = $history->to_date ? Carbon::parse($history->to_date) : null;

                if ($fromDate->lt($effectiveEnd) && (! $toDate || $toDate->gt($effectiveStart))) {
                    if (! $activeBed || $fromDate->gt(Carbon::parse($activeBed->from_date))) {
                        $activeBed = $history;
                        $activeBedHistoryIndex = $historyIndex;
                    }
                }
            }

            $bedCost = 0;
            $detail = null;
            $gstRate = 0;
            $cgstAmount = 0;
            $sgstAmount = 0;
            $sacHsnCode = null;

            if ($activeBed && $activeBed->bedGroup) {
                // Prefer a stored daywise charge (created at admission/transfer) if available
                $bedCost = 0;
                $bedChargeRate = 0;
                $gstRate = 0;
                $sacHsnCode = null;

                // First, try exact date match for daywise charge
                if ($daywiseCharges->has($chargeDate)) {
                    $daywise = $daywiseCharges->get($chargeDate);
                    if ($daywise && isset($daywise->bed_charge) && (float)$daywise->bed_charge > 0) {
                        $bedCost = (float) $daywise->bed_charge;
                        $bedChargeRate = (float) ($daywise->bed_charge_rate ?? $activeBed->bedGroup->bed_cost ?? 0);
                        $gstRate = $daywise->bedGroup->gst_rate ?? $activeBed->bedGroup->gst_rate ?? 0;
                        $sacHsnCode = $daywise->bedGroup->sac_hsn_code ?? $activeBed->bedGroup->sac_hsn_code ?? null;
                    }
                } else {
                    // If no exact date match, use the pre-computed most recent custom bed charge
                    // This ensures a custom charge set during admission/transfer applies to all subsequent days
                    $customChargeKey = $activeBed->bed_group_id . '|' . $activeBedHistoryIndex;
                    if (isset($bedGroupCustomCharges[$customChargeKey])) {
                        $daywise = $bedGroupCustomCharges[$customChargeKey];
                        if ($daywise && isset($daywise->bed_charge) && (float)$daywise->bed_charge > 0) {
                            $bedCost = (float) $daywise->bed_charge;
                            $bedChargeRate = (float) ($daywise->bed_charge_rate ?? $activeBed->bedGroup->bed_cost ?? 0);
                            $gstRate = $daywise->bedGroup->gst_rate ?? $activeBed->bedGroup->gst_rate ?? 0;
                            $sacHsnCode = $daywise->bedGroup->sac_hsn_code ?? $activeBed->bedGroup->sac_hsn_code ?? null;
                        }
                    }
                }

                // If no explicit daywise charge found, fall back to bed group master rate
                if ($bedCost <= 0) {
                    $bedCost = (float) ($activeBed->bedGroup->bed_cost ?? 0);
                    $bedChargeRate = $bedCost;
                    $gstRate = $activeBed->bedGroup->gst_rate ?? 0;
                    $sacHsnCode = $activeBed->bedGroup->sac_hsn_code ?? null;
                }

                if ($bedCost > 5000 && $gstRate) {
                    $totalGstAmount = ($bedCost * $gstRate) / 100;
                    $cgstAmount = $totalGstAmount / 2;
                    $sgstAmount = $totalGstAmount / 2;
                }

                $detail = (object) [
                    'charge_date' => $chargeDate,
                    'period_start_date' => $effectiveStart->format('Y-m-d'),
                    'period_end_date' => $effectiveEnd->format('Y-m-d'),
                    'bed_group_id' => $activeBed->bed_group_id,
                    'bed_id' => $activeBed->bed_id,
                    'bed_charge' => $bedCost,
                    'bed_charge_rate' => $bedChargeRate,
                    'no_of_days' => 1,
                    'bedGroup' => $activeBed->bedGroup,
                    'bed' => $activeBed->bed,
                    'gst_rate' => $gstRate,
                    'cgst_amount' => $cgstAmount,
                    'sgst_amount' => $sgstAmount,
                    'sac_hsn_code' => $sacHsnCode,
                ];
            }

            // Fallback: use IpdDaywiseBedCharge when history is missing.
            // Do NOT use fallback for non-overlapping periods (prevents extra-day billing).
            if ($bedCost <= 0 && $daywiseCharges->has($chargeDate) && ($activeBed || $bedHistories->isEmpty())) {
                $daywise = $daywiseCharges->get($chargeDate);
                $bedCost = (float) ($daywise->bed_charge ?? 0);
                if ($bedCost > 0) {
                    $bedGroup = $daywise->bedGroup;
                    if ($bedGroup && $bedCost > 5000 && $bedGroup->gst_rate) {
                        $gstRate = (float) $bedGroup->gst_rate;
                        $sacHsnCode = $bedGroup->sac_hsn_code ?? null;
                        $totalGstAmount = ($bedCost * $gstRate) / 100;
                        $cgstAmount = $totalGstAmount / 2;
                        $sgstAmount = $totalGstAmount / 2;
                    }
                    $detail = (object) [
                        'charge_date' => $chargeDate,
                        'period_start_date' => $daywise->period_start_date ?? $chargeDate,
                        'period_end_date' => $daywise->period_end_date ?? $chargeDate,
                        'bed_group_id' => $daywise->bed_group_id,
                        'bed_id' => $daywise->bed_id,
                        'bed_charge' => $bedCost,
                        'bed_charge_rate' => (float) ($daywise->bed_charge_rate ?? $bedCost),
                        'no_of_days' => (int) ($daywise->no_of_days ?? 1),
                        'bedGroup' => $daywise->bedGroup,
                        'bed' => $daywise->bed,
                        'gst_rate' => $gstRate,
                        'cgst_amount' => $cgstAmount,
                        'sgst_amount' => $sgstAmount,
                        'sac_hsn_code' => $sacHsnCode,
                    ];
                }
            }

            if ($bedCost > 0 && $detail) {
                $totalCharges += $bedCost;
                $totalCgst += $cgstAmount;
                $totalSgst += $sgstAmount;
                $details[] = $detail;
            }

            $currentDate->addDay();
        }

        return [
            'total' => $totalCharges,
            'total_cgst' => $totalCgst,
            'total_sgst' => $totalSgst,
            'details' => $details,
        ];
    }

    /**
     * Calculate breakup bill
     * 
     * @param int $ipdId
     * @param string|null $endDate End date for calculation (Y-m-d format). If null, uses current date.
     */
    private function calculateBreakup($ipdId, $endDate = null)
    {
        // Get IPD record first
        $ipd = IpdDetail::find($ipdId);
        
        if (!$ipd) {
            return [
                'bed_charges' => 0,
                'ipd_charges' => 0,
                'pathology_charges' => 0,
                'radiology_charges' => 0,
                'doctor_visit_charges' => 0,
                'package_charges' => 0,
                'cgst_charges' => 0,
                'sgst_charges' => 0,
                'total_charges' => 0,
                'total_payments' => 0,
                'outstanding' => 0,
            ];
        }

        // Bed Charges - from PatientBedHistory / IpdDaywiseBedCharge (excluded when a package is applied; package covers bed)
        $bedChargesData = $this->calculateBedChargesFromHistory($ipdId, $endDate);
        $bedCharges = $bedChargesData['total'];
        $cgstCharges = $bedChargesData['total_cgst'] ?? 0;
        $sgstCharges = $bedChargesData['total_sgst'] ?? 0;

        // IPD Charges (from ipd_charges table)
        $ipdCharges = IpdCharges::where('ipd_id', $ipdId)
            ->sum('net_amount');

        // Get case_reference_id from IPD
        $caseReferenceId = $ipd->case_reference_id ?? null;

        // Pathology Charges (check by patient_id + date range OR case_reference_id)
        $pathologyCharges = PathologyBilling::where(function($query) use ($ipd, $caseReferenceId) {
            $query->where('patient_id', $ipd->patient_id)
                  ->where('date', '>=', $ipd->date);
            if ($caseReferenceId) {
                $query->orWhere('case_reference_id', $caseReferenceId);
            }
        })->sum('net_amount');

        // Radiology Charges (check by patient_id + date range OR case_reference_id)
        $radiologyCharges = RadiologyBilling::where(function($query) use ($ipd, $caseReferenceId) {
            $query->where('patient_id', $ipd->patient_id)
                  ->where('date', '>=', $ipd->date);
            if ($caseReferenceId) {
                $query->orWhere('case_reference_id', $caseReferenceId);
            }
        })->sum('net_amount');

        // Get IPD patient_id and admission date (use date-only so same-day visits are included)
        $patientId = $ipd->patient_id ?? null;
        $admissionDate = $ipd->date ? Carbon::parse($ipd->date)->format('Y-m-d') : null;

        // Doctor Visit Charges (patient + admission; optional end date; include rows with null visit_date if reporting_date qualifies)
        $doctorVisitCharges = 0;
        if ($patientId && $admissionDate) {
            $doctorVisitCharges = (float) $this->doctorVisitsBillableToIpdQuery($patientId, $ipd->date, $endDate)
                ->sum('amount');
        }

        // Package Charges (applied packages only)
        $packageCharges = IpdPackage::where('ipd_id', $ipdId)
            ->where('status', 'applied')
            ->sum('final_amount');

        // When a package is applied, exclude bed charges (and their GST) from the bill
        if ($packageCharges > 0) {
            $bedCharges = 0;
            $cgstCharges = 0;
            $sgstCharges = 0;
        }

        // Total Charges (including GST and package charges)
        $totalCharges = $bedCharges + $ipdCharges + $pathologyCharges + $radiologyCharges + $doctorVisitCharges + $packageCharges + $cgstCharges + $sgstCharges;

        // Total Payments
        // Note: Refund receipts should reduce the net payment,
        // so we treat transactions with receipt_type = 'Refund'
        // as negative amounts in the total.
        $paymentTransactions = Transaction::where('ipd_id', $ipdId)
            ->where('type', 'payment')
            ->where('section', 'ipd')
            ->get();

        $totalPayments = $paymentTransactions->sum(function ($t) {
            $amount = (float) ($t->amount ?? 0);
            if (strcasecmp($t->receipt_type ?? '', 'Refund') === 0) {
                return -abs($amount);
            }
            return $amount;
        });

        // Outstanding
        $outstanding = $totalCharges - $totalPayments;

        return [
            'bed_charges' => $bedCharges,
            'ipd_charges' => $ipdCharges,
            'pathology_charges' => $pathologyCharges,
            'radiology_charges' => $radiologyCharges,
            'doctor_visit_charges' => $doctorVisitCharges,
            'package_charges' => $packageCharges,
            'cgst_charges' => $cgstCharges,
            'sgst_charges' => $sgstCharges,
            'total_charges' => $totalCharges,
            'total_payments' => $totalPayments,
            'outstanding' => $outstanding,
        ];
    }

    /**
     * Get billing summary (total payments and outstanding) for an IPD - used in IPD listing.
     *
     * @param int $ipdId
     * @return array ['total_payments' => float, 'outstanding' => float]
     */
    public function getBillingSummaryForIpd($ipdId)
    {
        $ipd = IpdDetail::find($ipdId);
        $endDate = $ipd ? $this->resolveBillingEndDateForIpd($ipd) : null;

        $breakup = $this->calculateBreakup($ipdId, $endDate);
        return [
            'total_charges' => $breakup['total_charges'] ?? 0,
            'total_payments' => $breakup['total_payments'] ?? 0,
            'outstanding' => $breakup['outstanding'] ?? 0,
            'package_charges' => $breakup['package_charges'] ?? 0,
            'bed_charges' => $breakup['bed_charges'] ?? 0,
            'ipd_charges' => $breakup['ipd_charges'] ?? 0,
            'pathology_charges' => $breakup['pathology_charges'] ?? 0,
            'radiology_charges' => $breakup['radiology_charges'] ?? 0,
            'doctor_visit_charges' => $breakup['doctor_visit_charges'] ?? 0,
        ];
    }

    /**
     * Resolve billing end date for an IPD.
     * For discharged IPD, billing stops at discharge date.
     */
    private function resolveBillingEndDateForIpd(IpdDetail $ipd): ?string
    {
        if (($ipd->discharged ?? 'no') !== 'yes') {
            return null;
        }

        if (!empty($ipd->discharged_date)) {
            return Carbon::parse($ipd->discharged_date)->format('Y-m-d');
        }

        $dischargeCard = DischargeCard::where('ipd_details_id', $ipd->id)->orderByDesc('id')->first();
        if ($dischargeCard && !empty($dischargeCard->discharge_date)) {
            return Carbon::parse($dischargeCard->discharge_date)->format('Y-m-d');
        }

        return null;
    }

    /**
     * Determine charge-label day (Y-m-d @ 00:00) for a moment.
     * If the moment is after boundary time, it belongs to the next label day.
     */
    private function resolveChargeLabelDayForMoment(Carbon $moment): Carbon
    {
        $labelDay = $moment->copy()->startOfDay();
        [, $boundaryEnd] = BedBillingPeriod::windowForChargeCalendarDay($labelDay);
        if ($moment->gt($boundaryEnd)) {
            $labelDay->addDay();
        }

        return $labelDay;
    }

    /**
     * Get detailed date-wise breakdown
     */
    private function getDetailedBreakup($ipdId, $ipd, $endDate = null)
    {
        $admissionDate = $ipd->date; // datetime for pathology/radiology
        $admissionDateOnly = $ipd->date ? Carbon::parse($ipd->date)->format('Y-m-d') : null; // date-only for doctor visits
        $patientId = $ipd->patient_id;
        $caseReferenceId = $ipd->case_reference_id;
        
        // Bed Charges Details - Calculate dynamically from PatientBedHistory
        $bedChargesData = $this->calculateBedChargesFromHistory($ipdId, $endDate);
        $bedChargesDetails = collect($bedChargesData['details'])->map(function($charge) {
            return [
                'date' => $charge->charge_date,
                'period_start' => $charge->period_start_date,
                'period_end' => $charge->period_end_date,
                'bed_group' => $charge->bedGroup->name ?? 'N/A',
                'bed' => $charge->bed->name ?? 'N/A',
                'rate' => $charge->bed_charge_rate ?? 0,
                'days' => $charge->no_of_days ?? 1,
                'amount' => $charge->bed_charge ?? 0,
                'type' => 'bed',
                'description' => 'Bed Charge - ' . ($charge->bedGroup->name ?? 'N/A') . ' - ' . ($charge->bed->name ?? 'N/A'),
            ];
        });

        // IPD Charges Details
        $ipdChargesDetails = IpdCharges::where('ipd_id', $ipdId)
            ->with(['charge', 'chargeCategory'])
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($charge) {
                return [
                    'date' => $charge->date,
                    'category' => $charge->chargeCategory->name ?? 'N/A',
                    'charge_name' => $charge->charge->name ?? 'N/A',
                    'qty' => $charge->qty ?? 1,
                    'amount' => $charge->net_amount ?? 0,
                    'type' => 'ipd',
                    'description' => ($charge->chargeCategory->name ?? 'N/A') . ' - ' . ($charge->charge->name ?? 'N/A'),
                ];
            });

        // Pathology Charges Details
        $pathologyDetails = PathologyBilling::where(function($query) use ($patientId, $admissionDate, $caseReferenceId) {
            $query->where('patient_id', $patientId)
                  ->where('date', '>=', $admissionDate);
            if ($caseReferenceId) {
                $query->orWhere('case_reference_id', $caseReferenceId);
            }
        })
        ->with(['patient', 'doctor'])
        ->orderBy('date', 'asc')
        ->get();
        
        // Get pathology test details from reports with instance information
        $pathologyDetailsWithTests = $pathologyDetails->map(function($bill) {
            $reports = DB::table('pathology_report')
                ->where('pathology_bill_id', $bill->id)
                ->leftJoin('ipd_prescription_test', 'pathology_report.ipd_prescription_test_id', '=', 'ipd_prescription_test.id')
                ->join('pathology', 'pathology_report.pathology_id', '=', 'pathology.id')
                ->select(
                    'pathology.test_name',
                    'pathology_report.apply_charge',
                    'pathology_report.instance_number',
                    'ipd_prescription_test.instance_number as prescription_instance_number'
                )
                ->get();
            
            if ($reports->count() > 0) {
                return $reports->map(function($report) use ($bill) {
                    // Format test name with instance number if available
                    $instanceNumber = $report->prescription_instance_number ?? $report->instance_number ?? null;
                    $testName = $report->test_name;
                    if ($instanceNumber && $instanceNumber > 1) {
                        $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' : 
                                         ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                        $testName = $report->test_name . $instanceSuffix;
                    }
                    
                    return [
                        'date' => $bill->date,
                        'test_name' => $testName,
                        'amount' => $report->apply_charge ?? 0,
                        'type' => 'pathology',
                        'description' => $testName,
                        'bill_id' => $bill->id,
                    ];
                });
            } else {
                // If no reports, show bill total
                return [[
                    'date' => $bill->date,
                    'test_name' => 'Pathology Bill #' . $bill->id,
                    'amount' => $bill->net_amount ?? 0,
                    'type' => 'pathology',
                    'description' => 'Pathology Bill #' . $bill->id,
                    'bill_id' => $bill->id,
                ]];
            }
        })->flatten(1);

        // Radiology Charges Details
        $radiologyDetails = RadiologyBilling::where(function($query) use ($patientId, $admissionDate, $caseReferenceId) {
            $query->where('patient_id', $patientId)
                  ->where('date', '>=', $admissionDate);
            if ($caseReferenceId) {
                $query->orWhere('case_reference_id', $caseReferenceId);
            }
        })
        ->with(['patient', 'doctor'])
        ->orderBy('date', 'asc')
        ->get();
        
        // Get radiology test details from reports with instance information
        $radiologyDetailsWithTests = $radiologyDetails->map(function($bill) {
            $reports = DB::table('radiology_report')
                ->where('radiology_bill_id', $bill->id)
                ->leftJoin('ipd_prescription_test', 'radiology_report.ipd_prescription_test_id', '=', 'ipd_prescription_test.id')
                ->join('radio', 'radiology_report.radiology_id', '=', 'radio.id')
                ->select(
                    'radio.test_name',
                    'radiology_report.apply_charge',
                    'radiology_report.instance_number',
                    'ipd_prescription_test.instance_number as prescription_instance_number'
                )
                ->get();
            
            if ($reports->count() > 0) {
                return $reports->map(function($report) use ($bill) {
                    // Format test name with instance number if available
                    $instanceNumber = $report->prescription_instance_number ?? $report->instance_number ?? null;
                    $testName = $report->test_name;
                    if ($instanceNumber && $instanceNumber > 1) {
                        $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' : 
                                         ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                        $testName = $report->test_name . $instanceSuffix;
                    }
                    
                    return [
                        'date' => $bill->date,
                        'test_name' => $testName,
                        'amount' => $report->apply_charge ?? 0,
                        'type' => 'radiology',
                        'description' => $testName,
                        'bill_id' => $bill->id,
                    ];
                });
            } else {
                // If no reports, show bill total
                return [[
                    'date' => $bill->date,
                    'test_name' => 'Radiology Bill #' . $bill->id,
                    'amount' => $bill->net_amount ?? 0,
                    'type' => 'radiology',
                    'description' => 'Radiology Bill #' . $bill->id,
                    'bill_id' => $bill->id,
                ]];
            }
        })->flatten(1);

        // Doctor Visit Charges Details (include null visit_date when reporting_date is set)
        $doctorVisitDetails = collect();
        if ($admissionDateOnly) {
            $doctorVisitDetails = $this->doctorVisitsBillableToIpdQuery($patientId, $admissionDateOnly, null)
                ->with(['doctor', 'charge'])
                ->orderByRaw('COALESCE(visit_date, DATE(reporting_date), DATE(created_at)) ASC')
                ->orderBy('id')
                ->get()
                ->map(function($visit) {
                    return [
                        'date' => $visit->visit_date ?? $visit->reporting_date,
                        'doctor' => ($visit->doctor->name ?? 'N/A') . ' ' . ($visit->doctor->surname ?? ''),
                        'charge_name' => $visit->charge->name ?? 'N/A',
                        'amount' => $visit->amount ?? 0,
                        'type' => 'doctor_visit',
                        'description' => 'Doctor Visit - ' . ($visit->doctor->name ?? 'N/A') . ' ' . ($visit->doctor->surname ?? '') . ' - ' . ($visit->charge->name ?? 'N/A'),
                    ];
                });
        }

        // Package Charges Details
        $packageDetails = IpdPackage::where('ipd_id', $ipdId)
            ->where('status', 'applied')
            ->with('package')
            ->orderBy('applied_date', 'asc')
            ->get()
            ->map(function($ipdPackage) {
                return [
                    'date' => $ipdPackage->applied_date,
                    'package_name' => $ipdPackage->package->name ?? 'N/A',
                    'amount' => $ipdPackage->final_amount ?? 0,
                    'type' => 'package',
                    'description' => 'Package - ' . ($ipdPackage->package->name ?? 'N/A'),
                ];
            });

        // Combine all charges and sort by date
        $allCharges = collect()
            ->merge($bedChargesDetails)
            ->merge($ipdChargesDetails)
            ->merge($pathologyDetailsWithTests)
            ->merge($radiologyDetailsWithTests)
            ->merge($doctorVisitDetails)
            ->merge($packageDetails)
            ->sortBy('date')
            ->values();

        // Group by date
        $groupedByDate = $allCharges->groupBy(function($charge) {
            return \Carbon\Carbon::parse($charge['date'])->format('Y-m-d');
        });

        return [
            'all_charges' => $allCharges,
            'grouped_by_date' => $groupedByDate,
            'bed_charges' => $bedChargesDetails,
            'ipd_charges' => $ipdChargesDetails,
            'pathology_charges' => $pathologyDetailsWithTests,
            'radiology_charges' => $radiologyDetailsWithTests,
            'doctor_visit_charges' => $doctorVisitDetails,
            'package_charges' => $packageDetails,
        ];
    }

    /**
     * Prepare GST charges grouped by bed group
     * 
     * @param \Illuminate\Support\Collection $bedChargesDetails
     * @return array ['cgst' => array, 'sgst' => array]
     */
    private function prepareGstCharges($bedChargesDetails)
    {
        $cgstGrouped = [];
        $sgstGrouped = [];
        
        // Group GST charges by bed group
        $gstByBedGroup = [];
        foreach ($bedChargesDetails as $charge) {
            if (isset($charge->cgst_amount) && $charge->cgst_amount > 0) {
                $bedGroupName = $charge->bedGroup->name ?? 'Unknown';
                $bedGroupId = $charge->bed_group_id;
                
                if (!isset($gstByBedGroup[$bedGroupId])) {
                    $gstByBedGroup[$bedGroupId] = [
                        'bed_group_name' => $bedGroupName,
                        'sac_code' => $charge->sac_hsn_code ?? '',
                        'gst_rate' => $charge->gst_rate ?? 0,
                        'cgst_rate' => ($charge->gst_rate ?? 0) / 2,
                        'days' => 0,
                        'cgst_per_day' => 0,
                        'sgst_per_day' => 0,
                        'total_cgst' => 0,
                        'total_sgst' => 0,
                    ];
                }
                
                $gstByBedGroup[$bedGroupId]['days']++;
                $gstByBedGroup[$bedGroupId]['total_cgst'] += $charge->cgst_amount;
                $gstByBedGroup[$bedGroupId]['total_sgst'] += $charge->sgst_amount;
                
                // Calculate per day amount (should be same for all days)
                if ($gstByBedGroup[$bedGroupId]['cgst_per_day'] == 0) {
                    $gstByBedGroup[$bedGroupId]['cgst_per_day'] = $charge->cgst_amount;
                    $gstByBedGroup[$bedGroupId]['sgst_per_day'] = $charge->sgst_amount;
                }
            }
        }
        
        // Convert to arrays for CGST and SGST
        foreach ($gstByBedGroup as $group) {
            $description = $group['days'] . ' ' . $group['bed_group_name'] . ' @' . number_format($group['cgst_per_day'], 0);
            if ($group['sac_code']) {
                $description .= ' (SAC-' . $group['sac_code'] . ' @ ' . number_format($group['cgst_rate'], 2) . '%)';
            }
            
            $cgstGrouped[] = [
                'description' => $description,
                'amount' => $group['total_cgst'],
            ];
            
            $sgstGrouped[] = [
                'description' => $description,
                'amount' => $group['total_sgst'],
            ];
        }
        
        return [
            'cgst' => $cgstGrouped,
            'sgst' => $sgstGrouped,
        ];
    }

    /**
     * Doctor visits billable on an IPD estimate/final: same patient, on/after admission date,
     * optionally on/before discharge. Rows with null visit_date are included when reporting_date qualifies.
     */
    private function doctorVisitsBillableToIpdQuery(int $patientId, $admissionDate, $endDateYmd = null): \Illuminate\Database\Eloquent\Builder
    {
        $admissionYmd = Carbon::parse($admissionDate)->format('Y-m-d');
        $endYmd = ($endDateYmd !== null && $endDateYmd !== '')
            ? Carbon::parse($endDateYmd)->format('Y-m-d')
            : null;

        return DoctorVisit::query()
            ->where('patient_id', $patientId)
            ->where(function ($q) use ($admissionYmd, $endYmd) {
                $q->where(function ($w) use ($admissionYmd, $endYmd) {
                    $w->whereNotNull('visit_date')
                        ->whereDate('visit_date', '>=', $admissionYmd);
                    if ($endYmd) {
                        $w->whereDate('visit_date', '<=', $endYmd);
                    }
                })->orWhere(function ($w) use ($admissionYmd, $endYmd) {
                    $w->whereNull('visit_date')
                        ->whereNotNull('reporting_date')
                        ->whereDate('reporting_date', '>=', $admissionYmd);
                    if ($endYmd) {
                        $w->whereDate('reporting_date', '<=', $endYmd);
                    }
                });
            });
    }

    /**
     * Calendar date for sorting/grouping doctor visit lines (visit_date, else reporting_date, else created_at).
     */
    private function doctorVisitEffectiveDateForBilling($visit): string
    {
        if (! empty($visit->visit_date)) {
            return $visit->visit_date instanceof \DateTimeInterface
                ? $visit->visit_date->format('Y-m-d')
                : Carbon::parse($visit->visit_date)->format('Y-m-d');
        }
        if (! empty($visit->reporting_date)) {
            return Carbon::parse($visit->reporting_date)->format('Y-m-d');
        }
        if (! empty($visit->created_at)) {
            return Carbon::parse($visit->created_at)->format('Y-m-d');
        }

        return Carbon::now()->format('Y-m-d');
    }

    /**
     * Group doctor visits by doctor + charge name and contiguous date ranges for display.
     * Returns one row per (doctor + visit type + continuous dates).
     *
     * @param \Illuminate\Support\Collection $doctorVisitDetails
     * @return \Illuminate\Support\Collection
     */
    private function groupDoctorVisitsForDisplay($doctorVisitDetails)
    {
        if (!$doctorVisitDetails || $doctorVisitDetails->isEmpty()) {
            return collect();
        }

        // Group by doctor + charge (visit type); use FK ids so missing relations don't throw
        $grouped = $doctorVisitDetails->groupBy(function ($visit) {
            $doctorId = (string) ($visit->doctor_id ?? optional($visit->doctor)->id ?? '0');
            $chargeId = (string) ($visit->charge_id ?? optional($visit->charge)->id ?? '0');

            return $doctorId . '|' . $chargeId;
        });

        $result = collect();

        foreach ($grouped as $key => $visits) {
            $sorted = $visits->sortBy(function ($v) {
                return $this->doctorVisitEffectiveDateForBilling($v);
            })->values();

            $prevDate = null;
            $rangeStart = null;
            $visitCount = 0;
            $totalAmount = 0;
            $doctorPayTotal = 0;
            $doctor = null;
            $charge = null;

            foreach ($sorted as $v) {
                $dateStr = $this->doctorVisitEffectiveDateForBilling($v);

                // Quantity for this line: each row stores no_of_visit + amount (line total), not one visit per row.
                $lineQty = max(1, (int) ($v->no_of_visit ?? 1));

                $isConsecutive = $prevDate !== null &&
                    \Carbon\Carbon::parse($prevDate)->addDay()->format('Y-m-d') === $dateStr;

                if ($rangeStart === null || !$isConsecutive) {
                    // Flush previous range
                    if ($rangeStart !== null && $visitCount > 0) {
                        $result->push((object) [
                            'from_date' => $rangeStart,
                            'to_date' => $prevDate,
                            'visit_count' => $visitCount,
                            'total_amount' => $totalAmount,
                            'rate_per_visit' => $visitCount > 0 ? round($totalAmount / $visitCount, 2) : 0,
                            'doctor_pay_total' => round($doctorPayTotal, 2),
                            'doctor' => $doctor,
                            'charge' => $charge,
                            'doctor_label' => $this->formatDoctorName($doctor),
                            'visit_type_label' => $this->formatVisitTypeLabel($charge),
                        ]);
                    }

                    $rangeStart = $dateStr;
                    $visitCount = $lineQty;
                    $totalAmount = (float) ($v->amount ?? 0);
                    $doctorPayTotal = (float) ($v->doctor_pay_amount ?? 0);
                    $doctor = $v->doctor ?? null;
                    $charge = $v->charge ?? null;
                } else {
                    $visitCount += $lineQty;
                    $totalAmount += (float) ($v->amount ?? 0);
                    $doctorPayTotal += (float) ($v->doctor_pay_amount ?? 0);
                }

                $prevDate = $dateStr;
            }

            if ($rangeStart !== null && $visitCount > 0) {
                $result->push((object) [
                    'from_date' => $rangeStart,
                    'to_date' => $prevDate,
                    'visit_count' => $visitCount,
                    'total_amount' => $totalAmount,
                    'rate_per_visit' => $visitCount > 0 ? round($totalAmount / $visitCount, 2) : 0,
                    'doctor_pay_total' => round($doctorPayTotal, 2),
                    'doctor' => $doctor,
                    'charge' => $charge,
                    'doctor_label' => $this->formatDoctorName($doctor),
                    'visit_type_label' => $this->formatVisitTypeLabel($charge),
                ]);
            }
        }

        return $result->sortBy('from_date')->values();
    }

    /**
     * Group doctor visit rows by visit type for PDF display.
     * Returns array of [ 'visit_type_label' => string, 'rows' => Collection ] for each visit type.
     *
     * @param \Illuminate\Support\Collection $doctorVisitGroupedRows Output from groupDoctorVisitsForDisplay
     * @return \Illuminate\Support\Collection
     */
    private function groupDoctorVisitsByVisitTypeForDisplay($doctorVisitGroupedRows)
    {
        if (!$doctorVisitGroupedRows || $doctorVisitGroupedRows->isEmpty()) {
            return collect();
        }

        $byType = $doctorVisitGroupedRows->groupBy(function ($row) {
            $label = $row->visit_type_label ?? '';
            return $label !== '' ? $label : 'Other';
        });

        return $byType->map(function ($rows, $visitTypeLabel) {
            return (object) [
                'visit_type_label' => $visitTypeLabel,
                'rows' => $rows->sortBy('from_date')->values(),
            ];
        })->sortBy('visit_type_label')->values();
    }

    /**
     * Get formatted doctor name.
     */
    private function formatDoctorName($doctor)
    {
        $doctorName = 'N/A';
        if ($doctor) {
            $first = $doctor->name ?? '';
            $last = $doctor->surname ?? '';
            $full = trim($first . ' ' . $last);
            $doctorName = $full !== '' ? $full : 'N/A';
        }

        return $doctorName;
    }

    /**
     * Get formatted visit type label (charge name).
     */
    private function formatVisitTypeLabel($charge)
    {
        return $charge->name ?? 'N/A';
    }

    /**
     * Normalize Y-m-d for bed charge period columns (falls back to billing day charge_date).
     */
    private function normalizeBedChargePeriodDate($value, string $fallbackYmd): string
    {
        if ($value === null || $value === '') {
            return $fallbackYmd;
        }
        try {
            if ($value instanceof \DateTimeInterface) {
                return $value->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return $fallbackYmd;
        }
    }

    /**
     * Group day-wise bed charges by bed and contiguous date ranges for display.
     * Returns one row per (bed + contiguous date range): e.g. "SINGLE - 5 SINGLE @5000 | 5 Days | 17/01/2026 To 21/01/2026".
     *
     * Uses period_start_date / period_end_date (bed billing boundary from config, default 11:00–11:00) for the printed "Date Range".
     * charge_date alone can repeat the same calendar day when the billing day label differs from the
     * occupancy window (e.g. admit 27 10:31 → one line with charge_date 28 but period 27→28).
     *
     * @param \Illuminate\Support\Collection $bedChargesDetails Day-wise details from calculateBedChargesFromHistory
     * @return \Illuminate\Support\Collection
     */
    private function groupBedChargesByBedForDisplay($bedChargesDetails)
    {
        if ($bedChargesDetails->isEmpty()) {
            return collect();
        }

        // Group by (bed_group_id, bed_id)
        $byBed = $bedChargesDetails->groupBy(function ($item) {
            return ($item->bed_group_id ?? '') . '|' . ($item->bed_id ?? '');
        });

        $result = collect();
        foreach ($byBed as $key => $items) {
            $sorted = $items->sortBy('charge_date')->values();
            $prevDate = null;
            $rangeStart = null;
            $rangeDisplayFrom = null;
            $rangeDisplayTo = null;
            $rangeDays = 0;
            $rangeAmount = 0;
            $rangeRate = null;
            $bedGroup = null;
            $bed = null;

            foreach ($sorted as $d) {
                $dDate = $d->charge_date instanceof \DateTimeInterface
                    ? $d->charge_date->format('Y-m-d')
                    : Carbon::parse($d->charge_date)->format('Y-m-d');

                $pStart = $this->normalizeBedChargePeriodDate($d->period_start_date ?? null, $dDate);
                $pEnd = $this->normalizeBedChargePeriodDate($d->period_end_date ?? null, $dDate);

                $isConsecutive = $prevDate !== null && Carbon::parse($prevDate)->addDay()->format('Y-m-d') === $dDate;

                if ($rangeStart === null || !$isConsecutive) {
                    // Flush previous range if any
                    if ($rangeStart !== null && $rangeDays > 0) {
                        $result->push((object) [
                            'from_date' => $rangeDisplayFrom,
                            'to_date' => $rangeDisplayTo,
                            'no_of_days' => $rangeDays,
                            'bed_charge' => $rangeAmount,
                            'bed_charge_rate' => $rangeRate,
                            'bedGroup' => $bedGroup,
                            'bed' => $bed,
                            'bed_label' => $this->formatBedChargeLabel($bedGroup, $bed, $rangeRate),
                        ]);
                    }
                    $rangeStart = $dDate;
                    $rangeDisplayFrom = $pStart;
                    $rangeDisplayTo = $pEnd;
                    $rangeDays = 1;
                    $rangeAmount = (float) ($d->bed_charge ?? 0);
                    $rangeRate = (float) ($d->bed_charge_rate ?? 0);
                    $bedGroup = $d->bedGroup ?? null;
                    $bed = $d->bed ?? null;
                } else {
                    $rangeDays++;
                    $rangeAmount += (float) ($d->bed_charge ?? 0);
                    $rangeDisplayTo = $pEnd;
                }
                $prevDate = $dDate;
            }

            if ($rangeStart !== null && $rangeDays > 0) {
                $result->push((object) [
                    'from_date' => $rangeDisplayFrom,
                    'to_date' => $rangeDisplayTo,
                    'no_of_days' => $rangeDays,
                    'bed_charge' => $rangeAmount,
                    'bed_charge_rate' => $rangeRate,
                    'bedGroup' => $bedGroup,
                    'bed' => $bed,
                    'bed_label' => $this->formatBedChargeLabel($bedGroup, $bed, $rangeRate),
                ]);
            }
        }

        // Sort by from_date so order is chronological
        return $result->sortBy('from_date')->values();
    }

    /**
     * Format bed charge row label: "BEDGROUP - BEDNAME @ RATE"
     */
    private function formatBedChargeLabel($bedGroup, $bed, $rate)
    {
        $groupName = ($bedGroup && isset($bedGroup->name)) ? $bedGroup->name : 'N/A';
        $bedName = ($bed && isset($bed->name)) ? $bed->name : 'N/A';
        $rateNum = (float) ($rate ?? 0);
        return $groupName . ' - ' . $bedName . ' @' . number_format($rateNum, 0);
    }

    /**
     * Get final bill register rows for one IPD (charge lines + GST + discount + due).
     * Used by IPD Final Bill Register report. Each row: charge_category_head, charge_details, amount.
     *
     * @param int $ipdId
     * @param string $dischargeDate Y-m-d
     * @return array List of ['charge_category_head' => string, 'charge_details' => string, 'amount' => float]
     */
    public function getFinalBillRegisterRows($ipdId, $dischargeDate)
    {
        $ipd = IpdDetail::with(['patient', 'duePatientPartyDoctor'])->find($ipdId);
        if (!$ipd) {
            return [];
        }

        $rows = [];
        $admissionDate = $ipd->date ?? null;
        $admissionDateOnly = $admissionDate ? Carbon::parse($admissionDate)->format('Y-m-d') : null;
        $patientId = $ipd->patient_id;
        $caseReferenceId = $ipd->case_reference_id ?? null;

        // Bed charges (up to discharge date)
        $bedChargesData = $this->calculateBedChargesFromHistory($ipdId, $dischargeDate);
        $bedChargesDetails = collect($bedChargesData['details']);
        foreach ($bedChargesDetails as $d) {
            $rows[] = [
                'charge_category_head' => 'Bed',
                'charge_details' => ($d->bedGroup->name ?? 'N/A') . ' - ' . ($d->bed->name ?? 'N/A') . ' (' . ($d->charge_date ?? '') . ')',
                'amount' => (float) ($d->bed_charge ?? 0),
            ];
        }

        // IPD charges (from ipd_charges, date <= discharge)
        $ipdCharges = IpdCharges::where('ipd_id', $ipdId)
            ->where('date', '<=', $dischargeDate)
            ->with(['charge', 'chargeCategory'])
            ->orderBy('date')
            ->get();
        foreach ($ipdCharges as $c) {
            $rows[] = [
                'charge_category_head' => $c->chargeCategory->name ?? 'IPD Charges',
                'charge_details' => ($c->charge->name ?? 'N/A') . ' x ' . ($c->qty ?? 1),
                'amount' => (float) ($c->net_amount ?? 0),
            ];
        }

        // Pathology (patient + date range up to discharge)
        $pathologyBills = PathologyBilling::where('patient_id', $patientId)
            ->where('date', '>=', $admissionDate)
            ->where('date', '<=', $dischargeDate)
            ->orderBy('date');
        foreach ($pathologyBills->get() as $bill) {
            $rows[] = [
                'charge_category_head' => 'Pathology',
                'charge_details' => 'Pathology Bill #' . $bill->id . ' (' . ($bill->date ?? '') . ')',
                'amount' => (float) ($bill->net_amount ?? 0),
            ];
        }

        // Radiology (patient + date range up to discharge)
        $radiologyBills = RadiologyBilling::where('patient_id', $patientId)
            ->where('date', '>=', $admissionDate)
            ->where('date', '<=', $dischargeDate)
            ->orderBy('date');
        foreach ($radiologyBills->get() as $bill) {
            $rows[] = [
                'charge_category_head' => 'Radiology',
                'charge_details' => 'Radiology Bill #' . $bill->id . ' (' . ($bill->date ?? '') . ')',
                'amount' => (float) ($bill->net_amount ?? 0),
            ];
        }

        // Doctor visits (include null visit_date when reporting_date is in range)
        $doctorVisits = collect();
        if ($admissionDate) {
            $doctorVisits = $this->doctorVisitsBillableToIpdQuery($patientId, $admissionDate, $dischargeDate)
                ->with(['doctor', 'charge'])
                ->orderByRaw('COALESCE(visit_date, DATE(reporting_date), DATE(created_at)) ASC')
                ->orderBy('id')
                ->get();
        }
        foreach ($doctorVisits as $v) {
            $rows[] = [
                'charge_category_head' => 'Doctor Visit',
                'charge_details' => ($v->charge->name ?? 'N/A') . ' - ' . (($v->doctor->name ?? '') . ' ' . ($v->doctor->surname ?? '')),
                'amount' => (float) ($v->amount ?? 0),
            ];
        }

        // GST breakups (from bed charges)
        $gstCharges = $this->prepareGstCharges($bedChargesDetails);
        foreach ($gstCharges['cgst'] as $g) {
            $rows[] = [
                'charge_category_head' => 'CGST',
                'charge_details' => $g['description'] ?? '',
                'amount' => (float) ($g['amount'] ?? 0),
            ];
        }
        foreach ($gstCharges['sgst'] as $g) {
            $rows[] = [
                'charge_category_head' => 'SGST',
                'charge_details' => $g['description'] ?? '',
                'amount' => (float) ($g['amount'] ?? 0),
            ];
        }

        // Discount details
        $mouDiscount = (float) ($ipd->mou_discount ?? 0);
        $specialDiscount = (float) ($ipd->special_discount ?? 0);
        if ($mouDiscount > 0) {
            $rows[] = [
                'charge_category_head' => 'Discount',
                'charge_details' => 'MOU Discount',
                'amount' => -$mouDiscount,
            ];
        }
        if ($specialDiscount > 0) {
            $rows[] = [
                'charge_category_head' => 'Discount',
                'charge_details' => 'Special / Hospital Discount',
                'amount' => -$specialDiscount,
            ];
        }

        // Due on account of patient party
        $dueAmount = (float) ($ipd->due_patient_party_amount ?? 0);
        if ($dueAmount > 0) {
            $doctorName = $ipd->duePatientPartyDoctor
                ? trim(($ipd->duePatientPartyDoctor->name ?? '') . ' ' . ($ipd->duePatientPartyDoctor->surname ?? ''))
                : '';
            $rows[] = [
                'charge_category_head' => 'Due (Patient Party)',
                'charge_details' => $doctorName ?: 'Under Doctor',
                'amount' => -$dueAmount,
            ];
        }

        return $rows;
    }

    /**
     * Build pathology and radiology line items date-wise for PDF (estimate/final).
     * Returns collection of ['date' => ..., 'type' => 'pathology'|'radiology', 'test_name' => ..., 'amount' => ...] sorted by date.
     */
    private function getPathologyRadiologyDatewise($ipd)
    {
        $patientId = $ipd->patient_id;
        $admissionDate = $ipd->date;
        $caseReferenceId = $ipd->case_reference_id ?? null;

        $pathologyBills = PathologyBilling::where(function ($query) use ($patientId, $admissionDate, $caseReferenceId) {
            $query->where('patient_id', $patientId)->where('date', '>=', $admissionDate);
            if ($caseReferenceId) {
                $query->orWhere('case_reference_id', $caseReferenceId);
            }
        })->orderBy('date', 'asc')->get();

        $pathologyRows = $pathologyBills->map(function ($bill) {
            $reports = DB::table('pathology_report')
                ->where('pathology_bill_id', $bill->id)
                ->leftJoin('ipd_prescription_test', 'pathology_report.ipd_prescription_test_id', '=', 'ipd_prescription_test.id')
                ->join('pathology', 'pathology_report.pathology_id', '=', 'pathology.id')
                ->select(
                    'pathology.test_name',
                    'pathology_report.apply_charge',
                    'pathology_report.instance_number',
                    'ipd_prescription_test.instance_number as prescription_instance_number'
                )
                ->get();
            if ($reports->count() > 0) {
                return $reports->map(function ($r) use ($bill) {
                    // Format test name with instance number if available
                    $instanceNumber = $r->prescription_instance_number ?? $r->instance_number ?? null;
                    $testName = $r->test_name ?? 'N/A';
                    if ($instanceNumber && $instanceNumber > 1 && $testName !== 'N/A') {
                        $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' : 
                                         ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                        $testName = $r->test_name . $instanceSuffix;
                    }
                    return ['date' => $bill->date, 'type' => 'pathology', 'test_name' => $testName, 'amount' => (float)($r->apply_charge ?? 0)];
                });
            }
            return [['date' => $bill->date, 'type' => 'pathology', 'test_name' => 'Pathology Bill #' . $bill->id, 'amount' => (float)($bill->net_amount ?? 0)]];
        })->flatten(1);

        $radiologyBills = RadiologyBilling::where(function ($query) use ($patientId, $admissionDate, $caseReferenceId) {
            $query->where('patient_id', $patientId)->where('date', '>=', $admissionDate);
            if ($caseReferenceId) {
                $query->orWhere('case_reference_id', $caseReferenceId);
            }
        })->orderBy('date', 'asc')->get();

        $radiologyRows = $radiologyBills->map(function ($bill) {
            $reports = DB::table('radiology_report')
                ->where('radiology_bill_id', $bill->id)
                ->leftJoin('ipd_prescription_test', 'radiology_report.ipd_prescription_test_id', '=', 'ipd_prescription_test.id')
                ->join('radio', 'radiology_report.radiology_id', '=', 'radio.id')
                ->select(
                    'radio.test_name',
                    'radiology_report.apply_charge',
                    'radiology_report.instance_number',
                    'ipd_prescription_test.instance_number as prescription_instance_number'
                )
                ->get();
            if ($reports->count() > 0) {
                return $reports->map(function ($r) use ($bill) {
                    // Format test name with instance number if available
                    $instanceNumber = $r->prescription_instance_number ?? $r->instance_number ?? null;
                    $testName = $r->test_name ?? 'N/A';
                    if ($instanceNumber && $instanceNumber > 1 && $testName !== 'N/A') {
                        $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' : 
                                         ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                        $testName = $r->test_name . $instanceSuffix;
                    }
                    return ['date' => $bill->date, 'type' => 'radiology', 'test_name' => $testName, 'amount' => (float)($r->apply_charge ?? 0)];
                });
            }
            return [['date' => $bill->date, 'type' => 'radiology', 'test_name' => 'Radiology Bill #' . $bill->id, 'amount' => (float)($bill->net_amount ?? 0)]];
        })->flatten(1);

        return $pathologyRows->merge($radiologyRows)->sortBy('date')->values();
    }

    /**
     * Export Estimate/Breakup Bill PDF
     */
    public function exportEstimate($ipdId)
    {
        try {
             $logged_user = auth()->user()->username ?? '';  

            \Log::info('exportEstimate started', ['ipd_id' => $ipdId]);
            
            $ipd = IpdDetail::with(['patient.organisation', 'doctor', 'bedGroup', 'bedDetail'])
                ->findOrFail($ipdId);
            
            \Log::info('IPD found', ['ipd_no' => $ipd->ipd_no]);

            $breakup = $this->calculateBreakup($ipdId);
            \Log::info('Breakup calculated', ['total_charges' => $breakup['total_charges']]);

            // Get detailed breakdown - Calculate dynamically from PatientBedHistory (omit from display when package applied)
            $bedChargesData = $this->calculateBedChargesFromHistory($ipdId);
            $bedChargesDetails = collect($bedChargesData['details']);
            $bedChargesGroupedForDisplay = $this->groupBedChargesByBedForDisplay($bedChargesDetails);
            $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);
            if (($breakup['package_charges'] ?? 0) > 0) {
                $bedChargesDetails = collect();
                $bedChargesGroupedForDisplay = $this->groupBedChargesByBedForDisplay($bedChargesDetails);
                $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);
            }

            $ipdChargesDetails = IpdCharges::where('ipd_id', $ipdId)
                ->with(['charge', 'chargeCategory'])
                ->orderBy('date', 'asc')
                ->get() ?? collect();

            // Pathology Details - Get all tests with names
            \Log::info('Getting pathology details');
            $pathologyTestNames = [];
            $pathologyTotal = 0;
            
            $pathologyDetails = PathologyBilling::where(function($query) use ($ipd) {
                $query->where(function($q) use ($ipd) {
                    $q->where('patient_id', $ipd->patient_id)
                      ->whereDate('date', '>=', $ipd->date);
                });
                if ($ipd->case_reference_id) {
                    $query->orWhere('case_reference_id', $ipd->case_reference_id);
                }
            })->orderBy('date', 'asc')->get();
            
            \Log::info('Pathology bills found', ['count' => $pathologyDetails->count()]);
            
            // Get all pathology test names with instance information
            foreach ($pathologyDetails as $bill) {
                $reports = DB::table('pathology_report')
                    ->where('pathology_bill_id', $bill->id)
                    ->leftJoin('ipd_prescription_test', 'pathology_report.ipd_prescription_test_id', '=', 'ipd_prescription_test.id')
                    ->join('pathology', 'pathology_report.pathology_id', '=', 'pathology.id')
                    ->select(
                        'pathology.test_name',
                        'pathology_report.apply_charge',
                        'pathology_report.instance_number',
                        'ipd_prescription_test.instance_number as prescription_instance_number'
                    )
                    ->get();
                
                foreach ($reports as $report) {
                    if ($report->test_name) {
                        // Format test name with instance number if available
                        $instanceNumber = $report->prescription_instance_number ?? $report->instance_number ?? null;
                        $testName = $report->test_name;
                        if ($instanceNumber && $instanceNumber > 1) {
                            $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' : 
                                             ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                            $testName = $report->test_name . $instanceSuffix;
                        }
                        $pathologyTestNames[] = $testName;
                        $pathologyTotal += $report->apply_charge ?? 0;
                    }
                }
            }
            
            \Log::info('Pathology tests collected', ['test_count' => count($pathologyTestNames), 'total' => $pathologyTotal]);

            // Radiology Details - Get all tests with names
            \Log::info('Getting radiology details');
            $radiologyTestNames = [];
            $radiologyTotal = 0;
            
            $radiologyDetails = RadiologyBilling::where(function($query) use ($ipd) {
                $query->where(function($q) use ($ipd) {
                    $q->where('patient_id', $ipd->patient_id)
                      ->whereDate('date', '>=', $ipd->date);
                });
                if ($ipd->case_reference_id) {
                    $query->orWhere('case_reference_id', $ipd->case_reference_id);
                }
            })->orderBy('date', 'asc')->get();
            
            \Log::info('Radiology bills found', ['count' => $radiologyDetails->count()]);
            
            // Get all radiology test names
            foreach ($radiologyDetails as $bill) {
                $reports = DB::table('radiology_report')
                    ->where('radiology_bill_id', $bill->id)
                    ->join('radio', 'radiology_report.radiology_id', '=', 'radio.id')
                    ->select('radio.test_name', 'radiology_report.apply_charge')
                    ->get();
                
                foreach ($reports as $report) {
                    if ($report->test_name) {
                        // Format test name with instance number if available
                        $instanceNumber = $report->prescription_instance_number ?? $report->instance_number ?? null;
                        $testName = $report->test_name;
                        if ($instanceNumber && $instanceNumber > 1) {
                            $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' : 
                                             ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                            $testName = $report->test_name . $instanceSuffix;
                        }
                        $radiologyTestNames[] = $testName;
                        $radiologyTotal += $report->apply_charge ?? 0;
                    }
                }
            }
            
            \Log::info('Radiology tests collected', ['test_count' => count($radiologyTestNames), 'total' => $radiologyTotal]);

            $doctorVisitDetails = $this->doctorVisitsBillableToIpdQuery((int) $ipd->patient_id, $ipd->date, null)
                ->with(['doctor', 'charge'])
                ->orderByRaw('COALESCE(visit_date, DATE(reporting_date), DATE(created_at)) ASC')
                ->orderBy('id')
                ->get();
            // Group doctor visits by doctor + visit type, then by visit type for PDF display
            $doctorVisitGroupedForDisplay = $this->groupDoctorVisitsForDisplay($doctorVisitDetails);
            $doctorVisitGroupedByVisitType = $this->groupDoctorVisitsByVisitTypeForDisplay($doctorVisitGroupedForDisplay);

            // Package details (applied packages) for estimate PDF
            $packageDetails = IpdPackage::where('ipd_id', $ipdId)
                ->where('status', 'applied')
                ->with('package')
                ->orderBy('applied_date', 'asc')
                ->get()
                ->map(function ($ipdPackage) {
                    return [
                        'date' => $ipdPackage->applied_date,
                        'package_name' => $ipdPackage->package->name ?? 'N/A',
                        'amount' => $ipdPackage->final_amount ?? 0,
                    ];
                });
            
            // Get payment details
            \Log::info('Getting payment details');
            $payments = Transaction::where('ipd_id', $ipdId)
                ->where('type', 'payment')
                ->where('section', 'ipd')
                ->orderBy('payment_date', 'asc')
                ->get() ?? collect();
            
            // Ensure pathologyDetails and radiologyDetails are collections
            if (!isset($pathologyDetails)) {
                $pathologyDetails = collect();
            }
            if (!isset($radiologyDetails)) {
                $radiologyDetails = collect();
            }

            // Convert amounts to words
            \Log::info('Converting amounts to words');
            
            // Initialize with fallback values
            $totalChargesInWords = 'Zero Rupees Only';
            $totalPaymentsInWords = 'Zero Rupees Only';
            $outstandingInWords = 'Zero Rupees Only';
            $netBalanceInWords = 'Zero Rupees Only';
            
            try {
                // Check if class exists
                if (!class_exists(\App\Helpers\NumberToWords::class)) {
                    \Log::error('NumberToWords class not found');
                    throw new \Exception('NumberToWords class not found');
                }
                
                \Log::info('NumberToWords class found, converting amounts');
                
                $totalChargesInWords = \App\Helpers\NumberToWords::convert($breakup['total_charges'] ?? 0);
                \Log::info('Total charges converted', ['words' => $totalChargesInWords]);
                
                $totalPaymentsInWords = \App\Helpers\NumberToWords::convert($breakup['total_payments'] ?? 0);
                \Log::info('Total payments converted', ['words' => $totalPaymentsInWords]);
                
                $outstandingInWords = \App\Helpers\NumberToWords::convert($breakup['outstanding'] ?? 0);
                \Log::info('Outstanding converted', ['words' => $outstandingInWords]);
                
                $netBalanceInWords = \App\Helpers\NumberToWords::convert($breakup['outstanding'] ?? 0);
                \Log::info('Net balance converted', ['words' => $netBalanceInWords]);
            } catch (\ParseError $e) {
                \Log::error('Parse error in NumberToWords: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Use fallback values
            } catch (\Error $e) {
                \Log::error('Fatal error in NumberToWords: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Use fallback values
            } catch (\Exception $e) {
                \Log::error('Error converting to words: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Use fallback values
            }
            
            \Log::info('Amounts converted to words', [
                'charges' => $totalChargesInWords,
                'payments' => $totalPaymentsInWords,
                'outstanding' => $outstandingInWords
            ]);
            
            // Ensure arrays are initialized even if empty
            if (empty($pathologyTestNames)) {
                $pathologyTestNames = [];
            }
            if (empty($radiologyTestNames)) {
                $radiologyTestNames = [];
            }
            
            // Use breakup totals if calculated totals are 0
            if ($pathologyTotal == 0 && isset($breakup['pathology_charges'])) {
                $pathologyTotal = $breakup['pathology_charges'];
            }
            if ($radiologyTotal == 0 && isset($breakup['radiology_charges'])) {
                $radiologyTotal = $breakup['radiology_charges'];
            }

            // Date-wise pathology and radiology for detail table
            $investigationDatewise = $this->getPathologyRadiologyDatewise($ipd);

            \Log::info('Loading PDF view', [
                'pathology_tests' => count($pathologyTestNames),
                'radiology_tests' => count($radiologyTestNames)
            ]);
            
            // Get hospital information
            $hospital = Hospital::first();
                    
            
            // First pass: Render to get accurate page count
            $tempPdf = Pdf::loadView('admin.billing.ipd_estimate_pdf', compact(
                'ipd', 'breakup', 'bedChargesDetails', 'bedChargesGroupedForDisplay', 'ipdChargesDetails',
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails', 'doctorVisitGroupedForDisplay', 'doctorVisitGroupedByVisitType', 'packageDetails', 'payments',
                'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
                'investigationDatewise',
                'totalChargesInWords', 'totalPaymentsInWords', 'outstandingInWords', 'netBalanceInWords',
                'hospital', 'logged_user'
            ));
            
            $tempPdf->setOption('enable-php', false); // Disable PHP for first pass
            $tempPdf->setOption('enable-local-file-access', true);
            $tempPdf->setPaper('a4', 'portrait');
            
            // Render to get page count
            $dompdf = $tempPdf->getDomPDF();
            $dompdf->render();
            
            // Get total pages - try different methods
            try {
                $canvas = $dompdf->getCanvas();
                $totalPages = method_exists($canvas, 'get_page_count') ? $canvas->get_page_count() : $dompdf->get_page_count();
            } catch (\Exception $e) {
                $totalPages = $dompdf->get_page_count();
            }
            
            // Fallback if still no count
            if (!$totalPages || $totalPages <= 0) {
                $totalPages = 1;
            }
            
            \Log::info('Total pages calculated', ['total_pages' => $totalPages]);


            // return view('admin.billing.ipd_estimate_pdf', compact(
            //     'ipd', 'breakup', 'bedChargesDetails', 'ipdChargesDetails',
            //     'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails',
            //     'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
            //     'totalChargesInWords', 'totalPaymentsInWords', 'outstandingInWords', 'netBalanceInWords',
            //     'hospital', 'totalPages'
            // ));
            // Second pass: Render with accurate page count stored in view
            $pdf = Pdf::loadView('admin.billing.ipd_estimate_pdf', compact(
                'ipd', 'breakup', 'bedChargesDetails', 'bedChargesGroupedForDisplay', 'ipdChargesDetails',
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails', 'doctorVisitGroupedForDisplay', 'doctorVisitGroupedByVisitType', 'packageDetails', 'payments',
                'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
                'investigationDatewise',
                'totalChargesInWords', 'totalPaymentsInWords', 'outstandingInWords', 'netBalanceInWords',
                'hospital', 'totalPages', 'gstChargesGrouped', 'logged_user'
            ));
            
            // Enable PHP scripts for page numbering
             $pdf->setOption('enable-php', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setPaper('a4', 'portrait');
            
            \Log::info('PDF generated, returning inline stream');

            // Open in browser tab (inline) instead of forcing download
            return $pdf->stream('IPD_Estimate_Bill_' . $ipd->ipd_no . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Error in exportEstimate: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ipd_id' => $ipdId
            ]);
            
            // Return error response
            abort(500, 'Error generating PDF: ' . $e->getMessage() . ' (Check logs for details)');
        }
    }

    /**
     * Check if IPD patient is discharged (for AJAX check before generating final bill)
     */
    public function checkDischarged($ipdId)
    {
        try {
            $ipd = IpdDetail::findOrFail($ipdId);
            
            $isDischarged = $ipd->discharged == 'yes';
            
            // Get discharge card to check discharge date
            $dischargeCard = DischargeCard::where('ipd_details_id', $ipdId)->first();
            $hasDischargeDate = $dischargeCard && !empty($dischargeCard->discharge_date);
            
            $message = '';
            if (!$isDischarged) {
                $message = 'Patient is not discharged. Please discharge the patient first before generating final bill.';
            } elseif (!$hasDischargeDate) {
                $message = 'Patient is discharged but discharge date is missing in discharge card. Please update the discharge card before generating final bill.';
            } else {
                $message = 'Patient is discharged. Final bill can be generated.';
            }
            
            return response()->json([
                'discharged' => $isDischarged && $hasDischargeDate,
                'discharged_date' => $dischargeCard ? $dischargeCard->discharge_date : null,
                'has_discharge_date' => $hasDischargeDate,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'discharged' => false,
                'message' => 'Error checking discharge status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export Final Bill PDF
     */
    public function exportFinal($ipdId)
    {
        try {
                $logged_user = auth()->user()->username ?? ''; 

            \Log::info('exportFinal started', ['ipd_id' => $ipdId]);
            
            $ipd = IpdDetail::with(['patient.organisation', 'doctor', 'bedGroup', 'bedDetail', 'duePatientPartyDoctor'])
                ->findOrFail($ipdId);
            
            \Log::info('IPD found', ['ipd_no' => $ipd->ipd_no, 'discharged' => $ipd->discharged]);

            // Check if patient exists
            if (!$ipd->patient) {
                abort(400, 'Patient information is missing for this IPD record.');
            }

            // Check if patient is discharged
            if ($ipd->discharged != 'yes') {
                abort(400, 'Final bill can only be generated for discharged patients. Please discharge the patient first.');
            }

            // Get discharge date and time from discharge_card table
            \Log::info('Getting discharge card information');
            $dischargeCard = DischargeCard::where('ipd_details_id', $ipdId)->first();
            
            if (!$dischargeCard || !$dischargeCard->discharge_date) {
                abort(400, 'Discharge card not found or discharge date is missing. Please ensure the patient has a discharge card with discharge date.');
            }
            
            $dischargeDate = $dischargeCard->discharge_date;
            $dischargeTime = $dischargeCard->discharge_time;
            $billingEndAt = !empty($dischargeTime)
                ? Carbon::parse($dischargeDate . ' ' . $dischargeTime)->format('Y-m-d H:i:s')
                : Carbon::parse($dischargeDate)->endOfDay()->format('Y-m-d H:i:s');
            
            \Log::info('Discharge information retrieved', [
                'discharge_date' => $dischargeDate,
                'discharge_time' => $dischargeTime,
                'billing_end_at' => $billingEndAt,
            ]);

            \Log::info('Calculating breakup');
            $breakup = $this->calculateBreakup($ipdId, $billingEndAt);
            \Log::info('Breakup calculated', ['total_charges' => $breakup['total_charges']]);

            // Get payment details
            \Log::info('Getting payment details');
            $payments = Transaction::where('ipd_id', $ipdId)
                ->where('type', 'payment')
                ->where('section', 'ipd')
                ->orderBy('payment_date', 'asc')
                ->get() ?? collect();

            // Get detailed breakdown - Calculate dynamically from PatientBedHistory up to discharge date (omit from display when package applied)
            \Log::info('Getting bed charges details');
            $bedChargesData = $this->calculateBedChargesFromHistory($ipdId, $billingEndAt);
            $bedChargesDetails = collect($bedChargesData['details']);
            $bedChargesGroupedForDisplay = $this->groupBedChargesByBedForDisplay($bedChargesDetails);
            $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);
            if (($breakup['package_charges'] ?? 0) > 0) {
                $bedChargesDetails = collect();
                $bedChargesGroupedForDisplay = $this->groupBedChargesByBedForDisplay($bedChargesDetails);
                $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);
            }

            \Log::info('Getting IPD charges details');
            $ipdChargesDetails = IpdCharges::where('ipd_id', $ipdId)
                ->with(['charge', 'chargeCategory'])
                ->orderBy('date', 'asc')
                ->get() ?? collect();

            // Pathology Details - Get all tests with names (same logic as estimate)
            \Log::info('Getting pathology details');
            $pathologyDetails = PathologyBilling::where(function($query) use ($ipd) {
                $query->where(function($q) use ($ipd) {
                    $q->where('patient_id', $ipd->patient_id)
                      ->whereDate('date', '>=', $ipd->date);
                });
                if ($ipd->case_reference_id) {
                    $query->orWhere('case_reference_id', $ipd->case_reference_id);
                }
            })->orderBy('date', 'asc')->get() ?? collect();

            // Radiology Details - Get all tests with names (same logic as estimate)
            \Log::info('Getting radiology details');
            $radiologyDetails = RadiologyBilling::where(function($query) use ($ipd) {
                $query->where(function($q) use ($ipd) {
                    $q->where('patient_id', $ipd->patient_id)
                      ->whereDate('date', '>=', $ipd->date);
                });
                if ($ipd->case_reference_id) {
                    $query->orWhere('case_reference_id', $ipd->case_reference_id);
                }
            })->orderBy('date', 'asc')->get() ?? collect();

            \Log::info('Getting doctor visit details');
            $doctorVisitDetails = $this->doctorVisitsBillableToIpdQuery((int) $ipd->patient_id, $ipd->date, $dischargeDate)
                ->with(['doctor', 'charge'])
                ->orderByRaw('COALESCE(visit_date, DATE(reporting_date), DATE(created_at)) ASC')
                ->orderBy('id')
                ->get();
            // Group doctor visits by doctor + visit type, then by visit type for PDF display
            $doctorVisitGroupedForDisplay = $this->groupDoctorVisitsForDisplay($doctorVisitDetails);
            $doctorVisitGroupedByVisitType = $this->groupDoctorVisitsByVisitTypeForDisplay($doctorVisitGroupedForDisplay);

            // Package details (applied packages) for final bill PDF
            $packageDetails = IpdPackage::where('ipd_id', $ipdId)
                ->where('status', 'applied')
                ->with('package')
                ->orderBy('applied_date', 'asc')
                ->get()
                ->map(function ($ipdPackage) {
                    return [
                        'date' => $ipdPackage->applied_date,
                        'package_name' => $ipdPackage->package->name ?? 'N/A',
                        'amount' => $ipdPackage->final_amount ?? 0,
                    ];
                });

            // Get hospital information
            $hospital = Hospital::first();

            // Generate final bill number (format: F-XXXXXX/YY-YY)
            $currentYear = date('y');
            $nextYear = $currentYear + 1;
            $yearRange = $currentYear . '-' . $nextYear;
            
            // Generate bill number based on IPD ID or use a sequential number
            // You can modify this logic based on your business requirements
            $billNumber = 'F-' . str_pad($ipd->id, 6, '0', STR_PAD_LEFT) . '/' . $yearRange;

            // Discharge date and time are already retrieved from discharge_card above
            $billDate = $dischargeDate ?? now();

            // Discount from IPD (MOU + Special/Hospital) — applies to final bill only
            $mouDiscount = (float) ($ipd->mou_discount ?? 0);
            $specialDiscount = (float) ($ipd->special_discount ?? 0);
            $discount = $mouDiscount + $specialDiscount;

            // Due on account of patient party (under doctor) — deducted from balance
            $duePatientPartyAmount = (float) ($ipd->due_patient_party_amount ?? 0);

            // Calculate balance
            $grandTotal = $breakup['total_charges'];
            $totalAdvance = $breakup['total_payments'];
            $balance = $grandTotal - $totalAdvance - $discount - $duePatientPartyAmount;
            $balance = max(0, $balance);

            // Convert amounts to words
            $grandTotalInWords = 'Zero Rupees Only';
            $totalAdvanceInWords = 'Zero Rupees Only';
            $balanceInWords = 'Zero Rupees Only';
            
            try {
                if (class_exists(\App\Helpers\NumberToWords::class)) {
                    $grandTotalInWords = \App\Helpers\NumberToWords::convert($grandTotal);
                    $totalAdvanceInWords = \App\Helpers\NumberToWords::convert($totalAdvance);
                    $balanceInWords = \App\Helpers\NumberToWords::convert($balance);
                }
            } catch (\Exception $e) {
                \Log::error('Error converting amounts to words: ' . $e->getMessage());
            }

            // Group bed charges by bed type for display
            $bedChargesGrouped = $bedChargesDetails->groupBy(function($item) {
                return ($item->bedGroup && $item->bedGroup->name) ? $item->bedGroup->name : 'Unknown';
            });

            // Get OT charges (from IPD charges where category is OT)
            $otCharges = $ipdChargesDetails->filter(function($charge) {
                $categoryName = ($charge->chargeCategory && $charge->chargeCategory->name) ? $charge->chargeCategory->name : '';
                $chargeName = ($charge->charge && $charge->charge->name) ? $charge->charge->name : '';
                return stripos($categoryName, 'OT') !== false || 
                       stripos($categoryName, 'Operation') !== false ||
                       stripos($chargeName, 'OT') !== false;
            });

            // Get medicine charges (from IPD charges)
            $medicineCharges = $ipdChargesDetails->filter(function($charge) {
                $categoryName = ($charge->chargeCategory && $charge->chargeCategory->name) ? $charge->chargeCategory->name : '';
                $chargeName = ($charge->charge && $charge->charge->name) ? $charge->charge->name : '';
                return stripos($categoryName, 'Medicine') !== false ||
                       stripos($categoryName, 'Pharmacy') !== false ||
                       stripos($chargeName, 'Medicine') !== false;
            });

            // Get surgeon and anesthesia charges
            $surgeonCharges = $ipdChargesDetails->filter(function($charge) {
                $categoryName = ($charge->chargeCategory && $charge->chargeCategory->name) ? $charge->chargeCategory->name : '';
                $chargeName = ($charge->charge && $charge->charge->name) ? $charge->charge->name : '';
                return stripos($chargeName, 'Surgeon') !== false ||
                       stripos($categoryName, 'Surgeon') !== false;
            });

            $anesthesiaCharges = $ipdChargesDetails->filter(function($charge) {
                $categoryName = ($charge->chargeCategory && $charge->chargeCategory->name) ? $charge->chargeCategory->name : '';
                $chargeName = ($charge->charge && $charge->charge->name) ? $charge->charge->name : '';
                return stripos($chargeName, 'Anesthesia') !== false ||
                       stripos($categoryName, 'Anesthesia') !== false;
            });

            // Pathology Details - Get all tests with names (same as estimate)
            \Log::info('Processing pathology test names', ['count' => $pathologyDetails->count()]);
            $pathologyTestNames = [];
            $pathologyTotal = 0;
            
            foreach ($pathologyDetails as $bill) {
                try {
                    $reports = DB::table('pathology_report')
                        ->where('pathology_bill_id', $bill->id)
                        ->leftJoin('ipd_prescription_test', 'pathology_report.ipd_prescription_test_id', '=', 'ipd_prescription_test.id')
                        ->join('pathology', 'pathology_report.pathology_id', '=', 'pathology.id')
                        ->select(
                            'pathology.test_name',
                            'pathology_report.apply_charge',
                            'pathology_report.instance_number',
                            'ipd_prescription_test.instance_number as prescription_instance_number'
                        )
                        ->get();
                    
                    foreach ($reports as $report) {
                        if ($report->test_name) {
                            // Format test name with instance number if available
                            $instanceNumber = $report->prescription_instance_number ?? $report->instance_number ?? null;
                            $testName = $report->test_name;
                            if ($instanceNumber && $instanceNumber > 1) {
                                $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' : 
                                                 ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                                $testName = $report->test_name . $instanceSuffix;
                            }
                            $pathologyTestNames[] = $testName;
                            $pathologyTotal += $report->apply_charge ?? 0;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Error processing pathology bill', ['bill_id' => $bill->id, 'error' => $e->getMessage()]);
                }
            }
            
            // Use breakup totals if calculated totals are 0
            if ($pathologyTotal == 0 && isset($breakup['pathology_charges'])) {
                $pathologyTotal = $breakup['pathology_charges'];
            }

            // Radiology Details - Get all tests with names (same as estimate)
            \Log::info('Processing radiology test names', ['count' => $radiologyDetails->count()]);
            $radiologyTestNames = [];
            $radiologyTotal = 0;
            
            foreach ($radiologyDetails as $bill) {
                try {
                    $reports = DB::table('radiology_report')
                        ->where('radiology_bill_id', $bill->id)
                        ->leftJoin('ipd_prescription_test', 'radiology_report.ipd_prescription_test_id', '=', 'ipd_prescription_test.id')
                        ->join('radio', 'radiology_report.radiology_id', '=', 'radio.id')
                        ->select(
                            'radio.test_name',
                            'radiology_report.apply_charge',
                            'radiology_report.instance_number',
                            'ipd_prescription_test.instance_number as prescription_instance_number'
                        )
                        ->get();
                    
                    foreach ($reports as $report) {
                        if ($report->test_name) {
                            // Format test name with instance number if available
                            $instanceNumber = $report->prescription_instance_number ?? $report->instance_number ?? null;
                            $testName = $report->test_name;
                            if ($instanceNumber && $instanceNumber > 1) {
                                $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' : 
                                                 ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                                $testName = $report->test_name . $instanceSuffix;
                            }
                            $radiologyTestNames[] = $testName;
                            $radiologyTotal += $report->apply_charge ?? 0;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Error processing radiology bill', ['bill_id' => $bill->id, 'error' => $e->getMessage()]);
                }
            }
            
            // Use breakup totals if calculated totals are 0
            if ($radiologyTotal == 0 && isset($breakup['radiology_charges'])) {
                $radiologyTotal = $breakup['radiology_charges'];
            }

            // Combine pathology and radiology as investigation charges
            $investigationCharges = $pathologyDetails->sum('total') + $radiologyDetails->sum('total');

            // Ensure arrays are initialized even if empty
            if (empty($pathologyTestNames)) {
                $pathologyTestNames = [];
            }
            if (empty($radiologyTestNames)) {
                $radiologyTestNames = [];
            }

            // Date-wise pathology and radiology for detail table
            $investigationDatewise = $this->getPathologyRadiologyDatewise($ipd);

            \Log::info('Starting PDF generation', [
                'pathology_tests' => count($pathologyTestNames),
                'radiology_tests' => count($radiologyTestNames)
            ]);

            // Set totalPages to 1 initially (will be calculated by PDF script)
            $totalPages = 1;

            // Generate PDF
            \Log::info('Loading PDF view');
            $pdf = Pdf::loadView('admin.billing.ipd_final_pdf', compact(
                'ipd', 'breakup', 'bedChargesDetails', 'bedChargesGrouped', 'bedChargesGroupedForDisplay', 'ipdChargesDetails',
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails', 'packageDetails', 'payments',
                'hospital', 'billNumber', 'billDate', 'dischargeDate', 'dischargeTime',
                'discount', 'mouDiscount', 'specialDiscount', 'duePatientPartyAmount',
                'grandTotal', 'totalAdvance', 'balance', 'grandTotalInWords',
                'totalAdvanceInWords', 'balanceInWords', 'otCharges', 'medicineCharges',
                'surgeonCharges', 'anesthesiaCharges', 'investigationCharges', 'totalPages',
                'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
                'investigationDatewise', 'doctorVisitGroupedForDisplay', 'doctorVisitGroupedByVisitType',
                'gstChargesGrouped', 'logged_user'
            ));
            
            \Log::info('PDF view loaded, setting options');
            $pdf->setOption('enable-php', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setPaper('a4', 'portrait');

            \Log::info('PDF generated successfully, returning inline stream');

            // Open in browser tab (inline) instead of forcing download
            return $pdf->stream('IPD_Final_Bill_' . $ipd->ipd_no . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Error in exportFinal: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ipd_id' => $ipdId
            ]);
            
            // Return a user-friendly error message
            $errorMessage = 'Error generating final bill PDF. ';
            $errorMessage .= 'Please check the logs for details or contact support.';
            $errorMessage .= ' Error: ' . $e->getMessage();
            
            if (request()->expectsJson() || request()->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => $errorMessage
                ], 500);
            }
            
            abort(500, $errorMessage);
        }
    }
}
