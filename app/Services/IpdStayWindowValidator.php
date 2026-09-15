<?php

namespace App\Services;

use App\Exceptions\IpdConstraintException;
use App\Models\IpdDetail;
use App\Services\InsuranceDischargeBedChargeService;
use Carbon\Carbon;

/**
 * Stay-window and reopen/final-bill mutation constraints for IPD.
 */
class IpdStayWindowValidator
{
    public function __construct(
        protected InsuranceDischargeBedChargeService $dischargeBedService,
        protected BedOccupancyService $bedOccupancyService
    ) {}

    public function isFinalized(IpdDetail $ipd): bool
    {
        return ! empty($ipd->final_bill_generated_at) && ! (bool) ($ipd->is_reopened ?? false);
    }

    public function isReopened(IpdDetail $ipd): bool
    {
        return (bool) ($ipd->is_reopened ?? false);
    }

    public function isDischarged(IpdDetail $ipd): bool
    {
        return ($ipd->discharged ?? 'no') === 'yes';
    }

    /**
     * Mutations that change clinical/billing data require reopen when finalized.
     */
    public function assertMutable(IpdDetail $ipd, string $actionLabel = 'update'): void
    {
        if ($this->isFinalized($ipd)) {
            throw new IpdConstraintException(
                'FINAL_BILL_LOCKED',
                "Cannot {$actionLabel}: final bill is generated. Please reopen discharge first.",
                ['ipd_id' => $ipd->id, 'ipd_no' => $ipd->ipd_no]
            );
        }
    }

    public function assertAdmissionImmutable(IpdDetail $ipd, $newAdmissionDateTime): void
    {
        if (! $this->isDischarged($ipd) && ! $this->isReopened($ipd) && ! $this->isFinalized($ipd)) {
            return;
        }

        $current = Carbon::parse($ipd->date)->format('Y-m-d H:i');
        $incoming = Carbon::parse($newAdmissionDateTime)->format('Y-m-d H:i');

        if ($current !== $incoming) {
            throw new IpdConstraintException(
                'ADMISSION_IMMUTABLE',
                'Admission date and time cannot be changed after discharge / reopen.',
                [
                    'current' => Carbon::parse($ipd->date)->format('d/m/Y h:i A'),
                    'attempted' => Carbon::parse($newAdmissionDateTime)->format('d/m/Y h:i A'),
                ]
            );
        }
    }

    public function assertDischargeImmutable(IpdDetail $ipd, $newDate, $newTime = null): void
    {
        if (! $this->isDischarged($ipd) && ! $this->isFinalized($ipd) && ! $this->isReopened($ipd)) {
            return;
        }

        // After clinical discharge (or reopen), discharge datetime is frozen.
        $current = $this->resolveDischargeAt($ipd);
        $incomingDate = Carbon::parse($newDate)->format('Y-m-d');
        $incomingTime = trim((string) ($newTime ?? ''));

        $currentDate = $current->format('Y-m-d');
        $currentTime = $current->format('H:i:s');

        $timeChanged = false;
        if ($incomingTime !== '') {
            try {
                $normalizedIncoming = Carbon::parse($incomingDate . ' ' . $incomingTime)->format('H:i:s');
                $timeChanged = $normalizedIncoming !== $currentTime;
            } catch (\Throwable $e) {
                $timeChanged = true;
            }
        }

        if ($incomingDate !== $currentDate || $timeChanged) {
            throw new IpdConstraintException(
                'DISCHARGE_IMMUTABLE',
                'Discharge date and time cannot be changed after final discharge / reopen.',
                [
                    'current' => $current->format('d/m/Y h:i A'),
                ]
            );
        }
    }

    /**
     * Charge/bill calendar date must fall within admission..discharge (date-only).
     */
    public function assertChargeDateInStayWindow(IpdDetail $ipd, $chargeDate, string $label = 'Charge date'): void
    {
        if (! $this->isDischarged($ipd) && ! $this->isReopened($ipd)) {
            // Still admitted: only enforce not before admission.
            $admit = Carbon::parse($ipd->date)->startOfDay();
            $date = Carbon::parse($chargeDate)->startOfDay();
            if ($date->lt($admit)) {
                throw new IpdConstraintException(
                    'CHARGE_DATE_OUT_OF_WINDOW',
                    "{$label} cannot be before admission date ({$admit->format('d/m/Y')}).",
                    ['admission' => $admit->format('Y-m-d'), 'charge_date' => $date->format('Y-m-d')]
                );
            }

            return;
        }

        [$start, $end] = $this->stayCalendarBounds($ipd);
        $date = Carbon::parse($chargeDate)->startOfDay();

        if ($date->lt($start) || $date->gt($end)) {
            throw new IpdConstraintException(
                'CHARGE_DATE_OUT_OF_WINDOW',
                "{$label} must be between admission ({$start->format('d/m/Y')}) and discharge ({$end->format('d/m/Y')}).",
                [
                    'admission' => $start->format('Y-m-d'),
                    'discharge' => $end->format('Y-m-d'),
                    'charge_date' => $date->format('Y-m-d'),
                ]
            );
        }
    }

