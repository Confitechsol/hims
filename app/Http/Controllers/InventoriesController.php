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
use Carbon\Carbon;
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
            'branch_id'         => auth()->user()->branch_id ?? null,
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
            $item_stock_id = $stock->id;
            // ✅ Recreate all batches (Capital or Consumable based on inputs)
            if ($request->has('capital_batches')) {
                foreach ($request->capital_batches as $batch) {
                    if (! empty($batch['batch_no'])) {
                        ItemStockBatches::create([
                            'item_stock_id'       => $item_stock_id,
                            'batch_no'            => $batch['batch_no'],
                            'serial_no'           => $batch['serial_no'] ?? null,
                            'purchase_price'      => $batch['purchase_price'] ?? null,
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
                            'item_stock_id'       => $item_stock_id,
                            'batch_no'            => $batch['batch_no'],
                            'serial_no'           => $batch['serial_no'] ?? null,
                            'purchase_price'      => $batch['purchase_price'] ?? null,
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
    
    public function reports()
    {
         return view('admin.reports.inventory.index');
    }
    // public function stockReports(Request $request)
    // {
    //     $dateFrom = $request->date_from;
    //     $dateTo   = $request->date_to;
    //     $search   = $request->search;

    //     $stockReport = ItemStock::with([
    //             'item',
    //             'itemCategory',
    //             'supplier',
    //             'store'
    //         ])
    //         ->withSum(['issues as total_issued' => function ($q) use ($dateFrom, $dateTo) {
    //             if ($dateFrom && $dateTo) {
    //                 $q->whereBetween('date', [$dateFrom, $dateTo]);
    //             }
    //         }], 'quantity')

    //         ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
    //             $query->whereBetween('date', [$dateFrom, $dateTo]);
    //         })

    //         ->when($search, function ($query) use ($search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->whereHas('item', function ($q1) use ($search) {
    //                     $q1->where('name', 'like', "%{$search}%");
    //                 })
    //                 ->WhereHas('itemCategory', function ($q2) use ($search) {
    //                     $q2->where('item_category', 'like', "%{$search}%");
    //                 })
    //                 ->orWhereHas('supplier', function ($q3) use ($search) {
    //                     $q3->where('item_supplier', 'like', "%{$search}%");
    //                 })
    //                 ->orWhereHas('store', function ($q4) use ($search) {
    //                     $q4->where('item_store', 'like', "%{$search}%");
    //                 });
    //             });
    //         })

    //         ->where('is_active', 'yes')
    //         ->get()
    //         ->map(function ($stock) {
    //             $stock->total_quantity = $stock->quantity;
    //             $stock->total_issued   = $stock->total_issued ?? 0;
    //             $stock->available_quantity =
    //                 $stock->total_quantity - $stock->total_issued;

    //             return $stock;
    //         });

    //     return view(
    //         'admin.reports.inventory.inventory-stock-report',
    //         compact('stockReport', 'dateFrom', 'dateTo', 'search')
    //     );
    // }
    public function stockReports(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;
        $search   = $request->search;

          $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

        $stockReport = ItemStock::with([
                'item',
                'itemCategory',
                'supplier',
                'store'
            ])
            ->withSum([
                'issues as total_issued' => function ($q) use ($dateFrom, $dateTo) {
                    if ($dateFrom && $dateTo) {
                        $q->whereBetween('date', [$dateFrom, $dateTo]);
                    }
                }
            ], 'quantity')

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->whereHas('item', function ($q1) use ($search) {
                            $q1->where('name', 'like', "%{$search}%");
                        })

                    ->orWhereHas('itemCategory', function ($q2) use ($search) {
                            $q2->where('item_category', 'like', "%{$search}%");
                        })

                    ->orWhereHas('supplier', function ($q3) use ($search) {
                            $q3->where('item_supplier', 'like', "%{$search}%");
                        })

                    ->orWhereHas('store', function ($q4) use ($search) {
                            $q4->where('item_store', 'like', "%{$search}%");
                        });

                });
            })

            ->where('is_active', 'yes')
             ->paginate($perPage)
        ->withQueryString();

    // ✅ Replace map() with transform()
    $stockReport->getCollection()->transform(function ($stock) {
        $stock->total_quantity     = $stock->quantity;
        $stock->total_issued       = $stock->total_issued ?? 0;
        $stock->available_quantity =
            $stock->total_quantity - $stock->total_issued;

        return $stock;
    });
            // // ->get()
            
            // ->map(function ($stock) {
            //     $stock->total_quantity     = $stock->quantity;
            //     $stock->total_issued       = $stock->total_issued ?? 0;
            //     $stock->available_quantity =
            //         $stock->total_quantity - $stock->total_issued;

            //     return $stock;
            // });

        return view(
            'admin.reports.inventory.inventory-stock-report',
            compact('stockReport', 'dateFrom', 'dateTo', 'search')
        );
    }

    public function itemReports(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;
        $search   = $request->search;
       
            $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

        $items = Item::with('category')

            ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('date', [$dateFrom, $dateTo]);
            })

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")

                    ->orWhereHas('category', function ($qc) use ($search) {
                        $qc->where('item_category', 'like', "%{$search}%");
                    });

                });
            })

            ->orderBy('name')
            //->get();
            ->paginate($perPage)
    ->withQueryString();
        return view(
            'admin.reports.inventory.inventory-item-report',
            compact('items', 'dateFrom', 'dateTo', 'search')
        );
    }
    public function assetReport(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;
        $search   = $request->search;

         $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

        $assets = ItemStock::with([
                'item',
                'itemCategory',
                'batches',
                'supplier',
                'store'
            ])
            ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('date', [$dateFrom, $dateTo]);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('item', function ($qi) use ($search) {
                            $qi->where('name', 'like', "%{$search}%");
                        })
                    ->orWhereHas('itemCategory', function ($qc) use ($search) {
                            $qc->where('item_category', 'like', "%{$search}%");
                        })
                    ->orWhereHas('supplier', function ($qs) use ($search) {
                            $qs->where('item_supplier', 'like', "%{$search}%");
                        })
                    ->orWhereHas('store', function ($qt) use ($search) {
                            $qt->where('item_store', 'like', "%{$search}%");
                        });
                });
            })
            ->where('is_active', 'yes')
            ->orderBy('date', 'desc')
             ->paginate($perPage)
        ->withQueryString();

    // ✅ Replace map() with transform()
    $assets->getCollection()->transform(function ($asset) {

        $asset->salvage_value = $asset->batches->sum('salvage_value');
        $asset->annual_depreciation = $asset->batches->sum('annual_depreciation');
        $asset->useful_life = $asset->batches->avg('useful_life');
        $asset->expiry_date = $asset->batches->min('expiry_date');

        $asset->total_cost = $asset->batches
            ->whereNotNull('purchase_price')
            ->sum('purchase_price');

        $asset->net_book_value =
            $asset->total_cost - $asset->salvage_value;

        $asset->asset_age =
            $asset->date
                ? \Carbon\Carbon::parse($asset->date)->diffInYears(now())
                : null;

        return $asset;
    });
            // ->get()
            // ->map(function ($asset) {

            //     // 🔹 Batch aggregations
            //     $asset->salvage_value = $asset->batches->sum('salvage_value');
            //     $asset->annual_depreciation = $asset->batches->sum('annual_depreciation');
            //     $asset->useful_life = $asset->batches->avg('useful_life');
            //     $asset->expiry_date = $asset->batches->min('expiry_date');

            //     // 🔹 Cost calculations
            //     $asset->total_cost = $asset->batches->whereNotNull('purchase_price')->sum('purchase_price');

            //     // 🔹 Net Book Value
            //     $asset->net_book_value =
            //         $asset->total_cost - $asset->salvage_value;

            //     // 🔹 Asset age
            //     $asset->asset_age =
            //         $asset->date
            //             ? Carbon::parse($asset->date)->diffInYears(now())
            //             : null;

            //     return $asset;
            // });

        return view(
            'admin.reports.inventory.inventory-asset-report',
            compact('assets', 'dateFrom', 'dateTo', 'search')
        );
    }
    public function issueReport(Request $request)
{
    $dateFrom = $request->date_from;
    $dateTo   = $request->date_to;
    $search   = $request->search;
    $returned = $request->is_returned; // yes / no / null

    $issues = ItemIssue::with([
            'item',
            'category',
            'department',
            'issuedTo'
        ])

        // 🔹 Date filter (issue date)
        ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('issue_date', [$dateFrom, $dateTo]);
        })

        // 🔹 Returned filter
        ->when($returned !== null && $returned !== '', function ($query) use ($returned) {
            $query->where('is_returned', $returned);
        })

        // 🔹 Search filter
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {

                $q->whereHas('item', function ($qi) use ($search) {
                        $qi->where('name', 'like', "%{$search}%");
                    })
                  ->orWhereHas('category', function ($qc) use ($search) {
                        $qc->where('item_category', 'like', "%{$search}%");
                    })
                  ->orWhereHas('department', function ($qd) use ($search) {
                        $qd->where('name', 'like', "%{$search}%");
                    })
                  ->orWhereHas('issuedTo', function ($qs) use ($search) {
                        $qs->where('name', 'like', "%{$search}%");
                    });

            });
        })

        ->where('is_active', 'yes')
        ->orderBy('issue_date', 'desc')
        ->get()

        // 🔹 Computed fields
        ->map(function ($issue) {

            $issue->issue_status = $issue->is_returned === 'yes'
                ? 'Returned'
                : 'Issued';

            $issue->issue_age = $issue->issue_date
                ? Carbon::parse($issue->issue_date)->diffInDays(now())
                : null;

            return $issue;
        });

    return view(
        'admin.reports.inventory.inventory-issue-report',
        compact('issues', 'dateFrom', 'dateTo', 'search', 'returned')
    );
}
    public function returnIssuedItem(Request $request, $id)
    {

        $issue = ItemIssue::findOrFail($id);
        //dd($issue);

    // Update return status
    $issue->update([
        'is_returned' => 1,
        'active' => '0',
        'return_date' => now(),
    ]);

    // Optional: Increase stock back
    // $item = $issue->item;
    // if ($item) {
    //     $item->increment('quantity', $issue->quantity);
    // }

    return redirect()->back()->with('success', 'Item returned successfully.');
    }






}
