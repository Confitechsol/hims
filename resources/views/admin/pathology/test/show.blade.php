@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-eye me-2"></i>Pathology Test Details
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="{{ route('pathology.test.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('pathology.test.edit', $test->id) }}" class="btn btn-warning text-white">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Test Name:</th>
                                    <td>{{ $test->test_name }}</td>
                                </tr>
                                <tr>
                                    <th>Short Name:</th>
                                    <td>{{ $test->short_name }}</td>
                                </tr>
                                <tr>
                                    <th>Test Type:</th>
                                    <td>{{ $test->test_type ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $test->category->category_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Sub Category:</th>
                                    <td>{{ $test->sub_category ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Method:</th>
                                    <td>{{ $test->method ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Report Days:</th>
                                    <td>{{ $test->report_days ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Charge Category:</th>
                                    <td>{{ $test->chargeCategory->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Charge Name:</th>
                                    <td>{{ $test->charge->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Standard Charge:</th>
                                    <td>₹{{ number_format($test->standard_charge ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Tax (%):</th>
                                    <td>{{ $test->charge && $test->charge->taxCategory ? number_format($test->charge->taxCategory->percentage, 2) : '0.00' }}%</td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td class="fw-bold">₹{{ number_format($test->amount ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($test->parameters && $test->parameters->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Test Parameters</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Parameter Name</th>
                                                <th>Reference Range</th>
                                                <th>Unit</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($test->parameters as $index => $paramDetail)
                                                @php
                                                    $parameter = $paramDetail->parameter;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $parameter->parameter_name ?? '-' }}</td>
                                                    <td>{{ $parameter->reference_range ?? '-' }}</td>
                                                    <td>{{ $parameter->unitRelation->unit_name ?? '-' }}</td>
                                                    <td>{{ $parameter->description ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

