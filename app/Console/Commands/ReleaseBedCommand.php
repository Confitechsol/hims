<?php

namespace App\Console\Commands;

use App\Models\Bed;
use App\Models\IpdDetail;
use App\Models\Patient;
use App\Models\PatientBedHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseBedCommand extends Command
{
    protected $signature = 'hims:release-bed
        {beds?* : Bed ID or bed name, e.g. "BED NO. 18" "BED NO. 23"}
        {--bed=* : Additional bed IDs or names (repeatable)}
        {--dry-run : Preview actions without writing to DB}
        {--force : Apply changes (required for real execution)}
        {--force-active : Also release when a live admitted IPD still points to this bed}';

    protected $description = 'Release one or more beds stuck as Alloted (close orphan bed history and mark bed Available)';

    public function handle(): int
    {
        $bedInputs = array_values(array_filter(array_merge(
            $this->argument('beds') ?? [],
            $this->option('bed') ?? []
        ), fn ($value) => trim((string) $value) !== ''));

        if ($bedInputs === []) {
            $this->error('Provide at least one bed: php artisan hims:release-bed "BED NO. 18" "BED NO. 23"');
            $this->line('Or use: php artisan hims:release-bed --bed="BED NO. 18" --bed="BED NO. 23"');
            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');
        $forceActive = (bool) $this->option('force-active');

        if (!$dryRun && !$force) {
            $this->error('Use --dry-run to preview, or --force to apply changes.');
            return self::FAILURE;
        }

        $this->info($dryRun ? '=== DRY RUN (no DB writes) ===' : '=== RELEASING BEDS ===');

        $released = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($bedInputs as $bedInput) {
            $bedInput = trim((string) $bedInput);
            $this->newLine();
            $this->line("Processing: {$bedInput}");

            $bed = $this->resolveBed($bedInput);
            if (!$bed) {
                $this->error("  Bed not found: {$bedInput}");
                $failed++;
                continue;
            }

            $assessment = $this->assessBed($bed);

            $this->table(
                ['Field', 'Value'],
                [
                    ['Bed ID', $bed->id],
                    ['Bed Name', $bed->name],
                    ['Status Now', $bed->is_active === 'yes' ? 'Available' : 'Alloted'],
                    ['Active History Rows', $assessment['active_histories']],
                    ['Linked IPD', $assessment['ipd_no'] ?? '(none)'],
                    ['IPD Discharged', $assessment['ipd_discharged'] ?? '(n/a)'],
                    ['Patient', $assessment['patient_label'] ?? '(none)'],
                    ['Release Reason', $assessment['reason']],
                ]
            );

            if (!$assessment['can_release']) {
                $this->warn('  Skipped: ' . $assessment['block_reason']);
                $this->line('  Re-run with --force-active if this bed must still be released.');
                $skipped++;
                continue;
            }

            if ($assessment['already_available']) {
                $this->comment('  Already available. No changes needed.');
                $skipped++;
                continue;
            }

            foreach ($assessment['actions'] as $action) {
                $this->line('  - ' . $action);
            }

            if ($dryRun) {
                $released++;
                continue;
            }

            try {
                DB::transaction(function () use ($bed, $assessment) {
                    $now = Carbon::now();
                    PatientBedHistory::where('bed_id', $bed->id)
                        ->where('is_active', 'yes')
                        ->get()
                        ->each(function (PatientBedHistory $history) use ($now) {
                            $history->is_active = 'no';
                            if (!$history->to_date) {
                                $history->to_date = $now;
                            }
                            $history->save();
                        });

                    foreach ($assessment['ipd_ids_to_clear'] as $ipdId) {
                        $ipd = IpdDetail::find($ipdId);
                        if (!$ipd || (int) $ipd->bed !== (int) $bed->id) {
                            continue;
                        }

                        $ipd->bed = null;
                        $ipd->bed_group_id = null;
                        $ipd->save();
                    }

                    Bed::where('id', $bed->id)->update(['is_active' => 'yes']);
                });

                $bed->refresh();
                $this->info("  Released: {$bed->name} is now Available.");
                $released++;
            } catch (\Throwable $e) {
                $this->error('  Failed: ' . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done. Released: {$released}, Skipped: {$skipped}, Failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function resolveBed(string $input): ?Bed
    {
        if (ctype_digit($input)) {
            return Bed::find((int) $input);
        }

        $exact = Bed::where('name', $input)->first();
        if ($exact) {
            return $exact;
        }

        $normalized = preg_replace('/\s+/', ' ', strtoupper(trim($input)));
        $candidates = Bed::whereRaw('UPPER(name) LIKE ?', ['%' . $normalized . '%'])
            ->orderBy('id')
            ->get();

        if ($candidates->count() === 1) {
            return $candidates->first();
        }

        if ($candidates->count() > 1) {
            $this->warn('  Multiple beds matched. Use exact bed name or numeric bed id:');
            foreach ($candidates as $candidate) {
                $this->line("    #{$candidate->id} {$candidate->name}");
            }

            return null;
        }

        return null;
    }

    protected function assessBed(Bed $bed): array
    {
        $activeHistories = PatientBedHistory::where('bed_id', $bed->id)
            ->where('is_active', 'yes')
            ->get();

        $activeIpd = IpdDetail::where('bed', $bed->id)
            ->where(function ($query) {
                $query->whereNull('discharged')
                    ->orWhere('discharged', '')
                    ->orWhere('discharged', 'no')
                    ->orWhere('discharged', 'draft');
            })
            ->orderByDesc('id')
            ->first();

        $patient = $activeIpd?->patient_id
            ? Patient::withTrashed()->find($activeIpd->patient_id)
            : null;

        $alreadyAvailable = $bed->is_active === 'yes' && $activeHistories->isEmpty();

        $ipdIdsToClear = IpdDetail::where('bed', $bed->id)->pluck('id')->all();

        $actions = [
            "Mark bed #{$bed->id} ({$bed->name}) as Available (is_active=yes)",
            'Close active patient_bed_history rows for this bed',
        ];

        if ($ipdIdsToClear !== []) {
            $actions[] = 'Clear bed / bed_group on linked IPD record(s): ' . implode(', ', $ipdIdsToClear);
        }

        $patientLabel = $patient
            ? trim($patient->patient_name . ($patient->trashed() ? ' (deleted)' : ''))
            : null;

        if ($alreadyAvailable) {
            return [
                'can_release' => false,
                'already_available' => true,
                'active_histories' => $activeHistories->count(),
                'ipd_no' => $activeIpd?->ipd_no,
                'ipd_discharged' => $activeIpd?->discharged,
                'patient_name' => $patient?->patient_name,
                'patient_label' => $patientLabel,
                'reason' => 'Already available',
                'block_reason' => null,
                'actions' => [],
                'ipd_ids_to_clear' => [],
            ];
        }

        if (!$activeIpd) {
            return [
                'can_release' => true,
                'already_available' => false,
                'active_histories' => $activeHistories->count(),
                'ipd_no' => null,
                'ipd_discharged' => null,
                'patient_name' => null,
                'patient_label' => null,
                'reason' => 'Bed marked Alloted but no active IPD is assigned',
                'block_reason' => null,
                'actions' => $actions,
                'ipd_ids_to_clear' => $ipdIdsToClear,
            ];
        }

        $isLiveAdmission = !$this->isDischargedIpd($activeIpd);
        $patientMissing = !$patient || trim((string) $patient->patient_name) === '' || $patient->trashed();

        if ($isLiveAdmission && !$patientMissing && !$this->option('force-active')) {
            return [
                'can_release' => false,
                'already_available' => false,
                'active_histories' => $activeHistories->count(),
                'ipd_no' => $activeIpd->ipd_no,
                'ipd_discharged' => $activeIpd->discharged ?: 'no',
                'patient_name' => $patient->patient_name,
                'patient_label' => $patientLabel,
                'reason' => 'Live IPD admission still linked',
                'block_reason' => "Active patient {$patient->patient_name} ({$activeIpd->ipd_no}) is still on this bed.",
                'actions' => $actions,
                'ipd_ids_to_clear' => $ipdIdsToClear,
            ];
        }

        if ($isLiveAdmission && $patientMissing) {
            $actions[] = "Clear ghost IPD {$activeIpd->ipd_no} bed pointer (patient missing/deleted)";
        } elseif ($this->isDischargedIpd($activeIpd)) {
            $actions[] = "Clear discharged IPD {$activeIpd->ipd_no} bed pointer";
        }

        return [
            'can_release' => true,
            'already_available' => false,
            'active_histories' => $activeHistories->count(),
            'ipd_no' => $activeIpd->ipd_no,
            'ipd_discharged' => $activeIpd->discharged,
            'patient_name' => $patient?->patient_name,
            'patient_label' => $patientLabel,
            'reason' => $isLiveAdmission
                ? 'Ghost / missing patient on active IPD'
                : 'Discharged IPD still holding bed',
            'block_reason' => null,
            'actions' => $actions,
            'ipd_ids_to_clear' => $ipdIdsToClear,
        ];
    }

    protected function isDischargedIpd(?IpdDetail $ipd): bool
    {
        if (!$ipd) {
            return false;
        }

        return in_array(strtolower((string) $ipd->discharged), ['yes', 'draft'], true)
            && ! empty($ipd->final_bill_generated_at);
    }
}
