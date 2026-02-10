<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChargeCategory;
use App\Models\ChargeTypeMaster;

class HospitalChargeCategoryController extends Controller
{
    public function index(Request $request){

       $chargesCatogery = ChargeCategory::query();
       
       $charge_types = ChargeTypeMaster::all();
        $perPage   = intval($request->input('perPage', 10));
        if ($perPage <= 0) {
            $perPage = 10;
        }
       if ($request->has('search')) {
         $search_term = $request->search;
            $chargesCatogery->where(function ($query) use ($search_term) {
                $query->where('name', 'like', "%{$search_term}%");
            });
         $chargesCatogery = $chargesCatogery->with('chargeType')->paginate($perPage);
         return ["result" => $chargesCatogery];
       }
       $chargesCatogery = $chargesCatogery->paginate($perPage);
         return view('admin.setup.charge_category',compact('chargesCatogery','charge_types'));
    }

    public function store(Request $request ){
    $validator = $request->validate ( [
        'charge_type' => 'required',
        'name' => 'required',
        'description' => 'required',

        
    ]);

     ChargeCategory::create(['charge_type_id'=>$request->charge_type,'name'=>$request->name,'description'=>$request->description,'short_code'=>'null','is_default'=>'null','hospital_id'=>'HS001']);
     
     return redirect()->back()->with("success","ChargeCategory Created Sucessfully!");

    }

    public function update(Request $request){
        $validator = $request->validate ( [
            'id'=>'required|exists:charge_categories,id',
            'charge_type' => 'required',
            'name' => 'required',
            'description' => 'required',
    
            
        ]);
    
         $chargeCategory = ChargeCategory::findOrFail($request->id);
         $chargeCategory->charge_type_id = $request->charge_type;
         $chargeCategory->name = $request->name;
         $chargeCategory->description = $request->description;
         $chargeCategory->save();
         
         return redirect()->back()->with("success","ChargeCategory Updated Sucessfully!");
    
    }

    public function destroy(Request $request)
    {
            $validator = $request->validate ([
                'id'=>'required'
            ]);
            $chargeCategory = ChargeCategory::findOrFail($request->id);
            $chargeCategory->delete();
            return redirect()->back()->with("success","Charge Category Deleted Sucessfully!");
    }
}
