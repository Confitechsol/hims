<?php

namespace App\Http\Controllers;

use App\Models\InsuranceRatePanel;
use App\Models\InsuranceTestRate;
use App\Models\Pathology;
use App\Models\Radio;
use App\Services\InsuranceTestMappingService;
use App\Services\InsuranceTestRateBulkMappingService;
use Illuminate\Http\Request;

class InsuranceTestMappingController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(10, (int) $request->input('perPage', 25));
        $status = $request->input('status', 'unmapped');
        $panelId = $request->input('panel_id');
        $testType = $request->input('test_type', 'pathology');

        $query = InsuranceTestRate::with(['panel', 'pathology', 'radiology'])
            ->where('test_type', $testType)
            ->orderBy('insurance_rate_panel_id')
            ->orderBy('insurer_test_name');

        if ($status && $status !== 'all') {
            $query->where('mapping_status', $status);
        }
        if ($panelId) {
            $query->where('insurance_rate_panel_id', $panelId);
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('hospital_system_name', 'like', "%{$search}%")
                    ->orWhere('insurer_test_name', 'like', "%{$search}%");
            });
        }

        $rates = $query->paginate($perPage)->withQueryString();
        $panels = InsuranceRatePanel::orderBy('name')->pluck('name', 'id');
        $pathologyTests = Pathology::orderBy('test_name')->get(['id', 'test_name', 'short_name']);
        $radiologyTests = Radio::orderBy('test_name')->get(['id', 'test_name', 'short_name']);

        $countsQuery = InsuranceTestRate::query()->where('test_type', $testType);
        $counts = [
            'unmapped' => (clone $countsQuery)->where('mapping_status', 'unmapped')->count(),
            'needs_review' => (clone $countsQuery)->where('mapping_status', 'needs_review')->count(),
            'mapped' => (clone $countsQuery)->where('mapping_status', 'mapped')->count(),
        ];

        $bulkMappingService = app(InsuranceTestRateBulkMappingService::class);

        return view('admin.insurance.test_mapping', compact(
            'rates',
            'panels',
            'pathologyTests',
            'radiologyTests',
            'status',
            'testType',
            'counts',
            'perPage',
            'bulkMappingService'
        ));
    }

    public function map(Request $request, InsuranceTestMappingService $mappingService)
    {
        $request->validate([
            'id' => 'required|exists:insurance_test_rates,id',
            'pathology_id' => 'nullable|exists:pathology,id',
            'radiology_id' => 'nullable|exists:radio,id',
        ]);

        $rate = InsuranceTestRate::findOrFail($request->id);
        $mappingService->mapRate(
            $rate,
            $request->input('pathology_id'),
            $request->input('radiology_id')
        );

        return redirect()->back()->with('success', 'Test mapping saved successfully.');
    }

    public function autoMapAll(Request $request, InsuranceTestMappingService $mappingService)
    {
        $request->validate([
            'test_type' => 'required|in:pathology,radiology',
        ]);

        $query = InsuranceTestRate::query()->where('test_type', $request->test_type);

        if ($request->filled('panel_id')) {
            $query->where('insurance_rate_panel_id', $request->panel_id);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('mapping_status', $request->status);
        } else {
            $query->whereIn('mapping_status', ['unmapped', 'needs_review']);
        }

        $updated = 0;
        foreach ($query->get() as $rate) {
            $mappingService->autoMapRate($rate);
            $rate->save();
            $updated++;
        }

        return redirect()->back()->with('success', "Auto-mapping run on {$updated} {$request->test_type} record(s).");
    }

    public function suggestions(Request $request, InsuranceTestMappingService $mappingService)
    {
        $request->validate(['id' => 'required|exists:insurance_test_rates,id']);
        $rate = InsuranceTestRate::findOrFail($request->id);

        $suggestions = $rate->test_type === 'pathology'
            ? $mappingService->suggestPathologyMatches($rate->hospital_system_name, $rate->insurer_test_name)
            : $mappingService->suggestRadiologyMatches($rate->hospital_system_name, $rate->insurer_test_name);

        return response()->json([
            'status' => 200,
            'suggestions' => $suggestions->map(fn ($row) => [
                'id' => $row['test']->id,
                'name' => $row['test']->test_name,
                'score' => round($row['score'], 1),
            ]),
        ]);
    }

    public function matchCount(Request $request, InsuranceTestRateBulkMappingService $bulkService)
    {
        $request->validate(['id' => 'required|exists:insurance_test_rates,id']);
        $rate = InsuranceTestRate::findOrFail($request->id);
        $tests = $bulkService->matchingHospitalTests($rate);

        return response()->json([
            'count' => $tests->count(),
            'hospital_system_name' => $rate->hospital_system_name,
            'tests' => $tests->take(50)->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->test_name,
            ]),
        ]);
    }

    public function bulkMap(Request $request, InsuranceTestRateBulkMappingService $bulkService)
    {
        $request->validate([
            'id' => 'required|exists:insurance_test_rates,id',
            'test_ids' => 'required|array|min:1',
            'test_ids.*' => 'integer',
        ]);

        $rate = InsuranceTestRate::findOrFail($request->id);

        if (!$rate->hospital_system_name) {
            return redirect()->back()->with('error', 'Hospital Name (Excel) is empty — cannot bulk map.');
        }

        $allowedIds = $bulkService->matchingHospitalTests($rate)->pluck('id')->all();
        $selectedIds = array_values(array_intersect(
            array_map('intval', $request->input('test_ids', [])),
            $allowedIds
        ));

        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'No valid tests selected for mapping.');
        }

        $result = $bulkService->bulkMapFromRate($rate, $selectedIds);

        return redirect()->back()->with(
            'success',
            "Mapped {$result['total']} selected {$rate->test_type} test(s) at ₹" . number_format((float) $rate->rate, 2)
            . " ({$result['created']} created, {$result['updated']} updated)."
        );
    }
}
