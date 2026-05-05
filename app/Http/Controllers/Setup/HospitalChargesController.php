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
       $charges = Charge::query();
       $charge_types = ChargeTypeMaster::all();
       $chargeCategories = ChargeCategory::all();
       $charge_unit= ChargeUnit::all();
       $charge_tax_category_id=TaxCategory::all();
       $organisation_names=organisation::all();
       $organisation_charges = OrganisationsCharge::all();
       $perPage   = intval($request->input('perPage', 10));
        if ($perPage <= 0) {
            $perPage = 10;
        }

    //     if ($request->has('search')) {
    //      $search_term = $request->search;
    //         $charges->where(function ($query) use ($search_term) {
    //             $query->where('name', 'like', "%{$search_term}%");
    //         });
    //      $charges = $charges->with('category.chargeType','unit','taxCategory')->paginate($perPage);
    //      return ["result" => $charges];
    // }
    //  $charges = $charges->paginate($perPage);
    // ✅ SEARCH FILTER (NO JSON RETURN)
    if ($request->filled('search')) {
        $search_term = $request->search;

        $charges->where('name', 'like', "%{$search_term}%");
    }

    // ✅ ALWAYS RETURN RELATIONS + VIEW
    $charges = $charges->with('category.chargeType', 'unit', 'taxCategory')
                       ->paginate($perPage)
                       ->withQueryString();

     return view('admin.setup.charges',compact('charges','charge_types','charge_unit','charge_tax_category_id','organisation_names','chargeCategories','organisation_charges'));

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
