{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Finding List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_finding"><i
                                                        class="ti ti-plus me-1"></i>Add Finding</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_finding" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Finding

                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('finding.store') }}" method="POST">
                                                                @csrf
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="finding" class="form-label">Finding
                                                                            <span class="text-danger">*</span></label>
                                                                        <input type="text" name="finding" id="finding"
                                                                            class="form-control" required />
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <label for="category" class="form-label">Category
                                                                            <span class="text-danger">*</span></label>
                                                                        <select name="category" id="category"
                                                                            class="form-select" required>
                                                                            <option value="">Select</option>
                                                                            @foreach ($findingCategories as $category)
                                                                                <option value="{{ $category->id }}">
                                                                                    {{ $category->category }}
                                                                                </option>
                                                                            @endforeach
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
                                                    <th>Finding</th>
                                                    <th>Category</th>
                                                    <th>Finding Description</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($findings as $finding)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $finding->name }}
                                                            </h6>
                                                        </td>
                                                        <td>{{ $finding->category->category ?? '-' }} </td>
                                                        <td>{{ $finding->description }}</td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-bs-toggle="modal" data-bs-target="#edit_finding"
                                                                data-id="{{ $finding->id }}"
                                                                data-name="{{ $finding->name }}"
                                                                data-category="{{ $finding->category->id }}"
                                                                data-description="{{ $finding->description }}">
                                                                <i class="ti ti-pencil"></i></a>
                                                            <form action="{{ route('finding.delete', [$finding->id]) }}"
                                                                id="delete-form-{{ $finding->id }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill delete-button"
                                                                    data-finding-id="{{ $finding->id }}"
                                                                    data-finding-name="{{ $finding->name }}"
                                                                    data-form-id="delete-form-{{ $finding->id }}">
                                                                    <i class="ti ti-trash"></i></a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--Edit Modal -->
                                    <div class="modal fade" id="edit_finding" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Update
                                                        Finding
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('finding.update') }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row gy-3 mb-2">
                                                            <input type="hidden" name="finding_id"
                                                                id="update_finding_id" class="form-control" required />
                                                            <div class="col-md-12">
                                                                <label for="update_finding_name"
                                                                    class="form-label">Finding
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="text" name="finding"
                                                                    id="update_finding_name" class="form-control"
                                                                    required />
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="update_category"
                                                                    class="form-label">Category<span
                                                                        class="text-danger">*</span></label>
                                                                <select name="category" onchange=""
                                                                    class="form-select" id="update_category"
                                                                    autocomplete="off" required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($findingCategories as $category)
                                                                        <option value="{{ $category->id }}">
                                                                            {{ $category->category }}</option>
                                                                    @endforeach
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
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('edit_finding');

            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var category = button.getAttribute('data-category');
                var description = button.getAttribute('data-description');

                console.log(category);

                // Populate modal inputs
                document.getElementById('update_finding_id').value = id;
                document.getElementById('update_finding_name').value = name;
                document.getElementById('update_description').value = description;

                // Select Unit
                let categorySelect = editModal.querySelector('select[name="category"]');
                if (categorySelect) {
                    categorySelect.value = category;
                }
            });
        });
    </script>

    <script>
        document.querySelectorAll('.delete-button').forEach(input => {
            input.addEventListener('click', function() {
                const findingId = this.dataset.findingId;
                const findingName = this.dataset.findingName;
                const formId = this.dataset.formId;

                Swal.fire({
                    title: `Please Confirm`,
                    text: `Delete Finding ${findingName}(${findingId})`,
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
@endsection
