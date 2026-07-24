@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-file-invoice me-2"></i> Final Bill Register</h5>
                    <a href="{{ route('finance') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i> Finance</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(!empty($reportError))
                    <div class="alert alert-danger">{{ $reportError }}</div>
                @endif

                <form action="{{ route('reports.ipd-final-bill-register') }}" method="GET">
                    <div class="row align-items-end gy-3">
                        <div class="col-md-3">
                            <label class="form-label">Date From <span class="text-danger">*</span></label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To <span class="text-danger">*</span></label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm">Generate Report</button>
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
                <h6 class="mb-0">
                    Final Bill Register:
                    {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }}
                    to
                    {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}
                    ({{ $result['patient_count'] ?? 0 }} discharged final bill(s))
                </h6>
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
                @if(!empty($result['errors']))
                    <div class="alert alert-warning">
                        <strong>{{ count($result['errors']) }} bill(s) could not be included:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($result['errors'] as $err)
                                <li>IPD {{ $err['ipd_no'] ?? $err['ipd_id'] }} — {{ $err['message'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>Bill Date</th>
                                <th class="text-end">Bed Ch.</th>
                                <th class="text-end">Diag Ch</th>
                                <th class="text-end">Other Ch</th>
                                <th class="text-end">Service Ch</th>
                                <th class="text-end">Home Amt</th>
                                <th class="text-end">Disc Amt</th>
                                <th class="text-end">Dr Visit</th>
                                <th class="text-end">Package</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result['rows'] as $row)
                            <tr>
                                <td>{{ $row['bill_date'] }}</td>
                                <td class="text-end">{{ number_format($row['bed_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($row['diagnosis_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($row['other_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($row['service_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($row['home_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($row['discount_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($row['doctor_visit_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($row['package_amount'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td>Gross Total</td>
                                <td class="text-end">{{ number_format($result['grand_total']['bed_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['diagnosis_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['other_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['service_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['home_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['discount_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['doctor_visit_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['package_amount'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
