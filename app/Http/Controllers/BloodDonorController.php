<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodDonor;
use App\Models\BloodBankProduct;
use App\Models\BloodIssue;
use Illuminate\Support\Facades\Auth;

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
    public function updateDonor(Request $request, $id)
    {
        $request->validate([
            'doner_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'blood_group' => 'required',
            'gender' => 'required|string',
            'father_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        $donor = BloodDonor::findOrFail($id);
        $donor->donor_name = $request->doner_name;
        $donor->date_of_birth = $request->dob;
        $donor->blood_bank_product_id = $request->blood_group;
        $donor->gender = $request->gender;
        $donor->father_name = $request->father_name;
        $donor->contact_no = $request->contact_no;
        $donor->address = $request->address;
        $donor->save();

        return redirect()->back()->with('success', 'Donor details updated successfully!');
    }

    public function destroyDonor($id)
    {
        $donor = BloodDonor::findOrFail($id);
        $donor->delete(); // Soft delete
        return redirect()->back()->with('success', 'Donor deleted successfully!');
    }
    public function bloodIssues()
    {
        // Fetch all blood issues with related donor info (if relationship exists)
        $bloodissues = BloodIssue::with(['donorCycle', 'patient']) // optional relation if exists
            ->orderBy('date_of_issue', 'desc')
            ->get();

        // Pass data to Blade view
        return view('admin.blood-bank-doner.blood-issue', compact('bloodissues'));
    }


}
