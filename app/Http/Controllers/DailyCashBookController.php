<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Services\DailyCashBookService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

class DailyCashBookController extends Controller
{
    public function __construct(
        protected DailyCashBookService $cashBookService
    ) {}

    protected function companyName(): ?string
    {
        $hospital = Hospital::query()->first();

        return $hospital?->name;
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildResult(string $dateFrom, string $dateTo): array
    {
        return $this->cashBookService->buildReport($dateFrom, $dateTo, $this->companyName());
    }

    protected function validateReportRequest(Request $request): array
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        return [
            'date_from' => Carbon::parse($validated['date_from'])->format('Y-m-d'),
            'date_to' => Carbon::parse($validated['date_to'])->format('Y-m-d'),
        ];
    }

    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        $result = null;
        $error = null;

        if ($request->has('date_from') && $request->has('date_to')) {
            try {
                $dates = $this->validateReportRequest($request);
                $result = $this->buildResult($dates['date_from'], $dates['date_to']);
                $dateFrom = $dates['date_from'];
                $dateTo = $dates['date_to'];
            } catch (InvalidArgumentException $e) {
                $error = $e->getMessage();
            } catch (Throwable $e) {
                Log::error('Daily Cash Book report failed', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $error = 'Unable to generate report. Please try again or contact support.';
            }
        }

        return view('admin.reports.finance.daily-cash-book', compact('result', 'dateFrom', 'dateTo', 'error'));
    }

    public function exportExcel(Request $request)
    {
        try {
            $dates = $this->validateReportRequest($request);
            $result = $this->buildResult($dates['date_from'], $dates['date_to']);
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            Log::error('Daily Cash Book Excel export failed', ['message' => $e->getMessage()]);

            return back()->with('error', 'Excel export failed.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daily Cash Book');

        $rowNum = 1;
        $company = $result['company_name'] ?? $this->companyName() ?? '';
        $sheet->setCellValue('A' . $rowNum, $company);
        $sheet->mergeCells('A' . $rowNum . ':H' . $rowNum);
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $rowNum++;

        $sheet->setCellValue('A' . $rowNum, 'DAILY CASH BOOK REGISTER');
        $sheet->mergeCells('A' . $rowNum . ':H' . $rowNum);
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $rowNum++;

        $period = Carbon::parse($result['date_from'])->format('d/m/Y') . ' to ' .
            Carbon::parse($result['date_to'])->format('d/m/Y');
        $sheet->setCellValue('A' . $rowNum, 'Period: ' . $period);
        $sheet->mergeCells('A' . $rowNum . ':H' . $rowNum);
        $sheet->getStyle('A' . $rowNum)->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $rowNum += 2;

        $headers = [
            'Total Clear Balance (₹)',
            'Date',
            'Total Cash Receipt (₹)',
            'Total UPI Receipt (₹)',
            'Total Transfer to Bank (₹)',
            'Total Received (₹)',
            'Total Expense (₹)',
            'Balance Amount (₹)',
        ];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $rowNum, $header);
            $col++;
        }
        $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $rowNum++;

        foreach ($result['rows'] as $r) {
            $sheet->setCellValue('A' . $rowNum, $r['total_clear_balance']);
            $sheet->setCellValue('B' . $rowNum, $r['date']);
            $sheet->setCellValue('C' . $rowNum, $r['total_cash']);
            $sheet->setCellValue('D' . $rowNum, $r['total_upi']);
            $sheet->setCellValue('E' . $rowNum, $r['total_transfer']);
            $sheet->setCellValue('F' . $rowNum, $r['total_received']);
            $sheet->setCellValue('G' . $rowNum, $r['total_expense']);
            $sheet->setCellValue('H' . $rowNum, $r['balance_amount']);
            $rowNum++;
        }

        if (! empty($result['rows'])) {
            $t = $result['totals'];
            $sheet->setCellValue('A' . $rowNum, 'Total');
            $sheet->setCellValue('C' . $rowNum, $t['total_cash']);
            $sheet->setCellValue('D' . $rowNum, $t['total_upi']);
            $sheet->setCellValue('E' . $rowNum, $t['total_transfer']);
            $sheet->setCellValue('F' . $rowNum, $t['total_received']);
            $sheet->setCellValue('G' . $rowNum, $t['total_expense']);
            $sheet->setCellValue('H' . $rowNum, $t['balance_amount']);
            $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->getFont()->setBold(true);
        }

        foreach (range('A', 'H') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $filename = 'Daily_Cash_Book_' . $result['date_from'] . '_to_' . $result['date_to'] . '.xlsx';
        $tempFile = storage_path('app/public/' . $filename);
        @mkdir(dirname($tempFile), 0755, true);
        (new Xlsx($spreadsheet))->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        try {
            $dates = $this->validateReportRequest($request);
            $result = $this->buildResult($dates['date_from'], $dates['date_to']);
            $hospital = Hospital::query()->first();
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        } catch (Throwable $e) {
            Log::error('Daily Cash Book PDF export failed', ['message' => $e->getMessage()]);

            return back()->with('error', 'PDF export failed.');
        }

        $pdf = Pdf::loadView('admin.reports.finance.daily-cash-book-pdf', compact('result', 'hospital'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Daily_Cash_Book_' . $result['date_from'] . '_to_' . $result['date_to'] . '.pdf';

        return $pdf->download($filename);
    }
}
