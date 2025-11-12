@extends('layouts.adminLayout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-edit me-2"></i>Edit Pathology Bill</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pathology.billing.update', $bill->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Patient and Bill Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-select" required>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ $bill->patient_id == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->patient_name }} (ID: {{ $patient->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Case Reference</label>
                            <input type="text" name="case_reference_id" class="form-control" value="{{ $bill->case_reference_id }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reference Doctor</label>
                            <select name="doctor_id" class="form-select">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $bill->doctor_id == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }} {{ $doctor->surname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reporting Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($bill->date)) }}" required>
                        </div>
                    </div>

                    <!-- Test Selection Table -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3">Pathology Test Details</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Report Days</th>
                                            <th>Report Date</th>
                                            <th>Tax (%)</th>
                                            <th>Amount (INR)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill->reports as $index => $report)
                                        <tr>
                                            <td>
                                                <select name="tests[{{ $index }}][pathology_id]" class="form-select" required>
                                                    @foreach($tests as $test)
                                                        <option value="{{ $test->id }}" {{ $report->pathology_id == $test->id ? 'selected' : '' }}>
                                                            {{ $test->test_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[{{ $index }}][report_days]" class="form-control" value="{{ $report->pathology->report_days ?? 0 }}" readonly>
                                            </td>
                                            <td>
                                                <input type="date" name="tests[{{ $index }}][report_date]" class="form-control" value="{{ date('Y-m-d', strtotime($report->reporting_date)) }}" required>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[{{ $index }}][tax_percentage]" class="form-control" value="{{ $report->tax_percentage }}" step="0.01" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[{{ $index }}][amount]" class="form-control" value="{{ $report->apply_charge }}" step="0.01" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6 offset-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table mb-0">
                                        <tr>
                                            <th>Total Amount</th>
                                            <td class="text-end">₹{{ number_format($bill->total, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td class="text-end">
                                                <input type="number" name="discount_percentage" class="form-control" value="{{ $bill->discount_percentage }}" step="0.01" min="0" max="100">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Discount (INR)</th>
                                            <td class="text-end">₹{{ number_format($bill->discount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tax (INR)</th>
                                            <td class="text-end">₹{{ number_format($bill->tax, 2) }}</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <th>Net Amount</th>
                                            <td class="text-end fw-bold">₹{{ number_format($bill->net_amount, 2) }}</td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="total" value="{{ $bill->total }}">
                                    <input type="hidden" name="discount" value="{{ $bill->discount }}">
                                    <input type="hidden" name="tax" value="{{ $bill->tax }}">
                                    <input type="hidden" name="tax_percentage" value="{{ $bill->tax_percentage }}">
                                    <input type="hidden" name="net_amount" value="{{ $bill->net_amount }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="2">{{ $bill->note }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('pathology.billing.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

