<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoseDuration;
class DosageDurationController extends Controller
{
    function index(Request $request){ {
        $dosageDuration = DoseDuration::query();
        $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
           $perPage = 5;
        }
        if($request->has('search')){
            $search_term = $request->search;
            $dosageDuration->where(function ($query) use ($search_term) {
                $query->where('name', 'like', "%{$search_term}%");
            });
            $dosageDuration = $dosageDuration->paginate($perPage);
            return array("result"=>$dosageDuration);
        }
        $dosageDuration = $dosageDuration->paginate($perPage);
    }
        return view('admin.setup.dosage_duration',compact('dosageDuration'));
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
                return redirect()->back()->with('error', 'Missing name for dosage duration at index ');
            }
            $dosageDuration = new DoseDuration;
            $dosageDuration->name = $names[$key];
            $dosageDuration->save();
        }
        return redirect()->back()->with('success', "Successfully Added Dosage Duration");
    }
    public function update(Request $request) {
        $request->validate([
            "id"=>"required",
            "name"=>"required"
        ]);
        $dosageDuration = DoseDuration::findOrFail($request->id);
        $dosageDuration->name = $request->name;
        $dosageDuration->save();
        return redirect()->back()->with('success','Dosage Duration Updated Successfully');
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:dose_duration,id',
        ]);
    
        $dosageDuration = DoseDuration::findOrFail($request->id);
        $dosageDuration->delete();

        return redirect()->back()
                         ->with('success', 'Dosage Duration deleted successfully.');
    }
}
