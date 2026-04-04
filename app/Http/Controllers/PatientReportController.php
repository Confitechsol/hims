<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;


class PatientReportController extends Controller
{
    // public function patientReport(Request $request)
    // {
    //     $query = Patient::query();

    //     // 🔍 Search
    //     if ($request->filled('search')) {
    //         $search = $request->search;

    //         $query->where(function ($q) use ($search) {
    //             $q->where('patient_name', 'like', "%{$search}%")
    //                 ->orWhere('guardian_name', 'like', "%{$search}%")
    //                 ->orWhere('mobileno', 'like', "%{$search}%");
    //         });
    //     }

    //     // Per page
    //     $perPage = (int) $request->input('perPage', 10);
    //     if ($perPage <= 0) {
    //         $perPage = 10;
    //     }

    //     // Pagination
    //     $patients = $query->orderBy('id', 'desc')
    //         ->paginate($perPage)
    //         ->appends($request->all());


    //   return view("admin.reports.patient.patient_reports");

    //     // ✅ Always return JSON
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Patient report fetched successfully',
    //         'data' => $patients->items(),   // actual records
    //         'pagination' => [
    //             'current_page' => $patients->currentPage(),
    //             'last_page' => $patients->lastPage(),
    //             'per_page' => $patients->perPage(),
    //             'total' => $patients->total(),
    //         ]
    //     ]);
    // }

    public function patientReport(Request $request)
{
    $query = Patient::query();

    // 📅 Date range filter
    // if ($request->filled('from_date') && $request->filled('to_date')) {
    //     $from = Carbon::parse($request->from_date)->startOfDay();
    //     $to = Carbon::parse($request->to_date)->endOfDay();

    //     $query->whereBetween('created_at', [$from, $to]);
    // }

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
