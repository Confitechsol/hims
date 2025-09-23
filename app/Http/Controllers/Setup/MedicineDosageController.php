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
                "unit"=>"Table"
            ],
            [
                "id"=>1,
                "medicine_category"=>"Test",
                "dosage"=>"1",
                "unit"=>"Table"
            ]
        ];
        return view('admin.setup.medicine_dosage',compact('assocArr'));
    }
}
