{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Operation List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_operation"><i
                                                        class="ti ti-plus me-1"></i>
                                                    Add Operation</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_operation" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Operation
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('operations.store') }}" method="POST">
                                                                @csrf

                                                                <div id="operationFields">
                                                                    <div class="row gy-3 operation-row mb-2">
                                                                        

                                                                        <!-- Category -->
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Category</label>
                                                                            <select class="form-control" name="category[]">
                                                                                <option value="">Select</option>
                                                                                @foreach($categories as $category)
                                                                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-5">
                                                                            <label class="form-label">Operation Name</label>
                                                                            <input type="text" name="operation_name[]" class="form-control" />
                                                                        </div>

                                                                        <!-- Remove -->
                                                                        <div class="col-md-1 d-flex align-items-end">
                                                                            <button type="button" class="btn btn-danger remove-btn" style="display:none;">
                                                                                <i class="ti ti-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Add Button -->
                                                                <div class="mt-3">
                                                                    <button type="button" id="addBtn" class="btn btn-primary">Add</button>
                                                                </div>
                                                            </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Save Role</button>
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
                                                    <th>Category </th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
    @foreach($operations as $operation)
        <tr>
            <td>
                <h6 class="mb-0 fs-14 fw-semibold">{{ $operation->operation }}</h6>
            </td>

            <td>
                {{ $operation->category->category ?? 'N/A' }}
            </td>

            <td>
                <a href="javascript:void(0);"
   class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
   onclick="openOperationModal(this)"
   data-operation-id="{{ $operation->id }}"
   data-operation-name="{{ $operation->operation }}"
   data-operation-category="{{ $operation->category_id }}">
   <i class="ti ti-pencil"></i>
</a>



                <a href="javascript:void(0);"
                   onclick="deleteOperation({{ $operation->id }})"
                   class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                   <i class="ti ti-trash"></i>
                </a>
                <form id="deleteOperationForm" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
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
<div class="modal fade" id="editOperationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Operation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editOperationForm" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Operation Name</label>
                <input type="text" class="form-control" name="operation_name" id="editOperationName" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select class="form-control" name="category_id" id="editOperationCategory" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category }}</option>
                    @endforeach
                </select>
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
        const operationFields = document.getElementById("operationFields");

        addBtn.addEventListener("click", function () {
            // Clone the first row
            let firstRow = operationFields.querySelector(".operation-row");
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
    function openOperationModal(el) {
        let id = el.getAttribute("data-operation-id");
        let name = el.getAttribute("data-operation-name");
        let category = el.getAttribute("data-operation-category");

        // Fill modal inputs
        document.getElementById("editOperationName").value = name;
        document.getElementById("editOperationCategory").value = category;

        // Update form action dynamically
        let form = document.getElementById("editOperationForm");
        form.action = "{{ url('operations/update') }}/" + id; // route to update

        // Show modal
        let modal = new bootstrap.Modal(document.getElementById("editOperationModal"));
        modal.show();
    }
</script>

<script>
    function deleteOperation(id) {
        if (confirm("Are you sure you want to delete this operation?")) {
            let form = document.getElementById("deleteOperationForm");
            form.action = "{{ url('operations/destroy') }}/" + id; // adjust route if needed
            form.submit();
        }
    }
</script>


@endsection