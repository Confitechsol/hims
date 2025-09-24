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
}
