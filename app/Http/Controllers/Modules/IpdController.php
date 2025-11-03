<?php
namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\BedGroup;
use App\Models\Doctor;
use App\Models\IpdDetail;
use App\Models\IpdPatient;
use App\Models\Patient;
use App\Models\Prefix;
use App\Models\Symptom;
use App\Models\SymptomsClassification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IpdController extends Controller
{
    public function index(Request $request)
    {
        $isIpdTab = $request->get('tab', 'ipd') == 'ipd';
        $doctors  = Doctor::all();
        if ($isIpdTab) {
            $ipd = IpdDetail::with('patient', 'doctor', 'bedDetail', 'bedGroup.floorDetail')->get();
        } else {
            // $patients = Patient::with(['ipds.doctor'])->get();
            $patients = IpdDetail::with('patient', 'doctor')->where('discharged', 'yes')->get();
            // dd($patients);
            // dd($patients);
            $ipd = $patients;
        }
        return view("admin.ipd.index", compact("ipd", 'doctors', 'isIpdTab'));
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
            'credit_limit'         => 'required|numeric|min:0',
            'live_consultation'    => 'nullable|string|max:100',
            'bed_group'            => 'required|exists:bed_group,id',
            'bed_number'           => 'required|exists:bed,id',
            'symptoms_type'        => 'required|array',
            'symptoms_type.*'      => 'string',
            'symptoms_title'       => 'required|array',
            'symptoms_title.*'     => 'string',
            'symptoms_description' => 'required|string',
            'note'                 => 'nullable|string',
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
            $ipd->date         = $request->appointment_date;
            $ipd->patient_old  = $request->case_type;
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
        // dd($request->all());
        $request->validate([
            'patient_id'           => 'required|exists:patients,id',
            'appointment_date'     => 'required|date',
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
            $ipd->date         = $request->appointment_date;
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
}
