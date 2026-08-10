<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Cash Book Register</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; margin: 15px; }
        .company { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 6px; }
        .title { font-size: 13px; font-weight: bold; text-align: center; margin-bottom: 4px; }
        .period { font-size: 11px; font-weight: bold; text-align: center; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px 5px; }
        th { background: #f0f0f0; font-weight: bold; }
        .text-right { text-align: right; }
        tfoot td { font-weight: bold; background: #e8e8e8; }
    </style>
</head>
<body>
    @php
        $company = $result['company_name'] ?? ($hospital->name ?? '');
        $periodFrom = \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y');
        $periodTo = \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y');
    @endphp
    <div class="company">{{ $company }}</div>
    <div class="title">DAILY CASH BOOK REGISTER</div>
    <div class="period">Period: {{ $periodFrom }} to {{ $periodTo }}</div>

    <table>
        <thead>
            <tr>
                <th class="text-right">Total Clear Balance (₹)</th>
                <th>Date</th>
                <th class="text-right">Total Cash Receipt (₹)</th>
                <th class="text-right">Total UPI Receipt (₹)</th>
                <th class="text-right">Total Transfer to Bank (₹)</th>
                <th class="text-right">Total Received (₹)</th>
                <th class="text-right">Total Expense (₹)</th>
                <th class="text-right">Balance Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($result['rows'] as $row)
                <tr>
                    <td class="text-right">{{ number_format($row['total_clear_balance'], 2) }}</td>
                    <td>{{ $row['date'] }}</td>
                    <td class="text-right">{{ number_format($row['total_cash'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['total_upi'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['total_transfer'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['total_received'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['total_expense'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['balance_amount'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-right">No records for this period.</td>
                </tr>
            @endforelse
        </tbody>
        @if(!empty($result['rows']))
        <tfoot>
            <tr>
                <td class="text-right">—</td>
                <td>Total</td>
                <td class="text-right">{{ number_format($result['totals']['total_cash'], 2) }}</td>
                <td class="text-right">{{ number_format($result['totals']['total_upi'], 2) }}</td>
                <td class="text-right">{{ number_format($result['totals']['total_transfer'], 2) }}</td>
                <td class="text-right">{{ number_format($result['totals']['total_received'], 2) }}</td>
                <td class="text-right">{{ number_format($result['totals']['total_expense'], 2) }}</td>
                <td class="text-right">{{ number_format($result['totals']['balance_amount'], 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>
