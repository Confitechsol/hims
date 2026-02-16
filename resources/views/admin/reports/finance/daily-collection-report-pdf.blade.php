<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Collection Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; margin: 12px; }
        h2 { margin: 0 0 8px 0; font-size: 14px; }
        .meta { margin-bottom: 8px; }
        .summary-box { margin-bottom: 15px; padding: 10px; border: 1px solid #333; }
        .summary-row { margin-bottom: 5px; }
        .summary-label { font-weight: bold; display: inline-block; width: 150px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 10px; }
        thead { display: table-header-group; }
        th, td { border: 1px solid #333; padding: 4px 5px; word-wrap: break-word; overflow: hidden; }
        th { background: #f0f0f0; font-weight: bold; }
        .text-right { text-align: right; }
        .text-danger { color: #d32f2f; }
        .section-row { background: #e0e0e0; font-weight: bold; }
        /* Column widths */
        .col-date { width: 8%; }
        .col-time { width: 6%; }
        .col-receipt { width: 10%; }
        .col-type { width: 10%; }
        .col-patient { width: 20%; }
        .col-amount { width: 10%; }
        .col-mode { width: 12%; }
        .col-received { width: 14%; }
    </style>
</head>
<body>
    <h2>Daily Collection Report</h2>
    <div class="meta">
        <strong>Period:</strong> {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}
        @if($hospital)
            | <strong>{{ $hospital->name ?? '' }}</strong>
        @endif
    </div>

    <!-- Summary Section -->
    <div class="summary-box">
        <div class="summary-row">
            <span class="summary-label">Total Collection:</span>
            <strong>₹ {{ number_format($result['summary']['total_collection'], 2) }}</strong>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Refund:</span>
            <span class="text-danger">₹ {{ number_format($result['summary']['total_refund'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Net Collection:</span>
            <strong>₹ {{ number_format($result['summary']['net_collection'], 2) }}</strong>
        </div>
        <div style="margin-top: 10px;">
            <strong>By Payment Mode:</strong>
            <table style="margin-top: 5px; width: 50%;">
                @foreach($result['summary']['by_payment_mode'] as $mode => $amount)
                    @if($amount != 0)
                    <tr>
                        <td>{{ $mode }}</td>
                        <td class="text-right">{{ $amount < 0 ? '-' : '' }}₹ {{ number_format(abs($amount), 2) }}</td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>

    <!-- Detail Section -->
    <h3 style="margin-top: 15px; margin-bottom: 5px;">Detail Transactions</h3>
    <table>
        <colgroup>
            <col class="col-date">
            <col class="col-time">
            <col class="col-receipt">
            <col class="col-type">
            <col class="col-patient">
            <col class="col-amount">
            <col class="col-mode">
            <col class="col-received">
        </colgroup>
        <thead>
            <tr>
                <th class="col-date">Date</th>
                <th class="col-time">Time</th>
                <th class="col-receipt">Receipt No.</th>
                <th class="col-type">Receipt Type</th>
                <th class="col-patient">Patient Name</th>
                <th class="col-amount text-right">Amount (₹)</th>
                <th class="col-mode">Payment Mode</th>
                <th class="col-received">Received By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($result['grouped_by_date'] as $dateKey => $dayData)
                <tr class="section-row">
                    <td colspan="7">{{ $dayData['date'] }}</td>
                    <td class="text-right">Total: ₹ {{ number_format($dayData['net'], 2) }}</td>
                </tr>
                @foreach($dayData['rows'] as $r)
                <tr>
                    <td class="col-date">{{ $r['date'] }}</td>
                    <td class="col-time">{{ $r['time'] }}</td>
                    <td class="col-receipt">{{ $r['receipt_no'] }}</td>
                    <td class="col-type">{{ $r['receipt_type'] }}</td>
                    <td class="col-patient">{{ $r['patient_name'] }}</td>
                    <td class="col-amount text-right {{ $r['is_refund'] ? 'text-danger' : '' }}">
                        {{ $r['is_refund'] ? '-' : '' }}{{ number_format($r['amount'], 2) }}
                    </td>
                    <td class="col-mode">{{ $r['payment_mode'] }}</td>
                    <td class="col-received">{{ $r['received_by'] }}</td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="8" class="text-right">No records.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
