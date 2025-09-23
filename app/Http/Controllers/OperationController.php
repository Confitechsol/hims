<?php

namespace App\Http\Controllers;
use App\Models\Operation;
use App\Models\OperationCategory;

use Illuminate\Http\Request;

class OperationController extends Controller
{

    public function create()
    {
        $categories = OperationCategory::all(); // fetch categories from DB
        return view('operations.create', compact('categories'));
    }
    public function Operations()
    {
        $operations = Operation::with('category')->get();
        $categories = OperationCategory::all();
        return view('admin.setup.operation', compact('operations','categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'operation_name.*' => 'required|string|max:255',
            'category.*'       => 'required|exists:operation_category,id',
        ]);

        foreach ($request->operation_name as $index => $operation) {
            Operation::insert([
                'operation' => $operation,
                'category_id'    => $request->category[$index],
                'created_at'     => now(),
                
            ]);
        }

        return redirect()->back()->with('success', 'Operations saved successfully!');
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'operation_name' => 'required|string|max:255',
            'category_id' => 'required|string|max:500',
        ]);

        $purpose = Operation::findOrFail($id);
        $purpose->update([
            'operation' => $validated['operation_name'],
            'category_id' => $validated['category_id'],
        ]);

        return redirect()->back()->with('success', 'Purpose updated successfully!');
    }
    public function destroy($id)
    {
        $purpose = Operation::findOrFail($id);
        $purpose->delete();

        return redirect()->back()->with('success', 'Purpose deleted successfully.');
    }

    public function operationCategories()
{
    $categories = OperationCategory::all();
    return view('admin.setup.operation_category', compact('categories'));
}

public function storeCategory(Request $request)
{
    $request->validate([
        'operation_category.*' => 'required|string|max:255',
    ]);

    foreach ($request->operation_category as $categoryName) {
        OperationCategory::insert([
            'category'       => $categoryName,
            'created_at' => now(),
            'is_active' => 'yes',
        ]);
    }

    return redirect()->back()->with('success', 'Operation Categories saved successfully!');
}

public function updateCategory(Request $request, $id)
{
    //dd($request->all());
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $category = OperationCategory::findOrFail($id);
    $category->update([
        'category' => $validated['name'],
    ]);

    return redirect()->back()->with('success', 'Operation Category updated successfully!');
}

public function destroyCategory($id)
{
    $category = OperationCategory::findOrFail($id);
    $category->delete();

    return redirect()->back()->with('success', 'Operation Category deleted successfully.');
}

}
