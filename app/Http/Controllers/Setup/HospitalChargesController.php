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
use App\Models\OrganisationsCharge;


class HospitalChargesController extends Controller
{
    public function index(Request $request){

    $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

    $search = $request->input('search');

     //  $charges = Charge::with(['category.chargeType','taxCategory','unit'])->get();
      $query = Charge::with([
        'category.chargeType',
        'taxCategory',
        'unit'
    ]);

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('charge_name', 'like', "%{$search}%")
              ->orWhere('charge_code', 'like', "%{$search}%")
              ->orWhereHas('category', function ($qc) use ($search) {
                  $qc->where('name', 'like', "%{$search}%");
              });
        });
    }
     $charges = $query->paginate($perPage)->withQueryString();

       $charge_types = ChargeTypeMaster::get();
       $chargeCategories = ChargeCategory::get();
       $charge_unit= ChargeUnit::get();
       $charge_tax_category_id=TaxCategory::get();
       $organisation_names=organisation::get();
       $organisation_charges = OrganisationsCharge::get();
        // return array(
        //     "charge_types"=>$charge_types,
        //     "charge_categories"=>$chargeCategories
        // );
       
    //  return  $charge_tax_category_id;
    // if ($request->ajax()) {
    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Charge list fetched successfully',
    //         'data'    => $charges
    //     ]);
    // }
       return view('admin.setup.charges',compact('charges','charge_types','charge_unit','charge_tax_category_id','organisation_names','chargeCategories','organisation_charges','perPage','search'));
    }

    public function store(Request $request){
    $request->validate ( [
        'charge_type' => 'required',
        'charge_category' => 'required',
        'tax_category' => 'nullable',
        'standard_charge'=>'required',
        'charge_name'=>'required',
        'unit_type'=>'required',
        'schedule_charge_id'=>'required|array',
        'schedule_charge_id.*'=>'required|exists:organisations_charges,id',
    ]);
    // dd(request->all());
    $organisation_ids = $request->schedule_charge_id;
    $charge =  Charge::create([
            'charge_category_id'=>$request->charge_category,
            'tax_category_id'=>$request->tax_category,
            'charge_unit_id'=>$request->unit_type,
            'name'=>$request->charge_name,
            'standard_charge'=>$request->standard_charge,
            'date'=>null,
            'description'=>$request->description,
            'status'=>'',
            // 'hospital_id'=>'HS001'
        ]);
        $newChargeId = $charge->id;
        foreach($organisation_ids as $org_id){
            if($request['schedule_charge_'.$org_id]){
                OrganisationsCharge::create([
                    'charge_id'=>$newChargeId,
                    'org_id'=>$org_id,
                    'org_charge'=>$request['schedule_charge_'.$org_id],
                ]);
            }
            
        }
           return redirect()->back()->with("success","Charges Created Sucessfully!");
    }
    public function update(Request $request){
        //dd($request->all());
        $request->validate ( [
            'charge_id'=>'required',
            'charge_type' => 'required',
            'charge_category' => 'required',
            'tax_category' => 'required',
            'standard_charge'=>'required',
            'charge_name'=>'required',
            'unit_type'=>'required',
            'schedule_charge_id'=>'required|array',
            'schedule_charge_id.*'=>'required|exists:organisations_charges,id',
        ]);
        $organisation_ids = $request->schedule_charge_id;
        $charge = Charge::findOrFail($request->charge_id);
        $charge->update([
            'charge_category_id' => $request->charge_category,
            'tax_category_id' => $request->tax_category,
            'charge_unit_id' => $request->unit_type,
            'name' => $request->charge_name,
            'standard_charge' => $request->standard_charge,
            'date' => null, // Assuming you still want to keep it null
            'description' => $request->description,
            'status' => '', // You can update the status field accordingly
        ]);
        foreach ($organisation_ids as $org_id) {
            if ($request['schedule_charge_' . $org_id]) {
                $organisationCharge = OrganisationsCharge::where('charge_id', $charge->id)
                                                         ->where('org_id', $org_id)
                                                         ->first();
        
                if ($organisationCharge) {
                    // If the record exists, update it
                    $organisationCharge->update([
                        'org_charge' => $request['schedule_charge_' . $org_id],
                    ]);
                } else {
                    // If the record doesn't exist, create it
                    OrganisationsCharge::create([
                        'charge_id' => $charge->id,
                        'org_id' => $org_id,
                        'org_charge' => $request['schedule_charge_' . $org_id],
                    ]);
                }
            }
        }
        return redirect()->back()->with("success",$charge->name." Charge Updated Sucessfully!");
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:charges,id',
        ]);

        $charge = Charge::findOrFail($request->id);

        // Soft delete related organisation charges
        OrganisationsCharge::where('charge_id', $charge->id)->delete();

        // Soft delete main charge
        $charge->delete();

        return redirect()->back()->with(
            'success',
            $charge->name . ' Charge Deleted Successfully!'
        );
    }
}
