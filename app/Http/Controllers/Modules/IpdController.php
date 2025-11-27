<?php
namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\BedGroup;
use App\Models\Doctor;
use App\Models\Finding;
use App\Models\IpdCharges;
use App\Models\IpdDetail;
use App\Models\IpdMedicine;
use App\Models\IpdPatient;
use App\Models\IpdPrescription;
use App\Models\MedicationReport;
use App\Models\NurseNote;
use App\Models\OperationTheatre;
use App\Models\PathologyReport;
use App\Models\Patient;
use App\Models\PatientBedHistory;
use App\Models\Prefix;
use App\Models\Staff;
use App\Models\Symptom;
use App\Models\SymptomsClassification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IpdController extends Controller
{
    public function index(Request $request)
    {
        
        $isIpdTab = $request->get('tab', 'ipd') == 'ipd';
        $doctors  = Doctor::all();
        $bedGroups  = BedGroup::with('floorDetail')->get();
        
        if ($isIpdTab) {
            $ipd = IpdDetail::with('patient', 'doctor', 'bedDetail', 'bedGroup.floorDetail')->get();
        } else {
            // $patients = Patient::with(['ipds.doctor'])->get();
            $patients = IpdDetail::with('patient', 'doctor')->where('discharged', 'yes')->get();
            // dd($patients);
            // dd($patients);
            $ipd = $patients;
        }
        return view("admin.ipd.index", compact("ipd", 'doctors', 'isIpdTab','bedGroups'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'patient_id'           => 'required|exists:patients,id',
            'admission_date'       => 'required|date',
            'patient_type'         => 'required|string',
            'case'                 => 'required|string',
            'casualty'             => 'required|string',
            'reference'            => 'nullable|string',
            'doctor_id'            => 'required|exists:doctor,id',
            'credit_limit'         => 'required|numeric|min:0',
            'live_consultation'    => 'nullable|string|max:100',
            'bed_group'            => 'required|exists:bed_group,id',
            'bed_number'           => 'required|exists:bed,id',
            'symptoms_type'        => 'required|array',
            'symptoms_type.*'      => 'string',
            'symptoms_title'       => 'required|array',
            'symptoms_title.*'     => 'string',
            'symptoms_description' => 'nullable|string',
            'note'                 => 'nullable|string',
            'apply_tpa'            => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
             dd($validator->errors()->all());
            return back()
                ->withErrors($validator)
                ->withInput();
                
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

            $lastIpd = IpdDetail::orderBy('id', 'desc')->first();
            if ($lastIpd && preg_match('/IPDN(\d+)/', $lastIpd->ipd_no, $matches)) {
                $lastNumber = intval($matches[1]);
            } else {
                $lastNumber = 0;
            }
            $ipdPrefix  = Prefix::where("type", 'ipd_no')->firstOrFail();
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $ipdNo      = $ipdPrefix->prefix . $nextNumber;
            // 🔹 Create OPD record
            $ipd        = new IpdDetail();
            $ipdPatient = new IpdPatient();
            $bedDetail  = Bed::where('id', $request->bed_number)->firstOrFail();
            // dd($opd);
            $ipd->hospital_id = $user->hospital_id;
            // Patient Details
            $ipd->patient_id = $request->patient_id;
            // Doctor Details
            $ipd->cons_doctor = $request->doctor_id;

            // Visit Details
            $ipd->bed_group_id = $request->bed_group;
            $ipd->bed          = $request->bed_number;
            $ipd->date         = $request->admission_date;
            $ipd->case_type    = $request->case;
            $ipd->patient_old  = $request->patient_type;
            $ipd->casualty     = $request->casualty;
            $ipd->refference   = $request->reference;

            // Billing / Payment
            $ipd->credit_limit = $request->credit_limit ?? 0;

            // Misc
            $ipd->live_consult         = $request->live_consultation;
            $ipd->symptoms_type        = $implodedSymptomType ?? "";
            $ipd->symptoms_title       = $implodedSymptomTitle ?? "";
            $ipd->symptoms_description = $request->symptoms_description;
            $ipd->note                 = $request->note;
            $ipd->generated_by         = Auth::user()->id ?? null;
            $ipd->ipd_no               = $ipdNo;
            // Save IPD Record
            $ipd->save();

            // dd($opd->id);
            $ipdPatient->patient_id = $request->patient_id ?? null;
            $ipdPatient->ipd_id     = $ipd->id ?? null;
            $ipdPatient->doctor_id  = $request->doctor_id ?? null;

            $ipdPatient->save();

            $bedDetail->is_active = 'no';
            $bedDetail->save();
            DB::commit();

            return redirect()->route('ipd')->with('success', 'IPD record created successfully . ');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Failed to save IPD record: ' . $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $ipd     = IpdDetail::with('patient', 'doctor', 'bedDetail', 'bedGroup.floorDetail')->where('id', $id)->firstOrFail();
        $doctors = Doctor::all();

        $symptomTypeIds = array_filter(
            explode(', ', $ipd->symptoms_type),
            fn($id) => $id !== null && trim($id) !== ''
        );
        $symptomIds = array_filter(
            explode(', ', $ipd->symptoms_title),
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

        $bedGroups  = BedGroup::with('floorDetail')->get();
        $bedNumbers = Bed::where('bed_group_id', $ipd->bed_group_id)->where('is_active', 'yes')->get();
        $patients   = Patient::with('organisation', 'bloodGroup')->get();
        // dd($patients);
        return view('admin.ipd.edit-ipd', compact('ipd', 'doctors', 'symptomTypeIds', 'allSymptomTypes', 'symptoms', 'bedGroups', 'bedNumbers', 'patients'));

    }

    public function update(Request $request, $id)
    {
         //dd($request->all());
        $validator = Validator::make($request->all(), [
            'patient_id'           => 'required|exists:patients,id',
            'admission_date'     => 'required|date',
            'old_patient'          => 'required|string',
            'casualty'             => 'required|string',
            'reference'            => 'nullable|string',
            'consultant_doctor'    => 'required|exists:doctor,id',
            'credit_limit'         => 'required|numeric|min:0',
            'bed_group'            => 'required|exists:bed_group,id',
            'bed_number'           => 'required|exists:bed,id',
            'symptoms_type'        => 'required|array',
            'symptoms_type.*'      => 'string',
            'symptoms_title'       => 'required|array',
            'symptoms_title.*'     => 'string',
            'symptoms_description' => 'required|string',
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
            $ipd        = IpdDetail::findOrFail($id);
            $allotedBed = $ipd->bed;
            //dd($id, IpdPatient::where('ipd_id', $id)->first());
            $ipdPatient = IpdPatient::where('ipd_id', $id)->firstOrFail();
            if ($request->bed_number != $allotedBed) {
                $newBedDetail            = Bed::where('id', $request->bed_number)->firstOrFail();
                $allotedBedDetail        = Bed::where('id', $allotedBed)->firstOrFail();
                $newBedDetail->is_active = 'no';
                $newBedDetail->save();
                $allotedBedDetail->is_active = 'yes';
                $allotedBedDetail->save();
            }
            // dd($opd);
            // Doctor Details
            $ipd->patient_id  = $request->patient_id;
            $ipd->cons_doctor = $request->consultant_doctor;

            // Visit Details
            $ipd->date         = $request->admission_date;
            $ipd->bed_group_id = $request->bed_group;
            $ipd->bed          = $request->bed_number;
            $ipd->patient_old  = $request->old_patient;
            $ipd->casualty     = $request->casualty;
            $ipd->refference   = $request->reference;

            // Billing / Payment
            $ipd->credit_limit = $request->credit_limit ?? 0;

            // Misc
            $ipd->symptoms_type        = $implodedSymptomType ?? "";
            $ipd->symptoms_title       = $implodedSymptomTitle ?? "";
            $ipd->symptoms_description = $request->symptoms_description;
            $ipd->note                 = $request->note;
            // Save IPD Record
            $ipd->save();

            $ipdPatient->patient_id = $request->patient_id ?? null;
            $ipdPatient->doctor_id  = $request->consultant_doctor ?? null;
            $ipdPatient->save();

            DB::commit();

            return redirect()->route('ipd')->with('success', 'IPD record Updated successfully . ');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Failed to save IPD record: ' . $e->getMessage());
        }
    }

    public function getBedGroups(Request $request)
    {
        $bedGroups = BedGroup::with('floorDetail')->get();
        return response()->json($bedGroups, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }
    public function getBedNumbers(Request $request, $id)
    {
        // dd($id);
        $bedNumbers = Bed::where('bed_group_id', $id)->where('is_active', 'yes')->get();
        // dd($bedNumbers);
        return response()->json($bedNumbers, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

  

    public function getNurses(Request $request)
    {
        $nurses = Staff::where('employee_id', 'LIKE', 'NUR%')->get();
        return response()->json($nurses, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function getIpdById(Request $request, $id)
    {
        $ipd = IpdDetail::with('patient.bloodGroup', 'doctor.department')->where('id', $id)->firstOrFail();
        return response()->json($ipd, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);

    }
    public function getIpdMedicineById(Request $request, $id)
    {
        $ipdMedicines = IpdMedicine::with('pharmacy', 'medicineDosage.unit', 'doseInterval', 'doseDuration')->where('prescription_id', $id)->get();
        return response()->json($ipdMedicines, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);

    }

    public function addNurseNote(Request $request)
    {
        // dd($request->all());
        try {
            //code...

            $request->validate([
                'ipd_id'  => 'required|exists:ipd_details,id',
                'date'    => 'required|date',
                'nurse'   => 'required|exists:staff,id',
                'note'    => 'required|string',
                'comment' => 'nullable|string',
            ]);

            NurseNote::create([
                'date'       => $request->date,
                'ipd_id'     => $request->ipd_id,
                'staff_id'   => $request->nurse,
                'note'       => $request->note,
                'comment'    => $request->comment,
                'created_by' => Auth::user()->id,
            ]);

            return redirect()->back()->with('success', 'Nurse Note Added!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
            //throw $th;
        }
    }

    public function storePrescription(Request $request)
    {
       // dd($request->all());
        try { $request->validate([
            'ipd_id'              => 'nullable|string',
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
            // $findings             = array_filter($request->findings, fn($title) => $title !== null && $title !== '');
            $findings             = array_filter($request->input('findings', []), fn($title) => $title !== null && $title !== '');
            $pathology_ids   = array_filter($request->input('pathology', []), fn($pathology) => $pathology !== null && $pathology !== '');
            $radiology_ids   = array_filter($request->input('radiology', []), fn($radio) => $radio !== null && $radio !== '');
            $notification_to = array_filter($request->input('visible', []), fn($notify) => $notify !== null && $notify !== '');
            // $pathology_ids        = array_filter($request->pathology, fn($pathology) => $pathology !== null && $pathology !== '');
            // $radiology_ids        = array_filter($request->radiology, fn($radio) => $radio !== null && $radio !== '');
            // $notification_to      = array_filter($request->visible, fn($notify) => $notify !== null && $notify !== '');
            $implodedFindingTypes = implode(", ", $findingTypes);
            $implodedFindings     = implode(", ", $findings);
            $implodedPathologies  = implode(", ", $pathology_ids);
            $implodedRadiologies  = implode(", ", $radiology_ids);
            $implodedVisibles     = implode(", ", $notification_to);
            $lastPrescription     = IpdPrescription::orderBy('id', 'desc')->first();
            if ($lastPrescription && preg_match('/IPDP(\d+)/', $lastPrescription->prescription_number, $matches)) {
                $lastNumber = intval($matches[1]);
            } else {
                $lastNumber = 0;
            }
            $prescriptionPrefix = Prefix::where("type", 'ipd_pre')->firstOrFail();
            $nextNumber         = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $prescriptionNo     = $prescriptionPrefix->prefix . $nextNumber;
            $prescription       = IpdPrescription::create([
                'prescription_number' => $prescriptionNo,
                'ipd_id'              => $request->ipd_id,
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

                IpdMedicine::create([
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

    public function addIpdCharge(Request $request)
    {
                                               // dd($request->charge_category);         // dd($request->all());
        $count = count($request->charge_type); // Number of rows

        for ($i = 0; $i < $count; $i++) {

            IpdCharges::create([
                'ipd_id'              => $request->ipd_id ?? null,
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

    public function getAvailableBeds(Request $request)
    {
        $bedGroupId = $request->bed_group_id;

        // Beds currently assigned (is_active = 1)
        $occupiedBeds = PatientBedHistory::where('is_active', 'yes')
                            ->pluck('bed_id')
                            ->toArray();

        // Fetch beds excluding occupied ones
        $availableBeds = Bed::where('bed_group_id', $bedGroupId)
                            ->where('is_active', 'yes')
                            ->whereNotIn('id', $occupiedBeds)
                            ->get();

        return response()->json($availableBeds);
    }
     public function assignNewBed(Request $request)
    {
       
        $request->validate([
            'released_date' => 'required|date',
            'bed_group' => 'required',
            'new_bed' => 'required',
        ]);

        $ipd = IpdDetail::findOrFail($request->ipd_id);
        //dd($ipd->id) ;
        // Mark old bed as released
        if($ipd->bedDetail){
            PatientBedHistory::where('ipd_id', $ipd->id)
                ->where('is_active', 'yes')
                ->update(['is_active' => 'no', 'to_date' => $request->released_date]);
             Bed::where('id', $ipd->bedDetail->id)->update(['is_active' => 'no']);
        }

        // Assign new bed
        PatientBedHistory::create([
            'ipd_id' => $ipd->id,
            'bed_id' => $request->new_bed,
            'bed_group_id' => $request->bed_group,
            'is_active' => 'yes',
            'from_date' => now(),
        ]);

       
        // Update ipd bed reference
        // $ipd->bed_id = $request->new_bed;
        // $ipd->bed_group_id = $request->bed_group;
        // $ipd->save();

        return redirect()->back()->with('success', 'Bed assigned successfully.');
    }

}