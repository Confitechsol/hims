<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PatientBedHistory;
use App\Models\IpdDetail;
use App\Models\OpdDetail;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * Get patient name for a transaction - check all loaded relationships first, then SQL fallback
     */
    protected function getPatientNameForTransaction(Transaction $transaction): string
    {
        // Method 1: Use direct patient relationship (already loaded)
        if ($transaction->patient && $transaction->patient->patient_name) {
            $name = trim($transaction->patient->patient_name);
            if (!empty($name)) {
                return $name;
            }
        }
        
        // Method 2: Use IPD -> patient relationship (already loaded)
        if ($transaction->ipd && $transaction->ipd->patient && $transaction->ipd->patient->patient_name) {
            $name = trim($transaction->ipd->patient->patient_name);
            if (!empty($name)) {
                return $name;
            }
        }
        
        // Method 3: Use OPD -> patient relationship (already loaded)
        if ($transaction->opd && $transaction->opd->patient && $transaction->opd->patient->patient_name) {
            $name = trim($transaction->opd->patient->patient_name);
            if (!empty($name)) {
                return $name;
            }
        }
        
        // Method 4: Use Pathology Billing -> patient relationship (need to load if not loaded)
        if ($transaction->pathology_billing_id) {
            if (!$transaction->relationLoaded('pathologyBilling')) {
                $transaction->load('pathologyBilling.patient');
            }
            if ($transaction->pathologyBilling && $transaction->pathologyBilling->patient && $transaction->pathologyBilling->patient->patient_name) {
                $name = trim($transaction->pathologyBilling->patient->patient_name);
                if (!empty($name)) {
                    return $name;
                }
            }
        }
        
        // Method 5: Use Radiology Billing -> patient relationship (need to load if not loaded)
        if ($transaction->radiology_billing_id) {
            if (!$transaction->relationLoaded('radiologyBilling')) {
                $transaction->load('radiologyBilling.patient');
            }
            if ($transaction->radiologyBilling && $transaction->radiologyBilling->patient && $transaction->radiologyBilling->patient->patient_name) {
                $name = trim($transaction->radiologyBilling->patient->patient_name);
                if (!empty($name)) {
                    return $name;
                }
            }
        }
        
        // Fallback: Direct SQL query
        $txId = $transaction->id;
        $result = DB::selectOne("
            SELECT 
                CASE 
                    WHEN p1.patient_name IS NOT NULL AND TRIM(p1.patient_name) != '' THEN TRIM(p1.patient_name)
                    WHEN p2.patient_name IS NOT NULL AND TRIM(p2.patient_name) != '' THEN TRIM(p2.patient_name)
                    WHEN p3.patient_name IS NOT NULL AND TRIM(p3.patient_name) != '' THEN TRIM(p3.patient_name)
                    WHEN p4.patient_name IS NOT NULL AND TRIM(p4.patient_name) != '' THEN TRIM(p4.patient_name)
                    WHEN p5.patient_name IS NOT NULL AND TRIM(p5.patient_name) != '' THEN TRIM(p5.patient_name)
                    ELSE NULL
                END as patient_name
            FROM transactions t
            LEFT JOIN patients p1 ON t.patient_id = p1.id
            LEFT JOIN ipd_details ipd ON t.ipd_id = ipd.id
            LEFT JOIN patients p2 ON ipd.patient_id = p2.id
            LEFT JOIN opd_details opd ON t.opd_id = opd.id
            LEFT JOIN patients p3 ON opd.patient_id = p3.id
            LEFT JOIN pathology_billing pb ON t.pathology_billing_id = pb.id
            LEFT JOIN patients p4 ON pb.patient_id = p4.id
            LEFT JOIN radiology_billing rb ON t.radiology_billing_id = rb.id
            LEFT JOIN patients p5 ON rb.patient_id = p5.id
            WHERE t.id = ?
        ", [$txId]);
        
        if ($result && isset($result->patient_name) && !empty(trim($result->patient_name))) {
            return trim($result->patient_name);
        }
        
        return '-';
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
    protected function getRegisterData(string $dateFrom, string $dateTo, ?string $paymentMode = null): array
    {
        // Get all transactions with all necessary relationships - ensure patient is loaded through all paths
        $query = Transaction::with([
                'patient', 
                'ipd', 
                'ipd.patient',
                'opd', 
                'opd.patient', 
                'receiver',
                'pathologyBilling.patient',
                'radiologyBilling.patient'
            ])
            ->whereNotNull('receipt_no')
            ->whereBetween('payment_date', [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59',
            ])
            ->orderBy('payment_date')
            ->orderBy('id');

        if ($paymentMode !== null && $paymentMode !== '') {
            $query->where('payment_mode', $paymentMode);
        }

        $rows = $query->get();
        
        // Ensure patient_id is populated on transactions from IPD/OPD if missing
        // and properly set the patient relationship
        foreach ($rows as $row) {
            if (!$row->patient_id && $row->ipd_id && $row->ipd && $row->ipd->patient_id) {
                $row->patient_id = $row->ipd->patient_id;
                // Set patient relationship directly from IPD's patient
                if ($row->ipd->patient) {
                    $row->setRelation('patient', $row->ipd->patient);
                } else {
                    $row->load('patient');
                }
            } elseif (!$row->patient_id && $row->opd_id && $row->opd && $row->opd->patient_id) {
                $row->patient_id = $row->opd->patient_id;
                // Set patient relationship directly from OPD's patient
                if ($row->opd->patient) {
                    $row->setRelation('patient', $row->opd->patient);
                } else {
                    $row->load('patient');
                }
            }
        }
        
        // Build patient name map - directly query patients table through all relationships
        $transactionIds = $rows->pluck('id')->toArray();
        $patientNameMap = [];
        
        if (!empty($transactionIds)) {
            $placeholders = implode(',', array_fill(0, count($transactionIds), '?'));
            
            // Direct query to get patient_name from patients table - check all paths
            $results = DB::select("
                SELECT 
                    t.id as transaction_id,
                    COALESCE(
                        p1.patient_name,
                        p2.patient_name,
                        p3.patient_name,
                        p4.patient_name,
                        p5.patient_name
                    ) as patient_name
                FROM transactions t
                LEFT JOIN patients p1 ON t.patient_id = p1.id AND t.patient_id IS NOT NULL AND t.patient_id > 0
                LEFT JOIN ipd_details ipd ON t.ipd_id = ipd.id AND t.ipd_id IS NOT NULL AND t.ipd_id > 0
                LEFT JOIN patients p2 ON ipd.patient_id = p2.id AND ipd.patient_id IS NOT NULL AND ipd.patient_id > 0
                LEFT JOIN opd_details opd ON t.opd_id = opd.id AND t.opd_id IS NOT NULL AND t.opd_id > 0
                LEFT JOIN patients p3 ON opd.patient_id = p3.id AND opd.patient_id IS NOT NULL AND opd.patient_id > 0
                LEFT JOIN pathology_billing pb ON t.pathology_billing_id = pb.id AND t.pathology_billing_id IS NOT NULL AND t.pathology_billing_id > 0
                LEFT JOIN patients p4 ON pb.patient_id = p4.id AND pb.patient_id IS NOT NULL AND pb.patient_id > 0
                LEFT JOIN radiology_billing rb ON t.radiology_billing_id = rb.id AND t.radiology_billing_id IS NOT NULL AND t.radiology_billing_id > 0
                LEFT JOIN patients p5 ON rb.patient_id = p5.id AND rb.patient_id IS NOT NULL AND rb.patient_id > 0
                WHERE t.id IN ($placeholders)
            ", $transactionIds);
            
            foreach ($results as $result) {
                if ($result->patient_name && trim($result->patient_name) !== '') {
                    $patientNameMap[$result->transaction_id] = trim($result->patient_name);
                }
            }
        }
        
        // Get IPD IDs for bed mapping
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

            // Get patient name from map (preferred) or fallback to helper method
            $patientName = $patientNameMap[$t->id] ?? $this->getPatientNameForTransaction($t);
            
            // If still empty, try direct lookup
            if (empty($patientName) || $patientName === '-') {
                // Try to get patient_id directly from transaction
                $pid = $t->patient_id ?? null;
                if (!$pid && $t->ipd_id) {
                    $pid = DB::selectOne("SELECT patient_id FROM ipd_details WHERE id = ?", [$t->ipd_id])->patient_id ?? null;
                }
                if (!$pid && $t->opd_id) {
                    $pid = DB::selectOne("SELECT patient_id FROM opd_details WHERE id = ?", [$t->opd_id])->patient_id ?? null;
                }
                if ($pid) {
                    $p = DB::selectOne("SELECT patient_name FROM patients WHERE id = ? AND patient_name IS NOT NULL AND TRIM(patient_name) != ''", [$pid]);
                    if ($p && $p->patient_name) {
                        $patientName = trim($p->patient_name);
                    }
                }
            }

            $data[] = [
                'admission_date'   => $admissionDate,
                'admission_number' => $admissionNumber,
                'patient_name'     => $patientName,
                'receipt_no'       => $t->receipt_no ?? '-',
                'receipt_date'     => $paymentDate ? $paymentDate->format('d/m/Y H:i') : '-',
                'receipt_amount'   => (float) ($t->amount ?? 0),
                'receipt_type'     => $t->receipt_type ?? '-',
                'payment_mode'     => $t->payment_mode ?? '-',
                'bed_number'       => $bedNumber,
                'username'         => $t->receiver->username ?? '-',
                'date_key'         => $dateKey,
                'category'        => $category,
            ];
        }

        // Group by date then by receipt type then by category (Received, Refund)
        $grouped = [];
        foreach ($data as $row) {
            $d = $row['date_key'];
            $rt = $row['receipt_type'];
            $c = $row['category'];
            
            if (!isset($grouped[$d])) {
                $grouped[$d] = [];
            }
            if (!isset($grouped[$d][$rt])) {
                $grouped[$d][$rt] = ['Received' => [], 'Refund' => []];
            }
            $grouped[$d][$rt][$c][] = $row;
        }
        ksort($grouped);

        return [
            'rows'    => $data,
            'grouped' => $grouped,
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
            'payment_mode' => $paymentMode,
        ];
    }

    /**
     * Show report form and results
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        $paymentMode = $request->input('payment_mode');

        $result = null;
        if ($request->has('date_from') && $request->has('date_to')) {
            $request->validate([
                'date_from' => 'required|date',
                'date_to'   => 'required|date|after_or_equal:date_from',
                'payment_mode' => 'nullable|string|max:100',
            ]);
            $result = $this->getRegisterData($dateFrom, $dateTo, $paymentMode);
        }

        $paymentModes = Transaction::query()
            ->whereNotNull('payment_mode')
            ->where('payment_mode', '<>', '')
            ->distinct()
            ->orderBy('payment_mode')
            ->pluck('payment_mode');

        return view('admin.reports.finance.money-receipt-register', compact('result', 'dateFrom', 'dateTo', 'paymentMode', 'paymentModes'));
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to'   => 'required|date|after_or_equal:date_from',
            'payment_mode' => 'nullable|string|max:100',
        ]);
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');
        $paymentMode = $request->input('payment_mode');
        $result   = $this->getRegisterData($dateFrom, $dateTo, $paymentMode);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Money Receipt Register');

        $headers = [
            'Admission Date', 'Admission Number', 'Patient Name', 'Receipt Number',
            'Receipt Date', 'Receipt Amount', 'Receipt Type', 'Payment/Receipt Mode', 'Bed Number',
            'Username (Entered By)',
        ];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowNum = 2;
        foreach ($result['grouped'] as $date => $receiptTypes) {
            foreach ($receiptTypes as $receiptType => $categories) {
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
                        $sheet->setCellValue('G' . $rowNum, $r['receipt_type']);
                        $sheet->setCellValue('H' . $rowNum, $r['payment_mode']);
                        $sheet->setCellValue('I' . $rowNum, $r['bed_number']);
                        $sheet->setCellValue('J' . $rowNum, $r['username']);
                        $rowNum++;
                    }
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
            'payment_mode' => 'nullable|string|max:100',
        ]);
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');
        $paymentMode = $request->input('payment_mode');
        $result   = $this->getRegisterData($dateFrom, $dateTo, $paymentMode);
        $hospital = \App\Models\Hospital::first();

        $pdf = Pdf::loadView('admin.reports.finance.money-receipt-register-pdf', compact('result', 'hospital'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'Money_Receipt_Register_' . $dateFrom . '_to_' . $dateTo . '.pdf';
        return $pdf->download($filename);
    }
}
