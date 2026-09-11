@extends('layouts.adminLayout')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-check-circle me-2"></i> Discharged Patients - Billing Summary
                </h5>
                <div>
                    <a href="{{ route('ipd') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to IPD
                    </a>
                </div>
            </div>

            <div class="card-body">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Summary Stats --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Total Discharged Patients</h6>
                                <h3 class="mb-0">{{ $pagination['total_all_discharged'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Discharged Patients Shown</h6>
                                <h3 class="mb-0 text-success">{{ $pagination['total_zero_balance'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Search and Filter --}}
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <form action="{{ route('ipd.billing.discharged.zero.balance') }}" method="GET" class="d-flex gap-2">
                                    <div>
                                        <input type="text" name="search" class="form-control" placeholder="Search IPD No / Patient Name"
                                            value="{{ request('search') }}">
                                    </div>
                                    {{-- <div>
                                        <select name="per_page" class="form-select">
                                            <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25 per page</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                                        </select>
                                    </div> --}}
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>IPD No.</th>
                                <th>Patient Name</th>
                                <th>Guardian Phone</th>
                                <th>Consultant</th>
                                <th>Bed</th>
                                <th class="text-end">Total Billing (INR)</th>
                                <th class="text-end">Total Payment (INR)</th>
                                <th class="text-end">Outstanding (before discount) (INR)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patients as $index => $patient)
                                <tr>
                                    <td>{{ ($pagination['current_page'] - 1) * $pagination['per_page'] + $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('ipd.show', ['id' => $patient['ipd_id']]) }}" class="text-primary fw-bold">
                                            {{ $patient['ipd_no'] }}
                                        </a>
                                    </td>
                                    <td>{{ $patient['patient_name'] }}</td>
                                    <td>{{ $patient['guardian_phone'] }}</td>
                                    <td>{{ $patient['consultant_name'] }}</td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">
                                            {{ $patient['bed_info'] }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold">{{ number_format($patient['total_charges'], 2) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-success">{{ number_format($patient['total_payments'], 2) }}</span>
                                    </td>
                                    <td class="text-end">
                                        @php
                                            $outstandingAmount = max(0, (float) ($patient['gross_outstanding'] ?? 0));
                                        @endphp
                                        @if ($outstandingAmount <= 0.01)
                                            <span class="badge bg-success-subtle text-success">
                                                ₹ 0.00
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                ₹ {{ number_format($outstandingAmount, 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('ipd.billing.export.final', ['ipdId' => $patient['ipd_id'], 'bill_stage' => 'final_bill']) }}"
                                            class="btn btn-icon btn-sm btn-soft-success rounded-pill"
                                            title="Export Final Bill PDF"
                                            target="_blank">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <p class="text-muted mb-0">No discharged patients found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-3" id="pagination-wrapper">
                    @php
                        $currentPage = $pagination['current_page'];
                        $lastPage = $pagination['total_pages'];
                    @endphp

                    {{-- Previous --}}
                    @if ($currentPage == 1)
                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                    @else
                        <a href="{{ route('ipd.billing.discharged.zero.balance', ['page' => $currentPage - 1]) }}{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                            class="btn btn-outline-secondary btn-sm me-1">
                            « Prev
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @for ($page = 1; $page <= $lastPage; $page++)
                        @if ($page == $currentPage)
                            <button class="btn btn-primary btn-sm me-1">{{ $page }}</button>
                        @else
                            <a href="{{ route('ipd.billing.discharged.zero.balance', ['page' => $page]) }}{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                                class="btn btn-outline-secondary btn-sm me-1">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    {{-- Next --}}
                    @if ($currentPage < $lastPage)
                        <a href="{{ route('ipd.billing.discharged.zero.balance', ['page' => $currentPage + 1]) }}{{ request('search') ? '&search=' . urlencode(request('search')) : '' }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}"
                            class="btn btn-outline-secondary btn-sm">
                            Next »
                        </a>
                    @else
                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
