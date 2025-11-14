<?php
namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\ChargeCategory;
use App\Models\Doctor;
use App\Models\MedicationReport;
use App\Models\OpdCharges;
use App\Models\OpdDetail;
use App\Models\OpdMedicine;
use App\Models\OpdPatient;
use App\Models\OpdPrescription;
use App\Models\OpdVisits;
use App\Models\OperationTheatre;
use App\Models\PathologyReport;
use App\Models\Patient;
use App\Models\Prefix;
use App\Models\Symptom;
use App\Models\SymptomsClassification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpdController extends Controller
{
    public function index(Request $request)
    {
        $isOpdTab    = $request->get('tab', 'opd') == 'opd';
        $doctors     = Doctor::all();
        $opdSymptoms = [];
        if ($isOpdTab) {
            $opdDetails = OpdDetail::with('patient.bloodGroup', 'doctor', 'chargeCategory', 'charge')->get();
            foreach ($opdDetails as $opdDetail) {
                // Split comma-separated symptom IDs and clean up
                $symptomIds = array_filter(
                    explode(',', $opdDetail->symptoms_title),
                    fn($id) => $id !== null && trim($id) !== ''
                );

                // Fetch symptoms related to this OPD
                $symptoms = ! empty($symptomIds)
                    ? Symptom::whereIn('id', $symptomIds)->get()
                    : collect();

                // Store in array using OPD number as key
                $opdSymptoms[$opdDetail->opd_no] = $symptoms;
            }
            $opd = $opdDetails;
        } else {
            $patients = Patient::with(['opds.doctor'])->get();
            $opd      = $patients;
            // dd($opd[1]->opds[0]->doctor);
            // dd($opd);
        }

        // $opd         = OpdDetail::with('patient', 'doctor', 'chargeCategory', 'charge')->get();
        return view("admin.opd.index", compact("opd", 'opdSymptoms', 'doctors', 'isOpdTab'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'patient_id'           => 'required|exists:patients,id',
            'appointment_date'     => 'required|date',
            'case_type'            => 'required|string',
            'casualty'             => 'required|string',
            'reference'            => 'nullable|string',
            'doctor_id'            => 'required|exists:doctor,id',
            'charge_category'      => 'required|exists:charge_categories,id',
            'charge'               => 'required|exists:charges,id',
            'standard_charge'      => 'required|numeric|min:0',
            'applied_charge'       => 'required|numeric|min:0',
            'discount'             => 'required|numeric|min:0',
            'tax'                  => 'required|numeric|min:0',
            'amount'               => 'required|numeric|min:0',
            'payment_mode'         => 'nullable|string|max:50',
            'payment_date'         => 'nullable|date',
            'paid_amount'          => 'required|numeric|min:0',
            'live_consultation'    => 'nullable|string|max:100',
            'symptoms_type'        => 'required|array',
            'symptoms_type.*'      => 'string',
            'symptoms_title'       => 'required|array',
            'symptoms_title.*'     => 'string',
            'symptoms_description' => 'required|string',
            'allergies'            => 'nullable|string',
            'note'                 => 'nullable|string',
            'status'               => 'nullable|string|max:50',
            'apply_tpa'            => 'nullable|string|max:10',
        ]);

        DB::beginTransaction();
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        try {
            $symptomType          = array_filter($request->symptoms_type, fn($type) => $type !== null && $type !== '');
            $symptomTitle         = array_filter($request->symptoms_title, fn($title) => $title !== null && $title !== '');
            $implodedSymptomType  = implode(", ", $symptomType);
            $implodedSymptomTitle = implode(", ", $symptomTitle);

            $lastOpd = OpdDetail::orderBy('id', 'desc')->first();
            if ($lastOpd && preg_match('/OPDN(\d+)/', $lastOpd->opd_no, $matches)) {
                $lastNumber = intval($matches[1]);
            } else {
                $lastNumber = 0;
            }
            $opdPrefix  = Prefix::where("type", 'opd_no')->firstOrFail();
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $opdNo      = $opdPrefix->prefix . $nextNumber;
            // 🔹 Create OPD record
            $opd        = new OpdDetail();
            $opdPatient = new OpdPatient();
            $opdVistis  = new OpdVisits();
            $opdCharge  = new OpdCharges();
            // dd($opd);
            $opd->hospital_id = $user->hospital_id;
            // Patient Details
            $opd->patient_id = $request->patient_id;
            // Doctor Details
            $opd->doctor_id = $request->doctor_id;

            // Visit Details
            $opd->charge_category_id = $request->charge_category;
            $opd->charge_id          = $request->charge;
            $opd->appointment_date   = $request->appointment_date;
            $opd->case_type          = $request->case_type;
            $opd->casualty           = $request->casualty;
            $opd->reference          = $request->reference;

            // Billing / Payment
            $opd->standard_charge = $request->standard_charge ?? 0;
            $opd->applied_charge  = $request->applied_charge;
            $opd->discount        = $request->discount;
            $opd->tax             = $request->tax ?? 0;
            $opd->amount          = $request->amount ?? 0;
            $opd->paid_amount     = $request->paid_amount;
            $opd->payment_date    = $request->payment_date;
            $opd->payment_mode    = $request->payment_mode;

            // Misc
            $opd->live_consultation    = $request->live_consultation;
            $opd->symptoms_type        = $implodedSymptomType ?? "";
            $opd->symptoms_title       = $implodedSymptomTitle ?? "";
            $opd->symptoms_description = $request->symptoms_description;
            $opd->allergies            = $request->allergies;
            $opd->note                 = $request->note;
            $opd->status               = $request->status ?? 'Active';
            $opd->created_by           = Auth::user()->id ?? null;
            $opd->opd_no               = $opdNo;
            // Save OPD Record
            $opd->save();

            // dd($opd->id);
            $opdPatient->patient_id = $request->patient_id ?? null;
            $opdPatient->opd_id     = $opd->id ?? null;
            $opdPatient->doctor_id  = $request->doctor_id ?? null;
            $opdPatient->save();

            $opdCharge->opd_id         = $opd->id;
            $opdCharge->charge_id      = $opd->charge_id;
            $opdCharge->discount       = $opd->discount;
            $opdCharge->charge_type_id = $opd->charge_category_id;
            $opdCharge->save();

            $lastVisit = OpdVisits::orderBy('id', 'desc')->first();
            if ($lastVisit && preg_match('/OCID(\d+)/', $lastVisit->visit_id, $matches)) {
                $lastNumber = intval($matches[1]);
            } else {
                $lastNumber = 0;
            }
            $visitPrefix = Prefix::where("type", 'opd_checkup_id')->firstOrFail();
            $nextNumber  = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $visitNo     = $visitPrefix->prefix . $nextNumber;

            $opdVistis->visit_id   = $visitNo ?? null;
            $opdVistis->patient_id = $request->patient_id ?? null;
            $opdVistis->opd_id     = $opd->id ?? null;
            $opdVistis->save();

            DB::commit();

            return redirect()->route('opd')->with('success', 'OPD record created successfully . ');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Failed to save OPD record: ' . $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $opd     = OpdDetail::with('patient', 'doctor', 'chargeCategory', 'charge')->where('id', $id)->firstOrFail();
        $doctors = Doctor::all();

        $symptomTypeIds = array_filter(
            explode(', ', $opd->symptoms_type),
            fn($id) => $id !== null && trim($id) !== ''
        );
        $symptomIds = array_filter(
            explode(', ', $opd->symptoms_title),
            fn($id) => $id !== null && trim($id) !== ''
        );

        // Fetch symptoms related to this OPD
        // $symptomTypes = ! empty($symptomTypeIds)
        //     ? SymptomsClassification::whereIn('id', $symptomTypeIds)->get()
        //     : collect(); // return empty collection if no symptoms
        $symptoms = ! empty($symptomIds)
            ? Symptom::whereIn('id', $symptomIds)->get()
            : collect(); // return empty collection if no symptoms

        $allSymptomTypes = SymptomsClassification::all();
        return view('admin.opd.edit-opd', compact('opd', 'doctors', 'symptomTypeIds', 'allSymptomTypes', 'symptoms'));

    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'appointment_date'     => 'required|date',
            'old_patient'          => 'required|string',
            'casualty'             => 'required|string',
            'reference'            => 'nullable|string',
            'consultant_doctor'    => 'required|exists:doctor,id',
            'payment_date'         => 'nullable|date',
            'paid_amount'          => 'required|numeric|min:0',
            'payment_mode'         => 'nullable|string|max:50',
            'symptoms_type'        => 'required|array',
            'symptoms_type.*'      => 'string',
            'symptoms_title'       => 'required|array',
            'symptoms_title.*'     => 'string',
            'symptoms_description' => 'required|string',
            'known_allergies'      => 'nullable|string',
            'note'                 => 'nullable|string',
        ]);

        DB::beginTransaction();
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        try {
            $symptomType          = array_filter($request->symptoms_type, fn($type) => $type !== null && $type !== '');
            $symptomTitle         = array_filter($request->symptoms_title, fn($title) => $title !== null && $title !== '');
            $implodedSymptomType  = implode(", ", $symptomType);
            $implodedSymptomTitle = implode(", ", $symptomTitle);
            // 🔹 Update OPD record
            $opd = OpdDetail::findOrFail($id);
            // dd($opd);
            // Doctor Details
            $opd->doctor_id = $request->consultant_doctor;

            // Visit Details
            $opd->appointment_date = $request->appointment_date;
            $opd->case_type        = $request->old_patient;
            $opd->casualty         = $request->casualty;
            $opd->reference        = $request->reference;

            // Billing / Payment
            $opd->paid_amount  = $request->paid_amount;
            $opd->payment_date = $request->payment_date;
            $opd->payment_mode = $request->payment_mode;

            // Misc
            $opd->symptoms_type        = $implodedSymptomType ?? "";
            $opd->symptoms_title       = $implodedSymptomTitle ?? "";
            $opd->symptoms_description = $request->symptoms_description;
            $opd->allergies            = $request->known_allergies;
            $opd->note                 = $request->note;
            // Save OPD Record
            $opd->save();

            DB::commit();

            return redirect()->route('opd')->with('success', 'OPD record Updated successfully . ');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Failed to save OPD record: ' . $e->getMessage());
        }
    }

    public function getDoctors(Request $request)
    {
        $doctors = Doctor::all();
        return response()->json($doctors, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getChargeCategories(Request $request)
    {
        $chargeCategories = ChargeCategory::all();
        return response()->json($chargeCategories, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getCharges(Request $request, $id)
    {
        // dd($id);
        $charges = Charge::with('taxCategory')->where('charge_category_id', $id)->get();
        // dd($charges);
        return response()->json($charges, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getSymptomsType(Request $request)
    {
        // dd($id);
        $symptomTypes = SymptomsClassification::all();
        // dd($charges);
        return response()->json($symptomTypes, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getSymptoms(Request $request)
    {
        $selectedTypeIds = $request->input('selectedTypeIds', []);
        if (empty($selectedTypeIds)) {
            return response()->json(['message' => 'No type IDs provided'], 400);
        }
        $symptoms = Symptom::whereIn('type', $selectedTypeIds)->get();
        // dd($symptoms);

        return response()->json($symptoms, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function showOpd(Request $request, $id)
    {
        $opd        = OpdDetail::with('patient.bloodGroup', 'patient.organisation', 'doctor', 'chargeCategory', 'charge')->where('id', $id)->firstOrFail();
        $symptomIds = array_filter(
            explode(',', $opd->symptoms_title),
            fn($id) => $id !== null && trim($id) !== ''
        );

        // Fetch symptoms related to this OPD
        $symptoms = ! empty($symptomIds)
            ? Symptom::whereIn('id', $symptomIds)->get()
            : collect();

        $medicationReport  = MedicationReport::with('medicineDosage.unit', 'pharmacy', 'generatedBy.userRole')->where('opd_details_id', $id)->get();
        $operationDetail   = OperationTheatre::with('operation.category')->where('opd_details_id', $id)->get();
        $opdCharges        = OpdCharges::with('opd', 'charge.taxCategory', 'chargeCategory.chargeType')->where('opd_id', $id)->get();
        $labInvestigations = PathologyReport::with('pathology')->where('patient_id', $opd->patient->id)->get();
        $opdVisits         = OpdVisits::with('patient', 'opd.doctor')->where('opd_id', $id)->get();
        $opdSymptoms       = [];
        foreach ($opdVisits as $opdDetail) {
            // Split comma-separated symptom IDs and clean up
            $symptomIds = array_filter(
                explode(',', $opdDetail->opd->symptoms_title),
                fn($id) => $id !== null && trim($id) !== ''
            );

            // Fetch symptoms related to this OPD
            $symptoms = ! empty($symptomIds)
                ? Symptom::whereIn('id', $symptomIds)->get()
                : collect();

            // Store in array using OPD number as key
            $opdSymptoms[$opdDetail->opd->opd_no] = $symptoms;
        }
        // Store in array using OPD number as key
        return view('admin.opd.opd_view', compact('opd', 'symptoms', 'medicationReport', 'operationDetail', 'opdCharges', 'labInvestigations', 'opdVisits', 'opdSymptoms'));
    }

    public function storePrescription(Request $request)
    {
        // dd($request->all());
        try { $request->validate([
            'opd_id'              => 'nullable|string',
            'header_note'         => 'nullable|string',
            'footer_note'         => 'nullable|string',
            'finding_description' => 'nullable|string',
            'finding_print'       => 'nullable|string',
            'finding_type'        => 'nullable|array',
            'finding_type.*'      => 'string',
            'findings'            => 'nullable|array',
            'findings.*'          => 'string',
            'pathology'           => 'nullable|array',
            'pathology.*'         => 'string',
            'radiology'           => 'nullable|array',
            'radiology.*'         => 'string',
            'visible'             => 'nullable|array',
            'visible.*'           => 'string',
            'medicines'           => 'nullable|array',
            'medicines.*'         => 'string',
            'dosages'             => 'nullable|array',
            'dosages.*'           => 'string',
            'interval_dosages'    => 'nullable|array',
            'interval_dosages.*'  => 'string',
            'duration_dosages'    => 'nullable|array',
            'duration_dosages.*'  => 'string',
            'instructions'        => 'nullable|array',
            'instructions.*'      => 'string',
        ]);
            $findingTypes         = array_filter($request->finding_type, fn($type) => $type !== null && $type !== '');
            $findings             = array_filter($request->findings, fn($title) => $title !== null && $title !== '');
            $pathology_ids        = array_filter($request->pathology, fn($pathology) => $pathology !== null && $pathology !== '');
            $radiology_ids        = array_filter($request->radiology, fn($radio) => $radio !== null && $radio !== '');
            $notification_to      = array_filter($request->visible, fn($notify) => $notify !== null && $notify !== '');
            $implodedFindingTypes = implode(", ", $findingTypes);
            $implodedFindings     = implode(", ", $findings);
            $implodedPathologies  = implode(", ", $pathology_ids);
            $implodedRadiologies  = implode(", ", $radiology_ids);
            $implodedVisibles     = implode(", ", $notification_to);
            $prescription         = OpdPrescription::create([
                'opd_id'              => $request->opd_id,
                'header_note'         => $request->header_note ?? null,
                'footer_note'         => $request->footer_note ?? null,
                'finding_description' => $request->finding_description ?? null,
                'is_finding_print'    => $request->finding_print ?? null,
                'date'                => Carbon::now()->toDateString(),
                'finding_categories'  => $implodedFindingTypes,
                'findings'            => $implodedFindings,
                'pathology_id'        => $implodedPathologies,
                'radiology_id'        => $implodedRadiologies,
                'notification_to'     => $implodedVisibles,
            ]);

            foreach ($request->medicines as $i => $med) {

                OpdMedicine::create([
                    "prescription_id"    => $prescription->id,
                    "pharmacy_id"        => intval($med),
                    "medicine_dosage_id" => intval($request->dosages[$i]), //$input['hsn_code'][$i],
                    "dose_interval_id"   => intval($request->interval_dosages[$i]),
                    "dose_duration_id"   => intval($request->duration_dosages[$i]),
                    "instruction"        => $request->instructions[$i],
                ]);
            }
            return redirect()->back()->with('success', 'Prescription created successfully.');} catch (Exception $e) {
            // dd($e);
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function createOpdMedication(Request $request)
    {
        try {

            $request->validate([
                'date'     => 'required|date',
                'time'     => 'required|date_format:H:i',
                'med_cat'  => 'required|exists:medicine_category,id',
                'med_name' => 'required|exists:pharmacy,id',
                'dosage'   => 'required|exists:medicine_dosage,id',
                'remark'   => 'nullable|string',
                'opd_id'   => 'required|exists:opd_details,id',
            ]);

            MedicationReport::create([
                'opd_details_id'     => $request->opd_id,
                'medicine_dosage_id' => $request->dosage,
                'pharmacy_id'        => $request->med_name,
                'date'               => $request->date,
                'time'               => $request->time,
                'remark'             => $request->remark,
                'generated_by'       => 1,
            ]);
            return redirect()->back()->with('success', 'Medication created successfully.');
        } catch (Exception $e) {
            //throw $th;
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

}