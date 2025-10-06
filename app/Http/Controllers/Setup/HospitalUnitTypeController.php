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
}
