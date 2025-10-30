{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Specialist List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_specialist"><i
                                                        class="ti ti-plus me-1"></i>Add Specialist</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_specialist" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add
                                                                Specialist
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('specialist.store') }}" method="POST">
                                                                @csrf
                                                                <div class="row gy-3 mb-2">
                                                                    <div class="col-md-12">
                                                                        <label for="name" class="form-label">Name <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="name" id="name"
                                                                            class="form-control" required>
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
                                                    <th>Specialist </th>
                                                    <th>Status </th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($specialists as $specialist)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">
                                                                {{ $specialist->specialist_name }}
                                                            </h6>
                                                        </td>
                                                        <td>
                                                            <form
                                                                action="{{ route('specialist.updateSpecialistStatus', [$specialist->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="form-check form-switch mb-0">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox" role="switch"
                                                                        id="switchCheckDefault" name="is_active"
                                                                        data-id="{{ $specialist->id }}"
                                                                        {{ $specialist->is_active == 'yes' ? 'checked' : '' }}>
                                                                </div>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-bs-toggle="modal" data-bs-target="#edit_specialist"
                                                                data-id="{{ $specialist->id }}"
                                                                data-name="{{ $specialist->specialist_name }}">
                                                                <i class="ti ti-pencil"></i></a>
                                                            <form
                                                                action="{{ route('specialist.deleteSpecialist', [$specialist->id]) }}"
                                                                class="d-inline" id="delete-form-{{ $specialist->id }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill delete-button"
                                                                    data-specialist-id="{{ $specialist->id }}"
                                                                    data-specialist-name="{{ $specialist->specialist_name }}"
                                                                    data-form-id="delete-form-{{ $specialist->id }}">
                                                                    <i class="ti ti-trash"></i></a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                    <!--Edit Modal -->
                                    <div class="modal fade" id="edit_specialist" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Update
                                                        Specialist
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('specialist.updateSpecialist') }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row gy-3 medicine-group-row mb-2">
                                                            <!-- Operation Name -->
                                                            <div class="col-md-12">
                                                                <label for="update_name" class="form-label">Name<span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" id="update_name"
                                                                    class="form-control" />
                                                                <input type="hidden" name="specialist_id"
                                                                    id="specialist_id">
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
        document.querySelectorAll('.status-toggle').forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('edit_specialist');

            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');

                // Populate modal inputs
                document.getElementById('specialist_id').value = id;
                document.getElementById('update_name').value = name;
            });
        });
    </script>
    <script>
        document.querySelectorAll('.delete-button').forEach(input => {
            input.addEventListener('click', function() {
                const specialistId = this.dataset.specialistId;
                const specialistName = this.dataset.specialistName;
                const formId = this.dataset.formId;

                Swal.fire({
                    title: `Please Confirm`,
                    text: `Delete Specialist ${specialistName}(${specialistId})`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete!',
                    cancelButtonText: 'Cancel',
                }).then(result => {
                    console.log(result);

                    if (result.isConfirmed) {
                        document.getElementById(formId).submit(); // Submit your form
                    }
                });
            });
        });
    </script>
@endsection
