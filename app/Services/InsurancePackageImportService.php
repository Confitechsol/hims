<?php

namespace App\Services;

use App\Models\BedGroup;
use App\Models\InsurerRoomMapping;
use App\Models\InsuranceRatePanel;
use App\Models\Package;
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
     * @return array{panels: int, packages: int, room_rates: int, linked: int, skipped: int, errors: array<int, string>}
     */
    public function importFromFile(string $filePath, bool $replacePanelPackages = false, bool $autoLinkHospital = true): array
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $rows = in_array($ext, ['xlsx', 'xls'], true)
            ? $this->rowsFromSpreadsheet($filePath)
            : $this->rowsFromCsv($filePath);

        $stats = [
            'panels' => 0,
            'packages' => 0,
            'room_rates' => 0,
            'linked' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $panelCache = [];
        $replacedPanels = [];

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
                        $panel = $this->upsertPanel($parsed['panel_code'], $parsed['panel_name']);
                        $panelCache[$panelKey] = $panel;
                        $stats['panels']++;

                        if ($replacePanelPackages && !in_array($panel->id, $replacedPanels, true)) {
                            Package::where('package_type', Package::TYPE_INSURANCE)
                                ->where('insurance_rate_panel_id', $panel->id)
                                ->each(function (Package $p) {
                                    PackageRoomRate::where('package_id', $p->id)->delete();
                                    $p->delete();
                                });
                            $replacedPanels[] = $panel->id;
                        }
                    }
                    $panel = $panelCache[$panelKey];

                    $package = $this->upsertInsurancePackage($panel, $parsed);
                    $stats['packages']++;
                    $stats['room_rates'] += $this->syncRoomRates($panel, $package, $parsed);

                    if ($autoLinkHospital && !$package->linked_hospital_package_id) {
                        $linked = $this->hospitalMapping->autoLinkHospitalPackage(
                            $package,
                            $parsed['procedure_name'],
                            $parsed['procedure_code']
                        );
                        if ($linked) {
                            $stats['linked']++;
                        }
                    } elseif ($package->linked_hospital_package_id) {
                        $stats['linked']++;
                    }
                } catch (\Throwable $e) {
                    $stats['errors'][] = 'Row ' . ($lineNum + 1) . ': ' . $e->getMessage();
                    $stats['skipped']++;
                }
            }

            DB::commit();
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
     */
    protected function upsertInsurancePackage(InsuranceRatePanel $panel, array $parsed): Package
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

        $package = $query->first();
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

        if ($package) {
            $package->update($attrs);
        } else {
            $package = Package::create($attrs);
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

        return $package->fresh();
    }

    /**
     * @param  array<string, mixed>  $parsed
     */
    protected function syncRoomRates(InsuranceRatePanel $panel, Package $package, array $parsed): int
    {
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
