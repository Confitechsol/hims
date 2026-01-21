<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Bill - {{ $ipd->ipd_no }}</title>
    <style>
        @page {
            size: A4;
            margin: 320px 15mm 100px 15mm;
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
        header {
            position: fixed;
            top: -310px;
            left: 15mm;
            right: 15mm;
            height: 310px;
            background-color: white;
            z-index: 1000;
            overflow: visible;
            padding: 5px 0;
        }

        header .header-content {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Fixed footer - repeats on all pages */
        footer {
            position: fixed;
            bottom: -90px;
            left: 15mm;
            right: 15mm;
            height: 90px;
            background-color: white;
            z-index: 1000;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 9px;
        }

        main {
            margin-top: 0;
            padding-top: 25px;
            position: relative;
        }

        .main_box {
            padding: 10px 0;
            width: 100%;
            box-sizing: border-box;
        }

        .top_head {
            width: 100%;
            margin-bottom: 8px;
        }

        .top_head table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }

        .top_head td {
            vertical-align: top;
            padding: 0 5px;
        }

        .first_logo {
            font-size: 9px;
            width: 35%;
            text-align: left;
            vertical-align: top;
        }

        .second_logo {
            font-size: 9px;
            width: 30%;
            text-align: center;
            vertical-align: middle;
        }

        .about_info {
            text-align: right;
            font-size: 9px;
            line-height: 1.1;
            width: 35%;
            vertical-align: top;
        }

        .about_info p {
            margin: 1px 0;
        }

        .heading {
            text-align: center;
            margin: 8px 0;
        }

        .heading h4 {
            text-transform: uppercase;
            margin: 5px 0;
            font-size: 18px;
            font-weight: 700;
            color: #000;
        }

        .bill-info {
            border: 2px solid #000;
            padding: 6px;
            margin-bottom: 5px;
            font-size: 10px;
        }

        .bill-info table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .bill-info td {
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .bill_label {
            font-weight: 700;
            text-align: left;
            width: 20%;
            white-space: nowrap;
        }

        .bill_colon {
            text-align: left;
            width: 3%;
            padding: 0 3px;
            font-weight: 700;
        }

        .bill_value {
            text-align: left;
            width: 27%;
            word-wrap: break-word;
        }

        .patient-info {
            border: 2px solid #000;
            padding: 6px;
            margin-bottom: 5px;
            font-size: 10px;
        }

        .patient-info table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .patient-info td {
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .patient_label {
            font-weight: 700;
            text-align: left;
            width: 15%;
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
            width: 32%;
            word-wrap: break-word;
        }

        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 9px;
            table-layout: fixed;
        }

        .charges-table th {
            background-color: transparent;
            color: #000;
            padding: 6px 4px;
            text-align: left;
            border: 1px solid #000;
            font-weight: 700;
            word-wrap: break-word;
        }

        .charges-table td {
            padding: 5px 4px;
            border: 1px solid #000;
            background-color: transparent;
            word-wrap: break-word;
            overflow: hidden;
        }

        .charges-table .text-right {
            text-align: right;
        }

        .charges-table .col-particulars {
            width: 45%;
        }

        .charges-table .col-date {
            width: 25%;
        }

        .charges-table .col-qty {
            width: 10%;
        }

        .charges-table .col-amount {
            width: 20%;
            text-align: right;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }

        .summary-table td {
            padding: 5px;
            border: 1px solid #000;
        }

        .summary-label {
            font-weight: 700;
            width: 70%;
        }

        .summary-value {
            text-align: right;
            font-weight: 700;
            width: 30%;
        }

        .footer-info {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            font-size: 9px;
        }

        .footer-left {
            text-align: left;
        }

        .footer-right {
            text-align: right;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            margin: 8px 0 5px 0;
            text-transform: uppercase;
        }

        .page-number {
            position: absolute;
            top: -310px;
            right: 50px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="top_head">
                <table>
                    <tr>
                        <td class="first_logo">
                            @if($hospital && $hospital->image)
                                <img src="{{ public_path('uploads/hospital_images/' . $hospital->image) }}" alt="Hospital Logo" style="max-width: 80px; max-height: 60px;">
                            @endif
                            <div style="margin-top: 5px;">
                                <strong>{{ $hospital->address ?? '10/4D, ELGIN ROAD, KOLKATA - 700 020' }}</strong><br>
                                <strong>{{ $hospital->name ?? 'SAMARITAN Medical Surgical & Critical Care' }}</strong><br>
                                @if($hospital)
                                    NABH/PESHCO-2018-3150/L-03<br>
                                    Phone - {{ $hospital->phone ?? '033 4060 - 8313' }}<br>
                                    @if($hospital->phone && strpos($hospital->phone, ',') === false)
                                        {{ '033 4029 - 2156' }}<br>
                                    @endif
                                @else
                                    NABH/PESHCO-2018-3150/L-03<br>
                                    Phone - 033 4060 - 8313<br>
                                    033 4029 - 2156<br>
                                @endif
                                <strong>{{ $ipd->ipd_no ?? 'A-002450/25-26' }}</strong>
                            </div>
                        </td>
                        <td class="second_logo">
                            @if($hospital && $hospital->mini_logo)
                                <img src="{{ public_path('uploads/hospital_images/' . $hospital->mini_logo) }}" alt="NABH Logo" style="max-width: 100px; max-height: 80px;">
                            @endif
                        </td>
                        <td class="about_info">
                            <!-- Right side info if needed -->
                        </td>
                    </tr>
                </table>
            </div>

            <div class="heading">
                <h4>FINAL BILL / TAX INVOICE - CUM BILL</h4>
            </div>

            <div class="page-number">
                Page <span id="page-num">1</span> of <span id="page-total">1</span>
            </div>

            <div class="bill-info">
                <table>
                    <tr>
                        <td class="bill_label">Final BILL No.</td>
                        <td class="bill_colon">:</td>
                        <td class="bill_value">{{ $billNumber ?? 'F-002457/25-26' }}</td>
                        <td class="bill_label">Bill Date</td>
                        <td class="bill_colon">:</td>
                        <td class="bill_value">{{ \Carbon\Carbon::parse($billDate)->format('d/M/Y') }}</td>
                    </tr>
                </table>
            </div>

            <div class="patient-info">
                <table>
                    <tr>
                        <td class="patient_label">Patient Name</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ $ipd->patient->patient_name ?? 'N/A' }}</td>
                        <td class="patient_label">Age</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ $ipd->patient->age ?? 'N/A' }} @if($ipd->patient->month){{ $ipd->patient->month }}M @endif @if($ipd->patient->day){{ $ipd->patient->day }}D @endif</td>
                        <td class="patient_label">Sex</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ ucfirst($ipd->patient->gender ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <td class="patient_label">Address</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value" colspan="7">{{ $ipd->patient->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="patient_label">Phone No.</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ $ipd->patient->mobileno ?? 'N/A' }}</td>
                        <td class="patient_label">Admission No.</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ $ipd->ipd_no ?? 'N/A' }}</td>
                        <td class="patient_label">Admission Date</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ \Carbon\Carbon::parse($ipd->date)->format('d/m/Y') }} @if($ipd->time) Time: {{ \Carbon\Carbon::parse($ipd->time)->format('h:i A') }} @endif</td>
                    </tr>
                    @if($dischargeDate)
                    <tr>
                        <td class="patient_label">Discharge Date</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value">{{ \Carbon\Carbon::parse($dischargeDate)->format('d/m/Y') }} @if(isset($ipd->discharge_time)) Time: {{ \Carbon\Carbon::parse($ipd->discharge_time)->format('h:i A') }} @endif</td>
                    </tr>
                    @endif
                    @if($ipd->doctor)
                    <tr>
                        <td class="patient_label">Under C/O</td>
                        <td class="patient_colon">:</td>
                        <td class="patient_value" colspan="7">{{ $ipd->doctor->name ?? 'N/A' }} {{ $ipd->doctor->surname ?? '' }} @if($ipd->doctor->staff_id) ( REG - {{ $ipd->doctor->staff_id }} ) @endif</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </header>

    <footer>
        <div class="footer-info">
            <div class="footer-left">
                <div><strong>PAN NO :</strong> {{ $hospital->pan_no ?? 'AAECS8384A' }}</div>
                <div><strong>GSTIN NO :</strong> {{ $hospital->gstin_no ?? '19AAECS8384A2Z5' }}</div>
                <div><strong>PLACE:</strong> {{ $hospital->place ?? 'WEST BENGAL' }}</div>
            </div>
            <div class="footer-right">
                <div><strong>Bill By :</strong> {{ auth()->user()->name ?? 'SUDIPTA' }}</div>
                <div><strong>E. & O. E.</strong></div>
                <div><strong>For :</strong> {{ $hospital->name ?? 'Samaritan Clinic Pvt. Ltd.' }}</div>
            </div>
        </div>
    </footer>

    <main>
        <div class="main_box">
            <table class="charges-table">
                <thead>
                    <tr>
                        <th class="col-particulars">PARTICULARS</th>
                        <th class="col-date"></th>
                        <th class="col-qty"></th>
                        <th class="col-amount">AMT. IN (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BED CHARGES -->
                    @if($bedChargesDetails->count() > 0)
                    <tr>
                        <td colspan="4" style="font-weight: 700; background-color: #f0f0f0;">BED CHARGES</td>
                    </tr>
                    @foreach($bedChargesGrouped as $bedType => $charges)
                    @php
                        $bedCount = $charges->count();
                        $firstCharge = $charges->first();
                        $bedRate = $firstCharge->bed_charge_rate ?? 0;
                        $totalBedAmount = $charges->sum('bed_charge');
                        $firstDate = \Carbon\Carbon::parse($firstCharge->charge_date)->format('d/m/Y');
                        $lastDate = \Carbon\Carbon::parse($charges->last()->charge_date)->format('d/m/Y');
                        $days = $charges->count();
                        $bedTypeUpper = strtoupper($bedType);
                        $bedDisplayText = $bedTypeUpper . ' - ' . $bedCount . ' ' . $bedTypeUpper . ' @' . number_format($bedRate, 0);
                    @endphp
                    <tr>
                        <td class="col-particulars">{{ $bedDisplayText }}</td>
                        <td class="col-date">{{ $days }} Day{{ $days > 1 ? 's' : '' }} {{ $firstDate }}@if($firstDate != $lastDate) To {{ $lastDate }}@endif</td>
                        <td class="col-qty"></td>
                        <td class="col-amount">{{ number_format($totalBedAmount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                        <td class="col-amount"><strong>{{ number_format($breakup['bed_charges'], 2) }}</strong></td>
                    </tr>
                    @endif

                    <!-- OT CHARGES -->
                    @if($otCharges->count() > 0)
                    <tr>
                        <td colspan="4" style="font-weight: 700; background-color: #f0f0f0;">OT CHARGES**</td>
                    </tr>
                    @foreach($otCharges as $charge)
                    @php
                        $categoryName = ($charge->chargeCategory && $charge->chargeCategory->name) ? $charge->chargeCategory->name : '';
                        $chargeName = ($charge->charge && $charge->charge->name) ? $charge->charge->name : 'OT Charge';
                        $unitPrice = $charge->net_amount / max($charge->qty, 1);
                    @endphp
                    <tr>
                        <td class="col-particulars">{{ $charge->qty }} x Rs. {{ number_format($unitPrice, 2) }} {{ strtoupper($chargeName) }}</td>
                        <td class="col-date"></td>
                        <td class="col-qty">{{ $charge->qty }}</td>
                        <td class="col-amount">{{ number_format($charge->net_amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                        <td class="col-amount"><strong>{{ number_format($otCharges->sum('net_amount'), 2) }}</strong></td>
                    </tr>
                    @endif

                    <!-- MEDICINE FROM SHOP -->
                    @php
                        $medicineFromShop = $medicineCharges->filter(function($c) {
                            return stripos($c->charge->name ?? '', 'shop') !== false || 
                                   stripos($c->chargeCategory->name ?? '', 'shop') !== false;
                        });
                    @endphp
                    @if($medicineFromShop->count() > 0)
                    <tr>
                        <td colspan="4" style="font-weight: 700; background-color: #f0f0f0;">MEDICINE FROM SHOP (BLUE PRINT)</td>
                    </tr>
                    @foreach($medicineFromShop as $charge)
                    @php
                        $chargeName = ($charge->charge && $charge->charge->name) ? $charge->charge->name : 'Medicine';
                        $unitPrice = $charge->net_amount / max($charge->qty, 1);
                    @endphp
                    <tr>
                        <td class="col-particulars">{{ $charge->qty }} x Rs. {{ number_format($unitPrice, 2) }} {{ strtoupper($chargeName) }} BILL</td>
                        <td class="col-date"></td>
                        <td class="col-qty">{{ $charge->qty }}</td>
                        <td class="col-amount">{{ number_format($charge->net_amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                        <td class="col-amount"><strong>{{ number_format($medicineFromShop->sum('net_amount'), 2) }}</strong></td>
                    </tr>
                    @endif

                    <!-- MEDICINE FROM STOCK -->
                    @php
                        $medicineFromStock = $medicineCharges->filter(function($c) {
                            return stripos($c->charge->name ?? '', 'stock') !== false || 
                                   stripos($c->chargeCategory->name ?? '', 'stock') !== false ||
                                   ($medicineFromShop->count() == 0 && stripos($c->chargeCategory->name ?? '', 'Medicine') !== false);
                        });
                    @endphp
                    @if($medicineFromStock->count() > 0)
                    <tr>
                        <td colspan="4" style="font-weight: 700; background-color: #f0f0f0;">MEDICINE FROM STOCK***</td>
                    </tr>
                    @foreach($medicineFromStock as $charge)
                    @php
                        $unitPrice = $charge->net_amount / max($charge->qty, 1);
                    @endphp
                    <tr>
                        <td class="col-particulars">{{ $charge->qty }} x Rs. {{ number_format($unitPrice, 2) }}</td>
                        <td class="col-date"></td>
                        <td class="col-qty">{{ $charge->qty }}</td>
                        <td class="col-amount">{{ number_format($charge->net_amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                        <td class="col-amount"><strong>{{ number_format($medicineFromStock->sum('net_amount'), 2) }}</strong></td>
                    </tr>
                    @endif

                    <!-- INVESTIGATION CHARGES -->
                    @if($investigationCharges > 0)
                    <tr>
                        <td colspan="4" style="font-weight: 700; background-color: #f0f0f0;">INVESTIGATION CHARGES</td>
                    </tr>
                    @php
                        $pathologyBillNo = $pathologyDetails->first()->bill_no ?? null;
                        $radiologyBillNo = $radiologyDetails->first()->bill_no ?? null;
                        $billNoDisplay = '';
                        if ($pathologyBillNo && $radiologyBillNo) {
                            $billNoDisplay = '(' . $pathologyBillNo . '/' . $radiologyBillNo . ')';
                        } elseif ($pathologyBillNo) {
                            $billNoDisplay = '(' . $pathologyBillNo . ')';
                        } elseif ($radiologyBillNo) {
                            $billNoDisplay = '(' . $radiologyBillNo . ')';
                        }
                    @endphp
                    <tr>
                        <td class="col-particulars">Total Amount of Bills @if($billNoDisplay){{ $billNoDisplay }} @endif : Rs-{{ number_format($investigationCharges, 0) }} And Less Adv Recvd in Diagnostic Rs-0.00</td>
                        <td class="col-date"></td>
                        <td class="col-qty"></td>
                        <td class="col-amount">{{ number_format($investigationCharges, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="col-amount"><strong>{{ number_format($investigationCharges, 2) }}</strong></td>
                    </tr>
                    @endif

                    <!-- OT SURGEON CHARGE -->
                    @if($surgeonCharges->count() > 0)
                    <tr>
                        <td colspan="4" style="font-weight: 700; background-color: #f0f0f0;">OT (SURGEON CHARGE)</td>
                    </tr>
                    @foreach($surgeonCharges as $charge)
                    <tr>
                        <td class="col-particulars">{{ strtoupper($ipd->doctor->name ?? 'N/A') }} {{ strtoupper($ipd->doctor->surname ?? '') }}@if($ipd->doctor && $ipd->doctor->staff_id) ( REG - {{ $ipd->doctor->staff_id }} )@endif</td>
                        <td class="col-date"></td>
                        <td class="col-qty"></td>
                        <td class="col-amount">{{ number_format($charge->net_amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"></td>
                        <td class="col-amount"><strong>{{ number_format($surgeonCharges->sum('net_amount'), 2) }}</strong></td>
                    </tr>
                    @endif

                    <!-- OT ANESTHESIA CHARGE -->
                    @if($anesthesiaCharges->count() > 0)
                    <tr>
                        <td colspan="4" style="font-weight: 700; background-color: #f0f0f0;">OT (ANESTHESIA CHARGE)</td>
                    </tr>
                    @foreach($anesthesiaCharges as $charge)
                    @php
                        $anesthesiaDoctor = \App\Models\Doctor::find($charge->doctor_id ?? null);
                        $doctorName = $anesthesiaDoctor ? (strtoupper($anesthesiaDoctor->name . ' ' . ($anesthesiaDoctor->surname ?? ''))) : (strtoupper(($charge->charge && $charge->charge->name) ? $charge->charge->name : 'N/A'));
                    @endphp
                    <tr>
                        <td class="col-particulars">{{ $doctorName }}</td>
                        <td class="col-date"></td>
                        <td class="col-qty"></td>
                        <td class="col-amount">{{ number_format($charge->net_amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"></td>
                        <td class="col-amount"><strong>{{ number_format($anesthesiaCharges->sum('net_amount'), 2) }}</strong></td>
                    </tr>
                    @endif

                    <!-- Other IPD Charges -->
                    @php
                        $otherCharges = $ipdChargesDetails->filter(function($charge) use ($otCharges, $medicineCharges, $surgeonCharges, $anesthesiaCharges) {
                            return !$otCharges->contains($charge) && 
                                   !$medicineCharges->contains($charge) && 
                                   !$surgeonCharges->contains($charge) && 
                                   !$anesthesiaCharges->contains($charge);
                        });
                    @endphp
                    @if($otherCharges->count() > 0)
                    @foreach($otherCharges->groupBy(function($c) { return $c->chargeCategory->name ?? 'Other'; }) as $categoryName => $charges)
                    <tr>
                        <td colspan="4" style="font-weight: 700; background-color: #f0f0f0;">{{ strtoupper($categoryName) }}</td>
                    </tr>
                    @foreach($charges as $charge)
                    <tr>
                        <td>{{ $charge->charge->name ?? 'N/A' }}</td>
                        <td>{{ $charge->qty }}</td>
                        <td class="text-right">{{ number_format($charge->net_amount, 2) }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                        <td class="text-right"><strong>{{ number_format($charges->sum('net_amount'), 2) }}</strong></td>
                    </tr>
                    @endforeach
                    @endif

                    <!-- Grand Total -->
                    <tr>
                        <td colspan="3" class="col-particulars" style="font-weight: 700; font-size: 11px;">Grand Total</td>
                        <td class="col-amount" style="font-weight: 700; font-size: 11px;">:</td>
                        <td></td>
                        <td class="col-amount" style="font-weight: 700; font-size: 11px;">{{ number_format($grandTotal, 2) }}</td>
                    </tr>

                    <!-- Less Advance Paid -->
                    <tr>
                        <td colspan="3" class="col-particulars" style="font-weight: 700;">Less Advance Paid</td>
                        <td class="col-amount" style="font-weight: 700;">:</td>
                        <td></td>
                        <td class="col-amount" style="font-weight: 700;">{{ number_format($totalAdvance, 2) }}</td>
                    </tr>
                    @if($payments->count() > 0)
                    <tr>
                        <td colspan="4" style="font-size: 9px; padding-left: 5px;">
                            Rupees {{ strtolower($totalAdvanceInWords) }}
                            @foreach($payments as $payment)
                                R/{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}({{ number_format($payment->amount, 0) }}-D)-{{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d/m/Y') }}
                                @if(!$loop->last), @endif
                            @endforeach
                        </td>
                    </tr>
                    @endif

                    <!-- Discount -->
                    <tr>
                        <td colspan="3" class="col-particulars" style="font-weight: 700;">Discount</td>
                        <td class="col-amount" style="font-weight: 700;">:</td>
                        <td class="col-qty text-right">{{ number_format($discount, 2) }}</td>
                        <td class="col-amount">{{ number_format($discount, 2) }}</td>
                    </tr>

                    <!-- Balance -->
                    <tr>
                        <td colspan="3" class="col-particulars" style="font-weight: 700; font-size: 11px;">Balance</td>
                        <td class="col-amount" style="font-weight: 700; font-size: 11px;">:</td>
                        <td></td>
                        <td class="col-amount" style="font-weight: 700; font-size: 11px;">{{ number_format($balance, 2) }}</td>
                    </tr>
                    @if($balance == 0)
                    <tr>
                        <td colspan="4" style="font-size: 9px; padding-left: 5px; font-weight: 700;">
                            Full & Final Payment
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("DejaVu Sans", "normal");
            $fontSize = 9;
            
            // Get current page number
            $pageNum = $pdf->get_page_number();
            
            // Get total pages - use passed value if available
            if (isset($totalPages) && $totalPages > 0) {
                $totalPagesCount = $totalPages;
            } else {
                $totalPagesCount = $pdf->get_page_count();
            }
            
            // Ensure valid numbers
            if ($pageNum <= 0) $pageNum = 1;
            if ($totalPagesCount <= 0) $totalPagesCount = 1;
            
            // Page number text
            $pageText = "Page " . $pageNum . " of " . $totalPagesCount;
            
            // Calculate position (top right, in header)
            $pageWidth = $fontMetrics->get_text_width($pageText, $font, $fontSize);
            $pageX = $pdf->get_width() - $pageWidth - 15; // 15mm from right edge (matching margin)
            $pageY = 20; // Top of page
            
            // Draw page number
            $pdf->page_text($pageX, $pageY, $pageText, $font, $fontSize);
        }
    </script>
</body>
</html>
