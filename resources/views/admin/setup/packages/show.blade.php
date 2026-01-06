@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #750096">
                    <i class="ti ti-package me-2"></i>Package Details
                </h5>
                <div>
                    <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-warning">
                        <i class="ti ti-pencil"></i> Edit
                    </a>
                    <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Package Information</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Package Name:</th>
                            <td>{{ $package->name }}</td>
                        </tr>
                        <tr>
                            <th>Account Head:</th>
                            <td>{{ $package->account_head ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Package Rate:</th>
                            <td>₹{{ number_format($package->package_rate, 2) }}</td>
                        </tr>
                        <tr>
                            <th>GST Amount:</th>
                            <td>₹{{ number_format($package->gst_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($package->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @if($package->description)
                        <tr>
                            <th>Description:</th>
                            <td>{{ $package->description }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Service Includes</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Charge Type</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($package->charges as $charge)
                            <tr>
                                <td>{{ $charge->charge_type }}</td>
                                <td>₹{{ number_format($charge->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">No charges defined</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

