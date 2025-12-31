<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransactionReportController extends Controller
{
    
    public function dailyTransactionReport(Request $request)
    {

        $request->validate([
            'date_from' => 'required|date',
            'date_to'   => 'required|date',
        ]);

        $dateFrom = Carbon::parse($request->date_from)->format('Y-m-d');
        $dateTo   = Carbon::parse($request->date_to)->format('Y-m-d');

        // 1. Fetch transactions (payments only – like CI logic)
        $transactions = DB::table('transactions')
            ->whereBetween('payment_date', [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59'
            ])
            ->where('type', 'payment')
            ->get();

        // 2. Create date buckets (IMPORTANT)
        $dateArray = [];
        for ($date = Carbon::parse($dateFrom); $date <= Carbon::parse($dateTo); $date->addDay()) {
            $dateArray[$date->format('Y-m-d')] = [
                'total_transaction'   => 0,
                'online_transaction'  => 0,
                'offline_transaction' => 0,
                'amount'              => 0,
            ];
        }

        // 3. Aggregate day-wise
        foreach ($transactions as $tx) {
            $date = Carbon::parse($tx->payment_date)->format('Y-m-d');

            $dateArray[$date]['total_transaction']++;
            $dateArray[$date]['amount'] += $tx->amount;

            if ($tx->payment_mode === 'Online') {
                $dateArray[$date]['online_transaction'] += $tx->amount;
            } else {
                $dateArray[$date]['offline_transaction'] += $tx->amount;
            }
        }

        // 4. Final result (same structure as CI)
        $result = [];
        foreach ($dateArray as $date => $values) {
            $result[] = array_merge(['date' => $date], $values);
        }

        return view('reports.finance.daily-transaction-report', compact('result', 'dateFrom', 'dateTo'));
    }
}
