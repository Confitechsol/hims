<?php

namespace App\Http\Controllers;

use App\Models\IpdDetail;
use App\Models\IpdDaywiseBedCharge;
use App\Models\IpdCharges;
use App\Models\IpdPackage;
use App\Models\IpdPrescription;
use App\Models\OpdDetail;
use App\Models\PathologyBilling;
use App\Models\RadiologyBilling;
use App\Models\Transaction;
use App\Models\DoctorVisit;
use App\Models\Hospital;
use App\Models\DischargeCard;
use App\Models\PatientBedHistory;
use App\Models\BedGroup;
use App\Models\Doctor;
use App\Services\InsuranceDischargeBedChargeService;
use App\Services\DaywiseBedChargeService;
use App\Services\IpdPackageService;
use App\Services\InsuranceFinalBillSummaryService;
use App\Services\IpdFinalBillService;
use App\Support\BedBillingPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
                $finalBillGenerated = !empty($ipd->final_bill_generated_at);
                $dischargeStatus = $isDischarged
                    ? ($finalBillGenerated ? ' (Discharged)' : ' (Discharged – bed occupied)')
                    : '';

                $patientName = $patient ? ($patient->patient_name ?? 'N/A') : 'N/A';
                $phone = $patient ? ($patient->mobileno ?? '') : '';
                
                return [
                    'id' => $ipd->id,
                    'ipd_no' => $ipd->ipd_no ?? 'N/A',
                    'patient_name' => $patientName,
                    'phone' => $phone,
                    'discharged' => $isDischarged,
                    'discharged_date' => $ipd->discharged_date,
                    'final_bill_generated' => $finalBillGenerated,
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
     * API: Get patient balance details (net_balance, payments, outstanding)
     * Returns net_balance or total payments balance if net_balance is zero
     */
    public function getPatientBalance(Request $request)
    {
        try {
            $ipdId = $request->get('ipd_id');
            $patientId = $request->get('patient_id');

            if (!$ipdId && !$patientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Either ipd_id or patient_id is required',
                ], 400);
            }

            // Get IPD details
            $ipd = null;
            if ($ipdId) {
                $ipd = IpdDetail::with(['patient', 'duePatientPartyDoctor'])
                    ->find($ipdId);
            } elseif ($patientId) {
                // Get latest IPD for patient
                $ipd = IpdDetail::with(['patient', 'duePatientPartyDoctor'])
                    ->where('patient_id', $patientId)
                    ->orderBy('date', 'desc')
                    ->first();
            }

            if (!$ipd) {
                return response()->json([
                    'success' => false,
                    'message' => 'IPD record not found',
                ], 404);
            }

            $patient = $ipd->patient;
            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient details not found',
                ], 404);
            }

            // Calculate breakup
            $endDate = $this->resolveBillingEndDateForIpd($ipd);
            $breakup = $this->calculateBreakup($ipd->id, $endDate);

            // Calculate net balance
            $totalDiscount = (float) ($ipd->mou_discount ?? 0) + (float) ($ipd->special_discount ?? 0);
            $duePatientPartyAmount = (float) ($ipd->due_patient_party_amount ?? 0);
            $outstanding = (float) ($breakup['outstanding'] ?? 0);
            $netBalance = max(0, $outstanding - $totalDiscount - $duePatientPartyAmount);
            $totalPayments = (float) ($breakup['total_payments'] ?? 0);
            $totalCharges = (float) ($breakup['total_charges'] ?? 0);

            // If net_balance is 0, show outstanding or total_payments balance
            $balanceToShow = $netBalance;
            if ($netBalance == 0) {
                $balanceToShow = max(0, $totalCharges - $totalPayments);
            }

            return response()->json([
                'success' => true,
                'patient' => [
                    'id' => $patient->id,
                    'patient_name' => $patient->patient_name ?? 'N/A',
                    'patient_id_no' => $patient->patient_id_no ?? '',
                    'phone' => $patient->mobileno ?? '',
                    'email' => $patient->email ?? '',
                    'address' => $patient->address ?? '',
                    'age' => $patient->age ?? '',
                    'gender' => $patient->gender ?? '',
                ],
                'ipd' => [
                    'id' => $ipd->id,
                    'ipd_no' => $ipd->ipd_no ?? 'N/A',
                    'admission_date' => $ipd->date ?? null,
                    'discharged' => $ipd->discharged ?? 'no',
                    'discharged_date' => $ipd->discharged_date ?? null,
                    'is_insurance' => $ipd->isInsuranceBilling() ? 'yes' : 'no',
                ],
                'billing' => [
                    'total_charges' => round($totalCharges, 2),
                    'total_payments' => round($totalPayments, 2),
                    'outstanding' => round($outstanding, 2),
                    'total_discount' => round($totalDiscount, 2),
                    'due_patient_party_amount' => round($duePatientPartyAmount, 2),
                    'net_balance' => round($netBalance, 2),
                    'balance_to_show' => round($balanceToShow, 2),
                ],
                'breakup' => [
                    'bed_charges' => round($breakup['bed_charges'] ?? 0, 2),
                    'ipd_charges' => round($breakup['ipd_charges'] ?? 0, 2),
                    'pathology_charges' => round($breakup['pathology_charges'] ?? 0, 2),
                    'radiology_charges' => round($breakup['radiology_charges'] ?? 0, 2),
                    'doctor_visit_charges' => round($breakup['doctor_visit_charges'] ?? 0, 2),
                    'package_charges' => round($breakup['package_charges'] ?? 0, 2),
                    'cgst_charges' => round($breakup['cgst_charges'] ?? 0, 2),
                    'sgst_charges' => round($breakup['sgst_charges'] ?? 0, 2),
                ],
                'message' => $netBalance == 0 
                    ? 'Net balance is zero. Balance to show is calculated from total charges minus payments.' 
                    : 'Balance calculated successfully',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Get patient balance failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error while fetching patient balance: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get breakup bill for IPD patient
     */
    public function breakup($ipdId)
    {
        $ipd = IpdDetail::with(['patient', 'doctor', 'bedGroup', 'bedDetail', 'duePatientPartyDoctor', 'organisation', 'insuranceCompany'])
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

        $isInsuranceIpd = $ipd->isInsuranceBilling();
        $grandTotal = (float) ($breakup['total_charges'] ?? 0);
        $mouDiscountAmount = (float) ($ipd->mou_discount ?? 0);
        $initialApprovalAmount = (float) ($ipd->initial_approval_amount ?? 0);
        $finalApprovalAmount = (float) ($ipd->final_approval_amount ?? 0);
        $requestFurtherApproval = max(0, $grandTotal - $mouDiscountAmount - $initialApprovalAmount);
        $insuranceBalanceAmount = max(0, $grandTotal - $finalApprovalAmount);

        // Get detailed date-wise breakdown with same boundary as summary.
        $detailedBreakup = $this->getDetailedBreakup($ipdId, $ipd, $endDate);

        return view('admin.billing.ipd_breakup', compact(
            'ipd',
            'breakup',
            'detailedBreakup',
            'doctors',
            'isInsuranceIpd',
            'grandTotal',
            'mouDiscountAmount',
            'initialApprovalAmount',
            'finalApprovalAmount',
            'insuranceBalanceAmount',
            'requestFurtherApproval'
        ));
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
            'initial_approval_amount' => 'nullable|numeric|min:0',
            'final_approval_amount' => 'nullable|numeric|min:0',
        ]);

        $ipd = IpdDetail::findOrFail($ipdId);
        $ipd->mou_discount = (float) ($request->input('mou_discount') ?? 0);
        $ipd->special_discount = (float) ($request->input('special_discount') ?? 0);
        if ($ipd->isInsuranceBilling()) {
            if ($request->exists('initial_approval_amount')) {
                $ipd->initial_approval_amount = $request->filled('initial_approval_amount')
                    ? (float) $request->input('initial_approval_amount')
                    : null;
            }
            if ($request->exists('final_approval_amount')) {
                $ipd->final_approval_amount = $request->filled('final_approval_amount')
                    ? (float) $request->input('final_approval_amount')
                    : null;
            }
        }
        $ipd->save();

        $totalDiscount = $ipd->mou_discount + $ipd->special_discount;
        $duePatientPartyAmount = (float) ($ipd->due_patient_party_amount ?? 0);
        $endDate = $this->resolveBillingEndDateForIpd($ipd);
        $breakup = $this->calculateBreakup($ipdId, $endDate);
        $outstanding = $breakup['outstanding'] ?? 0;
        $outstandingAfterDiscount = max(0, $outstanding - $totalDiscount);
        $netBalance = max(0, $outstanding - $totalDiscount - $duePatientPartyAmount);
        $approvalAmount = (float) ($ipd->final_approval_amount ?? 0);
        $balanceAmount = max(0, (float) ($breakup['total_charges'] ?? 0) - $approvalAmount);
        $initialApprovalAmount = (float) ($ipd->initial_approval_amount ?? 0);
        $requestFurtherApproval = max(
            0,
            (float) ($breakup['total_charges'] ?? 0) - (float) $ipd->mou_discount - $initialApprovalAmount
        );

        return response()->json([
            'success' => true,
            'message' => 'Billing values saved successfully.',
            'mou_discount' => $ipd->mou_discount,
            'special_discount' => $ipd->special_discount,
            'initial_approval_amount' => $ipd->initial_approval_amount,
            'final_approval_amount' => $ipd->final_approval_amount,
            'request_further_approval' => $requestFurtherApproval,
            'total_discount' => $totalDiscount,
            'due_patient_party_amount' => $duePatientPartyAmount,
            'outstanding' => $outstanding,
            'outstanding_after_discount' => $outstandingAfterDiscount,
            'net_balance' => $netBalance,
            'balance_amount' => $balanceAmount,
            'due_on_account' => $approvalAmount,
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
    private function calculateBedChargesFromHistory($ipdId, $endDate = null, ?Carbon $insuranceDischargeAtOverride = null)
    {
        $ipd = IpdDetail::find($ipdId);
        if (!$ipd) {
            return ['total' => 0, 'details' => []];
        }

        $admissionAt = Carbon::parse($ipd->date ?? $ipd->created_at ?? now());
        $billableAnchor = BedBillingPeriod::billableAnchorAt($admissionAt);
        // For non-discharged preview/breakup, calculate up to current moment.
        // Date-only end values use discharge card time when available (insurance discharge-day rule).
        if ($endDate === null) {
            $endAt = ($ipd->discharged ?? 'no') === 'yes'
                ? ($this->resolveDischargeDateTimeForIpd($ipd) ?? Carbon::now())
                : Carbon::now();
        } else {
            $rawEnd = trim((string) $endDate);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawEnd)) {
                $dischargeAt = $this->resolveDischargeDateTimeForIpd($ipd);
                if ($dischargeAt && $dischargeAt->format('Y-m-d') === $rawEnd) {
                    $endAt = $dischargeAt;
                } else {
                    $endAt = Carbon::parse($rawEnd)->endOfDay();
                }
            } else {
                $endAt = Carbon::parse($rawEnd);
            }
        }

        $insuranceDischargeBedService = app(InsuranceDischargeBedChargeService::class);
        $skipInsuranceDischargeChargeDate = $insuranceDischargeBedService->dischargeChargeDateToExclude(
            $ipd,
            $endAt,
            $insuranceDischargeAtOverride
        );

        // Get all bed history records for this IPD (primary source)
        $bedHistories = PatientBedHistory::where('ipd_id', $ipdId)
            ->with(['bedGroup', 'bed'])
            ->orderBy('from_date', 'asc')
            ->get();

        // Defensive fallback for imported/legacy admissions: the current IPD bed
        // remains billable even if its history row was never created.
        if ($bedHistories->isEmpty() && $ipd->bed && $ipd->bed_group_id) {
            $currentBed = new PatientBedHistory([
                'ipd_id' => $ipd->id,
                'bed_group_id' => $ipd->bed_group_id,
                'bed_id' => $ipd->bed,
                'from_date' => $admissionAt,
                'is_active' => 'yes',
            ]);
            $currentBed->setRelation('bedGroup', $ipd->bedGroup()->first());
            $currentBed->setRelation('bed', $ipd->bedDetail()->first());
            $bedHistories = collect([$currentBed]);
        }

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
        // First billable day uses billable anchor (post-midnight pre-11 AM admissions start at 11:00).
        $firstChargeDay = BedBillingPeriod::firstChargeCalendarDayFromAnchorDate($admissionAt);
        $lastChargeDay = $this->resolveChargeLabelDayForMoment($endAt);
        if ($endAt->gt($billableAnchor) && $lastChargeDay->lt($firstChargeDay)) {
            $lastChargeDay = $firstChargeDay->copy();
        }
        $currentDate = $firstChargeDay->copy();

        // Pre-compute custom bed charge for each bed segment (match by bed_id — admit/transfer rate).
        $bedSegmentCustomCharges = []; // Format: ['bed_id|history_index' => daywise_charge_object]
        foreach ($bedHistories as $historyIndex => $history) {
            $bedHistoryFromDate = Carbon::parse($history->from_date);
            $bedHistoryToDate = $history->to_date ? Carbon::parse($history->to_date) : Carbon::now();

            $mostRecentDaywise = null;
            $mostRecentDate = null;

            foreach ($daywiseCharges as $date => $daywise) {
                if ((int) $daywise->bed_id !== (int) $history->bed_id) {
                    continue;
                }
                $daywiseDateCarbon = Carbon::parse($daywise->charge_date)->startOfDay();

                if ($daywiseDateCarbon->gte($bedHistoryFromDate->copy()->startOfDay()) && $daywiseDateCarbon->lte($bedHistoryToDate->copy()->endOfDay())) {
                    if ($mostRecentDate === null || $daywiseDateCarbon->gt($mostRecentDate)) {
                        $mostRecentDate = $daywiseDateCarbon;
                        $mostRecentDaywise = $daywise;
                    }
                }
            }

            if ($mostRecentDaywise && isset($mostRecentDaywise->bed_charge) && (float) $mostRecentDaywise->bed_charge > 0) {
                $bedSegmentCustomCharges[$history->bed_id . '|' . $historyIndex] = $mostRecentDaywise;
            }
        }

        // Calculate charges for each calendar day from admission to end date (charge_date = label day).
        // Billing cycle remains boundary-based (default 11:00 -> next day 11:00).
        // We also clamp by actual discharge datetime so final bill does not over-count.
        while ($currentDate->lte($lastChargeDay)) {
            $chargeDate = $currentDate->format('Y-m-d');

            // Insurance IPD: skip discharge-day bed charge unless discharge is at/after 3 PM.
            if ($skipInsuranceDischargeChargeDate !== null && $chargeDate === $skipInsuranceDischargeChargeDate) {
                $currentDate->addDay();
                continue;
            }

            [$periodStart, $periodEnd] = BedBillingPeriod::windowForChargeCalendarDay($currentDate->copy()->startOfDay());

            // Respect requested end moment (discharge datetime for final bill).
            $isFirstAdmissionDay = $currentDate->isSameDay($firstChargeDay);
            if ($periodEnd->lte($billableAnchor) || (! $isFirstAdmissionDay && $periodStart->gte($endAt))) {
                $currentDate->addDay();
                continue;
            }

            $effectiveStart = $isFirstAdmissionDay
                ? $billableAnchor->copy()
                : $periodStart->copy()->max($billableAnchor);
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

                $customChargeKey = $activeBed->bed_id . '|' . $activeBedHistoryIndex;

                // Prefer custom rate set at admit/transfer for the whole bed segment.
                if (isset($bedSegmentCustomCharges[$customChargeKey])) {
                    $daywise = $bedSegmentCustomCharges[$customChargeKey];
                    if ($daywise && isset($daywise->bed_charge) && (float) $daywise->bed_charge > 0) {
                        $bedCost = (float) $daywise->bed_charge;
                        $bedChargeRate = (float) ($daywise->bed_charge_rate ?? $daywise->bed_charge ?? $activeBed->bedGroup->bed_cost ?? 0);
                        $gstRate = $daywise->bedGroup->gst_rate ?? $activeBed->bedGroup->gst_rate ?? 0;
                        $sacHsnCode = $daywise->bedGroup->sac_hsn_code ?? $activeBed->bedGroup->sac_hsn_code ?? null;
                    }
                } elseif ($daywiseCharges->has($chargeDate)) {
                    $daywise = $daywiseCharges->get($chargeDate);
                    if ($daywise && (int) $daywise->bed_id === (int) $activeBed->bed_id && isset($daywise->bed_charge) && (float) $daywise->bed_charge > 0) {
                        $bedCost = (float) $daywise->bed_charge;
                        $bedChargeRate = (float) ($daywise->bed_charge_rate ?? $activeBed->bedGroup->bed_cost ?? 0);
                        $gstRate = $daywise->bedGroup->gst_rate ?? $activeBed->bedGroup->gst_rate ?? 0;
                        $sacHsnCode = $daywise->bedGroup->sac_hsn_code ?? $activeBed->bedGroup->sac_hsn_code ?? null;
                    }
                }

                // No row for this charge date: resolve segment custom rate, else bed group master.
                if ($bedCost <= 0) {
                    $segmentFrom = Carbon::parse($activeBed->from_date);
                    $segmentTo = $activeBed->to_date ? Carbon::parse($activeBed->to_date) : null;
                    $resolvedRate = app(DaywiseBedChargeService::class)->resolveBedChargeRate(
                        $ipdId,
                        (int) $activeBed->bed_group_id,
                        $segmentFrom,
                        $segmentTo,
                        (int) $activeBed->bed_id
                    );
                    $bedCost = $resolvedRate > 0
                        ? $resolvedRate
                        : (float) ($activeBed->bedGroup->bed_cost ?? 0);
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
    private function calculateBreakup($ipdId, $endDate = null, ?string $billStage = null)
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
        $ipdCharges = $this->ipdChargesBaseQuery($ipdId, $billStage)
            ->sum('net_amount');

        // Pathology Charges (check by patient_id + date range OR case_reference_id)
        $pathologyCharges = (float) $this->pathologyBillsForIpdQuery($ipd, $billStage)->sum('net_amount');

        // Radiology Charges (check by patient_id + date range OR case_reference_id)
        $radiologyCharges = (float) $this->radiologyBillsForIpdQuery($ipd, $billStage)->sum('net_amount');

        // Get IPD patient_id and admission date (use date-only so same-day visits are included)
        $patientId = $ipd->patient_id ?? null;
        $admissionDate = $ipd->date ? Carbon::parse($ipd->date)->format('Y-m-d') : null;

        // Doctor Visit Charges (linked to this IPD; legacy null-ipd_id by admission window)
        $doctorVisitCharges = 0;
        if ($patientId && $admissionDate) {
            $doctorVisitCharges = (float) $this->doctorVisitsBillableToIpdQuery($ipd, $endDate)
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
     * Public snapshot of bed charges up to an end datetime (used by final-bill extra-bed preview).
     */
    public function computeBedChargesSnapshot(int $ipdId, $endDate = null, ?Carbon $insuranceDischargeAtOverride = null): array
    {
        return $this->calculateBedChargesFromHistory($ipdId, $endDate, $insuranceDischargeAtOverride);
    }

    /**
     * Resolve billing end datetime for an IPD.
     * Discharged IPD always stops at clinical discharge date/time (no post-discharge bed).
     */
    /**
     * IPD charges query, optionally filtered for a billing export stage.
     */
    private function ipdChargesBaseQuery(int $ipdId, ?string $billStage = null)
    {
        return IpdCharges::where('ipd_id', $ipdId)->visibleForBillStage($billStage);
    }

    /**
     * Pathology bills for an IPD, optionally filtered by export stage visibility.
     */
    private function pathologyBillsForIpdQuery(IpdDetail $ipd, ?string $billStage = null)
    {
        $caseReferenceId = $ipd->case_reference_id ?? null;

        return PathologyBilling::where(function ($query) use ($ipd, $caseReferenceId) {
            $query->where(function ($q) use ($ipd) {
                $q->where('patient_id', $ipd->patient_id)
                    ->where('date', '>=', $ipd->date);
            });
            if ($caseReferenceId) {
                $query->orWhere('case_reference_id', $caseReferenceId);
            }
        })->visibleForBillStage($billStage);
    }

    /**
     * Radiology bills for an IPD, optionally filtered by export stage visibility.
     */
    private function radiologyBillsForIpdQuery(IpdDetail $ipd, ?string $billStage = null)
    {
        $caseReferenceId = $ipd->case_reference_id ?? null;

        return RadiologyBilling::where(function ($query) use ($ipd, $caseReferenceId) {
            $query->where(function ($q) use ($ipd) {
                $q->where('patient_id', $ipd->patient_id)
                    ->where('date', '>=', $ipd->date);
            });
            if ($caseReferenceId) {
                $query->orWhere('case_reference_id', $caseReferenceId);
            }
        })->visibleForBillStage($billStage);
    }

    /**
     * Map export context to ipd_charges / pathology / radiology visibility stage.
     */
    private function resolveIpdChargeBillStage(?string $billType = null, ?string $finalBillStage = null): ?string
    {
        if ($billType === 'approval') {
            return IpdCharges::STAGE_APPROVAL;
        }
        if ($billType === 'approval_preview') {
            return IpdCharges::STAGE_APPROVAL_PREVIEW;
        }
        if ($finalBillStage === 'final_preview') {
            return IpdCharges::STAGE_FINAL_PREVIEW;
        }
        if ($finalBillStage === 'final_bill') {
            return IpdCharges::STAGE_FINAL_BILL;
        }

        return null;
    }

    private function resolveBillingEndDateForIpd(IpdDetail $ipd): ?string
    {
        if (($ipd->discharged ?? 'no') !== 'yes') {
            return null;
        }

        $dischargeAt = $this->resolveDischargeDateTimeForIpd($ipd);
        if ($dischargeAt) {
            return $dischargeAt->format('Y-m-d H:i:s');
        }

        if (! empty($ipd->discharged_date)) {
            return Carbon::parse($ipd->discharged_date)->startOfDay()->format('Y-m-d H:i:s');
        }

        return null;
    }

    /**
     * Discharge datetime for billing (prefers discharge card date + time).
     */
    private function resolveDischargeDateTimeForIpd(IpdDetail $ipd): ?Carbon
    {
        return app(InsuranceDischargeBedChargeService::class)->resolveDischargeAt($ipd);
    }

    /**
     * Determine charge-label day (Y-m-d @ 00:00) for a moment.
     * If the moment is after boundary time, it belongs to the next label day.
     */
    private function resolveChargeLabelDayForMoment(Carbon $moment): Carbon
    {
        return BedBillingPeriod::chargeLabelDayForMoment($moment);
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
            $doctorVisitDetails = $this->doctorVisitsBillableToIpdQuery($ipd, null)
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

        // Package Charges Details (ordered by approval % descending for procedure sequence)
        $packageDetails = $this->buildPackageDetailsForBillDisplay($ipdId);

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
     * Applied packages for bill PDFs: ordered 100% → lower %, with procedure labels.
     */
    private function buildPackageDetailsForBillDisplay(int $ipdId): \Illuminate\Support\Collection
    {
        $packages = IpdPackage::where('ipd_id', $ipdId)
            ->where('status', 'applied')
            ->with('package');

        if (Schema::hasColumn('ipd_packages', 'approval_percentage')) {
            $packages = $packages->orderByRaw('COALESCE(approval_percentage, 0) DESC');
        }

        $packages = $packages
            ->orderBy('applied_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return $packages->values()->map(function ($ipdPackage, $index) {
            $name = $ipdPackage->package->name ?? 'N/A';
            $pctLabel = $this->formatApprovalPercentageLabel($ipdPackage->approval_percentage);
            $packageNameDisplay = trim($name . ($pctLabel !== '' ? ' ' . $pctLabel : ''));

            return [
                'date' => $ipdPackage->applied_date,
                'procedure_label' => $this->ordinalProcedureLabel($index + 1),
                'package_name' => $name,
                'package_name_display' => $packageNameDisplay,
                'approval_percentage' => $ipdPackage->approval_percentage,
                'amount' => $ipdPackage->final_amount ?? 0,
                'original_amount' => $ipdPackage->package_rate ?? 0,
                'type' => 'package',
                'description' => 'Package - ' . $packageNameDisplay,
            ];
        });
    }

    private function ordinalProcedureLabel(int $position): string
    {
        $suffix = match (true) {
            ($position % 100) >= 11 && ($position % 100) <= 13 => 'th',
            $position % 10 === 1 => 'st',
            $position % 10 === 2 => 'nd',
            $position % 10 === 3 => 'rd',
            default => 'th',
        };

        return $position . $suffix . ' Procedure';
    }

    private function formatApprovalPercentageLabel($percentage): string
    {
        if ($percentage === null || $percentage === '') {
            return '';
        }

        $formatted = rtrim(rtrim(number_format((float) $percentage, 2, '.', ''), '0'), '.');

        return '(' . $formatted . '%)';
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
     * Doctor visits billable on an IPD estimate/final.
     * Prefer rows linked to this IPD; include legacy null-ipd_id rows in the admission date window.
     */
    private function doctorVisitsBillableToIpdQuery(IpdDetail $ipd, $endDateYmd = null): \Illuminate\Database\Eloquent\Builder
    {
        $patientId = (int) $ipd->patient_id;
        $admissionYmd = $ipd->date ? Carbon::parse($ipd->date)->format('Y-m-d') : null;
        $endYmd = ($endDateYmd !== null && $endDateYmd !== '')
            ? Carbon::parse($endDateYmd)->format('Y-m-d')
            : null;

        return DoctorVisit::query()
            ->where('patient_id', $patientId)
            ->where(function ($q) use ($ipd, $admissionYmd, $endYmd) {
                $q->where('ipd_id', $ipd->id);

                if ($admissionYmd) {
                    $q->orWhere(function ($legacy) use ($admissionYmd, $endYmd) {
                        $legacy->whereNull('ipd_id')
                            ->where(function ($dateQ) use ($admissionYmd, $endYmd) {
                                $dateQ->where(function ($w) use ($admissionYmd, $endYmd) {
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
                    });
                }
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
     * OT procedure doctor fees (e.g. OT(Anesthesia Charge), OT(Surgeon Charge)) — shown outside Doctor Visit Charges on bills.
     */
    private function isOtAnesthesiaOrSurgeonDoctorChargeLabel(string $label): bool
    {
        $name = strtoupper(trim($label));
        if ($name === '') {
            return false;
        }
        if (! str_contains($name, 'OT')) {
            return false;
        }

        return str_contains($name, 'ANESTHESIA') || str_contains($name, 'SURGEON');
    }

    private function isOtAnesthesiaOrSurgeonDoctorCharge($charge): bool
    {
        if (! $charge) {
            return false;
        }

        return $this->isOtAnesthesiaOrSurgeonDoctorChargeLabel((string) ($charge->name ?? ''));
    }

    /**
     * @return array{
     *     doctorVisitGroupedForDisplay: \Illuminate\Support\Collection,
     *     doctorVisitGroupedByVisitType: \Illuminate\Support\Collection,
     *     otDoctorChargeRows: \Illuminate\Support\Collection,
     *     doctorVisitChargesDisplaySubtotal: float,
     *     otDoctorChargesDisplaySubtotal: float
     * }
     */
    private function prepareDoctorVisitDisplayForBillPdf($doctorVisitDetails): array
    {
        $doctorVisitGroupedForDisplay = $this->groupDoctorVisitsForDisplay($doctorVisitDetails);
        $groupedByVisitType = $this->groupDoctorVisitsByVisitTypeForDisplay($doctorVisitGroupedForDisplay);

        $visitGroups = collect();
        $otRows = collect();

        foreach ($groupedByVisitType as $group) {
            $label = (string) ($group->visit_type_label ?? '');
            $isOtDoctorCharge = $this->isOtAnesthesiaOrSurgeonDoctorChargeLabel($label);
            if (! $isOtDoctorCharge && ($group->rows ?? collect())->isNotEmpty()) {
                $first = $group->rows->first();
                $isOtDoctorCharge = $this->isOtAnesthesiaOrSurgeonDoctorCharge($first->charge ?? null);
            }

            if ($isOtDoctorCharge) {
                foreach ($group->rows ?? [] as $row) {
                    $otRows->push($row);
                }
            } else {
                $visitGroups->push($group);
            }
        }

        $otRows = $otRows->sortBy('from_date')->values();

        $visitSubtotal = $visitGroups->sum(function ($group) {
            return ($group->rows ?? collect())->sum(fn ($row) => (float) ($row->total_amount ?? 0));
        });
        $otSubtotal = $otRows->sum(fn ($row) => (float) ($row->total_amount ?? 0));

        return [
            'doctorVisitGroupedForDisplay' => $doctorVisitGroupedForDisplay,
            'doctorVisitGroupedByVisitType' => $visitGroups->values(),
            'otDoctorChargeRows' => $otRows,
            'doctorVisitChargesDisplaySubtotal' => round((float) $visitSubtotal, 2),
            'otDoctorChargesDisplaySubtotal' => round((float) $otSubtotal, 2),
        ];
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
     * Calendar end date for grouped bed charge "Date Range" (display only; billing unchanged).
     * Uses period_end of the last billed day, capped to billing/discharge calendar date on the final bed row.
     */
    private function resolveBedChargeDisplayCalendarEnd(
        string $periodEndYmd,
        bool $isLastBedGroup,
        ?string $billingEndCalendarYmd
    ): string {
        if (! $isLastBedGroup || $billingEndCalendarYmd === null || $billingEndCalendarYmd === '') {
            return $periodEndYmd;
        }

        try {
            $end = Carbon::parse($periodEndYmd)->startOfDay();
            $cap = Carbon::parse($billingEndCalendarYmd)->startOfDay();

            return $end->lte($cap) ? $end->format('Y-m-d') : $cap->format('Y-m-d');
        } catch (\Throwable $e) {
            return $periodEndYmd;
        }
    }

    /**
     * Group day-wise bed charges by bed and contiguous date ranges for display.
     * Returns one row per (bed + contiguous date range): e.g. "SINGLE - 5 SINGLE @5000 | 5 Days | 17/01/2026 To 20/01/2026".
     *
     * Printed "Date Range" uses calendar dates only (display labels):
     * - from = period_start_date of first billed day (admission-side calendar date)
     * - to   = period_end_date of last billed day, capped to discharge/billing-end calendar date
     *          on the chronologically last bed group (never increases day count or amounts).
     *
     * @param \Illuminate\Support\Collection $bedChargesDetails Day-wise details from calculateBedChargesFromHistory
     * @param string|null $billingEndCalendarYmd Y-m-d discharge or estimate end (calendar); caps last row end date
     * @return \Illuminate\Support\Collection
     */
    private function groupBedChargesByBedForDisplay($bedChargesDetails, ?string $billingEndCalendarYmd = null)
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

        if ($result->isEmpty()) {
            return $result;
        }

        $sorted = $result->sortBy('from_date')->values();
        $lastIndex = $sorted->count() - 1;
        foreach ($sorted as $index => $row) {
            $row->to_date = $this->resolveBedChargeDisplayCalendarEnd(
                (string) $row->to_date,
                $index === $lastIndex,
                $billingEndCalendarYmd
            );
        }

        return $sorted;
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
            $doctorVisits = $this->doctorVisitsBillableToIpdQuery($ipd, $dischargeDate)
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
     * Final bill amount for Final Bill Register Home Amt column.
     * Total final bill charges minus MOU discount and hospital (special) discount.
     */
    private function resolveFinalBillGrandTotal(IpdDetail $ipd, array $breakup): float
    {
        $totalCharges = round((float) ($breakup['total_charges'] ?? 0), 2);
        $mouDiscount = round((float) ($ipd->mou_discount ?? 0), 2);
        $hospitalDiscount = round((float) ($ipd->special_discount ?? 0), 2);

        return max(0, round($totalCharges - $mouDiscount - $hospitalDiscount, 2));
    }

    /**
     * Day-wise register column totals for one discharged IPD (cash + insurance final bills).
     *
     * Home Amt = final bill total after excluding MOU and hospital discounts.
     * Disc Amt = MOU + hospital discount (shown separately).
     * Other columns are category breakdowns only.
     *
     * @return array{
     *     bed_charges: float,
     *     diagnosis_charges: float,
     *     other_charges: float,
     *     service_charges: float,
     *     home_amount: float,
     *     discount_amount: float,
     *     doctor_visit_amount: float,
     *     package_amount: float
     * }
     */
    public function getFinalBillRegisterDaySummary(int $ipdId): array
    {
        $empty = [
            'bed_charges' => 0.0,
            'diagnosis_charges' => 0.0,
            'other_charges' => 0.0,
            'service_charges' => 0.0,
            'home_amount' => 0.0,
            'discount_amount' => 0.0,
            'doctor_visit_amount' => 0.0,
            'package_amount' => 0.0,
        ];

        $ipd = IpdDetail::find($ipdId);
        if (!$ipd || $ipd->discharged !== 'yes') {
            return $empty;
        }

        $billingEndAt = $this->resolveDischargeDateTimeForIpd($ipd);
        if (!$billingEndAt) {
            $dischargeCard = DischargeCard::where('ipd_details_id', $ipdId)->first();
            if (!$dischargeCard || !$dischargeCard->discharge_date) {
                return $empty;
            }
            $billingEndAt = Carbon::parse($dischargeCard->discharge_date);
        }

        $billingEndAtStr = $billingEndAt->format('Y-m-d H:i:s');
        $breakup = $this->calculateBreakup($ipdId, $billingEndAtStr);

        $bedCharges = round((float) ($breakup['bed_charges'] ?? 0), 2);
        $serviceCharges = round(
            (float) ($breakup['cgst_charges'] ?? 0) + (float) ($breakup['sgst_charges'] ?? 0),
            2
        );
        $diagnosisCharges = round(
            (float) ($breakup['pathology_charges'] ?? 0) + (float) ($breakup['radiology_charges'] ?? 0),
            2
        );
        $otherCharges = round((float) ($breakup['ipd_charges'] ?? 0), 2);
        $doctorVisit = round((float) ($breakup['doctor_visit_charges'] ?? 0), 2);
        $package = round((float) ($breakup['package_charges'] ?? 0), 2);
        $discount = round((float) ($ipd->mou_discount ?? 0) + (float) ($ipd->special_discount ?? 0), 2);
        $homeAmount = $this->resolveFinalBillGrandTotal($ipd, $breakup);

        return [
            'bed_charges' => $bedCharges,
            'diagnosis_charges' => $diagnosisCharges,
            'other_charges' => $otherCharges,
            'service_charges' => $serviceCharges,
            'home_amount' => $homeAmount,
            'discount_amount' => $discount,
            'doctor_visit_amount' => $doctorVisit,
            'package_amount' => $package,
        ];
    }

    /**
     * Case No. shown on IPD estimate / approval / final bills for a pathology or radiology bill.
     * Prefers IPD prescription number (e.g. IPDP0248), then OPD visit no.
     */
    private function resolveDiagnosticBillCaseNo($bill, $prescriptionIdFromReport = null): string
    {
        static $cache = [];

        $lookupId = $prescriptionIdFromReport
            ?: ($bill->case_reference_id ?? null)
            ?: ($bill->ipd_prescription_basic_id ?? null);

        $cacheKey = $lookupId !== null && $lookupId !== ''
            ? 'id:' . $lookupId
            : 'bill:' . ($bill->getTable() ?? 'diag') . ':' . ($bill->id ?? 0);

        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }

        $resolved = '-';

        if ($lookupId) {
            $prescription = IpdPrescription::find($lookupId);
            if ($prescription) {
                $number = trim((string) ($prescription->prescription_number ?? ''));
                $resolved = $number !== ''
                    ? $number
                    : ('IPDP' . str_pad((string) $prescription->id, 4, '0', STR_PAD_LEFT));
            } else {
                $opd = OpdDetail::find($lookupId);
                if ($opd && ! empty($opd->opd_no)) {
                    $resolved = (string) $opd->opd_no;
                }
            }
        }

        if ($resolved === '-') {
            $linked = $bill->prescription ?? null;
            if ($linked) {
                $number = trim((string) ($linked->prescription_number ?? ''));
                $resolved = $number !== ''
                    ? $number
                    : ('IPDP' . str_pad((string) $linked->id, 4, '0', STR_PAD_LEFT));
            }
        }

        $cache[$cacheKey] = $resolved;

        return $resolved;
    }

    /**
     * Build pathology and radiology line items date-wise for PDF (estimate/final).
     * Returns collection of ['date' => ..., 'case_no' => ..., 'type' => 'pathology'|'radiology', 'test_name' => ..., 'amount' => ...] sorted by date.
     */
    private function getPathologyRadiologyDatewise($ipd, ?string $billStage = null)
    {
        $pathologyBills = $this->pathologyBillsForIpdQuery($ipd, $billStage)
            ->with('prescription')
            ->orderBy('date', 'asc')
            ->get();

        $pathologyRows = $pathologyBills->map(function ($bill) {
            $reports = DB::table('pathology_report')
                ->where('pathology_bill_id', $bill->id)
                ->leftJoin('ipd_prescription_test', 'pathology_report.ipd_prescription_test_id', '=', 'ipd_prescription_test.id')
                ->join('pathology', 'pathology_report.pathology_id', '=', 'pathology.id')
                ->select(
                    'pathology.test_name',
                    'pathology_report.apply_charge',
                    'pathology_report.instance_number',
                    'ipd_prescription_test.instance_number as prescription_instance_number',
                    'ipd_prescription_test.ipd_prescription_id as prescription_id'
                )
                ->get();
            $fallbackCaseNo = $this->resolveDiagnosticBillCaseNo($bill);
            if ($reports->count() > 0) {
                return $reports->map(function ($r) use ($bill, $fallbackCaseNo) {
                    $instanceNumber = $r->prescription_instance_number ?? $r->instance_number ?? null;
                    $testName = $r->test_name ?? 'N/A';
                    if ($instanceNumber && $instanceNumber > 1 && $testName !== 'N/A') {
                        $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' :
                                         ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                        $testName = $r->test_name . $instanceSuffix;
                    }
                    $caseNo = $this->resolveDiagnosticBillCaseNo($bill, $r->prescription_id ?? null);
                    if ($caseNo === '-') {
                        $caseNo = $fallbackCaseNo;
                    }

                    return [
                        'date' => $bill->date,
                        'case_no' => $caseNo,
                        'type' => 'pathology',
                        'test_name' => $testName,
                        'amount' => (float) ($r->apply_charge ?? 0),
                    ];
                });
            }

            return [[
                'date' => $bill->date,
                'case_no' => $fallbackCaseNo,
                'type' => 'pathology',
                'test_name' => 'Pathology Bill #' . $bill->id,
                'amount' => (float) ($bill->net_amount ?? 0),
            ]];
        })->flatten(1);

        $radiologyBills = $this->radiologyBillsForIpdQuery($ipd, $billStage)
            ->with('prescription')
            ->orderBy('date', 'asc')
            ->get();

        $radiologyRows = $radiologyBills->map(function ($bill) {
            $reports = DB::table('radiology_report')
                ->where('radiology_bill_id', $bill->id)
                ->leftJoin('ipd_prescription_test', 'radiology_report.ipd_prescription_test_id', '=', 'ipd_prescription_test.id')
                ->join('radio', 'radiology_report.radiology_id', '=', 'radio.id')
                ->select(
                    'radio.test_name',
                    'radiology_report.apply_charge',
                    'radiology_report.instance_number',
                    'ipd_prescription_test.instance_number as prescription_instance_number',
                    'ipd_prescription_test.ipd_prescription_id as prescription_id'
                )
                ->get();
            $fallbackCaseNo = $this->resolveDiagnosticBillCaseNo($bill);
            if ($reports->count() > 0) {
                return $reports->map(function ($r) use ($bill, $fallbackCaseNo) {
                    $instanceNumber = $r->prescription_instance_number ?? $r->instance_number ?? null;
                    $testName = $r->test_name ?? 'N/A';
                    if ($instanceNumber && $instanceNumber > 1 && $testName !== 'N/A') {
                        $instanceSuffix = $instanceNumber == 2 ? ' (2nd time)' :
                                         ($instanceNumber == 3 ? ' (3rd time)' : " ({$instanceNumber}th time)");
                        $testName = $r->test_name . $instanceSuffix;
                    }
                    $caseNo = $this->resolveDiagnosticBillCaseNo($bill, $r->prescription_id ?? null);
                    if ($caseNo === '-') {
                        $caseNo = $fallbackCaseNo;
                    }

                    return [
                        'date' => $bill->date,
                        'case_no' => $caseNo,
                        'type' => 'radiology',
                        'test_name' => $testName,
                        'amount' => (float) ($r->apply_charge ?? 0),
                    ];
                });
            }

            return [[
                'date' => $bill->date,
                'case_no' => $fallbackCaseNo,
                'type' => 'radiology',
                'test_name' => 'Radiology Bill #' . $bill->id,
                'amount' => (float) ($bill->net_amount ?? 0),
            ]];
        })->flatten(1);

        return $pathologyRows->merge($radiologyRows)->sortBy('date')->values();
    }

    /**
     * Export Estimate/Breakup Bill PDF
     */
    public function exportEstimate($ipdId, Request $request)
    {
        try {
             $logged_user = auth()->user()->username ?? '';
            $isApprovalBill = in_array($request->query('bill_type'), ['approval', 'approval_preview'], true);
            $isApprovalPreview = $request->query('bill_type') === 'approval_preview'
                || $request->boolean('approval_preview');
            $viewMode = strtolower((string) $request->query('view_mode', 'detailed'));
            if ($isApprovalBill) {
                $viewMode = 'detailed';
            }
            if (!in_array($viewMode, ['brief', 'detailed'], true)) {
                $viewMode = 'detailed';
            }

            \Log::info('exportEstimate started', [
                'ipd_id' => $ipdId,
                'bill_type' => $isApprovalPreview ? 'approval_preview' : ($isApprovalBill ? 'approval' : 'estimate'),
            ]);
            
            $ipd = IpdDetail::with(['patient.organisation', 'doctor', 'bedGroup', 'bedDetail', 'organisation', 'insuranceCompany'])
                ->findOrFail($ipdId);

            if ($isApprovalBill && ! $ipd->isInsuranceBilling()) {
                abort(400, 'Insurance approval bill is only available for insurance / TPA / cashless IPD admissions.');
            }
            
            \Log::info('IPD found', ['ipd_no' => $ipd->ipd_no]);

            $isDischarged = ($ipd->discharged ?? 'no') === 'yes';
            $dischargeAtForBill = $this->resolveDischargeDateTimeForIpd($ipd);
            if (! $dischargeAtForBill && ! empty($ipd->discharged_date)) {
                $dischargeAtForBill = Carbon::parse($ipd->discharged_date)->startOfDay();
            }

            // Approval preview (before discharge): bed charges till now.
            // Approval / discharged estimate / final-aligned: stop at discharge datetime.
            $billingEndAtForEstimate = null;
            if ($isApprovalPreview) {
                $billingEndAtForEstimate = null;
            } elseif ($isApprovalBill || $isDischarged) {
                $billingEndAtForEstimate = $dischargeAtForBill;
            }

            $headerDateLabel = 'Estimate Date';
            $headerTimeLabel = 'Estimate Time';
            $headerDateValue = Carbon::now()->format('d/m/Y');
            $headerTimeValue = Carbon::now()->format('H:i:s');
            if ($isApprovalBill && $isDischarged && $dischargeAtForBill) {
                $headerDateLabel = 'Discharge Date';
                $headerTimeLabel = 'Discharge Time';
                $headerDateValue = $dischargeAtForBill->format('d/m/Y');
                $headerTimeValue = $dischargeAtForBill->format('H:i:s');
            }

            $ipdChargeBillStage = $this->resolveIpdChargeBillStage(
                $isApprovalPreview ? 'approval_preview' : ($isApprovalBill ? 'approval' : null)
            );

            $breakup = $this->calculateBreakup(
                $ipdId,
                $billingEndAtForEstimate ? $billingEndAtForEstimate->format('Y-m-d H:i:s') : null,
                $ipdChargeBillStage
            );
            \Log::info('Breakup calculated', ['total_charges' => $breakup['total_charges']]);

            // Get detailed breakdown - Calculate dynamically from PatientBedHistory (omit from display when package applied)
            $bedChargesData = $this->calculateBedChargesFromHistory(
                $ipdId,
                $billingEndAtForEstimate ? $billingEndAtForEstimate->format('Y-m-d H:i:s') : null
            );
            $bedChargesDetails = collect($bedChargesData['details']);
            $bedChargeDisplayEndCalendar = $billingEndAtForEstimate
                ? Carbon::parse($billingEndAtForEstimate)->format('Y-m-d')
                : Carbon::now()->format('Y-m-d');
            $bedChargesGroupedForDisplay = $this->groupBedChargesByBedForDisplay(
                $bedChargesDetails,
                $bedChargeDisplayEndCalendar
            );
            $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);
            $bedChargesCoveredByPackage = ($breakup['package_charges'] ?? 0) > 0;
            // Estimate & approval: omit bed/GST lines when a package covers bed (same as final bill).
            if ($bedChargesCoveredByPackage) {
                $bedChargesDetails = collect();
                $bedChargesGroupedForDisplay = $this->groupBedChargesByBedForDisplay(
                    $bedChargesDetails,
                    $bedChargeDisplayEndCalendar
                );
                $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);
                $bedChargesDisplayTotal = 0;
            } else {
                $bedChargesDisplayTotal = $bedChargesGroupedForDisplay->sum('bed_charge');
            }

            $ipdChargesDetails = $this->ipdChargesBaseQuery($ipdId, $ipdChargeBillStage)
                ->with(['charge', 'chargeCategory'])
                ->orderBy('date', 'asc')
                ->get() ?? collect();

            // Pathology Details - Get all tests with names
            \Log::info('Getting pathology details');
            $pathologyTestNames = [];
            $pathologyTotal = 0;
            
            $pathologyDetails = $this->pathologyBillsForIpdQuery($ipd, $ipdChargeBillStage)
                ->orderBy('date', 'asc')
                ->get();
            
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
            
            $radiologyDetails = $this->radiologyBillsForIpdQuery($ipd, $ipdChargeBillStage)
                ->orderBy('date', 'asc')
                ->get();
            
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

            $doctorVisitDetails = $this->doctorVisitsBillableToIpdQuery($ipd, null)
                ->with(['doctor', 'charge'])
                ->orderByRaw('COALESCE(visit_date, DATE(reporting_date), DATE(created_at)) ASC')
                ->orderBy('id')
                ->get();
            // Group doctor visits for PDF (OT Anesthesia/Surgeon split to separate section)
            $doctorVisitBillDisplay = $this->prepareDoctorVisitDisplayForBillPdf($doctorVisitDetails);
            $doctorVisitGroupedForDisplay = $doctorVisitBillDisplay['doctorVisitGroupedForDisplay'];
            $doctorVisitGroupedByVisitType = $doctorVisitBillDisplay['doctorVisitGroupedByVisitType'];
            $otDoctorChargeRows = $doctorVisitBillDisplay['otDoctorChargeRows'];
            $doctorVisitChargesDisplaySubtotal = $doctorVisitBillDisplay['doctorVisitChargesDisplaySubtotal'];
            $otDoctorChargesDisplaySubtotal = $doctorVisitBillDisplay['otDoctorChargesDisplaySubtotal'];

            // Package details (applied packages) for estimate / approval PDF
            $packageDetails = $this->buildPackageDetailsForBillDisplay($ipdId);
            $showPackageProcedureColumn = $packageDetails->count() > 1;
            $showOriginalAmount = $isApprovalBill && $request->boolean('show_original_amount');
            
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
            $investigationDatewise = $this->getPathologyRadiologyDatewise($ipd, $ipdChargeBillStage);
            $investigationBrief = $this->buildInvestigationBriefSummary($pathologyDetails, $radiologyDetails, (float) $pathologyTotal, (float) $radiologyTotal);

            \Log::info('Loading PDF view', [
                'pathology_tests' => count($pathologyTestNames),
                'radiology_tests' => count($radiologyTestNames)
            ]);
            
            // Get hospital information
            $hospital = Hospital::first();

            $billHeading = $isApprovalBill
                ? ($isApprovalPreview ? 'FINAL BILL (FOR APPROVAL) — PREVIEW' : 'FINAL BILL (FOR APPROVAL)')
                : 'ESTIMATE COPY';
            $showInsuranceSection = $isApprovalBill;
            $showDischargeOnBill = $isApprovalBill && $isDischarged && $dischargeAtForBill;
            $dischargeDateDisplay = $showDischargeOnBill ? $dischargeAtForBill->format('d/m/Y') : null;
            $dischargeTimeDisplay = $showDischargeOnBill ? $dischargeAtForBill->format('h:i A') : null;
            $grandTotal = (float) ($breakup['total_charges'] ?? 0);
            $totalAdvance = (float) ($breakup['total_payments'] ?? 0);
            $mouDiscountAmount = (float) ($ipd->mou_discount ?? 0);
            $initialApprovalAmount = (float) ($ipd->initial_approval_amount ?? 0);
            $requestFurtherApproval = max(0, $grandTotal - $mouDiscountAmount - $initialApprovalAmount);
            $requestFurtherApprovalInWords = 'Zero Rupees Only';
            try {
                if (class_exists(\App\Helpers\NumberToWords::class)) {
                    $requestFurtherApprovalInWords = \App\Helpers\NumberToWords::convert($requestFurtherApproval);
                }
            } catch (\Throwable $e) {
                \Log::warning('Could not convert request further approval to words: ' . $e->getMessage());
            }
                    
            
            // First pass: Render to get accurate page count
            $tempPdf = Pdf::loadView('admin.billing.ipd_estimate_pdf', compact(
                'ipd', 'breakup', 'bedChargesDetails', 'bedChargesGroupedForDisplay', 'ipdChargesDetails',
                'bedChargesDisplayTotal', 'bedChargesCoveredByPackage',
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails', 'doctorVisitGroupedForDisplay', 'doctorVisitGroupedByVisitType',
                'otDoctorChargeRows', 'doctorVisitChargesDisplaySubtotal', 'otDoctorChargesDisplaySubtotal',
                'packageDetails', 'payments',
                'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
                'investigationDatewise', 'investigationBrief', 'viewMode',
                'totalChargesInWords', 'totalPaymentsInWords', 'outstandingInWords', 'netBalanceInWords',
                'hospital', 'logged_user', 'billHeading', 'isApprovalBill', 'isApprovalPreview', 'showInsuranceSection',
                'showOriginalAmount', 'showPackageProcedureColumn',
                'headerDateLabel', 'headerTimeLabel', 'headerDateValue', 'headerTimeValue',
                'showDischargeOnBill', 'dischargeDateDisplay', 'dischargeTimeDisplay',
                'grandTotal', 'totalAdvance', 'mouDiscountAmount', 'initialApprovalAmount',
                'requestFurtherApproval', 'requestFurtherApprovalInWords'
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
                'bedChargesDisplayTotal', 'bedChargesCoveredByPackage',
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails', 'doctorVisitGroupedForDisplay', 'doctorVisitGroupedByVisitType',
                'otDoctorChargeRows', 'doctorVisitChargesDisplaySubtotal', 'otDoctorChargesDisplaySubtotal',
                'packageDetails', 'payments',
                'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
                'investigationDatewise', 'investigationBrief', 'viewMode',
                'totalChargesInWords', 'totalPaymentsInWords', 'outstandingInWords', 'netBalanceInWords',
                'hospital', 'totalPages', 'gstChargesGrouped', 'logged_user',
                'billHeading', 'isApprovalBill', 'isApprovalPreview', 'showInsuranceSection',
                'showOriginalAmount', 'showPackageProcedureColumn',
                'headerDateLabel', 'headerTimeLabel', 'headerDateValue', 'headerTimeValue',
                'showDischargeOnBill', 'dischargeDateDisplay', 'dischargeTimeDisplay',
                'grandTotal', 'totalAdvance', 'mouDiscountAmount', 'initialApprovalAmount',
                'requestFurtherApproval', 'requestFurtherApprovalInWords'
            ));
            
            // Enable PHP scripts for page numbering
             $pdf->setOption('enable-php', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setPaper('a4', 'portrait');
            
            \Log::info('PDF generated, returning inline stream');

            $filename = $isApprovalBill
                ? ($isApprovalPreview
                    ? 'IPD_Approval_Bill_Preview_' . $ipd->ipd_no . '.pdf'
                    : 'IPD_Approval_Bill_' . $ipd->ipd_no . '.pdf')
                : 'IPD_Estimate_Bill_' . $ipd->ipd_no . '.pdf';

            return $pdf->stream($filename);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            throw $e;
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
     * Export Insurance Approval Bill PDF after discharge (bed charges stop at discharge datetime).
     */
    public function exportApprovalBill($ipdId, Request $request)
    {
        $ipd = IpdDetail::findOrFail($ipdId);

        if (! $ipd->isInsuranceBilling()) {
            abort(400, 'This IPD has no TPA / insurance details saved. Open Edit IPD, complete the TPA & Insurance section, save, then export the approval bill again.');
        }

        if (($ipd->discharged ?? 'no') !== 'yes') {
            abort(400, 'Export Approval Bill is available only after discharge. Use Export Approval Bill Preview before discharge.');
        }

        if ($ipd->isFinalBillGenerated()) {
            abort(400, 'Final bill has already been generated. Approval bill export is locked.');
        }

        $request->query->set('bill_type', 'approval');
        $request->query->set('view_mode', 'detailed');

        return $this->exportEstimate($ipdId, $request);
    }

    /**
     * Preview Insurance Approval Bill before discharge (bed charges till now; bed stays occupied).
     */
    public function exportApprovalBillPreview($ipdId, Request $request)
    {
        $ipd = IpdDetail::findOrFail($ipdId);

        if (! $ipd->isInsuranceBilling()) {
            abort(400, 'Insurance approval bill preview is only available for insurance / TPA / cashless IPD admissions.');
        }

        if (($ipd->discharged ?? 'no') === 'yes') {
            abort(400, 'Patient is already discharged. Use Export Approval Bill instead of preview.');
        }

        if ($ipd->isFinalBillGenerated()) {
            abort(400, 'Final bill has already been generated for this IPD.');
        }

        $request->query->set('bill_type', 'approval_preview');
        $request->query->set('view_mode', 'detailed');

        return $this->exportEstimate($ipdId, $request);
    }

    /**
     * Button / eligibility state for approval preview vs approval export.
     */
    public function checkApprovalBill($ipdId)
    {
        try {
            $ipd = IpdDetail::with('patient.organisation')->findOrFail($ipdId);
            $isInsurance = $ipd->isInsuranceBilling();
            $isDischarged = ($ipd->discharged ?? 'no') === 'yes';
            $finalGenerated = $ipd->isFinalBillGenerated();

            $message = 'Approval bill can be exported.';
            if (! $isInsurance) {
                if ($ipd->patient && $ipd->patient->organisation_id) {
                    $message = 'TPA is set on the patient profile (' . ($ipd->patient->organisation->organisation_name ?? 'patient TPA') . ') but not saved on this IPD admission. Open Edit IPD → TPA & Insurance, confirm details, click Save, then export again.';
                } else {
                    $message = 'Save TPA & Insurance on this IPD (Edit IPD → TPA & Insurance → Save) before exporting the approval bill.';
                }
            } elseif ($finalGenerated) {
                $message = 'Final bill already generated. Approval bill buttons are locked.';
            } elseif ($isDischarged) {
                $message = 'Patient discharged. Export Approval Bill is available.';
            } else {
                $message = 'Before discharge, use Export Approval Bill Preview (charges till now).';
            }

            return response()->json([
                'allowed' => $isInsurance,
                'is_insurance' => $isInsurance,
                'discharged' => $isDischarged,
                'final_bill_generated' => $finalGenerated,
                'can_preview_approval' => $isInsurance && ! $isDischarged && ! $finalGenerated,
                'can_export_approval' => $isInsurance && $isDischarged && ! $finalGenerated,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'allowed' => false,
                'is_insurance' => false,
                'discharged' => false,
                'final_bill_generated' => false,
                'can_preview_approval' => false,
                'can_export_approval' => false,
                'message' => 'Error checking approval bill: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Build compact investigation summary for brief estimate mode.
     */
    private function buildInvestigationBriefSummary($pathologyDetails, $radiologyDetails, float $pathologyTotal, float $radiologyTotal): array
    {
        $billNumbers = collect();

        $pathologyBillNumbers = collect($pathologyDetails)->map(function ($bill) {
            if (!empty($bill->bill_no)) {
                return (string) $bill->bill_no;
            }
            if (!empty($bill->bill_number)) {
                return (string) $bill->bill_number;
            }
            if (!empty($bill->billing_no)) {
                return (string) $bill->billing_no;
            }
            return isset($bill->id) ? ('PATB' . str_pad((string) $bill->id, 2, '0', STR_PAD_LEFT)) : null;
        })->filter();

        $radiologyBillNumbers = collect($radiologyDetails)->map(function ($bill) {
            if (!empty($bill->bill_no)) {
                return (string) $bill->bill_no;
            }
            if (!empty($bill->bill_number)) {
                return (string) $bill->bill_number;
            }
            if (!empty($bill->billing_no)) {
                return (string) $bill->billing_no;
            }
            return isset($bill->id) ? ('RADB' . str_pad((string) $bill->id, 2, '0', STR_PAD_LEFT)) : null;
        })->filter();

        $billNumbers = $pathologyBillNumbers
            ->merge($radiologyBillNumbers)
            ->unique()
            ->values();

        $grossTotal = round($pathologyTotal + $radiologyTotal, 2);
        // Keep existing behavior unless diagnostic-advance logic is introduced explicitly.
        $receivedInDiagnosis = 0.00;
        $netTotal = max(0, round($grossTotal - $receivedInDiagnosis, 2));

        return [
            'bill_numbers_text' => $billNumbers->implode(', '),
            'gross_total' => $grossTotal,
            'received_in_diagnosis' => $receivedInDiagnosis,
            'net_total' => $netTotal,
        ];
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
                'message' => $message,
                'is_insurance' => $ipd->isInsuranceBilling(),
                'final_approval_amount' => (float) ($ipd->final_approval_amount ?? 0),
                'final_bill_generated' => $ipd->isFinalBillGenerated(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'discharged' => false,
                'message' => 'Error checking discharge status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Status payload before Generate Final Bill (no extra-bed prompt).
     */
    public function previewFinalBill($ipdId, IpdFinalBillService $finalBillService)
    {
        try {
            $ipd = IpdDetail::findOrFail($ipdId);
            $preview = $finalBillService->preview($ipd);

            return response()->json([
                'success' => true,
                'data' => $preview,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('Final bill preview failed', ['ipd_id' => $ipdId, 'message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to prepare final bill status. Please try again.',
            ], 500);
        }
    }

    /**
     * Generate (lock) the final bill and release the bed at discharge datetime.
     */
    public function generateFinalBill(Request $request, $ipdId, IpdFinalBillService $finalBillService)
    {
        try {
            $ipd = IpdDetail::findOrFail($ipdId);

            if ($finalBillService->isGenerated($ipd)) {
                return response()->json([
                    'success' => true,
                    'already_generated' => true,
                    'pdf_url' => url('ipd/billing/' . $ipdId . '/export-final?bill_stage=final_bill'),
                    'message' => 'Final bill already generated.',
                ]);
            }

            $finalBillService->generate($ipd);

            return response()->json([
                'success' => true,
                'already_generated' => false,
                'pdf_url' => url('ipd/billing/' . $ipdId . '/export-final?bill_stage=final_bill'),
                'message' => 'Final bill generated and bed released.',
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('Generate final bill failed', [
                'ipd_id' => $ipdId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to generate the final bill. Please try again.',
            ], 500);
        }
    }

    /**
     * Export Final Bill PDF
     */
    public function exportFinal($ipdId, Request $request)
    {
        try {
                $logged_user = auth()->user()->username ?? ''; 

            \Log::info('exportFinal started', ['ipd_id' => $ipdId]);
            
            $finalBillStage = $request->query('bill_stage', IpdCharges::STAGE_FINAL_BILL);
            if (! in_array($finalBillStage, [IpdCharges::STAGE_FINAL_PREVIEW, IpdCharges::STAGE_FINAL_BILL], true)) {
                $finalBillStage = IpdCharges::STAGE_FINAL_BILL;
            }
            $ipdChargeBillStage = $this->resolveIpdChargeBillStage(null, $finalBillStage);
            
            $ipd = IpdDetail::with([
                'patient.organisation',
                'doctor',
                'bedGroup',
                'bedDetail',
                'duePatientPartyDoctor',
                'organisation',
                'insuranceCompany',
            ])->findOrFail($ipdId);
            
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
            $billingEndAt = app(IpdFinalBillService::class)->billingEndAt($ipd)->format('Y-m-d H:i:s');
            
            \Log::info('Discharge information retrieved', [
                'discharge_date' => $dischargeDate,
                'discharge_time' => $dischargeTime,
                'billing_end_at' => $billingEndAt,
            ]);

            \Log::info('Calculating breakup');
            $breakup = $this->calculateBreakup($ipdId, $billingEndAt, $ipdChargeBillStage);
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
            $bedChargeDisplayEndCalendar = Carbon::parse($dischargeDate)->format('Y-m-d');
            $bedChargesGroupedForDisplay = $this->groupBedChargesByBedForDisplay(
                $bedChargesDetails,
                $bedChargeDisplayEndCalendar
            );
            $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);
            if (($breakup['package_charges'] ?? 0) > 0) {
                $bedChargesDetails = collect();
                $bedChargesGroupedForDisplay = $this->groupBedChargesByBedForDisplay(
                    $bedChargesDetails,
                    $bedChargeDisplayEndCalendar
                );
                $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);
            }

            \Log::info('Getting IPD charges details');
            $ipdChargesDetails = $this->ipdChargesBaseQuery($ipdId, $ipdChargeBillStage)
                ->with(['charge', 'chargeCategory'])
                ->orderBy('date', 'asc')
                ->get() ?? collect();

            // Pathology Details - Get all tests with names (same logic as estimate)
            \Log::info('Getting pathology details');
            $pathologyDetails = $this->pathologyBillsForIpdQuery($ipd, $ipdChargeBillStage)
                ->orderBy('date', 'asc')
                ->get() ?? collect();

            // Radiology Details - Get all tests with names (same logic as estimate)
            \Log::info('Getting radiology details');
            $radiologyDetails = $this->radiologyBillsForIpdQuery($ipd, $ipdChargeBillStage)
                ->orderBy('date', 'asc')
                ->get() ?? collect();

            \Log::info('Getting doctor visit details');
            $doctorVisitDetails = $this->doctorVisitsBillableToIpdQuery($ipd, $dischargeDate)
                ->with(['doctor', 'charge'])
                ->orderByRaw('COALESCE(visit_date, DATE(reporting_date), DATE(created_at)) ASC')
                ->orderBy('id')
                ->get();
            // Group doctor visits for PDF (OT Anesthesia/Surgeon split to separate section)
            $doctorVisitBillDisplay = $this->prepareDoctorVisitDisplayForBillPdf($doctorVisitDetails);
            $doctorVisitGroupedForDisplay = $doctorVisitBillDisplay['doctorVisitGroupedForDisplay'];
            $doctorVisitGroupedByVisitType = $doctorVisitBillDisplay['doctorVisitGroupedByVisitType'];
            $otDoctorChargeRows = $doctorVisitBillDisplay['otDoctorChargeRows'];
            $doctorVisitChargesDisplaySubtotal = $doctorVisitBillDisplay['doctorVisitChargesDisplaySubtotal'];
            $otDoctorChargesDisplaySubtotal = $doctorVisitBillDisplay['otDoctorChargesDisplaySubtotal'];

            // Package details (applied packages) for final bill PDF
            $packageDetails = $this->buildPackageDetailsForBillDisplay($ipdId);
            $showPackageProcedureColumn = $packageDetails->count() > 1;
            $showOriginalAmount = false;

            $isInsuranceFinalBill = $ipd->isInsuranceBilling();
            $hasPackageCharges = $packageDetails->count() > 0;
            $insuranceSummaryService = app(InsuranceFinalBillSummaryService::class);

            // Package insurance final: show package + medicines/implants excluded from package
            $excludedMedicineImplantCharges = collect();
            $ipdChargesForDisplay = $ipdChargesDetails;
            $ipdChargesDisplaySubtotal = (float) ($breakup['ipd_charges'] ?? 0);
            if ($isInsuranceFinalBill && $hasPackageCharges) {
                $excludedMedicineImplantCharges = $ipdChargesDetails
                    ->filter(fn ($charge) => $insuranceSummaryService->isMedicineOrImplantCharge($charge))
                    ->values();
                $ipdChargesForDisplay = $ipdChargesDetails
                    ->filter(fn ($charge) => ! $insuranceSummaryService->isMedicineOrImplantCharge($charge))
                    ->values();
                $ipdChargesDisplaySubtotal = round((float) $ipdChargesForDisplay->sum('net_amount'), 2);
            }

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

            // Calculate balance (cash / internal final bill)
            $grandTotal = $breakup['total_charges'];
            $totalAdvance = $breakup['total_payments'];
            $balance = $grandTotal - $totalAdvance - $discount - $duePatientPartyAmount;
            $balance = max(0, $balance);

            // Insurance final settlement (manual Approval Amount after insurer response)
            $insuranceFinalSummary = null;
            $dueOnAccountInWords = 'Zero Rupees Only';
            $useInsurancePackageLayout = false;
            if ($isInsuranceFinalBill) {
                $summaryBreakup = $breakup;
                if ($hasPackageCharges) {
                    $useInsurancePackageLayout = true;
                }
                $insuranceFinalSummary = $insuranceSummaryService->build($ipd, $summaryBreakup, $payments);
                try {
                    if (class_exists(\App\Helpers\NumberToWords::class)) {
                        $dueOnAccountInWords = \App\Helpers\NumberToWords::convert($insuranceFinalSummary['due_on_account']);
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Could not convert due-on-account to words: ' . $e->getMessage());
                }
            }

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
            $investigationDatewise = $this->getPathologyRadiologyDatewise($ipd, $ipdChargeBillStage);

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
                'otDoctorChargeRows', 'doctorVisitChargesDisplaySubtotal', 'otDoctorChargesDisplaySubtotal',
                'gstChargesGrouped', 'logged_user', 'showOriginalAmount', 'showPackageProcedureColumn',
                'isInsuranceFinalBill', 'useInsurancePackageLayout', 'excludedMedicineImplantCharges',
                'ipdChargesForDisplay', 'ipdChargesDisplaySubtotal',
                'insuranceFinalSummary', 'dueOnAccountInWords'
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

    /**
     * API: Get all discharged patients with zero net balance
     * Returns list of patients who have been discharged and have Net Balance (Due) = 0
     */
    public function getDischargedPatientsWithZeroBalance(Request $request)
    {
        try {
            $perPage = intval($request->input('perPage', 50));
            $page = intval($request->input('page', 1));
            $sortBy = $request->get('sort_by', 'discharged_date');
            $sortOrder = $request->get('sort_order', 'desc');
            $searchTerm = $request->get('search', '');

            if ($perPage <= 0) {
                $perPage = 50;
            }
            if ($page <= 0) {
                $page = 1;
            }
            if (!in_array($sortOrder, ['asc', 'desc'])) {
                $sortOrder = 'desc';
            }

            // Get all discharged IPDs with related data
            $ipdDetails = IpdDetail::with(['patient', 'doctor', 'organisation'])
                ->where('discharged', 'yes')
                ->orderBy($sortBy, $sortOrder)
                ->get();

            $patientsWithZeroBalance = [];

            foreach ($ipdDetails as $ipd) {
                try {
                    // Calculate breakup
                    $endDate = $this->resolveBillingEndDateForIpd($ipd);
                    $billStage = !empty($ipd->final_bill_generated_at) ? IpdCharges::STAGE_FINAL_BILL : null;
                    $breakup = $this->calculateBreakup($ipd->id, $endDate, $billStage);

                    // Calculate net balance
                    $totalDiscount = (float) ($ipd->mou_discount ?? 0) + (float) ($ipd->special_discount ?? 0);
                    $duePatientPartyAmount = (float) ($ipd->due_patient_party_amount ?? 0);
                    $outstanding = (float) ($breakup['outstanding'] ?? 0);
                    $netBalance = max(0, $outstanding - $totalDiscount - $duePatientPartyAmount);

                    $patient = $ipd->patient;
                        
                        // Apply search filter if provided
                        if (!empty($searchTerm)) {
                            $ipdNo = $ipd->ipd_no ?? '';
                            $patientName = $patient ? ($patient->patient_name ?? '') : '';
                            
                            // Check if search term matches IPD No or Patient Name
                            if (stripos($ipdNo, $searchTerm) === false && stripos($patientName, $searchTerm) === false) {
                                continue;
                            }
                        }
                        
                    $patientsWithZeroBalance[] = [
                            'ipd_id' => $ipd->id,
                            'ipd_no' => $ipd->ipd_no ?? 'N/A',
                            'admission_date' => $ipd->date ?? null,
                            'discharge_date' => $ipd->discharged_date ?? null,
                            'patient' => [
                                'id' => $patient ? $patient->id : null,
                                'patient_name' => $patient ? ($patient->patient_name ?? 'N/A') : 'N/A',
                                'patient_id_no' => $patient ? ($patient->patient_id_no ?? '') : '',
                                'phone' => $patient ? ($patient->mobileno ?? '') : '',
                                'email' => $patient ? ($patient->email ?? '') : '',
                                'age' => $patient ? ($patient->age ?? '') : '',
                                'gender' => $patient ? ($patient->gender ?? '') : '',
                            ],
                            'doctor' => [
                                'id' => $ipd->doctor ? $ipd->doctor->id : null,
                                'name' => $ipd->doctor ? ($ipd->doctor->name ?? '') : '',
                                'surname' => $ipd->doctor ? ($ipd->doctor->surname ?? '') : '',
                            ],
                            'organisation' => [
                                'id' => $ipd->organisation ? $ipd->organisation->id : null,
                                'name' => $ipd->organisation ? ($ipd->organisation->organisation_name ?? '') : '',
                            ],
                            'billing' => [
                                'total_charges' => round($breakup['total_charges'] ?? 0, 2),
                                'total_payments' => round($breakup['total_payments'] ?? 0, 2),
                                'gross_outstanding' => round($outstanding, 2),
                                'outstanding' => round($netBalance, 2),
                                'total_discount' => round($totalDiscount, 2),
                                'due_patient_party_amount' => round($duePatientPartyAmount, 2),
                                'net_balance' => round($netBalance, 2),
                            ],
                            'breakup' => [
                                'bed_charges' => round($breakup['bed_charges'] ?? 0, 2),
                                'ipd_charges' => round($breakup['ipd_charges'] ?? 0, 2),
                                'pathology_charges' => round($breakup['pathology_charges'] ?? 0, 2),
                                'radiology_charges' => round($breakup['radiology_charges'] ?? 0, 2),
                                'doctor_visit_charges' => round($breakup['doctor_visit_charges'] ?? 0, 2),
                                'package_charges' => round($breakup['package_charges'] ?? 0, 2),
                                'cgst_charges' => round($breakup['cgst_charges'] ?? 0, 2),
                                'sgst_charges' => round($breakup['sgst_charges'] ?? 0, 2),
                            ],
                    ];
                } catch (\Exception $e) {
                    \Log::warning('Error calculating balance for IPD ' . $ipd->id . ': ' . $e->getMessage());
                    continue;
                }
            }

            // Apply pagination to filtered results
            $totalCount = count($patientsWithZeroBalance);
            $paginatedData = array_slice($patientsWithZeroBalance, ($page - 1) * $perPage, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Discharged patients with zero balance retrieved successfully',
                'pagination' => [
                    'total_zero_balance' => $totalCount,
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'pages' => ceil($totalCount / $perPage),
                ],
                'data' => $paginatedData,
            ]);
        } catch (\Exception $e) {
            \Log::error('Get discharged patients with zero balance failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error while fetching discharged patients with zero balance: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display view for discharged patients with zero balance
     */
    public function showDischargedPatientsWithZeroBalance(Request $request)
    {
        try {
            $perPage = (int) $request->get('per_page', 10);
            $page = (int) $request->get('page', 1);
            $sortBy = $request->get('sort_by', 'discharged_date');
            $sortOrder = $request->get('sort_order', 'desc');

            // Validate pagination
            if ($perPage <= 0) $perPage = 10;
            if ($page <= 0) $page = 1;
            if (!in_array($sortOrder, ['asc', 'desc'])) $sortOrder = 'desc';

            // Get all discharged IPDs with related data
            $query = IpdDetail::with(['patient', 'doctor', 'organisation', 'bedDetail', 'bedGroup'])
                ->where('discharged', 'yes')
                ->orderBy($sortBy, $sortOrder);

            $totalCount = $query->count();
            $ipdDetails = $query->get();

            $patientsWithZeroBalance = [];

            foreach ($ipdDetails as $ipd) {
                try {
                    // Calculate breakup
                    $endDate = $this->resolveBillingEndDateForIpd($ipd);
                    $billStage = !empty($ipd->final_bill_generated_at) ? IpdCharges::STAGE_FINAL_BILL : null;
                    $breakup = $this->calculateBreakup($ipd->id, $endDate, $billStage);

                    // Calculate net balance
                    $totalDiscount = (float) ($ipd->mou_discount ?? 0) + (float) ($ipd->special_discount ?? 0);
                    $duePatientPartyAmount = (float) ($ipd->due_patient_party_amount ?? 0);
                    $outstanding = (float) ($breakup['outstanding'] ?? 0);
                    $netBalance = max(0, $outstanding - $totalDiscount - $duePatientPartyAmount);

                    $patient = $ipd->patient;
                    $patientsWithZeroBalance[] = [
                            'ipd_id' => $ipd->id,
                            'ipd_no' => $ipd->ipd_no ?? 'N/A',
                            'admission_date' => $ipd->date ?? null,
                            'discharge_date' => $ipd->discharged_date ?? null,
                            'patient_name' => $patient ? ($patient->patient_name ?? 'N/A') : 'N/A',
                            'guardian_phone' => $patient ? ($patient->guardian_phone ?? '-') : '-',
                            'phone' => $patient ? ($patient->mobileno ?? '-') : '-',
                            'consultant_name' => $ipd->doctor ? ($ipd->doctor->name ?? '') : '-',
                            'bed_info' => ($ipd->bedDetail?->name ?? '-') . ' - ' . ($ipd->bedGroup?->name ?? '-'),
                            'total_charges' => round($breakup['total_charges'] ?? 0, 2),
                            'total_payments' => round($breakup['total_payments'] ?? 0, 2),
                            'due_patient_party_amount' => round($duePatientPartyAmount, 2),
                            'gross_outstanding' => round($outstanding, 2),
                            'outstanding' => round($netBalance, 2),
                            'net_balance' => round($netBalance, 2),
                    ];
                } catch (\Exception $e) {
                    \Log::warning('Error calculating balance for IPD ' . $ipd->id . ': ' . $e->getMessage());
                    continue;
                }
            }

            $totalFiltered = count($patientsWithZeroBalance);
            $patientsWithZeroBalance = array_slice(
                $patientsWithZeroBalance,
                ($page - 1) * $perPage,
                $perPage
            );

            return view('admin.ipd.discharged-zero-balance', [
                'patients' => $patientsWithZeroBalance,
                'pagination' => [
                    'total_all_discharged' => $totalCount,
                    'total_zero_balance' => $totalFiltered,
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total_pages' => ceil($totalFiltered / $perPage),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Show discharged patients view failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Error while loading discharged patients with zero balance: ' . $e->getMessage());
        }
    }
}
