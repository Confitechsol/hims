<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\MedicineUnit;
use Illuminate\Http\Request;

class MedicineUnitController extends Controller
{
    public function index()
    {
        $units = MedicineUnit::orderBy('id', 'desc')->get();
        return view('admin.setup.medicine_unit', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255'
        ]);

        MedicineUnit::create([
            'hospital_id' => session('hospital_id', '1'),
            'branch_id' => session('branch_id', '1'),
            'unit_name' => $request->unit_name
        ]);

        return redirect()->back()->with('success', 'Unit added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255'
        ]);

        $unit = MedicineUnit::findOrFail($id);
        $unit->update([
            'unit_name' => $request->unit_name
        ]);

        return redirect()->back()->with('success', 'Unit updated successfully!');
    }

    public function destroy($id)
    {
        $unit = MedicineUnit::findOrFail($id);
        $unit->delete();

        return redirect()->back()->with('success', 'Unit deleted successfully!');
    }
}
