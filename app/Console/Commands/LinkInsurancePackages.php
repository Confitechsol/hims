<?php

namespace App\Console\Commands;

use App\Models\Package;
use App\Services\InsurancePackageHospitalMappingService;
use Illuminate\Console\Command;

class LinkInsurancePackages extends Command
{
    protected $signature = 'insurance:link-packages
        {--min-score=72 : Minimum fuzzy match score (0-100)}
        {--force : Re-link packages that already have a hospital mapping}
        {--panel= : Only link packages for this insurance rate panel code}';

    protected $description = 'Auto-link insurance packages to hospital packages by procedure name';

    public function handle(InsurancePackageHospitalMappingService $mapping): int
    {
        $minScore = (float) $this->option('min-score');
        $force = (bool) $this->option('force');
        $panelCode = $this->option('panel');

        $query = Package::query()
            ->where('package_type', Package::TYPE_INSURANCE)
            ->with('insuranceRatePanel');

        if (!$force) {
            $query->whereNull('linked_hospital_package_id');
        }

        if ($panelCode) {
            $query->whereHas('insuranceRatePanel', fn ($q) => $q->where('code', strtoupper($panelCode)));
        }

        $packages = $query->orderBy('name')->get();
        if ($packages->isEmpty()) {
            $this->info('No insurance packages to link.');

            return self::SUCCESS;
        }

        $linked = 0;
        $skipped = 0;

        foreach ($packages as $package) {
            if ($force) {
                $package->linked_hospital_package_id = null;
                $package->save();
            }

            $hospital = $mapping->autoLinkHospitalPackage(
                $package,
                $package->name,
                $package->insurer_procedure_code,
                $minScore
            );

            if ($hospital) {
                $linked++;
                $this->line("  {$package->name} → {$hospital->name}");
            } else {
                $skipped++;
            }
        }

        $this->newLine();
        $this->table(
            ['Metric', 'Count'],
            [
                ['Processed', $packages->count()],
                ['Newly linked', $linked],
                ['No match', $skipped],
            ]
        );

        return self::SUCCESS;
    }
}
