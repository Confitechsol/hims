<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OperationController extends Controller
{
     public function create()
    {
        $categories = Category::all(); // fetch categories from DB
        return view('operations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'operation_name.*' => 'required|string|max:255',
            'category.*'       => 'required|exists:categories,id',
        ]);

        foreach ($request->operation_name as $index => $operation) {
            Operation::create([
                'operation_name' => $operation,
                'category_id'    => $request->category[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Operations saved successfully!');
    }
}
