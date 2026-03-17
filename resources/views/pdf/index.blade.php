<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $IpdPatient->patient['patient_name'] }}</title>
</head>
<style>
    body {
        font-family: 'Trebuchet MS', sans-serif;
    }

    .first_logo,
    .second_logo {
        font-size: 12px;
    }

    .first_logo,
    .second_logo,
    .about_info {
        width: 27%;
    }


    .about_info {
        font-size: 11px;
    }

    .wreeti {
        font-size: 10px;
    }

    .main_box {
        padding: 20px;
        max-width: 1199px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        margin: 20px auto;
        width: 210mm;
        height: 297mm;
        line-height: 7px;
    }

    .top_head {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .about_info {
        text-align: end;
        line-height: 0.5;
    }

    .heading h4 {
        text-transform: uppercase;
        text-align: center;
        margin: 10px auto 5px;
    }

    .red {
        color: #ff3405;
        font-weight: 700;
    }

    .admission_info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        /* padding: 0 12px; */
        font-size: 10px;
    }

    .patient_info {
        border: 2px solid #282828;
        display: flex;
        padding: 8px;
        margin-bottom: 2px;
        /* font-weight: 700; */
        font-size: 10px;
        padding-bottom: 0;
    }

    .patient_details {
        width: 50%;
    }

    .patient_items {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 6px;
    }

    .patient_head {
        width: 80px;
    }

    .patient_box {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .general_list li {
        margin-bottom: 6px;
    }

    .bottom_box {
        border-bottom: 2px dashed #282828;
        display: flex;
        align-items: end;
        justify-content: space-between;
        font-size: 10px;
    }

    .contact_box {
        width: 80%;
    }

    .sig_box {
        border-top: 2px solid #282828;
        width: 20%;
    }

    .blue {
        color: #010080;
        font-weight: 700;
    }

    .wreeti_items {
        font-weight: 700;
        /* margin-bottom: 8px; */
    }

    .wreeti_box {
        display: flex;
        align-items: center;
        gap: 50px;
    }

    .wreeti_sig {
        display: flex;
        align-items: end;
        gap: 10px;
        font-size: 10px;
        margin-top: 8px;
    }

    .wreeti_sig p {
        margin-bottom: 0;
    }

    .line {
        border: 1px solid #282828;
        width: 40%;
    }

    .text {
        font-size: 11px;
        line-height: 1.3;
    }

    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        #pdf-content {
            transform-origin: top left;
            page-break-inside: avoid;
        }

        * {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
    }

    .btn-primary {
        /* color: #fff; */
        /* background-color: #cb6ce6; */
        /* border-color: #cb6ce6; */
        font-size: 14px;
        font-weight: 600;
        padding: 8px 25px;
        border-radius: 5px;
        box-shadow: none;
    }

    .print_btn {
        margin: 40px 0;
        padding-bottom: 30px;
        text-align: center;
    }

    .btn-primary:hover {
        /* background-color: #b14cc1; */
        /* border-color: #b14cc1; */
        color: #fff;
    }

    .btn-primary:focus {
        /* background-color: #b14cc1; */
        /* border-color: #b14cc1; */
        color: #fff;
        box-shadow: 0 0 0 0.2rem rgba(177, 76, 193, 0.5);
    }

    .btn-primary {
        background-color: #cb6ce6;
        border-color: #cb6ce6;
        color: #fff;
        border: none;
    }
</style>

