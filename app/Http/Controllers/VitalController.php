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
            'from' => 'nullable|string|max:50',
            'to' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:100',
        ]);

        Vital::create($request->only(['vital_name', 'from', 'to', 'unit']));

        return redirect()->back()->with('success', 'Vital added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vital_name' => 'required|string|max:255',
            'from' => 'nullable|string|max:50',
            'to' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:100',
        ]);

        $vital = Vital::findOrFail($id);
        $vital->update($request->only(['vital_name', 'from', 'to', 'unit']));

        return redirect()->back()->with('success', 'Vital updated successfully.');
    }

    public function destroy($id)
    {
        $vital = Vital::findOrFail($id);
        $vital->delete();

        return redirect()->back()->with('success', 'Vital deleted successfully.');
    }
}
