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
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                                <div>
                                    <h4 class="fw-bold mb-0">Appointment Details</h4>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <div class="text-end d-flex">
                                        <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                            data-bs-toggle="modal" data-bs-target="#add_appointment"><i
                                                class="ti ti-plus me-1"></i>Add
                                            Appointment</a>
                                    </div>
                                    <!-- First Modal -->
                                    <div class="modal fade" id="add_appointment" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('appointments.store') }}">
                                                    @csrf
                                                    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <div class="row w-100 align-items-center">
                                                            <div class="col-md-7">
                                                                <select class="form-select" id="patient_id" name="patient_id"
                                                                    data-placeholder="Enter Patient Name or Id…">
                                                                    <option value="">Select Patient</option>
                                                                    @foreach($patients as $patient)
                                                                        <option value="{{ $patient->id }}" >
                                                                            {{ $patient->patient_name }}
                                                                        </option>
                                                                    @endforeach
                                                                
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                                                                    data-bs-target="#new_patient">
                                                                    <i class="ti ti-plus me-1"></i>New Patient
                                                                </a>
                                                            </div>
                                                            <div class="col-md-1 text-end">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row align-items-center gy-3">
                                                            <div class="col-md-3">
                                                                <label for="doctor" class="form-label">Doctor <span class="text-danger">*</span></label>
                                                                <select class="form-select js-example-basic-single" id="doctor" name="doctor" required>
                                                                    <option value="">Select Doctor</option>
                                                                    @foreach($doctors as $doctor)
                                                                        <option value="{{ $doctor->id }}" >
                                                                            {{ $doctor->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="doctor_fees" class="form-label">Doctor Fees (INR)</label>
                                                                <input type="text" name="doctor_fees" id="doctor_fees" class="form-control">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="shift" class="form-label">Shift <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="shift" name="shift" required>
                                                                    <option value="">Select Shift</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="datetimepicker" class="form-label">Appointment Date</label>
                                                                <input type="date" id="datetimepicker" name="appointment_date" class="form-control" required>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="slot" class="form-label fw-bold">Slot</label>
                                                                <select id="slot" name="slot" class="form-select">
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="appointment_priority" class="form-label">Appointment Priority</label>
                                                                <select class="form-select" id="appointment_priority" name="appointment_priority">
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="payment_method" class="form-label">Payment Method</label>
                                                                <select class="form-select" id="payment_method" name="payment_method">
                                                                    <option value="">Select</option>
                                                                    <option value="cash">Cash</option>
                                                                    <option value="card">Card</option>
                                                                    <option value="upi">UPI</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="status" class="form-label">Status</label>
                                                                <select class="form-select" id="status" name="status">
                                                                    <option value="pending">Pending</option>
                                                                    <option value="confirmed">Confirmed</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="discount_percentage" class="form-label">Discount Percentage</label>
                                                                <input type="text" id="discount_percentage" name="discount_percentage" class="form-control">
                                                            </div>

                                                            <div class="col-md-9">
                                                                <label for="message" class="form-label">Message</label>
                                                                <textarea name="message" id="message" class="form-control"></textarea>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <label for="live_con" class="form-label">Live Consultant (On Video Conference)</label>
                                                                <select class="form-select" id="live_con" name="live_con">
                                                                    <option value="">Select</option>
                                                                    <option value="no">No</option>
                                                                    <option value="yes">Yes</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save & Print</button>
                                                        <button type="submit" class="btn btn-secondary">Save</button>
                                                    </div>
                                                </form>
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
                                                                                                    Female</option>
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

                                                                                <input type="text" id="age_month"
                                                                                    placeholder="Month" name="age[month]"
                                                                                    value=""
                                                                                    class="form-control patient_age_month"
                                                                                    style="width: 36%;float: left; margin-left: 4px;">
                                                                                <input type="text" id="age_day"
                                                                                    placeholder="Day" name="age[day]"
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
                                                                            <label>Blood Group</label>
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
                                                                            <label for="pwd">Marital Status</label>
                                                                            <select name="marital_status"
                                                                                class="form-select" autocomplete="off">
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
                                                                    <label for="pwd">Phone</label>
                                                                    <input id="number" autocomplete="off" name="mobileno"
                                                                        type="text" placeholder="" class="form-control"
                                                                        value="">
                                                                    <span class="text-danger"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="text" placeholder="" id="addformemail"
                                                                        value="" name="email" class="form-control">
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
                                                                    <label for="email">Any Known Allergies</label>
                                                                    <textarea name="known_allergies" id="" placeholder=""
                                                                        class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="tpa">TPA</label>
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
                                                                    <label for="insurance_id">TPA ID</label>
                                                                    <input name="insurance_id" placeholder=""
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="validity">TPA Validity</label>
                                                                    <input name="validity" placeholder=""
                                                                        class="form-control date">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label>National Identification Number</label>
                                                                    <input name="identification_number" placeholder=""
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="height">Height</label>
                                                                    <input type="text" id="height" name="height"
                                                                        class="form-control" placeholder="Height (cm)"
                                                                        value="">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="weight">Weight</label>
                                                                    <input type="text" id="weight" name="weight"
                                                                        class="form-control" placeholder="Weight (kg)"
                                                                        value="">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="temperature">Temperature</label>
                                                                    <input type="text" id="temperature" name="temperature"
                                                                        class="form-control" placeholder="Temperature (°C)"
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
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('appointments.doctor-wise') }}"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Doctor Wise</a>
                                    <a href="{{ route('appointments.queue') }}"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Queue</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
                                        @forelse($appointments as $appointment)
                                        <tr>
                                            <td>
                                                <h6 class="fs-14 mb-1">
                                                    <a href="#" class="fw-semibold">
                                                        {{ $appointment->patient->patient_name ?? 'N/A' }} ({{ $appointment->patient_id }})
                                                    </a>
                                                </h6>
                                            </td>

                                            <td>{{ $appointment->appointment_id ?? 'N/A' }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                                <!-- {{ $appointment->slot->start_time ?? '' }} -->
                                            </td>

                                            <td>{{ $appointment->patient->contact_no ?? '-' }}</td>

                                            <td>{{ ucfirst($appointment->patient->gender ?? 'N/A') }}</td>

                                            <td>{{ $appointment->doctorUser->name ?? 'N/A' }} ({{ $appointment->doctorUser->doctor_id ?? '' }})</td>

                                            <td>{{ ucfirst($appointment->source ?? 'Offline') }}</td>

                                            <td>{{ $appointment->priority }}</td>

                                            <td>{{ ucfirst($appointment->live_consult ?? 'No') }}</td>

                                            <td>{{ number_format($appointment->discount_percentage ?? 0, 2) }}</td>

                                            <td>{{ number_format($appointment->amount ?? 0, 2) }}</td>

                                            <td>
                                                @if($appointment->appointment_status === 'confirmed')
                                                    <span class="badge fs-13 py-1 badge-soft-success border border-success rounded text-success fw-medium">Confirmed</span>
                                                @elseif($appointment->appointment_status === 'pending')
                                                    <span class="badge fs-13 py-1 badge-soft-warning border border-warning rounded text-warning fw-medium">Pending</span>
                                                @elseif($appointment->appointment_status === 'rescheduled')
                                                    <span class="badge fs-13 py-1 badge-soft-secondary border border-secondary rounded text-secondary fw-medium">Rescheduled</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="d-flex">
                                                    <a href="#"
                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                    data-bs-toggle="tooltip" title="Show">
                                                        <i class="ti ti-menu"></i>
                                                    </a>

                                                    <a href="#"
                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-warning rounded-pill"
                                                    data-bs-toggle="tooltip" title="Print">
                                                        <i class="ti ti-file-description"></i>
                                                    </a>

                                                    <!-- <a href="javascript:void(0);"
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill reschedule-btn"
                                                data-bs-toggle="tooltip" 
                                                title="Reschedule"
                                                data-id="{{ $appointment->id }}"
                                                data-patient="{{ $appointment->patient->patient_name ?? '' }}"
                                                data-patientid="{{ $appointment->patient_id }}"
                                                data-doctor="{{ $appointment->doctor->name ?? '' }}"
                                                data-doctorid="{{ $appointment->doctor_id }}"
                                                data-fees="{{ $appointment->doctor_fees ?? '' }}"
                                                data-date="{{ $appointment->appointment_date }}"
                                                data-shift="{{ $appointment->shift_id }}"
                                                data-slot="{{ $appointment->slot_id }}"
                                                data-priority="{{ $appointment->appointment_priority }}"
                                                data-status="{{ $appointment->status }}"
                                                data-payment="{{ $appointment->payment_method }}"
                                                data-discount="{{ $appointment->discount_percentage }}"
                                                data-message="{{ $appointment->message }}"
                                                data-live="{{ $appointment->live_con }}">
                                                    <i class="ti ti-calendar-time"></i>
                                                </a> -->

                                                <a href="javascript:void(0);" 
                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill rescheduleBtn" 
                                                    data-id="{{ $appointment->id }}" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Reschedule">
                                                        <i class="ti ti-calendar-time"></i>
                                                </a>


                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center text-muted">No appointments found.</td>
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
            <!-- row end -->
        </div>
         <!-- Edit Modal (nested) -->
        <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form id="rescheduleForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                            <h5 class="mb-0 text-dark fw-bold">Reschedule Appointment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row gy-3">
                                {{-- Patient Name --}}
                                <div class="col-md-3">
                                    <label>Patient</label>
                                    <input type="text" id="reschedule_patient" class="form-control" readonly>
                                </div>

                                {{-- Doctor --}}
                                <div class="col-md-3">
                                    <label>Doctor</label>
                                    <input type="text" id="reschedule_doctor" class="form-control" readonly>
                                </div>

                                {{-- Fees --}}
                                <div class="col-md-3">
                                    <label>Doctor Fees (INR)</label>
                                    <input type="text" id="reschedule_fees" class="form-control" readonly>
                                </div>

                                {{-- Shift --}}
                                <div class="col-md-3">
                                    <label>Shift</label>
                                    <select id="reschedule_shift" name="shift" class="form-select" required></select>
                                </div>

                                {{-- Date --}}
                                <div class="col-md-3">
                                    <label>Date</label>
                                    <input type="date" id="reschedule_date" name="appointment_date" class="form-control" required>
                                </div>

                                {{-- Slot --}}
                                <div class="col-md-3">
                                    <label>Slot</label>
                                    <select id="reschedule_slot" name="slot" class="form-select" required></select>
                                </div>
                                {{-- Status --}}
                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select id="reschedule_status" name="status" class="form-select">
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="rescheduled">Rescheduled</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>
        <script>

            document.querySelectorAll('.rescheduleBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;

                    fetch(`{{ url('appointment-details/appointments') }}/${id}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            const appointment = data.appointment;

                            // Set form action dynamically
                            const form = document.getElementById('rescheduleForm');
                            form.action = `{{ url('appointment-details/appointments') }}/${appointment.id}`;

                            // Fill modal fields
                            document.getElementById('reschedule_patient').value = appointment.patient.patient_name;
                            document.getElementById('reschedule_doctor').value = appointment.doctor_user.name;
                            document.getElementById('reschedule_fees').value = appointment.amount;
                            

                            // Set date (extract yyyy-mm-dd)
                            document.getElementById('reschedule_date').value = appointment.date.split('T')[0];

                            // Populate shifts
                            const shiftSelect = document.getElementById('reschedule_shift');
                            shiftSelect.innerHTML = '';
                            data.shifts.forEach(shift => {
                                const option = document.createElement('option');
                                option.value = shift.id;
                                option.text = shift.global_shift?.name || 'N/A';
                                if (appointment.doctor_global_shift_id == shift.global_shift_id) {
                                    option.selected = true;
                                }
                                shiftSelect.appendChild(option);
                            });

                            // Populate slots
                            const slotSelect = document.getElementById('reschedule_slot');
                            slotSelect.innerHTML = '';
                            data.slots.forEach(slot => {
                                const option = document.createElement('option');
                                option.value = slot.id;
                                option.text = `${slot.start_time} - ${slot.end_time}`;
                                if (appointment.doctor_shift_time_id == slot.id) {
                                    option.selected = true;
                                }
                                slotSelect.appendChild(option);
                            });
                            // Populate status
                            const statusSelect = document.getElementById('reschedule_status');
                            statusSelect.value = appointment.appointment_status;

                            // Show modal
                            const rescheduleModal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
                            rescheduleModal.show();
                        })
                        .catch(error => {
                            console.error('Error fetching appointment:', error);
                        });
                });
            });

        </script>


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
                        placeholder: 'Select',
                        allowClear: true,
                        dropdownParent: $('#add_appointment')

                    });
                });
            });
        </script>
        
        <script>
            $(document).ready(function () {

                // Fetch shifts based on doctor
                $('#doctor').change(function() {
                    let doctorId = $(this).val();

                    if (!doctorId) {
                        $('#shift').html('<option value="">Select</option>');
                        $('#slot').html('<option value="">Select</option>');
                        return;
                    }

                    $.ajax({
                        url: '{{ route("doctor.shifts", ":doctorId") }}'.replace(':doctorId', doctorId),
                        type: 'GET',
                        success: function(response) {
                            let options = '<option value="">Select Shift</option>';
                            response.shifts.forEach(function(shift) {
                                options += `<option value="${shift.id}">${shift.name}</option>`;
                            });
                            $('#shift').html(options);
                        },
                        error: function() {
                            alert('Could not fetch shifts!');
                        }
                    });
                });

                // Fetch slots based on doctor + shift
                $('#shift').change(function() {
                    let doctorId = $('#doctor').val();
                    console.log('doctor:', doctorId);
                    let shiftId = $(this).val();
                    console.log('doctor:', shiftId);
                    if (!shiftId || !doctorId) {
                        $('#slot').html('<option value="">Select</option>');
                        return;
                    }

                    $.ajax({
                        url: '{{ route("doctor.slots", [":doctorId", ":shiftId"]) }}'
                            .replace(':doctorId', doctorId)
                            .replace(':shiftId', shiftId),
                        type: 'GET',
                        success: function(response) {
                            console.log('Slots Response:', response);
                            let options = '<option value="">Select Slot</option>';
                            response.slots.forEach(function(slot) {
                                options += `<option value="${slot.id}">${slot.day} (${slot.start_time} - ${slot.end_time})</option>`;
                            });
                            $('#slot').html(options);
                        },
                        error: function(xhr) {

                            console.error('Error:', xhr.responseText);
                            alert('Could not fetch slots!');
                        }
                    });
                });

                $('.js-example-basic-single').select2();
            });
        </script>
        <script>
$(document).ready(function() {
    // Fetch priorities dynamically when modal is opened
    $('#add_appointment').on('shown.bs.modal', function () {
        $.ajax({
            url: '{{ route("appointment.priorities") }}',
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select</option>';
                response.priorities.forEach(function(priority) {
                    options += `<option value="${priority.appoint_priority.toLowerCase()}">${priority.appoint_priority}</option>`;
                });
                $('#appointment_priority').html(options);
            },
            error: function(xhr) {
                console.error('Error fetching priorities:', xhr.responseText);
                alert('Could not load appointment priorities!');
            }
        });
    });
});
</script>


@endsection