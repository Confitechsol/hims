<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\GlobalShift;
use App\Models\DoctorShiftTime;
use App\Models\DoctorGlobalShift;

use App\Models\Doctor;
use App\Models\AppointPriority;
use App\Models\ChargeCategory;
use App\Models\Charge;
use App\Models\ChargeTypeMaster;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{

    public function appointmentPriority()
    {
        
        $appointments = AppointPriority::all();
        return view('admin.setup.appointment_priority', compact('appointments'));
    }

    public function appointmentPriorityStore(Request $request)
    {
        $request->validate([
            'priority.*' => 'required|string|max:255',
            
        ]);
        foreach ($request->priority as $priority) {

            AppointPriority::create([
                'appoint_priority'  => $priority,
                'created_at' => now(),   
            ]);
        }

        return redirect()->back()->with('success', 'Priority added successfully.');
    }

    public function appointmentPriorityUpdate(Request $request, $id)
    {
        $request->validate([
            'priority' => 'required|string|max:255',
        ]);

        $priority = AppointPriority::findOrFail($id);
        $priority->update([
            'appoint_priority'  => $request->priority,
           
        ]);

        return redirect()->back()->with('success', 'Priority updated successfully.');
    }

    public function appointmentPriorityDestroy($id)
    {
        $priority = AppointPriority::findOrFail($id);
        $priority->delete();

        return redirect()->back()->with('success', 'Priority deleted successfully.');
    }
    public function shift() 
    {
        $shifts = GlobalShift::all();
        return view('admin.setup.shift', compact('shifts'));
    }

    public function shiftStore(Request $request)
    {
        $request->validate([
            'shift_name'  => 'required|string|max:255',
            'time_from'  => 'required|string|max:255',
            'time_to'    => 'required|string|max:255',
        ]);

        
            GlobalShift::create([
                'name'  => $request->shift_name,
                'start_time'  => $request->time_from,
                'end_time'  => $request->time_to,
                'created_at'  => now(),
            ]);
        

        return redirect()->back()->with('success', 'Shift(s) added successfully.');
    }

    public function shiftUpdate(Request $request, $id)
    {
        
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_time'  => 'required|string|max:255',
            'end_time'    => 'required|string|max:255',
        ]);

        $shift = GlobalShift::findOrFail($id);
        $shift->update([
            'name'  => $request->shift_name,
            'start_time'  => $request->start_time,
            'end_time'  => $request->end_time,
            'created_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Shift updated successfully.');
    }

    public function shiftDestroy($id)
    {
        $shift = GlobalShift::findOrFail($id);
        $shift->delete();

        return redirect()->back()->with('success', 'Shift deleted successfully.');
    }
    public function doctorShift() 
    {
        $shifts = GlobalShift::all();
        
       $doctors = Doctor::with('doctorGlobalShifts')->get();
        return view('admin.setup.doctor_shift', compact('shifts','doctors'));
    }
    
    public function toggleDoctorShift(Request $request)
    {
        try {
            $doctorId = $request->doctor_id;
            $shiftId  = $request->shift_id;
            $status   = $request->status; // 1 = checked, 0 = unchecked

            if ($status == 1) {
                DoctorGlobalShift::firstOrCreate([
                    'doctor_id'       => $doctorId,
                    'global_shift_id' => $shiftId,
                    'hospital_id'     => Auth::user()->hospital_id ?? null,
                    'branch_id'       => Auth::user()->branch_id ?? null,
                ]);

                return response()->json(['success' => true, 'message' => 'Shift saved successfully!']);
            } else {
                DoctorGlobalShift::where('doctor_id', $doctorId)
                    ->where('global_shift_id', $shiftId)
                    ->delete();

                return response()->json(['success' => true, 'message' => 'Shift removed successfully!']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Shift not saved!', 'error' => $e->getMessage()], 500);
        }
    }



    public function slots()
    {
        $doctors = Doctor::all();
        $slots = DoctorShiftTime::all();
        $shifts = GlobalShift::all();
        $categories = ChargeCategory::all();
        return view('admin.setup.slots', compact('doctors', 'slots', 'shifts', 'categories'));
    }

    public function getCharges($categoryId)
    {
        $charges = Charge::where('charge_category_id', $categoryId)->get();
        return response()->json($charges);
    }

    public function getDoctorFees()
    {
        
        $charge = Charge::with('taxCategory')->where('charge_category_id', 1)->first();
         //dd($charge);
        if ($charge) {
            return response()->json(['fees' => $charge->standard_charge]);
        }

        return response()->json(['fees' => 0]);
    }


    // public function searchSlots(Request $request)
    // {
    //     $doctorId = $request->doctor;
    //     $shiftId = $request->shift;

    //     // Get existing slots for doctor & shift
    //     $slots = DoctorShiftTime::where('doctor_id', $doctorId)
    //                 ->where('doctor_global_shift_id', $shiftId)
    //                 ->get();

    //     // Get shift timings
    //     $shift = GlobalShift::find($shiftId);

    //     return response()->json([
    //         'slots' => $slots,
    //         'shift' => $shift
    //     ]);
    // }
    public function searchSlots(Request $request)
    {
        $doctorId = $request->doctor;
        $shiftId = $request->shift;

        // Find the pivot record linking doctor and shift
        $doctorGlobalShift = DoctorGlobalShift::where('doctor_id', $doctorId)
                            ->where('global_shift_id', $shiftId)
                            ->first();

        if ($doctorGlobalShift) {
            // Get slots for this doctor & doctorGlobalShift
            $slots = DoctorShiftTime::where('doctor_id', $doctorId)
                        ->where('doctor_global_shift_id', $doctorGlobalShift->id)
                        ->get();

            if ($slots->isNotEmpty()) {
                // Doctor already has custom slots → return them
                return response()->json([
                    'slots' => $slots,
                    'shift' => null  // no need for global shift
                ]);
            }
        }

        // Case: No custom slots exist → return global shift timings
        $shift = GlobalShift::find($shiftId);

        return response()->json([
            'slots' => [],   // no doctor slots yet
            'shift' => $shift
        ]);
    }

    public function saveDoctorSlot(Request $request)
    {
        try {
            $slot = DoctorShiftTime::updateOrCreate(
                [
                    'doctor_id' => $request->doctor_id,
                    'shift_id'  => $request->shift_id
                ],
                [
                    'consult_time'    => $request->consult_time,
                    'charge_category' => $request->charge_category,
                    'charge_id'       => $request->charge_id,
                    'amount'          => $request->amount,
                ]
            );

            return response()->json(['success' => true, 'message' => 'Slot saved successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Slot not saved!']);
        }
    }
    public function slotsStore(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'doctor' => 'required|exists:doctor,id',
            'shift' => 'required|exists:doctor_global_shift,id',
            'consult_time' => 'required|numeric',

            'slots' => 'required|array',
        ]);

        $doctorId = $request->doctor;
        $shiftId = $request->shift;

        // 1. Ensure doctor_global_shift exists (mapping doctor ↔ shift)
        $doctorGlobalShift = DoctorGlobalShift::firstOrCreate(
            [
                'doctor_id' => $doctorId,
                'global_shift_id' => $shiftId,
            ]
        );

        // 2. Save slots against doctor_global_shift.id
        foreach ($request->slots as $day => $time) {
            DoctorShiftTime::updateOrCreate(
                [
                    'doctor_id' => $doctorId,
                    'doctor_global_shift_id' => $doctorGlobalShift->id, // ✅ Correct ID
                    'day' => $day
                ],
                [
                    'start_time' => $time['time_from'],
                    'end_time' => $time['time_to'],
                    'consultation_duration' => $request->consult_time,
                    'charge_category_id' => $request->charge_category,
                    'charge_id' => $request->charge_id,
                    'amount' => $request->amount, // add if needed
                ]
            );
        }

        return redirect()->back()->with('success', 'Slots saved successfully!');
    }


    public function slotUpdate(Request $request, $id)
    {
        $slot = Slot::findOrFail($id);

        $request->validate([
            'doctor'          => 'required|exists:doctors,id',
            'shift'           => 'required|exists:shifts,id',
            'consult_time'    => 'required|integer|min:5',
            'charge_category' => 'required|exists:charge_categories,id',
            'charge_id'       => 'required|exists:charges,id',
        ]);

        $charge = Charge::findOrFail($request->charge_id);

        $slot->doctor_id         = $request->doctor;
        $slot->shift_id          = $request->shift;
        $slot->consult_time      = $request->consult_time;
        $slot->charge_category_id = $request->charge_category;
        $slot->charge_id         = $request->charge_id;
        $slot->amount            = $charge->standard_amount ?? 0;
        $slot->save();

        return redirect()->route('slots.index')->with('success', 'Slot updated successfully.');
    }

    public function slotDestroy($id)
    {
        $slot = Slot::findOrFail($id);
        $slot->delete();

        return back()->with('success', 'Slot deleted successfully.');
    }

    public function getDoctorShifts($doctorId)
    {
        // Get all shifts linked to this doctor
        $shifts = DoctorGlobalShift::with('globalShift')
                    ->where('doctor_id', $doctorId)
                    ->get()
                    ->map(function($item) {
                        return [
                            'id' => $item->globalShift->id,
                            'name' => $item->globalShift->name
                        ];
                    });

        return response()->json([
            'shifts' => $shifts
        ]);
    }
    public function getDoctorSlots($doctorId, $shiftId)
    {
        $doctorGlobalShiftid = DoctorGlobalShift::where('doctor_id', $doctorId)
                    ->where('global_shift_id', $shiftId)->value('id');
        $slots = DoctorShiftTime::where('doctor_id', $doctorId)
                    ->where('doctor_global_shift_id', $doctorGlobalShiftid)
                    ->get(['id', 'day', 'start_time', 'end_time']);
        //dd($slots);

        return response()->json(['slots' => $slots]);
    }
    public function getAppointmentPriorities()
    {
        $priorities = AppointPriority::select('id', 'appoint_priority')->get();

        return response()->json(['priorities' => $priorities]);
    }

}
