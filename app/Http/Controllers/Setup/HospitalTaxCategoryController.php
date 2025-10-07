<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaxCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class HospitalTaxCategoryController extends Controller
{
    public function index(){
       $taxcatogery = TaxCategory::get();
       //  return $taxcatogery;
         return view('admin.setup.tax_category',compact('taxcatogery'));
    }

    public function store(Request $request)
{
    // Validate input
    $validator = $request->validate ( [
        'name' => 'required|string|max:255|unique:tax_category,name',
        'tax_percentage' => 'required|string'
        // Add other fields and their rules as needed
    ]);
    
    TaxCategory::create(['name'=>$request->name,'percentage'=>$request->tax_percentage,'hospital_id'=>Auth::user()->hospital_id]);

    return redirect()->back()->with("success","Tax Category Created Sucessfully!");   
}
public function update(Request $request){
    $validator = $request->validate ( [
        'id'=>'required|exists:tax_category,id',
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('tax_category', 'name')->ignore($request->id),
        ],
        'tax_percentage' => 'required|string'
    ]);
    $taxcatogery = TaxCategory::findOrFail($request->id);
    $taxcatogery->name = $request->name;
    $taxcatogery->percentage = $request->tax_percentage;
    $taxcatogery->save();
    return redirect()->back()->with("success","Tax Category Updated Sucessfully!");
}
public function destroy(Request $request){
    $validator = $request->validate ([
        'id'=>'required'
    ]);
    $taxcatogery = TaxCategory::findOrFail($request->id);
    $taxcatogery->delete();
    return redirect()->back()->with("success","Tax Category Deleted Sucessfully!");
}
}
