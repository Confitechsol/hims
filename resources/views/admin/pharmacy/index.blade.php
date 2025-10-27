{{-- resources/views/admin/pharmacy/index.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Pharmacy Medicine List --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-pills me-2"></i>Pharmacy Medicine List</h5>
                </div>

                <div class="card-body">
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
                                            <input type="text" class="form-control shadow-sm" id="searchMedicine" placeholder="Search Medicine">
                                        </div>

                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="{{ route('pharmacy.create') }}"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                    <i class="ti ti-plus me-1"></i>Add Medicine
                                                </a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="{{ route('pharmacy.import') }}"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                    <i class="ti ti-download me-1"></i>Import Medicines
                                                </a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="{{ route('pharmacy.below-min-level') }}"
                                                    class="btn btn-warning text-white ms-2 fs-13 btn-md">
                                                    <i class="ti ti-alert-triangle me-1"></i>Below Min Level
                                                </a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="{{ route('pharmacy.needs-reorder') }}"
                                                    class="btn btn-info text-white ms-2 fs-13 btn-md">
                                                    <i class="ti ti-package me-1"></i>Needs Reorder
                                                </a>
                                            </div>
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
                                                    <th>Medicine Name</th>
                                                    <th>Category</th>
                                                    <th>Company</th>
                                                    <th>Composition</th>
                                                    <th>Group</th>
                                                    <th>Unit</th>
                                                    <th>Stock</th>
                                                    <th>Min Level</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="medicineTableBody">
                                                @forelse($medicines as $medicine)
                                                    <tr>
                                                        <td>{{ $medicine->id }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($medicine->medicine_image)
                                                                    <img src="{{ asset('storage/' . $medicine->medicine_image) }}" 
                                                                         alt="{{ $medicine->medicine_name }}" 
                                                                         class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                                @endif
                                                                <span class="fw-bold">{{ $medicine->medicine_name }}</span>
                                                            </div>
                                                        </td>
                                                        <td>{{ $medicine->medicineCategory->medicine_category ?? '-' }}</td>
                                                        <td>{{ $medicine->company->company_name ?? '-' }}</td>
                                                        <td>{{ $medicine->medicine_composition ?? '-' }}</td>
                                                        <td>{{ $medicine->medicineGroup->group_name ?? '-' }}</td>
                                                        <td>{{ $medicine->unitRelation->unit_name ?? '-' }}</td>
                                                        <td>
                                                            @php
                                                                $totalQty = $medicine->total_quantity ?? 0;
                                                                $minLevel = $medicine->min_level ?? 0;
                                                                $badgeClass = $totalQty <= $minLevel ? 'bg-danger' : ($totalQty <= $medicine->reorder_level ? 'bg-warning' : 'bg-success');
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">{{ $totalQty }}</span>
                                                        </td>
                                                        <td>{{ $medicine->min_level ?? '-' }}</td>
                                                        <td>
                                                            @if($medicine->is_active === 'yes')
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-secondary">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-primary dropdown-toggle" 
                                                                        type="button" 
                                                                        data-bs-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('pharmacy.show', $medicine->id) }}">
                                                                            <i class="ti ti-eye me-2"></i>View
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('pharmacy.edit', $medicine->id) }}">
                                                                            <i class="ti ti-edit me-2"></i>Edit
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <form action="{{ route('pharmacy.destroy', $medicine->id) }}" 
                                                                              method="POST" 
                                                                              class="d-inline"
                                                                              onsubmit="return confirm('Are you sure you want to delete this medicine?')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item text-danger">
                                                                                <i class="ti ti-trash me-2"></i>Delete
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="11" class="text-center text-muted py-4">
                                                            <i class="ti ti-package-off" style="font-size: 3rem;"></i>
                                                            <p class="mt-2">No medicines found</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Pagination --}}
                                    <div class="mt-3">
                                        {{ $medicines->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchMedicine').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#medicineTableBody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    @endpush
@endsection

