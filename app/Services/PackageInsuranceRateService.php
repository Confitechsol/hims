<?php

namespace App\Services;

use App\Models\InsurerRoomMapping;
use App\Models\Package;

class PackageInsuranceRateService
{
    public function resolveRate(Package $package, ?int $bedGroupId = null): float
    {
        $package->loadMissing('roomRates');

        if ($bedGroupId) {
            $match = $package->roomRates->firstWhere('bed_group_id', (int) $bedGroupId);
            if ($match && $match->rate !== null) {
                return (float) $match->rate;
            }
        }

        if ($package->roomRates->isNotEmpty()) {
            $min = $package->roomRates->min('rate');
            if ($min !== null) {
                return (float) $min;
            }
        }

        return (float) ($package->package_rate ?? 0);
    }

    public function syncDefaultPackageRate(Package $package): void
    {
        $package->loadMissing('roomRates');
        if ($package->roomRates->isEmpty()) {
            return;
        }

        $min = $package->roomRates->min('rate');
        if ($min !== null && (float) $package->package_rate !== (float) $min) {
            $package->update(['package_rate' => $min]);
        }
    }

    /**
     * Resolve bed group from insurer room mapping when panel/company is known.
     */
    public function resolveBedGroupForRoomCode(
        ?int $panelId,
        ?int $insuranceCompanyId,
        string $roomCode
    ): ?int {
        $query = InsurerRoomMapping::query()->where('insurer_room_code', $roomCode);

        if ($panelId) {
            $id = (clone $query)->where('insurance_rate_panel_id', $panelId)->value('bed_group_id');
            if ($id) {
                return (int) $id;
            }
        }

        if ($insuranceCompanyId) {
            $id = (clone $query)->where('insurance_company_id', $insuranceCompanyId)->value('bed_group_id');
            if ($id) {
                return (int) $id;
            }
        }

        return null;
    }
}
