<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IpdDetail;
use App\Models\IpdPatient;
use App\Models\Hospital;

class PdfController extends Controller
{
    //
    // $order = Order::findOrFail($id);
    public function generatePdf($id,Request $request)
    {
       $hospital = Hospital::first();
       $IpdPatient = IpdPatient::with(['patient.organisation','ipd.bedDetail.bedGroup',
       'doctor',
       'doctor2',
       'doctor3',
       'doctor4',
       'ipd.organisation'])->findOrFail($id);
        return view('pdf.index',compact('IpdPatient','hospital'));
    }
    
}
