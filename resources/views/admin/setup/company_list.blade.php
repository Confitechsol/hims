{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Company List</h5>
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
                                    @if(session('error'))
                                        <div class="alert alert-danger">
                                            {{session('error')}}
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{session('success')}}
                                        </div>
                                    @endif
                                    <x-table-actions.actions id="company" name="Company" /> 
                                    <div class="table-responsive" id="company">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Database ID</th>
                                                    <th>Company Name</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($companys as $key => $item)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{$item->id}}</h6>
                                                        </h6>
                                                    </td>
                                                    <td>{{$item->company_name}}</td>
                                                    <td>
                                                         <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="{{ $item["id"] }}"
                                                            data-company_name="{{$item->company_name}}">
                                                            <i class="ti ti-pencil"></i>
                                                        </button>
                                                        <form action="{{ route('company-list.destroy')}}" method="POST" style="display:inline-block;" onsubmit="return confirmDeleteForm(event, 'Delete Company?', 'Are you sure you want to delete this company?');">
                                                            @csrf
                                                            @method('DELETE')
                                                                <input type="hidden" name="id" value="{{$item->id}}">
                                                                <button type="submit" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i>
                                                                </button>
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
                                        $currentPage = $companys->currentPage();
                                        $lastPage = $companys->lastPage();
                                    @endphp

                                    {{-- Previous --}}
                                    @if ($companys->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $companys->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm me-1">
                                            « Prev
                                        </a>
                                    @endif

                                    {{-- Page numbers --}}
                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $companys->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor

                                    {{-- Next --}}
                                    @if ($companys->hasMorePages())
                                        <a href="{{ $companys->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
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
    <x-modals.form-modal type="add" id="createModal" title="Add company" action="{{route('company-list.store')}}" :repeatable_group="[
        ['name' => 'company_name', 'label' => 'Company Name', 'type' => 'text', 'required' => true,'size'=>'11']
        ]" :columns="2" />
    <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Company Name"
    action="{{route('company-list.update')}}" :fields="[
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'company_name', 'label' => 'Unit Name', 'type' => 'text', 'required' => true, 'size' => '12']
    ]" :columns="2" />
    <script>
document.addEventListener('DOMContentLoaded', function () {    
    createAjaxTable({
    apiUrl: "{{ route('company-list') }}",
    tableSelector: "#company",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><h6 class="mb-0 fs-14 fw-semibold">${item.id}</h6></td>
            <td>${item.company_name}</td>
            <td>
            <button
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                    data-id="${item.id}"
                    data-company_name="${item.company_name}">
                    <i class="ti ti-pencil"></i>
                </button>
                <form action="{{ route('company-list.destroy')}}"
                method="POST" style="display:inline-block;" onsubmit="return confirmDeleteForm(event, 'Delete Company?', 'Are you sure you want to delete this company?');">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id" value="${item.id}">
                <button type="submit"
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