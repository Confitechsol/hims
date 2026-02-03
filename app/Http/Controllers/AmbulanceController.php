<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AmbulanceCall;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use App\Models\ChargeCategory;
use App\Models\Patient;
use App\Models\Charge;

class AmbulanceController extends Controller
{
    public function index()
    {
        $calls = AmbulanceCall::with(['patient', 'vehicle', 'generatedBy'])
            ->latest('date')
            ->paginate(10);
        $vehicles = Vehicle::all();
        $patients = Patient::all();
        $chargeCategories = ChargeCategory::all();

        return view('admin.ambulance.ambulanceCallList', compact('calls','vehicles','chargeCategories','patients'));
    }

    /**
     * Store a new ambulance call
     */
    public function addCall(Request $request)
    {
        $validated = $request->validate([
            'hospital_id'          => 'nullable|string',
            'branch_id'            => 'nullable|string',
            'patient_id'           => 'nullable|string',
            'case_reference_id'    => 'nullable|integer',
            'vehicle_id'           => 'nullable|integer',
            'contact_no'           => 'required|string|max:20',
            'address'              => 'required|string',
            'vehicle_model'        => 'nullable|string|max:255',
            'driver'               => 'nullable|string|max:255',
            'date'                 => 'required|date',
            'call_from'            => 'required|string|max:255',
            'call_to'              => 'required|string|max:255',
            'charge_category_id'   => 'nullable|integer',
            'charge_id'            => 'nullable|integer',
            'standard_charge'      => 'nullable|numeric',
            'discount_percentage'  => 'nullable|numeric|min:0|max:100',
            'discount'             => 'nullable|numeric|min:0',
            'tax_percentage'       => 'nullable|numeric|min:0|max:100',
            'amount'               => 'required|numeric|min:0',
            'net_amount'           => 'required|numeric|min:0',
            'transaction_id'       => 'nullable|string|max:255',
            'note'                 => 'nullable|string',
        ]);

        $validated['generated_by'] = Auth::id();

        AmbulanceCall::create($validated);

        return redirect()
            ->route('ambulanceCall.index')
            ->with('success', 'Ambulance call created successfully.');
    }

    /**
     * Get ambulance call for edit (page or modal)
     */
    public function editCall($id)
    {
        $call = AmbulanceCall::findOrFail($id);

        return view('ambulance.edit', compact('call'));
    }

    /**
     * Update an ambulance call
     */
    public function updateCall(Request $request, $id)
    {
        $call = AmbulanceCall::findOrFail($id);

        $validated = $request->validate([
            'hospital_id'          => 'required|integer',
            'branch_id'            => 'required|integer',
            'patient_id'           => 'nullable|integer',
            'case_reference_id'    => 'nullable|integer',
            'vehicle_id'           => 'nullable|integer',
            'contact_no'           => 'required|string|max:20',
            'address'              => 'required|string',
            'vehicle_model'        => 'nullable|string|max:255',
            'driver'               => 'nullable|string|max:255',
            'date'                 => 'required|date',
            'call_from'            => 'required|string|max:255',
            'call_to'              => 'required|string|max:255',
            'charge_category_id'   => 'nullable|integer',
            'charge_id'            => 'nullable|integer',
            'standard_charge'      => 'nullable|numeric',
            'discount_percentage'  => 'nullable|numeric|min:0|max:100',
            'discount'             => 'nullable|numeric|min:0',
            'tax_percentage'       => 'nullable|numeric|min:0|max:100',
            'amount'               => 'required|numeric|min:0',
            'net_amount'           => 'required|numeric|min:0',
            'transaction_id'       => 'nullable|string|max:255',
            'note'                 => 'nullable|string',
        ]);

        $call->update($validated);

        return redirect()
            ->route('ambulance.ambulanceCallList')
            ->with('success', 'Ambulance call updated successfully.');
    }

    /**
     * Delete ambulance call
     */
    public function destroyCall($id)
    {
        AmbulanceCall::findOrFail($id)->delete();

        return redirect()
            ->route('ambulanceCall.index')
            ->with('success', 'Ambulance call deleted successfully.');
    }

    public function getChargesByCategory($categoryId)
    {
        return Charge::where('charge_category_id', $categoryId)
            ->select('id', 'name')
            ->get();
    }

    public function getChargeDetails($chargeId)
    {
        return Charge::select('id', 'standard_charge')
            ->findOrFail($chargeId);
    }
}
