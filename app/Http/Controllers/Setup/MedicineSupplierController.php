<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\MedicineSupplier;
use Illuminate\Http\Request;

class MedicineSupplierController extends Controller
{
   public function index(){
    $medicineSuppliers = MedicineSupplier::get();
    return view('admin.setup.supplier',compact('medicineSuppliers'));
   }
   public function store(Request $request){
    $request->validate([
        'supplier_name'=>'required',
        'supplier_contact'=>'numeric|digits:10',
        'contact_person_phone'=>'numeric|digits:10'
    ]);
    MedicineSupplier::create([
        "supplier"=>$request->supplier_name ?? '',
        "contact"=>$request->supplier_contact ?? '',
        "supplier_person"=>$request->contact_person_name ?? '',
        "supplier_person_contact"=>$request->contact_person_phone ?? '',
        "supplier_drug_licence"=>$request->licence ?? '',
        "address"=>$request->address ?? '',
    ]);
    return redirect()->back()->with('success','Medicine Supplier Added Successfully');
   }
   public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:medicine_supplier,id',
        ]);
    
        $MedicineSupplier = MedicineSupplier::findOrFail($request->id);
        $MedicineSupplier->delete();

        return redirect()->back()
                         ->with('success', 'Medicine Supplier deleted successfully.');
    }
    public function update(Request $request){
        $request->validate([
            'id' => 'required|exists:medicine_supplier,id',
            'supplier_name'=>'required',
            'supplier_contact'=>'numeric|digits:10',
            'contact_person_phone'=>'numeric|digits:10'
        ]);
        $MedicineSupplier = MedicineSupplier::findOrFail($request->id);
        $MedicineSupplier->supplier = $request->supplier_name ?? '';
        $MedicineSupplier->contact = $request->supplier_contact ?? '';
        $MedicineSupplier->supplier_person = $request->contact_person_name ?? '';
        $MedicineSupplier->supplier_person_contact = $request->contact_person_phone ?? '';
        $MedicineSupplier->supplier_drug_licence = $request->licence ?? '';
        $MedicineSupplier->address = $request->address ?? '';
        $MedicineSupplier->save();

        return redirect()->back()->with('success', 'Medicine Supplier Updated successfully.');
    }

}
