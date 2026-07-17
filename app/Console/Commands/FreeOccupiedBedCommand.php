<?php

namespace App\Console\Commands;

use App\Models\Bed;
use App\Models\IpdDetail;
use App\Models\Patient;
use App\Models\PatientBedHistory;
use App\Models\PatientTimeline;
use App\Models\PatientVital;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FreeOccupiedBedCommand extends Command
{
    protected $signature = 'hims:free-occupied-bed
        {--patient-id= : Patient ID (optional if patient row is already missing)}
        {--bed= : Bed ID or bed name, e.g. "BED NO. 28" (required)}
        {--ipd-no= : IPD number for safe matching, e.g. IPDN0020 (recommended)}
        {--delete-patient : Soft-delete patient when no other active IPD remains}
        {--dry-run : Preview actions without writing to DB}
        {--force : Apply changes (required for real execution)}';

    protected $description = 'Free an unnecessarily occupied bed, remove bed history, and clean patient history for ghost IPD admissions';

    public function handle(): int
    {
        $bedInput = trim((string) $this->option('bed'));
        $patientId = $this->option('patient-id') !== null && $this->option('patient-id') !== ''
            ? (int) $this->option('patient-id')
            : null;
        $ipdNo = trim((string) $this->option('ipd-no'));
        $deletePatient = (bool) $this->option('delete-patient');
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        if ($bedInput === '') {
            $this->error('Option --bed is required (bed id or bed name).');
            return self::FAILURE;
        }

        if ($patientId === null && $ipdNo === '') {
            $this->error('Provide at least one of --patient-id or --ipd-no for safe matching.');
            return self::FAILURE;
        }

        if (!$dryRun && !$force) {
            $this->error('Use --dry-run to preview, or --force to apply changes.');
            return self::FAILURE;
        }

        $bed = $this->resolveBed($bedInput);
        if (!$bed) {
            $this->error("Bed not found for: {$bedInput}");
            return self::FAILURE;
        }

        $ipd = $this->resolveIpd($bed, $patientId, $ipdNo);
        if (!$ipd) {
            $this->error('No matching IPD found for the given bed / patient / IPD no.');
            return self::FAILURE;
        }

        if ($ipd->bed && (int) $ipd->bed !== (int) $bed->id) {
            $this->error(sprintf(
                'Safety check failed: IPD %s is linked to bed id %s, but requested bed id is %s.',
                $ipd->ipd_no,
                $ipd->bed,
                $bed->id
            ));
            return self::FAILURE;
        }

        if ($patientId !== null && (int) ($ipd->patient_id ?? 0) !== $patientId) {
            $this->error(sprintf(
                'Safety check failed: IPD %s patient_id=%s does not match --patient-id=%s.',
                $ipd->ipd_no,
                $ipd->patient_id ?? 'NULL',
                $patientId
            ));
            return self::FAILURE;
        }

        $patient = $ipd->patient_id ? Patient::withTrashed()->find($ipd->patient_id) : null;
        $activeHistories = PatientBedHistory::where('ipd_id', $ipd->id)->count();
        $activeOpenHistories = PatientBedHistory::where('ipd_id', $ipd->id)->where('is_active', 'yes')->count();

        $this->info($dryRun ? '=== DRY RUN (no DB writes) ===' : '=== APPLYING CHANGES ===');
        $this->table(
            ['Field', 'Value'],
            [
                ['IPD No', $ipd->ipd_no],
                ['IPD ID', $ipd->id],
                ['Discharged', $ipd->discharged ?: '(empty)'],
                ['Patient ID', $ipd->patient_id ?: '(none)'],
                ['Patient Name', $patient?->patient_name ?: '(missing/blank)'],
                ['Patient Exists', $patient ? 'yes' : 'no'],
                ['Bed ID', $bed->id],
                ['Bed Name', $bed->name],
                ['Bed Occupied Now', ($bed->is_active === 'no' ? 'yes (is_active=no)' : 'no')],
                ['Bed History Rows', $activeHistories],
                ['Active Bed History', $activeOpenHistories],
            ]
        );

        $actions = [
            "Free bed #{$bed->id} ({$bed->name}) => is_active=yes",
            "Delete all patient_bed_history rows for IPD #{$ipd->id}",
            "Clear IPD bed / bed_group_id",
            "Mark IPD discharged=yes (so it leaves active occupied list)",
        ];

        if ($patient) {
            $actions[] = "Delete patient timeline/vitals for patient #{$patient->id} (if tables exist)";
            if ($deletePatient) {
                $actions[] = "Soft-delete patient #{$patient->id} if no other active/draft IPD remains";
            }
        } else {
            $actions[] = 'Patient row missing — skip patient cleanup';
        }

        $this->line('Planned actions:');
        foreach ($actions as $action) {
            $this->line(' - ' . $action);
        }

        if ($dryRun) {
            $this->warn('Dry-run complete. Re-run with --force to apply.');
            return self::SUCCESS;
        }

        try {
            DB::transaction(function () use ($ipd, $bed, $patient, $deletePatient) {
                PatientBedHistory::where('ipd_id', $ipd->id)->delete();

                Bed::where('id', $bed->id)->update(['is_active' => 'yes']);

                $ipd->bed = null;
                $ipd->bed_group_id = null;
                $ipd->discharged = 'yes';
                $ipd->discharged_date = Carbon::now();
                $ipd->save();

                if ($patient) {
                    if (Schema::hasTable('patient_timeline')) {
                        PatientTimeline::where('patient_id', $patient->id)->delete();
                    }
                    if (class_exists(PatientVital::class) && Schema::hasTable((new PatientVital)->getTable())) {
                        PatientVital::where('patient_id', $patient->id)->delete();
                    }

                    if ($deletePatient && !$patient->trashed()) {
                        $otherActiveIpd = IpdDetail::where('patient_id', $patient->id)
                            ->where('id', '!=', $ipd->id)
                            ->where(function ($q) {
                                $q->whereNull('discharged')
                                    ->orWhere('discharged', '')
                                    ->orWhere('discharged', 'no')
                                    ->orWhere('discharged', 'draft');
                            })
                            ->exists();

                        if (!$otherActiveIpd) {
                            $patient->delete();
                        }
                    }
                }
            });
        } catch (\Throwable $e) {
            $this->error('Failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        $bed->refresh();
        $this->info('Done.');
        $this->line("Bed #{$bed->id} ({$bed->name}) is now available (is_active={$bed->is_active}).");
        $this->line("IPD {$ipd->ipd_no} discharged and bed/history cleared.");

        return self::SUCCESS;
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

        return Bed::where('name', 'like', '%' . $input . '%')->orderBy('id')->first();
    }

    protected function resolveIpd(Bed $bed, ?int $patientId, string $ipdNo): ?IpdDetail
    {
        $query = IpdDetail::query();

        if ($ipdNo !== '') {
            $query->where('ipd_no', $ipdNo);
        }

        if ($patientId !== null) {
            $query->where('patient_id', $patientId);
        }

        // Prefer IPD currently pointing to this bed; fall back to active bed history on this bed
        $ipd = (clone $query)->where('bed', $bed->id)->orderByDesc('id')->first();
        if ($ipd) {
            return $ipd;
        }

        $historyIpdId = PatientBedHistory::where('bed_id', $bed->id)
            ->when($ipdNo !== '', function ($q) use ($ipdNo) {
                $q->whereHas('ipd', fn ($iq) => $iq->where('ipd_no', $ipdNo));
            })
            ->when($patientId !== null, function ($q) use ($patientId) {
                $q->whereHas('ipd', fn ($iq) => $iq->where('patient_id', $patientId));
            })
            ->orderByDesc('id')
            ->value('ipd_id');

        if ($historyIpdId) {
            return IpdDetail::find($historyIpdId);
        }

        // Last fallback: if ipd-no/patient alone uniquely identify an IPD
        return $query->orderByDesc('id')->first();
    }
}
