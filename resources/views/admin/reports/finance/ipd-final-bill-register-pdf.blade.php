<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Bill Register</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; margin: 12px; }
        .header { text-align: center; margin-bottom: 10px; }
        .hospital-name { font-size: 13px; font-weight: bold; margin: 0; }
        .hospital-address { font-size: 9px; margin: 2px 0 6px 0; }
        .report-title { font-size: 12px; font-weight: bold; margin: 0 0 4px 0; }
        .meta { margin-bottom: 8px; text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        thead { display: table-header-group; }
        th, td { border: 1px solid #333; padding: 4px 5px; }
        th { background: #d9d9d9; font-weight: bold; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .total-row { background: #e8e8e8; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <p class="hospital-name">{{ strtoupper($hospital->name ?? 'HOSPITAL') }}</p>
        @if(!empty($hospital?->address))
            <p class="hospital-address">{{ strtoupper($hospital->address) }}</p>
        @endif
        <p class="report-title">FINAL BILL REGISTER</p>
    </div>
    <div class="meta">
        Date From: {{ \Carbon\Carbon::parse($result['date_from'])->format('d/M/Y') }}
        &nbsp;&nbsp;To:&nbsp;&nbsp;
        {{ \Carbon\Carbon::parse($result['date_to'])->format('d/M/Y') }}
    </div>
    <table>
        <thead>
            <tr>
                <th class="text-left">Bill Date</th>
                <th class="text-right">Bed Ch.</th>
                <th class="text-right">Diag Ch</th>
                <th class="text-right">Other Ch</th>
                <th class="text-right">Service Ch</th>
                <th class="text-right">Home Amt</th>
                <th class="text-right">Disc Amt</th>
                <th class="text-right">Dr Visit</th>
                <th class="text-right">Package</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result['rows'] ?? [] as $row)
            <tr>
                <td class="text-left">{{ $row['bill_date'] }}</td>
                <td class="text-right">{{ number_format($row['bed_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($row['diagnosis_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($row['other_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($row['service_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($row['home_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($row['discount_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($row['doctor_visit_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($row['package_amount'], 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td class="text-left">Gross Total</td>
                <td class="text-right">{{ number_format($result['grand_total']['bed_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['diagnosis_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['other_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['service_charges'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['home_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['discount_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['doctor_visit_amount'], 2) }}</td>
                <td class="text-right">{{ number_format($result['grand_total']['package_amount'], 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
