<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;

class DoctorVisitController extends Controller
{
    /**
     * Show create form
     */
    public function create()
    {
        $patients = Patient::all();
        $doctors  = Doctor::all();

        return view('admin.doctor-visit.create', compact('patients','doctors'));
    }

   

    
}
