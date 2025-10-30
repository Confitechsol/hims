<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChargeUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HospitalUnitTypeController extends Controller
{
    public function index(Request $request){
        
       $query = ChargeUnit::query();
       $perPage = $request->input('per_page', 10); 
   if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('unit', 'like', "%{$search}%");
        $data = $query->paginate($perPage);
        return $data;
    }
    $unittype =   $query->paginate($perPage);
    return view('admin.setup.unit_type',compact('unittype'));
    }

    public function store(Request $request){

    $hospital_id = Auth::user()->hospital_id ?? 'HS000'; // Get hospital_id from authenticated user or default
    // Validate input
    $validator = $request->validate ( [
        'unit' => 'required|string|max:255',
        
    ]);

    ChargeUnit::create(['unit'=>$request->unit,'is_active'=>'0','hospital_id'=>$hospital_id]);

    return redirect()->back()->with("success","Unit Created Sucessfully!");

    }

    public function update(Request $request){
        $validator = $request->validate ( [
            'id'=>'required|exists:charge_units,id',
            'unit' => 'required|string|max:255'
        ]);
        $unittype = ChargeUnit::findOrFail($request->id);
        $unittype->unit = $request->unit;
        $unittype->save();
        return redirect()->back()->with("success","Unit Updated Sucessfully!");
    }
    public function destroy(Request $request){
        $validator = $request->validate ([
            'id'=>'required'            
        ]);
        $unittype = ChargeUnit::findOrFail($request->id);
        $unittype->delete();
        return redirect()->back()->with("success","Unit Deleted Sucessfully!");
    }
}
