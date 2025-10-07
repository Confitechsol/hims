<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChargeUnit;

class HospitalUnitTypeController extends Controller
{
    public function index(){
       $unittype = ChargeUnit::get();
       // return $unittype;
         return view('admin.setup.unit_type',compact('unittype'));
    }

     public function store(Request $request)
{
    // Validate input
    $validator = $request->validate ( [
        'unit' => 'required|string|max:255',
        
    ]);

    ChargeUnit::create(['unit'=>$request->unit,'is_active'=>'0','hospital_id'=>'HS001']);

    return redirect()->back()->with("success","Unit Created Sucessfully!");

}}
