@extends('layouts.adminLayout')

@section('content')
    <!-- ========================
                                                                                                                                                   Start Page Content
                                                                                                                                                  ========================= -->

    {{-- <div class="page-wrapper"> --}}

        <style>
            .modal-backdrop.show:nth-of-type(2) {
                z-index: 1060;
                /* higher backdrop for nested modal */
            }

            #new_patient {
                z-index: 1070;
                /* ensure new modal is above the first */
            }
        </style>

        <!-- Start Content -->
        <div class="content pb-0">


            <!-- row start -->
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                            <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Appointment Billing</h5>
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
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-primary text-white ms-2 btn-md"
                                                            data-bs-toggle="modal" data-bs-target="#add_appointment"><i
                                                                class="ti ti-plus me-1"></i>Add
                                                            Appointment</a>
                                                    </div>
                                                    <!-- First Modal -->
                                                    <div class="modal fade" id="add_appointment" tabindex="-1"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                                            <div class="modal-content">

                                                                <div class="modal-header"
                                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                                    <div class="row w-100 align-items-center">
                                                                        <div class="col-md-7">
                                                                            <select class="form-select"
                                                                                id="appointment-type"
                                                                                data-placeholder="Enter Patient Name or Id…">
                                                                                <option value=""></option>
                                                                                <option>Patient 1</option>
                                                                                <option>Patient 2</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <!-- Button to open nested modal -->
                                                                            <a href="javascript:void(0);"
                                                                                class="btn btn-primary"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#new_patient">
                                                                                <i class="ti ti-plus me-1"></i>New Patient
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-md-1 text-end">
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="modal-body">

                                                                    <div class="row align-items-center gy-3">
                                                                        <div class="col-md-3">
                                                                            <label for="doctor" class="form-label">Doctor
                                                                                <span class="text-danger">*</span></label>
                                                                            <select class="form-select" id="doctor"
                                                                                data-placeholder="Select">
                                                                                <option value=""></option>
                                                                                <option value="">Amitabh Kulkarni</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="doctor_fees"
                                                                                class="form-label">Doctor
                                                                                Fees (INR)
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="text" name="amount"
                                                                                id="doctor_fees" class="form-control"
                                                                                readonly="readonly" disabled>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="shift" class="form-label">Shift
                                                                                <span class="text-danger">*</span></label>
                                                                            <select class="form-select" id="shift"
                                                                                data-placeholder="Select">
                                                                                <option value=""></option>
                                                                                <option value="">1</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="datetimepicker"
                                                                                class="form-label">Appointment Date
                                                                                <span class="text-danger">*</span></label>
                                                                            <input type="date" id="datetimepicker"
                                                                                name="date" class="form-control datetime">
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <label for="slot" class="form-label">Slot <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select class="form-select" id="slot">
                                                                                <option value="">Select</option>
                                                                                <option value="1">1</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="appointment_priority"
                                                                                class="form-label">Appointment
                                                                                Priority</label>
                                                                            <select class="form-select"
                                                                                id="appointment_priority"
                                                                                data-placeholder="Normal">
                                                                                <option value=""></option>
                                                                                <option value="low">Low</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="payment_method"
                                                                                class="form-label">Payment
                                                                                Method</label>
                                                                            <select class="form-select" id="payment_method">
                                                                                <option value="">Select</option>
                                                                                <option value="cash">Cash</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="status" class="form-label">Status
                                                                                <span class="text-danger">*</span></label>
                                                                            <select class="form-select" id="status">
                                                                                <option value="">Select</option>
                                                                                <option value="pending">Pending</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="discount_percentage"
                                                                                class="form-label">Discount
                                                                                Percentage <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" id="discount_percentage"
                                                                                name="discount_percentage"
                                                                                class="form-control">

                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <label for="discount_percentage"
                                                                                class="form-label">Message</label>
                                                                            <textarea name="message" id="message"
                                                                                class="form-control"></textarea>

                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <label for="live_con" class="form-label">Live
                                                                                Consultant (On
                                                                                Video Conference) <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select class="form-select" id="live_con">
                                                                                <option value="">Select</option>
                                                                                <option value="no">No</option>
                                                                            </select>
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
                                                                    <h5 class="modal-title">Add New Patient</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <form>
                                                                        <div class="row align-items-center gy-3">
                                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label>Name</label><span
                                                                                        class="text-danger"> *</span>
                                                                                    <input id="name" name="name"
                                                                                        placeholder="" type="text"
                                                                                        class="form-control" value=""
                                                                                        autocomplete="off">
                                                                                    <span class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label>Guardian Name</label>
                                                                                    <input type="text" name="guardian_name"
                                                                                        placeholder="" value=""
                                                                                        class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-12">
                                                                                <div class="row">
                                                                                    <div class="col-sm-3">
                                                                                        <div class="form-group">
                                                                                            <label> Gender</label>
                                                                                            <select class="form-select"
                                                                                                name="gender"
                                                                                                id="addformgender"
                                                                                                autocomplete="off">
                                                                                                <option value="">Select
                                                                                                </option>
                                                                                                <option value="Male">Male
                                                                                                </option>
                                                                                                <option value="Female">
                                                                                                    Female
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <div class="form-group">
                                                                                            <label for="dob">Date Of
                                                                                                Birth</label>
                                                                                            <input type="text" name="dob"
                                                                                                id="birth_date"
                                                                                                placeholder=""
                                                                                                class="form-control date patient_dob">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-5" id="calculate">
                                                                                        <div class="form-group">
                                                                                            <label>Age (yy-mm-dd)
                                                                                            </label><small class="req">
                                                                                                *</small>
                                                                                            <div
                                                                                                style="clear: both;overflow: hidden;">
                                                                                                <input type="text"
                                                                                                    placeholder="Year"
                                                                                                    name="age[year]"
                                                                                                    id="age_year" value=""
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
                                                                                                    name="age[day]" value=""
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
                                                                                            <label>Blood Group</label>
                                                                                            <select name="blood_group"
                                                                                                class="form-select">
                                                                                                <option value="">Select
                                                                                                </option>
                                                                                                <option value="1">O+
                                                                                                </option>
                                                                                                <option value="2">A+
                                                                                                </option>
                                                                                                <option value="3">B+
                                                                                                </option>
                                                                                                <option value="4">AB+
                                                                                                </option>
                                                                                                <option value="5">O-
                                                                                                </option>
                                                                                                <option value="6">AB-
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-3">
                                                                                        <div class="form-group">
                                                                                            <label for="pwd">Marital
                                                                                                Status</label>
                                                                                            <select name="marital_status"
                                                                                                class="form-select"
                                                                                                autocomplete="off">
                                                                                                <option value="">Select
                                                                                                </option>
                                                                                                <option value="Single">
                                                                                                    Single
                                                                                                </option>
                                                                                                <option value="Married">
                                                                                                    Married
                                                                                                </option>
                                                                                                <option value="Widowed">
                                                                                                    Widowed
                                                                                                </option>
                                                                                                <option value="Separated">
                                                                                                    Separated</option>
                                                                                                <option
                                                                                                    value="Not Specified">
                                                                                                    Not Specified
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6">
                                                                                        <div class="form-group">
                                                                                            <label for="exampleInputFile">
                                                                                                Patient Photo </label>
                                                                                            <div>
                                                                                                <!-- <div class="dropify-wrapper"
                                                                                                                style="height: 27.6px;">
                                                                                                                <div class="dropify-message">
                                                                                                                    <p><i
                                                                                                                            class="fa fa-cloud-upload dropi18"></i>Drop
                                                                                                                        a file here or click</p>
                                                                                                                    <p class="dropify-error">Ooops,
                                                                                                                        something wrong appended.</p>
                                                                                                                </div>
                                                                                                                <div class="dropify-loader"
                                                                                                                    style="display: none;"></div>
                                                                                                                <div class="dropify-errors-container">
                                                                                                                    <ul></ul>
                                                                                                                </div><input
                                                                                                                    class="filestyle form-control"
                                                                                                                    type="file" name="file" id="file"
                                                                                                                    size="20" data-height="26"><button
                                                                                                                    type="button"
                                                                                                                    class="dropify-clear">Remove</button>
                                                                                                                <div class="dropify-preview"
                                                                                                                    style="display: none;"><span
                                                                                                                        class="dropify-render"></span>
                                                                                                                    <div class="dropify-infos">
                                                                                                                        <div
                                                                                                                            class="dropify-infos-inner">
                                                                                                                            <p class="dropify-filename">
                                                                                                                                <span
                                                                                                                                    class="file-icon"></span>
                                                                                                                                <span
                                                                                                                                    class="dropify-filename-inner"></span>
                                                                                                                            </p>
                                                                                                                            <p
                                                                                                                                class="dropify-infos-message">
                                                                                                                                Drag and drop or click
                                                                                                                                to replace</p>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div> -->
                                                                                                <div
                                                                                                    class="d-flex border rounded position-relative p-1 mb-3 text-center align-items-center">
                                                                                                    <span
                                                                                                        class="avatar avatar-sm bg-primary text-white me-2">
                                                                                                        <i
                                                                                                            class="ti ti-upload fs-16"></i>
                                                                                                    </span>
                                                                                                    <p class="mb-0">Drop
                                                                                                        files
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
                                                                                    <label for="pwd">Phone</label>
                                                                                    <input id="number" autocomplete="off"
                                                                                        name="mobileno" type="text"
                                                                                        placeholder="" class="form-control"
                                                                                        value="">
                                                                                    <span class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <div class="form-group">
                                                                                    <label>Email</label>
                                                                                    <input type="text" placeholder=""
                                                                                        id="addformemail" value=""
                                                                                        name="email" class="form-control">
                                                                                    <span class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label for="address">Address</label>
                                                                                    <input name="address" placeholder=""
                                                                                        class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label for="pwd">Remarks</label>
                                                                                    <textarea name="note" id="note"
                                                                                        class="form-control"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group">
                                                                                    <label for="email">Any Known
                                                                                        Allergies</label>
                                                                                    <textarea name="known_allergies" id=""
                                                                                        placeholder=""
                                                                                        class="form-control"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="tpa">TPA</label>
                                                                                    <select class="form-select"
                                                                                        name="organisation_id">
                                                                                        <option value="">Select</option>
                                                                                        <option value="5">MedoLogi TPA Pvt.
                                                                                            Ltd.
                                                                                        </option>
                                                                                        <option value="4">Vidal Health TPA
                                                                                        </option>
                                                                                        <option value="3">Paramount Health
                                                                                            Services
                                                                                        </option>
                                                                                        <option value="2">Raksha TPA Pvt.
                                                                                            Ltd.
                                                                                        </option>
                                                                                        <option value="1">MediAssist TPA
                                                                                            Pvt.
                                                                                            Ltd.</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="insurance_id">TPA ID</label>
                                                                                    <input name="insurance_id"
                                                                                        placeholder="" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="validity">TPA
                                                                                        Validity</label>
                                                                                    <input name="validity" placeholder=""
                                                                                        class="form-control date">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label>National Identification
                                                                                        Number</label>
                                                                                    <input name="identification_number"
                                                                                        placeholder="" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="height">Height</label>
                                                                                    <input type="text" id="height"
                                                                                        name="height" class="form-control"
                                                                                        placeholder="Height (cm)" value="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="weight">Weight</label>
                                                                                    <input type="text" id="weight"
                                                                                        name="weight" class="form-control"
                                                                                        placeholder="Weight (kg)" value="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="temperature">Temperature</label>
                                                                                    <input type="text" id="temperature"
                                                                                        name="temperature"
                                                                                        class="form-control"
                                                                                        placeholder="Temperature (°C)"
                                                                                        value="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="screen_tb">Screen TB</label>
                                                                                    <select name="screen_tb" id="screen_tb"
                                                                                        class="form-select">
                                                                                        <option value="">Select</option>
                                                                                        <option value="Yes">Yes</option>
                                                                                        <option value="No">No</option>
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
                                                            <th>Patient Name</th>
                                                            <th>Appointment No</th>
                                                            <th>Appointment Date</th>
                                                            <th>Phone</th>
                                                            <th>Gender</th>
                                                            <th>Doctor</th>
                                                            <th>Source</th>
                                                            <th>Priority</th>
                                                            <th>Live Consultant</th>
                                                            <th>Discount</th>
                                                            <th>Fees(INR)</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <h6 class="fs-14 mb-1"><a href="doctor-details.html"
                                                                        class="fw-semibold">Virat Kohli (13)</a></h6>
                                                            </td>

                                                            <td>APPN2</td>
                                                            <td>07/02/2025 07:30 PM </td>
                                                            <td></td>
                                                            <td>Male</td>
                                                            <td>Anjali Rao (D011)</td>
                                                            <td>Offline</td>
                                                            <td>Normal</td>
                                                            <td>No</td>
                                                            <td>0.00</td>
                                                            <td>150.00</td>
                                                            <td><span
                                                                    class="badge fs-13 py-1 badge-soft-success border border-success rounded text-success fw-medium">Approved</span>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                        <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                            title="Show"></i></a>
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-warning rounded-pill">
                                                                        <i class="ti ti-file-description"
                                                                            data-bs-toggle="tooltip" title="Print"></i></a>
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                        <i class="ti ti-calendar-time"
                                                                            data-bs-toggle="tooltip"
                                                                            title="Reschedule"></i></a>
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
            <!-- row end -->
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                // Re-initialize Select2 every time the modal is shown
                $('#add_appointment').on('shown.bs.modal', function () {
                    $('#appointment-type').select2({
                        width: '100%',
                        placeholder: 'Enter Patient Name or Id…',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                // Re-initialize Select2 every time the modal is shown
                $('#add_appointment').on('shown.bs.modal', function () {
                    $('#doctor').select2({
                        width: '100%',
                        placeholder: 'Select',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                // Re-initialize Select2 every time the modal is shown
                $('#add_appointment').on('shown.bs.modal', function () {
                    $('#shift').select2({
                        width: '100%',
                        placeholder: 'Select',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                // Re-initialize Select2 every time the modal is shown
                $('#add_appointment').on('shown.bs.modal', function () {
                    $('#appointment_priority').select2({
                        width: '100%',
                        placeholder: 'Normal',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
@endsection