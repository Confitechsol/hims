<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisation;
use App\Models\ChargeTypeMaster;
use App\Models\Charge;
use App\Models\ChargeCategory;
use App\Models\OrganisationsCharge;

class TpamanagmentController extends Controller
{
    function index(Request $request)
    {
        $query = Organisation::query();
        if ($request->has("search")) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('organisation_name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('contact_no', 'like', "%{$search}%")
                    ->orWhere('contact_person_name', 'like', "%{$search}%")
                    ->orWhere('contact_person_phone', 'like', "%{$search}%");
            });
            $data = $query->get();
            return array(
                "status" => 200,
                "result" => $data,
                "total" => count($data)
            );
        }
        $organisations = $query->get();

        return view('admin.tpa.tpamanagement', compact('organisations'));

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
        ]);

        $organisation = new Organisation();
        $organisation->organisation_name = $request->organisation_name;
        $organisation->code = $request->code;
        $organisation->contact_no = $request->contact_no;
        $organisation->address = $request->address;
        $organisation->contact_person_name = $request->contact_person_name;
        $organisation->contact_person_phone = $request->contact_person_phone;
        $organisation->save();

        return redirect()->route('tpamanagement')->with('success', 'TPA added successfully.');
    }

    function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:organisation,id',
            'organisation_name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:organisation,code,' . $request->id,
            'contact_no' => 'required|string|max:15|different:contact_person_phone',
            'address' => 'required|string|max:500',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:15|different:contact_no',
        ]);

        $organisation = Organisation::findOrFail($request->id);
        $organisation->update([
            'organisation_name' => $request->organisation_name,
            'code' => $request->code,
            'contact_no' => $request->contact_no,
            'address' => $request->address,
            'contact_person_name' => $request->contact_person_name,
            'contact_person_phone' => $request->contact_person_phone,
        ]);

        return redirect()->route('tpamanagement')->with('success', 'TPA updated successfully.');
    }

    function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:organisation,id',
        ]);

        $organisation = Organisation::findOrFail($request->id);
        $organisation->delete();

        return redirect()->route('tpamanagement')->with('success', 'TPA deleted successfully.');
    }

    function detailsshow(Request $request,$id = null)
    {
        if ($id == null) {
            return redirect()->route('tpamanagement');
        }
        $chargetypes = ChargeTypeMaster::all();
        $organisations = Organisation::findOrFail($id);
        if ($request->isMethod('post')) {
            $request->validate([
                'charge_type' => 'required|exists:charge_type_master,id',
            ]);
            $charge_type = $request->input('charge_type');

            // ✅ Save selected charge type in session (so it persists after reload)
            session(['charge_type' => $charge_type]);

            $chargeCategory = ChargeCategory::where('charge_type_id', $charge_type)->first();
            if (!$chargeCategory) {
                return redirect()->back()->with('error', 'Charge category not found.');
            }
            $charge = Charge::where('charge_category_id', $chargeCategory->id)->first();
            if (!$charge) {
                return redirect()->back()->with('error', 'Charge not found.');
            }
            $organisationCharge = OrganisationsCharge::with(['charge.category.chargeType'])->where('charge_id', $charge->id)
                ->where('org_id', $id)
                ->get();
            $charge_type = ChargeTypeMaster::findOrFail($charge_type);
                if (!$organisationCharge) {
                    return redirect()->back()->with('error', 'No data available for '.$charge_type->charge_type.' on this organisation.');
                }
                return view('admin.tpa.tpa_details', compact('organisations', 'chargetypes', 'organisationCharge'))->with('charge_type',$charge_type);
        }
        session()->forget('charge_type');
        return view('admin.tpa.tpa_details', compact('organisations', 'chargetypes'));
    }

    function destroyTpaDetails(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:organisations_charges,id',
        ]);

        // ✅ Clear the stored charge_type from session
        session()->forget('charge_type');
        $organisationCharge = OrganisationsCharge::findOrFail($request->id);return $organisationCharge;
        $organisationCharge->delete();

        return redirect()->back()->with('success', 'TPA Charge deleted successfully.');
    }

    function updateTpaDetails(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:organisations_charges,id',
            'org_charge' => 'required|numeric|min:0',
        ]);
        session()->get('charge_type');

        $chargetypes = ChargeTypeMaster::all();
        $organisationCharge = OrganisationsCharge::findOrFail($request->id);
        $organisationCharge->org_charge = $request->org_charge;
        $organisationCharge->save();

        $organisations = Organisation::findOrFail($organisationCharge->org_id);
        // $request->validate([
        //         'charge_type' => 'required|exists:charge_type_master,id',
        //     ]);
            $charge_type = session()->get('charge_type');

            $chargeCategory = ChargeCategory::where('charge_type_id', $charge_type)->first();
            if (!$chargeCategory) {
                return redirect()->back()->with('error', 'Charge category not found.');
            }
            $charge = Charge::where('charge_category_id', $chargeCategory->id)->first();
            if (!$charge) {
                return redirect()->back()->with('error', 'Charge not found.');
            }
            $organisationCharge = OrganisationsCharge::with(['charge.category.chargeType'])->where('charge_id', $charge->id)
                ->where('org_id', $organisationCharge->org_id)
                ->get();
            $charge_type = ChargeTypeMaster::findOrFail($charge_type);
                if (!$organisationCharge) {
                    return redirect()->back()->with('error', 'No data available for '.$charge_type->charge_type.' on this organisation.');
                }
                return view('admin.tpa.tpa_details', compact('organisations', 'chargetypes', 'organisationCharge'))->with('charge_type',$charge_type);
        

        return redirect()->back()->with('success', 'TPA Charge updated successfully.');
    }
}

