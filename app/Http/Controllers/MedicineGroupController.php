<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicineGroup;

class MedicineGroupController extends Controller
{
    public function index(Request $request)
    {
        $categoriesQuery = MedicineGroup::query();
        $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
            $perPage = 5;
        }
        if ($request->has('search')) {
            $search_term = $request->search;
            $categoriesQuery->where(function ($query) use ($search_term) {
                $query->where('group_name', 'like', "%{$search_term}%");
            });
            $categories = $categoriesQuery->paginate($perPage);
            return response()->json(array("result"=>$categories));
        }
        $categories = $categoriesQuery->paginate($perPage);
        return view('admin.setup.medicine_group', compact('categories'));
    }
    public function storeMultiple(Request $request)
    {
        $request->validate([
            'medicine_group' => 'required|array|min:1',
            'medicine_group.*' => 'required|string|max:255',
        ], [
            'medicine_group.required' => 'Please add at least one medicine group.',
            'medicine_group.*.required' => 'Each Medicine Group field is required.',
            'medicine_group.*.string' => 'Each Medicine Group must be a valid string.',
            'medicine_group.*.max' => 'Each Medicine Group must not exceed 255 characters.',
        ]);

        foreach ($request->medicine_group as $group) {
            MedicineGroup::create([
                'group_name' => $group,
            ]);
        }

        return redirect()->back()->with('success', 'Medicine groups added successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'group_name' => 'required|string|max:255'
        ]);

        $MedicineGroup = MedicineGroup::findOrFail($id);
        $MedicineGroup->group_name = $request->group_name;
        $MedicineGroup->save();
        return redirect()->back()->with('success', 'Medicine Group Updated successfully.');
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:medicine_group,id',
        ]);

        $MedicineGroup = MedicineGroup::findOrFail($request->id);
        $MedicineGroup->delete();

        return redirect()->back()
            ->with('success', 'Medicine category deleted successfully.');
    }
}
