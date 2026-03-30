<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientReportController extends Controller
{
    public function patientReport(Request $request)
{
    $query = Patient::query();

    // 🔍 Search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('patient_name', 'like', "%{$search}%")
              ->orWhere('guardian_name', 'like', "%{$search}%")
              ->orWhere('mobileno', 'like', "%{$search}%");
        });
    }

    // Per page
    $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

    // Pagination
    $patients = $query->orderBy('id', 'desc')
                      ->paginate($perPage)
                      ->appends($request->all());

    // ✅ Always return JSON
    return response()->json([
        'status' => true,
        'message' => 'Patient report fetched successfully',
        'data' => $patients->items(),   // actual records
        'pagination' => [
            'current_page' => $patients->currentPage(),
            'last_page' => $patients->lastPage(),
            'per_page' => $patients->perPage(),
            'total' => $patients->total(),
        ]
    ]);
}

  
}
