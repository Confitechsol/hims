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
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add
                                                                Radiology Parameter
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('radiology-parameter.store') }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="parameter_name"
                                                                            class="form-label">Parameter
                                                                            Name<span class="text-danger">*</span></label>
                                                                        <input type="text" name="parameter_name"
                                                                            id="unit_name" class="form-control" required />
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
                                                                    <div class="col-md-6">
                                                                        <label for="unit" class="form-label">Unit<span
                                                                                class="text-danger">*</span></label>
                                                                        <select name="unit" onchange=""
                                                                            class="form-select" autocomplete="off" required>
                                                                            <option value="">Select</option>
                                                                            @foreach ($unitData as $unit)
                                                                                <option value="{{ $unit->id }}">
                                                                                    {{ $unit->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="gender" class="form-label">Gender<span
                                                                                class="text-danger">*</span></label>
                                                                        <select name="gender" onchange=""
                                                                            class="form-select" autocomplete="off" required>
                                                                            <option>Select</option>
                                                                            <option>Male</option>
                                                                            <option>Female</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="description"
                                                                            class="form-label">Description</label>
                                                                        <textarea name="description" id="description" class="form-control"></textarea>
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
                                                @foreach ($radiologyParameters as $parameter)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">
                                                                {{ $parameter->parameter_name }}
                                                            </h6>
                                                        </td>
                                                        <td>{{ $parameter->reference_range }}</td>
                                                        <td>{{ $units[$parameter->id]->name ?? '-' }}</td>
                                                        <td>{{ $parameter->description ?? '-' }}</td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#edit_radiology_parameter"
                                                                data-id="{{ $parameter->id }}"
                                                                data-name="{{ $parameter->parameter_name }}"
                                                                data-unit="{{ $parameter->unit }}"
                                                                data-description="{{ $parameter->description }}"
                                                                data-range_from="{{ $parameter->range_from }}"
                                                                data-range_to="{{ $parameter->range_to }}"
                                                                data-gender="{{ $parameter->gender }}">
                                                                <i class="ti ti-pencil"></i></a>
                                                            <form
                                                                action="{{ route('radiology-parameter.delete', [$parameter->id]) }}"
                                                                id="delete-form-{{ $parameter->id }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill delete-button"
                                                                    data-parameter-id="{{ $parameter->id }}"
                                                                    data-parameter-name="{{ $parameter->name }}"
                                                                    data-form-id="delete-form-{{ $parameter->id }}">
                                                                    <i class="ti ti-trash"></i></a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--Edit Modal -->
                                    <div class="modal fade" id="edit_radiology_parameter" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Update
                                                        Radiology Parameter
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('radiology-parameter.update') }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row gy-3 mb-2">
                                                            <!-- Operation Name -->
                                                            <input type="hidden" name="parameter_id"
                                                                id="update_parameter_id" class="form-control" required />
                                                            <div class="col-md-12">
                                                                <label for="update_parameter_name"
                                                                    class="form-label">Parameter
                                                                    Name<span class="text-danger">*</span></label>
                                                                <input type="text" name="parameter_name"
                                                                    id="update_parameter_name" class="form-control"
                                                                    required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="update_ref_range_from"
                                                                    class="form-label">Reference
                                                                    Range<span class="text-danger">*</span></label>
                                                                <input type="text" name="ref_range_from"
                                                                    id="update_ref_range_from" class="form-control"
                                                                    placeholder="From" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="update_ref_range_to"
                                                                    class="form-label">Reference
                                                                    Range<span class="text-danger">*</span></label>
                                                                <input type="text" name="ref_range_to"
                                                                    id="update_ref_range_to" class="form-control"
                                                                    placeholder="To" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="update_unit" class="form-label">Unit<span
                                                                        class="text-danger">*</span></label>
                                                                <select name="unit" onchange="" class="form-select"
                                                                    autocomplete="off" required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($unitData as $unit)
                                                                        <option value="{{ $unit->id }}">
                                                                            {{ $unit->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="gender" class="form-label">Gender<span
                                                                        class="text-danger">*</span></label>
                                                                <select name="gender" onchange="" class="form-select"
                                                                    autocomplete="off" required>
                                                                    <option>Select</option>
                                                                    <option>Male</option>
                                                                    <option>Female</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="update_description"
                                                                    class="form-label">Description</label>
                                                                <textarea name="description" id="update_description" class="form-control"></textarea>
                                                            </div>

                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.delete-button').forEach(input => {
            input.addEventListener('click', function() {
                const parameterId = this.dataset.parameterId;
                const parameterName = this.dataset.parameterName;
                const formId = this.dataset.formId;

                Swal.fire({
                    title: `Please Confirm`,
                    text: `Delete Radiology Parameter ${parameterName}(${parameterId})`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete!',
                    cancelButtonText: 'Cancel',
                }).then(result => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit(); // Submit your form
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('edit_radiology_parameter');

            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var unit = button.getAttribute('data-unit');
                var description = button.getAttribute('data-description');
                var rangeFrom = button.getAttribute('data-range_from');
                var rangeTo = button.getAttribute('data-range_to');
                var gender = button.getAttribute('data-gender');
                // Populate modal inputs
                document.getElementById('update_parameter_id').value = id;
                document.getElementById('update_parameter_name').value = name;

                document.getElementById('update_ref_range_from').value = rangeFrom;
                document.getElementById('update_ref_range_to').value = rangeTo;
                document.getElementById('update_description').value = description;

                // Select Unit
                let unitSelect = editModal.querySelector('select[name="unit"]');
                if (unitSelect) {
                    unitSelect.value = unit;
                }

                // Select Gender
                let genderSelect = editModal.querySelector('select[name="gender"]');
                if (genderSelect) {
                    genderSelect.value = gender;
                }

            });
        });
    </script>
@endsection
