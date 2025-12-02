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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PathologyTestController extends Controller
{
    /**
     * Display a listing of pathology tests
     */
    public function index()
    {
        $tests = Pathology::with(['category', 'chargeCategory', 'charge', 'charge.taxCategory'])
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
        
        return view('admin.pathology.test.create', compact('categories', 'chargeCategories', 'parameters'));
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
            // Create pathology test
            $pathology = Pathology::create([
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'],
                'pathology_category_id' => $validated['pathology_category_id'],
                'sub_category' => $validated['sub_category'],
                'method' => $validated['method'],
                'report_days' => $validated['report_days'],
                'charge_category_id' => $validated['charge_category_id'],
                'charge_id' => $validated['charge_id'],
                'standard_charge' => $validated['standard_charge'],
                'amount' => $validated['amount'],
            ]);

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

            DB::commit();
            
            return redirect()->route('pathology.test.index')
                ->with('success', 'Pathology test created successfully!');
                
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
        $test = Pathology::with(['category', 'chargeCategory', 'charge', 'charge.taxCategory', 'parameters.parameter'])
            ->findOrFail($id);
        
        return view('admin.pathology.test.show', compact('test'));
    }

    /**
     * Show the form for editing the specified pathology test
     */
    public function edit($id)
    {
        $test = Pathology::with('parameters')->findOrFail($id);
        $categories = PathologyCategory::all();
        $chargeCategories = ChargeCategory::all();
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
        
        return view('admin.pathology.test.edit', compact('test', 'categories', 'chargeCategories', 'parameters', 'selectedParameters'));
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
            
            // Update pathology test
            $pathology->update([
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'],
                'pathology_category_id' => $validated['pathology_category_id'],
                'sub_category' => $validated['sub_category'],
                'method' => $validated['method'],
                'report_days' => $validated['report_days'],
                'charge_category_id' => $validated['charge_category_id'],
                'charge_id' => $validated['charge_id'],
                'standard_charge' => $validated['standard_charge'],
                'amount' => $validated['amount'],
            ]);

            Log::info('Pathology test updated:', ['id' => $pathology->id]);

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

            DB::commit();
            
            return redirect()->route('pathology.test.index')
                ->with('success', 'Pathology test updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating pathology test:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
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

