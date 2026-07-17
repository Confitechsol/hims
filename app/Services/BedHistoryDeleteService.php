<?php

namespace App\Services;

use App\Models\Bed;
use App\Models\IpdDetail;
use App\Models\PatientBedHistory;
use App\Support\BedBillingPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BedHistoryDeleteService
{
    public function __construct(
        protected BedOccupancyService $bedOccupancyService,
        protected DaywiseBedChargeService $daywiseBedChargeService
    ) {}

    /**
     * Delete the latest bed history segment for an IPD (undo last assignment/transfer).
     *
     * @return array{success: bool, message: string}
     */
    public function deleteLatest(int $ipdId, int $bedHistoryId): array
    {
        $ipd = IpdDetail::find($ipdId);
        if (!$ipd) {
            return ['success' => false, 'message' => 'IPD record not found.'];
        }

        if (($ipd->discharged ?? 'no') === 'yes') {
            return ['success' => false, 'message' => 'Cannot delete bed history for a finally discharged patient.'];
        }

        $histories = PatientBedHistory::where('ipd_id', $ipdId)
            ->orderBy('from_date')
            ->orderBy('id')
            ->get();

        if ($histories->isEmpty()) {
            return ['success' => false, 'message' => 'No bed history found for this IPD.'];
        }

        $latest = $histories->last();
        if ((int) $latest->id !== $bedHistoryId) {
            return ['success' => false, 'message' => 'Only the latest bed assignment can be deleted.'];
        }

        $deletedBedId = (int) $latest->bed_id;
        $deletedFrom = Carbon::parse($latest->from_date);
        $deletedTo = $latest->to_date ? Carbon::parse($latest->to_date) : null;

        DB::beginTransaction();
        try {
            if ($histories->count() === 1) {
                $this->deleteOnlySegment($ipd, $latest, $deletedBedId, $deletedFrom, $deletedTo);
            } else {
                $previous = $histories->slice(-2, 1)->first();
                $this->deleteLatestAndRestorePrevious(
                    $ipd,
                    $latest,
                    $previous,
                    $deletedBedId,
                    $deletedFrom,
                    $deletedTo
                );
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Latest bed assignment deleted successfully. Estimate and final bill will reflect the changes.',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to delete bed history: ' . $e->getMessage(),
            ];
        }
    }

    protected function deleteOnlySegment(
        IpdDetail $ipd,
        PatientBedHistory $latest,
        int $deletedBedId,
        Carbon $deletedFrom,
        ?Carbon $deletedTo
    ): void {
        $this->daywiseBedChargeService->purgeStoredChargesForSegment(
            (int) $ipd->id,
            $deletedFrom,
            $deletedTo
        );

        if ($deletedBedId) {
            Bed::where('id', $deletedBedId)->update(['is_active' => 'yes']);
        }

        $latest->delete();

        $ipd->bed = null;
        $ipd->bed_group_id = null;
        $ipd->save();
    }

    protected function deleteLatestAndRestorePrevious(
        IpdDetail $ipd,
        PatientBedHistory $latest,
        PatientBedHistory $previous,
        int $deletedBedId,
        Carbon $deletedFrom,
        ?Carbon $deletedTo
    ): void {
        $previousBedId = (int) $previous->bed_id;
        $previousFrom = Carbon::parse($previous->from_date);

        $availability = $this->bedOccupancyService->checkAvailability(
            $previousBedId,
            $previousFrom,
            null,
            (int) $previous->id,
            (int) $ipd->id
        );
        if (!$availability['available']) {
            throw new \RuntimeException($availability['message'] ?? 'Previous bed is no longer available.');
        }

        $this->daywiseBedChargeService->purgeStoredChargesForSegment(
            (int) $ipd->id,
            $deletedFrom,
            $deletedTo
        );

        if ($deletedBedId) {
            Bed::where('id', $deletedBedId)->update(['is_active' => 'yes']);
        }

        $latest->delete();

        $previous->is_active = 'yes';
        $previous->to_date = null;
        $previous->save();

        if ($previousBedId) {
            Bed::where('id', $previousBedId)->update(['is_active' => 'no']);
        }

        $ipd->bed = $previousBedId;
        $ipd->bed_group_id = $previous->bed_group_id;
        $ipd->save();

        $bedChargeRate = $this->daywiseBedChargeService->resolveBedChargeRate(
            (int) $ipd->id,
            (int) $previous->bed_group_id,
            $previousFrom,
            null
        );

        $this->daywiseBedChargeService->syncStoredChargesForBedSegment(
            $ipd,
            $bedChargeRate,
            (int) $previous->bed_group_id,
            $previousBedId,
            $previousFrom,
            null
        );
    }
}
