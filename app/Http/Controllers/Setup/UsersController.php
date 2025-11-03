<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $isDoctorTab = $request->get('tab', 'doctor') == 'doctor';
        $statusTab   = $request->get('statusTab', 'active');
        if ($isDoctorTab) {
            // Query from doctors table
            $query = Doctor::query()
                ->select(
                    'doctor.id',
                    'doctor.name',
                    'doctor.surname',
                    'doctor.email',
                    'doctor.contact_no',
                    'doctor.is_active',
                    'doctor.specialization',
                    'doctor.designation as designation_name',
                    'department.department_name as department_name',
                    'roles.name as role_name'
                )
                ->leftJoin('department', 'department.id', '=', 'doctor.department_id')
                ->leftJoin('users', 'users.id', '=', 'doctor.user_id')
                ->leftJoin('roles', 'roles.id', '=', 'users.role')
                ->where('doctor.is_active', $statusTab === 'active' ? 1 : 0);
        } else {
            $query = Staff::query()
                ->select(
                    'staff.id',
                    'staff.name',
                    'staff.surname',
                    'staff.email',
                    'staff.contact_no',
                    'staff.is_active',
                    'department.department_name as department_name',
                    'staff_designation.designation as designation_name',
                    'roles.name as role_name'
                )
                ->leftJoin('department', 'department.id', '=', 'staff.department_id')
                ->leftJoin('staff_designation', 'staff_designation.id', '=', 'staff.staff_designation_id')
                ->leftJoin('users', 'users.id', '=', 'staff.user_id')
                ->leftJoin('roles', 'roles.id', '=', 'users.role')
                ->where('staff.is_active', $statusTab === 'active' ? 1 : 0);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('staff.name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('staff.surname', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('staff.email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('department.name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('staff_designation.name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('roles.name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $users = $query->get();

        return view("admin.setup.users", compact("users", 'isDoctorTab', 'statusTab'));
    }

    public function updateDrStatus(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        // dd($request->is_active == null);
        $doctor->is_active = $request->is_active == null ? 0 : 1;
        $doctor->save();
        return redirect()->back()->with('success', 'Doctor Status Updated');
    }
    public function updateStaffStatus(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        $user  = User::find($staff->user_id);
        if ($user) {
            $user->is_active = $request->is_active == null ? "no" : "yes";
            $user->save();
        }
        $staff->is_active = $request->is_active == null ? 0 : 1;
        $staff->save();
        return redirect()->back()->with('success', 'Staff Status Updated');
    }

}
