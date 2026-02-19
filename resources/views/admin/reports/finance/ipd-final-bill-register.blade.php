@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-file-invoice me-2"></i> IPD Final Bill Register</h5>
                    <a href="{{ route('finance') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i> Finance</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.ipd-final-bill-register') }}" method="GET">
                    <div class="row align-items-end gy-3">
                        <div class="col-md-3">
                            <label class="form-label">Date From <span class="text-danger">*</span></label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To <span class="text-danger">*</span></label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            <a href="{{ route('reports.ipd-final-bill-register') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($result)
    <div class="col-md-11 mt-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0">IPD Final Bill Register: {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }} (by Bill/Discharge Date)</h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('reports.ipd-final-bill-register.excel') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="ti ti-file-spreadsheet me-1"></i> Export Excel</button>
                    </form>
                    <form action="{{ route('reports.ipd-final-bill-register.pdf') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-file-text me-1"></i> Export PDF</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Admission Number</th>
                                <th>Admission Date</th>
                                <th>IPD Number</th>
                                <th>Patient Name</th>
                                <th>Bill Number</th>
                                <th>Bill Date</th>
                                <th>Charge Category Head</th>
                                <th>Charge Details</th>
                                <th class="text-end">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($result['rows'] as $r)
                            <tr>
                                <td>{{ $r['admission_number'] }}</td>
                                <td>{{ $r['admission_date'] }}</td>
                                <td>{{ $r['ipd_number'] }}</td>
                                <td>{{ $r['patient_name'] }}</td>
                                <td>{{ $r['bill_number'] }}</td>
                                <td>{{ $r['bill_date'] }}</td>
                                <td>{{ $r['charge_category_head'] }}</td>
                                <td>{{ $r['charge_details'] }}</td>
                                <td class="text-end">{{ $r['amount'] < 0 ? '-' : '' }}₹ {{ number_format(abs($r['amount']), 2) }}</td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">No IPD final bills found for the selected date range (discharge date).</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
