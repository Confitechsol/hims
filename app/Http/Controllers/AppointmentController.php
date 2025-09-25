<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\GlobalShift;

class AppointmentController extends Controller
{
    public function slots()
    {
        $slots = Appointment::latest()->get();
        return view('admin.setup.slots', compact('slots'));
    }
}
