<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PatientBedHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MoneyReceiptRegisterController extends Controller
{
    /**
     * Receipt type to report category: Current & Patient Due = Received, Refund = Refund
     */
    protected function getReportCategory(?string $receiptType): string
    {
        if (strcasecmp($receiptType ?? '', 'Refund') === 0) {
            return 'Refund';
        }
        return 'Received';
    }

    /**
     * Get bed name for an IPD (latest bed from patient_bed_history)
     */
    protected function getBedNameForIpd(?int $ipdId): string
    {
        if (!$ipdId) {
            return '-';
        }
        $history = PatientBedHistory::with(['bedGroup', 'bed'])
            ->where('ipd_id', $ipdId)
            ->orderByDesc('from_date')
            ->orderByDesc('id')
            ->first();
        if (!$history) {
            return '-';
        }
        $parts = array_filter([
            $history->bedGroup->name ?? null,
            $history->bed->name ?? null,
        ]);
        return empty($parts) ? '-' : implode(' ', $parts);
    }

    /**
     * Build register data: date range, grouped by date and Receipt Type (Received / Refund)
     */
    protected function getRegisterData(string $dateFrom, string $dateTo): array
    {
        $query = Transaction::with(['patient', 'ipd', 'opd', 'receiver'])
            ->whereNotNull('receipt_no')
            ->whereBetween('payment_date', [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59',
            ])
            ->orderBy('payment_date')
            ->orderBy('id');

        $rows = $query->get();
        $ipdIds = $rows->pluck('ipd_id')->filter()->unique()->values()->all();
        $bedMap = [];
        foreach ($ipdIds as $id) {
            $bedMap[$id] = $this->getBedNameForIpd($id);
        }

        $data = [];
        foreach ($rows as $t) {
            $category = $this->getReportCategory($t->receipt_type);
            $paymentDate = $t->payment_date ? Carbon::parse($t->payment_date) : null;
            $dateKey = $paymentDate ? $paymentDate->format('Y-m-d') : '';

            // Determine admission/appointment date and number based on IPD or OPD
            $admissionDate = '-';
            $admissionNumber = '-';
            if ($t->ipd && $t->ipd->date) {
                $admissionDate = Carbon::parse($t->ipd->date)->format('d/m/Y');
                $admissionNumber = $t->ipd->ipd_no ?? '-';
            } elseif ($t->opd && $t->opd->appointment_date) {
                $admissionDate = Carbon::parse($t->opd->appointment_date)->format('d/m/Y');
                $admissionNumber = $t->opd->opd_no ?? '-';
            }

            // Bed number only applies to IPD
            $bedNumber = '-';
            if ($t->ipd_id) {
                $bedNumber = $bedMap[$t->ipd_id ?? 0] ?? '-';
            }

            $data[] = [
                'admission_date'   => $admissionDate,
                'admission_number' => $admissionNumber,
                'patient_name'     => $t->patient->patient_name ?? '-',
                'receipt_no'       => $t->receipt_no ?? '-',
                'receipt_date'     => $paymentDate ? $paymentDate->format('d/m/Y H:i') : '-',
                'receipt_amount'   => (float) ($t->amount ?? 0),
                'payment_mode'     => $t->payment_mode ?? '-',
                'bed_number'       => $bedNumber,
                'username'         => $t->receiver->username ?? $t->receiver->name ?? '-',
                'date_key'         => $dateKey,
                'category'        => $category,
            ];
        }

        // Group by date then by category (Received, Refund)
        $grouped = [];
        foreach ($data as $row) {
            $d = $row['date_key'];
            $c = $row['category'];
            if (!isset($grouped[$d])) {
                $grouped[$d] = ['Received' => [], 'Refund' => []];
            }
            $grouped[$d][$c][] = $row;
        }
        ksort($grouped);

        return [
            'rows'    => $data,
            'grouped' => $grouped,
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ];
    }

    /**
     * Show report form and results
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        $result = null;
        if ($request->has('date_from') && $request->has('date_to')) {
            $request->validate([
                'date_from' => 'required|date',
                'date_to'   => 'required|date|after_or_equal:date_from',
            ]);
            $result = $this->getRegisterData($dateFrom, $dateTo);
        }

        return view('admin.reports.finance.money-receipt-register', compact('result', 'dateFrom', 'dateTo'));
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
        $result   = $this->getRegisterData($dateFrom, $dateTo);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Money Receipt Register');

        $headers = [
            'Admission Date', 'Admission Number', 'Patient Name', 'Receipt Number',
            'Receipt Date', 'Receipt Amount', 'Payment/Receipt Mode', 'Bed Number',
            'Username (Entered By)',
        ];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowNum = 2;
        foreach ($result['grouped'] as $date => $categories) {
            foreach (['Received', 'Refund'] as $cat) {
                $items = $categories[$cat] ?? [];
                if (empty($items)) {
                    continue;
                }
                foreach ($items as $r) {
                    $sheet->setCellValue('A' . $rowNum, $r['admission_date']);
                    $sheet->setCellValue('B' . $rowNum, $r['admission_number']);
                    $sheet->setCellValue('C' . $rowNum, $r['patient_name']);
                    $sheet->setCellValue('D' . $rowNum, $r['receipt_no']);
                    $sheet->setCellValue('E' . $rowNum, $r['receipt_date']);
                    $sheet->setCellValue('F' . $rowNum, $r['receipt_amount']);
                    $sheet->setCellValue('G' . $rowNum, $r['payment_mode']);
                    $sheet->setCellValue('H' . $rowNum, $r['bed_number']);
                    $sheet->setCellValue('I' . $rowNum, $r['username']);
                    $rowNum++;
                }
            }
        }

        $filename = 'Money_Receipt_Register_' . $dateFrom . '_to_' . $dateTo . '.xlsx';
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
        $result   = $this->getRegisterData($dateFrom, $dateTo);
        $hospital = \App\Models\Hospital::first();

        $pdf = Pdf::loadView('admin.reports.finance.money-receipt-register-pdf', compact('result', 'hospital'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Money_Receipt_Register_' . $dateFrom . '_to_' . $dateTo . '.pdf';
        return $pdf->download($filename);
    }
}
