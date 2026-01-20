<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>IPD Final Bill - {{ $ipd->ipd_no }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #750096;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #750096;
            margin: 5px 0;
            font-size: 20px;
        }

        .header h2 {
            color: #750096;
            margin: 5px 0;
            font-size: 16px;
            font-weight: normal;
        }

        .patient-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .patient-info-row {
            display: table-row;
        }

        .patient-info-cell {
            display: table-cell;
            padding: 5px 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .patient-info-label {
            font-weight: bold;
            width: 30%;
            background-color: #e9e9e9;
        }

        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .charges-table th {
            background-color: #750096;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #750096;
        }

        .charges-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
        }

        .charges-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .summary-box {
            border: 2px solid #750096;
            padding: 15px;
            margin-top: 20px;
            background-color: #f9f9f9;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            color: #750096;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #750096;
        }

        .summary-label {
            font-weight: bold;
        }

        .summary-value {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .note-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
        }

        .payment-box {
            background-color: #d1ecf1;
            border: 1px solid #0c5460;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #750096;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #750096;
        }

        .outstanding-box {
            background-color: #f8d7da;
            border: 2px solid #dc3545;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
        }

        .outstanding-box h3 {
            color: #dc3545;
            margin: 0;
            font-size: 18px;
        }

        .outstanding-amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FINAL BILL</h1>
        <h2>IPD Patient Billing Statement</h2>
    </div>

    <!-- Patient Information -->
    <div class="section-title">Patient Information</div>
    <div class="patient-info">
        <div class="patient-info-row">
            <div class="patient-info-cell patient-info-label">IPD Number:</div>
            <div class="patient-info-cell">{{ $ipd->ipd_no }}</div>
            <div class="patient-info-cell patient-info-label">Admission Date:</div>
            <div class="patient-info-cell">{{ \Carbon\Carbon::parse($ipd->date)->format('d/m/Y') }}</div>
        </div>
        <div class="patient-info-row">
            <div class="patient-info-cell patient-info-label">Patient Name:</div>
            <div class="patient-info-cell">{{ $ipd->patient->patient_name ?? 'N/A' }}</div>
            <div class="patient-info-cell patient-info-label">Phone:</div>
            <div class="patient-info-cell">{{ $ipd->patient->mobileno ?? 'N/A' }}</div>
        </div>
        @if($ipd->doctor)
        <div class="patient-info-row">
            <div class="patient-info-cell patient-info-label">Consultant Doctor:</div>
            <div class="patient-info-cell">{{ $ipd->doctor->name ?? 'N/A' }} {{ $ipd->doctor->surname ?? '' }}</div>
            <div class="patient-info-cell patient-info-label">Status:</div>
            <div class="patient-info-cell">{{ $ipd->discharged == 'yes' ? 'Discharged' : 'Admitted' }}</div>
        </div>
        @endif
        @if($ipd->bedGroup)
        <div class="patient-info-row">
            <div class="patient-info-cell patient-info-label">Bed Group:</div>
            <div class="patient-info-cell">{{ $ipd->bedGroup->name ?? 'N/A' }}</div>
            <div class="patient-info-cell patient-info-label">Bed:</div>
            <div class="patient-info-cell">{{ $ipd->bedDetail->name ?? 'N/A' }}</div>
        </div>
        @endif
    </div>

    <!-- Charges Breakdown -->
    <div class="section-title">Charges Breakdown</div>

    <!-- Bed Charges -->
    @if($bedChargesDetails->count() > 0)
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="4">Bed Charges</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Bed Group</th>
                <th>Rate/Day</th>
                <th class="text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bedChargesDetails as $bedCharge)
            <tr>
                <td>{{ \Carbon\Carbon::parse($bedCharge->charge_date)->format('d/m/Y') }}</td>
                <td>{{ $bedCharge->bedGroup->name ?? 'N/A' }}</td>
                <td>{{ number_format($bedCharge->bed_charge_rate, 2) }}</td>
                <td class="text-right">{{ number_format($bedCharge->bed_charge, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9e9e9; font-weight: bold;">
                <td colspan="3" class="text-right">Subtotal:</td>
                <td class="text-right">{{ number_format($breakup['bed_charges'], 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- IPD Charges -->
    @if($ipdChargesDetails->count() > 0)
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="5">IPD Charges</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Charge Name</th>
                <th>Qty</th>
                <th class="text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ipdChargesDetails as $charge)
            <tr>
                <td>{{ \Carbon\Carbon::parse($charge->date)->format('d/m/Y') }}</td>
                <td>{{ $charge->chargeCategory->name ?? 'N/A' }}</td>
                <td>{{ $charge->charge->name ?? 'N/A' }}</td>
                <td>{{ $charge->qty }}</td>
                <td class="text-right">{{ number_format($charge->net_amount, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9e9e9; font-weight: bold;">
                <td colspan="4" class="text-right">Subtotal:</td>
                <td class="text-right">{{ number_format($breakup['ipd_charges'], 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Pathology Charges -->
    @if($pathologyDetails->count() > 0)
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="4">Pathology Charges</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Test Name</th>
                <th>Doctor</th>
                <th class="text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pathologyDetails as $pathology)
            <tr>
                <td>{{ \Carbon\Carbon::parse($pathology->date)->format('d/m/Y') }}</td>
                <td>{{ $pathology->pathology->test_name ?? 'N/A' }}</td>
                <td>{{ $pathology->doctor_name ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($pathology->total, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9e9e9; font-weight: bold;">
                <td colspan="3" class="text-right">Subtotal:</td>
                <td class="text-right">{{ number_format($breakup['pathology_charges'], 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Radiology Charges -->
    @if($radiologyDetails->count() > 0)
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="4">Radiology Charges</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Test Name</th>
                <th>Doctor</th>
                <th class="text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($radiologyDetails as $radiology)
            <tr>
                <td>{{ \Carbon\Carbon::parse($radiology->date)->format('d/m/Y') }}</td>
                <td>{{ $radiology->radiology->test_name ?? 'N/A' }}</td>
                <td>{{ $radiology->doctor_name ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($radiology->total, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9e9e9; font-weight: bold;">
                <td colspan="3" class="text-right">Subtotal:</td>
                <td class="text-right">{{ number_format($breakup['radiology_charges'], 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Doctor Visit Charges -->
    @if($doctorVisitDetails->count() > 0)
    <table class="charges-table">
        <thead>
            <tr>
                <th colspan="4">Doctor Visit Charges</th>
            </tr>
            <tr>
                <th>Visit Date</th>
                <th>Doctor</th>
                <th>Visit Type</th>
                <th class="text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctorVisitDetails as $visit)
            <tr>
                <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') }}</td>
                <td>{{ $visit->doctor->name ?? 'N/A' }} {{ $visit->doctor->surname ?? '' }}</td>
                <td>{{ $visit->charge->name ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($visit->amount, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9e9e9; font-weight: bold;">
                <td colspan="3" class="text-right">Subtotal:</td>
                <td class="text-right">{{ number_format($breakup['doctor_visit_charges'], 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Payment Details -->
    @if($payments->count() > 0)
    <div class="section-title">Payment Details</div>
    <table class="charges-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction ID</th>
                <th>Payment Mode</th>
                <th>Note</th>
                <th class="text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d/m/Y') }}</td>
                <td>{{ $payment->transaction_no ?? 'TRID' . $payment->id }}</td>
                <td>{{ $payment->payment_mode == 1 ? 'Cash' : ($payment->payment_mode ?? 'N/A') }}</td>
                <td>{{ $payment->note ?? '-' }}</td>
                <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #e9e9e9; font-weight: bold;">
                <td colspan="4" class="text-right">Total Payments:</td>
                <td class="text-right">{{ number_format($breakup['total_payments'], 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Summary -->
    <div class="summary-box">
        <div class="section-title" style="margin-top: 0;">Bill Summary</div>
        <div class="summary-row">
            <span class="summary-label">Bed Charges:</span>
            <span class="summary-value">₹ {{ number_format($breakup['bed_charges'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">IPD Charges:</span>
            <span class="summary-value">₹ {{ number_format($breakup['ipd_charges'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Pathology Charges:</span>
            <span class="summary-value">₹ {{ number_format($breakup['pathology_charges'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Radiology Charges:</span>
            <span class="summary-value">₹ {{ number_format($breakup['radiology_charges'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Doctor Visit Charges:</span>
            <span class="summary-value">₹ {{ number_format($breakup['doctor_visit_charges'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Charges:</span>
            <span class="summary-value">₹ {{ number_format($breakup['total_charges'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Payments:</span>
            <span class="summary-value">₹ {{ number_format($breakup['total_payments'], 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Outstanding Amount:</span>
            <span class="summary-value">₹ {{ number_format($breakup['outstanding'], 2) }}</span>
        </div>
    </div>

    <!-- Outstanding Amount Highlight -->
    @if($breakup['outstanding'] > 0)
    <div class="outstanding-box">
        <h3>Outstanding Amount</h3>
        <div class="outstanding-amount">₹ {{ number_format($breakup['outstanding'], 2) }}</div>
    </div>
    @elseif($breakup['outstanding'] == 0)
    <div class="note-box" style="background-color: #d4edda; border-color: #28a745;">
        <strong>✓ Payment Complete:</strong> All charges have been paid. No outstanding amount.
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</p>
        <p>This is a computer-generated document. No signature required.</p>
    </div>
</body>
</html>
