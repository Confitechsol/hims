<?php

namespace App\Services;

use App\Http\Controllers\IpdBillingController;
use App\Models\DischargeCard;
use App\Models\IpdDetail;
use App\Models\IpdPackage;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * GST Break Report: one row per bill charge line (print head = charge type)
 * plus patient money receipts under the Money Receipt head.
 */
class GstBreakReportService
{
    /**
     * @return array{
     *     rows: list<array<string, mixed>>,
     *     date_from: string,
     *     date_to: string,
     *     ipd_id: int|null,
     *     patient_label: string|null,
     *     line_count: int,
     *     total_amount: float,
     *     errors: list<array{ipd_id: int, ipd_no: string, message: string}>
     * }
     */
    public function build(string $dateFrom, string $dateTo, ?int $ipdId = null): array
    {
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to = Carbon::parse($dateTo)->startOfDay();
        if ($from->gt($to)) {
            throw new \InvalidArgumentException('Date From cannot be after Date To.');
        }

        $billing = app(IpdBillingController::class);
        $errors = [];
        $rows = [];
        $sl = 1;

        $cards = DischargeCard::query()
            ->whereNotNull('ipd_details_id')
            ->whereDate('discharge_date', '>=', $from->toDateString())
            ->whereDate('discharge_date', '<=', $to->toDateString())
            ->when($ipdId, fn ($q) => $q->where('ipd_details_id', $ipdId))
            ->orderBy('discharge_date')
            ->orderBy('id')
            ->get(['id', 'ipd_details_id', 'discharge_date']);

        $ipdIds = $cards->pluck('ipd_details_id')->filter()->unique()->values();
        $ipds = IpdDetail::query()
            ->with([
                'patient' => static fn ($q) => $q->withTrashed(),
                'doctor:id,name,surname,registration_no',
            ])
            ->whereIn('id', $ipdIds)
            ->where('discharged', 'yes')
            ->get()
            ->keyBy('id');

        $patientLabel = null;

        foreach ($cards as $card) {
            $id = (int) $card->ipd_details_id;
            $ipd = $ipds->get($id);
            if (! $ipd) {
                continue;
            }

            $billDate = Carbon::parse($card->discharge_date);
            $doctor = $ipd->doctor;
            $doctorName = trim(implode(' ', array_filter([
                $doctor->name ?? null,
                $doctor->surname ?? null,
            ])));
            $header = [
                'admission_no' => (string) ($ipd->ipd_no ?? '-'),
                'admission_date' => $ipd->date
                    ? Carbon::parse($ipd->date)->format('d/m/Y h:i A')
                    : '-',
                'patient_name' => (string) ($ipd->patient->patient_name ?? '-'),
                'doctor_name' => $doctorName !== '' ? $doctorName : '-',
                'bill_no' => $this->formatFinalBillNo($ipd, $billDate),
                'bill_date' => $billDate->format('d/m/Y'),
                'discharge_date' => $billDate->format('d/m/Y'),
            ];
            if ($ipdId) {
                $patientLabel = $header['admission_no'] . ' — ' . $header['patient_name'];
            }

            try {
                $lines = $billing->getFinalBillRegisterRows($id, $billDate->toDateString());
                $lines = $this->applyPackageBedRule($id, $lines);
                $lines = array_merge($lines, $this->moneyReceiptLines($id));

                foreach ($lines as $line) {
                    $rows[] = array_merge($header, [
                        'sl_no' => $sl++,
                        'print_head' => (string) ($line['charge_category_head'] ?? '-'),
                        'particulars' => (string) ($line['charge_details'] ?? ''),
                        'amount' => round((float) ($line['amount'] ?? 0), 2),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('GST Break Report failed for IPD', [
                    'ipd_id' => $id,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = [
                    'ipd_id' => $id,
                    'ipd_no' => (string) ($ipd->ipd_no ?? $id),
                    'message' => $e->getMessage(),
                ];
            }
        }

        $total = 0.0;
        foreach ($rows as $row) {
            $total = round($total + (float) $row['amount'], 2);
        }

        return [
            'rows' => $rows,
            'date_from' => $from->toDateString(),
            'date_to' => $to->toDateString(),
            'ipd_id' => $ipdId,
            'patient_label' => $patientLabel,
            'line_count' => count($rows),
            'total_amount' => $total,
            'errors' => $errors,
        ];
    }

    /**
     * When a package is applied, bed charge and bed GST are not billed.
     *
     * @param  list<array<string, mixed>>  $lines
     * @return list<array<string, mixed>>
     */
    private function applyPackageBedRule(int $ipdId, array $lines): array
    {
        $packages = IpdPackage::query()
            ->with('package:id,name')
            ->where('ipd_id', $ipdId)
            ->where('status', 'applied')
            ->get();

        if ($packages->isEmpty()) {
            return $lines;
        }

        $lines = array_values(array_filter($lines, static function (array $line): bool {
            $head = (string) ($line['charge_category_head'] ?? '');

            return ! in_array($head, ['Bed', 'CGST', 'SGST'], true);
        }));

        foreach ($packages as $package) {
            $name = trim((string) ($package->package->name ?? 'Package'));
            $lines[] = [
                'charge_category_head' => 'Package',
                'charge_details' => $name,
                'amount' => round((float) ($package->final_amount ?? 0), 2),
            ];
        }

        return $lines;
    }

    /**
     * Patient money receipts for this IPD, printed under the Money Receipt head.
     *
     * @return list<array{charge_category_head: string, charge_details: string, amount: float}>
     */
    private function moneyReceiptLines(int $ipdId): array
    {
        $receipts = Transaction::query()
            ->where('ipd_id', $ipdId)
            ->whereNotNull('receipt_no')
            ->where('receipt_no', '!=', '')
            ->whereNull('deleted_at')
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get(['id', 'receipt_no', 'receipt_type', 'type', 'amount', 'payment_date', 'payment_mode']);

        $lines = [];
        foreach ($receipts as $receipt) {
            $amount = round((float) ($receipt->amount ?? 0), 2);
            $isRefund = strcasecmp((string) ($receipt->type ?? ''), 'refund') === 0
                || strcasecmp((string) ($receipt->receipt_type ?? ''), 'Refund') === 0;
            if ($isRefund) {
                $amount = -abs($amount);
            }

            $date = $receipt->payment_date
                ? Carbon::parse($receipt->payment_date)->format('d/m/Y')
                : '';
            $parts = array_filter([
                'Receipt ' . $receipt->receipt_no,
                $receipt->receipt_type ?: null,
                $receipt->payment_mode ?: null,
                $date !== '' ? $date : null,
            ]);

            $lines[] = [
                'charge_category_head' => 'Money Receipt',
                'charge_details' => implode(' | ', $parts),
                'amount' => $amount,
            ];
        }

        return $lines;
    }

    private function formatFinalBillNo(IpdDetail $ipd, Carbon $billDate): string
    {
        $y = (int) $billDate->format('y');

        return 'F-' . str_pad((string) $ipd->id, 6, '0', STR_PAD_LEFT) . '/' . $y . '-' . str_pad((string) ($y + 1), 2, '0', STR_PAD_LEFT);
    }
}
