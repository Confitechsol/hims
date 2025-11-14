

<?php $__env->startSection('content'); ?>
    <style>
        .module_billing {
            border-radius: 8px;
            color: #fff;
            background-color: #ab00db;
            width: 100%;
            padding: 20px;
            box-shadow: 5px 5px 8px 0px #bbbbbb;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .input-group .input-group-addon {
            border: 1px solid #d2d6de;
            background-color: #fff;
            padding: 8px;
            border-bottom-right-radius: 5px !important;
            border-top-right-radius: 5px !important;
        }

        .about_patient {
            width: 130px;
        }

        .patient_data {
            width: 175px;
        }

        .patient_img {
            width: 45px;
        }

        .tabs-scroll-wrapper {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            /* smooth scroll on mobile */
        }

        .tabs-scroll-wrapper::-webkit-scrollbar {
            height: 6px;
            /* optional */
        }

        .tabs-scroll-wrapper::-webkit-scrollbar-thumb {
            background: #cfcfcf;
            border-radius: 3px;
            /* optional */
        }
    </style>

    <div class="p-4">

        <!-- tab start -->
        <div class="tabs-scroll-wrapper">
            <ul class="nav nav-tabs nav-bordered mb-3 flex-nowrap">
                <li class="nav-item">
                    <a href="#overview" data-bs-toggle="tab" aria-expanded="false"
                        class="d-flex align-items-center justify-space-between px-2 nav-link active bg-transparent"><i
                            class="fa-solid fa-expand text-primary pe-1"></i>
                        <span>Overview</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#nurse_notes" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-regular fa-square-caret-down text-primary pe-1"></i>
                        <span>Nurse Notes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#medication" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-suitcase-medical text-primary pe-1"></i>
                        <span>Medication</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#lab_investigation" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-flask text-primary pe-1"></i>
                        <span>Lab Investigation</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#operations" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-scissors text-primary pe-1"></i>
                        <span>Operations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#charges" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-circle-dollar-to-slot text-primary pe-1"></i>
                        <span>Charges</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#payments" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-hand-holding-dollar text-primary pe-1"></i>
                        <span>Payments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#live_consultation" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-hand-holding-dollar text-primary pe-1"></i>
                        <span>Live Consultation</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#timeline" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-timeline text-primary pe-1"></i>
                        <span>Timeline</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#treatment_history" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-laptop-medical text-primary pe-1"></i>
                        <span>Treatment History</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#prescription" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-file-prescription text-primary pe-1"></i>
                        <span>Prescription</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#bed_hoistory" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-bed-pulse text-primary pe-1"></i>
                        <span>Bed History</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#bed_issue" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-bed text-primary pe-1"></i>
                        <span>Bed Issue</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#vitals" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-heart-pulse text-primary pe-1"></i>
                        <span>Vitals</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- tab end -->

        <!-- tab content start -->
        <div class="tab-content">
            <div class="tab-pane show active" id="overview">

                <div class="row">
                    <div class="col-md-6">

                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Virat Kohli (13)
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-sm-flex position-relative z-0 overflow-hidden p-2">
                                    <!-- <img src="assets/img/icons/shape-01.svg" alt="img"
                                                                                                                                                                                                                                                                                                                                                                    class="z-n1 position-absolute end-0 top-0 d-none d-lg-flex"> -->
                                    <a href="javascript:void(0);"
                                        class="avatar avatar-xxxl patient-avatar me-2 flex-shrink-0">
                                        <img src="assets/img/patient.png" alt="product" class="rounded">
                                    </a>
                                    <div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-phone text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Phone :</h6>
                                                <p class="patient_data mb-0">8910245678</p>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-calendar-days text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Age :</h6>
                                                <p class="patient_data mb-0">22 Year 9 Month 5 Days (As Of Date 10/06/2025)
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-hands-holding-child text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Guardian Name :</h6>
                                                <p class="patient_data mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-mars-and-venus text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Gender :</h6>
                                                <p class="patient_data mb-0">Male</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-users-gear text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">TPA :</h6>
                                                <p class="patient_data mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-id-badge text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">TPA ID :</h6>
                                                <p class="patient_data mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-user-check text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">TPA Validity :</h6>
                                                <p class="patient_data mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-barcode text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Barcode :</h6>
                                                <p class="patient_data mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-qrcode text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">QR Code :</h6>
                                                <p class="patient_data mb-0">--</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                                                                                                                                                                                                                                                                                                                                        <div class="col-sm-5">

                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                        <div class="col-sm-7">

                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                        <div class="col-sm-5">

                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                        <div class="col-sm-7">

                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                    </div> -->
                                </div>
                                <hr>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="fa-solid fa-tag text-primary"></i></span>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="about_patient fs-13 fw-bold mb-1"> Known Allergies :</h6>
                                        <p class="patient_data mb-0">--</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="fa-solid fa-tag text-primary"></i></span>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="about_patient fs-13 fw-bold mb-1"> Findings :</h6>
                                        <p class="patient_data mb-0">--</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="fa-solid fa-tag text-primary"></i></span>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class=" fs-13 fw-bold mb-1"> Symptoms :</h6>
                                        <p class=" mb-0">
                                        <ul>
                                            <li><i class="fa-regular fa-circle-check text-primary"></i> Fever Chest Pain
                                            </li>
                                            <li><i class="fa-regular fa-circle-check text-primary"></i> Fever Fever</li>
                                        </ul>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Consultant
                                    Doctor
                                </h5>
                            </div>
                            <div class="card-body">

                                <div>
                                    <a href="#">
                                        <div class="d-flex align-items-center mb-3 gap-2">
                                            <div class="patient_img">
                                                <img src="assets/img/patient.png" alt="product" class="rounded">
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fs-13 fw-bold mb-1">Anirban Ghosh (D010)</h6>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                <hr>
                                <div>
                                    <a href="#">
                                        <div class="d-flex align-items-center mb-3 gap-2">
                                            <div class="patient_img">
                                                <img src="assets/img/patient.png" alt="product" class="rounded">
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fs-13 fw-bold mb-1">Anjali Rao (D011)</h6>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> TimeLine
                                </h5>
                            </div>
                            <div class="card-body">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> IPD Payment /
                                    Billing
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary">IPD Payment/Billing</h6>
                                        <span>92.31%</span>
                                        <div class="progress mb-3 mt-1" role="progressbar" aria-valuenow="92.31"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar bg-gradient" style="width: 90%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Pharmacy Payment/Billing</h6>
                                        <span>0%</span>
                                        <div class="progress mb-3 mt-1" role="progressbar" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Pathology Payment/Billing</h6>
                                        <span>0%</span>
                                        <div class="progress mb-3 mt-1" role="progressbar" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Radiology Payment/Billing</h6>
                                        <span>0%</span>
                                        <div class="progress mb-3 mt-1" role="progressbar" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Blood Bank Payment/Billing</h6>
                                        <span>0%</span>
                                        <div class="progress mb-3 mt-1" role="progressbar" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Ambulance Payment/Billing</h6>
                                        <span>0%</span>
                                        <div class="progress mb-0 mt-1" role="progressbar" aria-valuenow="0"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Medication
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Medicine Name</th>
                                                <th>Dose</th>
                                                <th>Time</th>
                                                <th>Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Lab
                                    Investigation
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Test Name</th>
                                                <th>Lab</th>
                                                <th>Sample Collected</th>
                                                <th>Expected Date</th>
                                                <th>Approved By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Lipid Profile
                                                    (Lipid Profile)
                                                </td>
                                                <td>Pathology</td>
                                                <td></td>
                                                <td>09/21/2025</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Operation
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Reference No</th>
                                                <th>Operation Date</th>
                                                <th>Operation Name</th>
                                                <th>Operation Category</th>
                                                <th>OT Technician</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Charges
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Charge Type</th>
                                                <th>Standard Charge (INR)</th>
                                                <th>Tax</th>
                                                <th>Applied Charge (INR)</th>
                                                <th>Amount (INR)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Doctor Fees
                                                </td>
                                                <td style="text-transform: capitalize;">OPD</td>
                                                <td class="text-right">50.00</td>
                                                <td class="text-right">(0.00%) 0.00</td>
                                                <td class="text-right">400.00</td>
                                                <td class="text-right">400.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Payment
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Transaction ID </th>
                                                <th>Date</th>
                                                <th>Note</th>
                                                <th>Payment Mode</th>
                                                <th>Paid Amount (INR)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-right">TRID83</td>
                                                <td class="text-right">10/13/2025 06:25 PM</td>
                                                <td class="text-right">SmartPay Transaction ID: 528612554379</td>
                                                <td class="text-right"><br> </td>
                                                <td class="text-right">20.00</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Live
                                    Consultation
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Consultation Title</th>
                                                <th>Date</th>
                                                <th>Created By</th>
                                                <th>Created For</th>
                                                <th>Patient</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="nurse_notes">


                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Nurse Notes
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        <div class="text-end d-flex">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary text-white ms-2 btn-md"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#add_appointment"><i
                                                                    class="ti ti-plus me-1"></i>Add New Note</a>
                                                        </div>
                                                        <!-- First Modal -->
                                                        <div class="modal fade" id="add_appointment" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                        <h5 class="modal-title"
                                                                            id="addSpecializationLabel">
                                                                            Add Nurse Note
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>

                                                                    </div>

                                                                    <div class="modal-body">

                                                                        <div class="row gy-3">

                                                                            <div class="col-md-6">
                                                                                <label for="appointment_date"
                                                                                    class="form-label">
                                                                                    Date <span
                                                                                        class="text-danger">*</span></label>
                                                                                <input type="date" name="date"
                                                                                    id="date" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="nurse"
                                                                                    class="form-label">Nurse</label>
                                                                                <select class="form-select"
                                                                                    id="nurse">
                                                                                    <option value="">Select
                                                                                    </option>
                                                                                    <option value="1">Anjali Sarma
                                                                                    </option>
                                                                                    <option value="2">Puja Roy
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="casualty"
                                                                                    class="form-label">Note</label>
                                                                                <textarea class="form-control" id="note" name='name'>
                                                                                            
                                                                                    </textarea>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="comment"
                                                                                    class="form-label">Comment</label>
                                                                                <textarea class="form-control" id="comment" name="comment"></textarea>
                                                                            </div>





                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save &
                                                                            Print</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Second Modal (nested) -->
                                                        <div class="modal fade" id="new_patient" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                                                <div class="modal-content">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                        <h5 class="modal-title">Add Patient</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <form>
                                                                            <div class="row align-items-center gy-3">
                                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">Name</label><span
                                                                                            class="text-danger">
                                                                                            *</span>
                                                                                        <input id="name"
                                                                                            name="name" placeholder=""
                                                                                            type="text"
                                                                                            class="form-control"
                                                                                            value=""
                                                                                            autocomplete="off">
                                                                                        <span class="text-danger"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">Guardian
                                                                                            Name</label>
                                                                                        <input type="text"
                                                                                            name="guardian_name"
                                                                                            placeholder="" value=""
                                                                                            class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-sm-12">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-3">
                                                                                            <div class="form-group">
                                                                                                <label class="form-label">
                                                                                                    Gender</label>
                                                                                                <select class="form-select"
                                                                                                    name="gender"
                                                                                                    id="addformgender"
                                                                                                    autocomplete="off">
                                                                                                    <option value="">
                                                                                                        Select</option>
                                                                                                    <option value="Male">
                                                                                                        Male</option>
                                                                                                    <option value="Female">
                                                                                                        Female</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-4">
                                                                                            <div class="form-group">
                                                                                                <label for="dob"
                                                                                                    class="form-label">Date
                                                                                                    Of
                                                                                                    Birth</label>
                                                                                                <input type="text"
                                                                                                    name="dob"
                                                                                                    id="birth_date"
                                                                                                    placeholder=""
                                                                                                    class="form-control date patient_dob">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-5"
                                                                                            id="calculate">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    class="form-label">Age
                                                                                                    (yy-mm-dd)
                                                                                                </label><small
                                                                                                    class="req">
                                                                                                    *</small>
                                                                                                <div
                                                                                                    style="clear: both;overflow: hidden;">
                                                                                                    <input type="text"
                                                                                                        placeholder="Year"
                                                                                                        name="age[year]"
                                                                                                        id="age_year"
                                                                                                        value=""
                                                                                                        class="form-control patient_age_year"
                                                                                                        style="width: 30%; float: left;">

                                                                                                    <input type="text"
                                                                                                        id="age_month"
                                                                                                        placeholder="Month"
                                                                                                        name="age[month]"
                                                                                                        value=""
                                                                                                        class="form-control patient_age_month"
                                                                                                        style="width: 36%;float: left; margin-left: 4px;">
                                                                                                    <input type="text"
                                                                                                        id="age_day"
                                                                                                        placeholder="Day"
                                                                                                        name="age[day]"
                                                                                                        value=""
                                                                                                        class="form-control patient_age_day"
                                                                                                        style="width: 26%;float: left; margin-left: 4px;">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div><!--./col-md-6-->
                                                                                <div class="col-md-6 col-sm-12">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-3">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    class="form-label">Blood
                                                                                                    Group</label>
                                                                                                <select name="blood_group"
                                                                                                    class="form-select">
                                                                                                    <option value="">
                                                                                                        Select</option>
                                                                                                    <option value="1">
                                                                                                        O+
                                                                                                    </option>
                                                                                                    <option value="2">
                                                                                                        A+
                                                                                                    </option>
                                                                                                    <option value="3">
                                                                                                        B+
                                                                                                    </option>
                                                                                                    <option value="4">
                                                                                                        AB+</option>
                                                                                                    <option value="5">
                                                                                                        O-
                                                                                                    </option>
                                                                                                    <option value="6">
                                                                                                        AB-</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-3">
                                                                                            <div class="form-group">
                                                                                                <label for="pwd"
                                                                                                    class="form-label">Marital
                                                                                                    Status</label>
                                                                                                <select
                                                                                                    name="marital_status"
                                                                                                    class="form-select"
                                                                                                    autocomplete="off">
                                                                                                    <option value="">
                                                                                                        Select</option>
                                                                                                    <option value="Single">
                                                                                                        Single</option>
                                                                                                    <option
                                                                                                        value="Married">
                                                                                                        Married</option>
                                                                                                    <option
                                                                                                        value="Widowed">
                                                                                                        Widowed</option>
                                                                                                    <option
                                                                                                        value="Separated">
                                                                                                        Separated
                                                                                                    </option>
                                                                                                    <option
                                                                                                        value="Not Specified">
                                                                                                        Not
                                                                                                        Specified
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-sm-6">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    for="exampleInputFile"
                                                                                                    class="form-label">
                                                                                                    Patient Photo
                                                                                                </label>
                                                                                                <div>
                                                                                                    <div
                                                                                                        class="d-flex border rounded position-relative p-1 mb-3 text-center align-items-center">
                                                                                                        <span
                                                                                                            class="avatar avatar-sm bg-primary text-white me-2">
                                                                                                            <i
                                                                                                                class="ti ti-upload fs-16"></i>
                                                                                                        </span>
                                                                                                        <p class="mb-0">
                                                                                                            Drop files
                                                                                                            here</p>
                                                                                                        <input
                                                                                                            type="file"
                                                                                                            class="position-absolute top-0 start-0 opacity-0 w-100 h-100">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <span
                                                                                                    class="text-danger"></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div><!--./col-md-6-->
                                                                                <div class="col-sm-3">
                                                                                    <div class="form-group">
                                                                                        <label for="pwd"
                                                                                            class="form-label">Phone</label>
                                                                                        <input id="number"
                                                                                            autocomplete="off"
                                                                                            name="mobileno" type="text"
                                                                                            placeholder=""
                                                                                            class="form-control"
                                                                                            value="">
                                                                                        <span class="text-danger"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">Email</label>
                                                                                        <input type="text"
                                                                                            placeholder=""
                                                                                            id="addformemail"
                                                                                            value="" name="email"
                                                                                            class="form-control">
                                                                                        <span class="text-danger"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="address"
                                                                                            class="form-label">Address</label>
                                                                                        <input name="address"
                                                                                            placeholder=""
                                                                                            class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="pwd"
                                                                                            class="form-label">Remarks</label>
                                                                                        <textarea name="note" id="note" class="form-control"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="email"
                                                                                            class="form-label">Any Known
                                                                                            Allergies</label>
                                                                                        <textarea name="known_allergies" id="" placeholder="" class="form-control"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label for="tpa"
                                                                                            class="form-label">TPA</label>
                                                                                        <select class="form-select"
                                                                                            name="organisation_id">
                                                                                            <option value="">Select
                                                                                            </option>
                                                                                            <option value="5">MedoLogi
                                                                                                TPA Pvt. Ltd.
                                                                                            </option>
                                                                                            <option value="4">Vidal
                                                                                                Health TPA </option>
                                                                                            <option value="3">
                                                                                                Paramount
                                                                                                Health Services
                                                                                            </option>
                                                                                            <option value="2">Raksha
                                                                                                TPA
                                                                                                Pvt. Ltd. </option>
                                                                                            <option value="1">
                                                                                                MediAssist
                                                                                                TPA Pvt. Ltd.
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label for="insurance_id"
                                                                                            class="form-label">TPA
                                                                                            ID</label>
                                                                                        <input name="insurance_id"
                                                                                            placeholder=""
                                                                                            class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label for="validity"
                                                                                            class="form-label">TPA
                                                                                            Validity</label>
                                                                                        <input name="validity"
                                                                                            placeholder=""
                                                                                            class="form-control date">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">National
                                                                                            Identification
                                                                                            Number</label>
                                                                                        <input name="identification_number"
                                                                                            placeholder=""
                                                                                            class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label for="height"
                                                                                            class="form-label">Height</label>
                                                                                        <input type="text"
                                                                                            id="height" name="height"
                                                                                            class="form-control"
                                                                                            placeholder="Height (cm)"
                                                                                            value="">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label for="weight"
                                                                                            class="form-label">Weight</label>
                                                                                        <input type="text"
                                                                                            id="weight" name="weight"
                                                                                            class="form-control"
                                                                                            placeholder="Weight (kg)"
                                                                                            value="">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label for="temperature"
                                                                                            class="form-label">Temperature</label>
                                                                                        <input type="text"
                                                                                            id="temperature"
                                                                                            name="temperature"
                                                                                            class="form-control"
                                                                                            placeholder="Temperature (°C)"
                                                                                            value="">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label for="screen_tb"
                                                                                            class="form-label">Screen
                                                                                            TB</label>
                                                                                        <select name="screen_tb"
                                                                                            id="screen_tb"
                                                                                            class="form-select">
                                                                                            <option value="">Select
                                                                                            </option>
                                                                                            <option value="Yes">Yes
                                                                                            </option>
                                                                                            <option value="No">No
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Nurse</th>
                                                                <th>Date</th>
                                                                <th>Time</th>
                                                                <th>Note</th>
                                                                <th>Comment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <h6 class="fs-14 mb-1">Anjali Sharma ( NUR004 )</h6>
                                                                </td>
                                                                <td>09/17/2025</td>
                                                                <td>12:49 PM </td>
                                                                <td>Every thing is normal</td>
                                                                <td>Take rest</td>
                                                                <td>
                                                                    <div class="d-flex gap-2">

                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                            <i class="fa-solid fa-pencil"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Add Prescription"></i></a>
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                            <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                                title="Show"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="medication">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Timeline
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        <div class="text-end d-flex">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary text-white ms-2 btn-md"
                                                                data-bs-toggle="modal" data-bs-target="#add_medication"><i
                                                                    class="ti ti-plus me-1"></i>Add Medication Dose</a>
                                                        </div>
                                                        <!-- First Modal -->
                                                        <div class="modal fade" id="add_medication" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content ">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                        <h5 class="modal-title"
                                                                            id="addSpecializationLabel">
                                                                            Add Medication Dose
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>

                                                                    </div>

                                                                    <div class="modal-body">

                                                                        <div class="row gy-3">

                                                                            <div class="col-md-6">
                                                                                <label for="date"
                                                                                    class="form-label">Date
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="date" name="date"
                                                                                    id="date" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="time"
                                                                                    class="form-label">Time
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="time" name="time"
                                                                                    id="time" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="medi_cat"
                                                                                    class="form-label">Medicine Category
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <select name="medi_cat" id="med_cat"
                                                                                    class="form-select"
                                                                                    data-placeholder="Enter Patient Name or Id…">
                                                                                    <option value="0">Select</option>
                                                                                    <option value="1">Antibiotic
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="med_name"
                                                                                    class="form-label">Medicine Name
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <select name="med_name" id="med_name"
                                                                                    class="form-select"
                                                                                    data-placeholder="Enter Patient Name or Id…">
                                                                                    <option value="0">Select</option>
                                                                                    <option value="1">Paracetamol
                                                                                        500mg
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="dosage"
                                                                                    class="form-label">Dosage
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <select name="dosage" id="dosage"
                                                                                    class="form-select"
                                                                                    data-placeholder="Enter Patient Name or Id…">
                                                                                    <option value="0">Select</option>
                                                                                    <option value="1">1 Tablet
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="remark"
                                                                                    class="form-label">Remarks
                                                                                </label>
                                                                                <textarea name="remark" id="remark" class="form-control"></textarea>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Medication Name</th>
                                                                <th>Dose1</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <h6 class="fs-14 mb-1">10/08/2025
                                                                        (Wed)</h6>
                                                                </td>
                                                                <td>Paracetamol 500mg</td>
                                                                <td> Time: 03:30 PM
                                                                    1–2 Tablet Created By: Super Admin (9001)
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill">
                                                                            <i class="ti ti-pencil"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Show"></i></a>
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                            <i class="ti ti-trash"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Show"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="lab_investigation">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Lab
                                    Investigation
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Test Name</th>
                                                                <th>Lab</th>
                                                                <th>Sample Collected</th>
                                                                <th>Expected Date</th>
                                                                <th>Approved By</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <h6 class="fs-14 mb-1">Lipid Profile
                                                                        (Lipid Profile)</h6>
                                                                </td>
                                                                <td>Pathology</td>
                                                                <td>Pathology Center :</td>
                                                                <td>09/21/2025</td>
                                                                <td></td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                            <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                                title="Show"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="operations">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Operations
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Reference No</th>
                                                                <th>Operation Date</th>
                                                                <th>Operation Name</th>
                                                                <th>Operation Category</th>
                                                                <th>OT Technician</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <h6 class="fs-14 mb-1"></h6>
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>
                                                                    <div class="d-flex gap-2">

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="charges">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Charges</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        <div class="text-end d-flex">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary text-white ms-2 btn-md"
                                                                data-bs-toggle="modal" data-bs-target="#add_charges"><i
                                                                    class="ti ti-plus me-1"></i>Add Charges</a>
                                                        </div>
                                                        <!-- First Modal -->
                                                        <div class="modal fade" id="add_charges" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div
                                                                class="modal-dialog modal-dialog-centered modal-full-width">
                                                                <div class="modal-content">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                        <div class="row w-100 align-items-center">
                                                                            <div class="col-md-8">
                                                                                <h4 class="modal-title">Add Charges</h4>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox" value="1"
                                                                                        id="is_tpa" name="is_tpa"
                                                                                        onclick="reset_value()">
                                                                                    <label
                                                                                        class="form-check-label text-white"
                                                                                        for="">
                                                                                        Apply TPA </label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 text-end">
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"></button>
                                                                            </div>
                                                                        </div>


                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <div class="row ">
                                                                            <div class="col-lg-12 col-md-12 col-sm-12">

                                                                                <div class="row ptt10">
                                                                                    <div class="col-sm-2">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                class="form-label displayblock">Charge
                                                                                                Type<small class="req">
                                                                                                    *</small></label>
                                                                                            <select name="charge_type"
                                                                                                id="add_charge_type"
                                                                                                class="form-control charge_type select2 reset_value select2-hidden-accessible"
                                                                                                style="width: 100%"
                                                                                                tabindex="-1"
                                                                                                aria-hidden="true">
                                                                                                <option value="">
                                                                                                    Select
                                                                                                </option>
                                                                                                <option value="1">
                                                                                                    Appointment </option>

                                                                                            </select>

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                class="form-label">Charge
                                                                                                Category</label><small
                                                                                                class="req"> *</small>
                                                                                            <select name="charge_category2"
                                                                                                id="charge_category2"
                                                                                                style="width: 100%"
                                                                                                class="form-control select2 charge_category2 reset_value select2-hidden-accessible"
                                                                                                tabindex="-1"
                                                                                                aria-hidden="true">
                                                                                                <option value="">
                                                                                                    Select
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                class="form-label">Charge
                                                                                                Name</label><small
                                                                                                class="req"> *</small>
                                                                                            <select name="charge_id"
                                                                                                id="charge_id"
                                                                                                style="width: 100%"
                                                                                                class="form-control addcharge  select2 reset_value select2-hidden-accessible"
                                                                                                tabindex="-1"
                                                                                                aria-hidden="true">
                                                                                                <option value="">
                                                                                                    Select
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                class="form-label">Standard
                                                                                                Charge
                                                                                                (INR)</label>
                                                                                            <input type="text"
                                                                                                readonly=""
                                                                                                name="standard_charge"
                                                                                                id="addstandard_charge"
                                                                                                class="form-control reset_value standard_charge"
                                                                                                value="">
                                                                                            <span
                                                                                                class="text-danger"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <div class="form-group">
                                                                                            <label class="form-label">TPA
                                                                                                Charge (INR)</label>
                                                                                            <input type="text"
                                                                                                readonly=""
                                                                                                name="schedule_charge"
                                                                                                id="addscd_charge"
                                                                                                placeholder=""
                                                                                                class="form-control reset_value schedule_charge"
                                                                                                value="">
                                                                                            <span
                                                                                                class="text-danger"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-2">
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                class="form-label">Qty</label><small
                                                                                                class="req"> *</small>
                                                                                            <input type="text"
                                                                                                name="qty"
                                                                                                id="qty"
                                                                                                class="form-control qty"
                                                                                                value="1">
                                                                                            <span
                                                                                                class="text-danger"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row pt-3">
                                                                                    <div class="col-sm-5">
                                                                                        <table class="printablea4">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <th width="40%">
                                                                                                        Total
                                                                                                        (INR)</th>
                                                                                                    <td width="60%"
                                                                                                        colspan="2"
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            placeholder="Total"
                                                                                                            value="0"
                                                                                                            name="apply_charge"
                                                                                                            id="apply_charge"
                                                                                                            style="width: 30%; float: right"
                                                                                                            class="form-control total apply_charge_add_charge"
                                                                                                            readonly="">
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>Discount Percentage
                                                                                                        (INR)</th>
                                                                                                    <td
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <h4
                                                                                                            style="float: right;font-size: 12px; padding-left: 5px;">
                                                                                                            %</h4>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            value="0"
                                                                                                            placeholder="Discount Percentage"
                                                                                                            name="discount_percentage"
                                                                                                            id="discount_percentage_add_charge"
                                                                                                            class="form-control discount_percentage_add_charge"
                                                                                                            style="width: 70%; float: right;font-size: 12px;">
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            placeholder="Discount Percentage"
                                                                                                            name="discount_percentage_amount"
                                                                                                            value="0"
                                                                                                            id="discount_percentage_amount"
                                                                                                            style="width: 50%; float: right"
                                                                                                            class="form-control discount_percentage_amount"
                                                                                                            readonly="">
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>Tax (INR)</th>
                                                                                                    <td
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <h4
                                                                                                            style="float: right;font-size: 12px; padding-left: 5px;">
                                                                                                            %</h4>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            placeholder="Tax"
                                                                                                            name="charge_tax"
                                                                                                            id="charge_tax"
                                                                                                            class="form-control charge_tax"
                                                                                                            readonly=""
                                                                                                            style="width: 70%; float: right;font-size: 12px;">
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            placeholder="Tax"
                                                                                                            name="tax"
                                                                                                            value="0"
                                                                                                            id="tax"
                                                                                                            style="width: 50%; float: right"
                                                                                                            class="form-control tax"
                                                                                                            readonly="">
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>Net Amount (INR)
                                                                                                    </th>
                                                                                                    <td colspan="2"
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            placeholder="Net Amount"
                                                                                                            value="0"
                                                                                                            name="amount"
                                                                                                            id="final_amount"
                                                                                                            style="width: 30%; float: right"
                                                                                                            class="form-control net_amount"
                                                                                                            readonly="">
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12">
                                                                                                <div class="form-group">
                                                                                                    <label for=""
                                                                                                        class="form-label">Charge
                                                                                                        Note</label>
                                                                                                    <textarea name="note" id="edit_note" rows="3" class="form-control edit_charge_note"></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div><!--./col-sm-6-->
                                                                                    <div class="col-sm-3">
                                                                                        <div class="form-group mb-2">
                                                                                            <label for=""
                                                                                                class="form-label">Date</label>
                                                                                            <small class="req">
                                                                                                *</small>
                                                                                            <input id="charge_date"
                                                                                                name="date"
                                                                                                placeholder=""
                                                                                                type="text"
                                                                                                class="form-control datetime">
                                                                                        </div>
                                                                                        <button type="submit"
                                                                                            data-loading-text="Processing..."
                                                                                            name="charge_data"
                                                                                            value="add"
                                                                                            class="btn btn-primary pull-right"><i
                                                                                                class="fa fa-check-circle"></i>
                                                                                            Add</button>
                                                                                    </div>
                                                                                </div><!--./row-->
                                                                                <hr>
                                                                            </div>
                                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                                                <table
                                                                                    class="table table-striped table-bordered table-hover">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <th>Date</th>
                                                                                            <th>Charge Type</th>
                                                                                            <th>Charge Category</th>
                                                                                            <th>Charge Name <br> Charge Note
                                                                                            </th>
                                                                                            <th class="text-right">
                                                                                                Standard
                                                                                                Charge (INR)</th>
                                                                                            <th class="text-right">TPA
                                                                                                Charge (INR)</th>
                                                                                            <th class="text-right">Qty
                                                                                            </th>
                                                                                            <th class="text-right">Total
                                                                                                (INR)</th>
                                                                                            <th class="text-right">
                                                                                                Discount
                                                                                                (INR)</th>
                                                                                            <th class="text-right">Tax
                                                                                                (INR)
                                                                                            </th>
                                                                                            <th class="text-right">Net
                                                                                                Amount (INR)</th>
                                                                                            <th class="text-right">Action
                                                                                            </th>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                    <tbody id="preview_charges">

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">

                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Charge Name / Charge Note</th>
                                                                <th>Charge Type</th>
                                                                <th>Charge Category</th>
                                                                <th>Qty</th>
                                                                <th>Standard Charge (INR)</th>
                                                                <th>Applied Charge (INR)</th>
                                                                <th>TPA Charge (INR)</th>
                                                                <th>Discount</th>
                                                                <th>Tax</th>
                                                                <th>Amount (INR)</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    09/17/2025 12:49 PM
                                                                </td>
                                                                <td>Doctor Fees</td>
                                                                <td>OPD</td>
                                                                <td>OPD Doctor Fees </td>
                                                                <td>1</td>
                                                                <td>50.00</td>
                                                                <td>400.00</td>
                                                                <td>0.00</td>
                                                                <td>0.00 (0.00%)</td>
                                                                <td>0.00 (0.00%)</td>
                                                                <td>400.00</td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-primary rounded-pill">
                                                                            <i class="fa-solid fa-print"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Print"></i></a>
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                            <i class="ti ti-pencil"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Edit"></i></a>

                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                            <i class="ti ti-trash"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Delete"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="payments">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Payments
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        <div class="text-end d-flex">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary text-white ms-2 btn-md"
                                                                data-bs-toggle="modal" data-bs-target="#add_payment"><i
                                                                    class="ti ti-plus me-1"></i>Add Payment</a>
                                                        </div>
                                                        <!-- First Modal -->
                                                        <div class="modal fade" id="add_payment" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content ">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                        <h5 class="modal-title"
                                                                            id="addSpecializationLabel">
                                                                            Add Payment
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>

                                                                    </div>

                                                                    <div class="modal-body">

                                                                        <div class="row gy-3">

                                                                            <div class="col-md-6">
                                                                                <label for="date"
                                                                                    class="form-label">Date
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="date" name="date"
                                                                                    id="date" class="form-control"
                                                                                    required>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="amount"
                                                                                    class="form-label">Amount (INR)
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="text" name="amount"
                                                                                    id="amount" class="form-control"
                                                                                    required>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="payment_mode"
                                                                                    class="form-label">Payment Mode

                                                                                </label>
                                                                                <select name="payment_mode"
                                                                                    id="payment_mode"
                                                                                    class="form-select"
                                                                                    data-placeholder="Enter Patient Name or Id…">
                                                                                    <option value="0">Select</option>
                                                                                    <option value="1">Cash</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="note"
                                                                                    class="form-label">Note
                                                                                </label>
                                                                                <textarea name="note" id="note" class="form-control"></textarea>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Transaction ID</th>
                                                                <th>Date</th>
                                                                <th>Note</th>
                                                                <th>Payment Mode</th>
                                                                <th>Paid Amount (INR)</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    TRID83
                                                                </td>
                                                                <td>10/13/2025 06:25 PM </td>
                                                                <td> Time:SmartPay Transaction ID: 528612554379
                                                                </td>
                                                                <td></td>
                                                                <td>20.00</td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-primary rounded-pill">
                                                                            <i class="fa-solid fa-print"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Print"></i></a>
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill">
                                                                            <i class="ti ti-pencil"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Show"></i></a>
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                            <i class="ti ti-trash"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Delete"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="live_consultation">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Live
                                    Consultation
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Consultation Title</th>
                                                                <th>Date</th>
                                                                <th>Created By</th>
                                                                <th>Created For</th>
                                                                <th>Patient</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <h6 class="fs-14 mb-1"></h6>
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>
                                                                    <div class="d-flex gap-2">

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="timeline">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Timeline
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        <div class="text-end d-flex">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary text-white ms-2 btn-md"
                                                                data-bs-toggle="modal" data-bs-target="#add_timeline"><i
                                                                    class="ti ti-plus me-1"></i>Add Timeline</a>
                                                        </div>
                                                        <!-- First Modal -->
                                                        <div class="modal fade" id="add_timeline" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                        <h5 class="modal-title"
                                                                            id="addSpecializationLabel">
                                                                            Add Timeline
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>

                                                                    </div>

                                                                    <div class="modal-body">

                                                                        <div class="row gy-3">

                                                                            <div class="col-md-12">
                                                                                <label for="title"
                                                                                    class="form-label">Title
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="text" name="title"
                                                                                    id="title" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="date"
                                                                                    class="form-label">Date
                                                                                    <span class="text-danger">*</span>
                                                                                </label>
                                                                                <input type="date" name="date"
                                                                                    id="date" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="description"
                                                                                    class="form-label">Description
                                                                                </label>
                                                                                <textarea name="description" id="description" class="form-control"></textarea>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="attch_doc"
                                                                                    class="form-label">Attach Document
                                                                                </label>
                                                                                <input type="file" name="attch_doc"
                                                                                    id="date" class="form-control">
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="visible_person"
                                                                                    class="form-check-label">Visible to
                                                                                    this
                                                                                    person
                                                                                </label>
                                                                                <input type="checkbox"
                                                                                    name="visible_person" id="date"
                                                                                    class="form-check-input">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>OPD No</th>
                                                                <th>Case ID</th>
                                                                <th>Appointment Date</th>
                                                                <th>Title</th>
                                                                <th>Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <h6 class="fs-14 mb-1"><a href="#"
                                                                            class="fw-semibold">OPDN14</a></h6>
                                                                </td>
                                                                <td>18</td>
                                                                <td> 09/17/2025 12:49 PM</td>
                                                                <td>xyz
                                                                </td>
                                                                <td>09/17/2025 12:49 PM
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                            <i class="ti ti-menu"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Show"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="treatment_history">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Treatment
                                    History
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>OPD No</th>
                                                                <th>Case ID</th>
                                                                <th>Appointment Date</th>
                                                                <th>Symptoms</th>
                                                                <th>Consultant</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <h6 class="fs-14 mb-1"><a href="#"
                                                                            class="fw-semibold">OPDN14</a></h6>
                                                                </td>
                                                                <td>18</td>
                                                                <td> 09/17/2025 12:49 PM</td>
                                                                <td>Fever
                                                                </td>
                                                                <td>Anjali Rao (D011)
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                            <i class="ti ti-menu"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Show"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="vitals">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Vitals
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">

                                                    </div>
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        <div class="text-end d-flex">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary text-white ms-2 btn-md"
                                                                data-bs-toggle="modal" data-bs-target="#add_vital"><i
                                                                    class="ti ti-plus me-1"></i>Add Vitals</a>
                                                        </div>
                                                        <!-- First Modal -->
                                                        <div class="modal fade" id="add_vital" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                        <h5 class="modal-title"
                                                                            id="addSpecializationLabel">
                                                                            Add Vitals
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>

                                                                    </div>

                                                                    <div class="modal-body">



                                                                        <div id="vitalFields">
                                                                            <div class="row gy-3 vital-row mb-2">
                                                                                <!-- Vital Name -->
                                                                                <div class="col-md-4">
                                                                                    <label for="vital_name"
                                                                                        class="form-label">Vital
                                                                                        Name</label>
                                                                                    <select class="form-select"
                                                                                        name="vital_name[]"
                                                                                        id="vital_name">
                                                                                        <option value="">Select
                                                                                        </option>
                                                                                        <option value="1">1</option>
                                                                                    </select>
                                                                                </div>
                                                                                <!-- Vital Value -->
                                                                                <div class="col-md-3">
                                                                                    <label for="vital_value"
                                                                                        class="form-label">Vital
                                                                                        Value</label>
                                                                                    <input type="text"
                                                                                        name="vital_value[]"
                                                                                        id="vital_value"
                                                                                        class="form-control" />
                                                                                </div>
                                                                                <!-- Date -->
                                                                                <div class="col-md-4">
                                                                                    <label for="date"
                                                                                        class="form-label">Date</label>
                                                                                    <input type="date" name="date[]"
                                                                                        id="date"
                                                                                        class="form-control" />
                                                                                </div>
                                                                                <!-- Remove -->
                                                                                <div
                                                                                    class="col-md-1 d-flex align-items-end">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger remove-btn"
                                                                                        style="display:none;">
                                                                                        <i class="ti ti-trash"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            <button type="button"
                                                                                class="btn btn-primary" id="addBtn">
                                                                                <i class="ti ti-plus"></i> Add Operation
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>OPD No</th>
                                                                <th>Case ID</th>
                                                                <th>Appointment Date</th>
                                                                <th>Vital Name</th>
                                                                <th>Vital Value</th>
                                                                <th>Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <h6 class="fs-14 mb-1"><a href="#"
                                                                            class="fw-semibold">OPDN14</a></h6>
                                                                </td>
                                                                <td>18</td>
                                                                <td> 09/17/2025 12:49 PM</td>
                                                                <td>xyz
                                                                <td>3
                                                                </td>
                                                                <td>09/17/2025 12:49 PM
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                            <i class="ti ti-menu"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Show"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Table end -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end -->

        </div>
    </div>
    <!-- tab content end -->
    </div>
    <!-- Chart JS -->
    <script src="assets/plugins/chartjs/chart.min.js"></script>
    <script src="assets/plugins/chartjs/chart-data.js"></script>

    <script>
        $(document).ready(function() {
            // Re-initialize Select2 every time the modal is shown
            $('#add_medication').on('shown.bs.modal', function() {
                $('#med_cat, #med_name, #dosage').select2({
                    width: '100%',
                    placeholder: 'Select',
                    allowClear: true,
                    dropdownParent: $('#add_medication')
                });
            });
        });
    </script>

    <!-- Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.Chart) {
                var ctx = document.getElementById('chartLine1').getContext('2d');
                var chartLine1 = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Patients',
                            data: [12, 19, 3, 5, 2, 3],
                            borderColor: 'rgba(171,0,219,1)',
                            backgroundColor: 'rgba(171,0,219,0.2)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            x: {
                                display: true
                            },
                            y: {
                                display: true
                            }
                        }
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addBtn = document.getElementById("addBtn");
            const vitalFields = document.getElementById("vitalFields");

            // Attach remove event to existing remove buttons
            vitalFields.querySelectorAll(".remove-btn").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    btn.closest(".vital-row").remove();
                });
            });

            addBtn.addEventListener("click", function() {
                // Clone the first row
                let firstRow = vitalFields.querySelector(".vital-row");
                let newRow = firstRow.cloneNode(true);

                // Clear input values
                newRow.querySelectorAll("input, select").forEach(el => el.value = "");

                // Show remove button
                let removeBtn = newRow.querySelector(".remove-btn");
                removeBtn.style.display = "inline-block";

                // Attach remove event to the new button
                removeBtn.addEventListener("click", function() {
                    newRow.remove();
                });

                // Append new row
                vitalFields.appendChild(newRow);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/ipd/ipd_view.blade.php ENDPATH**/ ?>