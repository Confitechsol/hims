<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\IpdDetail;
use App\Models\IpdPatient;
use App\Models\Hospital;
use App\Models\Area;

class PdfController extends Controller
{
    //
    // $order = Order::findOrFail($id);
    public function generatePdf($id,Request $request)
    {
       $hospital = Hospital::first();
       $user = Auth::user();
       $IpdPatient = IpdPatient::with(['patient.organisation','patient.areaName','ipd.bedDetail.bedGroup',
       'doctor',
       'doctor2',
       'doctor3',
       'doctor4',
       'ipd.organisation'])->findOrFail($id);
       return view('pdf.index',compact('IpdPatient','hospital','user'));
    //      return response()->json([
    //     'hospital' => $hospital,
    //     'ipd_patient' => $IpdPatient,
    //     'name' => $name
    // ]);
    }
    
}
