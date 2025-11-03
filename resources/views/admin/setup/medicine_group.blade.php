{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    {{-- Settings Form --}}
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Medicine Group List</h5>
            </div>
            <div class="card-body">
                {{-- Hospital Name & Code --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                  <!-- Modal -->
                                  <div class="modal fade" id="createModal" tabindex="-1"
                                  aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                      <div class="modal-content">
                                          <div class="modal-header rounded-0"
                                              style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                              <h5 class="modal-title" id="addSpecializationLabel">Add Medicine
                                                  Group
                                              </h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                  aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                              <form action="{{ route('medicine-group.storeMultiple') }}"
                                                  method="POST">
                                                  @csrf

                                                  <div id="medicine_group_fields">
                                                      <div class="row gy-3 medicine-group-row mb-2">

                                                          <!-- Operation Name -->
                                                          <div class="col-md-11">
                                                              <label for="medicine_group"
                                                                  class="form-label">Medicine Group <span
                                                                      class="text-danger">*</span></label>
                                                              <!-- <input type="text" name="medicine_group"
                                                                      id="medicine_group" class="form-control" /> -->
                                                              <input type="text" name="medicine_group[]"
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
                                <x-table-actions.actions id="medicine-group" name="Medicine Group" /> 
                                @if (session('success'))
                                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="table-responsive" id="medicine-group">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Database ID</th>
                                                <th>Medicine Group</th>
                                                <th style="width: 200px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{ $category->id }}
                                                        </h6>
                                                    </td>
                                                    <td>{{ $category->group_name }}</td>
                                                    <td>
                                                        <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="{{ $category->id }}"
                                                            data-group_name="{{ $category->group_name }}"><i
                                                                class="ti ti-pencil"></i></button>

                                                        <form action="{{ route('medicine-group.destroy')}}" method="POST"
                                                            style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id" value="{{$category->id}}">
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
                                {{-- Pagination Links --}}
                                <div class="mt-3" id="pagination-wrapper">
                                    @php
                                        $currentPage = $categories->currentPage();
                                        $lastPage = $categories->lastPage();
                                    @endphp

                                    {{-- Previous --}}
                                    @if ($categories->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $categories->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm me-1">
                                            « Prev
                                        </a>
                                    @endif

                                    {{-- Page numbers --}}
                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $categories->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor

                                    {{-- Next --}}
                                    @if ($categories->hasMorePages())
                                        <a href="{{ $categories->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
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
<!-- Edit Modal -->
<div class="modal fade" id="editMedicineGroupModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editMedicineGroupForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Medicine Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if ($errors->has('medicine_category') && session('edit_id'))
                        <div class="alert alert-danger">{{ $errors->first('medicine_category') }}</div>
                    @endif

                    <div class="mb-3">
                        <label for="edit_medicine_category" class="form-label">Medicine Group <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_medicine_category" name="group_name"
                            value="{{ old('group_name') }}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const addBtn = document.getElementById("addBtn");
    const operationFields = document.getElementById("medicine_group_fields");

    addBtn.addEventListener("click", function () {
        // Clone the first row
        let firstRow = operationFields.querySelector(".medicine-group-row");
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
document.addEventListener('DOMContentLoaded', function () {    
    createAjaxTable({
    apiUrl: "{{ route('medicine-group') }}",
    tableSelector: "#medicine-group",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><h6 class="mb-0 fs-14 fw-semibold">${item.id}</h6></td>
            <td>${item.group_name}</td>
            <td>
            <button
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                    data-id="${item.id}"
                    data-group_name="${item.group_name}">
                    <i class="ti ti-pencil"></i>
                </button>
                <form action="{{ route('medicine-group.destroy')}}"
                method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value="${item.id}">
                <button onclick="return confirm('Are you sure?')"
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i
                class="ti ti-trash"></i></button>
            </form>
            </td>
        `;
        return row;
    }
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-btn');
        const modal = new bootstrap.Modal(document.getElementById('editMedicineGroupModal'));
        const form = document.getElementById('editMedicineGroupForm');
        const nameInput = document.getElementById('edit_medicine_category');
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.edit-btn');
            if (!button) return;
                    const inputFields = form.querySelectorAll('[data-field]');
                    const fieldValue = button.getAttribute(`data-group_name`);
                    nameInput.value = fieldValue;
                    modal.show();
        });
    });
</script>


@endsection