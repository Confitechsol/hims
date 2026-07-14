<?php

namespace App\Services;

use App\Models\InsuranceCompany;
use App\Models\IpdDetail;
use App\Models\Organisation;
use App\Models\Patient;
use Illuminate\Support\Collection;

class BillingTpaHelper
{
    public static function formatTpa(?Organisation $organisation, ?int $insuranceCompanyId = null): ?array
    {
        if (!$organisation) {
            return null;
        }

        $resolvedInsuranceId = $insuranceCompanyId ?: ($organisation->insurance_company_id ? (int) $organisation->insurance_company_id : null);
        $insuranceName = null;

        if ($resolvedInsuranceId) {
            if ($organisation->relationLoaded('insuranceCompany')
                && $organisation->insuranceCompany
                && (int) $organisation->insuranceCompany->id === $resolvedInsuranceId
            ) {
                $insuranceName = $organisation->insuranceCompany->name;
            } else {
                $insuranceName = InsuranceCompany::where('id', $resolvedInsuranceId)->value('name');
            }
        }

        return [
            'id' => $organisation->id,
            'name' => $organisation->organisation_name ?? 'Unknown TPA',
            'code' => $organisation->code ?? null,
            'insurance_company_id' => $resolvedInsuranceId,
            'insurance_company_name' => $insuranceName,
            'preferred' => false,
            'from_ipd' => false,
        ];
    }

    public static function formatTpaFromIpd(?IpdDetail $ipd): ?array
    {
        if (!$ipd || !$ipd->organisation_id) {
            return null;
        }

        $organisation = $ipd->relationLoaded('organisation')
            ? $ipd->organisation
            : Organisation::with('insuranceCompany')->find($ipd->organisation_id);

        if (!$organisation) {
            return null;
        }

        if (!$organisation->relationLoaded('insuranceCompany')) {
            $organisation->load('insuranceCompany');
        }

        $tpa = self::formatTpa(
            $organisation,
            $ipd->insurance_company_id ? (int) $ipd->insurance_company_id : null
        );

        if ($tpa) {
            $tpa['preferred'] = true;
            $tpa['from_ipd'] = true;
            $tpa['ipd_id'] = $ipd->id;
        }

        return $tpa;
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

    /**
     * Latest IPD admission for billing context (active first, then newest).
     */
    public static function latestIpdForPatient(int $patientId, ?int $ipdId = null): ?IpdDetail
    {
        if ($ipdId) {
            return IpdDetail::with(['organisation.insuranceCompany', 'insuranceCompany'])
                ->where('patient_id', $patientId)
                ->where('id', $ipdId)
                ->first();
        }

        $active = IpdDetail::with(['organisation.insuranceCompany', 'insuranceCompany'])
            ->where('patient_id', $patientId)
            ->where(function ($q) {
                $q->whereNull('discharged')->orWhere('discharged', '!=', 'yes');
            })
            ->orderByDesc('id')
            ->first();

        if ($active) {
            return $active;
        }

        return IpdDetail::with(['organisation.insuranceCompany', 'insuranceCompany'])
            ->where('patient_id', $patientId)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Collect TPAs for pathology/radiology billing.
     * Always prefers the latest IPD admission's TPA + insurance_company_id.
     *
     * @param  'pathology'|'radiology'  $billingSource
     */
    public static function collectPatientTpas(int $patientId, string $billingSource = 'pathology', ?int $ipdId = null): Collection
    {
        $items = collect();

        $latestIpd = self::latestIpdForPatient($patientId, $ipdId);
        if ($latestIpd && $latestIpd->organisation_id) {
            $fromIpd = self::formatTpaFromIpd($latestIpd);
            if ($fromIpd) {
                $items->push($fromIpd);
            }
        }

        $patient = Patient::with('organisation.insuranceCompany')->find($patientId);
        if ($patient && $patient->organisation_id && $patient->organisation) {
            $items->push(self::formatTpa($patient->organisation));
        }

        if ($billingSource === 'radiology') {
            $billClass = \App\Models\RadiologyBilling::class;
        } else {
            $billClass = \App\Models\PathologyBilling::class;
        }

        $fromBills = $billClass::where('patient_id', $patientId)
            ->whereNotNull('organisation_id')
            ->with('organisation.insuranceCompany')
            ->select('organisation_id')
            ->distinct()
            ->get()
            ->map(fn ($billing) => self::formatTpa($billing->organisation))
            ->filter();

        $items = $items->merge($fromBills);

        $fromIpds = IpdDetail::where('patient_id', $patientId)
            ->whereNotNull('organisation_id')
            ->with(['organisation.insuranceCompany'])
            ->orderByDesc('id')
            ->get()
            ->map(fn ($ipd) => self::formatTpaFromIpd($ipd))
            ->filter();

        $items = $items->merge($fromIpds);

        return self::uniquePreferLatestIpd($items);
    }

    /**
     * Resolve TPA payload for an IPD prescription — always from that IPD (not patient master).
     */
    public static function tpaFromPrescriptionIpd(?IpdDetail $ipd): ?array
    {
        if (!$ipd) {
            return null;
        }

        if (!$ipd->relationLoaded('organisation') && $ipd->organisation_id) {
            $ipd->load('organisation.insuranceCompany');
        }

        return self::formatTpaFromIpd($ipd);
    }

    protected static function uniquePreferLatestIpd(Collection $items): Collection
    {
        $byId = [];

        foreach ($items->filter() as $tpa) {
            $id = $tpa['id'] ?? null;
            if (!$id) {
                continue;
            }

            if (!isset($byId[$id])) {
                $byId[$id] = $tpa;
                continue;
            }

            $existing = $byId[$id];
            $incomingPreferred = !empty($tpa['preferred']) || !empty($tpa['from_ipd']);
            $existingPreferred = !empty($existing['preferred']) || !empty($existing['from_ipd']);

            if ($incomingPreferred && !$existingPreferred) {
                $byId[$id] = $tpa;
                continue;
            }

            // Same TPA: keep newer IPD insurance overlay when available
            if ($incomingPreferred && $existingPreferred) {
                $incomingIpdId = (int) ($tpa['ipd_id'] ?? 0);
                $existingIpdId = (int) ($existing['ipd_id'] ?? 0);
                if ($incomingIpdId > $existingIpdId) {
                    $byId[$id] = $tpa;
                }
            }
        }

        return collect(array_values($byId))
            ->sortByDesc(fn ($tpa) => !empty($tpa['preferred']) ? 1 : 0)
            ->values();
    }
}
