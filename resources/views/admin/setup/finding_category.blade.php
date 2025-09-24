{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Finding Category List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_finding_category"><i
                                                        class="ti ti-plus me-1"></i>Add Finding Category</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_finding_category" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Finding
                                                                Category
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('finding-category.storeCategory') }}"
                                                                method="POST">
                                                                @csrf

                                                                <div id="finding_category_fields">
                                                                    <div class="row gy-3 finding-category-row mb-2">

                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-11">
                                                                            <label for="finding_category"
                                                                                class="form-label">Finding Category <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="finding_category[]"
                                                                                id="finding_category"
                                                                                class="form-control" />
                                                                        </div>

                                                                        <div class="col-md-1 d-flex align-items-end p-0">
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-btn"
                                                                                style="display:none;"><i
                                                                                    class="ti ti-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Add button -->
                                                                <div class="mt-3">
                                                                    <button type="button" id="addBtn"
                                                                        class="btn btn-primary">Add</button>
                                                                </div>


                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($findingCategories as $category)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $category->category }}
                                                            </h6>
                                                        </td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#edit_finding_category"
                                                                data-id="{{ $category->id }}"
                                                                data-name="{{ $category->category }}">
                                                                <i class="ti ti-pencil"></i></a>
                                                            <form
                                                                action="{{ route('finding-category.deleteCategory', [$category->id]) }}"
                                                                id="delete-form-{{ $category->id }}" method="POST"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill delete-button"
                                                                    data-category-id="{{ $category->id }}"
                                                                    data-category-name="{{ $category->name }}"
                                                                    data-form-id="delete-form-{{ $category->id }}">
                                                                    <i class="ti ti-trash"></i></a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach


                                            </tbody>
                                        </table>
                                    </div>
                                    <!--Edit Modal -->
                                    <div class="modal fade" id="edit_finding_category" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Update Finding
                                                        Category
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('finding-category.updateCategory') }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div id="finding_category_fields">
                                                            <div class="row gy-3 finding-category-row mb-2">

                                                                <!-- Operation Name -->
                                                                <div class="col-md-11">
                                                                    <label for="update_finding_category"
                                                                        class="form-label">Finding
                                                                        Category <span class="text-danger">*</span></label>
                                                                    <input type="text" name="finding_category"
                                                                        id="update_finding_category"
                                                                        class="form-control" />
                                                                    <input type="hidden" name="category_id"
                                                                        id="update_category_id">
                                                                </div>

                                                                <div class="col-md-1 d-flex align-items-end p-0">
                                                                    <button type="button"
                                                                        class="btn btn-danger remove-btn"
                                                                        style="display:none;"><i
                                                                            class="ti ti-trash"></i></button>
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
        const addBtn = document.getElementById("addBtn");
        const operationFields = document.getElementById("finding_category_fields");

        addBtn.addEventListener("click", function() {
            // Clone the first row
            let firstRow = operationFields.querySelector(".finding-category-row");
            let newRow = firstRow.cloneNode(true);

            // Clear input values
            newRow.querySelectorAll("input, select").forEach(el => el.value = "");

            // Show remove button
            newRow.querySelector(".remove-btn").style.display = "inline-block";

            // Append new row
            operationFields.appendChild(newRow);

            // Add remove functionality
            newRow.querySelector(".remove-btn").addEventListener("click", function() {
                newRow.remove();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('edit_finding_category');

            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');

                // Populate modal inputs

                document.getElementById('update_category_id').value = id;
                document.getElementById('update_finding_category').value = name;
            });
        });
    </script>

    <script>
        document.querySelectorAll('.delete-button').forEach(input => {
            input.addEventListener('click', function() {
                const categoryId = this.dataset.categoryId;
                const categoryName = this.dataset.categoryName;
                const formId = this.dataset.formId;

                Swal.fire({
                    title: `Please Confirm`,
                    text: `Delete Finding Category ${categoryName}(${categoryId})`,
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
