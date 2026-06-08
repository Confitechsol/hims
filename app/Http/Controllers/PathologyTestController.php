<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pathology;
use App\Models\PathologyCategory;
use App\Models\PathologyParameter;
use App\Models\PathologyParameterDetail;
use App\Services\InsuranceTestRateForTestService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PathologyTestController extends Controller
{
    public function __construct(
        protected InsuranceTestRateForTestService $insuranceTestRateService
    ) {}

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

        return view('admin.pathology.test.index', compact('tests'));
    }

    /**
     * Show the form for creating a new pathology test
     */
    public function create()
    {
        $categories = PathologyCategory::all();
        $parameters = PathologyParameter::with('unitRelation')->get()->map(function ($param) {
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

        return view('admin.pathology.test.create', compact('categories', 'parameters'));
    }

    /**
     * Store a newly created pathology test
     */
    public function store(Request $request)
    {
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

            if (Schema::hasColumn('pathology', 'hospital_id')) {
                $createData['hospital_id'] = $user->hospital_id ?? '';
            }
            if (Schema::hasColumn('pathology', 'branch_id')) {
                $createData['branch_id'] = $user->branch_id ?? '';
            }

            $pathology = Pathology::create($createData);

            if (!empty($validated['parameters'])) {
                foreach ($validated['parameters'] as $parameterId) {
                    PathologyParameterDetail::create([
                        'pathology_id' => $pathology->id,
                        'pathology_parameter_id' => $parameterId,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('pathology.test.edit', $pathology->id)
                ->with('success', 'Pathology test created. Add insurance panel rates below, then save.');
        } catch (\Exception $e) {
            DB::rollBack();

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
            'parameters.parameter',
        ])->findOrFail($id);

        $panelRates = $this->insuranceTestRateService->getPanelsWithRatesForPathology($test->id);

        return view('admin.pathology.test.show', compact('test', 'panelRates'));
    }

    /**
     * Show the form for editing the specified pathology test
     */
    public function edit($id)
    {
        $test = Pathology::with(['parameters'])->findOrFail($id);
        $categories = PathologyCategory::all();
        $panelRates = $this->insuranceTestRateService->getPanelsWithRatesForPathology($test->id);

        $parameters = PathologyParameter::with('unitRelation')->get()->map(function ($param) {
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

        return view('admin.pathology.test.edit', compact(
            'test',
            'categories',
            'parameters',
            'selectedParameters',
            'panelRates'
        ));
    }

    /**
     * Update the specified pathology test
     */
    public function update(Request $request, $id)
    {
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
            'insurance_rate' => 'nullable|array',
            'insurance_rate.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $pathology = Pathology::findOrFail($id);

            $pathology->update([
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'pathology_category_id' => $validated['pathology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'method' => $validated['method'] ?? '',
                'report_days' => $validated['report_days'],
                'standard_charge_ipd' => $validated['standard_charge_ipd'],
                'standard_charge_opd' => $validated['standard_charge_opd'],
            ]);

            PathologyParameterDetail::where('pathology_id', $pathology->id)->delete();

            if (!empty($validated['parameters'])) {
                foreach ($validated['parameters'] as $parameterId) {
                    PathologyParameterDetail::create([
                        'pathology_id' => $pathology->id,
                        'pathology_parameter_id' => $parameterId,
                    ]);
                }
            }

            $ratesSaved = $this->insuranceTestRateService->syncPathologyRates(
                $pathology->id,
                $pathology->test_name,
                $request->input('insurance_rate', [])
            );

            DB::commit();

            $successMessage = 'Pathology test updated successfully!';
            if ($ratesSaved > 0) {
                $successMessage .= " {$ratesSaved} insurance panel rate(s) saved.";
            }

            return redirect()->route('pathology.test.show', $pathology->id)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

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

            PathologyParameterDetail::where('pathology_id', $pathology->id)->delete();

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
}
