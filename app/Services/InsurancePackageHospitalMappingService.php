<?php

namespace App\Services;

use App\Models\Package;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class InsurancePackageHospitalMappingService
{
    /** @var list<string> */
    protected const PROCEDURE_KEYWORDS = [
        'TONSILLECTOMY', 'ADENOIDECTOMY', 'APPENDECTOMY', 'CHOLECYSTECTOMY',
        'ANGIOGRAPHY', 'ANGIOPLASTY', 'CABG', 'HERNIOPLASTY', 'HYSTERECTOMY',
        'CATARACT', 'MASTOIDECTOMY', 'FESS', 'MYRINGOTOMY', 'HAEMORRHOIDECTOMY',
        'HEMORRHOIDECTOMY', 'DELIVERY', 'LSCS', 'TKR', 'KNEE', 'CIRCUMCISION',
        'LAPAROSCOPY', 'PROSTATECTOMY', 'NEPHRECTOMY', 'THYROIDECTOMY',
    ];

    public function normalizeName(?string $name): string
    {
        if ($name === null) {
            return '';
        }

        $name = Str::upper(trim($name));
        $name = preg_replace('/\s+/', ' ', $name);
        $name = preg_replace('/[^A-Z0-9 ]+/', ' ', $name);

        return trim($name);
    }

    /**
     * @return list<string>
     */
    public function searchTerms(?string $procedureName, ?string $procedureCode = null): array
    {
        $terms = [];
        $upperName = Str::upper(trim((string) $procedureName));
        $normalizedName = $this->normalizeName($procedureName);

        if ($normalizedName !== '' && !preg_match('/^PPN [A-Z]+ \d/', $normalizedName)) {
            $terms[] = $normalizedName;
        }

        foreach (self::PROCEDURE_KEYWORDS as $keyword) {
            if ($upperName !== '' && str_contains($upperName, $keyword)) {
                $terms[] = $keyword;
            }
        }

        $code = $this->normalizeName($procedureCode);
        if ($code !== '') {
            $terms[] = $code;
        }

        return array_values(array_unique(array_filter($terms)));
    }

    /**
     * @return Collection<int, array{package: Package, score: float}>
     */
    public function suggestHospitalPackages(?string $procedureName, ?string $procedureCode = null, int $limit = 5): Collection
    {
        $needles = $this->searchTerms($procedureName, $procedureCode);
        $normalizedCode = $this->normalizeName($procedureCode);

        if (empty($needles) && $normalizedCode === '') {
            return collect();
        }

        $hospitalPackages = Package::query()
            ->where(function ($q) {
                $q->where('package_type', Package::TYPE_HOSPITAL)
                    ->orWhereNull('package_type');
            })
            ->orderBy('name')
            ->get();

        return $hospitalPackages->map(function (Package $pkg) use ($needles, $procedureCode) {
            $haystack = $this->normalizeName($pkg->name);
            $score = 0.0;

            $normalizedCode = $this->normalizeName($procedureCode);
            if ($normalizedCode !== '' && str_contains($haystack, $normalizedCode)) {
                $score = max($score, 96.0);
            }

            if ($score === 0.0 && empty($needles)) {
                return ['package' => $pkg, 'score' => 0.0];
            }

            foreach ($needles as $needle) {
                if ($needle === '') {
                    continue;
                }
                if ($haystack === $needle) {
                    $score = max($score, 100.0);
                } elseif (strlen($needle) >= 6 && str_contains($haystack, $needle)) {
                    $score = max($score, 92.0);
                } elseif (str_contains($haystack, $needle) || str_contains($needle, $haystack)) {
                    $score = max($score, 88.0);
                } else {
                    similar_text($haystack, $needle, $percent);
                    $score = max($score, (float) $percent);
                }
            }

            return ['package' => $pkg, 'score' => $score];
        })
            ->filter(fn ($row) => $row['score'] >= 55.0)
            ->sortByDesc('score')
            ->take($limit)
            ->values();
    }

    public function autoLinkHospitalPackage(Package $insurancePackage, ?string $procedureName, ?string $procedureCode = null, float $minScore = 72.0): ?Package
    {
        $best = $this->suggestHospitalPackages($procedureName, $procedureCode, 1)->first();
        if (!$best || $best['score'] < $minScore) {
            return null;
        }

        $insurancePackage->linked_hospital_package_id = $best['package']->id;
        $insurancePackage->save();

        return $best['package'];
    }
}
