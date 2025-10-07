<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicineDosage;
use App\Models\Unit;
use App\Models\MedicineCategory;
class MedicineDosageController extends Controller
{
    public function index(){
        $medicineDosage = MedicineDosage::with(['category','unit'])->get();
        $units = Unit::get();
        $medicineCategories = MedicineCategory::get();
        return view('admin.setup.medicine_dosage',compact('medicineDosage','units','medicineCategories'));
    }
    public function store(Request $request){
        $request->validate([
            "medicine_category"=>'required',
            "dosage"=>'required|array|min:1',
            "dosage.*"=>'required',
            "unit"=>'required|array|min:1',
            "unit.*"=>'required'
        ]);
        $dosage = $request->dosage;
        $units = $request->unit;
        if (count($dosage) !== count($units)) {
            return redirect()->back()->with('error','Dosage and units arrays must have the same number of elements.');
        }
        foreach ($dosage as $key => $dose) {
            // Make sure that the key exists in both arrays
            if (!isset($units[$key])) {
                return redirect()->back()->with('error', 'Missing unit for dosage at index ');
            }
            $medicineDosage = new MedicineDosage;
            $medicineDosage->medicine_category_id = $request->medicine_category;
            $medicineDosage->dosage = $dose;
            $medicineDosage->units_id = $units[$key];
            $medicineDosage->save();
        }
        return redirect()->back()->with('success', "Successfully Added Medicine Dosage");
    }
    public function update(Request $request){
        $request->validate([
            "medicine_category"=>'required',
            "dosage"=>'required',
            "unit"=>'required'
        ]);
        $medicineDosage = MedicineDosage::findOrFail($request->id);
        $medicineDosage->medicine_category_id = $request->medicine_category;
        $medicineDosage->dosage = $request->dosage;
        $medicineDosage->units_id = $request->unit;
        $medicineDosage->save();
        return redirect()->back()->with('success',"Successfully updated Medicine Dosage");
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:medicine_dosage,id',
        ]);
    
        $medicineDosage = MedicineDosage::findOrFail($request->id);
        $medicineDosage->delete();

        return redirect()->back()
                         ->with('success', 'Medicine Dosage deleted successfully.');
    }
}
