{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
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

        .patient-info-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header Section */
        .patient-header {
            background: linear-gradient(-90deg, #75009673 0%, #cb6ce673 100%);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .patient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .patient-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        /* Patient Avatar */
        .patient-avatar-wrapper {
            position: relative;
        }

        .patient-avatar {
            width: 140px;
            height: 140px;
            border-radius: 16px;
            overflow: hidden;
            border: 4px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            background: white;
        }

        .patient-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-badge {
            position: absolute;
            bottom: -8px;
            right: -8px;
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        /* Patient Name Section */
        .patient-name-section {
            flex: 1;
            color: white;
        }

        .patient-id {
            font-size: 0.875rem;
            opacity: 0.9;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .patient-name {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .patient-quick-info {
            display: grid;
            grid-template-columns: 2fr 2fr;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .quick-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            opacity: 0.95;
        }

        .quick-info-item i {
            font-size: 1rem;
        }

        /* Body Section */
        .patient-body {
            padding: 1.5rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        /* Info Item */
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .info-item:hover {
            background: white;
            border-color: var(--primary-light);
            box-shadow: 0 4px 12px rgba(233, 30, 99, 0.08);
            transform: translateY(-2px);
        }

        /* Icon Container */
        .info-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(203, 108, 230, 0.25);
        }

        .info-icon i {
            color: white;
            font-size: 1.1rem;
        }

        /* Info Content */
        .info-content {
            flex: 1;
            min-width: 0;
        }

        .info-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            word-break: break-word;
        }

        .info-value.empty {
            color: var(--text-muted);
            font-style: italic;
            font-weight: 400;
        }

        /* Section Divider */
        .section-divider {
            display: flex;
            align-items: center;
            margin: 2rem 0 1.5rem;
            gap: 1rem;
        }

        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--border-color), transparent);
        }

        .section-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .header-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .patient-quick-info {
                flex-direction: column;
                gap: 0.75rem;
                align-items: center;
            }

            .patient-avatar {
                width: 120px;
                height: 120px;
            }

            .patient-name {
                font-size: 1.5rem;
            }

            .patient-body {
                padding: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
                padding: 1rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .info-item {
            animation: fadeInUp 0.4s ease-out;
        }

        .info-item:nth-child(1) {
            animation-delay: 0.05s;
        }

        .info-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .info-item:nth-child(3) {
            animation-delay: 0.15s;
        }

        .info-item:nth-child(4) {
            animation-delay: 0.2s;
        }

        .info-item:nth-child(5) {
            animation-delay: 0.25s;
        }

        .info-item:nth-child(6) {
            animation-delay: 0.3s;
        }
    </style>

    <div class="p-4">
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: "{{ session('alertTitle') ?? 'Success' }}",
                    text: "{{ session('success') }}",
                });
            </script>
        @endif
        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: "{{ session('alertTitle') ?? 'Error' }}",
                    text: "{{ session('error') }}",
                });
            </script>
        @endif

        <!-- tab start -->
        <ul class="nav nav-tabs nav-bordered mb-3 flex-nowrap">
            <li class="nav-item">
                <a href="#overview" data-bs-toggle="tab" aria-expanded="false"
                    class="d-flex align-items-center justify-space-between px-2 nav-link active bg-transparent"><i
                        class="fa-solid fa-expand text-primary pe-1"></i>
                    <span>Overview</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#visits" data-bs-toggle="tab" aria-expanded="true"
                    class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                        class="fa-regular fa-square-caret-down text-primary pe-1"></i>
                    <span>Visits</span>
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
                <a href="#vitals" data-bs-toggle="tab" aria-expanded="true"
                    class="d-flex align-items-center justify-space-between px-2 nav-link bg-transparent"><i
                        class="fa-solid fa-heart-pulse text-primary pe-1"></i>
                    <span>Vitals</span>
                </a>
            </li>
        </ul>
        <!-- tab end -->

        <!-- tab content start -->
        <div class="tab-content">
            <div class="tab-pane show active" id="overview">

                <div class="row">
                    <div class="col-md-6">

                        <div class="card shadow-sm border-0 mt-2" style="border-radius: 16px;">
                            {{-- <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Virat Kohli (13)
                                </h5>
                            </div> --}}
                            {{-- <div class="card-body"> --}}
                            <div class="patient-info-card">
                                <!-- Header Section -->
                                <div class="patient-header">
                                    <div class="header-content">
                                        <div class="patient-avatar-wrapper">
                                            <div class="patient-avatar">
                                                <img src="{{ asset('assets/img/patient.png') }}" alt="Patient Photo">
                                            </div>
                                            <div class="avatar-badge">
                                                <i class="fas fa-user-check"></i>
                                            </div>
                                        </div>

                                        <div class="patient-name-section">
                                            <div class="patient-id">
                                                <i class="fas fa-id-card me-1"></i> OPD ID: {{ $opd->opd_no }}
                                            </div>
                                            <h2 class="patient-name">{{ $opd->patient->patient_name }}</h2>
                                            <div class="patient-quick-info">
                                                <div class="quick-info-item">
                                                    <i class="fas fa-mars"></i>
                                                    <span>{{ $opd->patient->gender }}</span>
                                                </div>
                                                <div class="quick-info-item">
                                                    <i class="fas fa-birthday-cake"></i>
                                                    <span>{{ \Carbon\Carbon::parse($opd->patient->dob)->format('d-M-Y') }}</span>
                                                </div>
                                                <div class="quick-info-item">
                                                    <i class="fas fa-droplet"></i>
                                                    <span>{{ $opd->patient->bloodGroup->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Body Section -->
                                <div class="patient-body">
                                    <!-- Contact Information -->
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-phone text-primary"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">Phone</div>
                                                <div class="info-value">{{ $opd->patient->mobileno ?? '--' }}</div>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-calendar-days text-primary"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">Age</div>
                                                <div class="info-value">{{ $opd->patient->age }} Year
                                                    {{ $opd->patient->month }} Month {{ $opd->patient->day }} Days (As
                                                    Of
                                                    {{ \Carbon\Carbon::parse($opd->patient->as_of_date)->format('d/m/Y') }})
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-hands-holding-child text-primary"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">Guardian Name</div>
                                                <div class="info-value empty">{{ $opd->patient->guardian_name ?? '--' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-mars-and-venus text-primary"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">Gender</div>
                                                <div class="info-value">{{ $opd->patient->gender }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Insurance & Medical Information -->
                                    <div class="section-divider">
                                        <span class="section-title">Insurance & Medical Information</span>
                                    </div>

                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-users-gear text-primary"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">TPA</div>
                                                <div class="info-value empty">
                                                    {{ $opd->patient->organisation->organisation_name ?? '--' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-id-badge text-primary"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">TPA ID</div>
                                                <div class="info-value empty">
                                                    {{ $opd->patient->organisation->code ?? '--' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-user-check text-primary"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">TPA Validity</div>
                                                <div class="info-value empty">{{ $opd->patient->tpa_validity ?? '--' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-barcode text-primary"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">Barcode</div>
                                                <div class="info-value empty">--</div>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon">
                                                <i class="fa-solid fa-qrcode text-primary"></i>
                                            </div>
                                            <div class="info-content">
                                                <div class="info-label">QR Code</div>
                                                <div class="info-value empty">--</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex align-items-center mb-3 px-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="fa-solid fa-tag text-primary"></i></span>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="about_patient fs-13 fw-bold mb-1"> Known Allergies :</h6>
                                        <p class="patient_data mb-0">{{ $opd->allergies ?? '--' }}</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3 px-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="fa-solid fa-tag text-primary"></i></span>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="about_patient fs-13 fw-bold mb-1"> Findings :</h6>
                                        <p class="patient_data mb-0">--</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3 px-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="fa-solid fa-tag text-primary"></i></span>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class=" fs-13 fw-bold mb-1"> Symptoms :</h6>
                                        <p class=" mb-0">
                                        <ul class="m-0">
                                            @foreach ($symptoms as $symptom)
                                                <li><i class="fa-regular fa-circle-check text-primary"></i>
                                                    {{ $symptom->symptoms_title }}</li>
                                            @endforeach
                                        </ul>
                                        </p>
                                    </div>
                                </div>

                            </div>
                            {{-- <div class="d-sm-flex position-relative z-0 overflow-hidden p-2">
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
                                </div> --}}

                            {{--
                            </div> --}}
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
                                                <img src="{{ asset('assets/img/patient.png') }}" alt="product"
                                                    class="rounded">
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fs-13 fw-bold mb-1">
                                                    {{ $opd->doctor->name . '(' . $opd->doctor->doctor_id . ')' ?? '--' }}
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
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> OPD Payment /
                                    Billing
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary">OPD Payment/Billing</h6>
                                        <span>{{ round(($opd->paid_amount / $opd->amount) * 100, 2) }}%</span>
                                        <div class="progress mb-3 mt-1" role="progressbar" aria-valuenow="68.18"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar bg-gradient"
                                                style="width: {{ ($opd->paid_amount / $opd->amount) * 100 }}%;"></div>
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
                                        <h6 class="text-primary">Blood Bank Payment/Billing</h6>
                                        <span>0%</span>
                                        <div class="progress mb-3 mt-1" role="progressbar" aria-valuenow="0"
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
                                                        {{ $lab->pathology->test_name .
                                                            "
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            (" .
                                                            $lab->pathology->short_name .
                                                            ')' }}
                                                    </td>
                                                    <td>Pathology</td>
                                                    <td>{{ '--' }}</td>
                                                    <td>{{ \Carbon\Carbon::today()->copy()->addDays(intval($lab->pathology->report_days))->format('d-M-Y') }}
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
                                                    <td>{{ $operation->reference_no }}</td>
                                                    <td>{{ $operation->date }}</td>
                                                    <td>{{ $operation->operation->operation }}</td>
                                                    <td>{{ $operation->operation->category->category }}</td>
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
                                                <th>Tax</th>
                                                <th>Applied Charge (INR)</th>
                                                <th>Amount (INR)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($opdCharges as $charge)
                                                @php
                                                    $taxAmount =
                                                        ($charge->charge->standard_charge *
                                                            $charge->charge->taxCategory->percentage) /
                                                        100;
                                                    $amount = $charge->charge->standard_charge + $taxAmount;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $charge->charge->name }}
                                                    </td>
                                                    <td style="text-transform: capitalize;">
                                                        {{ $charge->chargeCategory->chargeType->charge_type }}
                                                    </td>
                                                    <td class="text-right">{{ $charge->charge->standard_charge }}</td>
                                                    <td class="text-right">
                                                        ({{ $charge->charge->taxCategory->percentage }}%)
                                                        {{ $taxAmount }}
                                                    </td>
                                                    <td class="text-right">{{ $charge->charge->standard_charge }}</td>
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
            <div class="tab-pane" id="visits">


                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Checkups</h5>
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
                                                                data-bs-toggle="modal" data-is-hidden="true"
                                                                data-bs-target="#createOpdModal"
                                                                data-patient='@json($opd->patient)'><i
                                                                    class="ti ti-plus me-1"></i>New Checkup</a>
                                                        </div>
                                                        <!-- First Modal -->
                                                        @include('components.modals.opd-create-modal')
                                                        {{-- <div class="modal fade" id="add_appointment" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div
                                                                class="modal-dialog modal-dialog-centered modal-full-width">
                                                                <div class="modal-content">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                        <h5 class="modal-title" id="addSpecializationLabel">
                                                                            Patient Details
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>

                                                                    </div>

                                                                    <div class="modal-body">

                                                                        <div class="row gy-3">

                                                                            <div class="col-md-8 border-end">
                                                                                <div class="row gy-3">
                                                                                    <div class="col-md-10">
                                                                                        <h3>
                                                                                            Animesh Das (39)
                                                                                        </h3>
                                                                                        <p>Guardian : --</p>
                                                                                        <p>Gender : Male</p>
                                                                                        <p>Blood Group : A+</p>
                                                                                        <p>Marital Status: Single</p>
                                                                                        <p>Age: 41 Year 2 Month 27 Days (As
                                                                                            Of Date 10/14/2025)</p>
                                                                                        <p>Phone: --</p>
                                                                                        <p>Email:
                                                                                        </p>
                                                                                        <p>Address: --</p>
                                                                                        <p>Any Known Allergies: --</p>
                                                                                        <p>Remarks: --</p>
                                                                                        <p>TPA : --
                                                                                        </p>
                                                                                        <p>TPA ID : --</p>
                                                                                        <p>TPA Validity : --</p>
                                                                                        <p>National Identification
                                                                                            Number : --</p>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <img src="assets/img/patient.png"
                                                                                            alt="product"
                                                                                            class="rounded thumbnail">
                                                                                    </div>
                                                                                    <hr>
                                                                                    <div class="col-md-3">
                                                                                        <label for="symptoms_type"
                                                                                            class="form-label">Symptoms
                                                                                            Type</label>
                                                                                        <select class="form-select"
                                                                                            id="symptoms_type">
                                                                                            <option value="">Select
                                                                                            </option>
                                                                                            <option value="">General
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label for="symptoms_title"
                                                                                            class="form-label">Symptoms
                                                                                            Title </label>
                                                                                        <input type="text"
                                                                                            name="symptoms_title"
                                                                                            id="symptoms_title"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="symptoms_des"
                                                                                            class="form-label">Symptoms
                                                                                        </label>
                                                                                        <textarea name="symptoms"
                                                                                            id="symptoms"
                                                                                            class="form-control"></textarea>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="note"
                                                                                            class="form-label">Note</label>
                                                                                        <textarea name="note" id="note"
                                                                                            class="form-control"></textarea>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="allergies"
                                                                                            class="form-label">Any Known
                                                                                            Allergies</label>
                                                                                        <textarea name="allergies"
                                                                                            id="allergies"
                                                                                            class="form-control"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="row gy-3">

                                                                                    <div class="col-md-6">
                                                                                        <label for="appointment_date"
                                                                                            class="form-label">Appointment
                                                                                            Date <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="date"
                                                                                            name="appointment_date"
                                                                                            id="appointment_date"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="case"
                                                                                            class="form-label">Case</label>
                                                                                        <input type="text" name="case"
                                                                                            id="case" class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="casualty"
                                                                                            class="form-label">Casualty</label>
                                                                                        <select class="form-select"
                                                                                            id="casualty">
                                                                                            <option value="">Select
                                                                                            </option>
                                                                                            <option value="">No
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="old_patient"
                                                                                            class="form-label">Old
                                                                                            Patient</label>
                                                                                        <select class="form-select"
                                                                                            id="old_patient">
                                                                                            <option value="">Select
                                                                                            </option>
                                                                                            <option value="">No
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="reference"
                                                                                            class="form-label">Reference</label>
                                                                                        <input type="text" name="reference"
                                                                                            id="reference"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="doctor"
                                                                                            class="form-label">Consultant
                                                                                            Doctor
                                                                                            <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <select class="form-select"
                                                                                            id="doctor"
                                                                                            data-placeholder="Select">
                                                                                            <option value="">
                                                                                            </option>
                                                                                            <option value="1">Amitabh
                                                                                                Kulkarni</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div
                                                                                            class="form-check d-flex gap-2 mt-4">
                                                                                            <input type="checkbox"
                                                                                                name="tpa_check"
                                                                                                id="tpa_check"
                                                                                                class="form-check-input">
                                                                                            <label for="tpa_check"
                                                                                                class="form-check-label">Apply
                                                                                                TPA</label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="charge_category"
                                                                                            class="form-label">Charge
                                                                                            Category
                                                                                        </label>
                                                                                        <select class="form-select"
                                                                                            id="charge_category"
                                                                                            data-placeholder="Select">
                                                                                            <option value="0">
                                                                                            </option>
                                                                                            <option value="1">OPD
                                                                                                Doctor
                                                                                                Fees</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="charge"
                                                                                            class="form-label">Charge
                                                                                            <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <select class="form-select"
                                                                                            id="charge"
                                                                                            data-placeholder="Select">
                                                                                            <option value="">
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="standard_charge"
                                                                                            class="form-label">Standard
                                                                                            Charge (INR)</label>
                                                                                        <input type="text" readonly="true"
                                                                                            name="standard_charge"
                                                                                            id="standard_charge"
                                                                                            class="form-control" value=""
                                                                                            autocomplete="off" disabled>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="apply_charge"
                                                                                            class="form-label">Applied
                                                                                            Charge (INR) <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text" name="amount"
                                                                                            id="apply_charge"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="discount_percentage"
                                                                                            class="form-label">Discount</label>
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                class="form-control discount_percentage"
                                                                                                name="discount_percentage"
                                                                                                id="discount_percentage"
                                                                                                value="0"
                                                                                                autocomplete="off">
                                                                                            <span
                                                                                                class="input-group-addon ">
                                                                                                %</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="tax"
                                                                                            class="form-label">Tax</label>
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                class="form-control discount_percentage"
                                                                                                name="tax" id="tax"
                                                                                                value="0" autocomplete="off"
                                                                                                readonly="true" disabled>
                                                                                            <span
                                                                                                class="input-group-addon ">
                                                                                                %</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="amount"
                                                                                            class="form-label">Amount
                                                                                            (INR) <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text" readonly="true"
                                                                                            name="amount" id="amount"
                                                                                            class="form-control" value=""
                                                                                            autocomplete="off" disabled>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="payment_mode"
                                                                                            class="form-label">Payment
                                                                                            Mode</label>
                                                                                        <select class="form-select"
                                                                                            id="payment_mode">
                                                                                            <option value="">Select
                                                                                            </option>
                                                                                            <option value="1">Case
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="paid_amount"
                                                                                            class="form-label">Paid
                                                                                            Amount
                                                                                            (INR) <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text"
                                                                                            name="paid_amount"
                                                                                            id="paid_amount"
                                                                                            class="form-control" value=""
                                                                                            autocomplete="off">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="live_con"
                                                                                            class="form-label">Live
                                                                                            Consultation</label>
                                                                                        <select class="form-select"
                                                                                            id="live_con">
                                                                                            <option value="">Select
                                                                                            </option>
                                                                                            <option value="1">No
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>

                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Save &
                                                                            Print</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}

                                                        <!-- Second Modal (nested) -->
                                                        {{-- <div class="modal fade" id="new_patient" tabindex="-1"
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
                                                                                        <input id="name" name="name"
                                                                                            placeholder="" type="text"
                                                                                            class="form-control" value=""
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
                                                                                                </label><small class="req">
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
                                                                                                    <option value="Married">
                                                                                                        Married</option>
                                                                                                    <option value="Widowed">
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
                                                                                                        <input type="file"
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
                                                                                            class="form-control" value="">
                                                                                        <span class="text-danger"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="form-label">Email</label>
                                                                                        <input type="text" placeholder=""
                                                                                            id="addformemail" value=""
                                                                                            name="email"
                                                                                            class="form-control">
                                                                                        <span class="text-danger"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="address"
                                                                                            class="form-label">Address</label>
                                                                                        <input name="address" placeholder=""
                                                                                            class="form-control">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="pwd"
                                                                                            class="form-label">Remarks</label>
                                                                                        <textarea name="note" id="note"
                                                                                            class="form-control"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label for="email"
                                                                                            class="form-label">Any Known
                                                                                            Allergies</label>
                                                                                        <textarea name="known_allergies"
                                                                                            id="" placeholder=""
                                                                                            class="form-control"></textarea>
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
                                                                                        <input type="text" id="height"
                                                                                            name="height"
                                                                                            class="form-control"
                                                                                            placeholder="Height (cm)"
                                                                                            value="">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label for="weight"
                                                                                            class="form-label">Weight</label>
                                                                                        <input type="text" id="weight"
                                                                                            name="weight"
                                                                                            class="form-control"
                                                                                            placeholder="Weight (kg)"
                                                                                            value="">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <div class="form-group">
                                                                                        <label for="temperature"
                                                                                            class="form-label">Temperature</label>
                                                                                        <input type="text" id="temperature"
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
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>OPD Checkup ID</th>
                                                                <th>Appointment Date</th>
                                                                <th>Consultant</th>
                                                                <th>Reference</th>
                                                                <th>Symptoms</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($opdVisits as $visit)
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">{{ $visit->visit_id }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>{{ $visit->opd->appointment_date }}</td>
                                                                    <td>{{ $visit->opd->doctor->name }}
                                                                        ({{ $visit->opd->doctor->doctor_id }})
                                                                    </td>
                                                                    <td>{{ $visit->opd->reference }}</td>
                                                                    <td>
                                                                        @if (isset($opdSymptoms[$visit->opd->opd_no]) && count($opdSymptoms[$visit->opd->opd_no]) > 0)
                                                                            @foreach ($opdSymptoms[$visit->opd->opd_no] as $symptom)
                                                                                <span
                                                                                    class="badge bg-primary me-1">{{ $symptom->symptoms_title }}</span>
                                                                            @endforeach
                                                                        @else
                                                                            --
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex gap-2">

                                                                            <a href="javascript: void(0);"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#addPrescriptionModal"
                                                                                data-id="{{ $opd->id }}">
                                                                                <i class="fa-solid fa-prescription"
                                                                                    {{--
                                                                                    data-bs-toggle="tooltip" --}}
                                                                                    title="Add Prescription"></i></a>


                                                                            <a href="javascript: void(0);"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-primary rounded-pill"
                                                                                data-is-ipd="false"
                                                                                data-id="{{ $visit->opd->id }}"
                                                                                data-pres-id = "{{ $visit->id }}"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#showPrescriptionModal">
                                                                                <i class="fa-solid fa-print"
                                                                                    title="Manual Prescription"></i></a>



                                                                            <a href="javascript: void(0);"
                                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                                <i class="ti ti-menu"
                                                                                    data-bs-toggle="tooltip"
                                                                                    title="Show"></i></a>



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
                                                                        <form action="{{ route('opd.createMedication') }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <div class="row gy-3">
                                                                                <input type="hidden" name="opd_id"
                                                                                    value="{{ $opd->id }}">
                                                                                <div class="col-md-6">
                                                                                    <label for="date"
                                                                                        class="form-label">Date
                                                                                        <span class="text-danger">*</span>
                                                                                    </label>
                                                                                    <input type="date" name="date"
                                                                                        id="date"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="time"
                                                                                        class="form-label">Time
                                                                                        <span class="text-danger">*</span>
                                                                                    </label>
                                                                                    <input type="time" name="time"
                                                                                        id="time"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="medi_cat"
                                                                                        class="form-label">Medicine
                                                                                        Category
                                                                                        <span class="text-danger">*</span>
                                                                                    </label>
                                                                                    <select name="med_cat" id="med_cat"
                                                                                        class="form-select"
                                                                                        data-placeholder="Enter Patient Name or Id…">
                                                                                        <option value="0">Select
                                                                                        </option>
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
                                                                                        <option value="0">Select
                                                                                        </option>
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
                                                                                        <option value="0">Select
                                                                                        </option>
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
                                                            @foreach ($medicationReport as $medication)
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="fs-14 mb-1">
                                                                            {{ \Carbon\Carbon::parse($medication->date)->format('d-M-Y') }}&nbsp;{{ '(' . \Carbon\Carbon::parse($medication->date)->format('D') . ')' }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>{{ $medication->pharmacy->medicine_name }}</td>
                                                                    <td> Time:
                                                                        {{ \Carbon\Carbon::parse($medication->time)->format('h:i A') }}
                                                                        {{ $medication->medicineDosage->dosage . $medication->medicineDosage->unit->unit_name }}<br>
                                                                        Created By:
                                                                        {{ $medication->generatedBy->userRole->name }}
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
                                                                        <h6 class="fs-14 mb-1">
                                                                            {{ $lab->pathology->test_name . '(' . $lab->pathology->short_name . ')' }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>Pathology</td>
                                                                    <td>Pathology Center :{{ '--' }}</td>
                                                                    <td>{{ \Carbon\Carbon::today()->copy()->addDays(intval($lab->pathology->report_days))->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>{{ $lab->approved_by ?? '--' }}</td>
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
                                                                    <form action="{{ route('opd.addOpdCharge') }}"
                                                                        method="POST" id="addChargeForm">
                                                                        @csrf
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
                                                                                                <input type="hidden" name="opd_id" id="opd_id" value="{{ $opd->id }}">
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
                                                                                <div class="col-lg-12 col-md-12 col-sm-12">
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
                                                            {{-- @php
                                                            $taxAmount =
                                                            ($charge->charge->standard_charge *
                                                            $charge->charge->taxCategory->percentage) /
                                                            100;
                                                            $amount = $charge->charge->standard_charge + $taxAmount;
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    {{ $charge->charge->name }}
                                                                </td>
                                                                <td style="text-transform: capitalize;">
                                                                    {{ $charge->chargeCategory->chargeType->charge_type }}
                                                                </td>
                                                                <td class="text-right">{{ $charge->charge->standard_charge
                                                                    }}</td>
                                                                <td class="text-right">
                                                                    ({{ $charge->charge->taxCategory->percentage }}%)
                                                                    {{ $taxAmount }}
                                                                </td>
                                                                <td class="text-right">{{ $charge->charge->standard_charge
                                                                    }}</td>
                                                                <td class="text-right">{{ $amount }}</td>
                                                            </tr> --}}
                                                            @foreach ($opdCharges as $charge)
                                                                @php
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
                                                                @endphp
                                                                <tr>
                                                                    <td>
                                                                        {{ \Carbon\Carbon::parse($charge->created_at)->format('d-M-Y') }}
                                                                    </td>
                                                                    <td>{{ $charge->charge->name }}</td>
                                                                    <td style="text-transform: capitalize;">
                                                                        {{ $charge->chargeCategory->chargeType->charge_type }}
                                                                    </td>
                                                                    <td>{{ $charge->chargeCategory->name }} </td>
                                                                    <td>1</td>
                                                                    <td>{{ $charge->charge->standard_charge }}</td>
                                                                    <td>{{ $charge->charge->standard_charge }}</td>
                                                                    <td>0.00</td>
                                                                    <td>{{ $discountAmount }}&nbsp;({{ $charge->discount }}%)
                                                                    </td>
                                                                    <td>{{ $taxAmount }}&nbsp;({{ $charge->charge->taxCategory->percentage }}%)
                                                                    </td>
                                                                    <td>{{ $amount }}</td>
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
                                                                                    id="payment_mode" class="form-select">
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
                                                                    TRID95
                                                                </td>
                                                                <td>10/14/2025 11:45 AM </td>
                                                                <td>SmartPay Transaction ID: 528706160448
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
                                                                    <form action="{{ route('patient-timeline.store') }}"
                                                                        method="post">
                                                                        @csrf

                                                                        <div class="modal-body">

                                                                            <div class="row gy-3">

                                                                                <div class="col-md-12">
                                                                                    <label for="title"
                                                                                        class="form-label">Title
                                                                                        <span class="text-danger">*</span>
                                                                                    </label>
                                                                                    <input type="hidden"
                                                                                        name="patient_id"
                                                                                        value="{{ $opd->patient->id }}">
                                                                                    <input type="text" name="title"
                                                                                        id="title"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label for="date"
                                                                                        class="form-label">Date
                                                                                        <span class="text-danger">*</span>
                                                                                    </label>
                                                                                    <input type="date" name="date"
                                                                                        id="date"
                                                                                        class="form-control">
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
                                                                                    <input type="file"
                                                                                        name="attch_doc" id="file"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label for="visible_person"
                                                                                        class="form-check-label">Visible
                                                                                        to
                                                                                        this
                                                                                        person
                                                                                    </label>
                                                                                    <input type="checkbox"
                                                                                        name="visible_person"
                                                                                        id="date"
                                                                                        class="form-check-input">
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
                                                                            class="fw-semibold">{{ $opd->opd_no }}</a>
                                                                    </h6>
                                                                </td>
                                                                <td>{{ '--' }}</td>
                                                                <td> {{ $opd->appointment_date }}</td>
                                                                <td>
                                                                    <ul>
                                                                        @foreach ($symptoms as $symptom)
                                                                            <li><i
                                                                                    class="fa-regular fa-circle-check text-primary"></i>
                                                                                {{ $symptom->symptoms_title }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </td>
                                                                <td>{{ $opd->doctor->name }}
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
    @include('components.modals.add-prescription-modal')
    @include('components.modals.show-prescription-modal')

    <!-- Chart JS -->
    <script src="assets/plugins/chartjs/chart.min.js"></script>
    <script src="assets/plugins/chartjs/chart-data.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chargeTypeSelect = document.getElementById("add_charge_type")
            const chargeCategorySelect = document.getElementById("charge_category2")
            const chargeSelect = document.getElementById("charge_id")



            fetch("{{ route('getChargeTypes') }}").then(response => response.json())
                .then(data => {
                    window.chargeTypeData = data;
                    chargeTypeSelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.charge_type;
                        if ("{{ old('charge_type') }}" == type.id) {
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
                const baseUrl = "{{ route('getChargeCategoriesByTypeId', ['id' => 'ID']) }}";
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
                            if ("{{ old('charge_category') }}" == category.id) {
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
                const baseUrl = "{{ route('getCharges', ['id' => 'ID']) }}";
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
                            if ("{{ old('charge') }}" == charge.id) {
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

        const medCatSelect = document.getElementById('med_cat')
        const medNameSelect = document.getElementById('med_name')
        const doseSelect = document.getElementById('dosage')
        medCatSelect.innerHTML = '<option value="">Loading...</option>';
        medNameSelect.innerHTML = '<option value="">Loading...</option>';
        doseSelect.innerHTML = '<option value="">Loading...</option>';
        fetch("{{ route('getMedicineCategories') }}")
            .then(response => response.json())
            .then(data => {
                window.medicineCategories = data;
                medCatSelect.innerHTML = '<option value="">Select</option>';
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.medicine_category;
                    if ("{{ old('med_cat') }}" == category.id) {
                        option.selected = true;
                    }
                    medCatSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching charge categories:', error);
                medCatSelect.innerHTML = '<option value="">Error loading options</option>';
            });

        // Listen for Charge Category dropdown change
        medCatSelect.addEventListener('change', function() {
            const selectedId = this.value;
            const baseUrl = "{{ route('getMedicines', ['categoryId' => 'ID']) }}";
            const finalUrl = baseUrl.replace('ID', selectedId);
            fetch(finalUrl)
                .then(response => response.json())
                .then(data => {
                    window.chargeData = data;
                    medNameSelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(charge => {
                        const option = document.createElement('option');
                        option.value = charge.id;
                        option.textContent = charge.medicine_name;
                        if ("{{ old('med_name') }}" == charge.id) {
                            option.selected = true;
                        }
                        medNameSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching Charges:', error);
                    medNameSelect.innerHTML = '<option value="">Error loading options</option>';
                });
            const baseUrlDose = "{{ route('getDoses', ['categoryId' => 'ID']) }}";
            const finalUrlDose = baseUrlDose.replace('ID', selectedId);
            fetch(finalUrlDose)
                .then(response => response.json())
                .then(data => {
                    window.chargeData = data;
                    doseSelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(charge => {
                        const option = document.createElement('option');
                        option.value = charge.id;
                        option.textContent = charge.dosage + " " + charge.unit.unit_name;
                        if ("{{ old('dosage') }}" == charge.id) {
                            option.selected = true;
                        }
                        doseSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching Charges:', error);
                    doseSelect.innerHTML = '<option value="">Error loading options</option>';
                });
        })
    </script>
@endsection
