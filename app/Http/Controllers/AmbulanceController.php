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

    public function editCall($id)
    {
        return response()->json(
            $call = AmbulanceCall::with(['patient', 'vehicle', 'generatedBy'])->findOrFail($id)
        );

        
    }

    public function updateCall(Request $request, $id)
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

        $ambulanceCall = AmbulanceCall::findOrFail($id);

        // optional: track who updated
        $validated['updated_by'] = Auth::id();

        $ambulanceCall->update($validated);

        return redirect()
            ->route('ambulanceCall.index')
            ->with('success', 'Ambulance call updated successfully.');
    }

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
    public function ambulanceList()
    {
        
        $vehicles = Vehicle::paginate(10);
        

        return view('admin.ambulance.ambulanceList', compact('vehicles'));
    }

    public function editList($id)
    {
        return response()->json(
            Vehicle::findOrFail($id)
        );
    }

    public function addList(Request $request)
    {
        $request->validate([
            'vehicle_no'         => 'required|string|max:50',
            'vehicle_model'      => 'required|string|max:100',
            'year_made'          => 'required|string|max:10',
            'driver_name'        => 'required|string|max:100',
            'driver_license'     => 'required|string|max:100',
            'driver_contact_no'  => 'required|string|max:15',       
            'note'               => 'nullable|string',
        ]);

        Vehicle::create([
            'vehicle_no'         => $request->vehicle_no,
            'vehicle_model'      => $request->vehicle_model,
            'manufacture_year'   => $request->year_made,
            'driver_name'        => $request->driver_name,
            'driver_licence'     => $request->driver_license,
            'driver_contact'     => $request->driver_contact_no,
            'vehicle_type'       => $request->vehicle_type,
            'note'               => $request->note,
        ]);

        return redirect()->back()->with('success', 'Ambulance added successfully.');
    }

    public function updateList(Request $request, $id)
    {
        $request->validate([
            'vehicle_no'         => 'required|string|max:50',
            'vehicle_model'      => 'required|string|max:100',
            'year_made'          => 'required|string|max:10',
            'driver_name'        => 'required|string|max:100',
            'driver_license'     => 'required|string|max:100',
            'driver_contact_no'  => 'required|string|max:15',
            'note'               => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($id);

        $vehicle->update([
            'vehicle_no'         => $request->vehicle_no,
            'vehicle_model'      => $request->vehicle_model,
            'manufacture_year'   => $request->year_made,
            'driver_name'        => $request->driver_name,
            'driver_licence'     => $request->driver_license,
            'driver_contact'     => $request->driver_contact_no,
            'vehicle_type'       => $request->vehicle_type,
            'note'               => $request->note,
        ]);

        return redirect()->back()->with('success', 'Ambulance updated successfully.');
    }
    
    public function destroyList($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete(); 

        return redirect()->back()->with('success', 'Ambulance deleted successfully.');
    }

}
