<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicineDosage;
use App\Models\Unit;
use App\Models\MedicineCategory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
class MedicineDosageController extends Controller
{
    public function index(request $request){
        $medicineDosage = MedicineDosage::with(['category','unit']);
        $units = Unit::get();
        $medicineCategories = MedicineCategory::get();
        $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
           $perPage = 5;
        }
        if($request->has('search')){
            $medicineCategories = MedicineCategory::query();
            $search_term = $request->search;
            $medicineCategories->where(function ($query) use ($search_term) {
                $query->where('medicine_category', 'like', "%{$search_term}%");
            });
            $medicineCategories = $medicineCategories->get();
            $data = collect();
            foreach($medicineCategories  as $medicineCategory){
                $dosages = MedicineDosage::with(['category','unit'])
                            ->where('medicine_category_id',$medicineCategory->id)
                            ->get();
                $data = $data->merge($dosages);
            }
            $page = LengthAwarePaginator::resolveCurrentPage();
            $paginatedData = new LengthAwarePaginator(
            $data->forPage($page, $perPage)->values(),
            $data->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
            );
            return response()->json([
                "result"=>[
                    'data' => $paginatedData->items(),
                    'total' => $paginatedData->total(),
                    'per_page' => $paginatedData->perPage(),
                    'current_page' => $paginatedData->currentPage(),
                    'last_page' => $paginatedData->lastPage(),
                ]
            ]);
        }
        $medicineDosage = $medicineDosage->paginate($perPage);
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
