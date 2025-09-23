<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicineDosageController extends Controller
{
    public function index(){
            $assocArr = [
            [
                "id"=>1,
                "medicine_category"=>"Antibiotic",
                "dosage"=>"1",
                "unit"=>"Tablet",
                "unit_id"=>1
            ],
            [
                "id"=>1,
                "medicine_category"=>"Test",
                "dosage"=>"1",
                "unit"=>"Tablet",
                "unit_id"=>1
            ]
        ];
        return view('admin.setup.medicine_dosage',compact('assocArr'));
    }
}
