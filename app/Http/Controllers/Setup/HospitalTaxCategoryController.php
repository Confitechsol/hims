<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaxCategory;



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
        'name' => 'required|string|max:255',
        'tax_percentage' => 'required|string'
        // Add other fields and their rules as needed
    ]);

    TaxCategory::create(['name'=>$request->name,'percentage'=>$request->tax_percentage,'hospital_id'=>"HS001"]);

    return redirect()->back()->with("success","Tax Category Created Sucessfully!");

   



    
}}
