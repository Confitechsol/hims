<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Services\FinalBillRegisterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IpdFinalBillRegisterController extends Controller
{
    public function __construct(
        private readonly FinalBillRegisterService $registerService
    ) {}

    /**
     * Build day-wise register data for discharged cash + insurance final bills.
     */
    protected function getRegisterData(string $dateFrom, string $dateTo): array
    {
        return $this->registerService->build($dateFrom, $dateTo);
    }

    /**
     * Show report form and results.
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        $result = null;
        $reportError = null;

        if ($request->has('date_from') && $request->has('date_to')) {
            $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
            ]);

            try {
                $result = $this->getRegisterData($dateFrom, $dateTo);
            } catch (\Throwable $e) {
                Log::error('Final Bill Register index failed', [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'error' => $e->getMessage(),
                ]);
                $reportError = 'Unable to generate the report. Please try again or contact support.';
            }
        }

        return view('admin.reports.finance.ipd-final-bill-register', compact('result', 'dateFrom', 'dateTo', 'reportError'));
    }

    /**
     * Export to Excel.
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        try {
            $result = $this->getRegisterData($dateFrom, $dateTo);
        } catch (\Throwable $e) {
            Log::error('Final Bill Register Excel export failed', [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Excel export failed. Please try again.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Final Bill Register');

        $hospital = Hospital::first();
        $sheet->setCellValue('A1', strtoupper($hospital->name ?? 'HOSPITAL'));
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'FINAL BILL REGISTER');
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $periodText = sprintf(
            'Date From: %s  To: %s',
            Carbon::parse($result['date_from'])->format('d/M/Y'),
            Carbon::parse($result['date_to'])->format('d/M/Y')
        );
        $sheet->setCellValue('A3', $periodText);
        $sheet->mergeCells('A3:I3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headers = [
            'Bill Date', 'Bed Ch.', 'Diag Ch', 'Other Ch', 'Service Ch',
            'Home Amt', 'Disc Amt', 'Dr Visit', 'Package',
        ];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '5', $header);
            $col++;
        }
        $sheet->getStyle('A5:I5')->getFont()->setBold(true);
        $sheet->getStyle('A5:I5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:I5')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9D9D9');

        $rowNum = 6;
        foreach ($result['rows'] as $row) {
            $sheet->setCellValue('A' . $rowNum, $row['bill_date']);
            $sheet->setCellValue('B' . $rowNum, $row['bed_charges']);
            $sheet->setCellValue('C' . $rowNum, $row['diagnosis_charges']);
            $sheet->setCellValue('D' . $rowNum, $row['other_charges']);
            $sheet->setCellValue('E' . $rowNum, $row['service_charges']);
            $sheet->setCellValue('F' . $rowNum, $row['home_amount']);
            $sheet->setCellValue('G' . $rowNum, $row['discount_amount']);
            $sheet->setCellValue('H' . $rowNum, $row['doctor_visit_amount']);
            $sheet->setCellValue('I' . $rowNum, $row['package_amount']);
            $rowNum++;
        }

        $grand = $result['grand_total'];
        $sheet->setCellValue('A' . $rowNum, 'Gross Total');
        $sheet->setCellValue('B' . $rowNum, $grand['bed_charges']);
        $sheet->setCellValue('C' . $rowNum, $grand['diagnosis_charges']);
        $sheet->setCellValue('D' . $rowNum, $grand['other_charges']);
        $sheet->setCellValue('E' . $rowNum, $grand['service_charges']);
        $sheet->setCellValue('F' . $rowNum, $grand['home_amount']);
        $sheet->setCellValue('G' . $rowNum, $grand['discount_amount']);
        $sheet->setCellValue('H' . $rowNum, $grand['doctor_visit_amount']);
        $sheet->setCellValue('I' . $rowNum, $grand['package_amount']);
        $sheet->getStyle('A' . $rowNum . ':I' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowNum . ':I' . $rowNum)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE8E8E8');

        foreach (range('B', 'I') as $column) {
            $sheet->getStyle($column . '6:' . $column . $rowNum)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle($column . '6:' . $column . $rowNum)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'Final_Bill_Register_' . $dateFrom . '_to_' . $dateTo . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $tempFile = storage_path('app/public/' . $filename);
        @mkdir(dirname($tempFile), 0755, true);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Export to PDF.
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        try {
            $result = $this->getRegisterData($dateFrom, $dateTo);
        } catch (\Throwable $e) {
            Log::error('Final Bill Register PDF export failed', [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'PDF export failed. Please try again.');
        }

        $hospital = Hospital::first();
        $pdf = Pdf::loadView('admin.reports.finance.ipd-final-bill-register-pdf', compact('result', 'hospital'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Final_Bill_Register_' . $dateFrom . '_to_' . $dateTo . '.pdf';

        return $pdf->download($filename);
    }
}
