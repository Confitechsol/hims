<?php
/**
 * Generate database/sql/tpa_insurance_seed_data.sql from InsuranceAndTpaSeeder (TPA LIST.xlsx).
 * Usage: php scripts/generate_tpa_seed_sql.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Str;

function sqlStr(?string $v): string
{
    if ($v === null) {
        return 'NULL';
    }

    return "'" . str_replace(["\\", "'"], ["\\\\", "''"], $v) . "'";
}

function makeCode(string $prefix, string $name, int $sequence): string
{
    $slug = Str::upper(Str::slug(Str::limit($name, 20, ''), '_'));
    $slug = preg_replace('/[^A-Z0-9_]/', '', $slug) ?: $prefix;

    return $prefix . '_' . $slug . '_' . str_pad((string) $sequence, 2, '0', STR_PAD_LEFT);
}

$insuranceCompanies = [
    'ACKO GENERAL INSURANCE',
    'ADITYA BIRLA HEALTH INSURANCE CO. LIMITED',
    'CHOLA MS GENERAL INSURANCE',
    'EEXPEDISE HEALTHCARE PVT. LTD.',
    'FUTURE GENERALI INSURANCE',
    'GO DIGIT GENERAL INSURANCE LTD',
    'HDFC ERGO GENERAL INSURANCE CO. LIMITED.',
    'ICICI LOMBARD GENERAL INS. CO. LTD.',
    'KOTAK MAHINDRA GENERAL INSURANCE',
    'LIBERTY GENERAL INSURANCE',
    'MAGMA HDI GENERAL INSURANCE CO. LTD.',
    'MANIPALCIGNA HEALTH INS. CO. LTD.',
    'NAVI GENERAL INSURANCE LTD.',
    'NIVA BUPA/MAX BUPA HEALTH INS. CO. LTD.',
    'RAHEJA QBE GENERAL INSURANCE CO. LTD.',
    'RELIANCE GENERAL INSURANCE CO. LTD.',
    'ROYAL SUNDARAM GENERAL INS. CO. LTD',
    'SBI GENERAL INSURANCE CO. LTD.',
    'STAR HEALTH AND ALLIED INSURANCE CO.LTD.',
    'TATA AIG INSURANCE CO. LTD.',
    'THE  ORIENTAL INSURANCE CO. LTD.',
    'THE NATIONAL INSURANCE CO. LTD.',
    'THE NEW INDIA INSURANCE CO. LTD.',
    'THE UNITED INDIA INSURANCE CO. LTD.',
    'UNIVERSAL SOMPO GENERAL INS. CO. LTD',
];

$tpas = [
    'MEDI ASSIST INS. TPA PVT. LTD.',
    'EAST WEST ASSIST INS. TPA PVT. LTD.',
    'FAMILY HEALTH PLAN INS. TPA PVT. LTD.',
    'GENINS INDIA INS. TPA PVT. LTD.',
    'E-MEITEK  TPA PVT. LTD.',
    'GOOD HEALTH INSURANCE TPA LTD.',
    'HEALTH INDIA INS. TPA PVT. LTD.',
    'HERITAGE HEALTH INS. TPA PVT. LTD.',
    'ERICKSON INSURANCE TPA PVT. LTD.',
    'MD INDIA HEALTH INS. TPA PVT. LTD.',
    'MEDSAVE HEALTH INS. TPA PVT. LTD.',
    'PARAMOUNT HEALTH INS. TPA PVT. LTD.',
    'RAKSHA HEALTH INS. TPA PVT. LTD.',
    'ROTHSHIELD INSURANCE TPA LTD.',
    'SAFEWAY INSURANCE TPA PVT. LTD.',
    'UNITED HEALTHCARE (PAREKH)',
    'VIDAL HEALTH INS. TPA PVT. LTD.',
    'VIPUL MEDCORP TPA PVT. LTD.',
    'VISION DIGITAL INS. TPA PVT. LTD.',
    'HEALTH INSURNACE TPA PVT. LTD.',
];

$ratePanels = [
    ['code' => 'GIPSA_PPN', 'name' => 'GIPSA PPN Samaritan 2022'],
    ['code' => 'STAR_HEALTH', 'name' => 'Star Health Package 2022'],
    ['code' => 'HDFC_ERGO', 'name' => 'HDFC Ergo Package 2023'],
    ['code' => 'GALAXY', 'name' => 'Galaxy Health Insurance'],
    ['code' => 'GIPSA', 'name' => 'GIPSA Panel'],
    ['code' => 'ICICI_LOMBARD', 'name' => 'ICICI Lombard Panel'],
];

$lines = [];
$lines[] = '-- =============================================================================';
$lines[] = '-- HIMS TPA Management — Seed Data (Insurance companies + TPAs + rate panels)';
$lines[] = '-- =============================================================================';
$lines[] = '-- Source: TPA LIST.xlsx (Sheet1) via InsuranceAndTpaSeeder';
$lines[] = '-- Run AFTER: database/sql/insurance_implementation.sql';
$lines[] = '-- Safe to re-run: INSERT IGNORE on unique codes';
$lines[] = '-- =============================================================================';
$lines[] = '';
$lines[] = 'SET NAMES utf8mb4;';
$lines[] = '';

$insCodes = [];
$lines[] = '-- 1) Insurance companies (' . count($insuranceCompanies) . ')';
foreach ($insuranceCompanies as $i => $name) {
    $code = makeCode('INS', $name, $i + 1);
    $insCodes[$i] = $code;
    $lines[] = 'INSERT IGNORE INTO `insurance_companies` (`code`, `name`, `created_at`, `updated_at`) VALUES ('
        . sqlStr($code) . ', ' . sqlStr(trim($name)) . ', NOW(), NOW());';
}
$lines[] = '';

$lines[] = '-- 2) TPAs / organisation table (' . count($tpas) . ')';
foreach ($tpas as $i => $tpaName) {
    $tpaCode = makeCode('TPA', $tpaName, $i + 1);
    $insCode = $insCodes[$i] ?? null;
    $insSub = $insCode
        ? '(SELECT `id` FROM `insurance_companies` WHERE `code` = ' . sqlStr($insCode) . ' LIMIT 1)'
        : 'NULL';

    $lines[] = 'INSERT IGNORE INTO `organisation` (`hospital_id`, `branch_id`, `insurance_company_id`, `organisation_name`, `code`, `contact_no`, `address`, `contact_person_name`, `contact_person_phone`, `poilicy_no`, `e_card_no`, `e_card_upload`) VALUES ('
        . sqlStr('') . ', '
        . sqlStr('') . ', '
        . $insSub . ', '
        . sqlStr(trim($tpaName)) . ', '
        . sqlStr($tpaCode) . ', '
        . sqlStr('0000000000') . ', '
        . sqlStr('N/A') . ', '
        . sqlStr('N/A') . ', '
        . sqlStr('0000000001') . ', '
        . sqlStr('N/A') . ', '
        . sqlStr('N/A') . ', '
        . sqlStr('') . ');';
}
$lines[] = '';

$lines[] = '-- 3) TPA ↔ insurance company pivot (primary link per TPA)';
foreach ($tpas as $i => $tpaName) {
    $tpaCode = makeCode('TPA', $tpaName, $i + 1);
    $insCode = $insCodes[$i] ?? null;
    if (!$insCode) {
        continue;
    }
    $lines[] = 'INSERT IGNORE INTO `insurance_company_organisation` (`organisation_id`, `insurance_company_id`, `created_at`, `updated_at`)'
        . ' SELECT o.`id`, ic.`id`, NOW(), NOW()'
        . ' FROM `organisation` o, `insurance_companies` ic'
        . ' WHERE o.`code` = ' . sqlStr($tpaCode)
        . ' AND ic.`code` = ' . sqlStr($insCode) . ';';
}
$lines[] = '';

$lines[] = '-- 4) Insurance rate panels (for test rates & surgical packages)';
foreach ($ratePanels as $panel) {
    $lines[] = 'INSERT IGNORE INTO `insurance_rate_panels` (`code`, `name`, `is_active`, `created_at`, `updated_at`) VALUES ('
        . sqlStr($panel['code']) . ', ' . sqlStr($panel['name']) . ', 1, NOW(), NOW());';
}
$lines[] = '';

$lines[] = '-- 5) Ensure organisation.insurance_company_id matches pivot';
$lines[] = 'UPDATE `organisation` o';
$lines[] = 'INNER JOIN `insurance_companies` ic ON ic.`id` = o.`insurance_company_id`';
$lines[] = 'SET o.`insurance_company_id` = ic.`id`';
$lines[] = 'WHERE o.`insurance_company_id` IS NOT NULL;';
$lines[] = '';

$out = dirname(__DIR__) . '/database/sql/tpa_insurance_seed_data.sql';
file_put_contents($out, implode("\n", $lines) . "\n");
echo "Wrote: $out\n";
echo 'Insurance companies: ' . count($insuranceCompanies) . "\n";
echo 'TPAs: ' . count($tpas) . "\n";
echo 'Rate panels: ' . count($ratePanels) . "\n";
