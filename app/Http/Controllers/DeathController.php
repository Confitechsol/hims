<?php

namespace App\Http\Controllers;
use App\Models\DeathReport;
use Illuminate\Http\Request;

class DeathController extends Controller
{
    function index(Request $request){     
    $query = DeathReport::with(['patient'])->get();
     $perPage = intval($request->input('perPage', 5));
     if ($perPage <= 0) {
        $perPage = 5;
    }
    $query = DeathReport::with(['patient']);
    if ($request->has('search')) {
        $search_term = $request->search;
        $query->where(function ($q) use ($search_term) {
            $q->where('guardian_name', 'like', "%{$search_term}%")
                ->orWhere('case_reference_id', 'like', "%{$search_term}%")
                ->orWhereHas('patient', function ($sub) use ($search_term) {
                    $sub->where('patient_name', 'like', "%{$search_term}%");
                });
        });
    }
    $deathReports = $query->paginate($perPage);
    //    return response()->json($deathReports , 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
      return view('admin.birthordeath.indexdeath', compact('deathReports'));
    }
}
