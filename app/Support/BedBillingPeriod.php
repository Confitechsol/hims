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
     * Daily boundary on the anchor's calendar day (default 11:00 AM).
     */
    public static function boundaryOnCalendarDay(Carbon $calendarDayStart): Carbon
    {
        return $calendarDayStart->copy()->startOfDay()->setTime(
            self::boundaryHour(),
            self::boundaryMinute(),
            0
        );
    }

    /**
     * True when admission/segment starts after midnight and before the daily boundary (11:00 AM).
     * Hospital policy: no bed charge until the boundary on that calendar day.
     */
    public static function isPostMidnightPreBoundaryAdmission(Carbon $anchorAt): bool
    {
        return $anchorAt->lt(self::boundaryOnCalendarDay($anchorAt->copy()->startOfDay()));
    }

    /**
     * First billable moment for bed charges.
     * Post-midnight admissions before 11:00 AM start billing at 11:00 AM same day; all others at actual anchor time.
     */
    public static function billableAnchorAt(Carbon $anchorAt): Carbon
    {
        if (self::isPostMidnightPreBoundaryAdmission($anchorAt)) {
            return self::boundaryOnCalendarDay($anchorAt->copy()->startOfDay());
        }

        return $anchorAt->copy();
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
     * First charge label day from billable anchor (not raw admission when grace period applies).
     */
    public static function firstChargeCalendarDayFromAnchorDate(Carbon $anchorAt): Carbon
    {
        return self::billableAnchorAt($anchorAt)->copy()->startOfDay()->addDay();
    }

    /**
     * Stored period_start_date / period_end_date (date columns) for ipd_daywise_bed_charges + bill lines.
     */
    public static function periodStorageDatesForChargeDay(Carbon $chargeCalendarDayStart, Carbon $anchorAt): ?array
    {
        [$ps, $pe] = self::windowForChargeCalendarDay($chargeCalendarDayStart);
        $eff = self::effectiveWindow($ps, $pe, self::billableAnchorAt($anchorAt));
        if ($eff === null) {
            return null;
        }
        [$es] = $eff;

        return [
            'period_start_date' => $es->format('Y-m-d'),
            'period_end_date' => $pe->format('Y-m-d'),
        ];
    }

    /**
     * Charge label calendar day for a moment (admission, discharge, or now).
     * If the moment is after the boundary on its calendar day, it belongs to the next label day.
     */
    public static function chargeLabelDayForMoment(Carbon $moment): Carbon
    {
        $labelDay = $moment->copy()->startOfDay();
        [, $boundaryEnd] = self::windowForChargeCalendarDay($labelDay);
        if ($moment->gt($boundaryEnd)) {
            $labelDay->addDay();
        }

        return $labelDay;
    }
}
