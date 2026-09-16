<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Backend Money Receipt {{ $receipt->receipt_no }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
            font-family: Arial, Helvetica, sans-serif;
            color: #1d1d1d;
        }

        .invoice {
            width: 100%;
            max-width: 860px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #d9d9d9;
            padding: 24px 30px 18px;
            min-height: 720px;
        }

        .header-block {
            border-bottom: 2px solid #1bb4c9;
            padding-bottom: 10px;
        }

        .top-header {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .top-header > div {
            display: table-cell;
            vertical-align: top;
        }

        .brand-left {
            width: 34%;
            padding-top: 2px;
        }

        .brand-logo {
            display: block;
            width: 52px;
            height: auto;
            max-height: 56px;
            margin-bottom: 6px;
        }

        .brand-center {
            width: 24%;
            text-align: center;
            padding-top: 8px;
        }

        .center-logo {
            display: inline-block;
            width: 120px;
            height: auto;
            max-height: 120px;
        }

        .brand-right {
            width: 42%;
            text-align: left;
            font-size: 12px;
            line-height: 1.35;
            padding-top: 8px;
        }

        .brand-left .logo-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .hospital-logo {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: #1b9ec5;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .hospital-logo::before {
            content: "";
            position: absolute;
            inset: 8px;
            border: 2px solid #fff;
            border-radius: 6px;
        }

        .hospital-name {
            font-weight: 700;
            font-size: 26px;
            letter-spacing: 1px;
            color: #0a4ea0;
            line-height: 1;
        }

        .hospital-name .small {
            font-size: 12px;
            letter-spacing: 0;
            color: #2b2b2b;
            display: block;
            margin-top: 6px;
            font-weight: 600;
        }

        .nabh-text {
            font-size: 12px;
            color: #1a1a1a;
            margin-top: 8px;
            font-weight: 700;
        }

        .triangle-mark {
            width: 128px;
            height: 128px;
            margin: 0 auto;
            position: relative;
        }

        .triangle-mark::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #0f804b 0%, #0f804b 50%, #0b6a3d 50%, #0b6a3d 100%);
            clip-path: polygon(50% 0%, 100% 100%, 0% 100%);
            border: 4px solid #0f8a4d;
        }

        .triangle-mark::after {
            content: "";
            position: absolute;
            inset: 18px 22px 18px 22px;
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 50%, #f2f2f2 50%, #f2f2f2 100%);
            clip-path: polygon(50% 0%, 100% 100%, 0% 100%);
            border: 3px solid #0e7d49;
        }

        .triangle-mark .center-circle {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            background: #0f8a4d;
            border: 3px solid #fff;
            z-index: 2;
        }

        .triangle-mark .center-circle::before {
            content: "";
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            background: #fff;
        }

        .brand-right h3 {
            margin: 0 0 6px;
            font-size: 18px;
            color: #1e1e1e;
            font-weight: 700;
        }

        .brand-right p {
            margin: 0;
            font-size: 12px;
            color: #1a1a1a;
            line-height: 1.5;
        }

        .brand-right strong {
            font-weight: 700;
        }

        .receipt-title {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            margin: 18px 0 14px;
            color: #1f1f1f;
        }

        .info-section {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .patient-info,
        .admission-info {
            display: table-cell;
            width: 50%;
            border: 1px solid #d9d9d9;
            background: #fafafa;
            padding: 10px 12px;
            vertical-align: top;
            font-size: 13px;
        }

        .patient-info {
            border-right: none;
        }

        .info-section p {
            margin: 6px 0;
            color: #242424;
        }

        .info-section b {
            font-weight: 700;
            color: #1f1f1f;
            min-width: 100px;
            display: inline-block;
        }

        .amount-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d9d9d9;
            margin-top: 8px;
        }

        .amount-table th {
            background: #1ab7cf;
            color: #fff;
            text-align: left;
            font-size: 14px;
            font-weight: 700;
            padding: 10px 12px;
            letter-spacing: 0.4px;
        }

        .amount-table td {
            border-top: 1px solid #d9d9d9;
            padding: 10px 12px;
            font-size: 13px;
            background: #fff;
        }

        .amount-table td:last-child {
            text-align: right;
            font-weight: 600;
            width: 25%;
        }

        .summary-table {
            width: 42%;
            margin: 12px 0 0 auto;
            border-collapse: collapse;
            border: 1px solid #d9d9d9;
        }

        .summary-table td {
            border-top: 1px solid #d9d9d9;
            padding: 8px 10px;
            font-size: 13px;
        }

        .summary-table tr:first-child td {
            border-top: 0;
        }

        .summary-table td:first-child {
            background: #f1f1f1;
            font-weight: 700;
        }

        .summary-table td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .summary-table .total-row td {
            font-size: 14px;
            font-weight: 700;
        }

        .amount-row td {
            background: #fff;
        }

        .receipt-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 24px;
            margin-top: 12px;
            font-size: 13px;
            color: #111;
        }

        .receipt-footer p {
            margin: 0;
        }

        @media print {
            body {
                padding: 0;
                background: #fff;
            }

            .invoice {
                border: 0;
                box-shadow: none;
                margin: 0;
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header-block">
            <div class="top-header">
                <div class="brand-left">
                    @if (file_exists(public_path('assets/images/logo.webp')))
                        <img src="{{ public_path('assets/images/logo.webp') }}" alt="Hospital Logo" class="brand-logo">
                    @else
                        <div class="hospital-name">SAMARITAN<span class="small">Medical Surgical &amp; Critical Care</span></div>
                    @endif
                    <div class="nabh-text">NABH/PESHCO-2018-3150/L-03</div>
                </div>

                <div class="brand-center">
                    @if (file_exists(public_path('assets/images/nabh-logo.png')))
                        <img src="{{ public_path('assets/images/nabh-logo.png') }}" alt="NABH Logo" class="center-logo">
                    @else
                        <div class="triangle-mark">
                            <div class="center-circle"></div>
                        </div>
                    @endif
                </div>

                <div class="brand-right">
                    <h3>Samaritan Clinic Pvt.Ltd.</h3>
                    <p>10/4D, ELGIN ROAD, KOLKATA - 700020</p>
                    <p><strong>Phone :-</strong> 033 4060 8313</p>
                    <p>033 4029 2156</p>
                    <p><strong>Ambulance :-</strong> 96747 77261</p>
                    <p><strong>E-mail :-</strong> samaritan84@gmail.com</p>
                </div>
            </div>

           <div class="receipt-title">
               BILL RECEIPT
           </div>


            <div class="info-section">
                <div class="patient-info">
                    <p><b>Receipt No.</b>: {{ $receipt->receipt_no ?? '-' }}</p>
                    <p><b>Patient Name</b>: {{ $receipt->patient_name ?? ($bill->patient_name ?? '-') }}</p>
                    <p><b>Admission no</b>: {{ $receipt->case_no ?? ($bill->case_no ?? '-') }}</p>
                    <p><b>Address</b>: {{ $bill->address ?? '-' }}</p>
                    <p><b>Under Doctor</b>: {{ $bill->doctor_name ?? '-' }}</p>
                </div>
                <div class="admission-info">
                    <p><b>Receipt Date</b>: {{ !empty($receipt->received_date) ? \Carbon\Carbon::parse($receipt->received_date)->format('d.m.Y') : '-' }}</p>
                    <p><b>Age</b>: {{ $receipt->age ?? ($bill->age ?? '-') }}</p>
                    <p><b>Gender</b>: {{ $bill->gender ?? '-' }}</p>
                    <p><b>Payment Mode</b>: {{ $receipt->payment_mode ?? '-' }}</p>
                </div>
            </div>

            @php
                $discountAmount = (float) ($receipt->discount_amount ?? ($bill->adjustment_amount ?? 0));
                $billItems = $bill?->billItems ?? collect();
            @endphp

            <table class="amount-table" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($billItems as $item)
                        <tr class="amount-row">
                            <td>{{ $item->bill_item ?? '-' }}</td>
                            <td>{{ number_format((float) ($item->amount ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr class="amount-row">
                            <td>Bill Amount</td>
                            <td>{{ number_format((float) ($billAmount ?? 0), 2) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <table class="summary-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td>Total Amount</td>
                        <td>{{ number_format((float) ($billAmount ?? 0), 2) }}</td>
                    </tr>
                    <tr>
                        <td>Discount Amount</td>
                        <td>{{ number_format($discountAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Received Amount</td>
                        <td>{{ number_format((float) ($totalReceived ?? $receipt->received_amount ?? 0), 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Due Amount</td>
                        <td>{{ number_format((float) ($dueAmount ?? 0), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="receipt-footer">
            <p><strong>Print Date &amp; Time</strong> : {{ now()->format('d/m/Y h:i:sA') }}</p>
            <p><strong>Received By</strong> : {{ $receipt->received_by ?? '-' }}</p>
        </div>
    </div>
</body>
</html>
