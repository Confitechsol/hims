<?php

namespace App\Console\Commands;

use App\Models\IpdPrescription;
use App\Services\PmsBridgeService;
use Illuminate\Console\Command;

class SyncIpdPrescriptionToPms extends Command
{
    protected $signature = 'hims:sync-prescription-to-pms {prescription : Prescription id or number e.g. IPDP0432}';

    protected $description = 'Force-push an IPD prescription pathology tests to PMS Orders';

    public function handle(PmsBridgeService $bridge): int
    {
        $key = trim((string) $this->argument('prescription'));
        $prescription = ctype_digit($key)
            ? IpdPrescription::find((int) $key)
            : IpdPrescription::where('prescription_number', $key)->first();

        if (! $prescription) {
            $this->error("Prescription not found: {$key}");

            return self::FAILURE;
        }

        $this->info("Syncing {$prescription->prescription_number} (id={$prescription->id}) to PMS...");
        $result = $bridge->sendIpdPathologyOrder($prescription->fresh());

        if (! empty($result['success'])) {
            $orderNo = $result['data']['pms_order_no'] ?? 'n/a';
            $this->info('OK — PMS order: '.$orderNo);

            return self::SUCCESS;
        }

        $this->error('FAILED — '.($result['message'] ?? 'unknown'));
        if (! empty($result['data'])) {
            $this->line(json_encode($result['data'], JSON_PRETTY_PRINT));
        }

        return self::FAILURE;
    }
}
