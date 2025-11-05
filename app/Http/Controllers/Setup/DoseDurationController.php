<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\DoseDuration;
use Illuminate\Http\Request;

class DoseDurationController extends Controller
{
    public function index()
    {
        $durations = DoseDuration::orderBy('id', 'desc')->get();
        return view('admin.setup.dose_duration', compact('durations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        DoseDuration::create([
            'hospital_id' => session('hospital_id', '1'),
            'branch_id' => session('branch_id', '1'),
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Dose Duration added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $duration = DoseDuration::findOrFail($id);
        $duration->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Dose Duration updated successfully!');
    }

    public function destroy($id)
    {
        $duration = DoseDuration::findOrFail($id);
        $duration->delete();

        return redirect()->back()->with('success', 'Dose Duration deleted successfully!');
    }
}
