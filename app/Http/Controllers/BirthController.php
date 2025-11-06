<?php

namespace App\Http\Controllers;
use App\Models\BirthReport;
use Illuminate\Http\Request;

class BirthController extends Controller
{
    function index(Request $request){
     $perPage = intval($request->input('perPage', 5));
    if ($perPage <= 0) {
        $perPage = 5;
    }
    $query = BirthReport::with(['patient']);
    if ($request->has('search')) {
        $search_term = $request->search;
        $query->where(function ($q) use ($search_term) {
            $q->where('child_name', 'like', "%{$search_term}%")
                ->orWhere('father_name', 'like', "%{$search_term}%")
                ->orWhereHas('patient', function ($sub) use ($search_term) {
                    $sub->where('patient_name', 'like', "%{$search_term}%");
                });
        });
    }
    $birthReports = $query->paginate($perPage);
    //return response()->json($birthReports, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
     return view('admin.birthordeath.index', compact('birthReports'));
    }
}
