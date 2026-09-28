<?php

namespace App\Services;

use App\Models\DischargeCard;
use App\Models\IpdDetail;
use Carbon\Carbon;

/**
 * Discharge datetime lookup shared by cash and insurance bed billing.
 * Day count follows the cash 11:00 boundary. Insurance no longer drops the
 * discharge day when discharge is before 3:00 PM.
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

            return Carbon::parse($date)->startOfDay();
        }

        if (! empty($ipd->discharged_date) && ($ipd->discharged ?? 'no') === 'yes') {
            return Carbon::parse($ipd->discharged_date)->startOfDay();
        }

        return null;
    }

    /**
     * Discharge-day bed charge is included for cash and insurance.
     * Day count uses the shared 11:00 billing window, not a 3:00 PM cutoff.
     */
    public function shouldIncludeDischargeDayBedCharge(IpdDetail $ipd, ?Carbon $dischargeAt = null): bool
    {
        return true;
    }

    /**
     * No charge label day is skipped for insurance. Same discharge count as cash.
     */
    public function dischargeChargeDateToExclude(
        IpdDetail $ipd,
        ?Carbon $billingEndAt = null,
        ?Carbon $effectiveDischargeAt = null
    ): ?string {
        return null;
    }
}
