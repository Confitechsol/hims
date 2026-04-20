<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strcasecmp($receipt->receipt_type ?? '', 'Refund') === 0 ? 'Refund Receipt' : 'Money Receipt' }} - {{ $receipt->receipt_no }}</title>
    <style>        
        @page {
            size: A4;
            /* Make top margin tight so the receipt box sits
               just under the header, with almost no extra gap. */
            margin-top: 150px;
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
        header {
            position: fixed;
            /* Slightly above the page content; paired with margin-top:150px
               to minimise the blank space between header and patient info. */
            top: -140px;
            left: 20px;
            right: 20px;
            height: auto;
            padding: 8px 0;
        }

        header .header-content {
            width: 100%;
            max-width: 100%;
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

        header .top_head {
            margin-bottom: 8px;
        }

        .top_head {
            width: 100%;
            margin-bottom: 8px;
            margin-left: 0;
            margin-right: 0;
        }

        .top_head table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
            padding: 0;
            max-width: 100%;
        }

        .top_head td {
            vertical-align: top;
            padding: 0 5px;
            margin: 0;
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
            margin: 5px 0;
        }

        .heading h4 {
            text-transform: uppercase;
            margin: 5px 0;
            font-size: 16px;
        }

        .red {
            color: #ff3405;
            font-weight: 700;
        }

        .blue {
            color: #010080;
            font-weight: 700;
        }

        .receipt_info {
            border: 2px solid #282828;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .receipt_info table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }

        .receipt_info td {
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .receipt_label {
            font-weight: 700;
            text-align: left;
            width: 30%;
            white-space: nowrap;
        }

        .receipt_colon {
            text-align: left;
            width: 2%;
            padding: 0 3px;
            font-weight: 700;
        }

        .receipt_value {
            text-align: left;
            width: 68%;
            word-wrap: break-word;
        }

        .receipt_value.red {
            color: #ff3405;
        }

        .payment_section {
            /* border: 2px solid #282828; */
            padding: 10px;
            margin-top: 15px;
            font-size: 10px;
        }

        .payment_amount {
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .payment_nature {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #282828;
        }

        .footer_info {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #282828;
            font-size: 9px;
        }

        .footer_info table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer_info td {
            padding: 3px 5px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Fixed Header - repeats on all pages -->
    <header>
        <div class="header-content">
        <!-- Header with Logo and Hospital Info -->
        <div class="top_head">
            <table style="width: 100%; table-layout: fixed; border-collapse: collapse; margin: 0; padding: 0;">
                <tr>
                    <td class="second_logo" style="width: 30%; padding: 0 5px; margin: 0; vertical-align: middle; text-align: center;">
                        @if (file_exists(public_path('assets/images/nabh-logo.png')))
                            <div style="text-align: center;">
                                <img src="{{ public_path('assets/images/nabh-logo.png') }}" alt="NABH" style="height: 70px; width:auto; display: inline-block;">
                            </div>
                        @endif
                        <p style="margin: 0; font-size: 10px;">NABH/PESHCO-2018-3150/L-03</p>
                    </td>
                    <td class="first_logo" style="width: 20%; padding: 0 5px; margin: 0; vertical-align: middle;">
                        @if (file_exists(public_path('assets/images/logo.webp')))
                            <img src="{{ public_path('assets/images/logo.webp') }}" alt="LOGO" style="height: 50px; display: block; margin-bottom: 3px;">
                        @endif
                    </td>
                    
                    <td class="about_info" style="width: 35%; padding: 0 5px; margin: 0; vertical-align: top; text-align: right;">
                        <p style="margin: 1px 0;"><strong>{{ $hospital->name ?? 'Hospital Name' }}</strong></p>
                        <p style="margin: 1px 0;">{{ $hospital->address ?? 'Hospital Address' }}</p>
                        <p style="margin: 1px 0;">Phone - {{ $hospital->phone ?? 'Phone Number' }}</p>
                        @if(!empty($hospital->hospital_landline_1) || !empty($hospital->hospital_landline_2))
                        <p style="margin: 1px 0;">Landline - 
                            @if(!empty($hospital->hospital_landline_1)){{ $hospital->hospital_landline_1 }}@endif
                            @if(!empty($hospital->hospital_landline_1) && !empty($hospital->hospital_landline_2)), @endif
                            @if(!empty($hospital->hospital_landline_2)){{ $hospital->hospital_landline_2 }}@endif
                        </p>
                        @endif
                        <p style="margin: 1px 0;">E-mail: {{ $hospital->email ?? 'Email' }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Title -->
        <div class="heading" style="margin: 5px 0;">
            <h4 class="red" style="margin: 0; font-size: 14px;">{{ strcasecmp($receipt->receipt_type ?? '', 'Refund') === 0 ? 'REFUND RECEIPT' : 'MONEY RECEIPT' }}</h4>
        </div>

        <!-- Thick Horizontal Line -->
        <div style="border-top: 2px solid #282828; margin: 5px 0;"></div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="main_box">
            <!-- Receipt Details Section -->
            <div class="receipt_info">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; padding-right: 10px; vertical-align: top;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td class="receipt_label"><strong>Receipt No.</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value red">{{ $receipt->receipt_no }}</td>
                                </tr>
                                <tr>
                                    <td class="receipt_label"><strong>Receipt Date & Time</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value">
                                        {{ $receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('d.m.Y H:i:s') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="receipt_label"><strong>Patient Name</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value">{{ $receipt->patient->patient_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="receipt_label"><strong>Age & Sex</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value">
                                        {{ $receipt->patient->age ?? '-' }} Y
                                        @if($receipt->patient && $receipt->patient->gender)
                                            & {{ strtoupper($receipt->patient->gender) }}
                                        @endif
                                    </td>
                                </tr>
                                @if($receipt->ipd)
                                <tr>
                                    <td class="receipt_label"><strong>Admission No.</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value red">{{ $receipt->ipd->ipd_no }}</td>
                                </tr>
                                @php
                                    // Get latest bed history for this IPD (by from_date / created_at)
                                    $bedHistory = \App\Models\PatientBedHistory::with(['bedGroup', 'bed'])
                                        ->where('ipd_id', $receipt->ipd->id)
                                        ->orderByDesc('from_date')
                                        ->orderByDesc('id')
                                        ->first();

                                    $bedName = null;
                                    if ($bedHistory) {
                                        $groupName = $bedHistory->bedGroup->name ?? null;
                                        $bedNumber = $bedHistory->bed->name ?? null;
                                        $parts = array_filter([$groupName, $bedNumber]);
                                        if (!empty($parts)) {
                                            $bedName = implode(' ', $parts);
                                        }
                                    }
                                @endphp
                                @if($bedName)
                                <tr>
                                    <td class="receipt_label"><strong>BED No.</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value">{{ $bedName }}.</td>
                                </tr>
                                @endif
                                @endif
                            </table>
                        </td>
                        <td style="width: 50%; padding-left: 10px; vertical-align: top;">
                            <table style="width: 100%; border-collapse: collapse;">
                                @if($receipt->ipd)
                                <tr>
                                    <td class="receipt_label"><strong>Admission Date</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value">
                                        {{ $receipt->ipd->date ? \Carbon\Carbon::parse($receipt->ipd->date)->format('d.m.Y') : '-' }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="receipt_label"><strong>Address</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value">{{ $receipt->patient->address ?? '-' }}</td>
                                </tr>
                                @if($receipt->ipd)
                                <tr>
                                    <td class="receipt_label"><strong>Police Station</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value">{{ $receipt->patient->police_station ?? '-' }}</td>
                                </tr>
                                @endif
                                @if($receipt->ipd && $receipt->ipd->doctor)
                                <tr>
                                    <td class="receipt_label"><strong>Under Doctor</strong></td>
                                    <td class="receipt_colon">:</td>
                                    <td class="receipt_value">
                                        {{ $receipt->ipd->doctor->name ?? '' }} {{ $receipt->ipd->doctor->surname ?? '' }}
                                        @if($receipt->ipd->doctor->registration_number)
                                            (REG. {{ $receipt->ipd->doctor->registration_number }})
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Payment Information Section -->
            <div class="payment_section">
                @php
                    $amount = (float) ($receipt->amount ?? 0);
                    $discount = (float) ($receipt->discount ?? 0);
                    $tds = (float) ($receipt->tds ?? 0);
                    $finalAmount = max(0, $amount - $discount - $tds);
                    $amountInWords = '';
                    if ($finalAmount > 0 && class_exists(\App\Helpers\NumberToWords::class)) {
                        try {
                            $amountInWords = \App\Helpers\NumberToWords::convert($finalAmount);
                        } catch (\Exception $e) {
                            $amountInWords = '';
                        }
                    }
                    $isRefund = strcasecmp($receipt->receipt_type ?? '', 'Refund') === 0;
                    $paymentModeDisplay = strtoupper($receipt->payment_mode ?? 'CASH');
                    // Map common payment_mode values to display format (e.g. "Bank Transfer" -> "TRANSFER TO BANK ACCOUNT")
                    if (stripos($paymentModeDisplay, 'transfer') !== false || stripos($paymentModeDisplay, 'bank') !== false) {
                        $paymentModeDisplay = 'TRANSFER TO BANK ACCOUNT';
                    } elseif (stripos($paymentModeDisplay, 'cash') !== false) {
                        $paymentModeDisplay = 'CASH';
                    } elseif (stripos($paymentModeDisplay, 'cheque') !== false) {
                        $paymentModeDisplay = 'CHEQUE';
                    } elseif (stripos($paymentModeDisplay, 'card') !== false) {
                        $paymentModeDisplay = 'CARD';
                    }
                @endphp
                <div class="payment_amount">
                    @if($isRefund)
                        Refunded Final Amount of Rs. {{ number_format($finalAmount, 2) }}
                        @if($amountInWords)
                            ({{ $amountInWords }})
                        @endif
                        For Patient {{ strtoupper($receipt->patient->patient_name ?? '-') }}
                    @else
                        Received With Thanks The Final Amount of Rs. {{ number_format($finalAmount, 2) }}
                        @if($amountInWords)
                            ({{ strtolower($amountInWords) }})
                        @endif
                        For Patient {{ $receipt->patient->patient_name ?? '-' }}
                    @endif
                </div>
                <div style="margin-top:6px; font-size: 9px;">
                    Gross: Rs. {{ number_format($amount, 2) }} |
                    Discount: Rs. {{ number_format($discount, 2) }} |
                    TDS: Rs. {{ number_format($tds, 2) }} |
                    Final: Rs. {{ number_format($finalAmount, 2) }}
                </div>
                <div class="payment_nature">
                    <strong>Payment Nature:</strong> Payment Vide {{ $paymentModeDisplay }}
                </div>
            </div>

            <!-- Footer Information -->
            <div class="footer_info">
                <table>
                    <tr>
                        <td style="width: 50%;">
                            <strong>Print Date & Time:</strong> {{ now()->format('d/m/Y H:i:s') }}
                        </td>
                        <td style="width: 50%; text-align: right;">
                            <strong>Received By:</strong> {{ $receipt->receiver->username ?? Auth::user()->username ?? '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </main>

    <!-- Fixed Footer -->
    <footer>
        <!-- Footer content if needed -->
    </footer>
</body>
</html>
