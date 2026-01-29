<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pathology;
use App\Models\PathologyCategory;
use App\Models\PathologyParameter;
use App\Models\PathologyParameterDetail;
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
    public function index(Request $request)
    {

    $perPage = (int) $request->input('perPage', 10);
    if ($perPage <= 0) {
        $perPage = 10;
    }

    $search = $request->input('search');
    $query = Pathology::with(['category'])
        ->orderBy('id', 'desc');

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('test_name', 'like', "%{$search}%")
              ->orWhere('sub_category', 'like', "%{$search}%");
        });
    }

    $tests = $query->paginate($perPage);

    //      return response()->json([
    //     'status' => true,
    //     'message' => 'Staff list fetched successfully',
    //     'data' => $tests
    // ]);
        
       return view('admin.pathology.test.index', compact('tests'));
    }

    /**
     * Show the form for creating a new pathology test
     */
    public function create()
    {
        $categories = PathologyCategory::all();
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
        
        return view('admin.pathology.test.create', compact('categories', 'parameters', 'organisations'));
    }

    /**
     * Store a newly created pathology test
     */
    public function store(Request $request)
    {
        // Log incoming data for debugging
        Log::info('Pathology Test Store Request:', $request->all());
        
        // Debug: Log all TPA-related inputs
        $allTpaInputs = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'tpa_charge') === 0) {
                $allTpaInputs[$key] = $value;
            }
        }
        Log::info('All TPA charge inputs from request:', $allTpaInputs);
        
        $validated = $request->validate([
            'test_name' => 'required|string|max:50',
            'short_name' => 'required|string|max:20',
            'test_type' => 'nullable|string|max:15',
            'pathology_category_id' => 'required|exists:pathology_category,id',
            'sub_category' => 'nullable|string|max:25',
            'method' => 'nullable|string|max:25',
            'report_days' => 'required|integer',
            'standard_charge_ipd' => 'required|numeric|min:0',
            'standard_charge_opd' => 'required|numeric|min:0',
            'parameters' => 'nullable|array',
            'parameters.*' => 'exists:pathology_parameter,id',
        ]);

        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            // Create pathology test
            $createData = [
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'pathology_category_id' => $validated['pathology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'method' => $validated['method'] ?? '',
                'report_days' => $validated['report_days'],
                'standard_charge_ipd' => $validated['standard_charge_ipd'],
                'standard_charge_opd' => $validated['standard_charge_opd'],
            ];
            
            // Add hospital_id and branch_id if columns exist
            if (Schema::hasColumn('pathology', 'hospital_id')) {
                $createData['hospital_id'] = $user->hospital_id ?? '';
            }
            if (Schema::hasColumn('pathology', 'branch_id')) {
                $createData['branch_id'] = $user->branch_id ?? '';
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

            // Create/Update TPA charges for all TPA organizations (IPD and OPD)
            $hospitalId = $user->hospital_id ?? null;
            $branchId = $user->branch_id ?? null;
            
            Log::info('TPA Charge Processing - User Info:', [
                'user_id' => $user->id ?? 'unknown',
                'hospital_id' => $hospitalId,
                'branch_id' => $branchId,
                'has_hospital_id' => !empty($hospitalId)
            ]);
            
            $tpaChargesCreated = 0;
            
            // Process TPA charges even if hospital_id is not set (use empty string)
            $organisations = Organisation::all();
            
            // Debug: Log all TPA charge inputs from form
            $tpaInputs = [];
            foreach ($organisations as $org) {
                $tpaInputs['tpa_charge_ipd_' . $org->id] = $request->input('tpa_charge_ipd_' . $org->id);
                $tpaInputs['tpa_charge_opd_' . $org->id] = $request->input('tpa_charge_opd_' . $org->id);
            }
            Log::info('TPA Charges from form (create):', $tpaInputs);
            
            // Check if pathology_id and charge_type columns exist
            $hasPathologyIdColumn = Schema::hasColumn('organisations_charges', 'pathology_id');
            $hasChargeTypeColumn = Schema::hasColumn('organisations_charges', 'charge_type');
            
            Log::info('Database columns check:', [
                'has_pathology_id' => $hasPathologyIdColumn,
                'has_charge_type' => $hasChargeTypeColumn
            ]);
            
            if (!$hasPathologyIdColumn || !$hasChargeTypeColumn) {
                Log::error('Missing database columns for TPA charges!', [
                    'pathology_id_column' => $hasPathologyIdColumn,
                    'charge_type_column' => $hasChargeTypeColumn,
                    'message' => 'Please run the SQL migration: pathology_changes_step2_update_organisations_charges.sql'
                ]);
            } else {
                Log::info('Creating/Updating TPA charges for pathology test', [
                    'pathology_id' => $pathology->id,
                    'organisations_count' => $organisations->count(),
                    'hospital_id' => $hospitalId
                ]);
                
                foreach ($organisations as $organisation) {
                    // Process IPD TPA charges
                    $tpaChargeIpdKey = 'tpa_charge_ipd_' . $organisation->id;
                    $tpaChargeIpdValue = $request->input($tpaChargeIpdKey);
                    
                    // Check if value exists and is valid (not empty, not null, not "0" as string)
                    if ($tpaChargeIpdValue !== null && $tpaChargeIpdValue !== '' && $tpaChargeIpdValue !== '0') {
                        $floatValue = is_numeric($tpaChargeIpdValue) ? floatval($tpaChargeIpdValue) : 0;
                        if ($floatValue > 0) {
                            try {
                                // Delete any existing IPD charges for this pathology and organization to prevent duplicates
                                OrganisationsCharge::where('pathology_id', $pathology->id)
                                    ->where('org_id', $organisation->id)
                                    ->where('charge_type', 'IPD')
                                    ->delete();
                                
                                // Create new IPD TPA charge
                                $tpaChargeData = [
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'charge_type' => 'IPD',
                                    'org_charge' => $floatValue,
                                ];
                                
                                if (Schema::hasColumn('organisations_charges', 'hospital_id')) {
                                    $tpaChargeData['hospital_id'] = $hospitalId;
                                }
                                if (Schema::hasColumn('organisations_charges', 'branch_id')) {
                                    $tpaChargeData['branch_id'] = $branchId ?? '';
                                }
                                
                                OrganisationsCharge::create($tpaChargeData);
                                $tpaChargesCreated++;
                                Log::info('TPA charge IPD created:', [
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'org_charge' => $floatValue
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Error creating/updating IPD TPA charge:', [
                                    'error' => $e->getMessage(),
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'trace' => $e->getTraceAsString()
                                ]);
                            }
                        }
                    }
                    
                    // Process OPD TPA charges
                    $tpaChargeOpdKey = 'tpa_charge_opd_' . $organisation->id;
                    $tpaChargeOpdValue = $request->input($tpaChargeOpdKey);
                    
                    // Check if value exists and is valid (not empty, not null, not "0" as string)
                    if ($tpaChargeOpdValue !== null && $tpaChargeOpdValue !== '' && $tpaChargeOpdValue !== '0') {
                        $floatValue = is_numeric($tpaChargeOpdValue) ? floatval($tpaChargeOpdValue) : 0;
                        if ($floatValue > 0) {
                            try {
                                // Delete any existing OPD charges for this pathology and organization to prevent duplicates
                                OrganisationsCharge::where('pathology_id', $pathology->id)
                                    ->where('org_id', $organisation->id)
                                    ->where('charge_type', 'OPD')
                                    ->delete();
                                
                                // Create new OPD TPA charge
                                $tpaChargeData = [
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'charge_type' => 'OPD',
                                    'org_charge' => $floatValue,
                                ];
                                
                                if (Schema::hasColumn('organisations_charges', 'hospital_id')) {
                                    $tpaChargeData['hospital_id'] = $hospitalId;
                                }
                                if (Schema::hasColumn('organisations_charges', 'branch_id')) {
                                    $tpaChargeData['branch_id'] = $branchId ?? '';
                                }
                                
                                OrganisationsCharge::create($tpaChargeData);
                                $tpaChargesCreated++;
                                Log::info('TPA charge OPD created:', [
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'org_charge' => $floatValue
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Error creating/updating OPD TPA charge:', [
                                    'error' => $e->getMessage(),
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'trace' => $e->getTraceAsString()
                                ]);
                            }
                        }
                    }
                }
                
                Log::info('TPA charges creation completed', [
                    'total_created' => $tpaChargesCreated,
                    'total_organisations' => $organisations->count()
                ]);
            }

            DB::commit();
            
            $successMessage = 'Pathology test created successfully!';
            if ($tpaChargesCreated > 0) {
                $successMessage .= " {$tpaChargesCreated} TPA charge(s) created.";
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
            'parameters.parameter'
        ])
            ->findOrFail($id);
        
        // Load TPA charges for this pathology test - get latest record for each org_id and charge_type combination
        $tpaCharges = OrganisationsCharge::with(['organisation'])
            ->where('pathology_id', $test->id)
            ->orderBy('id', 'desc') // Get latest records first
            ->get();
        
        // Group by organisation_id and charge_type, then get the latest (first) record for each combination
        $groupedTpaCharges = $tpaCharges->groupBy('org_id')->map(function($charges) {
            // For each organization, get the latest IPD and OPD charges (first one since we ordered by id desc)
            $ipdCharges = $charges->where('charge_type', 'IPD');
            $opdCharges = $charges->where('charge_type', 'OPD');
            
            // Get the latest record (first after ordering by id desc)
            $ipdCharge = $ipdCharges->first();
            $opdCharge = $opdCharges->first();
            
            return [
                'organisation' => $charges->first()->organisation ?? null,
                'ipd_charge' => $ipdCharge,
                'opd_charge' => $opdCharge,
            ];
        });
        
        return view('admin.pathology.test.show', compact('test', 'groupedTpaCharges'));
    }

    /**
     * Show the form for editing the specified pathology test
     */
    public function edit($id)
    {
        $test = Pathology::with(['parameters'])->findOrFail($id);
        $categories = PathologyCategory::all();
        $organisations = Organisation::all();
        
        // Load existing TPA charges for this test (IPD and OPD)
        $existingTpaCharges = ['IPD' => [], 'OPD' => []];
        $tpaCharges = OrganisationsCharge::where('pathology_id', $test->id)->get();
        foreach ($tpaCharges as $tpaCharge) {
            $existingTpaCharges[$tpaCharge->charge_type][$tpaCharge->org_id] = $tpaCharge->org_charge;
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
        
        return view('admin.pathology.test.edit', compact('test', 'categories', 'parameters', 'selectedParameters', 'organisations', 'existingTpaCharges'));
    }

    /**
     * Update the specified pathology test
     */
    public function update(Request $request, $id)
    {
        // Log incoming data for debugging
        Log::info('Pathology Test Update Request:', ['id' => $id, 'data' => $request->all()]);
        
        $validated = $request->validate([
            'test_name' => 'required|string|max:50',
            'short_name' => 'required|string|max:20',
            'test_type' => 'nullable|string|max:15',
            'pathology_category_id' => 'required|exists:pathology_category,id',
            'sub_category' => 'nullable|string|max:25',
            'method' => 'nullable|string|max:25',
            'report_days' => 'required|integer',
            'standard_charge_ipd' => 'required|numeric|min:0',
            'standard_charge_opd' => 'required|numeric|min:0',
            'parameters' => 'nullable|array',
            'parameters.*' => 'exists:pathology_parameter,id',
        ]);

        DB::beginTransaction();
        
        try {
            $pathology = Pathology::findOrFail($id);
            
            // Update pathology test
            $updateData = [
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'pathology_category_id' => $validated['pathology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'method' => $validated['method'] ?? '',
                'report_days' => $validated['report_days'],
                'standard_charge_ipd' => $validated['standard_charge_ipd'],
                'standard_charge_opd' => $validated['standard_charge_opd'],
            ];
            
            $pathology->update($updateData);
            
            Log::info('Pathology test updated:', [
                'id' => $pathology->id,
                'standard_charge_ipd' => $pathology->standard_charge_ipd,
                'standard_charge_opd' => $pathology->standard_charge_opd,
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

            // Handle TPA charges: Delete all existing TPA charges for this pathology, then create only the ones user provided
            $user = Auth::user();
            $hospitalId = $user->hospital_id ?? null;
            $branchId = $user->branch_id ?? null;
            
            Log::info('TPA Charge Processing (update) - User Info:', [
                'user_id' => $user->id ?? 'unknown',
                'hospital_id' => $hospitalId,
                'branch_id' => $branchId
            ]);
            
            $tpaChargesCreated = 0;
            
            // Delete all existing TPA charges for this pathology first
            OrganisationsCharge::where('pathology_id', $pathology->id)->delete();
            Log::info('Deleted existing TPA charges for pathology_id:', ['pathology_id' => $pathology->id]);
            
            $organisations = Organisation::all();
            
            // Debug: Log all TPA charge inputs from form
            $tpaInputs = [];
            foreach ($organisations as $org) {
                $tpaInputs['tpa_charge_ipd_' . $org->id] = $request->input('tpa_charge_ipd_' . $org->id);
                $tpaInputs['tpa_charge_opd_' . $org->id] = $request->input('tpa_charge_opd_' . $org->id);
            }
            Log::info('TPA Charges from form (update):', $tpaInputs);
            
            // Check if pathology_id and charge_type columns exist
            $hasPathologyIdColumn = Schema::hasColumn('organisations_charges', 'pathology_id');
            $hasChargeTypeColumn = Schema::hasColumn('organisations_charges', 'charge_type');
            
            Log::info('Database columns check (update):', [
                'has_pathology_id' => $hasPathologyIdColumn,
                'has_charge_type' => $hasChargeTypeColumn
            ]);
            
            if (!$hasPathologyIdColumn || !$hasChargeTypeColumn) {
                Log::error('Missing database columns for TPA charges (update)!', [
                    'pathology_id_column' => $hasPathologyIdColumn,
                    'charge_type_column' => $hasChargeTypeColumn,
                    'message' => 'Please run the SQL migration: pathology_changes_step2_update_organisations_charges.sql'
                ]);
            } else {
                foreach ($organisations as $organisation) {
                    // Process IPD TPA charges
                    $tpaChargeIpdKey = 'tpa_charge_ipd_' . $organisation->id;
                    $tpaChargeIpdValue = $request->input($tpaChargeIpdKey);
                    
                    // Check if value exists and is valid (not empty, not null, not "0" as string)
                    if ($tpaChargeIpdValue !== null && $tpaChargeIpdValue !== '' && $tpaChargeIpdValue !== '0') {
                        $floatValue = is_numeric($tpaChargeIpdValue) ? floatval($tpaChargeIpdValue) : 0;
                        if ($floatValue > 0) {
                            try {
                                $tpaChargeData = [
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'charge_type' => 'IPD',
                                    'org_charge' => $floatValue,
                                ];
                                
                                if (Schema::hasColumn('organisations_charges', 'hospital_id')) {
                                    $tpaChargeData['hospital_id'] = $hospitalId;
                                }
                                if (Schema::hasColumn('organisations_charges', 'branch_id')) {
                                    $tpaChargeData['branch_id'] = $branchId ?? '';
                                }
                                
                                OrganisationsCharge::create($tpaChargeData);
                                $tpaChargesCreated++;
                                Log::info('TPA charge IPD created (update):', [
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'org_charge' => $floatValue
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Error creating IPD TPA charge (update):', [
                                    'error' => $e->getMessage(),
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'trace' => $e->getTraceAsString()
                                ]);
                            }
                        }
                    }
                    
                    // Process OPD TPA charges
                    $tpaChargeOpdKey = 'tpa_charge_opd_' . $organisation->id;
                    $tpaChargeOpdValue = $request->input($tpaChargeOpdKey);
                    
                    // Check if value exists and is valid (not empty, not null, not "0" as string)
                    if ($tpaChargeOpdValue !== null && $tpaChargeOpdValue !== '' && $tpaChargeOpdValue !== '0') {
                        $floatValue = is_numeric($tpaChargeOpdValue) ? floatval($tpaChargeOpdValue) : 0;
                        if ($floatValue > 0) {
                            try {
                                $tpaChargeData = [
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'charge_type' => 'OPD',
                                    'org_charge' => $floatValue,
                                ];
                                
                                if (Schema::hasColumn('organisations_charges', 'hospital_id')) {
                                    $tpaChargeData['hospital_id'] = $hospitalId;
                                }
                                if (Schema::hasColumn('organisations_charges', 'branch_id')) {
                                    $tpaChargeData['branch_id'] = $branchId ?? '';
                                }
                                
                                OrganisationsCharge::create($tpaChargeData);
                                $tpaChargesCreated++;
                                Log::info('TPA charge OPD created (update):', [
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'org_charge' => $floatValue
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Error creating OPD TPA charge (update):', [
                                    'error' => $e->getMessage(),
                                    'pathology_id' => $pathology->id,
                                    'org_id' => $organisation->id,
                                    'trace' => $e->getTraceAsString()
                                ]);
                            }
                        }
                    }
                }
                
                Log::info('TPA charges processing completed:', [
                    'created' => $tpaChargesCreated
                ]);
            }

            DB::commit();
            
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

            // Get the pathology test ID from pathology_id
            if ($organisationCharge->pathology_id) {
                return redirect()->route('pathology.test.show', $organisationCharge->pathology_id)
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
}

