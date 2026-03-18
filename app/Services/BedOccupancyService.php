<?php

namespace App\Services;

use App\Models\PatientBedHistory;
use Carbon\Carbon;

class BedOccupancyService
{
    /**
     * Find a conflicting bed history record for the given bed and period.
     *
     * A conflict exists if any other history record for the same bed overlaps
     * the requested [from, to] range.
     *
     * - If $to is null, it's treated as "open-ended" (up to now/end of day).
     * - You can exclude a specific history or IPD when checking (for edits).
     *
     * @param  int         $bedId
     * @param  Carbon      $from
     * @param  Carbon|null $to
     * @param  int|null    $excludeHistoryId
     * @param  int|null    $excludeIpdId
     * @return PatientBedHistory|null
     */
    public function findConflict(
        int $bedId,
        Carbon $from,
        ?Carbon $to = null,
        ?int $excludeHistoryId = null,
        ?int $excludeIpdId = null
    ): ?PatientBedHistory {
        $myTo = $to ? $to->copy() : Carbon::now()->endOfDay();

        return PatientBedHistory::where('bed_id', $bedId)
            ->when($excludeHistoryId, function ($q) use ($excludeHistoryId) {
                $q->where('id', '!=', $excludeHistoryId);
            })
            ->when($excludeIpdId, function ($q) use ($excludeIpdId) {
                $q->where('ipd_id', '!=', $excludeIpdId);
            })
            ->orderBy('from_date')
            ->get()
            ->first(function (PatientBedHistory $adj) use ($from, $myTo) {
                $adjFrom = Carbon::parse($adj->from_date);
                $adjTo   = $adj->to_date ? Carbon::parse($adj->to_date) : Carbon::now()->endOfDay();
                // Overlap if our start is before their end AND our end is after their start
                return $from->lt($adjTo) && $myTo->gt($adjFrom);
            });
    }

    /**
     * Check availability and return a human-friendly error message if occupied.
     *
     * @return array{available: bool, message: string|null}
     */
    public function checkAvailability(
        int $bedId,
        Carbon $from,
        ?Carbon $to = null,
        ?int $excludeHistoryId = null,
        ?int $excludeIpdId = null
    ): array {
        $conflict = $this->findConflict($bedId, $from, $to, $excludeHistoryId, $excludeIpdId);

        if (! $conflict) {
            return ['available' => true, 'message' => null];
        }

        $fromStr = $from->format('d/m/Y h:i A');
        $occupiedFrom = Carbon::parse($conflict->from_date)->format('d/m/Y h:i A');
        $occupiedTo   = $conflict->to_date
            ? Carbon::parse($conflict->to_date)->format('d/m/Y h:i A')
            : 'present';

        $msg = "Selected bed is already occupied in the period {$occupiedFrom} to {$occupiedTo} "
             . "and cannot be assigned at {$fromStr}. Please choose another bed or adjust the dates.";

        return ['available' => false, 'message' => $msg];
    }
}

