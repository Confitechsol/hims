{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    {{-- Settings Form --}}
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Dosage Duration List</h5>
            </div>

            <div class="card-body">


                {{-- Hospital Name & Code --}}
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                @endif
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{session('success')}}
                                    </div>
                                @endif
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
                                                data-bs-toggle="modal" data-bs-target="#add_dosage_duration"><i
                                                    class="ti ti-plus me-1"></i>Add Dosage Duration</a>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="----add_dosage_duration" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Dosage
                                                            Duration
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="POST">
                                                            @csrf

                                                            <div id="dosage_duration_fields">
                                                                <div class="row gy-3 dosage-duration-row mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-11">
                                                                        <label for="duration"
                                                                            class="form-label">Duration <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="duration" id="duration"
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
                                            @foreach ($dosageDuration as $item)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{$item->name}}</h6>
                                                    </td>

                                                    <td>
                                                        <!-- <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                <i class="ti ti-pencil"></i></a> -->
                                                        <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="{{ $item["id"] }}" data-name="{{ $item["name"] }}">
                                                            <i class="ti ti-pencil"></i></button>
                                                        <form action="{{ route('dosage-duration.destroy')}}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <button onclick="return confirm('Are you sure?')"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i
                                                                    class="ti ti-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- end table-responsive -->
                                <!-- Pagination -->
                                {{-- Pagination Links --}}
                                <div class="mt-3" id="pagination-wrapper">
                                    @php
                                        $currentPage = $dosageDuration->currentPage();
                                        $lastPage = $dosageDuration->lastPage();
                                    @endphp

                                    {{-- Previous --}}
                                    @if ($dosageDuration->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $dosageDuration->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm me-1">
                                            « Prev
                                        </a>
                                    @endif

                                    {{-- Page numbers --}}
                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $dosageDuration->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor

                                    {{-- Next --}}
                                    @if ($dosageDuration->hasMorePages())
                                        <a href="{{ $dosageDuration->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            Next »
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                    @endif
                                </div>                        
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                </div>

            </div>
        </div>
    </div>
</div>
<x-modals.form-modal type="add" id="add_dosage_duration" title="Add Dosage Duration"
    action="{{route('dosage-duration.store')}}" :repeatable_group="[
        ['name' => 'name', 'label' => 'Duration', 'type' => 'text', 'required' => true, 'size' => '11']
    ]" :columns="2" />
<x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Dosage Duration"
    action="{{route('dosage-duration.update')}}" :fields="[
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'name', 'label' => 'Duration', 'type' => 'text', 'required' => true, 'size' => '12'],
    ]" :columns="2" />
<script>
    const addBtn = document.getElementById("addBtn");
    const operationFields = document.getElementById("dosage_duration_fields");

    addBtn.addEventListener("click", function () {
        // Clone the first row
        let firstRow = operationFields.querySelector(".dosage-duration-row");
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