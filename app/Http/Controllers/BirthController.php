<?php

namespace App\Http\Controllers;
use App\Models\BirthReport;
use Illuminate\Http\Request;

class BirthController extends Controller
{
    function index(Request $request){
     $perPage = intval($request->input('perPage', 5));
    if ($perPage <= 0) {
        $perPage = 5;
    }
    $query = BirthReport::with(['patient']);
    if ($request->has('search')) {
        $search_term = $request->search;
        $query->where(function ($q) use ($search_term) {
            $q->where('child_name', 'like', "%{$search_term}%")
                ->orWhere('father_name', 'like', "%{$search_term}%")
                ->orWhereHas('patient', function ($sub) use ($search_term) {
                    $sub->where('patient_name', 'like', "%{$search_term}%");
                });
        });
    }
    $birthReports = $query->paginate($perPage);
    //return response()->json($birthReports, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);~
     return view('admin.birthordeath.index', compact('birthReports'));
    }

    function store(Request $request)
    {
        $request->validate([
            'child_name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:organisation,code',
            'contact_no' => 'required|string|max:15|different:contact_person_phone',
            'address' => 'required|string|max:500',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:15|different:contact_no',
            'poilicy_no' => 'required|string|max:255',
            'e_card_no' => 'required|string|max:255',
            'e_card_upload' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $birth = new BirthReport();
        $birth->child_name = $request->child_name;
        $birth->gender = $request->gender;
        $birth->weight = $request->weight;
        
      if ($request->hasFile('baby_image')) {
       $file = $request->file('baby_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/baby_image'), $filename);
      $birth->baby_image = 'uploads/baby_image/' . $filename;  
     }


        $birth->contact_person_phone = $request->contact_person_phone;
        $birth->address = $request->address;
        $birth->caseId = $request->caseId;
        $birth->mother_name = $request->mother_name;

         if ($request->hasFile('mother_image')) {
       $file = $request->file('mother_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/mother_image'), $filename);
      $birth->mother_image = 'uploads/mother_image/' . $filename;  
     }


        $birth->father_name = $request->father_name;

        
       if ($request->hasFile('father_image')) {
       $file = $request->file('father_image');
       $filename = time() . '_' . $file->getClientOriginalName();
       $file->move(public_path('uploads/father_image'), $filename);
      $birth->father_image = 'uploads/father_image/' . $filename;  
       }

        $birth->report = $request->report;
        // Handle file upload
        if ($request->hasFile('report_image')) {
       $file = $request->file('report_image');
       $filename = time() . '_' . $file->getClientOriginalName();
       $file->move(public_path('uploads/report_image'), $filename);
       $birth->report_image = 'uploads/report_image/' . $filename;  
       }
        
        $birth->save();

        return redirect()->route('birthordeath')->with('success', 'Birth added successfully.');
    }
}
