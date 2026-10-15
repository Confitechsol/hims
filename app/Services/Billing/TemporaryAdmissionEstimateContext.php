<?php

namespace App\Services\Billing;

use Carbon\Carbon;

/**
 * Request-only holder for a temporary admission instant.
 * The estimate add-on sets it around bill calculation and always clears it.
 * It is never written to the database.
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
