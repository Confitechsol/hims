<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Services\DoctorRevenueReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DoctorRevenueReportController extends Controller
{
    public function __construct(
        private readonly DoctorRevenueReportService $reportService
    ) {}

    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        $doctorId = $this->doctorIdFromRequest($request);
        $doctors = $this->doctorOptions();

        $result = null;
        $reportError = null;

        if ($request->has('date_from') && $request->has('date_to')) {
            $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'doctor_id' => 'nullable|integer|exists:doctor,id',
            ]);

            try {
                $result = $this->reportService->build($dateFrom, $dateTo, $doctorId);
            } catch (\Throwable $e) {
                Log::error('Doctor Revenue Report index failed', [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'doctor_id' => $doctorId,
                    'error' => $e->getMessage(),
                ]);
                $reportError = 'Unable to generate the report. Please try again or contact support.';
            }
        }

        return view('admin.reports.doctor.doctor_revenue_report', compact(
            'result',
            'dateFrom',
            'dateTo',
            'doctorId',
            'doctors',
            'reportError'
        ));
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'doctor_id' => 'nullable|integer|exists:doctor,id',
        ]);

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $doctorId = $this->doctorIdFromRequest($request);

        try {
            $result = $this->reportService->build($dateFrom, $dateTo, $doctorId);
        } catch (\Throwable $e) {
            Log::error('Doctor Revenue Report Excel export failed', [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'doctor_id' => $doctorId,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Excel export failed. Please try again.');
        }

        $hospital = Hospital::first();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Doctor Revenue');

        $sheet->setCellValue('A1', strtoupper($hospital->name ?? 'HOSPITAL'));
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'DOCTOR REVENUE REPORT (REFERRAL)');
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue(
            'A3',
            sprintf(
                'Date From: %s  To: %s',
                Carbon::parse($result['date_from'])->format('d/M/Y'),
                Carbon::parse($result['date_to'])->format('d/M/Y')
            ) . (! empty($result['doctor_filter_label'])
                ? '  |  Doctor: ' . $result['doctor_filter_label']
                : '  |  Doctor: All')
        );
        $sheet->mergeCells('A3:M3');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $headers = [
            'Sl No', 'Bill Date', 'Patient Name', 'Bill No', 'Adm. No.',
            'Bed Ch.', 'Diag Ch', 'Other Ch', 'Pack Amount', 'Dr Visit',
            'Bill Amt', 'Discount', 'N H Amt',
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '5', $header);
            $col++;
        }
        $sheet->getStyle('A5:M5')->getFont()->setBold(true);
        $sheet->getStyle('A5:M5')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9D9D9');

        $rowNum = 6;
        foreach ($result['groups'] as $group) {
            $sheet->setCellValue('A' . $rowNum, $group['doctor_label']);
            $sheet->mergeCells('A' . $rowNum . ':M' . $rowNum);
            $sheet->getStyle('A' . $rowNum . ':M' . $rowNum)->getFont()->setBold(true);
            $sheet->getStyle('A' . $rowNum . ':M' . $rowNum)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE8E8E8');
            $rowNum++;

            foreach ($group['rows'] as $row) {
                $sheet->fromArray([
                    $row['sl_no'],
                    $row['bill_date'],
                    $row['patient_name'],
                    $row['bill_no'],
                    $row['adm_no'],
                    $row['bed_charges'],
                    $row['diagnosis_charges'],
                    $row['other_charges'],
                    $row['package_amount'],
                    $row['doctor_visit_amount'],
                    $row['bill_amount'],
                    $row['discount_amount'],
                    $row['net_hospital_amount'],
                ], null, 'A' . $rowNum);
                $rowNum++;
            }

            $t = $group['totals'];
            $sheet->fromArray([
                '',
                '',
                'TOTAL :',
                '',
                '',
                $t['bed_charges'],
                $t['diagnosis_charges'],
                $t['other_charges'],
                $t['package_amount'],
                $t['doctor_visit_amount'],
                $t['bill_amount'],
                $t['discount_amount'],
                $t['net_hospital_amount'],
            ], null, 'A' . $rowNum);
            $sheet->getStyle('A' . $rowNum . ':M' . $rowNum)->getFont()->setBold(true);
            $rowNum += 2;
        }

        $g = $result['grand_total'];
        $sheet->fromArray([
            '',
            '',
            'GRAND TOTAL :',
            '',
            '',
            $g['bed_charges'],
            $g['diagnosis_charges'],
            $g['other_charges'],
            $g['package_amount'],
            $g['doctor_visit_amount'],
            $g['bill_amount'],
            $g['discount_amount'],
            $g['net_hospital_amount'],
        ], null, 'A' . $rowNum);
        $sheet->getStyle('A' . $rowNum . ':M' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('A' . $rowNum . ':M' . $rowNum)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9D9D9');

        foreach (range('F', 'M') as $column) {
            $sheet->getStyle($column . '6:' . $column . $rowNum)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
            $sheet->getStyle($column . '6:' . $column . $rowNum)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'Doctor_Revenue_Report_' . $dateFrom . '_to_' . $dateTo . '.xlsx';
        $tempFile = storage_path('app/public/' . $filename);
        @mkdir(dirname($tempFile), 0755, true);
        (new Xlsx($spreadsheet))->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'doctor_id' => 'nullable|integer|exists:doctor,id',
        ]);

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $doctorId = $this->doctorIdFromRequest($request);

        try {
            $result = $this->reportService->build($dateFrom, $dateTo, $doctorId);
        } catch (\Throwable $e) {
            Log::error('Doctor Revenue Report PDF export failed', [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'doctor_id' => $doctorId,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'PDF export failed. Please try again.');
        }

        $hospital = Hospital::first();
        $pdf = Pdf::loadView('admin.reports.doctor.doctor_revenue_report_pdf', compact('result', 'hospital'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Doctor_Revenue_Report_' . $dateFrom . '_to_' . $dateTo . '.pdf');
    }

    private function doctorIdFromRequest(Request $request): ?int
    {
        $id = (int) $request->input('doctor_id');

        return $id > 0 ? $id : null;
    }

    private function doctorOptions()
    {
        return Doctor::query()
            ->orderBy('name')
            ->get(['id', 'name', 'registration_no']);
    }
}
