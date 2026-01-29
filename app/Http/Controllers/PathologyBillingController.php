<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PathologyBilling;
use App\Models\PathologyReport;
use App\Models\Pathology;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\CaseReference;
use App\Models\OpdDetail;
use App\Models\Organisation;
use App\Models\OrganisationsCharge;
use App\Models\IpdPrescription;
use App\Models\IpdDetail;
use App\Models\IpdPrescriptionTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PathologyBillingController extends Controller
{
    /**
     * Display a listing of pathology bills
     */
    public function index(Request $request)
    {
         $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

    $search = $request->input('search');
        $query = PathologyBilling::with(['patient', 'doctor', 'organisation']);
      
            if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('doctor_name', 'like', "%{$search}%")
                ->orWhereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('patient_name', 'like', "%{$search}%");
                });
        });
    }

     $bills = $query->paginate($perPage);
    //      return response()->json([
    //     'status' => true,
    //     'message' => 'Staff list fetched successfully',
    //     'data' => $bills
    // ]);
        
        return view('admin.pathology.billing.index', compact('bills'));
    }

    /**
     * Show the form for creating a new pathology bill
     */
    public function create()
    {
        $patients = Patient::select('id', 'patient_name', 'mobileno')->get();
        $doctors = Doctor::select('id', 'name', 'surname', 'doctor_id')
            ->where(function($query) {
                $query->where('is_active', true)
                      ->orWhere('is_active', 1)
                      ->orWhere('is_active', 'yes');
            })
            ->get();
        $tests = Pathology::with(['category'])->get();
        
        return view('admin.pathology.billing.create', compact('patients', 'doctors', 'tests'));
    }

    /**
     * Store a newly created pathology bill
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'case_reference_id' => 'nullable',
            'doctor_id' => 'nullable|exists:doctor,id',
            'doctor_name' => 'nullable|string|max:100',
            'date' => 'required|date',
            'total' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'organisation_id' => 'nullable|exists:organisation,id',
            'activate_tpa' => 'nullable',
            'tests' => 'required|array|min:1',
            'tests.*.pathology_id' => 'required|exists:pathology,id',
            'tests.*.report_days' => 'required|integer|min:0',
            'tests.*.report_date' => 'required|date',
            'tests.*.tax_percentage' => 'nullable|numeric|min:0',
            'tests.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            // Get bill number
            $billNo = 'PATB' . str_pad(PathologyBilling::max('id') + 1, 2, '0', STR_PAD_LEFT);
            
            // Get doctor name if doctor_id is provided
            $doctorName = $validated['doctor_name'] ?? null;
            if (!$doctorName && $validated['doctor_id']) {
                $doctor = Doctor::find($validated['doctor_id']);
                if ($doctor) {
                    $doctorName = 'Dr. ' . trim($doctor->name . ' ' . ($doctor->surname ?? ''));
                }
            }
            
            // Create pathology bill
            // Ensure case_reference_id is null if empty or invalid
            $caseReferenceId = null;
            if (!empty($validated['case_reference_id'])) {
                $caseRefId = $validated['case_reference_id'];
                // Verify it exists in the database
                if (CaseReference::where('id', $caseRefId)->exists()) {
                    $caseReferenceId = $caseRefId;
                } else {
                    \Log::warning('Invalid case_reference_id provided: ' . $caseRefId . '. Setting to null.');
                    $caseReferenceId = null;
                }
            }
            
            $bill = PathologyBilling::create([
                'date' => $validated['date'],
                'patient_id' => $validated['patient_id'],
                'case_reference_id' => $caseReferenceId,
                'doctor_id' => $validated['doctor_id'] ?? null,
                'doctor_name' => $doctorName ?? '',
                'total' => $validated['total'],
                'discount_percentage' => $validated['discount_percentage'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'net_amount' => $validated['net_amount'],
                'note' => $validated['note'] ?? null,
                'organisation_id' => ($request->has('activate_tpa') && $request->activate_tpa) ? ($validated['organisation_id'] ?? null) : null,
                'generated_by' => Auth::id(),
            ]);
            
            // Create pathology reports
            foreach ($validated['tests'] as $test) {
                PathologyReport::create([
                    'pathology_bill_id' => $bill->id,
                    'pathology_id' => $test['pathology_id'],
                    'patient_id' => $validated['patient_id'],
                    'reporting_date' => $test['report_date'],
                    'tax_percentage' => $test['tax_percentage'] ?? 0,
                    'apply_charge' => $test['amount'],
                    'customer_type' => 'OPD',
                ]);
            }

            DB::commit();
            
            return redirect()->route('pathology.billing.index')
                ->with('success', 'Pathology bill created successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation error creating pathology bill: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating pathology bill: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating pathology bill: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified pathology bill
     */
    public function show($id)
    {
        $bill = PathologyBilling::with(['patient', 'doctor', 'reports.pathology', 'organisation'])
            ->findOrFail($id);
        
        return view('admin.pathology.billing.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified pathology bill
     */
    public function edit($id)
    {
        $bill = PathologyBilling::with(['patient.organisation', 'doctor', 'reports.pathology', 'organisation', 'prescription'])->findOrFail($id);
        $patients = Patient::select('id', 'patient_name', 'mobileno')->get();
        $doctors = Doctor::select('id', 'name', 'surname', 'doctor_id')
            ->where(function($query) {
                $query->where('is_active', true)
                      ->orWhere('is_active', 1)
                      ->orWhere('is_active', 'yes');
            })
            ->get();
        $tests = Pathology::with(['category'])->get();
        
        // Get prescription number for display
        $prescriptionNumber = '';
        if ($bill->prescription) {
            $prescriptionNumber = $bill->prescription->prescription_number ?? 'IPDP' . str_pad($bill->prescription->id, 4, '0', STR_PAD_LEFT);
        }
        
        return view('admin.pathology.billing.edit', compact('bill', 'patients', 'doctors', 'tests', 'prescriptionNumber'));
    }

    /**
     * Update the specified pathology bill
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'case_reference_id' => 'nullable',
            'doctor_id' => 'nullable|exists:doctor,id',
            'doctor_name' => 'nullable|string|max:100',
            'date' => 'required|date',
            'total' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'organisation_id' => 'nullable|exists:organisation,id',
            'activate_tpa' => 'nullable',
            'tests' => 'required|array|min:1',
            'tests.*.pathology_id' => 'required|exists:pathology,id',
            'tests.*.report_days' => 'required|integer|min:0',
            'tests.*.report_date' => 'required|date',
            'tests.*.tax_percentage' => 'nullable|numeric|min:0',
            'tests.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $bill = PathologyBilling::findOrFail($id);
            
            // Get doctor name if doctor_id is provided
            $doctorName = $validated['doctor_name'] ?? null;
            if (!$doctorName && $validated['doctor_id']) {
                $doctor = Doctor::find($validated['doctor_id']);
                if ($doctor) {
                    $doctorName = 'Dr. ' . trim($doctor->name . ' ' . ($doctor->surname ?? ''));
                }
            }
            
            // Update pathology bill
            $bill->update([
                'date' => $validated['date'],
                'patient_id' => $validated['patient_id'],
                'case_reference_id' => $validated['case_reference_id'] ?? null,
                'doctor_id' => $validated['doctor_id'] ?? null,
                'doctor_name' => $doctorName ?? '',
                'total' => $validated['total'],
                'discount_percentage' => $validated['discount_percentage'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'net_amount' => $validated['net_amount'],
                'note' => $validated['note'] ?? null,
                'organisation_id' => ($request->has('activate_tpa') && $request->activate_tpa) ? ($validated['organisation_id'] ?? null) : null,
            ]);

            // Delete existing reports
            PathologyReport::where('pathology_bill_id', $bill->id)->delete();

            // Create new pathology reports
            foreach ($validated['tests'] as $test) {
                PathologyReport::create([
                    'pathology_bill_id' => $bill->id,
                    'pathology_id' => $test['pathology_id'],
                    'patient_id' => $validated['patient_id'],
                    'reporting_date' => $test['report_date'],
                    'tax_percentage' => $test['tax_percentage'] ?? 0,
                    'apply_charge' => $test['amount'],
                    'customer_type' => 'OPD',
                ]);
            }

            DB::commit();
            
            return redirect()->route('pathology.billing.index')
                ->with('success', 'Pathology bill updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating pathology bill: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating pathology bill: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified pathology bill
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $bill = PathologyBilling::findOrFail($id);
            
            // Delete related reports
            PathologyReport::where('pathology_bill_id', $bill->id)->delete();
            
            // Delete bill
            $bill->delete();
            
            DB::commit();
            
            return redirect()->route('pathology.billing.index')
                ->with('success', 'Pathology bill deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting pathology bill: ' . $e->getMessage());
        }
    }

    /**
     * API: Get patient prescriptions (OPD Case References and IPD Prescriptions)
     */
    public function getPatientPrescriptions($patientId)
    {
        try {
            \Log::info('Getting prescriptions for patient ID: ' . $patientId);
            
            $prescriptions = collect();
            
            // Get OPD Visits (instead of case_references which doesn't have the needed fields)
            $opdVisits = OpdDetail::where('patient_id', $patientId)
                ->with('doctor')
                ->orderBy('appointment_date', 'desc')
                ->get()
                ->map(function($opd) {
                    return [
                        'id' => $opd->id,
                        'case_id' => $opd->opd_no ?? 'OPD' . str_pad($opd->id, 4, '0', STR_PAD_LEFT),
                        'date' => $opd->appointment_date ? date('Y-m-d', strtotime($opd->appointment_date)) : null,
                        'symptoms' => $opd->symptoms_description ?? $opd->symptoms_title ?? '',
                        'doctor' => $opd->doctor ? $opd->doctor->name : null,
                        'type' => 'opd',
                    ];
                });
            
            $prescriptions = $prescriptions->merge($opdVisits);
            \Log::info('OPD Visits found: ' . $opdVisits->count());
            
            // Get IPD Prescriptions - try direct join first
            $ipdPrescriptions = IpdPrescription::join('ipd_details', 'ipd_prescription.ipd_id', '=', 'ipd_details.id')
                ->where('ipd_details.patient_id', $patientId)
                ->select('ipd_prescription.*')
                ->with(['ipd', 'prescribedBy'])
                ->orderBy('ipd_prescription.date', 'desc')
                ->get()
                ->map(function($prescription) {
                    return [
                        'id' => $prescription->id,
                        'case_id' => $prescription->prescription_number ?? 'IPDP' . str_pad($prescription->id, 4, '0', STR_PAD_LEFT),
                        'date' => $prescription->date ? $prescription->date->format('Y-m-d') : null,
                        'symptoms' => $prescription->finding_description ?? '',
                        'doctor' => $prescription->prescribedBy ? $prescription->prescribedBy->name : null,
                        'type' => 'ipd',
                        'prescription_id' => $prescription->id,
                    ];
                });
            
            $prescriptions = $prescriptions->merge($ipdPrescriptions);
            \Log::info('IPD Prescriptions found: ' . $ipdPrescriptions->count());
            \Log::info('Total prescriptions: ' . $prescriptions->count());
            
            return response()->json($prescriptions->values());
        } catch (\Exception $e) {
            \Log::error('Error getting patient prescriptions: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Get pathology test details
     */
    public function getTestDetails(Request $request)
    {
        $testId = $request->input('test_id');
        $customerType = $request->input('customer_type', 'OPD'); // Default to OPD
        
        $test = Pathology::find($testId);
        
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }
        
        // Determine which charge to use based on customer type (IPD or OPD)
        $standardCharge = ($customerType === 'IPD') ? ($test->standard_charge_ipd ?? 0) : ($test->standard_charge_opd ?? 0);
        
        return response()->json([
            'id' => $test->id,
            'test_name' => $test->test_name,
            'report_days' => $test->report_days,
            'standard_charge_ipd' => $test->standard_charge_ipd ?? 0,
            'standard_charge_opd' => $test->standard_charge_opd ?? 0,
            'amount' => $standardCharge,
        ]);
    }

    /**
     * API: Get TPA names for a patient from previous pathology bills, IPD records, and Patient record
     */
    public function getPatientTpas($patientId)
    {
        try {
            \Log::info('Getting TPAs for patient ID: ' . $patientId);
            
            $tpas = collect();
            
            // FIRST: Get TPA directly from Patient record (this is the primary source)
            $patient = Patient::with('organisation')->find($patientId);
            if ($patient && $patient->organisation_id && $patient->organisation) {
                $tpas->push([
                    'id' => $patient->organisation_id,
                    'name' => $patient->organisation->organisation_name ?? 'Unknown TPA',
                    'code' => $patient->organisation->code ?? null,
                ]);
                \Log::info('TPA from Patient record: ' . ($patient->organisation->organisation_name ?? 'N/A'));
            }
            
            // Get TPAs from previous pathology bills
            $pathologyTpas = PathologyBilling::where('patient_id', $patientId)
                ->whereNotNull('organisation_id')
                ->with('organisation')
                ->select('organisation_id')
                ->distinct()
                ->get()
                ->filter(function($billing) {
                    return $billing->organisation !== null;
                })
                ->map(function($billing) {
                    return [
                        'id' => $billing->organisation_id,
                        'name' => $billing->organisation->organisation_name ?? 'Unknown TPA',
                        'code' => $billing->organisation->code ?? null,
                    ];
                });
            
            $tpas = $tpas->merge($pathologyTpas);
            \Log::info('TPAs from pathology bills: ' . $pathologyTpas->count());
            
            // Get TPAs from IPD records
            $ipdTpas = IpdDetail::where('patient_id', $patientId)
                ->whereNotNull('organisation_id')
                ->with('organisation')
                ->select('organisation_id')
                ->distinct()
                ->get()
                ->filter(function($ipd) {
                    return $ipd->organisation !== null; // Filter out null organisations
                })
                ->map(function($ipd) {
                    return [
                        'id' => $ipd->organisation_id,
                        'name' => $ipd->organisation->organisation_name ?? 'Unknown TPA',
                        'code' => $ipd->organisation->code ?? null,
                    ];
                });
            
            $tpas = $tpas->merge($ipdTpas);
            \Log::info('TPAs from IPD records: ' . $ipdTpas->count());
            
            // Also get TPAs from IPD Prescriptions (if the prescription has an IPD with TPA)
            $ipdPrescriptionTpas = IpdPrescription::whereHas('ipd', function($query) use ($patientId) {
                    $query->where('patient_id', $patientId)
                          ->whereNotNull('organisation_id');
                })
                ->with('ipd.organisation')
                ->get()
                ->map(function($prescription) {
                    if ($prescription->ipd && $prescription->ipd->organisation) {
                        return [
                            'id' => $prescription->ipd->organisation_id,
                            'name' => $prescription->ipd->organisation->organisation_name ?? 'Unknown TPA',
                            'code' => $prescription->ipd->organisation->code ?? null,
                        ];
                    }
                    return null;
                })
                ->filter(); // Remove nulls
            
            $tpas = $tpas->merge($ipdPrescriptionTpas);
            \Log::info('TPAs from IPD Prescriptions: ' . $ipdPrescriptionTpas->count());
            
            // Remove duplicates based on ID
            $uniqueTpas = $tpas->unique('id')->values();
            \Log::info('Total unique TPAs: ' . $uniqueTpas->count());
            
            return response()->json($uniqueTpas);
        } catch (\Exception $e) {
            \Log::error('Error getting patient TPAs: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API: Get prescription tests (pathology tests from IPD prescription)
     */
    public function getPrescriptionTests($prescriptionId)
    {
        $prescription = IpdPrescription::with(['tests.pathology', 'ipd.patient.organisation', 'ipd.organisation'])
            ->find($prescriptionId);
        
        if (!$prescription) {
            return response()->json(['error' => 'Prescription not found'], 404);
        }
        
        // Get pathology tests from the prescription
        $pathologyTests = $prescription->tests()
            ->whereNotNull('pathology_id')
            ->with('pathology')
            ->get()
            ->filter(function($test) {
                return $test->pathology !== null; // Filter out tests with null pathology
            })
            ->map(function($test) {
                $pathology = $test->pathology;
                // Use IPD charge since this is from IPD prescription
                $standardCharge = $pathology->standard_charge_ipd ?? 0;
                return [
                    'id' => $pathology->id,
                    'test_name' => $pathology->test_name,
                    'report_days' => $pathology->report_days ?? 0,
                    'standard_charge_ipd' => $pathology->standard_charge_ipd ?? 0,
                    'standard_charge_opd' => $pathology->standard_charge_opd ?? 0,
                    'amount' => $standardCharge,
                ];
            });
        
        // Get TPA - Priority: Patient's TPA > IPD's TPA
        $tpa = null;
        if ($prescription->ipd && $prescription->ipd->patient) {
            // First check patient's TPA (primary source)
            if ($prescription->ipd->patient->organisation_id && $prescription->ipd->patient->organisation) {
                $tpa = [
                    'id' => $prescription->ipd->patient->organisation_id,
                    'name' => $prescription->ipd->patient->organisation->organisation_name,
                    'code' => $prescription->ipd->patient->organisation->code,
                ];
            }
            // Fallback to IPD's TPA if patient doesn't have one
            elseif ($prescription->ipd->organisation_id && $prescription->ipd->organisation) {
                $tpa = [
                    'id' => $prescription->ipd->organisation_id,
                    'name' => $prescription->ipd->organisation->organisation_name,
                    'code' => $prescription->ipd->organisation->code,
                ];
            }
        }
        
        // Get full doctor name with surname
        $doctorName = null;
        $doctorId = null;
        if ($prescription->prescribedBy) {
            $name = trim($prescription->prescribedBy->name . ' ' . ($prescription->prescribedBy->surname ?? ''));
            $doctorName = $name;
            $doctorId = $prescription->prescribedBy->id;
        }
        
        return response()->json([
            'prescription' => [
                'id' => $prescription->id,
                'prescription_number' => $prescription->prescription_number,
                'date' => $prescription->date ? $prescription->date->format('Y-m-d') : null,
                'doctor' => $doctorName,
                'doctor_id' => $doctorId,
            ],
            'tests' => $pathologyTests,
            'tpa' => $tpa, // Include TPA if available
        ]);
    }

    /**
     * API: Get TPA charge for a specific test and TPA
     */
    public function getTpaCharge(Request $request)
    {
        $testId = $request->input('test_id');
        $organisationId = $request->input('organisation_id');
        $customerType = $request->input('customer_type', 'OPD'); // Default to OPD
        
        if (!$testId || !$organisationId) {
            return response()->json(['error' => 'Test ID and Organisation ID are required'], 400);
        }
        
        $test = Pathology::find($testId);
        
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }
        
        // Determine which standard charge to use based on customer type
        $standardCharge = ($customerType === 'IPD') ? ($test->standard_charge_ipd ?? 0) : ($test->standard_charge_opd ?? 0);
        
        // Look for TPA charge for this pathology, organization, and charge type
        $tpaCharge = OrganisationsCharge::where('pathology_id', $test->id)
            ->where('org_id', $organisationId)
            ->where('charge_type', $customerType)
            ->first();
        
        \Log::info("TPA charge lookup - Test: {$testId}, Org: {$organisationId}, Type: {$customerType}, Found: " . ($tpaCharge ? 'Yes' : 'No'));
        
        if ($tpaCharge && $tpaCharge->org_charge !== null) {
            \Log::info("TPA charge found: {$tpaCharge->org_charge}");
            return response()->json([
                'tpa_charge_ipd' => ($customerType === 'IPD') ? (float)$tpaCharge->org_charge : null,
                'tpa_charge_opd' => ($customerType === 'OPD') ? (float)$tpaCharge->org_charge : null,
                'standard_charge_ipd' => $test->standard_charge_ipd ?? 0,
                'standard_charge_opd' => $test->standard_charge_opd ?? 0,
                'tpa_charge' => (float)$tpaCharge->org_charge,
                'standard_charge' => $standardCharge,
            ]);
        }
        
        \Log::info("No TPA charge found, returning standard charge: {$standardCharge}");
        return response()->json([
            'tpa_charge_ipd' => null,
            'tpa_charge_opd' => null,
            'standard_charge_ipd' => $test->standard_charge_ipd ?? 0,
            'standard_charge_opd' => $test->standard_charge_opd ?? 0,
            'tpa_charge' => null,
            'standard_charge' => $standardCharge,
        ]);
    }
}

