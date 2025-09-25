<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Charge;

class HospitalChargesController extends Controller
{
    public function index(){
       $charges = Charge::with(['category','taxCategory','unit','chargeType'])->get();
    //   return $charges;
         return view('admin.setup.charges',compact('charges'));
    }
}
