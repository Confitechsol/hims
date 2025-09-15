<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HospitalBranch;

class ProfileController extends Controller
{
    /**
     * Display the profile settings form.
     */
    public function index()
    {
        $branch = HospitalBranch::first();
        return view('admin.setup.profile', compact('branch'));
    }

    /**
     * Update existing profile.
     */
    public function update(Request $request)
    {
        $branch = HospitalBranch::first();
        if (!$branch) {
            $branch = new HospitalBranch();
            $branch->hospital_id = 1; // default hospital id
        }

        // Assign request data to model
        $branch->name = $request->hospital_name;
        $branch->branch_id = $request->hospital_code;
        $branch->address = $request->address;
        $branch->phone = $request->phone;
        $branch->email = $request->email;
        $branch->timezone = $request->time_zone;
        $branch->currency = $request->currency;
        $branch->currency_symbol = $request->currency_symbol;
        $branch->credit_limit = $request->credit_limit;

        // Handle logos
        if ($request->hasFile('hospital_logo')) {
            $branch->image = $request->file('hospital_logo')->store('hospital_content/logo', 'public');
        }
        if ($request->hasFile('small_logo')) {
            $branch->mini_logo = $request->file('small_logo')->store('hospital_content/logo', 'public');
        }
        if ($request->hasFile('mobile_app_logo')) {
            $branch->mobile_app_logo = $request->file('mobile_app_logo')->store('hospital_content/logo', 'public');
        }

        $branch->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Store new profile.
     */
    public function store(Request $request)
    {
        $branch = new HospitalBranch();
        $branch->hospital_id = 1; // default hospital id
        $branch->name = $request->hospital_name;
        $branch->branch_id = $request->hospital_code;
        $branch->address = $request->address;
        $branch->phone = $request->phone;
        $branch->email = $request->email;
        $branch->timezone = $request->time_zone;
        $branch->currency = $request->currency;
        $branch->currency_symbol = $request->currency_symbol;
        $branch->credit_limit = $request->credit_limit;

        if ($request->hasFile('hospital_logo')) {
            $branch->image = $request->file('hospital_logo')->store('hospital_content/logo', 'public');
        }
        if ($request->hasFile('small_logo')) {
            $branch->mini_logo = $request->file('small_logo')->store('hospital_content/logo', 'public');
        }
        if ($request->hasFile('mobile_app_logo')) {
            $branch->mobile_app_logo = $request->file('mobile_app_logo')->store('hospital_content/logo', 'public');
        }

        $branch->save();

        return redirect()->back()->with('success', 'Profile created successfully!');
    }
}
