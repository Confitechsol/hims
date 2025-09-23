{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Medicine Dosage List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_medicine_dosage"><i
                                                        class="ti ti-plus me-1"></i>Add Medicine Dosage</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="--------add_medicine_dosage" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Medicine
                                                                Dosage
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST">
                                                                @csrf

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <label for="medicine_category"
                                                                            class="form-label">Medicine Category <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="medicine_category"
                                                                            id="medicine_category" class="form-control" />
                                                                    </div>
                                                                </div>

                                                                <div id="medicine_dosage_fields">
                                                                    <div class="row gy-3 medicine-dosage-row mb-2">

                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-5">
                                                                            <label for="dose" class="form-label">Dose <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="dose" id="dose"
                                                                                class="form-control" />
                                                                        </div>

                                                                        <!-- Category -->
                                                                        <div class="col-md-6">
                                                                            <label for="unit" class="form-label">Category
                                                                                <span class="text-danger">*</span></label>
                                                                            <select class="form-select" name="unit"
                                                                                id="unit">

                                                                                <option value="">Select</option>
                                                                                <option value="1">Tablet</option>
                                                                                <option value="2">Syrup</option>
                                                                                <option value="3">Injection</option>
                                                                                <option value="4">Cream</option>
                                                                                <option value="5">Ointment</option>
                                                                                <option value="29">Inhaler</option>
                                                                                <option value="32">Solution</option>
                                                                            </select>
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
                                                    <th>Category Name</th>
                                                    <th>Dosage</th>
                                                    <th>Unit</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($assocArr as $item)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{$item["medicine_category"]}}</h6>
                                                    </td>

                                                    <td>{{$item["dosage"]}}</td>
                                                    <td>{{$item["unit"]}}</td>
                                                    <td>
                                                    <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="{{ $item["id"] }}"
                                                            data-medicine_category="{{$item['medicine_category']}}"
                                                            data-dosage="{{$item['dosage']}}"
                                                            >
                                                            <i class="ti ti-pencil"></i></button>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach                                              
                                                <!-- <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">Analgesic</h6>
                                                    </td>

                                                    <td>1-2</td>
                                                    <td>Table</td>

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
                                                        <h6 class="mb-0 fs-14 fw-semibold">Antipyretic</h6>
                                                    </td>

                                                    <td>1</td>
                                                    <td>Table</td>

                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
                                                    </td>
                                                </tr> -->
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
@php
//$options = ["Tablet","Syrup","Injection","Creame","Ointment","Inhaler","Solution"];
$options = [
            ""=>"Select",
            1 => 'Tablet',
            2 => 'Capsule',
            3 => 'Syrup',
            4=>'Injection',
            5=>'Creame'
        ];
@endphp
    <x-modals.form-modal id="add_medicine_dosage" title="Add Medicine Dosage" action="{{route('supplier-store')}}" :fields="[
        ['name' => 'medicine_category', 'label' => 'Medicine Category', 'type' => 'text', 'required' => true,'size'=>'12']
    ]" :repeatable_group="[
        ['name' => 'dosage', 'label' => 'Dose', 'type' => 'text', 'required' => true,'size'=>'5'],
        ['name' => 'unit', 'label' => 'Unit', 'type' => 'select','options'=>$options, 'required' => true,'size'=>'6']
        ]" :columns="2" />
    <x-modals.form-modal id="edit_modal" title="Edit Medicine Dosage" action="{{route('supplier-store')}}" :fields="[
        ['name' => 'medicine_category', 'label' => 'Medicine Category', 'type' => 'text', 'required' => true,'size'=>'12'],
        ['name' => 'dosage', 'label' => 'Dose', 'type' => 'text', 'required' => true,'size'=>'5'],
        ['name' => 'unit', 'label' => 'Unit', 'type' => 'select','options'=>$options, 'required' => true,'size'=>'6'],
    ]" :columns="2" />
    <script>
        const addBtn = document.getElementById("addBtn");
        const operationFields = document.getElementById("medicine_dosage_fields");

        addBtn.addEventListener("click", function () {
            // Clone the first row
            let firstRow = operationFields.querySelector(".medicine-dosage-row");
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