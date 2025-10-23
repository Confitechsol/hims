<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
class UnitController extends Controller
{
    function index(Request $request)
    {
        $units = Unit::query();
        $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
           $perPage = 5;
        }
        if ($request->has('search')) {
            $search_term = $request->search;
            $units->where(function ($query) use ($search_term) {
                $query->where('unit_name', 'like', "%{$search_term}%");
            });
            $units = $units->paginate($perPage);
            return array("result" => $units);
        }
        $units = $units->paginate($perPage);

        return view('admin.setup.unit_list', compact('units'));
    }

    function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }

        $request->validate([
            "unit_name" => "required|array|min:1",
            "unit_name.*" => 'required'
        ],
        [
            "unit_name.required" => "Please provide at least one unit name.",
            "unit_name.*.required" => "Each unit name is required."
        ]);
        $unit_names = $request->unit_name;
        foreach ($unit_names as $key => $unit_name) {
            if (!isset($unit_names[$key])) {
                return redirect()->back()->with('error', 'Missing name for unit at index ' . $key);
            }
            $unit = new Unit;
            $unit->unit_name = $unit_names[$key];
            $unit->unit_type = "pharmacy";
            $unit->hospital_id = $user->hospital_id;
            $unit->save();
        }
        return redirect()->back()->with('success', "Successfully Added Unit");
    }
    function update(Request $request)
    {
        $request->validate([
            "id" => "required",
            "unit_name" => "required"
        ]);
        $unit = Unit::findOrFail($request->id);
        $unit->unit_name = $request->unit_name;
        $unit->save();
        return redirect()->back()->with('success', 'Unit Updated Successfully');
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:unit,id',
        ]);
        $unit = Unit::findOrFail($request->id);
        $unit->delete();
        return redirect()->back()->with('success', 'Unit deleted successfully.');
    }
}
