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
    </style>

    <div class="p-4">

        <!-- tab start -->
        <ul class="nav nav-tabs nav-bordered mb-3">
            <li class="nav-item">
                <a href="#overview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active bg-transparent"><i
                        class="fa-solid fa-expand text-primary"></i>
                    <span>Overview</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#visits" data-bs-toggle="tab" aria-expanded="true" class="nav-link bg-transparent"><i
                        class="fa-regular fa-square-caret-down text-primary"></i>
                    <span>Visits</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#lab_investigation" data-bs-toggle="tab" aria-expanded="true" class="nav-link bg-transparent"><i
                        class="fa-solid fa-flask text-primary"></i>
                    <span>Lab Investigation</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#treatment_history" data-bs-toggle="tab" aria-expanded="true" class="nav-link bg-transparent"><i
                        class="fa-solid fa-laptop-medical text-primary"></i>
                    <span>Treatment History</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#timeline" data-bs-toggle="tab" aria-expanded="true" class="nav-link bg-transparent"><i
                        class="fa-solid fa-timeline text-primary"></i>
                    <span>Timeline</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#vitals" data-bs-toggle="tab" aria-expanded="true" class="nav-link bg-transparent"><i
                        class="fa-solid fa-heart-pulse text-primary"></i>
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

                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> {{ $appointment->patient->patient_name }}
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
                                                <p class="patient_data mb-0">{{ $appointment->patient->mobileno }}</p>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-calendar-days text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Age :</h6>
                                                <p class="patient_data mb-0"> {{ $appointment->patient->age }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-hands-holding-child text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Guardian Name :</h6>
                                                <p class="patient_data mb-0">{{ $appointment->patient->	guardian_name }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-mars-and-venus text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="about_patient fs-13 fw-bold mb-1">Gender :</h6>
                                                <p class="patient_data mb-0">{{ $appointment->patient->gender }}</p>
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
                                                                                                                                                                                                                                       
                                </div>
                                <hr>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="fa-solid fa-tag text-primary"></i></span>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="about_patient fs-13 fw-bold mb-1"> Known Allergies :</h6>
                                        <p class="patient_data mb-0">{{ $appointment->patient->known_allergies ?? 'N/A'}}</p>
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
                                            @forelse($symptomTitles as $title)
                                                <li>
                                                    <i class="fa-regular fa-circle-check text-primary"></i> {{ $title }}
                                                </li>
                                            @empty
                                                <li><i class="fa-regular fa-circle-xmark text-danger"></i> No symptoms recorded</li>
                                            @endforelse
                                        </ul>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Consultant Doctor
                                </h5>
                            </div>
                            <div class="card-body">

                               
                                <hr>
                                <div>
                                    <a href="#">
                                        <div class="d-flex align-items-center mb-3 gap-2">
                                            <div class="patient_img">
                                                <img src="assets/img/patient.png" alt="product" class="rounded">
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fs-13 fw-bold mb-1">{{ $appointment->doctorUser->name ?? 'N/A' }}</h6>
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
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Medical History
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="card card-h-100">
                                    <div class="card-header">
                                        <div class="card-title">Line Chart</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chartjs-wrapper-demo">
                                            <canvas id="AppointchartLine1" class="h-300"></canvas>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-2">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Visit Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>OPD No</th>
                                                <th>Patient Name</th>
                                                <th>Appointment Date</th>
                                                <th>Consultant</th>
                                                <th>Reference</th>
                                                <th>Symptoms</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             @forelse($visitDetails as $opd)
                                                <tr>
                                                    <td>
                                                        <h6 class="fs-14 mb-1">
                                                            <a href="#" class="fw-semibold">{{ $opd->opd_details_id ?? '-' }}</a>
                                                        </h6>
                                                    </td>
                                                    <td>{{ $opd->opdDetail->patient->patient_name ?? '-' }}</td>
                                                    <td>
                                                        @if($opd->appointment_date)
                                                            {{ \Carbon\Carbon::parse($opd->appointment_date)->format('d/m/Y h:i A') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $opd->doctor->name ?? $opd->cons_doctor ?? '-' }}
                                                        <!-- @if(!empty($opd->doctor->employee_id))
                                                            ({{ $opd->doctor->employee_id }})
                                                        @endif -->
                                                    </td>
                                                    <td>{{ $opd->reference ?? '-' }}</td>
                                                    <td>{{ $opd->symptom_titles ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No OPD records found</td>
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
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Lab Investigation
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Test Name</th>
                                                <th>Case ID</th>
                                                <th>Lab</th>
                                                <th>Sample Collected</th>
                                                <th>Expected Date</th>
                                                <th>Approved By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($labInvestigations as $investigation)
                                                <tr>
                                                    <td>{{ $investigation->pathology->test_name ?? '-' }}</td>
                                                    <td>{{ $investigation->case_id ?? '-' }}</td>
                                                    <td>{{ $investigation->lab_name ?? 'Pathology' }}</td>
                                                    <td>
                                                        @if(!empty($investigation->collection_date))
                                                            {{ \Carbon\Carbon::parse($investigation->collection_date)->format('d/m/Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($investigation->reporting_date))
                                                            {{ \Carbon\Carbon::parse($investigation->reporting_date)->format('d/m/Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $investigation->approved_by ?? 'Admin' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">No lab investigations found</td>
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
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Treatment History
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Table start -->
                                <div class="table-responsive table-nowrap">
                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>OPD No</th>
                                                <th>Case ID</th>
                                                <th>Appointment Date</th>
                                                <th>Consultant</th>
                                                <th>Reference</th>
                                                <th>Symptoms</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($opdDetails as $opd)
                                                <tr>
                                                    <td>
                                                        <h6 class="fs-14 mb-1">
                                                            <a href="#" class="fw-semibold">{{ $opd->opd_no ?? '-' }}</a>
                                                        </h6>
                                                    </td>
                                                    <td>{{ $opd->case_id ?? '-' }}</td>
                                                    <td>
                                                        @if($opd->appointment_date)
                                                            {{ \Carbon\Carbon::parse($opd->appointment_date)->format('d/m/Y h:i A') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $opd->doctor->name ?? $opd->consultant_name ?? '-' }}
                                                        @if(!empty($opd->doctor->employee_id))
                                                            ({{ $opd->doctor->employee_id }})
                                                        @endif
                                                    </td>
                                                    <td>{{ $opd->reference ?? '-' }}</td>
                                                    <td>{{ $opd->symptom_titles ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No OPD records found</td>
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
            <div class="tab-pane" id="visits">


                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Visits</h5>
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
                                                                data-bs-toggle="modal" data-bs-target="#add_appointment"><i
                                                                    class="ti ti-plus me-1"></i>New Visit</a>
                                                        </div>
                                                        <!-- First Modal -->
                                                        <div class="modal fade" id="add_appointment" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div
                                                                class="modal-dialog modal-dialog-centered modal-full-width">
                                                                <div class="modal-content">

                                                                    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

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
                                                                                            {{ $appointment->patient->patient_name }}
                                                                                        </h3>
                                                                                        <p>Guardian : {{ $appointment->patient->guardian_name }}</p>
                                                                                        <p>Gender : {{ $appointment->patient->gender }}</p>
                                                                                        <p>Blood Group : {{ $appointment->patient->blood_group }}</p>
                                                                                        <p>Marital Status: {{ $appointment->patient->marital_status }}</p>
                                                                                        <p>Age: {{ $appointment->patient->age }}</p>
                                                                                        <p>Phone: {{ $appointment->patient->mobileno }}</p>
                                                                                        <p>Email: {{$appointment->patient->email }}
                                                                                        </p>
                                                                                        <p>Address: {{$appointment->patient->address }}</p>
                                                                                        <p>Any Known Allergies: {{$appointment->patient->known_allergies}}</p>
                                                                                        <p>Remarks: {{$appointment->patient->note_remark}}</p>
                                                                                        <p>TPA : {{$appointment->patient->note_remark}}</p>
                                                                                        <p>TPA ID : {{$appointment->patient->note_remark}} </p>
                                                                                        <p>TPA Validity : {{$appointment->patient->note_remark}}</p>
                                                                                        <p>National Identification Number :  {{$appointment->patient->note_remark}}</p>
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
                                                                                            Description</label>
                                                                                        <textarea name="symptoms_des"
                                                                                            id="symptoms_des"
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
                                                                                            <option value="">No</option>
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
                                                                                            <option value="">No</option>
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
                                                                                            <option value=""></option>
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
                                                                                            <option value=""></option>
                                                                                            <option value="1">OPD Doctor
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
                                                                                            <option value=""></option>
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
                                                                                    <div class="col-md-6">
                                                                                        <div
                                                                                            class="form-check d-flex gap-2 mt-5">
                                                                                            <input type="checkbox"
                                                                                                name="antenatal"
                                                                                                id="antenatal"
                                                                                                class="form-check-input">
                                                                                            <label for="antenatal"
                                                                                                class="form-check-label">Is
                                                                                                Antenatal</label>
                                                                                        </div>
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
                                                                                                    <option value="1">O+
                                                                                                    </option>
                                                                                                    <option value="2">A+
                                                                                                    </option>
                                                                                                    <option value="3">B+
                                                                                                    </option>
                                                                                                    <option value="4">
                                                                                                        AB+</option>
                                                                                                    <option value="5">O-
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
                                                                                            <option value="3">Paramount
                                                                                                Health Services
                                                                                            </option>
                                                                                            <option value="2">Raksha TPA
                                                                                                Pvt. Ltd. </option>
                                                                                            <option value="1">MediAssist
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
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Table start -->
                                                <div class="table-responsive table-nowrap">
                                                    <table class="table border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>OPD No</th>
                                                <th>Patient Name</th>
                                                <th>Appointment Date</th>
                                                <th>Consultant</th>
                                                <th>Reference</th>
                                                <th>Symptoms</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             @forelse($visitDetails as $opd)
                                                <tr>
                                                    <td>
                                                        <h6 class="fs-14 mb-1">
                                                            <a href="#" class="fw-semibold">{{ $opd->opd_details_id ?? '-' }}</a>
                                                        </h6>
                                                    </td>
                                                    <td>{{ $opd->opdDetail->patient->patient_name ?? '-' }}</td>
                                                    <td>
                                                        @if($opd->appointment_date)
                                                            {{ \Carbon\Carbon::parse($opd->appointment_date)->format('d/m/Y h:i A') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $opd->doctor->name ?? $opd->cons_doctor ?? '-' }}
                                                        <!-- @if(!empty($opd->doctor->employee_id))
                                                            ({{ $opd->doctor->employee_id }})
                                                        @endif -->
                                                    </td>
                                                    <td>{{ $opd->reference ?? '-' }}</td>
                                                    <td>{{ $opd->symptom_titles ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No OPD records found</td>
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
            <div class="tab-pane" id="lab_investigation">
                <!-- row start -->
                <div class="row">
                    <div class="col-12 d-flex">
                        <div class="card shadow-sm flex-fill w-100">
                            <div class="card-header"
                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Lab Investigation
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
                                                                <th>Case ID</th>
                                                                <th>Lab</th>
                                                                <th>Sample Collected</th>
                                                                <th>Expected Date</th>
                                                                <th>Approved By</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($labInvestigations as $investigation)
                                                <tr>
                                                    <td>{{ $investigation->pathology->test_name ?? '-' }}</td>
                                                    <td>{{ $investigation->case_id ?? '-' }}</td>
                                                    <td>{{ $investigation->lab_name ?? 'Pathology' }}</td>
                                                    <td>
                                                        @if(!empty($investigation->collection_date))
                                                            {{ \Carbon\Carbon::parse($investigation->collection_date)->format('d/m/Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($investigation->reporting_date))
                                                            {{ \Carbon\Carbon::parse($investigation->reporting_date)->format('d/m/Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $investigation->approved_by ?? 'Admin' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">No lab investigations found</td>
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
                                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Treatment History
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

                                                        @forelse($opdDetails as $opd)
                                                <tr>
                                                    <td>
                                                        <h6 class="fs-14 mb-1">
                                                            <a href="#" class="fw-semibold">{{ $opd->opd_no ?? '-' }}</a>
                                                        </h6>
                                                    </td>
                                                    <td>{{ $opd->case_id ?? '-' }}</td>
                                                    <td>
                                                        @if($opd->appointment_date)
                                                            {{ \Carbon\Carbon::parse($opd->appointment_date)->format('d/m/Y h:i A') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $opd->symptom_titles ?? '-' }}</td>
                                                    <td>
                                                        {{ $opd->doctor->name ?? $opd->consultant_name ?? '-' }}
                                                        @if(!empty($opd->doctor->employee_id))
                                                            ({{ $opd->doctor->employee_id }})
                                                        @endif
                                                    </td>
                                                    
                                                    
                                                    <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a href="javascript: void(0);"
                                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                            <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                                title="Show"></i></a>
                                                                    </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No OPD records found</td>
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

                                                                        <h5 class="modal-title" id="addSpecializationLabel">
                                                                            Add Timeline
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>

                                                                    </div>
                                                                    <form method="POST" action="{{ isset($timeline) ? route('patient-timeline.update', $timeline->id) : route('patient-timeline.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($timeline))
        @method('PUT')
    @endif

    <input type="hidden" name="patient_id" value="{{ $appointment->patient_id ?? '' }}">

    <div class="modal-body">
        <div class="row gy-3">
            <!-- Title -->
            <div class="col-md-12">
                <label for="title" class="form-label">
                    Title <span class="text-danger">*</span>
                </label>
                <input type="text" name="title" id="title" class="form-control"
                    value="{{ old('title', $timeline->title ?? '') }}" required>
            </div>

            <!-- Date -->
            <div class="col-md-12">
                <label for="date" class="form-label">
                    Date <span class="text-danger">*</span>
                </label>
                <input type="date" name="date" id="date" class="form-control"
                    value="{{ old('date', isset($timeline->date) ? \Carbon\Carbon::parse($timeline->date)->format('Y-m-d') : '') }}" required>
            </div>

            <!-- Description -->
            <div class="col-md-12">
                <label for="description" class="form-label">
                    Description
                </label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $timeline->description ?? '') }}</textarea>
            </div>

            <!-- Attach Document -->
            <div class="col-md-12">
                <label for="attch_doc" class="form-label">
                    Attach Document
                </label>
                <input type="file" name="attch_doc" id="attch_doc" class="form-control">
                @if(isset($timeline) && $timeline->attch_doc)
                    <small class="text-muted d-block mt-1">
                        Current File:
                        <a href="{{ asset('storage/timeline_docs/' . $timeline->attch_doc) }}" target="_blank">
                            View Document
                        </a>
                    </small>
                @endif
            </div>

            <!-- Visible to Person -->
            <div class="col-md-12 form-check">
                <input type="checkbox" name="visible_person" id="visible_person" class="form-check-input"
                    {{ old('visible_person', $timeline->visible_person ?? false) ? 'checked' : '' }}>
                <label for="visible_person" class="form-check-label">Visible to this person</label>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
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
                            <a href="#" class="fw-semibold">{{ $timeline->patient->patient_name ?? '-' }}</a>
                        </h6>
                    </td>
                    
                    
                    <td>{{ $timeline->title ?? '-' }}</td>
                    <td>{{ $timeline->description ?? '-' }}</td>
                    <td>
                        @if(!empty($timeline->timeline_date))
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
                    <td colspan="6" class="text-center text-muted">No timeline records found</td>
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
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">

                                                                    <div class="modal-header"
                                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                        <h5 class="modal-title" id="addSpecializationLabel">
                                                                            Add Vitals
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>

                                                                    </div>
                                                                    <form method="POST" action="{{ route('patient-vitals.store') }}">
                                                                        @csrf
                                                                            <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                                                                        <div class="modal-body">
                                                                            <div id="vitalFields">
                                                                                <div class="row gy-3 vital-row mb-2">
                                                                                    <!-- Vital Name -->
                                                                                      
                                                                                    <div class="col-md-4">
                                                                                        <label for="vital_name" class="form-label">Vital Name</label>
                                                                                        <select class="form-select" name="vital_name[]" id="vital_name">
                                                                                            <option value="">Select</option>
                                                                                            @foreach($vitals as $vital)
                                                                                                <option value="{{ $vital->id }}">{{ $vital->name . ' (' . $vital->reference_range . ')' }} </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>

                                                                                    <!-- Vital Value -->
                                                                                    <div class="col-md-3">
                                                                                        <label for="vital_value" class="form-label">Vital Value</label>
                                                                                        <input type="text" name="vital_value[]" id="vital_value" class="form-control" />
                                                                                    </div>

                                                                                    <!-- Date -->
                                                                                    <div class="col-md-4">
                                                                                        <label for="date" class="form-label">Date</label>
                                                                                        <input type="date" name="date[]" id="date" class="form-control" />
                                                                                    </div>

                                                                                    <!-- Remove -->
                                                                                    <div class="col-md-1 d-flex align-items-end">
                                                                                        <button type="button" class="btn btn-danger remove-btn" style="display:none;">
                                                                                            <i class="ti ti-trash"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="mt-2">
                                                                                <button type="button" class="btn btn-primary" id="addBtn">
                                                                                    <i class="ti ti-plus"></i> Add Vital
                                                                                </button>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-primary">Save</button>
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
                                                                @foreach($vitals as $vital)
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
                    @if(!empty($firstRecord->messure_date))
                        {{ \Carbon\Carbon::parse($firstRecord->messure_date)->format('d/m/Y h:i A') }}
                    @else
                        -
                    @endif
                </td>

                {{-- Loop through all vitals dynamically --}}
                @foreach($vitals as $vital)
                    @php
                        $record = $caseVitals->where('vital_id', $vital->id)->first();
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
                <td colspan="{{ 4 + $vitals->count() }}" class="text-center text-muted">
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

        @include('components.modals.add-prescription-modal')
 <!-- Chart.js Scripts -->
<script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chartjs/chart-data.js') }}"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.Chart) {
            // Fetch data dynamically from Laravel controller
            fetch("{{ route('chart.data') }}") // Laravel route returning JSON
                .then(response => response.json())
                .then(data => {
                    // Render chart dynamically with fetched data
                    var ctx = document.getElementById('AppointchartLine1').getContext('2d');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,   // e.g. ['Jan', 'Feb', ...]
                            datasets: [{
                                label: data.label,  // e.g. 'Patients'
                                data: data.values,  // e.g. [10, 15, 8, ...]
                                borderColor: 'rgba(171,0,219,1)',
                                backgroundColor: 'rgba(171,0,219,0.2)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: true },
                                tooltip: { mode: 'index', intersect: false }
                            },
                            scales: {
                                x: { display: true },
                                y: { display: true, beginAtZero: true }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error loading chart data:', error));
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addBtn = document.getElementById('addBtn');
        const vitalFields = document.getElementById('vitalFields');

        addBtn.addEventListener('click', function () {
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'gy-3', 'vital-row', 'mb-2');
            newRow.innerHTML = `
                <div class="col-md-4">
                    <label class="form-label">Vital Name</label>
                    <select class="form-select" name="vital_name[]">
                        <option value="">Select</option>
                        @foreach($vitals as $vital)
                            <option value="{{ $vital->id }}">{{ $vital->name . ' (' . $vital->reference_range . ')' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vital Value</label>
                    <input type="text" name="vital_value[]" class="form-control" />
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" name="date[]" class="form-control" />
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-btn">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            `;
            vitalFields.appendChild(newRow);

            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.vital-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('.remove-btn');
                removeBtn.style.display = rows.length > 1 ? 'block' : 'none';
                removeBtn.addEventListener('click', function () {
                    row.remove();
                    updateRemoveButtons();
                });
            });
        }

        updateRemoveButtons();
    });
</script>

    <!-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            const addBtn = document.getElementById("addBtn");
            const vitalFields = document.getElementById("vitalFields");

            // Attach remove event to existing remove buttons
            vitalFields.querySelectorAll(".remove-btn").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    btn.closest(".vital-row").remove();
                });
            });

            addBtn.addEventListener("click", function () {
                // Clone the first row
                let firstRow = vitalFields.querySelector(".vital-row");
                let newRow = firstRow.cloneNode(true);

                // Clear input values
                newRow.querySelectorAll("input, select").forEach(el => el.value = "");

                // Show remove button
                let removeBtn = newRow.querySelector(".remove-btn");
                removeBtn.style.display = "inline-block";

                // Attach remove event to the new button
                removeBtn.addEventListener("click", function () {
                    newRow.remove();
                });

                // Append new row
                vitalFields.appendChild(newRow);
            });
        });
    </script> -->

@endsection