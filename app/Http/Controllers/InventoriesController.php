<?php
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemIssue;
use App\Models\ItemStock;
use App\Models\ItemStockBatches;
use App\Models\ItemStore;
use App\Models\ItemSupplier;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventoriesController extends Controller
{
    public function index()
    {
        $categories = ItemCategory::all();
        $suppliers  = ItemSupplier::all();
        $stores     = ItemStore::all();
        $stocks     = ItemStock::with(['itemCategory', 'item', 'supplier', 'store'])->latest()->get();
        return view('admin.inventory.inventory_details', compact('categories', 'suppliers', 'stores', 'stocks'));
    }
    public function getItems($categoryId)
    {
        $items = Item::where('item_category_id', $categoryId)->get(['id', 'name']);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'item_category' => 'required',
            'item'          => 'required',
            'supplier'      => 'required',
            'quantity'      => 'required|numeric',
            'date'          => 'required|date',
        ]);

        // Identify selected category’s item_head
        $itemCategory = ItemCategory::find($request->item_category);
        $itemHead     = $itemCategory ? $itemCategory->item_head : null;

        // Create main stock record
        $data = [
            'hospital_id'      => auth()->user()->hospital_id,
            'item_category_id' => $request->item_category,
            'item_id'          => $request->item,
            'supplier_id'      => $request->supplier,
            'store_id'         => $request->store,
            'quantity'         => $request->quantity,
            'date'             => $request->date,
            'description'      => $request->message,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = file_get_contents($request->file('attachment')->getRealPath());
        }

        $stock = ItemStock::create($data);

        // Handle CAPITAL EQUIPMENT
        if ($request->filled('capital_batches')) {
            foreach ($request->capital_batches as $batch) {
                if (empty(array_filter($batch))) {
                    continue;
                }

                $stock->batches()->create([
                    'batch_no'            => $batch['batch_no'] ?? null,
                    'serial_no'           => $batch['serial_no'] ?? null,
                    'purchase_price'      => $batch['purchase_price'] ?? null,
                    'salvage_value'       => $batch['salvage_value'] ?? null,
                    'useful_life'         => $batch['useful_life'] ?? null,
                    'annual_depreciation' => $batch['annual_depreciation'] ?? null,
                ]);
            }
        }

        // Handle CONSUMABLES
        if ($request->filled('consumable_batches')) {
            foreach ($request->consumable_batches as $batch) {
                if (empty(array_filter($batch))) {
                    continue;
                }

                $stock->batches()->create([
                    'batch_no'       => $batch['batch_no'] ?? null,
                    'serial_no'      => $batch['serial_no'] ?? null,
                    'purchase_price' => $batch['purchase_price'] ?? null,
                    'expiry_date'    => $batch['expiry_date'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Stock saved successfully!');
    }
    public function edit($id)
    {

        $stock = ItemStock::with(['itemCategory', 'item', 'supplier', 'store', 'batches'])->find($id);

        if (! $stock) {
            return response()->json(['error' => 'Stock not found'], 404);
        }

        $categories = ItemCategory::select('id', 'item_category')->get();
        $items      = Item::select('id', 'name')->get();
        $suppliers  = ItemSupplier::select('id', 'item_supplier')->get();
        $stores     = ItemStore::select('id', 'item_store')->get();

        return response()->json([
            'stock'      => $stock,
            'categories' => $categories,
            'items'      => $items,
            'suppliers'  => $suppliers,
            'stores'     => $stores,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            //dd($request->all());
            DB::beginTransaction();

            $stock = ItemStock::findOrFail($id);

            // ✅ Update ItemStock fields
            $stock->update([
                'item_category_id' => $request->item_category,
                'item_id'          => $request->item,
                'supplier_id'      => $request->supplier,
                'store_id'         => $request->store,
                'quantity'         => $request->quantity,
                'date'             => $request->date,
                'description'      => $request->message,
            ]);

            // ✅ Delete all previous batch entries
            ItemStockBatches::where('item_stock_id', $stock->id)->delete();

            // ✅ Recreate all batches (Capital or Consumable based on inputs)
            if ($request->has('capital_batches')) {
                foreach ($request->capital_batches as $batch) {
                    if (! empty($batch['batch_no'])) {
                        ItemStockBatches::create([
                            'item_stock_id'       => $request->stock_id,
                            'batch_no'            => $batch['batch_no'],
                            'serial_no'           => $batch['serial_no'] ?? null,
                            'salvage_value'       => $batch['salvage_value'] ?? null,
                            'useful_life'         => $batch['useful_life'] ?? null,
                            'annual_depreciation' => $batch['annual_depreciation'] ?? null,
                            'expiry_date'         => null,
                        ]);
                    }
                }
            }

            if ($request->has('consumable_batches')) {
                foreach ($request->consumable_batches as $batch) {
                    if (! empty($batch['batch_no'])) {
                        ItemStockBatches::create([
                            'item_stock_id'       => $request->stock_id,
                            'batch_no'            => $batch['batch_no'],
                            'serial_no'           => $batch['serial_no'] ?? null,
                            'salvage_value'       => null,
                            'useful_life'         => null,
                            'annual_depreciation' => null,
                            'expiry_date'         => $batch['expiry_date'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            // return response()->json(['success' => 'Item Stock updated successfully.']);
            return redirect()->back()->with('success', 'Stock saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->with('error', 'something went wrong!');
            return response()->json(['error' => 'Failed to update item stock: ' . $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $itemStock = ItemStock::findOrFail($id);

            // 1️⃣ Delete all related batches
            ItemStockBatches::where('item_stock_id', $itemStock->id)->delete();

            // 2️⃣ Delete the main stock record
            $itemStock->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Item stock and related batches deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting item stock: ' . $e->getMessage());
        }
    }

    public function items()
    {
        $items      = Item::with('category')->get();
        $categories = ItemCategory::select('id', 'item_category')->get();
        return view('admin.inventory.item_details', compact('items', 'categories'));
    }

    public function storeItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_category_id' => 'required|exists:item_category,id',
            'name'             => 'required|string|max:255',
            'unit'             => 'required|string|max:100',
            'quantity'         => 'required|numeric|min:1',
            'date'             => 'required|date',
            'description'      => 'nullable|string',
            'item_photo'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            dd($validator->errors()->all()); // Dumps all error messages
        }

        $validatedData = $validator->validated();
        //dd($validatedData);
        // ✅ Handle image upload if provided
        if ($request->hasFile('item_photo')) {
            $validatedData['item_photo'] = $request->file('item_photo')->store('items', 'public');
        }

        // ✅ Create the new item
        Item::create($validatedData);

        // ✅ Redirect back with success message
        return redirect()->back()->with('success', 'Item added successfully.');
    }
    public function editItem($id)
    {

        try {
            // Fetch item details
            $item = Item::with('category')->findOrFail($id);

            // Return JSON response for AJAX
            return response()->json([
                'status' => 'success',
                'item'   => $item,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unable to fetch item details',
                'error'   => $e->getMessage(),
            ]);
        }
    }
    public function updateItem(Request $request, $id)
    {
        try {
            // ✅ 1. Validate inputs
            $validated = $request->validate([
                'item_category_id' => 'required|exists:item_category,id',
                'name'             => 'required|string|max:255',
                'unit'             => 'required|string|max:100',
                'quantity'         => 'required|integer|min:1',
                'date'             => 'required|date',
                'description'      => 'nullable|string',
                'item_photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // ✅ 2. Find the item
            $item = Item::findOrFail($id);

            // ✅ 3. Handle photo upload (if a new one is uploaded)
            if ($request->hasFile('item_photo')) {
                // Delete old image if exists
                if ($item->item_photo && file_exists(public_path('uploads/items/' . $item->item_photo))) {
                    unlink(public_path('uploads/items/' . $item->item_photo));
                }

                $file     = $request->file('item_photo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/items'), $fileName);

                $validated['item_photo'] = $fileName;
            }

            // ✅ 4. Update item record
            $item->update($validated);

            // ✅ 5. Return success response
            // return response()->json([
            //     'status'  => 'success',
            //     'message' => 'Item updated successfully!',
            //     'item'    => $item
            // ]);
            return redirect()->back()->with('success', 'Item added successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors
            return response()->json([
                'status' => 'validation_error',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            // Return general error
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update item',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function destroyItem($id)
    {
        try {
            // ✅ 1. Find the item
            $item = Item::findOrFail($id);

            // ✅ 2. Delete uploaded photo (if exists)
            if ($item->item_photo && file_exists(public_path('uploads/items/' . $item->item_photo))) {
                unlink(public_path('uploads/items/' . $item->item_photo));
            }

            // ✅ 3. Delete the record
            $item->delete();

            // ✅ 4. Redirect back with success message
            return redirect()->back()->with('success', 'Item deleted successfully.');

        } catch (\Exception $e) {
            // Handle exceptions gracefully
            return redirect()->back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }

    public function issueItems()
    {
        $categories = ItemCategory::all();
        $itemIssues = ItemIssue::with(['item', 'category', 'issuedTo'])->get();
        // $suppliers = ItemSupplier::all();
        $staffs      = Staff::with('department')->get();
        $stores      = ItemStore::all();
        $departments = Department::all();
        $stocks      = ItemStock::with(['itemCategory', 'item', 'supplier', 'store'])->latest()->get();
        return view('admin.inventory.issue_item', compact('categories', 'itemIssues', 'departments', 'stores', 'stocks', 'staffs'));
    }

    public function getStaffByDepartment(Request $request)
    {
        $staff = Staff::where('department_id', $request->department_id)
            ->select('id', 'name', 'surname')
            ->get();
        return response()->json($staff);
    }

    public function storeIssuedItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_id'    => 'required',
            'item_category_id' => 'required|integer|exists:item_category,id',
            'item_id'          => 'required|integer|exists:item,id',
            'quantity'         => 'required|numeric|min:1',
            'issue_to'         => 'required|integer|exists:staff,id',
            'issue_date'       => 'required|date',
            'return_date'      => 'nullable|date|after_or_equal:issue_date',
            'note'             => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            dd($validator->errors()->all()); // Dumps all error messages
        }

        $validatedData = $validator->validated();
        // dd( $validatedData);

        try {
            $issue                   = new ItemIssue();
            $issue->hospital_id      = Auth::user()->hospital_id ?? null;
            $issue->branch_id        = Auth::user()->branch_id ?? null;
            $issue->department_id    = $validatedData['department_id'];
            $issue->item_category_id = $validatedData['item_category_id'];
            $issue->item_id          = $validatedData['item_id'];
            $issue->quantity         = $validatedData['quantity'];
            $issue->issue_to         = $validatedData['issue_to'];
            $issue->issue_by         = Auth::id();
            $issue->issue_date       = $validatedData['issue_date'];
            $issue->return_date      = $validatedData['return_date'] ?? null;
            $issue->note             = $validatedData['note'] ?? null;
            $issue->is_returned      = 0;
            $issue->is_active        = 1;

            $issue->save();

            return redirect()->back()->with('success', 'Item issued successfully.');

        } catch (\Exception $e) {
            //dd( $e);
            return redirect()->back()->with('error', 'Error issuing item: ' . $e->getMessage());
        }
    }
    public function editIssuedItem($id)
    {

        $issue = ItemIssue::with(['category', 'item', 'issuedTo', 'department'])
            ->findOrFail($id);
        $categories  = ItemCategory::select('id', 'item_category')->get();
        $departments = Department::select('id', 'department_name')->get();
        return response()->json(['issue' => $issue, 'categories' => $categories,
            'departments'                    => $departments]);
    }
    public function getItemsByCategory(Request $request)
    {
        $items = Item::where('item_category_id', $request->category_id)->select('id', 'name')->get();
        return response()->json($items);
    }

    public function updateIssuedItem(Request $request, $id)
    {

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'item_category_id' => 'required',
            'item_id'          => 'required',
            'department_id'    => 'required',
            'issued_to'        => 'nullable',
            'issued_date'      => 'required|date',
            'quantity'         => 'required|numeric|min:1',
            'remarks'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            dd($validator->errors()->all()); // Dumps all error messages
        }

        $validatedData = $validator->validated();

        // Find the issue record
        $issue = ItemIssue::findOrFail($id);

        // Update the issue record
        $issue->update([
            'item_category_id' => $request->item_category_id,
            'item_id'          => $request->item_id,
            'department_id'    => $request->department_id,
            'issue_to'         => $request->issued_to,
            'issue_by'         => auth()->id(), // or get from form if editable
            'issue_date'       => $request->issued_date,
            'return_date'      => $request->return_date ?? null,
            'quantity'         => $request->quantity,
            'note'             => $request->remarks,
            'is_returned'      => $request->has('is_returned') ? 1 : 0,
            'is_active'        => $request->has('is_active') ? 1 : 0,
        ]);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Item issue updated successfully.',
        //     'issue' => $issue,
        // ]);
        return redirect()->back()->with('success', 'Item added successfully.');
    }
    public function destroyIssuedItem($id)
    {
        try {
            // ✅ 1. Find the item
            $item = ItemIssue::findOrFail($id);

            // ✅ 3. Delete the record
            $item->delete();

            // ✅ 4. Redirect back with success message
            return redirect()->back()->with('success', 'Issued Item deleted successfully.');

        } catch (\Exception $e) {
            // Handle exceptions gracefully
            return redirect()->back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }

}
