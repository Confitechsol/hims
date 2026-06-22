<?php

namespace App\Console\Commands;

use App\Services\InsuranceTestRateImportService;
use Illuminate\Console\Command;

class ImportInsurancePathologyRates extends Command
{
    protected $signature = 'insurance:import-pathology-rates {file?}';

    protected $description = 'Import pathology insurance panel rates from Excel';

    public function handle(InsuranceTestRateImportService $importService): int
    {
        $file = $this->argument('file') ?? base_path('PATHOLOGY_INSURANCE TEST RATE.xlsx');

        if (!is_file($file)) {
            $this->error("File not found: {$file}");

            return self::FAILURE;
        }

        $stats = $importService->importFromFile($file, 'pathology', true);
        $this->displayStats($stats);

        return self::SUCCESS;
    }

    protected function displayStats(array $stats): void
    {
        $this->table(
            ['Metric', 'Count'],
            collect($stats)->map(fn ($v, $k) => [ucfirst(str_replace('_', ' ', $k)), $v])->values()->all()
        );
    }
}
