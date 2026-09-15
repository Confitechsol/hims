<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Backend Money Receipt {{ $receipt->receipt_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        .header { text-align: center; border-bottom: 2px solid #750096; padding-bottom: 12px; }
        h1 { margin: 0 0 4px; font-size: 20px; }
        h2 { margin: 20px 0 10px; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { border: 1px solid #bbb; padding: 9px; }
        th { background: #f1e6f5; text-align: left; width: 35%; }
        .amount { text-align: right; font-size: 15px; }
        .footer { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Backend Money Receipt</h1>
        <div>Receipt No: {{ $receipt->receipt_no }}</div>
    </div>

    <h2>Receipt Details</h2>
    <table>
        <tr><th>Patient Name</th><td>{{ $receipt->patient_name ?: '-' }}</td></tr>
        <tr><th>Age</th><td>{{ $receipt->age ?? '-' }}</td></tr>
        <tr><th>Case No.</th><td>{{ $receipt->case_no ?: '-' }}</td></tr>
        <tr><th>Receipt Date</th><td>{{ $receipt->received_date ? \Carbon\Carbon::parse($receipt->received_date)->format('d/m/Y') : '-' }}</td></tr>
        <tr><th>Receipt Type</th><td>{{ $receipt->receipt_type ?: '-' }}</td></tr>
        <tr><th>Payment Mode</th><td>{{ $receipt->payment_mode ?: '-' }}</td></tr>
    </table>

    <h2>Amount Details</h2>
    <table>
        <tr><th>Bill Amount</th><td class="amount">₹ {{ number_format($billAmount, 2) }}</td></tr>
        <tr><th>Received Amount</th><td class="amount"><strong>₹ {{ number_format((float) $receipt->received_amount, 2) }}</strong></td></tr>
        <tr><th>Due Amount</th><td class="amount"><strong>₹ {{ number_format($dueAmount, 2) }}</strong></td></tr>
    </table>

    <div class="footer">Received By: {{ $receipt->received_by ?? '-' }}</div>
</body>
</html>
