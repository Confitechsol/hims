<?php

namespace App\Services;

use App\Models\DischargeCard;
use App\Models\IpdDetail;
use App\Support\BedBillingPeriod;
use Carbon\Carbon;

/**
 * Insurance IPD: discharge-day bed charge is excluded unless discharge is at/after 3 PM
 * (late approval / late discharge scenario).
 */
class InsuranceDischargeBedChargeService
{
    public function lateDischargeHour(): int
    {
        return (int) config('hims.insurance_discharge_bed_charge_after_hour', 15);
    }

    /**
     * Resolve discharge moment from discharge card or IPD record.
     */
    public function resolveDischargeAt(IpdDetail $ipd): ?Carbon
    {
        $dischargeCard = DischargeCard::where('ipd_details_id', $ipd->id)->orderByDesc('id')->first();
        if ($dischargeCard && ! empty($dischargeCard->discharge_date)) {
            $date = Carbon::parse($dischargeCard->discharge_date)->format('Y-m-d');
            $time = trim((string) ($dischargeCard->discharge_time ?? ''));
            if ($time !== '') {
                try {
                    return Carbon::parse($date . ' ' . $time);
                } catch (\Throwable $e) {
                    // fall through to start of day
                }
            }

            // No discharge time recorded — treat as early discharge (exclude discharge-day bed charge).
            return Carbon::parse($date)->startOfDay();
        }

        if (! empty($ipd->discharged_date) && ($ipd->discharged ?? 'no') === 'yes') {
            return Carbon::parse($ipd->discharged_date)->startOfDay();
        }

        return null;
    }

    /**
     * Whether discharge-day bed charge should be billed for this insurance IPD.
     */
    public function shouldIncludeDischargeDayBedCharge(IpdDetail $ipd, ?Carbon $dischargeAt = null): bool
    {
        if (! $ipd->isInsuranceBilling()) {
            return true;
        }

        $dischargeAt = $dischargeAt ?? $this->resolveDischargeAt($ipd);
        if (! $dischargeAt) {
            return true;
        }

        return $dischargeAt->hour >= $this->lateDischargeHour();
    }

    /**
     * Charge label day (Y-m-d) to skip for insurance discharge, or null if none.
     */
    public function dischargeChargeDateToExclude(
        IpdDetail $ipd,
        ?Carbon $billingEndAt = null,
        ?Carbon $effectiveDischargeAt = null
    ): ?string {
        if (! $ipd->isInsuranceBilling()) {
            return null;
        }

        if (($ipd->discharged ?? 'no') !== 'yes') {
            return null;
        }

        $dischargeAt = $effectiveDischargeAt ?? $this->resolveDischargeAt($ipd);
        if (! $dischargeAt) {
            return null;
        }

        if ($this->shouldIncludeDischargeDayBedCharge($ipd, $dischargeAt)) {
            return null;
        }

        $labelDay = BedBillingPeriod::chargeLabelDayForMoment(
            $billingEndAt ?? $dischargeAt
        );

        return $labelDay->format('Y-m-d');
    }
}
