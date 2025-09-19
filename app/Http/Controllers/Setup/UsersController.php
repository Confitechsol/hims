<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Staff;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $isDoctorTab = $request->get('tab') === 'doctor';

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
                    'doctor.designation as designation_name',
                    'department.department_name as department_name',
                    'roles.name as role_name'
                )
                ->leftJoin('department', 'department.id', '=', 'doctor.department_id')
                ->leftJoin('users', 'users.id', '=', 'doctor.user_id')
                ->leftJoin('roles', 'roles.id', '=', 'users.role');

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
                ->where('staff.is_active', 1);
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

        return view("admin.setup.users", compact("users", 'isDoctorTab'));
    }

}
