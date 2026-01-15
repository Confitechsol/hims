<?php

namespace App\Console\Commands;

use App\Services\DaywiseBedChargeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CalculateDaywiseBedChargesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ipd:calculate-bed-charges {--date= : Specific date to calculate charges for (Y-m-d format). Default: previous day} {--ipd-id= : Calculate for specific IPD ID only} {--batch-size=50 : Number of patients to process in each batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and store daywise bed charges for IPD patients (runs daily at 10:05 AM)';

    /**
     * Execute the console command.
     *
     * @param DaywiseBedChargeService $service
     * @return int
     */
    public function handle(DaywiseBedChargeService $service)
    {
        $this->info('Starting daywise bed charges calculation...');
        Log::info('Daywise bed charges calculation started', [
            'command' => 'ipd:calculate-bed-charges',
            'options' => $this->options(),
        ]);

        try {
            // Get charge date
            $chargeDate = $this->option('date');
            if (!$chargeDate) {
                $chargeDate = Carbon::yesterday()->format('Y-m-d');
            } else {
                // Validate date format
                try {
                    Carbon::createFromFormat('Y-m-d', $chargeDate);
                } catch (\Exception $e) {
                    $this->error("Invalid date format. Please use Y-m-d format (e.g., 2024-01-15)");
                    return Command::FAILURE;
                }
            }

            $this->info("Calculating charges for date: {$chargeDate}");

            // Get IPD patients
            $ipdId = $this->option('ipd-id');
            if ($ipdId) {
                $ipdPatients = collect([(object)['id' => (int)$ipdId]]);
                $this->info("Processing specific IPD ID: {$ipdId}");
            } else {
                $ipdPatients = $service->getActiveIpdPatients();
                $this->info("Found {$ipdPatients->count()} active IPD patients");
            }

            if ($ipdPatients->isEmpty()) {
                $this->warn('No active IPD patients found.');
                return Command::SUCCESS;
            }

            // Process in batches
            $batchSize = (int)$this->option('batch-size');
            $totalPatients = $ipdPatients->count();
            $processed = 0;
            $failed = 0;

            $bar = $this->output->createProgressBar($totalPatients);
            $bar->start();

            foreach ($ipdPatients->chunk($batchSize) as $batch) {
                foreach ($batch as $ipd) {
                    try {
                        \App\Jobs\ProcessDaywiseBedChargeJob::dispatch($ipd->id, $chargeDate);
                        $processed++;
                    } catch (\Exception $e) {
                        $failed++;
                        Log::error("Failed to dispatch job for IPD ID: {$ipd->id}", [
                            'error' => $e->getMessage(),
                        ]);
                    }
                    $bar->advance();
                }
            }

            $bar->finish();
            $this->newLine(2);

            // Summary
            $this->info("Processing complete!");
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total Patients', $totalPatients],
                    ['Jobs Dispatched', $processed],
                    ['Failed Dispatches', $failed],
                    ['Charge Date', $chargeDate],
                ]
            );

            Log::info('Daywise bed charges calculation completed', [
                'total_patients' => $totalPatients,
                'jobs_dispatched' => $processed,
                'failed_dispatches' => $failed,
                'charge_date' => $chargeDate,
            ]);

            $this->info("Jobs have been dispatched to the queue. Monitor queue workers for processing status.");
            $this->info("Check logs at: storage/logs/laravel.log");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            Log::error('Daywise bed charges calculation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return Command::FAILURE;
        }
    }
}
