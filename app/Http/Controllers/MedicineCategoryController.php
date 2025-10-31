<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MedicineCategory;

class MedicineCategoryController extends Controller
{
    public function index(Request $request){
        $categories = MedicineCategory::query();
        $perPage = intval($request->input('perPage', 5));
        if ($perPage <= 0) {
           $perPage = 5;
        }
        if($request->has('search')){
            $search_term = $request->search;
            $categories->where(function ($query) use ($search_term) {
                $query->where('medicine_category', 'like', "%{$search_term}%");
            });
            $categories = $categories->paginate($perPage);
            return array("result"=>$categories);
        }
        $categories = $categories->paginate($perPage);
        return view('admin.setup.medicine_category',compact('categories'));
    }
    public function storeMultiple(Request $request)
{
    $request->validate([
        'category_name' => 'required|array|min:1',
        'category_name.*' => 'required|string|max:255',
    ],[
        'category_name.required' => 'Please add at least one medicine group.',
        'category_name.*.required' => 'Each Medicine Category field is required.',
        'category_name.*.string' => 'Each Medicine Category must be a valid string.',
        'category_name.*.max' => 'Each Medicine Category must not exceed 255 characters.',
    ]);

    foreach ($request->category_name as $category) {
        MedicineCategory::create([
            'medicine_category' => $category,
        ]);
    }

    return redirect()->back()->with('success', 'Medicine Categories added successfully.');
}
public function update(Request $request,$id)
{
    $request->validate([
        'medicine_category'=>'required|string|max:255'
    ]);

    $MedicineCategory = MedicineCategory::findOrFail($id);
    $MedicineCategory->medicine_category = $request->medicine_category;
    $MedicineCategory->save();
    return redirect()->back()->with('success', 'Medicine Category Updated successfully.');
}
public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:medicine_category,id',
        ]);
    
        $MedicineCategory = MedicineCategory::findOrFail($request->id);
        $MedicineCategory->delete();

        return redirect()->back()
                         ->with('success', 'Medicine category deleted successfully.');
    }
}
