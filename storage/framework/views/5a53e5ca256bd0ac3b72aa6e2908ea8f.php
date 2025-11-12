

<?php $__env->startSection('content'); ?>
    <!-- ========================
                                                                                                                                                                                                                                           Start Page Content
                                                                                                                                                                                                                                          ========================= -->

    

        <style>
            .modal-backdrop.show:nth-of-type(2) {
                z-index: 1060;
                /* higher backdrop for nested modal */
            }

            #new_patient {
                z-index: 1070;
                /* ensure new modal is above the first */
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
        <div class="content pb-0">
            <!-- row start -->
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                            <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>OPD Billing</h5>
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
                                                            Patient</a>
                                                    </div>
                                                    <!-- First Modal -->
                                                    <div class="modal fade" id="add_appointment" tabindex="-1"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-full-width">
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

                                                                    <div class="row gy-3">

                                                                        <div class="col-md-8 border-end">
                                                                            <div class="row gy-3">
                                                                                <div class="col-md-3">
                                                                                    <label for="symptoms_type"
                                                                                        class="form-label">Symptoms
                                                                                        Type</label>
                                                                                    <select class="form-select"
                                                                                        id="symptoms_type">
                                                                                        <option value="">Select</option>
                                                                                        <option value="">General</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <label for="symptoms_title"
                                                                                        class="form-label">Symptoms
                                                                                        Title </label>
                                                                                    <select class="form-select"
                                                                                        id="symptoms_title">
                                                                                        <option value="">Select</option>
                                                                                    </select>
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
                                                                                        class="form-label">Appointment Date
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="date"
                                                                                        name="appointment_date"
                                                                                        id="appointment_date"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="case"
                                                                                        class="form-label">Case</label>
                                                                                    <input type="text" name="case" id="case"
                                                                                        class="form-control">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="casualty"
                                                                                        class="form-label">Casualty</label>
                                                                                    <select class="form-select"
                                                                                        id="casualty">
                                                                                        <option value="">Select</option>
                                                                                        <option value="">No</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="old_patient"
                                                                                        class="form-label">Old
                                                                                        Patient</label>
                                                                                    <select class="form-select"
                                                                                        id="old_patient">
                                                                                        <option value="">Select</option>
                                                                                        <option value="">No</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="reference"
                                                                                        class="form-label">Reference</label>
                                                                                    <input type="text" name="reference"
                                                                                        id="reference" class="form-control">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="doctor"
                                                                                        class="form-label">Consultant
                                                                                        Doctor
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select class="form-select" id="doctor"
                                                                                        data-placeholder="Select">
                                                                                        <option value=""></option>
                                                                                        <option value="1">Amitabh Kulkarni
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-check d-flex gap-2">
                                                                                        <input type="checkbox"
                                                                                            name="tpa_check" id="tpa_check"
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
                                                                                        <option value="1">OPD Doctor Fees
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="charge"
                                                                                        class="form-label">Charge
                                                                                        <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <select class="form-select" id="charge"
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
                                                                                            value="0" autocomplete="off">
                                                                                        <span class="input-group-addon ">
                                                                                            %</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="tax"
                                                                                        class="form-label">Tax</label>
                                                                                    <div class="input-group">
                                                                                        <input type="text"
                                                                                            class="form-control discount_percentage"
                                                                                            name="tax" id="tax" value="0"
                                                                                            autocomplete="off"
                                                                                            readonly="true" disabled>
                                                                                        <span class="input-group-addon ">
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
                                                                                        <option value="">Select</option>
                                                                                        <option value="1">Case</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label for="paid_amount"
                                                                                        class="form-label">Paid
                                                                                        Amount
                                                                                        (INR) <span
                                                                                            class="text-danger">*</span></label>
                                                                                    <input type="text" name="paid_amount"
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
                                                                                        <option value="">Select</option>
                                                                                        <option value="1">No</option>
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
                                                                                    <label class="form-label">Guardian
                                                                                        Name</label>
                                                                                    <input type="text" name="guardian_name"
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
                                                                                                <option value="">Select
                                                                                                </option>
                                                                                                <option value="Male">Male
                                                                                                </option>
                                                                                                <option value="Female">
                                                                                                    Female</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-4">
                                                                                        <div class="form-group">
                                                                                            <label for="dob"
                                                                                                class="form-label">Date Of
                                                                                                Birth</label>
                                                                                            <input type="text" name="dob"
                                                                                                id="birth_date"
                                                                                                placeholder=""
                                                                                                class="form-control date patient_dob">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-5" id="calculate">
                                                                                        <div class="form-group">
                                                                                            <label class="form-label">Age
                                                                                                (yy-mm-dd)
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
                                                                                            <label class="form-label">Blood
                                                                                                Group</label>
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
                                                                                            <label for="pwd"
                                                                                                class="form-label">Marital
                                                                                                Status</label>
                                                                                            <select name="marital_status"
                                                                                                class="form-select"
                                                                                                autocomplete="off">
                                                                                                <option value="">Select
                                                                                                </option>
                                                                                                <option value="Single">
                                                                                                    Single</option>
                                                                                                <option value="Married">
                                                                                                    Married</option>
                                                                                                <option value="Widowed">
                                                                                                    Widowed</option>
                                                                                                <option value="Separated">
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
                                                                                            <label for="exampleInputFile"
                                                                                                class="form-label">
                                                                                                Patient Photo </label>
                                                                                            <div>
                                                                                                <div
                                                                                                    class="d-flex border rounded position-relative p-1 mb-3 text-center align-items-center">
                                                                                                    <span
                                                                                                        class="avatar avatar-sm bg-primary text-white me-2">
                                                                                                        <i
                                                                                                            class="ti ti-upload fs-16"></i>
                                                                                                    </span>
                                                                                                    <p class="mb-0">Drop
                                                                                                        files here</p>
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
                                                                                    <input id="number" autocomplete="off"
                                                                                        name="mobileno" type="text"
                                                                                        placeholder="" class="form-control"
                                                                                        value="">
                                                                                    <span class="text-danger"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Email</label>
                                                                                    <input type="text" placeholder=""
                                                                                        id="addformemail" value=""
                                                                                        name="email" class="form-control">
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
                                                                                    <textarea name="known_allergies" id=""
                                                                                        placeholder=""
                                                                                        class="form-control"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="tpa"
                                                                                        class="form-label">TPA</label>
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
                                                                                            Ltd. </option>
                                                                                        <option value="1">MediAssist TPA
                                                                                            Pvt. Ltd.
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
                                                                                        placeholder="" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="validity"
                                                                                        class="form-label">TPA
                                                                                        Validity</label>
                                                                                    <input name="validity" placeholder=""
                                                                                        class="form-control date">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">National
                                                                                        Identification
                                                                                        Number</label>
                                                                                    <input name="identification_number"
                                                                                        placeholder="" class="form-control">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="height"
                                                                                        class="form-label">Height</label>
                                                                                    <input type="text" id="height"
                                                                                        name="height" class="form-control"
                                                                                        placeholder="Height (cm)" value="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label for="weight"
                                                                                        class="form-label">Weight</label>
                                                                                    <input type="text" id="weight"
                                                                                        name="weight" class="form-control"
                                                                                        placeholder="Weight (kg)" value="">
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
                                                <table class="table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Patient ID</th>
                                                            <th>Guardian Name</th>
                                                            <th>Gender</th>
                                                            <th>Phone</th>
                                                            <th>Consultant Doctor</th>
                                                            <th>Last Visit</th>
                                                            <th>Last Visit</th>
                                                            <th>Is Antenatal</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <h6 class="fs-14 mb-1"><a href="patient-profile"
                                                                        class="fw-semibold">Shreya
                                                                        Bhattacharyya</a></h6>
                                                            </td>

                                                            <td>29</td>
                                                            <td></td>
                                                            <td>Female</td>
                                                            <td>8910245678</td>
                                                            <td>Amitabh Kulkarni (D007)</td>
                                                            <td>10/28/2025 12:56 PM</td>
                                                            <td>3</td>
                                                            <td>No</td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                        <i class="ti ti-menu" data-bs-toggle="tooltip"
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
                    $('#charge_category').select2({
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
                    $('#charge').select2({
                        width: '100%',
                        placeholder: 'Select',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/billing/opd.blade.php ENDPATH**/ ?>