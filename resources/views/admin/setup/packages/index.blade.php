@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0" style="color: #750096"><i class="ti ti-package me-2"></i>Package Master</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('packages.room-mappings') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-bed"></i> Room Mappings
                    </a>
                    <a href="{{ route('packages.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Add Package
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <select name="package_type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All types</option>
                        <option value="hospital" {{ request('package_type') === 'hospital' ? 'selected' : '' }}>Hospital</option>
                        <option value="insurance" {{ request('package_type') === 'insurance' ? 'selected' : '' }}>Insurance</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="insurance_rate_panel_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All panels</option>
                        @foreach($ratePanels as $panel)
                            <option value="{{ $panel->id }}" {{ (string) request('insurance_rate_panel_id') === (string) $panel->id ? 'selected' : '' }}>{{ $panel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, code, speciality" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-secondary w-100">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Code / Speciality</th>
                            <th>Panel / Insurer</th>
                            <th>Base Rate</th>
                            <th>Room Tiers</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                        <tr>
                            <td>
                                @if(($package->package_type ?? 'hospital') === 'insurance')
                                    <span class="badge bg-info">Insurance</span>
                                @else
                                    <span class="badge bg-secondary">Hospital</span>
                                @endif
                            </td>
                            <td>{{ $package->name }}</td>
                            <td>
                                @if($package->insurer_procedure_code)
                                    <code>{{ $package->insurer_procedure_code }}</code><br>
                                @endif
                                <small class="text-muted">{{ $package->speciality ?? '—' }}</small>
                            </td>
                            <td>
                                <small>{{ $package->insuranceRatePanel->name ?? '—' }}</small>
                                @if($package->insuranceCompany)
                                    <br><small class="text-muted">{{ $package->insuranceCompany->name }}</small>
                                @endif
                            </td>
                            <td>₹{{ number_format($package->package_rate, 2) }}</td>
                            <td>{{ $package->room_rates_count ?? 0 }}</td>
                            <td>
                                @if($package->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('packages.show', $package->id) }}" class="btn btn-sm btn-info"><i class="ti ti-eye"></i></a>
                                <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-sm btn-warning"><i class="ti ti-pencil"></i></a>
                                <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this package?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">No packages found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
