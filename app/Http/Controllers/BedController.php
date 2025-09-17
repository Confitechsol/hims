<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bed;


class BedController extends Controller
{
   public function index(){
    $beds = Bed::with(['bedGroup', 'bedType'])->get();
    // return $beds;
       return view('admin.bed.index',compact('beds'));
   }
}
