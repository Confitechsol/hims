<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * Hospital bed billing windows: (prev day @ boundary, current day @ boundary].
 * Period labels are clamped so billing never implies a day before the anchor (admission / transfer / segment start).
 */
class BedBillingPeriod
{
    public static function boundaryHour(): int
    {
        return (int) config('hims.bed_billing_hour', 11);
    }

    public static function boundaryMinute(): int
    {
        return (int) config('hims.bed_billing_minute', 0);
    }

    /**
     * Nominal window for the same convention as {@see calculateBedChargesFromHistory}:
     * $chargeCalendarDay = startOfDay of the "current" day in the loop (charge_date / label day).
     *
     * @return array{0: Carbon, 1: Carbon} [periodStart, periodEnd)
     */
    public static function windowForChargeCalendarDay(Carbon $chargeCalendarDayStart): array
    {
        $h = self::boundaryHour();
        $m = self::boundaryMinute();

        $periodEnd = $chargeCalendarDayStart->copy()->setTime($h, $m, 0);
        $periodStart = $chargeCalendarDayStart->copy()->subDay()->setTime($h, $m, 0);

        return [$periodStart, $periodEnd];
    }

    /**
     * Effective window within [periodStart, periodEnd) where the patient/segment exists (anchor time onward).
     * Returns null if the anchor starts on or after period end (nothing to bill for this nominal day).
     *
     * @return array{0: Carbon, 1: Carbon}|null [effectiveStart, periodEnd)
     */
    public static function effectiveWindow(Carbon $periodStart, Carbon $periodEnd, Carbon $anchorAt): ?array
    {
        $effectiveStart = $periodStart->copy()->max($anchorAt);
        if ($effectiveStart->gte($periodEnd)) {
            return null;
        }

        return [$effectiveStart, $periodEnd];
    }

    /**
     * First calendar day (midnight) such that the nominal window for that day overlaps the anchor.
     */
    public static function firstChargeCalendarDayStart(Carbon $anchorAt): Carbon
    {
        $d = $anchorAt->copy()->startOfDay();

        for ($i = 0; $i < 400; $i++) {
            [$ps, $pe] = self::windowForChargeCalendarDay($d);
            if (self::effectiveWindow($ps, $pe, $anchorAt) !== null) {
                return $d;
            }
            $d->addDay();
        }

        return $anchorAt->copy()->startOfDay();
    }

    /**
     * First charge label day from anchor calendar date (not overlap based).
     * Example: admission on 6th any time => first label day 7th (period 6th->7th).
     */
    public static function firstChargeCalendarDayFromAnchorDate(Carbon $anchorAt): Carbon
    {
        return $anchorAt->copy()->startOfDay()->addDay();
    }

    /**
     * Stored period_start_date / period_end_date (date columns) for ipd_daywise_bed_charges + bill lines.
     */
    public static function periodStorageDatesForChargeDay(Carbon $chargeCalendarDayStart, Carbon $anchorAt): ?array
    {
        [$ps, $pe] = self::windowForChargeCalendarDay($chargeCalendarDayStart);
        $eff = self::effectiveWindow($ps, $pe, $anchorAt);
        if ($eff === null) {
            return null;
        }
        [$es] = $eff;

        return [
            'period_start_date' => $es->format('Y-m-d'),
            'period_end_date' => $pe->format('Y-m-d'),
        ];
    }
}
