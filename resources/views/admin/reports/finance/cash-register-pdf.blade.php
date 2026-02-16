<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Register</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; margin: 15px; }
        h2 { margin: 0 0 10px 0; font-size: 14px; }
        .meta { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 3px 5px; }
        th { background: #f0f0f0; font-weight: bold; }
        .section-row { background: #e0e0e0; font-weight: bold; }
        .total-row { background: #d0d0d0; font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Cash Register</h2>
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
                <th>Case/Prescription No</th>
                <th class="text-right">Discount Amount</th>
                <th>Bed Number</th>
                <th>Username (Entered By)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($result['grouped'] as $date => $receiptTypes)
                @php
                    $dayTotal = 0;
                    $dayDiscountTotal = 0;
                @endphp
                @foreach($receiptTypes as $receiptType => $items)
                    @php
                        $typeTotal = 0;
                        $typeDiscountTotal = 0;
                    @endphp
                    <tr class="section-row">
                        <td colspan="10">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} — {{ $receiptType }}</td>
                    </tr>
                    @foreach($items as $r)
                    @php
                        $typeTotal += $r['receipt_amount'];
                        $typeDiscountTotal += $r['discount_amount'];
                        $dayTotal += $r['receipt_amount'];
                        $dayDiscountTotal += $r['discount_amount'];
                    @endphp
                    <tr>
                        <td>{{ $r['admission_date'] }}</td>
                        <td>{{ $r['admission_number'] }}</td>
                        <td>{{ $r['patient_name'] }}</td>
                        <td>{{ $r['receipt_no'] }}</td>
                        <td>{{ $r['receipt_date'] }}</td>
                        <td class="text-right">{{ number_format($r['receipt_amount'], 2) }}</td>
                        <td>{{ $r['case_prescription_no'] }}</td>
                        <td class="text-right">{{ number_format($r['discount_amount'], 2) }}</td>
                        <td>{{ $r['bed_number'] }}</td>
                        <td>{{ $r['username'] }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="5" class="text-right">{{ $receiptType }} Total:</td>
                        <td class="text-right">{{ number_format($typeTotal, 2) }}</td>
                        <td></td>
                        <td class="text-right">{{ number_format($typeDiscountTotal, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5" class="text-right"><strong>Day Total:</strong></td>
                    <td class="text-right"><strong>{{ number_format($dayTotal, 2) }}</strong></td>
                    <td></td>
                    <td class="text-right"><strong>{{ number_format($dayDiscountTotal, 2) }}</strong></td>
                    <td colspan="2"></td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-right">No records.</td>
                </tr>
            @endforelse
        </tbody>
        @if($result && count($result['rows']) > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>Grand Total:</strong></td>
                <td class="text-right"><strong>{{ number_format(collect($result['rows'])->sum('receipt_amount'), 2) }}</strong></td>
                <td></td>
                <td class="text-right"><strong>{{ number_format(collect($result['rows'])->sum('discount_amount'), 2) }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>
