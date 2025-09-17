<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index(){
        return view('admin.patients.index');
    }
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'nullable|date',
            'age.year' => 'nullable|integer|min:0',
            'age.month' => 'nullable|integer|min:0|max:11',
            'age.day' => 'nullable|integer|min:0|max:31',
            'blood_group' => 'nullable|in:1,2,3,4,5,6',
            'marital_status' => 'nullable|in:Single,Married,Widowed,Separated,Not Specified',
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:500',
            'allergies' => 'nullable|string|max:255',
            'tpa' => 'nullable|in:1,2,3,4,5',
            'tpa_id' => 'nullable|string|max:100',
            'tpa_validity' => 'nullable|string|max:100',
            'national_id_number' => 'nullable|string|max:50',
        ]);

        // Handle file upload
        $photoPath = null;
        if ($request->hasFile('file')) {
            $photoPath = $request->file('file')->store('patient_photos', 'public');
        }

        // Save the patient
        $patient = Patient::create([
            'name' => $validated['name'],
            'guardian_name' => $validated['guardian_name'] ?? null,
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'] ?? null,
            'age_year' => $validated['age']['year'] ?? null,
            'age_month' => $validated['age']['month'] ?? null,
            'age_day' => $validated['age']['day'] ?? null,
            'blood_group' => $validated['blood_group'] ?? null,
            'marital_status' => $validated['marital_status'] ?? null,
            'photo' => $photoPath,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'tpa' => $validated['tpa'] ?? null,
            'tpa_id' => $validated['tpa_id'] ?? null,
            'tpa_validity' => $validated['tpa_validity'] ?? null,
            'national_id_number' => $validated['national_id_number'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Patient saved successfully!');
    }
}
