@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-calendar-day me-2"></i> Daily Collection Report</h5>
                    <a href="{{ route('finance') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i> Finance</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.daily-collection') }}" method="GET">
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
                            <a href="{{ route('reports.daily-collection') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($result)
    <!-- Summary Section -->
    <div class="col-md-11 mt-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0">Summary: {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}</h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('reports.daily-collection.excel') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="ti ti-file-spreadsheet me-1"></i> Export Excel</button>
                    </form>
                    <form action="{{ route('reports.daily-collection.pdf') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-file-text me-1"></i> Export PDF</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title">Total Collection</h6>
                                <h3 class="mb-0">₹ {{ number_format($result['summary']['total_collection'], 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h6 class="card-title">Total Refund</h6>
                                <h3 class="mb-0">₹ {{ number_format($result['summary']['total_refund'], 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title">Net Collection</h6>
                                <h3 class="mb-0">₹ {{ number_format($result['summary']['net_collection'], 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="mb-3">By Payment Mode:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Payment Mode</th>
                                <th class="text-end">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result['summary']['by_payment_mode'] as $mode => $amount)
                                @if($amount != 0)
                                <tr>
                                    <td>{{ $mode }}</td>
                                    <td class="text-end">{{ $amount < 0 ? '-' : '' }}₹ {{ number_format(abs($amount), 2) }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Section -->
    <div class="col-md-11 mt-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0">Detail Transactions</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Receipt No.</th>
                                <th>Receipt Type</th>
                                <th>Patient Name</th>
                                <th class="text-end">Amount (₹)</th>
                                <th>Payment Mode</th>
                                <th>Received By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($result['grouped_by_date'] as $dateKey => $dayData)
                                <tr class="table-secondary">
                                    <td colspan="7" class="fw-bold">{{ $dayData['date'] }}</td>
                                    <td class="text-end fw-bold">Total: ₹ {{ number_format($dayData['net'], 2) }}</td>
                                </tr>
                                @foreach($dayData['rows'] as $r)
                                <tr>
                                    <td>{{ $r['date'] }}</td>
                                    <td>{{ $r['time'] }}</td>
                                    <td>{{ $r['receipt_no'] }}</td>
                                    <td>{{ $r['receipt_type'] }}</td>
                                    <td>{{ $r['patient_name'] }}</td>
                                    <td class="text-end {{ $r['is_refund'] ? 'text-danger' : '' }}">
                                        {{ $r['is_refund'] ? '-' : '' }}₹ {{ number_format($r['amount'], 2) }}
                                    </td>
                                    <td>{{ $r['payment_mode'] }}</td>
                                    <td>{{ $r['received_by'] }}</td>
                                </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No transactions found for the selected date range.</td>
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
