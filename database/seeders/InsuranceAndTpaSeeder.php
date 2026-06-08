<?php

namespace Database\Seeders;

use App\Models\InsuranceCompany;
use App\Models\Organisation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InsuranceAndTpaSeeder extends Seeder
{
    /**
     * Seed insurance companies and TPAs from TPA LIST.xlsx (Sheet1).
     */
    public function run(): void
    {
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

        $insuranceMap = [];

        foreach ($insuranceCompanies as $index => $name) {
            $code = $this->makeCode('INS', $name, $index + 1);
            $insurance = InsuranceCompany::firstOrCreate(
                ['code' => $code],
                ['name' => trim($name)]
            );
            $insuranceMap[$name] = $insurance->id;
        }

        foreach ($tpas as $index => $tpaName) {
            $code = $this->makeCode('TPA', $tpaName, $index + 1);
            $insuranceName = $insuranceCompanies[$index] ?? null;
            $insuranceId = $insuranceName ? ($insuranceMap[$insuranceName] ?? null) : null;

            Organisation::firstOrCreate(
                ['code' => $code],
                [
                    'insurance_company_id' => $insuranceId,
                    'organisation_name' => trim($tpaName),
                    'contact_no' => '0000000000',
                    'address' => 'N/A',
                    'contact_person_name' => 'N/A',
                    'contact_person_phone' => '0000000001',
                    'poilicy_no' => 'N/A',
                    'e_card_no' => 'N/A',
                    'e_card_upload' => '',
                ]
            );
        }
    }

    private function makeCode(string $prefix, string $name, int $sequence): string
    {
        $slug = Str::upper(Str::slug(Str::limit($name, 20, ''), '_'));
        $slug = preg_replace('/[^A-Z0-9_]/', '', $slug) ?: $prefix;

        return $prefix . '_' . $slug . '_' . str_pad((string) $sequence, 2, '0', STR_PAD_LEFT);
    }
}
