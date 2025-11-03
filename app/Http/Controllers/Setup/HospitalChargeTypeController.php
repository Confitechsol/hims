<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChargeTypeMaster;
use App\Models\ChargeTypeModule;
use Illuminate\Support\Facades\DB;


class HospitalChargeTypeController extends Controller
{
    public function index(){
     $chargetypes = ChargeTypeMaster::get();
     $chargestypemodule = DB::table('charge_type_module as ctm')
    ->join('charge_type_master as ctmst', 'ctm.charge_type_master_id', '=', 'ctmst.id')
    ->select('ctm.*', 'ctmst.charge_type')
    ->whereRaw('ctm.id IN (
        SELECT MIN(id) 
        FROM charge_type_module 
        GROUP BY module_shortcode
    )')
    ->orderBy('ctm.id')
    ->get();
       $filter = [];
       foreach($chargetypes as $chargetype){
          $modules = ChargeTypeModule::where('charge_type_master_id', $chargetype->id)->pluck('module_shortcode')->toArray();
          $filter[$chargetype->id] = $modules;
       }
     //   return array("filter"=>$filter,"chargestypemodule"=>$chargestypemodule);
         return view('admin.setup.charge_type',compact('chargetypes','chargestypemodule','filter'));
    }

    public function store(Request $request ){
    $request->validate ( [
         'charge_type' => 'required',
         'module'=>'required|array|min:1',
         'module.*'=>'required'
    ]);
     $chargeType = ChargeTypeMaster::create(['charge_type'=>$request->charge_type,'is_default'=>'yes','is_active'=>'yes']);
     foreach ($request->module as $module) {
          ChargeTypeModule::create([
               "charge_type_master_id"=>$chargeType->id,
               "module_shortcode"=>$module
          ]);
     }         
     return redirect()->back()->with("success","Charge Type Created Sucessfully!");

    }
    public function update(Request $request){
     $request->validate([
          'id'=>'required',
          'charge_type'=>'required'
     ]);
     $chargeType = ChargeTypeMaster::findOrFail($request->id);
     $chargeType->update([
          "charge_type"=>$request->charge_type
     ]);
     return redirect()->back()->with('success',"Charge Type Successfully Updated");
    }
    public function destroy(Request $request){
     $request->validate([
          'id'=>'required'
     ]);
     $chargeType = ChargeTypeMaster::findOrFail($request->id);
     $chargeType->delete();
     return redirect()->back()->with('success',$chargeType->charge_type." Successfully Updated");
    }
    public function updateChargeTypeModule(Request $request)
    {
        $chargeTypeId = $request->input('charge_type_master_id');
        $moduleShortcode = $request->input('module_shortcode');
        $checked = $request->input('checked');
    
        if ($checked) {
            // Create if not exists
            ChargeTypeModule::firstOrCreate([
                'charge_type_master_id' => $chargeTypeId,
                'module_shortcode' => $moduleShortcode
            ]);
        } else {
            // Delete the mapping
            ChargeTypeModule::where([
                'charge_type_master_id' => $chargeTypeId,
                'module_shortcode' => $moduleShortcode
            ])->delete();
        }
    
        return response()->json(['success' => true]);
    }
}
