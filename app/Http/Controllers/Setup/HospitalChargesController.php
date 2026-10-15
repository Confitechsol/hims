<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Charge;
use App\Models\ChargeTypeMaster;
use App\Models\ChargeUnit;
use App\Models\TaxCategory;
use App\Models\Organisation;
use App\Models\ChargeCategory;
use App\Models\OrganisationsCharge;
use Illuminate\Support\Facades\DB;


class HospitalChargesController extends Controller
{
    public function index(Request $request){
       $charges = Charge::query();
       $charge_types = ChargeTypeMaster::all();
       $chargeCategories = ChargeCategory::all();
       $charge_unit= ChargeUnit::all();
       $charge_tax_category_id=TaxCategory::all();
       $organisation_names=organisation::all();
       $organisation_charges = OrganisationsCharge::all();
       $perPage   = intval($request->input('perPage', 10));
        if ($perPage <= 0) {
            $perPage = 10;
        }

    //     if ($request->has('search')) {
    //      $search_term = $request->search;
    //         $charges->where(function ($query) use ($search_term) {
    //             $query->where('name', 'like', "%{$search_term}%");
    //         });
    //      $charges = $charges->with('category.chargeType','unit','taxCategory')->paginate($perPage);
    //      return ["result" => $charges];
    // }
    //  $charges = $charges->paginate($perPage);
    // ✅ SEARCH FILTER (NO JSON RETURN)
    if ($request->filled('search')) {
        $search_term = $request->search;

        $charges->where('name', 'like', "%{$search_term}%");
    }

    // ✅ ALWAYS RETURN RELATIONS + VIEW
    $charges = $charges->with('category.chargeType', 'unit', 'taxCategory')
                       ->paginate($perPage)
                       ->withQueryString();

     return view('admin.setup.charges',compact('charges','charge_types','charge_unit','charge_tax_category_id','organisation_names','chargeCategories','organisation_charges'));

    }
   
    public function store(Request $request){
        $validated = $request->validate([
            'charge_type' => 'required',
            'charge_category' => 'required',
            'tax_category' => 'nullable',
            'standard_charge' => 'required|numeric|min:0',
            'charge_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'unit_type' => 'required',
            'schedule_charge_id' => 'required|array',
            'schedule_charge_id.*' => 'required|integer|distinct|exists:organisation,id',
        ]);

        $organisationIds = $validated['schedule_charge_id'];
        $scheduleChargeRules = [];
        foreach ($organisationIds as $organisationId) {
            $scheduleChargeRules["schedule_charge_{$organisationId}"] = 'nullable|numeric|min:0';
        }
        $validatedScheduleCharges = $request->validate($scheduleChargeRules);

        $user = $request->user();
        $hospitalId = $user->hospital_id ?? session('hospital_id', '1');
        $branchId = $user->branch_id ?? session('branch_id', '1');

        DB::transaction(function () use ($validated, $validatedScheduleCharges, $organisationIds, $hospitalId, $branchId) {
            $charge = Charge::create([
                'hospital_id' => $hospitalId,
                'branch_id' => $branchId,
                'charge_category_id' => $validated['charge_category'],
                'tax_category_id' => $validated['tax_category'] ?? null,
                'charge_unit_id' => $validated['unit_type'],
                'name' => $validated['charge_name'],
                'standard_charge' => $validated['standard_charge'],
                'date' => null,
                'description' => $validated['description'] ?? null,
                'status' => '',
            ]);

            foreach ($organisationIds as $organisationId) {
                $inputName = "schedule_charge_{$organisationId}";
                if (isset($validatedScheduleCharges[$inputName]) && $validatedScheduleCharges[$inputName] !== '') {
                    OrganisationsCharge::create([
                        'hospital_id' => $hospitalId,
                        'branch_id' => $branchId,
                        'charge_id' => $charge->id,
                        'org_id' => $organisationId,
                        'org_charge' => $validatedScheduleCharges[$inputName],
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Charges Created Successfully!');
    }
    public function update(Request $request){
        $rules = [
            'charge_id' => 'required|exists:charges,id',
            'charge_type' => 'required|exists:charge_type_master,id',
            'charge_category' => 'required|exists:charge_categories,id',
            'tax_category' => 'nullable|exists:tax_category,id',
            'standard_charge' => 'required|numeric|min:0',
            'charge_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'unit_type' => 'required|exists:charge_units,id',
            'schedule_charge_id' => 'required|array',
            'schedule_charge_id.*' => 'required|integer|distinct|exists:organisation,id',
        ];

        $validated = $request->validate($rules);
        $organisationIds = $validated['schedule_charge_id'];
        $scheduleChargeRules = [];
        foreach ($organisationIds as $organisationId) {
            $scheduleChargeRules["schedule_charge_{$organisationId}"] = 'nullable|numeric|min:0';
        }
        $validatedScheduleCharges = $request->validate($scheduleChargeRules);
        $charge = Charge::findOrFail($validated['charge_id']);
        $scheduleChargeValues = [];
        foreach ($validated['schedule_charge_id'] as $organisationId) {
            $inputName = "schedule_charge_{$organisationId}";
            $scheduleChargeValues[$organisationId] = $validatedScheduleCharges[$inputName] ?? null;
        }

        $user = $request->user();
        $hospitalId = $user->hospital_id ?? session('hospital_id', '1');
        $branchId = $user->branch_id ?? session('branch_id', '1');

        DB::transaction(function () use ($charge, $validated, $scheduleChargeValues, $hospitalId, $branchId) {
            $charge->update([
                'charge_category_id' => $validated['charge_category'],
                'tax_category_id' => $validated['tax_category'] ?? null,
                'charge_unit_id' => $validated['unit_type'],
                'name' => $validated['charge_name'],
                'standard_charge' => $validated['standard_charge'],
                'date' => null,
                'description' => $validated['description'] ?? null,
                'status' => '',
            ]);

            foreach ($scheduleChargeValues as $organisationId => $orgCharge) {
                $organisationCharge = OrganisationsCharge::where('charge_id', $charge->id)
                    ->where('org_id', $organisationId)
                    ->first();

                if ($orgCharge === null || $orgCharge === '') {
                    $organisationCharge?->delete();
                    continue;
                }

                if ($organisationCharge) {
                    $organisationCharge->update([
                        'hospital_id' => $hospitalId,
                        'branch_id' => $branchId,
                        'org_charge' => $orgCharge,
                    ]);
                } else {
                    OrganisationsCharge::create([
                        'hospital_id' => $hospitalId,
                        'branch_id' => $branchId,
                        'charge_id' => $charge->id,
                        'org_id' => $organisationId,
                        'org_charge' => $orgCharge,
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', $charge->name . ' Charge Updated Successfully!');
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:charges,id',
        ]);

        $charge = Charge::findOrFail($request->id);

        // Soft delete related organisation charges
        OrganisationsCharge::where('charge_id', $charge->id)->delete();

        // Soft delete main charge
        $charge->delete();

        return redirect()->back()->with(
            'success',
            $charge->name . ' Charge Deleted Successfully!'
        );
    }
}
