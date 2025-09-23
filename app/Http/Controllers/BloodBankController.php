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
            'product_name' => 'required|string|max:255',
            'blood_group'  => 'required|string|max:10',
            'quantity'     => 'required|integer|min:1',
            'expiry_date'  => 'required|date',
        ]);

        BloodBankProduct::insert([
            'product_name' => $request->product_name,
            'blood_group'  => $request->blood_group,
            'quantity'     => $request->quantity,
            'expiry_date'  => $request->expiry_date,
            'created_at'   => now(),
        ]);

        return redirect()->back()->with('success', 'Blood Bank Product added successfully!');
    }

    // Update product
    public function updateProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'blood_group'  => 'required|string|max:10',
            'quantity'     => 'required|integer|min:1',
            'expiry_date'  => 'required|date',
        ]);

        $product = BloodBankProduct::findOrFail($id);
        $product->update([
            'product_name' => $validated['product_name'],
            'blood_group'  => $validated['blood_group'],
            'quantity'     => $validated['quantity'],
            'expiry_date'  => $validated['expiry_date'],
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
