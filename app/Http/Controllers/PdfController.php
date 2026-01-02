<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IpdDetail;
use App\Models\IpdPatient;

class PdfController extends Controller
{
    //
    // $order = Order::findOrFail($id);
    public function generatePdf($id,Request $request)
    {
        $IpdPatient = IpdPatient::with(['patient','ipd.bedDetail.bedGroup','doctor','ipd.organisation'])->findOrFail($id);
        return view('pdf.invoice',compact('IpdPatient'));
    }
}
