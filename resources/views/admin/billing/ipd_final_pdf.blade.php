<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Bill - {{ $ipd->ipd_no }}</title>
    <style>
        @page {
            size: A4;
            margin: 220px 20px 90px 20px;
        }

        header {
            position: fixed;
            top: -200px;
            left: 20px;
            right: 20px;
        }

        /* @page {
                size: A4;
                margin: 300px 20px 90px 20px;
            }

            header {
                position: fixed;
                top: -270px;
                left: 20px;
                right: 20px;
                height: auto;                     
                padding: 8px 0;
            } */

        /* @page {
                size: A4;
                margin-top: 450px;   
                margin-bottom: 80px;
                margin-left: 20px;
                margin-right: 20px;
            } */


        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Fixed header - repeats on all pages */
        /*  header {
                position: fixed;
                top: -420px;         
                left: 20px;
                right: 20px;
                height: auto;                     
                padding: 8px 0;
            } */



        header .header-content {
            width: 100%;
            max-width: 100%;
            /* margin: 0;
            padding: 0; */
            box-sizing: border-box;
        }

        /* Fixed footer - repeats on all pages */
        /* footer {
            position: fixed;
            bottom: -70px;
            left: 50px;
            right: 50px;
            height: 70px;
            background-color: white;
            z-index: 1000;
        } */

        footer {
            position: fixed;
            bottom: -60px;
            left: 20px;
            right: 20px;
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

        /* Ensure content on subsequent pages respects header */
        /* main > *:first-child {
            margin-top: 0;
        } */

        .main_box {
            padding: 15px 0;
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            box-sizing: border-box;
        }

        /* Ensure header content doesn't overflow */
        header .top_head {
            margin-bottom: 8px;
        }

        header .patient_info {
            margin-bottom: 3px;
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

        .patient_info {
            border: 2px solid #282828;
            padding: 6px;
            margin-bottom: 5px;
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

        .summary-section,
        .words-section {
            page-break-inside: avoid;
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

        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
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
                        <td class="second_logo"
                            style="width: 30%; padding: 0 5px; margin: 0; vertical-align: middle; ">
                            @if (file_exists(public_path('assets/images/nabh-logo.png')))
                                <div style="text-align: center;">
                                    <img src="{{ public_path('assets/images/nabh-logo.png') }}" alt="NABH"
                                        style="height: 70px; width:auto; display: inline-block;">
                                </div>
                            @endif
                            <p style="margin: 0; font-size: 10px;">NABH/PESHCO-2018-3150/L-03</p>
                        </td>
                        <td class="first_logo text-center" style="width: 20%; padding: 0 5px; margin: 0 auto; vertical-align: middle;">
                            @if (file_exists(public_path('assets/images/logo.webp')))
                                <img src="{{ public_path('assets/images/logo.webp') }}" alt="LOGO"
                                    style="height: 50px; display: block; margin-bottom: 3px;">
                            @endif
                        </td>

                        <td class="about_info"
                            style="width: 35%; padding: 0 5px; margin: 0; vertical-align: top; text-align: right;">
                            <p style="margin: 1px 0;"><strong>{{ $hospital->name ?? 'Hospital Name' }}</strong></p>
                            <p style="margin: 1px 0;">{{ $hospital->address ?? 'Hospital Address' }}</p>
                            <p style="margin: 1px 0;">Phone - {{ $hospital->phone ?? 'Phone Number' }}</p>
                            @if(!empty($hospital->hospital_landline_1) || !empty($hospital->hospital_landline_2))
                                <p style="margin: 1px 0;">Landline -
                                    @if(!empty($hospital->hospital_landline_1)){{ $hospital->hospital_landline_1 }}@endif
                                    @if(!empty($hospital->hospital_landline_1) && !empty($hospital->hospital_landline_2)),
                                    @endif
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
                <h4 class="red" style="margin: 0; font-size: 14px;">FINAL BILL / TAX INVOICE</h4>
            </div>

            <!-- Thick Horizontal Line -->
            <div style="border-top: 2px solid #282828; margin: 5px 0;"></div>

            <!-- Final Bill Reference Section -->
            <div class="patient_info" style="margin-bottom: 5px;">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
                    <tr>
                        <td style="text-align: left; width: 25%; padding: 4px 5px;">
                            <strong>ADM No.</strong> : <span class="red">{{ $ipd->ipd_no }}</span>
                        </td>
                        <td style="text-align: center; width: 25%; padding: 4px 5px;">
                            <strong>Bill No.</strong> : <span
                                class="red">{{ $billNumber ?? 'F-' . str_pad($ipd->id, 6, '0', STR_PAD_LEFT) . '/' . date('y') . '-' . (date('y') + 1) }}</span>
                        </td>
                        <td style="text-align: center; width: 25%; padding: 4px 5px;">
                            <strong>Bill Date</strong> :
                            {{ $dischargeDate ? \Carbon\Carbon::parse($dischargeDate)->format('d/m/Y') : (\Carbon\Carbon::parse($billDate)->format('d/m/Y')) }}
                        </td>
                        <td style="text-align: right; width: 25%; padding: 4px 5px;">
                            <strong>Bill Time</strong> :
                            @if(isset($dischargeTime) && $dischargeTime)
                                @php
                                    if (is_string($dischargeTime)) {
                                        $time = \Carbon\Carbon::createFromFormat('H:i:s', $dischargeTime);
                                        echo $time->format('H:i:s');
                                    } else {
                                        echo \Carbon\Carbon::parse($dischargeTime)->format('H:i:s');
                                    }
                                @endphp
                            @elseif($dischargeDate)
                                {{ \Carbon\Carbon::parse($dischargeDate)->format('H:i:s') }}
                            @else
                                {{ \Carbon\Carbon::parse($billDate)->format('H:i:s') }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Thick Horizontal Line -->
            <div style="border-top: 2px solid #282828; margin: 5px 0;"></div>


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
                                <td class="patient_value">
                                    {{ strtoupper($ipd->patient->patient_name ?? 'N/A') }}@php
                                        $tpaTag = $ipd->organisation->organisation_name
                                            ?? ($ipd->patient->organisation->organisation_name ?? null);
                                    @endphp
                                    @if($tpaTag)
                                    ({{ strtoupper($tpaTag) }})@endif</td>
                            </tr>
                            <tr>
                                <td class="patient_label">Address</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">
                                    @if($ipd->patient && $ipd->patient->address)
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
                                <td class="patient_label">Police Station</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">
                                    {{ ($ipd->patient && $ipd->patient->police_station) ? $ipd->patient->police_station : 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="patient_label">Phone No.</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">
                                    {{ $ipd->patient && $ipd->patient->mobileno ? $ipd->patient->mobileno : 'N/A' }}
                                </td>
                            </tr>
                            @if($ipd->doctor)
                                <tr>
                                    <td class="patient_label">Under Doctor</td>
                                    <td class="patient_colon">:</td>
                                    <td class="patient_value">{{ strtoupper($ipd->doctor->name ?? 'N/A') }}
                                        {{ strtoupper($ipd->doctor->surname ?? '') }}@if($ipd->doctor->registration_no)
                                        (REG-{{ $ipd->doctor->registration_no }})@endif</td>
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
                                <td class="patient_value">
                                    {{ ($ipd->patient && $ipd->patient->age) ? $ipd->patient->age . ' Y' : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="patient_label">Sex</td>
                                <td class="patient_colon">:</td>
                                <td class="patient_value">
                                    {{ $ipd->patient ? strtoupper($ipd->patient->gender ?? 'N/A') : 'N/A' }}</td>
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
                            @if($dischargeDate)
                                <tr>
                                    <td class="patient_label">Discharge Date</td>
                                    <td class="patient_colon">:</td>
                                    <td class="patient_value"><span
                                            class="red">{{ \Carbon\Carbon::parse($dischargeDate)->format('d/m/Y') }}</span>
                                    </td>
                                </tr>
                                @if(isset($dischargeTime) && $dischargeTime)
                                    <tr>
                                        <td class="patient_label">Discharge Time</td>
                                        <td class="patient_colon">:</td>
                                        <td class="patient_value"><span class="red">
                                                @php
                                                    // Handle time field - it might be a string like "14:30:00" or Carbon instance
                                                    if (is_string($dischargeTime)) {
                                                        $time = \Carbon\Carbon::createFromFormat('H:i:s', $dischargeTime);
                                                        echo $time->format('h:i A');
                                                    } else {
                                                        echo \Carbon\Carbon::parse($dischargeTime)->format('h:i A');
                                                    }
                                                @endphp
                                            </span></td>
                                    </tr>
                                @endif
                            @endif
                            @if($ipd->bedDetail)
                                <tr>
                                    <td class="patient_label">Bed No.</td>
                                    <td class="patient_colon">:</td>
                                    <td class="patient_value"><span class="red">{{ $ipd->bedDetail->name ?? 'N/A' }}.</span>
                                    </td>
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

        @if(!empty($isInsuranceFinalBill))
            @include('admin.billing.partials.ipd_insurance_info_block')
        @endif

        <!-- Package Charges (first when present) -->
        @if(!empty($useInsurancePackageLayout))
            @include('admin.billing.partials.ipd_insurance_package_section')
        @else
            @include('admin.billing.partials.ipd_package_charges_table')

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
                            <td>{{ $row->from_date ? \Carbon\Carbon::parse($row->from_date)->format('d/m/Y') : 'N/A' }} To
                                {{ $row->to_date ? \Carbon\Carbon::parse($row->to_date)->format('d/m/Y') : 'N/A' }}</td>
                            <td class="text-right">Rs. {{ number_format($row->bed_charge ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr style="font-weight: bold;">
                        <td colspan="3" class="text-right">Subtotal:</td>
                        <td class="text-right">Rs. {{ number_format($breakup['bed_charges'] ?? 0, 2) }}</td>
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
                        <td class="text-right">Rs. {{ number_format($breakup['ipd_charges'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        <!-- Pathology Section (Date wise details) -->
        @if(isset($investigationDatewise) && $investigationDatewise->where('type', 'pathology')->count() > 0)
            <div class="section-title">Pathology Details</div>
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
                        <td class="text-right">Rs.
                            {{ number_format(isset($pathologyTotal) && $pathologyTotal > 0 ? $pathologyTotal : ($breakup['pathology_charges'] ?? 0), 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @elseif(isset($pathologyTestNames) && count($pathologyTestNames) > 0)
            <div class="section-title">Pathology</div>
            <table class="charges-table">
                <thead>
                    <tr>
                        <th>Pathology</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ implode(', ', $pathologyTestNames) }}</td>
                        <td class="text-right">Rs.
                            {{ number_format(isset($pathologyTotal) && $pathologyTotal > 0 ? $pathologyTotal : ($breakup['pathology_charges'] ?? 0), 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        <!-- Radiology Section (Date wise details) -->
        @if(isset($investigationDatewise) && $investigationDatewise->where('type', 'radiology')->count() > 0)
            <div class="section-title">Radiology Details</div>
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
                        <td class="text-right">Rs.
                            {{ number_format(isset($radiologyTotal) && $radiologyTotal > 0 ? $radiologyTotal : ($breakup['radiology_charges'] ?? 0), 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @elseif(isset($radiologyTestNames) && count($radiologyTestNames) > 0)
            <div class="section-title">Radiology</div>
            <table class="charges-table">
                <thead>
                    <tr>
                        <th>Radiology</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ implode(', ', $radiologyTestNames) }}</td>
                        <td class="text-right">Rs.
                            {{ number_format(isset($radiologyTotal) && $radiologyTotal > 0 ? $radiologyTotal : ($breakup['radiology_charges'] ?? 0), 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        <!-- Doctor Visit Charges (grouped by visit type, then by doctor) -->
        @if(isset($doctorVisitGroupedByVisitType) && $doctorVisitGroupedByVisitType->count() > 0)
            <table class="charges-table">
                <thead>
                    <tr>
                        <th colspan="4">Doctor Visit Charges</th>
                    </tr>
                    <tr>
                        <th>Doctor</th>
                        <th>Visits</th>
                        <th>Date Range</th>
                        <th class="text-right">Amount (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctorVisitGroupedByVisitType as $visitTypeGroup)
                        <tr style="background-color: #f0f0f0; font-weight: bold;">
                            <td colspan="4">{{ $visitTypeGroup->visit_type_label ?? 'Other' }}</td>
                        </tr>
                        @foreach($visitTypeGroup->rows as $row)
                            <tr>
                                <td>
                                    {{ $row->doctor_label ?? 'N/A' }}
                                    @if(isset($row->rate_per_visit))
                                        @ {{ number_format($row->rate_per_visit, 2) }}
                                    @endif
                                </td>
                                <td>{{ $row->visit_count ?? 0 }} {{ ($row->visit_count ?? 0) == 1 ? 'Visit' : 'Visits' }}</td>
                                <td>
                                    {{ $row->from_date ? \Carbon\Carbon::parse($row->from_date)->format('d/m/Y') : 'N/A' }}
                                    To
                                    {{ $row->to_date ? \Carbon\Carbon::parse($row->to_date)->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="text-right">Rs. {{ number_format($row->total_amount ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr style="font-weight: bold;">
                        <td colspan="3" class="text-right">Subtotal:</td>
                        <td class="text-right">Rs. {{ number_format($breakup['doctor_visit_charges'] ?? 0, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        @endif {{-- end non-package layout --}}

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
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d-m-Y') }}
                            </td>
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
                                        <strong>Cheque Date:</strong>
                                        {{ \Carbon\Carbon::parse($payment->cheque_date)->format('d-m-Y') }}
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
        @if(!empty($isInsuranceFinalBill) && !empty($insuranceFinalSummary))
            @include('admin.billing.partials.ipd_insurance_final_summary')
        @else
        <div class="summary-section">
            <div class="section-title" style="margin-top: 0; margin-bottom: 8px;">Payment Summary</div>
            @if(($breakup['package_charges'] ?? 0) > 0)
                <div class="summary-row">
                    <span class="summary-label">Package Charges:</span>
                    <span class="summary-value">Rs. {{ number_format($breakup['package_charges'] ?? 0, 2) }}</span>
                </div>
            @endif
            @if(($breakup['bed_charges'] ?? 0) > 0)
                <div class="summary-row">
                    <span class="summary-label">Bed Charges:</span>
                    <span class="summary-value">Rs. {{ number_format($breakup['bed_charges'] ?? 0, 2) }}</span>
                </div>
            @endif
            <div class="summary-row">
                <span class="summary-label">IPD Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['ipd_charges'] ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Pathology Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['pathology_charges'] ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Radiology Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['radiology_charges'] ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Doctor Visit Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['doctor_visit_charges'] ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Charges:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['total_charges'] ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Payments:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['total_payments'] ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Outstanding Amount:</span>
                <span class="summary-value">Rs. {{ number_format($breakup['outstanding'] ?? 0, 2) }}</span>
            </div>
            @if(isset($mouDiscount) && $mouDiscount > 0)
                <div class="summary-row">
                    <span class="summary-label">MOU Discount (TPA/Insurance):</span>
                    <span class="summary-value">Rs. {{ number_format($mouDiscount, 2) }}</span>
                </div>
            @endif
            @if(isset($specialDiscount) && $specialDiscount > 0)
                <div class="summary-row">
                    <span class="summary-label">Special / Hospital Discount:</span>
                    <span class="summary-value">Rs. {{ number_format($specialDiscount, 2) }}</span>
                </div>
            @endif
            @if(isset($discount) && $discount > 0)
                <div class="summary-row">
                    <span class="summary-label">Total Discount:</span>
                    <span class="summary-value">Rs. {{ number_format($discount, 2) }}</span>
                </div>
            @endif
            @if(isset($duePatientPartyAmount) && $duePatientPartyAmount > 0)
                <div class="summary-row">
                    <span class="summary-label">Due on Account of Patient Party
                        @if(isset($ipd->duePatientPartyDoctor))
                            (Dr. {{ $ipd->duePatientPartyDoctor->name ?? '' }} {{ $ipd->duePatientPartyDoctor->surname ?? '' }})
                        @endif
                        @if(isset($ipd->due_patient_party_receipt_type) && $ipd->due_patient_party_receipt_type)
                            [{{ $ipd->due_patient_party_receipt_type }}]
                        @endif:
                    </span>
                    <span class="summary-value">Rs. {{ number_format($duePatientPartyAmount, 2) }}</span>
                </div>
            @endif
            <div class="summary-row">
                <span class="summary-label"><span class="red">Net Balance:</span></span>
                <span class="summary-value"><span class="red">Rs.
                        {{ number_format($balance ?? ($breakup['outstanding'] ?? 0), 2) }}</span></span>
            </div>
        </div>

        <!-- Amount in Words -->
        @if(isset($grandTotalInWords) || isset($totalAdvanceInWords) || isset($balanceInWords))
            <div class="words-section">
                <div class="section-title" style="margin-top: 0; margin-bottom: 8px;">Amount in Words</div>
                @if(isset($grandTotalInWords))
                    <div class="words-row">
                        <span class="words-label">Total Charges:</span>
                        <span class="words-value">{{ $grandTotalInWords }}</span>
                    </div>
                @endif
                @if(isset($totalAdvanceInWords))
                    <div class="words-row">
                        <span class="words-label">Total Payments:</span>
                        <span class="words-value">{{ $totalAdvanceInWords }}</span>
                    </div>
                @endif
                @if(isset($balanceInWords))
                    <div class="words-row">
                        <span class="words-label">Balance Amount:</span>
                        <span class="words-value">{{ $balanceInWords }}</span>
                    </div>
                @endif
                <div class="words-row">
                    <span class="words-label"><strong>Net Balance:</strong></span>
                    <span class="words-value"><strong>{{ $balanceInWords ?? 'Zero Rupees Only' }}</strong></span>
                </div>
            </div>
        @endif
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