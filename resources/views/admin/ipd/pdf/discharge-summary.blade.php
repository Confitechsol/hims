<style>
    /* @page {
        size: A4;
        margin: 0;
    } */

    @page {
        size: A4;
        margin-top: 180px;
        margin-bottom: 40px;
        margin-left: 0px;
        margin-right: 0px;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        background-image: url("{{ public_path('/assets/images/body.webp') }}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100% 100%;
    }

    .main_box {
        widows: 100%;
        /* width: 210mm; */
        /* height: 297mm; */
        /* position: relative; */
        /* background-image: url("{{ public_path('/assets/images/body.webp') }}"); */
        /* background-position: center;
        background-repeat: no-repeat;
        background-size: 100% 100%; */
    }

    .header img,
    .footer img {
        width: 100%;
    }

    .section-title {
        text-align: center;
        font-size: 16px;
        text-transform: uppercase;
        margin: 10px 0;
        text-decoration: underline;
        color: #7d7c7c;
    }

    .table-box-border,
    .table-box {
        /* width: 100%; */
        border: 2px solid #9c9c9c;
        border-collapse: collapse;
        margin: 2px 0;
        width: calc(100% - 40px);
    }

    .table-box-border td,
    .table-box td {
        padding: 4px;
        vertical-align: top;
    }

    .label {
        width: 35%;
        font-weight: bold;
    }

    .colon {
        width: 5%;
        text-align: center;
    }

    .value {
        width: 60%;
    }

    .text-box {
        border: 2px solid #9c9c9c;
        padding: 8px;
        margin: 2px 0;
    }

    /* .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
    } */
    .header {
        position: fixed;
        top: -180px;
        left: 0;
        right: 0;
        height: 180px;
    }

    .footer {
        position: fixed;
        bottom: -40px;
        left: 0;
        right: 0;
        height: 40px;
    }

    .table-box-border,
    .table-box,
    .text-box {
        /* width: calc(100% - 40px); */
        margin: 0 20px;
    }

    .table-box {
        border: none;
        margin-bottom: 10px;
    }

    .table-box td {
        vertical-align: middle;
    }

    .discharge_no_label {
        width: auto;
        text-wrap: nowrap;
    }

    .discharge-no {
        width: 20%;
        color: red;
    }

    .page-header {
        text-transform: uppercase;
        font-size: 15px;
        vertical-align: middle;
        width: 50%;
    }

    .bar-code {
        width: 30%
    }

    .under_care {
        width: 25%;
    }

    .hidden {
        visibility: hidden;
    }



    /* .header,
    .footer {
        height: 120px;
    } */
</style>


@if ($showHeaderFooter)
    <div class="header">
        <img src="{{ public_path('assets/images/header.webp') }}">
    </div>
@endif

{{-- FOOTER --}}
@if ($showHeaderFooter)
    <div class="footer">
        <img src="{{ public_path('assets/images/footer.webp') }}">
    </div>
@endif

<div class="main_box">

    {{-- HEADER --}}
    {{-- <div class="header {{ !$showHeaderFooter ? 'hidden' : '' }}">
        <img src="{{ public_path('assets/images/header.webp') }}">
    </div> --}}

    {{-- TITLE --}}
    <div class="section-title">
        DISCHARGE SUMMARY & CERTIFICATE
    </div>

    {{-- PATIENT INFO --}}
    <table class="table-box">
        <tr>
            <td class="label discharge_no_label">DISCHARGE&nbsp;NO.</td>
            <td class="colon">:</td>
            <td class="value discharge-no">{{ $data->discharge_number }}</td>

            <td class="value page-header">{{ $data->reason_discharge }}</td>
            <td class="value bar-code">
                <img src="{{ $data->barcode }}" id="barcode" class="img-fluid rounded shadow-sm"
                    style="max-height:50px; height:50px; min-width:120px; width:250px; object-fit:cover;">
            </td>
        </tr>
    </table>
    <table class="table-box-border">
        <tr>
            <td class="label">Patient Name</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->patient_name }}</td>

            <td class="label">Sex</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->gender }}</td>
        </tr>

        <tr>
            <td class="label">Address</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->address }}</td>

            <td class="label">Age</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->age }}</td>
        </tr>

        <tr>
            <td class="label">Admission Date</td>
            <td class="colon">:</td>
            <td class="value">{{ \Carbon\Carbon::parse($data->admission_date)->format('d-m-Y') }}</td>
            

            <td class="label">Contact No.</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->phone }}</td>
        </tr>
        <tr>
            <td class="label">Admission Time</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->admit_time }}</td>

            <td class="label">Discharge Date</td>
            <td class="colon">:</td>
            <td class="value">
    {{ \Carbon\Carbon::parse($data->discharge_date)->format('d-m-Y') }}
</td>
        </tr>
        <tr>
            <td class="label">OT Date</td>
            <td class="colon">:</td>
            <td class="value">{{ \Carbon\Carbon::parse($data->ot_date)->format('d-m-Y') }}</td>

            <td class="label">Discharge Time</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->discharge_time }}</td>
        </tr>
        <tr>
            <td class="label">Admission No.</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->admission_no }}</td>

            <td class="label">Bed</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->bed }}</td>
        </tr>
    </table>
    <table class="table-box-border">
        <tr>
            <td class="label under_care">Under Care</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->under_care_dr }}</td>

            <td class="label under_care">Registration No.</td>
            <td class="colon">:</td>
            <td class="value">{{ $data->registration_no }}</td>
        </tr>
    </table>
    {{-- MEDICAL CONTENT --}}
    <div class="text-box">
        @if ($data->present_complaints != null || $data->present_complaints != '')
            <h4>Present Complaints</h4>
            {!! $data->present_complaints !!}
        @endif

        @if ($data->diagnosis != null || $data->diagnosis != '')
            <h4>Diagnosis</h4>
            {!! $data->diagnosis !!}
        @endif

        @if ($data->ot_note != null || $data->ot_note != '')
            <h4>Treatment / OT Note</h4>
            {!! $data->ot_note !!}
        @endif

        @if ($data->course_in_hospital != null || $data->course_in_hospital != '')
            <h4>Course in Hospital</h4>
            {!! $data->course_in_hospital !!}
        @endif

        @if ($data->discharge_medicines_html != null || $data->discharge_medicines_html != '')
            <h4>Discharge Advised Medicines</h4>
            {!! $data->discharge_medicines_html !!}
        @endif

        @if ($data->investigation != null || $data->investigation != '')
            <h4>Investigations</h4>
            {!! $data->investigation !!}
        @endif

        @if ($data->urgent_care != null || $data->urgent_care != '')
            <h4>Urgent Care Instructions</h4>
            {!! $data->urgent_care !!}
        @endif

        @if ($data->diet_advice != null || $data->diet_advice != '')
            <h4>Diet Advice</h4>
            {!! $data->diet_advice !!}
        @endif

        @if ($data->discharge_advice != null || $data->discharge_advice != '')
            <h4>Condition at Discharge</h4>
            {!! $data->discharge_advice !!}
        @endif
                                          
    </div>



                                      

    {{-- FOOTER --}}
    {{-- <div class="footer {{ !$showHeaderFooter ? 'hidden' : '' }}">
        <img src="{{ public_path('assets/images/footer.webp') }}">
    </div> --}}


</div>

<script>
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 300);
    };
</script>
