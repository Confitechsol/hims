<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\IpdDetail;
use App\Models\IpdPatient;

class PdfController extends Controller
{
    //
    // $order = Order::findOrFail($id);
    public function generatePdf($id)
    {
       $IpdPatient = IpdPatient::with(['patient','ipd.bedDetail.bedGroup','doctor','ipd.organisation'])->findOrFail($id);
        return view('pdf.invoice',compact('IpdPatient')); 
    // $pdf = Pdf::loadView('pdf.invoice')->setPaper('a4', 'landscape');
    // return $pdf->download('invoice.pdf');
    }
}
