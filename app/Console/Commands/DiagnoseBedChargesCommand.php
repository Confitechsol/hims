<?php

namespace App\Console\Commands;

use App\Services\DaywiseBedChargeService;
use App\Models\IpdDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DiagnoseBedChargesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ipd:diagnose-bed-charges {--ipd-id= : Specific IPD ID to test} {--date= : Date to test (Y-m-d format)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose bed charge calculation issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Bed Charges Diagnosis ===');
        $this->newLine();

        // Check tables
        $this->info('1. Checking database tables...');
        $tables = ['patient_bed_history', 'bed_group', 'ipd_daywise_bed_charges', 'ipd_details'];
        foreach ($tables as $table) {
            $exists = DB::getSchemaBuilder()->hasTable($table);
            $this->line("   - {$table}: " . ($exists ? '✓ EXISTS' : '✗ MISSING'));
        }
        $this->newLine();

        // Check failed jobs
        $this->info('2. Checking failed jobs...');
        $failedCount = DB::table('failed_jobs')->count();
        if ($failedCount > 0) {
            $this->warn("   Found {$failedCount} failed job(s)");
            $latest = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->first();
            if ($latest) {
                try {
                    $exception = json_decode($latest->exception, true);
                    $this->error("   Latest Error: " . ($exception['message'] ?? 'N/A'));
                    $this->line("   File: " . ($exception['file'] ?? 'N/A'));
                    $this->line("   Line: " . ($exception['line'] ?? 'N/A'));
                } catch (\Exception $e) {
                    $this->warn("   Could not parse exception data");
                }
            }
        } else {
            $this->info("   ✓ No failed jobs");
        }
        $this->newLine();

        // Check pending jobs
        $this->info('3. Checking pending jobs...');
        $pendingCount = DB::table('jobs')->where('queue', 'bed-charges')->count();
        $this->line("   Pending bed-charges jobs: {$pendingCount}");
        $this->newLine();

        // Test with actual IPD
        $ipdId = $this->option('ipd-id');
        if (!$ipdId) {
            $ipd = IpdDetail::where('discharged', 'no')
                ->orWhereNull('discharged')
                ->first();
            if ($ipd) {
                $ipdId = $ipd->id;
                $this->info("4. Testing with IPD ID: {$ipdId} (auto-selected)");
            } else {
                $this->warn("4. No active IPD patients found to test");
                return Command::SUCCESS;
            }
        } else {
            $this->info("4. Testing with IPD ID: {$ipdId}");
        }

        $testDate = $this->option('date') ?: '2024-01-15';
        $this->line("   Test Date: {$testDate}");
        $this->newLine();

        // Check IPD exists
        $ipd = IpdDetail::find($ipdId);
        if (!$ipd) {
            $this->error("   ✗ IPD record not found: {$ipdId}");
            return Command::FAILURE;
        }
        $this->info("   ✓ IPD record found");
        $this->line("   - Patient ID: {$ipd->patient_id}");
        $this->line("   - Case Reference ID: {$ipd->case_reference_id}");
        $this->line("   - Hospital ID: {$ipd->hospital_id}");
        $this->line("   - Branch ID: {$ipd->branch_id}");
        $this->newLine();

        // Check bed history
        $this->info("5. Checking bed history...");
        $bedHistoryCount = DB::table('patient_bed_history')
            ->where('ipd_id', $ipdId)
            ->where('is_active', 'yes')
            ->count();
        $this->line("   Active bed history records: {$bedHistoryCount}");

        if ($bedHistoryCount > 0) {
            $latestBed = DB::table('patient_bed_history')
                ->where('ipd_id', $ipdId)
                ->where('is_active', 'yes')
                ->orderBy('from_date', 'desc')
                ->first();
            
            if ($latestBed) {
                $this->info("   ✓ Latest bed assignment found");
                $this->line("   - Bed Group ID: {$latestBed->bed_group_id}");
                $this->line("   - Bed ID: {$latestBed->bed_id}");
                $this->line("   - From Date: {$latestBed->from_date}");
                $this->line("   - To Date: " . ($latestBed->to_date ?? 'NULL'));

                // Check bed group
                if ($latestBed->bed_group_id) {
                    $bedGroup = DB::table('bed_group')->find($latestBed->bed_group_id);
                    if ($bedGroup) {
                        $this->info("   ✓ Bed group found");
                        $this->line("   - Name: {$bedGroup->name}");
                        $this->line("   - Bed Cost: " . ($bedGroup->bed_cost ?? '0.00'));
                    } else {
                        $this->error("   ✗ Bed group not found: {$latestBed->bed_group_id}");
                    }
                }
            }
        } else {
            $this->warn("   ⚠ No active bed history records found");
        }
        $this->newLine();

        // Test service
        $this->info("6. Testing service calculation...");
        try {
            $service = new DaywiseBedChargeService();
            $result = $service->calculateDaywiseCharges($ipdId, $testDate);

            if ($result['success']) {
                $this->info("   ✓ Calculation successful!");
                if (isset($result['data'])) {
                    $this->line("   - Bed Charge: " . ($result['data']->bed_charge ?? 'N/A'));
                }
            } else {
                $this->error("   ✗ Calculation failed");
                $this->line("   Message: " . $result['message']);
            }
        } catch (\Exception $e) {
            $this->error("   ✗ Exception occurred");
            $this->error("   Error: " . $e->getMessage());
            $this->line("   File: " . $e->getFile());
            $this->line("   Line: " . $e->getLine());
        }

        $this->newLine();
        $this->info('=== Diagnosis Complete ===');

        return Command::SUCCESS;
    }
}
