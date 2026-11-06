<?php

namespace App\Services\Billing;

use Carbon\Carbon;

/**
 * Request-only holder kept for backward-compatible tests/helpers.
 * Estimate as-of time is no longer applied as a fake admission; bed calc
 * always uses the real IPD admission and passes as-of only as billing end.
 */
class TemporaryAdmissionEstimateContext
{
    private ?Carbon $admissionAt = null;

    /**
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function using(Carbon $admissionAt, callable $callback)
    {
        $previous = $this->admissionAt;
        $this->admissionAt = $admissionAt->copy();

        try {
            return $callback();
        } finally {
            $this->admissionAt = $previous;
        }
    }

    public function admissionAt(): ?Carbon
    {
        return $this->admissionAt?->copy();
    }
}
