<?php

namespace App\Http\Controllers;
use App\Models\VisitorsPurpose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorsController extends Controller
{
    function index(Request $request){

    $query = VisitorsPurpose::with(['visitorBooks'])->get();
    $perPage = intval($request->input('perPage', 5));
     if ($perPage <= 0) {
        $perPage = 5;
    }
    $query = VisitorsPurpose::with(['visitorBooks']);
    if ($request->has('search')) {
        $search_term = $request->search;
        $query->where(function ($q) use ($search_term) {
            $q->where('visitors_purpose', 'like', "%{$search_term}%")
                ->orWhereHas('visitorBooks', function ($sub) use ($search_term) {
                    $sub->where('name', 'like', "%{$search_term}%");
                });
        });
    }
    $visitorsReports = $query->paginate($perPage);
 //   return $visitorsReports;
 //  return response()->json($visitorsReports , 200, [], JSON_INVALID_UTF8_SUBSTITUTE);

    return view('admin.front-office.visitorlist' ,compact('visitorsReports'));
    }
}
