<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Charge;
use App\Models\ChargeTypeMaster;
use App\Models\ChargeUnit;
use App\Models\TaxCategory;
use App\Models\Organisation;
use App\Models\ChargeCategory;


class HospitalChargesController extends Controller
{
    public function index(){
       $charges = Charge::with(['category.chargeType','taxCategory','unit'])->get();
       $charge_types = ChargeTypeMaster::get();
       $chargeCategories = ChargeCategory::get();
       $charge_unit= ChargeUnit::get();
       $charge_tax_category_id=TaxCategory::get();
       $organisation_name=organisation::get();
        // return array(
        //     "charge_types"=>$charge_types,
        //     "charge_categories"=>$chargeCategories
        // );
       
    //  return  $charge_tax_category_id;
         return view('admin.setup.charges',compact('charges','charge_types','charge_unit','charge_tax_category_id','organisation_name','chargeCategories'));
    }

    public function store(Request $request){
return $request->schedule_charge_5;
         $validator = $request->validate ( [
        // 'charge_type' => 'required',
        // 'name' => 'required',
        // 'description' => 'required',

        
    ]);
        // return $request->charge_type;
         Charge::create(['charge_category_id'=>$request->charge_type,'tax_category_id'=>$request->charge_category,'charge_unit_id'=>$request->unit_type,'name'=>$request->charge_name,'standard_charge'=>$request->standard_charge,'date'=>null,'description'=>$request->description,'status'=>'','hospital_id'=>'HS001']);
           return redirect()->back()->with("success","Charges Created Sucessfully!");



    }
}
