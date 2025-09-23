<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodBankProduct;

class BloodBankController extends Controller
{
    public function products()
    {
        $products = BloodBankProduct::all();
        return view('admin.setup.product', compact('products'));
    }

    // Store product
    public function storeProduct(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'type'  => 'required',
           
        ]);

        BloodBankProduct::insert([
            'name' => $request->name,
            'is_blood_group'  => $request->type,
            'created_at'   => now(),
        ]);

        return redirect()->back()->with('success', 'Blood Bank Product added successfully!');
    }

    // Update product
    public function updateProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type'  => 'required',

        ]);

        $product = BloodBankProduct::findOrFail($id);
        $product->update([
            'name' => $validated['name'],
            'is_blood_group'  => $validated['type'],

        ]);

        return redirect()->back()->with('success', 'Blood Bank Product updated successfully!');
    }

    // Delete product
    public function destroyProduct($id)
    {
        $product = BloodBankProduct::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Blood Bank Product deleted successfully.');
    }
}
