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
        
        // If no search term, return all active IPD patients (for initial load)
        $query = IpdDetail::with(['patient', 'doctor'])
            ->where(function($q) {
                $q->where('discharged', '!=', 'yes')
                  ->orWhereNull('discharged');
            });

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

        $ipdPatients = $query->limit(100)->get();

        $data = $ipdPatients->map(function($ipd) {
            $patient = $ipd->patient;
            
            return [
                'id' => $ipd->id,
                'ipd_no' => $ipd->ipd_no ?? 'N/A',
                'patient_name' => $patient->patient_name ?? 'N/A',
                'phone' => $patient->mobileno ?? '',
                'display_text' => ($ipd->ipd_no ?? 'N/A') . ' - ' . ($patient->patient_name ?? 'N/A'),
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
        $ipd = IpdDetail::with(['patient', 'doctor', 'bedGroup', 'bedDetail'])
            ->findOrFail($ipdId);

        // Calculate all charges
        $breakup = $this->calculateBreakup($ipdId);
        
        // Get detailed date-wise breakdown
        $detailedBreakup = $this->getDetailedBreakup($ipdId, $ipd);

        return view('admin.billing.ipd_breakup', compact('ipd', 'breakup', 'detailedBreakup'));
    }

    /**
     * Calculate breakup bill
     */
    private function calculateBreakup($ipdId)
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

        // Bed Charges
        $bedCharges = IpdDaywiseBedCharge::where('ipd_id', $ipdId)
            ->where('is_active', 'yes')
            ->sum('bed_charge');

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

        // Total Charges
        $totalCharges = $bedCharges + $ipdCharges + $pathologyCharges + $radiologyCharges + $doctorVisitCharges;

        // Total Payments
        $totalPayments = Transaction::where('ipd_id', $ipdId)
            ->where('type', 'payment')
            ->where('section', 'ipd')
            ->sum('amount');

        // Outstanding
        $outstanding = $totalCharges - $totalPayments;

        return [
            'bed_charges' => $bedCharges,
            'ipd_charges' => $ipdCharges,
            'pathology_charges' => $pathologyCharges,
            'radiology_charges' => $radiologyCharges,
            'doctor_visit_charges' => $doctorVisitCharges,
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
        
        // Bed Charges Details
        $bedChargesDetails = IpdDaywiseBedCharge::where('ipd_id', $ipdId)
            ->where('is_active', 'yes')
            ->with(['bedGroup', 'bed'])
            ->orderBy('charge_date', 'asc')
            ->get()
            ->map(function($charge) {
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

            // Get detailed breakdown - Initialize with empty collections
            $bedChargesDetails = IpdDaywiseBedCharge::where('ipd_id', $ipdId)
                ->where('is_active', 'yes')
                ->with(['bedGroup', 'bed'])
                ->orderBy('charge_date', 'asc')
                ->get() ?? collect();

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
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails',
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
                'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails',
                'pathologyTestNames', 'radiologyTestNames', 'pathologyTotal', 'radiologyTotal',
                'totalChargesInWords', 'totalPaymentsInWords', 'outstandingInWords', 'netBalanceInWords',
                'hospital', 'totalPages'
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
     * Export Final Bill PDF
     */
    public function exportFinal($ipdId)
    {
        $ipd = IpdDetail::with(['patient', 'doctor', 'bedGroup', 'bedDetail'])
            ->findOrFail($ipdId);

        $breakup = $this->calculateBreakup($ipdId);

        // Get payment details
        $payments = Transaction::where('ipd_id', $ipdId)
            ->where('type', 'payment')
            ->where('section', 'ipd')
            ->orderBy('payment_date', 'asc')
            ->get();

        // Get detailed breakdown
        $bedChargesDetails = IpdDaywiseBedCharge::where('ipd_id', $ipdId)
            ->where('is_active', 'yes')
            ->orderBy('charge_date', 'asc')
            ->get();

        $ipdChargesDetails = IpdCharges::where('ipd_id', $ipdId)
            ->with(['charge', 'chargeCategory'])
            ->orderBy('date', 'asc')
            ->get();

        $pathologyDetails = collect();
        if ($ipd->case_reference_id) {
            $pathologyDetails = PathologyBilling::where('case_reference_id', $ipd->case_reference_id)
                ->with(['pathology'])
                ->orderBy('date', 'asc')
                ->get();
        }

        $radiologyDetails = collect();
        if ($ipd->case_reference_id) {
            $radiologyDetails = RadiologyBilling::where('case_reference_id', $ipd->case_reference_id)
                ->with(['radiology'])
                ->orderBy('date', 'asc')
                ->get();
        }

        $doctorVisitDetails = DoctorVisit::where('patient_id', $ipd->patient_id)
            ->where('visit_date', '>=', $ipd->date)
            ->with(['doctor', 'charge'])
            ->orderBy('visit_date', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.billing.ipd_final_pdf', compact(
            'ipd', 'breakup', 'bedChargesDetails', 'ipdChargesDetails',
            'pathologyDetails', 'radiologyDetails', 'doctorVisitDetails', 'payments'
        ));

        return $pdf->download('IPD_Final_Bill_' . $ipd->ipd_no . '.pdf');
    }
}
