<?php

namespace App\Console\Commands;

use App\Services\InsurancePackageImportService;
use Illuminate\Console\Command;

class ImportInsurancePackages extends Command
{
    protected $signature = 'insurance:import-packages
        {file : Path to CSV or Excel file}
        {--replace : Delete existing insurance packages for each panel before import}
        {--link : Auto-link to hospital packages by name similarity}
        {--dry-run : Preview insert/update/skip/deactivate counts without saving}
        {--deactivate-missing : Mark panel packages not in the file as inactive}';

    protected $description = 'Import insurance surgical packages from CSV/Excel (GIPSA PPN layout or template CSV)';

    public function handle(InsurancePackageImportService $service): int
    {
        $file = $this->argument('file');
        if (!is_file($file)) {
            $alt = base_path($file);
            if (is_file($alt)) {
                $file = $alt;
            } else {
                $this->error("File not found: {$file}");

                return self::FAILURE;
            }
        }

        $this->info('Importing insurance packages from: ' . $file);
        if ($this->option('dry-run')) {
            $this->warn('DRY RUN — no database changes will be saved.');
        }

        try {
            $stats = $service->importFromFile(
                $file,
                (bool) $this->option('replace'),
                (bool) $this->option('link'),
                (bool) $this->option('dry-run'),
                (bool) $this->option('deactivate-missing')
            );
        } catch (\Throwable $e) {
            $this->error('Import failed: ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->table(
            ['Metric', 'Count'],
            [
                ['Panels touched', $stats['panels']],
                ['Rows processed', $stats['packages']],
                ['Inserted', $stats['inserted']],
                ['Updated', $stats['updated']],
                ['Unchanged (skipped)', $stats['unchanged']],
                ['Deactivated (not in file)', $stats['deactivated']],
                ['Purged (panel replace)', $stats['purged']],
                ['Room rate rows synced', $stats['room_rates']],
                ['Linked to hospital package', $stats['linked']],
                ['Errored rows', $stats['skipped']],
            ]
        );

        if (!empty($stats['errors'])) {
            $this->warn('Errors:');
            foreach (array_slice($stats['errors'], 0, 20) as $err) {
                $this->line('  - ' . $err);
            }
        }

        $this->info('Done. Review Package Master (filter: Insurance) and Room Mappings.');

        return self::SUCCESS;
    }
}
