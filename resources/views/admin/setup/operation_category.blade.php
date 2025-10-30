{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Operation Category List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_operation_category"><i
                                                        class="ti ti-plus me-1"></i>
                                                    Add Category</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_operation_category" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Category
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('operation-category.store') }}" method="POST">
                                                                @csrf


                                                                <div id="operation_category">
                                                                    <div class="row gy-3 operation_category_row mb-2">

                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-11">
                                                                            <label for="operation_name"
                                                                                class="form-label">Operation
                                                                                Category</label>
                                                                            <input type="text" name="operation_category[]"
                                                                                class="form-control" />
                                                                        </div>

                                                                        <div class="col-md-1 d-flex align-items-end">
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
                                                    <th>Name</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 @foreach($categories as $category)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $category->category }}</h6>
                                                        </td>

                                                        <td>
                                                            <a href="javascript:void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                onclick="openOperationCategoryModal(this)"
                                                                data-category-id="{{ $category->id }}"
                                                                data-category-name="{{ $category->category }}">
                                                                <i class="ti ti-pencil"></i>
                                                            </a>
                                                            <a href="javascript:void(0);"
                                                                onclick="deleteOperationCategory({{ $category->id }})"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i>
                                                            </a>
                                                        </td>
                                                        <form id="deleteOperationCategoryForm" method="POST" style="display:none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </tr>
                                                @endforeach
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
<div class="modal fade" id="editOperationCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Operation Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editOperationCategoryForm" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" class="form-control" name="name" id="editOperationCategoryName" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <script>
        const addBtn = document.getElementById("addBtn");
        const operationFields = document.getElementById("operation_category");

        addBtn.addEventListener("click", function () {
            // Clone the first row
            let firstRow = operationFields.querySelector(".operation_category_row");
            let newRow = firstRow.cloneNode(true);

            // Clear input values
            newRow.querySelectorAll("input, select").forEach(el => el.value = "");

            // Show remove button
            newRow.querySelector(".remove-btn").style.display = "inline-block";

            // Append new row
            operationFields.appendChild(newRow);

            // Add remove functionality
            newRow.querySelector(".remove-btn").addEventListener("click", function () {
                newRow.remove();
            });
        });
    </script>

<script>
function openOperationCategoryModal(el) {
    let id = el.getAttribute("data-category-id");
    let name = el.getAttribute("data-category-name");

    // Fill modal inputs
    document.getElementById("editOperationCategoryName").value = name;

    // Update form action dynamically
    let form = document.getElementById("editOperationCategoryForm");
    form.action = "{{ url('operation-category/update') }}/" + id; // route to update

    // Show modal
    let modal = new bootstrap.Modal(document.getElementById("editOperationCategoryModal"));
    modal.show();
}
</script>
<script>
    function deleteOperationCategory(id) {
        if (confirm("Are you sure you want to delete this operation?")) {
            let form = document.getElementById("deleteOperationCategoryForm");
            form.action = "{{ url('operation-category/destroy') }}/" + id; // adjust route if needed
            form.submit();
        }
    }
</script>


@endsection