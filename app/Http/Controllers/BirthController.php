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
            'organisation_name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:organisation,code',
            'contact_no' => 'required|string|max:15|different:contact_person_phone',
            'address' => 'required|string|max:500',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:15|different:contact_no',
            'poilicy_no' => 'required|string|max:255',
            'e_card_no' => 'required|string|max:255',
            'e_card_upload' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $organisation = new Organisation();
        $organisation->organisation_name = $request->organisation_name;
        $organisation->code = $request->code;
        $organisation->contact_no = $request->contact_no;
        $organisation->address = $request->address;
        $organisation->contact_person_name = $request->contact_person_name;
        $organisation->contact_person_phone = $request->contact_person_phone;
        $organisation->poilicy_no = $request->poilicy_no;
        $organisation->e_card_no = $request->e_card_no;
        // Handle file upload
        if ($request->hasFile('e_card_upload')) {       
            $file = $request->file('e_card_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/e_cards'), $filename);
            $organisation->e_card_upload = 'uploads/e_cards/' . $filename;  
        }

        $organisation->save();

        return redirect()->route('tpamanagement')->with('success', 'TPA added successfully.');
    }
}
