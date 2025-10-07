<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChargeTypeMaster;
use App\Models\ChargeTypeModule;


class HospitalChargeTypeController extends Controller
{
    public function index(){
       $chargestype = ChargeTypeMaster::get();
       $chargestypemodul = ChargeTypeModule::get();
        // return $chargestypemodul;
         return view('admin.setup.charge_type',compact('chargestype','chargestypemodul'));
    }

    public function store(Request $request ){
    $validator = $request->validate ( [
         'charge_type' => 'required',
        

        
    ]);

     ChargeTypeMaster::create(['charge_type'=>$request->charge_type,'is_default'=>$request->name,'is_active'=>$request->description]);
     
     return redirect()->back()->with("success","ChargeCategory Created Sucessfully!");

    }
}
