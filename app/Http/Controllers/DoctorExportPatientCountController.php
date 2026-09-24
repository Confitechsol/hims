<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DoctorExportPatientCountController extends Controller
{
  
  public function getDoctors(Request $request)
{
    $year = $request->input('year', date('Y')); // default: current year

    $doctors = Doctor::select('id', 'name')->get();

    $ipdCounts = DB::table('ipd_details')
        ->select(
            'cons_doctor',
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(id) as total')
        )
        ->whereNotNull('cons_doctor')
        ->whereYear('created_at', $year) // ✅ filter by created_at year
        ->groupBy('cons_doctor', DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'))
        ->get();

    $doctorData = [];

    foreach ($ipdCounts as $row) {
        $doctorData[$row->cons_doctor][$row->month] = $row->total;
    }


    return view('admin.reports.doctor.doctor_reports', compact('doctors', 'doctorData', 'year'));
}
   
// {
//     $fromDate = $request->input('from_date');
//     $toDate   = $request->input('to_date');

//     // Optional defaults
//     if (!$fromDate) {
//         $fromDate = date('Y-01-01');
//     }

//     if (!$toDate) {
//         $toDate = date('Y-12-31');
//     }

//     $doctors = Doctor::select('id', 'name')->get();

//     $dueReports = DB::table('ipd_details')
//         ->select(
//             'cons_doctor',
//             DB::raw('MONTH(created_at) as month'),
//             DB::raw('YEAR(created_at) as year'),
//             DB::raw('COUNT(id) as total')
//         )
//         ->whereNotNull('cons_doctor')
//         ->whereBetween('created_at', [
//             $fromDate . ' 00:00:00',
//             $toDate . ' 23:59:59'
//         ])
//         ->where('due_patient_party_amount', 'due')
//         ->groupBy(
//             'cons_doctor',
//             DB::raw('MONTH(created_at)'),
//             DB::raw('YEAR(created_at)')
//         )
//         ->get();

//     $doctorDueData = [];

//     foreach ($dueReports as $row) {
//         $doctorDueData[$row->cons_doctor][$row->year][$row->month] = $row->total;
//     }

//     return response()->json([
//         'success' => true,
//         'from_date' => $fromDate,
//         'to_date' => $toDate,
//         'doctors' => $doctors,
//         'doctor_due_data' => $doctorDueData
//     ]);
// }

  public function getDoctorsDueReports(Request $request)
{
    $fromDate = $request->input('from_date');
    $toDate   = $request->input('to_date');
    $dueType  = $request->input('due_type');

    // Optional defaults
    if (!$fromDate) {
        $fromDate = date('Y-01-01');
    }

    if (!$toDate) {
        $toDate = date('Y-12-31');
    }

   $dueReports = $this->fetchDoctorsDueReports($fromDate, $toDate, $dueType);

   if (!$request->expectsJson()) {
       return view('admin.reports.doctor.doctor_due_reports', compact(
           'dueReports',
           'fromDate',
           'toDate',
           'dueType'
       ));
   }

    return response()->json([
        'success' => true,
        'from_date' => $fromDate,
        'to_date' => $toDate,
        'data' => $dueReports
    ]);
}

public function exportDoctorsDueReports(Request $request)
{
    $fromDate = $request->input('from_date', date('Y-01-01'));
    $toDate = $request->input('to_date', date('Y-12-31'));
    $dueType = $request->input('due_type');

    $reports = $this->fetchDoctorsDueReports($fromDate, $toDate, $dueType);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Doctor Due Reports');
    $headers = [
        'IPD NO',
        'Patient Name',
        'Doctor Name',
        'Gross Total',
        'Due Amount',
        'Receipt Type',
        'Transaction Amounts',
        'Total Transaction Amount',
        'Admission Date',
    ];

    foreach ($headers as $index => $header) {
        $sheet->setCellValue(chr(65 + $index) . '1', $header);
    }
    $sheet->getStyle('A1:I1')->getFont()->setBold(true);

    foreach ($reports as $index => $report) {
        $sheet->fromArray([
            $report->ipd_no,
            $report->patient_name ?? '',
            $report->doctor_name ?? '',
            $report->gross_total ?? 0,
            $report->due_patient_party_amount,
            $report->due_patient_party_receipt_type ?? '',
            implode(', ', $report->transaction_amount ?? []),
            $report->total_transaction ?? 0,
            $report->created_at,
        ], null, 'A' . ($index + 2));
    }

    foreach (range('A', 'I') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    $temporaryFile = tempnam(sys_get_temp_dir(), 'doctor_due');
    (new Xlsx($spreadsheet))->save($temporaryFile);

    return response()->download(
        $temporaryFile,
        'doctor_due_reports_' . $fromDate . '_to_' . $toDate . '.xlsx'
    )->deleteFileAfterSend(true);
}

    /**
     * Doctor due rows for screen + Excel (same columns / figures).
     *
     * @return \Illuminate\Support\Collection<int, object>
     */
    private function fetchDoctorsDueReports(string $fromDate, string $toDate, $dueType)
    {
        $billingController = app(IpdBillingController::class);

        return DB::table('ipd_details')
            ->leftJoin('patients', 'ipd_details.patient_id', '=', 'patients.id')
            ->leftJoin('doctor', 'ipd_details.due_patient_party_doctor_id', '=', 'doctor.id')
            ->leftJoin('transactions', 'ipd_details.patient_id', '=', 'transactions.patient_id')
            ->select(
                'ipd_details.id as ipd_id',
                'ipd_details.ipd_no as ipd_no',
                'patients.patient_name as patient_name',
                'ipd_details.due_patient_party_doctor_id',
                'doctor.name as doctor_name',
                'ipd_details.due_patient_party_amount',
                'ipd_details.due_patient_party_receipt_type',
                'ipd_details.created_at',
                DB::raw('COALESCE(SUM(transactions.amount), 0) as total_transaction'),
                DB::raw("GROUP_CONCAT(transactions.amount SEPARATOR ',') as transaction_amount")
            )
            ->whereDate('ipd_details.created_at', '>=', $fromDate)
            ->whereDate('ipd_details.created_at', '<=', $toDate)
            ->where('ipd_details.due_patient_party_amount', '>', 0)
            ->when(in_array($dueType, ['Patient Due', 'Corporate Due'], true), function ($query) use ($dueType) {
                $query->where('ipd_details.due_patient_party_receipt_type', $dueType);
            })
            ->groupBy(
                'ipd_details.id',
                'ipd_details.ipd_no',
                'patients.patient_name',
                'ipd_details.due_patient_party_doctor_id',
                'doctor.name',
                'ipd_details.due_patient_party_amount',
                'ipd_details.due_patient_party_receipt_type',
                'ipd_details.created_at'
            )
            ->orderBy('ipd_details.created_at')
            ->get()
            ->map(function ($report) use ($billingController) {
                $report->transaction_amount = $this->parseGroupedTransactionAmounts($report->transaction_amount);
                $summary = $billingController->getBillingSummaryForIpd((int) $report->ipd_id);
                $report->gross_total = round((float) ($summary['total_charges'] ?? 0), 2);

                return $report;
            });
    }

    /**
     * Parse GROUP_CONCAT amounts into a list (Hostinger/XAMPP-safe; no JSON_ARRAYAGG).
     *
     * @return list<string|float|int>
     */
    private function parseGroupedTransactionAmounts($raw): array
    {
        if ($raw === null || $raw === '') {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode(',', (string) $raw)),
            static fn ($amount) => $amount !== ''
        ));
    }

}
