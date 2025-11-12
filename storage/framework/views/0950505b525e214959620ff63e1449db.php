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

        
    </style>

    <!-- Start Content -->
    <div class="content">

        <!-- page header start -->
        <div class="mb-4">
            <h6 class="fw-bold mb-0 d-flex align-items-center"> <a href="patients.html" class="text-dark"> <i
                        class="ti ti-chevron-left me-1"></i>Patients</a></h6>
        </div>
        <!-- page header end -->

        <!-- card start -->
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-sm-flex align-items-center position-relative z-0 overflow-hidden p-2">
                        <img src="assets/img/icons/shape-01.svg" alt="img"
                            class="z-n1 position-absolute end-0 top-0 d-none d-lg-flex">
                        <a href="javascript:void(0);" class="avatar avatar-xxxl patient-avatar me-2 flex-shrink-0">
                            <img src="assets/img/patient.png" alt="product" class="rounded">
                        </a>
                        <div>
                            <p class="text-primary mb-1">29</p>
                            <h5 class="mb-1"><a href="javascript:void(0);" class="fw-bold">Shreya Bhattacharyya</a></h5>
                            <p class="mb-3">abs.shrey@gmail.com</p>
                            <div class="d-flex align-items-center flex-wrap">
                                <p class="mb-0 d-inline-flex align-items-center"><i
                                        class="ti ti-phone me-1 text-dark"></i>Phone :
                                    <span class="text-dark ms-1">8910245678</span>
                                </p>
                                <span class="mx-2 text-light">|</span>
                                <p class="mb-0 d-inline-flex align-items-center"><i
                                        class="ti ti-map-pin-check me-1 text-dark"></i>Address : <span
                                        class="text-dark ms-1">m,nm,nm,nm,</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="ti ti-calendar-event text-body fs-16"></i></span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1">Age</h6>
                                        <p class="mb-0">22 Year 9 Month 5 Days (As Of Date 10/06/2025)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="ti ti-droplet text-body fs-16"></i></span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1">Guardian</h6>
                                        <p class="mb-0">--</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="ti ti-gender-male text-body fs-16"></i></span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1">Gender</h6>
                                        <p class="mb-0">Female</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                            class="ti ti-mail text-body fs-16"></i></span>
                                    <div>
                                        <h6 class="fs-13 fw-bold mb-1">Marital Status</h6>
                                        <p class="mb-0 text-break">--
                                        </p>
                                    </div>
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
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                            <div>
                                <h4 class="fw-bold mb-0">Visits</h4>
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="text-end d-flex">
                                    <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                        data-bs-toggle="modal" data-bs-target="#add_appointment"><i
                                            class="ti ti-plus me-1"></i>New Visit</a>
                                </div>
                                <!-- First Modal -->
                                <div class="modal fade" id="add_appointment" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-full-width">
                                        <div class="modal-content">

                                            <div class="modal-header"
                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                <h5 class="modal-title" id="addSpecializationLabel">Patient Details
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                            </div>

                                            <div class="modal-body">

                                                <div class="row gy-3">

                                                    <div class="col-md-8 border-end">
                                                        <div class="row gy-3">
                                                            <div class="col-md-10">
                                                                <h3>
                                                                    Shreya Bhattacharyya (29)
                                                                </h3>
                                                                <p>Guardian : --</p>
                                                                <p>Gender : Female</p>
                                                                <p>Blood Group : </p>
                                                                <p>Marital Status: </p>
                                                                <p>Age: 22 Year 9 Month 5 Days (As Of Date 10/06/2025)</p>
                                                                <p>Phone: 8910245678</p>
                                                                <p>Email: abs.shrey@gmail.com</p>
                                                                <p>Address: m,nm,nm,nm,</p>
                                                                <p>Any Known Allergies: No</p>
                                                                <p>Remarks: m,nmnmn</p>
                                                                <p>TPA : MedoLogi TPA Pvt. Ltd.</p>
                                                                <p>TPA ID : TPA005</p>
                                                                <p>TPA Validity : 07/22/2021</p>
                                                                <p>National Identification Number : --</p>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <img src="assets/img/patient.png" alt="product"
                                                                    class="rounded thumbnail">
                                                            </div>
                                                            <hr>
                                                            <div class="col-md-3">
                                                                <label for="symptoms_type" class="form-label">Symptoms
                                                                    Type</label>
                                                                <select class="form-select" id="symptoms_type">
                                                                    <option value="">Select</option>
                                                                    <option value="">General</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="symptoms_title" class="form-label">Symptoms
                                                                    Title </label>
                                                                <select class="form-select" id="symptoms_title">
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="symptoms_des" class="form-label">Symptoms
                                                                    Description</label>
                                                                <textarea name="symptoms_des" id="symptoms_des"
                                                                    class="form-control"></textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="note" class="form-label">Note</label>
                                                                <textarea name="note" id="note"
                                                                    class="form-control"></textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="allergies" class="form-label">Any Known
                                                                    Allergies</label>
                                                                <textarea name="allergies" id="allergies"
                                                                    class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="row gy-3">

                                                            <div class="col-md-6">
                                                                <label for="appointment_date" class="form-label">Appointment
                                                                    Date <span class="text-danger">*</span></label>
                                                                <input type="date" name="appointment_date"
                                                                    id="appointment_date" class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="case" class="form-label">Case</label>
                                                                <input type="text" name="case" id="case"
                                                                    class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="casualty" class="form-label">Casualty</label>
                                                                <select class="form-select" id="casualty">
                                                                    <option value="">Select</option>
                                                                    <option value="">No</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="old_patient" class="form-label">Old
                                                                    Patient</label>
                                                                <select class="form-select" id="old_patient">
                                                                    <option value="">Select</option>
                                                                    <option value="">No</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="reference" class="form-label">Reference</label>
                                                                <input type="text" name="reference" id="reference"
                                                                    class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="doctor" class="form-label">Consultant Doctor
                                                                    <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="doctor"
                                                                    data-placeholder="Select">
                                                                    <option value=""></option>
                                                                    <option value="1">Amitabh Kulkarni</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check d-flex gap-2 mt-4">
                                                                    <input type="checkbox" name="tpa_check" id="tpa_check"
                                                                        class="form-check-input">
                                                                    <label for="tpa_check" class="form-check-label">Apply
                                                                        TPA</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="charge_category" class="form-label">Charge
                                                                    Category
                                                                </label>
                                                                <select class="form-select" id="charge_category"
                                                                    data-placeholder="Select">
                                                                    <option value=""></option>
                                                                    <option value="1">OPD Doctor Fees</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="charge" class="form-label">Charge
                                                                    <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="charge"
                                                                    data-placeholder="Select">
                                                                    <option value=""></option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="standard_charge" class="form-label">Standard
                                                                    Charge (INR)</label>
                                                                <input type="text" readonly="true" name="standard_charge"
                                                                    id="standard_charge" class="form-control" value=""
                                                                    autocomplete="off" disabled>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="apply_charge" class="form-label">Applied
                                                                    Charge (INR) <span class="text-danger">*</span></label>
                                                                <input type="text" name="amount" id="apply_charge"
                                                                    class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="discount_percentage"
                                                                    class="form-label">Discount</label>
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control discount_percentage"
                                                                        name="discount_percentage" id="discount_percentage"
                                                                        value="0" autocomplete="off">
                                                                    <span class="input-group-addon "> %</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="tax" class="form-label">Tax</label>
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control discount_percentage" name="tax"
                                                                        id="tax" value="0" autocomplete="off"
                                                                        readonly="true" disabled>
                                                                    <span class="input-group-addon "> %</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="amount" class="form-label">Amount
                                                                    (INR) <span class="text-danger">*</span></label>
                                                                <input type="text" readonly="true" name="amount" id="amount"
                                                                    class="form-control" value="" autocomplete="off"
                                                                    disabled>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="payment_mode" class="form-label">Payment
                                                                    Mode</label>
                                                                <select class="form-select" id="payment_mode">
                                                                    <option value="">Select</option>
                                                                    <option value="1">Case</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="paid_amount" class="form-label">Paid Amount
                                                                    (INR) <span class="text-danger">*</span></label>
                                                                <input type="text" name="paid_amount" id="paid_amount"
                                                                    class="form-control" value="" autocomplete="off">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="live_con" class="form-label">Live
                                                                    Consultation</label>
                                                                <select class="form-select" id="live_con">
                                                                    <option value="">Select</option>
                                                                    <option value="1">No</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check d-flex gap-2 mt-5">
                                                                    <input type="checkbox" name="antenatal" id="antenatal"
                                                                        class="form-check-input">
                                                                    <label for="antenatal" class="form-check-label">Is Antenatal</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save & Print</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Second Modal (nested) -->
                                <div class="modal fade" id="new_patient" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">

                                            <div class="modal-header"
                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                <h5 class="modal-title">Add Patient</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <form>
                                                    <div class="row align-items-center gy-3">
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Name</label><span
                                                                    class="text-danger"> *</span>
                                                                <input id="name" name="name" placeholder="" type="text"
                                                                    class="form-control" value="" autocomplete="off">
                                                                <span class="text-danger"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Guardian Name</label>
                                                                <input type="text" name="guardian_name" placeholder=""
                                                                    value="" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="row">
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label class="form-label"> Gender</label>
                                                                        <select class="form-select" name="gender"
                                                                            id="addformgender" autocomplete="off">
                                                                            <option value="">Select</option>
                                                                            <option value="Male">Male</option>
                                                                            <option value="Female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="dob" class="form-label">Date Of
                                                                            Birth</label>
                                                                        <input type="text" name="dob" id="birth_date"
                                                                            placeholder=""
                                                                            class="form-control date patient_dob">
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-5" id="calculate">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Age (yy-mm-dd)
                                                                        </label><small class="req"> *</small>
                                                                        <div style="clear: both;overflow: hidden;">
                                                                            <input type="text" placeholder="Year"
                                                                                name="age[year]" id="age_year" value=""
                                                                                class="form-control patient_age_year"
                                                                                style="width: 30%; float: left;">

                                                                            <input type="text" id="age_month"
                                                                                placeholder="Month" name="age[month]"
                                                                                value=""
                                                                                class="form-control patient_age_month"
                                                                                style="width: 36%;float: left; margin-left: 4px;">
                                                                            <input type="text" id="age_day"
                                                                                placeholder="Day" name="age[day]" value=""
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
                                                                        <label class="form-label">Blood Group</label>
                                                                        <select name="blood_group" class="form-select">
                                                                            <option value="">Select</option>
                                                                            <option value="1">O+</option>
                                                                            <option value="2">A+</option>
                                                                            <option value="3">B+</option>
                                                                            <option value="4">AB+</option>
                                                                            <option value="5">O-</option>
                                                                            <option value="6">AB-</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <label for="pwd" class="form-label">Marital
                                                                            Status</label>
                                                                        <select name="marital_status" class="form-select"
                                                                            autocomplete="off">
                                                                            <option value="">Select</option>
                                                                            <option value="Single">Single</option>
                                                                            <option value="Married">Married</option>
                                                                            <option value="Widowed">Widowed</option>
                                                                            <option value="Separated">Separated</option>
                                                                            <option value="Not Specified">Not Specified
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label for="exampleInputFile" class="form-label">
                                                                            Patient Photo </label>
                                                                        <div>
                                                                            <div
                                                                                class="d-flex border rounded position-relative p-1 mb-3 text-center align-items-center">
                                                                                <span
                                                                                    class="avatar avatar-sm bg-primary text-white me-2">
                                                                                    <i class="ti ti-upload fs-16"></i>
                                                                                </span>
                                                                                <p class="mb-0">Drop files here</p>
                                                                                <input type="file"
                                                                                    class="position-absolute top-0 start-0 opacity-0 w-100 h-100">
                                                                            </div>
                                                                        </div>
                                                                        <span class="text-danger"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!--./col-md-6-->
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="pwd" class="form-label">Phone</label>
                                                                <input id="number" autocomplete="off" name="mobileno"
                                                                    type="text" placeholder="" class="form-control"
                                                                    value="">
                                                                <span class="text-danger"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label class="form-label">Email</label>
                                                                <input type="text" placeholder="" id="addformemail" value=""
                                                                    name="email" class="form-control">
                                                                <span class="text-danger"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="address" class="form-label">Address</label>
                                                                <input name="address" placeholder="" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="pwd" class="form-label">Remarks</label>
                                                                <textarea name="note" id="note"
                                                                    class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="email" class="form-label">Any Known
                                                                    Allergies</label>
                                                                <textarea name="known_allergies" id="" placeholder=""
                                                                    class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="tpa" class="form-label">TPA</label>
                                                                <select class="form-select" name="organisation_id">
                                                                    <option value="">Select</option>
                                                                    <option value="5">MedoLogi TPA Pvt. Ltd.</option>
                                                                    <option value="4">Vidal Health TPA </option>
                                                                    <option value="3">Paramount Health Services
                                                                    </option>
                                                                    <option value="2">Raksha TPA Pvt. Ltd. </option>
                                                                    <option value="1">MediAssist TPA Pvt. Ltd.</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="insurance_id" class="form-label">TPA
                                                                    ID</label>
                                                                <input name="insurance_id" placeholder=""
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="validity" class="form-label">TPA
                                                                    Validity</label>
                                                                <input name="validity" placeholder=""
                                                                    class="form-control date">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="form-label">National Identification
                                                                    Number</label>
                                                                <input name="identification_number" placeholder=""
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="height" class="form-label">Height</label>
                                                                <input type="text" id="height" name="height"
                                                                    class="form-control" placeholder="Height (cm)" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="weight" class="form-label">Weight</label>
                                                                <input type="text" id="weight" name="weight"
                                                                    class="form-control" placeholder="Weight (kg)" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="temperature"
                                                                    class="form-label">Temperature</label>
                                                                <input type="text" id="temperature" name="temperature"
                                                                    class="form-control" placeholder="Temperature (°C)"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="screen_tb" class="form-label">Screen
                                                                    TB</label>
                                                                <select name="screen_tb" id="screen_tb" class="form-select">
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
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                        <th>Consultant Doctor</th>
                                        <th>Reference</th>
                                        <th>Symptoms</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h6 class="fs-14 mb-1"><a href="#" class="fw-semibold">OPDN25</a></h6>
                                        </td>
                                        <td>30</td>
                                        <td>10/21/2025 01:44 PM</td>
                                        <td>Anirban Ghosh (D010)</td>
                                        <td></td>
                                        <td>Fever</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="javascript: void(0);"
                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                    <i class="ti ti-menu" data-bs-toggle="tooltip" title="Show"></i></a>
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
        <!-- row end -->
    </div>
    <!-- row end -->

    </div>
    <!-- End Content -->

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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/patient_profile.blade.php ENDPATH**/ ?>