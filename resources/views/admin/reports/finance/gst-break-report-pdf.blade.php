<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GST Break Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; margin: 10px; }
        .header { text-align: center; }
        .hospital-name { font-size: 12px; font-weight: bold; margin: 0; }
        .report-title { font-size: 11px; font-weight: bold; margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 3px; }
        th { background: #d9d9d9; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background: #eee; }
    </style>
</head>
<body>
    <div class="header">
        <p class="hospital-name">{{ strtoupper($hospital->name ?? 'HOSPITAL') }}</p>
        <p class="report-title">GST BREAK REPORT</p>
        <p>
            Date From: {{ \Carbon\Carbon::parse($result['date_from'])->format('d/M/Y') }}
            To: {{ \Carbon\Carbon::parse($result['date_to'])->format('d/M/Y') }}
            @if(!empty($result['patient_label']))
                | {{ $result['patient_label'] }}
            @endif
        </p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Sr</th>
                <th>Adm No.</th>
                <th>Adm Date</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Bill No.</th>
                <th>Bill Date</th>
                <th>Disch. Date</th>
                <th>Print Head</th>
                <th>Particulars</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($result['rows'] as $row)
            <tr>
                <td>{{ $row['sl_no'] }}</td>
                <td>{{ $row['admission_no'] }}</td>
                <td>{{ $row['admission_date'] }}</td>
                <td>{{ $row['patient_name'] }}</td>
                <td>{{ $row['doctor_name'] }}</td>
                <td>{{ $row['bill_no'] }}</td>
                <td>{{ $row['bill_date'] }}</td>
                <td>{{ $row['discharge_date'] }}</td>
                <td>{{ $row['print_head'] }}</td>
                <td>{{ $row['particulars'] }}</td>
                <td class="text-right">{{ number_format($row['amount'], 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="11">No data.</td></tr>
            @endforelse
            @if(!empty($result['rows']))
            <tr class="total-row">
                <td colspan="10" class="text-right">Total</td>
                <td class="text-right">{{ number_format($result['total_amount'], 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
