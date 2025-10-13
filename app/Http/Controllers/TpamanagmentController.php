<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisation;

class TpamanagmentController extends Controller
{
    function index() {

        $organisations = Organisation::all();
        return view('admin.tpa.tpamanagement', compact('organisations'));
        // return view('admin.tpa.tpamanagement', compact('organisations'));
        
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
}

