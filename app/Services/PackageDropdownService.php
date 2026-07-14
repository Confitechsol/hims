<?php

namespace App\Services;

use App\Models\Package;
use App\Services\PackageInsuranceRateService;

class PackageDropdownService
{
    public function __construct(
        protected PackageInsuranceRateService $rateService
    ) {
    }

    /**
     * Format a package for dropdown APIs (admission, edit IPD, apply package).
     */
    public function toDropdownArray(Package $package, ?int $bedGroupId = null): array
    {
        $resolvedRate = $this->rateService->resolveRate(
            $package,
            $bedGroupId ? (int) $bedGroupId : null
        );

        $isInsurance = $package->isInsurance();
        $insuranceName = $package->insuranceCompany?->name;
        $panelName = $package->insuranceRatePanel?->name;

        $tpaNames = collect();
        if ($package->relationLoaded('insuranceCompany') && $package->insuranceCompany) {
            $tpaNames = $package->insuranceCompany->relationLoaded('tpas')
                ? $package->insuranceCompany->tpas->pluck('organisation_name')->filter()->unique()->values()
                : collect();
        }

        $subtitleParts = [];
        if ($isInsurance) {
            if ($insuranceName) {
                $subtitleParts[] = 'Insurance: ' . $insuranceName;
            }
            if ($tpaNames->isNotEmpty()) {
                $subtitleParts[] = 'TPA: ' . $tpaNames->implode(', ');
            }
            if ($panelName) {
                $subtitleParts[] = 'Panel: ' . $panelName;
            }
            if (empty($subtitleParts)) {
                $subtitleParts[] = 'Insurance package';
            }
        } else {
            $subtitleParts[] = 'Hospital package (Cash / General)';
        }

        $displaySubtitle = implode(' · ', $subtitleParts);
        $displayTitle = $package->name . ' — ₹' . number_format($resolvedRate, 2);

        return [
            'id' => $package->id,
            'name' => $package->name,
            'package_type' => $package->package_type ?? Package::TYPE_HOSPITAL,
            'package_rate' => $resolvedRate,
            'base_package_rate' => (float) $package->package_rate,
            'gst_amount' => (float) $package->gst_amount,
            'description' => $package->description,
            'insurer_procedure_code' => $package->insurer_procedure_code,
            'speciality' => $package->speciality,
            'insurance_company_id' => $package->insurance_company_id,
            'insurance_company_name' => $insuranceName,
            'insurance_rate_panel_id' => $package->insurance_rate_panel_id,
            'panel_name' => $panelName,
            'tpa_names' => $tpaNames->all(),
            'display_title' => $displayTitle,
            'display_subtitle' => $displaySubtitle,
            'has_room_rates' => $package->relationLoaded('roomRates')
                ? $package->roomRates->isNotEmpty()
                : false,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function mapPackagesForDropdown($packages, ?int $bedGroupId = null): array
    {
        return $packages->map(fn (Package $package) => $this->toDropdownArray($package, $bedGroupId))->values()->all();
    }
}
