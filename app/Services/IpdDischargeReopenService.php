<?php

namespace App\Services;

use App\Exceptions\IpdConstraintException;
use App\Models\Bed;
use App\Models\IpdDetail;
use App\Models\PatientBedHistory;
use App\Services\Audit\AuditLogger;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class IpdDischargeReopenService
{
    public function __construct(
        protected IpdStayWindowValidator $stayValidator,
        protected BedOccupancyService $bedOccupancyService,
        protected AuditLogger $auditLogger
    ) {}

    public function canReopen(IpdDetail $ipd): bool
    {
        return ($ipd->discharged ?? 'no') === 'yes'
            && ! empty($ipd->final_bill_generated_at)
            && ! (bool) ($ipd->is_reopened ?? false);
    }

    /**
     * @return array{ipd: IpdDetail, warnings: array<int, string>, bed_restored: bool}
     */
    public function reopen(IpdDetail $ipd, string $reason): array
    {
        $reason = trim($reason);
        if ($reason === '') {
            throw new IpdConstraintException(
                'REOPEN_NOT_ALLOWED',
                'A reason is required to reopen discharge.',
                []
            );
        }

        if (! $this->canReopen($ipd)) {
            if ((bool) ($ipd->is_reopened ?? false)) {
                throw new IpdConstraintException(
                    'REOPEN_NOT_ALLOWED',
                    'This IPD is already in reopen mode.',
                    ['ipd_no' => $ipd->ipd_no]
                );
            }
            if (empty($ipd->final_bill_generated_at)) {
                throw new IpdConstraintException(
                    'REOPEN_NOT_ALLOWED',
                    'Discharge reopen is allowed only after Final Bill is generated.',
                    ['ipd_no' => $ipd->ipd_no]
                );
            }
            throw new IpdConstraintException(
                'REOPEN_NOT_ALLOWED',
                'This IPD cannot be reopened in its current state.',
                ['ipd_no' => $ipd->ipd_no]
            );
        }

        $warnings = [];
        $bedRestored = false;

        DB::transaction(function () use ($ipd, $reason, &$warnings, &$bedRestored) {
            $before = [
                'final_bill_generated_at' => optional($ipd->final_bill_generated_at)?->toDateTimeString(),
                'final_bill_generated_by' => $ipd->final_bill_generated_by,
                'physical_release_at' => optional($ipd->physical_release_at)?->toDateTimeString(),
                'is_reopened' => (bool) ($ipd->is_reopened ?? false),
            ];

            // Keep discharged=yes and discharge card; clear final-bill lock only.
            $ipd->final_bill_generated_at = null;
            $ipd->final_bill_generated_by = null;
            $ipd->physical_release_at = null;
            $ipd->is_reopened = true;
            $ipd->reopened_at = Carbon::now();
            $ipd->reopened_by = Auth::id();
            $ipd->reopen_reason = $reason;
            $ipd->reopen_closed_at = null;
            $ipd->save();

            $restore = $this->tryRestoreLastBed($ipd);
            $bedRestored = (bool) ($restore['restored'] ?? false);
            if (! empty($restore['warning'])) {
                $warnings[] = $restore['warning'];
            }

            $this->auditLogger->log([
                'module' => 'discharge',
                'entity_type' => 'ipd_details',
                'entity_id' => $ipd->id,
                'parent_type' => 'ipd_details',
                'parent_id' => $ipd->id,
                'patient_id' => $ipd->patient_id,
                'case_no' => $ipd->ipd_no,
                'action' => 'discharge_reopened',
                'reason' => $reason,
                'old_values' => $before,
                'new_values' => [
                    'final_bill_generated_at' => null,
                    'is_reopened' => true,
                    'reopened_at' => optional($ipd->reopened_at)?->toDateTimeString(),
                    'bed_restored' => $bedRestored,
                ],
                'meta' => [
                    'warnings' => $warnings,
                ],
            ]);
        });

        return [
            'ipd' => $ipd->fresh(),
            'warnings' => $warnings,
            'bed_restored' => $bedRestored,
        ];
    }

    /**
     * Called when final bill is generated again after reopen.
     */
    public function markReopenClosed(IpdDetail $ipd): void
    {
        if (! (bool) ($ipd->is_reopened ?? false)) {
            return;
        }

        $ipd->is_reopened = false;
        $ipd->reopen_closed_at = Carbon::now();
        $ipd->save();

        $this->auditLogger->log([
            'module' => 'discharge',
            'entity_type' => 'ipd_details',
            'entity_id' => $ipd->id,
            'parent_type' => 'ipd_details',
            'parent_id' => $ipd->id,
            'patient_id' => $ipd->patient_id,
            'case_no' => $ipd->ipd_no,
            'action' => 'discharge_re_finalized',
            'reason' => $ipd->reopen_reason,
            'new_values' => [
                'is_reopened' => false,
                'reopen_closed_at' => optional($ipd->reopen_closed_at)?->toDateTimeString(),
                'final_bill_generated_at' => optional($ipd->final_bill_generated_at)?->toDateTimeString(),
            ],
        ]);
    }

    /**
     * @return array{restored: bool, warning: ?string}
     */
    protected function tryRestoreLastBed(IpdDetail $ipd): array
    {
        $last = PatientBedHistory::where('ipd_id', $ipd->id)
            ->orderByDesc('from_date')
            ->orderByDesc('id')
            ->first();

        if (! $last || ! $last->bed_id) {
            return [
                'restored' => false,
                'warning' => 'No bed history found to restore. You can add charges; assign a free bed within admit–discharge window if needed.',
            ];
        }

        $dischargeAt = $this->stayValidator->resolveDischargeAt($ipd);
        $from = Carbon::parse($last->from_date);
        // Availability for re-occupying from original from_date through discharge.
        $availability = $this->bedOccupancyService->checkAvailability(
            (int) $last->bed_id,
            $from,
            $dischargeAt,
            (int) $last->id,
            (int) $ipd->id
        );

        if (! ($availability['available'] ?? false)) {
            return [
                'restored' => false,
                'warning' => 'Last bed could not be re-occupied because it is assigned to another patient. '
                    . ($availability['message'] ?? '')
                    . ' Reopen continues in charges-only mode; assign another free bed within the stay window if required.',
            ];
        }

        // Re-open last segment for rate edits; keep to_date = discharge (clinical end frozen).
        $last->to_date = $dischargeAt->format('Y-m-d H:i:s');
        $last->is_active = 'yes';
        $last->save();

        Bed::where('id', $last->bed_id)->update(['is_active' => 'no']);

        $ipd->bed = $last->bed_id;
        $ipd->bed_group_id = $last->bed_group_id;
        $ipd->save();

        return ['restored' => true, 'warning' => null];
    }
}
