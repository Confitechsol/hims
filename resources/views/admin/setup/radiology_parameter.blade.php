{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Radiology Parameter List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_radiology_parameter"><i
                                                        class="ti ti-plus me-1"></i>Add Radiology Parameter</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_radiology_parameter" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Radiology Parameter
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST">
                                                                @csrf
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="unit_name" class="form-label">Parameter
                                                                            Name<span class="text-danger">*</span></label>
                                                                        <input type="text" name="unit_name" id="unit_name"
                                                                            class="form-control" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="ref_range_from"
                                                                            class="form-label">Reference Range<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="ref_range_from"
                                                                            id="ref_range_from" class="form-control"
                                                                            placeholder="From" required>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="ref_range_to"
                                                                            class="form-label">Reference Range<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="ref_range_to"
                                                                            id="ref_range_to" class="form-control"
                                                                            placeholder="To" required>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="unit" class="form-label">Unit<span
                                                                                class="text-danger">*</span></label>
                                                                        <select name="unit" onchange="" class="form-select"
                                                                            autocomplete="off" required>
                                                                            <option value="">Select</option>
                                                                            <option value="6">Complete Blood Count (CBC)
                                                                            </option>
                                                                            <option value="7">Hemoglobin (Hb)</option>
                                                                            <option value="8">Platelet Count</option>
                                                                            <option value="9">Blood Smear Examination
                                                                            </option>
                                                                            <option value="10">Liver Function Test (LFT)
                                                                            </option>
                                                                            <option value="11">Kidney Function Test (KFT)
                                                                            </option>
                                                                            <option value="12">Lipid Profile (Cholesterol,
                                                                                Triglycerides)</option>
                                                                            <option value="13">Electrolytes (Na, K, Cl)
                                                                            </option>
                                                                            <option value="14">HIV, HBsAg, HCV</option>
                                                                            <option value="15">Urine Routine &amp;
                                                                                Microscopy</option>
                                                                            <option value="16">Semen Analysis</option>
                                                                            <option value="17">CSF Analysis (Cerebrospinal
                                                                                Fluid)</option>
                                                                            <option value="18">Thyroid Profile (TSH, T3, T4)
                                                                            </option>
                                                                            <option value="19">Testosterone, Estrogen
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="description"
                                                                            class="form-label">Description</label>
                                                                        <textarea name="description" id="description"
                                                                            class="form-control"></textarea>
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

                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Parameter Name</th>
                                                    <th>Reference Range</th>
                                                    <th>Unit</th>
                                                    <th>Description</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">Liver Size
                                                        </h6>
                                                    </td>
                                                    <td>13-15</td>
                                                    <td>Abdominal Ultrasound</td>
                                                    <td></td>
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
                                                        <h6 class="mb-0 fs-14 fw-semibold">Kidneys Length (adult)	
                                                        </h6>
                                                    </td>
                                                    <td>9-12</td>
                                                    <td>Abdominal Ultrasound</td>
                                                    <td></td>
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