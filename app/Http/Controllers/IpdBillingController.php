<?php

namespace App\Http\Controllers;

use App\Models\IpdDetail;
use App\Models\IpdDaywiseBedCharge;
use App\Models\IpdCharges;
use App\Models\PathologyBilling;
use App\Models\RadiologyBilling;
use App\Models\Transaction;
use App\Models\DoctorVisit;
use App\Models\Hospital;
use App\Models\DischargeCard;
use App\Models\PatientBedHistory;
use App\Models\BedGroup;
use App\Models\Doctor;
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
        $search = $request->get('search', '');
        
        // Include all IPD patients (both discharged and non-discharged) for billing purposes
        $query = IpdDetail::with(['patient', 'doctor']);

        // Apply search filter if provided
        if (!empty($search)) {
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
            
            return [
                'id' => $ipd->id,
                'ipd_no' => $ipd->ipd_no ?? 'N/A',
                'patient_name' => $patient->patient_name ?? 'N/A',
                'phone' => $patient->mobileno ?? '',
                'discharged' => $isDischarged,
                'discharged_date' => $ipd->discharged_date,
                'display_text' => ($ipd->ipd_no ?? 'N/A') . ' - ' . ($patient->patient_name ?? 'N/A') . $dischargeStatus,
            ];
        });

        return response()->json([
            'data' => $data,
            'success' => true
        ]);
    }

    /**
     * Get breakup bill for IPD patient
     */
    public function breakup($ipdId)
    {
        $ipd = IpdDetail::with(['patient', 'doctor', 'bedGroup', 'bedDetail', 'duePatientPartyDoctor'])
            ->findOrFail($ipdId);

        $doctors = Doctor::orderBy('name')->get(['id', 'name', 'surname']);

        // Calculate all charges
        $breakup = $this->calculateBreakup($ipdId);

        // Discount and due patient party for display (final bill logic)
        $totalDiscount = (float) ($ipd->mou_discount ?? 0) + (float) ($ipd->special_discount ?? 0);
        $duePatientPartyAmount = (float) ($ipd->due_patient_party_amount ?? 0);
        $outstanding = $breakup['outstanding'] ?? 0;
        $netBalance = max(0, $outstanding - $totalDiscount - $duePatientPartyAmount);
        $breakup['total_discount'] = $totalDiscount;
        $breakup['due_patient_party_amount'] = $duePatientPartyAmount;
        $breakup['net_balance'] = $netBalance;

        // Get detailed date-wise breakdown
        $detailedBreakup = $this->getDetailedBreakup($ipdId, $ipd);

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
        $breakup = $this->calculateBreakup($ipdId);
        $outstanding = $breakup['outstanding'] ?? 0;
        $netBalance = max(0, $outstanding - $totalDiscount - $duePatientPartyAmount);

        return response()->json([
            'success' => true,
            'message' => 'Discount saved. It will apply to the final bill only.',
            'mou_discount' => $ipd->mou_discount,
            'special_discount' => $ipd->special_discount,
            'total_discount' => $totalDiscount,
            'due_patient_party_amount' => $duePatientPartyAmount,
            'outstanding' => $outstanding,
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
        $breakup = $this->calculateBreakup($ipdId);
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

        $admissionDate = Carbon::parse($ipd->date);
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();
        
        // Get all bed history records for this IPD patient (including inactive ones for historical calculation)
        $bedHistories = PatientBedHistory::where('ipd_id', $ipdId)
            ->with(['bedGroup', 'bed'])
            ->orderBy('from_date', 'asc')
            ->get();

        if ($bedHistories->isEmpty()) {
            return ['total' => 0, 'details' => []];
        }

        $totalCharges = 0;
        $totalCgst = 0;
        $totalSgst = 0;
        $details = [];
        $currentDate = $admissionDate->copy();

        // Calculate charges for each day from admission to end date
        while ($currentDate->lte($endDate)) {
            $chargeDate = $currentDate->format('Y-m-d');
            
            // Calculate period: 10 AM previous day to 10 AM current day
            $periodStart = $currentDate->copy()->subDay()->setTime(10, 0, 0);
            $periodEnd = $currentDate->copy()->setTime(10, 0, 0);
            
            // Find which bed was assigned during this period
            $activeBed = null;
            foreach ($bedHistories as $history) {
                $fromDate = Carbon::parse($history->from_date);
                $toDate = $history->to_date ? Carbon::parse($history->to_date) : null;
                
                // Check if this bed history was active during the period
                if ($fromDate->lte($periodEnd) && (!$toDate || $toDate->gte($periodStart))) {
                    // If multiple beds, use the most recent one
                    if (!$activeBed || $fromDate->gt(Carbon::parse($activeBed->from_date))) {
                        $activeBed = $history;
                    }
                }
            }
            
            if ($activeBed && $activeBed->bedGroup) {
                $bedCost = $activeBed->bedGroup->bed_cost ?? 0;
                $totalCharges += $bedCost;
                
                // Calculate GST if bed charge > 5000
                $gstRate = 0;
                $cgstAmount = 0;
                $sgstAmount = 0;
                $sacHsnCode = null;
                
                if ($bedCost > 5000 && $activeBed->bedGroup->gst_rate) {
                    $gstRate = $activeBed->bedGroup->gst_rate;
                    $sacHsnCode = $activeBed->bedGroup->sac_hsn_code;
                    
                    // Calculate GST: CGST and SGST are each half of the total GST rate
                    $totalGstAmount = ($bedCost * $gstRate) / 100;
                    $cgstAmount = $totalGstAmount / 2; // CGST is half
                    $sgstAmount = $totalGstAmount / 2; // SGST is half
                    
                    $totalCgst += $cgstAmount;
                    $totalSgst += $sgstAmount;
                }
                
                // Create object compatible with IpdDaywiseBedCharge structure
                $detail = new \stdClass();
                $detail->charge_date = $chargeDate;
                $detail->period_start_date = $periodStart->format('Y-m-d');
                $detail->period_end_date = $periodEnd->format('Y-m-d');
                $detail->bed_group_id = $activeBed->bed_group_id;
                $detail->bed_id = $activeBed->bed_id;
                $detail->bed_charge = $bedCost;
                $detail->bed_charge_rate = $bedCost;
                $detail->no_of_days = 1;
                $detail->bedGroup = $activeBed->bedGroup;
                $detail->bed = $activeBed->bed;
                $detail->gst_rate = $gstRate;
                $detail->cgst_amount = $cgstAmount;
                $detail->sgst_amount = $sgstAmount;
                $detail->sac_hsn_code = $sacHsnCode;
                
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
                'total_charges' => 0,
                'total_payments' => 0,
                'outstanding' => 0,
            ];
        }

        // Bed Charges - Calculate dynamically from PatientBedHistory
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

        // Get IPD patient_id and admission date
        $patientId = $ipd->patient_id ?? null;
        $admissionDate = $ipd->date ?? null;

        // Doctor Visit Charges (for this patient after admission)
        $doctorVisitCharges = 0;
        if ($patientId && $admissionDate) {
            $doctorVisitCharges = DoctorVisit::where('patient_id', $patientId)
                ->where('visit_date', '>=', $admissionDate)
                ->sum('amount');
        }

        // Total Charges (including GST)
        $totalCharges = $bedCharges + $ipdCharges + $pathologyCharges + $radiologyCharges + $doctorVisitCharges + $cgstCharges + $sgstCharges;

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
            'cgst_charges' => $cgstCharges,
            'sgst_charges' => $sgstCharges,
            'total_charges' => $totalCharges,
            'total_payments' => $totalPayments,
            'outstanding' => $outstanding,
        ];
    }

    /**
     * Get detailed date-wise breakdown
     */
    private function getDetailedBreakup($ipdId, $ipd)
    {
        $admissionDate = $ipd->date;
        $patientId = $ipd->patient_id;
        $caseReferenceId = $ipd->case_reference_id;
        
        // Bed Charges Details - Calculate dynamically from PatientBedHistory
        $bedChargesData = $this->calculateBedChargesFromHistory($ipdId);
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
        
        // Get pathology test details from reports
        $pathologyDetailsWithTests = $pathologyDetails->map(function($bill) {
            $reports = DB::table('pathology_report')
                ->where('pathology_bill_id', $bill->id)
                ->join('pathology', 'pathology_report.pathology_id', '=', 'pathology.id')
                ->select('pathology.test_name', 'pathology_report.apply_charge')
                ->get();
            
            if ($reports->count() > 0) {
                return $reports->map(function($report) use ($bill) {
                    return [
                        'date' => $bill->date,
                        'test_name' => $report->test_name,
                        'amount' => $report->apply_charge ?? 0,
                        'type' => 'pathology',
                        'description' => $report->test_name,
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
        
        // Get radiology test details from reports
        $radiologyDetailsWithTests = $radiologyDetails->map(function($bill) {
            $reports = DB::table('radiology_report')
                ->where('radiology_bill_id', $bill->id)
                ->join('radio', 'radiology_report.radiology_id', '=', 'radio.id')
                ->select('radio.test_name', 'radiology_report.apply_charge')
                ->get();
            
            if ($reports->count() > 0) {
                return $reports->map(function($report) use ($bill) {
                    return [
                        'date' => $bill->date,
                        'test_name' => $report->test_name,
                        'amount' => $report->apply_charge ?? 0,
                        'type' => 'radiology',
                        'description' => $report->test_name,
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

        // Doctor Visit Charges Details
        $doctorVisitDetails = DoctorVisit::where('patient_id', $patientId)
            ->where('visit_date', '>=', $admissionDate)
            ->with(['doctor', 'charge'])
            ->orderBy('visit_date', 'asc')
            ->get()
            ->map(function($visit) {
                return [
                    'date' => $visit->visit_date,
                    'doctor' => ($visit->doctor->name ?? 'N/A') . ' ' . ($visit->doctor->surname ?? ''),
                    'charge_name' => $visit->charge->name ?? 'N/A',
                    'amount' => $visit->amount ?? 0,
                    'type' => 'doctor_visit',
                    'description' => 'Doctor Visit - ' . ($visit->doctor->name ?? 'N/A') . ' ' . ($visit->doctor->surname ?? '') . ' - ' . ($visit->charge->name ?? 'N/A'),
                ];
            });

        // Combine all charges and sort by date
        $allCharges = collect()
            ->merge($bedChargesDetails)
            ->merge($ipdChargesDetails)
            ->merge($pathologyDetailsWithTests)
            ->merge($radiologyDetailsWithTests)
            ->merge($doctorVisitDetails)
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

        // Doctor visits
        $doctorVisits = DoctorVisit::where('patient_id', $patientId)
            ->where('visit_date', '>=', $admissionDate)
            ->where('visit_date', '<=', $dischargeDate)
            ->with(['doctor', 'charge'])
            ->get();
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
     * Export Estimate/Breakup Bill PDF
     */
    public function exportEstimate($ipdId)
    {
        try {
            \Log::info('exportEstimate started', ['ipd_id' => $ipdId]);
            
            $ipd = IpdDetail::with(['patient.organisation', 'doctor', 'bedGroup', 'bedDetail'])
                ->findOrFail($ipdId);
            
            \Log::info('IPD found', ['ipd_no' => $ipd->ipd_no]);

            $breakup = $this->calculateBreakup($ipdId);
            \Log::info('Breakup calculated', ['total_charges' => $breakup['total_charges']]);

            // Get detailed breakdown - Calculate dynamically from PatientBedHistory
            $bedChargesData = $this->calculateBedChargesFromHistory($ipdId);
            $bedChargesDetails = collect($bedChargesData['details']);
            
            // Prepare GST charges grouped by bed group
            $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);

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
            
            // Get all pathology test names
            foreach ($pathologyDetails as $bill) {
                $reports = DB::table('pathology_report')
                    ->where('pathology_bill_id', $bill->id)
                    ->join('pathology', 'pathology_report.pathology_id', '=', 'pathology.id')
                    ->select('pathology.test_name', 'pathology_report.apply_charge')
                    ->get();
                
                foreach ($reports as $report) {
                    if ($report->test_name) {
                        $pathologyTestNames[] = $report->test_name;
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
                        $radiologyTestNames[] = $report->test_name;
                        $radiologyTotal += $report->apply_charge ?? 0;
                    }
                }
            }
            
            \Log::info('Radiology tests collected', ['test_count' => count($radiologyTestNames), 'total' => $radiologyTotal]);

            $doctorVisitDetails = DoctorVisit::where('patient_id', $ipd->patient_id)
                ->whereDate('visit_date', '>=', $ipd->date)
                ->with(['doctor', 'charge'])
                ->orderBy('visit_date', 'asc')
                ->get() ?? collect();
            
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

            \Log::info('Loading PDF view', [
                'pathology_tests' => count($pathologyTestNames),
                'radiology_tests' => count($radiologyTestNames)
            ]);
            
            // Get hospital information
            $hospital = Hospital::first();
            
            // First pass: Render to get accurate page count
            $tempPdf = Pdf::loadView('admin.billing.ipd_estimate_pdf', compact(
                'ipd', 'breakup', 'bedChargesDetails', 'ipdChargesDetails',
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails', 'payments',
                'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
                'totalChargesInWords', 'totalPaymentsInWords', 'outstandingInWords', 'netBalanceInWords',
                'hospital'
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
                'ipd', 'breakup', 'bedChargesDetails', 'ipdChargesDetails',
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails', 'payments',
                'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
                'totalChargesInWords', 'totalPaymentsInWords', 'outstandingInWords', 'netBalanceInWords',
                'hospital', 'totalPages', 'gstChargesGrouped'
            ));
            
            // Enable PHP scripts for page numbering
             $pdf->setOption('enable-php', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setPaper('a4', 'portrait');
            
            \Log::info('PDF generated, returning download');
            
            return $pdf->download('IPD_Estimate_Bill_' . $ipd->ipd_no . '.pdf');
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
            
            \Log::info('Discharge information retrieved', [
                'discharge_date' => $dischargeDate,
                'discharge_time' => $dischargeTime
            ]);

            \Log::info('Calculating breakup');
            $breakup = $this->calculateBreakup($ipdId, $dischargeDate);
            \Log::info('Breakup calculated', ['total_charges' => $breakup['total_charges']]);

            // Get payment details
            \Log::info('Getting payment details');
            $payments = Transaction::where('ipd_id', $ipdId)
                ->where('type', 'payment')
                ->where('section', 'ipd')
                ->orderBy('payment_date', 'asc')
                ->get() ?? collect();

            // Get detailed breakdown - Calculate dynamically from PatientBedHistory up to discharge date
            \Log::info('Getting bed charges details');
            $bedChargesData = $this->calculateBedChargesFromHistory($ipdId, $dischargeDate);
            $bedChargesDetails = collect($bedChargesData['details']);
            
            // Prepare GST charges grouped by bed group
            $gstChargesGrouped = $this->prepareGstCharges($bedChargesDetails);

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
            $doctorVisitDetails = DoctorVisit::where('patient_id', $ipd->patient_id)
                ->whereDate('visit_date', '>=', $ipd->date)
                ->with(['doctor', 'charge'])
                ->orderBy('visit_date', 'asc')
                ->get() ?? collect();

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
                        ->join('pathology', 'pathology_report.pathology_id', '=', 'pathology.id')
                        ->select('pathology.test_name', 'pathology_report.apply_charge')
                        ->get();
                    
                    foreach ($reports as $report) {
                        if ($report->test_name) {
                            $pathologyTestNames[] = $report->test_name;
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
                        ->join('radio', 'radiology_report.radiology_id', '=', 'radio.id')
                        ->select('radio.test_name', 'radiology_report.apply_charge')
                        ->get();
                    
                    foreach ($reports as $report) {
                        if ($report->test_name) {
                            $radiologyTestNames[] = $report->test_name;
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

            \Log::info('Starting PDF generation', [
                'pathology_tests' => count($pathologyTestNames),
                'radiology_tests' => count($radiologyTestNames)
            ]);

            // Set totalPages to 1 initially (will be calculated by PDF script)
            $totalPages = 1;

            // Generate PDF
            \Log::info('Loading PDF view');
            $pdf = Pdf::loadView('admin.billing.ipd_final_pdf', compact(
                'ipd', 'breakup', 'bedChargesDetails', 'bedChargesGrouped', 'ipdChargesDetails',
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails', 'payments',
                'hospital', 'billNumber', 'billDate', 'dischargeDate', 'dischargeTime',
                'discount', 'mouDiscount', 'specialDiscount', 'duePatientPartyAmount',
                'grandTotal', 'totalAdvance', 'balance', 'grandTotalInWords', 
                'totalAdvanceInWords', 'balanceInWords', 'otCharges', 'medicineCharges',
                'surgeonCharges', 'anesthesiaCharges', 'investigationCharges', 'totalPages',
                'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
                'gstChargesGrouped'
            ));
            
            \Log::info('PDF view loaded, setting options');
            $pdf->setOption('enable-php', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('enable-local-file-access', true);
            $pdf->setPaper('a4', 'portrait');

            \Log::info('PDF generated successfully, returning download');
            return $pdf->download('IPD_Final_Bill_' . $ipd->ipd_no . '.pdf');
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
