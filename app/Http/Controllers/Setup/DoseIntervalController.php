<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoseInterval;
class DoseIntervalController extends Controller
{
    public function index(Request $request) {
        $dosageInterval = DoseInterval::query();
        // return $dosageInterval;
        $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
           $perPage = 5;
        }
        if($request->has('search')){
            $search_term = $request->search;
            $dosageInterval->where(function ($query) use ($search_term) {
                $query->where('name', 'like', "%{$search_term}%");
            });
            $dosageInterval = $dosageInterval->paginate($perPage);
            return array("result"=>$dosageInterval);
        }
        $dosageInterval = $dosageInterval->paginate($perPage);
        return view('admin.setup.dosage_interval',compact('dosageInterval'));
    }
    public function store(Request $request) {
        $request->validate([
            "name"=>'required|array|min:1',
            "name.*"=>'required'
        ]);
        $names = $request->name;
        foreach ($names as $key => $name) {
            // Make sure that the key exists in both arrays
            if (!isset($names[$key])) {
                return redirect()->back()->with('error', 'Missing name for dosage interval at index ');
            }
            $dosageInterval = new DoseInterval;
            $dosageInterval->name = $names[$key];
            $dosageInterval->save();
        }
        return redirect()->back()->with('success', "Successfully Added Dosage Interval");
    }
    public function update(Request $request) {
        $request->validate([
            "id"=>"required",
            "name"=>"required"
        ]);
        $dosageInterval = DoseInterval::findOrFail($request->id);
        $dosageInterval->name = $request->name;
        $dosageInterval->save();
        return redirect()->back()->with('success','Dosage Interval Updated Successfully');
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:dose_interval,id',
        ]);
    
        $dosageInterval = DoseInterval::findOrFail($request->id);
        $dosageInterval->delete();

        return redirect()->back()
                         ->with('success', 'Dosage Interval deleted successfully.');
    }
}
