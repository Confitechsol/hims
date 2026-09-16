<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use App\Models\InsuranceRatePanel;
use App\Models\Pathology;
use App\Models\Radio;
use App\Services\InsuranceTestRateForTestService;
use App\Services\InsuranceTestRateImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class InsuranceRatePanelController extends Controller
{
    public function index()
    {
        $panels = InsuranceRatePanel::with('insuranceCompanies')
            ->withCount([
                'testRates',
                'testRates as pathology_rates_count' => fn ($q) => $q->where('test_type', 'pathology'),
                'testRates as radiology_rates_count' => fn ($q) => $q->where('test_type', 'radiology'),
                'testRates as mapped_rates_count' => fn ($q) => $q->where('mapping_status', 'mapped'),
                'testRates as unmapped_rates_count' => fn ($q) => $q->where('mapping_status', 'unmapped'),
                'testRates as review_rates_count' => fn ($q) => $q->where('mapping_status', 'needs_review'),
                'insuranceCompanies',
            ])
            ->orderBy('name')
            ->get();

        $insuranceCompanies = InsuranceCompany::orderBy('name')->get(['id', 'name', 'code']);
        $activePanels = InsuranceRatePanel::where('is_active', true)->orderBy('name')->get(['id', 'name', 'code']);
        $pathologyTests = Pathology::orderBy('test_name')->get(['id', 'test_name']);
        $radiologyTests = Radio::orderBy('test_name')->get(['id', 'test_name']);

        return view('admin.insurance.rate_panels', compact(
            'panels',
            'insuranceCompanies',
            'activePanels',
            'pathologyTests',
            'radiologyTests'
        ));
    }

    public function updateCompanies(Request $request)
    {
        $request->validate([
            'panel_id' => 'required|exists:insurance_rate_panels,id',
            'insurance_company_ids' => 'nullable|array',
            'insurance_company_ids.*' => 'exists:insurance_companies,id',
        ]);

        $panel = InsuranceRatePanel::findOrFail($request->panel_id);
        $panel->insuranceCompanies()->sync($request->input('insurance_company_ids', []));

        return redirect()->route('insurance.rate-panels')->with(
            'success',
            'Insurance companies updated for ' . $panel->name . '.'
        );
    }

    public function storeRate(Request $request, InsuranceTestRateForTestService $rateService)
    {
        $validated = $request->validate([
            'test_type' => ['required', Rule::in(['pathology', 'radiology'])],
            'panel_id' => ['required', 'exists:insurance_rate_panels,id'],
            'pathology_id' => ['nullable', 'required_if:test_type,pathology', 'exists:pathology,id'],
            'radiology_id' => ['nullable', 'required_if:test_type,radiology', 'exists:radio,id'],
            'rate' => ['required', 'numeric', 'gt:0'],
            'insurer_test_name' => ['nullable', 'string', 'max:255'],
        ]);

        $testType = $validated['test_type'];
        if ($testType === 'pathology') {
            $test = Pathology::findOrFail($validated['pathology_id']);
            $testId = (int) $test->id;
            $hospitalName = (string) $test->test_name;
        } else {
            $test = Radio::findOrFail($validated['radiology_id']);
            $testId = (int) $test->id;
            $hospitalName = (string) $test->test_name;
        }

        $rate = $rateService->upsertSingleRate(
            $testType,
            (int) $validated['panel_id'],
            $testId,
            $hospitalName,
            (float) $validated['rate'],
            $validated['insurer_test_name'] ?? null
        );

        $panelName = $rate->panel?->name ?? 'panel';

        return redirect()->route('insurance.rate-panels')->with(
            'success',
            sprintf(
                'Saved %s rate for "%s" on %s (₹%s).',
                $testType,
                $hospitalName,
                $panelName,
                number_format((float) $rate->rate, 2)
            )
        );
    }

    public function importPathology(Request $request, InsuranceTestRateImportService $importService)
    {
        return $this->runImport($request, $importService, 'pathology', 'PATHOLOGY_INSURANCE TEST RATE.xlsx');
    }

    public function importRadiology(Request $request, InsuranceTestRateImportService $importService)
    {
        return $this->runImport($request, $importService, 'radiology', 'RADIOLOGY_INSURANCE TEST RATE.xlsx');
    }

    protected function runImport(Request $request, InsuranceTestRateImportService $importService, string $testType, string $defaultFilename)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:xlsx,xls|max:10240',
            'use_default_file' => 'nullable|boolean',
            'panel_code' => ['nullable', 'string', Rule::in(['GIPSA', 'ICICI_LOMBARD', 'STAR_HEALTH'])],
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $stored = $request->file('file')->store('imports');
            $filePath = storage_path('app/' . $stored);
        } elseif ($request->boolean('use_default_file')) {
            $filePath = base_path($defaultFilename);
        }

        if (!$filePath || !File::exists($filePath)) {
            return redirect()->back()->with('error', "Please upload an Excel file or ensure {$defaultFilename} exists in the project root.");
        }

        $panelCode = $request->filled('panel_code') ? strtoupper(trim((string) $request->panel_code)) : null;

        try {
            $stats = $importService->importFromFile($filePath, $testType, true, $panelCode);
            $label = ucfirst($testType);
            $scope = $panelCode ? " ({$panelCode} only)" : '';
            $message = sprintf(
                '%s import complete%s: %d panel(s), %d rate row(s) — mapped: %d, needs review: %d, unmapped: %d.',
                $label,
                $scope,
                $stats['panels'],
                $stats['rates'],
                $stats['mapped'],
                $stats['needs_review'],
                $stats['unmapped']
            );

            if ($panelCode && (int) $stats['panels'] === 0) {
                return redirect()->back()->with(
                    'error',
                    "{$label} import: no sheet matched panel {$panelCode}. Check Excel sheet names (GIPSA / ICICI LOMBARD / STAR)."
                );
            }

            return redirect()->route('insurance.rate-panels')->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', ucfirst($testType) . ' import failed: ' . $e->getMessage());
        }
    }
}
