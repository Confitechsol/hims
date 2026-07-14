<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function sqlStr(?string $v): string
{
    if ($v === null) {
        return 'NULL';
    }

    return "'" . str_replace(["\\", "'"], ["\\\\", "''"], $v) . "'";
}

$out = dirname(__DIR__) . '/database/sql/tpa_insurance_seed_data.sql';
$lines = [];
$lines[] = '-- TPA & Insurance Companies seed data (from TPA LIST.xlsx / InsuranceAndTpaSeeder)';
$lines[] = '-- Run AFTER database/sql/insurance_implementation.sql';
$lines[] = '-- Safe to re-run: uses INSERT IGNORE on unique code columns';
$lines[] = '';
$lines[] = 'SET NAMES utf8mb4;';
$lines[] = '';

$ins = App\Models\InsuranceCompany::orderBy('id')->get();
$lines[] = '-- Insurance companies (' . $ins->count() . ')';
foreach ($ins as $row) {
    $lines[] = 'INSERT IGNORE INTO `insurance_companies` (`code`, `name`, `created_at`, `updated_at`) VALUES ('
        . sqlStr($row->code) . ', ' . sqlStr($row->name) . ', NOW(), NOW());';
}
$lines[] = '';

$tpas = App\Models\Organisation::orderBy('id')->get();
$lines[] = '-- TPAs / organisations (' . $tpas->count() . ')';
foreach ($tpas as $row) {
    $insCode = $row->insuranceCompany?->code;
    $insSub = $insCode
        ? '(SELECT `id` FROM `insurance_companies` WHERE `code` = ' . sqlStr($insCode) . ' LIMIT 1)'
        : 'NULL';

    $hospitalId = $row->hospital_id ?: '';
    $branchId = $row->branch_id ?: '';

    $lines[] = 'INSERT IGNORE INTO `organisation` (`hospital_id`, `branch_id`, `insurance_company_id`, `organisation_name`, `code`, `contact_no`, `address`, `contact_person_name`, `contact_person_phone`, `poilicy_no`, `e_card_no`, `e_card_upload`) VALUES ('
        . sqlStr($hospitalId) . ', '
        . sqlStr($branchId) . ', '
        . $insSub . ', '
        . sqlStr($row->organisation_name) . ', '
        . sqlStr($row->code) . ', '
        . sqlStr($row->contact_no ?: '0000000000') . ', '
        . sqlStr($row->address ?: 'N/A') . ', '
        . sqlStr($row->contact_person_name ?: 'N/A') . ', '
        . sqlStr($row->contact_person_phone ?: '0000000001') . ', '
        . sqlStr($row->poilicy_no ?: 'N/A') . ', '
        . sqlStr($row->e_card_no ?: 'N/A') . ', '
        . sqlStr($row->e_card_upload ?: '') . ');';
}
$lines[] = '';

$pivot = DB::table('insurance_company_organisation as ico')
    ->join('organisation as o', 'o.id', '=', 'ico.organisation_id')
    ->join('insurance_companies as ic', 'ic.id', '=', 'ico.insurance_company_id')
    ->orderBy('ico.id')
    ->get(['o.code as org_code', 'ic.code as ins_code']);

$lines[] = '-- TPA ↔ insurance company links (' . $pivot->count() . ')';
foreach ($pivot as $row) {
    $lines[] = 'INSERT IGNORE INTO `insurance_company_organisation` (`organisation_id`, `insurance_company_id`, `created_at`, `updated_at`)'
        . ' SELECT o.`id`, ic.`id`, NOW(), NOW()'
        . ' FROM `organisation` o, `insurance_companies` ic'
        . ' WHERE o.`code` = ' . sqlStr($row->org_code)
        . ' AND ic.`code` = ' . sqlStr($row->ins_code) . ';';
}

$lines[] = '';
$lines[] = '-- Sync primary insurer on organisation from first pivot link';
$lines[] = 'UPDATE `organisation` o';
$lines[] = 'INNER JOIN (';
$lines[] = '    SELECT ico.`organisation_id`, MIN(ico.`insurance_company_id`) AS `insurance_company_id`';
$lines[] = '    FROM `insurance_company_organisation` ico';
$lines[] = '    GROUP BY ico.`organisation_id`';
$lines[] = ') x ON x.`organisation_id` = o.`id`';
$lines[] = 'SET o.`insurance_company_id` = x.`insurance_company_id`';
$lines[] = 'WHERE o.`insurance_company_id` IS NULL OR o.`insurance_company_id` = 0;';

file_put_contents($out, implode("\n", $lines) . "\n");
echo "Wrote: $out\n";
echo 'Insurance: ' . $ins->count() . ', TPA: ' . $tpas->count() . ', Pivot: ' . $pivot->count() . "\n";
