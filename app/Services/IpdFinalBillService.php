<?php

namespace App\Services;

use App\Models\DischargeCard;
use App\Models\IpdDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class IpdFinalBillService
{
    public function __construct(
        protected IpdBedReleaseService $bedReleaseService
    ) {}

    public function isGenerated(IpdDetail $ipd): bool
    {
        return ! empty($ipd->final_bill_generated_at);
    }

    /**
     * Status for Generate Final Bill confirm (no post-discharge / extra-bed logic).
     *
     * @return array<string, mixed>
     */
    public function preview(IpdDetail $ipd): array
    {
        $this->assertCanPreview($ipd);

        $dischargeAt = $this->resolveDischargeAt($ipd);
        $alreadyGenerated = $this->isGenerated($ipd);

        return [
            'discharged' => true,
            'already_generated' => $alreadyGenerated,
            'is_insurance' => $ipd->isInsuranceBilling(),
            'final_approval_amount' => (float) ($ipd->final_approval_amount ?? 0),
            'discharge_at' => $dischargeAt->format('Y-m-d H:i:s'),
            'discharge_at_display' => $dischargeAt->format('d/m/Y h:i A'),
            'prompt_needed' => false,
            'message' => $alreadyGenerated
                ? 'Final bill already generated. You can download it.'
                : 'Generating the final bill will release the bed. Bed charges stop at discharge date and time.',
        ];
    }

    /**
     * Lock final bill and release bed at discharge datetime (no post-discharge bed days).
     */
    public function generate(IpdDetail $ipd): IpdDetail
    {
        $preview = $this->preview($ipd);

        if (! empty($preview['already_generated'])) {
            throw new RuntimeException('Final bill has already been generated for this IPD.');
        }

        $dischargeAt = Carbon::parse($preview['discharge_at']);

        DB::transaction(function () use ($ipd, $dischargeAt) {
            $this->bedReleaseService->releaseBedsAndCloseHistory((int) $ipd->id, $dischargeAt);

            $ipd->final_bill_generated_at = Carbon::now();
            $ipd->final_bill_generated_by = Auth::id();
            $ipd->include_post_discharge_bed_charge = false;
            $ipd->physical_release_at = $dischargeAt;
            $ipd->save();
        });

        return $ipd->fresh();
    }

    /**
     * Billing always ends at clinical discharge (never post-discharge occupancy).
     */
    public function billingEndAt(IpdDetail $ipd): Carbon
    {
        return $this->resolveDischargeAt($ipd);
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
}
