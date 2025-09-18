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
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Charges Details List</h5>
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
                                                data-bs-target="#add_charges"><i class="ti ti-plus me-1"></i>Add Charges</a>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="add_charges" tabindex="-1"
                                            aria-labelledby="addSpecializationLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                                <div class="modal-content modal-xl">
                                                    <div class="modal-header rounded-0 modal-xl"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Charges</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('roles.store')  }}" method="POST">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="row gy-3">
                                                                        <div class="col-md-6">
                                                                            <label for="" class="form-label">Charge
                                                                                Type <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name=" charge_type" id="charge_type"
                                                                                class="form-select" required>
                                                                                <option value="">Select</option>
                                                                                <option value="1">Appointment</option>
                                                                                <option value="2">OPD</option>
                                                                                <option value="3">IPD</option>
                                                                                <option value="4">Pathology</option>
                                                                                <option value="5">Radiology</option>
                                                                                <option value="6">Blood Bank</option>
                                                                                <option value="7">Ambulance</option>
                                                                                <option value="8">Procedures</option>
                                                                                <option value="9">Investigations</option>
                                                                                <option value="10">Supplier</option>
                                                                                <option value="11">Operations</option>
                                                                                <option value="12">Others</option>
                                                                                <option value="13">Bed Charges</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="" class="form-label">Charge
                                                                                Category <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name=" charge_category"
                                                                                id="charge_category" class="form-select"
                                                                                required>
                                                                                <option value="">Select</option>
                                                                                <option value="1">Appointment</option>
                                                                                <option value="2">OPD</option>
                                                                                <option value="3">IPD</option>
                                                                                <option value="4">Pathology</option>
                                                                                <option value="5">Radiology</option>
                                                                                <option value="6">Blood Bank</option>
                                                                                <option value="7">Ambulance</option>
                                                                                <option value="8">Procedures</option>
                                                                                <option value="9">Investigations</option>
                                                                                <option value="10">Supplier</option>
                                                                                <option value="11">Operations</option>
                                                                                <option value="12">Others</option>
                                                                                <option value="13">Bed Charges</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="" class="form-label">Tax
                                                                                Category <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="tax_category" id="tax_category"
                                                                                class="form-select" autocomplete="off"
                                                                                required>
                                                                                <option value="">Select</option>
                                                                                <option value="2">VAT</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label for="" class="form-label">Tax</label>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control"
                                                                                    name="tax_percentage"
                                                                                    id="tax_percentage">
                                                                                <span class="input-group-addon "> %</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <label for="" class="form-label">Standard Charge
                                                                                (INR) <span
                                                                                    class="text-danger">*</span></label>

                                                                            <input type="text" class="form-control"
                                                                                name="standard_charge" id="standard_charge"
                                                                                required>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <label for=""
                                                                                class="form-label">Description</label>

                                                                            <textarea name="description" id="description"
                                                                                class="form-control"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row gy-3">
                                                                        <div class="col-md-4">
                                                                            <label for="" class="form-label">Unit Type <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="unit_type" id="unit_type"
                                                                                class="form-select" autocomplete="off"
                                                                                required>
                                                                                <option value="">Select</option>
                                                                                <option value="2">Each</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <label for="" class="form-label">Charge
                                                                                Name<span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="charge_name"
                                                                                id="charge_name" class="form-control">
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <label>Scheduled Charges For TPA</label>
                                                                            <button type="button" class="plusign float-end"
                                                                                onclick="apply_to_all()">Apply To
                                                                                All</button>
                                                                            <div class="chargesborbg form-control mt-4">
                                                                                <div class="form-group">
                                                                                    <table class="printablea4">
                                                                                        <tbody>
                                                                                            <tr id="schedule_charge">
                                                                                                <input type="hidden"
                                                                                                    name="schedule_charge_id[]"
                                                                                                    value="5">
                                                                                                <td class="col-sm-8"
                                                                                                    style="vertical-align: bottom; text-align: left; padding-right: 20px;">
                                                                                                    MedoLogi TPA Pvt. Ltd.
                                                                                                </td>
                                                                                                <td class="col-sm-4">
                                                                                                    <input type="text"
                                                                                                        name="schedule_charge_5"
                                                                                                        id="schedule_charge_5"
                                                                                                        class="form-control schedule_charge">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr id="schedule_charge">
                                                                                                <input type="hidden"
                                                                                                    name="schedule_charge_id[]"
                                                                                                    value="4">
                                                                                                <td class="col-sm-8"
                                                                                                    style="vertical-align: bottom; text-align: left; padding-right: 20px;">
                                                                                                    Vidal Health TPA </td>
                                                                                                <td class="col-sm-4">
                                                                                                    <input type="text"
                                                                                                        name="schedule_charge_4"
                                                                                                        id="schedule_charge_4"
                                                                                                        class="form-control schedule_charge">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr id="schedule_charge">
                                                                                                <input type="hidden"
                                                                                                    name="schedule_charge_id[]"
                                                                                                    value="3">
                                                                                                <td class="col-sm-8"
                                                                                                    style="vertical-align: bottom; text-align: left; padding-right: 20px;">
                                                                                                    Paramount Health
                                                                                                    Services </td>
                                                                                                <td class="col-sm-4">
                                                                                                    <input type="text"
                                                                                                        name="schedule_charge_3"
                                                                                                        id="schedule_charge_3"
                                                                                                        class="form-control schedule_charge">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr id="schedule_charge">
                                                                                                <input type="hidden"
                                                                                                    name="schedule_charge_id[]"
                                                                                                    value="2">
                                                                                                <td class="col-sm-8"
                                                                                                    style="vertical-align: bottom; text-align: left; padding-right: 20px;">
                                                                                                    Raksha TPA Pvt. Ltd.
                                                                                                </td>
                                                                                                <td class="col-sm-4">
                                                                                                    <input type="text"
                                                                                                        name="schedule_charge_2"
                                                                                                        id="schedule_charge_2"
                                                                                                        class="form-control schedule_charge">
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr id="schedule_charge">
                                                                                                <input type="hidden"
                                                                                                    name="schedule_charge_id[]"
                                                                                                    value="1">
                                                                                                <td class="col-sm-8"
                                                                                                    style="vertical-align: bottom; text-align: left; padding-right: 20px;">
                                                                                                    MediAssist TPA Pvt. Ltd.
                                                                                                </td>
                                                                                                <td class="col-sm-4">
                                                                                                    <input type="text"
                                                                                                        name="schedule_charge_1"
                                                                                                        id="schedule_charge_1"
                                                                                                        class="form-control schedule_charge">
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <span class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
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
                                                    <th>Name</th>
                                                    <th>Charge Category</th>
                                                    <th>Charge Type</th>
                                                    <th>Unit</th>
                                                    <th>Tax(%)</th>
                                                    <th>Standard Charge (INR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> Bed</h6>
                                                    </td>
                                                    <td>Bed Charges</td>
                                                    <td>IPD</td>
                                                    <td>Each</td>
                                                    <td>10.00</td>
                                                    <td>500</td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> Doctor Fees</h6>
                                                    </td>
                                                    <td>IPD Doctor Fees</td>
                                                    <td>IPD</td>
                                                    <td>Each</td>
                                                    <td>10.00
                                                    <td>800</td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
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