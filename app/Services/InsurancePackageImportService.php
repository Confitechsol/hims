<?php

namespace App\Services;

use App\Models\BedGroup;
use App\Models\InsurerRoomMapping;
use App\Models\InsuranceRatePanel;
use App\Models\Package;
use App\Models\PackageCharge;
use App\Models\PackageExclude;
use App\Models\PackageRoomRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InsurancePackageImportService
{
    public function __construct(
        protected InsurancePackageHospitalMappingService $hospitalMapping,
        protected PackageInsuranceRateService $rateService
    ) {
    }

    /**
     * @return array{
     *     panels: int,
     *     packages: int,
     *     inserted: int,
     *     updated: int,
     *     unchanged: int,
     *     deactivated: int,
     *     purged: int,
     *     room_rates: int,
     *     linked: int,
     *     skipped: int,
     *     errors: array<int, string>,
     *     dry_run: bool
     * }
     */
    public function importFromFile(
        string $filePath,
        bool $replacePanelPackages = false,
        bool $autoLinkHospital = false,
        bool $dryRun = false,
        bool $deactivateMissing = false
    ): array {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (in_array($ext, ['xlsx', 'xls'], true) && $this->isGipsaPpnExcel($filePath)) {
            $rows = $this->rowsFromGipsaPpnExcel($filePath);
        } elseif (in_array($ext, ['xlsx', 'xls'], true)) {
            $rows = $this->rowsFromSpreadsheet($filePath);
        } else {
            $rows = $this->rowsFromCsv($filePath);
        }

        $stats = [
            'panels' => 0,
            'packages' => 0,
            'inserted' => 0,
            'updated' => 0,
            'unchanged' => 0,
            'deactivated' => 0,
            'purged' => 0,
            'room_rates' => 0,
            'linked' => 0,
            'skipped' => 0,
            'errors' => [],
            'dry_run' => $dryRun,
        ];

        $panelCache = [];
        $replacedPanels = [];
        /** @var array<int, array<int, string>> $importedCodesByPanel */
        $importedCodesByPanel = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $lineNum => $row) {
                $parsed = $this->parseRow($row);
                if ($parsed === null) {
                    continue;
                }

                try {
                    $panelKey = strtoupper($parsed['panel_code']);
                    if (!isset($panelCache[$panelKey])) {
                        $panel = $dryRun
                            ? (InsuranceRatePanel::where('code', strtoupper(Str::slug($parsed['panel_code'], '_')))->first()
                                ?? new InsuranceRatePanel(['code' => strtoupper(Str::slug($parsed['panel_code'], '_')), 'name' => $parsed['panel_name']]))
                            : $this->upsertPanel($parsed['panel_code'], $parsed['panel_name']);
                        $panelCache[$panelKey] = $panel;
                        $stats['panels']++;

                        if ($replacePanelPackages && !$dryRun && !in_array($panel->id, $replacedPanels, true)) {
                            $stats['purged'] += $this->purgePanelPackages($panel->id);
                            $replacedPanels[] = $panel->id;
                        }
                    }
                    $panel = $panelCache[$panelKey];
                    $panelTrackId = $panel->id ?: $panelKey;

                    if ($parsed['procedure_code'] !== '') {
                        $importedCodesByPanel[$panelTrackId][] = $parsed['procedure_code'];
                    }

                    $result = $this->upsertInsurancePackage($panel, $parsed, $dryRun);
                    $stats['packages']++;
                    $stats[$result['action']]++;
                    if ($result['action'] !== 'unchanged') {
                        $stats['room_rates'] += $this->syncRoomRates($panel, $result['package'], $parsed, $dryRun);
                    }

                    if (!$dryRun && $autoLinkHospital && !$result['package']->linked_hospital_package_id) {
                        $linked = $this->hospitalMapping->autoLinkHospitalPackage(
                            $result['package'],
                            $parsed['procedure_name'],
                            $parsed['procedure_code']
                        );
                        if ($linked) {
                            $stats['linked']++;
                        }
                    } elseif ($result['package']->linked_hospital_package_id) {
                        $stats['linked']++;
                    }
                } catch (\Throwable $e) {
                    $stats['errors'][] = 'Row ' . ($lineNum + 1) . ': ' . $e->getMessage();
                    $stats['skipped']++;
                }
            }

            if ($deactivateMissing) {
                foreach ($panelCache as $panelKey => $panel) {
                    $trackId = $panel->id ?: $panelKey;
                    if (!$panel->id) {
                        continue;
                    }
                    $codes = array_values(array_unique($importedCodesByPanel[$trackId] ?? []));
                    $stats['deactivated'] += $this->deactivatePackagesNotInImport($panel->id, $codes, $dryRun);
                }
            }

            if ($dryRun) {
                DB::rollBack();
            } else {
                DB::commit();
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $stats;
    }

    protected function rowsFromCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \RuntimeException('Cannot open CSV: ' . $filePath);
        }

        $header = null;
        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if ($header === null) {
                $header = array_map(fn ($h) => $this->normalizeHeader($h), $data);
                continue;
            }
            if (count(array_filter($data, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }
            $assoc = [];
            foreach ($header as $i => $key) {
                $assoc[$key] = $data[$i] ?? null;
            }
            $rows[] = $assoc;
        }
        fclose($handle);

        return $rows;
    }

    protected function isGipsaPpnExcel(string $filePath): bool
    {
        $sheet = IOFactory::load($filePath)->getActiveSheet();
        foreach ($sheet->toArray(null, true, true, false) as $row) {
            $colB = trim((string) ($row[1] ?? ''));
            if (stripos($colB, 'NEW PPN CODE') !== false || stripos($colB, 'PPN CODE') !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parse Samaritan GIPSA PPN Excel (header row with NEW PPN CODE + category sections).
     *
     * @return array<int, array<string, mixed>>
     */
    protected function rowsFromGipsaPpnExcel(string $filePath): array
    {
        $sheet = IOFactory::load($filePath)->getActiveSheet();
        $raw = $sheet->toArray(null, true, true, false);
        if (empty($raw)) {
            return [];
        }

        $headerIndex = null;
        foreach ($raw as $i => $row) {
            $colB = trim((string) ($row[1] ?? ''));
            if (stripos($colB, 'NEW PPN CODE') !== false || stripos($colB, 'PPN CODE') !== false) {
                $headerIndex = $i;
                break;
            }
        }
        if ($headerIndex === null) {
            return [];
        }

        $contractReference = '';
        foreach (array_slice($raw, 0, $headerIndex) as $row) {
            $line = trim(implode(' ', array_map(fn ($v) => trim((string) $v), $row)));
            if (stripos($line, 'ROHINI') !== false) {
                $contractReference = $line;
                break;
            }
        }

        $panelCode = 'GIPSA_PPN';
        $panelName = 'GIPSA PPN Samaritan 2022';
        $currentSpeciality = 'CARDIOLOGY';
        $byCode = [];

        for ($i = $headerIndex + 1; $i < count($raw); $i++) {
            $row = $raw[$i] ?? [];
            $colA = trim((string) ($row[0] ?? ''));
            $colB = trim((string) ($row[1] ?? ''));
            $colC = trim((string) ($row[2] ?? ''));
            $general = $this->floatOrNull($row[5] ?? null);
            $semi = $this->floatOrNull($row[6] ?? null);
            $private = $this->floatOrNull($row[7] ?? null);

            if ($colB !== '' && stripos($colB, 'PPN') === 0) {
                $parsed = [
                    'panel_code' => $panelCode,
                    'panel_name' => $panelName,
                    'procedure_code' => $colB,
                    'procedure_name' => $colC !== '' ? $colC : $colB,
                    'speciality' => $currentSpeciality,
                    'package_inclusions' => $this->normalizeLcodes($row[3] ?? ''),
                    'package_exclusions' => $this->normalizeLcodes($row[4] ?? ''),
                    'contract_reference' => $contractReference,
                    'general' => $general,
                    'semi_private' => $semi,
                    'private' => $private,
                ];

                $existing = $byCode[$colB] ?? null;
                if ($existing === null || $this->gipsaRowRateScore($parsed) > $this->gipsaRowRateScore($existing)) {
                    $byCode[$colB] = $parsed;
                }
                continue;
            }

            if ($colC !== '' && $general === null && $semi === null && $private === null && stripos($colC, 'note') === false) {
                $currentSpeciality = $colC;
                continue;
            }

            if ($colA !== '' && !is_numeric($colA) && stripos($colA, 'PPN') !== 0 && stripos($colA, 'note') === false) {
                $currentSpeciality = $colA;
            }
        }

        return array_values($byCode);
    }

    /**
     * @param  array<string, mixed>  $row
     */
    protected function gipsaRowRateScore(array $row): int
    {
        $score = 0;
        foreach (['general', 'semi_private', 'private'] as $key) {
            if (($row[$key] ?? null) !== null && (float) $row[$key] > 0) {
                $score++;
            }
        }

        return $score;
    }

    protected function rowsFromSpreadsheet(string $filePath): array
    {
        $sheet = IOFactory::load($filePath)->getActiveSheet();
        $raw = $sheet->toArray(null, true, true, false);
        if (empty($raw)) {
            return [];
        }

        $header = array_map(fn ($h) => $this->normalizeHeader((string) $h), $raw[0]);
        $rows = [];
        for ($i = 1; $i < count($raw); $i++) {
            $line = $raw[$i] ?? [];
            if (count(array_filter($line, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }
            $assoc = [];
            foreach ($header as $j => $key) {
                $assoc[$key] = $line[$j] ?? null;
            }
            $rows[] = $assoc;
        }

        return $rows;
    }

    protected function normalizeHeader(string $header): string
    {
        $h = Str::lower(trim($header));
        $h = preg_replace('/[^a-z0-9]+/', '_', $h);

        return trim($h, '_');
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>|null
     */
    protected function parseRow(array $row): ?array
    {
        $procedureCode = trim((string) ($row['procedure_code'] ?? $row['insurer_procedure_code'] ?? $row['ppn_code'] ?? ''));
        $procedureName = trim((string) ($row['procedure_name'] ?? $row['name'] ?? ''));
        $panelCode = trim((string) ($row['panel_code'] ?? $row['rate_panel_code'] ?? ''));

        if ($panelCode === '' || ($procedureCode === '' && $procedureName === '')) {
            return null;
        }

        $tiers = $this->parseTierRates($row);

        return [
            'panel_code' => $panelCode,
            'panel_name' => trim((string) ($row['panel_name'] ?? $row['rate_panel_name'] ?? $panelCode)),
            'procedure_code' => $procedureCode,
            'procedure_name' => $procedureName,
            'speciality' => trim((string) ($row['speciality'] ?? $row['department'] ?? '')),
            'max_stay' => $this->intOrNull($row['max_stay'] ?? $row['max_duration'] ?? null),
            'package_inclusions' => $this->normalizeLcodes($row['package_inclusions'] ?? $row['inclusions'] ?? ''),
            'package_exclusions' => $this->normalizeLcodes($row['package_exclusions'] ?? $row['exclusions'] ?? ''),
            'inclusion_notes' => trim((string) ($row['inclusion_notes'] ?? '')),
            'contract_reference' => trim((string) ($row['contract_reference'] ?? '')),
            'hospital_package_name' => trim((string) ($row['hospital_package_name'] ?? '')),
            'tiers' => $tiers,
        ];
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<int, array{code: string, label: string, rate: float}>
     */
    protected function parseTierRates(array $row): array
    {
        $tiers = [];

        for ($i = 1; $i <= 4; $i++) {
            $rate = $this->floatOrNull($row['tier' . $i . '_rate'] ?? $row['rate_tier_' . $i] ?? null);
            if ($rate === null || $rate <= 0) {
                continue;
            }
            $code = strtoupper(trim((string) ($row['tier' . $i . '_code'] ?? $row['rate_tier_' . $i . '_code'] ?? '')));
            $label = trim((string) ($row['tier' . $i . '_label'] ?? $row['rate_tier_' . $i . '_label'] ?? ''));
            if ($code === '') {
                $defaults = ['GEN', 'SEMI', 'PVT', 'DLX'];
                $code = $defaults[$i - 1] ?? 'T' . $i;
            }
            $tiers[] = ['code' => $code, 'label' => $label, 'rate' => $rate];
        }

        // Legacy column names from PDF extract script
        foreach (['general' => 'GEN', 'semi_private' => 'SEMI', 'semi' => 'SEMI', 'private' => 'PVT', 'deluxe' => 'DLX', 'gw' => 'GW', 'shr' => 'SHR', 'dlx' => 'DLX'] as $col => $code) {
            $rate = $this->floatOrNull($row[$col . '_rate'] ?? $row[$col] ?? null);
            if ($rate !== null && $rate > 0) {
                $tiers[] = [
                    'code' => $code,
                    'label' => str_replace('_', ' ', ucfirst($col)),
                    'rate' => $rate,
                ];
            }
        }

        // Deduplicate by code (keep first)
        $seen = [];
        $unique = [];
        foreach ($tiers as $tier) {
            if (isset($seen[$tier['code']])) {
                continue;
            }
            $seen[$tier['code']] = true;
            $unique[] = $tier;
        }

        return $unique;
    }

    /**
     * Remove all insurance packages (and child rows) for one rate panel only.
     */
    public function purgePanelPackages(int $panelId): int
    {
        $packageIds = Package::query()
            ->where('package_type', Package::TYPE_INSURANCE)
            ->where('insurance_rate_panel_id', $panelId)
            ->pluck('id');

        if ($packageIds->isEmpty()) {
            return 0;
        }

        Package::query()
            ->whereIn('linked_hospital_package_id', $packageIds)
            ->update(['linked_hospital_package_id' => null]);

        PackageRoomRate::whereIn('package_id', $packageIds)->delete();
        PackageCharge::whereIn('package_id', $packageIds)->delete();
        PackageExclude::whereIn('package_id', $packageIds)->delete();
        Package::whereIn('id', $packageIds)->delete();

        return $packageIds->count();
    }

    protected function upsertPanel(string $code, string $name): InsuranceRatePanel
    {
        $code = strtoupper(Str::slug($code, '_'));
        if ($code === '') {
            $code = 'PANEL_' . substr(md5($name), 0, 8);
        }

        return InsuranceRatePanel::firstOrCreate(
            ['code' => $code],
            ['name' => $name ?: $code, 'is_active' => true]
        );
    }

    /**
     * @param  array<string, mixed>  $parsed
     * @return array{package: Package, action: 'inserted'|'updated'|'unchanged'}
     */
    protected function upsertInsurancePackage(InsuranceRatePanel $panel, array $parsed, bool $dryRun = false): array
    {
        $name = $parsed['procedure_name'] !== ''
            ? $parsed['procedure_name']
            : $parsed['procedure_code'];

        $query = Package::query()
            ->where('package_type', Package::TYPE_INSURANCE)
            ->where('insurance_rate_panel_id', $panel->id);

        if ($parsed['procedure_code'] !== '') {
            $query->where('insurer_procedure_code', $parsed['procedure_code']);
        } else {
            $query->where('name', $name);
        }

        $package = $query->with('roomRates')->first();
        $minRate = collect($parsed['tiers'])->min('rate') ?? 0;

        $attrs = [
            'name' => $name,
            'package_type' => Package::TYPE_INSURANCE,
            'insurance_rate_panel_id' => $panel->id,
            'insurer_procedure_code' => $parsed['procedure_code'] ?: null,
            'speciality' => $parsed['speciality'] ?: null,
            'package_inclusions' => $parsed['package_inclusions'] ?: null,
            'package_exclusions' => $parsed['package_exclusions'] ?: null,
            'inclusion_notes' => $parsed['inclusion_notes'] ?: null,
            'contract_reference' => $parsed['contract_reference'] ?: null,
            'package_rate' => $minRate > 0 ? $minRate : 0,
            'status' => 'active',
            'is_active' => true,
        ];

        if ($package && $this->packageMatchesParsed($package, $parsed, $attrs)) {
            return ['package' => $package, 'action' => 'unchanged'];
        }

        if ($dryRun) {
            return [
                'package' => $package ?? new Package($attrs),
                'action' => $package ? 'updated' : 'inserted',
            ];
        }

        if ($package) {
            $package->update($attrs);
            $action = 'updated';
        } else {
            $package = Package::create($attrs);
            $action = 'inserted';
        }

        if ($parsed['hospital_package_name'] !== '') {
            $hospital = Package::query()
                ->where(function ($q) {
                    $q->where('package_type', Package::TYPE_HOSPITAL)->orWhereNull('package_type');
                })
                ->where('name', $parsed['hospital_package_name'])
                ->first();
            if ($hospital) {
                $package->update(['linked_hospital_package_id' => $hospital->id]);
            }
        }

        return ['package' => $package->fresh(['roomRates']), 'action' => $action];
    }

    /**
     * @param  array<string, mixed>  $attrs
     * @param  array<string, mixed>  $parsed
     */
    protected function packageMatchesParsed(Package $package, array $parsed, array $attrs): bool
    {
        if (!$package->is_active || ($package->status ?? 'active') !== 'active') {
            return false;
        }

        $fields = ['name', 'speciality', 'package_inclusions', 'package_exclusions', 'contract_reference'];
        foreach ($fields as $field) {
            $existing = trim((string) ($package->{$field} ?? ''));
            $incoming = trim((string) ($attrs[$field] ?? ''));
            if ($existing !== $incoming) {
                return false;
            }
        }

        if (abs((float) $package->package_rate - (float) $attrs['package_rate']) > 0.009) {
            return false;
        }

        $existingTiers = $package->relationLoaded('roomRates')
            ? $package->roomRates
            : PackageRoomRate::where('package_id', $package->id)->get();

        $existingByCode = [];
        foreach ($existingTiers as $tier) {
            $code = strtoupper(trim((string) ($tier->insurer_room_code ?? '')));
            if ($code !== '') {
                $existingByCode[$code] = (float) $tier->rate;
            }
        }

        $incomingByCode = [];
        foreach ($parsed['tiers'] as $tier) {
            $incomingByCode[strtoupper($tier['code'])] = (float) $tier['rate'];
        }

        if (count($existingByCode) !== count($incomingByCode)) {
            return false;
        }

        foreach ($incomingByCode as $code => $rate) {
            if (!isset($existingByCode[$code]) || abs($existingByCode[$code] - $rate) > 0.009) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<int, string>  $importedCodes
     */
    protected function deactivatePackagesNotInImport(int $panelId, array $importedCodes, bool $dryRun = false): int
    {
        $query = Package::query()
            ->where('package_type', Package::TYPE_INSURANCE)
            ->where('insurance_rate_panel_id', $panelId)
            ->where(function ($q) {
                $q->where('is_active', true)->orWhereNull('is_active');
            })
            ->where(function ($q) {
                $q->where('status', 'active')->orWhereNull('status');
            });

        if (!empty($importedCodes)) {
            $query->whereNotIn('insurer_procedure_code', $importedCodes);
        }

        $count = (clone $query)->count();
        if ($count === 0 || $dryRun) {
            return $count;
        }

        $query->update([
            'is_active' => false,
            'status' => 'inactive',
        ]);

        return $count;
    }

    /**
     * @param  array<string, mixed>  $parsed
     */
    protected function syncRoomRates(InsuranceRatePanel $panel, Package $package, array $parsed, bool $dryRun = false): int
    {
        if ($dryRun || !$package->id) {
            return count($parsed['tiers']);
        }

        PackageRoomRate::where('package_id', $package->id)->delete();
        $count = 0;

        foreach ($parsed['tiers'] as $tier) {
            $bedGroupId = InsurerRoomMapping::query()
                ->where('insurance_rate_panel_id', $panel->id)
                ->where('insurer_room_code', $tier['code'])
                ->value('bed_group_id');

            if (!$bedGroupId) {
                $bedGroupId = $this->guessBedGroupByTierOrder($tier['code'], $count);
            }

            if (!$bedGroupId) {
                continue;
            }

            PackageRoomRate::create([
                'package_id' => $package->id,
                'bed_group_id' => (int) $bedGroupId,
                'insurer_room_code' => $tier['code'],
                'label' => $tier['label'] ?: null,
                'rate' => $tier['rate'],
            ]);
            $count++;
        }

        $this->rateService->syncDefaultPackageRate($package->fresh());

        return $count;
    }

    protected function guessBedGroupByTierOrder(string $code, int $tierIndex): ?int
    {
        $groups = BedGroup::orderBy('bed_cost')->orderBy('name')->pluck('id');
        if ($groups->isEmpty()) {
            return null;
        }

        $orderMap = [
            'GEN' => 0, 'GW' => 0, 'A' => 0,
            'SEMI' => 1, 'SHR' => 1, 'B' => 1,
            'PVT' => 2, 'DLX' => 2, 'C' => 2,
            'D' => 3,
        ];
        $idx = $orderMap[strtoupper($code)] ?? $tierIndex;

        return (int) ($groups[$idx] ?? $groups->last());
    }

    protected function normalizeLcodes(mixed $value): string
    {
        $s = strtoupper(trim((string) $value));
        if ($s === '') {
            return '';
        }
        preg_match_all('/L\s*(\d)/', $s, $m);
        if (empty($m[1])) {
            return $s;
        }
        $codes = array_unique($m[1]);
        sort($codes, SORT_NUMERIC);

        return implode(',', array_map(fn ($n) => 'L' . $n, $codes));
    }

    protected function floatOrNull(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        $v = preg_replace('/[^0-9.]/', '', (string) $value);

        return $v === '' ? null : (float) $v;
    }

    protected function intOrNull(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) preg_replace('/\D/', '', (string) $value);
    }
}
