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


         /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            padding: 1.5rem;
            border: none;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-title i {
            font-size: 1.5rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.9;
        }

        .btn-close:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .modal-body {
            padding: 0;
            background: var(--bg-light);
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        /* Section Cards */
        .section-card {
            background: white;
            margin: 1rem;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-header {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 2px solid var(--border-color);
        }

        .section-header.primary {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(33, 150, 243, 0.05) 100%);
            border-bottom-color: var(--primary-color);
        }

        .section-header.danger {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.1) 0%, rgba(244, 67, 54, 0.05) 100%);
            border-bottom-color: var(--danger-color);
        }

        .section-header.warning {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.1) 0%, rgba(255, 152, 0, 0.05) 100%);
            border-bottom-color: var(--warning-color);
        }

        .section-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .section-icon.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .section-icon.danger {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
        }

        .section-icon.warning {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
        }

        .section-title {
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--text-dark);
            margin: 0;
        }

        .section-body {
            padding: 1.5rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .info-grid.full {
            grid-template-columns: 1fr;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-label i {
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .info-value {
            font-size: 1rem;
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.75rem;
            background: var(--bg-light);
            border-radius: 6px;
            border-left: 3px solid var(--primary-color);
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        .info-value.empty {
            color: var(--text-muted);
            font-style: italic;
            opacity: 0.7;
            border-left-color: var(--border-color);
        }

        .info-value.long-text {
            white-space: pre-wrap;
            word-break: break-word;
            align-items: flex-start;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            width: fit-content;
        }

        .status-badge.active {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.discharged {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-badge.death {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.referral {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge i {
            font-size: 1rem;
        }

        /* Modal Footer */
        .modal-footer {
            background: white;
            border-top: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            gap: 0.75rem;
        }

        .btn {
            border-radius: 8px;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-print {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
        }

        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
        }

        .btn-close-modal {
            background: white;
            color: #6c757d;
            border: 1px solid #6c757d !important;
        }

        .btn-close-modal:hover {
            background: #f8f9fa;
            color: #212529;
            border-color: #6c757d;
        }

        /* Scrollbar */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: var(--bg-light);
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .section-card {
                margin: 0.5rem;
            }

            .section-body {
                padding: 1rem;
            }
        }

        /* Hidden sections */
        .d-none {
            display: none !important;
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
                                <div class= "d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>
                                        <?php echo e($ipd->patient->patient_name); ?>

                                    </h5>
                                    <?php if($ipd->discharged == 'yes'): ?>
                                        <button class="bg-transparent border-0" data-bs-toggle="modal"
                                            data-bs-target="#dischargeDetailsModal"
                                            data-discharge='<?php echo json_encode($ipd->dischargeCard, 15, 512) ?>'><i
                                                class="bi bi-clipboard-pulse text-white"></i></button>
                                    <?php else: ?>
                                        <button class="bg-transparent border-0" data-bs-toggle="modal"
                                            data-bs-target="#patientDischargeModal" data-id="<?php echo e($ipd->id); ?>"><i
                                                class="bi bi-clipboard-pulse text-white"></i></button>
                                    <?php endif; ?>
                                </div>
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
                                            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo e($transaction->transaction_no ?? 'TRID'.$transaction->id); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e(\Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y h:i A')); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e($transaction->note ?? '-'); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e($transaction->payment_mode == 1 ? 'Cash' : '-'); ?>

                                                                    </td>

                                                                    <td class="text-end">
                                                                        <?php echo e(number_format($transaction->amount, 2)); ?>

                                                                    </td>


                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                                <tr>
                                                                    <td colspan="6" class="text-center text-muted">
                                                                        No payments found
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
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
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Medication
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

                                                                    <form method="POST"
                                                                        action="<?php echo e(route('medication.store')); ?>">
                                                                        <?php echo csrf_field(); ?>
                                                                        <input type="hidden" name="ipd_id"
                                                                            value="<?php echo e($ipd->id); ?>">
                                                                        <div class="modal-body">
                                                                            <div class="row gy-3 py-4 mx-1">

                                                                                
                                                                                <div class="col-md-6">
                                                                                    <label for="date"
                                                                                        class="form-label">Date <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="date" name="date"
                                                                                        id="date"
                                                                                        value="<?php echo e(old('date')); ?>"
                                                                                        class="form-control <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                                    <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                        <div class="text-danger small">
                                                                                            <?php echo e($message); ?></div>
                                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                                </div>

                                                                                
                                                                                <div class="col-md-6">
                                                                                    <label for="time"
                                                                                        class="form-label">Time <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="time" name="time"
                                                                                        id="time"
                                                                                        value="<?php echo e(old('time')); ?>"
                                                                                        class="form-control <?php $__errorArgs = ['time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                                    <?php $__errorArgs = ['time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                        <div class="text-danger small">
                                                                                            <?php echo e($message); ?></div>
                                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                                </div>

                                                                                
                                                                                <div class="col-md-6">
                                                                                    <label for="medi_cat"
                                                                                        class="form-label">Medicine
                                                                                        Category <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select name="medi_cat" id="medi_cat"
                                                                                        class="form-select <?php $__errorArgs = ['medi_cat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                                        <option value="">Select
                                                                                        </option>
                                                                                        <?php $__currentLoopData = $medicineCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <option
                                                                                                value="<?php echo e($cat->id); ?>"
                                                                                                <?php echo e(old('medi_cat') == $cat->id ? 'selected' : ''); ?>>
                                                                                                <?php echo e($cat->medicine_category); ?>

                                                                                            </option>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </select>
                                                                                    <?php $__errorArgs = ['medi_cat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                        <div class="text-danger small">
                                                                                            <?php echo e($message); ?></div>
                                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                                </div>

                                                                                
                                                                                <div class="col-md-6">
                                                                                    <label for="med_name"
                                                                                        class="form-label">Medicine Name
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select name="med_name" id="med_name"
                                                                                        class="form-select <?php $__errorArgs = ['med_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                                        <option value="">Select
                                                                                        </option>
                                                                                    </select>
                                                                                    <?php $__errorArgs = ['med_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                        <div class="text-danger small">
                                                                                            <?php echo e($message); ?></div>
                                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                                </div>

                                                                                
                                                                                <div class="col-md-6">
                                                                                    <label for="dosage"
                                                                                        class="form-label">Dosage <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select name="dosage" id="dosage"
                                                                                        class="form-select <?php $__errorArgs = ['dosage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                                        <option value="">Select
                                                                                        </option>

                                                                                    </select>
                                                                                    <?php $__errorArgs = ['dosage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                        <div class="text-danger small">
                                                                                            <?php echo e($message); ?></div>
                                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                                </div>

                                                                                
                                                                                <div class="col-md-6">
                                                                                    <label for="remark"
                                                                                        class="form-label">Remarks</label>
                                                                                    <textarea name="remark" id="remark" class="form-control"><?php echo e(old('remark')); ?></textarea>
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
                                                                <th>Medication Name</th>
                                                                <th>Dose</th>
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
                                                                            <a href="javascript:void(0);"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill editMedicationBtn"
                                                                                data-id="<?php echo e($medication->id); ?>"
                                                                                data-date="<?php echo e($medication->date); ?>"
                                                                                data-time="<?php echo e($medication->time); ?>"
                                                                                data-cat="<?php echo e($medication->pharmacy->medicine_category_id); ?>"
                                                                                data-med="<?php echo e($medication->pharmacy_id); ?>"
                                                                                data-dose="<?php echo e($medication->medicine_dosage_id); ?>"
                                                                                data-remark="<?php echo e($medication->remark); ?>"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#edit_medication">
                                                                                <i class="ti ti-pencil"></i>
                                                                            </a>
                                                                            <!-- <a href="javascript:void(0);"
                                                                                                    onclick="confirmDelete('<?php echo e(route('medication.delete', $medication->id)); ?>')"
                                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                                                        <i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i>
                                                                                                </a> -->

                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <div class="modal fade" id="edit_medication"
                                                                    tabindex="-1" aria-hidden="true">
                                                                    <div
                                                                        class="modal-dialog modal-dialog-centered modal-lg">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header"
                                                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                                <h5 class="modal-title">Edit Medication
                                                                                    Dose</h5>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"></button>
                                                                            </div>

                                                                            <form method="POST"
                                                                                action="<?php echo e(route('medication.update')); ?>">
                                                                                <?php echo csrf_field(); ?>
                                                                                <?php echo method_field('PUT'); ?>

                                                                                <input type="hidden" name="id"
                                                                                    id="edit_id">
                                                                                <input type="hidden" name="ipd_id"
                                                                                    value="<?php echo e($ipd->id); ?>">

                                                                                <div class="modal-body">
                                                                                    <div class="row gy-3 py-4 mx-1">

                                                                                        
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Date</label>
                                                                                            <input type="date"
                                                                                                name="date"
                                                                                                id="edit_date"
                                                                                                class="form-control">
                                                                                        </div>

                                                                                        
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Time</label>
                                                                                            <input type="time"
                                                                                                name="time"
                                                                                                id="edit_time"
                                                                                                class="form-control">
                                                                                        </div>

                                                                                        
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Medicine
                                                                                                Category</label>
                                                                                            <select name="medi_cat"
                                                                                                id="edit_medi_cat"
                                                                                                class="form-select">
                                                                                                <option value="">
                                                                                                    Select</option>
                                                                                                <?php $__currentLoopData = $medicineCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <option
                                                                                                        value="<?php echo e($cat->id); ?>">
                                                                                                        <?php echo e($cat->medicine_category); ?>

                                                                                                    </option>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                            </select>
                                                                                        </div>

                                                                                        
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Medicine
                                                                                                Name</label>
                                                                                            <select name="med_name"
                                                                                                id="edit_med_name"
                                                                                                class="form-select">
                                                                                                <?php $__currentLoopData = $pharmacyDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <option
                                                                                                        value="<?php echo e($med->id); ?>">
                                                                                                        <?php echo e($med->medicine_name); ?>

                                                                                                    </option>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                            </select>
                                                                                        </div>

                                                                                        
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Dosage</label>
                                                                                            <select name="dosage"
                                                                                                id="edit_dosage"
                                                                                                class="form-select">
                                                                                                <?php $__currentLoopData = $medDosages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dose): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <option
                                                                                                        value="<?php echo e($dose->id); ?>">
                                                                                                        <?php echo e($dose->dosage); ?>

                                                                                                    </option>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                            </select>
                                                                                        </div>

                                                                                        
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Remarks</label>
                                                                                            <input type="text"
                                                                                                name="remark"
                                                                                                id="edit_remark"
                                                                                                class="form-control">
                                                                                        </div>

                                                                                    </div>
                                                                                </div>

                                                                                <div class="modal-footer">
                                                                                    <button
                                                                                        class="btn btn-primary">Update</button>
                                                                                </div>
                                                                            </form>

                                                                        </div>
                                                                    </div>
                                                                </div>
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
                                                    <!-- Search Bar -->
                                                    <div class="input-icon-start position-relative me-2">
                                                        <span class="input-icon-addon">
                                                            <i class="ti ti-search"></i>
                                                        </span>
                                                        <input type="text" class="form-control shadow-sm"
                                                            placeholder="Search">
                                                    </div>

                                                    <!-- Add Operation Button -->
                                                    <button type="button" class="btn btn-primary shadow-sm"
                                                        data-bs-toggle="modal" data-bs-target="#addOperationModal">
                                                        <i class="ti ti-plus me-1"></i> Add Operation
                                                    </button>

                                                </div>

                                                <!-- Modal -->
                                                <div class="modal fade" id="addOperationModal" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content">

                                                            <div class="modal-header"
                                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                <h5 class="modal-title" style="color:#750096"><i
                                                                        class="fas fa-cogs me-2"></i>Add Operation</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <!-- Include the Operation Form -->
                                                                <form action="<?php echo e(route('operation.store')); ?>"
                                                                    method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <input type="text" name="ipd_details_id"
                                                                        class="form-control" value="<?php echo e($ipd->id); ?>"
                                                                        hidden>
                                                                    <div class="row gy-3 py-4 mx-1">

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Customer
                                                                                Type</label>
                                                                            <select name="customer_type"
                                                                                class="form-control" required>
                                                                                <option value="">Select</option>
                                                                                <option value="General">General</option>
                                                                                <option value="VIP">VIP</option>
                                                                                <option value="Corporate">Corporate
                                                                                </option>
                                                                            </select>
                                                                        </div>


                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Operation
                                                                                Category</label>
                                                                            <select name="operation_category_id"
                                                                                id="operation_category"
                                                                                class="form-select">
                                                                                <option value="">Select Category
                                                                                </option>
                                                                                <?php $__currentLoopData = $operationCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <option value="<?php echo e($cat->id); ?>">
                                                                                        <?php echo e($cat->category); ?></option>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            </select>
                                                                        </div>


                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Operations</label>
                                                                            <select name="operation_id"
                                                                                id="operation_type" class="form-select">
                                                                                <option value="">Select Operation
                                                                                </option>
                                                                                
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Operation Date &
                                                                                Time</label>
                                                                            <input type="datetime-local" name="date"
                                                                                class="form-control" required>
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Consultant
                                                                                Doctor</label>
                                                                            <select name="consultant_doctor"
                                                                                class="form-select">
                                                                                <option value="">Select Doctor
                                                                                </option>
                                                                                <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <option value="<?php echo e($doctor->id); ?>">
                                                                                        <?php echo e($doctor->name); ?></option>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Assistant Consultant
                                                                                1</label>
                                                                            <input type="text"
                                                                                name="ass_consultant_1"
                                                                                class="form-control">
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Assistant Consultant
                                                                                2</label>
                                                                            <input type="text"
                                                                                name="ass_consultant_2"
                                                                                class="form-control">
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Anesthetist</label>
                                                                            <input type="text" name="anesthetist"
                                                                                class="form-control">
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Anaesthesia
                                                                                Type</label>
                                                                            <input type="text" name="anaethesia_type"
                                                                                class="form-control">
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">OT
                                                                                Technician</label>
                                                                            <input type="text" name="ot_technician"
                                                                                class="form-control">
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">OT Assistant</label>
                                                                            <input type="text" name="ot_assistant"
                                                                                class="form-control">
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Result</label>
                                                                            <input type="text" name="result"
                                                                                class="form-control">
                                                                        </div>

                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="form-label">Remark</label>
                                                                            <textarea name="remark" rows="3" class="form-control"></textarea>
                                                                        </div>



                                                                    </div>

                                                                    <div class="mt-3 text-end modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save
                                                                            Operation</button>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                        </div>
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
                                                                            <a href="#" data-bs-toggle="modal"
                                                                                data-bs-target="#editOperationModal<?php echo e($operation->id); ?>"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill">
                                                                                <i class="ti ti-pencil"></i>
                                                                            </a>
                                                                            <!-- <a href="javascript: void(0);"
                                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                                                    <i class="ti ti-trash"
                                                                                                        data-bs-toggle="tooltip"
                                                                                                        title="Show"></i></a> -->
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <!-- EDIT OPERATION MODAL -->
                                                                <div class="modal fade"
                                                                    id="editOperationModal<?php echo e($operation->id); ?>"
                                                                    tabindex="-1" aria-hidden="true">
                                                                    <div
                                                                        class="modal-dialog modal-lg modal-dialog-centered">
                                                                        <div class="modal-content">

                                                                            <div class="modal-header"
                                                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                                <h5 class="modal-title"
                                                                                    style="color:#750096"><i
                                                                                        class="fas fa-cogs me-2"></i>Edit
                                                                                    Operation</h5>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"></button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <form
                                                                                    action="<?php echo e(route('operation.update', $operation->id)); ?>"
                                                                                    method="POST">
                                                                                    <?php echo csrf_field(); ?>
                                                                                    <?php echo method_field('PUT'); ?>
                                                                                    <input type="text"
                                                                                        name="ipd_details_id"
                                                                                        class="form-control"
                                                                                        value="<?php echo e($ipd->id); ?>"
                                                                                        hidden>
                                                                                    <div class="row gy-3 py-4 mx-1">
                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Customer
                                                                                                Type</label>
                                                                                            <select name="customer_type"
                                                                                                class="form-control"
                                                                                                required>
                                                                                                <option value="">
                                                                                                    Select</option>
                                                                                                <option value="General"
                                                                                                    <?php echo e($operation->customer_type == 'General' ? 'selected' : ''); ?>>
                                                                                                    General</option>
                                                                                                <option value="VIP"
                                                                                                    <?php echo e($operation->customer_type == 'VIP' ? 'selected' : ''); ?>>
                                                                                                    VIP</option>
                                                                                                <option value="Corporate"
                                                                                                    <?php echo e($operation->customer_type == 'Corporate' ? 'selected' : ''); ?>>
                                                                                                    Corporate</option>
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Operation
                                                                                                Category</label>
                                                                                            <select
                                                                                                name="operation_category_id"
                                                                                                class="form-select">
                                                                                                <option value="">
                                                                                                    Select Category</option>
                                                                                                <?php $__currentLoopData = $operationCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <option
                                                                                                        value="<?php echo e($cat->id); ?>"
                                                                                                        <?php echo e($operation->operation->category_id == $cat->id ? 'selected' : ''); ?>>
                                                                                                        <?php echo e($cat->category); ?>

                                                                                                    </option>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Operations</label>
                                                                                            <select name="operation_id"
                                                                                                class="form-select">
                                                                                                <option value="">
                                                                                                    Select Operation
                                                                                                </option>
                                                                                                <?php $__currentLoopData = $operations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <option
                                                                                                        value="<?php echo e($op->id); ?>"
                                                                                                        <?php echo e($operation->operation_id == $op->id ? 'selected' : ''); ?>>
                                                                                                        <?php echo e($op->operation); ?>

                                                                                                    </option>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Operation
                                                                                                Date & Time</label>
                                                                                            <input type="datetime-local"
                                                                                                name="date"
                                                                                                class="form-control"
                                                                                                value="<?php echo e(\Carbon\Carbon::parse($operation->date)->format('Y-m-d\TH:i')); ?>"
                                                                                                required>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Consultant
                                                                                                Doctor</label>
                                                                                            <select
                                                                                                name="consultant_doctor"
                                                                                                class="form-select">
                                                                                                <option value="">
                                                                                                    Select Doctor</option>
                                                                                                <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <option
                                                                                                        value="<?php echo e($doctor->id); ?>"
                                                                                                        <?php echo e($operation->consultant_doctor == $doctor->id ? 'selected' : ''); ?>>
                                                                                                        <?php echo e($doctor->name); ?>

                                                                                                    </option>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Assistant
                                                                                                Consultant 1</label>
                                                                                            <input type="text"
                                                                                                name="ass_consultant_1"
                                                                                                class="form-control"
                                                                                                value="<?php echo e($operation->ass_consultant_1); ?>">
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Assistant
                                                                                                Consultant 2</label>
                                                                                            <input type="text"
                                                                                                name="ass_consultant_2"
                                                                                                class="form-control"
                                                                                                value="<?php echo e($operation->ass_consultant_2); ?>">
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Anesthetist</label>
                                                                                            <input type="text"
                                                                                                name="anesthetist"
                                                                                                class="form-control"
                                                                                                value="<?php echo e($operation->anesthetist); ?>">
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Anaesthesia
                                                                                                Type</label>
                                                                                            <input type="text"
                                                                                                name="anaethesia_type"
                                                                                                class="form-control"
                                                                                                value="<?php echo e($operation->anaethesia_type); ?>">
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label class="form-label">OT
                                                                                                Technician</label>
                                                                                            <input type="text"
                                                                                                name="ot_technician"
                                                                                                class="form-control"
                                                                                                value="<?php echo e($operation->ot_technician); ?>">
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label class="form-label">OT
                                                                                                Assistant</label>
                                                                                            <input type="text"
                                                                                                name="ot_assistant"
                                                                                                class="form-control"
                                                                                                value="<?php echo e($operation->ot_assistant); ?>">
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Result</label>
                                                                                            <input type="text"
                                                                                                name="result"
                                                                                                class="form-control"
                                                                                                value="<?php echo e($operation->result); ?>">
                                                                                        </div>

                                                                                        <div class="col-md-12 mb-3">
                                                                                            <label
                                                                                                class="form-label">Remark</label>
                                                                                            <textarea name="remark" rows="3" class="form-control"><?php echo e($operation->remark); ?></textarea>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div
                                                                                        class="mt-3 text-end modal-footer">
                                                                                        <button type="submit"
                                                                                            class="btn btn-primary">Update
                                                                                            Operation</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
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
                                                                            <div class="row gy-3 py-4 mx-1">
                                                                                <div
                                                                                    class="col-lg-12 col-md-12 col-sm-12">

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
                                                                                                    <option
                                                                                                        value="">
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
                                                                                                    <option
                                                                                                        value="">
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
                                                                                                    <option
                                                                                                        value="">
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
                                                                                                                >
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
                                                                                                                >
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
                                                                                                                >
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
                                                                                                                >
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
                                                                <!-- <th>Action</th> -->
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
                                                                         <?php echo e(\Carbon\Carbon::parse($charge->date)->format('d-m-Y')); ?>

                                                                    </td>
                                                                    <td>
                                                                        <?php echo e($charge->charge->name); ?>

                                                                    </td>
                                                                    <td style="text-transform: capitalize;">
                                                                        <?php echo e($charge->chargeCategory->chargeType->charge_type); ?>

                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo e($charge->chargeCategory->name); ?>

                                                                    </td>

                                                                    <td class="text-right">
                                                                        <?php echo e($charge->qty); ?></td>
                                                                    <td class="text-right">
                                                                        <?php echo e($charge->standard_charge); ?></td>
                                                                    <td class="text-right">0.00</td>
                                                                    <td><?php echo e($discountAmount); ?>&nbsp;(<?php echo e($charge->discount); ?>%)
                                                                    </td>
                                                                    <td><?php echo e($taxAmount); ?>&nbsp;(<?php echo e($charge->charge->taxCategory->percentage); ?>%)
                                                                    </td>
                                                                    <td><?php echo e($amount); ?></td>
                                                                    <!-- <td>
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
                                                                    </td> -->
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
                                                                        <form action="<?php echo e(route('transactions.store')); ?>" method="POST">
                                                                            <?php echo csrf_field(); ?>
                                                                            <input type="hidden" name="ipd_id" value="<?php echo e($ipd->id); ?>">
                                                                            <input type="hidden" name="patient_id" value="<?php echo e($ipd->patient_id); ?>">
                                                                            <input type="hidden" name="type" value="payment">
                                                                            <input type="hidden" name="section" value="ipd">
                                                                                <div class="row gy-3 py-4 mx-1">

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
                                                                <th>Transaction ID</th>
                                                                <th>Date</th>
                                                                <th>Note</th>
                                                                <th>Payment Mode</th>
                                                                <th>Paid Amount (INR)</th>
                                                                <!-- <th>Action</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo e($transaction->transaction_no ?? 'TRID'.$transaction->id); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e(\Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y h:i A')); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e($transaction->note ?? '-'); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e($transaction->payment_mode == 1 ? 'Cash' : '-'); ?>

                                                                    </td>

                                                                    <td class="text-end">
                                                                        <?php echo e(number_format($transaction->amount, 2)); ?>

                                                                    </td>

                                                                    <!-- <td>
                                                                        <div class="d-flex gap-2">
                                                                            
                                                                            <a href="<?php echo e(route('transactions.print', $transaction->id)); ?>"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-primary rounded-pill"
                                                                            data-bs-toggle="tooltip" title="Print">
                                                                                <i class="fa-solid fa-print"></i>
                                                                            </a>

                                                                            
                                                                            <a href="<?php echo e(route('transactions.show', $transaction->id)); ?>"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill"
                                                                            data-bs-toggle="tooltip" title="Show">
                                                                                <i class="ti ti-pencil"></i>
                                                                            </a>

                                                                            
                                                                            <form action="<?php echo e(route('transactions.destroy', $transaction->id)); ?>"
                                                                                method="POST"
                                                                                onsubmit="return confirm('Delete this payment?')">
                                                                                <?php echo csrf_field(); ?>
                                                                                <?php echo method_field('DELETE'); ?>
                                                                                <button type="submit"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                                                    data-bs-toggle="tooltip" title="Delete">
                                                                                    <i class="ti ti-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </td> -->
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                                <tr>
                                                                    <td colspan="6" class="text-center text-muted">
                                                                        No payments found
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
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
                                                                    <form method="POST"
                                                                        action="<?php echo e(isset($timeline) ? route('patient-timeline.update', $timeline->id) : route('patient-timeline.store')); ?>"
                                                                        enctype="multipart/form-data">
                                                                        <?php echo csrf_field(); ?>
                                                                        <?php if(isset($timeline)): ?>
                                                                            <?php echo method_field('PUT'); ?>
                                                                        <?php endif; ?>

                                                                        <input type="hidden" name="patient_id"
                                                                            value="<?php echo e($ipd->patient_id ?? ''); ?>">

                                                                        <div class="modal-body">
                                                                            <div class="row gy-3 py-4 mx-1">
                                                                                <!-- Title -->
                                                                                <div class="col-md-12">
                                                                                    <label for="title"
                                                                                        class="form-label">
                                                                                        Title <span
                                                                                            class="text-danger">*</span>
                                                                                    </label>
                                                                                    <input type="text" name="title"
                                                                                        id="title"
                                                                                        class="form-control"
                                                                                        value="<?php echo e(old('title', $timeline->title ?? '')); ?>"
                                                                                        required>
                                                                                </div>

                                                                                <!-- Date -->
                                                                                <div class="col-md-12">
                                                                                    <label for="date"
                                                                                        class="form-label">
                                                                                        Date <span
                                                                                            class="text-danger">*</span>
                                                                                    </label>
                                                                                    <input type="date" name="date"
                                                                                        id="date"
                                                                                        class="form-control"
                                                                                        value="<?php echo e(old('date', isset($timeline->date) ? \Carbon\Carbon::parse($timeline->date)->format('Y-m-d') : '')); ?>"
                                                                                        required>
                                                                                </div>

                                                                                <!-- Description -->
                                                                                <div class="col-md-12">
                                                                                    <label for="description"
                                                                                        class="form-label">
                                                                                        Description
                                                                                    </label>
                                                                                    <textarea name="description" id="description" class="form-control" rows="3"><?php echo e(old('description', $timeline->description ?? '')); ?></textarea>
                                                                                </div>

                                                                                <!-- Attach Document -->
                                                                                <div class="col-md-12">
                                                                                    <label for="attch_doc"
                                                                                        class="form-label">
                                                                                        Attach Document
                                                                                    </label>
                                                                                    <input type="file"
                                                                                        name="attch_doc" id="attch_doc"
                                                                                        class="form-control">
                                                                                    <?php if(isset($timeline) && $timeline->attch_doc): ?>
                                                                                        <small
                                                                                            class="text-muted d-block mt-1">
                                                                                            Current File:
                                                                                            <a href="<?php echo e(asset('storage/timeline_docs/' . $timeline->attch_doc)); ?>"
                                                                                                target="_blank">
                                                                                                View Document
                                                                                            </a>
                                                                                        </small>
                                                                                    <?php endif; ?>
                                                                                </div>

                                                                                <!-- Visible to Person -->
                                                                                <div class="col-md-12 form-check">
                                                                                    <input type="checkbox"
                                                                                        name="visible_person"
                                                                                        id="visible_person"
                                                                                        class="form-check-input"
                                                                                        <?php echo e(old('visible_person', $timeline->visible_person ?? false) ? 'checked' : ''); ?>>
                                                                                    <label for="visible_person"
                                                                                        class="form-check-label">Visible
                                                                                        to this person</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                class="btn btn-primary">
                                                                                <?php echo e(isset($timeline) ? 'Update' : 'Save'); ?>

                                                                            </button>
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

                                                                <th>Patient Name</th>
                                                                <th>Title</th>
                                                                <th>Description</th>
                                                                <th>Timeline Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $__empty_1 = true; $__currentLoopData = $PatientTimelines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">
                                                                            <a href="#"
                                                                                class="fw-semibold"><?php echo e($timeline->patient->patient_name ?? '-'); ?></a>
                                                                        </h6>
                                                                    </td>


                                                                    <td><?php echo e($timeline->title ?? '-'); ?></td>
                                                                    <td><?php echo e($timeline->description ?? '-'); ?></td>
                                                                    <td>
                                                                        <?php if(!empty($timeline->timeline_date)): ?>
                                                                            <?php echo e(\Carbon\Carbon::parse($timeline->timeline_date)->format('d/m/Y h:i A')); ?>

                                                                        <?php else: ?>
                                                                            -
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex gap-2">
                                                                            <a href="#"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                                                data-bs-toggle="tooltip" title="Show">
                                                                                <i class="ti ti-menu"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                                <tr>
                                                                    <td colspan="6" class="text-center text-muted">No
                                                                        timeline records found</td>
                                                                </tr>
                                                            <?php endif; ?>
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
                                                                <th>IPD No</th>
                                                                <th>Patient ID</th>
                                                                <th>Consultant Doctor</th>
                                                                <th>Bed Assigned</th>
                                                                <!-- <th>Action</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <td><?php echo e($ipd->ipd_no); ?></td>
                                                            <td><?php echo e($ipd->patient_id); ?></td>
                                                            <td><?php echo e($ipd->doctor->name); ?> <?php echo e($ipd->doctor->surname); ?>

                                                            </td>
                                                            <td><?php echo e($ipd->bedGroup->name); ?>-<?php echo e($ipd->bedDetail->name); ?>

                                                            </td>
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
                                                                            <?php echo e($history->bedGroup->name ?? '-'); ?></h6>
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
                                <form action = "<?php echo e(route('assignNewBed')); ?>" method = "POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row gy-4">
                                                <div class="col-md-6">
                                                    <span class="text-primary"> <b>Old Assigned Bed : </b> </span>

                                                    <span>
                                                        <?php echo e($bedShiftHistory->bed->name ?? '-'); ?>

                                                        -
                                                        <?php echo e($bedShiftHistory->bedGroup->name ?? 'No Ward'); ?>

                                                        -
                                                        <?php echo e($bedShiftHistory->bedGroup->floorDetail->name ?? '-'); ?>

                                                    </span>


                                                </div>
                                                <input type="hidden" name="ipd_id" value="<?php echo e($ipd->id); ?>">
                                                <div class="col-md-6">
                                                    <span class="text-primary"><b>Assigned Date : </b></span>
                                                    <span>
                                                        <?php if($bedShiftHistory): ?>
                                                            <?php echo e($bedShiftHistory->from_date ? \Carbon\Carbon::parse($bedShiftHistory->from_date)->format('jS F Y h:i:s a') : '-'); ?>

                                                        <?php else: ?>
                                                            <span class="text-danger">No active bed history</span>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="released_date" class="form-label">Select Released Date
                                                        <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="released_date"
                                                        id="released_date" class="form-control">
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
                                </form>
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
                                                                    <form method="POST"
                                                                        action="<?php echo e(route('patient-vitals.store')); ?>">
                                                                        <?php echo csrf_field(); ?>
                                                                        <input type="hidden" name="patient_id"
                                                                            value="<?php echo e($ipd->patient_id); ?>">
                                                                        <div class="modal-body p-4 mx-1">
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
                                                                                            <?php $__currentLoopData = $vitals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vital): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                <option
                                                                                                    value="<?php echo e($vital->id); ?>">
                                                                                                    <?php echo e($vital->name . ' (' . $vital->reference_range . ')'); ?>

                                                                                                </option>
                                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        </select>
                                                                                    </div>

                                                                                    <!-- Vital Value -->
                                                                                    <div class="col-md-4">
                                                                                        <label for="vital_value"
                                                                                            class="form-label">Vital
                                                                                            Value</label>
                                                                                        <input type="text"
                                                                                            name="vital_value[]"
                                                                                            id="vital_value"
                                                                                            class="form-control" />
                                                                                    </div>

                                                                                    <!-- Date -->
                                                                                    <div class="col-md-3">
                                                                                        <label for="date"
                                                                                            class="form-label">Date</label>
                                                                                        <input type="date"
                                                                                            name="date[]"
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
                                                                                    class="btn btn-primary"
                                                                                    id="addBtn">
                                                                                    <i class="ti ti-plus"></i> Add Vital
                                                                                </button>
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

                                                                <th>Messure Date</th>
                                                                
                                                                <?php $__currentLoopData = $vitals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vital): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <th><?php echo e($vital->name); ?></th>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <!-- <th>Action</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $__empty_1 = true; $__currentLoopData = $vitalDetails->groupBy('patient_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caseId => $caseVitals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                                <?php
                                                                    $firstRecord = $caseVitals->first();
                                                                ?>
                                                                <tr>

                                                                    <td>
                                                                        <?php if(!empty($firstRecord->messure_date)): ?>
                                                                            <?php echo e(\Carbon\Carbon::parse($firstRecord->messure_date)->format('d/m/Y h:i A')); ?>

                                                                        <?php else: ?>
                                                                            -
                                                                        <?php endif; ?>
                                                                    </td>

                                                                    
                                                                    <?php $__currentLoopData = $vitals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vital): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php
                                                                            $record = $caseVitals
                                                                                ->where('vital_id', $vital->id)
                                                                                ->first();
                                                                        ?>
                                                                        <td>
                                                                            <?php echo e($record->reference_range ?? '-'); ?>

                                                                        </td>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                    <!-- <td>
                                                                                            <div class="d-flex gap-2">
                                                                                                <a href="#"
                                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                                                                    data-bs-toggle="tooltip" title="Show">
                                                                                                    <i class="ti ti-menu"></i>
                                                                                                </a>
                                                                                            </div>
                                                                                        </td> -->
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                                <tr>
                                                                    <td colspan="<?php echo e(4 + $vitals->count()); ?>"
                                                                        class="text-center text-muted">
                                                                        No vital records found
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
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

    
    <div class="modal fade" id="dischargeDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-clipboard-check"></i>
                        Discharge Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <!-- Common Details Section -->
                    <div class="section-card">
                        <div class="section-header primary">
                            <div class="section-icon primary">
                                <i class="bi bi-file-medical"></i>
                            </div>
                            <h6 class="section-title pb-0 mb-0">General Information</h6>
                        </div>
                        <div class="section-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-calendar-event"></i>
                                        Discharge Date
                                    </div>
                                    <div class="info-value" id="dc_discharge_date">January 15, 2025</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-check-circle"></i>
                                        Status
                                    </div>
                                    <div class="info-value">
                                        <span class="status-badge discharged" id="dc_status">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Discharged
                                        </span>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-bandaid"></i>
                                        Operation
                                    </div>
                                    <div class="info-value" id="dc_operation">Appendectomy</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-heart-pulse"></i>
                                        Diagnosis
                                    </div>
                                    <div class="info-value" id="dc_diagnosis">Acute Appendicitis</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-clipboard2-pulse"></i>
                                        Investigations
                                    </div>
                                    <div class="info-value" id="dc_investigations">CT Scan, Blood Tests, Ultrasound
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-house-heart"></i>
                                        Treatment Home
                                    </div>
                                    <div class="info-value" id="dc_treatment_home">Rest, prescribed medications,
                                        follow-up in 2 weeks</div>
                                </div>
                            </div>

                            <div class="info-grid full mt-3">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-journal-text"></i>
                                        Note
                                    </div>
                                    <div class="info-value long-text" id="dc_note">Patient recovered well
                                        post-surgery. No complications observed. Advised to continue medication and maintain
                                        proper diet. Follow-up required after 2 weeks.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Death Details Section -->
                    <div id="deathSection" class="section-card d-none">
                        <div class="section-header danger">
                            <div class="section-icon danger">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <h6 class="section-title pb-0 mb-0">Death Details</h6>
                        </div>
                        <div class="section-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-calendar-x"></i>
                                        Death Date
                                    </div>
                                    <div class="info-value" id="dc_death_date">--</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-person"></i>
                                        Guardian Name
                                    </div>
                                    <div class="info-value" id="dc_guardian_name">--</div>
                                </div>
                            </div>

                            <div class="info-grid full mt-3">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-file-earmark-text"></i>
                                        Report
                                    </div>
                                    <div class="info-value long-text" id="dc_report">--</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Details Section -->
                    <div id="referralSection" class="section-card d-none">
                        <div class="section-header warning">
                            <div class="section-icon warning">
                                <i class="bi bi-arrow-right-circle-fill"></i>
                            </div>
                            <h6 class="section-title pb-0 mb-0">Referral Details</h6>
                        </div>
                        <div class="section-body">
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-calendar-check"></i>
                                        Referral Date
                                    </div>
                                    <div class="info-value" id="dc_referral_date">--</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-hospital"></i>
                                        Hospital Name
                                    </div>
                                    <div class="info-value" id="dc_refer_to_hospital">--</div>
                                </div>
                            </div>

                            <div class="info-grid full mt-3">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-chat-left-text"></i>
                                        Reason for Referral
                                    </div>
                                    <div class="info-value long-text" id="dc_reason_for_referral">--</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-close-modal" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                        Close
                    </button>
                    <button type="button" class="btn btn-print">
                        <i class="bi bi-printer"></i>
                        Print Details
                    </button>
                </div>

            </div>
        </div>
    </div>

    
    <?php echo $__env->make('components.modals.discharge-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Chart JS -->
    <script src="assets/plugins/chartjs/chart.min.js"></script>
    <script src="assets/plugins/chartjs/chart-data.js"></script>
    <script>
        let operations = <?php echo json_encode($operations, 15, 512) ?>; // All operations from DB

        document.getElementById('operation_category').addEventListener('change', function() {

            let catId = this.value;
            let operationDropdown = document.getElementById('operation_type');

            // Clear old options
            operationDropdown.innerHTML = '<option value="">Select Operation</option>';

            if (catId) {
                operations.forEach(op => {
                    if (op.category_id == catId) {
                        operationDropdown.innerHTML += `<option value="${op.id}">${op.operation}</option>`;
                    }
                });
            }
        });
    </script>


    <script>
        $(document).on('click', '.editMedicationBtn', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_date').val($(this).data('date'));
            $('#edit_time').val($(this).data('time'));
            $('#edit_medi_cat').val($(this).data('cat'));
            $('#edit_med_name').val($(this).data('med'));
            $('#edit_dosage').val($(this).data('dose'));
            $('#edit_remark').val($(this).data('remark'));
        });
    </script>

    <script>
        let medicines = <?php echo json_encode($medicinesByCategory, 15, 512) ?>;
        let dosages = <?php echo json_encode($dosages, 15, 512) ?>; // grouped by medicine_id

        let mediCatDropdown = document.getElementById('medi_cat');
        let medDropdown = document.getElementById('med_name');
        let doseDropdown = document.getElementById('dosage');

        mediCatDropdown.addEventListener('change', function() {
            let categoryId = this.value;

            // Reset medicine dropdown
            medDropdown.innerHTML = '<option value="">Select</option>';
            doseDropdown.innerHTML = '<option value="">Select</option>';

            if (categoryId && medicines[categoryId]) {
                medicines[categoryId].forEach(med => {
                    medDropdown.innerHTML += `<option value="${med.id}">${med.medicine_name}</option>`;
                });
            }
        });

        // When user selects medicine, load its dosage
        medDropdown.addEventListener('change', function() {
            let medId = this.value;

            doseDropdown.innerHTML = '<option value="">Select</option>';

            if (medId && dosages[medId]) {
                dosages[medId].forEach(dose => {
                    doseDropdown.innerHTML += `<option value="${dose.id}">${dose.dosage}</option>`;
                });
            }
        });
    </script>



<script>
document.addEventListener("DOMContentLoaded", function () {

    const chargeTypeSelect     = document.getElementById("add_charge_type");
    const chargeCategorySelect = document.getElementById("charge_category2");
    const chargeSelect         = document.getElementById("charge_id");

    const standardChargeInp = document.getElementById("addstandard_charge");
    const tpaChargeInp      = document.getElementById("addscd_charge");
    const qtyInp            = document.getElementById("qty");
    const totalInp          = document.getElementById("apply_charge");
    const discountPercInp   = document.getElementById("discount_percentage_add_charge");
    const discountAmtInp    = document.getElementById("discount_percentage_amount");
    const taxPercInp        = document.getElementById("charge_tax");
    const taxAmtInp         = document.getElementById("tax_amt");
    const netAmountInp      = document.getElementById("final_amount");

    const previewBody = document.getElementById("preview_charges");
    const addBtn = document.querySelector("button[name='charge_data']");

    /*--------------------------------------------------
     | FETCH CHARGE TYPES
     --------------------------------------------------*/
    fetch("<?php echo e(route('getChargeTypes')); ?>")
        .then(res => res.json())
        .then(data => {
            window.chargeTypeData = data;
            chargeTypeSelect.innerHTML = `<option value="">Select</option>`;
            data.forEach(type => {
                chargeTypeSelect.innerHTML += `
                    <option value="${type.id}">${type.charge_type}</option>
                `;
            });
        });

    /*--------------------------------------------------
     | FETCH CATEGORIES BY TYPE
     --------------------------------------------------*/
    chargeTypeSelect.addEventListener("change", function () {

        chargeCategorySelect.innerHTML = `<option value="">Select</option>`;
        chargeSelect.innerHTML = `<option value="">Select</option>`;

        if (!this.value) return;

        fetch("<?php echo e(route('getChargeCategoriesByTypeId', ['id' => 'ID'])); ?>".replace('ID', this.value))
            .then(res => res.json())
            .then(data => {
                window.chargeCategoryData = data;
                data.forEach(cat => {
                    chargeCategorySelect.innerHTML += `
                        <option value="${cat.id}">${cat.name}</option>
                    `;
                });
            });
    });

    /*--------------------------------------------------
     | FETCH CHARGES BY CATEGORY
     --------------------------------------------------*/
    chargeCategorySelect.addEventListener("change", function () {

        chargeSelect.innerHTML = `<option value="">Select</option>`;

        if (!this.value) return;

        fetch("<?php echo e(route('getCharges', ['id' => 'ID'])); ?>".replace('ID', this.value))
            .then(res => res.json())
            .then(data => {
                window.chargeData = data;
                data.forEach(charge => {
                    chargeSelect.innerHTML += `
                        <option value="${charge.id}">${charge.name}</option>
                    `;
                });
            });
    });

    /*--------------------------------------------------
     | AUTO-FILL ON CHARGE SELECT (ONCE)
     --------------------------------------------------*/
    chargeSelect.addEventListener("change", function () {

        const chargeId = this.value;
        const selectedCharge = window.chargeData.find(c => c.id == chargeId);
        if (!selectedCharge) return;

        standardChargeInp.value = selectedCharge.standard_charge ?? 0;
        tpaChargeInp.value      = 0;
        qtyInp.value            = 1;
        discountPercInp.value   = 0;
        taxPercInp.value        = selectedCharge.tax_category?.percentage ?? 0;

        calculateAmount();
    });

    /*--------------------------------------------------
     | REAL-TIME CALCULATION (EDITABLE SAFE)
     --------------------------------------------------*/
    [
        standardChargeInp,
        qtyInp,
        discountPercInp,
        taxPercInp
    ].forEach(el => el.addEventListener("input", calculateAmount));

    function calculateAmount() {

        const standard = parseFloat(standardChargeInp.value) || 0;
        const qty      = parseFloat(qtyInp.value) || 1;

        const discountPerc = parseFloat(discountPercInp.value) || 0;
        const taxPerc      = parseFloat(taxPercInp.value) || 0;

        const appliedCharge = standard * qty;
        const discountAmt   = appliedCharge * (discountPerc / 100);
        const taxAmt        = appliedCharge * (taxPerc / 100);
        const netAmount     = appliedCharge + taxAmt - discountAmt;

        totalInp.value       = appliedCharge.toFixed(2);
        discountAmtInp.value = discountAmt.toFixed(2);
        taxAmtInp.value      = taxAmt.toFixed(2);
        netAmountInp.value   = netAmount.toFixed(2);
    }

    /*--------------------------------------------------
     | ADD ROW TO PREVIEW TABLE
     --------------------------------------------------*/
    addBtn.addEventListener("click", function (e) {
        e.preventDefault();

        if (!chargeTypeSelect.value || !chargeCategorySelect.value || !chargeSelect.value) {
            alert("Please fill required fields");
            return;
        }

        const row = `
        <tr>
            <td>${document.getElementById("charge_date").value}</td>
            <td>${chargeTypeSelect.selectedOptions[0].text}</td>
            <td>${chargeCategorySelect.selectedOptions[0].text}</td>
            <td>${chargeSelect.selectedOptions[0].text}<br>
                <small>${document.getElementById("edit_note").value}</small>
            </td>
            <td class="text-right">${standardChargeInp.value}</td>
            <td class="text-right">${tpaChargeInp.value}</td>
            <td class="text-right">${qtyInp.value}</td>
            <td class="text-right">${totalInp.value}</td>
            <td class="text-right">${discountAmtInp.value}</td>
            <td class="text-right">${taxAmtInp.value}</td>
            <td class="text-right">${netAmountInp.value}</td>
            <td class="text-right">
                <button type="button" class="btn btn-danger btn-sm delete-charge-row">X</button>
            </td>

            <input type="hidden" name="charge_type[]" value="${chargeTypeSelect.value}">
            <input type="hidden" name="charge_category[]" value="${chargeCategorySelect.value}">
            <input type="hidden" name="charge_id[]" value="${chargeSelect.value}">
            <input type="hidden" name="standard_charge[]" value="${standardChargeInp.value}">
            <input type="hidden" name="tpa_charge[]" value="${tpaChargeInp.value}">
            <input type="hidden" name="qty[]" value="${qtyInp.value}">
            <input type="hidden" name="total[]" value="${totalInp.value}">
            <input type="hidden" name="discount_percentage[]" value="${discountAmtInp.value}">
            <input type="hidden" name="tax[]" value="${taxAmtInp.value}">
            <input type="hidden" name="net_amount[]" value="${netAmountInp.value}">
            <input type="hidden" name="charge_note[]" value="${document.getElementById("edit_note").value}">
            <input type="hidden" name="charge_date[]" value="${document.getElementById("charge_date").value}">
        </tr>
        `;

        previewBody.insertAdjacentHTML("beforeend", row);

        document.getElementById("addChargeForm").reset();
        totalInp.value = discountAmtInp.value = taxAmtInp.value = netAmountInp.value = 0;
    });

    /*--------------------------------------------------
     | DELETE ROW
     --------------------------------------------------*/
    document.addEventListener("click", function (e) {
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
    <script>
        $(document).ready(function() {

            // Load bed groups on page load
            $.get("<?php echo e(route('getBedGroups')); ?>", function(data) {
                let options = '<option value="">Select Bed Group</option>';
                data.forEach(function(group) {
                    options +=
                        `<option value="${group.id}">${group.name} - ${group.floor_detail?.name ?? '-'}</option>`;
                });
                $('#bed_group').html(options);
            });

            // Load available beds when bed group changes
            $('#bed_group').on('change', function() {
                let groupId = $(this).val();
                $('#new_bed').html('<option value="">Loading...</option>');

                if (groupId) {
                    $.get("<?php echo e(route('get.available.beds')); ?>", {
                        bed_group_id: groupId
                    }, function(data) {
                        let options = '<option value="">Select New Bed</option>';
                        data.forEach(function(bed) {
                            options += `<option value="${bed.id}"> ${bed.name}</option>`;
                        });
                        $('#new_bed').html(options);
                    });
                } else {
                    $('#new_bed').html('<option value="">Select New Bed</option>');
                }
            });

        });
    </script>
    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: "Are you sure?",
                text: "This record will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {

                if (result.isConfirmed) {
                    console.log("DELETE URL:", url);
                    console.log("Form created:", form);
                    // Get CSRF token
                    const csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute("content");

                    // Create form
                    const form = document.createElement("form");
                    form.style.display = "none"; // keep it invisible
                    form.method = "POST";
                    form.action = url;

                    // Add hidden inputs
                    form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;

                    // Append to body
                    document.body.appendChild(form);

                    // Submit
                    form.submit();
                }
            });
        }
    </script>

    <script>
        const modal = document.getElementById('dischargeDetailsModal');

        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const data = JSON.parse(button.getAttribute('data-discharge'));

            // Common fields
            document.getElementById('dc_discharge_date').innerText = data.discharge_date ?? '-';
            document.getElementById('dc_operation').innerText = data.operation ?? '-';
            document.getElementById('dc_diagnosis').innerText = data.diagnosis ?? '-';
            document.getElementById('dc_investigations').innerText = data.investigations ?? '-';
            document.getElementById('dc_treatment_home').innerText = data.treatment_home ?? '-';
            document.getElementById('dc_note').innerText = data.note ?? '-';

            // Status badge
            const statusEl = document.getElementById('dc_status');
            statusEl.className = 'badge';

            if (data.discharge_status == "death") {
                statusEl.innerText = 'Death';
                statusEl.classList.add('bg-danger');
            } else if (data.discharge_status == "referral") {
                statusEl.innerText = 'Referral';
                statusEl.classList.add('bg-warning');
            } else {
                statusEl.innerText = 'Normal';
                statusEl.classList.add('bg-success');
            }

            // Hide sections initially
            document.getElementById('deathSection').classList.add('d-none');
            document.getElementById('referralSection').classList.add('d-none');

            // Death section
            if (data.discharge_status == "death") {
                document.getElementById('deathSection').classList.remove('d-none');
                document.getElementById('dc_death_date').innerText = data.death_date ?? '-';
                document.getElementById('dc_guardian_name').innerText = data.guardian_name ?? '-';
                document.getElementById('dc_report').innerText = data.report ?? '-';
            }

            // Referral section
            if (data.discharge_status == "referral") {
                document.getElementById('referralSection').classList.remove('d-none');
                document.getElementById('dc_referral_date').innerText = data.refer_date ?? '-';
                document.getElementById('dc_refer_to_hospital').innerText = data.refer_to_hospital ?? '-';
                document.getElementById('dc_reason_for_referral').innerText = data.reason_for_referral ?? '-';
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/ipd/ipd_view.blade.php ENDPATH**/ ?>