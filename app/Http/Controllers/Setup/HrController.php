<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\LeaveType;
use App\Models\Specialist;
use App\Models\StaffDesignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrController extends Controller
{

    // Leave Type
    public function index(Request $request)
    {
        $leaves = LeaveType::all();
        return view("admin.setup.leave_type", compact("leaves"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        LeaveType::create([
            'hospital_id' => $user->hospital_id,
            'type'        => $request->input('name'),
            'is_active'   => 'yes',
        ]);
        return redirect()->back()->with('success', 'Leave Type successfully Added!');
    }

    public function updateStatus(Request $request, $id)
    {
        $leave = LeaveType::findOrFail($id);
        // dd($request->is_active == null);
        $leave->is_active = $request->is_active == null ? 'no' : 'yes';
        $leave->save();
        return redirect()->back()->with('success', 'Leave Type Status Updated');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'leave_type_id' => 'required|exists:leave_types,id',
        ]);

        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        $leaveType->update([
            'type' => $request->name,
        ]);
        // $radiologyCategory->save();

        return redirect()->back()->with('success', 'Leave Type Successfully Updated.');
    }

    public function delete(Request $request, $id)
    {
        // dd(Auth::user());
        if (Auth::user()->role == '1') {
            $leave = LeaveType::findOrFail($id);
            $leave->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Leave Type Successfully Deleted');
    }

    // Department
    public function indexDepartment(Request $request)
    {
        $departments = Department::all();
        return view("admin.setup.department", compact("departments"));
    }
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        Department::create([
            'hospital_id'     => $user->hospital_id,
            'department_name' => $request->input('name'),
            'is_active'       => 'yes',
        ]);
        return redirect()->back()->with('success', 'Department successfully Added!');
    }

    public function updateDepartment(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'department_id' => 'required|exists:department,id',
            'name'          => 'required|string|max:255',
        ]);
        // dd($validated);
        $department = Department::findOrFail($request->department_id);
        if (! $department) {
            return redirect()->back()->with('error', 'No Department Found with this Given ID!');
        }
        $department->update([
            'department_name' => $request->input('name'),
        ]);
        // $radiologyUnit->save();

        return redirect()->back()->with('success', 'Department Successfully Updated.');
    }
    public function updateDepartmentStatus(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        // dd($request->is_active == null);
        $department->is_active = $request->is_active == null ? 'no' : 'yes';
        $department->save();
        return redirect()->back()->with('success', 'Department Status Updated');
    }
    public function deleteDepartment(Request $request, $id)
    {
        if (Auth::user()->role == '1') {
            $department = Department::findOrFail($id);
            $department->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Department Successfully Deleted');
    }

    // Designation
    public function indexDesignation(Request $request)
    {
        $designations = StaffDesignation::all();
        return view("admin.setup.designation", compact("designations"));
    }

    public function storeDesignation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        StaffDesignation::create([
            'hospital_id' => $user->hospital_id,
            'designation' => $request->input('name'),
            'is_active'   => 'yes',
        ]);
        return redirect()->back()->with('success', 'Designation successfully Added!');
    }

    public function updateDesignation(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'designation_id' => 'required|exists:staff_designation,id',
            'name'           => 'required|string|max:255',
        ]);
        // dd($validated);
        $designation = StaffDesignation::findOrFail($request->designation_id);
        if (! $designation) {
            return redirect()->back()->with('error', 'No Designation Found with this Given ID!');
        }
        $designation->update([
            'designation' => $request->input('name'),
        ]);
        // $radiologyUnit->save();

        return redirect()->back()->with('success', 'Designation Successfully Updated.');
    }
    public function updateDesignationStatus(Request $request, $id)
    {
        $designation            = StaffDesignation::findOrFail($id);
        $status                 = $request->is_active == null ? 'no' : 'yes';
        $designation->is_active = $status;
        $designation->save();
        // $designation->is_active = $request->is_active == null ? 'no' : 'yes';

        return redirect()->back()->with('success', 'Designation Status Updated');
    }

    public function deleteDesignation(Request $request, $id)
    {
        if (Auth::user()->role == '1') {
            $designation = StaffDesignation::findOrFail($id);
            $designation->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Designation Successfully Deleted');
    }

    // Specialist
    public function indexSpecialist(Request $request)
    {
        $specialists = Specialist::all();
        return view("admin.setup.specialist", compact("specialists"));
    }

    public function storeSpecialist(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        Specialist::create([
            'hospital_id'     => $user->hospital_id,
            'specialist_name' => $request->input('name'),
            'is_active'       => 'yes',
        ]);
        return redirect()->back()->with('success', 'Specialist successfully Added!');
    }

    public function updateSpecialist(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'specialist_id' => 'required|exists:specialist,id',
            'name'          => 'required|string|max:255',
        ]);
        // dd($validated);
        $specialist = Specialist::findOrFail($request->specialist_id);
        if (! $specialist) {
            return redirect()->back()->with('error', 'No Specialist Found with this Given ID!');
        }
        $specialist->update([
            'specialist_name' => $request->input('name'),
        ]);
        // $radiologyUnit->save();

        return redirect()->back()->with('success', 'Specialist Successfully Updated.');
    }
    public function updateSpecialistStatus(Request $request, $id)
    {
        $specialist            = Specialist::findOrFail($id);
        $status                = $request->is_active == null ? 'no' : 'yes';
        $specialist->is_active = $status;
        $specialist->save();
        // $designation->is_active = $request->is_active == null ? 'no' : 'yes';

        return redirect()->back()->with('success', 'Specialist Status Updated');
    }

    public function deleteSpecialist(Request $request, $id)
    {
        if (Auth::user()->role == '1') {
            $specialist = Specialist::findOrFail($id);
            $specialist->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Specialist Successfully Deleted');
    }
}