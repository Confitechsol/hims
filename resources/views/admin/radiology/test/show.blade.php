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
                                    <th width="40%">Standard Charge IPD (INR):</th>
                                    <td class="fw-bold">₹{{ number_format($test->standard_charge_ipd ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Standard Charge OPD (INR):</th>
                                    <td class="fw-bold">₹{{ number_format($test->standard_charge_opd ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                    <h5 class="mb-0" style="color: #750096">
                                        <i class="ti ti-shield-check me-2"></i>Insurance Panel Rates
                                    </h5>
                                    <a href="{{ route('radiology.test.edit', $test->id) }}" class="btn btn-sm btn-primary">Edit rates</a>
                                </div>
                                <div class="card-body">
                                    @include('admin.insurance.partials.test_panel_rates', [
                                        'panelRates' => $panelRates,
                                        'testType' => 'radiology',
                                        'editable' => false,
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
