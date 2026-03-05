@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-receipt me-2"></i>Money/Refund Receipt List
                    </h5>
                    <a href="{{ route('money-receipt.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i>New Receipt
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filters -->
                <form method="GET" action="{{ route('money-receipt.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" 
                                placeholder="Receipt No, Patient, Final Bill No..." 
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Receipt Type</label>
                            <select name="receipt_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($receiptTypes as $type)
                                    <option value="{{ $type }}" {{ request('receipt_type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Per Page</label>
                            <select name="perPage" class="form-select" onchange="this.form.submit()">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Receipt No.</th>
                                <th>Date</th>
                                <th>Receipt Type</th>
                                <th>Patient Name</th>
                                <th>Final Bill No.</th>
                                <th>Amount (₹)</th>
                                <th>Payment Mode</th>
                                <th>Received By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receipts as $receipt)
                                <tr>
                                    <td><strong>{{ $receipt->receipt_no }}</strong></td>
                                    <td>{{ $receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $receipt->receipt_type ?? '-' }}</span>
                                    </td>
                                    <td>{{ $receipt->patient->patient_name ?? '-' }}</td>
                                    <td>{{ $receipt->final_bill_no ?? '-' }}</td>
                                    <td><strong>₹ {{ number_format($receipt->amount ?? 0, 2) }}</strong></td>
                                    <td>{{ $receipt->payment_mode ?? '-' }}</td>
                                    <td>{{ $receipt->receiver->name ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('money-receipt.show', $receipt->id) }}" 
                                                class="btn btn-sm btn-info" title="View">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('money-receipt.edit', $receipt->id) }}" 
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <a href="{{ route('money-receipt.print', $receipt->id) }}" 
                                                class="btn btn-sm btn-primary" title="Print" target="_blank">
                                                <i class="ti ti-printer"></i>
                                            </a>
                                            <form method="POST" action="{{ route('money-receipt.destroy', $receipt->id) }}" 
                                                onsubmit="return confirm('Are you sure you want to delete this receipt?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        No money receipts found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($receipts->hasPages())
                <div class="mt-3">
                    {{ $receipts->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
