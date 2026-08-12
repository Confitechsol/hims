<?php

namespace App\Services;

use App\Http\Controllers\IpdBillingController;
use App\Models\DischargeCard;
use App\Models\IpdDetail;
use App\Models\IpdPackage;
use App\Models\PatientBedHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class IpdFinalBillService
{
    public function __construct(
        protected IpdBedReleaseService $bedReleaseService,
        protected DaywiseBedChargeService $daywiseBedChargeService
    ) {}

    public function isGenerated(IpdDetail $ipd): bool
    {
        return ! empty($ipd->final_bill_generated_at);
    }

    /**
     * Extra bed charge between clinical discharge and proposed physical release (now).
     *
     * @return array<string, mixed>
     */
    public function preview(IpdDetail $ipd): array
    {
        $this->assertCanPreview($ipd);

        $dischargeAt = $this->resolveDischargeAt($ipd);
        $releaseAt = Carbon::now();
        $alreadyGenerated = $this->isGenerated($ipd);

        $packageAmount = (float) IpdPackage::where('ipd_id', $ipd->id)
            ->where('status', 'applied')
            ->sum('final_amount');

        $bedAtDischarge = 0.0;
        $bedAtRelease = 0.0;
        $extraAmount = 0.0;

        if ($packageAmount <= 0) {
            $billing = app(IpdBillingController::class);
            $bedAtDischarge = (float) ($billing->computeBedChargesSnapshot(
                $ipd->id,
                $dischargeAt->format('Y-m-d H:i:s')
            )['total'] ?? 0);
            $bedAtRelease = (float) ($billing->computeBedChargesSnapshot(
                $ipd->id,
                $releaseAt->format('Y-m-d H:i:s'),
                $releaseAt
            )['total'] ?? 0);
            $extraAmount = round(max(0, $bedAtRelease - $bedAtDischarge), 2);
        }

        return [
            'discharged' => true,
            'already_generated' => $alreadyGenerated,
            'is_insurance' => $ipd->isInsuranceBilling(),
            'final_approval_amount' => (float) ($ipd->final_approval_amount ?? 0),
            'discharge_at' => $dischargeAt->format('Y-m-d H:i:s'),
            'discharge_at_display' => $dischargeAt->format('d/m/Y h:i A'),
            'release_at' => $releaseAt->format('Y-m-d H:i:s'),
            'release_at_display' => $releaseAt->format('d/m/Y h:i A'),
            'bed_at_discharge' => round($bedAtDischarge, 2),
            'bed_at_release' => round($bedAtRelease, 2),
            'extra_bed_amount' => $extraAmount,
            'prompt_needed' => $extraAmount > 0.009,
            'package_applied' => $packageAmount > 0,
            'message' => $alreadyGenerated
                ? 'Final bill already generated. You can download it.'
                : ($extraAmount > 0.009
                    ? 'Extra bed charge applies from discharge to now. Confirm whether to include it on the final bill.'
                    : 'No extra bed charge. Generating the final bill will release the bed.'),
        ];
    }

    /**
     * Persist extra-bed choice, optionally sync extra days, release bed, lock final bill.
     */
    public function generate(IpdDetail $ipd, bool $includeExtraBed): IpdDetail
    {
        $preview = $this->preview($ipd);

        if (! empty($preview['already_generated'])) {
            throw new RuntimeException('Final bill has already been generated for this IPD.');
        }

        $releaseAt = Carbon::parse($preview['release_at']);
        $include = ! empty($preview['prompt_needed']) && $includeExtraBed;

        DB::transaction(function () use ($ipd, $include, $releaseAt) {
            if ($include) {
                $this->syncBedChargesThrough($ipd, $releaseAt);
            }

            $this->bedReleaseService->releaseBedsAndCloseHistory((int) $ipd->id, $releaseAt);

            $ipd->final_bill_generated_at = Carbon::now();
            $ipd->final_bill_generated_by = Auth::id();
            $ipd->include_post_discharge_bed_charge = $include;
            $ipd->physical_release_at = $releaseAt;
            $ipd->save();
        });

        return $ipd->fresh();
    }

    public function billingEndAt(IpdDetail $ipd): Carbon
    {
        $dischargeAt = $this->resolveDischargeAt($ipd);

        if ($this->isGenerated($ipd)
            && $ipd->include_post_discharge_bed_charge
            && ! empty($ipd->physical_release_at)
        ) {
            return Carbon::parse($ipd->physical_release_at);
        }

        return $dischargeAt;
    }

    public function resolveDischargeAt(IpdDetail $ipd): Carbon
    {
        $fromCard = app(InsuranceDischargeBedChargeService::class)->resolveDischargeAt($ipd);
        if ($fromCard) {
            return $fromCard;
        }

        if (! empty($ipd->discharged_date)) {
            return Carbon::parse($ipd->discharged_date)->startOfDay();
        }

        throw new RuntimeException('Discharge date is missing. Please complete the discharge card first.');
    }

    protected function assertCanPreview(IpdDetail $ipd): void
    {
        if (($ipd->discharged ?? 'no') !== 'yes') {
            throw new RuntimeException('Patient is not discharged. Please discharge the patient first.');
        }

        $card = DischargeCard::where('ipd_details_id', $ipd->id)->orderByDesc('id')->first();
        if (! $card || empty($card->discharge_date)) {
            throw new RuntimeException('Discharge card or discharge date is missing.');
        }
    }

    protected function syncBedChargesThrough(IpdDetail $ipd, Carbon $endAt): void
    {
        $history = PatientBedHistory::with('bedGroup')
            ->where('ipd_id', $ipd->id)
            ->orderByDesc('from_date')
            ->orderByDesc('id')
            ->first();

        if (! $history || ! $history->bed_group_id || ! $history->bed_id) {
            return;
        }

        $fromDate = Carbon::parse($history->from_date);
        $bedChargeRate = (float) ($history->bedGroup->bed_cost ?? 0);
        if ($bedChargeRate <= 0) {
            $bedChargeRate = $this->daywiseBedChargeService->resolveBedChargeRate(
                (int) $ipd->id,
                (int) $history->bed_group_id,
                $fromDate,
                $endAt,
                (int) $history->bed_id
            );
        }
        if ($bedChargeRate <= 0) {
            return;
        }

        $this->daywiseBedChargeService->syncStoredChargesForBedSegment(
            $ipd->fresh(),
            $bedChargeRate,
            (int) $history->bed_group_id,
            (int) $history->bed_id,
            $fromDate,
            $endAt,
            $endAt
        );
    }
}
