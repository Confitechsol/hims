{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <style>
        .hidden {
            display: none;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            background-color: #ccc;
            border-radius: 24px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            transition: .3s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: .3s;
        }

        input:checked+.slider {
            background-color: #750096;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }
    </style>

    <style>
        .certificate {
            width: 1000px;
            margin: 20px auto;
            background: #ffffff;
            padding: 40px;
            /* border: 5px solid #4a6fa5; */
            border-radius: 12px;
            box-shadow: 0 0 18px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-box {
            width: 200px;
            margin: 0px auto 50px;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 3px;
            color: #750096;
        }

        .sub-title {
            font-size: 14px;
            color: #5f6e82;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        h2 {
            font-size: 22px;
            margin-top: 30px;
            color: #750096;
            border-bottom: 3px solid #750096;
            padding-bottom: 6px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            gap: 20px;
        }

        .col {
            width: 48%;
        }

        label {
            font-weight: 600;
            font-size: 15px;
            color: #2c3e50;
            display: block;
            margin-bottom: 4px;
        }

        .line-data {
            font-size: 16px;
            padding: 8px 10px;
            border-bottom: 2px solid #750096;
            background: #e7c1f217;
            border-radius: 4px;
            margin-bottom: 12px;
        }

        .multi-data {
            font-size: 16px;
            padding: 8px 10px;
            border: 2px solid #750096;
            background: #e7c1f217;
            border-radius: 4px;
            height: 70px;
            margin-bottom: 15px;
        }

        .seal-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .seal-box,
        .sign-box {
            width: 45%;
            height: 130px;
            border: 2px dashed #750096;
            border-radius: 6px;
            text-align: center;
            padding-top: 50px;
            font-size: 15px;
            color: #750096;
            background: #e7c1f217;
        }
    </style>
    <!-- row start -->

    <div class="row p-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>
                        Certificate Template List
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
                                            <input type="text" class="form-control shadow-sm" placeholder="Search">

                                        </div>
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_certificate"><i
                                                        class="ti ti-plus me-1"></i>Add Certificate Template</a>
                                            </div>
                                            <!-- First Modal -->
                                            <div class="modal fade" id="add_certificate" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content ">

                                                        <div class="modal-header"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                            <h5 class="modal-title" id="addSpecializationLabel">
                                                                Add Certificate Template
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>

                                                        </div>

                                                        <div class="modal-body">

                                                            <div class="row gy-3">

                                                                <div class="col-md-12">
                                                                    <label for="template_name"
                                                                        class="form-label">Certificate Template Name <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        name="template_name" id="template_name" required>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="header_left" class="form-label">Header Left
                                                                        Text</label>
                                                                    <input type="text" class="form-control"
                                                                        name="header_left" id="header_left">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="header_center" class="form-label">Header
                                                                        Center Text</label>
                                                                    <input type="text" class="form-control"
                                                                        name="header_center" id="header_center">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="header_right" class="form-label">Header
                                                                        Right Text</label>
                                                                    <input type="text" class="form-control"
                                                                        name="header_right" id="header_right">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="body_txt" class="form-label">Body
                                                                        Text <span class="text-danger">*</span></label>
                                                                    <textarea name="body_txt" id="body_txt"
                                                                        class="form-control mb-2" required></textarea>
                                                                    <span class="text-primary">[patient_name] [patient_id]
                                                                        [dob] [age] [gender]
                                                                        [email] [phone] [address] [opd_ipd_no]
                                                                        [guardian_name] [opd_checkup_id] [consultant_doctor]
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="footer_left" class="form-label">Footer Left
                                                                        Text</label>
                                                                    <input type="text" class="form-control"
                                                                        name="footer_left" id="footer_left">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="footer_center" class="form-label">Footer
                                                                        Center Text</label>
                                                                    <input type="text" class="form-control"
                                                                        name="footer_center" id="footer_center">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="footer_right" class="form-label">Footer
                                                                        Right Text</label>
                                                                    <input type="text" class="form-control"
                                                                        name="footer_right" id="footer_right">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="certificate_design"
                                                                        class="form-label">Certificate Design</label>
                                                                    <div class="row gy-3">
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                name="header_height" id="header_height"
                                                                                placeholder="Header Height">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                name="footer_height" id="footer_height"
                                                                                placeholder="Footer Height">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                name="body_height" id="body_height"
                                                                                placeholder="Body Height">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control"
                                                                                name="body_width" id="body_width"
                                                                                placeholder="Body Width">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="toggleField" class="form-label">
                                                                                Patient Photo</label>
                                                                            <label class="switch form-label">
                                                                                <input type="checkbox" id="toggleField">
                                                                                <span class="slider form-control"></span>
                                                                            </label>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div id="extraField" class="hidden">
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Photo Height">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <label for="background_image"
                                                                                class="form-label">Background Image</label>
                                                                            <input type="file" class="form-control"
                                                                                name="background_image"
                                                                                id="background_image">
                                                                        </div>


                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save</button>
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
                                                    <th>Certificate Template Name</th>
                                                    <th>Background Image</th>
                                                    <!-- <th>Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                                            <div class="text-end d-flex">
                                                                <a href="patient_details_template" data-bs-toggle="modal"
                                                                    data-bs-target="#patient_details">Sample Patient File
                                                                    Cover</a>
                                                            </div>
                                                            <!-- First Modal -->
                                                            <div class="modal fade" id="patient_details" tabindex="-1"
                                                                aria-hidden="true">
                                                                <div
                                                                    class="modal-dialog modal-dialog-centered modal-fullscreen">
                                                                    <div class="modal-content ">

                                                                        <div class="modal-header"
                                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                            <h5 class="modal-title"
                                                                                id="addSpecializationLabel">
                                                                                View Certificate Template
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"></button>

                                                                        </div>

                                                                        <div class="modal-body">

                                                                            <div class="row pb-3">
                                                                                <div class="col-md-8">
                                                                                    <img src="" alt="Hospital Logo">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <p><strong class="text-primary">Address
                                                                                            :</strong> ABC</p>
                                                                                </div>
                                                                            </div>
                                                                            <!-- card start -->
                                                                            <div class="card">
                                                                                <div class="row align-items-end">

                                                                                    <div class="col-xl-12 col-lg-12">
                                                                                        <div
                                                                                            class="d-sm-flex align-items-center position-relative z-0 overflow-hidden p-3">
                                                                                            <img src="assets/img/icons/shape-01.svg"
                                                                                                alt="img"
                                                                                                class="z-n1 position-absolute end-0 top-0 d-none d-lg-flex">
                                                                                            <a href="javascript:void(0);"
                                                                                                class="avatar avatar-xxxl patient-avatar me-2 flex-shrink-0">
                                                                                                <img src="assets/img/users/user-08.jpg"
                                                                                                    alt="product"
                                                                                                    class="rounded">
                                                                                            </a>
                                                                                            <div>
                                                                                                <p
                                                                                                    class="text-primary mb-1">
                                                                                                    #PT0025</p>
                                                                                                <h5 class="mb-1"><a
                                                                                                        href="javascript:void(0);"
                                                                                                        class="fw-bold">Alberto
                                                                                                        Ripley</a></h5>
                                                                                                <p class="mb-3">4150 Hiney
                                                                                                    Road, Las Vegas, NV
                                                                                                    89109</p>
                                                                                                <div
                                                                                                    class="d-flex align-items-center flex-wrap">
                                                                                                    <p
                                                                                                        class="mb-0 d-inline-flex align-items-center">
                                                                                                        <i
                                                                                                            class="ti ti-phone me-1 text-dark"></i>Phone
                                                                                                        : <span
                                                                                                            class="text-dark ms-1">+1
                                                                                                            54546
                                                                                                            45648</span>
                                                                                                    </p>
                                                                                                    <span
                                                                                                        class="mx-2 text-light">|</span>
                                                                                                    <p
                                                                                                        class="mb-0 d-inline-flex align-items-center">
                                                                                                        <i
                                                                                                            class="ti ti-user me-1 text-dark"></i>Guardian
                                                                                                        Name: <span
                                                                                                            class="text-dark ms-1">abc</span>
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- card end -->

                                                                            <!-- row start -->
                                                                            <div class="row">
                                                                                <div class="col-md-12 d-flex">
                                                                                    <div
                                                                                        class="card shadow-sm flex-fill w-100">
                                                                                        <div class="card-header">
                                                                                            <h5 class="fw-bold mb-0"><i
                                                                                                    class="ti ti-user-star me-1"></i>About
                                                                                            </h5>
                                                                                        </div>
                                                                                        <div class="card-body pb-0">
                                                                                            <div class="row">
                                                                                                <div class="col-md-4">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center mb-3">
                                                                                                        <span
                                                                                                            class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                                                                                class="ti ti-calendar-event text-primary fs-16"></i></span>
                                                                                                        <div>
                                                                                                            <h6
                                                                                                                class="fs-13 fw-bold mb-1">
                                                                                                                DOB</h6>
                                                                                                            <p class="mb-0">
                                                                                                                25 Jan 1990
                                                                                                            </p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center mb-3">
                                                                                                        <span
                                                                                                            class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                                                                                class="ti ti-calendar-time text-primary fs-16"></i></span>
                                                                                                        <div>
                                                                                                            <h6
                                                                                                                class="fs-13 fw-bold mb-1">
                                                                                                                Age
                                                                                                            </h6>
                                                                                                            <p class="mb-0">
                                                                                                                35</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center mb-3">
                                                                                                        <span
                                                                                                            class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                                                                                class="ti ti-gender-male text-primary fs-16"></i></span>
                                                                                                        <div>
                                                                                                            <h6
                                                                                                                class="fs-13 fw-bold mb-1">
                                                                                                                Gender</h6>
                                                                                                            <p class="mb-0">
                                                                                                                Male</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center mb-3">
                                                                                                        <span
                                                                                                            class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                                                                                class="ti ti-mail text-primary fs-16"></i></span>
                                                                                                        <div>
                                                                                                            <h6
                                                                                                                class="fs-13 fw-bold mb-1">
                                                                                                                Email</h6>
                                                                                                            <p
                                                                                                                class="mb-0 text-break">
                                                                                                                <a href="https://preclinic.dreamstechnologies.com/cdn-cgi/l/email-protection"
                                                                                                                    class="__cf_email__"
                                                                                                                    data-cfemail="31505d535443455e715449505c415d541f525e5c">[email&#160;protected]</a>
                                                                                                            </p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center mb-3">
                                                                                                        <span
                                                                                                            class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                                                                                class="ti ti-heart-rate-monitor text-primary fs-16"></i></span>
                                                                                                        <div>
                                                                                                            <h6
                                                                                                                class="fs-13 fw-bold mb-1">
                                                                                                                OPD/IPD NO
                                                                                                            </h6>
                                                                                                            <p class="mb-0">
                                                                                                                [opd_ipd_no]
                                                                                                            </p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center mb-3">
                                                                                                        <span
                                                                                                            class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                                                                                class="ti ti-heart-rate-monitor text-primary fs-16"></i></span>
                                                                                                        <div>
                                                                                                            <h6
                                                                                                                class="fs-13 fw-bold mb-1">
                                                                                                                OPD Checkup
                                                                                                                Id
                                                                                                            </h6>
                                                                                                            <p class="mb-0">
                                                                                                                [opd_checkup_id]
                                                                                                            </p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-4">
                                                                                                    <div
                                                                                                        class="d-flex align-items-center mb-3">
                                                                                                        <span
                                                                                                            class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                                                                                class="ti ti-heart-rate-monitor text-primary fs-16"></i></span>
                                                                                                        <div>
                                                                                                            <h6
                                                                                                                class="fs-13 fw-bold mb-1">
                                                                                                                Consultant
                                                                                                                Doctor
                                                                                                            </h6>
                                                                                                            <p class="mb-0">
                                                                                                                [consultant_doctor]
                                                                                                            </p>
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
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <img src="" alt="Background Image">
                                                    </td>
                                                    <!-- <td>
                                                            <div class="d-flex gap-2">
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-primary rounded-pill">
                                                                    <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                        title="Show"></i></a>
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill">
                                                                    <i class="ti ti-pencil" data-bs-toggle="tooltip"
                                                                        title="edit"></i></a>
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                    <i class="ti ti-trash" data-bs-toggle="tooltip"
                                                                        title="Delete"></i></a>
                                                            </div>
                                                        </td> -->
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                                            <div class="text-end d-flex">
                                                                <a href="" data-bs-toggle="modal"
                                                                    data-bs-target="#birth_certificate">Sample Birth
                                                                    Certificate</a>
                                                            </div>
                                                            <!-- First Modal -->
                                                            <div class="modal fade" id="birth_certificate" tabindex="-1"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                                                    <div class="modal-content ">

                                                                        <div class="modal-header"
                                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                            <h5 class="modal-title"
                                                                                id="addSpecializationLabel">
                                                                                Birth Certificate Template
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"></button>

                                                                        </div>

                                                                        <div class="modal-body">

                                                                            <div class="certificate">

                                                                                <div class="header">
                                                                                    <div class="logo-box">
                                                                                        <img src="assets/img/logo.png"
                                                                                            alt="">
                                                                                    </div>
                                                                                    <h1>Birth Certificate</h1>

                                                                                </div>

                                                                                <!-- Child Information -->
                                                                                <h2>Child Information</h2>

                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Full Name</label>
                                                                                        <div class="line-data">Aarav Sharma
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Sex</label>
                                                                                        <div class="line-data">Male</div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Date of Birth</label>
                                                                                        <div class="line-data">14 November
                                                                                            2025</div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Time of Birth</label>
                                                                                        <div class="line-data">09:42 AM
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <label>Place of Birth</label>
                                                                                <div class="line-data">Sunrise
                                                                                    Multispeciality Hospital, Kolkata</div>

                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Birth Weight</label>
                                                                                        <div class="line-data">3.1 kg</div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Birth Length</label>
                                                                                        <div class="line-data">49 cm</div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Apgar Score (1 min / 5
                                                                                            min)</label>
                                                                                        <div class="line-data">8 / 9</div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Gestational Age
                                                                                            (Weeks)</label>
                                                                                        <div class="line-data">38 weeks
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- ICD-10 -->
                                                                                <h2>ICD-10 Coding</h2>

                                                                                <label>Live Birth Code (Z38 Series)</label>
                                                                                <div class="line-data">Z38.0 – Single live
                                                                                    birth in hospital</div>

                                                                                <label>Maternal Conditions (O00 –
                                                                                    O99)</label>
                                                                                <div class="line-data">O80 – Full-term
                                                                                    uncomplicated delivery</div>

                                                                                <label>Congenital Anomalies (Q00 –
                                                                                    Q99)</label>
                                                                                <div class="line-data">None Reported</div>

                                                                                <!-- Mother -->
                                                                                <h2>Mother's Details</h2>
                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Full Name</label>
                                                                                        <div class="line-data">Priya Sharma
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Age</label>
                                                                                        <div class="line-data">29</div>
                                                                                    </div>
                                                                                </div>

                                                                                <label>Address</label>
                                                                                <div class="multi-data">45/2 Lake Road,
                                                                                    South Kolkata, West Bengal</div>

                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Mobile Number</label>
                                                                                        <div class="line-data">9876543210
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Blood Group</label>
                                                                                        <div class="line-data">B+</div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Father -->
                                                                                <h2>Father's Details</h2>
                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Full Name</label>
                                                                                        <div class="line-data">Rohan Sharma
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Age</label>
                                                                                        <div class="line-data">32</div>
                                                                                    </div>
                                                                                </div>

                                                                                <label>Address</label>
                                                                                <div class="multi-data">45/2 Lake Road,
                                                                                    South Kolkata, West Bengal</div>

                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Mobile Number</label>
                                                                                        <div class="line-data">9830023456
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Blood Group</label>
                                                                                        <div class="line-data">O+</div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Informant -->
                                                                                <h2>Informant Details</h2>
                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Name of Informant</label>
                                                                                        <div class="line-data">Rohan Sharma
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Relation to Child</label>
                                                                                        <div class="line-data">Father</div>
                                                                                    </div>
                                                                                </div>

                                                                                <label>Date of Reporting</label>
                                                                                <div class="line-data">15 November 2025
                                                                                </div>

                                                                                <!-- Seal & Signature -->
                                                                                <h2>Authentication</h2>
                                                                                <div class="seal-section">
                                                                                    <div class="sign-box">Dr. Ananya
                                                                                        Gupta<br><br>MBBS, MD (Pediatrics)
                                                                                    </div>
                                                                                    <div class="seal-box">Sunrise
                                                                                        Multispeciality Hospital Seal</div>
                                                                                </div>

                                                                            </div>


                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <img src="" alt="Background Image">
                                                    </td>
                                                    <!-- <td>
                                                            <div class="d-flex gap-2">
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-primary rounded-pill">
                                                                    <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                        title="Show"></i></a>
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill">
                                                                    <i class="ti ti-pencil" data-bs-toggle="tooltip"
                                                                        title="edit"></i></a>
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                    <i class="ti ti-trash" data-bs-toggle="tooltip"
                                                                        title="Delete"></i></a>
                                                            </div>
                                                        </td> -->
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                                            <div class="text-end d-flex">
                                                                <a href="" data-bs-toggle="modal"
                                                                    data-bs-target="#death_certificate">Sample Death
                                                                    Certificate</a>
                                                            </div>
                                                            <!-- First Modal -->
                                                            <div class="modal fade" id="death_certificate" tabindex="-1"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                                                    <div class="modal-content ">

                                                                        <div class="modal-header"
                                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                            <h5 class="modal-title"
                                                                                id="addSpecializationLabel">
                                                                                Death Certificate Template
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"></button>

                                                                        </div>

                                                                        <div class="modal-body">

                                                                            <div class="certificate">

                                                                                <div class="header">
                                                                                    <div class="logo-box">
                                                                                        <img src="assets/img/logo.png"
                                                                                            alt="">
                                                                                    </div>
                                                                                    <h1>Death Certificate</h1>

                                                                                </div>

                                                                                <!-- Child Information -->
                                                                                <!-- <h2>Child Information</h2> -->

                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Patient Name</label>
                                                                                        <div class="line-data">Abisekh Roy
                                                                                            (5)
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Gender</label>
                                                                                        <div class="line-data">Male</div>
                                                                                    </div>

                                                                                </div>
                                                                                
                                                                                <div class="row">
                                                                                    
                                                                                    <div class="col">
                                                                                        <label>Death Date</label>
                                                                                        <div class="line-data"> 07/04/2025
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Death Time</label>
                                                                                        <div class="line-data">10:10 PM
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label>Guardian Name</label>
                                                                                        <div class="line-data">Anil Roy
                                                                                        </div>
                                                                                    </div>

                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col">
                                                                                        <label>Address</label>
                                                                                        <div class="line-data">--</div>
                                                                                    </div>
                                                                                </div>



                                                                            </div>


                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <img src="" alt="Background Image">
                                                    </td>
                                                    <!-- <td>
                                                            <div class="d-flex gap-2">
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-primary rounded-pill">
                                                                    <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                        title="Show"></i></a>
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill">
                                                                    <i class="ti ti-pencil" data-bs-toggle="tooltip"
                                                                        title="edit"></i></a>
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                    <i class="ti ti-trash" data-bs-toggle="tooltip"
                                                                        title="Delete"></i></a>
                                                            </div>
                                                        </td> -->
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

    <script>
        const toggle = document.getElementById('toggleField');
        const extraField = document.getElementById('extraField');

        toggle.addEventListener('change', () => {
            if (toggle.checked) {
                extraField.classList.remove('hidden');
            } else {
                extraField.classList.add('hidden');
            }
        });
    </script>

@endsection