@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #750096"><i class="ti ti-package me-2"></i>Package Master</h5>
                <a href="{{ route('packages.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Add Package
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Package Name</th>
                            <th>Account Head</th>
                            <th>Package Rate</th>
                            <th>GST Amount</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                        <tr>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->account_head ?? 'N/A' }}</td>
                            <td>₹{{ number_format($package->package_rate, 2) }}</td>
                            <td>₹{{ number_format($package->gst_amount, 2) }}</td>
                            <td>
                                @if($package->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('packages.show', $package->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="ti ti-pencil"></i>
                                </a>
                                <form action="{{ route('packages.destroy', $package->id) }}" method="POST" style="display:inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this package?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No packages found. <a href="{{ route('packages.create') }}">Create your first package</a></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

