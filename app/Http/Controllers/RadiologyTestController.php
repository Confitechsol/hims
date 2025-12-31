<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radio;
use App\Models\RadiologyCategory;
use App\Models\ChargeCategory;
use App\Models\Charge;
use App\Models\TaxCategory;
use App\Models\Organisation;
use App\Models\OrganisationsCharge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class RadiologyTestController extends Controller
{
    /**
     * Display a listing of radiology tests
     */
    public function index()
    {
        $tests = Radio::with([
            'radiologyCategory', 
            'charge', 
            'charge.taxCategory',
            'charge.category'
        ])
            ->orderBy('id', 'desc')
            ->get();
        
        return view('admin.radiology.test.index', compact('tests'));
    }

    /**
     * Show the form for creating a new radiology test
     */
    public function create()
    {
        $categories = RadiologyCategory::all();
        $chargeCategories = ChargeCategory::all();
        $organisations = Organisation::all();
        
        return view('admin.radiology.test.create', compact('categories', 'chargeCategories', 'organisations'));
    }

    /**
     * Store a newly created radiology test
     */
    public function store(Request $request)
    {
        Log::info('Radiology Test Store Request:', $request->all());
        
        $validated = $request->validate([
            'test_name' => 'required|string|max:50',
            'short_name' => 'required|string|max:20',
            'test_type' => 'nullable|string|max:15',
            'radiology_category_id' => 'required|exists:radiology_category,id',
            'sub_category' => 'nullable|string|max:25',
            'report_days' => 'required|integer',
            'charge_id' => 'required|exists:charges,id',
        ]);

        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            $createData = [
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'radiology_category_id' => $validated['radiology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'report_days' => $validated['report_days'],
                'charge_id' => $validated['charge_id'],
                // 'hospital_id' => $user->hospital_id ?? '',
                // 'branch_id' => $user->branch_id ?? '',
            ];
            
            $radiology = Radio::create($createData);

            Log::info('Radiology test created:', ['id' => $radiology->id]);

            // Create/Update TPA charges for all TPA organizations
            $hospitalId = $user->hospital_id ?? null;
            $branchId = $user->branch_id ?? null;
            
            $tpaChargesCreated = 0;
            $tpaChargesUpdated = 0;
            if ($hospitalId) {
                $organisations = Organisation::all();
                
                foreach ($organisations as $organisation) {
                    $tpaChargeKey = 'tpa_charge_' . $organisation->id;
                    $tpaChargeValue = $request->input($tpaChargeKey);
                    
                    $floatValue = ($tpaChargeValue !== null && $tpaChargeValue !== '' && is_numeric($tpaChargeValue)) 
                        ? floatval($tpaChargeValue) 
                        : 0;
                    
                    if ($floatValue > 0) {
                        $orgCharge = $floatValue;
                        
                        $existingTpaCharge = OrganisationsCharge::where('charge_id', $validated['charge_id'])
                            ->where('org_id', $organisation->id)
                            ->first();

                        if ($existingTpaCharge) {
                            $existingTpaCharge->org_charge = $orgCharge;
                            $existingTpaCharge->save();
                            $tpaChargesUpdated++;
                        } else {
                            $tpaChargeData = [
                                'charge_id' => $validated['charge_id'],
                                'org_id' => $organisation->id,
                                'org_charge' => $orgCharge,
                            ];
                            
                            if (Schema::hasColumn('organisations_charges', 'hospital_id')) {
                                $tpaChargeData['hospital_id'] = $hospitalId;
                            }
                            if (Schema::hasColumn('organisations_charges', 'branch_id')) {
                                $tpaChargeData['branch_id'] = $branchId ?? '';
                            }
                            
                            OrganisationsCharge::create($tpaChargeData);
                            $tpaChargesCreated++;
                        }
                    }
                }
            }

            DB::commit();
            
            $successMessage = 'Radiology test created successfully!';
            if ($tpaChargesCreated > 0 || $tpaChargesUpdated > 0) {
                $messageParts = [];
                if ($tpaChargesCreated > 0) {
                    $messageParts[] = "{$tpaChargesCreated} TPA charge(s) created";
                }
                if ($tpaChargesUpdated > 0) {
                    $messageParts[] = "{$tpaChargesUpdated} TPA charge(s) updated";
                }
                $successMessage .= " " . implode(", ", $messageParts) . ".";
            }
            
            return redirect()->route('radiology.test.index')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating radiology test:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating radiology test: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified radiology test
     */
    public function show($id)
    {
        $test = Radio::with([
            'radiologyCategory', 
            'charge', 
            'charge.taxCategory',
            'charge.category'
        ])
            ->findOrFail($id);
        
        // Load TPA charges for this radiology test's charge_id
        $tpaCharges = [];
        if ($test->charge_id) {
            $tpaCharges = OrganisationsCharge::with(['organisation', 'charge.category.chargeType'])
                ->where('charge_id', $test->charge_id)
                ->get();
        }
        
        return view('admin.radiology.test.show', compact('test', 'tpaCharges'));
    }

    /**
     * Show the form for editing the specified radiology test
     */
    public function edit($id)
    {
        $test = Radio::with(['charge', 'charge.category', 'charge.taxCategory', 'radiologyCategory'])->findOrFail($id);
        $categories = RadiologyCategory::all();
        $chargeCategories = ChargeCategory::all();
        $organisations = Organisation::all();
        
        // Load existing TPA charges for this test's charge_id
        $existingTpaCharges = [];
        if ($test->charge_id) {
            $tpaCharges = OrganisationsCharge::where('charge_id', $test->charge_id)->get();
            foreach ($tpaCharges as $tpaCharge) {
                $existingTpaCharges[$tpaCharge->org_id] = $tpaCharge->org_charge;
            }
        }
        
        return view('admin.radiology.test.edit', compact('test', 'categories', 'chargeCategories', 'organisations', 'existingTpaCharges'));
    }

    /**
     * Update the specified radiology test
     */
    public function update(Request $request, $id)
    {
        Log::info('Radiology Test Update Request:', ['id' => $id, 'data' => $request->all()]);
        
        $validated = $request->validate([
            'test_name' => 'required|string|max:50',
            'short_name' => 'required|string|max:20',
            'test_type' => 'nullable|string|max:15',
            'radiology_category_id' => 'required|exists:radiology_category,id',
            'sub_category' => 'nullable|string|max:25',
            'report_days' => 'required|integer',
            'charge_id' => 'required|exists:charges,id',
        ]);

        DB::beginTransaction();
        
        try {
            $radiology = Radio::findOrFail($id);
            
            $updateData = [
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'radiology_category_id' => $validated['radiology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'report_days' => $validated['report_days'],
                'charge_id' => $validated['charge_id'],
            ];
            
            $radiology->update($updateData);

            // Handle TPA charges
            $user = Auth::user();
            $hospitalId = $user->hospital_id ?? null;
            $branchId = $user->branch_id ?? null;
            
            $tpaChargesCreated = 0;
            if ($hospitalId) {
                // Delete all existing TPA charges for this charge_id first
                OrganisationsCharge::where('charge_id', $validated['charge_id'])->delete();
                
                $organisations = Organisation::all();
                foreach ($organisations as $organisation) {
                    $tpaChargeKey = 'tpa_charge_' . $organisation->id;
                    $tpaChargeValue = $request->input($tpaChargeKey);
                    
                    $floatValue = ($tpaChargeValue !== null && $tpaChargeValue !== '' && is_numeric($tpaChargeValue)) 
                        ? floatval($tpaChargeValue) 
                        : 0;
                    
                    if ($floatValue > 0) {
                        $orgCharge = $floatValue;
                        
                        $tpaChargeData = [
                            'charge_id' => $validated['charge_id'],
                            'org_id' => $organisation->id,
                            'org_charge' => $orgCharge,
                        ];
                        
                        if (Schema::hasColumn('organisations_charges', 'hospital_id')) {
                            $tpaChargeData['hospital_id'] = $hospitalId;
                        }
                        if (Schema::hasColumn('organisations_charges', 'branch_id')) {
                            $tpaChargeData['branch_id'] = $branchId ?? '';
                        }
                        
                        OrganisationsCharge::create($tpaChargeData);
                        $tpaChargesCreated++;
                    }
                }
            }

            DB::commit();
            
            $radiology->refresh();
            
            $successMessage = 'Radiology test updated successfully!';
            if ($tpaChargesCreated > 0) {
                $successMessage .= " {$tpaChargesCreated} TPA charge(s) created.";
            }
            
            return redirect()->route('radiology.test.show', $radiology->id)
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating radiology test:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating radiology test: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified radiology test
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $radiology = Radio::findOrFail($id);
            $radiology->delete();
            
            DB::commit();
            
            return redirect()->route('radiology.test.index')
                ->with('success', 'Radiology test deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting radiology test: ' . $e->getMessage());
        }
    }

    /**
     * Update TPA charge for a radiology test
     */
    public function updateTpaCharge(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:organisations_charges,id',
            'org_charge' => 'required|numeric|min:0',
        ]);

        try {
            $organisationCharge = OrganisationsCharge::findOrFail($validated['id']);
            $organisationCharge->org_charge = $validated['org_charge'];
            $organisationCharge->save();

            $radiology = Radio::where('charge_id', $organisationCharge->charge_id)->first();
            
            if ($radiology) {
                return redirect()->route('radiology.test.show', $radiology->id)
                    ->with('success', 'TPA charge updated successfully!');
            } else {
                return redirect()->back()
                    ->with('error', 'Radiology test not found for this charge.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating TPA charge:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error updating TPA charge: ' . $e->getMessage());
        }
    }

    /**
     * API: Get charge names by charge category
     */
    public function getChargeNames(Request $request)
    {
        $chargeCategoryId = $request->input('charge_category_id');
        
        $charges = Charge::where('charge_category_id', $chargeCategoryId)
            ->with('taxCategory')
            ->get()
            ->map(function($charge) {
                return [
                    'id' => $charge->id,
                    'name' => $charge->name,
                    'standard_charge' => $charge->standard_charge,
                    'tax_percentage' => $charge->taxCategory ? $charge->taxCategory->percentage : 0,
                ];
            });
        
        return response()->json($charges);
    }

    /**
     * API: Get charge details by charge ID
     */
    public function getChargeDetails(Request $request)
    {
        $chargeId = $request->input('charge_id');
        
        $charge = Charge::with('taxCategory')->find($chargeId);
        
        if (!$charge) {
            return response()->json(['error' => 'Charge not found'], 404);
        }
        
        return response()->json([
            'id' => $charge->id,
            'name' => $charge->name,
            'standard_charge' => $charge->standard_charge,
            'tax_percentage' => $charge->taxCategory ? $charge->taxCategory->percentage : 0,
            'amount' => $charge->standard_charge + ($charge->standard_charge * ($charge->taxCategory ? $charge->taxCategory->percentage : 0) / 100),
        ]);
    }
}

