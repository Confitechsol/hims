<?php

namespace App\Services\Billing;

use App\Exceptions\InvalidTemporaryAdmissionException;
use App\Models\IpdDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Estimate-only add-on. A temporary admission datetime can be used while
 * calculating the bed charge on an estimate PDF. The value is not saved,
 * and approval and final bills ignore it.
 */
class TemporaryAdmissionEstimateAddon
{
    public function __construct(private TemporaryAdmissionEstimateContext $context)
    {
    }

    /**
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function run(Request $request, IpdDetail $ipd, bool $isApprovalBill, callable $callback)
    {
        $temporaryAdmission = $this->resolve($request, $ipd, $isApprovalBill);

        if ($temporaryAdmission === null) {
            return $callback();
        }

        Log::info('Estimate bed charge used a temporary admission. The value was not saved.', [
            'ipd_id' => $ipd->id,
            'ipd_no' => $ipd->ipd_no,
            'real_admission' => (string) ($ipd->date ?? ''),
            'temporary_admission' => $temporaryAdmission->format('Y-m-d H:i:s'),
        ]);

        return $this->context->using($temporaryAdmission, $callback);
    }

    public function resolve(Request $request, IpdDetail $ipd, bool $isApprovalBill): ?Carbon
    {
        if ($isApprovalBill) {
            return null;
        }

        $raw = $request->query('temp_admission_at');
        if ($raw === null) {
            return null;
        }

        if (! is_string($raw) && ! is_numeric($raw)) {
            throw new InvalidTemporaryAdmissionException('Temporary admission date and time is not a valid date.');
        }

        $raw = trim((string) $raw);
        if ($raw === '') {
            return null;
        }

        if (strlen($raw) > 32) {
            throw new InvalidTemporaryAdmissionException('Temporary admission date and time is not a valid date.');
        }

        try {
            $parsed = Carbon::parse($raw);
        } catch (\Throwable $e) {
            throw new InvalidTemporaryAdmissionException('Temporary admission date and time is not a valid date.');
        }

        if ($parsed->year < 2000 || $parsed->year > 2100) {
            throw new InvalidTemporaryAdmissionException('Temporary admission date and time is outside the allowed range.');
        }

        $realRaw = $ipd->date ?? $ipd->created_at ?? null;
        if ($realRaw) {
            try {
                $real = Carbon::parse($realRaw);
                if ($parsed->format('Y-m-d H:i') === $real->format('Y-m-d H:i')) {
                    return null;
                }
            } catch (\Throwable $e) {
                Log::warning('Could not compare temporary admission with the stored admission.', [
                    'ipd_id' => $ipd->id,
                    'stored_admission' => (string) $realRaw,
                ]);
            }
        }

        return $parsed;
    }
}
