{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Pathology Parameter List</h5>
                </div>

                <div class="card-body">


                    {{-- Hospital Name & Code --}}
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                         <form method="GET" action="" class="input-icon-start position-relative me-2 d-flex align-items-center">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" name="search" class="form-control shadow-sm" placeholder="Search" value="{{ request('search') }}" style="max-width: 300px;">
                                            <button type="submit" class="btn btn-primary ms-2">Search</button>
                                        </form>
                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_pathology_parameter"><i
                                                        class="ti ti-plus me-1"></i>Add Pathology Parameter</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_pathology_parameter" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Pathology Parameter
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('pathology-parameter.store') }}" method="POST">
                                                                @csrf
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="parameter_name" class="form-label">Parameter
                                                                            Name<span class="text-danger">*</span></label>
                                                                        <input type="text" name="parameter_name" id="parameter_name"
                                                                            class="form-control" required />
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
                                                                    <div class="col-md-12">
                                                                        <label for="unit" class="form-label">Unit<span
                                                                                class="text-danger">*</span></label>
                                                                        <select name="unit_id" id="unit_id" class="form-select" required>
                                                                            <option value="">Select Unit</option>
                                                                            @foreach($units as $unit)
                                                                                <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="description"
                                                                            class="form-label">Description</label>
                                                                        <textarea name="description" id="description"
                                                                            class="form-control"></textarea>
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
                                               
                                                @foreach($parameters as $parameter)
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $parameter->parameter_name }}</h6>
                                                        </td>
                                                        <td>{{ $parameter->reference_range }}</td> <!-- if you have this column -->
                                                        <td>{{ $parameter->unitRelation ? $parameter->unitRelation->unit_name : '-' }}</td> <!-- e.g., category name or type -->
                                                        <td>{{ $parameter->description }}</td>
                                                        <td>
                                                            <!-- Edit Button -->
                                                            <a href="javascript:void(0);" 
                                                            onclick="openPathologyParameterModal(this)"
                                                            data-id="{{ $parameter->id }}"
                                                            data-name="{{ $parameter->parameter_name }}"
                                                            data-range_from="{{ $parameter->range_from }}"
                                                            data-range_to="{{ $parameter->range_to }}"
                                                            data-unit="{{ $parameter->unit_id }}"
                                                            data-description="{{ $parameter->description }}"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i>
                                                            </a>

                                                            <!-- Delete Button -->
                                                            <a href="javascript:void(0);"
                                                            onclick="deletePathologyParameter({{ $parameter->id }})"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i>
                                                            </a>

                                                            <form id="deletePathologyParameterForm" method="POST" style="display:none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                          {{-- Pagination Links --}}
                                         
                                    <div class="mt-3" id="pagination-wrapper">
                                        @php
                                            $currentPage = $parameters->currentPage();
                                            $lastPage = $parameters->lastPage();
                                            $window = 2; // how many pages to show on each side
                                            $start = max(1, $currentPage - $window);
                                            $end = min($lastPage, $currentPage + $window);
                                        @endphp

                                        @if ($parameters->onFirstPage())
                                            <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                        @else
                                            <a href="{{ $parameters->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                        @endif

                                        @if ($start > 1)
                                            <a href="{{ $parameters->url(1) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">1</a>
                                            @if ($start > 2)
                                                <span class="btn btn-outline-secondary btn-sm me-1 disabled">...</span>
                                            @endif
                                        @endif

                                        @for ($page = $start; $page <= $end; $page++)
                                            @if ($page == $currentPage)
                                                <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                            @else
                                                <a href="{{ $parameters->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">{{ $page }}</a>
                                            @endif
                                        @endfor

                                        @if ($end < $lastPage)
                                            @if ($end < $lastPage - 1)
                                                <span class="btn btn-outline-secondary btn-sm me-1 disabled">...</span>
                                            @endif
                                            <a href="{{ $parameters->url($lastPage) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm me-1">{{ $lastPage }}</a>
                                        @endif

                                        @if ($parameters->hasMorePages())
                                            <a href="{{ $parameters->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" class="btn btn-outline-secondary btn-sm">Next »</a>
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

    <div class="modal fade" id="editPathologyParameterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pathology Parameter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPathologyParameterForm" method="POST" >
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_parameter_id">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Parameter Name</label>
                            <input type="text" name="parameter_name" id="edit_parameter_name" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Reference Range From</label>
                                <input type="text" name="ref_range_from" id="edit_range_from" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Reference Range To</label>
                                <input type="text" name="ref_range_to" id="edit_range_to" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" id="edit_unit_id" class="form-select" required>
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    function openPathologyParameterModal(el) {
    let id = el.getAttribute("data-id");
    let name = el.getAttribute("data-name");
    let range_from = el.getAttribute("data-range_from");
    let range_to = el.getAttribute("data-range_to");
    let unit = el.getAttribute("data-unit");
    let description = el.getAttribute("data-description");

    // Fill modal inputs
    document.getElementById("edit_parameter_id").value = id;
    document.getElementById("edit_parameter_name").value = name;
    document.getElementById("edit_range_from").value = range_from || "";
    document.getElementById("edit_range_to").value = range_to || "";
    document.getElementById("edit_unit_id").value = unit || "";
    document.getElementById("edit_description").value = description || "";

    // Update form action dynamically
    let form = document.getElementById("editPathologyParameterForm");
    form.action = "{{ url('pathology-parameter/update') }}/" + id;

    // Show modal
    let modal = new bootstrap.Modal(document.getElementById("editPathologyParameterModal"));
    modal.show();
}

</script>
<script>
    function deletePathologyParameter(id) {
        if (confirm("Are you sure you want to delete this pathology parameter?")) {
            let form = document.getElementById("deletePathologyParameterForm");
            form.action = "{{ url('pathology-parameter/destroy') }}/" + id; // your delete route
            form.submit();
        }
    }

</script>

@endsection