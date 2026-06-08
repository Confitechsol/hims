<?php

namespace App\Services;

use App\Models\Organisation;

class BillingTpaHelper
{
    public static function formatTpa(?Organisation $organisation): ?array
    {
        if (!$organisation) {
            return null;
        }

        return [
            'id' => $organisation->id,
            'name' => $organisation->organisation_name ?? 'Unknown TPA',
            'code' => $organisation->code ?? null,
            'insurance_company_id' => $organisation->insurance_company_id,
            'insurance_company_name' => $organisation->insuranceCompany?->name,
        ];
    }

    public static function resolveInsuranceCompanyId(?int $organisationId, ?int $insuranceCompanyId = null): ?int
    {
        if ($insuranceCompanyId) {
            return $insuranceCompanyId;
        }

        if (!$organisationId) {
            return null;
        }

        return Organisation::where('id', $organisationId)->value('insurance_company_id');
    }
}
