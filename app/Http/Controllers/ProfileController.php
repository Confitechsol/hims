<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\HospitalBranch;

class ProfileController extends Controller
{
    /**
     * Display the profile settings form.
     */
    public function index()
    {
        $hospital = Hospital::first();
        return view('admin.setup.profile', compact('hospital'));
    }

    /**
     * Update existing profile.
     */
    public function update(Request $request)
    {
        $hospital = Hospital::first();
        if (!$hospital) {
            $hospital = new Hospital();
        }

        // Update Hospital
        $hospital->name = $request->hospital_name;
        $hospital->hospital_id = $request->hospital_code;
        $hospital->address = $request->address;
        $hospital->phone = $request->phone;
        $hospital->email = $request->email;
        $hospital->languages = $request->language;
        $hospital->date_format = $request->date_format;
        $hospital->time_format = $request->time_format;
        $hospital->timezone = $request->time_zone;
        $hospital->mobile_api_url = $request->api_url;
        $hospital->currency = $request->currency;
        $hospital->currency_symbol = $request->currency_symbol;
        $hospital->credit_limit = $request->credit_limit;

        $hospital->image = $request->hasFile('hospital_logo') ? $request->file('hospital_logo')->store('hospital_content/logo', 'public') : '';
        $hospital->mini_logo = $request->hasFile('small_logo') ? $request->file('small_logo')->store('hospital_content/logo', 'public') : '';
        $hospital->mobile_app_logo = $request->hasFile('mobile_app_logo') ? $request->file('mobile_app_logo')->store('hospital_content/logo', 'public') : '';

        $hospital->save();

        // Add new branches
        if ($request->has('branch_id') && is_array($request->branch_id)) {
            foreach ($request->branch_id as $index => $branchId) {
                // Check if branch_id already exists to avoid duplicates
                $existing = HospitalBranch::where('branch_id', $branchId)->first();
                if (!$existing) {
                    $branch = new HospitalBranch();
                    $branch->hospital_id = $hospital->id;
                $branch->branch_id = $branchId;
                $branch->name = $request->branch_name[$index] ?? '';
                $branch->address = $request->branch_address[$index] ?? '';
                $branch->phone = $request->branch_phone[$index] ?? '';
                $branch->email = $request->branch_email[$index] ?? '';
                $branch->branch_currency = $request->branch_currency[$index] ?? '';
                $branch->branch_currency_symbol = $request->branch_currency_symbol[$index] ?? '';
                $branch->branch_credit_limit = $request->branch_credit_limit[$index] ?? '';
                $branch->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Store new profile.
     */
    public function store(Request $request)
    {
        // Save to Hospital
        $hospital = Hospital::first();
        if (!$hospital) {
            $hospital = new Hospital();
        }
        $hospital->name = $request->hospital_name;
        $hospital->hospital_id = $request->hospital_code; // assuming hospital_id is the code
        $hospital->address = $request->address;
        $hospital->phone = $request->phone;
        $hospital->email = $request->email;
        $hospital->languages = $request->language;
        $hospital->date_format = $request->date_format;
        $hospital->time_format = $request->time_format;
        $hospital->timezone = $request->time_zone;
        $hospital->mobile_api_url = $request->api_url;
        $hospital->currency = $request->currency;
        $hospital->currency_symbol = $request->currency_symbol;
        $hospital->credit_limit = $request->credit_limit;

        $hospital->image = $request->hasFile('hospital_logo') ? $request->file('hospital_logo')->store('hospital_content/logo', 'public') : '';
        $hospital->mini_logo = $request->hasFile('small_logo') ? $request->file('small_logo')->store('hospital_content/logo', 'public') : '';
        //$hospital->mobile_app_logo = $request->hasFile('mobile_app_logo') ? $request->file('mobile_app_logo')->store('hospital_content/logo', 'public') : '';

        $hospital->save();

        // Save branches
        if ($request->has('branch_id') && is_array($request->branch_id)) {
            foreach ($request->branch_id as $index => $branchId) {
                $branch = new HospitalBranch();
                $branch->hospital_id = $hospital->id;
                $branch->branch_id = $branchId;
                $branch->name = $request->branch_name[$index] ?? '';
                $branch->address = $request->branch_address[$index] ?? '';
                $branch->phone = $request->branch_phone[$index] ?? '';
                $branch->email = $request->branch_email[$index] ?? '';
                $branch->branch_currency = $request->branch_currency[$index] ?? '';
                $branch->branch_currency_symbol = $request->branch_currency_symbol[$index] ?? '';
                $branch->branch_credit_limit = $request->branch_credit_limit[$index] ?? '';
                $branch->save();
            }
        }

        return redirect()->back()->with('success', 'Profile created successfully!');
    }
}
