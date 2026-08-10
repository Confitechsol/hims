<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Daily Cash Book: day-wise opening balance, receipts (Cash/UPI/Transfer), expenses, closing balance.
 */
class DailyCashBookService
{
    public const MODE_CASH = 'Cash';

    public const MODE_UPI = 'UPI';

    public const MODE_TRANSFER = 'Transfer to Bank Account';

    public const MAX_RANGE_DAYS = 366;

    /**
     * @return array{
     *     rows: list<array<string, mixed>>,
     *     date_from: string,
     *     date_to: string,
     *     totals: array<string, float>,
     *     company_name: string|null
     * }
     */
    public function buildReport(string $dateFrom, string $dateTo, ?string $companyName = null): array
    {
        $from = Carbon::parse($dateFrom)->startOfDay();
        $to = Carbon::parse($dateTo)->startOfDay();

        if ($from->gt($to)) {
            throw new InvalidArgumentException('Date from must be on or before date to.');
        }

        $dayCount = $from->diffInDays($to) + 1;
        if ($dayCount > self::MAX_RANGE_DAYS) {
            throw new InvalidArgumentException('Date range cannot exceed ' . self::MAX_RANGE_DAYS . ' days.');
        }

        $receiptsByDay = $this->aggregateReceiptsThrough($dateTo);
        $expensesByDay = $this->aggregateExpensesThrough($dateTo);

        $firstActivity = $this->resolveFirstActivityDate($receiptsByDay, $expensesByDay);

        $runningBalance = 0.0;
        $dayBeforeFrom = $from->copy()->subDay();

        if ($firstActivity !== null && $firstActivity->lte($dayBeforeFrom)) {
            for ($cursor = $firstActivity->copy(); $cursor->lte($dayBeforeFrom); $cursor->addDay()) {
                $key = $cursor->format('Y-m-d');
                $runningBalance = $this->closingBalanceForDay(
                    $key,
                    $runningBalance,
                    $receiptsByDay,
                    $expensesByDay
                );
            }
        }

        $rows = [];
        $totals = [
            'total_clear_balance' => 0.0,
            'total_cash' => 0.0,
            'total_upi' => 0.0,
            'total_transfer' => 0.0,
            'total_received' => 0.0,
            'total_expense' => 0.0,
            'balance_amount' => 0.0,
        ];

        for ($cursor = $from->copy(); $cursor->lte($to); $cursor->addDay()) {
            $key = $cursor->format('Y-m-d');
            $opening = round($runningBalance, 2);

            $cash = round((float) ($receiptsByDay[$key]['cash'] ?? 0), 2);
            $upi = round((float) ($receiptsByDay[$key]['upi'] ?? 0), 2);
            $transfer = round((float) ($receiptsByDay[$key]['transfer'] ?? 0), 2);
            $expense = round((float) ($expensesByDay[$key] ?? 0), 2);

            $totalReceived = round($cash + $upi + $transfer, 2);
            $balanceAmount = round(($cash - $expense) + $upi + $transfer + $opening, 2);

            $rows[] = [
                'date_key' => $key,
                'date' => $cursor->format('d/m/Y'),
                'total_clear_balance' => $opening,
                'total_cash' => $cash,
                'total_upi' => $upi,
                'total_transfer' => $transfer,
                'total_received' => $totalReceived,
                'total_expense' => $expense,
                'balance_amount' => $balanceAmount,
            ];

            $totals['total_cash'] += $cash;
            $totals['total_upi'] += $upi;
            $totals['total_transfer'] += $transfer;
            $totals['total_received'] += $totalReceived;
            $totals['total_expense'] += $expense;

            $runningBalance = $balanceAmount;
        }

        if (! empty($rows)) {
            $totals['total_clear_balance'] = (float) $rows[0]['total_clear_balance'];
            $totals['balance_amount'] = (float) $rows[count($rows) - 1]['balance_amount'];
        }

        foreach ($totals as $k => $v) {
            $totals[$k] = round($v, 2);
        }

        return [
            'rows' => $rows,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'totals' => $totals,
            'company_name' => $companyName,
        ];
    }

    protected function closingBalanceForDay(
        string $dateKey,
        float $opening,
        array $receiptsByDay,
        array $expensesByDay
    ): float {
        $cash = (float) ($receiptsByDay[$dateKey]['cash'] ?? 0);
        $upi = (float) ($receiptsByDay[$dateKey]['upi'] ?? 0);
        $transfer = (float) ($receiptsByDay[$dateKey]['transfer'] ?? 0);
        $expense = (float) ($expensesByDay[$dateKey] ?? 0);

        return round(($cash - $expense) + $upi + $transfer + $opening, 2);
    }

    /**
     * @return array<string, array{cash: float, upi: float, transfer: float}>
     */
    protected function aggregateReceiptsThrough(string $dateTo): array
    {
        $signedAmount = "CASE WHEN LOWER(TRIM(COALESCE(receipt_type, ''))) = 'refund' "
            . "THEN -ABS(COALESCE(amount, 0)) ELSE ABS(COALESCE(amount, 0)) END";

        $rows = DB::table('transactions')
            ->selectRaw('DATE(payment_date) as day')
            ->selectRaw(
                "SUM(CASE WHEN TRIM(COALESCE(payment_mode, '')) = '" . self::MODE_CASH . "' THEN {$signedAmount} ELSE 0 END) as cash_total"
            )
            ->selectRaw(
                "SUM(CASE WHEN TRIM(COALESCE(payment_mode, '')) = '" . self::MODE_UPI . "' THEN {$signedAmount} ELSE 0 END) as upi_total"
            )
            ->selectRaw(
                "SUM(CASE WHEN TRIM(COALESCE(payment_mode, '')) = '" . self::MODE_TRANSFER . "' THEN {$signedAmount} ELSE 0 END) as transfer_total"
            )
            ->where('type', 'payment')
            ->whereNotNull('receipt_no')
            ->where('payment_date', '<=', $dateTo . ' 23:59:59')
            ->groupByRaw('DATE(payment_date)')
            ->get();

        $map = [];
        foreach ($rows as $row) {
            if (empty($row->day)) {
                continue;
            }
            $key = Carbon::parse($row->day)->format('Y-m-d');
            $map[$key] = [
                'cash' => (float) ($row->cash_total ?? 0),
                'upi' => (float) ($row->upi_total ?? 0),
                'transfer' => (float) ($row->transfer_total ?? 0),
            ];
        }

        return $map;
    }

    /**
     * @return array<string, float>
     */
    protected function aggregateExpensesThrough(string $dateTo): array
    {
        $rows = DB::table('expenses')
            ->selectRaw('date as day')
            ->selectRaw('SUM(COALESCE(amount, 0)) as expense_total')
            ->where('date', '<=', $dateTo)
            ->where(function ($q) {
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->groupBy('date')
            ->get();

        $map = [];
        foreach ($rows as $row) {
            if (empty($row->day)) {
                continue;
            }
            $key = Carbon::parse($row->day)->format('Y-m-d');
            $map[$key] = (float) ($row->expense_total ?? 0);
        }

        return $map;
    }

    /**
     * @param  array<string, array{cash: float, upi: float, transfer: float}>  $receiptsByDay
     * @param  array<string, float>  $expensesByDay
     */
    protected function resolveFirstActivityDate(array $receiptsByDay, array $expensesByDay): ?Carbon
    {
        $keys = array_unique(array_merge(array_keys($receiptsByDay), array_keys($expensesByDay)));
        if ($keys === []) {
            return null;
        }
        sort($keys);

        return Carbon::parse($keys[0])->startOfDay();
    }
}
