<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pathology;
use App\Models\Unit;
use App\Models\PathologyCategory;
use App\Models\pathologyParameter;

class PathologyController extends Controller
{
    public function pathologyCategories()
    {
        $categories = PathologyCategory::all();
        return view('admin.setup.pathology_category', compact('categories'));
    }
    public function storeCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        PathologyCategory::insert([
            'category_name'       => $request->category_name,
            'created_at' => now(), // only if your table has created_at
        ]);

        return redirect()->back()->with('success', 'Pathology Category added successfully!');
    }
    public function updateCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = PathologyCategory::findOrFail($id);
        $category->update([
            'category_name' => $validated['category_name'],
        ]);

        return redirect()->back()->with('success', 'Pathology Category updated successfully!');
    }

    public function destroyCategory($id)
    {
        $category = PathologyCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Pathology Category deleted successfully.');
    }
    public function pathologyUnits()
    {
        $units = Unit::where('unit_type', 'Pathology')->get();
        return view('admin.setup.pathology_unit', compact('units'));
    }

    public function storeUnit(Request $request)
    {
       
        $request->validate([
            'unit_name' => 'required|string|max:255',
        ]);

        Unit::insert([
            'unit_name'  => $request->unit_name,
            'unit_type'  => 'Pathology',
            'created_at' => now(), // only if your table has created_at
        ]);

        return redirect()->back()->with('success', 'Pathology Unit added successfully!');
    }

    public function updateUnit(Request $request, $id)
    {
        $validated = $request->validate([
            'unit_name' => 'required|string|max:255',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update([
            'unit_name' => $validated['unit_name'],
        ]);

        return redirect()->back()->with('success', 'Pathology Unit updated successfully!');
    }

    public function destroyUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->back()->with('success', 'Pathology Unit deleted successfully.');
    }
    public function pathologyParameters()
    {
        // Fetch only pathology parameters
        $parameters = PathologyParameter::with('unitRelation')->get(); // or Unit::where('unit_type','PathologyParameter')->get();
        $units = Unit::where('unit_type', 'Pathology')->get();
        return view('admin.setup.pathology_parameter', compact('parameters','units'));
    }

    public function storeParameter(Request $request)
    {
            $request->validate([
            'parameter_name' => 'required|string|max:255', // parameter name
            'ref_range_from' => 'required|string|max:255',
            'ref_range_to'   => 'required|string|max:255',
            'unit_id'        => 'required|exists:unit,id',
            'description'    => 'nullable|string',
        ]);

        PathologyParameter::create([
            'parameter_name'   => $request->parameter_name,  // maps form field "unit_name" to parameter_name
            'range_from'       => $request->ref_range_from,
            'range_to'         => $request->ref_range_to,
            'reference_range'  => $request->ref_range_from . ' - ' . $request->ref_range_to,
            'unit_id'          => $request->unit_id,
            'description'      => $request->description,
        ]);

        return redirect()->back()->with('success', 'Pathology Parameter added successfully!');

      
    }

    public function updateParameter(Request $request, $id)
    {
        $request->validate([
            'parameter_name' => 'required|string|max:255',
        ]);

        $parameter = PathologyParameter::findOrFail($id);
        $parameter->update([
            'parameter_name' => $request->parameter_name,
            'range_from'       => $request->ref_range_from,
            'range_to'         => $request->ref_range_to,
            'reference_range'  => $request->ref_range_from . ' - ' . $request->ref_range_to,
            'unit_id'          => $request->unit_id,
            'description'      => $request->description,
        ]);

        return redirect()->back()->with('success', 'Pathology Parameter updated successfully!');
    }

    public function destroyParameter($id)
    {
        $parameter = PathologyParameter::findOrFail($id);
        $parameter->delete();

        return redirect()->back()->with('success', 'Pathology Parameter deleted successfully.');
    }



}
