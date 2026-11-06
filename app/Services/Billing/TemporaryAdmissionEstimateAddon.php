<?php

namespace App\Services\Billing;

use App\Exceptions\InvalidTemporaryAdmissionException;
use App\Models\IpdDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Estimate-only as-of time for bed/outstanding calculation on the estimate PDF.
 *
 * The selected time is the billing end ("what will charges look like at this time?").
 * Real admission is never changed. Approval and final bills ignore this add-on.
 * Query param kept as temp_admission_at for existing UI; estimate_at is also accepted.
 */
class TemporaryAdmissionEstimateAddon
{
    public function __construct(private TemporaryAdmissionEstimateContext $context)
    {
    }

    /**
     * Resolve estimate as-of datetime, or null when the estimate should use "now".
     * Never activates for approval bills.
     */
    public function resolve(Request $request, IpdDetail $ipd, bool $isApprovalBill): ?Carbon
    {
        if ($isApprovalBill) {
            return null;
        }

        $raw = $request->query('estimate_at', $request->query('temp_admission_at'));
        if ($raw === null) {
            return null;
        }

        if (! is_string($raw) && ! is_numeric($raw)) {
            throw new InvalidTemporaryAdmissionException('Estimate time is not a valid date.');
        }

        $raw = trim((string) $raw);
        if ($raw === '') {
            return null;
        }

        if (strlen($raw) > 32) {
            throw new InvalidTemporaryAdmissionException('Estimate time is not a valid date.');
        }

        try {
            $parsed = Carbon::parse($raw);
        } catch (\Throwable $e) {
            throw new InvalidTemporaryAdmissionException('Estimate time is not a valid date.');
        }

        if ($parsed->year < 2000 || $parsed->year > 2100) {
            throw new InvalidTemporaryAdmissionException('Estimate time is outside the allowed range.');
        }

        $now = Carbon::now();
        $min = $now->copy()->startOfMinute();
        $max = $now->copy()->endOfDay()->second(0);

        if (! $parsed->isSameDay($now)) {
            throw new InvalidTemporaryAdmissionException('Estimate time must be on the current date.');
        }

        if ($parsed->lt($min)) {
            throw new InvalidTemporaryAdmissionException('Estimate time cannot be before the current time.');
        }

        if ($parsed->gt($max)) {
            throw new InvalidTemporaryAdmissionException('Estimate time cannot be after 11:59 PM today.');
        }

        // Same minute as "now" → treat as use-current (no special as-of override needed).
        if ($parsed->format('Y-m-d H:i') === $now->format('Y-m-d H:i')) {
            return null;
        }

        Log::info('Estimate as-of time applied for bed charge calculation (not saved).', [
            'ipd_id' => $ipd->id,
            'ipd_no' => $ipd->ipd_no,
            'real_admission' => (string) ($ipd->date ?? ''),
            'estimate_as_of' => $parsed->format('Y-m-d H:i:s'),
        ]);

        return $parsed;
    }

    /**
     * @deprecated Admission is never overridden. Prefer resolve() as billing end in exportEstimate.
     * Kept so existing callers/tests that invoke run() still execute the callback safely.
     *
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function run(Request $request, IpdDetail $ipd, bool $isApprovalBill, callable $callback)
    {
        // Do not set TemporaryAdmissionEstimateContext — real admission must drive bed anchor.
        $this->resolve($request, $ipd, $isApprovalBill);

        return $callback();
    }
}
