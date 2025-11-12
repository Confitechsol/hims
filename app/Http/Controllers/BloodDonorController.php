<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodDonor;
use App\Models\BloodBankProduct;

class BloodDonorController extends Controller
{
    public function index()
    {
        $bloodGroups = BloodBankProduct::all();
        // Fetch all donors from database
        $donors = BloodDonor::with('bloodBankProduct')->get();

        // Pass data to the Blade view using compact
        return view('admin.blood-bank-doner.doners', compact('donors','bloodGroups'));
    }
     public function addDonors(Request $request)
    {

        $validated = $request->validate([
            'doner_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'blood_group' => 'required|string',
            'gender' => 'required|string',
            'father_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        BloodDonor::create([
            'donor_name' => $validated['doner_name'],
            'dob' => $validated['dob'],
            'blood_bank_product_id ' => $validated['blood_group'],
            'gender' => $validated['gender'],
            'father_name' => $validated['father_name'],
            'contact_no' => $validated['contact_no'],
            'address' => $validated['address'],
        ]);

        return redirect()->back()->with('success', 'Donor added successfully!');
    }
}
