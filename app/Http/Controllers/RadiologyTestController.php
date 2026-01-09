<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radio;
use App\Models\RadiologyCategory;
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
        $tests = Radio::with(['radiologyCategory'])
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
        $organisations = Organisation::all();
        
        return view('admin.radiology.test.create', compact('categories', 'organisations'));
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
            'standard_charge_ipd' => 'required|numeric|min:0',
            'standard_charge_opd' => 'required|numeric|min:0',
            'parameters' => 'nullable|array',
            'parameters.*' => 'exists:radiology_parameter,id',
        ]);

        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            $hospitalId = $user->hospital_id ?? null;
            $branchId = $user->branch_id ?? null;
            
            $createData = [
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'radiology_category_id' => $validated['radiology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'report_days' => $validated['report_days'],
                'standard_charge_ipd' => $validated['standard_charge_ipd'],
                'standard_charge_opd' => $validated['standard_charge_opd'],
            ];
            
            if ($hospitalId) {
                $createData['hospital_id'] = $hospitalId;
            }
            if ($branchId) {
                $createData['branch_id'] = $branchId ?? '';
            }
            
            $radiology = Radio::create($createData);

            Log::info('Radiology test created:', ['id' => $radiology->id]);

            // Process TPA charges even if hospital_id is not set (use empty string)
            $organisations = Organisation::all();
            
            // Debug: Log all TPA charge inputs from form
            $tpaInputs = [];
            foreach ($organisations as $org) {
                $tpaInputs['tpa_charge_ipd_' . $org->id] = $request->input('tpa_charge_ipd_' . $org->id);
                $tpaInputs['tpa_charge_opd_' . $org->id] = $request->input('tpa_charge_opd_' . $org->id);
            }
            Log::info('TPA Charges from form (create):', $tpaInputs);
            
            // Check if radiology_id and charge_type columns exist
            $hasRadiologyIdColumn = Schema::hasColumn('organisations_charges', 'radiology_id');
            $hasChargeTypeColumn = Schema::hasColumn('organisations_charges', 'charge_type');
            
            Log::info('Database columns check:', [
                'has_radiology_id' => $hasRadiologyIdColumn,
                'has_charge_type' => $hasChargeTypeColumn
            ]);
            
            $tpaChargesCreated = 0;
            if (!$hasRadiologyIdColumn || !$hasChargeTypeColumn) {
                Log::error('Missing database columns for TPA charges!', [
                    'radiology_id_column' => $hasRadiologyIdColumn,
                    'charge_type_column' => $hasChargeTypeColumn,
                    'message' => 'Please run the SQL migration: radiology_changes_step2_update_organisations_charges.sql'
                ]);
            } else {
                Log::info('Creating/Updating TPA charges for radiology test', [
                    'radiology_id' => $radiology->id,
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
                                // Delete any existing IPD charges for this radiology and organization to prevent duplicates
                                OrganisationsCharge::where('radiology_id', $radiology->id)
                                    ->where('org_id', $organisation->id)
                                    ->where('charge_type', 'IPD')
                                    ->delete();
                                
                                // Create new IPD TPA charge
                                $tpaChargeData = [
                                    'radiology_id' => $radiology->id,
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
                                    'radiology_id' => $radiology->id,
                                    'org_id' => $organisation->id,
                                    'org_charge' => $floatValue
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Error creating/updating IPD TPA charge:', [
                                    'error' => $e->getMessage(),
                                    'radiology_id' => $radiology->id,
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
                                // Delete any existing OPD charges for this radiology and organization to prevent duplicates
                                OrganisationsCharge::where('radiology_id', $radiology->id)
                                    ->where('org_id', $organisation->id)
                                    ->where('charge_type', 'OPD')
                                    ->delete();
                                
                                // Create new OPD TPA charge
                                $tpaChargeData = [
                                    'radiology_id' => $radiology->id,
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
                                    'radiology_id' => $radiology->id,
                                    'org_id' => $organisation->id,
                                    'org_charge' => $floatValue
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Error creating/updating OPD TPA charge:', [
                                    'error' => $e->getMessage(),
                                    'radiology_id' => $radiology->id,
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
            
            $successMessage = 'Radiology test created successfully!';
            if ($tpaChargesCreated > 0) {
                $successMessage .= " {$tpaChargesCreated} TPA charge(s) created.";
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
        ])
            ->findOrFail($id);
        
        // Load TPA charges for this radiology test - get latest record for each org_id and charge_type combination
        $tpaCharges = OrganisationsCharge::with(['organisation'])
            ->where('radiology_id', $test->id)
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
        
        return view('admin.radiology.test.show', compact('test', 'groupedTpaCharges'));
    }

    /**
     * Show the form for editing the specified radiology test
     */
    public function edit($id)
    {
        $test = Radio::with(['radiologyCategory'])->findOrFail($id);
        $categories = RadiologyCategory::all();
        $organisations = Organisation::all();
        
        // Load existing TPA charges for this radiology test
        $tpaCharges = OrganisationsCharge::with(['organisation'])
            ->where('radiology_id', $test->id)
            ->orderBy('id', 'desc')
            ->get();
        
        // Group by organisation_id and charge_type
        $existingTpaCharges = [];
        foreach ($tpaCharges->groupBy('org_id') as $orgId => $charges) {
            $ipdCharge = $charges->where('charge_type', 'IPD')->first();
            $opdCharge = $charges->where('charge_type', 'OPD')->first();
            $existingTpaCharges[$orgId] = [
                'ipd' => $ipdCharge ? $ipdCharge->org_charge : null,
                'opd' => $opdCharge ? $opdCharge->org_charge : null,
            ];
        }
        
        return view('admin.radiology.test.edit', compact('test', 'categories', 'organisations', 'existingTpaCharges'));
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
            'standard_charge_ipd' => 'required|numeric|min:0',
            'standard_charge_opd' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            $hospitalId = $user->hospital_id ?? null;
            $branchId = $user->branch_id ?? null;
            
            $radiology = Radio::findOrFail($id);
            
            $updateData = [
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'radiology_category_id' => $validated['radiology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'report_days' => $validated['report_days'],
                'standard_charge_ipd' => $validated['standard_charge_ipd'],
                'standard_charge_opd' => $validated['standard_charge_opd'],
            ];
            
            $radiology->update($updateData);

            // Process TPA charges
            $organisations = Organisation::all();
            
            // Debug: Log all TPA charge inputs from form
            $tpaInputs = [];
            foreach ($organisations as $org) {
                $tpaInputs['tpa_charge_ipd_' . $org->id] = $request->input('tpa_charge_ipd_' . $org->id);
                $tpaInputs['tpa_charge_opd_' . $org->id] = $request->input('tpa_charge_opd_' . $org->id);
            }
            Log::info('TPA Charges from form (update):', $tpaInputs);
            
            // Check if radiology_id and charge_type columns exist
            $hasRadiologyIdColumn = Schema::hasColumn('organisations_charges', 'radiology_id');
            $hasChargeTypeColumn = Schema::hasColumn('organisations_charges', 'charge_type');
            
            $tpaChargesCreated = 0;
            if (!$hasRadiologyIdColumn || !$hasChargeTypeColumn) {
                Log::error('Missing database columns for TPA charges!', [
                    'radiology_id_column' => $hasRadiologyIdColumn,
                    'charge_type_column' => $hasChargeTypeColumn,
                    'message' => 'Please run the SQL migration: radiology_changes_step2_update_organisations_charges.sql'
                ]);
            } else {
                foreach ($organisations as $organisation) {
                    // Process IPD TPA charges
                    $tpaChargeIpdKey = 'tpa_charge_ipd_' . $organisation->id;
                    $tpaChargeIpdValue = $request->input($tpaChargeIpdKey);
                    
                    if ($tpaChargeIpdValue !== null && $tpaChargeIpdValue !== '' && $tpaChargeIpdValue !== '0') {
                        $floatValue = is_numeric($tpaChargeIpdValue) ? floatval($tpaChargeIpdValue) : 0;
                        if ($floatValue > 0) {
                            try {
                                // Delete any existing IPD charges for this radiology and organization to prevent duplicates
                                OrganisationsCharge::where('radiology_id', $radiology->id)
                                    ->where('org_id', $organisation->id)
                                    ->where('charge_type', 'IPD')
                                    ->delete();
                                
                                // Create new IPD TPA charge
                                $tpaChargeData = [
                                    'radiology_id' => $radiology->id,
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
                                    'radiology_id' => $radiology->id,
                                    'org_id' => $organisation->id,
                                    'org_charge' => $floatValue
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Error creating/updating IPD TPA charge:', [
                                    'error' => $e->getMessage(),
                                    'radiology_id' => $radiology->id,
                                    'org_id' => $organisation->id,
                                    'trace' => $e->getTraceAsString()
                                ]);
                            }
                        }
                    }
                    
                    // Process OPD TPA charges
                    $tpaChargeOpdKey = 'tpa_charge_opd_' . $organisation->id;
                    $tpaChargeOpdValue = $request->input($tpaChargeOpdKey);
                    
                    if ($tpaChargeOpdValue !== null && $tpaChargeOpdValue !== '' && $tpaChargeOpdValue !== '0') {
                        $floatValue = is_numeric($tpaChargeOpdValue) ? floatval($tpaChargeOpdValue) : 0;
                        if ($floatValue > 0) {
                            try {
                                // Delete any existing OPD charges for this radiology and organization to prevent duplicates
                                OrganisationsCharge::where('radiology_id', $radiology->id)
                                    ->where('org_id', $organisation->id)
                                    ->where('charge_type', 'OPD')
                                    ->delete();
                                
                                // Create new OPD TPA charge
                                $tpaChargeData = [
                                    'radiology_id' => $radiology->id,
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
                                    'radiology_id' => $radiology->id,
                                    'org_id' => $organisation->id,
                                    'org_charge' => $floatValue
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Error creating/updating OPD TPA charge:', [
                                    'error' => $e->getMessage(),
                                    'radiology_id' => $radiology->id,
                                    'org_id' => $organisation->id,
                                    'trace' => $e->getTraceAsString()
                                ]);
                            }
                        }
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
            Log::error('Error updating radiology test:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
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

            $radiology = Radio::where('id', $organisationCharge->radiology_id)->first();
            
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
}
