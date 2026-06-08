<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use App\Models\InsuranceRatePanel;
use App\Models\InsuranceTestRate;
use App\Services\InsuranceTestRateImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

        return view('admin.insurance.rate_panels', compact('panels', 'insuranceCompanies'));
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

        try {
            $stats = $importService->importFromFile($filePath, $testType, true);
            $label = ucfirst($testType);
            $message = sprintf(
                '%s import complete: %d panel(s), %d rate row(s) — mapped: %d, needs review: %d, unmapped: %d.',
                $label,
                $stats['panels'],
                $stats['rates'],
                $stats['mapped'],
                $stats['needs_review'],
                $stats['unmapped']
            );

            return redirect()->route('insurance.rate-panels')->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', ucfirst($testType) . ' import failed: ' . $e->getMessage());
        }
    }
}
