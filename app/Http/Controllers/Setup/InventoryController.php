<?php
namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\ItemStore;
use App\Models\ItemSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $itemCategories = ItemCategory::all();
        return view("admin.setup.item_category", compact("itemCategories"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_head'       => 'required|array',
            'item_head.*'     => 'required|string|max:255',
            'item_category'   => 'required|array',
            'item_category.*' => 'required|string|max:255',
            'description'     => 'required|array',
            'description.*'   => 'required|string',
        ]);

        // dd($request->item_head[0]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }
        foreach ($request->item_category as $key => $categoryName) {
            if (! empty($categoryName)) {
                ItemCategory::create([
                    'hospital_id'   => $user->hospital_id,
                    'item_category' => $categoryName,
                    'item_head'     => $request->item_head[$key],
                    'description'   => $request->description[$key],
                    'is_active'     => 'yes',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Item Category successfully Added!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'item_category'    => 'required|string|max:255',
            'item_head'        => 'required|string|max:255',
            'description'      => 'required|string',
            'item_category_id' => 'required|exists:item_category,id',
        ]);

        $itemCategory = ItemCategory::findOrFail($request->item_category_id);
        $itemCategory->update([
            'item_category' => $request->item_category,
            'item_head'     => $request->item_head,
            'description'   => $request->description,
        ]);

        return redirect()->back()->with('success', 'Item Category Successfully Updated.');
    }

    public function updateStatus(Request $request, $id)
    {
        $itemCategory = ItemCategory::findOrFail($id);
        // dd($request->is_active == null);
        $itemCategory->is_active = $request->is_active == null ? 'no' : 'yes';
        $itemCategory->save();
        return redirect()->back()->with('success', 'Item Category Status Updated');
    }
    public function delete(Request $request, $id)
    {
        // dd(Auth::user());
        if (Auth::user()->role == '1') {
            $itemCategory = ItemCategory::findOrFail($id);
            $itemCategory->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Item Category Successfully Deleted');
    }

    // Item Store
    public function indexStore(Request $request)
    {
        $itemStores = ItemStore::all();
        return view("admin.setup.item_store", compact("itemStores"));
    }

    public function storeItemStore(Request $request)
    {
        $request->validate([
            'item_store_name' => 'required|string|max:255',
            'item_stock_code' => 'required|string|max:255',
            'description'     => 'required|string',
        ]);

        // dd($request->item_head[0]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }

        ItemStore::create([
            'hospital_id' => $user->hospital_id,
            'item_store'  => $request->item_store_name,
            'code'        => $request->item_stock_code,
            'description' => $request->description,
            'is_active'   => 'yes',
        ]);

        return redirect()->back()->with('success', 'Item Store successfully Added!');
    }

    public function updateStore(Request $request)
    {
        $request->validate([
            'item_store_name' => 'required|string|max:255',
            'item_stock_code' => 'required|string|max:255',
            'description'     => 'required|string',
            'item_store_id'   => 'required|exists:item_store,id',
        ]);

        $itemStore = ItemStore::findOrFail($request->item_store_id);
        $itemStore->update([
            'item_store'  => $request->item_store_name,
            'code'        => $request->item_stock_code,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Item Store Successfully Updated.');
    }

    public function updateStoreStatus(Request $request, $id)
    {
        $itemStore = ItemStore::findOrFail($id);
        // dd($request->is_active == null);
        $itemStore->is_active = $request->is_active == null ? 'no' : 'yes';
        $itemStore->save();
        return redirect()->back()->with('success', 'Item Store Status Updated');
    }
    public function deleteStore(Request $request, $id)
    {
        // dd(Auth::user());
        if (Auth::user()->role == '1') {
            $itemStore = ItemStore::findOrFail($id);
            $itemStore->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Item Store Successfully Deleted');
    }

    //item supplier
    public function indexSupplier(Request $request)
    {
        $itemSuppliers = ItemSupplier::all();
        return view("admin.setup.item_supplier", compact("itemSuppliers"));
    }

    public function storeItemSupplier(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'phone'                => 'required|string|max:255',
            'mail'                 => 'required|email|max:255',
            'contact_person_name'  => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:255',
            'contact_person_email' => 'required|email|max:255',
            'address'              => 'required|string|max:255',
            'description'          => 'required|string',
        ]);

        // dd($request->item_head[0]);
        $user = Auth::user();
        // dd($user);
        if (! $user || ! $user->hospital_id) {
            return redirect()->back()->with('error', 'User not authenticated or hospital ID missing.');
        }

        ItemSupplier::create([
            'hospital_id'          => $user->hospital_id,
            'item_supplier'        => $request->name,
            'phone'                => $request->phone,
            'email'                => $request->mail,
            'contact_person_name'  => $request->contact_person_name,
            'contact_person_phone' => $request->contact_person_phone,
            'contact_person_email' => $request->contact_person_email,
            'description'          => $request->description,
            'address'              => $request->address,
            'is_active'            => 'yes',
        ]);

        return redirect()->back()->with('success', 'Item Supplier successfully Added!');
    }

    public function updateSupplier(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'phone'                => 'required|string|max:255',
            'mail'                 => 'required|email|max:255',
            'contact_person_name'  => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:255',
            'contact_person_email' => 'required|email|max:255',
            'address'              => 'required|string|max:255',
            'description'          => 'required|string',
            'item_supplier_id'     => 'required|exists:item_supplier,id',
        ]);

        $itemSupplier = ItemSupplier::findOrFail($request->item_supplier_id);
        $itemSupplier->update([
            'item_supplier'        => $request->name,
            'phone'                => $request->phone,
            'email'                => $request->mail,
            'contact_person_name'  => $request->contact_person_name,
            'contact_person_phone' => $request->contact_person_phone,
            'contact_person_email' => $request->contact_person_email,
            'description'          => $request->description,
            'address'              => $request->address,
        ]);

        return redirect()->back()->with('success', 'Item Supplier Successfully Updated.');
    }

    public function updateSupplierStatus(Request $request, $id)
    {
        $itemSupplier = ItemSupplier::findOrFail($id);
        // dd($request->is_active == null);
        $itemSupplier->is_active = $request->is_active == null ? 'no' : 'yes';
        $itemSupplier->save();
        return redirect()->back()->with('success', 'Item Supplier Status Updated');
    }
    public function deleteSupplier(Request $request, $id)
    {
        // dd(Auth::user());
        if (Auth::user()->role == '1') {
            $itemSupplier = ItemSupplier::findOrFail($id);
            $itemSupplier->delete();
        } else {
            return redirect()->back()->with('error', 'Unauthorized!');
        }
        return redirect()->back()->with('success', 'Item Supplier Successfully Deleted');
    }
}