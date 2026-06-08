<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radio;
use App\Models\RadiologyCategory;
use App\Services\InsuranceTestRateForTestService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RadiologyTestController extends Controller
{
    public function __construct(
        protected InsuranceTestRateForTestService $insuranceTestRateService
    ) {}

    /**
     * Display a listing of radiology tests
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        if ($perPage <= 0) {
            $perPage = 10;
        }

        $search = $request->input('search');
        $query = Radio::with(['radiologyCategory'])
            ->orderBy('id', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('test_name', 'like', "%{$search}%");
            });
        }
        $tests = $query->paginate($perPage);

        return view('admin.radiology.test.index', compact('tests'));
    }

    /**
     * Show the form for creating a new radiology test
     */
    public function create()
    {
        $categories = RadiologyCategory::all();

        return view('admin.radiology.test.create', compact('categories'));
    }

    /**
     * Store a newly created radiology test
     */
    public function store(Request $request)
    {
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

            DB::commit();

            return redirect()->route('radiology.test.edit', $radiology->id)
                ->with('success', 'Radiology test created. Add insurance panel rates below, then save.');
        } catch (\Exception $e) {
            DB::rollBack();

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
        ])->findOrFail($id);

        $panelRates = $this->insuranceTestRateService->getPanelsWithRatesForRadiology($test->id);

        return view('admin.radiology.test.show', compact('test', 'panelRates'));
    }

    /**
     * Show the form for editing the specified radiology test
     */
    public function edit($id)
    {
        $test = Radio::with(['radiologyCategory'])->findOrFail($id);
        $categories = RadiologyCategory::all();
        $panelRates = $this->insuranceTestRateService->getPanelsWithRatesForRadiology($test->id);

        return view('admin.radiology.test.edit', compact('test', 'categories', 'panelRates'));
    }

    /**
     * Update the specified radiology test
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'test_name' => 'required|string|max:50',
            'short_name' => 'required|string|max:20',
            'test_type' => 'nullable|string|max:15',
            'radiology_category_id' => 'required|exists:radiology_category,id',
            'sub_category' => 'nullable|string|max:25',
            'report_days' => 'required|integer',
            'standard_charge_ipd' => 'required|numeric|min:0',
            'standard_charge_opd' => 'required|numeric|min:0',
            'insurance_rate' => 'nullable|array',
            'insurance_rate.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $radiology = Radio::findOrFail($id);

            $radiology->update([
                'test_name' => $validated['test_name'],
                'short_name' => $validated['short_name'],
                'test_type' => $validated['test_type'] ?? '',
                'radiology_category_id' => $validated['radiology_category_id'],
                'sub_category' => $validated['sub_category'] ?? '',
                'report_days' => $validated['report_days'],
                'standard_charge_ipd' => $validated['standard_charge_ipd'],
                'standard_charge_opd' => $validated['standard_charge_opd'],
            ]);

            $ratesSaved = $this->insuranceTestRateService->syncRadiologyRates(
                $radiology->id,
                $radiology->test_name,
                $request->input('insurance_rate', [])
            );

            DB::commit();

            $successMessage = 'Radiology test updated successfully!';
            if ($ratesSaved > 0) {
                $successMessage .= " {$ratesSaved} insurance panel rate(s) saved.";
            }

            return redirect()->route('radiology.test.show', $radiology->id)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

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
}
