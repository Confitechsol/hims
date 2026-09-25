<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Revenue Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; margin: 10px; }
        .header { text-align: center; margin-bottom: 8px; }
        .hospital-name { font-size: 12px; font-weight: bold; margin: 0; }
        .hospital-address { font-size: 8px; margin: 2px 0 4px 0; }
        .report-title { font-size: 11px; font-weight: bold; margin: 0 0 4px 0; }
        .meta { margin-bottom: 6px; text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        thead { display: table-header-group; }
        th, td { border: 1px solid #333; padding: 3px 4px; }
        th { background: #d9d9d9; font-weight: bold; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .doctor-row { background: #e8e8e8; font-weight: bold; }
        .total-row { background: #f3f3f3; font-weight: bold; }
        .grand-row { background: #d9d9d9; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <p class="hospital-name">{{ strtoupper($hospital->name ?? 'HOSPITAL') }}</p>
        @if(!empty($hospital?->address))
            <p class="hospital-address">{{ strtoupper($hospital->address) }}</p>
        @endif
        <p class="report-title">DOCTOR REVENUE REPORT (REFERRAL)</p>
    </div>
    <div class="meta">
        Date From: {{ \Carbon\Carbon::parse($result['date_from'])->format('d/M/Y') }}
        &nbsp;&nbsp;To:&nbsp;&nbsp;
        {{ \Carbon\Carbon::parse($result['date_to'])->format('d/M/Y') }}
        &nbsp;&nbsp;|&nbsp;&nbsp;Doctor:
        {{ $result['doctor_filter_label'] ?? 'All' }}
        &nbsp;&nbsp;({{ $result['patient_count'] ?? 0 }} patient bill(s))
    </div>
    <table>
        <thead>
            <tr>
                <th class="text-left">Sl</th>
                <th class="text-left">Bill Date / Patient</th>
                <th class="text-left">Bill No</th>
                <th class="text-left">Adm. No.</th>
                <th class="text-right">Bed Ch.</th>
                <th class="text-right">Diag Ch</th>
                <th class="text-right">Other Ch</th>
                <th class="text-right">Pack Amt</th>
                <th class="text-right">Dr Visit</th>
                <th class="text-right">Bill Amt</th>
                <th class="text-right">Discount</th>
                <th class="text-right">N H Amt</th>
            </tr>
        </thead>
        <tbody>
            @forelse($result['groups'] ?? [] as $group)
                <tr class="doctor-row">
                    <td colspan="12" class="text-left">{{ $group['doctor_label'] }}</td>
                </tr>
                @foreach($group['rows'] as $row)
                <tr>
                    <td class="text-left">{{ $row['sl_no'] }}</td>
                    <td class="text-left">{{ $row['bill_date'] }}<br>{{ $row['patient_name'] }}</td>
                    <td class="text-left">{{ $row['bill_no'] }}</td>
                    <td class="text-left">{{ $row['adm_no'] }}</td>
                    <td class="text-right">{{ number_format($row['bed_charges'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['diagnosis_charges'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['other_charges'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['package_amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['doctor_visit_amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['bill_amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['discount_amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['net_hospital_amount'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" class="text-right">TOTAL :</td>
                    <td class="text-right">{{ number_format($group['totals']['bed_charges'], 2) }}</td>
                    <td class="text-right">{{ number_format($group['totals']['diagnosis_charges'], 2) }}</td>
                    <td class="text-right">{{ number_format($group['totals']['other_charges'], 2) }}</td>
                    <td class="text-right">{{ number_format($group['totals']['package_amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($group['totals']['doctor_visit_amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($group['totals']['bill_amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($group['totals']['discount_amount'], 2) }}</td>
                    <td class="text-right">{{ number_format($group['totals']['net_hospital_amount'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-left">No data for selected date range.</td>
                </tr>
            @endforelse
            @if(!empty($result['groups']))
            <tr class="grand-row">
                <td colspan="4" class="text-right">GRAND TOTAL :</td>
                <td class="text-right">{{ number_format($result['grand_total']['bed_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['diagnosis_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['other_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['package_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['doctor_visit_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['bill_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['discount_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['net_hospital_amount'], 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
