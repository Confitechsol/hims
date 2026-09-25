<?php

namespace App\Services;

use App\Http\Controllers\IpdBillingController;
use App\Models\DischargeCard;
use App\Models\Doctor;
use App\Models\IpdDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Doctor Revenue (Referral) Report — discharged IPD final bills grouped by consulting/referral doctor.
 *
 * Net Hospital Amount = Bill Amount − (Doctor Visit + Hospital/MOU Discount).
 * Package rule: bed charges are already zeroed in billing breakup when a package is applied.
 */
class DoctorRevenueReportService
{
    /** @var list<string> */
    private const NUMERIC_COLUMNS = [
        'bed_charges',
        'diagnosis_charges',
        'other_charges',
        'package_amount',
        'doctor_visit_amount',
        'bill_amount',
        'discount_amount',
        'net_hospital_amount',
    ];

    /**
     * @return array{
     *     groups: list<array<string, mixed>>,
     *     grand_total: array<string, float>,
     *     date_from: string,
     *     date_to: string,
     *     doctor_id: int|null,
     *     doctor_filter_label: string|null,
     *     patient_count: int,
     *     errors: list<array{ipd_id: int, ipd_no: string, message: string}>
     * }
     */
    public function build(string $dateFrom, string $dateTo, ?int $doctorId = null): array
    {
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to = Carbon::parse($dateTo)->startOfDay();
        if ($from->gt($to)) {
            throw new \InvalidArgumentException('Date From cannot be after Date To.');
        }

        $billingController = app(IpdBillingController::class);
        $errors = [];
        $patientCount = 0;

        $dischargeCards = DischargeCard::query()
            ->whereNotNull('ipd_details_id')
            ->whereDate('discharge_date', '>=', $from->toDateString())
            ->whereDate('discharge_date', '<=', $to->toDateString())
            ->orderBy('discharge_date')
            ->get(['id', 'ipd_details_id', 'discharge_date']);

        $ipdIds = $dischargeCards->pluck('ipd_details_id')->filter()->unique()->values();
        $ipdsById = IpdDetail::query()
            ->with([
                'patient' => static fn ($q) => $q->withTrashed(),
                'doctor',
            ])
            ->whereIn('id', $ipdIds)
            ->where('discharged', 'yes')
            ->whereNotNull('cons_doctor')
            ->when($doctorId, function ($query) use ($doctorId) {
                $query->where('cons_doctor', $doctorId);
            })
            ->get()
            ->keyBy('id');

        /** @var array<int, array<string, mixed>> $grouped */
        $grouped = [];

        foreach ($dischargeCards as $dischargeCard) {
            $ipdId = (int) $dischargeCard->ipd_details_id;
            $ipd = $ipdsById->get($ipdId);
            if (! $ipd) {
                continue;
            }

            $doctorId = (int) ($ipd->cons_doctor ?? 0);
            if ($doctorId <= 0) {
                continue;
            }

            $billDate = Carbon::parse($dischargeCard->discharge_date);

            try {
                $summary = $billingController->getFinalBillRegisterDaySummary($ipdId);

                $bed = round((float) ($summary['bed_charges'] ?? 0), 2);
                $diag = round((float) ($summary['diagnosis_charges'] ?? 0), 2);
                $other = round((float) ($summary['other_charges'] ?? 0), 2);
                $service = round((float) ($summary['service_charges'] ?? 0), 2);
                $package = round((float) ($summary['package_amount'] ?? 0), 2);
                $doctorVisit = round((float) ($summary['doctor_visit_amount'] ?? 0), 2);
                $discount = round((float) ($summary['discount_amount'] ?? 0), 2);

                // Bill Amt = all charge heads (incl. GST service charges). Package already zeros bed in breakup.
                $billAmount = round($bed + $diag + $other + $service + $package + $doctorVisit, 2);
                $netHospital = round(max(0, $billAmount - $doctorVisit - $discount), 2);

                if (! isset($grouped[$doctorId])) {
                    $doctor = $ipd->doctor;
                    if (! $doctor) {
                        $doctor = Doctor::query()->find($doctorId);
                    }
                    $name = trim((string) ($doctor->name ?? 'Unknown Doctor'));
                    $reg = trim((string) ($doctor->registration_no ?? ''));
                    $grouped[$doctorId] = [
                        'doctor_id' => $doctorId,
                        'doctor_name' => $name,
                        'registration_no' => $reg,
                        'doctor_label' => $reg !== '' ? "{$name} ({$reg})" : $name,
                        'rows' => [],
                        'totals' => array_fill_keys(self::NUMERIC_COLUMNS, 0.0),
                    ];
                }

                $row = [
                    'ipd_id' => $ipdId,
                    'patient_name' => (string) ($ipd->patient->patient_name ?? '-'),
                    'bill_date' => $billDate->format('d-M-y'),
                    'bill_date_sort' => $billDate->format('Y-m-d'),
                    'bill_no' => $this->formatFinalBillNo($ipd, $billDate),
                    'adm_no' => (string) ($ipd->ipd_no ?? '-'),
                    'bed_charges' => $bed,
                    'diagnosis_charges' => $diag,
                    'other_charges' => round($other + $service, 2),
                    'package_amount' => $package,
                    'doctor_visit_amount' => $doctorVisit,
                    'bill_amount' => $billAmount,
                    'discount_amount' => $discount,
                    'net_hospital_amount' => $netHospital,
                ];

                $grouped[$doctorId]['rows'][] = $row;
                foreach (self::NUMERIC_COLUMNS as $column) {
                    $grouped[$doctorId]['totals'][$column] = round(
                        $grouped[$doctorId]['totals'][$column] + (float) $row[$column],
                        2
                    );
                }
                $patientCount++;
            } catch (\Throwable $e) {
                Log::error('Doctor Revenue Report: failed to summarize IPD', [
                    'ipd_id' => $ipdId,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = [
                    'ipd_id' => $ipdId,
                    'ipd_no' => (string) ($ipd->ipd_no ?? $ipdId),
                    'message' => $e->getMessage(),
                ];
            }
        }

        $groups = array_values($grouped);
        usort($groups, static function (array $a, array $b): int {
            return strcasecmp((string) $a['doctor_name'], (string) $b['doctor_name']);
        });

        foreach ($groups as &$group) {
            usort($group['rows'], static function (array $a, array $b): int {
                $cmp = strcmp((string) $a['bill_date_sort'], (string) $b['bill_date_sort']);
                if ($cmp !== 0) {
                    return $cmp;
                }

                return strcasecmp((string) $a['patient_name'], (string) $b['patient_name']);
            });
            $sl = 1;
            foreach ($group['rows'] as &$row) {
                $row['sl_no'] = $sl++;
            }
            unset($row);
        }
        unset($group);

        $doctorFilterLabel = null;
        if ($doctorId) {
            $selectedDoctor = Doctor::query()->find($doctorId);
            if ($selectedDoctor) {
                $name = trim((string) $selectedDoctor->name);
                $reg = trim((string) ($selectedDoctor->registration_no ?? ''));
                $doctorFilterLabel = $reg !== '' ? "{$name} ({$reg})" : $name;
            }
        }

        return [
            'groups' => $groups,
            'grand_total' => $this->buildGrandTotal($groups),
            'date_from' => $from->toDateString(),
            'date_to' => $to->toDateString(),
            'doctor_id' => $doctorId,
            'doctor_filter_label' => $doctorFilterLabel,
            'patient_count' => $patientCount,
            'errors' => $errors,
        ];
    }

    private function formatFinalBillNo(IpdDetail $ipd, Carbon $billDate): string
    {
        $y = (int) $billDate->format('y');

        return 'F-' . str_pad((string) $ipd->id, 6, '0', STR_PAD_LEFT) . '/' . $y . '-' . str_pad((string) ($y + 1), 2, '0', STR_PAD_LEFT);
    }

    /**
     * @param  list<array<string, mixed>>  $groups
     * @return array<string, float>
     */
    private function buildGrandTotal(array $groups): array
    {
        $grandTotal = array_fill_keys(self::NUMERIC_COLUMNS, 0.0);

        foreach ($groups as $group) {
            foreach (self::NUMERIC_COLUMNS as $column) {
                $grandTotal[$column] = round(
                    $grandTotal[$column] + (float) ($group['totals'][$column] ?? 0),
                    2
                );
            }
        }

        return $grandTotal;
    }
}
