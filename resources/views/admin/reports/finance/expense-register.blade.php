@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-file-invoice-dollar me-2"></i> Expense Register</h5>
                    <a href="{{ route('finance') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i> Finance</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.expense-register') }}" method="GET">
                    <div class="row align-items-end gy-3">
                        <div class="col-md-3">
                            <label class="form-label">Date From <span class="text-danger">*</span></label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To <span class="text-danger">*</span></label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type" class="form-select">
                                @foreach($paymentTypeOptions as $value => $label)
                                    <option value="{{ $value }}" {{ (string)($paymentType ?? '') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            <a href="{{ route('reports.expense-register') }}" class="btn btn-secondary btn-sm">Reset</a>
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
                <h6 class="mb-0">Expense Register: {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}
                    @if($result['payment_type'])
                        (Payment: {{ $result['payment_type'] }})
                    @endif
                </h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('reports.expense-register.excel') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <input type="hidden" name="payment_type" value="{{ $result['payment_type'] ?? '' }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="ti ti-file-spreadsheet me-1"></i> Export Excel</button>
                    </form>
                    <form action="{{ route('reports.expense-register.pdf') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <input type="hidden" name="payment_type" value="{{ $result['payment_type'] ?? '' }}">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-file-text me-1"></i> Export PDF</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Expense Head</th>
                                <th>Expense Receipt No.</th>
                                <th class="text-end">Amount (₹)</th>
                                <th>Payment Type</th>
                                <th>Username (Entered By)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($result['grouped'] as $date => $items)
                                <tr class="table-secondary">
                                    <td colspan="6" class="fw-bold">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                </tr>
                                @foreach($items as $r)
                                <tr>
                                    <td>{{ $r['date'] }}</td>
                                    <td>{{ $r['expense_head'] }}</td>
                                    <td>{{ $r['expense_receipt_no'] }}</td>
                                    <td class="text-end">₹ {{ number_format($r['amount'], 2) }}</td>
                                    <td>{{ $r['payment_mode'] }}</td>
                                    <td>{{ $r['username'] }}</td>
                                </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No expenses found for the selected date range.</td>
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
