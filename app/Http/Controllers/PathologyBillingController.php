<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PathologyBilling;
use App\Models\PathologyReport;
use App\Models\Pathology;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\CaseReference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PathologyBillingController extends Controller
{
    /**
     * Display a listing of pathology bills
     */
    public function index()
    {
        $bills = PathologyBilling::with(['patient', 'doctor'])
            ->orderBy('id', 'desc')
            ->paginate(15);
        
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
        $tests = Pathology::with(['category', 'charge.taxCategory'])->get();
        
        return view('admin.pathology.billing.create', compact('patients', 'doctors', 'tests'));
    }

    /**
     * Store a newly created pathology bill
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
            
            // Create pathology bill
            $bill = PathologyBilling::create([
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
                
        } catch (\Exception $e) {
            DB::rollBack();
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
        $bill = PathologyBilling::with(['patient', 'doctor', 'reports.pathology'])
            ->findOrFail($id);
        
        return view('admin.pathology.billing.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified pathology bill
     */
    public function edit($id)
    {
        $bill = PathologyBilling::with(['patient', 'doctor', 'reports.pathology'])->findOrFail($id);
        $patients = Patient::select('id', 'patient_name', 'mobileno')->get();
        $doctors = Doctor::select('id', 'name', 'surname', 'doctor_id')
            ->where(function($query) {
                $query->where('is_active', true)
                      ->orWhere('is_active', 1)
                      ->orWhere('is_active', 'yes');
            })
            ->get();
        $tests = Pathology::with(['category', 'charge.taxCategory'])->get();
        
        return view('admin.pathology.billing.edit', compact('bill', 'patients', 'doctors', 'tests'));
    }

    /**
     * Update the specified pathology bill
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
            
            // Update pathology bill
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
     * API: Get pathology test details
     */
    public function getTestDetails(Request $request)
    {
        $testId = $request->input('test_id');
        
        $test = Pathology::with(['charge.taxCategory'])->find($testId);
        
        if (!$test) {
            return response()->json(['error' => 'Test not found'], 404);
        }
        
        return response()->json([
            'id' => $test->id,
            'test_name' => $test->test_name,
            'report_days' => $test->report_days,
            'tax_percentage' => $test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0,
            'amount' => $test->amount ?? 0,
        ]);
    }
}

