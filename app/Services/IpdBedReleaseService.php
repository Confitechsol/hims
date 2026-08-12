<?php

namespace App\Services;

use App\Models\Bed;
use App\Models\PatientBedHistory;
use Carbon\Carbon;

class IpdBedReleaseService
{
    /**
     * End active patient_bed_history rows and mark beds available (Bed.is_active = yes).
     */
    public function releaseBedsAndCloseHistory(int $ipdDetailsId, Carbon $releaseAt): void
    {
        $toDate = $releaseAt->format('Y-m-d H:i:s');

        $bedIds = PatientBedHistory::where('ipd_id', $ipdDetailsId)
            ->where('is_active', 'yes')
            ->pluck('bed_id')
            ->filter()
            ->unique()
            ->values();

        PatientBedHistory::where('ipd_id', $ipdDetailsId)
            ->where('is_active', 'yes')
            ->update([
                'to_date' => $toDate,
                'is_active' => 'no',
            ]);

        foreach ($bedIds as $bedId) {
            Bed::where('id', $bedId)->update(['is_active' => 'yes']);
        }
    }
}
