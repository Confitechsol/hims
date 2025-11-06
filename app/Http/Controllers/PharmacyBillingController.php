<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PharmacyBillBasic;
use App\Models\PharmacyBillDetail;
use App\Models\Pharmacy;
use App\Models\MedicineBatchDetail;
use App\Models\MedicineCategory;
use App\Models\MedicineCompany;
use App\Models\MedicineGroup;
use App\Models\MedicineUnit;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\CaseReference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PharmacyBillingController extends Controller
{
    /**
     * Test method
     */
    public function test()
    {
        return 'PharmacyBillingController test method working!';
    }

    /**
     * Display a listing of pharmacy bills
     */
    public function index()
    {
        try {
            $bills = PharmacyBillBasic::with(['patient', 'generatedBy', 'billDetails.medicineBatch.pharmacy'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('admin.pharmacy.billing.index', compact('bills'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('PharmacyBillingController@index error: ' . $e->getMessage());
            
            // Return a simple response for debugging
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new pharmacy bill
     */
    public function create()
    {
        $categories = MedicineCategory::all();
        $companies = MedicineCompany::all();
        $groups = MedicineGroup::all();
        $units = MedicineUnit::all();
        $patients = Patient::select('id', 'patient_name', 'mobileno')->get();
        $doctors = Doctor::select('id', 'name', 'surname', 'doctor_id')
            ->where(function($query) {
                $query->where('is_active', true)
                      ->orWhere('is_active', 1)
                      ->orWhere('is_active', 'yes');
            })
            ->get();
        $caseReferences = CaseReference::all();

        return view('admin.pharmacy.billing.create', compact(
            'categories', 'companies', 'groups', 'units', 'patients', 'doctors', 'caseReferences'
        ));
    }

    /**
     * Store a newly created pharmacy bill
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'case_reference_id' => 'nullable|exists:case_references,id',
            'doctor_name' => 'nullable|string|max:50',
            'customer_name' => 'nullable|string|max:50',
            'note' => 'nullable|string',
            'total' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_batch_detail_id' => 'required|exists:medicine_batch_details,id',
            'medicines.*.quantity' => 'required|numeric|min:1',
            'medicines.*.sale_price' => 'required|numeric|min:0',
            'medicines.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Create pharmacy bill basic record
            $bill = PharmacyBillBasic::create([
                'date' => now(),
                'patient_id' => $validated['patient_id'],
                'case_reference_id' => $validated['case_reference_id'],
                'customer_name' => $validated['customer_name'],
                'customer_type' => 'OPD', // Default to OPD since we removed the dropdown
                'doctor_name' => $validated['doctor_name'],
                'total' => $validated['total'],
                'discount_percentage' => $validated['discount_percentage'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'tax_percentage' => $validated['tax_percentage'],
                'tax' => $validated['tax'] ?? 0,
                'net_amount' => $validated['net_amount'],
                'note' => $validated['note'],
                'generated_by' => Auth::id(),
            ]);

            // Create bill details
            foreach ($validated['medicines'] as $medicine) {
                PharmacyBillDetail::create([
                    'pharmacy_bill_basic_id' => $bill->id,
                    'medicine_batch_detail_id' => $medicine['medicine_batch_detail_id'],
                    'quantity' => $medicine['quantity'],
                    'sale_price' => $medicine['sale_price'],
                    'amount' => $medicine['amount'],
                ]);
            }

            DB::commit();

            return redirect()->route('pharmacy.billing.index')
                ->with('success', 'Pharmacy bill created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to create pharmacy bill: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified pharmacy bill
     */
    public function show($id)
    {
        $bill = PharmacyBillBasic::with([
            'patient', 
            'generatedBy', 
            'caseReference',
            'billDetails.medicineBatch.pharmacy',
            'billDetails.medicineBatch.pharmacy.category',
            'billDetails.medicineBatch.pharmacy.company'
        ])->findOrFail($id);

        return view('admin.pharmacy.billing.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified pharmacy bill
     */
    public function edit($id)
    {
        $bill = PharmacyBillBasic::with(['billDetails.medicineBatch.pharmacy'])->findOrFail($id);
        $categories = MedicineCategory::all();
        $companies = MedicineCompany::all();
        $groups = MedicineGroup::all();
        $units = MedicineUnit::all();
        $patients = Patient::select('id', 'patient_name', 'mobileno')->get();
        $doctors = Doctor::select('id', 'name', 'surname', 'doctor_id')
            ->where(function($query) {
                $query->where('is_active', true)
                      ->orWhere('is_active', 1)
                      ->orWhere('is_active', 'yes');
            })
            ->get();
        $caseReferences = CaseReference::all();

        return view('admin.pharmacy.billing.edit', compact(
            'bill', 'categories', 'companies', 'groups', 'units', 'patients', 'doctors', 'caseReferences'
        ));
    }

    /**
     * Update the specified pharmacy bill
     */
    public function update(Request $request, $id)
    {
        $bill = PharmacyBillBasic::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'case_reference_id' => 'nullable|exists:case_references,id',
            'doctor_name' => 'nullable|string|max:50',
            'customer_name' => 'nullable|string|max:50',
            'customer_type' => 'nullable|string|max:50',
            'note' => 'nullable|string',
            'total' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_batch_detail_id' => 'required|exists:medicine_batch_details,id',
            'medicines.*.quantity' => 'required|numeric|min:1',
            'medicines.*.sale_price' => 'required|numeric|min:0',
            'medicines.*.amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Update pharmacy bill basic record
            $bill->update([
                'patient_id' => $validated['patient_id'],
                'case_reference_id' => $validated['case_reference_id'],
                'customer_name' => $validated['customer_name'],
                'customer_type' => $validated['customer_type'],
                'doctor_name' => $validated['doctor_name'],
                'total' => $validated['total'],
                'discount_percentage' => $validated['discount_percentage'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'tax_percentage' => $validated['tax_percentage'],
                'tax' => $validated['tax'] ?? 0,
                'net_amount' => $validated['net_amount'],
                'note' => $validated['note'],
            ]);

            // Delete existing bill details
            $bill->billDetails()->delete();

            // Create new bill details
            foreach ($validated['medicines'] as $medicine) {
                PharmacyBillDetail::create([
                    'pharmacy_bill_basic_id' => $bill->id,
                    'medicine_batch_detail_id' => $medicine['medicine_batch_detail_id'],
                    'quantity' => $medicine['quantity'],
                    'sale_price' => $medicine['sale_price'],
                    'amount' => $medicine['amount'],
                ]);
            }

            DB::commit();

            return redirect()->route('pharmacy.billing.index')
                ->with('success', 'Pharmacy bill updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update pharmacy bill: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified pharmacy bill
     */
    public function destroy($id)
    {
        $bill = PharmacyBillBasic::findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete bill details first
            $bill->billDetails()->delete();
            
            // Delete the bill
            $bill->delete();

            DB::commit();

            return redirect()->route('pharmacy.billing.index')
                ->with('success', 'Pharmacy bill deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to delete pharmacy bill: ' . $e->getMessage()]);
        }
    }

    /**
     * Print pharmacy bill
     */
    public function print($id)
    {
        $bill = PharmacyBillBasic::with([
            'patient', 
            'generatedBy', 
            'caseReference',
            'billDetails.medicineBatch.pharmacy',
            'billDetails.medicineBatch.pharmacy.category',
            'billDetails.medicineBatch.pharmacy.company'
        ])->findOrFail($id);

        return view('admin.pharmacy.billing.print', compact('bill'));
    }

    /**
     * Search pharmacy bills
     */
    public function search(Request $request)
    {
        $query = PharmacyBillBasic::with(['patient', 'generatedBy']);

        if ($request->filled('bill_no')) {
            $query->where('id', $request->bill_no);
        }

        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->patient_name . '%')
                  ->orWhere('last_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('doctor_name')) {
            $query->where('doctor_name', 'like', '%' . $request->doctor_name . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $bills = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.pharmacy.billing.index', compact('bills'));
    }

    /**
     * Get medicines by category (AJAX)
     */
    public function getMedicines(Request $request)
    {
        $medicines = Pharmacy::where('medicine_category_id', $request->category_id)
            ->where('is_active', 'yes')
            ->with(['category', 'company', 'group', 'unitType'])
            ->get();

        return response()->json($medicines);
    }

    /**
     * Get medicine batches (AJAX)
     */
    public function getMedicineBatches($pharmacyId)
    {
        $batches = MedicineBatchDetail::where('pharmacy_id', $pharmacyId)
            ->whereRaw('available_quantity > used_quantity')
            ->with(['pharmacy'])
            ->get()
            ->map(function($batch) {
                return [
                    'id' => $batch->id,
                    'batch_no' => $batch->batch_no,
                    'expiry' => $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : null,
                    'available_quantity' => $batch->available_quantity - $batch->used_quantity,
                    'sale_rate' => $batch->sale_rate,
                    'medicine_name' => $batch->pharmacy->medicine_name ?? 'N/A',
                    'gst_percentage' => $batch->pharmacy->gst_percentage ?? 0,
                ];
            });

        return response()->json($batches);
    }

    /**
     * Get batch details (AJAX)
     */
    public function getBatchDetails(Request $request)
    {
        $batch = MedicineBatchDetail::with(['pharmacy'])
            ->findOrFail($request->batch_id);

        $availableQty = $batch->available_quantity - $batch->used_quantity;

        return response()->json([
            'id' => $batch->id,
            'batch_no' => $batch->batch_no,
            'expiry' => $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : null,
            'available_quantity' => $availableQty,
            'sale_rate' => $batch->sale_rate,
            'medicine_name' => $batch->pharmacy->medicine_name ?? 'N/A',
            'gst_percentage' => $batch->pharmacy->gst_percentage ?? 0,
        ]);
    }

    /**
     * Get patient prescriptions/case references (AJAX)
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
}