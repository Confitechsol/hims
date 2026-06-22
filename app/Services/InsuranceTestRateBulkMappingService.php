<?php

namespace App\Services;

use App\Models\InsuranceTestRate;
use App\Models\Pathology;
use App\Models\Radio;
use Illuminate\Support\Collection;

class InsuranceTestRateBulkMappingService
{
    public function __construct(
        protected InsuranceTestMappingService $mappingService
    ) {}

    /**
     * Hospital tests whose name matches the Excel "Hospital Name" group label.
     */
    public function matchingHospitalTests(InsuranceTestRate $rate): Collection
    {
        $pattern = $this->mappingService->normalizeName($rate->hospital_system_name);
        if ($pattern === '') {
            return collect();
        }

        if ($rate->test_type === 'pathology') {
            return Pathology::query()
                ->where(function ($query) use ($pattern) {
                    $query->whereRaw('UPPER(test_name) LIKE ?', [$pattern . '%'])
                        ->orWhereRaw('UPPER(short_name) LIKE ?', [$pattern . '%']);
                })
                ->orderBy('test_name')
                ->get();
        }

        return Radio::query()
            ->where(function ($query) use ($pattern) {
                $query->whereRaw('UPPER(test_name) LIKE ?', [$pattern . '%'])
                    ->orWhereRaw('UPPER(short_name) LIKE ?', [$pattern . '%']);
            })
            ->orderBy('test_name')
            ->get();
    }

    public function matchCount(InsuranceTestRate $rate): int
    {
        return $this->matchingHospitalTests($rate)->count();
    }

    /**
     * Create/update one insurance_test_rates row per hospital test (same panel, rate, insurer name).
     *
     * @param  array<int>|null  $testIds  Pathology or radiology IDs; null = all matching tests
     * @return array{created: int, updated: int, total: int}
     */
    public function bulkMapFromRate(InsuranceTestRate $rate, ?array $testIds = null): array
    {
        $tests = $this->matchingHospitalTests($rate);
        if ($testIds !== null) {
            $testIds = array_map('intval', $testIds);
            $tests = $tests->whereIn('id', $testIds)->values();
        }

        if ($tests->isEmpty()) {
            return ['created' => 0, 'updated' => 0, 'total' => 0];
        }

        $created = 0;
        $updated = 0;

        foreach ($tests as $test) {
            $attributes = [
                'insurance_rate_panel_id' => $rate->insurance_rate_panel_id,
                'test_type' => $rate->test_type,
            ];

            if ($rate->test_type === 'pathology') {
                $attributes['pathology_id'] = $test->id;
                $attributes['radiology_id'] = null;
            } else {
                $attributes['radiology_id'] = $test->id;
                $attributes['pathology_id'] = null;
            }

            $existing = InsuranceTestRate::withTrashed()
                ->where($attributes)
                ->first();

            $values = [
                'hospital_system_name' => $rate->hospital_system_name,
                'insurer_test_name' => $rate->insurer_test_name,
                'rate' => $rate->rate,
                'mapping_status' => 'mapped',
            ];

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }
                $existing->update($values);
                $updated++;
            } else {
                InsuranceTestRate::create(array_merge($attributes, $values));
                $created++;
            }
        }

        $rate->pathology_id = null;
        $rate->radiology_id = null;
        $rate->mapping_status = 'mapped';
        $rate->save();

        return [
            'created' => $created,
            'updated' => $updated,
            'total' => $tests->count(),
        ];
    }
}
