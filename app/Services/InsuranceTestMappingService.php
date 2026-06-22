<?php

namespace App\Services;

use App\Models\InsuranceTestRate;
use App\Models\Pathology;
use App\Models\Radio;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class InsuranceTestMappingService
{
    public function normalizeName(?string $name): string
    {
        if ($name === null) {
            return '';
        }

        $name = Str::upper(trim($name));
        $name = preg_replace('/\s+/', ' ', $name);
        $name = str_replace(['**', '*'], '', $name);

        return trim($name);
    }

    public function suggestPathologyMatches(?string $hospitalName, ?string $insurerName, int $limit = 5): Collection
    {
        return $this->suggestMatches(
            Pathology::query()->orderBy('test_name')->get(),
            'test_name',
            $hospitalName,
            $insurerName,
            $limit
        );
    }

    public function suggestRadiologyMatches(?string $hospitalName, ?string $insurerName, int $limit = 5): Collection
    {
        return $this->suggestMatches(
            Radio::query()->orderBy('test_name')->get(),
            'test_name',
            $hospitalName,
            $insurerName,
            $limit
        );
    }

    protected function suggestMatches(Collection $tests, string $nameField, ?string $hospitalName, ?string $insurerName, int $limit): Collection
    {
        $needles = array_filter([
            $this->normalizeName($hospitalName),
            $this->normalizeName($insurerName),
        ]);

        if (empty($needles)) {
            return collect();
        }

        return $tests->map(function ($test) use ($nameField, $needles) {
            $haystack = $this->normalizeName($test->{$nameField});
            $shortName = $this->normalizeName($test->short_name ?? '');
            $score = 0.0;

            foreach ($needles as $needle) {
                if ($needle === '') {
                    continue;
                }
                if ($haystack === $needle || $shortName === $needle) {
                    $score = max($score, 100.0);
                } elseif (str_contains($haystack, $needle) || str_contains($needle, $haystack)) {
                    $score = max($score, 85.0);
                } else {
                    similar_text($haystack, $needle, $percent);
                    $score = max($score, (float) $percent);
                    if ($shortName !== '') {
                        similar_text($shortName, $needle, $shortPercent);
                        $score = max($score, (float) $shortPercent);
                    }
                }
            }

            return ['test' => $test, 'score' => $score];
        })
            ->filter(fn ($row) => $row['score'] >= 55)
            ->sortByDesc('score')
            ->take($limit)
            ->values();
    }

    public function autoMapRate(InsuranceTestRate $rate, float $autoMapThreshold = 92.0, float $reviewThreshold = 75.0): InsuranceTestRate
    {
        $suggestions = $rate->test_type === 'pathology'
            ? $this->suggestPathologyMatches($rate->hospital_system_name, $rate->insurer_test_name, 1)
            : $this->suggestRadiologyMatches($rate->hospital_system_name, $rate->insurer_test_name, 1);

        if ($suggestions->isEmpty()) {
            $rate->mapping_status = 'unmapped';
            $rate->pathology_id = null;
            $rate->radiology_id = null;

            return $rate;
        }

        $best = $suggestions->first();
        $test = $best['test'];
        $score = $best['score'];

        if ($rate->test_type === 'pathology') {
            $rate->pathology_id = $test->id;
            $rate->radiology_id = null;
        } else {
            $rate->radiology_id = $test->id;
            $rate->pathology_id = null;
        }

        if ($score >= $autoMapThreshold) {
            $rate->mapping_status = 'mapped';
        } elseif ($score >= $reviewThreshold) {
            $rate->mapping_status = 'needs_review';
        } else {
            $rate->mapping_status = 'unmapped';
            $rate->pathology_id = null;
            $rate->radiology_id = null;
        }

        return $rate;
    }

    public function mapRate(InsuranceTestRate $rate, ?int $pathologyId, ?int $radiologyId): InsuranceTestRate
    {
        if ($rate->test_type === 'pathology') {
            $rate->pathology_id = $pathologyId;
            $rate->radiology_id = null;
        } else {
            $rate->radiology_id = $radiologyId;
            $rate->pathology_id = null;
        }

        $rate->mapping_status = ($rate->pathology_id || $rate->radiology_id) ? 'mapped' : 'unmapped';
        $rate->save();

        return $rate;
    }
}
