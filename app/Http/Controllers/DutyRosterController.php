<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DutyRosterAssign;
use App\Models\DutyRosterShift;
use App\Models\DutyRosterList;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\Floor;
use App\Models\Department;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DutyRosterController extends Controller
{
    public function rosterListDetails()
    {
        // Eager load the related shift for efficiency
        $rosters = DutyRosterList::with('dutyRosterShift')
            ->orderBy('id', 'desc')
            ->get();
        $shifts = DutyRosterShift::all();

        return view('admin.duty-roster.roster_list_details', compact('rosters','shifts'));
    }
    public function addRoster(Request $request)
    {
        
        $request->validate([
            'duty_roster_shift_id' => 'required|exists:duty_roster_shift,id',
            'duty_roster_start_date' => 'required|date',
            'duty_roster_end_date' => 'required|date|after_or_equal:duty_roster_start_date',
            'duty_roster_total_day' => 'required|integer|min:1',
        ]);
        
        DutyRosterList::create([
            'duty_roster_shift_id' => $request->duty_roster_shift_id,
            'duty_roster_start_date' => $request->duty_roster_start_date,
            'duty_roster_end_date' => $request->duty_roster_end_date,
            'duty_roster_total_day' => $request->duty_roster_total_day,
        ]);

        return redirect()->back()->with('success', 'Roster added successfully!');
    }
    public function updateRoster(Request $request, $id)
    {
        $request->validate([
            'duty_roster_shift_id' => 'required|exists:duty_roster_shift,id',
            'duty_roster_start_date' => 'required|date',
            'duty_roster_end_date' => 'required|date|after_or_equal:duty_roster_start_date',
            'duty_roster_total_day' => 'required|integer|min:1',
        ]);

        $roster = DutyRosterList::findOrFail($id);
        $roster->update($request->all());

        return redirect()->back()->with('success', 'Duty roster updated successfully.');
    }

    public function destroyRoster($id)
    {
        $dutyRosterList = DutyRosterList::find($id);

        if (!$dutyRosterList) {
            return response()->json(['error' => 'Duty roster not found.'], 404);
        }

        $dutyRosterList->delete(); // soft delete (sets deleted_at)

        return response()->json(['success' => 'Duty roster deleted successfully.']);
    }

    public function showShift()
    {
        // Eager load the related shift for efficiency
        $rosters = DutyRosterList::with('dutyRosterShift')
            ->orderBy('id', 'desc')
            ->get();
        $shifts = DutyRosterShift::all();

        return view('admin.duty-roster.roster_shift', compact('shifts'));
    }

    public function addShift(Request $request)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'shift_start' => 'required|date_format:H:i',
            'shift_end' => 'required|date_format:H:i',
        ]);

        
        // Save the shift
        DutyRosterShift::create([
            'shift_name' => $request->shift_name,
            'shift_start' => $request->shift_start,
            'shift_end' => $request->shift_end,
            'shift_hour' => $request->shift_hour,
        ]);

        return redirect()->back()->with('success', 'Shift added successfully!');
    }

    public function updateShift(Request $request, $id)
    {
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'shift_start' => 'required|date_format:H:i',
            'shift_end' => 'required|date_format:H:i',
        ]);

        // Fetch existing shift
        $shift = DutyRosterShift::findOrFail($id);

        // Convert start & end to Carbon
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $request->shift_start);
        $endTime = \Carbon\Carbon::createFromFormat('H:i', $request->shift_end);

        // Calculate difference in seconds (handles overnight shifts)
        $diffInSeconds = $endTime->diffInSeconds($startTime, false);
        if ($diffInSeconds < 0) {
            $diffInSeconds += 24 * 3600;
        }

        // Convert seconds to HH:MM:SS format
        $shiftHour = gmdate('H:i:s', $diffInSeconds);

        // Update shift record
        $shift->update([
            'shift_name' => $request->shift_name,
            'shift_start' => $request->shift_start,
            'shift_end' => $request->shift_end,
            'shift_hour' => $request->shift_hour,
        ]);

        return redirect()->back()->with('success', 'Shift updated successfully!');
    }

    public function destroyShift($id)
    {
        $shifts = DutyRosterShift::find($id);

        if (!$shifts) {
            return response()->json(['error' => 'Duty roster not found.'], 404);
        }

        $shifts->delete(); // soft delete (sets deleted_at)

        return response()->json(['success' => 'Duty roster deleted successfully.']);
    }


     public function doctorRoster()
    {
        $rosters = DutyRosterList::with([
            'dutyRosterShift',
            'dutyRosterAssigns' => function ($q) {
                $q->with('department', 'floor');
            }
        ])->get();

        $doctors = Doctor::select('id', 'name', 'specialization')->get();

        return view('admin.duty-roster.doctor_roster', compact('rosters', 'doctors'));
    }

    // // ✅ Staff roster view
    // public function staffRoster()
    // {

    //     $assignments = DutyRosterAssign::with([ 'staff', 'floor','department','dutyRosterList.dutyRosterShift'])->get();
    //     // Group by staff_id
    //     $grouped = $assignments->groupBy('staff_id');
    //     $shifts = DutyRosterShift::all();
    //     $dutyRosterLists = DutyRosterList::all();
    //     $staffList = Staff::all();
    //     $floors = Floor::all();
    //     $departments = Department::all();

    //     // Build one summary per staff
    //     $rosterSummary = $grouped->map(function ($records) {
    //         $first = $records->first(); // first record for that staff
    //         $staff = $first->staff;

    //         return [
    //             'staff_name' => $staff->name ?? 'N/A',

    //             'floor' => $records->map(function ($r) {
    //                 return optional($r->floor)->floor_name;
    //             })->filter()->unique()->implode(', ') ?: 'N/A',

    //             'department' => $records->map(function ($r) {
    //                 return optional($r->department)->department_name;
    //             })->filter()->unique()->implode(', ') ?: 'N/A',

    //             'shift' => $records->map(function ($r) {
    //                 return optional($r->dutyRosterList->dutyRosterShift)->shift_name;
    //             })->filter()->unique()->implode(', ') ?: 'N/A',

    //             'period' => $records->map(function ($r) {
    //                 if ($r->dutyRosterList) {
    //                     return date('d/m/Y', strtotime($r->dutyRosterList->duty_roster_start_date)) . 
    //                         ' - ' . 
    //                         date('d/m/Y', strtotime($r->dutyRosterList->duty_roster_end_date));
    //                 }
    //                 return null;
    //             })->filter()->unique()->implode(', ') ?: 'N/A',

    //             'shift_time' => $records->map(function ($r) {
    //                 if ($r->dutyRosterList && $r->dutyRosterList->dutyRosterShift) {
    //                     return date('h:i A', strtotime($r->dutyRosterList->dutyRosterShift->shift_start_time)) . 
    //                         ' - ' . 
    //                         date('h:i A', strtotime($r->dutyRosterList->dutyRosterShift->shift_end_time));
    //                 }
    //                 return null;
    //             })->filter()->unique()->implode(', ') ?: 'N/A',
    //         ];
    //     });
       

    //     return view('admin.duty-roster.staff_roster', compact('rosterSummary','shifts','dutyRosterLists','staffList','floors','departments'));
    // }

    public function staffRoster()
{
    $assignments = DutyRosterAssign::with([
        'staff',
        'floor',
        'department',
        'dutyRosterList.dutyRosterShift'
    ])->get();

    // Group by staff_id and roster period (start + end)
    $grouped = $assignments->groupBy(function ($item) {
        if ($item->dutyRosterList && $item->staff_id) {
            return $item->staff_id . '|' . 
                $item->dutyRosterList->duty_roster_start_date . '|' . 
                $item->dutyRosterList->duty_roster_end_date;
        }
        return 'N/A';
    });

    $shifts = DutyRosterShift::all();
    $dutyRosterLists = DutyRosterList::all();
    $staffList = Staff::all();
    $floors = Floor::all();
    $departments = Department::all();

    // Build one summary per staff per date range
    $rosterSummary = $grouped->map(function ($records, $key) {
        $first = $records->first();

        // Split key to extract staff_id and date range
        $parts = explode('|', $key);
        $staff_id = $parts[0] ?? null;

        $startDate = $parts[1] ?? null;
        $endDate = $parts[2] ?? null;
       

        $staff = $first->staff;

        return [
            'id' => $first->id, // <-- Add this to get DutyRosterAssign ID
            'staff_id' => $staff_id,
            'code' => $first->code ?? ($first->dutyRosterList->code ?? 'N/A'),
            'staff_name' => $staff->name ?? 'N/A',
            'floor' => $records->map(function ($r) {
                return optional($r->floor)->name;
            })->filter()->unique()->implode(', ') ?: 'N/A',
            'floor_id' => $records->map(fn($r) => $r->floor_id)->filter()->unique()->implode(',') ?: '',
    'department_id' => $records->map(fn($r) => $r->department_id)->filter()->unique()->implode(',') ?: '',

            'department' => $records->map(function ($r) {
                return optional($r->department)->department_name;
            })->filter()->unique()->implode(', ') ?: 'N/A',
            'shift' => $records->map(function ($r) {
                return optional($r->dutyRosterList->dutyRosterShift)->shift_name;
            })->filter()->unique()->implode(', ') ?: 'N/A',
            'shift_time' => $records->map(function ($r) {
                if ($r->dutyRosterList && $r->dutyRosterList->dutyRosterShift) {
                    return date('h:i A', strtotime($r->dutyRosterList->dutyRosterShift->shift_start_time)) .
                        ' - ' .
                        date('h:i A', strtotime($r->dutyRosterList->dutyRosterShift->shift_end_time));
                }
                return null;
            })->filter()->unique()->implode(', ') ?: 'N/A',
            'period' => $startDate && $endDate
                ? date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate))
                : 'N/A',
        ];
    });

    return view('admin.duty-roster.staff_roster', compact(
        'rosterSummary',
        'shifts',
        'dutyRosterLists',
        'staffList',
        'floors',
        'departments'
    ));
}

    public function assignStaff(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'duty_roster_list_id' => 'required|integer',
            'staff_id' => 'required|integer',
            'floor_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'shift_id' => 'required|integer',
        ]);
        //dd($request->all());
        try {
            // 🔹 Step 1: Fetch roster period details
            $rosterList = DutyRosterList::findOrFail($request->duty_roster_list_id);
            
            // 🔹 Step 2: Determine the date range
            $startDate = $rosterList->duty_roster_start_date;
            $endDate = $rosterList->duty_roster_end_date;
            
            $rosterDates = [];
            $currentDate = Carbon::parse($startDate);

            while ($currentDate->lte($endDate)) {
                $rosterDates[] = $currentDate->format('Y-m-d');
                $currentDate->addDay();
            }

            foreach ($rosterDates as $date) {

                // 🔹 Step 3: Find last code for the same staff & date
                $lastCode = DutyRosterAssign::where('staff_id', $request->staff_id)
                    ->whereDate('roster_duty_date', $date)
                    ->max('code');

                $newCode = $lastCode ? $lastCode + 1 : 1;

                // 🔹 Step 4: Create new record
                DutyRosterAssign::create([
                    'hospital_id' => auth()->user()->hospital_id ?? 1, // adjust as needed
                    'branch_id' => auth()->user()->branch_id ?? 1, // adjust as needed
                    'code' => $newCode,
                    'roster_duty_date' => $date,
                    'floor_id' => $request->floor_id,
                    'department_id' => $request->department_id,
                    'staff_id' => $request->staff_id,
                    'doctor_id' => null,
                    'duty_roster_list_id' => $request->duty_roster_list_id,
                ]);
            }

            return redirect()->back()->with('success', 'Roster assigned successfully.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

        
    }
    public function updateStaffRoster(Request $request)
    {
        // Validate input
        $request->validate([
            'code' => 'required|exists:duty_roster_assign,code',
            'staff_id' => 'required|exists:staff,id',
            'floor_id' => 'nullable|exists:floor,id',
            'department_id' => 'nullable|exists:department,id',
            'shift_id' => 'required|exists:duty_roster_shift,id',
            'duty_roster_list_id' => 'required|exists:duty_roster_list,id',
        ]);

        // ✅ Update all duty roster assignments with the same code
        DutyRosterAssign::where('code', $request->code)->update([
            'staff_id' => $request->staff_id,
            'floor_id' => $request->floor_id,
            'department_id' => $request->department_id,
            'duty_roster_list_id' => $request->duty_roster_list_id,
        ]);

        // ✅ Update the related DutyRosterList shift
        $dutyRosterList = DutyRosterList::findOrFail($request->duty_roster_list_id);
        $dutyRosterList->duty_roster_shift_id = $request->shift_id;
        $dutyRosterList->save();

        return redirect()->back()->with('success', 'Roster updated successfully for all matching records.');
    }
    public function destroyStaffRoster($code)
    {
        DutyRosterAssign::where('code', $code)->delete();

return redirect()->back()->with('success', 'All roster records for this code deleted successfully.');


        return redirect()->back()->with('success', 'Roster deleted successfully.');
    }



}
