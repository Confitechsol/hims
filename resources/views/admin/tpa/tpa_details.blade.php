@extends('layouts.adminLayout')
@section('content')


    <style>
        .sidebars.settings-sidebar {
            width: 250px !important;
        }

        .module_billing {
            border-radius: 8px;
            color: #fff;
            background-color: #ab00db;
            width: 100%;
            padding: 20px;
            box-shadow: 5px 5px 8px 0px #bbbbbb;
            min-height: 110px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .module_billing h5 {
            color: #fff;
            font-size: 18px;
        }

        .gray_text {
            color: #d2d2d2ff;
        }
    </style>



    <div class="content">

        <!-- page header start -->
        <div class="mb-4">
            <h6 class="fw-bold mb-0 d-flex align-items-center"> <a href="patients.html" class="text-dark"> <i
                        class="ti ti-chevron-left me-1"></i>TPA Details</a></h6>
        </div>
        <!-- page header end -->

        <!-- card start -->
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="tpa_details p-4">
                        <div class="row gy-4">
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>TPA Name</h5>
                                    <p class="gray_text">{{ $organisations->organisation_name }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5> Code</h5>
                                    <p class="gray_text">{{ $organisations->code }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>Contact No</h5>
                                    <p class="gray_text">{{ $organisations->contact_no }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>Address</h5>
                                    <p class="gray_text">{{ $organisations->address }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>Contact Person Name</h5>
                                    <p class="gray_text">{{ $organisations->contact_person_name }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>Contact Person Phone</h5>
                                    <p class="gray_text">{{ $organisations->contact_person_phone }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- card end -->


        <!-- row start -->
        <div class="row">
            <div class="col-12 d-flex">
                <div class="card shadow-sm border-0 w-100">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>TPA Details
                        </h5>
                    </div>

                    <div class="card-body" id="charge_type_form">
                        <form action="" method="post">
                            @csrf
                            <div class="d-flex gap-3 align-items-center">
                                <div class="col-md-1">
                                    <label for="case_id" class="form-label">Charge Type<span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="charge-type" name="charge_type" style="width: 100%;">
                                        <option value="">Select</option>
                                        @foreach($chargetypes as $chargetype)
                                            <option value="{{ $chargetype->id }}">{{ $chargetype->charge_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <!-- Table start -->
                    <div class="table-responsive table-nowrap">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Charge Type</th>
                                    <th>Charge Category</th>
                                    <th>Charge Name</th>
                                    <th>Description</th>
                                    <th>Standard Charge (INR)</th>
                                    <th>TPA Charge (INR)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <h6 class="fs-14 mb-1">opd</h6>
                                    </td>
                                    <td>qw</td>
                                    <td>qw</td>
                                    <td>qw</td>
                                    <td>100</td>
                                    <td>2323</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="javascript: void(0);"
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                <i class="ti ti-pencil" data-bs-toggle="tooltip" title="Show"></i></a>
                                            <a href="javascript: void(0);"
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                <i class="ti ti-trash" data-bs-toggle="tooltip" title="Reschedule"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Table end -->
                </div><!-- end card -->
            </div>
        </div>
        <!-- row end -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#charge-type').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true,
                dropdownParent: $('#charge_type_form')

            });
        });
    </script>

@endsection