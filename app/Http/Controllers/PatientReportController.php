<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;


class PatientReportController extends Controller
{
    
    public function patientReport(Request $request)
{
  //  $query = Patient::query();
    $query = Patient::with(['ipds.doctor', 'districtName', 'stateName']);

    $patients = $query->orderBy('id', 'desc')->get();

  // return response()->json($patients);
    return view("admin.reports.patient.patient_reports", compact('patients'));
}

public function patientReportApi(Request $request)
{
    
    $query = Patient::with(['ipds.doctor', 'districtName', 'stateName']); // Eager load IpdDetails, Doctor, District, State
    
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $from = Carbon::parse($request->from_date)->startOfDay();
        $to = Carbon::parse($request->to_date)->endOfDay();

        $query->whereBetween('created_at', [$from, $to]);
    }

    $patients = $query->orderBy('id', 'desc')->get();

    return response()->json([
        'status' => true,
        'message' => 'Patient data fetched successfully',
        'data' => $patients
    ]);
}


}
