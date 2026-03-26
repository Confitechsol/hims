<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\MedicineDosage;
use App\Models\MedicineCategory;
use App\Models\MedicineUnit;
use Illuminate\Http\Request;

class MedicineDosageController extends Controller
{
  public function index(Request $request)
{
    // Per page
    $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

    $search = $request->input('search');

    // Start query
    $query = MedicineDosage::with(['category', 'unit'])

->orWhereHas('category', function ($q2) use ($search) {
    $q2->where('medicine_category', 'like', "%{$search}%");
})

->orWhereHas('unit', function ($q3) use ($search) {
    $q3->where('unit_name', 'like', "%{$search}%");
});

 
   $dosages = $query->paginate($perPage);

    $categories = MedicineCategory::all();
    $units = MedicineUnit::all();
    //     return response()->json([
    //     "result" => $dosages
    // ]);
    return view('admin.setup.medicine_dosage', compact('dosages', 'categories', 'units', 'perPage', 'search'));
}
    public function store(Request $request)
    {
        $request->validate([
            'medicine_category_id' => 'required|exists:medicine_category,id',
            'dosage' => 'required|string|max:255',
            'units_id' => 'required|exists:unit,id'
        ]);

        MedicineDosage::create([
            'hospital_id' => session('hospital_id', '1'),
            'branch_id' => session('branch_id', '1'),
            'medicine_category_id' => $request->medicine_category_id,
            'dosage' => $request->dosage,
            'units_id' => $request->units_id
        ]);

        return redirect()->back()->with('success', 'Medicine Dosage added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'medicine_category_id' => 'required|exists:medicine_category,id',
            'dosage' => 'required|string|max:255',
            'units_id' => 'required|exists:unit,id'
        ]);

        $dosage = MedicineDosage::findOrFail($id);
        $dosage->update([
            'medicine_category_id' => $request->medicine_category_id,
            'dosage' => $request->dosage,
            'units_id' => $request->units_id
        ]);

        return redirect()->back()->with('success', 'Medicine Dosage updated successfully!');
    }

    public function destroy($id)
    {
        $dosage = MedicineDosage::findOrFail($id);
        $dosage->delete();

        return redirect()->back()->with('success', 'Medicine Dosage deleted successfully!');
    }
}