    /**
     * New/edited bed segment datetimes must lie inside admit→discharge.
     */
    public function assertBedIntervalInStayWindow(IpdDetail $ipd, Carbon $from, ?Carbon $to = null): void
    {
        $admissionAt = Carbon::parse($ipd->date);
        $dischargeAt = $this->isDischarged($ipd) || $this->isReopened($ipd)
            ? $this->resolveDischargeAt($ipd)
            : null;

        if ($from->lt($admissionAt)) {
            throw new IpdConstraintException(
                'BED_OUTSIDE_STAY_WINDOW',
                'Bed From date/time cannot be before IPD admission (' . $admissionAt->format('d/m/Y h:i A') . ').',
                ['admission' => $admissionAt->toDateTimeString()]
            );
        }

        if ($dischargeAt) {
            if ($from->gt($dischargeAt)) {
                throw new IpdConstraintException(
                    'BED_OUTSIDE_STAY_WINDOW',
                    'Bed From date/time cannot be after discharge (' . $dischargeAt->format('d/m/Y h:i A') . ').',
                    ['discharge' => $dischargeAt->toDateTimeString()]
                );
            }
            if ($to && $to->gt($dischargeAt)) {
                throw new IpdConstraintException(
                    'BED_OUTSIDE_STAY_WINDOW',
                    'Bed To date/time cannot be after discharge (' . $dischargeAt->format('d/m/Y h:i A') . ').',
                    ['discharge' => $dischargeAt->toDateTimeString()]
                );
            }
        }

        if ($to && $to->lt($from)) {
            throw new IpdConstraintException(
                'BED_OUTSIDE_STAY_WINDOW',
                'Bed To date/time must be on or after From date/time.',
                []
            );
        }
    }

    public function assertBedAvailable(
        int $bedId,
        Carbon $from,
        ?Carbon $to,
        ?int $excludeHistoryId,
        ?int $excludeIpdId
    ): void {
        $check = $this->bedOccupancyService->checkAvailability(
            $bedId,
            $from,
            $to,
            $excludeHistoryId,
            $excludeIpdId
        );

        if (! ($check['available'] ?? false)) {
            throw new IpdConstraintException(
                'BED_OCCUPIED_CONFLICT',
                $check['message'] ?? 'Selected bed is already occupied for the requested period.',
                ['bed_id' => $bedId]
            );
        }
    }

    /**
     * Existing bed history from/to cannot change in reopen mode (rate-only).
     */
    public function assertBedDatetimesUnchanged($history, Carbon $newFrom, ?Carbon $newTo): void
    {
        $oldFrom = Carbon::parse($history->from_date)->format('Y-m-d H:i');
        $newFromKey = $newFrom->format('Y-m-d H:i');
        $oldTo = $history->to_date ? Carbon::parse($history->to_date)->format('Y-m-d H:i') : null;
        $newToKey = $newTo ? $newTo->format('Y-m-d H:i') : null;

        if ($oldFrom !== $newFromKey || $oldTo !== $newToKey) {
            throw new IpdConstraintException(
                'BED_DATETIME_IMMUTABLE',
                'Bed assignment date and time cannot be changed after discharge reopen. You may update bed charge/rate only.',
                [
                    'current_from' => $oldFrom,
                    'current_to' => $oldTo,
                ]
            );
        }
    }

    /**
     * @return array{0: Carbon, 1: Carbon} start/end of day bounds
     */
    public function stayCalendarBounds(IpdDetail $ipd): array
    {
        $start = Carbon::parse($ipd->date)->startOfDay();
        $end = $this->resolveDischargeAt($ipd)->startOfDay();

        return [$start, $end];
    }

    public function resolveDischargeAt(IpdDetail $ipd): Carbon
    {
        $at = $this->dischargeBedService->resolveDischargeAt($ipd);
        if ($at) {
            return $at;
        }
        if (! empty($ipd->discharged_date)) {
            return Carbon::parse($ipd->discharged_date)->startOfDay();
        }

        throw new IpdConstraintException(
            'DISCHARGE_IMMUTABLE',
            'Discharge date is missing for this IPD.',
            ['ipd_id' => $ipd->id]
        );
    }

    public function resolveAdmissionAt(IpdDetail $ipd): Carbon
    {
        return Carbon::parse($ipd->date);
    }

    /**
     * Resolve IPD to apply stay-window rules for a patient bill (path/radio/doctor visit).
     * Prefer open admission, else reopened IPD, else null (OPD-only — no IPD window).
     */
    public function resolveIpdForPatientBilling(int $patientId): ?IpdDetail
    {
        $open = IpdDetail::where('patient_id', $patientId)
            ->where(function ($q) {
                $q->whereNull('discharged')
                    ->orWhere('discharged', '!=', 'yes');
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->first();

        if ($open) {
            return $open;
        }

        return IpdDetail::where('patient_id', $patientId)
            ->where('is_reopened', true)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * If patient has an IPD under stay constraints, validate charge date + mutability.
     */
    public function assertPatientChargeDateForIpdBilling(int $patientId, $chargeDate, string $label = 'Bill date'): ?IpdDetail
    {
        $ipd = $this->resolveIpdForPatientBilling($patientId);
        if (! $ipd) {
            // Also block if latest IPD is finalized (not reopened) — pharmacy-style
            $latest = IpdDetail::where('patient_id', $patientId)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->first();
            if ($latest && $this->isFinalized($latest)) {
                throw new IpdConstraintException(
                    'FINAL_BILL_LOCKED',
                    "Cannot {$label}: patient's latest IPD final bill is generated. Please reopen discharge first.",
                    ['ipd_no' => $latest->ipd_no]
                );
            }

            return null;
        }

        $this->assertMutable($ipd, strtolower($label));
        $this->assertChargeDateInStayWindow($ipd, $chargeDate, $label);

        return $ipd;
    }
}
