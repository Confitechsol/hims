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
                                                            <form action="" method="POST">
                                                                @csrf


                                                                <div id="operationFields">
                                                                    <div class="row gy-3 operation-row mb-2">

                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-5">
                                                                            <label for="operation_name"
                                                                                class="form-label">Operation Name</label>
                                                                            <input type="text" name="operation_name[]"
                                                                                class="form-control" />
                                                                        </div>

                                                                        <!-- Category -->
                                                                        <div class="col-md-6">
                                                                            <label for="category"
                                                                                class="form-label">Category</label>
                                                                            <select class="form-control" name="category[]">
                                                                                <option value="">Select</option>
                                                                                <option value="4">Cardiothoracic Surgery
                                                                                </option>
                                                                                <option value="7">ENT Surgery</option>
                                                                                <option value="1">General Surgery</option>
                                                                                <option value="3">Neurosurgery</option>
                                                                                <option value="5">Obstetrics &amp;
                                                                                    Gynecology (OBG)</option>
                                                                                <option value="8">Ophthalmology</option>
                                                                                <option value="2">Orthopedic Surgery
                                                                                </option>
                                                                                <option value="9">Plastic &amp;
                                                                                    Reconstructive Surgery</option>
                                                                                <option value="6">Urology</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-1 d-flex align-items-end">
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-btn"
                                                                                style="display:none;"><i class="ti ti-trash"></i></button>
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
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">Adenoidectomy</h6>
                                                    </td>

                                                    <td>ENT Surgery</td>

                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil" data-bs-toggle="tooltip"
                                                                title="Assign Permission"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash" data-bs-toggle="tooltip"
                                                                title="Assign Permission"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">Burn Debridement</h6>
                                                    </td>

                                                    <td>Plastic & Reconstructive Surgery</td>

                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil" data-bs-toggle="tooltip"
                                                                title="Assign Permission"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash" data-bs-toggle="tooltip"
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



@endsection