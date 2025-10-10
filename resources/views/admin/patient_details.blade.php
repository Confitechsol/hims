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
    </style>

    <div class="p-4">

        <!-- tab start -->
        <ul class="nav nav-tabs nav-bordered mb-3">
            <li class="nav-item">
                <a href="#overview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active bg-transparent">
                    <span>Overview</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#transactions" data-bs-toggle="tab" aria-expanded="true" class="nav-link bg-transparent">
                    <span>Transactions</span>
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
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="fs-13 fw-bold mb-1">Phone :</h6>
                                                <p class="mb-0">8910245678</p>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-calendar-days text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="fs-13 fw-bold mb-1">Age :</h6>
                                                <p class="mb-0">22 Year 9 Month 5 Days (As Of Date 10/06/2025)</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-hands-holding-child text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="fs-13 fw-bold mb-1">Guardian Name :</h6>
                                                <p class="mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-mars-and-venus text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="fs-13 fw-bold mb-1">Gender :</h6>
                                                <p class="mb-0">Female</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-users-gear text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="fs-13 fw-bold mb-1">TPA :</h6>
                                                <p class="mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-id-badge text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="fs-13 fw-bold mb-1">TPA ID :</h6>
                                                <p class="mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-user-check text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="fs-13 fw-bold mb-1">TPA Validity :</h6>
                                                <p class="mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-barcode text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="fs-13 fw-bold mb-1">Barcode :</h6>
                                                <p class="mb-0">--</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                    class="fa-solid fa-qrcode text-primary"></i></span>
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="fs-13 fw-bold mb-1">QR Code :</h6>
                                                <p class="mb-0">--</p>
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
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>

            </div>
            <div class="tab-pane" id="transactions">

                <!--  Start Filter -->
                <div class=" d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div class="search-set mb-3">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="table-search d-flex align-items-center mb-0">
                                    <div class="search-input">
                                        <a href="javascript:void(0);" class="btn-searchset"></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex right-content align-items-center flex-wrap mb-3">
                            <div class="reportrange-picker d-flex align-items-center reportrange">
                                <i class="ti ti-calendar text-gray-5 fs-14 me-1"></i><span
                                    class="reportrange-picker-field">16
                                    Apr 25 - 16 Apr 25</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex table-dropdown mb-3 right-content align-items-center flex-wrap row-gap-3">
                        <div class="dropdown me-2">
                            <a href="javascript:void(0);"
                                class="bg-white border rounded btn btn-md text-dark fs-14 py-1 align-items-center d-flex fw-normal"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i class="ti ti-filter text-gray-5 me-1"></i>Filters
                            </a>
                            <div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown p-0">
                                <div class="d-flex align-items-center justify-content-between border-bottom filter-header">
                                    <h4 class="mb-0 fw-bold">Filter</h4>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="link-danger text-decoration-underline">Clear
                                            All</a>
                                    </div>
                                </div>
                                <form action="#">
                                    <div class="filter-body pb-0">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Transaction ID</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                    aria-expanded="true">
                                                    Select <i class="ti ti-chevron-down ms-auto"></i>
                                                </a>
                                                <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">
                                                    <div class="mb-3">
                                                        <div class="input-icon-start input-icon position-relative">
                                                            <span class="input-icon-addon fs-12">
                                                                <i class="ti ti-search"></i>
                                                            </span>
                                                            <input type="text" class="form-control form-control-md"
                                                                placeholder="Search">
                                                        </div>
                                                    </div>
                                                    <ul class="mb-3">
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                #TNX0025
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                #TNX0024
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                #TNX0023
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                #TNX0022
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                #TNX0021
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                #TNX0020
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                #TNX0019
                                                            </label>
                                                        </li>
                                                    </ul>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-outline-white w-100 close-filter">Cancel</a>
                                                        </div>
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary w-100">Select</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Description</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                    aria-expanded="true">
                                                    Select <i class="ti ti-chevron-down ms-auto"></i>
                                                </a>
                                                <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">
                                                    <div class="mb-3">
                                                        <div class="input-icon-start input-icon position-relative">
                                                            <span class="input-icon-addon fs-12">
                                                                <i class="ti ti-search"></i>
                                                            </span>
                                                            <input type="text" class="form-control form-control-md"
                                                                placeholder="Search">
                                                        </div>
                                                    </div>
                                                    <ul class="mb-3">
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                General Consultation
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Dental Cleaning
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Eye Checkup
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                X-Ray
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Physiotherapy Session
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Cardiac Screening
                                                            </label>
                                                        </li>
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Skin Allergy Test
                                                            </label>
                                                        </li>
                                                    </ul>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-outline-white w-100 close-filter">Cancel</a>
                                                        </div>
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary w-100">Select</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label mb-1 text-dark fs-14 fw-medium">Date<span
                                                    class="text-danger">*</span></label>
                                            <div class="input-icon-end position-relative">
                                                <input type="text" class="form-control datetimepicker"
                                                    placeholder="dd/mm/yyyy">
                                                <span class="input-icon-addon">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Payment Method</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                    aria-expanded="true">
                                                    Select <i class="ti ti-chevron-down ms-auto"></i>
                                                </a>
                                                <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">
                                                    <div class="mb-3">
                                                        <div class="input-icon-start input-icon position-relative">
                                                            <span class="input-icon-addon fs-12">
                                                                <i class="ti ti-search"></i>
                                                            </span>
                                                            <input type="text" class="form-control form-control-md"
                                                                placeholder="Search">
                                                        </div>
                                                    </div>
                                                    <ul class="mb-3">
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                PayPal
                                                            </label>
                                                        </li>
                                                        <li class="mb-0">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Debit Card
                                                            </label>
                                                        </li>
                                                        <li class="mb-0">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Cheque
                                                            </label>
                                                        </li>
                                                    </ul>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-outline-white w-100 close-filter">Cancel</a>
                                                        </div>
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary w-100">Select</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Amount</label>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="dropdown-toggle form-control"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                    aria-expanded="true">
                                                    Select
                                                </a>
                                                <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">
                                                    <div class="filter-range">
                                                        <input type="text" id="range_03">
                                                        <p>Range : <span class="text-gray-9">$200 - $5695</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Status</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                    aria-expanded="true">
                                                    Select <i class="ti ti-chevron-down ms-auto"></i>
                                                </a>
                                                <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">
                                                    <div class="mb-3">
                                                        <div class="input-icon-start input-icon position-relative">
                                                            <span class="input-icon-addon fs-12">
                                                                <i class="ti ti-search"></i>
                                                            </span>
                                                            <input type="text" class="form-control form-control-md"
                                                                placeholder="Search">
                                                        </div>
                                                    </div>
                                                    <ul class="mb-3">
                                                        <li class="mb-1">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Completed
                                                            </label>
                                                        </li>
                                                        <li class="mb-0">
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                                Pending
                                                            </label>
                                                        </li>
                                                    </ul>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-outline-white w-100 filter-close">Cancel</a>
                                                        </div>
                                                        <div class="col-6">
                                                            <a href="javascript:void(0);"
                                                                class="btn btn-primary w-100">Select</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="filter-footer d-flex align-items-center justify-content-end border-top">
                                        <a href="javascript:void(0);"
                                            class="btn btn-light btn-md me-2 fw-medium close-filter">Close</a>
                                        <button type="submit" class="btn btn-primary btn-md fw-medium">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  End Filter -->

                <!--  Start Table -->
                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-light">
                            <tr>
                                <th class="no-sort">
                                    Transaction ID
                                </th>
                                <th>Description</th>
                                <th>Paid Date</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0025</a></td>
                                <td class="text-dark"> General Consultation </td>
                                <td class="text-dark"> 30 Apr 2025</td>
                                <td class="text-dark"> PayPal</td>
                                <td class="text-dark"> $800</td>
                                <td> <span
                                        class="badge fs-13 badge-soft-success rounded text-success fw-medium border border-success">Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0024</a></td>
                                <td class="text-dark">Dental Cleaning</td>
                                <td class="text-dark"> 15 Apr 2025</td>
                                <td class="text-dark"> Debit Card </td>
                                <td class="text-dark"> $930</td>
                                <td> <span
                                        class="badge fs-13 badge-soft-info rounded text-info fw-medium border border-info">Pending</span>
                                </td>
                            </tr>

                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0023</a></td>
                                <td class="text-dark"> Eye Checkup </td>
                                <td class="text-dark"> 02 Apr 2025 </td>
                                <td class="text-dark"> Cheque </td>
                                <td class="text-dark"> $850</td>
                                <td> <span
                                        class="badge fs-13 badge-soft-success rounded text-success fw-medium border border-success">Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0022</a></td>
                                <td class="text-dark"> X-Ray </td>
                                <td class="text-dark"> 27 Mar 2025 </td>
                                <td class="text-dark"> Debit Card</td>
                                <td class="text-dark"> $80</td>

                                <td> <span
                                        class="badge fs-13 badge-soft-success rounded text-success fw-medium border border-success">Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0021</a></td>
                                <td class="text-dark">
                                    Physiotherapy Session
                                </td>
                                <td class="text-dark"> 12 Mar 2025</td>
                                <td class="text-dark"> PayPal</td>
                                <td class="text-dark"> $650</td>
                                <td> <span
                                        class="badge fs-13 badge-soft-success rounded text-success fw-medium border border-success">Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0020</a></td>
                                <td class="text-dark">
                                    Cardiac Screening
                                </td>
                                <td class="text-dark"> 05 Mar 2025 </td>
                                <td class="text-dark"> Cheque </td>
                                <td class="text-dark"> $430 </td>
                                <td> <span
                                        class="badge fs-13 badge-soft-success rounded text-success fw-medium border border-success">Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0019</a></td>
                                <td class="text-dark">
                                    Skin Allergy Test
                                </td>
                                <td class="text-dark"> 24 Feb 2025 </td>
                                <td class="text-dark"> Debit Card </td>
                                <td class="text-dark"> $300</td>

                                <td> <span
                                        class="badge fs-13 badge-soft-info rounded text-info fw-medium border border-info">Pending</span>
                                </td>
                            </tr>

                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0018</a></td>
                                <td class="text-dark">
                                    Blood Test
                                </td>
                                <td class="text-dark"> 16 Feb 2025 </td>
                                <td class="text-dark"> Cheque </td>
                                <td class="text-dark"> $450</td>
                                <td> <span
                                        class="badge fs-13 badge-soft-success rounded text-success fw-medium border border-success">Completed</span>
                                </td>
                            </tr>

                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0017</a></td>
                                <td class="text-dark">
                                    ENT Consultation
                                </td>
                                <td class="text-dark"> 01 Feb 2025 </td>
                                <td class="text-dark"> Debit Card </td>
                                <td class="fw-semibold text-dark"> $570</td>
                                <td> <span
                                        class="badge fs-13 badge-soft-success rounded text-success fw-medium border border-success">
                                        completed</span> </td>
                            </tr>
                            <tr>
                                <td> <a href="javascript:void(0);">#TNX0017</a></td>
                                <td class="text-dark">
                                    Nutrition Counseling
                                </td>
                                <td class="text-dark"> 25 Jan 2025 </td>
                                <td class="text-dark"> PayPal</td>
                                <td class="text-dark"> $800</td>
                                <td> <span
                                        class="badge fs-13 badge-soft-success rounded text-success fw-medium border border-success">Completed</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <!--  End Table -->

            </div>
        </div>
        <!-- tab content end -->
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for the doctor dropdown
            $('#doctor').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });
        });
    </script>

@endsection