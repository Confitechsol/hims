<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IPD Final Bill Register</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; margin: 12px; }
        h2 { margin: 0 0 8px 0; font-size: 14px; }
        .meta { margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        thead { display: table-header-group; }
        th, td { border: 1px solid #333; padding: 3px 4px; word-wrap: break-word; overflow: hidden; }
        th { background: #f0f0f0; font-weight: bold; }
        .text-right { text-align: right; }
        /* Fixed column widths so columns stay aligned across page breaks */
        .col-sno { width: 5%; }
        .col-adm-no { width: 8%; }
        .col-adm-date { width: 8%; }
        .col-ipd { width: 8%; }
        .col-patient { width: 12%; }
        .col-bill-no { width: 10%; }
        .col-bill-date { width: 8%; }
        .col-cat { width: 12%; }
        .col-details { width: 21%; }
        .col-amount { width: 8%; }
    </style>
</head>
<body>
    <h2>IPD Final Bill Register</h2>
    <div class="meta">
        <strong>Period (Bill/Discharge Date):</strong> {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}
        @if($hospital)
            | <strong>{{ $hospital->name ?? '' }}</strong>
        @endif
    </div>
    <table>
        <colgroup>
            <col class="col-sno">
            <col class="col-adm-no">
            <col class="col-adm-date">
            <col class="col-ipd">
            <col class="col-patient">
            <col class="col-bill-no">
            <col class="col-bill-date">
            <col class="col-cat">
            <col class="col-details">
            <col class="col-amount">
        </colgroup>
        <thead>
            <tr>
                <th class="col-sno">Sl. No.</th>
                <th class="col-adm-no">Admission No.</th>
                <th class="col-adm-date">Admission Date</th>
                <th class="col-ipd">IPD No.</th>
                <th class="col-patient">Patient Name</th>
                <th class="col-bill-no">Bill Number</th>
                <th class="col-bill-date">Bill Date</th>
                <th class="col-cat">Charge Category Head</th>
                <th class="col-details">Charge Details</th>
                <th class="col-amount text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($result['rows'] ?? [] as $r)
            <tr>
                <td class="col-sno">{{ $r['bill_sl_no'] }}</td>
                <td class="col-adm-no">{{ $r['admission_number'] }}</td>
                <td class="col-adm-date">{{ $r['admission_date'] }}</td>
                <td class="col-ipd">{{ $r['ipd_number'] }}</td>
                <td class="col-patient">{{ $r['patient_name'] }}</td>
                <td class="col-bill-no">{{ $r['bill_number'] }}</td>
                <td class="col-bill-date">{{ $r['bill_date'] }}</td>
                <td class="col-cat">{{ $r['charge_category_head'] }}</td>
                <td class="col-details">{{ $r['charge_details'] }}</td>
                <td class="col-amount text-right">{{ $r['amount'] < 0 ? '-' : '' }}{{ number_format(abs($r['amount']), 2) }}</td>
            </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-right">No records.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
