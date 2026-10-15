<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pathology and Radiology Breakup - {{ $ipd->ipd_no ?? $ipd->id }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 22px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .letterhead {
            width: 100%;
            margin-bottom: 8px;
            border-collapse: collapse;
        }

        .letterhead td {
            padding: 3px;
            border: 0;
            vertical-align: middle;
        }

        .logo-cell {
            width: 25%;
            text-align: center;
        }

        .logo-cell img {
            max-width: 110px;
            max-height: 58px;
        }

        .hospital-details {
            width: 50%;
            text-align: right;
            line-height: 1.4;
        }

        .report-title {
            margin: 7px 0;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        .rule {
            border-top: 2px solid #333;
            margin: 6px 0;
        }

        .patient-info {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .patient-info td {
            width: 50%;
            padding: 4px;
            border: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #bbb;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

        tfoot th {
            background: #e8e8e8;
        }

        .case-heading th {
            background: #e5e5e5;
        }

        .case-total td {
            font-weight: bold;
            background: #f7f7f7;
        }

        .empty {
            padding: 16px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <table class="letterhead">
        <tr>
            <td class="logo-cell">
                @if (file_exists(public_path('assets/images/nabh-logo.png')))
                    <img src="{{ public_path('assets/images/nabh-logo.png') }}" alt="NABH">
                    <div>NABH/PESHCO-2018-3150/L-03</div>
                @endif
            </td>
            <td class="logo-cell">
                @if (file_exists(public_path('assets/images/logo.webp')))
                    <img src="{{ public_path('assets/images/logo.webp') }}" alt="Hospital logo">
                @endif
            </td>
            <td class="hospital-details">
                <strong>{{ $hospital->name ?? 'Hospital Name' }}</strong><br>
                {{ $hospital->address ?? '' }}<br>
                @if (!empty($hospital->phone))
                    Phone: {{ $hospital->phone }}<br>
                @endif
                @if (!empty($hospital->hospital_landline_1) || !empty($hospital->hospital_landline_2))
                    Landline:
                    {{ $hospital->hospital_landline_1 ?? '' }}{{ !empty($hospital->hospital_landline_1) && !empty($hospital->hospital_landline_2) ? ', ' : '' }}{{ $hospital->hospital_landline_2 ?? '' }}<br>
                @endif
                {{ $hospital->email ?? '' }}
            </td>
        </tr>
    </table>

    {{-- <div class="rule"></div> --}}
    <div class="report-title">DIAGNOSTIC BREAKUP</div>
    {{-- <div class="rule"></div> --}}

    <table class="patient-info">
        <tr>
            <td><strong>Patient Name:</strong> {{ $ipd->patient->patient_name ?? 'N/A' }}</td>
            <td><strong>IPD No.:</strong> {{ $ipd->ipd_no ?? $ipd->id }}</td>
        </tr>
        <tr>
            <td><strong>Address:</strong> {{ $ipd->patient->address ?? 'N/A' }}</td>
            <td><strong>Admission Date:</strong> {{ $ipd->date ? \Carbon\Carbon::parse($ipd->date)->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Gender / Age:</strong> {{ $ipd->patient->gender ?? 'N/A' }} / {{ $ipd->patient->age ?? 'N/A' }}</td>
            <td><strong>Consultant:</strong> {{ $ipd->doctor->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Phone:</strong> {{ $ipd->patient->mobileno ?? 'N/A' }}</td>
            <td><strong>Print Date:</strong> {{ now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Test</th>
                <th class="amount">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($investigationRowsByCase as $caseNo => $caseRows)
                <tr class="case-heading">
                    <th colspan="3">Case Number: {{ $caseNo ?: '-' }}</th>
                    <th class="amount">Case Total: {{ number_format((float) $caseRows->sum('amount'), 2) }}</th>
                </tr>
                @foreach($caseRows as $row)
                    <tr>
                        <td>{{ $row['date'] ? \Carbon\Carbon::parse($row['date'])->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ ucfirst($row['type']) }}</td>
                        <td>{{ $row['test_name'] }}</td>
                        <td class="amount">{{ number_format((float) $row['amount'], 2) }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="4" class="empty">No pathology or radiology charges found for this IPD.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Pathology Subtotal</th>
                <th class="amount">{{ number_format($pathologyTotal, 2) }}</th>
            </tr>
            <tr>
                <th colspan="3">Radiology Subtotal</th>
                <th class="amount">{{ number_format($radiologyTotal, 2) }}</th>
            </tr>
            <tr>
                <th colspan="3">Total Investigation Charges</th>
                <th class="amount">{{ number_format($pathologyTotal + $radiologyTotal, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
