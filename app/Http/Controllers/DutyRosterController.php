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

    // ✅ Staff roster view
    public function staffRoster()
    {

        $assignments = DutyRosterAssign::with([ 'staff', 'floor','department','dutyRosterList.dutyRosterShift'])->get();
        // Group by staff_id
        $grouped = $assignments->groupBy('staff_id');
        $shifts = DutyRosterShift::all();
        $dutyRosterLists = DutyRosterList::all();
        $staffList = Staff::all();
        $floors = Floor::all();
        $departments = Department::all();

        // Build one summary per staff
        $rosterSummary = $grouped->map(function ($records) {
            $first = $records->first(); // first record for that staff
            $staff = $first->staff;

            return [
                'staff_name' => $staff->name ?? 'N/A',

                'floor' => $records->map(function ($r) {
                    return optional($r->floor)->floor_name;
                })->filter()->unique()->implode(', ') ?: 'N/A',

                'department' => $records->map(function ($r) {
                    return optional($r->department)->department_name;
                })->filter()->unique()->implode(', ') ?: 'N/A',

                'shift' => $records->map(function ($r) {
                    return optional($r->dutyRosterList->dutyRosterShift)->shift_name;
                })->filter()->unique()->implode(', ') ?: 'N/A',

                'period' => $records->map(function ($r) {
                    if ($r->dutyRosterList) {
                        return date('d/m/Y', strtotime($r->dutyRosterList->duty_roster_start_date)) . 
                            ' - ' . 
                            date('d/m/Y', strtotime($r->dutyRosterList->duty_roster_end_date));
                    }
                    return null;
                })->filter()->unique()->implode(', ') ?: 'N/A',

                'shift_time' => $records->map(function ($r) {
                    if ($r->dutyRosterList && $r->dutyRosterList->dutyRosterShift) {
                        return date('h:i A', strtotime($r->dutyRosterList->dutyRosterShift->shift_start_time)) . 
                            ' - ' . 
                            date('h:i A', strtotime($r->dutyRosterList->dutyRosterShift->shift_end_time));
                    }
                    return null;
                })->filter()->unique()->implode(', ') ?: 'N/A',
            ];
        });
       

        return view('admin.duty-roster.staff_roster', compact('rosterSummary','shifts','dutyRosterLists','staffList','floors','departments'));
    }

    public function store(Request $request)
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
    public function update(Request $request, $id)
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

}
