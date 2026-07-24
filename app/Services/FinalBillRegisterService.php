<?php

namespace App\Services;

use App\Http\Controllers\IpdBillingController;
use App\Models\DischargeCard;
use App\Models\IpdDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FinalBillRegisterService
{
    /** @var list<string> */
    private const NUMERIC_COLUMNS = [
        'bed_charges',
        'diagnosis_charges',
        'other_charges',
        'service_charges',
        'home_amount',
        'discount_amount',
        'doctor_visit_amount',
        'package_amount',
    ];

    /**
     * Build day-wise Final Bill Register for discharged cash + insurance patients.
     *
     * @return array{
     *     rows: list<array<string, mixed>>,
     *     grand_total: array<string, float>,
     *     date_from: string,
     *     date_to: string,
     *     patient_count: int,
     *     errors: list<array{ipd_id: int, ipd_no: string, message: string}>
     * }
     */
    public function build(string $dateFrom, string $dateTo): array
    {
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to = Carbon::parse($dateTo)->startOfDay();
        if ($from->gt($to)) {
            throw new \InvalidArgumentException('Date From cannot be after Date To.');
        }

        $dayRows = $this->initializeDayRows($from, $to);
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
            ->whereIn('id', $ipdIds)
            ->where('discharged', 'yes')
            ->get(['id', 'ipd_no', 'discharged'])
            ->keyBy('id');

        foreach ($dischargeCards as $dischargeCard) {
            $ipdId = (int) $dischargeCard->ipd_details_id;
            $ipd = $ipdsById->get($ipdId);
            if (!$ipd) {
                continue;
            }

            $dischargeDay = Carbon::parse($dischargeCard->discharge_date)->format('Y-m-d');
            if (!isset($dayRows[$dischargeDay])) {
                continue;
            }

            try {
                $summary = $billingController->getFinalBillRegisterDaySummary($ipdId);
                foreach (self::NUMERIC_COLUMNS as $column) {
                    $dayRows[$dischargeDay][$column] = round(
                        $dayRows[$dischargeDay][$column] + (float) ($summary[$column] ?? 0),
                        2
                    );
                }
                $patientCount++;
            } catch (\Throwable $e) {
                Log::error('Final Bill Register: failed to summarize IPD', [
                    'ipd_id' => $ipdId,
                    'discharge_date' => $dischargeDay,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = [
                    'ipd_id' => $ipdId,
                    'ipd_no' => (string) ($ipd->ipd_no ?? $ipdId),
                    'message' => $e->getMessage(),
                ];
            }
        }

        $rows = array_values($dayRows);
        $grandTotal = $this->buildGrandTotal($rows);

        return [
            'rows' => $rows,
            'grand_total' => $grandTotal,
            'date_from' => $from->toDateString(),
            'date_to' => $to->toDateString(),
            'patient_count' => $patientCount,
            'errors' => $errors,
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function initializeDayRows(Carbon $from, Carbon $to): array
    {
        $dayRows = [];
        $cursor = $from->copy();

        while ($cursor->lte($to)) {
            $key = $cursor->format('Y-m-d');
            $dayRows[$key] = [
                'bill_date' => $cursor->format('d/m/Y'),
                'bill_date_sort' => $key,
                'bed_charges' => 0.0,
                'diagnosis_charges' => 0.0,
                'other_charges' => 0.0,
                'service_charges' => 0.0,
                'home_amount' => 0.0,
                'discount_amount' => 0.0,
                'doctor_visit_amount' => 0.0,
                'package_amount' => 0.0,
            ];
            $cursor->addDay();
        }

        return $dayRows;
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return array<string, float>
     */
    private function buildGrandTotal(array $rows): array
    {
        $grandTotal = array_fill_keys(self::NUMERIC_COLUMNS, 0.0);

        foreach ($rows as $row) {
            foreach (self::NUMERIC_COLUMNS as $column) {
                $grandTotal[$column] = round($grandTotal[$column] + (float) ($row[$column] ?? 0), 2);
            }
        }

        return $grandTotal;
    }
}
