<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChargeUnit;
use Illuminate\Validation\Rule;

class HospitalUnitTypeController extends Controller
{
    public function index()
    {
        $unittype = ChargeUnit::get();
        // return $unittype;
        return view('admin.setup.unit_type', compact('unittype'));
    }

    public function store(Request $request)
    {
        // Validate input
        $validator = $request->validate([
            'unit' => 'required|string|max:255',

        ]);

        ChargeUnit::create(['unit' => $request->unit, 'is_active' => '0', 'hospital_id' => 'HS001']);

        return redirect()->back()->with("success", "Unit Created Sucessfully!");

    }
    public function update(Request $request)
    {
        $validator = $request->validate([
            'id' => 'required|exists:charge_units,id',
            'update_unit' => 'required|string|max:255'

        ]);
        $unittype = ChargeUnit::findOrFail($request->id);
        $unittype->unit = $request->update_unit;
        $unittype->save();
        return redirect()->back()->with("success", "Unit Type Updated Sucessfully!");
    }

      public function delete(Request $request)
    {
        $validator = $request->validate([
            'id' => 'required'
        ]);
        $unittype = ChargeUnit::findOrFail($request->id);
        $unittype->delete();
        return redirect()->back()->with("success", "Unit Type Deleted Sucessfully!");
    }

}
