<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RadiologyBilling;
use App\Models\RadiologyReport;
use App\Models\Radio;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\CaseReference;
use App\Models\Organisation;
use App\Models\OrganisationsCharge;
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
        $tests = Radio::with(['radiologyCategory', 'charge.taxCategory'])->get();
        
        return view('admin.radiology.billing.create', compact('patients', 'doctors', 'tests'));
    }

    /**
     * Store a newly created radiology bill
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'case_reference_id' => 'nullable|exists:case_references,id',
            'doctor_id' => 'nullable|exists:doctors,id',
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
            'activate_tpa' => 'nullable|boolean',
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
            
            // Create radiology bill
            $bill = RadiologyBilling::create([
                'date' => $validated['date'],
                'patient_id' => $validated['patient_id'],
                'case_reference_id' => $validated['case_reference_id'] ?? null,
                'doctor_id' => $validated['doctor_id'] ?? null,
                'doctor_name' => $validated['doctor_name'] ?? null,
                'total' => $validated['total'],
                'discount_percentage' => $validated['discount_percentage'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'net_amount' => $validated['net_amount'],
                'note' => $validated['note'] ?? null,
                'organisation_id' => ($request->has('activate_tpa') && $request->activate_tpa) ? ($validated['organisation_id'] ?? null) : null,
                'generated_by' => Auth::id(),
                'hospital_id' => $user->hospital_id ?? '',
                'branch_id' => $user->branch_id ?? '',
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
                    'hospital_id' => $user->hospital_id ?? '',
                    'branch_id' => $user->branch_id ?? '',
                ]);
            }

            DB::commit();
            
            return redirect()->route('radiology.billing.index')
                ->with('success', 'Radiology bill created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
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
        $bill = RadiologyBilling::with(['patient', 'doctor', 'reports.radiology'])
            ->findOrFail($id);
        
        return view('admin.radiology.billing.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified radiology bill
     */
    public function edit($id)
    {
        $bill = RadiologyBilling::with(['patient', 'doctor', 'reports.radiology'])->findOrFail($id);
        $patients = Patient::select('id', 'patient_name', 'mobileno')->get();
        $doctors = Doctor::select('id', 'name', 'surname', 'doctor_id')
            ->where(function($query) {
                $query->where('is_active', true)
                      ->orWhere('is_active', 1)
                      ->orWhere('is_active', 'yes');
            })
            ->get();
        $tests = Radio::with(['radiologyCategory', 'charge.taxCategory'])->get();
        
        return view('admin.radiology.billing.edit', compact('bill', 'patients', 'doctors', 'tests'));
    }

    /**
     * Update the specified radiology bill
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'case_reference_id' => 'nullable|exists:case_references,id',
            'doctor_id' => 'nullable|exists:doctors,id',
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
            'activate_tpa' => 'nullable|boolean',
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
            
            // Update radiology bill
            $bill->update([
                'date' => $validated['date'],
                'patient_id' => $validated['patient_id'],
                'case_reference_id' => $validated['case_reference_id'] ?? null,
                'doctor_id' => $validated['doctor_id'] ?? null,
                'doctor_name' => $validated['doctor_name'] ?? null,
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
            $user = Auth::user();
            foreach ($validated['tests'] as $test) {
                RadiologyReport::create([
                    'radiology_bill_id' => $bill->id,
                    'radiology_id' => $test['radiology_id'],
                    'patient_id' => $validated['patient_id'],
                    'reporting_date' => $test['report_date'],
                    'tax_percentage' => $test['tax_percentage'] ?? 0,
                    'apply_charge' => $test['amount'],
                    'customer_type' => 'OPD',
                    'hospital_id' => $user->hospital_id ?? '',
                    'branch_id' => $user->branch_id ?? '',
                ]);
            }

            DB::commit();
            
            return redirect()->route('radiology.billing.index')
                ->with('success', 'Radiology bill updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
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
     * API: Get patient prescriptions
     */
    public function getPatientPrescriptions($patientId)
    {
        $caseReferences = CaseReference::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->select('id', 'case_id', 'appointment_date as date', 'symptoms', 'reference_doctor')
            ->get()
            ->map(function($case) {
                return [
                    'id' => $case->id,
                    'case_id' => $case->case_id ?? 'Case #' . $case->id,
                    'date' => $case->date ? date('Y-m-d', strtotime($case->date)) : null,
                    'symptoms' => $case->symptoms,
                    'doctor' => $case->reference_doctor,
                ];
            });
        
        return response()->json($caseReferences);
    }

    /**
     * API: Get radiology test details
     */
    public function getTestDetails(Request $request)
    {
        $testId = $request->input('test_id');
        
        $test = Radio::with(['charge.taxCategory'])->find($testId);
        
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }
        
        return response()->json([
            'id' => $test->id,
            'test_name' => $test->test_name,
            'report_days' => $test->report_days,
            'tax_percentage' => $test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0,
            'amount' => $test->charge ? $test->charge->standard_charge : 0,
        ]);
    }

    /**
     * API: Get TPA names for a patient from previous radiology bills
     */
    public function getPatientTpas($patientId)
    {
        $tpas = RadiologyBilling::where('patient_id', $patientId)
            ->whereNotNull('organisation_id')
            ->with('organisation')
            ->select('organisation_id')
            ->distinct()
            ->get()
            ->map(function($billing) {
                return [
                    'id' => $billing->organisation_id,
                    'name' => $billing->organisation ? $billing->organisation->organisation_name : 'Unknown TPA',
                    'code' => $billing->organisation ? $billing->organisation->code : null,
                ];
            });
        
        return response()->json($tpas);
    }

    /**
     * API: Get TPA charge for a specific test and TPA
     */
    public function getTpaCharge(Request $request)
    {
        $testId = $request->input('test_id');
        $organisationId = $request->input('organisation_id');
        
        if (!$testId || !$organisationId) {
            return response()->json(['error' => 'Test ID and Organisation ID are required'], 400);
        }
        
        $test = Radio::find($testId);
        
        if (!$test || !$test->charge_id) {
            return response()->json(['tpa_charge' => null, 'standard_charge' => $test->charge ? $test->charge->standard_charge : 0]);
        }
        
        $tpaCharge = OrganisationsCharge::where('charge_id', $test->charge_id)
            ->where('org_id', $organisationId)
            ->first();
        
        $standardCharge = $test->charge ? $test->charge->standard_charge : 0;
        
        return response()->json([
            'tpa_charge' => $tpaCharge ? $tpaCharge->org_charge : null,
            'standard_charge' => $standardCharge,
        ]);
    }
}

