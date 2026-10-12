<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\IpdDetail;
use App\Models\IpdDaywiseBedCharge;
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
       $IpdPatient = IpdPatient::with(['patient.organisation','patient.areaName','ipd.patient.organisation','ipd.patient.areaName','ipd.bedDetail.bedGroup',
       'ipd.insuranceCompany',
       'doctor',
       'doctor2',
       'doctor3',
       'doctor4',
       'ipd.organisation'])->findOrFail($id);
       $daywiseBedCharge = $IpdPatient->ipd
           ? IpdDaywiseBedCharge::where('ipd_id', $IpdPatient->ipd->id)
               ->where('is_active', 'yes')
               ->orderBy('charge_date')
               ->orderBy('id')
               ->first(['bed_charge_rate', 'bed_charge'])
           : null;
       $bedChargeRate = $daywiseBedCharge?->bed_charge_rate ?? $daywiseBedCharge?->bed_charge;
       $bedChargeRate ??= $IpdPatient->ipd?->bedDetail?->bedGroup?->bed_cost;

       return view('pdf.index', compact('IpdPatient', 'hospital', 'user', 'bedChargeRate'));
    //      return response()->json([
    //     'hospital' => $hospital,
    //     'ipd_patient' => $IpdPatient,
    //     'name' => $name
    // ]);
    }
    
}
