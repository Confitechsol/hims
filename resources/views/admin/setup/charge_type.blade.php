{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <style>
        .input-group .input-group-addon {
            border-radius: 0;
            border: 1px solid #d2d6de;
            background-color: #d3a2e03d;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
        }

        .input-group {
            position: relative;
            display: table;
            border-collapse: separate;
        }

        .form-select {
            padding: 0.5rem 0.75rem !important;
        }
    </style>

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Charge Type List</h5>
                </div>

                <div class="card-body">


                    {{-- Hospital Name & Code --}}
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
                                        <div class="text-end d-flex">
                                            <a href="javascript:void(0);"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md" data-bs-toggle="modal"
                                                data-bs-target="#add_type"><i class="ti ti-plus me-1"></i>Add Charge
                                                Type</a>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="add_type" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Charge
                                                            Type</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="POST">
                                                            @csrf
                                                            <div class="row gy-3">
                                                                <div class="col-md-12 border-bottom pb-3">
                                                                    <label for="" class="form-label">Charge Type <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="charge_type" id="charge_type"
                                                                        class="form-control" required>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Module <span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="appointment" id="appointment" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">Appointment</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="opd" id="opd" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">OPD</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="ipd" id="ipd" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">IPD</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="pathology" id="pathology" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">Pathology</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="radiology" id="radiology" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">Radiology</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="blood_bank" id="blood_bank" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">Blood Bank</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="ambulance" id="ambulance" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">Ambulance</label>
                                                                    </div>
                                                                </div>
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

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Charge Type</th>
                                                    <th>Appointment</th>
                                                    <th>OPD</th>
                                                    <th>IPD</th>
                                                    <th>Pathology</th>
                                                    <th>Radiology</th>
                                                    <th>Blood Bank</th>
                                                    <th>Ambulance</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> Appointment </h6>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" checked>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> OPD </h6>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" checked>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> IPD </h6>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" checked>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> Pathology </h6>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" checked>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> Radiology </h6>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" checked>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input">
                                                    </td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>




@endsection