<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pathology;
use App\Models\PathologyCategory;
use App\Models\ChargeCategory;
use App\Models\Charge;
use App\Models\PathologyParameter;
use App\Models\PathologyParameterDetail;
use App\Models\TaxCategory;
use App\Models\Organisation;
use App\Models\OrganisationsCharge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class PathologyTestController extends Controller
{
    /**
     * Display a listing of pathology tests
     */
    public function index()
    {
        $tests = Pathology::with([
            'category', 
            'chargeCategory', 
            'charge', 
            'charge.taxCategory',
            'charge.category'
        ])
            ->orderBy('id', 'desc')
            ->get();
        
        return view('admin.pathology.test.index', compact('tests'));
    }

    /**
     * Show the form for creating a new pathology test
     */
    public function create()
    {
        $categories = PathologyCategory::all();
        $chargeCategories = ChargeCategory::all();
        $organisations = Organisation::all();
        $parameters = PathologyParameter::with('unitRelation')->get()->map(function($param) {
            $unitData = [];
            if ($param->unitRelation) {
                $unitData = [
                    'id' => $param->unitRelation->id,
                    'unit_name' => $param->unitRelation->unit_name,
                ];
            }
            return [
                'id' => $param->id,
                'parameter_name' => $param->parameter_name,
                'reference_range' => $param->reference_range,
                'unit_id' => $param->unit_id,
                'unit_relation' => $unitData,
            ];
        })->toArray();
        
        return view('admin.pathology.test.create', compact('categories', 'chargeCategories', 'parameters', 'organisations'));
    }

    /**
     * Store a newly created pathology test
     */
    public function store(Request $request)
    {
        // Log incoming data for debugging
        Log::info('Pathology Test Store Request:', $request->all());
        
        $validated = $request->validate([
            'test_name' => 'required|string|max:50',
            'short_name' => 'required|string|max:20',
            'test_type' => 'nullable|string|max:15',
            'pathology_category_id' => 'required|exists:pathology_category,id',
            'sub_category' => 'nullable|string|max:25',
            'method' => 'nullable|string|max:25',
            'report_days' => 'required|integer',
            'charge_category_id' => 'required|exists:charge_categories,id',
            'charge_id' => 'required|exists:charges,id',
            'standard_charge' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'tax_percentage' => 'nullable|numeric',  // Accept but don't store
            'parameters' => 'nullable|array',
            'parameters.*' => 'exists:pathology_parameter,id',
        ]);

        DB::beginTransaction();
        
        try {
            // Create pathology test - only include columns that exist in the table
            $createData = [
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'pathology_category_id' => $validated['pathology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'method' => $validated['method'] ?? '',
                'report_days' => $validated['report_days'],
                'charge_id' => $validated['charge_id'],
                // 'hospital_id' => $user->hospital_id ?? '',
                // 'branch_id' => $user->branch_id ?? '',
            ];
            
            // Only add columns if they exist in the table
            if (Schema::hasColumn('pathology', 'standard_charge')) {
                $createData['standard_charge'] = $validated['standard_charge'];
            }
            
            if (Schema::hasColumn('pathology', 'amount')) {
                $createData['amount'] = $validated['amount'];
            }
            
            if (Schema::hasColumn('pathology', 'charge_category_id')) {
                $createData['charge_category_id'] = $validated['charge_category_id'];
            }
            
            $pathology = Pathology::create($createData);

            Log::info('Pathology test created:', ['id' => $pathology->id]);

            // Add parameters if provided
            if (!empty($validated['parameters'])) {
                foreach ($validated['parameters'] as $parameterId) {
                    PathologyParameterDetail::create([
                        'pathology_id' => $pathology->id,
                        'pathology_parameter_id' => $parameterId,
                    ]);
                }
                Log::info('Parameters added:', ['count' => count($validated['parameters'])]);
            }

            // Create/Update TPA charges for all TPA organizations
            $user = Auth::user();
            $hospitalId = $user->hospital_id ?? null;
            $branchId = $user->branch_id ?? null;
            
            $tpaChargesCreated = 0;
            $tpaChargesUpdated = 0;
            if ($hospitalId) {
                $organisations = Organisation::all();
                Log::info('Creating/Updating TPA charges for pathology test', [
                    'pathology_id' => $pathology->id,
                    'charge_id' => $validated['charge_id'],
                    'organisations_count' => $organisations->count(),
                    'hospital_id' => $hospitalId
                ]);
                
                foreach ($organisations as $organisation) {
                    // Get TPA charge from form
                    $tpaChargeKey = 'tpa_charge_' . $organisation->id;
                    $tpaChargeValue = $request->input($tpaChargeKey);
                    
                    // Only process if user provided a valid value (greater than 0)
                    // Empty number inputs send "0" as string, so we need to check for actual value > 0
                    $floatValue = ($tpaChargeValue !== null && $tpaChargeValue !== '' && is_numeric($tpaChargeValue)) 
                        ? floatval($tpaChargeValue) 
                        : 0;
                    
                    // Only process if value is greater than 0 (reject 0, null, empty, "0")
                    if ($floatValue > 0) {
                        $orgCharge = $floatValue;
                        
                        // Check if TPA charge already exists for this charge_id and org_id
                        $existingTpaCharge = OrganisationsCharge::where('charge_id', $validated['charge_id'])
                            ->where('org_id', $organisation->id)
                            ->first();

                        if ($existingTpaCharge) {
                            // Update existing TPA charge
                            $existingTpaCharge->org_charge = $orgCharge;
                            $existingTpaCharge->save();
                            $tpaChargesUpdated++;
                            Log::info('TPA charge updated:', [
                                'org_id' => $organisation->id,
                                'org_name' => $organisation->organisation_name,
                                'charge_id' => $validated['charge_id'],
                                'org_charge' => $orgCharge
                            ]);
                        } else {
                            // Create new TPA charge
                            $tpaChargeData = [
                                'charge_id' => $validated['charge_id'],
                                'org_id' => $organisation->id,
                                'org_charge' => $orgCharge,
                            ];
                            
                            // Only include hospital_id and branch_id if columns exist
                            if (Schema::hasColumn('organisations_charges', 'hospital_id')) {
                                $tpaChargeData['hospital_id'] = $hospitalId;
                            }
                            if (Schema::hasColumn('organisations_charges', 'branch_id')) {
                                $tpaChargeData['branch_id'] = $branchId ?? '';
                            }
                            
                            OrganisationsCharge::create($tpaChargeData);
                            $tpaChargesCreated++;
                            Log::info('TPA charge created:', [
                                'org_id' => $organisation->id,
                                'org_name' => $organisation->organisation_name,
                                'charge_id' => $validated['charge_id'],
                                'org_charge' => $orgCharge,
                                'hospital_id' => $hospitalId,
                                'branch_id' => $branchId
                            ]);
                        }
                    }
                }
                Log::info('TPA charges creation/update completed', [
                    'total_created' => $tpaChargesCreated,
                    'total_updated' => $tpaChargesUpdated,
                    'total_organisations' => $organisations->count()
                ]);
            } else {
                Log::warning('TPA charges not created: hospital_id missing for user', ['user_id' => $user->id ?? 'unknown']);
            }

            DB::commit();
            
            $successMessage = 'Pathology test created successfully!';
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
            
            return redirect()->route('pathology.test.index')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating pathology test:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating pathology test: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified pathology test
     */
    public function show($id)
    {
        $test = Pathology::with([
            'category', 
            'chargeCategory', 
            'charge', 
            'charge.taxCategory',
            'charge.category',
            'parameters.parameter'
        ])
            ->findOrFail($id);
        
        // Log the loaded data for debugging
        Log::info('Pathology test show - loaded data:', [
            'id' => $test->id,
            'charge_id' => $test->charge_id,
            'charge_category_id' => $test->charge_category_id ?? 'NULL',
            'has_charge' => $test->charge ? 'YES' : 'NO',
            'charge_name' => $test->charge ? $test->charge->name : 'NULL',
            'has_chargeCategory' => $test->chargeCategory ? 'YES' : 'NO',
            'chargeCategory_name' => $test->chargeCategory ? $test->chargeCategory->name : 'NULL',
            'has_charge_category' => $test->charge && $test->charge->category ? 'YES' : 'NO',
            'charge_category_name' => $test->charge && $test->charge->category ? $test->charge->category->name : 'NULL',
            'standard_charge' => $test->standard_charge ?? ($test->charge ? $test->charge->standard_charge : 'NULL'),
            'amount' => $test->amount ?? ($test->charge ? $test->charge->standard_charge : 'NULL'),
            'tax_percentage' => $test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->percentage : 'NULL',
        ]);
        
        // Load TPA charges for this pathology test's charge_id
        $tpaCharges = [];
        if ($test->charge_id) {
            $tpaCharges = OrganisationsCharge::with(['organisation', 'charge.category.chargeType'])
                ->where('charge_id', $test->charge_id)
                ->get();
        }
        
        return view('admin.pathology.test.show', compact('test', 'tpaCharges'));
    }

    /**
     * Show the form for editing the specified pathology test
     */
    public function edit($id)
    {
        $test = Pathology::with(['parameters', 'charge', 'charge.category', 'charge.taxCategory', 'chargeCategory'])->findOrFail($id);
        $categories = PathologyCategory::all();
        $chargeCategories = ChargeCategory::all();
        $organisations = Organisation::all();
        
        // Get charge_category_id from test or from charge relationship
        $chargeCategoryId = $test->charge_category_id ?? ($test->charge ? $test->charge->charge_category_id : null);
        
        // If charge_category_id is not set, try to get it from the charge
        if (!$test->charge_category_id && $test->charge && $test->charge->charge_category_id) {
            // Update the test with charge_category_id if it exists in the charge
            if (Schema::hasColumn('pathology', 'charge_category_id')) {
                $test->charge_category_id = $test->charge->charge_category_id;
                $test->save();
            }
        }
        
        // Load existing TPA charges for this test's charge_id
        $existingTpaCharges = [];
        if ($test->charge_id) {
            $tpaCharges = OrganisationsCharge::where('charge_id', $test->charge_id)->get();
            foreach ($tpaCharges as $tpaCharge) {
                $existingTpaCharges[$tpaCharge->org_id] = $tpaCharge->org_charge;
            }
        }
        
        $parameters = PathologyParameter::with('unitRelation')->get()->map(function($param) {
            $unitData = [];
            if ($param->unitRelation) {
                $unitData = [
                    'id' => $param->unitRelation->id,
                    'unit_name' => $param->unitRelation->unit_name,
                ];
            }
            return [
                'id' => $param->id,
                'parameter_name' => $param->parameter_name,
                'reference_range' => $param->reference_range,
                'unit_id' => $param->unit_id,
                'unit_relation' => $unitData,
            ];
        })->toArray();
        
        $selectedParameters = $test->parameters->pluck('pathology_parameter_id')->toArray();
        
        return view('admin.pathology.test.edit', compact('test', 'categories', 'chargeCategories', 'parameters', 'selectedParameters', 'organisations', 'existingTpaCharges'));
    }

    /**
     * Update the specified pathology test
     */
    public function update(Request $request, $id)
    {
        // Log incoming data for debugging
        Log::info('Pathology Test Update Request:', ['id' => $id, 'data' => $request->all()]);
        
        // Debug TPA charges specifically
        $tpaChargesDebug = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'tpa_charge_') === 0) {
                $tpaChargesDebug[$key] = $value;
            }
        }
        Log::info('TPA Charges from form:', $tpaChargesDebug);
        
        $validated = $request->validate([
            'test_name' => 'required|string|max:50',
            'short_name' => 'required|string|max:20',
            'test_type' => 'nullable|string|max:15',
            'pathology_category_id' => 'required|exists:pathology_category,id',
            'sub_category' => 'nullable|string|max:25',
            'method' => 'nullable|string|max:25',
            'report_days' => 'required|integer',
            'charge_category_id' => 'required|exists:charge_categories,id',
            'charge_id' => 'required|exists:charges,id',
            'standard_charge' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'tax_percentage' => 'nullable|numeric',  // Accept but don't store
            'parameters' => 'nullable|array',
            'parameters.*' => 'exists:pathology_parameter,id',
        ]);

        DB::beginTransaction();
        
        try {
            $pathology = Pathology::findOrFail($id);
            
            // Store old charge_id before updating
            $oldChargeId = $pathology->charge_id;
            
            // Update pathology test - only update columns that exist in the table
            $updateData = [
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'pathology_category_id' => $validated['pathology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'method' => $validated['method'] ?? '',
                'report_days' => $validated['report_days'],
                'charge_id' => $validated['charge_id'],
            ];
            
            // Only add columns if they exist in the table
            if (Schema::hasColumn('pathology', 'standard_charge')) {
                $updateData['standard_charge'] = $validated['standard_charge'];
            }
            
            if (Schema::hasColumn('pathology', 'amount')) {
                $updateData['amount'] = $validated['amount'];
            }
            
            if (Schema::hasColumn('pathology', 'charge_category_id')) {
                $updateData['charge_category_id'] = $validated['charge_category_id'];
            }
            
            $pathology->update($updateData);
            
            // Refresh the model to get updated data and reload relationships
            $pathology->refresh();
            $pathology->load(['charge', 'charge.category', 'charge.taxCategory', 'chargeCategory']);

            Log::info('Pathology test updated:', [
                'id' => $pathology->id,
                'charge_id' => $pathology->charge_id,
                'charge_name' => $pathology->charge ? $pathology->charge->name : 'NULL',
                'charge_category_id' => $pathology->charge_category_id ?? 'NULL',
                'charge_category_name' => $pathology->chargeCategory ? $pathology->chargeCategory->name : ($pathology->charge && $pathology->charge->category ? $pathology->charge->category->name : 'NULL'),
                'standard_charge' => $pathology->standard_charge ?? ($pathology->charge ? $pathology->charge->standard_charge : 'NULL'),
                'amount' => $pathology->amount ?? ($pathology->charge ? $pathology->charge->standard_charge : 'NULL'),
                'tax_percentage' => $pathology->charge && $pathology->charge->taxCategory ? $pathology->charge->taxCategory->percentage : 'NULL',
                'updateData' => $updateData
            ]);

            // Update parameters
            PathologyParameterDetail::where('pathology_id', $pathology->id)->delete();
            
            if (!empty($validated['parameters'])) {
                foreach ($validated['parameters'] as $parameterId) {
                    PathologyParameterDetail::create([
                        'pathology_id' => $pathology->id,
                        'pathology_parameter_id' => $parameterId,
                    ]);
                }
                Log::info('Parameters updated:', ['count' => count($validated['parameters'])]);
            }

            // Handle TPA charges: Delete all existing TPA charges for this charge_id, then create only the ones user provided
            $user = Auth::user();
            $hospitalId = $user->hospital_id ?? null;
            $branchId = $user->branch_id ?? null;
            
            $tpaChargesCreated = 0;
            if ($hospitalId) {
                // Delete all existing TPA charges for this charge_id first
                OrganisationsCharge::where('charge_id', $validated['charge_id'])->delete();
                Log::info('Deleted existing TPA charges for charge_id:', ['charge_id' => $validated['charge_id']]);
                
                $organisations = Organisation::all();
                foreach ($organisations as $organisation) {
                    // Get TPA charge from form
                    $tpaChargeKey = 'tpa_charge_' . $organisation->id;
                    $tpaChargeValue = $request->input($tpaChargeKey);
                    
                    // Only process if user provided a valid value (greater than 0)
                    // Empty number inputs send "0" as string, so we need to check for actual value > 0
                    $floatValue = ($tpaChargeValue !== null && $tpaChargeValue !== '' && is_numeric($tpaChargeValue)) 
                        ? floatval($tpaChargeValue) 
                        : 0;
                    
                    // Only process if value is greater than 0 (reject 0, null, empty, "0")
                    if ($floatValue > 0) {
                        $orgCharge = $floatValue;
                        
                        // Create new TPA charge (we already deleted all existing ones)
                        $tpaChargeData = [
                            'charge_id' => $validated['charge_id'],
                            'org_id' => $organisation->id,
                            'org_charge' => $orgCharge,
                        ];
                        
                        // Only include hospital_id and branch_id if columns exist
                        if (Schema::hasColumn('organisations_charges', 'hospital_id')) {
                            $tpaChargeData['hospital_id'] = $hospitalId;
                        }
                        if (Schema::hasColumn('organisations_charges', 'branch_id')) {
                            $tpaChargeData['branch_id'] = $branchId ?? '';
                        }
                        
                        OrganisationsCharge::create($tpaChargeData);
                        $tpaChargesCreated++;
                        
                        Log::info('TPA charge created:', [
                            'org_id' => $organisation->id,
                            'org_name' => $organisation->organisation_name,
                            'charge_id' => $validated['charge_id'],
                            'org_charge' => $orgCharge
                        ]);
                    } else {
                        Log::info('Skipping TPA charge - no valid value provided:', [
                            'org_id' => $organisation->id,
                            'value' => $tpaChargeValue
                        ]);
                    }
                }
                
                Log::info('TPA charges processing completed:', [
                    'created' => $tpaChargesCreated
                ]);
            }

            DB::commit();
            
            // Reload the pathology test with all relationships to ensure fresh data
            $pathology->refresh();
            
            // Verify charge_id was saved
            Log::info('After update - verifying charge_id:', [
                'pathology_id' => $pathology->id,
                'charge_id_in_db' => $pathology->charge_id,
                'charge_id_in_request' => $validated['charge_id'],
                'match' => $pathology->charge_id == $validated['charge_id'] ? 'YES' : 'NO'
            ]);
            
            // Reload all relationships
            $pathology->load([
                'category', 
                'chargeCategory', 
                'charge', 
                'charge.taxCategory',
                'charge.category',
                'parameters.parameter'
            ]);
            
            // Log final state
            Log::info('Final pathology state after update:', [
                'id' => $pathology->id,
                'charge_id' => $pathology->charge_id,
                'charge_name' => $pathology->charge ? $pathology->charge->name : 'NULL',
                'charge_category_id' => $pathology->charge_category_id ?? ($pathology->charge ? $pathology->charge->charge_category_id : 'NULL'),
                'charge_category_name' => $pathology->chargeCategory ? $pathology->chargeCategory->name : ($pathology->charge && $pathology->charge->category ? $pathology->charge->category->name : 'NULL'),
            ]);
            
            $successMessage = 'Pathology test updated successfully!';
            if ($tpaChargesCreated > 0) {
                $successMessage .= " {$tpaChargesCreated} TPA charge(s) created.";
            }
            
            // Redirect to show page to see the updated data
            return redirect()->route('pathology.test.show', $pathology->id)
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating pathology test:', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating pathology test: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified pathology test
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $pathology = Pathology::findOrFail($id);
            
            // Delete related parameters
            PathologyParameterDetail::where('pathology_id', $pathology->id)->delete();
            
            // Delete pathology test
            $pathology->delete();
            
            DB::commit();
            
            return redirect()->route('pathology.test.index')
                ->with('success', 'Pathology test deleted successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting pathology test: ' . $e->getMessage());
        }
    }

    /**
     * Update TPA charge for a pathology test
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

            // Get the pathology test ID from the charge_id
            $pathology = Pathology::where('charge_id', $organisationCharge->charge_id)->first();
            
            if ($pathology) {
                return redirect()->route('pathology.test.show', $pathology->id)
                    ->with('success', 'TPA charge updated successfully!');
            } else {
                return redirect()->back()
                    ->with('error', 'Pathology test not found for this charge.');
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

