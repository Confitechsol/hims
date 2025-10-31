<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bed;
use App\Models\BedGroup;
use App\Models\BedType;


class BedController extends Controller
{
   public function index(Request $request)
   {
       $bedsQuery = Bed::with(['bedGroup', 'bedType']);
       $bedGroups = BedGroup::all();
       $bedTypes = BedType::all();
       $perPage = intval($request->input('perPage', 5));
       if ($perPage <= 0) {
           $perPage = 5;
       }
       if ($request->has('search')) {
           $search = $request->input('search');
           $bedsQuery->where(function ($query) use ($search) {
               $query->where('name', 'like', "%{$search}%")
                   ->orWhereHas('bedGroup', function ($q) use ($search) {
                       $q->where('name', 'like', "%{$search}%");
                   })
                   ->orWhereHas('bedType', function ($q) use ($search) {
                       $q->where('name', 'like', "%{$search}%");
                   });
           });
           $beds = $bedsQuery->paginate($perPage);
           return response()->json([
               'result' => $beds
           ]);
       }
       $beds = $bedsQuery->paginate($perPage);
       return view('admin.bed.index', compact('beds', 'bedGroups', 'bedTypes'));
   }   
   public function status(Request $request)
   {
   $perPage = intval($request->input('perPage', 5));
   if ($perPage <= 0) {
          $perPage = 5;
   }
     $bedsQuery = Bed::with(['bedGroup', 'bedType']);
     if ($request->has('search')) {
      $search = $request->input('search');
      $bedsQuery->where(function ($query) use ($search) {
          $query->where('name', 'like', "%{$search}%")
              ->orWhereHas('bedGroup', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('bedType', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
      });
      $beds = $bedsQuery->paginate($perPage);
      return response()->json([
          'result' => $beds
      ]);
  }
     $beds = $bedsQuery->paginate($perPage);
      return view('admin.bed.status', compact('beds'));
   }
   public function store(Request $request)
   {
      $request->validate([
         'name' => 'required|string|max:255',
         'bed_type_id' => 'required|exists:bed_type,id',
         'bed_group_id' => 'required|exists:bed_group,id',
      ]);

      $is_active = "yes";
     if($request->has('is_active')){
      $is_active = "noused";
     }
     Bed::create([
      "name" => $request->name,
      "bed_type_id" => $request->bed_type_id,
      "bed_group_id" => $request->bed_group_id,
      "is_active"=>$is_active
     ]);
      return redirect()->back()->with('success', 'Bed Created successfully.');
   }
   public function update(Request $request)
   {
      $request->validate([
         'id' => 'required|exists:bed,id',
         'name' => 'required|string|max:255',
         'bed_type_id' => 'required|exists:bed_type,id',
         'bed_group_id' => 'required|exists:bed_group,id',
      ]);

      $bed = Bed::findOrFail($request->id);
      $bed->is_active = "yes";
      if ($bed->is_active === 'no' && $request->has('is_active')) {
         return redirect()->back()->with('error', 'This bed is already allotted and cannot be updated.');
     }
     if($request->has('is_active') && $bed->is_active == "yes"){
      $bed->is_active = "noused";
     }
      $bed->name = $request->name;
      $bed->bed_type_id = $request->bed_type_id;
      $bed->bed_group_id = $request->bed_group_id;
      $bed->save();

      return redirect()->back()->with('success', 'Bed updated successfully.');
   }
   public function destroy(Request $request)
   {
       $request->validate([
           'id' => 'required|exists:bed,id',
       ]);
   
       $bed = Bed::findOrFail($request->id);
       $bed->delete();
   
       return redirect()->back()->with('success', 'Bed deleted successfully.');
   }
   
}
