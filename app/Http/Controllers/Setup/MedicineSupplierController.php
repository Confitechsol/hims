<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\MedicineSupplier;
use Illuminate\Http\Request;

class MedicineSupplierController extends Controller
{
    public function index()
    {
        $suppliers = MedicineSupplier::orderBy('id', 'desc')->get();
        return view('admin.setup.medicine_supplier', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'required|string|max:255'
        ]);

        MedicineSupplier::create([
            'hospital_id' => session('hospital_id', '1'),
            'branch_id' => session('branch_id', '1'),
            'supplier' => $request->supplier,
            'contact' => $request->contact,
            'supplier_person' => $request->supplier_person,
            'supplier_person_contact' => $request->supplier_person_contact,
            'supplier_drug_licence' => $request->supplier_drug_licence,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Supplier added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier' => 'required|string|max:255'
        ]);

        $supplier = MedicineSupplier::findOrFail($id);
        $supplier->update([
            'supplier' => $request->supplier,
            'contact' => $request->contact,
            'supplier_person' => $request->supplier_person,
            'supplier_person_contact' => $request->supplier_person_contact,
            'supplier_drug_licence' => $request->supplier_drug_licence,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Supplier updated successfully!');
    }

    public function destroy($id)
    {
        $supplier = MedicineSupplier::findOrFail($id);
        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier deleted successfully!');
    }
}
