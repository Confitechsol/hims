@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-money-bill-wave me-2"></i> Cash Register</h5>
                    <a href="{{ route('finance') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i> Finance</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.cash-register') }}" method="GET">
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
                            <a href="{{ route('reports.cash-register') }}" class="btn btn-secondary btn-sm">Reset</a>
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
                <h6 class="mb-0">Cash Register Report: {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}</h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('reports.cash-register.excel') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="ti ti-file-spreadsheet me-1"></i> Export Excel</button>
                    </form>
                    <form action="{{ route('reports.cash-register.pdf') }}" method="GET" class="d-inline">
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
                                <th>Admission Date</th>
                                <th>Admission Number</th>
                                <th>Patient Name</th>
                                <th>Receipt Number</th>
                                <th>Receipt Date</th>
                                <th class="text-end">Receipt Amount</th>
                                <th>Case/Prescription No</th>
                                <th class="text-end">Discount Amount</th>
                                <th>Bed Number</th>
                                <th>Username (Entered By)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($result['grouped'] as $date => $receiptTypes)
                                @php
                                    $dayTotal = 0;
                                    $dayDiscountTotal = 0;
                                @endphp
                                @foreach($receiptTypes as $receiptType => $items)
                                    @php
                                        $typeTotal = 0;
                                        $typeDiscountTotal = 0;
                                    @endphp
                                    <tr class="table-secondary">
                                        <td colspan="10" class="fw-bold">
                                            {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} — {{ $receiptType }}
                                        </td>
                                    </tr>
                                    @foreach($items as $r)
                                    @php
                                        $typeTotal += $r['receipt_amount'];
                                        $typeDiscountTotal += $r['discount_amount'];
                                        $dayTotal += $r['receipt_amount'];
                                        $dayDiscountTotal += $r['discount_amount'];
                                    @endphp
                                    <tr>
                                        <td>{{ $r['admission_date'] }}</td>
                                        <td>{{ $r['admission_number'] }}</td>
                                        <td>{{ $r['patient_name'] }}</td>
                                        <td>{{ $r['receipt_no'] }}</td>
                                        <td>{{ $r['receipt_date'] }}</td>
                                        <td class="text-end">₹ {{ number_format($r['receipt_amount'], 2) }}</td>
                                        <td>{{ $r['case_prescription_no'] }}</td>
                                        <td class="text-end">₹ {{ number_format($r['discount_amount'], 2) }}</td>
                                        <td>{{ $r['bed_number'] }}</td>
                                        <td>{{ $r['username'] }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-warning">
                                        <td colspan="5" class="fw-bold text-end">{{ $receiptType }} Total:</td>
                                        <td class="text-end fw-bold">₹ {{ number_format($typeTotal, 2) }}</td>
                                        <td></td>
                                        <td class="text-end fw-bold">₹ {{ number_format($typeDiscountTotal, 2) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                @endforeach
                                <tr class="table-info">
                                    <td colspan="5" class="fw-bold text-end">Day Total:</td>
                                    <td class="text-end fw-bold">₹ {{ number_format($dayTotal, 2) }}</td>
                                    <td></td>
                                    <td class="text-end fw-bold">₹ {{ number_format($dayDiscountTotal, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">No cash receipts found for the selected date range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($result && count($result['rows']) > 0)
                        <tfoot class="table-dark">
                            <tr>
                                <td colspan="5" class="fw-bold text-end">Grand Total:</td>
                                <td class="text-end fw-bold">₹ {{ number_format(collect($result['rows'])->sum('receipt_amount'), 2) }}</td>
                                <td></td>
                                <td class="text-end fw-bold">₹ {{ number_format(collect($result['rows'])->sum('discount_amount'), 2) }}</td>
                                <td colspan="2"></td>
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
