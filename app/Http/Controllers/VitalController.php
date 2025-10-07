<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vital;

class VitalController extends Controller
{
    public function index()
    {
        $vitals = Vital::all();
        return view('admin.setup.vital', compact('vitals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vital_name' => 'required|string|max:255',
            'range_from' => 'nullable|string|max:50',
            'range_to' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:100',
        ]);
         $reference_range = null;
        if ($request->filled('range_from') || $request->filled('range_to')) {
            $reference_range = trim($request->range_from . ' - ' . $request->range_to, ' -');
        }
        Vital::create([
            'name' => $request->vital_name,
            'range_from' => $request->range_from,
            'range_to'   => $request->range_to,
            'unit'       => $request->unit,
            'reference_range' => $reference_range,
        ]);

        return redirect()->back()->with('success', 'Vital added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vital_name' => 'required|string|max:255',
            'range_from' => 'nullable|string|max:50',
            'range_to' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:100',
        ]);

        $vital = Vital::findOrFail($id);
        $reference_range = null;
        if ($request->filled('range_from') || $request->filled('range_to')) {
            $reference_range = trim($request->range_from . ' - ' . $request->range_to, ' -');
        }
         $vital->update([
            'name' => $request->vital_name,
            'range_from' => $request->range_from,
            'range_to'   => $request->range_to,
            'unit'       => $request->unit,
            'reference_range' => $reference_range,
        ]);

        return redirect()->back()->with('success', 'Vital updated successfully.');
    }

    public function destroy($id)
    {
        $vital = Vital::findOrFail($id);
        $vital->delete();

        return redirect()->back()->with('success', 'Vital deleted successfully.');
    }
}
