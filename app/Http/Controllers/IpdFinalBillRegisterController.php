<?php

namespace App\Http\Controllers;

use App\Models\IpdDetail;
use App\Models\DischargeCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class IpdFinalBillRegisterController extends Controller
{
    /**
     * Build register data: discharged IPDs with discharge_date in date range; each IPD expanded into charge rows + GST + discount + due.
     */
    protected function getRegisterData(string $dateFrom, string $dateTo): array
    {
        $dischargeCards = DischargeCard::whereNotNull('ipd_details_id')
            ->whereBetween('discharge_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('discharge_date')
            ->get();

        $billingController = new IpdBillingController();
        $allRows = [];
        $billSlNo = 0;

        foreach ($dischargeCards as $dc) {
            $ipdId = $dc->ipd_details_id;
            $ipd = IpdDetail::with(['patient'])->find($ipdId);
            if (!$ipd || $ipd->discharged != 'yes') {
                continue;
            }

            $dischargeDate = $dc->discharge_date ? Carbon::parse($dc->discharge_date)->format('Y-m-d') : null;
            if (!$dischargeDate) {
                continue;
            }

            $billSlNo++;
            $admissionNo = $dc->admission_no ?? $ipd->ipd_no ?? '-';
            $admissionDate = $ipd->date ? Carbon::parse($ipd->date)->format('d/m/Y') : '-';
            $ipdNo = $ipd->ipd_no ?? '-';
            $patientName = $ipd->patient->patient_name ?? '-';
            $currentYear = (int) date('y');
            $nextYear = $currentYear + 1;
            $yearRange = $currentYear . '-' . $nextYear;
            $billNumber = 'F-' . str_pad($ipd->id, 6, '0', STR_PAD_LEFT) . '/' . $yearRange;
            $billDate = Carbon::parse($dc->discharge_date)->format('d/m/Y');

            $chargeRows = $billingController->getFinalBillRegisterRows($ipdId, $dischargeDate);

            foreach ($chargeRows as $r) {
                $allRows[] = [
                    'bill_sl_no' => $billSlNo,
                    'admission_number' => $admissionNo,
                    'admission_date' => $admissionDate,
                    'ipd_number' => $ipdNo,
                    'patient_name' => $patientName,
                    'bill_number' => $billNumber,
                    'bill_date' => $billDate,
                    'charge_category_head' => $r['charge_category_head'],
                    'charge_details' => $r['charge_details'],
                    'amount' => $r['amount'],
                ];
            }
        }

        // Grouped by same bill (for PDF/Excel merge): consecutive rows with same bill key
        $groupedRows = [];
        $i = 0;
        while ($i < count($allRows)) {
            $billKey = $allRows[$i]['bill_number'] . '|' . $allRows[$i]['ipd_number'] . '|' . $allRows[$i]['admission_number'];
            $group = [
                'admission_number' => $allRows[$i]['admission_number'],
                'admission_date' => $allRows[$i]['admission_date'],
                'ipd_number' => $allRows[$i]['ipd_number'],
                'patient_name' => $allRows[$i]['patient_name'],
                'bill_number' => $allRows[$i]['bill_number'],
                'bill_date' => $allRows[$i]['bill_date'],
                'lines' => [],
            ];
            $group['bill_sl_no'] = $allRows[$i]['bill_sl_no'];
            while ($i < count($allRows) && ($allRows[$i]['bill_number'] . '|' . $allRows[$i]['ipd_number'] . '|' . $allRows[$i]['admission_number']) === $billKey) {
                $group['lines'][] = [
                    'charge_category_head' => $allRows[$i]['charge_category_head'],
                    'charge_details' => $allRows[$i]['charge_details'],
                    'amount' => $allRows[$i]['amount'],
                ];
                $i++;
            }
            // Compute rowspan for Charge Category Head (consecutive same value within group)
            $lines = &$group['lines'];
            $j = 0;
            while ($j < count($lines)) {
                $val = $lines[$j]['charge_category_head'];
                $run = 1;
                while ($j + $run < count($lines) && $lines[$j + $run]['charge_category_head'] === $val) {
                    $run++;
                }
                $lines[$j]['charge_category_rowspan'] = $run;
                for ($k = 1; $k < $run; $k++) {
                    $lines[$j + $k]['charge_category_rowspan'] = 0;
                }
                $j += $run;
            }
            $groupedRows[] = $group;
        }

        return [
            'rows' => $allRows,
            'grouped_rows' => $groupedRows,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
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

        return view('admin.reports.finance.ipd-final-bill-register', compact('result', 'dateFrom', 'dateTo'));
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
        $sheet->setTitle('IPD Final Bill Register');

        $headers = [
            'Sl. No.', 'Admission Number', 'Admission Date', 'IPD Number', 'Patient Name',
            'Bill Number', 'Bill Date', 'Charge Category Head', 'Charge Details', 'Amount',
        ];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowNum = 2;
        foreach ($result['rows'] as $r) {
            $sheet->setCellValue('A' . $rowNum, $r['bill_sl_no']);
            $sheet->setCellValue('B' . $rowNum, $r['admission_number']);
            $sheet->setCellValue('C' . $rowNum, $r['admission_date']);
            $sheet->setCellValue('D' . $rowNum, $r['ipd_number']);
            $sheet->setCellValue('E' . $rowNum, $r['patient_name']);
            $sheet->setCellValue('F' . $rowNum, $r['bill_number']);
            $sheet->setCellValue('G' . $rowNum, $r['bill_date']);
            $sheet->setCellValue('H' . $rowNum, $r['charge_category_head']);
            $sheet->setCellValue('I' . $rowNum, $r['charge_details']);
            $sheet->setCellValue('J' . $rowNum, $r['amount']);
            $rowNum++;
        }

        // Merge cells for same bill (columns B to G) for consecutive rows
        $rows = $result['rows'];
        $n = count($rows);
        $mergeCols = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        $runStart = 0;
        for ($i = 1; $i <= $n; $i++) {
            $currentKey = $i <= $n ? ($rows[$i - 1]['bill_number'] . '|' . $rows[$i - 1]['ipd_number'] . '|' . $rows[$i - 1]['admission_number']) : '';
            $nextKey = $i < $n ? ($rows[$i]['bill_number'] . '|' . $rows[$i]['ipd_number'] . '|' . $rows[$i]['admission_number']) : '';
            if ($currentKey !== $nextKey) {
                $excelRunStart = $runStart + 2;
                $excelRunEnd = $i + 1;
                if ($excelRunEnd > $excelRunStart) {
                    foreach ($mergeCols as $col) {
                        $sheet->mergeCells($col . $excelRunStart . ':' . $col . $excelRunEnd);
                    }
                }
                $runStart = $i;
            }
        }

        // Merge column H (Charge Category Head) for consecutive rows with same value
        $runStart = 0;
        for ($i = 1; $i <= $n; $i++) {
            $currentVal = $i <= $n ? $rows[$i - 1]['charge_category_head'] : '';
            $nextVal = $i < $n ? $rows[$i]['charge_category_head'] : '';
            if ($currentVal !== $nextVal) {
                $excelRunStart = $runStart + 2;
                $excelRunEnd = $i + 1;
                if ($excelRunEnd > $excelRunStart) {
                    $sheet->mergeCells('H' . $excelRunStart . ':H' . $excelRunEnd);
                }
                $runStart = $i;
            }
        }

        $filename = 'IPD_Final_Bill_Register_' . $dateFrom . '_to_' . $dateTo . '.xlsx';
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

        $pdf = Pdf::loadView('admin.reports.finance.ipd-final-bill-register-pdf', compact('result', 'hospital'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'IPD_Final_Bill_Register_' . $dateFrom . '_to_' . $dateTo . '.pdf';
        return $pdf->download($filename);
    }
}
