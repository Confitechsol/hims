<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\RadiologyCategory;
use App\Models\RadiologyParameter;
use App\Models\RadiologyUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RadiologyController extends Controller
{
    public function radiologyCategoryIndex()
    {
        $radiologyCategories = RadiologyCategory::all();

        return view("admin.setup.radiology_category", compact("radiologyCategories"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        RadiologyCategory::create([
            "name"        => $request->category_name,
            'hospital_id' => $user->hospital_id,
            "is_active"   => 'yes',
        ]);

        return redirect()->back()->with('success', 'Radiology Category Successfully Added.');
    }
    public function update(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_id'   => 'required|exists:radiology_category,id',
        ]);

        $radiologyCategory = RadiologyCategory::findOrFail($request->category_id);
        $radiologyCategory->update([
            'name' => $request->category_name,
        ]);
        // $radiologyCategory->save();

        return redirect()->back()->with('success', 'Radiology Category Successfully Updated.');
    }

    public function updateStatus(Request $request, $id)
    {
        $radiologyCategory = RadiologyCategory::findOrFail($id);
        // dd($request->is_active == null);
        $radiologyCategory->is_active = $request->is_active == null ? 'no' : 'yes';
        $radiologyCategory->save();
        return redirect()->back()->with('success', 'Radiology Category Status Updated');
    }
    public function delete(Request $request, $id)
    {
        // dd(Auth::user());
        if (Auth::user()->role == '1') {
            $radiologyCategory = RadiologyCategory::findOrFail($id);
            $radiologyCategory->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Radiology Category Successfully Deleted');
    }

    // radiology units
    public function radiologyUnitIndex()
    {
        $radiologyUnits = RadiologyUnit::all();

        return view("admin.setup.radiology_unit", compact("radiologyUnits"));
    }

    public function storeUnit(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255',
        ]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        RadiologyUnit::create([
            "name"        => $request->unit_name,
            'hospital_id' => $user->hospital_id,
        ]);

        return redirect()->back()->with('success', 'Radiology Unit Successfully Added.');
    }
    public function updateUnit(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|string|max:255',
            'unit_id'   => 'required|exists:radiology_unit,id',
        ]);

        $radiologyUnit = RadiologyUnit::findOrFail($request->unit_id);
        $radiologyUnit->update([
            'name' => $request->unit_name,
        ]);
        // $radiologyUnit->save();

        return redirect()->back()->with('success', 'Radiology Unit Successfully Updated.');
    }

    public function deleteUnit(Request $request, $id)
    {
        // dd(Auth::user());
        if (Auth::user()->role == '1') {
            $radiologyUnit = RadiologyUnit::findOrFail($id);
            $radiologyUnit->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Radiology Unit Successfully Deleted');
    }

    // radiology parameter
    public function radiologyParameterIndex()
    {
        $radiologyParameters = RadiologyParameter::all();
        $units               = [];

        foreach ($radiologyParameters as $value) {
            $units[$value->id] = RadiologyUnit::find($value->unit);
        }
        $unitData = RadiologyUnit::all();
        return view("admin.setup.radiology_parameter", compact("radiologyParameters", "units", 'unitData'));
    }

    public function storeParameter(Request $request)
    {
        $request->validate([
            'parameter_name' => 'required|string|max:255',
            'ref_range_from' => 'required|string|max:255',
            'ref_range_to'   => 'required|string|max:255',
            'unit'           => 'required|string|max:255',
            'gender'         => 'required|string|max:255',
            'description'    => 'required|string',
        ]);
        // dd($request->all());
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        $radiology_referance_range = $request->ref_range_from . " - " . $request->ref_range_to;
        // dd($radiology_referance_range);
        RadiologyParameter::create([
            "parameter_name"  => $request->parameter_name,
            'range_from'      => $request->ref_range_from,
            'range_to'        => $request->ref_range_to,
            'unit'            => $request->unit,
            'gender'          => $request->gender,
            'description'     => $request->description,
            'reference_range' => $radiology_referance_range,
        ]);

        return redirect()->back()->with('success', 'Radiology Parameter Successfully Added.');
    }

    public function updateParameter(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'parameter_id'   => 'required|exists:radiology_parameter,id',
            'parameter_name' => 'required|string|max:255',
            'ref_range_from' => 'required|string|max:255',
            'ref_range_to'   => 'required|string|max:255',
            'unit'           => 'required|string|max:255',
            'gender'         => 'required|string|max:255',
            'description'    => 'required|string',
        ]);
        $radiologyParameter        = RadiologyParameter::findOrFail($request->parameter_id);
        $radiology_referance_range = $request->ref_range_from . " - " . $request->ref_range_to;
        $radiologyParameter->update([
            "parameter_name"  => $request->parameter_name,
            'range_from'      => $request->ref_range_from,
            'range_to'        => $request->ref_range_to,
            'unit'            => $request->unit,
            'gender'          => $request->gender,
            'description'     => $request->description,
            'reference_range' => $radiology_referance_range,
        ]);
        // $radiologyUnit->save();

        return redirect()->back()->with('success', 'Radiology Unit Successfully Updated.');
    }

    public function deleteParameter(Request $request, $id)
    {
        if (Auth::user()->role == '1') {
            $radiologyParameter = RadiologyParameter::findOrFail($id);
            $radiologyParameter->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Radiology Parameter Successfully Deleted');
    }
}