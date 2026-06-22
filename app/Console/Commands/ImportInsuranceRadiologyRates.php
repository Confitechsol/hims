<?php

namespace App\Console\Commands;

use App\Services\InsuranceTestRateImportService;
use Illuminate\Console\Command;

class ImportInsuranceRadiologyRates extends Command
{
    protected $signature = 'insurance:import-radiology-rates {file?}';

    protected $description = 'Import radiology insurance panel rates from Excel';

    public function handle(InsuranceTestRateImportService $importService): int
    {
        $file = $this->argument('file') ?? base_path('RADIOLOGY_INSURANCE TEST RATE.xlsx');

        if (!is_file($file)) {
            $this->error("File not found: {$file}");

            return self::FAILURE;
        }

        $stats = $importService->importFromFile($file, 'radiology', true);
        $this->table(
            ['Metric', 'Count'],
            collect($stats)->map(fn ($v, $k) => [ucfirst(str_replace('_', ' ', $k)), $v])->values()->all()
        );

        return self::SUCCESS;
    }
}
