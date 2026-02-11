<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Money Receipt Register</title>
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
    <h2>Money Receipt Register</h2>
    <div class="meta">
        <strong>Period:</strong> {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}
        @if($hospital)
            | <strong>{{ $hospital->name ?? '' }}</strong>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>Admission Date</th>
                <th>Admission Number</th>
                <th>Patient Name</th>
                <th>Receipt Number</th>
                <th>Receipt Date</th>
                <th class="text-right">Receipt Amount</th>
                <th>Payment/Receipt Mode</th>
                <th>Bed Number</th>
                <th>Username (Entered By)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($result['grouped'] as $date => $categories)
                @foreach(['Received', 'Refund'] as $category)
                    @php $items = $categories[$category] ?? []; @endphp
                    @if(count($items) > 0)
                        <tr class="section-row">
                            <td colspan="9">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} — {{ $category }}</td>
                        </tr>
                        @foreach($items as $r)
                        <tr>
                            <td>{{ $r['admission_date'] }}</td>
                            <td>{{ $r['admission_number'] }}</td>
                            <td>{{ $r['patient_name'] }}</td>
                            <td>{{ $r['receipt_no'] }}</td>
                            <td>{{ $r['receipt_date'] }}</td>
                            <td class="text-right">{{ number_format($r['receipt_amount'], 2) }}</td>
                            <td>{{ $r['payment_mode'] }}</td>
                            <td>{{ $r['bed_number'] }}</td>
                            <td>{{ $r['username'] }}</td>
                        </tr>
                        @endforeach
                    @endif
                @endforeach
            @empty
                <tr>
                    <td colspan="9" class="text-right">No records.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
