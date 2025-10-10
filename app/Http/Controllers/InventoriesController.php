<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemStock;
use App\Models\ItemStore;
use App\Models\ItemSupplier;

class InventoriesController extends Controller
{
     public function index()
    {
        $categories = ItemCategory::all();
        $suppliers = ItemSupplier::all();
        $stores = ItemStore::all();
        $stocks = ItemStock::with(['itemCategory', 'item', 'supplier', 'store'])->latest()->get();
        return view('admin.inventory.inventory_details', compact('categories','suppliers','stores','stocks'));
    }
    public function getItems($categoryId)
    {
        $items = Item::where('item_category_id', $categoryId)->get(['id', 'name']);
        return response()->json($items);
    }

   public function store(Request $request)
{
   
    // $request->validate([
    //     'item_category' => 'required',
    //     'item' => 'required',
    //     'supplier' => 'required',
    //     'quantity' => 'required|numeric',
    //     'purchase_price' => 'required|numeric',
    //     'attachment.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    // ]);
    //dd($request->all());

    $data = [
        'item_category_id' => $request->item_category,
        'item_id' => $request->item,
        'supplier_id' => $request->supplier,
        'store_id' => $request->store,
        'quantity' => $request->quantity,
        'purchase_price' => $request->purchase_price,
        'date' => $request->date,
        'expiry_date' => $request->expiry_date,
        'salvage_value' => $request->salvage_value,
        'useful_life' => $request->useful_life,
        'annual_depreciation' => $request->annual_depreciation,
        'description' => $request->message,
    ];

//     if ($request->hasFile('attachment')) {
//     $file = $request->file('attachment');
//     $filename = time() . '_' . $file->getClientOriginalName();
//     $path = $file->storeAs('purchases', $filename, 'public');
//     $data['attachment'] = $path; // store file path in DB
// }

    // ✅ Handle file upload
    if ($request->hasFile('attachment')) {
    $fileContent = file_get_contents($request->file('attachment')->getRealPath());
    $data['attachment'] = $fileContent; // store binary data
}

    ItemStock::create($data);

    return redirect()->back()->with('success', 'Purchase saved successfully!');
}


}
