<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prefix;
use App\Models\GlobalShift;
use App\Models\DoctorShiftTime;
use App\Models\DoctorGlobalShift;
use App\Models\OpdDetail;
use App\Models\OpdPatient;
use App\Models\AppointPriority;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AppointmentsController extends Controller
{
    public function index()
    {
        $response = Http::get('https://hospitaldocker-gsgmhybjbvbpgxhb.canadacentral-01.azurewebsites.net/showbooking');
        // dd($response->json());
        $bookings = [];
        if ($response->ok()) {
            $bookings = $response->json();
        }
        return view('home.appointments', compact('bookings'));
    }

    public function appointmentDetails()
    {
        $appointments = Appointment::with(['patient', 'doctorUser', 'doctorShift'])
        ->orderBy('date', 'desc')
        ->get();
        //dd(Doctor::find(1));
        //dd($appointments->first()->doctorUser);
        $doctors  = Doctor::all();
        $patients = Patient::all();
        $priorities = AppointPriority::select('id', 'appoint_priority')->get();
        return view('admin.appointments.appointment_details', compact('appointments','doctors', 'patients','priorities'));
    }
    
    public function store(Request $request)
    {
        
        $request->validate([
            'patient_id' => 'required',
            'doctor' => 'required',
            'appointment_date' => 'required|date',
            'shift' => 'required',
            'slot' => 'required',
            'appointment_priority' => 'nullable',
            'payment_method' => 'nullable|string',
            'status' => 'required|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'message' => 'nullable|string',
            'live_con' => 'nullable|string',
        ]);

        $latestAppointment = Appointment::orderBy('id', 'desc')->first();

            // ✅ Fetch dynamic prefix
        $prefix = Prefix::where('type', 'appointment')->value('prefix') ?? 'APPID';

        // ✅ Find last appointment_id with this prefix
        $lastAppointment = Appointment::where('appointment_id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastAppointment) {
            $lastNumber = (int) str_replace($prefix, '', $lastAppointment->appointment_id);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // ✅ Generate new appointment_id
        $appointmentId = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $appointment = new Appointment();
        $appointment->appointment_id = $appointmentId;
        $appointment->patient_id = $request->patient_id;
        $appointment->doctor = $request->doctor;
        // $appointment->amount = $request->doctor_fees;
        $appointment->doctor_global_shift_id = $request->shift;
        $appointment->date = $request->appointment_date;
        $appointment->doctor_shift_time_id = $request->slot;
        $appointment->priority = $request->appointment_priority;
    
        $appointment->appointment_status = $request->status;
        // $appointment->discount_percentage = $request->discount_percentage;
        $appointment->source = 'Offline'; 
        $appointment->message = $request->message;
        $appointment->live_consult = $request->live_con;
        $appointment->is_opd = 'Yes';
        $appointment->is_ipd = 'No'; 
        $appointment->save();

         $opdPrefix = Prefix::where('type', 'opd_no')->value('prefix') ?? 'OPD';

        $lastOpd = OpdDetail::where('opd_no', 'like', $opdPrefix . '%')
        ->orderBy('id', 'desc')
        ->first();

        $nextOpdNumber = $lastOpd
        ? ((int) str_replace($opdPrefix, '', $lastOpd->opd_no) + 1)
        : 1;

    $opdNo = $opdPrefix . str_pad($nextOpdNumber, 3, '0', STR_PAD_LEFT);

    /**
     * ======================
     * ✅ Store OPD Details
     * ======================
     */
    $opd = new OpdDetail();
    $opdPatient = new OpdPatient();
    $opd->hospital_id = auth()->user()->hospital_id ?? null;
    $opd->branch_id = auth()->user()->branch_id ?? null;
    $opd->opd_no = $opdNo;
    $opd->patient_id = $request->patient_id;
    $opd->appointment_date = $request->appointment_date;
    $opd->case_type = $request->case_type;
    $opd->apply_tpa = 'No';
    $opd->casualty = 'No';
    $opd->reference = null;
    $opd->doctor_id = $request->doctor;
    $opd->charge_category_id = '1';
    $opd->charge_id = '1';
    $opd->standard_charge = $request->doctor_fees;
    $opd->applied_charge = $request->doctor_fees;
    $opd->discount = $request->discount_percentage ?? 0;
    $opd->tax = 0;
    $opd->amount = $request->doctor_fees;
    $opd->payment_mode = $request->payment_method ?? 'Cash';
    $opd->paid_amount = $request->doctor_fees;
    $opd->payment_date = now();
    $opd->live_consultation = $request->live_con ?? 'No';
    $opd->symptoms_type = '';
    $opd->symptoms_title = '';
    $opd->allergies = '';
    $opd->symptoms_description = '';
    $opd->note = $request->message ?? null;
    $opd->status = $request->status;
    $opd->created_by = auth()->id();
    $opd->save();

    // dd($opd->id);
    $opdPatient->patient_id = $request->patient_id ?? null;
    $opdPatient->opd_id     = $opd->id ?? null;
    $opdPatient->doctor_id  = $request->doctor ?? null;
    $opdPatient->save();

        

        return redirect()->back()->with('success', 'Appointment created successfully!');
    }

    public function edit($id)
    {
        $appointment = Appointment::with(['patient', 'doctorUser'])->find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        $shifts = DoctorGlobalShift::with('globalShift')->where('doctor_id', $appointment->doctor)->get();
        $slots = DoctorShiftTime::where('doctor_id', $appointment->doctor)
                    ->where('doctor_global_shift_id', $appointment->doctor_global_shift_id)
                    ->get();

        return response()->json([
            'appointment' => $appointment,
            'shifts' => $shifts,
            'slots' => $slots,
        ]);
    }

    public function editOld(Appointment $appointment)
    {
        return response()->json([
            'appointment' => $appointment,
            'shifts' => DoctorGlobalShift::all(),
            'slots' => DoctorShiftTime::where('doctor_id', $appointment->doctor)->get(),
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'shift' => 'required',
            'slot' => 'required',
        ]);
        //dd($request->all());

        $appointment = Appointment::findOrFail($id);
        $appointment->date = $request->appointment_date;
        $appointment->doctor_global_shift_id = $request->shift;
        $appointment->doctor_shift_time_id = $request->slot;
        $appointment->appointment_status = $request->status;
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment rescheduled successfully!');
    }
    public function doctorwise()
    {
        $appointments = Appointment::with(['patient', 'doctorUser', 'doctorShift'])
        ->orderBy('date', 'desc')
        ->get();
        $doctors  = Doctor::all();
        $patients = Patient::all();
        $doctors  = Doctor::all();
        return view('admin.appointments.doctor_wise', compact('appointments','doctors', 'patients'));
    }

    public function searchAppointments(Request $request)
    {
        $query = Appointment::with(['patient', 'doctorUser']);

        if ($request->doctor_id) {
            $query->where('doctor', $request->doctor_id);
        }

        if ($request->date) {
            $query->whereDate('date', $request->date);
        }

        $appointments = $query->orderBy('date', 'desc')->get();

        return response()->json([
            'appointments' => $appointments ?? []
        ]);
    }

    public function show($patient_id)
    {
        // Fetch the patient details
        $appointment = Appointment::with(['patient'])->where('patient_id', $patient_id)->firstOrFail();

        $visitDetails = OpdDetail::where('patient_id', $patient_id)->get();

        //dd($visitDetails);

        // Return to patient details view
        return view('admin.appointments.patient_view', compact('appointment','visitDetails'));
    }



    
}