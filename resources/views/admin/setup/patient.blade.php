{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Patient List</h5>
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
                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_patient"><i
                                                        class="ti ti-plus me-1"></i>Add New
                                                    Patient</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_patient" tabindex="-1"
                                                aria-labelledby="addSpecializationLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                                    <div class="modal-content modal-xl">
                                                        <div class="modal-header modal-xl rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add New
                                                                Patien
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('roles.store')  }}" method="POST">
                                                                @csrf
                                                                <div class="row gy-3">
                                                                    <div class="col-md-6">
                                                                        <label for="" class="form-label">
                                                                            Name</label>
                                                                        <input type="text" id="name" name="name"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="" class="form-label">
                                                                            Guardian Name</label>
                                                                        <input type="text" id="guardian_name"
                                                                            name="guardian_name" class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <label for=""
                                                                                    class="form-label">Gender</label>
                                                                                <select class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    <option value="Male">Male</option>
                                                                                    <option value="Female">Female</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="" class="form-label">Date of
                                                                                    Birth</label>
                                                                                <input type="date" id="birth_date"
                                                                                    name="birth_date"
                                                                                    class="form-control" />
                                                                            </div>
                                                                            <div class="col-sm-5">
                                                                                <label for="" class="form-label">Age
                                                                                    (yy-mm-dd) </label><small class="req">
                                                                                    *</small>
                                                                                <div style="clear: both;overflow: hidden;">
                                                                                    <input type="text" placeholder="YY"
                                                                                        name="age[year]" id="age_year"
                                                                                        value=""
                                                                                        class="form-control patient_age_year"
                                                                                        style="width: 30%; float: left;"
                                                                                        autocomplete="off">

                                                                                    <input type="text" id="age_month"
                                                                                        placeholder="MM" name="age[month]"
                                                                                        value=""
                                                                                        class="form-control patient_age_month"
                                                                                        style="width: 36%;float: left; margin-left: 4px;"
                                                                                        autocomplete="off">
                                                                                    <input type="text" id="age_day"
                                                                                        placeholder="DD" name="age[day]"
                                                                                        value=""
                                                                                        class="form-control patient_age_day"
                                                                                        style="width: 26%;float: left; margin-left: 4px;"
                                                                                        autocomplete="off">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <label for="" class="form-label">Blood
                                                                                    Group</label>
                                                                                <select name="blood_group"
                                                                                    class="form-control" autocomplete="off">
                                                                                    <option value="">Select</option>
                                                                                    <option value="1">O+</option>
                                                                                    <option value="2">A+</option>
                                                                                    <option value="3">B+</option>
                                                                                    <option value="4">AB+</option>
                                                                                    <option value="5">O-</option>
                                                                                    <option value="6">AB-</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <label for="" class="form-label">Marital
                                                                                    Status</label>
                                                                                <select name="marital_status"
                                                                                    class="form-control">
                                                                                    <option value="">Select</option>
                                                                                    <option value="Single">Single</option>
                                                                                    <option value="Married">Married</option>
                                                                                    <option value="Widowed">Widowed</option>
                                                                                    <option value="Separated">Separated
                                                                                    </option>
                                                                                    <option value="Not Specified">Not
                                                                                        Specified
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for="" class="form-label">Patient
                                                                                    Photo</label>
                                                                                <input class="filestyle form-control"
                                                                                    type="file" name="file" id="file"
                                                                                    size="20" data-height="26"
                                                                                    autocomplete="off">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label for=""
                                                                                    class="form-label">Phone</label>
                                                                                <input type="tel" id="phone" name="phone"
                                                                                    class="form-control" />
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label for=""
                                                                                    class="form-label">Email</label>
                                                                                <input type="mail" id="email" name="email"
                                                                                    class="form-control" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="" class="form-label">Address</label>
                                                                        <input type="address" id="address" name="address"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="" class="form-label">Remarks</label>
                                                                        <input type="text" id="remarks" name="remarks"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="" class="form-label">Any Known
                                                                            Allergies</label>
                                                                        <input type="text" id="allergies" name="allergies"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="" class="form-label">TPA</label>
                                                                        <select class="form-control">
                                                                            <option value="">Select</option>
                                                                            <option value="5">MedoLogi TPA Pvt. Ltd.
                                                                            </option>
                                                                            <option value="4">Vidal Health TPA </option>
                                                                            <option value="3">Paramount Health Services
                                                                            </option>
                                                                            <option value="2">Raksha TPA Pvt. Ltd. </option>
                                                                            <option value="1">MediAssist TPA Pvt. Ltd.
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="" class="form-label">TPA ID</label>
                                                                        <input type="text" id="tpa_id" name="tpa_id"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="" class="form-label">TPA
                                                                            Validity</label>
                                                                        <input type="text" id="tpa_validity"
                                                                            name="tpa_validity" class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="" class="form-label">National
                                                                            Identification Number</label>
                                                                        <input type="text" id="national_id_number"
                                                                            name="national_id_number"
                                                                            class="form-control" />
                                                                    </div>
                                                                </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save Role</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-download me-1"></i>Import Patient</a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-menu me-1"></i>Disable Patient List</a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" name="checkbox" id="checkbox" >
                                                        #</th>
                                                    <th>Patient Name</th>
                                                    <th>Age</th>
                                                    <th>Gender</th>
                                                    <th>Phone</th>
                                                    <th>Guardian Name</th>
                                                    <th>Address</th>
                                                    <th>Dead</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        <input type="checkbox" name="checkbox" id="checkbox" >
                                                    </th>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> Sayan P</h6>
                                                    </td>

                                                    <td>24 Year 4 Month 3 Days</td>
                                                    <td>Male</td>
                                                    <td>8617284931</td>
                                                    <td></td>
                                                    <td>Bhadreswar</td>
                                                    <td>No</td>

                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                title="Assign Permission"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        <input type="checkbox" name="checkbox" id="checkbox" >
                                                    </th>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> Bimal</h6>
                                                    </td>

                                                    <td>15 Year 4 Month 8 Days</td>
                                                    <td>Male</td>
                                                    <td>7044094367</td>
                                                    <td>Das	</td>
                                                    <td>xXzXzXzX</td>
                                                    <td>No</td>

                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                            <i class="ti ti-dots-vertical" data-bs-toggle="tooltip"
                                                                title="Assign Permission"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                title="Assign Permission"></i></a>
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