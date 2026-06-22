<?php

namespace App\Services;

use App\Models\InsuranceCompany;
use App\Models\InsuranceTestRate;
use App\Models\OrganisationsCharge;
use App\Models\Pathology;
use App\Models\Radio;

class InsuranceBillingRateResolver
{
    public const SOURCE_INSURANCE_PANEL = 'insurance_panel';
    public const SOURCE_TPA = 'tpa';
    public const SOURCE_STANDARD = 'standard';

    public function resolvePathology(
        int $pathologyId,
        ?int $insuranceCompanyId = null,
        ?int $organisationId = null,
        string $customerType = 'OPD'
    ): array {
        $test = Pathology::findOrFail($pathologyId);
        $standard = ($customerType === 'IPD')
            ? (float) ($test->standard_charge_ipd ?? $test->standard_charge ?? 0)
            : (float) ($test->standard_charge_opd ?? $test->standard_charge ?? 0);

        if ($insuranceCompanyId) {
            $panelRate = $this->findPanelRate($insuranceCompanyId, 'pathology', $pathologyId);
            if ($panelRate) {
                return $this->buildResult($panelRate, $standard);
            }
        }

        if ($organisationId) {
            $tpaCharge = OrganisationsCharge::where('pathology_id', $pathologyId)
                ->where('org_id', $organisationId)
                ->where('charge_type', $customerType)
                ->first();

            if ($tpaCharge && $tpaCharge->org_charge !== null) {
                return [
                    'rate' => (float) $tpaCharge->org_charge,
                    'source' => self::SOURCE_TPA,
                    'insurer_test_name' => null,
                    'panel_id' => null,
                    'insurance_test_rate_id' => null,
                    'standard_rate' => $standard,
                ];
            }
        }

        return [
            'rate' => $standard,
            'source' => self::SOURCE_STANDARD,
            'insurer_test_name' => null,
            'panel_id' => null,
            'insurance_test_rate_id' => null,
            'standard_rate' => $standard,
        ];
    }

    public function resolveRadiology(
        int $radiologyId,
        ?int $insuranceCompanyId = null,
        ?int $organisationId = null,
        string $customerType = 'OPD'
    ): array {
        $test = Radio::with('charge')->findOrFail($radiologyId);
        $standard = $test->charge ? (float) ($test->charge->standard_charge ?? 0) : 0.0;

        if ($insuranceCompanyId) {
            $panelRate = $this->findPanelRate($insuranceCompanyId, 'radiology', $radiologyId);
            if ($panelRate) {
                return $this->buildResult($panelRate, $standard);
            }
        }

        if ($organisationId) {
            $tpaCharge = OrganisationsCharge::where('radiology_id', $radiologyId)
                ->where('org_id', $organisationId)
                ->where('charge_type', $customerType)
                ->first();

            if ($tpaCharge && $tpaCharge->org_charge !== null) {
                return [
                    'rate' => (float) $tpaCharge->org_charge,
                    'source' => self::SOURCE_TPA,
                    'insurer_test_name' => null,
                    'panel_id' => null,
                    'insurance_test_rate_id' => null,
                    'standard_rate' => $standard,
                ];
            }
        }

        return [
            'rate' => $standard,
            'source' => self::SOURCE_STANDARD,
            'insurer_test_name' => null,
            'panel_id' => null,
            'insurance_test_rate_id' => null,
            'standard_rate' => $standard,
        ];
    }

    protected function buildResult(InsuranceTestRate $panelRate, float $standard): array
    {
        return [
            'rate' => (float) $panelRate->rate,
            'source' => self::SOURCE_INSURANCE_PANEL,
            'insurer_test_name' => $panelRate->insurer_test_name,
            'panel_id' => $panelRate->insurance_rate_panel_id,
            'insurance_test_rate_id' => $panelRate->id,
            'standard_rate' => $standard,
        ];
    }

    protected function findPanelRate(int $insuranceCompanyId, string $testType, int $testId): ?InsuranceTestRate
    {
        $company = InsuranceCompany::with('ratePanels')->find($insuranceCompanyId);
        if (!$company || $company->ratePanels->isEmpty()) {
            return null;
        }

        $query = InsuranceTestRate::query()
            ->whereIn('insurance_rate_panel_id', $company->ratePanels->pluck('id'))
            ->where('test_type', $testType)
            ->where('mapping_status', 'mapped');

        if ($testType === 'pathology') {
            $query->where('pathology_id', $testId);
        } else {
            $query->where('radiology_id', $testId);
        }

        return $query->orderByDesc('id')->first();
    }
}
