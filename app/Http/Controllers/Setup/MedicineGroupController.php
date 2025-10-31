<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\MedicineGroup;
use Illuminate\Http\Request;

class MedicineGroupController extends Controller
{
    public function index()
    {
        $groups = MedicineGroup::orderBy('id', 'desc')->get();
        return view('admin.setup.medicine_group', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255'
        ]);

        MedicineGroup::create([
            'hospital_id' => session('hospital_id', '1'),
            'branch_id' => session('branch_id', '1'),
            'group_name' => $request->group_name
        ]);

        return redirect()->back()->with('success', 'Medicine Group added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'group_name' => 'required|string|max:255'
        ]);

        $group = MedicineGroup::findOrFail($id);
        $group->update([
            'group_name' => $request->group_name
        ]);

        return redirect()->back()->with('success', 'Medicine Group updated successfully!');
    }

    public function destroy($id)
    {
        $group = MedicineGroup::findOrFail($id);
        $group->delete();

        return redirect()->back()->with('success', 'Medicine Group deleted successfully!');
    }
}
