<?php

namespace App\Jobs;

use App\Services\DaywiseBedChargeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessDaywiseBedChargeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300; // 5 minutes

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = [60, 180, 600]; // 1 min, 3 min, 10 min

    /**
     * Create a new job instance.
     *
     * @param int $ipdId IPD patient ID
     * @param string|null $chargeDate Date for which to calculate charges (Y-m-d format)
     */
    public function __construct(
        public int $ipdId,
        public ?string $chargeDate = null
    ) {
        // Set queue name
        $this->onQueue('bed-charges');
    }

    /**
     * Execute the job.
     *
     * @param DaywiseBedChargeService $service
     * @return void
     */
    public function handle(DaywiseBedChargeService $service)
    {
        try {
            Log::info("Processing daywise bed charge job", [
                'ipd_id' => $this->ipdId,
                'charge_date' => $this->chargeDate ?? 'previous day',
                'attempt' => $this->attempts(),
            ]);

            $result = $service->calculateDaywiseCharges($this->ipdId, $this->chargeDate);

            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            Log::info("Successfully processed daywise bed charge job", [
                'ipd_id' => $this->ipdId,
                'charge_date' => $this->chargeDate ?? 'previous day',
            ]);

        } catch (Exception $e) {
            Log::error("Failed to process daywise bed charge job", [
                'ipd_id' => $this->ipdId,
                'charge_date' => $this->chargeDate ?? 'previous day',
                'attempt' => $this->attempts(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::error("Daywise bed charge job failed permanently", [
            'ipd_id' => $this->ipdId,
            'charge_date' => $this->chargeDate ?? 'previous day',
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // You can add additional failure handling here, such as:
        // - Sending notification to admin
        // - Creating a manual review record
        // - Sending alert to monitoring system
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addHours(2);
    }
}
