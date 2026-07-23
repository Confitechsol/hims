<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Organisation;
use App\Models\InsuranceCompany;
use App\Models\ChargeTypeMaster;
use App\Models\Charge;
use App\Models\ChargeCategory;
use App\Models\OrganisationsCharge;

class TpamanagmentController extends Controller
{
    protected function tpaListingQuery()
    {
        return Organisation::query()
            ->leftJoin('insurance_companies as ic', 'organisation.insurance_company_id', '=', 'ic.id')
            ->select('organisation.*', 'ic.name as insurance_company_name');
    }

    function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $query = $this->tpaListingQuery();

        if ($request->filled('insurance_company_id')) {
            $query->where('organisation.insurance_company_id', $request->input('insurance_company_id'));
        }

        if ($request->has("search")) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('organisation.organisation_name', 'like', "%{$search}%")
                    ->orWhere('organisation.code', 'like', "%{$search}%")
                    ->orWhere('organisation.contact_no', 'like', "%{$search}%")
                    ->orWhere('organisation.contact_person_name', 'like', "%{$search}%")
                    ->orWhere('organisation.contact_person_phone', 'like', "%{$search}%")
                    ->orWhere('ic.name', 'like', "%{$search}%");
            });
            $data = $query->get();
            return array(
                "status" => 200,
                "result" => $data,
                "total" => count($data)
            );
        }

        $organisations = $query->paginate($perPage);
        $insuranceCompanies = InsuranceCompany::orderBy('name')->pluck('name', 'id');
        $selectedInsuranceId = $request->input('insurance_company_id');

        return view('admin.tpa.tpamanagement', compact('organisations', 'perPage', 'insuranceCompanies', 'selectedInsuranceId'));

    }

    function store(Request $request)
    {
        $request->validate([
            'insurance_company_id' => 'required|exists:insurance_companies,id',
            'organisation_name' => 'required|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('organisation', 'code')->where(fn ($query) => $query->whereNotNull('code')->where('code', '!=', '')),
            ],
            'contact_no' => 'nullable|string|max:15|different:contact_person_phone',
            'address' => 'nullable|string|max:500',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:15|different:contact_no',
            'poilicy_no' => 'nullable|string|max:255',
            'e_card_no' => 'nullable|string|max:255',
            'e_card_upload' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $organisation = new Organisation();
        $organisation->insurance_company_id = $request->insurance_company_id;
        $organisation->organisation_name = $request->organisation_name;
        $organisation->code = $request->input('code', '');
        $organisation->contact_no = $request->input('contact_no', '');
        $organisation->address = $request->input('address', '');
        $organisation->contact_person_name = $request->input('contact_person_name', '');
        $organisation->contact_person_phone = $request->input('contact_person_phone', '');
        $organisation->poilicy_no = $request->input('poilicy_no', '');
        $organisation->e_card_no = $request->input('e_card_no', '');
        $organisation->e_card_upload = '';
        if ($request->hasFile('e_card_upload')) {
            $file = $request->file('e_card_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/e_cards'), $filename);
            $organisation->e_card_upload = 'uploads/e_cards/' . $filename;
        }

        $organisation->save();

        return redirect()->route('tpamanagement')->with('success', 'TPA added successfully.');
    }

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        $request->validate([
            'id' => 'required|exists:organisation,id',
            'insurance_company_id' => 'required|exists:insurance_companies,id',
            'organisation_name' => 'required|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('organisation', 'code')
                    ->ignore($id)
                    ->where(fn ($query) => $query->whereNotNull('code')->where('code', '!=', '')),
            ],
            'contact_no' => 'nullable|string|max:15|different:contact_person_phone',
            'address' => 'nullable|string|max:500',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:15|different:contact_no',
            'poilicy_no' => 'nullable|string|max:255',
            'e_card_no' => 'nullable|string|max:255',
            'e_card_upload' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $organisation = Organisation::findOrFail($id);

        $organisation->insurance_company_id = $request->insurance_company_id;
        $organisation->organisation_name = $request->organisation_name;
        $organisation->code = $request->input('code', '');
        $organisation->contact_no = $request->input('contact_no', '');
        $organisation->address = $request->input('address', '');
        $organisation->contact_person_name = $request->input('contact_person_name', '');
        $organisation->contact_person_phone = $request->input('contact_person_phone', '');
        $organisation->poilicy_no = $request->input('poilicy_no', '');
        $organisation->e_card_no = $request->input('e_card_no', '');

        if ($request->hasFile('e_card_upload')) {
            $file = $request->file('e_card_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/e_cards'), $filename);
            $organisation->e_card_upload = 'uploads/e_cards/' . $filename;
        }

        $organisation->save();

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

