<?php

namespace App\Services;

use App\Models\InsuranceRatePanel;
use App\Models\InsuranceTestRate;
use Illuminate\Support\Collection;

class InsuranceTestRateForTestService
{
    /**
     * @return Collection<int, array{panel: InsuranceRatePanel, rate: InsuranceTestRate|null}>
     */
    public function getPanelsWithRatesForPathology(int $pathologyId): Collection
    {
        return $this->getPanelsWithRates('pathology', $pathologyId);
    }

    /**
     * @return Collection<int, array{panel: InsuranceRatePanel, rate: InsuranceTestRate|null}>
     */
    public function getPanelsWithRatesForRadiology(int $radiologyId): Collection
    {
        return $this->getPanelsWithRates('radiology', $radiologyId);
    }

    /**
     * @param  array<int, string|float|null>  $panelRates  panel_id => rate (empty removes)
     */
    public function syncPathologyRates(int $pathologyId, string $testName, array $panelRates): int
    {
        return $this->syncRates('pathology', $pathologyId, $testName, $panelRates, 'pathology_id');
    }

    /**
     * @param  array<int, string|float|null>  $panelRates  panel_id => rate (empty removes)
     */
    public function syncRadiologyRates(int $radiologyId, string $testName, array $panelRates): int
    {
        return $this->syncRates('radiology', $radiologyId, $testName, $panelRates, 'radiology_id');
    }

    /**
     * @return Collection<int, array{panel: InsuranceRatePanel, rate: InsuranceTestRate|null}>
     */
    protected function getPanelsWithRates(string $testType, int $testId): Collection
    {
        $panels = InsuranceRatePanel::where('is_active', true)
            ->orderBy('name')
            ->get();

        $testIdColumn = $testType === 'pathology' ? 'pathology_id' : 'radiology_id';

        $existingRates = InsuranceTestRate::query()
            ->where('test_type', $testType)
            ->where($testIdColumn, $testId)
            ->orderByDesc('id')
            ->get()
            ->unique('insurance_rate_panel_id')
            ->keyBy('insurance_rate_panel_id');

        return $panels->map(function (InsuranceRatePanel $panel) use ($existingRates) {
            return [
                'panel' => $panel,
                'rate' => $existingRates->get($panel->id),
            ];
        });
    }

    /**
     * @param  array<int, string|float|null>  $panelRates
     */
    protected function syncRates(
        string $testType,
        int $testId,
        string $testName,
        array $panelRates,
        string $testIdColumn
    ): int {
        $saved = 0;
        $activePanelIds = InsuranceRatePanel::where('is_active', true)->pluck('id');

        foreach ($activePanelIds as $panelId) {
            $raw = $panelRates[$panelId] ?? $panelRates[(string) $panelId] ?? null;
            $hasValue = $raw !== null && $raw !== '' && is_numeric($raw) && (float) $raw > 0;

            $existing = InsuranceTestRate::query()
                ->where('insurance_rate_panel_id', $panelId)
                ->where('test_type', $testType)
                ->where($testIdColumn, $testId)
                ->orderByDesc('id')
                ->first();

            if (!$hasValue) {
                if ($existing) {
                    $existing->delete();
                }
                continue;
            }

            $rateValue = round((float) $raw, 2);
            $data = [
                'insurance_rate_panel_id' => $panelId,
                'test_type' => $testType,
                $testIdColumn => $testId,
                'hospital_system_name' => $testName,
                'insurer_test_name' => $existing?->insurer_test_name ?: $testName,
                'rate' => $rateValue,
                'mapping_status' => 'mapped',
            ];

            if ($existing) {
                $existing->update($data);
            } else {
                InsuranceTestRate::create($data);
            }

            $saved++;
        }

        return $saved;
    }
}
