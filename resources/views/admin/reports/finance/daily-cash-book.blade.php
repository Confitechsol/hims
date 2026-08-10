@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-book me-2"></i> Daily Cash Book</h5>
                    <a href="{{ route('finance') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i> Finance</a>
                </div>
            </div>
            <div class="card-body">
                @if(!empty($error))
                    <div class="alert alert-danger">{{ $error }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form action="{{ route('reports.daily-cash-book') }}" method="GET">
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
                            <a href="{{ route('reports.daily-cash-book') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        Opening balance (Total Clear Balance) is the previous day&apos;s closing balance. Before the first day of recorded receipts/expenses it is zero.
                        Cash receipts are reduced by daily expenses in the closing balance; UPI and bank transfer receipts are added in full.
                    </p>
                </form>
            </div>
        </div>
    </div>

    @if($result)
    <div class="col-md-11 mt-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="mb-0">{{ $result['company_name'] ?? 'Company' }}</h6>
                    <small class="text-muted">DAILY CASH BOOK REGISTER — {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}</small>
                </div>
                <div class="d-flex gap-2">
                    <form action="{{ route('reports.daily-cash-book.excel') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="ti ti-file-spreadsheet me-1"></i> Export Excel</button>
                    </form>
                    <form action="{{ route('reports.daily-cash-book.pdf') }}" method="GET" class="d-inline">
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
                                <th class="text-end">Total Clear Balance (₹)</th>
                                <th>Date</th>
                                <th class="text-end">Total Cash Receipt (₹)</th>
                                <th class="text-end">Total UPI Receipt (₹)</th>
                                <th class="text-end">Total Transfer to Bank (₹)</th>
                                <th class="text-end">Total Received (₹)</th>
                                <th class="text-end">Total Expense (₹)</th>
                                <th class="text-end">Balance Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($result['rows'] as $row)
                                <tr>
                                    <td class="text-end">{{ number_format($row['total_clear_balance'], 2) }}</td>
                                    <td>{{ $row['date'] }}</td>
                                    <td class="text-end">{{ number_format($row['total_cash'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['total_upi'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['total_transfer'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['total_received'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['total_expense'], 2) }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($row['balance_amount'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No days in selected range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(!empty($result['rows']))
                        <tfoot class="table-secondary">
                            <tr>
                                <td class="text-end">—</td>
                                <td><strong>Total</strong></td>
                                <td class="text-end"><strong>{{ number_format($result['totals']['total_cash'], 2) }}</strong></td>
                                <td class="text-end"><strong>{{ number_format($result['totals']['total_upi'], 2) }}</strong></td>
                                <td class="text-end"><strong>{{ number_format($result['totals']['total_transfer'], 2) }}</strong></td>
                                <td class="text-end"><strong>{{ number_format($result['totals']['total_received'], 2) }}</strong></td>
                                <td class="text-end"><strong>{{ number_format($result['totals']['total_expense'], 2) }}</strong></td>
                                <td class="text-end"><strong>{{ number_format($result['totals']['balance_amount'], 2) }}</strong></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
