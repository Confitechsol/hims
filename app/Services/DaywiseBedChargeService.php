<?php

namespace App\Services;

use App\Models\IpdDaywiseBedCharge;
use App\Models\PatientBedHistory;
use App\Models\BedGroup;
use App\Models\IpdDetail;
use App\Services\InsuranceDischargeBedChargeService;
use App\Support\BedBillingPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DaywiseBedChargeService
{
    /**
     * Calculate and store daywise bed charges for an IPD patient
     *
     * @param int $ipdId IPD patient ID
     * @param string|null $chargeDate Date for which to calculate charges (Y-m-d format). If null, calculates for previous day.
     * @return array Result with status and message
     */
    public function calculateDaywiseCharges($ipdId, $chargeDate = null)
    {
        try {
            DB::beginTransaction();

            // Get IPD details
            $ipd = IpdDetail::find($ipdId);
            if (!$ipd) {
                throw new \Exception("IPD record not found: {$ipdId}");
            }

            // Determine charge date (previous day if not provided)
            if ($chargeDate === null) {
                $chargeDate = Carbon::yesterday()->format('Y-m-d');
            }

            // Calculate day period (boundary @ config, default 11:00 → next day 11:00)
            $dayPeriod = $this->getDayPeriod($chargeDate);
            $startTime = $dayPeriod['start'];
            $endTime = $dayPeriod['end'];

            $admissionAt = Carbon::parse($ipd->date);
            $billableAnchor = BedBillingPeriod::billableAnchorAt($admissionAt);
            $effective = BedBillingPeriod::effectiveWindow($startTime, $endTime, $billableAnchor);
            if ($effective === null) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'No billable bed window for this date (before admission).',
                ];
            }
            [$effectiveStart, $effectiveEnd] = $effective;
            $startTime = $effectiveStart;
            $endTime = $effectiveEnd;

            Log::info("Calculating bed charges for IPD ID: {$ipdId}, Date: {$chargeDate}", [
                'period_start' => $startTime->format('Y-m-d H:i:s'),
                'period_end' => $endTime->format('Y-m-d H:i:s'),
            ]);

            // Get last bed assignment for this period
            $lastBed = $this->getLastBedForPeriod($ipdId, $startTime, $endTime);

            if (!$lastBed) {
                // Log all bed history for debugging
                $allBeds = PatientBedHistory::where('ipd_id', $ipdId)
                    ->where('is_active', 'yes')
                    ->orderBy('from_date', 'desc')
                    ->get(['id', 'from_date', 'to_date', 'bed_group_id', 'bed_id']);
                
                Log::info("No bed assignment found for IPD ID: {$ipdId} in period", [
                    'charge_date' => $chargeDate,
                    'period_start' => $startTime->format('Y-m-d H:i:s'),
                    'period_end' => $endTime->format('Y-m-d H:i:s'),
                    'available_beds' => $allBeds->map(function($bed) {
                        return [
                            'from_date' => $bed->from_date,
                            'to_date' => $bed->to_date,
                            'bed_group_id' => $bed->bed_group_id,
                        ];
                    })->toArray(),
                ]);
                
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'No bed assignment found for this period',
                ];
            }

            $bedGroup = BedGroup::find($lastBed->bed_group_id);
            if (!$bedGroup) {
                Log::warning("Bed group not found for bed group ID: {$lastBed->bed_group_id}", [
                    'ipd_id' => $ipdId,
                    'charge_date' => $chargeDate,
                ]);
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Bed group not found',
                ];
            }

            $segmentFrom = Carbon::parse($lastBed->from_date);
            $segmentTo = $lastBed->to_date ? Carbon::parse($lastBed->to_date) : null;
            $bedChargeRate = $this->resolveBedChargeRate(
                $ipdId,
                (int) $lastBed->bed_group_id,
                $segmentFrom,
                $segmentTo
            );
            
            if ($bedChargeRate <= 0) {
                Log::warning("Invalid bed charge rate for bed group ID: {$lastBed->bed_group_id}", [
                    'ipd_id' => $ipdId,
                    'charge_date' => $chargeDate,
                    'bed_charge_rate' => $bedChargeRate,
                ]);
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Invalid bed charge rate',
                ];
            }

            $periodDates = BedBillingPeriod::periodStorageDatesForChargeDay(
                Carbon::parse($chargeDate)->startOfDay(),
                $billableAnchor
            );
            $periodStartDate = $periodDates['period_start_date'];
            $periodEndDate = $periodDates['period_end_date'];
            
            // Calculate total bed charge (rate × number of days)
            // For 1 day period, bed_charge = bed_charge_rate × 1
            $bedCharge = $bedChargeRate * 1; // Always 1 day for this period
            
            // Store or update daywise charge
            $result = $this->storeDaywiseCharge([
                'hospital_id' => $ipd->hospital_id,
                'branch_id' => $ipd->branch_id,
                'ipd_id' => $ipdId,
                'case_reference_id' => $ipd->case_reference_id,
                'patient_id' => $ipd->patient_id,
                'charge_date' => $chargeDate,
                'period_start_date' => $periodStartDate,
                'period_end_date' => $periodEndDate,
                'bed_group_id' => $lastBed->bed_group_id,
                'bed_id' => $lastBed->bed_id,
                'bed_charge' => $bedCharge, // Total charge for the period
                'bed_charge_rate' => $bedChargeRate,
                'no_of_days' => 1, // Always 1 for each day period (boundary → next day boundary)
                'is_active' => 'yes',
            ]);

            DB::commit();

            Log::info("Successfully calculated bed charge for IPD ID: {$ipdId}", [
                'charge_date' => $chargeDate,
                'period_start_date' => $periodStartDate,
                'period_end_date' => $periodEndDate,
                'bed_charge' => $bedCharge,
                'bed_charge_rate' => $bedChargeRate,
                'no_of_days' => 1,
                'bed_group_id' => $lastBed->bed_group_id,
                'bed_group_name' => $bedGroup->name ?? 'N/A',
            ]);

            return [
                'success' => true,
                'message' => 'Bed charge calculated successfully',
                'data' => $result,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error calculating bed charges for IPD ID: {$ipdId}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'charge_date' => $chargeDate ?? 'N/A',
            ]);

            return [
                'success' => false,
                'message' => 'Error calculating bed charges: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Nominal day period for a charge calendar day (see {@see BedBillingPeriod::windowForChargeCalendarDay}).
     *
     * @param string $date Date in Y-m-d format
     * @return array Array with 'start' and 'end' Carbon instances
     */
    public function getDayPeriod($date)
    {
        $dateCarbon = Carbon::parse($date)->startOfDay();
        [$start, $end] = BedBillingPeriod::windowForChargeCalendarDay($dateCarbon);

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * Get last bed assignment for a given period
     *
     * @param int $ipdId IPD patient ID
     * @param Carbon $startTime Period start time
     * @param Carbon $endTime Period end time
     * @return PatientBedHistory|null Last bed assignment or null
     */
    public function getLastBedForPeriod($ipdId, $startTime, $endTime)
    {
        // Find beds that were active during the period
        // A bed is active during the period if:
        // 1. It started before or during the period AND
        // 2. It ended after the period started (or is still active - to_date is NULL)
        return PatientBedHistory::where('ipd_id', $ipdId)
            ->where('is_active', 'yes')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // Bed assignment started before or during the period
                    $q->where('from_date', '<=', $endTime)
                      // AND either still active (to_date is NULL) or ended after period started
                      ->where(function ($subQ) use ($startTime) {
                          $subQ->whereNull('to_date')
                               ->orWhere('to_date', '>=', $startTime);
                      });
                });
            })
            ->orderBy('from_date', 'desc')
            ->first();
    }

    /**
     * Resolve per-day bed rate: custom admit/transfer rate for the bed segment, else bed group master.
     */
    public function resolveBedChargeRate(
        int $ipdId,
        int $bedGroupId,
        Carbon $segmentFrom,
        ?Carbon $segmentTo = null
    ): float {
        $firstChargeDay = BedBillingPeriod::firstChargeCalendarDayFromAnchorDate($segmentFrom);
        $query = IpdDaywiseBedCharge::where('ipd_id', $ipdId)
            ->where('bed_group_id', $bedGroupId)
            ->where('is_active', 'yes')
            ->whereDate('charge_date', '>=', $firstChargeDay->format('Y-m-d'));

        if ($segmentTo !== null) {
            $query->whereDate(
                'charge_date',
                '<=',
                BedBillingPeriod::chargeLabelDayForMoment($segmentTo)->format('Y-m-d')
            );
        }

        $custom = $query->orderByDesc('charge_date')->get()->first(function ($row) {
            return (float) ($row->bed_charge_rate ?? $row->bed_charge ?? 0) > 0;
        });

        if ($custom) {
            $rate = (float) ($custom->bed_charge_rate ?? $custom->bed_charge ?? 0);
            if ($rate > 0) {
                return $rate;
            }
        }

        $bedGroup = BedGroup::find($bedGroupId);

        return (float) ($bedGroup->bed_cost ?? 0);
    }

    /**
     * Write/update ipd_daywise_bed_charges for every billable day in a bed segment at the given rate.
     */
    public function syncStoredChargesForBedSegment(
        IpdDetail $ipd,
        float $bedChargeRate,
        int $bedGroupId,
        int $bedId,
        Carbon $segmentFrom,
        ?Carbon $segmentTo = null
    ): int {
        if ($bedChargeRate <= 0) {
            return 0;
        }

        if ($segmentTo === null && $ipd->discharged === 'yes' && ! empty($ipd->discharged_date)) {
            $endAt = app(InsuranceDischargeBedChargeService::class)->resolveDischargeAt($ipd)
                ?? Carbon::parse($ipd->discharged_date);
        } elseif ($segmentTo !== null) {
            $endAt = $segmentTo->copy();
        } else {
            $endAt = Carbon::now();
        }

        $insuranceBedService = app(InsuranceDischargeBedChargeService::class);
        $skipDischargeChargeDate = $insuranceBedService->dischargeChargeDateToExclude($ipd, $endAt);

        $billableAnchor = BedBillingPeriod::billableAnchorAt($segmentFrom);
        $firstChargeDay = BedBillingPeriod::firstChargeCalendarDayFromAnchorDate($segmentFrom);
        $lastChargeDay = BedBillingPeriod::chargeLabelDayForMoment($endAt);
        if ($endAt->gt($billableAnchor) && $lastChargeDay->lt($firstChargeDay)) {
            $lastChargeDay = $firstChargeDay->copy();
        }
        $segmentCalendarDay = $segmentFrom->copy()->startOfDay();
        $updated = 0;
        $current = $firstChargeDay->copy();

        while ($current->lte($lastChargeDay)) {
            $chargeDate = $current->format('Y-m-d');

            if ($skipDischargeChargeDate !== null && $chargeDate === $skipDischargeChargeDate) {
                IpdDaywiseBedCharge::where('ipd_id', $ipd->id)
                    ->where('charge_date', $chargeDate)
                    ->delete();
                $current->addDay();
                continue;
            }

            [$periodStart, $periodEnd] = BedBillingPeriod::windowForChargeCalendarDay($current->copy()->startOfDay());
            $isFirstSegmentDay = $current->isSameDay($firstChargeDay);

            if ($periodStart->copy()->startOfDay()->lt($segmentCalendarDay)) {
                $current->addDay();
                continue;
            }

            if ($periodEnd->lte($billableAnchor) || (! $isFirstSegmentDay && $periodStart->gte($endAt))) {
                $current->addDay();
                continue;
            }

            $periodDates = BedBillingPeriod::periodStorageDatesForChargeDay(
                $current->copy()->startOfDay(),
                $billableAnchor
            );
            if ($periodDates === null) {
                $current->addDay();
                continue;
            }

            $this->storeDaywiseCharge([
                'hospital_id' => $ipd->hospital_id,
                'branch_id' => $ipd->branch_id,
                'ipd_id' => $ipd->id,
                'case_reference_id' => $ipd->case_reference_id,
                'patient_id' => $ipd->patient_id,
                'charge_date' => $chargeDate,
                'period_start_date' => $periodDates['period_start_date'],
                'period_end_date' => $periodDates['period_end_date'],
                'bed_group_id' => $bedGroupId,
                'bed_id' => $bedId,
                'bed_charge' => $bedChargeRate,
                'bed_charge_rate' => $bedChargeRate,
                'no_of_days' => 1,
                'is_active' => 'yes',
            ]);
            $updated++;
            $current->addDay();
        }

        // Post-midnight grace: no billable day yet, but keep the segment rate on the first
        // charge label day so estimate/billing can resolve the admission bed charge after 11 AM.
        if ($updated === 0 && $bedChargeRate > 0 && $endAt->lte($billableAnchor)) {
            $periodDates = BedBillingPeriod::periodStorageDatesForChargeDay(
                $firstChargeDay->copy()->startOfDay(),
                $segmentFrom
            );
            if ($periodDates !== null) {
                $this->storeDaywiseCharge([
                    'hospital_id' => $ipd->hospital_id,
                    'branch_id' => $ipd->branch_id,
                    'ipd_id' => $ipd->id,
                    'case_reference_id' => $ipd->case_reference_id,
                    'patient_id' => $ipd->patient_id,
                    'charge_date' => $firstChargeDay->format('Y-m-d'),
                    'period_start_date' => $periodDates['period_start_date'],
                    'period_end_date' => $periodDates['period_end_date'],
                    'bed_group_id' => $bedGroupId,
                    'bed_id' => $bedId,
                    'bed_charge' => $bedChargeRate,
                    'bed_charge_rate' => $bedChargeRate,
                    'no_of_days' => 1,
                    'is_active' => 'yes',
                ]);
                $updated = 1;
            }
        }

        return $updated;
    }

    /**
     * Remove stored daywise bed charges for a deleted bed segment date range.
     */
    public function purgeStoredChargesForSegment(int $ipdId, Carbon $segmentFrom, ?Carbon $segmentTo = null): int
    {
        $endAt = $segmentTo ? $segmentTo->copy() : Carbon::now();
        $firstChargeDay = BedBillingPeriod::firstChargeCalendarDayFromAnchorDate($segmentFrom);
        $lastChargeDay = BedBillingPeriod::chargeLabelDayForMoment($endAt);

        if ($firstChargeDay->gt($lastChargeDay)) {
            return 0;
        }

        $deleted = 0;
        $current = $firstChargeDay->copy();

        while ($current->lte($lastChargeDay)) {
            $deleted += IpdDaywiseBedCharge::where('ipd_id', $ipdId)
                ->whereDate('charge_date', $current->format('Y-m-d'))
                ->delete();
            $current->addDay();
        }

        return $deleted;
    }

    /**
     * Get bed charge from bed group
     *
     * @param int|null $bedGroupId Bed group ID
     * @return float|null Bed charge amount or null
     */
    public function getBedCharge($bedGroupId)
    {
        if (!$bedGroupId) {
            return null;
        }

        $bedGroup = BedGroup::find($bedGroupId);
        if (!$bedGroup) {
            return null;
        }

        return $bedGroup->bed_cost ?? 0.00;
    }

    /**
     * Store or update daywise bed charge
     *
     * @param array $data Charge data
     * @return IpdDaywiseBedCharge Created or updated record
     */
    public function storeDaywiseCharge(array $data)
    {
        return IpdDaywiseBedCharge::updateOrCreate(
            [
                'ipd_id' => $data['ipd_id'],
                'charge_date' => $data['charge_date'],
            ],
            $data
        );
    }

    /**
     * Calculate charges for multiple IPD patients
     *
     * @param array $ipdIds Array of IPD IDs
     * @param string|null $chargeDate Date for which to calculate charges
     * @return array Summary of results
     */
    public function calculateChargesForMultiplePatients(array $ipdIds, $chargeDate = null)
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($ipdIds as $ipdId) {
            $result = $this->calculateDaywiseCharges($ipdId, $chargeDate);
            if ($result['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
                $results['errors'][] = [
                    'ipd_id' => $ipdId,
                    'message' => $result['message'],
                ];
            }
        }

        return $results;
    }

    /**
     * Calculate daywise charges for an IPD over a date range (inclusive)
     *
     * @param int $ipdId
     * @param string $startDate Y-m-d
     * @param string $endDate Y-m-d
     * @return array summary with counts and errors
     */
    public function calculateChargesForDateRange($ipdId, $startDate, $endDate)
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
            'ipd_id' => $ipdId,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        try {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            if ($end->lt($start)) {
                throw new \Exception('End date must be after or equal to start date');
            }

            $current = $start->copy();
            while ($current->lte($end)) {
                $date = $current->format('Y-m-d');
                $res = $this->calculateDaywiseCharges($ipdId, $date);
                if (!empty($res['success'])) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = [
                        'date' => $date,
                        'message' => $res['message'] ?? 'failed',
                    ];
                }
                $current->addDay();
            }
        } catch (\Exception $e) {
            $results['failed']++;
            $results['errors'][] = ['message' => $e->getMessage()];
        }

        return $results;
    }

    /**
     * Get all active IPD patients
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveIpdPatients()
    {
        return IpdDetail::where('discharged', 'no')
            ->orWhereNull('discharged')
            ->get(['id', 'patient_id', 'case_reference_id', 'hospital_id', 'branch_id']);
    }
}
