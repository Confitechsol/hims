<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\IpdDetail;
use App\Services\GstBreakReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GstBreakReportController extends Controller
{
    public function __construct(
        private readonly GstBreakReportService $reportService
    ) {}

    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        $ipdId = $this->ipdIdFromRequest($request);
        $patientQuery = (string) $request->input('patient_query', '');

        $result = null;
        $reportError = null;

        if ($request->has('date_from') && $request->has('date_to')) {
            $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'ipd_id' => 'nullable|integer|exists:ipd_details,id',
            ]);

            try {
                $result = $this->reportService->build($dateFrom, $dateTo, $ipdId);
            } catch (\Throwable $e) {
                Log::error('GST Break Report index failed', [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'ipd_id' => $ipdId,
                    'error' => $e->getMessage(),
                ]);
                $reportError = 'Unable to generate the report. Please try again or contact support.';
            }
        }

        return view('admin.reports.finance.gst-break-report', compact(
            'result',
            'dateFrom',
            'dateTo',
            'ipdId',
            'patientQuery',
            'reportError'
        ));
    }

    public function searchIpd(Request $request)
    {
        $term = trim((string) $request->input('q', ''));
        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $rows = IpdDetail::query()
            ->with(['patient' => static fn ($q) => $q->withTrashed()])
            ->where('discharged', 'yes')
            ->where(function ($query) use ($term) {
                $query->where('ipd_no', 'like', '%' . $term . '%')
                    ->orWhereHas('patient', function ($patient) use ($term) {
                        $patient->withTrashed()->where('patient_name', 'like', '%' . $term . '%');
                    });
            })
            ->orderByDesc('id')
            ->limit(20)
            ->get(['id', 'ipd_no', 'patient_id', 'date']);

        return response()->json($rows->map(function (IpdDetail $ipd) {
            $name = (string) ($ipd->patient->patient_name ?? '');

            return [
                'id' => $ipd->id,
                'label' => trim($ipd->ipd_no . ' — ' . $name),
                'ipd_no' => $ipd->ipd_no,
                'patient_name' => $name,
            ];
        })->values());
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'ipd_id' => 'nullable|integer|exists:ipd_details,id',
        ]);

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $ipdId = $this->ipdIdFromRequest($request);

        try {
            $result = $this->reportService->build($dateFrom, $dateTo, $ipdId);
        } catch (\Throwable $e) {
            Log::error('GST Break Report Excel failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'Excel export failed. Please try again.');
        }

        $hospital = Hospital::first();
        $sheet = (new Spreadsheet())->getActiveSheet();
        $book = $sheet->getParent();
        $sheet->setTitle('GST Break');

        $sheet->setCellValue('A1', strtoupper($hospital->name ?? 'HOSPITAL'));
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'GST BREAK REPORT');
        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $meta = sprintf(
            'Date From: %s  To: %s',
            Carbon::parse($result['date_from'])->format('d/M/Y'),
            Carbon::parse($result['date_to'])->format('d/M/Y')
        );
        if (! empty($result['patient_label'])) {
            $meta .= '  |  Patient: ' . $result['patient_label'];
        }
        $sheet->setCellValue('A3', $meta);
        $sheet->mergeCells('A3:K3');

        $headers = [
            'Sr. No.', 'Admission No.', 'Admission Date', 'Patient Name', 'Doctor Name',
            'Bill No.', 'Bill Date', 'Discharge Date', 'Print Head', 'Particular Details', 'Amount',
        ];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '5', $header);
            $col++;
        }
        $sheet->getStyle('A5:K5')->getFont()->setBold(true);
        $sheet->getStyle('A5:K5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9D9D9');

        $rowNum = 6;
        foreach ($result['rows'] as $row) {
            $sheet->fromArray([
                $row['sl_no'],
                $row['admission_no'],
                $row['admission_date'],
                $row['patient_name'],
                $row['doctor_name'],
                $row['bill_no'],
                $row['bill_date'],
                $row['discharge_date'],
                $row['print_head'],
                $row['particulars'],
                $row['amount'],
            ], null, 'A' . $rowNum);
            $rowNum++;
        }

        $sheet->setCellValue('J' . $rowNum, 'Total');
        $sheet->setCellValue('K' . $rowNum, $result['total_amount']);
        $sheet->getStyle('A' . $rowNum . ':K' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('K6:K' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');

        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'GST_Break_Report_' . $dateFrom . '_to_' . $dateTo . '.xlsx';
        $tempFile = storage_path('app/public/' . $filename);
        @mkdir(dirname($tempFile), 0755, true);
        (new Xlsx($book))->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'ipd_id' => 'nullable|integer|exists:ipd_details,id',
        ]);

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        try {
            $result = $this->reportService->build($dateFrom, $dateTo, $this->ipdIdFromRequest($request));
        } catch (\Throwable $e) {
            Log::error('GST Break Report PDF failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'PDF export failed. Please try again.');
        }

        $hospital = Hospital::first();
        $pdf = Pdf::loadView('admin.reports.finance.gst-break-report-pdf', compact('result', 'hospital'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('GST_Break_Report_' . $dateFrom . '_to_' . $dateTo . '.pdf');
    }

    private function ipdIdFromRequest(Request $request): ?int
    {
        $id = (int) $request->input('ipd_id');

        return $id > 0 ? $id : null;
    }
}
