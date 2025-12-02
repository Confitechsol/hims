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

    <style>
        .timeline-wrapper {
            position: relative;
            padding-left: 110px;
            padding-right: 20px;
            margin-top: 80px;
        }

        /* vertical line */
        .timeline-wrapper::before {
            content: "";
            position: absolute;
            left: 58px;
            top: -30px;
            bottom: -45px;
            width: 4px;
            background: #e6edf8;
            border-radius: 2px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 65px;
            display: flex;
            gap: 18px;
            align-items: flex-start;
        }

        /* date badge on the left */
        .timeline-date {
            position: absolute;
            left: -110px;
            top: -50px;
            width: 140px;
            display: inline-block;
        }

        .timeline-date .date-badge {
            display: inline-block;
            background: #750096;
            color: #fff;
            padding: 8px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            box-shadow: 0 2px 6px rgba(11, 113, 199, 0.15);
        }

        .timeline-date .date-badge .time {
            font-weight: 500;
            font-size: 11px;
            opacity: 0.95;
        }

        /* round node that sits on the line */
        .timeline-node {
            position: absolute;
            left: -68px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(11, 113, 199, 0.15);
            top: 20px;
            z-index: 3;
        }

        /* card on right */
        .timeline-card {
            background: #f7f8fb;
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #eceff6;
            flex: 1;
        }

        .timeline-card .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .timeline-card .title {
            font-weight: 700;
            margin: 0;
            font-size: 15px;
        }

        .timeline-card .time {
            color: #6c757d;
            font-size: 12px;
        }

        .timeline-actions i {
            cursor: pointer;
        }

        .timeline-body {
            margin-top: 8px;
            color: #4b5563;
            font-size: 13px;
            line-height: 1.4;
        }

        /* final clock marker */
        .timeline-end {
            position: relative;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .timeline-end .node-end {
            position: absolute;
            left: -70px;
            top: -25px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffffff;
            color: #750096;
            border: 2px solid #e9e9e9;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
        }

        /* small responsive tweak */
        @media (max-width: 768px) {
            .timeline-wrapper {
                padding-left: 120px;
            }

            .timeline-wrapper::before {
                left: 84px;
            }

            .timeline-node {
                left: 72px;
            }

            .timeline-date {
                left: 0;
                width: 100px;
            }

            .timeline-card {
                margin-left: 22px;
            }

            .timeline-end .node-end {
                left: 72px;
            }
        }
    </style>

    <div class="p-4">
        <?php if(session('success')): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: "<?php echo e(session('alertTitle') ?? 'Success'); ?>",
                    text: "<?php echo e(session('success')); ?>",
                });
            </script>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: "<?php echo e(session('alertTitle') ?? 'Error'); ?>",
                    text: "<?php echo e(session('error')); ?>",
                });
            </script>
        <?php endif; ?>
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
                    <a href="#bed_history" data-bs-toggle="tab" aria-expanded="true"
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
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>
                                    <?php echo e($ipd->patient->patient_name); ?>

                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-sm-flex position-relative z-0 overflow-hidden p-2">
                                    <!-- <img src="assets/img/icons/shape-01.svg" alt="img"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            class="z-n1 position-absolute end-0 top-0 d-none d-lg-flex"> -->
                                    <a href="javascript:void(0);"
                                        class="avatar avatar-xxxl patient-avatar me-2 flex-shrink-0">
                                        <img src="<?php echo e(asset('assets/img/patient.png')); ?>" alt="product" class="rounded">
                                    </a>
                                    <div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-phone text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Phone :</h6>
                                                <p class="patient_data mb-0"><?php echo e($ipd->patient->mobileno); ?></p>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-calendar-days text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Age :</h6>
                                                <p class="patient_data mb-0"><?php echo e($ipd->patient->age); ?> Year
                                                    <?php echo e($ipd->patient->month); ?> Month <?php echo e($ipd->patient->day); ?> Days (As
                                                    Of
                                                    <?php echo e(\Carbon\Carbon::parse($ipd->patient->as_of_date)->format('d/m/Y')); ?>)
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-hands-holding-child text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Guardian Name :</h6>
                                                <p class="patient_data mb-0"><?php echo e($ipd->patient->guardian_name ?? '--'); ?>

                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-mars-and-venus text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Gender :</h6>
                                                <p class="patient_data mb-0"><?php echo e($ipd->patient->gender); ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-users-gear text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">TPA :</h6>
                                                <p class="patient_data mb-0">
                                                    <?php echo e($ipd->patient->organisation->organisation_name ?? '--'); ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-id-badge text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">TPA ID :</h6>
                                                <p class="patient_data mb-0">
                                                    <?php echo e($ipd->patient->organisation->code ?? '--'); ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-user-check text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">TPA Validity :</h6>
                                                <p class="patient_data mb-0"><?php echo e($ipd->patient->tpa_validity ?? '--'); ?></p>
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
                                        <p class="patient_data mb-0"><?php echo e($ipd->known_allergies ?? '--'); ?></p>
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
                                            <?php $__currentLoopData = $symptoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $symptom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><i class="fa-regular fa-circle-check text-primary"></i>
                                                    <?php echo e($symptom->symptoms_title); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="patient_img">
                                                <img src="<?php echo e(asset('assets/img/patient.png')); ?>" alt="product"
                                                    class="rounded">
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fs-13 fw-bold mb-1">
                                                    <?php echo e($ipd->doctor->name . '(' . $ipd->doctor->doctor_id . ')' ?? '--'); ?>

                                                </h6>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Nurse Notes
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline-wrapper">

                                    <!-- Step 1 (Completed) -->
                                    <?php $__currentLoopData = $nurseNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="timeline-item">
                                            <div class="timeline-date">
                                                <div class="date-badge">
                                                    <?php echo e(\Carbon\Carbon::parse($note->date)->format('d/m/Y')); ?>


                                                    <span class="time"></span>
                                                </div>
                                            </div>

                                            <div class="timeline-node bg-primary" title="Completed">
                                                <i class="fa-solid fa-file-lines"></i>
                                            </div>

                                            <div class="timeline-card">
                                                <div class="card-header p-0 pb-3">
                                                    <div>
                                                        <h5 class="title text-primary">
                                                            <?php echo e($note->staff->name); ?>

                                                        </h5>

                                                    </div>

                                                </div>
                                                <div class="timeline-body">

                                                    <p class="lh-base"><strong>Note</strong> <br>
                                                        <?php echo e($note->note); ?></p>
                                                    <p class="lh-base"><strong>Comment</strong> <br>
                                                        <?php echo e($note->comment); ?>

                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <!-- Final clock marker -->
                                    <div class="timeline-end">
                                        <div class="node-end" aria-hidden="true">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>

                                </div>
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
                                            <?php $__currentLoopData = $medicationReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($medication->date); ?></td>
                                                    <td><?php echo e($medication->pharmacy->medicine_name); ?></td>
                                                    <td><?php echo e($medication->medicineDosage->dosage); ?>

                                                        <?php echo e($medication->medicineDosage->unit->unit_name); ?>

                                                    </td>
                                                    <td><?php echo e($medication->time); ?></td>
                                                    <td><?php echo e($medication->remark); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                        
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Prescription
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Prescription No.</th>
                                                <th>Date</th>
                                                <th>Prescribed By</th>
                                                <th>Generated By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $ipdPrescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="fs-14 mb-1">
                                                            <?php echo e($prescription->prescription_number); ?></h6>
                                                    </td>
                                                    <td><?php echo e(\Carbon\Carbon::parse($prescription->date)->format('d/m/Y')); ?>

                                                    </td>
                                                    <td>--</td>
                                                    <td>--</td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <?php $__currentLoopData = $labInvestigations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <?php echo e($lab->pathology->test_name .
                                                            "
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            (" .
                                                            $lab->pathology->short_name .
                                                            ')'); ?>

                                                    </td>
                                                    <td>Pathology</td>
                                                    <td><?php echo e('--'); ?></td>
                                                    <td><?php echo e(\Carbon\Carbon::today()->copy()->addDays(intval($lab->pathology->report_days))->format('d-M-Y')); ?>

                                                    </td>
                                                    <td><?php echo e($lab->approved_by ?? '--'); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <?php $__currentLoopData = $operationDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="fs-14 mb-1">
                                                            <?php echo e($operation->reference_no); ?>

                                                        </h6>
                                                    </td>
                                                    <td><?php echo e($operation->date); ?></td>
                                                    <td><?php echo e($operation->operation->operation); ?></td>
                                                    <td><?php echo e($operation->operation->category->category); ?>

                                                    </td>
                                                    <td><?php echo e($operation->ot_technician); ?></td>

                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <?php $__currentLoopData = $ipdCharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $charge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $taxAmount =
                                                        ($charge->charge->standard_charge *
                                                            $charge->charge->taxCategory->percentage) /
                                                        100;
                                                    $amount = $charge->charge->standard_charge + $taxAmount;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo e($charge->charge->name); ?>

                                                    </td>
                                                    <td style="text-transform: capitalize;">
                                                        <?php echo e($charge->chargeCategory->chargeType->charge_type); ?>

                                                    </td>
                                                    <td class="text-right"><?php echo e($charge->charge->standard_charge); ?></td>
                                                    <td class="text-right">
                                                        (<?php echo e($charge->charge->taxCategory->percentage); ?>%)
                                                        <?php echo e($taxAmount); ?>

                                                    </td>
                                                    <td class="text-right"><?php echo e($charge->charge->standard_charge); ?></td>
                                                    <td class="text-right"><?php echo e($amount); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                                                    <form action="<?php echo e(route('nurseNote.store')); ?>"
                                                                        method="post">
                                                                        <?php echo csrf_field(); ?>

                                                                        <div class="modal-body">

                                                                            <div class="row gy-3 py-4 mx-1">
                                                                                <input type="hidden" name="ipd_id"
                                                                                    value="<?php echo e($ipd->id); ?>">
                                                                                <div class="col-md-6">
                                                                                    <label for="appointment_date"
                                                                                        class="form-label">
                                                                                        Date <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="date" name="date"
                                                                                        id="date"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="nurse"
                                                                                        class="form-label">Nurse</label>
                                                                                    <select class="form-select"
                                                                                        id="nurse" name="nurse">
                                                                                        <option value="">Loading...
                                                                                        </option>

                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label for="casualty"
                                                                                        class="form-label">Note</label>
                                                                                    <textarea class="form-control" id="note" name='note'>

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
                                                                    </form>

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
                                                <div class="timeline-wrapper">

                                                    <!-- Step 1 (Completed) -->
                                                    <?php $__currentLoopData = $nurseNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="timeline-item">
                                                            <div class="timeline-date">
                                                                <div class="date-badge">
                                                                    <?php echo e(\Carbon\Carbon::parse($note->date)->format('d/m/Y')); ?>


                                                                    <span class="time"></span>
                                                                </div>
                                                            </div>

                                                            <div class="timeline-node bg-primary" title="Completed">
                                                                <i class="fa-solid fa-file-lines"></i>
                                                            </div>

                                                            <div class="timeline-card">
                                                                <div class="card-header p-0 pb-3">
                                                                    <div>
                                                                        <h5 class="title text-primary">
                                                                            <?php echo e($note->staff->name); ?>

                                                                        </h5>

                                                                    </div>
                                                                    <div class="timeline-actions"
                                                                        aria-label="Edit or delete step">
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-info rounded-pill">
                                                                            <i class="ti ti-pencil"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Edit"></i></a>
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 btn btn-icon btn-sm btn-danger rounded-pill">
                                                                            <i class="ti ti-trash text"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Delete"></i></a>

                                                                    </div>
                                                                </div>
                                                                <div class="timeline-body">

                                                                    <p class="lh-base"><strong>Note</strong> <br>
                                                                        <?php echo e($note->note); ?></p>
                                                                    <p class="lh-base"><strong>Comment</strong> <br>
                                                                        <?php echo e($note->comment); ?>

                                                                    </p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    <!-- Final clock marker -->
                                                    <div class="timeline-end">
                                                        <div class="node-end" aria-hidden="true">
                                                            <i class="fas fa-clock"></i>
                                                        </div>
                                                    </div>

                                                </div>

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
                                                            <?php $__currentLoopData = $medicationReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td><?php echo e($medication->date); ?></td>
                                                                    <td><?php echo e($medication->pharmacy->medicine_name); ?></td>
                                                                    <td><?php echo e($medication->medicineDosage->dosage); ?>

                                                                        <?php echo e($medication->medicineDosage->unit->unit_name); ?>

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
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                                            <?php $__currentLoopData = $labInvestigations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo e($lab->pathology->test_name .
                                                                            "
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        (" .
                                                                            $lab->pathology->short_name .
                                                                            ')'); ?>

                                                                    </td>
                                                                    <td>Pathology</td>
                                                                    <td><?php echo e('--'); ?></td>
                                                                    <td><?php echo e(\Carbon\Carbon::today()->copy()->addDays(intval($lab->pathology->report_days))->format('d-M-Y')); ?>

                                                                    </td>
                                                                    <td><?php echo e($lab->approved_by ?? '--'); ?></td>
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
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



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
                                                            <?php $__currentLoopData = $operationDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">
                                                                            <?php echo e($operation->reference_no); ?>

                                                                        </h6>
                                                                    </td>
                                                                    <td><?php echo e($operation->date); ?></td>
                                                                    <td><?php echo e($operation->operation->operation); ?></td>
                                                                    <td><?php echo e($operation->operation->category->category); ?>

                                                                    </td>
                                                                    <td><?php echo e($operation->ot_technician); ?></td>
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
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                                                                    <form action="<?php echo e(route('ipd.addIpdCharge')); ?>"
                                                                        method="POST" id="addChargeForm">
                                                                        <?php echo csrf_field(); ?>
                                                                        <div class="modal-body">
                                                                            <div class="row ">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12">

                                                                                    <div class="row ptt10">
                                                                                        <div class="col-sm-2">
                                                                                            <div class="form-group">
                                                                                                <label
                                                                                                    class="form-label displayblock">Charge
                                                                                                    Type<small
                                                                                                        class="req">
                                                                                                        *</small></label>
                                                                                                <input type="hidden"
                                                                                                    name="ipd_id"
                                                                                                    id="ipd_id"
                                                                                                    value="<?php echo e($ipd->id); ?>">
                                                                                                <select name="charge_type"
                                                                                                    id="add_charge_type"
                                                                                                    class="form-control charge_type select2 reset_value select2-hidden-accessible"
                                                                                                    style="width: 100%"
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
                                                                                                    Category</label><small
                                                                                                    class="req">
                                                                                                    *</small>
                                                                                                <select
                                                                                                    name="charge_category2"
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
                                                                                                    class="req">
                                                                                                    *</small>
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
                                                                                                <label
                                                                                                    class="form-label">TPA
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
                                                                                                    class="req">
                                                                                                    *</small>
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
                                                                                                        <th
                                                                                                            width="40%">
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
                                                                                                        <th>Discount
                                                                                                            Percentage
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
                                                                                                                style="width: 70%; float: right;font-size: 12px;">
                                                                                                        </td>
                                                                                                        <td
                                                                                                            class="text-right ipdbilltable">
                                                                                                            <input
                                                                                                                type="text"
                                                                                                                placeholder="Tax"
                                                                                                                name="tax"
                                                                                                                value="0"
                                                                                                                id="tax_amt"
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
                                                                                                    <div
                                                                                                        class="form-group">
                                                                                                        <label
                                                                                                            for=""
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
                                                                                                    type="date"
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
                                                                                <div
                                                                                    class="col-lg-12 col-md-12 col-sm-12">
                                                                                    <table
                                                                                        class="table table-striped table-bordered table-hover">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <th>Date</th>
                                                                                                <th>Charge Type</th>
                                                                                                <th>Charge Category</th>
                                                                                                <th>Charge Name <br> Charge
                                                                                                    Note
                                                                                                </th>
                                                                                                <th class="text-right">
                                                                                                    Standard
                                                                                                    Charge (INR)</th>
                                                                                                <th class="text-right">TPA
                                                                                                    Charge (INR)</th>
                                                                                                <th class="text-right">Qty
                                                                                                </th>
                                                                                                <th class="text-right">
                                                                                                    Total
                                                                                                    (INR)</th>
                                                                                                <th class="text-right">
                                                                                                    Discount
                                                                                                    (INR)</th>
                                                                                                <th class="text-right">Tax
                                                                                                    (INR)
                                                                                                </th>
                                                                                                <th class="text-right">Net
                                                                                                    Amount (INR)</th>
                                                                                                <th class="text-right">
                                                                                                    Action
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
                                                                    </form>
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
                                                            <?php $__currentLoopData = $ipdCharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $charge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php
                                                                    $taxAmount =
                                                                        ($charge->charge->standard_charge *
                                                                            $charge->charge->taxCategory->percentage) /
                                                                        100;
                                                                    $discountAmount =
                                                                        ($charge->charge->standard_charge *
                                                                            $charge->discount) /
                                                                        100;
                                                                    $amount =
                                                                        $charge->charge->standard_charge -
                                                                        $discountAmount +
                                                                        $taxAmount;
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo e($charge->charge->name); ?>

                                                                    </td>
                                                                    <td style="text-transform: capitalize;">
                                                                        <?php echo e($charge->chargeCategory->chargeType->charge_type); ?>

                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo e($charge->chargeCategory->name); ?>

                                                                    </td>
                                                                    <td>
                                                                        1
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo e($charge->charge->standard_charge); ?></td>
                                                                    <td class="text-right">
                                                                        <?php echo e($charge->charge->standard_charge); ?></td>
                                                                    <td class="text-right">0.00</td>
                                                                    <td><?php echo e($discountAmount); ?>&nbsp;(<?php echo e($charge->discount); ?>%)
                                                                    </td>
                                                                    <td><?php echo e($taxAmount); ?>&nbsp;(<?php echo e($charge->charge->taxCategory->percentage); ?>%)
                                                                    </td>
                                                                    <td><?php echo e($amount); ?></td>
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
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
            <div class="tab-pane" id="prescription">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Prescription
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
                                                                data-bs-target="#addPrescriptionModal"
                                                                data-ipd-id="<?php echo e($ipd->id); ?>"><i
                                                                    class="ti ti-plus me-1"></i>Add Prescription</a>
                                                        </div>
                                                        <?php echo $__env->make('components.modals.add-prescription-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                        <!-- First Modal -->
                                                        <div class="modal fade" id="add_timeline" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                        <h5 class="modal-title"
                                                                            id="addSpecializationLabel">
                                                                            Add Prescription
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
                                                                <th>Prescription No</th>
                                                                <th>Date</th>
                                                                <th>Finding</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $__currentLoopData = $ipdPrescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">
                                                                            <?php echo e($prescription->prescription_number); ?></h6>
                                                                    </td>
                                                                    <td><?php echo e(\Carbon\Carbon::parse($prescription->date)->format('d/m/Y')); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php $__currentLoopData = $ipdFindings[$prescription->ipd_id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $finding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <span
                                                                                class="badge bg-primary me-1"><?php echo e($finding->name); ?></span><br>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex gap-2">
                                                                            <a href="javascript: void(0);"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#showPrescriptionModal"
                                                                                data-is-ipd="true"
                                                                                data-id="<?php echo e($ipd->id); ?>"
                                                                                data-pres-id = "<?php echo e($prescription->id); ?>">
                                                                                <i class="fa-solid fa-prescription"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="Show"></i></a>
                                                                        </div>
                                                                        <?php echo $__env->make('components.modals.show-prescription-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
            <div class="tab-pane" id="bed_history">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Bed History
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
                                                                <th>Bed Group</th>
                                                                <th>Bed</th>
                                                                <th>From Date</th>
                                                                <th>To Date</th>
                                                                <th>Active Bed</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $__currentLoopData = $bedHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">
                                                                            <?php echo e($history->bedGroup->name); ?></h6>
                                                                    </td>
                                                                    <td><?php echo e($history->bed->name); ?></td>
                                                                    <td><?php echo e(\Carbon\Carbon::parse($history->from_date)->format('d/m/Y h:i A')); ?>

                                                                    </td>
                                                                    <td><?php echo e($history->to_date ? \Carbon\Carbon::parse($history->to_date)->format('d/m/Y h:i A') : '--'); ?>

                                                                    </td>
                                                                    <td><?php echo e($history->bed->is_active); ?></td>

                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
            <div class="tab-pane" id="bed_issue">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Bed Assign
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row gy-4">
                                            <div class="col-md-6">
                                                <span class="text-primary"> <b>Old Assigned Bed : </b> </span>
                                                <span> Bed 4 - General Ward - Floor 2</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="text-primary"> <b>Assigned Date : </b> </span>
                                                <span> 12th September 2025 06:08:22 am</span>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="released_date" class="form-label">Select Released Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="released_date" id="released_date"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="bed_group" class="form-label">Select Bed Group <span
                                                        class="text-danger">*</span></label>
                                                <select name="bed_group" id="bed_group" class="form-select">
                                                    <option value="">Select Bed Group</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="new_bed" class="form-label">Select New Bed <span
                                                        class="text-danger">*</span></label>
                                                <select name="new_bed" id="new_bed" class="form-select">
                                                    <option value="">Select New Bed</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12 text-end mt-4">
                                                <button type="submit" class="btn btn-primary">Assign</button>
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
        document.addEventListener("DOMContentLoaded", function() {
            const chargeTypeSelect = document.getElementById("add_charge_type")
            const chargeCategorySelect = document.getElementById("charge_category2")
            const chargeSelect = document.getElementById("charge_id")



            fetch("<?php echo e(route('getChargeTypes')); ?>").then(response => response.json())
                .then(data => {
                    window.chargeTypeData = data;
                    chargeTypeSelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.charge_type;
                        if ("<?php echo e(old('charge_type')); ?>" == type.id) {
                            option.selected = true;
                        }
                        chargeTypeSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching charge types:', error);
                    chargeTypeSelect.innerHTML = '<option value="">Error loading options</option>';
                });

            chargeTypeSelect.addEventListener('change', function() {
                const selectedId = this.value;
                const baseUrl = "<?php echo e(route('getChargeCategoriesByTypeId', ['id' => 'ID'])); ?>";
                const finalUrl = baseUrl.replace('ID', selectedId);
                fetch(finalUrl)
                    .then(response => response.json())
                    .then(data => {
                        window.chargeCategoryData = data;
                        chargeCategorySelect.innerHTML = '<option value="">Select</option>';
                        data.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            if ("<?php echo e(old('charge_category')); ?>" == category.id) {
                                option.selected = true;
                            }
                            chargeCategorySelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching charge categories:', error);
                        chargeCategorySelect.innerHTML =
                            '<option value="">Error loading options</option>';
                    });
            })


            // Listen for Charge Category dropdown change
            chargeCategorySelect.addEventListener('change', function() {
                const selectedId = this.value;
                const baseUrl = "<?php echo e(route('getCharges', ['id' => 'ID'])); ?>";
                const finalUrl = baseUrl.replace('ID', selectedId);
                fetch(finalUrl)
                    .then(response => response.json())
                    .then(data => {
                        window.chargeData = data;
                        chargeSelect.innerHTML = '<option value="">Select</option>';
                        data.forEach(charge => {
                            const option = document.createElement('option');
                            option.value = charge.id;
                            option.textContent = charge.name;
                            if ("<?php echo e(old('charge')); ?>" == charge.id) {
                                option.selected = true;
                            }
                            chargeSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching Charges:', error);
                        chargeSelect.innerHTML = '<option value="">Error loading options</option>';
                    });

                chargeSelect.addEventListener('change', function() {
                    const selectedCharge = window.chargeData[0];

                    const standardCharge = document.getElementById("addstandard_charge")
                    const tpaCharge = document.getElementById("addscd_charge")
                    const total = document.getElementById("apply_charge");
                    const discount = document.getElementById("discount_percentage_add_charge");
                    const tax = document.getElementById("charge_tax");
                    const netAmount = document.getElementById("final_amount");
                    const discountAmountInp = document.getElementById("discount_percentage_amount");
                    const taxAmountInp = document.getElementById("tax_amt");

                    standardCharge.value = selectedCharge.standard_charge
                    tpaCharge.value = 0
                    total.value = selectedCharge.standard_charge
                    tax.value = selectedCharge.tax_category.percentage
                    discount.value = 0
                    calculateAmount();
                    if (!total || !tax || !discount) {
                        console.error(
                            "❌ One or more required input fields are missing in the DOM.");
                        return;
                    }
                    [total, tax, discount].forEach(field => {
                        field.addEventListener('input', calculateAmount);
                    });

                    function calculateAmount() {
                        const appliedChargeValue = parseFloat(total.value) || 0;
                        const taxValue = parseFloat(tax.value) || 0;
                        const discountValue = parseFloat(discount.value) || 0;

                        // Formula: Amount = (AppliedCharge + Tax%) - Discount%
                        const taxAmount = appliedChargeValue * (taxValue / 100);
                        const discountAmount = appliedChargeValue * (discountValue / 100);
                        const totalAmount = appliedChargeValue + taxAmount - discountAmount;

                        discountAmountInp.value = discountAmount;
                        taxAmountInp.value = taxAmount;
                        netAmount.value = totalAmount.toFixed(2);

                    }
                })


            })



            const previewBody = document.getElementById("preview_charges");
            const addBtn = document.querySelector("button[name='charge_data']");

            addBtn.addEventListener("click", function(e) {
                e.preventDefault();

                const chargeTypeText = document.getElementById("add_charge_type").selectedOptions[0].text;
                const chargeTypeVal = document.getElementById("add_charge_type").value;

                const categoryText = document.getElementById("charge_category2").selectedOptions[0].text;
                const categoryVal = document.getElementById("charge_category2").value;

                const chargeText = document.getElementById("charge_id").selectedOptions[0].text;
                const chargeVal = document.getElementById("charge_id").value;

                const stdCharge = document.getElementById("addstandard_charge").value;
                const tpaCharge = document.getElementById("addscd_charge").value;
                const qty = document.getElementById("qty").value;

                const total = document.getElementById("apply_charge").value;
                const discount = document.getElementById("discount_percentage_amount").value;
                const tax = document.getElementById("tax_amt").value;
                const netAmount = document.getElementById("final_amount").value;

                const note = document.getElementById("edit_note").value;
                const date = document.getElementById("charge_date").value;

                // VALIDATION
                if (!chargeTypeVal || !categoryVal || !chargeVal) {
                    alert("Please fill required fields");
                    return;
                }

                // -------------------------------
                // BUILD ROW HTML
                // -------------------------------
                let row = `
    <tr>
        <td>${date}</td>
        <td>${chargeTypeText}</td>
        <td>${categoryText}</td>
        <td>${chargeText}<br><small>${note}</small></td>

        <td class="text-right">${stdCharge}</td>
        <td class="text-right">${tpaCharge}</td>
        <td class="text-right">${qty}</td>
        <td class="text-right">${total}</td>
        <td class="text-right">${discount}</td>
        <td class="text-right">${tax}</td>
        <td class="text-right">${netAmount}</td>

        <td class="text-right">
            <button type="button" class="btn btn-danger btn-sm delete-charge-row">X</button>
        </td>

        <!-- HIDDEN INPUT FIELDS -->
        <input type="hidden" name="charge_type[]" value="${chargeTypeVal}">
        <input type="hidden" name="charge_category[]" value="${categoryVal}">
        <input type="hidden" name="charge_id[]" value="${chargeVal}">
        <input type="hidden" name="standard_charge[]" value="${stdCharge}">
        <input type="hidden" name="tpa_charge[]" value="${tpaCharge}">
        <input type="hidden" name="qty[]" value="${qty}">
        <input type="hidden" name="total[]" value="${total}">
        <input type="hidden" name="discount_percentage[]" value="${discount}">
        <input type="hidden" name="tax[]" value="${tax}">
        <input type="hidden" name="net_amount[]" value="${netAmount}">
        <input type="hidden" name="charge_note[]" value="${note}">
        <input type="hidden" name="charge_date[]" value="${date}">
    </tr>
    `;

                previewBody.insertAdjacentHTML("beforeend", row);

                // RESET FIELDS
                document.getElementById("add_charge_type").value = "";
                document.getElementById("charge_category2").value = "";
                document.getElementById("charge_id").value = "";
                document.getElementById("addstandard_charge").value = "";
                document.getElementById("addscd_charge").value = "";
                document.getElementById("qty").value = "1";
                document.getElementById("apply_charge").value = "0";
                document.getElementById("discount_percentage_add_charge").value = "0";
                document.getElementById("discount_percentage_amount").value = "0";
                document.getElementById("charge_tax").value = "0";
                document.getElementById("tax_amt").value = "0";
                document.getElementById("final_amount").value = "0";
                document.getElementById("edit_note").value = "";
                document.getElementById("charge_date").value = "";
            });

            // DELETE Row
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("delete-charge-row")) {
                    e.target.closest("tr").remove();
                }
            });

        });
    </script>

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
            const nurseSelect = document.getElementById('nurse')
            nurseSelect.innerHTML = '<option value="">Loading...</option>';

            fetch("<?php echo e(route('getNurses')); ?>")
                .then(response => response.json())
                .then(data => {
                    window.nursesData = data;
                    nurseSelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(nurse => {
                        const option = document.createElement('option');
                        option.value = nurse.id;
                        option.textContent = nurse.name;
                        if ("<?php echo e(old('nurse')); ?>" == nurse.id) {
                            option.selected = true;
                        }
                        nurseSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching Nurses:', error);
                    nurseSelect.innerHTML = '<option value="">Error loading options</option>';
                });
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