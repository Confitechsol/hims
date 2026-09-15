<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Backend Bill {{ $bill->case_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h2 { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #bbb; padding: 7px; }
        th { background: #f1f1f1; text-align: left; }
        .amount { text-align: right; }
        .summary { width: 45%; margin-left: auto; }
        .summary td { text-align: right; }
    </style>
</head>
<body>
    <h2>Backend Bill</h2>
    <div>Case No: {{ $bill->case_no }}</div>
    <div>Patient: {{ $bill->patient_name ?: '-' }}</div>
    <div>Date: {{ $bill->date ?: '-' }}</div>

    <table>
        <thead><tr><th>Bill Type</th><th class="amount">Amount</th></tr></thead>
        <tbody>
            @foreach($bill->billItems as $item)
                <tr><td>{{ $item->bill_item }}</td><td class="amount">{{ number_format((float) $item->amount, 2) }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr><th>Subtotal</th><td>{{ number_format($bill->billItems->sum('amount'), 2) }}</td></tr>
        <tr><th>{{ ucfirst($bill->adjustment_type) }}</th><td>{{ number_format((float) $bill->adjustment_amount, 2) }}</td></tr>
        <tr><th>Total Amount</th><td><strong>{{ number_format((float) ($bill->total_amount ?: $bill->billItems->sum('amount')), 2) }}</strong></td></tr>
    </table>
</body>
</html>