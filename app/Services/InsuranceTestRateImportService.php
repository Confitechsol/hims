<?php

namespace App\Services;

use App\Models\InsuranceCompany;
use App\Models\InsuranceRatePanel;
use App\Models\InsuranceTestRate;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InsuranceTestRateImportService
{
    public function __construct(
        protected InsuranceTestMappingService $mappingService
    ) {}

    /**
     * @param  'pathology'|'radiology'  $testType
     * @return array{panels: int, rates: int, mapped: int, needs_review: int, unmapped: int}
     */
    public function importFromFile(string $filePath, string $testType, bool $replaceExistingForType = true): array
    {
        if (!in_array($testType, ['pathology', 'radiology'], true)) {
            throw new \InvalidArgumentException('testType must be pathology or radiology');
        }

        $spreadsheet = IOFactory::load($filePath);
        $stats = ['panels' => 0, 'rates' => 0, 'mapped' => 0, 'needs_review' => 0, 'unmapped' => 0];

        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $panelConfig = $this->panelConfigForSheet($sheet->getTitle());
            if (!$panelConfig) {
                continue;
            }

            $panel = $this->upsertPanel($panelConfig['code'], $panelConfig['name']);
            $this->linkInsuranceCompanies($panel, $panelConfig['insurance_matchers']);
            $stats['panels']++;

            if ($replaceExistingForType) {
                InsuranceTestRate::where('insurance_rate_panel_id', $panel->id)
                    ->where('test_type', $testType)
                    ->forceDelete();
            }

            $rows = $sheet->toArray(null, true, true, false);
            $headerRowIndex = $this->findHeaderRowIndex($rows);
            if ($headerRowIndex === null) {
                continue;
            }

            for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                $row = $rows[$i] ?? [];
                $parsed = $this->parseRow($row);
                if (!$parsed) {
                    continue;
                }

                $rate = InsuranceTestRate::create([
                    'insurance_rate_panel_id' => $panel->id,
                    'test_type' => $testType,
                    'hospital_system_name' => $parsed['hospital_system_name'],
                    'insurer_test_name' => $parsed['insurer_test_name'],
                    'rate' => $parsed['rate'],
                    'mapping_status' => 'unmapped',
                ]);

                $rate = $this->mappingService->autoMapRate($rate);
                $rate->save();

                $stats['rates']++;
                $stats[$rate->mapping_status === 'mapped' ? 'mapped' : ($rate->mapping_status === 'needs_review' ? 'needs_review' : 'unmapped')]++;
            }
        }

        return $stats;
    }

    protected function panelConfigForSheet(string $sheetTitle): ?array
    {
        $key = Str::upper(trim($sheetTitle));

        return match (true) {
            str_contains($key, 'GIPSA') => [
                'code' => 'GIPSA',
                'name' => 'GIPSA Panel',
                'insurance_matchers' => [
                    'NATIONAL INSURANCE',
                    'NEW INDIA',
                    'ORIENTAL INSURANCE',
                    'UNITED INDIA',
                ],
            ],
            str_contains($key, 'ICICI') => [
                'code' => 'ICICI_LOMBARD',
                'name' => 'ICICI Lombard Panel',
                'insurance_matchers' => ['ICICI'],
            ],
            str_contains($key, 'STAR') => [
                'code' => 'STAR_HEALTH',
                'name' => 'Star Health Panel',
                'insurance_matchers' => ['STAR'],
            ],
            default => null,
        };
    }

    protected function upsertPanel(string $code, string $name): InsuranceRatePanel
    {
        return InsuranceRatePanel::updateOrCreate(
            ['code' => $code],
            ['name' => $name, 'is_active' => true]
        );
    }

    /**
     * @param  array<int, string>  $matchers
     */
    protected function linkInsuranceCompanies(InsuranceRatePanel $panel, array $matchers): void
    {
        $companyIds = InsuranceCompany::all()
            ->filter(function (InsuranceCompany $company) use ($matchers) {
                $name = Str::upper($company->name);
                foreach ($matchers as $matcher) {
                    if (str_contains($name, Str::upper($matcher))) {
                        return true;
                    }
                }

                return false;
            })
            ->pluck('id')
            ->all();

        if (!empty($companyIds)) {
            $panel->insuranceCompanies()->syncWithoutDetaching($companyIds);
        }
    }

    /**
     * @param  array<int, array<int, mixed>>  $rows
     */
    protected function findHeaderRowIndex(array $rows): ?int
    {
        foreach ($rows as $index => $row) {
            $colB = Str::upper(trim((string) ($row[1] ?? '')));
            $colC = Str::upper(trim((string) ($row[2] ?? '')));
            if ($colB === 'TEST NAME' && ($colC === 'RS.' || $colC === 'RS')) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Parse columns A/B/C only (index 0, 1, 2).
     *
     * @param  array<int, mixed>  $row
     */
    protected function parseRow(array $row): ?array
    {
        $hospitalName = $this->cleanText($row[0] ?? null);
        $insurerName = $this->cleanText($row[1] ?? null);
        $rateValue = $row[2] ?? null;

        if ($insurerName === null) {
            return null;
        }

        $rate = $this->cleanRate($rateValue);
        if ($rate === null || $rate <= 0) {
            return null;
        }

        return [
            'hospital_system_name' => $hospitalName,
            'insurer_test_name' => $insurerName,
            'rate' => $rate,
        ];
    }

    protected function cleanText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    protected function cleanRate(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return round((float) $value, 2);
        }
        $cleaned = preg_replace('/[^0-9.]/', '', (string) $value);

        return $cleaned !== '' ? round((float) $cleaned, 2) : null;
    }
}
