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
                            <th>Type:</th>
                            <td>{{ ($package->package_type ?? 'hospital') === 'insurance' ? 'Insurance' : 'Hospital' }}</td>
                        </tr>
                        @if($package->isInsurance())
                        <tr><th>Procedure Code:</th><td><code>{{ $package->insurer_procedure_code ?? '—' }}</code></td></tr>
                        <tr><th>Speciality:</th><td>{{ $package->speciality ?? '—' }}</td></tr>
                        <tr><th>Panel:</th><td>{{ $package->insuranceRatePanel->name ?? '—' }}</td></tr>
                        <tr><th>Insurance Co.:</th><td>{{ $package->insuranceCompany->name ?? '—' }}</td></tr>
                        <tr><th>Room eligibility:</th><td>{{ $package->room_eligibility ?? '—' }}</td></tr>
                        @if($package->inclusion_notes)
                        <tr><th>Inclusions:</th><td>{{ $package->inclusion_notes }}</td></tr>
                        @endif
                        @endif
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
            @if($package->roomRates->isNotEmpty())
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Room-tier Rates</h6>
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr><th>Bed Group</th><th>Room Code</th><th>Label</th><th>Rate</th></tr>
                        </thead>
                        <tbody>
                            @foreach($package->roomRates as $rr)
                            <tr>
                                <td>{{ $rr->bedGroup->name ?? $rr->bed_group_id }}</td>
                                <td>{{ $rr->insurer_room_code ?? '—' }}</td>
                                <td>{{ $rr->label ?? '—' }}</td>
                                <td>₹{{ number_format($rr->rate, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

