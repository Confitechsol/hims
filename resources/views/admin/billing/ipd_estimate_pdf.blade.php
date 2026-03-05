<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estimate Bill - {{ $ipd->ipd_no }}</title>
    <style>        
        /* @page {
                size: A4;
                margin-top: 300px;   
                margin-bottom: 80px;
                margin-left: 20px;
                margin-right: 20px;
            } */


            @page {
                size: A4;
                margin-top: 120px;
                margin-bottom: 80px;
                margin-left: 20px;
                margin-right: 20px;
            }


        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Fixed header - repeats on all pages */
        /* header {
                position: fixed;
                top: -240px;
                left: 20px;
                right: 20px;
                height: auto;
                padding: 0;
            } */


            header {
                position: fixed;
                top: -100px;
                left: 20px;
                right: 20px;
                height: auto;
                padding: 0;
            }



        header .header-content {
            width: 100%;
            max-width: 100%;
            /* margin: 0;
            padding: 0; */
            box-sizing: border-box;
        }

        /* Fixed footer - repeats on all pages */
        footer {
            position: fixed;
            bottom: -70px;
            left: 50px;
            right: 50px;
            height: 70px;
            background-color: white;
            z-index: 1000;
        }

        main {
            margin: 0;
            padding: 0;
            margin-left: 20px;
            margin-right: 20px;
            position: relative;
        }
        
        /* Small gap between header and body on all pages */
        .header-body-spacer {
            height: 18px;
            margin: 0;
            padding: 0;
            border: none;
            font-size: 0;
            line-height: 0;
            display: block;
        }
        
        main > *:first-child {
            margin-top: 0;
        }

        .main_box {
            padding: 15px 0;
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            box-sizing: border-box;
        }

        header .patient_info {
            margin-bottom: 0;
        }

        .heading {
            text-align: center;
            margin: 0 0 1px 0;
        }

        .heading h4 {
            text-transform: uppercase;
            margin: 0;
            font-size: 14px;
        }

        .red {
            color: #ff3405;
            font-weight: 700;
        }

        .blue {
            color: #010080;
            font-weight: 700;
        }

        .patient_info {
            border: 2px solid #282828;
            padding: 4px;
            margin-bottom: 0;
            font-size: 10px;
        }

        .patient_info table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }

        .patient_info td {
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .patient_label {
            font-weight: 700;
            text-align: left;
            width: 35%;
            white-space: nowrap;
        }

        .patient_colon {
            text-align: left;
            width: 3%;
            padding: 0 3px;
            font-weight: 700;
        }

        .patient_value {
            text-align: left;
            width: 62%;
            word-wrap: break-word;
        }

        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
        }

        .charges-table th {
            background-color: transparent;
            color: #000;
            padding: 5px;
            text-align: left;
            border: 1px solid #282828;
            font-weight: 700;
        }

        .charges-table td {
            padding: 4px 5px;
            border: 1px solid #282828;
            background-color: transparent;
        }

        .charges-table tr {
            background-color: transparent;
        }
        
        .charges-table tr:nth-child(even) {
            background-color: transparent;
        }

        .summary-section {
            border: 2px solid #282828;
            padding: 10px;
            margin-top: 15px;
            font-size: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dashed #282828;
        }

        .summary-row:last-child {
            border-bottom: none;
            border-top: 2px solid #282828;
            margin-top: 5px;
            padding-top: 8px;
            font-weight: 700;
            font-size: 12px;
        }

        .summary-label {
            font-weight: 700;
        }

        .summary-value {
            text-align: right;
        }

        .words-section {
            border: 2px solid #282828;
            padding: 10px;
            margin-top: 10px;
            font-size: 9px;
        }

        .words-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px dashed #282828;
        }

        .words-row:last-child {
            border-bottom: none;
            border-top: 2px solid #282828;
            margin-top: 5px;
            padding-top: 5px;
            font-weight: 700;
        }

        .words-label {
            font-weight: 700;
            width: 40%;
        }

        .words-value {
            width: 60%;
            font-style: italic;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #000;
            margin: 10px 0 5px 0;
            text-transform: uppercase;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
        }

        .payment-table th {
            background-color: transparent;
            color: #000;
            padding: 5px;
            text-align: left;
            border: 1px solid #282828;
            font-weight: 700;
        }

        .payment-table td {
            padding: 4px 5px;
            border: 1px solid #282828;
            background-color: transparent;
        }

        .first-page-patient {
            display: block;
        }

        @page :first {
        }

        @media print {
            .first-page-patient {
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Fixed Header - repeats on all pages -->
    <header>
        <div class="header-content">
        <!-- Title -->
        <div class="heading">
            <h4 class="red">ESTIMATE COPY</h4>
        </div>

        <!-- Thick Horizontal Line -->
        <div style="border-top: 2px solid #282828; margin: 1px 0;"></div>

        <!-- Estimate/Admission Reference Section -->
        <div class="patient_info" style="margin-bottom: 0;">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
                <tr>
                    <td style="text-align: left; width: 33.33%; padding: 3px 4px;">
                        <strong>ADM No.</strong> : <span class="red">{{ $ipd->ipd_no }}</span>
                    </td>
                    <td style="text-align: center; width: 33.33%; padding: 3px 4px;">
                        <strong>Estimate Date</strong> : {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </td>
                    <td style="text-align: right; width: 33.34%; padding: 3px 4px;">
                        <strong>Estimate Time</strong> : {{ \Carbon\Carbon::now()->format('H:i:s') }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Thick Horizontal Line -->
       {{--  <div style="border-top: 2px solid #282828; margin: 1px 0;"></div> --}}

        
        </div>
    </header>

    <!-- Fixed Footer - repeats on all pages -->
    <footer>
        <hr style="border-top: 1px solid #282828; margin: 5px 0;">
        <table width="100%" style="font-size: 8px; color: #666;">
            <tr>
                <td>
                    Generated on {{ \Carbon\Carbon::now()->format('d-m-Y h:i A') }}
                </td>
                <td align="right" style="text-align: right;">
                    <!-- Page number will be added via PHP script -->
                </td>
            </tr>
            <tr>
                <td>
                    Generated by: {{ $logged_user ?? '' }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; padding-top: 5px;">
                    This is a computer-generated document. No signature required.
                </td>
            </tr>
        </table>
    </footer>

    <!-- Main Content Area -->
    <main>
        <!-- Patient Information -->
        <div class="patient_info">
            <table>
                <tr>
                    <!-- Left Column -->
                    <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                        <table style="width: 100%;">
                            <tr>
                                <td class="patient_label">Patient Name</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">{{ strtoupper($ipd->patient->patient_name ?? 'N/A') }}@if($ipd->patient->organisation) ({{ strtoupper($ipd->patient->organisation->organisation_name ?? '') }})@endif</td>
                            </tr>
                            <tr>
                                <td class="patient_label">Address</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">
                                    @if($ipd->patient->address)
                                        @php
                                            $addressLines = array_filter(array_map('trim', explode(',', $ipd->patient->address)));
                                        @endphp
                                        @foreach($addressLines as $line)
                                            {{ $line }}<br>
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="patient_label">Phone No.</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">{{ $ipd->patient->mobileno ?? 'N/A' }}</td>
                            </tr>
                            @if($ipd->doctor)
                            <tr>
                                <td class="patient_label">Under Doctor</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">{{ strtoupper($ipd->doctor->name ?? 'N/A') }} {{ strtoupper($ipd->doctor->surname ?? '') }}@if($ipd->doctor->registration_no) (REG-{{ $ipd->doctor->registration_no }})@endif</td>
                            </tr>
                            @endif
                        </table>
                    </td>
                    <!-- Right Column -->
                    <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                        <table style="width: 100%;">
                            <tr>
                                <td class="patient_label">Age</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">{{ $ipd->patient->age ?? 'N/A' }} Y</td>
                            </tr>
                            <tr>
                                <td class="patient_label">Sex</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">{{ strtoupper($ipd->patient->gender ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <td class="patient_label">Adm Date</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">{{ \Carbon\Carbon::parse($ipd->date)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="patient_label">Adm Time</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">{{ \Carbon\Carbon::parse($ipd->date)->format('h:i A') }}</td>
                            </tr>
                            @if($ipd->bedDetail)
                            <tr>
                                <td class="patient_label">Bed No.</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value"><span class="red">{{ $ipd->bedDetail->name ?? 'N/A' }}.</span></td>
                            </tr>
                            @endif
                            <tr>
                                <td class="patient_label">URN</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">{{ $ipd->urn ?? '' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="header-body-spacer" aria-hidden="true"></div>
        <!-- Charges Breakdown -->
        <div class="section-title">Charges Breakdown</div>

        <!-- Bed Charges (grouped by bed and date range) -->
        @if(isset($bedChargesGroupedForDisplay) && $bedChargesGroupedForDisplay->count() > 0)
        <table class="charges-table">
            <thead>
                <tr>
                    <th colspan="4">Bed Charges</th>
                </tr>
                <tr>
                    <th>Bed / Rate</th>
                    <th>Duration</th>
                    <th>Date Range</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bedChargesGroupedForDisplay as $row)
                <tr>
                    <td>{{ $row->bed_label ?? 'N/A' }}</td>
                    <td>{{ $row->no_of_days ?? 0 }} {{ ($row->no_of_days ?? 0) === 1 ? 'Day' : 'Days' }}</td>
                    <td>{{ $row->from_date ? \Carbon\Carbon::parse($row->from_date)->format('d/m/Y') : 'N/A' }} To {{ $row->to_date ? \Carbon\Carbon::parse($row->to_date)->format('d/m/Y') : 'N/A' }}</td>
                    <td class="text-right">Rs. {{ number_format($row->bed_charge ?? 0, 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="3" class="text-right">Subtotal:</td>
                    <td class="text-right">Rs. {{ number_format($breakup['bed_charges'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- CGST Charges -->
        @if(isset($gstChargesGrouped['cgst']) && count($gstChargesGrouped['cgst']) > 0)
        <table class="charges-table">
            <thead>
                <tr>
                    <th colspan="2">CGST CHARGES</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gstChargesGrouped['cgst'] as $cgst)
                <tr>
                    <td>{{ $cgst['description'] }}</td>
                    <td class="text-right">Rs. {{ number_format($cgst['amount'], 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td class="text-right">Subtotal:</td>
                    <td class="text-right">Rs. {{ number_format($breakup['cgst_charges'] ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- SGST Charges -->
        @if(isset($gstChargesGrouped['sgst']) && count($gstChargesGrouped['sgst']) > 0)
        <table class="charges-table">
            <thead>
                <tr>
                    <th colspan="2">SGST CHARGES</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gstChargesGrouped['sgst'] as $sgst)
                <tr>
                    <td>{{ $sgst['description'] }}</td>
                    <td class="text-right">Rs. {{ number_format($sgst['amount'], 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td class="text-right">Subtotal:</td>
                    <td class="text-right">Rs. {{ number_format($breakup['sgst_charges'] ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- IPD Charges -->
        @if(isset($ipdChargesDetails) && $ipdChargesDetails->count() > 0)
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
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ipdChargesDetails as $charge)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($charge->date)->format('d-m-Y') }}</td>
                    <td>{{ $charge->chargeCategory->name ?? 'N/A' }}</td>
                    <td>{{ $charge->charge->name ?? 'N/A' }}</td>
                    <td>{{ $charge->qty }}</td>
                    <td class="text-right">Rs. {{ number_format($charge->net_amount, 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="4" class="text-right">Subtotal:</td>
                    <td class="text-right">Rs. {{ number_format($breakup['ipd_charges'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Pathology Section (Date wise details) -->
        @if(isset($investigationDatewise) && $investigationDatewise->where('type', 'pathology')->count() > 0)
        <div class="section-title">Pathology (Date wise)</div>
        <table class="charges-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Test Name</th>
                    <th class="text-right">Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($investigationDatewise->where('type', 'pathology') as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row['date'])->format('d-m-Y') }}</td>
                    <td>{{ $row['test_name'] ?? '-' }}</td>
                    <td class="text-right">{{ number_format($row['amount'] ?? 0, 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="2" class="text-right">Pathology Total:</td>
                    <td class="text-right">Rs. {{ number_format(isset($pathologyTotal) && $pathologyTotal > 0 ? $pathologyTotal : ($breakup['pathology_charges'] ?? 0), 2) }}</td>
                </tr>
            </tbody>
        </table>
        @elseif(isset($pathologyTestNames) && count($pathologyTestNames) > 0)
        <div class="section-title">Pathology</div>
        <table class="charges-table">
            <thead><tr><th>Pathology</th><th class="text-right">Amount</th></tr></thead>
            <tbody>
                <tr>
                    <td>{{ implode(', ', $pathologyTestNames) }}</td>
                    <td class="text-right">Rs. {{ number_format(isset($pathologyTotal) && $pathologyTotal > 0 ? $pathologyTotal : $breakup['pathology_charges'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Radiology Section (Date wise details) -->
        @if(isset($investigationDatewise) && $investigationDatewise->where('type', 'radiology')->count() > 0)
        <div class="section-title">Radiology (Date wise)</div>
        <table class="charges-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Test Name</th>
                    <th class="text-right">Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($investigationDatewise->where('type', 'radiology') as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row['date'])->format('d-m-Y') }}</td>
                    <td>{{ $row['test_name'] ?? '-' }}</td>
                    <td class="text-right">{{ number_format($row['amount'] ?? 0, 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="2" class="text-right">Radiology Total:</td>
                    <td class="text-right">Rs. {{ number_format(isset($radiologyTotal) && $radiologyTotal > 0 ? $radiologyTotal : ($breakup['radiology_charges'] ?? 0), 2) }}</td>
                </tr>
            </tbody>
        </table>
        @elseif(isset($radiologyTestNames) && count($radiologyTestNames) > 0)
        <div class="section-title">Radiology</div>
        <table class="charges-table">
            <thead><tr><th>Radiology</th><th class="text-right">Amount</th></tr></thead>
            <tbody>
                <tr>
                    <td>{{ implode(', ', $radiologyTestNames) }}</td>
                    <td class="text-right">Rs. {{ number_format(isset($radiologyTotal) && $radiologyTotal > 0 ? $radiologyTotal : $breakup['radiology_charges'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Doctor Visit Charges -->
        @if(isset($doctorVisitDetails) && $doctorVisitDetails->count() > 0)
        <table class="charges-table">
            <thead>
                <tr>
                    <th colspan="4">Doctor Visit Charges</th>
                </tr>
                <tr>
                    <th>Visit Date</th>
                    <th>Doctor</th>
                    <th>Visit Type</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctorVisitDetails as $visit)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('d-m-Y') }}</td>
                    <td>{{ $visit->doctor->name ?? 'N/A' }} {{ $visit->doctor->surname ?? '' }}</td>
                    <td>{{ $visit->charge->name ?? 'N/A' }}</td>
                    <td class="text-right">Rs. {{ number_format($visit->amount, 2) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="3" class="text-right">Subtotal:</td>
                    <td class="text-right">Rs. {{ number_format($breakup['doctor_visit_charges'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Payment Details -->
        @if(isset($payments) && $payments->count() > 0)
        <div class="section-title">Payment Details</div>
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Receipt No.</th>
                    <th>Payment Mode</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                @php
                    $paymentMode = $payment->payment_mode ?? null;
                    $isCash = ($paymentMode == '1' || $paymentMode === 1 || strtolower($paymentMode) == 'cash');
                    $paymentModeText = 'N/A';
                    if ($paymentMode == '1' || $paymentMode === 1) {
                        $paymentModeText = 'Cash';
                    } elseif (!empty($paymentMode)) {
                        $paymentModeText = $paymentMode;
                    }
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d-m-Y') }}</td>
                    <td>R/{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $paymentModeText }}</td>
                    <td class="text-right">Rs. {{ number_format($payment->amount, 2) }}</td>
                </tr>
                @if(!$isCash && ($payment->cheque_no || $payment->cheque_date || $payment->note))
                <tr style="background-color: #f9f9f9;">
                    <td colspan="4" style="padding-left: 30px; font-size: 8px;">
                        @if($payment->cheque_no)
                            <strong>Cheque No.:</strong> {{ $payment->cheque_no }}
                        @endif
                        @if($payment->cheque_date)
                            @if($payment->cheque_no) | @endif
                            <strong>Cheque Date:</strong> {{ \Carbon\Carbon::parse($payment->cheque_date)->format('d-m-Y') }}
                        @endif
                        @if($payment->note)
                            @if($payment->cheque_no || $payment->cheque_date) | @endif
                            <strong>Note:</strong> {{ $payment->note }}
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="3" class="text-right">Total Payments:</td>
                    <td class="text-right">Rs. {{ number_format($breakup['total_payments'] ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="section-title" style="margin-top: 0; margin-bottom: 8px;">Payment Summary</div>
            <div class="summary-row">
                <span class="summary-label">Bed Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['bed_charges'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">IPD Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['ipd_charges'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Pathology Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['pathology_charges'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Radiology Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['radiology_charges'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Doctor Visit Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['doctor_visit_charges'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['total_charges'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Payments:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['total_payments'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Outstanding Amount:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['outstanding'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label"><span class="red">Net Balance:</span></span>
                <span class="summary-value"><span class="red">Rs. {{ number_format($breakup['outstanding'], 2) }}</span></span>
            </div>
        </div>

        <!-- Amount in Words -->
        @if(isset($totalChargesInWords))
        <div class="words-section">
            <div class="section-title" style="margin-top: 0; margin-bottom: 8px;">Amount in Words</div>
            <div class="words-row">
                <span class="words-label">Total Charges:</span>
                <span class="words-value">{{ $totalChargesInWords }}</span>
            </div>
            <div class="words-row">
                <span class="words-label">Total Payments:</span>
                <span class="words-value">{{ $totalPaymentsInWords }}</span>
            </div>
            <div class="words-row">
                <span class="words-label">Outstanding Amount:</span>
                <span class="words-value">{{ $outstandingInWords }}</span>
            </div>
            <div class="words-row">
                <span class="words-label"><strong>Net Balance:</strong></span>
                <span class="words-value"><strong>{{ $netBalanceInWords }}</strong></span>
            </div>
        </div>
        @endif
    </main>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("DejaVu Sans", "normal");
            $size = 8;
    
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
    
            $x = $pdf->get_width() - 120; // right aligned
            $y = $pdf->get_height() - 25; // footer area
    
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
    
</body>
</html>
