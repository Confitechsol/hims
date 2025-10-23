{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    {{-- Settings Form --}}
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Medicine Category List</h5>
            </div>

            <div class="card-body">


                {{-- Hospital Name & Code --}}
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <x-table-actions.actions id="medicine-category" name="Medicine Category" />
                                {{-- <div
                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                    <div class="input-icon-start position-relative me-2">
                                        <span class="input-icon-addon">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" class="form-control shadow-sm" placeholder="Search">

                                    </div>
                                    <div class="text-end d-flex">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary text-white ms-2 fs-13 btn-md" data-bs-toggle="modal"
                                            data-bs-target="#add_medicine_category"><i class="ti ti-plus me-1"></i>Add
                                            Medicine Category</a>
                                    </div> --}}
                                    <!-- Modal -->
                                    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Add Medicine
                                                        Category
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('medicine-categories.storeMultiple') }}" method="POST">
                                                        @csrf
                                                        <div class="row gy-3">

                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Category Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="category_name[]"
                                                                    id="category_name"  class="form-control" required>
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
                                <div class="table-responsive">
                                    <table class="table mb-0" id="medicine-category">
                                        <thead>
                                            <tr>
                                                <th>Category Name</th>
                                                <th style="width: 200px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">
                                                    {{ $category->medicine_category }}
                                                    </h6>
                                                    </td>
                                                    <td>
                                                        <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="{{ $category->id }}"
                                                            data-medicine_category="{{ $category->medicine_category }}"><i
                                                                class="ti ti-pencil"></i></button>

                                                        <form action="{{ route('medicine-categories.destroy')}}"
                                                            method="POST" style="display:inline-block;">
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
{{-- <div class="modal fade" id="editMedicineGroupModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editMedicineGroupForm" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Medicine Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          @if ($errors->has('medicine_category') && session('edit_id'))
              <div class="alert alert-danger">{{ $errors->first('medicine_category') }}</div>
          @endif

          <div class="mb-3">
            <label for="edit_medicine_category" class="form-label">Medicine Group <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_medicine_category" name="medicine_category"
                   value="{{ old('medicine_category') }}">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div> --}}
<x-modals.form-modal 
    id="edit_modal"
    title="Edit Medicine Category"
    action="{{url('/')}}/medicine-categories/${id}"
    method="put"
    type="edit"
    :fields="[
        ['name' => 'id', 'label' => '', 'type' => 'hidden', 'required' => true],
        ['name' => 'medicine_category', 'label' => 'Medicine Group', 'type' => 'text', 'required' => true]
    ]"
    :columns="1"
/>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    createAjaxTable({
    apiUrl: "{{ route('medicine-categories') }}",
    tableSelector: "#medicine-category",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><h6 class="mb-0 fs-14 fw-semibold">${item.medicine_category}</h6></td>
            <td>
            <button
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                    data-id="${item.id}"
                    data-name="${item.medicine_category}">
                    <i class="ti ti-pencil"></i>
                </button>
                <form action="{{ route('medicine-categories.destroy')}}"
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


@endsection