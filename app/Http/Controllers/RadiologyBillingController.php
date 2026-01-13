<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RadiologyBilling;
use App\Models\RadiologyReport;
use App\Models\Radio;
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

class RadiologyBillingController extends Controller
{
    /**
     * Display a listing of radiology bills
     */
    public function index()
    {
        $bills = RadiologyBilling::with(['patient', 'doctor'])
            ->orderBy('id', 'desc')
            ->paginate(15);
        
        return view('admin.radiology.billing.index', compact('bills'));
    }

    /**
     * Show the form for creating a new radiology bill
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
        $tests = Radio::with(['radiologyCategory'])->get();
        
        return view('admin.radiology.billing.create', compact('patients', 'doctors', 'tests'));
    }

    /**
     * Store a newly created radiology bill
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
            'tests.*.radiology_id' => 'required|exists:radio,id',
            'tests.*.report_days' => 'required|integer|min:0',
            'tests.*.report_date' => 'required|date',
            'tests.*.tax_percentage' => 'nullable|numeric|min:0',
            'tests.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            // Get doctor name if doctor_id is provided
            $doctorName = $validated['doctor_name'] ?? null;
            if (!$doctorName && $validated['doctor_id']) {
                $doctor = Doctor::find($validated['doctor_id']);
                if ($doctor) {
                    $name = trim($doctor->name . ' ' . ($doctor->surname ?? ''));
                    // Only add "Dr." prefix if it's not already there
                    $doctorName = (stripos($name, 'Dr.') === 0) ? $name : 'Dr. ' . $name;
                }
            }
            
            // Truncate doctor name to fit database column (limit to 8 chars - column appears to be very short)
            // Store truncated version for reports
            $truncatedDoctorName = $doctorName ? mb_substr(trim($doctorName), 0, 8) : '';
            
            // Create radiology bill
            $bill = RadiologyBilling::create([
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
                'generated_by' => Auth::id(),
            ]);

            // Create radiology reports
            foreach ($validated['tests'] as $test) {
                RadiologyReport::create([
                    'radiology_bill_id' => $bill->id,
                    'radiology_id' => $test['radiology_id'],
                    'patient_id' => $validated['patient_id'],
                    'reporting_date' => $test['report_date'],
                    'tax_percentage' => $test['tax_percentage'] ?? 0,
                    'apply_charge' => $test['amount'],
                    'customer_type' => 'OPD',
                    'consultant_doctor' => $truncatedDoctorName,
                    'radiology_center' => '', // Required field, set to empty string
                ]);
            }

            DB::commit();
            
            return redirect()->route('radiology.billing.index')
                ->with('success', 'Radiology bill created successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation error creating radiology bill: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating radiology bill: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating radiology bill: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified radiology bill
     */
    public function show($id)
    {
        $bill = RadiologyBilling::with(['patient', 'doctor', 'reports.radiology', 'organisation'])
            ->findOrFail($id);
        
        return view('admin.radiology.billing.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified radiology bill
     */
    public function edit($id)
    {
        $bill = RadiologyBilling::with(['patient.organisation', 'doctor', 'reports.radiology', 'organisation', 'prescription'])->findOrFail($id);
        $patients = Patient::select('id', 'patient_name', 'mobileno')->get();
        $doctors = Doctor::select('id', 'name', 'surname', 'doctor_id')
            ->where(function($query) {
                $query->where('is_active', true)
                      ->orWhere('is_active', 1)
                      ->orWhere('is_active', 'yes');
            })
            ->get();
        $tests = Radio::with(['radiologyCategory'])->get();
        
        // Get prescription number for display
        $prescriptionNumber = '';
        if ($bill->prescription) {
            $prescriptionNumber = $bill->prescription->prescription_number ?? 'IPDP' . str_pad($bill->prescription->id, 4, '0', STR_PAD_LEFT);
        }
        
        return view('admin.radiology.billing.edit', compact('bill', 'patients', 'doctors', 'tests', 'prescriptionNumber'));
    }

    /**
     * Update the specified radiology bill
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
            'tests.*.radiology_id' => 'required|exists:radio,id',
            'tests.*.report_days' => 'required|integer|min:0',
            'tests.*.report_date' => 'required|date',
            'tests.*.tax_percentage' => 'nullable|numeric|min:0',
            'tests.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $bill = RadiologyBilling::findOrFail($id);
            
            // Get doctor name if doctor_id is provided
            $doctorName = $validated['doctor_name'] ?? null;
            if (!$doctorName && $validated['doctor_id']) {
                $doctor = Doctor::find($validated['doctor_id']);
                if ($doctor) {
                    $name = trim($doctor->name . ' ' . ($doctor->surname ?? ''));
                    // Only add "Dr." prefix if it's not already there
                    $doctorName = (stripos($name, 'Dr.') === 0) ? $name : 'Dr. ' . $name;
                }
            }
            
            // Truncate doctor name to fit database column (limit to 8 chars - column appears to be very short)
            // Store truncated version for reports
            $truncatedDoctorName = $doctorName ? mb_substr(trim($doctorName), 0, 8) : '';
            
            // Update radiology bill
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
            RadiologyReport::where('radiology_bill_id', $bill->id)->delete();

            // Create new radiology reports
            foreach ($validated['tests'] as $test) {
                RadiologyReport::create([
                    'radiology_bill_id' => $bill->id,
                    'radiology_id' => $test['radiology_id'],
                    'patient_id' => $validated['patient_id'],
                    'reporting_date' => $test['report_date'],
                    'tax_percentage' => $test['tax_percentage'] ?? 0,
                    'apply_charge' => $test['amount'],
                    'customer_type' => 'OPD',
                    'consultant_doctor' => $truncatedDoctorName,
                    'radiology_center' => '', // Required field, set to empty string
                ]);
            }

            DB::commit();
            
            return redirect()->route('radiology.billing.index')
                ->with('success', 'Radiology bill updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation error updating radiology bill: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating radiology bill: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating radiology bill: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified radiology bill
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $bill = RadiologyBilling::findOrFail($id);
            
            // Delete related reports
            RadiologyReport::where('radiology_bill_id', $bill->id)->delete();
            
            // Delete bill
            $bill->delete();
            
            DB::commit();
            
            return redirect()->route('radiology.billing.index')
                ->with('success', 'Radiology bill deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting radiology bill: ' . $e->getMessage());
        }
    }

    /**
     * API: Get patient prescriptions (OPD Visits and IPD Prescriptions)
     */
    public function getPatientPrescriptions($patientId)
    {
        try {
            \Log::info('Getting prescriptions for patient ID: ' . $patientId);
            
            $prescriptions = collect();
            
            // Get OPD Visits
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
            
            // Get IPD Prescriptions
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
     * API: Get radiology test details
     */
    public function getTestDetails(Request $request)
    {
        $testId = $request->input('test_id');
        $customerType = $request->input('customer_type', 'OPD'); // Default to OPD if not provided
        
        $test = Radio::find($testId);
        
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }
        
        // Select the appropriate charge based on customer type
        $amount = ($customerType === 'IPD') ? ($test->standard_charge_ipd ?? 0) : ($test->standard_charge_opd ?? 0);
        
        return response()->json([
            'id' => $test->id,
            'test_name' => $test->test_name,
            'report_days' => $test->report_days,
            'tax_percentage' => 0, // Tax is now handled separately in billing
            'amount' => $amount,
            'standard_charge_ipd' => $test->standard_charge_ipd ?? 0,
            'standard_charge_opd' => $test->standard_charge_opd ?? 0,
        ]);
    }

    /**
     * API: Get TPA names for a patient from previous radiology bills, IPD records, and Patient record
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
            
            // Get TPAs from previous radiology bills
            $radiologyTpas = RadiologyBilling::where('patient_id', $patientId)
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
            
            $tpas = $tpas->merge($radiologyTpas);
            \Log::info('TPAs from radiology bills: ' . $radiologyTpas->count());
            
            // Get TPAs from IPD records
            $ipdTpas = IpdDetail::where('patient_id', $patientId)
                ->whereNotNull('organisation_id')
                ->with('organisation')
                ->select('organisation_id')
                ->distinct()
                ->get()
                ->filter(function($ipd) {
                    return $ipd->organisation !== null;
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
            
            // Also get TPAs from IPD Prescriptions
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
                ->filter();
            
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
     * API: Get TPA charge for a specific test and TPA
     */
    public function getTpaCharge(Request $request)
    {
        $testId = $request->input('test_id');
        $organisationId = $request->input('organisation_id');
        $customerType = $request->input('customer_type', 'OPD'); // Default to OPD if not provided
        
        if (!$testId || !$organisationId) {
            return response()->json(['error' => 'Test ID and Organisation ID are required'], 400);
        }
        
        $test = Radio::find($testId);
        
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }
        
        // Get standard charge based on customer type
        $standardCharge = ($customerType === 'IPD') ? ($test->standard_charge_ipd ?? 0) : ($test->standard_charge_opd ?? 0);
        
        // Look for TPA charge using radiology_id and charge_type
        $tpaCharge = OrganisationsCharge::where('radiology_id', $testId)
            ->where('org_id', $organisationId)
            ->where('charge_type', $customerType)
            ->first();
        
        \Log::info("TPA charge lookup - Test: {$testId}, Org: {$organisationId}, Customer Type: {$customerType}, Found: " . ($tpaCharge ? 'Yes' : 'No'));
        
        if ($tpaCharge && $tpaCharge->org_charge !== null) {
            \Log::info("TPA charge found: {$tpaCharge->org_charge}");
            return response()->json([
                'tpa_charge_ipd' => ($customerType === 'IPD') ? (float)$tpaCharge->org_charge : null,
                'tpa_charge_opd' => ($customerType === 'OPD') ? (float)$tpaCharge->org_charge : null,
                'standard_charge' => $standardCharge,
            ]);
        }
        
        \Log::info("No TPA charge found, returning standard charge: {$standardCharge}");
        return response()->json([
            'tpa_charge_ipd' => null,
            'tpa_charge_opd' => null,
            'standard_charge' => $standardCharge,
        ]);
    }

    /**
     * API: Get prescription tests (radiology tests from IPD prescription)
     */
    public function getPrescriptionTests($prescriptionId)
    {
        $prescription = IpdPrescription::with(['tests.radiology', 'ipd.patient.organisation', 'ipd.organisation'])
            ->find($prescriptionId);
        
        if (!$prescription) {
            return response()->json(['error' => 'Prescription not found'], 404);
        }
        
        // Get radiology tests from the prescription
        // Determine if this is IPD or OPD based on prescription type
        $isIpd = $prescription->ipd !== null;
        $customerType = $isIpd ? 'IPD' : 'OPD';
        
        $radiologyTests = $prescription->tests()
            ->whereNotNull('radiology_id')
            ->with('radiology')
            ->get()
            ->filter(function($test) {
                return $test->radiology !== null;
            })
            ->map(function($test) use ($isIpd) {
                $radiology = $test->radiology;
                // Use IPD charge for IPD prescriptions, OPD charge for OPD
                $standardCharge = $isIpd ? ($radiology->standard_charge_ipd ?? 0) : ($radiology->standard_charge_opd ?? 0);
                $amount = $test->amount ?? $standardCharge;
                
                return [
                    'id' => $radiology->id,
                    'test_name' => $radiology->test_name,
                    'report_days' => $radiology->report_days ?? 0,
                    'tax_percentage' => 0, // Tax is handled separately in billing
                    'amount' => $amount,
                    'standard_charge_ipd' => $radiology->standard_charge_ipd ?? 0,
                    'standard_charge_opd' => $radiology->standard_charge_opd ?? 0,
                ];
            });
        
        // Get TPA - Priority: Patient's TPA > IPD's TPA
        $tpa = null;
        if ($prescription->ipd && $prescription->ipd->patient) {
            if ($prescription->ipd->patient->organisation_id && $prescription->ipd->patient->organisation) {
                $tpa = [
                    'id' => $prescription->ipd->patient->organisation_id,
                    'name' => $prescription->ipd->patient->organisation->organisation_name,
                    'code' => $prescription->ipd->patient->organisation->code,
                ];
            }
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
            'tests' => $radiologyTests,
            'tpa' => $tpa,
        ]);
    }
}

