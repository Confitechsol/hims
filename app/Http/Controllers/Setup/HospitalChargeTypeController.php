<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChargeTypeMaster;

class HospitalChargeTypeController extends Controller
{
    public function index(){
       $chargestype = ChargeTypeMaster::get();
        // return $chargestype;
         return view('admin.setup.charge_type',compact('chargestype'));
    }
}
