@extends('layouts.adminLayout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-x-ray me-2"></i>Medicine Master
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                        <form method="GET" action=""
                                            class="input-icon-start position-relative me-2 d-flex align-items-center">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" name="search" class="form-control shadow-sm"
                                                placeholder="Search" value="{{ request('search') }}"
                                                style="max-width: 300px;">
                                            <button type="submit" class="btn btn-primary ms-2">Search</button>
                                        </form>

                                        <div class="page_btn d-flex">
                                            <a href="{{ route('medicine-master.create') }}"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                <i class="ti ti-plus me-1"></i>Add Medicine
                                            </a>
                                            <a href="{{ route('medicine-master.import') }}"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                <i class="ti ti-plus me-1"></i>Import Medicines
                                            </a>
                                        </div>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Price</th>
                                                    <th>Manufacturer Name</th>
                                                    <th>Pack Size Label</th>
                                                    <th>Short Composition 1</th>
                                                    <th>Short Composition 2</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="testTableBody">
                                                @forelse($medicines as $medicine)
                                                    <tr>
                                                        <td>{{ $medicine->id }}</td>
                                                        <td class="fw-bold">{{ $medicine->name }}</td>
                                                        <td>{{ $medicine->medicine_type ?? '-' }}</td>
                                                        <td>{{ $medicine->price }}</td>
                                                        <td>{{ $medicine->manufacturer_name ?? '-' }}</td>
                                                        <td>{{ $medicine->pack_size_label ?? '-' }}</td>
                                                        <td>{{ $medicine->short_composition1 ?? '-' }}</td>
                                                        <td>{{ $medicine->short_composition2 ?? '-' }}</td>
                                                        <td>
                                                            <div class="d-flex gap-2">

                                                                <a href="{{ route('medicine-master.edit', $medicine->id) }}"
                                                                    class="btn btn-sm btn-warning text-white"
                                                                    title="Edit">
                                                                    <i class="ti ti-edit"></i>
                                                                </a>
                                                                <form
                                                                    action="{{ route('medicine-master.delete', $medicine->id) }}"
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirmDeleteForm(event, 'Delete Medicine?', 'Are you sure you want to delete this medicine?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger text-white"
                                                                        title="Delete">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="ti ti-inbox fs-48 mb-2"></i>
                                                                <p>No radiology tests found. Add your first test!</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- Pagination Links --}}

                                    <div class="mt-3 text-end" id="pagination-wrapper">
                                        @php
                                            $currentPage = $medicines->currentPage();
                                            $lastPage = $medicines->lastPage();
                                            $window = 2; // how many pages to show on each side
                                            $start = max(1, $currentPage - $window);
                                            $end = min($lastPage, $currentPage + $window);
                                        @endphp

                                        @if ($medicines->onFirstPage())
                                            <button class="btn btn-outline-primary btn-sm me-1" disabled>« Prev</button>
                                        @else
                                            <a href="{{ $medicines->previousPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-primary btn-sm me-1">« Prev</a>
                                        @endif

                                        @if ($start > 1)
                                            <a href="{{ $medicines->url(1) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-primary btn-sm me-1">1</a>
                                            @if ($start > 2)
                                                <span class="btn btn-outline-primary btn-sm me-1 disabled">...</span>
                                            @endif
                                        @endif

                                        @for ($page = $start; $page <= $end; $page++)
                                            @if ($page == $currentPage)
                                                <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                                            @else
                                                <a href="{{ $medicines->url($page) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                    class="btn btn-outline-primary btn-sm me-1">{{ $page }}</a>
                                            @endif
                                        @endfor

                                        @if ($end < $lastPage)
                                            @if ($end < $lastPage - 1)
                                                <span class="btn btn-outline-primary btn-sm me-1 disabled">...</span>
                                            @endif
                                            <a href="{{ $medicines->url($lastPage) }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-primary btn-sm me-1">{{ $lastPage }}</a>
                                        @endif

                                        @if ($medicines->hasMorePages())
                                            <a href="{{ $medicines->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                                class="btn btn-outline-primary btn-sm">Next »</a>
                                        @else
                                            <button class="btn btn-outline-primary btn-sm" disabled>Next »</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
