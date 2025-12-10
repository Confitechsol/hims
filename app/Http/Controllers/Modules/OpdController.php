<?php
namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\ChargeCategory;
use App\Models\ChargeTypeMaster;
use App\Models\Doctor;
use App\Models\MedicationReport;
use App\Models\MedicineCategory;
use App\Models\MedicineDosage;
use App\Models\MedicineGroup;
use App\Models\Pharmacy;
use App\Models\OpdCharges;
use App\Models\OpdDetail;
use App\Models\OpdMedicine;
use App\Models\OpdPatient;
use App\Models\OpdPrescription;
use App\Models\OpdVisits;
use App\Models\VisitDetail;
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
use Illuminate\Support\Facades\Validator;
use App\Models\Vital;
use App\Models\PatientTimeline;
use App\Models\PatientVital;

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

            $lastVisit = VisitDetail::orderBy('id', 'desc')->first();
            if ($lastVisit && preg_match('/OCID(\d+)/', $lastVisit->checkup_id, $matches)) {
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

            // ===============================
            // 🔹 Save Visit Detail (checkup)
            // ===============================
            $visitDetail = new VisitDetail();

            $visitDetail->hospital_id       = $user->hospital_id;
            $visitDetail->branch_id         = $user->branch_id;
            $visitDetail->checkup_id        = $visitNo ?? null;
            $visitDetail->opd_details_id    = $opd->id;           // link to opd_details
            $visitDetail->patient_id        = $request->patient_id;
            $visitDetail->organisation_id   = $request->organisation_id ?? null;
            $visitDetail->patient_charge_id = $opdCharge->id ?? null;
            $visitDetail->transaction_id    = null;               // add later when payment comes
            $visitDetail->cons_doctor       = $request->doctor_id;
            $visitDetail->case_type         = $request->case_type;
            $visitDetail->appointment_date  = $request->appointment_date;

            $visitDetail->symptoms_type     = $implodedSymptomType;
            $visitDetail->symptoms          = $implodedSymptomTitle ?? "";

            $visitDetail->bp                = $request->bp;
            $visitDetail->height            = $request->height;
            $visitDetail->weight            = $request->weight;
            $visitDetail->pulse             = $request->pulse;
            $visitDetail->temperature       = $request->temperature;
            $visitDetail->respiration       = $request->respiration;

            $visitDetail->known_allergies   = $request->allergies;
            $visitDetail->note              = $request->note;
            $visitDetail->note_remark       = $request->symptoms_description;

            $visitDetail->payment_mode      = $request->payment_mode;
            $visitDetail->generated_by      = $user->id;
            $visitDetail->live_consult      = $request->live_consultation;

            $visitDetail->patient_old       = $request->case_type == "Old Patient" ? 1 : 0;
            $visitDetail->casualty          = $request->casualty;
            $visitDetail->refference        = $request->reference;
            $visitDetail->date              = date('Y-m-d');

            $visitDetail->is_antenatal      = 0;
            $visitDetail->can_delete        = 1;

            $visitDetail->save();


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
        //dd($request->all());
        // $request->validate([
        //     'appointment_date'     => 'required|date',
        //     'old_patient'          => 'required|string',
        //     'casualty'             => 'required|string',
        //     'reference'            => 'nullable|string',
        //     'consultant_doctor'    => 'required|exists:doctor,id',
        //     'payment_date'         => 'nullable|date',
        //     'paid_amount'          => 'required|numeric|min:0',
        //     'payment_mode'         => 'nullable|string|max:50',
        //     'symptoms_type'        => 'required|array',
        //     'symptoms_type.*'      => 'string',
        //     'symptoms_title'       => 'required|array',
        //     'symptoms_title.*'     => 'string',
        //     'symptoms_description' => 'required|string',
        //     'known_allergies'      => 'nullable|string',
        //     'note'                 => 'nullable|string',
        // ]);
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            dd($validator->errors()->all());  // 👈 will show validation errors
        }

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
        $vitals = Vital::all();
        
        $vitalDetails = PatientVital::with('vital')->where('patient_id', $opd->patient->id)->get();
        $PatientTimelines = PatientTimeline::with('patient')->where('patient_id', $opd->patient->id)->get();
        $medicineCategories = MedicineCategory::all();
        $pharmacyDetails = Pharmacy::all();
        $medicinesByCategory = Pharmacy::select('id', 'medicine_name', 'medicine_category_id')
                                    ->get()
                                    ->groupBy('medicine_category_id');
        $dosages = MedicineDosage::select('id', 'medicine_category_id', 'dosage')
                        ->get()
                        ->groupBy('medicine_category_id');
        $medicationReport  = MedicationReport::with('medicineDosage.unit', 'pharmacy', 'generatedBy.userRole')->where('opd_details_id', $id)->get();
        $medDosages = MedicineDosage::all();
        $operationDetail   = OperationTheatre::with('operation.category')->where('opd_details_id', $id)->get();
        $opdCharges        = OpdCharges::with('opd', 'charge.taxCategory', 'chargeCategory.chargeType')->where('opd_id', $id)->get();
        $labInvestigations = PathologyReport::with('pathology')->where('patient_id', $opd->patient->id)->get();
        // $opdVisits         = OpdVisits::with('patient', 'opd.doctor')->where('opd_id', $id)->get();
        $opdVisits         = VisitDetail::with('opdDetail', 'doctor')->where('opd_details_id', $id)->get();
        $opdSymptoms       = [];
        foreach ($opdVisits as $opdDetail) {
            // Split comma-separated symptom IDs and clean up
            $symptomIds = array_filter(
                explode(',', $opdDetail->symptoms_title ?? ''),
                fn($id) => $id !== null && trim($id) !== ''
            );

            // Fetch symptoms related to this OPD
            $symptoms = ! empty($symptomIds)
                ? Symptom::whereIn('id', $symptomIds)->get()
                : collect();

            // Store in array using OPD number as key
            $opdSymptoms[$opdDetail->opd_no] = $symptoms;
        }
        // Store in array using OPD number as key
        return view('admin.opd.opd_view', compact('opd', 'symptoms','vitals','vitalDetails','pharmacyDetails','medDosages','dosages','PatientTimelines','medicineCategories', 'medicationReport', 'operationDetail', 'opdCharges', 'labInvestigations', 'opdVisits', 'opdSymptoms'));
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

    public function getOpdById(Request $request, $id)
    {
        $opd = OpdDetail::with('patient.bloodGroup', 'doctor.department')->where('id', $id)->firstOrFail();
        return response()->json($opd, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getOpdMedicineById(Request $request, $id)
    {
        $opdPrescription = OpdPrescription::where('visit_id', $id)->firstOrFail();
        $opdMedicines    = OpdMedicine::with('pharmacy', 'medicineDosage.unit', 'doseInterval', 'doseDuration')->where('prescription_id', $opdPrescription->id)->get();
        // dd($opdMedicines);
        return response()->json($opdMedicines, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function getChargeTypes(Request $request)
    {
        $chargeTypes = ChargeTypeMaster::all();
        return response()->json($chargeTypes, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getChargeCategoriesByTypeId(Request $request, $id)
    {
        $chargeCategories = ChargeCategory::where('charge_type_id', $id)->get();
        return response()->json($chargeCategories, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function addOpdCharge(Request $request)
    {
                                               // dd($request->charge_category);         // dd($request->all());
        $count = count($request->charge_type); // Number of rows

        for ($i = 0; $i < $count; $i++) {

            OpdCharges::create([
                'opd_id'              => $request->opd_id ?? null,
                'charge_type_id'      => $request->charge_type[$i],
                'charge_category_id'  => $request->charge_category[$i],
                'charge_id'           => $request->charge_id[$i],
                'standard_charge'     => $request->standard_charge[$i],
                'tpa_charge'          => $request->tpa_charge[$i],
                'qty'                 => $request->qty[$i],
                'total'               => $request->total[$i],
                'discount_percentage' => $request->discount_percentage[$i],
                'tax'                 => $request->tax[$i],
                'net_amount'          => $request->net_amount[$i],
                'charge_note'         => $request->charge_note[$i],
                'date'                => $request->charge_date[$i],
            ]);
        }

        return redirect()->back()->with('success', 'Charges saved successfully!');
    }
    public function storeVisitDetails(Request $request)
    {
        // ----------------------------------------
        // ✅ VALIDATOR (No ValidationException)
        // ----------------------------------------
        $validator = Validator::make($request->all(), [
            'opd_id' => 'required|exists:opd_details,id',
            'patient_id'     => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'doctor_id'    => 'required|exists:doctor,id',

            'symptoms_type'  => 'nullable|array',
            'symptoms_title' => 'nullable|array',

            'bp'           => 'nullable|string',
            'height'       => 'nullable|string',
            'weight'       => 'nullable|string',
            'pulse'        => 'nullable|string',
            'temperature'  => 'nullable|string',
            'respiration'  => 'nullable|string',

            'payment_mode' => 'nullable|string',
        ]);

        // ----------------------------------------
        // 🔥 If validation fails → Dump all errors
        // ----------------------------------------
        if ($validator->fails()) {
            dd($validator->errors()->toArray());
        }

        try {

            $symptomType          = array_filter($request->symptoms_type, fn($type) => $type !== null && $type !== '');
            $symptomTitle         = array_filter($request->symptoms_title, fn($title) => $title !== null && $title !== '');
            $implodedSymptomType  = implode(", ", $symptomType);
            $implodedSymptomTitle = implode(", ", $symptomTitle);

            DB::beginTransaction();

            $user = Auth::user();

            // ----------------------------------------
            // 🔥 GENERATE VISIT NUMBER
            // ----------------------------------------
            $lastVisit = VisitDetail::orderBy('id', 'desc')->first();
            if ($lastVisit && preg_match('/OCID(\d+)/', $lastVisit->checkup_id, $matches)) {
                $lastNumber = intval($matches[1]);
            } else {
                $lastNumber = 0;
            }

            $visitPrefix = Prefix::where("type", 'opd_checkup_id')->firstOrFail();
            $nextNumber  = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $visitNo     = $visitPrefix->prefix . $nextNumber;

            // ----------------------------------------
            // 🔥 SAVE VISIT DETAILS
            // ----------------------------------------
            $visit = new VisitDetail();

            $visit->hospital_id       = $user->hospital_id;
            $visit->branch_id         = $user->branch_id;
            $visit->checkup_id        = $visitNo;
            $visit->opd_details_id    = $request->opd_id;
            $visit->patient_id        = $request->patient_id;
            $visit->organisation_id   = $request->organisation_id ?? null;
            $visit->patient_charge_id = $request->patient_charge_id ?? null;
            $visit->transaction_id    = null;

            $visit->cons_doctor       = $request->doctor_id;
            $visit->case_type         = $request->case_type;
            $visit->appointment_date  = $request->appointment_date;

            $visit->symptoms_type     = $implodedSymptomType ?? "";
            $visit->symptoms          = $implodedSymptomTitle ?? "";
            $visit->bp                = $request->bp;
            $visit->height            = $request->height;
            $visit->weight            = $request->weight;
            $visit->pulse             = $request->pulse;
            $visit->temperature       = $request->temperature;
            $visit->respiration       = $request->respiration;

            $visit->known_allergies   = $request->known_allergies;
            $visit->note              = $request->note;
            $visit->note_remark       = $request->note_remark;

            $visit->payment_mode      = $request->payment_mode;
            $visit->generated_by      = $user->id;
            $visit->live_consult      = $request->live_consultation;

            $visit->patient_old       = $request->case_type == "Old Patient" ? 1 : 0;
            $visit->casualty          = $request->casualty;
            $visit->refference        = $request->reference;
            $visit->date              = now()->format('Y-m-d');

            $visit->is_antenatal      = 0;
            $visit->can_delete        = 1;

            $visit->save();

            DB::commit();

            return redirect()->back()->with('success', 'Visit details saved successfully!');

        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('error', "Something went wrong: " . $e->getMessage());
        }
    }



}