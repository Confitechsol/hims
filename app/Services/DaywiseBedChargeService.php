<?php

namespace App\Services;

use App\Models\IpdDaywiseBedCharge;
use App\Models\PatientBedHistory;
use App\Models\BedGroup;
use App\Models\IpdDetail;
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

            // Calculate day period (10 AM to next 10 AM)
            $dayPeriod = $this->getDayPeriod($chargeDate);
            $startTime = $dayPeriod['start'];
            $endTime = $dayPeriod['end'];

            Log::info("Calculating bed charges for IPD ID: {$ipdId}, Date: {$chargeDate}", [
                'period_start' => $startTime->format('Y-m-d H:i:s'),
                'period_end' => $endTime->format('Y-m-d H:i:s'),
            ]);

            // Get last bed assignment for this period
            $lastBed = $this->getLastBedForPeriod($ipdId, $startTime, $endTime);

            if (!$lastBed) {
                Log::info("No bed assignment found for IPD ID: {$ipdId} in period", [
                    'charge_date' => $chargeDate,
                ]);
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'No bed assignment found for this period',
                ];
            }

            // Get bed charge
            $bedCharge = $this->getBedCharge($lastBed->bed_group_id);

            if ($bedCharge === null || $bedCharge <= 0) {
                Log::warning("Invalid bed charge for bed group ID: {$lastBed->bed_group_id}", [
                    'ipd_id' => $ipdId,
                    'charge_date' => $chargeDate,
                ]);
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Invalid bed charge amount',
                ];
            }

            // Store or update daywise charge
            $result = $this->storeDaywiseCharge([
                'hospital_id' => $ipd->hospital_id,
                'branch_id' => $ipd->branch_id,
                'ipd_id' => $ipdId,
                'case_reference_id' => $ipd->case_reference_id,
                'patient_id' => $ipd->patient_id,
                'charge_date' => $chargeDate,
                'bed_group_id' => $lastBed->bed_group_id,
                'bed_id' => $lastBed->bed_id,
                'bed_charge' => $bedCharge,
                'is_active' => 'yes',
            ]);

            DB::commit();

            Log::info("Successfully calculated bed charge for IPD ID: {$ipdId}", [
                'charge_date' => $chargeDate,
                'bed_charge' => $bedCharge,
                'bed_group_id' => $lastBed->bed_group_id,
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
     * Get day period (10 AM to next 10 AM)
     *
     * @param string $date Date in Y-m-d format
     * @return array Array with 'start' and 'end' Carbon instances
     */
    public function getDayPeriod($date)
    {
        $dateCarbon = Carbon::parse($date);

        // Start: Previous day 10:00 AM
        $start = $dateCarbon->copy()->subDay()->setTime(10, 0, 0);

        // End: Current day 10:00 AM
        $end = $dateCarbon->copy()->setTime(10, 0, 0);

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
        return PatientBedHistory::where('ipd_id', $ipdId)
            ->where(function ($query) use ($startTime, $endTime) {
                // Bed assignment started within the period
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('from_date', '>=', $startTime)
                      ->where('from_date', '<', $endTime);
                })
                // OR bed assignment was active during the period (to_date is null or after start)
                ->orWhere(function ($q) use ($startTime, $endTime) {
                    $q->where('from_date', '<=', $startTime)
                      ->where(function ($subQ) use ($endTime) {
                          $subQ->whereNull('to_date')
                               ->orWhere('to_date', '>=', $endTime);
                      });
                });
            })
            ->where('is_active', 'yes')
            ->orderBy('from_date', 'desc')
            ->first();
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
