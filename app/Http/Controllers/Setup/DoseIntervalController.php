<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\DoseInterval;
use Illuminate\Http\Request;

class DoseIntervalController extends Controller
{
    public function index()
    {
        $intervals = DoseInterval::orderBy('id', 'desc')->get();
        return view('admin.setup.dose_interval', compact('intervals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        DoseInterval::create([
            'hospital_id' => session('hospital_id', '1'),
            'branch_id' => session('branch_id', '1'),
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Dose Interval added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $interval = DoseInterval::findOrFail($id);
        $interval->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Dose Interval updated successfully!');
    }

    public function destroy($id)
    {
        $interval = DoseInterval::findOrFail($id);
        $interval->delete();

        return redirect()->back()->with('success', 'Dose Interval deleted successfully!');
    }
}
