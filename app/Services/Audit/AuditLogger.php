<?php

namespace App\Services\Audit;

use App\Jobs\RecordAuditEventJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuditLogger
{
    /**
     * Actions that require a non-empty reason.
     */
    public static function reasonRequiredActions(): array
    {
        return [
            'discharge_reopened',
            'discharge_re_finalized',
            'updated',
            'deleted',
            'bed_assigned',
            'bed_history_updated',
            'visibility_changed',
            'discount_updated',
        ];
    }

    /**
     * Queue audit write on the `audit` queue (ProcessDaywiseBedChargeJob pattern).
     * Captures request/user context in-process, then dispatches Persist job.
     *
     * @return bool true if dispatched (or sync-fallback wrote)
     */
    public function log(array $payload): bool
    {
        try {
            $request = request();
            $user = Auth::user();

            $full = [
                'hospital_id' => $payload['hospital_id'] ?? ($user->hospital_id ?? null),
                'branch_id' => $payload['branch_id'] ?? ($user->branch_id ?? null),
                'occurred_at' => isset($payload['occurred_at'])
                    ? (string) Carbon::parse($payload['occurred_at'])
                    : Carbon::now()->toDateTimeString(),
                'user_id' => $payload['user_id'] ?? Auth::id(),
                'user_role_name' => $payload['user_role_name'] ?? (function_exists('getUserRoleName') ? getUserRoleName() : null),
                'ip_address' => $payload['ip_address'] ?? ($request?->ip()),
                'user_agent' => $payload['user_agent'] ?? substr((string) ($request?->userAgent() ?? ''), 0, 500),
                'module' => $payload['module'] ?? 'system',
                'entity_type' => $payload['entity_type'] ?? 'unknown',
                'entity_id' => $payload['entity_id'] ?? null,
                'parent_type' => $payload['parent_type'] ?? null,
                'parent_id' => $payload['parent_id'] ?? null,
                'patient_id' => $payload['patient_id'] ?? null,
                'case_no' => $payload['case_no'] ?? null,
                'action' => $payload['action'] ?? 'updated',
                'reason' => $payload['reason'] ?? null,
                'request_route' => $payload['request_route'] ?? ($request?->path()),
                'request_method' => $payload['request_method'] ?? ($request?->method()),
                'old_values' => $payload['old_values'] ?? null,
                'new_values' => $payload['new_values'] ?? null,
                'meta' => $payload['meta'] ?? null,
            ];

            RecordAuditEventJob::dispatch($full);

            return true;
        } catch (Throwable $e) {
            // Never break business flow because of audit write failure.
            Log::error('AuditLogger dispatch failed', [
                'message' => $e->getMessage(),
                'payload_action' => $payload['action'] ?? null,
            ]);

            return false;
        }
    }

    /**
     * Diff only changed keys between two associative arrays.
     *
     * @return array{0: array, 1: array}
     */
    public function diff(array $before, array $after): array
    {
        $old = [];
        $new = [];
        $keys = array_unique(array_merge(array_keys($before), array_keys($after)));

        foreach ($keys as $key) {
            $b = $before[$key] ?? null;
            $a = $after[$key] ?? null;
            if ($this->normalize($b) !== $this->normalize($a)) {
                $old[$key] = $b;
                $new[$key] = $a;
            }
        }

        return [$old, $new];
    }

    private function normalize(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('c');
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_array($value)) {
            return json_encode($value) ?: '';
        }

        return (string) ($value ?? '');
    }
}
