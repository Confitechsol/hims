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
 * Selected time = billing end ("charges as of this moment").
 * Allowed range: admission datetime → 11:59 PM of the current calendar day
 * (past after admission, and later today up to 23:59, are OK).
 * Real admission is never changed. Approval and final bills ignore this add-on.
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
        // Client rule: may increase estimate time up to 11:59 PM of the current date
        // so bed cycles (e.g. crossing 11:00) can be projected for today.
        $max = $now->copy()->endOfDay()->startOfMinute(); // 23:59 today
        $nowMinute = $now->copy()->startOfMinute();

        $admissionRaw = $ipd->date ?? $ipd->created_at ?? null;
        if (! $admissionRaw) {
            throw new InvalidTemporaryAdmissionException('Admission date is missing for this IPD.');
        }

        try {
            $admissionAt = Carbon::parse($admissionRaw)->startOfMinute();
        } catch (\Throwable $e) {
            throw new InvalidTemporaryAdmissionException('Admission date is invalid for this IPD.');
        }

        $min = $admissionAt->lte($max) ? $admissionAt : $max;
        $parsedMinute = $parsed->copy()->startOfMinute();

        if ($parsedMinute->lt($min)) {
            throw new InvalidTemporaryAdmissionException('Estimate time cannot be before admission time.');
        }

        if ($parsedMinute->gt($max)) {
            throw new InvalidTemporaryAdmissionException('Estimate time cannot be after 11:59 PM of the current date.');
        }

        // Same minute as wall-clock "now" → normal current estimate (no special as-of override).
        if ($parsedMinute->equalTo($nowMinute)) {
            return null;
        }

        Log::info('Estimate as-of time applied for bed charge calculation (not saved).', [
            'ipd_id' => $ipd->id,
            'ipd_no' => $ipd->ipd_no,
            'real_admission' => (string) $admissionRaw,
            'estimate_as_of' => $parsedMinute->format('Y-m-d H:i:s'),
        ]);

        // Past or later-today (up to 23:59) as-of: bed calc ends at this moment.
        return $parsedMinute;
    }

    /**
     * @deprecated Admission is never overridden. Prefer resolve() as billing end in exportEstimate.
     *
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function run(Request $request, IpdDetail $ipd, bool $isApprovalBill, callable $callback)
    {
        $this->resolve($request, $ipd, $isApprovalBill);

        return $callback();
    }
}
