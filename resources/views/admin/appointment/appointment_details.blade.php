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

                                                <div class="modal-header"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                                                    <div class="d-flex w-100 align-items-center gap-3">
                                                        <div class="col-md-8">
                                                            <select class="form-select" id="appointment-type" data-placeholder="Enter Patient Name or Id…">
                                                                <option value=""></option>
                                                                <option>Patient 1</option>
                                                                <option>Patient 2</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <!-- Button to open nested modal -->
                                                            <a href="javascript:void(0);" class="btn btn-primary"
                                                                data-bs-toggle="modal" data-bs-target="#new_patient">
                                                                <i class="ti ti-plus me-1"></i>New Patient
                                                            </a>
                                                        </div>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>

                                                </div>

                                                <div class="modal-body">


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Second Modal (nested) -->
                                    <div class="modal fade" id="new_patient" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">

                                                <div class="modal-header"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title">Add New Patient</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <form>
                                                        <div class="mb-3">
                                                            <label class="form-label">Patient Name</label>
                                                            <input type="text" class="form-control">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="doctor-schedule.html"
                                        class="btn btn-outline-primary d-inline-flex align-items-center"><i
                                            class="ti ti-menu me-1"></i>Doctor Wise</a>
                                    <a href="doctor-schedule.html"
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
                                                        <i class="ti ti-menu" data-bs-toggle="tooltip" title="Show"></i></a>
                                                    <a href="javascript: void(0);"
                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-warning rounded-pill">
                                                        <i class="ti ti-file-description" data-bs-toggle="tooltip"
                                                            title="Print"></i></a>
                                                    <a href="javascript: void(0);"
                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                        <i class="ti ti-calendar-time" data-bs-toggle="tooltip"
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
@endsection