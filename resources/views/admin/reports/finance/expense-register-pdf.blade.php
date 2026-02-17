<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Register</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; margin: 15px; }
        h2 { margin: 0 0 10px 0; font-size: 14px; }
        .meta { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px 6px; }
        th { background: #f0f0f0; font-weight: bold; }
        .section-row { background: #e0e0e0; font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Expense Register</h2>
    <div class="meta">
        <strong>Period:</strong> {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}
        @if($result['payment_type'])
            | <strong>Payment Type:</strong> {{ $result['payment_type'] }}
        @endif
        @if($hospital)
            | <strong>{{ $hospital->name ?? '' }}</strong>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Expense Head</th>
                <th>Expense Receipt No.</th>
                <th class="text-right">Amount (₹)</th>
                <th>Payment Type</th>
                <th>Username (Entered By)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($result['grouped'] as $date => $items)
                <tr class="section-row">
                    <td colspan="6">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                </tr>
                @foreach($items as $r)
                <tr>
                    <td>{{ $r['date'] }}</td>
                    <td>{{ $r['expense_head'] }}</td>
                    <td>{{ $r['expense_receipt_no'] }}</td>
                    <td class="text-right">{{ number_format($r['amount'], 2) }}</td>
                    <td>{{ $r['payment_mode'] }}</td>
                    <td>{{ $r['username'] }}</td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="text-right">No records.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
