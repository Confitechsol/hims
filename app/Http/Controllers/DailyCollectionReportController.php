<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DailyCollectionReportController extends Controller
{
    /**
     * Get patient name for a transaction (reuse from MoneyReceiptRegisterController pattern)
     */
    protected function getPatientNameForTransaction(Transaction $transaction): string
    {
        if ($transaction->patient && $transaction->patient->patient_name) {
            $name = trim($transaction->patient->patient_name);
            if (!empty($name)) {
                return $name;
            }
        }
        
        if ($transaction->ipd && $transaction->ipd->patient && $transaction->ipd->patient->patient_name) {
            $name = trim($transaction->ipd->patient->patient_name);
            if (!empty($name)) {
                return $name;
            }
        }
        
        if ($transaction->opd && $transaction->opd->patient && $transaction->opd->patient->patient_name) {
            $name = trim($transaction->opd->patient->patient_name);
            if (!empty($name)) {
                return $name;
            }
        }
        
        return '-';
    }

    /**
     * Build report data: summary (totals by payment mode) + detail (all transactions)
     */
    protected function getReportData(string $dateFrom, string $dateTo): array
    {
        // Get all payment transactions in date range
        $transactions = Transaction::with(['patient', 'ipd.patient', 'opd.patient', 'receiver'])
            ->where('type', 'payment')
            ->whereNotNull('receipt_no')
            ->whereBetween('payment_date', [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59',
            ])
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get();

        // Summary: totals by payment mode
        $summaryByMode = [];
        $totalCollection = 0;
        $totalRefund = 0;
        
        $paymentModes = ['Cash', 'Cheque', 'Card', 'UPI', 'Online', 'Transfer to Bank Account', 'Other'];
        
        foreach ($paymentModes as $mode) {
            $summaryByMode[$mode] = 0;
        }

        // Detail rows
        $detailRows = [];
        $groupedByDate = [];
        
        foreach ($transactions as $t) {
            $amount = (float) ($t->amount ?? 0);
            $isRefund = (strcasecmp($t->receipt_type ?? '', 'Refund') === 0);
            
            if ($isRefund) {
                $totalRefund += $amount;
            } else {
                $totalCollection += $amount;
            }
            
            $mode = $t->payment_mode ?? 'Other';
            if (!isset($summaryByMode[$mode])) {
                $summaryByMode[$mode] = 0;
            }
            if ($isRefund) {
                $summaryByMode[$mode] -= $amount; // Refunds reduce the mode total
            } else {
                $summaryByMode[$mode] += $amount;
            }

            $paymentDate = $t->payment_date ? Carbon::parse($t->payment_date) : null;
            $dateKey = $paymentDate ? $paymentDate->format('Y-m-d') : '';
            
            $patientName = $this->getPatientNameForTransaction($t);
            
            $row = [
                'date' => $paymentDate ? $paymentDate->format('d/m/Y') : '-',
                'date_key' => $dateKey,
                'time' => $paymentDate ? $paymentDate->format('H:i') : '-',
                'receipt_no' => $t->receipt_no ?? '-',
                'receipt_type' => $t->receipt_type ?? '-',
                'patient_name' => $patientName,
                'amount' => $amount,
                'payment_mode' => $mode,
                'received_by' => $t->receiver->username ?? $t->receiver->name ?? '-',
                'is_refund' => $isRefund,
            ];
            
            $detailRows[] = $row;
            
            // Group by date for subtotals
            if ($dateKey) {
                if (!isset($groupedByDate[$dateKey])) {
                    $groupedByDate[$dateKey] = [
                        'date' => $paymentDate->format('d/m/Y'),
                        'total' => 0,
                        'refund' => 0,
                        'net' => 0,
                        'rows' => [],
                    ];
                }
                $groupedByDate[$dateKey]['rows'][] = $row;
                if ($isRefund) {
                    $groupedByDate[$dateKey]['refund'] += $amount;
                } else {
                    $groupedByDate[$dateKey]['total'] += $amount;
                }
                $groupedByDate[$dateKey]['net'] = $groupedByDate[$dateKey]['total'] - $groupedByDate[$dateKey]['refund'];
            }
        }
        
        ksort($groupedByDate);

        $netCollection = $totalCollection - $totalRefund;

        return [
            'summary' => [
                'total_collection' => $totalCollection,
                'total_refund' => $totalRefund,
                'net_collection' => $netCollection,
                'by_payment_mode' => $summaryByMode,
            ],
            'detail_rows' => $detailRows,
            'grouped_by_date' => $groupedByDate,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];
    }

    /**
     * Show report form and results
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->format('Y-m-d'));
        $dateTo   = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        $result = null;
        if ($request->has('date_from') && $request->has('date_to')) {
            $request->validate([
                'date_from' => 'required|date',
                'date_to'   => 'required|date|after_or_equal:date_from',
            ]);
            $result = $this->getReportData($dateFrom, $dateTo);
        }

        return view('admin.reports.finance.daily-collection-report', compact('result', 'dateFrom', 'dateTo'));
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to'   => 'required|date|after_or_equal:date_from',
        ]);
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');
        $result   = $this->getReportData($dateFrom, $dateTo);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daily Collection Report');

        $rowNum = 1;

        // Title
        $sheet->setCellValue('A' . $rowNum, 'Daily Collection Report');
        $sheet->mergeCells('A' . $rowNum . ':J' . $rowNum);
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $rowNum++;

        // Period
        $sheet->setCellValue('A' . $rowNum, 'Period: ' . Carbon::parse($dateFrom)->format('d/m/Y') . ' to ' . Carbon::parse($dateTo)->format('d/m/Y'));
        $sheet->mergeCells('A' . $rowNum . ':J' . $rowNum);
        $rowNum++;
        $rowNum++; // Blank row

        // Summary Section
        $sheet->setCellValue('A' . $rowNum, 'SUMMARY');
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
        $rowNum++;

        $sheet->setCellValue('A' . $rowNum, 'Total Collection:');
        $sheet->setCellValue('B' . $rowNum, $result['summary']['total_collection']);
        $sheet->getStyle('A' . $rowNum . ':B' . $rowNum)->getFont()->setBold(true);
        $rowNum++;

        $sheet->setCellValue('A' . $rowNum, 'Total Refund:');
        $sheet->setCellValue('B' . $rowNum, $result['summary']['total_refund']);
        $rowNum++;

        $sheet->setCellValue('A' . $rowNum, 'Net Collection:');
        $sheet->setCellValue('B' . $rowNum, $result['summary']['net_collection']);
        $sheet->getStyle('A' . $rowNum . ':B' . $rowNum)->getFont()->setBold(true);
        $rowNum++;
        $rowNum++; // Blank row

        // By Payment Mode
        $sheet->setCellValue('A' . $rowNum, 'By Payment Mode:');
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
        $rowNum++;
        $sheet->setCellValue('A' . $rowNum, 'Payment Mode');
        $sheet->setCellValue('B' . $rowNum, 'Amount');
        $sheet->getStyle('A' . $rowNum . ':B' . $rowNum)->getFont()->setBold(true);
        $rowNum++;
        foreach ($result['summary']['by_payment_mode'] as $mode => $amount) {
            if ($amount != 0) {
                $sheet->setCellValue('A' . $rowNum, $mode);
                $sheet->setCellValue('B' . $rowNum, $amount);
                $rowNum++;
            }
        }
        $rowNum += 2; // Blank rows

        // Detail Section
        $sheet->setCellValue('A' . $rowNum, 'DETAIL');
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
        $rowNum++;

        $headers = ['Date', 'Time', 'Receipt No.', 'Receipt Type', 'Patient Name', 'Amount', 'Payment Mode', 'Received By'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $rowNum, $h);
            $col++;
        }
        $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $rowNum++;

        // Group by date for subtotals
        foreach ($result['grouped_by_date'] as $dateKey => $dayData) {
            // Date header row
            $sheet->setCellValue('A' . $rowNum, $dayData['date']);
            $sheet->mergeCells('A' . $rowNum . ':G' . $rowNum);
            $sheet->setCellValue('H' . $rowNum, 'Total: ' . $dayData['net']);
            $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getFont()->setBold(true);
            $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
            $rowNum++;

            foreach ($dayData['rows'] as $r) {
                $sheet->setCellValue('A' . $rowNum, $r['date']);
                $sheet->setCellValue('B' . $rowNum, $r['time']);
                $sheet->setCellValue('C' . $rowNum, $r['receipt_no']);
                $sheet->setCellValue('D' . $rowNum, $r['receipt_type']);
                $sheet->setCellValue('E' . $rowNum, $r['patient_name']);
                $sheet->setCellValue('F' . $rowNum, $r['is_refund'] ? -$r['amount'] : $r['amount']);
                $sheet->setCellValue('G' . $rowNum, $r['payment_mode']);
                $sheet->setCellValue('H' . $rowNum, $r['received_by']);
                $rowNum++;
            }
        }

        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Daily_Collection_Report_' . $dateFrom . '_to_' . $dateTo . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempFile = storage_path('app/public/' . $filename);
        @mkdir(dirname($tempFile), 0755, true);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to'   => 'required|date|after_or_equal:date_from',
        ]);
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');
        $result   = $this->getReportData($dateFrom, $dateTo);
        $hospital = \App\Models\Hospital::first();

        $pdf = Pdf::loadView('admin.reports.finance.daily-collection-report-pdf', compact('result', 'hospital'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Daily_Collection_Report_' . $dateFrom . '_to_' . $dateTo . '.pdf';
        return $pdf->download($filename);
    }
}
