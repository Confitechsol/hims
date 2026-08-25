<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ipd_details.id is int(11); bigint unsigned FK fails on this schema.
        if (! Schema::hasColumn('doctor_visits', 'ipd_id')) {
            Schema::table('doctor_visits', function (Blueprint $table) {
                $table->integer('ipd_id')->nullable()->after('patient_id');
            });
        } else {
            DB::statement('ALTER TABLE doctor_visits MODIFY ipd_id INT NULL');
        }

        $indexExists = collect(DB::select('SHOW INDEX FROM doctor_visits WHERE Key_name = ?', ['doctor_visits_ipd_id_index']))
            ->isNotEmpty();
        if (! $indexExists) {
            Schema::table('doctor_visits', function (Blueprint $table) {
                $table->index('ipd_id');
            });
        }

        $this->backfillIpdIds();
    }

    public function down(): void
    {
        if (! Schema::hasColumn('doctor_visits', 'ipd_id')) {
            return;
        }

        Schema::table('doctor_visits', function (Blueprint $table) {
            try {
                $table->dropIndex(['ipd_id']);
            } catch (\Throwable $e) {
                // ignore
            }
            $table->dropColumn('ipd_id');
        });
    }

    /**
     * Assign legacy visits to the IPD whose stay window contains the visit date.
     * Prefer the latest matching IPD when multiple windows overlap.
     */
    private function backfillIpdIds(): void
    {
        $visits = DB::table('doctor_visits')
            ->whereNull('ipd_id')
            ->whereNull('deleted_at')
            ->select('id', 'patient_id', 'visit_date', 'reporting_date', 'created_at')
            ->get();

        foreach ($visits as $visit) {
            $effectiveDate = $visit->visit_date
                ?? ($visit->reporting_date ? substr((string) $visit->reporting_date, 0, 10) : null)
                ?? ($visit->created_at ? substr((string) $visit->created_at, 0, 10) : null);

            if (! $effectiveDate || ! $visit->patient_id) {
                continue;
            }

            $ipds = DB::table('ipd_details')
                ->where('patient_id', $visit->patient_id)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->get(['id', 'date', 'discharged', 'discharged_date']);

            $matchedIpdId = null;
            foreach ($ipds as $ipd) {
                $admitYmd = $ipd->date ? substr((string) $ipd->date, 0, 10) : null;
                if (! $admitYmd || $effectiveDate < $admitYmd) {
                    continue;
                }

                $endYmd = null;
                if (($ipd->discharged ?? '') === 'yes' && ! empty($ipd->discharged_date)) {
                    $endYmd = substr((string) $ipd->discharged_date, 0, 10);
                }

                if ($endYmd !== null && $effectiveDate > $endYmd) {
                    continue;
                }

                $matchedIpdId = $ipd->id;
                break;
            }

            if ($matchedIpdId) {
                DB::table('doctor_visits')
                    ->where('id', $visit->id)
                    ->update(['ipd_id' => $matchedIpdId]);
            }
        }
    }
};
