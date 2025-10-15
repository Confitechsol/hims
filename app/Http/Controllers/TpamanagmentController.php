<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisation;
use App\Models\ChargeTypeMaster;
use Illuminate\Support\Facades\DB;

class TpamanagmentController extends Controller
{
    function index(Request $request) {
    $query = Organisation::query();
    if ($request->has("search")) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
        $q->where('organisation_name', 'like', "%{$search}%")
          ->orWhere('code', 'like', "%{$search}%")
          ->orWhere('contact_no', 'like', "%{$search}%")
          ->orWhere('contact_person_name', 'like', "%{$search}%")
          ->orWhere('contact_person_phone', 'like', "%{$search}%");
    });
    $data = $query->get();
    return array(
        "status"=>200,
        "result"=>$data,
        "total"=>count($data)
    );
    }
    $organisations = $query->get();

    return view('admin.tpa.tpamanagement', compact('organisations'));
        
    }
    
    function store(Request $request) {
        $request->validate([
            'organisation_name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:organisation,code',
            'contact_no' => 'required|string|max:15|different:contact_person_phone',
            'address' => 'required|string|max:500',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:15|different:contact_no',
        ]);

        $organisation = new Organisation();
        $organisation->organisation_name = $request->organisation_name;
        $organisation->code = $request->code;
        $organisation->contact_no = $request->contact_no;
        $organisation->address = $request->address;
        $organisation->contact_person_name = $request->contact_person_name;
        $organisation->contact_person_phone = $request->contact_person_phone;
        $organisation->save();

        return redirect()->route('tpamanagement')->with('success', 'TPA added successfully.');
    }

    function update(Request $request) {
        $request->validate([
            'id' => 'required|exists:organisation,id',
            'organisation_name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:organisation,code,'.$request->id,
            'contact_no' => 'required|string|max:15|different:contact_person_phone',
            'address' => 'required|string|max:500',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:15|different:contact_no',
        ]);

        $organisation = Organisation::findOrFail($request->id);
        $organisation->update([
            'organisation_name' => $request->organisation_name,
            'code' => $request->code,
            'contact_no' => $request->contact_no,
            'address' => $request->address,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_phone' => $request->contact_person_phone,
        ]);

        return redirect()->route('tpamanagement')->with('success', 'TPA updated successfully.');
    }

    function destroy(Request $request) {
        $request->validate([
            'id' => 'required|exists:organisation,id',
        ]);

        $organisation = Organisation::findOrFail($request->id);
        $organisation->delete();

        return redirect()->route('tpamanagement')->with('success', 'TPA deleted successfully.');
    }

    function detailsshow($id=null, Request $request) {

        if($id==null){
            return redirect()->route('tpamanagement');
        }
        $chargetypes = ChargeTypeMaster::all();
        $organisations = Organisation::findOrFail($id);
        //return $organisations;
        if($request->isMethod('post')){
            $request->validate([
                'charge_type' => 'required|exists:charge_type_master,id',
            ]);
            $charge_type = $request->input('charge_type');
            
            DB::select("select * from organisation_charge_type where organisation_id=$id and charge_type_id=$charge_type");
            return $charge_type;
        }
        return  view('admin.tpa.tpa_details', compact('organisations','chargetypes'));
    }
}

