<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $isDoctorTab = $request->get('tab', 'doctor') == 'doctor';
        $statusTab   = $request->get('statusTab', 'active');
         $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }
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

        $query->where(function ($q) use ($searchTerm, $isDoctorTab) {

            if ($isDoctorTab) {
                // 🔹 Doctor search
                $q->where('doctor.name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('doctor.surname', 'LIKE', "%{$searchTerm}%")
                ->orWhere('doctor.email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('department.department_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('doctor.designation', 'LIKE', "%{$searchTerm}%")
                ->orWhere('roles.name', 'LIKE', "%{$searchTerm}%");
            } else {
                // 🔹 Staff search
                $q->where('staff.name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('staff.surname', 'LIKE', "%{$searchTerm}%")
                ->orWhere('staff.email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('department.department_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('staff_designation.designation', 'LIKE', "%{$searchTerm}%")
                ->orWhere('roles.name', 'LIKE', "%{$searchTerm}%");
            }

        });
    }

         $users = $query->paginate($perPage)->appends($request->all());

        return view("admin.setup.users", compact("users", 'isDoctorTab', 'statusTab','perPage'));
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
    public function createCredentials($staffId)
    {
        $staff = Staff::findOrFail($staffId);

        // Check if credentials already exist
        $existingUser = User::where('user_id', $staff->id)->first();

        if ($existingUser) {
            return back()->with('error', 'Credentials already exist for this staff.');
        }

        $password = '123456';

        $user = new User();
        $user->hospital_id = $staff->hospital_id;
        $user->branch_id   = $staff->branch_id;
        $user->username    = $staff->name . ' ' . $staff->surname;
        $user->email       = $staff->email ?? ($staff->name . '.' . $staff->surname . '@temp.com');
        $user->password    = Hash::make($password);
        $user->role        = $staff->role_id;
        $user->user_id     = $staff->id;
        $user->is_active   = 1;
        $user->save();

        return back()->with('success', 'Credentials created! Password: ' . $password);
    }
    public function showChangePassword()
    {
        return view('admin.setup.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }

}
