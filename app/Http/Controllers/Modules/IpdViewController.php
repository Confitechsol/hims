<?php
namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Finding;
use App\Models\IpdCharges;
use App\Models\IpdDetail;
use App\Models\IpdPrescription;
use App\Models\MedicationReport;
use App\Models\MedicineCategory;
use App\Models\MedicineDosage;
use App\Models\NurseNote;
use App\Models\Operation;
use App\Models\OperationCategory;
use App\Models\OperationTheatre;
use App\Models\PathologyReport;
use App\Models\Patient;
use App\Models\PatientBedHistory;
use App\Models\PatientTimeline;
use App\Models\PatientVital;
use App\Models\Pharmacy;
use App\Models\Symptom;
use App\Models\Vital;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IpdViewController extends Controller
{
    public function showIpd(Request $request, $id)
    {
        $ipd             = IpdDetail::with('patient.bloodGroup', 'patient.organisation', 'doctor', 'bedDetail', 'bedGroup', 'treatmentHistory')->where('id', $id)->firstOrFail();
        $bedShiftHistory = PatientBedHistory::with('ipd', 'bedGroup', 'bed')->where('is_active', 'yes')->where('ipd_id', $id)->first();
        $symptomIds      = array_filter(
            explode(',', $ipd->symptoms_title),
            fn($id) => $id !== null && trim($id) !== ''
        );

        // Fetch symptoms related to this OPD
        $symptoms = ! empty($symptomIds)
            ? Symptom::whereIn('id', $symptomIds)->get()
            : collect();
        $nurseNotes       = NurseNote::with('staff')->where('ipd_id', $id)->get();
        $medicationReport = MedicationReport::with('medicineDosage.unit', 'pharmacy.medicineCategory', 'generatedBy.userRole')->where('ipd_id', $id)->get();
        //dd($medicationReport);
        $ipdCharges        = IpdCharges::with('ipd', 'charge.taxCategory', 'chargeCategory.chargeType')->where('ipd_id', $id)->get();
        $labInvestigations = PathologyReport::with('pathology')->where('patient_id', $ipd->patient->id)->get();
        $ipdPrescriptions  = IpdPrescription::where('ipd_id', $id)->get();
        $transactions      = Transaction::with('ipd')->where('ipd_id', $ipd->id)->where('patient_id', $ipd->patient->id)->get();
        // $transactions = Transaction::where('ipd_id', $ipd->id)->where('section', 'ipd')->where('type', 'payment')
        // ->orderBy('transaction_date', 'desc')
        // ->get();
        $doctors           = Doctor::all();
        //dd($id);
        $ipdFindings = [];
        foreach ($ipdPrescriptions as $pres) {
            // Split comma-separated symptom IDs and clean up
            $findingIds = array_filter(
                explode(',', $pres->findings),
                fn($id) => $id !== null && trim($id) !== ''
            );

            // Fetch symptoms related to this OPD
            $findings = ! empty($findingIds)
                ? Finding::whereIn('id', $findingIds)->get()
                : collect();

            // Store in array using OPD number as key
            $ipdFindings[$pres->ipd_id] = $findings;
        }
        $bedHistories    = PatientBedHistory::with('bedGroup', 'bed')->where('ipd_id', $id)->get();
        $operationDetail = OperationTheatre::with('operation.category')->where('ipd_details_id', $id)->get();

        $pharmacyDetails     = Pharmacy::all();
        $medicinesByCategory = Pharmacy::select('id', 'medicine_name', 'medicine_category_id')
            ->get()
            ->groupBy('medicine_category_id');
        $dosages = MedicineDosage::select('id', 'medicine_category_id', 'dosage')
            ->get()
            ->groupBy('medicine_category_id');

        $medicineCategories = MedicineCategory::all();

        $medDosages          = MedicineDosage::all();
        $operationCategories = OperationCategory::all();
        $operations          = Operation::all();

        $vitals     = Vital::all();
        $patient_id = $ipd->patient_id;

        $PatientTimelines = PatientTimeline::with('patient')->where('patient_id', $patient_id)->get();
        $vitalDetails     = PatientVital::with('vital')->where('patient_id', $patient_id)->get();

        return view('admin.ipd.ipd_view', compact(
            'ipd',
            'doctors',
            'medDosages',
            'operationCategories',
            'transactions',
            'operations',
            'symptoms',
            'nurseNotes',
            'medicationReport',
            'labInvestigations',
            'ipdPrescriptions',
            'ipdFindings',
            'bedHistories',
            'ipdCharges',
            'pharmacyDetails',
            'bedShiftHistory',
            'medicineCategories',
            'operationDetail',
            'medicinesByCategory',
            'PatientTimelines',
            'vitalDetails',
            'vitals',
            'dosages'
        ));
    }
    public function store(Request $request)
    {
        // 🔍 Validation
        $validated = $request->validate([
            'ipd_id'   => 'required|exists:ipd_details,id',
            'date'     => 'required|date',
            'time'     => 'required',
            'medi_cat' => 'required|exists:medicine_category,id',
            'med_name' => 'required|exists:pharmacy,id',
            'dosage'   => 'required|exists:medicine_dosage,id',
            'remark'   => 'nullable|string',
        ]);

        // 📝 Store Medication Report
        MedicationReport::create([
            'ipd_id'             => $request->ipd_id,
            'hospital_id'        => Auth::user()->hospital_id,
            'branch_id'          => Auth::user()->branch_id ?? null,
            'pharmacy_id'        => $request->med_name,
            'medicine_dosage_id' => $request->dosage,
            'date'               => $request->date,
            'time'               => $request->time,
            'remark'             => $request->remark,
            'generated_by'       => Auth::id(), // optional if needed
        ]);

        return redirect()
            ->back()
            ->with('success', 'Medication added successfully!');
    }
    public function update(Request $request)
    {
        // Validate fields
        $request->validate([
            'id'       => 'required|integer',
            'ipd_id'   => 'required|integer',
            'date'     => 'required|date',
            'time'     => 'required',
            'medi_cat' => 'required|integer',
            'med_name' => 'required|integer',
            'dosage'   => 'required|integer',
            'remark'   => 'nullable|string|max:255',
        ]);

        // Find the medication record
        $medication = MedicationReport::find($request->id);

        if (! $medication) {
            return back()->with('error', 'Medication record not found.');
        }

        // Update the record
        $medication->update([
            'ipd_id'   => $request->ipd_id,
            'date'     => $request->date,
            'time'     => $request->time,
            'medi_cat' => $request->medi_cat,
            'med_name' => $request->med_name,
            'dosage'   => $request->dosage,
            'remark'   => $request->remark,
        ]);

        return back()->with('success', 'Medication updated successfully!');
    }
    public function delete($id)
    {
        MedicationReport::findOrFail($id)->delete(); // Now this will soft delete

        return back()->with('success', 'Medication deleted successfully');
    }
    public function storeOperation(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'ipd_details_id'    => 'required|integer',
            'customer_type'     => 'nullable|string',
            'operation_id'      => 'nullable|integer',
            'date'              => 'required|date',
            'operation_type'    => 'nullable|string|max:255',
            'consultant_doctor' => 'nullable|string|max:255',
            'ass_consultant_1'  => 'nullable|string|max:255',
            'ass_consultant_2'  => 'nullable|string|max:255',
            'anesthetist'       => 'nullable|string|max:255',
            'anaethesia_type'   => 'nullable|string|max:255',
            'ot_technician'     => 'nullable|string|max:255',
            'ot_assistant'      => 'nullable|string|max:255',
            'result'            => 'nullable|string|max:255',
            'remark'            => 'nullable|string|max:1000',
            'generated_by'      => 'nullable|string|max:255',
            // Remove reference_no from request validation since it will be generated
        ]);

        // Fetch the prefix from the prefixes table
        $prefix = DB::table('prefixes')
            ->where('type', 'operation_ref_no')
            ->value('prefix'); // assuming the table has a 'prefix' column

        // Generate the reference number by combining prefix and auto-incremented ID
        // Option 1: Get the last inserted operation id
        $lastId      = DB::table('operation_theatre')->max('id') ?? 0;
        $referenceNo = $prefix . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        // e.g., OT-0001, OT-0002

        // Add reference_no to the validated data
        $validated['reference_no'] = $referenceNo;

        // Create a new operation record
        $operation = OperationTheatre::create($validated);

        return redirect()->back()->with('success', 'Operation added successfully. Reference No: ' . $referenceNo);
    }

    public function updateOperation(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'ipd_details_id'    => 'required|integer',
            'customer_type'     => 'nullable|string',
            'operation_id'      => 'nullable|integer',
            'date'              => 'required|date',
            'operation_type'    => 'nullable|string|max:255',
            'consultant_doctor' => 'nullable|string|max:255',
            'ass_consultant_1'  => 'nullable|string|max:255',
            'ass_consultant_2'  => 'nullable|string|max:255',
            'anesthetist'       => 'nullable|string|max:255',
            'anaethesia_type'   => 'nullable|string|max:255',
            'ot_technician'     => 'nullable|string|max:255',
            'ot_assistant'      => 'nullable|string|max:255',
            'result'            => 'nullable|string|max:255',
            'remark'            => 'nullable|string|max:1000',
            'generated_by'      => 'nullable|string|max:255',
            // DO NOT validate reference_no → we keep existing one
        ]);

        // Find the record
        $operation = OperationTheatre::findOrFail($id);

        // Update record
        $operation->update($validated);

        return redirect()->back()->with('success', 'Operation updated successfully.');
    }

}
