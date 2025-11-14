<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Specialist;
use App\Models\Department;
use App\Models\Designation;


class StaffController extends Controller
{
     public function index()
    {
        $staffs = Staff::with('department')->get();
        return view('admin.staff.staffs', compact("staffs"));
    }
    public function create()
    {
        
        return view('admin.staff.addStaff');
    }
}
