<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExpenseRegisterController extends Controller
{
    /** Payment type options for filter (same as expense form) */
    public static function paymentTypeOptions(): array
    {
        return [
            '' => 'All',
            'Cash' => 'Cash',
            'Cheque' => 'Cheque',
            'Card' => 'Card',
            'UPI' => 'UPI',
            'Online' => 'Online',
            'Transfer to Bank Account' => 'Transfer to Bank Account',
            'Other' => 'Other',
        ];
    }

    /**
     * Build register data: expenses in date range, optional payment type, datewise.
     */
    protected function getRegisterData(string $dateFrom, string $dateTo, ?string $paymentType): array
    {
        $query = Expense::with(['expenseHead', 'generatedBy'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->where(function ($q) {
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->orderBy('date')
            ->orderBy('id');

        if ($paymentType !== null && $paymentType !== '') {
            $query->where('payment_mode', $paymentType);
        }

        $expenses = $query->get();

        $rows = [];
        foreach ($expenses as $e) {
            $dateObj = $e->date ? (is_string($e->date) ? Carbon::parse($e->date) : $e->date) : null;
            $rows[] = [
                'date' => $dateObj ? $dateObj->format('d/m/Y') : '-',
                'date_key' => $dateObj ? $dateObj->format('Y-m-d') : '',
                'expense_head' => $e->expenseHead->exp_category ?? '-',
                'expense_receipt_no' => $e->invoice_no ?? '-',
                'amount' => (float) ($e->amount ?? 0),
                'username' => $e->generatedBy->name ?? $e->generatedBy->username ?? '-',
                'payment_mode' => $e->payment_mode ?? '-',
            ];
        }

        // Group by date for display (datewise)
        $grouped = [];
        foreach ($rows as $row) {
            $d = $row['date_key'];
            if (!isset($grouped[$d])) {
                $grouped[$d] = [];
            }
            $grouped[$d][] = $row;
        }
        ksort($grouped);

        return [
            'rows' => $rows,
            'grouped' => $grouped,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'payment_type' => $paymentType,
        ];
    }

    /**
     * Show report form and results
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        $paymentType = $request->input('payment_type');

        $result = null;
        if ($request->has('date_from') && $request->has('date_to')) {
            $request->validate([
                'date_from' => 'required|date',
                'date_to'   => 'required|date|after_or_equal:date_from',
                'payment_type' => 'nullable|string',
            ]);
            $result = $this->getRegisterData($dateFrom, $dateTo, $paymentType);
        }

        $paymentTypeOptions = self::paymentTypeOptions();
        return view('admin.reports.finance.expense-register', compact('result', 'dateFrom', 'dateTo', 'paymentType', 'paymentTypeOptions'));
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to'   => 'required|date|after_or_equal:date_from',
            'payment_type' => 'nullable|string',
        ]);
        $dateFrom    = $request->input('date_from');
        $dateTo      = $request->input('date_to');
        $paymentType = $request->input('payment_type');
        $result      = $this->getRegisterData($dateFrom, $dateTo, $paymentType);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Expense Register');

        $headers = ['Date', 'Expense Head', 'Expense Receipt No.', 'Amount', 'Payment Type', 'Username (Entered By)'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowNum = 2;
        foreach ($result['grouped'] as $date => $items) {
            foreach ($items as $r) {
                $sheet->setCellValue('A' . $rowNum, $r['date']);
                $sheet->setCellValue('B' . $rowNum, $r['expense_head']);
                $sheet->setCellValue('C' . $rowNum, $r['expense_receipt_no']);
                $sheet->setCellValue('D' . $rowNum, $r['amount']);
                $sheet->setCellValue('E' . $rowNum, $r['payment_mode']);
                $sheet->setCellValue('F' . $rowNum, $r['username']);
                $rowNum++;
            }
        }

        $filename = 'Expense_Register_' . $dateFrom . '_to_' . $dateTo . '.xlsx';
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
            'payment_type' => 'nullable|string',
        ]);
        $dateFrom    = $request->input('date_from');
        $dateTo      = $request->input('date_to');
        $paymentType = $request->input('payment_type');
        $result      = $this->getRegisterData($dateFrom, $dateTo, $paymentType);
        $hospital    = \App\Models\Hospital::first();

        $pdf = Pdf::loadView('admin.reports.finance.expense-register-pdf', compact('result', 'hospital'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Expense_Register_' . $dateFrom . '_to_' . $dateTo . '.pdf';
        return $pdf->download($filename);
    }
}
