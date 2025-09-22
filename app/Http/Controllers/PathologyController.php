<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pathology;
use App\Models\PathologyCategory;

class PathologyController extends Controller
{
    public function pathologyCategories()
    {
        $categories = PathologyCategory::all();
        return view('admin.setup.pathology_category', compact('categories'));
    }
    public function store(Request $request)
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

}