<body>



    <div class="main_box" id="pdf-content">

        <div class="top_head">
            <div class="second_logo" style="text-align: center;">
                @if (file_exists(public_path('assets/images/nabh-logo.png')))
                    <img src="{{ asset('assets/images/nabh-logo.png') }}" alt="LOGO2" style="height: auto; width:50%;">
                    <p>NABH/PESHCO-2018-3150/L-03
                @endif
            </div>
            <div class="first_logo" style="text-align: center;">
                <img src="{{ asset('assets/images/logo.webp') }}" alt="LOGO1" style="height: 50px">
                </p>
            </div>
            <div class="about_info">
                <p>{{ $hospital->name ?? '' }} </p>
                <p>{{ $hospital->address ?? '' }}</p>
                <p>Phone - {{ $hospital->phone ?? '' }}</p>
                <p>Ambulance :- 96747 77261</p>
                <p>E-mail: {{ $hospital->email ?? '' }}</p>
            </div>
        </div>

        <div class="heading">
            <h4 class="red"> ADMISSION FORM
            </h4>
        </div>

        <div class="admission_info">
            <div class="admission_item">
                <p>
                    <b>ADMISSION NO.</b> : <b class="red">{{ $IpdPatient->ipd['ipd_no'] }}</b>
                </p>
            </div>
            <div class="admission_item">
                <p>
                    <b>ADMISSION DATE.</b> : {{ \Carbon\Carbon::parse($IpdPatient->created_at)->format('d-m-Y') ?? '' }}
                </p>
            </div>
            <div class="admission_item">
                <p>
                    <b>ADMISSION TIME</b> : <span
                        class="red">{{ \Carbon\Carbon::parse($IpdPatient->ipd['date'])->format('h:i') ?? '' }}
                        {{ \Carbon\Carbon::parse($IpdPatient->ipd['date'])->format('A') ?? '' }}</span>
                </p>
            </div>
        </div>

        <div class="patient_info">

            <div class="patient_details">

                <div class="patient_items">
                    <div class="patient_head">
                        PATIENT NAME
                    </div>
                    :
                    <div class="patient_data">
                        <strong>
                            {{ $IpdPatient->patient['patient_name'] }}
                        </strong>
                    </div>
                </div>

                <div class="patient_items">
                    <div class="patient_head">
                        ADDRESS
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['address'] }}
                    </div>
                </div>

                <div class="patient_items">
                    <div class="patient_head">
                        RELIGION
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['religion'] ?? '' }}
                    </div>
                </div>
                <div class="patient_items">
                    <div class="patient_head">
                        URN
                    </div>
                    :
                    <div class="patient_data">

                    </div>
                </div>
                <div class="patient_items">
                    <div class="patient_head">
                        OCCUPATION
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['occupation'] ?? '' }}
                    </div>
                </div>

                <div class="patient_items">
                    <div class="patient_head">
                        GENDER
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['gender'] }}
                    </div>
                </div>


            </div>

            <div class="patient_details">
                <div class="patient_box">
                    <div class="patient_items">
                        <div class="patient_head">
                            AGE
                        </div>
                        :
                        <div class="patient_data">
                            {{ $IpdPatient->patient['age'] }} y
                        </div>
                    </div>


                </div>
                <div class="patient_items">
                    <div class="patient_head">
                        AREA
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient->areaName->name ?? '' }}
                    </div>
                </div>
                <div class="patient_items">
                    <div class="patient_head">
                        STATUS
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['marital_status'] }}
                    </div>
                </div>
                <div class="patient_items">
                    <div class="patient_head">
                        PHONE NO.
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['emergency_contact_no'] ?? '' }}
                    </div>
                </div>
                <div class="patient_items">
                    <div class="patient_head">
                        NATIONALITY
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['nationality'] ?? '' }}
                    </div>
                </div>

                <div class="patient_items">
                    <div class="patient_head">
                        POLICE STATION
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['police_station'] }}
                    </div>
                </div>

            </div>

        </div>

        <div class="patient_info">

            <div class="patient_details">

                <div class="patient_items">
                    <div class="patient_head">
                        W/O S/O D/O
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['guardian_name'] }}
                    </div>
                </div>

                <div class="patient_items">
                    <div class="patient_head">
                        PRIMARY PHONE NUMBER
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['mobileno'] }}
                    </div>
                </div>

            </div>

            <div class="patient_details">

                <div class="patient_items">
                    <div class="patient_head">
                        RELATION
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['guardian_relation'] ?? '' }}
                    </div>
                </div>

                <div class="patient_items">
                    <div class="patient_head">
                        ALTERNATE PHONE NUMBER
                        
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient['guardian_phone'] ?? '' }}
                        @if (!empty($IpdPatient->patient['guardian_phone']) && !empty($IpdPatient->patient['emergency_contact_no']))
                            ,
                        @endif
                        {{ $IpdPatient->patient['emergency_contact_no'] ?? '' }}
                    </div>
                </div>

            </div>

        </div>

        <div class="patient_info">

            <div class="patient_details">

                <div class="patient_box">

                    <div class="patient_items">
                        <div class="patient_head">
                            BED NO.
                        </div>
                        :
                        <div class="patient_data">
                            {{ $IpdPatient->ipd['bedDetail']['name'] ?? '' }}
                        </div>
                    </div>

                </div>

                <!-- <div class="patient_items">
                        <div class="patient_head">
                            BED Rate.
                        </div>
                        :
                        <div class="patient_data">
                            {{ $IpdPatient->ipd['bedDetail']['bedGroup']['bed_cost'] ?? '' }}
                        </div>
                    </div> -->

                <div class="patient_items">
                    <div class="patient_head">
                        UNDER CARE
                    </div>
                    :
                    <div class="patient_data">
                        {{-- Dr. {{ $IpdPatient->doctor['name'] }} {{ $IpdPatient->doctor['surname'] }}
                        ({{ $IpdPatient->doctor['registration_no'] }}) --}}
                        @php
                            $doctors = [];

                            if (!empty($IpdPatient->doctor)) {
                                $doctors[] =
                                    'Dr. ' .
                                    $IpdPatient->doctor['name'] .
                                    ' ' .
                                    $IpdPatient->doctor['surname'] .
                                    ' (' .
                                    $IpdPatient->doctor['registration_no'] .
                                    ')';
                            }
                            if (!empty($IpdPatient->doctor2)) {
                                $doctors[] =
                                    'Dr. ' .
                                    $IpdPatient->doctor2['name'] .
                                    ' ' .
                                    ($IpdPatient->doctor2['surname'] ?? '') .
                                    ' (' .
                                    ($IpdPatient->doctor2['registration_no'] ?? '') .
                                    ')';
                            }
                            if (!empty($IpdPatient->doctor3)) {
                                $doctors[] =
                                    '' .
                                    $IpdPatient->doctor3['name'] .
                                    ' ' .
                                    ($IpdPatient->doctor3['surname'] ?? '') .
                                    ' (' .
                                    ($IpdPatient->doctor3['registration_no'] ?? '') .
                                    ')';
                            }
                            if (!empty($IpdPatient->doctor4)) {
                                $doctors[] =
                                    'Dr. ' .
                                    $IpdPatient->doctor4['name'] .
                                    ' ' .
                                    ($IpdPatient->doctor4['surname'] ?? '') .
                                    ' (' .
                                    ($IpdPatient->doctor4['registration_no'] ?? '') .
                                    ')';
                            }
                        @endphp

                        {{ implode(', ', $doctors) }}
                    </div>
                </div>

            </div>

            <div class="patient_details">

                <div class="patient_items">
                    <div class="patient_head">
                        BED Rate.
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->ipd['bedDetail']['bedGroup']['bed_cost'] ?? '' }}
                    </div>
                </div>
                <div class="patient_items">
                    <div class="patient_head">
                        DEPARTMENT
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->ipd['bedDetail']['bedGroup']['name'] ?? '' }}
                    </div>
                </div>

            </div>


        </div>

        <div class="patient_info">

            <div class="patient_details">

                <div class="patient_items">
                    <div class="patient_head">
                        TPA
                    </div>
                    :
                    <div class="patient_data">
                        {{ $IpdPatient->patient->organisation['organisation_name'] ?? '' }}
                    </div>
                </div>

            </div>

            <div class="patient_details">

                <div class="patient_items">
                    <div class="patient_head">
                        COMPANY
                    </div>
                    :
                    <div class="patient_data">
                    </div>
                </div>

            </div>

        </div>

        <div class="text">
            <p> I do hereby give my full consent to undertake treatment of above
                Patient by Medical Management, Surgical Management, Instensive Care at
                this Nursing Home.</p>

            <p>I..............................................................................
                in my full sense hereby authorize Dr.
                .................................................................................
                & Such Associates, Doctors, Consultants, Nurses & Paramedical staff of
                Hospital to conduct all necessary Investigation, Medical / Surgical /
                Procedure on me/my patient under General or religional anaesthesia as
                deemed suitable for the same.</p>

            <p> I agree to pay all the bills and when submitted by hospital
                authority for my/my patient's treatment , and clear all the dues of
                Nursing Home
                incurred for the treatment of the patient before discharge /DORB.</p>

            <p> I shall not hold the institution, it's staff and the doctors
                responsible for any unwanted consequences during the course of medical
                treatment
                and the surgery administration of anaesthesia/drug or
                investigation/treatment etc..</p>

            <p>I have been fully explained the consequences of the procedures and
                their risks.</p>

            <h4 style="margin:0;">GENERAL NORMS FOR PATIENT ADMISSION:</h4>

            <ol class="general_list">
                <li>An ADVANCE PAYMENT shuld be made at the time of admission
                    accordingly
                    <br><br>
                    <span>a) Rs. 15000/- For GENERAL WARD</span>
                    <span>b) Rs. 20000/- For CABINS.</span>
                    <span>c) Rs. 30000/- For ICU.</span>
                </li>
                <li>A minimum of 80% to 85% amount of the surgery package must be paid
                    before the oparation .</li>
                <li>Patient should NOT bear any cash, valuables, mobile phone etc.
                    during his/her stay in the Nursing Home.</li>
                <li>Only two persons are allowed during visiting hours, childrens are
                    allowed only on Sunday evening.</li>
                <li>No foods from outside are allowed without prior permission.</li>
                <li>Patient availing cash less facility should submit His/Her
                    documents at the Insurance Desk.</li>
                <li>Patient Party should enquire about there outstanding payment
                    regularly from the respective counters, so that maximum outstanding
                    does
                    not exceeds Rs.10,000</li>
                <li>Shifting from ICU to Ward depends on bed availiabilty.</li>
                <li>PATIENT / PARTIES ID DOCUMENT IS MANDATORY . PLEASE PROVIDE US AT
                    THE EARLIEST.</li>
                <li>Whether any reimbusement for claim will be availed against any
                    insurance policy or health scheme in connection with the treatment
                    of the
                    patient. Yes [ &nbsp&nbsp ] No. [ &nbsp&nbsp ] If you don t disclose in the consent form
                    by ticking Yes/No, then Samaritan Clinic Pvt. Ltd. will not be
                    liable for any reimbursement insuarance</li>
                <li>In the event of a delay in body release due to unavoidable reasons, the deceased will be
                    respectfully transferred to the mortuary freezer after four hours (4 hours), subject to consent from
                    the next of kin.</li>
                <li>Emergency Icu Number 9674777261</li>
            </ol>
            <p>Witness Signature with relation</p>
        </div>

        <div class="bottom_box">
            <div class="contact_box">
                <p style="margin:0;">Contact No :</p>
                <p class="red">Full charge on the day of the admission. No charge if
                    the patient leaves before 11:30 am on the day of discharge.</p>
            </div>
            <div class="sig_box">
                <p>Signature of Patient / Party</p>
            </div>
        </div>

        <div class="heading">
            <h4 class="blue"> FOR OFFICE USE
            </h4>
        </div>

        <div class="admission_info">
            <div class="admission_item">
                <p>
                    <b>Date of Admission </b>:
                    {{ \Carbon\Carbon::parse($IpdPatient->ipd['date'])->format('d-m-Y') ?? '' }}
                </p>
            </div>
            <div class="admission_item">
                <p>
                    <b>ADMISSION TIME.</b> : {{ \Carbon\Carbon::parse($IpdPatient->ipd['date'])->format('h:i') ?? '' }}
                    {{ \Carbon\Carbon::parse($IpdPatient->ipd['date'])->format('A') ?? '' }}
                </p>
            </div>
            <div class="admission_item">
                <p>
                    <b>Admission No</b> : <span class="red">{{ $IpdPatient->ipd['ipd_no'] }}</span>
                </p>
            </div>
            <div class="admission_item">
                <p>
                    <b>BED No</b> : {{ $IpdPatient->ipd['bedDetail']['name'] ?? '' }}
                </p>
            </div>
            <div class="admission_item">
                <p>
                    <b>Under Care Doctor</b> :
                    @php
                        $doctors = [];

                        if (!empty($IpdPatient->doctor)) {
                            $doctors[] =
                                'Dr. ' .
                                $IpdPatient->doctor['name'] .
                                ' ' .
                                $IpdPatient->doctor['surname'] .
                                ' (' .
                                $IpdPatient->doctor['registration_no'] .
                                ')';
                        }
                        if (!empty($IpdPatient->doctor2)) {
                            $doctors[] =
                                'Dr. ' .
                                $IpdPatient->doctor2['name'] .
                                ' ' .
                                ($IpdPatient->doctor2['surname'] ?? '') .
                                ' (' .
                                ($IpdPatient->doctor2['registration_no'] ?? '') .
                                ')';
                        }
                        if (!empty($IpdPatient->doctor3)) {
                            $doctors[] =
                                '' .
                                $IpdPatient->doctor3['name'] .
                                ' ' .
                                ($IpdPatient->doctor3['surname'] ?? '') .
                                ' (' .
                                ($IpdPatient->doctor3['registration_no'] ?? '') .
                                ')';
                        }
                        if (!empty($IpdPatient->doctor4)) {
                            $doctors[] =
                                'Dr. ' .
                                $IpdPatient->doctor4['name'] .
                                ' ' .
                                ($IpdPatient->doctor4['surname'] ?? '') .
                                ' (' .
                                ($IpdPatient->doctor4['registration_no'] ?? '') .
                                ')';
                        }
                    @endphp

                    {{ implode(', ', $doctors) }}
                </p>
            </div>
        </div>

        <div class="wreeti">
            <div class="wreeti_box">
                <div class="wreeti_items">
                    <span class="red">VEGETARIAN : </span>
                    <span><input type="checkbox" name id></span>
                    <label for class="red">Yes</label>
                    <span><input type="checkbox" name id></span>
                    <label for class="red">NO</label>
                </div>
                <div class="wreeti_items">
                    <span class="red">Insurance / TPA : </span>

                </div>
            </div>
            <div class="wreeti_items">
                <span class="red">Patient's History : </span>
                <span><input type="checkbox" name id></span>
                <label for class="red">Diabetic</label>
                <span><input type="checkbox" name id></span>
                <label for class="red">HTN</label>
                <span><input type="checkbox" name id></span>
                <label for class="red">Asthma</label>
                <span><input type="checkbox" name id></span>
                <label for class="red">Cardiac</label>
            </div>
            <div class="wreeti_items">
                <span class="red">Allergies to Food and/or Drugs : </span>
                <span><input type="checkbox" name id></span>
                <label for class="red">Asprin / Ecosprin</label>
                <span><input type="checkbox" name id></span>
                <label for class="red">Clopitogril</label>
                <span><input type="checkbox" name id></span>
                <label for class="red">Others</label>
            </div>
        </div>

        <div class="wreeti_sig">
            <p><b>Signature of the Front Office Executive : </b>{{ $user->username }}</p>
            <div class="line">

            </div>

            <p><b>DATE : </b></p>
            <p><b>{{ \Carbon\Carbon::parse($IpdPatient->created_at)->format('d-m-Y') ?? '' }}</b></p>
        </div>


    </div>
    <div class="print_btn">
        <button class="btn btn-primary" onclick="printPdf()">Print</button>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function generatePdf() {
            return new Promise((resolve) => {
                const targetElement = document.getElementById("pdf-content");

                html2canvas(targetElement, {
                    scale: 2,
                    useCORS: true
                }).then(canvas => {
                    const {
                        jsPDF
                    } = window.jspdf;

                    const pdf = new jsPDF('p', 'mm', 'a4');

                    const imgData = canvas.toDataURL('image/png');

                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = pdf.internal.pageSize.getHeight();

                    const imgWidth = pdfWidth;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;

                    // Center vertically if smaller than A4
                    const y = imgHeight < pdfHeight ? (pdfHeight - imgHeight) / 2 : 0;

                    pdf.addImage(imgData, 'PNG', 0, y, imgWidth, imgHeight);

                    resolve(pdf);
                });
            });
        }

        function printPdf() {
            generatePdf().then(pdf => {
                const blob = pdf.output('bloburl');

                const printWindow = window.open(blob);
                printWindow.onload = () => {
                    printWindow.focus();
                    printWindow.print();
                };
            });
        }
    </script>
</body>

</html>