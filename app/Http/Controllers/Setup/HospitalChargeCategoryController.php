<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChargeCategory;

class HospitalChargeCategoryController extends Controller
{
    public function index(){
       $chargesCatogery = ChargeCategory::with(['chargeType'])->get();
     //  return $chargesCatogery;
         return view('admin.setup.charge_category',compact('chargesCatogery'));
    }
}
