{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    {{-- Hidden element for back-dated prescription: admission date used when Add Prescription modal opens --}}
    <div id="ipdViewContext" data-admission-date="{{ $ipd->date ? \Carbon\Carbon::parse($ipd->date)->format('Y-m-d') : '' }}"
        data-ipd-id="{{ $ipd->id ?? '' }}" style="display:none"></div>
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
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
        @if (session('success'))
            <script>
                (function() {
                    var title = @json(session('alertTitle') ?? 'Success');
                    var text = @json(session('success'));
                    if (typeof Swal !== 'undefined') Swal.fire({
                        icon: 'success',
                        title: title,
                        text: text
                    });
                })();
            </script>
        @endif
        @if (session('error'))
            <script>
                (function() {
                    var title = @json(session('alertTitle') ?? 'Error');
                    var text = @json(session('error'));
                    if (typeof Swal !== 'undefined') Swal.fire({
                        icon: 'error',
                        title: title,
                        text: text
                    });
                })();
            </script>
        @endif
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
                    <a href="#radiology_reports" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-flask text-primary pe-1"></i>
                        <span>Radiology Details</span>
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
                    <a href="#packages" data-bs-toggle="tab" aria-expanded="true"
                        class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                            class="fa-solid fa-gift text-primary pe-1"></i>
                        <span>Packages</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                        <a href="#payments" data-bs-toggle="tab" aria-expanded="true"
                            class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                                class="fa-solid fa-hand-holding-dollar text-primary pe-1"></i>
                            <span>Payments</span>
                        </a>
                    </li> -->
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
                @if ($ipd->discharged != 'yes')
                    <li class="nav-item">
                        <a href="#bed_issue" data-bs-toggle="tab" aria-expanded="true"
                            class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                                class="fa-solid fa-bed text-primary pe-1"></i>
                            <span>Bed Transfer</span>
                        </a>
                    </li>
                @endif
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
                                        {{ $ipd->patient->patient_name }}
                                    </h5>
                                    @if ($ipd->discharged == 'yes' || $ipd->discharged == 'draft')
                                        <div class="d-flex align-items-center">
                                        @if ($ipd->discharged == 'yes' && empty($ipd->final_bill_generated_at))
                                            <span class="badge bg-info me-2">Bed occupied until final bill</span>
                                        @endif
                                        <button class="border-0 text-white"
                                            style="background-color: #750096;padding: 0.5rem;border-radius: 8px;"
                                            data-bs-toggle="modal" data-bs-target="#dischargePreviewModal"
                                            data-discharge='@json($ipd->dischargeCard)'
                                            data-medicines='@json($ipd->discharge_medicines)'><i
                                                class="bi bi-clipboard-pulse text-white"></i>
                                            Discharge</button>
                                        </div>
                                    @else
                                        <button class="border-0 text-white" style="background-color: #750096;padding: 0.5rem;border-radius: 8px;" data-bs-toggle="modal"
                                            data-bs-target="#patientDischargeModal" data-ipd="{{ $ipd }}"
                                            data-doctors="{{ $doctors }}" data-user="{{ $currentUser }}"
                                            data-departments="{{ $departments }}"
                                            data-outstanding={{ $billingSummary['outstanding'] ?? 0 }}><i
                                                class="bi bi-clipboard-pulse text-white"></i> Discharge</button>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-sm-flex position-relative z-0 overflow-hidden p-2">
                                    <!-- <img src="assets/img/icons/shape-01.svg" alt="img"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            class="z-n1 position-absolute end-0 top-0 d-none d-lg-flex"> -->
                                    <a href="javascript:void(0);"
                                        class="avatar avatar-xxxl patient-avatar me-2 flex-shrink-0">
                                        <img src="{{ asset('assets/img/patient.png') }}" alt="product" class="rounded">
                                    </a>
                                    <div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-phone text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Phone :</h6>
                                                <p class="patient_data mb-0">{{ $ipd->patient->mobileno }}</p>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-calendar-days text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Age :</h6>
                                                <p class="patient_data mb-0">{{ $ipd->patient->age }} Year
                                                    {{ $ipd->patient->month }} Month {{ $ipd->patient->day }} Days (As
                                                    Of
                                                    {{ \Carbon\Carbon::parse($ipd->patient->as_of_date)->format('d/m/Y') }})
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-hands-holding-child text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Guardian Name :</h6>
                                                <p class="patient_data mb-0">{{ $ipd->patient->guardian_name ?? '--' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-mars-and-venus text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Gender :</h6>
                                                <p class="patient_data mb-0">{{ $ipd->patient->gender }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-users-gear text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">TPA :</h6>
                                                <p class="patient_data mb-0">
                                                    {{ $ipd->patient->organisation->organisation_name ?? '--' }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-id-badge text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">TPA ID :</h6>
                                                <p class="patient_data mb-0">
                                                    {{ $ipd->patient->organisation->code ?? '--' }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-user-check text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">TPA Validity :</h6>
                                                <p class="patient_data mb-0">{{ $ipd->patient->tpa_validity ?? '--' }}</p>
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
                                        <p class="patient_data mb-0">{{ $ipd->known_allergies ?? '--' }}</p>
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
                                            @foreach ($symptoms as $symptom)
                                                <li><i class="fa-regular fa-circle-check text-primary"></i>
                                                    {{ $symptom->symptoms_title }}</li>
                                            @endforeach
                                        </ul>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Finance / Billing Summary Section --}}
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-wallet me-2"></i> Finance
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 bg-light">
                                            <div class="text-muted small mb-1">Total Billing (INR)</div>
                                            <div class="fw-bold fs-5">
                                                {{ number_format($billingSummary['total_charges'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 bg-light">
                                            <div class="text-muted small mb-1">Total Payment (INR)</div>
                                            <div class="fw-bold fs-5 text-success">
                                                {{ number_format($billingSummary['total_payments'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 bg-light">
                                            <div class="text-muted small mb-1">Total Outstanding (INR)</div>
                                            <div
                                                class="fw-bold fs-5 {{ ($billingSummary['outstanding'] ?? 0) > 0 ? 'text-danger' : '' }}">
                                                {{ number_format($billingSummary['outstanding'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Billing breakdown (always show; includes Package) --}}
                                @php
                                    $bs = $billingSummary ?? [];
                                    $bedCh = $bs['bed_charges'] ?? 0;
                                    $pkgCh = $bs['package_charges'] ?? 0;
                                    $ipdCh = $bs['ipd_charges'] ?? 0;
                                    $pathCh = $bs['pathology_charges'] ?? 0;
                                    $radCh = $bs['radiology_charges'] ?? 0;
                                    $docCh = $bs['doctor_visit_charges'] ?? 0;
                                @endphp
                                <div class="mt-3 pt-3 border-top">
                                    <h6 class="text-muted small mb-2">Billing breakdown</h6>
                                    <div class="row g-2 small">
                                        <div class="col-md-4 col-6"><span class="text-muted">Bed charges:</span>
                                            ₹{{ number_format($bedCh, 2) }}</div>
                                        <div class="col-md-4 col-6"><span class="text-muted">Package:</span>
                                            ₹{{ number_format($pkgCh, 2) }}</div>
                                        <div class="col-md-4 col-6"><span class="text-muted">IPD charges:</span>
                                            ₹{{ number_format($ipdCh, 2) }}</div>
                                        <div class="col-md-4 col-6"><span class="text-muted">Pathology:</span>
                                            ₹{{ number_format($pathCh, 2) }}</div>
                                        <div class="col-md-4 col-6"><span class="text-muted">Radiology:</span>
                                            ₹{{ number_format($radCh, 2) }}</div>
                                        <div class="col-md-4 col-6"><span class="text-muted">Doctor visit:</span>
                                            ₹{{ number_format($docCh, 2) }}</div>
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
                                                <img src="{{ asset('assets/img/patient.png') }}" alt="product"
                                                    class="rounded">
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fs-13 fw-bold mb-1">
                                                    {{ $ipd->doctor ? $ipd->doctor->name . '(' . $ipd->doctor->doctor_id . ')' : '--' }}
                                                </h6>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                {{-- <hr>
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

                                </div> --}}
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Doctor Visit
                                    Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Doctor Name</th>
                                                <th>Rate</th>
                                                <th>No. of Visit</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Entry</th>
                                                <th>Visit Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($doctorvisits as $doctorvisit)
                                                <tr>
                                                    <td>
                                                        {{ $doctorvisit->doctor->name ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $doctorvisit->rate ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $doctorvisit->no_of_visit ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $doctorvisit->amount ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $doctorvisit->date ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $doctorvisit->time ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $doctorvisit->entry ?? '-' }}
                                                    </td>

                                                    <td class="text-end">
                                                        {{ $doctorvisit->visit_type ?? '-' }}
                                                    </td>


                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        No payments found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
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
                                    @foreach ($nurseNotes as $note)
                                        <div class="timeline-item">
                                            <div class="timeline-date">
                                                <div class="date-badge">
                                                    {{ \Carbon\Carbon::parse($note->date)->format('d/m/Y') }}

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
                                                            {{ $note->staff->name }}
                                                        </h5>

                                                    </div>

                                                </div>
                                                <div class="timeline-body">

                                                    <p class="lh-base"><strong>Note</strong> <br>
                                                        {{ $note->note }}</p>
                                                    <p class="lh-base"><strong>Comment</strong> <br>
                                                        {{ $note->comment }}
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

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
                                            @foreach ($medicationReport as $medication)
                                                <tr>
                                                    <td>{{ $medication->date }}</td>
                                                    <td>{{ $medication->pharmacy->medicine_name }}</td>
                                                    <td>{{ $medication->medicineDosage->dosage }}
                                                        {{ $medication->medicineDosage->unit->unit_name }}
                                                    </td>
                                                    <td>{{ $medication->time }}</td>
                                                    <td>{{ $medication->remark }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                        {{-- prescription --}}
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
                                                @if ($ipd->discharged != 'yes')
                                                    <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ipdPrescriptions as $prescription)
                                                <tr>
                                                    <td>
                                                        <h6 class="fs-14 mb-1">
                                                            {{ $prescription->prescription_number }}</h6>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($prescription->date)->format('d/m/Y') }}
                                                    </td>
                                                    <td>--</td>
                                                    <td>--</td>
                                                    @if ($ipd->discharged != 'yes')
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <a href="{{ route('ipd.prescription.edit', $prescription->id) }}"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-warning rounded-pill"
                                                                    data-bs-toggle="tooltip" title="Edit">
                                                                    <i class="fa-solid fa-pencil"></i>
                                                                </a>
                                                                <a href="{{ route('ipd.prescription.print', $prescription->id) }}"
                                                                    target="_blank"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                    data-bs-toggle="tooltip" title="Print">
                                                                    <i class="fa-solid fa-print"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                        {{-- prescription End --}}



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
                                            @foreach ($labInvestigations as $lab)
                                                <tr>
                                                    <td>
                                                        {{ optional($lab->pathology)->test_name
                                                            ? optional($lab->pathology)->test_name .
                                                                (optional($lab->pathology)->short_name ? ' (' . optional($lab->pathology)->short_name . ')' : '')
                                                            : 'N/A' }}
                                                    </td>

                                                    <td>Pathology</td>
                                                    <td>{{ '--' }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::today()->addDays((int) ($lab->pathology?->report_days ?? 0))->format('d-M-Y') }}
                                                    </td>
                                                    <td>{{ $lab->approved_by ?? '--' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table end -->
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Radiology
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Test Name</th>
                                                <th>Radiology</th>
                                                <th>Sample Collected</th>
                                                <th>Expected Date</th>
                                                <th>Approved By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($radiologyReports as $lab)
                                                <tr>
                                                    <td>
                                                        {{ $lab->radiology->test_name . ($lab->radiology->short_name ? ' (' . $lab->radiology->short_name . ')' : '') }}
                                                    </td>
                                                    <td>Radiology</td>
                                                    <td>{{ '--' }}</td>
                                                    <td>{{ \Carbon\Carbon::today()->copy()->addDays(intval($lab->radiology->report_days))->format('d-M-Y') }}
                                                    </td>
                                                    <td>{{ $lab->approved_by ?? '--' }}</td>
                                                </tr>
                                            @endforeach
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
                                            @foreach ($operationDetail as $operation)
                                                <tr>
                                                    <td>
                                                        <h6 class="fs-14 mb-1">
                                                            {{ $operation->reference_no }}
                                                        </h6>
                                                    </td>
                                                    <td>{{ $operation->date }}</td>
                                                    <td>{{ $operation->operation->operation }}</td>
                                                    <td>{{ $operation->operation->category->category }}
                                                    </td>
                                                    <td>{{ $operation->ot_technician }}</td>

                                                </tr>
                                            @endforeach
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
                                                <!-- <th>Tax</th> -->
                                                <th>Applied Charge (INR)</th>
                                                <th>Amount (INR)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ipdCharges as $charge)
                                                @php
                                                    $standardCharge =
                                                        $charge->charge?->standard_charge ??
                                                        ($charge->standard_charge ?? 0);
                                                    $taxPct = $charge->charge?->taxCategory?->percentage ?? 0;
                                                    $taxAmount = ($standardCharge * $taxPct) / 100;
                                                    $amount =
                                                        ($charge->standard_charge ?? $standardCharge) + $taxAmount;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $charge->charge?->name ?? '-' }}
                                                    </td>
                                                    <td style="text-transform: capitalize;">
                                                        {{ $charge->chargeCategory?->chargeType?->charge_type ?? '-' }}
                                                    </td>
                                                    <td class="text-right">{{ $charge->standard_charge ?? '-' }}</td>
                                                    <!-- <td class="text-right">
                                                            ({{ $charge->charge->taxCategory->percentage ?? '-' }}%)
    {{ $taxAmount }}
                                                        </td> -->
                                                    <td class="text-right">{{ $charge->standard_charge ?? '-' }}</td>
                                                    <td class="text-right">{{ $amount }}</td>
                                                </tr>
                                            @endforeach
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
                                                <th class="text-end">Paid Amount (INR)</th>
                                                <th class="text-center">Money Receipt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($transactions as $transaction)
                                                <tr>
                                                    <td>
                                                        {{ $transaction->transaction_no ?? 'TRID' . $transaction->id }}
                                                    </td>

                                                    <td>
                                                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y h:i A') }}
                                                    </td>

                                                    <td>
                                                        {{ $transaction->note ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $transaction->payment_mode }}
                                                    </td>

                                                    <td class="text-end">
                                                        {{ number_format($transaction->amount, 2) }}
                                                    </td>

                                                    <td class="text-center">
                                                        @if (!empty($transaction->receipt_no))
                                                            <a href="{{ route('money-receipt.print', $transaction->id) }}"
                                                                class="btn btn-sm btn-primary" target="_blank"
                                                                title="Print Money Receipt">
                                                                <i class="ti ti-printer"></i>
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        No payments found
                                                    </td>
                                                </tr>
                                            @endforelse
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
                                                        @if ($ipd->discharged != 'yes')
                                                            <div class="text-end d-flex">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-primary text-white ms-2 btn-md"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#add_appointment"><i
                                                                        class="ti ti-plus me-1"></i>Add New Note</a>
                                                            </div>
                                                        @endif
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
                                                                    <form action="{{ route('nurseNote.store') }}"
                                                                        method="post">
                                                                        @csrf

                                                                        <div class="modal-body">

                                                                            <div class="row gy-3 py-4 mx-1">
                                                                                <input type="hidden" name="ipd_id"
                                                                                    value="{{ $ipd->id }}">
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
                                                    @foreach ($nurseNotes as $note)
                                                        <div class="timeline-item">
                                                            <div class="timeline-date">
                                                                <div class="date-badge">
                                                                    {{ \Carbon\Carbon::parse($note->date)->format('d/m/Y') }}

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
                                                                            {{ $note->staff->name }}
                                                                        </h5>

                                                                    </div>
                                                                    @if ($ipd->discharged != 'yes')
                                                                        <div class="timeline-actions"
                                                                            aria-label="Edit or delete step">
                                                                            <a href="javascript: void(0);"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-info rounded-pill">
                                                                                <i class="ti ti-pencil"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="Edit"></i></a>
                                                                            <a href="javascript: void(0);"
                                                                                class="fs-18 btn btn-icon btn-sm btn-danger rounded-pill">
                                                                                <i class="ti ti-trash"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="Delete"></i></a>

                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="timeline-body">

                                                                    <p class="lh-base"><strong>Note</strong> <br>
                                                                        {{ $note->note }}</p>
                                                                    <p class="lh-base"><strong>Comment</strong> <br>
                                                                        {{ $note->comment }}
                                                                    </p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

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
                                                        @if ($ipd->discharged != 'yes')
                                                            <div class="text-end d-flex">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-primary text-white ms-2 btn-md"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#add_medication"><i
                                                                        class="ti ti-plus me-1"></i>Add Medication Dose</a>
                                                            </div>
                                                        @endif
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
                                                                        action="{{ route('medication.store') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="ipd_id"
                                                                            value="{{ $ipd->id }}">
                                                                        <div class="modal-body">
                                                                            <div class="row gy-3 py-4 mx-1">

                                                                                {{-- Date --}}
                                                                                <div class="col-md-6">
                                                                                    <label for="date"
                                                                                        class="form-label">Date <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="date" name="date"
                                                                                        id="date"
                                                                                        value="{{ old('date') }}"
                                                                                        class="form-control @error('date') is-invalid @enderror">
                                                                                    @error('date')
                                                                                        <div class="text-danger small">
                                                                                            {{ $message }}</div>
                                                                                    @enderror
                                                                                </div>

                                                                                {{-- Time --}}
                                                                                <div class="col-md-6">
                                                                                    <label for="time"
                                                                                        class="form-label">Time <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="time" name="time"
                                                                                        id="time"
                                                                                        value="{{ old('time') }}"
                                                                                        class="form-control @error('time') is-invalid @enderror">
                                                                                    @error('time')
                                                                                        <div class="text-danger small">
                                                                                            {{ $message }}</div>
                                                                                    @enderror
                                                                                </div>

                                                                                {{-- Medicine Category --}}
                                                                                <div class="col-md-6">
                                                                                    <label for="medi_cat"
                                                                                        class="form-label">Medicine
                                                                                        Category <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select name="medi_cat" id="medi_cat"
                                                                                        class="form-select @error('medi_cat') is-invalid @enderror">
                                                                                        <option value="">Select
                                                                                        </option>
                                                                                        @foreach ($medicineCategories as $cat)
                                                                                            <option
                                                                                                value="{{ $cat->id }}"
                                                                                                {{ old('medi_cat') == $cat->id ? 'selected' : '' }}>
                                                                                                {{ $cat->medicine_category }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    @error('medi_cat')
                                                                                        <div class="text-danger small">
                                                                                            {{ $message }}</div>
                                                                                    @enderror
                                                                                </div>

                                                                                {{-- Medicine Name (filtered by category via JS if needed) --}}
                                                                                <div class="col-md-6">
                                                                                    <label for="med_name"
                                                                                        class="form-label">Medicine Name
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select name="med_name" id="med_name"
                                                                                        class="form-select @error('med_name') is-invalid @enderror">
                                                                                        <option value="">Select
                                                                                        </option>
                                                                                    </select>
                                                                                    @error('med_name')
                                                                                        <div class="text-danger small">
                                                                                            {{ $message }}</div>
                                                                                    @enderror
                                                                                </div>

                                                                                {{-- Dosage --}}
                                                                                <div class="col-md-6">
                                                                                    <label for="dosage"
                                                                                        class="form-label">Dosage <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select name="dosage" id="dosage"
                                                                                        class="form-select @error('dosage') is-invalid @enderror">
                                                                                        <option value="">Select
                                                                                        </option>

                                                                                    </select>
                                                                                    @error('dosage')
                                                                                        <div class="text-danger small">
                                                                                            {{ $message }}</div>
                                                                                    @enderror
                                                                                </div>

                                                                                {{-- Remarks --}}
                                                                                <div class="col-md-6">
                                                                                    <label for="remark"
                                                                                        class="form-label">Remarks</label>
                                                                                    <textarea name="remark" id="remark" class="form-control">{{ old('remark') }}</textarea>
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
                                                                @if ($ipd->discharged != 'yes')
                                                                    <th>Action</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($medicationReport as $medication)
                                                                <tr>
                                                                    <td>{{ $medication->date }}</td>
                                                                    <td>{{ $medication->pharmacy->medicine_name }}</td>
                                                                    <td>{{ $medication->medicineDosage->dosage }}
                                                                        {{ $medication->medicineDosage->unit->unit_name }}
                                                                    </td>
                                                                    @if ($ipd->discharged != 'yes')
                                                                        <td>
                                                                            <div class="d-flex gap-2">
                                                                                <a href="javascript:void(0);"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill editMedicationBtn"
                                                                                    data-id="{{ $medication->id }}"
                                                                                    data-date="{{ $medication->date }}"
                                                                                    data-time="{{ $medication->time }}"
                                                                                    data-cat="{{ $medication->pharmacy->medicine_category_id }}"
                                                                                    data-med="{{ $medication->pharmacy_id }}"
                                                                                    data-dose="{{ $medication->medicine_dosage_id }}"
                                                                                    data-remark="{{ $medication->remark }}"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#edit_medication">
                                                                                    <i class="ti ti-pencil"></i>
                                                                                </a>
                                                                                <!-- <a href="javascript:void(0);"
                                                                                                                    onclick="confirmDelete('{{ route('medication.delete', $medication->id) }}')"
                                                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                                                                        <i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i>
                                                                                                                </a> -->

                                                                            </div>
                                                                        </td>
                                                                    @endif
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
                                                                                action="{{ route('medication.update') }}">
                                                                                @csrf
                                                                                @method('PUT')

                                                                                <input type="hidden" name="id"
                                                                                    id="edit_id">
                                                                                <input type="hidden" name="ipd_id"
                                                                                    value="{{ $ipd->id }}">

                                                                                <div class="modal-body">
                                                                                    <div class="row gy-3 py-4 mx-1">

                                                                                        {{-- Date --}}
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Date</label>
                                                                                            <input type="date"
                                                                                                name="date"
                                                                                                id="edit_date"
                                                                                                class="form-control">
                                                                                        </div>

                                                                                        {{-- Time --}}
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Time</label>
                                                                                            <input type="time"
                                                                                                name="time"
                                                                                                id="edit_time"
                                                                                                class="form-control">
                                                                                        </div>

                                                                                        {{-- Category --}}
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Medicine
                                                                                                Category</label>
                                                                                            <select name="medi_cat"
                                                                                                id="edit_medi_cat"
                                                                                                class="form-select">
                                                                                                <option value="">
                                                                                                    Select</option>
                                                                                                @foreach ($medicineCategories as $cat)
                                                                                                    <option
                                                                                                        value="{{ $cat->id }}">
                                                                                                        {{ $cat->medicine_category }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        {{-- Medicine --}}
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Medicine
                                                                                                Name</label>
                                                                                            <select name="med_name"
                                                                                                id="edit_med_name"
                                                                                                class="form-select">
                                                                                                @foreach ($pharmacyDetails as $med)
                                                                                                    <option
                                                                                                        value="{{ $med->id }}">
                                                                                                        {{ $med->medicine_name }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        {{-- Dosage --}}
                                                                                        <div class="col-md-6">
                                                                                            <label
                                                                                                class="form-label">Dosage</label>
                                                                                            <select name="dosage"
                                                                                                id="edit_dosage"
                                                                                                class="form-select">
                                                                                                @foreach ($medDosages as $dose)
                                                                                                    <option
                                                                                                        value="{{ $dose->id }}">
                                                                                                        {{ $dose->dosage }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        {{-- Remarks --}}
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
                                                            @endforeach
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
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul class="mb-0">
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

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
                                                            @foreach ($labInvestigations as $lab)
                                                                <tr>
                                                                    <td>
                                                                        @php $p = $lab->pathology; @endphp

                                                                        {{ $p?->test_name ? $p->test_name . ($p->short_name ? ' (' . $p->short_name . ')' : '') : 'N/A' }}
                                                                    </td>
                                                                    <td>Pathology</td>
                                                                    <td>{{ '--' }}</td>
                                                                    <td>
                                                                        {{ \Carbon\Carbon::today()->copy()->addDays(intval($lab->pathology?->report_days ?? 0))->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>{{ $lab->approved_by ?? '--' }}</td>
                                                                    <td>
                                                                        <div class="d-flex gap-2">
                                                                            <!-- Edit -->
                                                                            @if ($ipd->discharged != 'yes')
                                                                                <a href="javascript:void(0);"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-warning rounded-pill editLabBtn"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#addPathLabModal"
                                                                                    data-lab-id="{{ $lab->id }}"
                                                                                    title="Edit">
                                                                                    <i class="ti ti-edit"></i>
                                                                                </a>
                                                                            @endif
                                                                            <!-- Download -->
                                                                            @if ($lab->path_doc_path)
                                                                                <a href="{{ route('path.report.download', $lab->id) }}"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                                    title="Download" download>
                                                                                    <i class="ti ti-download"></i>
                                                                                </a>
                                                                            @else
                                                                                <a href="javascript:void(0);"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill disabled"
                                                                                    title="No file available">
                                                                                    <i class="ti ti-download"></i>
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @include('components.modals.add-pathlab-report')
                                                            @endforeach
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
            <div class="tab-pane" id="radiology_reports">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Radiology
                                    Reports
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
                                                    <div class="text-end d-flex">
                                                        {{-- This tab is for radiology; bed-history actions must not appear here --}}
                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Test Name</th>
                                                                <th>Radiology</th>
                                                                <th>Sample Collected</th>
                                                                <th>Expected Date</th>
                                                                <th>Approved By</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($radiologyReports as $lab)
                                                                <tr>
                                                                    <td>
                                                                        {{ $lab->radiology->test_name . ($lab->radiology->short_name ? ' (' . $lab->radiology->short_name . ')' : '') }}
                                                                    </td>
                                                                    <td>Radiology</td>
                                                                    <td>{{ '--' }}</td>
                                                                    <td>{{ \Carbon\Carbon::today()->copy()->addDays(intval($lab->radiology->report_days))->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>{{ $lab->approved_by ?? '--' }}</td>
                                                                    <td>
                                                                        <div class="d-flex gap-2">
                                                                            <!-- Edit -->
                                                                            @if ($ipd->discharged != 'yes')
                                                                                <a href="javascript:void(0);"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-warning rounded-pill editLabBtn"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#addRadioLabModal"
                                                                                    data-lab-id="{{ $lab->id }}"
                                                                                    title="Edit">
                                                                                    <i class="ti ti-edit"></i>
                                                                                </a>
                                                                            @endif

                                                                            <!-- Download -->
                                                                            @if ($lab->radio_doc_path)
                                                                                <a href="{{ route('radio.report.download', $lab->id) }}"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                                    title="Download" download>
                                                                                    <i class="ti ti-download"></i>
                                                                                </a>
                                                                            @else
                                                                                <a href="javascript:void(0);"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill disabled"
                                                                                    title="No file available">
                                                                                    <i class="ti ti-download"></i>
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @include('components.modals.add-radlab-report')
                                                            @endforeach



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

                                                    @if ($ipd->discharged != 'yes')
                                                        <!-- Add Operation Button -->
                                                        <button type="button" class="btn btn-primary shadow-sm"
                                                            data-bs-toggle="modal" data-bs-target="#addOperationModal">
                                                            <i class="ti ti-plus me-1"></i> Add Operation
                                                        </button>
                                                    @endif

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
                                                                <form action="{{ route('operation.store') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="text" name="ipd_details_id"
                                                                        class="form-control"
                                                                        value="{{ $ipd->id }}" hidden>
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
                                                                                @foreach ($operationCategories as $cat)
                                                                                    <option value="{{ $cat->id }}">
                                                                                        {{ $cat->category }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>


                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Operations</label>
                                                                            <select name="operation_id"
                                                                                id="operation_type" class="form-select">
                                                                                <option value="">Select Operation
                                                                                </option>
                                                                                {{-- Options will be populated via JS --}}
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
                                                                                @foreach ($doctors as $doctor)
                                                                                    <option value="{{ $doctor->id }}">
                                                                                        {{ $doctor->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Assistant Consultant
                                                                                1</label>
                                                                            <!-- <input type="text"
                                                                                    name="ass_consultant_1"
                                                                                    class="form-control"> -->
                                                                            <select name="ass_consultant_1"
                                                                                class="form-select">
                                                                                <option value="">Select Assistant
                                                                                    Consultant 2
                                                                                </option>
                                                                                @foreach ($doctors as $doctor)
                                                                                    <option value="{{ $doctor->id }}">
                                                                                        {{ $doctor->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Assistant Consultant
                                                                                2</label>

                                                                            <select name="ass_consultant_2"
                                                                                class="form-select">
                                                                                <option value="">Select Assistant
                                                                                    Consultant 2
                                                                                </option>
                                                                                @foreach ($doctors as $doctor)
                                                                                    <option value="{{ $doctor->id }}">
                                                                                        {{ $doctor->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-4 mb-3">
                                                                            <label class="form-label">Anesthetist</label>

                                                                            <select name="anesthetist"
                                                                                class="form-select">
                                                                                <option value="">Select Anesthetist
                                                                                </option>
                                                                                @foreach ($doctors as $doctor)
                                                                                    <option value="{{ $doctor->id }}">
                                                                                        {{ $doctor->name }}</option>
                                                                                @endforeach
                                                                            </select>
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
                                                                @if ($ipd->discharged != 'yes')
                                                                    <th>Action</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($operationDetail as $operation)
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">
                                                                            {{ $operation->reference_no }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>{{ $operation->date }}</td>
                                                                    <td>{{ $operation->operation->operation }}</td>
                                                                    <td>{{ $operation->operation->category->category }}
                                                                    </td>
                                                                    <td>{{ $operation->ot_technician }}</td>
                                                                    @if ($ipd->discharged != 'yes')
                                                                        <td>
                                                                            <div class="d-flex gap-2">
                                                                                <a href="#" data-bs-toggle="modal"
                                                                                    data-bs-target="#editOperationModal{{ $operation->id }}"
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
                                                                    @endif
                                                                </tr>
                                                                <!-- EDIT OPERATION MODAL -->
                                                                <div class="modal fade"
                                                                    id="editOperationModal{{ $operation->id }}"
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
                                                                                    action="{{ route('operation.update', $operation->id) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <input type="text"
                                                                                        name="ipd_details_id"
                                                                                        class="form-control"
                                                                                        value="{{ $ipd->id }}"
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
                                                                                                    {{ $operation->customer_type == 'General' ? 'selected' : '' }}>
                                                                                                    General</option>
                                                                                                <option value="VIP"
                                                                                                    {{ $operation->customer_type == 'VIP' ? 'selected' : '' }}>
                                                                                                    VIP</option>
                                                                                                <option value="Corporate"
                                                                                                    {{ $operation->customer_type == 'Corporate' ? 'selected' : '' }}>
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
                                                                                                @foreach ($operationCategories as $cat)
                                                                                                    <option
                                                                                                        value="{{ $cat->id }}"
                                                                                                        {{ $operation->operation->category_id == $cat->id ? 'selected' : '' }}>
                                                                                                        {{ $cat->category }}
                                                                                                    </option>
                                                                                                @endforeach
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
                                                                                                @foreach ($operations as $op)
                                                                                                    <option
                                                                                                        value="{{ $op->id }}"
                                                                                                        {{ $operation->operation_id == $op->id ? 'selected' : '' }}>
                                                                                                        {{ $op->operation }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Operation
                                                                                                Date & Time</label>
                                                                                            <input type="datetime-local"
                                                                                                name="date"
                                                                                                class="form-control"
                                                                                                value="{{ \Carbon\Carbon::parse($operation->date)->format('Y-m-d\TH:i') }}"
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
                                                                                                @foreach ($doctors as $doctor)
                                                                                                    <option
                                                                                                        value="{{ $doctor->id }}"
                                                                                                        {{ $operation->consultant_doctor == $doctor->id ? 'selected' : '' }}>
                                                                                                        {{ $doctor->name }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Assistant
                                                                                                Consultant 1</label>

                                                                                            <select
                                                                                                name="ass_consultant_1"
                                                                                                class="form-select">
                                                                                                <option value="">
                                                                                                    Select Consultant
                                                                                                </option>
                                                                                                @foreach ($doctors as $doctor)
                                                                                                    <option
                                                                                                        value="{{ $doctor->id }}"
                                                                                                        {{ $operation->ass_consultant_1 == $doctor->id ? 'selected' : '' }}>
                                                                                                        {{ $doctor->name }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Assistant
                                                                                                Consultant 2</label>
                                                                                            <select
                                                                                                name="ass_consultant_2"
                                                                                                class="form-select">
                                                                                                <option value="">
                                                                                                    Select Consultant
                                                                                                </option>
                                                                                                @foreach ($doctors as $doctor)
                                                                                                    <option
                                                                                                        value="{{ $doctor->id }}"
                                                                                                        {{ $operation->ass_consultant_2 == $doctor->id ? 'selected' : '' }}>
                                                                                                        {{ $doctor->name }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Anesthetist</label>

                                                                                            <select name="anesthetist"
                                                                                                class="form-select">
                                                                                                <option value="">
                                                                                                    Select Consultant
                                                                                                </option>
                                                                                                @foreach ($doctors as $doctor)
                                                                                                    <option
                                                                                                        value="{{ $doctor->id }}"
                                                                                                        {{ $operation->ass_consultant_2 == $doctor->id ? 'selected' : '' }}>
                                                                                                        {{ $doctor->name }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Anaesthesia
                                                                                                Type</label>
                                                                                            <input type="text"
                                                                                                name="anaethesia_type"
                                                                                                class="form-control"
                                                                                                value="{{ $operation->anaethesia_type }}">
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label class="form-label">OT
                                                                                                Technician</label>
                                                                                            <input type="text"
                                                                                                name="ot_technician"
                                                                                                class="form-control"
                                                                                                value="{{ $operation->ot_technician }}">
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label class="form-label">OT
                                                                                                Assistant</label>
                                                                                            <input type="text"
                                                                                                name="ot_assistant"
                                                                                                class="form-control"
                                                                                                value="{{ $operation->ot_assistant }}">
                                                                                        </div>

                                                                                        <div class="col-md-4 mb-3">
                                                                                            <label
                                                                                                class="form-label">Result</label>
                                                                                            <input type="text"
                                                                                                name="result"
                                                                                                class="form-control"
                                                                                                value="{{ $operation->result }}">
                                                                                        </div>

                                                                                        <div class="col-md-12 mb-3">
                                                                                            <label
                                                                                                class="form-label">Remark</label>
                                                                                            <textarea name="remark" rows="3" class="form-control">{{ $operation->remark }}</textarea>
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
                                                            @endforeach
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
                                                        @if ($ipd->discharged != 'yes')
                                                            <div class="text-end d-flex">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-primary text-white ms-2 btn-md"
                                                                    data-bs-toggle="modal" data-bs-target="#add_charges"
                                                                    id="open_add_charges_btn"><i
                                                                        class="ti ti-plus me-1"></i>Add Charges</a>
                                                            </div>
                                                        @endif
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

                                                                    <form action="{{ route('ipd.addIpdCharge') }}"
                                                                        method="POST" id="addChargeForm">
                                                                        @csrf
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
                                                                                                    value="{{ $ipd->id }}">
                                                                                                <select name="charge_type"
                                                                                                    id="add_charge_type"
                                                                                                    class="form-control charge_type reset_value"
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
                                                                                                    Category<small
                                                                                                        class="req">
                                                                                                        *</small></label>
                                                                                                <select
                                                                                                    name="charge_category2"
                                                                                                    id="charge_category2"
                                                                                                    style="width: 100%"
                                                                                                    class="form-control select2 charge_category2 reset_value "
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
                                                                                                    Name<small
                                                                                                        class="req">
                                                                                                        *</small></label>
                                                                                                <select name="charge_id"
                                                                                                    id="charge_id"
                                                                                                    style="width: 100%"
                                                                                                    class="form-control addcharge  select2 reset_value "
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
                                                                                                    class="form-label">Qty<small
                                                                                                        class="req">
                                                                                                        *</small></label>
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
                                                                                                                class="form-control total apply_charge_add_charge">
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
                                                                                                                class="form-control discount_percentage_amount">
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
                                                                                                                class="form-control tax">
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
                                                                                                                class="form-control net_amount">
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
                                                                                        <div class="col-sm-3"
                                                                                            id="charge_add_button_col">
                                                                                            <div class="form-group mb-2">
                                                                                                <label for=""
                                                                                                    class="form-label">Date
                                                                                                    <small class="req">
                                                                                                        *</small></label>
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
                                                                                    <div class="row pt-2 mx-0" id="bill_visibility_section">
                                                                                        <div class="col-12 border rounded p-2 bg-light">
                                                                                            <label class="form-label fw-semibold mb-2">Show on bills</label>
                                                                                            <div class="d-flex flex-wrap gap-3">
                                                                                                <div class="form-check">
                                                                                                    <input class="form-check-input bill-vis-default" type="checkbox" value="1" id="bill_vis_approval" checked>
                                                                                                    <label class="form-check-label" for="bill_vis_approval">Export Approval Bill</label>
                                                                                                </div>
                                                                                                <div class="form-check">
                                                                                                    <input class="form-check-input bill-vis-default" type="checkbox" value="1" id="bill_vis_approval_preview" checked>
                                                                                                    <label class="form-check-label" for="bill_vis_approval_preview">Approval Bill Preview</label>
                                                                                                </div>
                                                                                                <div class="form-check">
                                                                                                    <input class="form-check-input bill-vis-default" type="checkbox" value="1" id="bill_vis_final_preview" checked>
                                                                                                    <label class="form-check-label" for="bill_vis_final_preview">Preview Final Bill</label>
                                                                                                </div>
                                                                                                <div class="form-check">
                                                                                                    <input class="form-check-input bill-vis-default" type="checkbox" value="1" id="bill_vis_final_bill" checked>
                                                                                                    <label class="form-check-label" for="bill_vis_final_bill">Generate Final Bill</label>
                                                                                                </div>
                                                                                            </div>
                                                                                            <small class="text-muted d-block mt-1">Uncheck to exclude this charge from the selected bill PDF.</small>
                                                                                            <div id="bill_visibility_edit_fields" style="display:none;">
                                                                                                <input type="hidden" name="show_on_approval_bill" value="0">
                                                                                                <input type="hidden" name="show_on_approval_preview" value="0">
                                                                                                <input type="hidden" name="show_on_final_preview" value="0">
                                                                                                <input type="hidden" name="show_on_final_bill" value="0">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <hr>
                                                                                </div>
                                                                                <div class="col-lg-12 col-md-12 col-sm-12"
                                                                                    id="charge_preview_wrapper">
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
                                                                <!-- <th>Tax</th> -->
                                                                <th>Amount (INR)</th>
                                                                <th class="text-center" title="Export Approval Bill">Appr.</th>
                                                                <th class="text-center" title="Approval Bill Preview">Appr. Prev.</th>
                                                                <th class="text-center" title="Preview Final Bill">Fin. Prev.</th>
                                                                <th class="text-center" title="Generate Final Bill">Fin. Bill</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($ipdCharges as $charge)
                                                                @php
                                                                    $standardCharge =
                                                                        $charge->charge?->standard_charge ??
                                                                        ($charge->standard_charge ?? 0);
                                                                    $taxPct =
                                                                        $charge->charge?->taxCategory?->percentage ?? 0;
                                                                    $taxAmount = ($standardCharge * $taxPct) / 100;
                                                                    $discountAmount =
                                                                        ($standardCharge * ($charge->discount ?? 0)) /
                                                                        100;
                                                                    $amount =
                                                                        $standardCharge - $discountAmount + $taxAmount;
                                                                @endphp
                                                                <tr>
                                                                    <td>
                                                                        {{ \Carbon\Carbon::parse($charge->date)->format('d-m-Y') }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $charge->charge?->name ?? '-' }}
                                                                    </td>
                                                                    <td style="text-transform: capitalize;">
                                                                        {{ $charge->chargeCategory?->chargeType?->charge_type ?? '-' }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ $charge->chargeCategory->name ?? '-' }}
                                                                    </td>

                                                                    <td class="text-right">
                                                                        {{ $charge->qty ?? '-' }}</td>
                                                                    <td class="text-right">
                                                                        {{ $charge->standard_charge ?? '-' }}</td>
                                                                    <td class="text-right">0.00</td>
                                                                    <td>{{ $discountAmount }}&nbsp;({{ $charge->discount }}%)
                                                                    </td>
                                                                    <!-- <td>{{ $taxAmount }}&nbsp;({{ $charge->charge?->taxCategory?->percentage ?? '-' }}%)
                                                                        </td> -->
                                                                    <td>&nbsp;</td>
                                                                    <td>{{ $amount }}</td>
                                                                    @php
                                                                        $visFields = [
                                                                            'show_on_approval_bill' => 'Export Approval Bill',
                                                                            'show_on_approval_preview' => 'Approval Bill Preview',
                                                                            'show_on_final_preview' => 'Preview Final Bill',
                                                                            'show_on_final_bill' => 'Generate Final Bill',
                                                                        ];
                                                                    @endphp
                                                                    @foreach ($visFields as $visField => $visLabel)
                                                                        <td class="text-center">
                                                                            <input type="checkbox"
                                                                                class="form-check-input ipd-charge-bill-vis-toggle mt-0"
                                                                                data-charge-id="{{ $charge->id }}"
                                                                                data-field="{{ $visField }}"
                                                                                title="{{ $visLabel }}"
                                                                                @checked($charge->{$visField} ?? true)>
                                                                        </td>
                                                                    @endforeach
                                                                    <td>
                                                                        <div class="d-flex gap-2">
                                                                            <a href="javascript:void(0);"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-ipd-charge-btn"
                                                                                data-charge-id="{{ $charge->id }}"
                                                                                data-bs-toggle="tooltip" title="Edit">
                                                                                <i class="ti ti-pencil"></i>
                                                                            </a>
                                                                            <form
                                                                                action="{{ route('ipd.charge.delete', $charge->id) }}"
                                                                                method="POST" class="d-inline-block"
                                                                                onsubmit="return confirm('Are you sure you want to delete this charge?');">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="Delete">
                                                                                    <i class="ti ti-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

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

            <!-- Packages Tab -->
            <div class="tab-pane" id="packages">
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0" style="color: #750096">
                                        <i class="fa-solid fa-gift me-2"></i>Applied Packages
                                    </h5>
                                    @if ($ipd->discharged != 'yes')
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#apply_package_modal">
                                            <i class="ti ti-plus me-1"></i>Apply Package
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="applied-packages-list">
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>Loading packages...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apply Package Modal -->
            <div class="modal fade" id="apply_package_modal" tabindex="-1" aria-labelledby="apply_package_label"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="apply_package_label">Apply Package</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="apply_package_form">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="package_select" class="form-label">Select Package <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2-input" id="package_select" name="package_id" required>
                                        <option value="">-- Select Package --</option>
                                    </select>
                                    <div id="package_details" class="mt-3 p-3 bg-light rounded"
                                        style="display: none;">
                                        <h6>Package Details</h6>
                                        <p><strong>Rate:</strong> <span id="pkg_rate">0</span></p>
                                        <p><strong>GST:</strong> <span id="pkg_gst">0</span>%</p>
                                        <p><strong>Description:</strong> <span id="pkg_desc">-</span></p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="package_amount_input" class="form-label">Package Amount (INR) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="package_amount_input"
                                        step="0.01" min="0" placeholder="0.00" value="">
                                    <small class="text-muted">Contract rate — auto-filled from package (editable). Reflects on estimate
                                        &amp; final bill.</small>
                                </div>
                                <div class="mb-3" id="approval_percentage_wrap" style="display: none;">
                                    <label for="approval_percentage_input" class="form-label">Approval %</label>
                                    <input type="number" class="form-control" id="approval_percentage_input"
                                        step="0.01" min="0" max="100" placeholder="e.g. 50">
                                    <small class="text-muted">Optional. Used for insurance/TPA/cashless admissions or insurance packages. Leave blank if not yet approved.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="applied_date" class="form-label">Applied Date</label>
                                    <input type="date" class="form-control" id="applied_date" name="applied_date"
                                        value="{{ date('Y-m-d') }}">
                                    <small class="text-muted">Default: Today. Leave blank for today's date</small>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Optional notes..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="apply_package_btn">
                                    <i class="ti ti-check me-1"></i>Apply Package
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Applied Package Modal -->
            <div class="modal fade" id="edit_applied_package_modal" tabindex="-1" aria-labelledby="edit_applied_package_label"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="edit_applied_package_label">Edit Applied Package</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="edit_applied_package_form">
                            <div class="modal-body">
                                <input type="hidden" id="edit_ipd_package_id" value="">
                                <div class="mb-3">
                                    <label class="form-label">Package</label>
                                    <input type="text" class="form-control" id="edit_package_name" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_package_rate" class="form-label">Contract Rate (INR) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="edit_package_rate" step="0.01" min="0" required>
                                    <small class="text-muted">Contract rate for this admission. Final bill uses calculated final amount.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_approval_percentage" class="form-label">Approval %</label>
                                    <input type="number" class="form-control" id="edit_approval_percentage" step="0.01" min="0" max="100" placeholder="e.g. 50">
                                    <small class="text-muted">Optional. Insurer approval on contract rate. Leave blank if not applicable.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_applied_date" class="form-label">Applied Date</label>
                                    <input type="date" class="form-control" id="edit_applied_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_package_note" class="form-label">Notes</label>
                                    <textarea class="form-control" id="edit_package_note" rows="2" placeholder="Optional notes..."></textarea>
                                </div>
                                <div class="alert alert-light border mb-0 py-2">
                                    <small class="text-muted">Current final amount: <strong id="edit_final_amount_preview">₹0.00</strong></small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="edit_applied_package_btn">
                                    <i class="ti ti-check me-1"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- <div class="tab-pane" id="payments">

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
                                                            @if ($ipd->discharged != 'yes')
    <div class="text-end d-flex">
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-primary text-white ms-2 btn-md"
                                                                        data-bs-toggle="modal" data-bs-target="#add_payment"><i
                                                                            class="ti ti-plus me-1"></i>Add Payment</a>
                                                                </div>
    @endif

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
                                                                            <form action="{{ route('transactions.store') }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="ipd_id"
                                                                                    value="{{ $ipd->id }}">
                                                                                <input type="hidden" name="patient_id"
                                                                                    value="{{ $ipd->patient_id }}">
                                                                                <input type="hidden" name="type"
                                                                                    value="payment">
                                                                                <input type="hidden" name="section"
                                                                                    value="ipd">
                                                                                <div class="row gy-3 py-4 mx-1">

                                                                                    <div class="col-md-6">
                                                                                        <label for="date"
                                                                                            class="form-label">Date
                                                                                            <span class="text-danger">*</span>
                                                                                        </label>
                                                                                        <input type="date" name="date"
                                                                                            id="date"
                                                                                            class="form-control" required>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="amount"
                                                                                            class="form-label">Amount (INR)
                                                                                            <span class="text-danger">*</span>
                                                                                        </label>
                                                                                        <input type="text" name="amount"
                                                                                            id="amount"
                                                                                            class="form-control" required>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="payment_mode"
                                                                                            class="form-label">Payment Mode

                                                                                        </label>
                                                                                        <select name="payment_mode"
                                                                                            id="payment_mode"
                                                                                            class="form-select"
                                                                                            data-placeholder="Enter Patient Name or Id…">
                                                                                            <option value="0">Select
                                                                                            </option>
                                                                                            <option value="Cash">Cash</option>
                                                                                            <option value="Cheque">Cheque</option>
                                                                                            <option value="transfer_to_bank_account">Transfer to Bank Account</option>
                                                                                            <option value="UPI">UPI</option>
                                                                                            <option value="Online">Online</option>
                                                                                            <option value="Other">Other</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="row gy-3 mt-2" id="chequeFields" style="display: none;">
                                                                                        <div class="col-md-6">
                                                                                            <label for="cheque_no" class="form-label">
                                                                                                Cheque No <span class="text-danger">*</span>
                                                                                            </label>
                                                                                            <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                                                                        </div>

                                                                                        <div class="col-md-6">
                                                                                            <label for="cheque_date" class="form-label">
                                                                                                Cheque Date <span class="text-danger">*</span>
                                                                                            </label>
                                                                                            <input type="date" name="cheque_date" id="cheque_date" class="form-control">
                                                                                        </div>


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

                                                    <div class="table-responsive table-nowrap">
                                                        <table class="table border">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Transaction ID</th>
                                                                    <th>Date</th>
                                                                    <th>Note</th>
                                                                    <th>Payment Mode</th>
                                                                    <th>Paid Amount (INR)</th>
                                                                    <th class="text-center">Money Receipt</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($transactions as $transaction)
    <tr>
                                                                        <td>
                                                                            {{ $transaction->transaction_no ?? 'TRID' . $transaction->id }}
                                                                        </td>

                                                                        <td>
                                                                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y h:i A') }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $transaction->note ?? '-' }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $transaction->payment_mode }}
                                                                        </td>

                                                                        <td>
                                                                            {{ number_format($transaction->amount, 2) }}
                                                                        </td>

                                                                        <td class="text-center">
                                                                            @if (!empty($transaction->receipt_no))
    <a href="{{ route('money-receipt.print', $transaction->id) }}"
                                                                                   class="btn btn-sm btn-primary"
                                                                                   target="_blank"
                                                                                   title="Print Money Receipt">
                                                                                    <i class="ti ti-printer"></i>
                                                                                </a>
@else
    <span class="text-muted">-</span>
    @endif
                                                                        </td>
                                                                    </tr>
                                                            @empty
                                                                    <tr>
                                                                        <td colspan="6" class="text-center text-muted">
                                                                            No payments found
                                                                        </td>
                                                                    </tr>
    @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
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
                                                    <div class="text-end d-flex">
                                                        {{-- This tab is for live consultation; bed-history actions must not appear here --}}
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
                                                                @if ($ipd->discharged != 'yes')
                                                                    <th>Action</th>
                                                                @endif
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
                                                                @if ($ipd->discharged != 'yes')
                                                                    <td>
                                                                        <div class="d-flex gap-2">

                                                                        </div>
                                                                    </td>
                                                                @endif
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
                                                        @if ($ipd->discharged != 'yes')
                                                            <div class="text-end d-flex">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-primary text-white ms-2 btn-md"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#add_timeline"><i
                                                                        class="ti ti-plus me-1"></i>Add Timeline</a>
                                                            </div>
                                                        @endif
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
                                                                        action="{{ isset($timeline) ? route('patient-timeline.update', $timeline->id) : route('patient-timeline.store') }}"
                                                                        enctype="multipart/form-data">
                                                                        @csrf
                                                                        @if (isset($timeline))
                                                                            @method('PUT')
                                                                        @endif

                                                                        <input type="hidden" name="patient_id"
                                                                            value="{{ $ipd->patient_id ?? '' }}">

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
                                                                                        value="{{ old('title', $timeline->title ?? '') }}"
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
                                                                                        value="{{ old('date', isset($timeline->date) ? \Carbon\Carbon::parse($timeline->date)->format('Y-m-d') : '') }}"
                                                                                        required>
                                                                                </div>

                                                                                <!-- Description -->
                                                                                <div class="col-md-12">
                                                                                    <label for="description"
                                                                                        class="form-label">
                                                                                        Description
                                                                                    </label>
                                                                                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $timeline->description ?? '') }}</textarea>
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
                                                                                    @if (isset($timeline) && $timeline->attch_doc)
                                                                                        <small
                                                                                            class="text-muted d-block mt-1">
                                                                                            Current File:
                                                                                            <a href="{{ asset('storage/timeline_docs/' . $timeline->attch_doc) }}"
                                                                                                target="_blank">
                                                                                                View Document
                                                                                            </a>
                                                                                        </small>
                                                                                    @endif
                                                                                </div>

                                                                                <!-- Visible to Person -->
                                                                                <div class="col-md-12 form-check">
                                                                                    <input type="checkbox"
                                                                                        name="visible_person"
                                                                                        id="visible_person"
                                                                                        class="form-check-input"
                                                                                        {{ old('visible_person', $timeline->visible_person ?? false) ? 'checked' : '' }}>
                                                                                    <label for="visible_person"
                                                                                        class="form-check-label">Visible
                                                                                        to this person</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                class="btn btn-primary">
                                                                                {{ isset($timeline) ? 'Update' : 'Save' }}
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
                                                            @forelse($PatientTimelines as $timeline)
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">
                                                                            <a href="#"
                                                                                class="fw-semibold">{{ $timeline->patient->patient_name ?? '-' }}</a>
                                                                        </h6>
                                                                    </td>


                                                                    <td>{{ $timeline->title ?? '-' }}</td>
                                                                    <td>{{ $timeline->description ?? '-' }}</td>
                                                                    <td>
                                                                        @if (!empty($timeline->timeline_date))
                                                                            {{ \Carbon\Carbon::parse($timeline->timeline_date)->format('d/m/Y h:i A') }}
                                                                        @else
                                                                            -
                                                                        @endif
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
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center text-muted">No
                                                                        timeline records found</td>
                                                                </tr>
                                                            @endforelse
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
                                                    <div class="text-end d-flex">
                                                        {{-- This tab is for treatment history; bed-history actions must not appear here --}}
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
                                                            <td>{{ $ipd->ipd_no }}</td>
                                                            <td>{{ $ipd->patient_id }}</td>
                                                            <td>{{ $ipd->doctor ? $ipd->doctor->name . ' ' . $ipd->doctor->surname : '--' }}
                                                            </td>
                                                            <td>{{ $ipd->bedGroup ? $ipd->bedGroup->name : '--' }}-{{ $ipd->bedDetail ? $ipd->bedDetail->name : '--' }}
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
                                                        @if ($ipd->discharged != 'yes')
                                                            <div class="text-end d-flex">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-primary text-white ms-2 btn-md"
                                                                    id="addPrescriptionBtn_{{ $ipd->id }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addPrescriptionModal"
                                                                    data-ipd-id="{{ $ipd->id }}"
                                                                    data-admission-date="{{ $ipd->date ? \Carbon\Carbon::parse($ipd->date)->format('Y-m-d') : date('Y-m-d') }}"
                                                                    onclick="if(window.showIpdPrescriptionModal){ event.preventDefault(); event.stopPropagation(); window.showIpdPrescriptionModal(this); }"><i
                                                                        class="ti ti-plus me-1"></i>Add Prescription</a>
                                                            </div>
                                                        @endif
                                                        <!-- add-prescription-modal moved to page level (outside tab-pane) so it displays correctly -->
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
                                                            @foreach ($ipdPrescriptions as $prescription)
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">
                                                                            {{ $prescription->prescription_number }}</h6>
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($prescription->date)->format('d/m/Y') }}
                                                                    </td>
                                                                    <td>
                                                                        @foreach ($ipdFindings[$prescription->ipd_id] as $finding)
                                                                            <span
                                                                                class="badge bg-primary me-1">{{ $finding->name }}</span><br>
                                                                        @endforeach
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex gap-2">
                                                                            <a href="javascript: void(0);"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#showPrescriptionModal"
                                                                                data-is-ipd="true"
                                                                                data-id="{{ $ipd->id }}"
                                                                                data-pres-id = "{{ $prescription->id }}"
                                                                                data-prescription-date="{{ \Carbon\Carbon::parse($prescription->date)->format('Y-m-d') }}">
                                                                                <i class="fa-solid fa-prescription"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="Show"></i>
                                                                            </a>
                                                                            @if ($ipd->discharged != 'yes')
                                                                                <a href="{{ route('ipd.prescription.edit', $prescription->id) }}"
                                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-warning rounded-pill"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="Edit">
                                                                                    <i class="fa-solid fa-pencil"></i>
                                                                                </a>
                                                                            @endif
                                                                            {{-- <a href="{{ route('ipd.prescription.print', $prescription->id) }}"
                                                                                target="_blank"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Print">
                                                                                <i class="fa-solid fa-print"></i>
                                                                            </a> --}}
                                                                        </div>
                                                                        @include('components.modals.show-prescription-modal')
                                                                    </td>
                                                                </tr>
                                                            @endforeach

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
                                                    <div class="text-end d-flex">
                                                        @if ($ipd->discharged != 'yes')
                                                            <button type="button"
                                                                class="btn btn-primary text-white ms-2 btn-md add-bed-history-btn">
                                                                <i class="ti ti-plus me-1"></i>Add Bed History
                                                            </button>
                                                        @endif
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
                                                                <th>Active</th>
                                                                @if ($ipd->discharged != 'yes')
                                                                    <th>Action</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($bedHistories as $history)
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">
                                                                            {{ $history->bedGroup->name ?? '-' }}</h6>
                                                                    </td>
                                                                    <td>{{ $history->bed->name ?? '-' }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($history->from_date)->format('d/m/Y h:i A') }}
                                                                    </td>
                                                                    <td>{{ $history->to_date ? \Carbon\Carbon::parse($history->to_date)->format('d/m/Y h:i A') : '--' }}
                                                                    </td>
                                                                    <td>{{ $history->is_active === 'yes' ? 'Yes' : 'No' }}
                                                                    </td>
                                                                    @if ($ipd->discharged != 'yes')
                                                                        <td>
                                                                        @php
                                                                            $daywiseCharge = \App\Models\IpdDaywiseBedCharge::where(
                                                                                'ipd_id',
                                                                                $ipd->id,
                                                                            )
                                                                                ->whereDate(
                                                                                    'charge_date',
                                                                                    \Carbon\Carbon::parse(
                                                                                        $history->from_date,
                                                                                    )->format('Y-m-d'),
                                                                                )
                                                                                ->where(
                                                                                    'bed_group_id',
                                                                                    $history->bed_group_id,
                                                                                )
                                                                                ->first();
                                                                            $existingCharge =
                                                                                $daywiseCharge &&
                                                                                $daywiseCharge->bed_charge
                                                                                    ? $daywiseCharge->bed_charge
                                                                                    : $history->bedGroup->bed_cost ??
                                                                                        '';
                                                                        @endphp
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-soft-warning edit-bed-history-btn"
                                                                            data-history-id="{{ $history->id }}"
                                                                            data-ipd-id="{{ $ipd->id }}"
                                                                            data-bed-group-id="{{ $history->bed_group_id }}"
                                                                            data-bed-id="{{ $history->bed_id }}"
                                                                            data-from-date="{{ $history->from_date ? \Carbon\Carbon::parse($history->from_date)->format('Y-m-d\TH:i') : '' }}"
                                                                            data-to-date="{{ $history->to_date ? \Carbon\Carbon::parse($history->to_date)->format('Y-m-d\TH:i') : '' }}"
                                                                            data-bed-charge="{{ $existingCharge }}"
                                                                            data-is-active="{{ $history->is_active }}"
                                                                            title="Edit">
                                                                            <i class="ti ti-edit"></i>
                                                                        </button>
                                                                        @if (isset($latestBedHistoryId) && (int) $history->id === (int) $latestBedHistoryId)
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-soft-danger delete-bed-history-btn ms-1"
                                                                                data-history-id="{{ $history->id }}"
                                                                                data-ipd-id="{{ $ipd->id }}"
                                                                                data-bed-group="{{ $history->bedGroup->name ?? '-' }}"
                                                                                data-bed-name="{{ $history->bed->name ?? '-' }}"
                                                                                data-from-date="{{ $history->from_date ? \Carbon\Carbon::parse($history->from_date)->format('d/m/Y h:i A') : '-' }}"
                                                                                title="Delete latest bed assignment">
                                                                                <i class="ti ti-trash"></i>
                                                                            </button>
                                                                        @endif
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach

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
            {{-- Edit Bed History Modal --}}
            <div class="modal fade" id="editBedHistoryModal" tabindex="-1"
                aria-labelledby="editBedHistoryModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header"
                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%);">
                            <h5 class="modal-title text-white" id="editBedHistoryModalLabel"><i
                                    class="ti ti-edit me-2"></i>Edit Bed History</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="editBedHistoryForm" method="POST" action="{{ route('ipd.bedHistory.update') }}">
                            @csrf
                            <input type="hidden" name="bed_history_id" id="edit_bed_history_id">
                            <input type="hidden" name="ipd_id" id="edit_ipd_id" value="{{ $ipd->id }}">
                            <div class="modal-body">
                                <div id="editBedHistoryError" class="alert alert-danger d-none"></div>
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <label for="edit_bed_group" class="form-label">Bed Group <span
                                                class="text-danger">*</span></label>
                                        <select name="bed_group" id="edit_bed_group" class="form-select" required>
                                            <option value="">Select Bed Group</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit_bed" class="form-label">Bed <span
                                                class="text-danger">*</span></label>
                                        <select name="bed" id="edit_bed" class="form-select" required>
                                            <option value="">Select Bed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit_from_date" class="form-label">From Date <span
                                                class="text-danger">*</span></label>
                                        <input type="datetime-local" name="from_date" id="edit_from_date"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit_to_date" class="form-label">To Date <span
                                                class="text-muted">(leave empty for active)</span></label>
                                        <input type="datetime-local" name="to_date" id="edit_to_date"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="edit_bed_charge" class="form-label">Bed Charge (INR)</label>
                                        <input type="number" name="bed_charge" id="edit_bed_charge"
                                            class="form-control" step="0.01" min="0" placeholder="0.00">
                                        <small class="text-muted">Auto-filled from bed group (editable)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Delete latest Bed History Modal --}}
            <div class="modal fade" id="deleteBedHistoryModal" tabindex="-1"
                aria-labelledby="deleteBedHistoryModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="deleteBedHistoryModalLabel">
                                <i class="ti ti-trash me-2"></i>Delete Latest Bed Assignment
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="deleteBedHistoryForm" method="POST" action="{{ route('ipd.bedHistory.delete') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="bed_history_id" id="delete_bed_history_id">
                            <input type="hidden" name="ipd_id" id="delete_ipd_id" value="{{ $ipd->id }}">
                            <div class="modal-body">
                                <p class="mb-2">This will remove the latest bed assignment for this patient.</p>
                                <ul class="mb-3">
                                    <li><strong>Bed Group:</strong> <span id="delete_bed_group_label">-</span></li>
                                    <li><strong>Bed:</strong> <span id="delete_bed_name_label">-</span></li>
                                    <li><strong>From:</strong> <span id="delete_from_date_label">-</span></li>
                                </ul>
                                <p class="text-muted small mb-0">
                                    If this was a transfer, the previous bed will become active again.
                                    If this is the only bed record, the patient will have no assigned bed until reassigned.
                                    Estimate and final bill will be updated accordingly.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
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
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>New Bed
                                    Assign
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action = "{{ route('assignNewBed') }}" method = "POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row gy-4">
                                                <div class="col-md-6">
                                                    <span class="text-primary"> <b>Old Assigned Bed : </b> </span>

                                                    <span>
                                                        {{ $bedShiftHistory->bed->name ?? '-' }}
                                                        -
                                                        {{ $bedShiftHistory->bedGroup->name ?? 'No Ward' }}
                                                        -
                                                        {{ $bedShiftHistory->bedGroup->floorDetail->name ?? '-' }}
                                                    </span>


                                                </div>
                                                <input type="hidden" name="ipd_id" value="{{ $ipd->id }}">
                                                <div class="col-md-6">
                                                    <span class="text-primary"><b>Assigned Date : </b></span>
                                                    <span>
                                                        @if ($bedShiftHistory)
                                                            {{ $bedShiftHistory->from_date ? \Carbon\Carbon::parse($bedShiftHistory->from_date)->format('jS F Y h:i:s a') : '-' }}
                                                        @else
                                                            <span class="text-danger">No active bed history</span>
                                                        @endif
                                                    </span>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="released_date" class="form-label">Select Released Date
                                                        <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="released_date"
                                                        id="released_date" class="form-control"
                                                        max="{{ now()->format('Y-m-d\TH:i') }}">
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

                                                <div class="col-md-4">
                                                    <label for="bed_charge_transfer" class="form-label">Bed Charge (INR)
                                                        <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="bed_charge"
                                                        id="bed_charge_transfer" step="0.01" min="0"
                                                        placeholder="0.00">
                                                    <small class="text-muted">Auto-filled from bed group
                                                        (editable)</small>
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
                                                        @if ($ipd->discharged != 'yes')
                                                            <div class="text-end d-flex">
                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-primary text-white ms-2 btn-md"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#add_vital"><i
                                                                        class="ti ti-plus me-1"></i>Add Vitals</a>
                                                            </div>
                                                        @endif
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
                                                                        action="{{ route('patient-vitals.store') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="patient_id"
                                                                            value="{{ $ipd->patient_id }}">
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
                                                                                            @foreach ($vitals as $vital)
                                                                                                <option
                                                                                                    value="{{ $vital->id }}">
                                                                                                    {{ $vital->name . ' (' . $vital->reference_range . ')' }}
                                                                                                </option>
                                                                                            @endforeach
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
                                                                {{-- Dynamically generate vital headers --}}
                                                                @foreach ($vitals as $vital)
                                                                    <th>{{ $vital->name }}</th>
                                                                @endforeach
                                                                <!-- <th>Action</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($vitalDetails->groupBy('patient_id') as $caseId => $caseVitals)
                                                                @php
                                                                    $firstRecord = $caseVitals->first();
                                                                @endphp
                                                                <tr>

                                                                    <td>
                                                                        @if (!empty($firstRecord->messure_date))
                                                                            {{ \Carbon\Carbon::parse($firstRecord->messure_date)->format('d/m/Y h:i A') }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>

                                                                    {{-- Loop through all vitals dynamically --}}
                                                                    @foreach ($vitals as $vital)
                                                                        @php
                                                                            $record = $caseVitals
                                                                                ->where('vital_id', $vital->id)
                                                                                ->first();
                                                                        @endphp
                                                                        <td>
                                                                            {{ $record->reference_range ?? '-' }}
                                                                        </td>
                                                                    @endforeach

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
                                                            @empty
                                                                <tr>
                                                                    <td colspan="{{ 4 + $vitals->count() }}"
                                                                        class="text-center text-muted">
                                                                        No vital records found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
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

    {{-- Modals: placed at page level (outside tab-panes) so they display correctly --}}
    @include('components.modals.add-prescription-modal')
    @include('components.modals.discharge-modal')
    @include('components.modals.discharge-details-modal')

    <script>
        console.log('🔵 IPD View Script Block 1 Loading...');

        // Test: Verify script is executing
        try {
            console.log('✅ Script execution test passed');
        } catch (e) {
            console.error('❌ Script execution test failed:', e);
            alert('Script error: ' + e.message);
        }

        // Suppress browser extension errors (they're harmless but noisy)
        window.addEventListener('error', function(e) {
            if (e.message && e.message.includes('message channel closed')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }, true);

        console.log('🔵 IPD View Script Block 1 Loaded');

        // --- Prescription modal: global opener (used by inline onclick so modal opens even if other scripts fail) ---
        window.showIpdPrescriptionModal = function(btn) {
            var m = document.getElementById('addPrescriptionModal');
            if (!m) {
                console.error('addPrescriptionModal not found');
                return;
            }
            if (m.parentNode !== document.body) document.body.appendChild(m);
            if (btn) {
                var ipdId = btn.getAttribute('data-ipd-id');
                if (ipdId) {
                    var f = document.getElementById('ipd_id');
                    if (f) f.value = ipdId;
                    var ad = btn.getAttribute('data-admission-date');
                    var dateEl = document.getElementById('prescription_date');
                    if (dateEl) {
                        dateEl.max = new Date().toISOString().split('T')[0];
                        dateEl.value = ad || dateEl.max;
                        if (ad) dateEl.setAttribute('min', ad);
                    }
                }
            }
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(m).show();
            } else {
                m.classList.remove('fade');
                m.classList.add('show');
                m.style.display = 'block';
                m.style.visibility = 'visible';
                m.style.opacity = '1';
                m.style.zIndex = '1055';
                m.setAttribute('aria-hidden', 'false');
                document.body.classList.add('modal-open');
                var back = document.querySelector('.modal-backdrop');
                if (!back) {
                    back = document.createElement('div');
                    back.className = 'modal-backdrop fade show';
                    back.style.zIndex = '1050';
                    document.body.insertBefore(back, m);
                }
                back.classList.add('show');
            }
        };
        (function prescriptionModalFallback() {
            function run() {
                var modal = document.getElementById('addPrescriptionModal');
                if (!modal) return;
                if (modal.parentNode !== document.body) document.body.appendChild(modal);
            }
            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
            else run();
        })();

        // Direct function to open prescription modal - bypasses Bootstrap issues
        // Define function to fetch pathology/radiology data DIRECTLY (not dependent on modal)
        // Make sure this is defined BEFORE any button click handlers
        if (typeof window.fetchPathologyRadiologyData === 'undefined') {
            window.fetchPathologyRadiologyData = function() {
                console.log('🔴 fetchPathologyRadiologyData called directly');
                var pathSelect = document.getElementById('pathologyOpt');
                var radSelect = document.getElementById('radiologyOpt');
                var pathologyUrl = "{{ url(route('getPathologies')) }}";
                var radiologyUrl = "{{ url(route('getRadiologies')) }}";

                function initAfterBothLoaded() {
                    if (typeof window.initPathologyRadiologySelect2 === 'function') {
                        window.initPathologyRadiologySelect2();
                    }
                }

                if (pathSelect && pathSelect.options.length > 1 && radSelect && radSelect.options.length > 1) {
                    console.log('📡 Pathology/Radiology already have options, initializing Select2 only');
                    initAfterBothLoaded();
                    return;
                }

                var pathDone = false,
                    radDone = false;

                function maybeInit() {
                    if (pathDone && radDone) {
                        console.log('✅ Both pathology and radiology loaded, initializing Select2');
                        initAfterBothLoaded();
                    }
                }

                if (pathSelect) {
                    if (pathSelect.options.length <= 1) {
                        if (window.jQuery && window.jQuery(pathSelect).hasClass('select2-hidden-accessible')) {
                            try {
                                window.jQuery(pathSelect).select2('destroy');
                            } catch (e) {}
                        }
                        pathSelect.innerHTML = '<option value="">Loading...</option>';
                        fetch(pathologyUrl)
                            .then(function(r) {
                                if (!r.ok) throw new Error('HTTP ' + r.status);
                                return r.json();
                            })
                            .then(function(data) {
                                pathSelect.innerHTML = '';
                                if (data && Array.isArray(data) && data.length > 0) {
                                    data.forEach(function(p) {
                                        var opt = document.createElement('option');
                                        opt.value = p.id;
                                        opt.textContent = (p.test_name || 'Unknown') + (p.short_name ?
                                            ' (' + p.short_name + ')' : '');
                                        pathSelect.appendChild(opt);
                                    });
                                    console.log('✅ Pathology options added:', pathSelect.options.length);
                                }
                                pathDone = true;
                                maybeInit();
                            })
                            .catch(function(err) {
                                console.error('❌ Pathology fetch error:', err);
                                pathSelect.innerHTML = '<option value="">Error loading</option>';
                                pathDone = true;
                                maybeInit();
                            });
                    } else {
                        pathDone = true;
                        maybeInit();
                    }
                } else {
                    pathDone = true;
                    maybeInit();
                }

                if (radSelect) {
                    if (radSelect.options.length <= 1) {
                        if (window.jQuery && window.jQuery(radSelect).hasClass('select2-hidden-accessible')) {
                            try {
                                window.jQuery(radSelect).select2('destroy');
                            } catch (e) {}
                        }
                        radSelect.innerHTML = '<option value="">Loading...</option>';
                        fetch(radiologyUrl)
                            .then(function(r) {
                                if (!r.ok) throw new Error('HTTP ' + r.status);
                                return r.json();
                            })
                            .then(function(data) {
                                radSelect.innerHTML = '';
                                if (data && Array.isArray(data) && data.length > 0) {
                                    data.forEach(function(r) {
                                        var opt = document.createElement('option');
                                        opt.value = r.id;
                                        opt.textContent = (r.test_name || 'Unknown') + (r.short_name ?
                                            ' (' + r.short_name + ')' : '');
                                        radSelect.appendChild(opt);
                                    });
                                    console.log('✅ Radiology options added:', radSelect.options.length);
                                }
                                radDone = true;
                                maybeInit();
                            })
                            .catch(function(err) {
                                console.error('❌ Radiology fetch error:', err);
                                radSelect.innerHTML = '<option value="">Error loading</option>';
                                radDone = true;
                                maybeInit();
                            });
                    } else {
                        radDone = true;
                        maybeInit();
                    }
                } else {
                    radDone = true;
                    maybeInit();
                }
            };
        }

        window.initPathologyRadiologySelect2 = function() {
            if (typeof window.jQuery === 'undefined' || !window.jQuery.fn.select2) return;
            var $ = window.jQuery;
            var pathEl = document.getElementById('pathologyOpt');
            var radEl = document.getElementById('radiologyOpt');
            if (!pathEl && !radEl) return;
            var opts = {
                placeholder: 'Select Tests',
                allowClear: true,
                width: '100%',
                dropdownParent: $('body'),
                dropdownCssClass: 'pathology-radiology-dropdown',
                minimumResultsForSearch: 0,
                multiple: true,
                closeOnSelect: false,
                language: {
                    inputTooShort: function() {
                        return '';
                    },
                    searching: function() {
                        return '';
                    }
                }
            };

            function initOne(el) {
                if (!el || el.options.length === 0) return;
                try {
                    if ($(el).data('select2')) {
                        try {
                            $(el).select2('destroy');
                        } catch (d) {}
                    }
                    $(el).select2(opts);
                } catch (e) {
                    console.debug('Select2 init:', e);
                }
            }

            function doInit() {
                var modal = document.getElementById('addPrescriptionModal');
                if (!modal) return;
                initOne(pathEl);
                initOne(radEl);
            }
            setTimeout(doInit, 100);
            setTimeout(doInit, 400);
            setTimeout(doInit, 800);
        };

        // Ensure function is available
        console.log('fetchPathologyRadiologyData defined:', typeof window.fetchPathologyRadiologyData === 'function');

        // Directly populate medicine category, dose interval, dose duration in the prescription modal (no dependency on modal script)
        window.populateMedicineDropdownsInModal = function() {
            var modal = document.getElementById('addPrescriptionModal');
            if (!modal) return;
            var container = modal.querySelector('#medicineContainer') || document.getElementById('medicineContainer');
            if (!container) return;
            var rows = container.querySelectorAll('.medicine-row');
            var cats = window.medicineCategories || [];
            var intervals = window.doseIntervals || [];
            var durations = window.doseDurations || [];
            var $ = window.jQuery;
            var hasSelect2 = $ && $.fn.select2;

            function fillSelect(sel, list, textKey) {
                if (!sel) return;
                if (hasSelect2 && $(sel).hasClass('select2-hidden-accessible')) {
                    try {
                        $(sel).select2('destroy');
                    } catch (e) {}
                }
                sel.innerHTML = '';
                var opt0 = document.createElement('option');
                opt0.value = '';
                opt0.textContent = textKey === 'cat' ? 'Select Category' : (textKey === 'int' ? 'Select Interval' :
                    'Select Duration');
                sel.appendChild(opt0);
                if (Array.isArray(list) && list.length > 0) {
                    list.forEach(function(item) {
                        if (!item || item.id == null) return;
                        var o = document.createElement('option');
                        o.value = item.id;
                        o.textContent = textKey === 'cat' ? (item.medicine_category || item.name || item
                            .category || '') : (item.name || '');
                        sel.appendChild(o);
                    });
                }
                if (hasSelect2) {
                    try {
                        $(sel).select2({
                            width: '100%',
                            placeholder: textKey === 'cat' ? 'Select Category' : (textKey === 'int' ?
                                'Select Interval' : 'Select Duration'),
                            allowClear: true,
                            dropdownParent: $('#addPrescriptionModal')
                        });
                    } catch (e) {}
                }
            }
            rows.forEach(function(row) {
                var catSel = row.querySelector('.medicine_category');
                var intSel = row.querySelector('.interval_dosage');
                var durSel = row.querySelector('.duration_dosage');
                fillSelect(catSel, cats, 'cat');
                fillSelect(intSel, intervals, 'int');
                fillSelect(durSel, durations, 'dur');
            });
            if (typeof window.attachMedicineCategoryChangeInModal === 'function') {
                window.attachMedicineCategoryChangeInModal();
            }
            if (typeof window.attachMedicineChangeForDosesInModal === 'function') {
                window.attachMedicineChangeForDosesInModal();
            }
            console.log('✅ Medicine dropdowns populated in modal:', rows.length, 'rows');
        };

        // When user selects a medicine category, fetch medicines and fill the medicine dropdown (works with our populated category select)
        window.attachMedicineCategoryChangeInModal = function() {
            var modal = document.getElementById('addPrescriptionModal');
            if (!modal) return;
            var $ = window.jQuery;
            if (!$ || !$.fn.select2) return;
            var baseUrl = "{{ url(route('getMedicines', ['categoryId' => 'ID'])) }}";
            $(modal).off('change.medcat select2:select.medcat select2:clear.medcat', '.medicine_category').on(
                'change.medcat select2:select.medcat select2:clear.medcat', '.medicine_category',
                function() {
                    var categoryId = $(this).val();
                    var row = $(this).closest('.medicine-row')[0];
                    if (!row) return;
                    var medicineSelect = row.querySelector('.medicine_name');
                    if (!medicineSelect) return;
                    if (!categoryId || categoryId === '') {
                        medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
                        try {
                            $(medicineSelect).select2('destroy');
                        } catch (e) {}
                        $(medicineSelect).select2({
                            width: '100%',
                            placeholder: 'Select Medicine',
                            allowClear: true,
                            dropdownParent: $('#addPrescriptionModal')
                        });
                        return;
                    }
                    var url = baseUrl.replace('ID', categoryId);
                    $(medicineSelect).prop('disabled', true).html('<option value="">Loading...</option>');
                    try {
                        $(medicineSelect).select2('destroy');
                    } catch (e) {}
                    fetch(url)
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(data) {
                            var list = Array.isArray(data) ? data : (data && data.data) || [];
                            medicineSelect.innerHTML = '';
                            var o0 = document.createElement('option');
                            o0.value = '';
                            o0.textContent = 'Select Medicine';
                            medicineSelect.appendChild(o0);
                            list.forEach(function(item) {
                                if (!item || item.id == null) return;
                                var o = document.createElement('option');
                                o.value = item.id;
                                o.textContent = item.medicine_name || item.name || 'Unknown';
                                medicineSelect.appendChild(o);
                            });
                            $(medicineSelect).prop('disabled', false);
                            $(medicineSelect).select2({
                                width: '100%',
                                placeholder: 'Select Medicine',
                                allowClear: true,
                                dropdownParent: $('#addPrescriptionModal')
                            });
                            console.log('✅ Medicines filled in dropdown:', list.length);
                        })
                        .catch(function(e) {
                            console.warn('Medicine fetch error:', e);
                            medicineSelect.innerHTML = '<option value="">Error loading</option>';
                            $(medicineSelect).prop('disabled', false);
                            $(medicineSelect).select2({
                                width: '100%',
                                placeholder: 'Select Medicine',
                                allowClear: true,
                                dropdownParent: $('#addPrescriptionModal')
                            });
                        });
                });
            console.log('✅ Medicine category change handler attached');
        };

        // When user selects a medicine, fetch doses for that category and fill the dose dropdown
        window.attachMedicineChangeForDosesInModal = function() {
            var modal = document.getElementById('addPrescriptionModal');
            if (!modal) return;
            var $ = window.jQuery;
            if (!$ || !$.fn.select2) return;
            var baseUrl = "{{ url(route('getDoses', ['categoryId' => 'ID'])) }}";
            $(modal).off('change.meddose select2:select.meddose select2:clear.meddose', '.medicine_name').on(
                'change.meddose select2:select.meddose select2:clear.meddose', '.medicine_name',
                function() {
                    var row = $(this).closest('.medicine-row')[0];
                    if (!row) return;
                    var categorySelect = row.querySelector('.medicine_category');
                    var doseSelect = row.querySelector('.medicine_dosage');
                    if (!categorySelect || !doseSelect) return;
                    var categoryId = $(categorySelect).val();
                    if (!categoryId || categoryId === '') {
                        doseSelect.innerHTML = '<option value="">Select Category First</option>';
                        try {
                            $(doseSelect).select2('destroy');
                        } catch (e) {}
                        $(doseSelect).select2({
                            width: '100%',
                            placeholder: 'Select Dose',
                            allowClear: true,
                            dropdownParent: $('#addPrescriptionModal')
                        });
                        return;
                    }
                    var url = baseUrl.replace('ID', categoryId);
                    $(doseSelect).prop('disabled', true);
                    doseSelect.innerHTML = '<option value="">Loading doses...</option>';
                    try {
                        $(doseSelect).select2('destroy');
                    } catch (e) {}
                    fetch(url)
                        .then(function(r) {
                            return r.json();
                        })
                        .then(function(data) {
                            var list = Array.isArray(data) ? data : (data && data.data) || [];
                            doseSelect.innerHTML = '';
                            var o0 = document.createElement('option');
                            o0.value = '';
                            o0.textContent = 'Select Dose';
                            doseSelect.appendChild(o0);
                            list.forEach(function(item) {
                                if (!item || item.id == null) return;
                                var o = document.createElement('option');
                                o.value = item.id;
                                var label = item.dosage || item.name || 'Unknown';
                                if (item.unit && item.unit.unit_name) label += ' ' + item.unit
                                .unit_name;
                                o.textContent = label;
                                doseSelect.appendChild(o);
                            });
                            $(doseSelect).prop('disabled', false);
                            $(doseSelect).select2({
                                width: '100%',
                                placeholder: 'Select Dose',
                                allowClear: true,
                                dropdownParent: $('#addPrescriptionModal')
                            });
                            console.log('✅ Doses filled in dropdown:', list.length);
                        })
                        .catch(function(e) {
                            console.warn('Doses fetch error:', e);
                            doseSelect.innerHTML = '<option value="">Error loading doses</option>';
                            $(doseSelect).prop('disabled', false);
                            $(doseSelect).select2({
                                width: '100%',
                                placeholder: 'Select Dose',
                                allowClear: true,
                                dropdownParent: $('#addPrescriptionModal')
                            });
                        });
                });
            console.log('✅ Medicine change → doses handler attached');
        };

        // Fetch medicine category, dose interval, dose duration - call this when Add Prescription is clicked so APIs always run
        window.fetchMedicineDropdownData = function() {
            var catUrl = "{{ url(route('getMedicineCategories')) }}";
            var intUrl = "{{ url(route('getDoseIntervals')) }}";
            var durUrl = "{{ url(route('getDoseDurations')) }}";
            console.log('📡 Fetching medicine dropdowns:', catUrl, intUrl, durUrl);
            Promise.all([
                fetch(catUrl).then(function(r) {
                    return r.json().then(function(d) {
                        return Array.isArray(d) ? d : [];
                    }).catch(function() {
                        return [];
                    });
                }),
                fetch(intUrl).then(function(r) {
                    return r.json().then(function(d) {
                        return Array.isArray(d) ? d : [];
                    }).catch(function() {
                        return [];
                    });
                }),
                fetch(durUrl).then(function(r) {
                    return r.json().then(function(d) {
                        return Array.isArray(d) ? d : [];
                    }).catch(function() {
                        return [];
                    });
                })
            ]).then(function(arr) {
                window.medicineCategories = arr[0] || [];
                window.doseIntervals = arr[1] || [];
                window.doseDurations = arr[2] || [];
                console.log('✅ Medicine dropdowns loaded:', window.medicineCategories.length, 'categories',
                    window.doseIntervals.length, 'intervals', window.doseDurations.length, 'durations');
                // Populate modal dropdowns directly so data always shows (then run modal init if present)
                setTimeout(function() {
                    if (typeof window.populateMedicineDropdownsInModal === 'function') {
                        window.populateMedicineDropdownsInModal();
                    }
                    if (typeof window.initializeMedicineRows === 'function') {
                        window.initializeMedicineRows();
                    }
                }, 300);
            }).catch(function(e) {
                console.warn('Medicine dropdown fetch error:', e);
                window.medicineCategories = window.medicineCategories || [];
                window.doseIntervals = window.doseIntervals || [];
                window.doseDurations = window.doseDurations || [];
                setTimeout(function() {
                    if (typeof window.populateMedicineDropdownsInModal === 'function') window
                        .populateMedicineDropdownsInModal();
                    if (typeof window.initializeMedicineRows === 'function') window
                        .initializeMedicineRows();
                }, 300);
            });
        };

        function openAddPrescriptionModal(ipdId) {
            console.log('openAddPrescriptionModal called with IPD ID:', ipdId);
            const modalEl = document.getElementById('addPrescriptionModal');
            if (!modalEl) {
                console.error('addPrescriptionModal element not found');
                alert('Error: Prescription modal not found. Please refresh the page.');
                return false;
            }

            // Set IPD ID in hidden field
            const ipdIdField = document.getElementById('ipd_id');
            if (ipdIdField) {
                ipdIdField.value = ipdId;
                console.log('IPD ID set to:', ipdId);
            }

            // Set prescription date for back-dated support
            const btn = document.querySelector('[data-bs-target="#addPrescriptionModal"][data-ipd-id="' + ipdId + '"]');
            const prescriptionDateEl = document.getElementById('prescription_date');
            const today = new Date().toISOString().split('T')[0];
            if (prescriptionDateEl) {
                prescriptionDateEl.max = today;
                const admissionDate = btn ? btn.getAttribute('data-admission-date') : null;
                if (admissionDate) {
                    prescriptionDateEl.value = admissionDate;
                    prescriptionDateEl.min = admissionDate;
                    console.log('Prescription date set to admission date:', admissionDate);
                } else {
                    prescriptionDateEl.value = today;
                    prescriptionDateEl.removeAttribute('min');
                }
            }

            // IMMEDIATELY fetch data - don't wait for modal
            if (typeof window.fetchPathologyRadiologyData === 'function') {
                window.fetchPathologyRadiologyData();
            }
            if (typeof window.fetchMedicineDropdownData === 'function') {
                window.fetchMedicineDropdownData();
            }

            // Try Bootstrap modal first
            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    console.log('✅ Bootstrap and Modal are available');

                    // Check if modal already has an instance
                    let modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (!modalInstance) {
                        console.log('Creating new Bootstrap Modal instance');
                        modalInstance = new bootstrap.Modal(modalEl, {
                            backdrop: true,
                            keyboard: true,
                            focus: true
                        });
                    } else {
                        console.log('Using existing Bootstrap Modal instance');
                    }

                    // Show the modal
                    console.log('Calling modalInstance.show()...');
                    modalInstance.show();
                    console.log('✅ Bootstrap modal.show() called');
                    // CRITICAL: Return here - do NOT fall through to manual fallback. Bootstrap manages show/hide/backdrop.
                    // Manual fallback only runs when Bootstrap is unavailable (see below).
                    return true;

                    // Verify modal is showing after a short delay (kept for reference, Bootstrap handles it)
                    setTimeout(() => {
                        // Check visibility - aria-hidden must be explicitly 'false' (not null)
                        const ariaHidden = modalEl.getAttribute('aria-hidden');
                        const computedStyle = window.getComputedStyle(modalEl);
                        const isVisible = modalEl.classList.contains('show') &&
                            (ariaHidden === 'false' || ariaHidden === null) && // Accept null as visible too
                            computedStyle.display !== 'none' &&
                            computedStyle.visibility !== 'hidden' &&
                            parseFloat(computedStyle.opacity) > 0;

                        console.log('Modal visibility check after Bootstrap show:', {
                            isVisible: isVisible,
                            hasShowClass: modalEl.classList.contains('show'),
                            ariaHidden: ariaHidden,
                            display: computedStyle.display,
                            visibility: computedStyle.visibility,
                            opacity: computedStyle.opacity,
                            zIndex: computedStyle.zIndex,
                            position: computedStyle.position
                        });

                        if (!isVisible) {
                            console.warn(
                                '⚠️ Bootstrap modal.show() did not make modal visible, using manual fallback');
                            // Don't return, fall through to manual fallback
                        } else {
                            console.log('✅ Modal is visible via Bootstrap');
                        }

                        // Ensure medicine categories, dose intervals, dose durations and medicine rows are loaded
                        setTimeout(function() {
                            if (typeof window.loadPathologyRadiologyData === 'function') {
                                window.loadPathologyRadiologyData();
                            }
                            var needsCat = !window.medicineCategories || !window.medicineCategories.length;
                            var needsInt = !window.doseIntervals || !window.doseIntervals.length;
                            var needsDur = !window.doseDurations || !window.doseDurations.length;
                            if (needsCat || needsInt || needsDur) {
                                var catUrl = "{{ url(route('getMedicineCategories')) }}",
                                    intUrl = "{{ url(route('getDoseIntervals')) }}",
                                    durUrl = "{{ url(route('getDoseDurations')) }}";
                                Promise.all([
                                    needsCat ? fetch(catUrl).then(function(r) {
                                        return r.json().then(function(d) {
                                            return Array.isArray(d) ? d : [];
                                        }).catch(function() {
                                            return [];
                                        });
                                    }) : Promise.resolve(window.medicineCategories || []),
                                    needsInt ? fetch(intUrl).then(function(r) {
                                        return r.json().then(function(d) {
                                            return Array.isArray(d) ? d : [];
                                        }).catch(function() {
                                            return [];
                                        });
                                    }) : Promise.resolve(window.doseIntervals || []),
                                    needsDur ? fetch(durUrl).then(function(r) {
                                        return r.json().then(function(d) {
                                            return Array.isArray(d) ? d : [];
                                        }).catch(function() {
                                            return [];
                                        });
                                    }) : Promise.resolve(window.doseDurations || [])
                                ]).then(function(arr) {
                                    window.medicineCategories = arr[0] || [];
                                    window.doseIntervals = arr[1] || [];
                                    window.doseDurations = arr[2] || [];
                                    if (typeof window.initializeMedicineRows === 'function') {
                                        window.initializeMedicineRows();
                                    }
                                }).catch(function(e) {
                                    console.warn('Medicine data fallback:', e);
                                    if (typeof window.initializeMedicineRows === 'function') window
                                        .initializeMedicineRows();
                                });
                            } else if (typeof window.initializeMedicineRows === 'function') {
                                window.initializeMedicineRows();
                            }
                        }, 400);
                    }, 300);

                    // Also call the other function if available
                    setTimeout(() => {
                        if (typeof window.loadPathologyRadiologyData === 'function') {
                            window.loadPathologyRadiologyData();
                        }
                    }, 500);

                    // Don't return immediately - let fallback run if Bootstrap fails
                    // return true;
                } else {
                    console.error('❌ Bootstrap or Modal not available:', {
                        bootstrap: typeof bootstrap,
                        Modal: typeof bootstrap !== 'undefined' ? typeof bootstrap.Modal : 'N/A'
                    });
                }
            } catch (bootstrapError) {
                console.error('❌ Bootstrap modal error:', bootstrapError);
                console.error('Error stack:', bootstrapError.stack);
            }

            // Fallback: Manual modal display
            console.warn('⚠️ Using manual modal fallback');
            console.log('Modal element before manual show:', {
                exists: !!modalEl,
                currentClasses: modalEl ? Array.from(modalEl.classList) : [],
                currentDisplay: modalEl ? modalEl.style.display : 'N/A',
                currentAriaHidden: modalEl ? modalEl.getAttribute('aria-hidden') : 'N/A'
            });

            // Do NOT force other modals to display - duplicate ID was causing wrong modal to be targeted (now fixed in add-pathlab-report).
            // CRITICAL: Set aria-hidden to 'false' (not null, not remove attribute)
            modalEl.setAttribute('aria-hidden', 'false');
            modalEl.setAttribute('aria-modal', 'true');

            // Remove fade class temporarily to show immediately
            modalEl.classList.remove('fade');
            modalEl.classList.add('show');

            // Set inline styles with !important to override any CSS
            modalEl.style.cssText +=
                'display: block !important; visibility: visible !important; opacity: 1 !important; position: fixed !important; z-index: 1055 !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; overflow-x: hidden !important; overflow-y: auto !important;';

            // Also ensure modal-dialog has proper positioning
            const modalDialog = modalEl.querySelector('.modal-dialog');
            if (modalDialog) {
                modalDialog.style.cssText +=
                    'position: relative !important; margin: 1.75rem auto !important; z-index: 1056 !important;';
                console.log('✅ Modal dialog styled');
            }

            // Ensure modal is visible
            const computedStyle = window.getComputedStyle(modalEl);
            console.log('Modal computed styles after manual show:', {
                display: computedStyle.display,
                visibility: computedStyle.visibility,
                opacity: computedStyle.opacity,
                zIndex: computedStyle.zIndex,
                position: computedStyle.position,
                ariaHidden: modalEl.getAttribute('aria-hidden')
            });

            document.body.classList.add('modal-open');
            document.body.style.paddingRight = '0px';
            document.body.style.overflow = 'hidden';

            // Use Bootstrap's backdrop if present; do NOT create our own (causes lingering overlay on close)
            const existingBackdrop = document.querySelector('.modal-backdrop');
            if (existingBackdrop) {
                existingBackdrop.classList.add('show');
            }

            // Trigger shown event manually
            const shownEvent = new Event('shown.bs.modal', {
                bubbles: true
            });
            modalEl.dispatchEvent(shownEvent);

            // Verify modal is actually visible
            setTimeout(() => {
                const isNowVisible = modalEl.classList.contains('show') &&
                    window.getComputedStyle(modalEl).display !== 'none' &&
                    window.getComputedStyle(modalEl).visibility !== 'hidden';
                console.log('Modal visibility verification:', {
                    isVisible: isNowVisible,
                    hasShowClass: modalEl.classList.contains('show'),
                    display: window.getComputedStyle(modalEl).display,
                    visibility: window.getComputedStyle(modalEl).visibility,
                    opacity: window.getComputedStyle(modalEl).opacity
                });

                if (!isNowVisible) {
                    console.error('❌ Modal still not visible after manual show attempt');
                    // Try even more aggressive approach
                    modalEl.style.cssText =
                        'display: block !important; visibility: visible !important; opacity: 1 !important; z-index: 1055 !important; position: relative !important;';
                }
            }, 100);

            // Ensure data loads after modal opens
            setTimeout(() => {
                console.log('🔵 Triggering data load from manual fallback');
                if (typeof window.loadPathologyRadiologyData === 'function') {
                    window.loadPathologyRadiologyData();
                } else {
                    console.warn('⚠️ loadPathologyRadiologyData function not found, trying fallback');
                    const pathEl = document.getElementById('pathologyOpt');
                    const radEl = document.getElementById('radiologyOpt');
                    if (pathEl && pathEl.options.length <= 1) {
                        console.log('Pathology data missing, triggering fetch...');
                        if (typeof window.initializePathologyMultiselect === 'function') {
                            window.initializePathologyMultiselect();
                        }
                    }
                    if (radEl && radEl.options.length <= 1) {
                        console.log('Radiology data missing, triggering fetch...');
                        if (typeof window.initializeRadiologyMultiselect === 'function') {
                            window.initializeRadiologyMultiselect();
                        }
                    }
                }
            }, 500);

            console.log('✅ Modal manually shown');
            return true;
        }

        // Also handle Bootstrap data attributes as fallback for all prescription buttons
        // Ensure functions are available before DOMContentLoaded
        console.log('🔵 IPD View Script Block 2 Loading - checking functions:', {
            fetchPathologyRadiologyData: typeof window.fetchPathologyRadiologyData,
            openAddPrescriptionModal: typeof openAddPrescriptionModal
        });

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔵 DOMContentLoaded fired for IPD view');

            const addPrescriptionModal = document.getElementById('addPrescriptionModal');
            if (addPrescriptionModal && addPrescriptionModal.parentNode !== document.body) {
                document.body.appendChild(addPrescriptionModal);
            }
            if (addPrescriptionModal) {
                addPrescriptionModal.addEventListener('show.bs.modal', function() {
                    document.body.appendChild(addPrescriptionModal);
                    addPrescriptionModal.classList.add('show');
                    addPrescriptionModal.style.display = 'block';
                    addPrescriptionModal.style.visibility = 'visible';
                    addPrescriptionModal.setAttribute('aria-hidden', 'false');
                });
                addPrescriptionModal.addEventListener('shown.bs.modal', function() {
                    addPrescriptionModal.classList.add('show');
                    addPrescriptionModal.style.display = 'block';
                    addPrescriptionModal.style.visibility = 'visible';

                    function doInit() {
                        if (typeof window.initPathologyRadiologySelect2 === 'function') window
                            .initPathologyRadiologySelect2();
                        if (typeof window.loadPathologyRadiologyData === 'function') window
                            .loadPathologyRadiologyData();
                    }
                    doInit();
                    [200, 500, 1000, 1500, 2500, 3500].forEach(function(d) {
                        setTimeout(doInit, d);
                    });
                });
            }

            const prescriptionButtons = document.querySelectorAll(
                '[data-bs-target="#addPrescriptionModal"][data-ipd-id]');
            prescriptionButtons.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const ipdId = this.getAttribute('data-ipd-id');
                    const modalEl = document.getElementById('addPrescriptionModal');
                    if (!modalEl) return;
                    document.body.appendChild(modalEl);
                    if (ipdId) {
                        const ipdIdField = document.getElementById('ipd_id');
                        if (ipdIdField) ipdIdField.value = ipdId;
                        const admissionDate = this.getAttribute('data-admission-date');
                        const prescriptionDateEl = document.getElementById('prescription_date');
                        if (prescriptionDateEl) {
                            prescriptionDateEl.max = new Date().toISOString().split('T')[0];
                            if (admissionDate) {
                                prescriptionDateEl.value = admissionDate;
                                prescriptionDateEl.min = admissionDate;
                            } else {
                                prescriptionDateEl.removeAttribute('min');
                                if (!prescriptionDateEl.value) prescriptionDateEl.value =
                                    prescriptionDateEl.max;
                            }
                        }
                    }
                    if (typeof window.fetchPathologyRadiologyData === 'function') window
                        .fetchPathologyRadiologyData();
                    if (typeof window.fetchMedicineDropdownData === 'function') window
                        .fetchMedicineDropdownData();
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        bootstrap.Modal.getOrCreateInstance(modalEl).show();
                    }
                });
            });

            console.log('🔵 Button event listeners attached');
        });

        console.log('🔵 IPD View Script Block 2 Loaded');
    </script>
    <!-- Chart JS (CDN fallback; chart.min.js may not exist in assets) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('assets/plugins/chartjs/chart-data.js') }}"></script>
    <script>
        (function() {
            const paymentModeEl = document.getElementById('payment_mode');
            if (!paymentModeEl) return;
            paymentModeEl.addEventListener('change', function() {
                const chequeFields = document.getElementById('chequeFields');
                if (!chequeFields) return;
                if (this.value === 'Cheque') {
                    chequeFields.style.display = 'flex';
                    chequeFields.querySelectorAll('input').forEach(el => el.required = true);
                } else {
                    chequeFields.style.display = 'none';
                    chequeFields.querySelectorAll('input').forEach(el => {
                        el.required = false;
                        el.value = '';
                    });
                }
            });
        })();
    </script>

    <script>
        let operations = @json($operations); // All operations from DB

        (function() {
            const operationCategoryEl = document.getElementById('operation_category');
            if (!operationCategoryEl) return;
            operationCategoryEl.addEventListener('change', function() {
                let catId = this.value;
                let operationDropdown = document.getElementById('operation_type');
                if (!operationDropdown) return;
                operationDropdown.innerHTML = '<option value="">Select Operation</option>';
                if (catId) {
                    operations.forEach(op => {
                        if (op.category_id == catId) {
                            operationDropdown.innerHTML +=
                                `<option value="${op.id}">${op.operation}</option>`;
                        }
                    });
                }
            });
        })();
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
        let medicines = @json($medicinesByCategory);
        let dosages = @json($dosages); // grouped by medicine_id

        (function() {
            let mediCatDropdown = document.getElementById('medi_cat');
            let medDropdown = document.getElementById('med_name');
            let doseDropdown = document.getElementById('dosage');
            if (!mediCatDropdown || !medDropdown || !doseDropdown) return;

            mediCatDropdown.addEventListener('change', function() {
                let categoryId = this.value;
                medDropdown.innerHTML = '<option value="">Select</option>';
                doseDropdown.innerHTML = '<option value="">Select</option>';
                if (categoryId && medicines[categoryId]) {
                    medicines[categoryId].forEach(med => {
                        medDropdown.innerHTML +=
                            `<option value="${med.id}">${med.medicine_name}</option>`;
                    });
                }
            });

            medDropdown.addEventListener('change', function() {
                let medId = this.value;
                doseDropdown.innerHTML = '<option value="">Select</option>';
                if (medId && dosages[medId]) {
                    dosages[medId].forEach(dose => {
                        doseDropdown.innerHTML += `<option value="${dose.id}">${dose.dosage}</option>`;
                    });
                }
            });
        })();
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const chargeTypeSelect = document.getElementById("add_charge_type");
            const chargeCategorySelect = document.getElementById("charge_category2");
            const chargeSelect = document.getElementById("charge_id");

            const standardChargeInp = document.getElementById("addstandard_charge");
            const tpaChargeInp = document.getElementById("addscd_charge");
            const qtyInp = document.getElementById("qty");
            const totalInp = document.getElementById("apply_charge");
            const discountPercInp = document.getElementById("discount_percentage_add_charge");
            const discountAmtInp = document.getElementById("discount_percentage_amount");
            const taxPercInp = document.getElementById("charge_tax");
            const taxAmtInp = document.getElementById("tax_amt");
            const netAmountInp = document.getElementById("final_amount");

            const previewBody = document.getElementById("preview_charges");
            const addBtn = document.querySelector("button[name='charge_data']");

            const addBtnCol = document.getElementById("charge_add_button_col");
            const previewWrapper = document.getElementById("charge_preview_wrapper");
            const modalFooterBtn = document.querySelector("#add_charges .modal-footer button[type='submit']");
            const openAddChargesBtn = document.getElementById("open_add_charges_btn");

            const billVisCheckboxIds = {
                show_on_approval_bill: 'bill_vis_approval',
                show_on_approval_preview: 'bill_vis_approval_preview',
                show_on_final_preview: 'bill_vis_final_preview',
                show_on_final_bill: 'bill_vis_final_bill',
            };

            function billVisibilityHiddenInputsHtml() {
                let html = '';
                Object.keys(billVisCheckboxIds).forEach(function(field) {
                    const cb = document.getElementById(billVisCheckboxIds[field]);
                    const val = cb && cb.checked ? '1' : '0';
                    html += `<input type="hidden" name="${field}[]" value="${val}">`;
                });
                return html;
            }

            function resetBillVisibilityCheckboxes(allChecked) {
                Object.values(billVisCheckboxIds).forEach(function(id) {
                    const cb = document.getElementById(id);
                    if (cb) {
                        cb.checked = !!allChecked;
                        cb.removeAttribute('name');
                    }
                });
            }

            function setBillVisibilityFromCharge(charge) {
                Object.keys(billVisCheckboxIds).forEach(function(field) {
                    const cb = document.getElementById(billVisCheckboxIds[field]);
                    if (cb) {
                        cb.checked = charge[field] !== false && charge[field] !== 0;
                        cb.removeAttribute('name');
                    }
                });
            }

            function syncEditBillVisibilityHiddenFields() {
                Object.keys(billVisCheckboxIds).forEach(function(field) {
                    const cb = document.getElementById(billVisCheckboxIds[field]);
                    const hidden = document.querySelector('#bill_visibility_edit_fields input[name="' + field + '"]');
                    if (hidden && cb) {
                        hidden.value = cb.checked ? '1' : '0';
                    }
                });
            }

            const addChargeFormEl = document.getElementById('addChargeForm');
            if (addChargeFormEl) {
                addChargeFormEl.addEventListener('submit', function() {
                    if (window.editIpdChargeId) {
                        syncEditBillVisibilityHiddenFields();
                    }
                });
            }

            document.addEventListener('change', function(e) {
                const toggle = e.target.closest('.ipd-charge-bill-vis-toggle');
                if (!toggle) {
                    return;
                }

                const chargeId = toggle.getAttribute('data-charge-id');
                const field = toggle.getAttribute('data-field');
                const value = toggle.checked ? 1 : 0;
                const csrfMeta = document.querySelector("meta[name='csrf-token']");
                const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

                fetch("{{ url('/ipd_charge') }}/" + chargeId + "/bill-visibility", {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ field: field, value: value })
                })
                    .then(function(res) { return res.json().then(function(body) { return { ok: res.ok, body: body }; }); })
                    .then(function(result) {
                        if (!result.ok || !result.body.success) {
                            toggle.checked = !toggle.checked;
                            alert((result.body && result.body.message) ? result.body.message : 'Unable to update bill visibility.');
                        }
                    })
                    .catch(function() {
                        toggle.checked = !toggle.checked;
                        alert('Unable to update bill visibility.');
                    });
            });

            function enterAddMode() {
                window.editIpdChargeId = null;

                if (addBtnCol) addBtnCol.classList.remove('d-none');
                if (previewWrapper) previewWrapper.classList.remove('d-none');
                if (modalFooterBtn) modalFooterBtn.textContent = 'Save';

                resetBillVisibilityCheckboxes(true);

                // Auto-set admission date in charge_date field
                const ipdContext = document.getElementById('ipdViewContext');
                const chargeDate = document.getElementById('charge_date');
                if (ipdContext && chargeDate && ipdContext.hasAttribute('data-admission-date')) {
                    const admissionDate = ipdContext.getAttribute('data-admission-date');
                    if (admissionDate) {
                        chargeDate.value = admissionDate;
                    }
                }

                // Reset form action back to create route and remove PUT override
                const form = document.getElementById('addChargeForm');
                if (form) {
                    form.action = "{{ route('ipd.addIpdCharge') }}";
                    const methodInput = form.querySelector('input[name=\"_method\"]');
                    if (methodInput) methodInput.remove();
                }
            }

            if (openAddChargesBtn) {
                openAddChargesBtn.addEventListener('click', enterAddMode);
            }

            /*--------------------------------------------------
             | FUNCTION TO POPULATE CHARGE TYPES
             --------------------------------------------------*/
            function populateChargeTypes() {
                if (window.chargeTypeData) {
                    chargeTypeSelect.innerHTML = `<option value="">Select</option>`;
                    window.chargeTypeData.forEach(type => {
                        chargeTypeSelect.innerHTML += `
                    <option value="${type.id}">${type.charge_type}</option>
                `;
                    });
                    refreshSelect2(chargeTypeSelect);
                }
            }

            /*--------------------------------------------------
             | FETCH CHARGE TYPES
             --------------------------------------------------*/
            fetch("{{ route('getChargeTypes') }}")
                .then(res => res.json())
                .then(data => {
                    window.chargeTypeData = data;
                    populateChargeTypes();
                });

            /*--------------------------------------------------
             | FETCH CATEGORIES BY TYPE
             --------------------------------------------------*/
            chargeTypeSelect.addEventListener("change", function() {

                chargeCategorySelect.innerHTML = `<option value="">Select</option>`;
                chargeSelect.innerHTML = `<option value="">Select</option>`;

                if (!this.value) return;

                fetch("{{ route('getChargeCategoriesByTypeId', ['id' => 'ID']) }}".replace('ID', this
                        .value))
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
            chargeCategorySelect.addEventListener("change", function() {

                chargeSelect.innerHTML = `<option value="">Select</option>`;

                const selectedChargeTypeText = chargeTypeSelect.selectedOptions[0]?.text || '';
                const selectedCategoryText = chargeCategorySelect.selectedOptions[0]?.text || '';

                if (!this.value) return;

                fetch("{{ route('getCharges', ['id' => 'ID']) }}".replace('ID', this.value))
                    .then(res => res.json())
                    .then(data => {
                        window.chargeData = data;
                        data.forEach(charge => {
                            chargeSelect.innerHTML += `
                        <option value="${charge.id}">${charge.name}</option>
                    `;
                        });

                        if (data.length > 0) {
                            chargeSelect.value = data[0].id;
                            refreshSelect2(chargeSelect);
                        }

                        if (isOneTimeWardSelection(selectedChargeTypeText, selectedCategoryText)) {
                            autoAddAllCharges(data);
                        }
                    });
            });

            function refreshSelect2(selectElement) {
                if (window.jQuery && window.jQuery(selectElement).hasClass('select2-hidden-accessible')) {
                    try {
                        window.jQuery(selectElement).trigger('change.select2');
                    } catch (e) {
                        console.warn('Select2 refresh failed', e);
                    }
                }
            }

            function isOneTimeWardSelection(chargeTypeText, categoryText) {
                const normalizedChargeType = String(chargeTypeText).toLowerCase().trim();
                // Only trigger auto-add for "OneTimeCharges" charge type
                return normalizedChargeType === 'onetimecharges';
            }

            function autoAddAllCharges(charges) {
                const chargeTypeText = chargeTypeSelect.selectedOptions[0]?.text || '';
                const categoryText = chargeCategorySelect.selectedOptions[0]?.text || '';
                const note = document.getElementById('edit_note').value || '';
                
                // Get admission date from the hidden ipdViewContext element
                const ipdContext = document.getElementById('ipdViewContext');
                let date = document.getElementById('charge_date').value || '';
                if (ipdContext && ipdContext.hasAttribute('data-admission-date')) {
                    date = ipdContext.getAttribute('data-admission-date') || date;
                    // Also update the date field so user can see it
                    document.getElementById('charge_date').value = date;
                }

                charges.forEach(charge => {
                    const standardCharge = parseFloat(charge.standard_charge || 0).toFixed(2);
                    const tpaCharge = '0.00';
                    const qty = 1;
                    const total = standardCharge;
                    const taxPerc = parseFloat(charge.tax_category?.percentage || 0) || 0;
                    const taxAmount = (parseFloat(total) * (taxPerc / 100)).toFixed(2);
                    const discountAmount = '0.00';
                    const netAmount = (parseFloat(total) + parseFloat(taxAmount)).toFixed(2);

                    const row = `
                        <tr>
                            <td>${date}</td>
                            <td>${chargeTypeText}</td>
                            <td>${categoryText}</td>
                            <td>${charge.name}<br><small>${note}</small></td>
                            <td class="text-right">${standardCharge}</td>
                            <td class="text-right">${tpaCharge}</td>
                            <td class="text-right">${qty}</td>
                            <td class="text-right">${total}</td>
                            <td class="text-right">${discountAmount}</td>
                            <td class="text-right">${taxAmount}</td>
                            <td class="text-right">${netAmount}</td>
                            <td class="text-right">
                                <button type="button" class="btn btn-danger btn-sm delete-charge-row">X</button>
                            </td>
                            <input type="hidden" name="charge_type[]" value="${chargeTypeSelect.value}">
                            <input type="hidden" name="charge_category[]" value="${chargeCategorySelect.value}">
                            <input type="hidden" name="charge_id[]" value="${charge.id}">
                            <input type="hidden" name="standard_charge[]" value="${standardCharge}">
                            <input type="hidden" name="tpa_charge[]" value="${tpaCharge}">
                            <input type="hidden" name="qty[]" value="${qty}">
                            <input type="hidden" name="total[]" value="${total}">
                            <input type="hidden" name="discount_percentage[]" value="${discountAmount}">
                            <input type="hidden" name="tax[]" value="${taxAmount}">
                            <input type="hidden" name="net_amount[]" value="${netAmount}">
                            <input type="hidden" name="charge_note[]" value="${note}">
                            <input type="hidden" name="charge_date[]" value="${date}">
                            ${billVisibilityHiddenInputsHtml()}
                        </tr>
                    `;

                    previewBody.insertAdjacentHTML('beforeend', row);
                });
            }

            /*--------------------------------------------------
             | AUTO-FILL ON CHARGE SELECT (ONCE)
             --------------------------------------------------*/
            chargeSelect.addEventListener("change", function() {

                const chargeId = this.value;
                const selectedCharge = window.chargeData.find(c => c.id == chargeId);
                if (!selectedCharge) return;

                standardChargeInp.value = selectedCharge.standard_charge ?? 0;
                tpaChargeInp.value = 0;
                qtyInp.value = 1;
                discountPercInp.value = 0;
                taxPercInp.value = selectedCharge.tax_category?.percentage ?? 0;

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
                const qty = parseFloat(qtyInp.value) || 1;

                const discountPerc = parseFloat(discountPercInp.value) || 0;
                const taxPerc = parseFloat(taxPercInp.value) || 0;

                const appliedCharge = standard * qty;
                const discountAmt = appliedCharge * (discountPerc / 100);
                const taxAmt = appliedCharge * (taxPerc / 100);
                const netAmount = appliedCharge + taxAmt - discountAmt;

                totalInp.value = appliedCharge.toFixed(2);
                discountAmtInp.value = discountAmt.toFixed(2);
                taxAmtInp.value = taxAmt.toFixed(2);
                netAmountInp.value = netAmount.toFixed(2);
            }

            /*--------------------------------------------------
             | ADD ROW TO PREVIEW TABLE
             --------------------------------------------------*/
            addBtn.addEventListener("click", function(e) {
                e.preventDefault();

                if (!chargeTypeSelect.value || !chargeCategorySelect.value || !chargeSelect.value) {
                    alert("Please fill required fields");
                    return;
                }

                // Capture date before any operations
                const chargeDate = document.getElementById('charge_date').value;
                if (!chargeDate) {
                    alert("Please select a date");
                    return;
                }

                // Verify date is in correct format (YYYY-MM-DD)
                const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
                if (!dateRegex.test(chargeDate)) {
                    alert("Date must be in YYYY-MM-DD format");
                    return;
                }

                const row = `
        <tr>
            <td>${chargeDate}</td>
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
            <input type="hidden" name="charge_date[]" value="${chargeDate}">
            ${billVisibilityHiddenInputsHtml()}
        </tr>
        `;

                previewBody.insertAdjacentHTML("beforeend", row);

                // Store date before reset
                const ipdContext = document.getElementById('ipdViewContext');
                const admissionDate = ipdContext ? ipdContext.getAttribute('data-admission-date') : chargeDate;

                document.getElementById("addChargeForm").reset();
                
                // Restore date and repopulate dropdowns after reset
                document.getElementById("charge_date").value = admissionDate;
                populateChargeTypes();
                chargeCategorySelect.innerHTML = `<option value="">Select</option>`;
                chargeSelect.innerHTML = `<option value="">Select</option>`;
                totalInp.value = '0.00';
                discountAmtInp.value = '0.00';
                taxAmtInp.value = '0.00';
                netAmountInp.value = '0.00';
                resetBillVisibilityCheckboxes(true);
                
                console.log('✅ Charge added. Date captured:', chargeDate, 'Dropdowns repopulated');
            });

            /*--------------------------------------------------
             | DELETE ROW
             --------------------------------------------------*/
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("delete-charge-row")) {
                    e.target.closest("tr").remove();
                }
            });

            /*--------------------------------------------------
             | RESET CHARGE MODAL ON CLOSE
             --------------------------------------------------*/
            const addChargesModalEl = document.getElementById('add_charges');

            function resetChargeModal() {
                const form = document.getElementById('addChargeForm');
                
                // Store admission date before form reset
                const ipdContext = document.getElementById('ipdViewContext');
                const admissionDate = ipdContext ? ipdContext.getAttribute('data-admission-date') : '';
                
                if (form) {
                    form.reset();
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput) methodInput.remove();
                    form.action = "{{ route('ipd.addIpdCharge') }}";
                }

                // Restore admission date after form reset
                const chargeDate = document.getElementById('charge_date');
                if (chargeDate && admissionDate) {
                    chargeDate.value = admissionDate;
                }

                // Repopulate charge types and clear other dropdowns
                populateChargeTypes();
                chargeCategorySelect.innerHTML = `<option value="">Select</option>`;
                chargeSelect.innerHTML = `<option value="">Select</option>`;

                // Clear dynamic preview rows
                if (previewBody) previewBody.innerHTML = '';

                // Reset calculated fields
                totalInp.value = '0.00';
                discountPercInp.value = '0';
                discountAmtInp.value = '0.00';
                taxPercInp.value = '0';
                taxAmtInp.value = '0.00';
                netAmountInp.value = '0.00';

                // Return to Add mode layout (button + table visible, footer Save)
                enterAddMode();
            }

            if (addChargesModalEl) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    addChargesModalEl.addEventListener('hidden.bs.modal', resetChargeModal);
                } else {
                    // Fallback: if closed via custom logic
                    addChargesModalEl.addEventListener('click', function(e) {
                        if (e.target.classList.contains('btn-close')) {
                            resetChargeModal();
                        }
                    });
                }
            }

            /*--------------------------------------------------
             | EDIT EXISTING IPD CHARGE (Charges tab)
             | - Reuse Add Charges modal
             | - Uses delegated click handler for robustness
             --------------------------------------------------*/
            document.addEventListener('click', function(event) {
                const btn = event.target.closest('.edit-ipd-charge-btn');
                if (!btn) {
                    return;
                }

                const chargeId = btn.getAttribute('data-charge-id');
                if (!chargeId) {
                    return;
                }

                fetch("{{ url('/ipd_charge') }}/" + chargeId)
                    .then(response => response.json())
                    .then(payload => {
                        if (!payload.success || !payload.data) {
                            alert('Unable to load charge details.');
                            return;
                        }

                        const charge = payload.data;

                        window.editIpdChargeId = charge.id;

                        const modalEl = document.getElementById('add_charges');

                        // Open modal safely even if Bootstrap is not available
                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            let bsModal = bootstrap.Modal.getInstance(modalEl);
                            if (!bsModal) {
                                bsModal = new bootstrap.Modal(modalEl);
                            }
                            bsModal.show();
                        } else {
                            modalEl.classList.add('show');
                            modalEl.style.display = 'block';
                            modalEl.removeAttribute('aria-hidden');
                        }

                        document.querySelector('#add_charges .modal-title').textContent =
                        'Edit Charges';
                        const form = document.getElementById('addChargeForm');
                        form.action = "{{ url('/ipd_charge') }}/" + charge.id;

                        let methodInput = form.querySelector('input[name="_method"]');
                        if (!methodInput) {
                            methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            form.appendChild(methodInput);
                        }
                        methodInput.value = 'PUT';

                        // In edit mode, hide the Add-row section and preview table,
                        // and change footer button text to "Update"
                        if (addBtnCol) addBtnCol.classList.add('d-none');
                        if (previewWrapper) {
                            previewWrapper.classList.add('d-none');
                            if (previewBody) previewBody.innerHTML = '';
                        }
                        if (modalFooterBtn) modalFooterBtn.textContent = 'Update';

                        document.getElementById('ipd_id').value = charge.ipd_id;

                        const chargeTypeSelect = document.getElementById('add_charge_type');
                        const chargeCategorySelect = document.getElementById('charge_category2');
                        const chargeSelect = document.getElementById('charge_id');

                        // 1) Set charge type and load categories
                        if (chargeTypeSelect) {
                            chargeTypeSelect.value = charge.charge_type_id;
                        }

                        if (chargeTypeSelect && chargeCategorySelect && chargeSelect) {
                            // Load categories for this type
                            chargeCategorySelect.innerHTML = '<option value=\"\">Select</option>';
                            chargeSelect.innerHTML = '<option value=\"\">Select</option>';

                            fetch("{{ route('getChargeCategoriesByTypeId', ['id' => 'ID']) }}"
                                    .replace('ID', charge.charge_type_id))
                                .then(res => res.json())
                                .then(categoryData => {
                                    categoryData.forEach(cat => {
                                        chargeCategorySelect.innerHTML +=
                                            `<option value=\"${cat.id}\">${cat.name}</option>`;
                                    });
                                    chargeCategorySelect.value = charge.charge_category_id;

                                    // 2) Load charges for this category
                                    return fetch("{{ route('getCharges', ['id' => 'ID']) }}"
                                        .replace('ID', charge.charge_category_id));
                                })
                                .then(res => res.json())
                                .then(chargesData => {
                                    chargeSelect.innerHTML = '<option value=\"\">Select</option>';
                                    chargesData.forEach(ch => {
                                        chargeSelect.innerHTML +=
                                            `<option value=\"${ch.id}\">${ch.name}</option>`;
                                    });
                                    chargeSelect.value = charge.charge_id;
                                })
                                .catch(() => {
                                    // Fallback: ignore select population errors, user can re-select manually
                                });
                        }

                        const standardInp = document.getElementById('addstandard_charge');
                        const tpaInp = document.getElementById('addscd_charge');
                        const qtyInp = document.getElementById('qty');
                        const totalInp = document.getElementById('apply_charge');
                        const discPercInp = document.getElementById('discount_percentage_add_charge');
                        const discAmtInp = document.getElementById('discount_percentage_amount');
                        const taxPercInp = document.getElementById('charge_tax');
                        const taxAmtInp = document.getElementById('tax_amt');
                        const netAmtInp = document.getElementById('final_amount');

                        const appliedCharge = parseFloat(charge.total ?? 0) || 0;
                        const discountAmount = parseFloat(charge.discount_percentage ?? 0) ||
                        0; // stored as amount
                        const taxAmount = parseFloat(charge.tax ?? 0) || 0;
                        const discountPercent = appliedCharge > 0 ? (discountAmount / appliedCharge) *
                            100 : 0;
                        const taxPercent = appliedCharge > 0 ? (taxAmount / appliedCharge) * 100 : 0;

                        standardInp.value = charge.standard_charge ?? 0;
                        tpaInp.value = charge.tpa_charge ?? 0;
                        qtyInp.value = charge.qty ?? 1;
                        totalInp.value = appliedCharge.toFixed(2);

                        // UI shows "Discount Percentage (INR)" – amount field + % label
                        discPercInp.value = discountPercent.toFixed(2);
                        discAmtInp.value = discountAmount.toFixed(2);

                        taxPercInp.value = taxPercent.toFixed(2);
                        taxAmtInp.value = taxAmount.toFixed(2);

                        netAmtInp.value = (parseFloat(charge.net_amount ?? 0) || 0).toFixed(2);
                        document.getElementById('edit_note').value = charge.charge_note ?? '';
                        document.getElementById('charge_date').value = charge.date ?
                            String(charge.date).substring(0, 10) :
                            '';
                        setBillVisibilityFromCharge(charge);
                    })
                    .catch(() => {
                        alert('Error loading charge details.');
                    });
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
                var chartEl = document.getElementById('chartLine1');
                if (!chartEl) return;
                var ctx = chartEl.getContext('2d');
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
            const nurseSelect = document.getElementById('nurse');
            if (!nurseSelect) return;
            nurseSelect.innerHTML = '<option value="">Loading...</option>';

            fetch("{{ route('getNurses') }}")
                .then(response => response.json())
                .then(data => {
                    window.nursesData = data;
                    nurseSelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(nurse => {
                        const option = document.createElement('option');
                        option.value = nurse.id;
                        option.textContent = nurse.name;
                        if ("{{ old('nurse') }}" == nurse.id) {
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
            if (vitalFields) vitalFields.querySelectorAll(".remove-btn").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    btn.closest(".vital-row").remove();
                });
            });

            if (addBtn && vitalFields) {
                addBtn.addEventListener("click", function() {
                    let firstRow = vitalFields.querySelector(".vital-row");
                    if (!firstRow) return;
                    let newRow = firstRow.cloneNode(true);

                    newRow.querySelectorAll("input, select").forEach(el => el.value = "");
                    let removeBtn = newRow.querySelector(".remove-btn");
                    if (removeBtn) removeBtn.style.display = "inline-block";

                    removeBtn && removeBtn.addEventListener("click", function() {
                        newRow.remove();
                    });

                    vitalFields.appendChild(newRow);
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {

            // Load bed groups on page load
            $.get("{{ route('getBedGroups') }}")
                .done(function(data) {
                    var options = '<option value="">Select Bed Group</option>';
                    if (Array.isArray(data)) {
                        data.forEach(function(group) {
                            var floorName = (group.floor_detail && group.floor_detail.name) ? group
                                .floor_detail.name : '-';
                            options += '<option value="' + (group.id || '') + '">' + (group.name ||
                                '') + ' - ' + floorName + '</option>';
                        });
                    }
                    $('#bed_group').html(options);
                })
                .fail(function() {
                    $('#bed_group').html(
                        '<option value="">Select Bed Group</option><option value="" disabled>Could not load bed groups</option>'
                        );
                });

            // Load available beds when bed group changes
            $('#bed_group').on('change', function() {
                let groupId = $(this).val();
                $('#new_bed').html('<option value="">Loading...</option>');

                if (groupId) {
                    // Fetch bed charge for selected bed group
                    $.get("{{ url('/getBedGroupCharge') }}/" + groupId, function(data) {
                        if (data && data.bed_cost) {
                            $('#bed_charge_transfer').val(data.bed_cost);
                        } else {
                            $('#bed_charge_transfer').val('');
                        }
                    }).fail(function() {
                        $('#bed_charge_transfer').val('');
                    });

                    // Load available beds
                    $.get("{{ route('get.available.beds') }}", {
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
                    $('#bed_charge_transfer').val('');
                }
            });

            // Edit Bed History modal - load groups and beds before showing
            $(document).on('click', '.edit-bed-history-btn', function() {
                var btn = $(this);
                var bedId = btn.data('bed-id');
                var groupId = btn.data('bed-group-id');
                $('#edit_bed_history_id').val(btn.data('history-id'));
                $('#edit_ipd_id').val(btn.data('ipd-id'));
                $('#edit_from_date').val(btn.data('from-date'));
                $('#edit_to_date').val(btn.data('to-date') || '');
                $('#editBedHistoryError').addClass('d-none').text('');
                $('#edit_bed_group').html('<option value="">Loading...</option>');
                $('#edit_bed').html('<option value="">Loading...</option>');

                function showModal() {
                    new bootstrap.Modal(document.getElementById('editBedHistoryModal')).show();
                }

                if (!groupId) {
                    $('#edit_bed_group').html('<option value="">Select Bed Group</option>');
                    $('#edit_bed').html('<option value="">Select Bed</option>');
                    showModal();
                    return;
                }

                $.get("{{ route('getBedGroups') }}")
                    .done(function(data) {
                        var opts = '<option value="">Select Bed Group</option>';
                        if (Array.isArray(data)) {
                            data.forEach(function(g) {
                                var fn = (g.floor_detail && g.floor_detail.name) ? g
                                    .floor_detail.name : '-';
                                opts += '<option value="' + (g.id || '') + '">' + (g.name ||
                                    '') + ' - ' + fn + '</option>';
                            });
                        }
                        $('#edit_bed_group').html(opts).val(groupId);

                        $.get("{{ route('get.available.beds') }}", {
                            bed_group_id: groupId,
                            include_bed_id: bedId
                        }, function(beds) {
                            var options = '<option value="">Select Bed</option>';
                            beds.forEach(function(bed) {
                                var sel = (bed.id == bedId) ? ' selected' : '';
                                options += '<option value="' + bed.id + '"' + sel +
                                    '>' + bed.name + '</option>';
                            });
                            $('#edit_bed').html(options);
                        });

                        var existingCharge = btn.data('bed-charge');
                        if (existingCharge !== undefined && existingCharge !== null &&
                            existingCharge !== '') {
                            $('#edit_bed_charge').val(existingCharge);
                        } else {
                            $.get("{{ url('/getBedGroupCharge') }}/" + groupId, function(data) {
                                $('#edit_bed_charge').val(data && data.bed_cost ? data
                                    .bed_cost : '');
                            });
                        }

                        showModal();
                    })
                    .fail(function() {
                        $('#edit_bed_group').html('<option value="">Select Bed Group</option>');
                        showModal();
                    });
            });

            // Delete latest bed history
            $(document).on('click', '.delete-bed-history-btn', function() {
                var btn = $(this);
                $('#delete_bed_history_id').val(btn.data('history-id'));
                $('#delete_ipd_id').val(btn.data('ipd-id'));
                $('#delete_bed_group_label').text(btn.data('bed-group') || '-');
                $('#delete_bed_name_label').text(btn.data('bed-name') || '-');
                $('#delete_from_date_label').text(btn.data('from-date') || '-');
                new bootstrap.Modal(document.getElementById('deleteBedHistoryModal')).show();
            });

            // Add Bed History - reuse same modal in create mode
            $(document).on('click', '.add-bed-history-btn', function() {
                $('#editBedHistoryError').addClass('d-none').text('');
                $('#edit_bed_history_id').val(''); // no existing record
                $('#edit_from_date').val('');
                $('#edit_to_date').val('');
                $('#edit_bed_charge').val('');

                // Set form to store route and adjust labels
                $('#editBedHistoryForm').attr('action', "{{ route('ipd.bedHistory.store') }}");
                $('#editBedHistoryModalLabel').html('<i class="ti ti-plus me-2"></i>Add Bed History');
                $('#editBedHistoryForm button[type=\"submit\"]').text('Save');

                $('#edit_bed_group').html('<option value=\"\">Loading...</option>');
                $('#edit_bed').html('<option value=\"\">Select Bed</option>');

                $.get("{{ route('getBedGroups') }}")
                    .done(function(data) {
                        var opts = '<option value=\"\">Select Bed Group</option>';
                        if (Array.isArray(data)) {
                            data.forEach(function(g) {
                                var fn = (g.floor_detail && g.floor_detail.name) ? g
                                    .floor_detail.name : '-';
                                opts += '<option value=\"' + (g.id || '') + '\">' + (g.name ||
                                    '') + ' - ' + fn + '</option>';
                            });
                        }
                        $('#edit_bed_group').html(opts);
                    })
                    .fail(function() {
                        $('#edit_bed_group').html('<option value=\"\">Select Bed Group</option>');
                    });

                new bootstrap.Modal(document.getElementById('editBedHistoryModal')).show();
            });

            $('#edit_bed_group').on('change', function() {
                var groupId = $(this).val();
                var curBedId = $('#edit_bed').val();
                $('#edit_bed').html('<option value="">Loading...</option>');
                if (groupId) {
                    $.get("{{ url('/getBedGroupCharge') }}/" + groupId, function(data) {
                        if (data && data.bed_cost) $('#edit_bed_charge').val(data.bed_cost);
                        else $('#edit_bed_charge').val('');
                    });
                    $.get("{{ route('get.available.beds') }}", {
                        bed_group_id: groupId,
                        include_bed_id: curBedId
                    }, function(data) {
                        var options = '<option value="">Select Bed</option>';
                        data.forEach(function(bed) {
                            var sel = (bed.id == curBedId) ? ' selected' : '';
                            options += '<option value="' + bed.id + '"' + sel + '>' + bed
                                .name + '</option>';
                        });
                        $('#edit_bed').html(options);
                    });
                } else {
                    $('#edit_bed').html('<option value="">Select Bed</option>');
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

    <!-- Package Management Script -->
    @include('admin.ipd.partials.package_select2_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ipdId = "{{ $ipd->id }}";
            const packagesUrl = "{{ route('ipd.packages', $ipd->id) }}";
            const isInsuranceIpd = @json((bool) ($ipd->insurance_company_id || $ipd->is_cashless || $ipd->organisation_id));
            const canEditPackages = @json($ipd->discharged != 'yes');
            const packageSelectEl = document.getElementById('package_select');
            const applyPackageForm = document.getElementById('apply_package_form');
            const appliedPackagesList = document.getElementById('applied-packages-list');
            const applyPackageModal = document.getElementById('apply_package_modal');
            const editAppliedPackageForm = document.getElementById('edit_applied_package_form');
            const editAppliedPackageModal = document.getElementById('edit_applied_package_modal');
            const packagesTabLink = document.querySelector('a[href="#packages"]');
            let appliedPackagesCache = [];

            function formatInr(amount) {
                const n = parseFloat(amount);
                if (isNaN(n)) return '₹0.00';
                return '₹' + n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function toDateInputValue(dateStr) {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                if (isNaN(d.getTime())) return '';
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${y}-${m}-${day}`;
            }

            if (applyPackageModal) {
                applyPackageModal.addEventListener('shown.bs.modal', function() {
                    loadAvailablePackages();
                    const packageAmountInputEl = document.getElementById('package_amount_input');
                    if (packageAmountInputEl) packageAmountInputEl.value = '';
                    const detailsDiv = document.getElementById('package_details');
                    if (detailsDiv) detailsDiv.style.display = 'none';
                });
            }

            if (packagesTabLink) {
                packagesTabLink.addEventListener('shown.bs.tab', function() {
                    loadAppliedPackages();
                });
            }

            if (appliedPackagesList) {
                loadAppliedPackages();
            }

            function packageIsInsurance(pkg) {
                if (!pkg) return false;
                const p = pkg.package || pkg;
                return p.package_type === 'insurance'
                    || (p.insurance_company_id != null && p.insurance_company_id !== '')
                    || (p.insurance_rate_panel_id != null && p.insurance_rate_panel_id !== '')
                    || (p.insurer_procedure_code != null && p.insurer_procedure_code !== '');
            }

            function loadAvailablePackages() {
                if (!packageSelectEl) return;

                fetch(packagesUrl)
                    .then(response => {
                        const ct = (response.headers.get('content-type') || '').toLowerCase();
                        if (!ct.includes('application/json')) {
                            return {
                                success: false,
                                available_packages: []
                            };
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.available_packages) {
                            const currentOptions = packageSelectEl.value;

                            if (window.IpdPackageSelect) {
                                window.IpdPackageSelect.appendOptions(packageSelectEl, data.available_packages);
                                window.IpdPackageSelect.initSelect2(
                                    packageSelectEl,
                                    document.getElementById('apply_package_modal')
                                );
                            } else {
                                packageSelectEl.innerHTML = '<option value="">-- Select Package --</option>';
                                data.available_packages.forEach(pkg => {
                                    const option = document.createElement('option');
                                    option.value = pkg.id;
                                    option.textContent = pkg.display_title || pkg.name;
                                    option.dataset.rate = pkg.package_rate;
                                    option.dataset.gst = pkg.gst_amount ?? '';
                                    option.dataset.desc = pkg.description ?? '';
                                    option.dataset.insuranceCompanyId = pkg.insurance_company_id ?? '';
                                    option.dataset.packageType = packageIsInsurance(pkg) ? 'insurance' : 'hospital';
                                    packageSelectEl.appendChild(option);
                                });
                            }

                            if (currentOptions) {
                                packageSelectEl.value = currentOptions;
                                if (window.jQuery) {
                                    window.jQuery(packageSelectEl).trigger('change');
                                }
                            }
                            toggleApplyApprovalField();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading packages:', error);
                    });
            }

            function loadAppliedPackages() {
                if (!appliedPackagesList) return;

                fetch(packagesUrl)
                    .then(response => {
                        const ct = (response.headers.get('content-type') || '').toLowerCase();
                        if (!response.ok || !ct.includes('application/json')) {
                            appliedPackagesList.innerHTML =
                                '<div class="alert alert-warning"><i class="ti ti-alert-circle me-2"></i>Could not load packages. You can still add prescriptions.</div>';
                            return null;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data) return;
                        if (data.success && data.applied_packages) {
                            appliedPackagesCache = data.applied_packages;
                            if (data.applied_packages.length === 0) {
                                appliedPackagesList.innerHTML =
                                    '<div class="alert alert-info"><i class="ti ti-info-circle me-2"></i>No packages applied yet.</div>';
                            } else {
                                const showApprovalColumn = true;
                                let headerCols =
                                    '<th>Package</th><th>Applied Date</th><th>Contract Rate (INR)</th>';
                                if (showApprovalColumn) {
                                    headerCols += '<th>Approval %</th>';
                                }
                                headerCols += '<th>Final Amount</th><th class="text-center">Action</th>';
                                let html =
                                    `<div class="table-responsive"><table class="table table-hover"><thead><tr>${headerCols}</tr></thead><tbody>`;

                                data.applied_packages.forEach(pkg => {
                                    const rate = parseFloat(pkg.package_rate);
                                    const finalAmt = parseFloat(pkg.final_amount);
                                    const approvalDisplay = pkg.approval_percentage != null && pkg.approval_percentage !== '' ?
                                        parseFloat(pkg.approval_percentage).toFixed(2) + '%' : '—';
                                    let approvalCell = '';
                                    if (showApprovalColumn) {
                                        approvalCell = `<td>${approvalDisplay}</td>`;
                                    }
                                    const actionBtns = pkg.status === 'applied' && canEditPackages
                                        ? `<div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-warning edit-pkg-btn" data-ipd-pkg-id="${pkg.id}" title="Edit package">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger remove-pkg-btn" data-ipd-pkg-id="${pkg.id}" title="Remove package">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                           </div>`
                                        : `<span class="badge bg-secondary">${pkg.status}</span>`;
                                    html += `<tr>
                                    <td><strong>${pkg.package.name}</strong></td>
                                    <td>${new Date(pkg.applied_date).toLocaleDateString()}</td>
                                    <td>${formatInr(rate)}</td>
                                    ${approvalCell}
                                    <td class="package-final-amount" data-ipd-pkg-id="${pkg.id}">${formatInr(finalAmt)}</td>
                                    <td class="text-center">${actionBtns}</td>
                                </tr>`;
                                });

                                html += '</tbody></table></div>';
                                appliedPackagesList.innerHTML = html;

                                document.querySelectorAll('.edit-pkg-btn').forEach(btn => {
                                    btn.addEventListener('click', function() {
                                        openEditAppliedPackageModal(this.dataset.ipdPkgId);
                                    });
                                });
                                document.querySelectorAll('.remove-pkg-btn').forEach(btn => {
                                    btn.addEventListener('click', function() {
                                        if (confirm(
                                                'Are you sure you want to remove this package?'
                                                )) {
                                            removePackage(this.dataset.ipdPkgId);
                                        }
                                    });
                                });
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading applied packages:', error);
                    });
            }

            // Handle package selection change - show details and set package amount
            const packageAmountInputEl = document.getElementById('package_amount_input');
            const approvalWrapEl = document.getElementById('approval_percentage_wrap');
            const approvalInputEl = document.getElementById('approval_percentage_input');

            function toggleApplyApprovalField() {
                if (!approvalWrapEl || !packageSelectEl) return;
                const show = !!packageSelectEl.value;
                approvalWrapEl.style.display = show ? 'block' : 'none';
                if (!show && approvalInputEl) approvalInputEl.value = '';
            }

            if (packageSelectEl) {
                packageSelectEl.addEventListener('change', function() {
                    const detailsDiv = document.getElementById('package_details');
                    if (this.value) {
                        const selected = this.options[this.selectedIndex];
                        const rate = selected.dataset.rate ? parseFloat(selected.dataset.rate) : 0;
                        document.getElementById('pkg_rate').textContent = selected.dataset.rate ?? '0';
                        document.getElementById('pkg_gst').textContent = selected.dataset.gst || '0';
                        document.getElementById('pkg_desc').textContent = selected.dataset.desc || '-';
                        detailsDiv.style.display = 'block';
                        if (packageAmountInputEl) packageAmountInputEl.value = rate.toFixed(2);
                    } else {
                        detailsDiv.style.display = 'none';
                        if (packageAmountInputEl) packageAmountInputEl.value = '';
                    }
                    toggleApplyApprovalField();
                });
            }

            // Handle form submission
            if (applyPackageForm) {
                applyPackageForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const packageId = document.getElementById('package_select').value;
                    const appliedDate = document.getElementById('applied_date').value;
                    const notes = document.getElementById('notes').value;
                    const packageAmountEl = document.getElementById('package_amount_input');
                    const packageRate = packageAmountEl && packageAmountEl.value ? parseFloat(
                        packageAmountEl.value) : null;
                    const approvalEl = document.getElementById('approval_percentage_input');
                    const payload = {
                        package_id: packageId,
                        applied_date: appliedDate,
                        notes: notes,
                        package_rate: packageRate
                    };
                    if (approvalEl && approvalWrapEl && approvalWrapEl.style.display !== 'none' && approvalEl.value.trim() !== '') {
                        payload.approval_percentage = parseFloat(approvalEl.value);
                    }

                    if (!packageId) {
                        alert('Please select a package');
                        return;
                    }

                    fetch("{{ url('ipd/' . $ipd->id . '/apply-package') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Package applied successfully!');
                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'apply_package_modal'));
                                modal.hide();
                                applyPackageForm.reset();
                                loadAppliedPackages();
                                loadAvailablePackages();
                            } else {
                                alert('Error: ' + (data.message || 'Failed to apply package'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error applying package: ' + error.message);
                        });
                });
            }

            function openEditAppliedPackageModal(ipdPackageId) {
                const pkg = appliedPackagesCache.find(p => String(p.id) === String(ipdPackageId));
                if (!pkg) return;

                document.getElementById('edit_ipd_package_id').value = pkg.id;
                document.getElementById('edit_package_name').value = pkg.package?.name || '';
                document.getElementById('edit_package_rate').value = parseFloat(pkg.package_rate).toFixed(2);
                document.getElementById('edit_approval_percentage').value =
                    pkg.approval_percentage != null && pkg.approval_percentage !== '' ?
                    parseFloat(pkg.approval_percentage) : '';
                document.getElementById('edit_applied_date').value = toDateInputValue(pkg.applied_date);
                document.getElementById('edit_package_note').value = pkg.note || '';
                document.getElementById('edit_final_amount_preview').textContent = formatInr(pkg.final_amount);

                if (editAppliedPackageModal) {
                    bootstrap.Modal.getOrCreateInstance(editAppliedPackageModal).show();
                }
            }

            if (editAppliedPackageForm) {
                editAppliedPackageForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const ipdPackageId = document.getElementById('edit_ipd_package_id').value;
                    const approvalRaw = document.getElementById('edit_approval_percentage').value.trim();
                    const payload = {
                        package_rate: parseFloat(document.getElementById('edit_package_rate').value),
                        approval_percentage: approvalRaw === '' ? null : parseFloat(approvalRaw),
                        applied_date: document.getElementById('edit_applied_date').value,
                        note: document.getElementById('edit_package_note').value
                    };

                    updatePackageBilling(ipdPackageId, payload, true);
                });
            }

            function removePackage(ipdPackageId) {
                fetch("{{ url('ipd/' . $ipd->id . '/remove-package') }}", {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            ipd_package_id: ipdPackageId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Package removed successfully!');
                            loadAppliedPackages();
                            loadAvailablePackages();
                        } else {
                            alert('Error: ' + (data.message || 'Failed to remove package'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error removing package: ' + error.message);
                    });
            }

            function updatePackageBilling(ipdPackageId, fields, closeEditModal = false) {
                const url = "{{ url('ipd/' . $ipd->id . '/packages') }}/" + ipdPackageId;
                fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(fields)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (closeEditModal && editAppliedPackageModal) {
                                const modal = bootstrap.Modal.getInstance(editAppliedPackageModal);
                                if (modal) modal.hide();
                            }
                            loadAppliedPackages();
                        } else {
                            alert('Error: ' + (data.message || 'Failed to update package'));
                            loadAppliedPackages();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating package: ' + error.message);
                    });
            }
        });
    </script>

@endsection
