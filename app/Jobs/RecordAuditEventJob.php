<?php

namespace App\Jobs;

use App\Models\AuditEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Persist audit_events asynchronously (same technique as ProcessDaywiseBedChargeJob).
 */
class RecordAuditEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    /** @var array<int, int> */
    public array $backoff = [30, 90, 180];

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(public array $payload)
    {
        $this->onQueue('audit');
    }

    public function handle(): void
    {
        try {
            AuditEvent::create([
                'hospital_id' => $this->payload['hospital_id'] ?? null,
                'branch_id' => $this->payload['branch_id'] ?? null,
                'occurred_at' => $this->payload['occurred_at'] ?? now(),
                'user_id' => $this->payload['user_id'] ?? null,
                'user_role_name' => $this->payload['user_role_name'] ?? null,
                'ip_address' => $this->payload['ip_address'] ?? null,
                'user_agent' => $this->payload['user_agent'] ?? null,
                'module' => $this->payload['module'] ?? 'system',
                'entity_type' => $this->payload['entity_type'] ?? 'unknown',
                'entity_id' => $this->payload['entity_id'] ?? null,
                'parent_type' => $this->payload['parent_type'] ?? null,
                'parent_id' => $this->payload['parent_id'] ?? null,
                'patient_id' => $this->payload['patient_id'] ?? null,
                'case_no' => $this->payload['case_no'] ?? null,
                'action' => $this->payload['action'] ?? 'updated',
                'reason' => $this->payload['reason'] ?? null,
                'request_route' => $this->payload['request_route'] ?? null,
                'request_method' => $this->payload['request_method'] ?? null,
                'old_values' => $this->payload['old_values'] ?? null,
                'new_values' => $this->payload['new_values'] ?? null,
                'meta' => $this->payload['meta'] ?? null,
            ]);
        } catch (Exception $e) {
            Log::error('RecordAuditEventJob failed', [
                'action' => $this->payload['action'] ?? null,
                'attempt' => $this->attempts(),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(Exception $exception): void
    {
        Log::error('RecordAuditEventJob failed permanently', [
            'action' => $this->payload['action'] ?? null,
            'module' => $this->payload['module'] ?? null,
            'error' => $exception->getMessage(),
        ]);
    }

    public function retryUntil()
    {
        return now()->addHours(2);
    }
}
