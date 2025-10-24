{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    {{-- Settings Form --}}
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Dosage Interval List</h5>
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
                                <x-table-actions.actions id="dosage-interval" name="Dosage Interval" /> 
                                <div class="table-responsive">
                                    <table class="table mb-0" id="dosage-interval">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th style="width: 200px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dosageInterval as $item)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">{{$item->name}}</h6>
                                                    </td>
                                                    <td>
                                                        <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="{{ $item["id"] }}" data-name="{{ $item["name"] }}">
                                                            <i class="ti ti-pencil"></i></button>
                                                        <form action="{{ route('dosage-interval.destroy')}}" method="POST"
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
                                {{-- Pagination Links --}}
                                <div class="mt-3" id="pagination-wrapper">
                                    @php
                                        $currentPage = $dosageInterval->currentPage();
                                        $lastPage = $dosageInterval->lastPage();
                                    @endphp

                                    {{-- Previous --}}
                                    @if ($dosageInterval->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    @else
                                        <a href="{{ $dosageInterval->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                            class="btn btn-outline-secondary btn-sm me-1">
                                            « Prev
                                        </a>
                                    @endif

                                    {{-- Page numbers --}}
                                    @for ($page = 1; $page <= $lastPage; $page++)
                                        @if ($page == $currentPage)
                                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                        @else
                                            <a href="{{ $dosageInterval->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor

                                    {{-- Next --}}
                                    @if ($dosageInterval->hasMorePages())
                                        <a href="{{ $dosageInterval->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
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
<x-modals.form-modal type="add" id="createModal" title="Add Dosage Interval"
    action="{{route('dosage-interval.store')}}" :repeatable_group="[
        ['name' => 'name', 'label' => 'Dosage Interval', 'type' => 'text', 'required' => true, 'size' => '11']
    ]" :columns="2" />
<x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit Dosage Interval"
    action="{{route('dosage-interval.update')}}" :fields="[
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'name', 'label' => 'Interval', 'type' => 'text', 'required' => true, 'size' => '12'],
    ]" :columns="2" />
<script>
document.addEventListener('DOMContentLoaded', function () {    
    createAjaxTable({
    apiUrl: "{{ route('dosage-interval') }}",
    tableSelector: "#dosage-interval",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><h6 class="mb-0 fs-14 fw-semibold">${item.name}</h6></td>
            <td>
            <button
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                    data-id="${item.id}"
                    data-name="${item.name}">
                    <i class="ti ti-pencil"></i>
                </button>
                <form action="{{ route('dosage-interval.destroy')}}"
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