<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BedGroup;
use Illuminate\Http\Request;
use App\Models\Floor;

class BedGroupController extends Controller
{
    public function index()
    {
        $bedGroups = BedGroup::all();
        $floors = Floor::all();
        return view('admin.bed-group.index', compact('bedGroups', 'floors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:bed_group,name',
            'floor' => 'required',
            'bed_cost' => 'required'
        ]);

        BedGroup::create([
            'name' => $request->name, 
            'floor' => $request->floor,
            'color' => $request->color,
            'bed_cost'=>$request->bed_cost,
            'description'=>$request->description ?? "",
            'is_active'=>0
        ]);

        return redirect()->back()->with('success', 'Bed group created successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bed_group,id',
            'name' => 'required|string|max:255|unique:bed_group,name,' . $request->id,
            'floor' => 'required'
        ]);

        $group = BedGroup::findOrFail($request->id);
        $group->name = $request->name;
        $group->save();

        return redirect()->back()->with('success', 'Bed group updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bed_group,id',
        ]);

        BedGroup::findOrFail($request->id)->delete();

        return redirect()->back()->with('success', 'Bed group deleted successfully.');
    }
}
