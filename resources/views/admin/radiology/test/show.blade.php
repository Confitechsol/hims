@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-eye me-2"></i>Radiology Test Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="{{ route('radiology.test.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('radiology.test.edit', $test->id) }}" class="btn btn-warning text-white me-2">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                            <button class="btn btn-primary" onclick="window.print()">
                                <i class="ti ti-printer me-1"></i>Print
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Test Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Test Name:</th>
                                    <td>{{ $test->test_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Short Name:</th>
                                    <td>{{ $test->short_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Test Type:</th>
                                    <td>{{ $test->test_type ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $test->radiologyCategory->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Sub Category:</th>
                                    <td>{{ $test->sub_category ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Report Days:</th>
                                    <td>{{ $test->report_days ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3">Charge Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Charge Category:</th>
                                    <td>{{ $test->charge && $test->charge->category ? $test->charge->category->name : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Charge Name:</th>
                                    <td>{{ $test->charge ? $test->charge->name : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tax Category:</th>
                                    <td>{{ $test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->name : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tax Percentage:</th>
                                    <td>{{ $test->charge && $test->charge->taxCategory ? number_format($test->charge->taxCategory->percentage, 2) . '%' : '0%' }}</td>
                                </tr>
                                <tr>
                                    <th>Standard Charge (INR):</th>
                                    <td>₹{{ $test->charge ? number_format($test->charge->standard_charge, 2) : '0.00' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount (INR):</th>
                                    <td class="fw-bold">₹{{ $test->charge ? number_format($test->charge->standard_charge + ($test->charge->standard_charge * ($test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0) / 100), 2) : '0.00' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($tpaCharges && $tpaCharges->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">
                                <i class="fas fa-building me-2"></i>TPA Charges
                            </h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>TPA Organization</th>
                                                    <th>TPA Code</th>
                                                    <th>TPA Charge (INR)</th>
                                                    <th>Standard Charge (INR)</th>
                                                    <th>Difference (INR)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tpaCharges as $index => $tpaCharge)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <strong>{{ $tpaCharge->organisation->organisation_name ?? '-' }}</strong>
                                                        </td>
                                                        <td>{{ $tpaCharge->organisation->code ?? '-' }}</td>
                                                        <td class="fw-bold">₹{{ number_format($tpaCharge->org_charge ?? 0, 2) }}</td>
                                                        <td>₹{{ $test->charge ? number_format($test->charge->standard_charge, 2) : '0.00' }}</td>
                                                        <td>
                                                            @php
                                                                $standardCharge = $test->charge ? $test->charge->standard_charge : 0;
                                                                $tpaChargeAmount = $tpaCharge->org_charge ?? 0;
                                                                $difference = $tpaChargeAmount - $standardCharge;
                                                            @endphp
                                                            <span class="{{ $difference < 0 ? 'text-danger' : ($difference > 0 ? 'text-success' : 'text-muted') }}">
                                                                {{ $difference >= 0 ? '+' : '' }}₹{{ number_format($difference, 2) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                No TPA charges configured for this test. Standard charge will be used for all TPAs.
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

