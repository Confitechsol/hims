{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Income Head List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_income_head"><i
                                                        class="ti ti-plus me-1"></i>Add Income Head</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_income_head" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Income Head
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('income-head.store') }}" method="POST">
                                                                @csrf

                                                                <div id="income_head_fields">
                                                                    <div class="row gy-3 income-head-row mb-2">

                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-4">
                                                                            <label for="income_head"
                                                                                class="form-label">Income Head <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="income_head[]"
                                                                                id="income_head" class="form-control" required />
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <label for="description"
                                                                                class="form-label">Description</label>
                                                                            <input type="text" name="description[]"
                                                                                id="description" class="form-control"  />
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
                                                    <th>Income Head</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($incomeHeads as $incomeHead)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $incomeHead->income_category }}</h6>
                                                            <small class="text-muted">{{ $incomeHead->description }}</small>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);" 
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="{{ $incomeHead->id }}"
                                                            data-name="{{ $incomeHead->income_category }}"
                                                            data-desc="{{ $incomeHead->description }}">
                                                                <i class="ti ti-pencil"></i>
                                                            </a>
                                                            <a href="javascript:void(0);"
                                                                onclick="deleteIncomeHead({{ $incomeHead->id }})"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i>
                                                            </a>
                                                            

                                                        </td>
                                                        <form id="deleteIncomeHeadForm" method="POST" style="display:none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </tr>
                                                    
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted">No income heads found</td>
                                                    </tr>
                                                @endforelse
                                                
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
    <!-- Edit Modal -->
<div class="modal fade" id="edit_income_head" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title">Edit Income Head</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="mb-3">
                        <label for="edit_income_head_name" class="form-label">Income Head</label>
                        <input type="text" name="income_head" id="edit_income_head_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <input type="text" name="description" id="edit_description" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Handle Edit button click
    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function () {
            let id = this.dataset.id;
            let name = this.dataset.name;
            let desc = this.dataset.desc;

            // Set values inside modal
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_income_head_name").value = name;
            document.getElementById("edit_description").value = desc;

            // Update form action URL dynamically
            let form = document.getElementById("editForm");
            form.action = '{{ url('income-head/update') }}/' + id;

            // Open modal
            new bootstrap.Modal(document.getElementById("edit_income_head")).show();
        });
    });

    function deleteIncomeHead(id) {
        if (confirm("Are you sure you want to delete this Income Head?")) {
            let form = document.getElementById("deleteIncomeHeadForm");
            form.action = "{{ url('income-head/destroy') }}/" + id;
            form.submit();
        }
    }
</script>

    <script>
        const addBtn = document.getElementById("addBtn");
        const operationFields = document.getElementById("income_head_fields");

        addBtn.addEventListener("click", function () {
            // Clone the first row
            let firstRow = operationFields.querySelector(".income-head-row");
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



@endsection