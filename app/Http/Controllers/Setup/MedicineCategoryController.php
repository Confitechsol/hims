<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;

class MedicineCategoryController extends Controller
{
    public function index()
    {
        $categories = MedicineCategory::orderBy('id', 'desc')->get();
        return view('admin.setup.medicine_category', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255'
        ]);

        MedicineCategory::create([
            'hospital_id' => session('hospital_id', '1'),
            'branch_id' => session('branch_id', '1'),
            'category_name' => $request->category_name
        ]);

        return redirect()->back()->with('success', 'Medicine Category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255'
        ]);

        $category = MedicineCategory::findOrFail($id);
        $category->update([
            'category_name' => $request->category_name
        ]);

        return redirect()->back()->with('success', 'Medicine Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = MedicineCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Medicine Category deleted successfully!');
    }
}
