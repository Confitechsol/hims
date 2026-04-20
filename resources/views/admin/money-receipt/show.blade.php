@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-receipt me-2"></i>Money/Refund Receipt - {{ $receipt->receipt_no }}
                    </h5>
                    <div>
                        <a href="{{ route('money-receipt.print', $receipt->id) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="ti ti-printer me-1"></i>Print
                        </a>
                        <a href="{{ route('money-receipt.edit', $receipt->id) }}" class="btn btn-warning btn-sm">
                            <i class="ti ti-pencil me-1"></i>Edit
                        </a>
                        <a href="{{ route('money-receipt.index') }}" class="btn btn-secondary btn-sm">
                            <i class="ti ti-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Receipt Detail -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Receipt Detail</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"><strong>Receipt No.:</strong> {{ $receipt->receipt_no }}</div>
                            <div class="col-md-3"><strong>Date:</strong> {{ $receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('d/m/Y') : '-' }}</div>
                            <div class="col-md-3"><strong>Time:</strong> {{ $receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('H:i:s') : '-' }}</div>
                            <div class="col-md-3"><strong>Receipt Type:</strong> <span class="badge bg-info">{{ $receipt->receipt_type ?? '-' }}</span></div>
                            <div class="col-md-3"><strong>Slip No.:</strong> {{ $receipt->slip_no ?? '-' }}</div>
                            <div class="col-md-3"><strong>Booking No.:</strong> {{ $receipt->booking_no ?? '-' }}</div>
                            <div class="col-md-6"><strong>Final Bill No.:</strong> {{ $receipt->final_bill_no ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Patient Detail -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Patient Detail</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4"><strong>Patient Name:</strong> {{ $receipt->patient->patient_name ?? '-' }}</div>
                            <div class="col-md-4"><strong>Phone:</strong> {{ $receipt->patient->mobileno ?? '-' }}</div>
                            <div class="col-md-4"><strong>Address:</strong> {{ $receipt->patient->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Charges -->
                @if($receipt->ipd && $receipt->ipd->due_patient_party_amount > 0)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-user-doctor me-2"></i>Doctor Charges</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6"><strong>Doctor:</strong> {{ $receipt->ipd->duePatientPartyDoctor->name ?? '' }} {{ $receipt->ipd->duePatientPartyDoctor->surname ?? '' }}</div>
                            <div class="col-md-6"><strong>Amount:</strong> ₹ {{ number_format($receipt->ipd->due_patient_party_amount, 2) }}</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payment Detail -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Payment Detail</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $finalAmount = max(0, (float)($receipt->amount ?? 0) - (float)($receipt->discount ?? 0) - (float)($receipt->tds ?? 0));
                        @endphp
                        <div class="row">
                            <div class="col-md-3"><strong>Amount:</strong> ₹ {{ number_format($receipt->amount ?? 0, 2) }}</div>
                            <div class="col-md-3"><strong>Discount:</strong> ₹ {{ number_format($receipt->discount ?? 0, 2) }}</div>
                            <div class="col-md-3"><strong>TDS:</strong> ₹ {{ number_format($receipt->tds ?? 0, 2) }}</div>
                            <div class="col-md-3"><strong>Final Amount:</strong> ₹ {{ number_format($finalAmount, 2) }}</div>
                            <div class="col-md-3"><strong>Payment Mode:</strong> {{ $receipt->payment_mode ?? '-' }}</div>
                            <div class="col-md-3"><strong>Remarks:</strong> {{ $receipt->remarks ?? '-' }}</div>
                            <div class="col-md-4"><strong>Paid By:</strong> {{ $receipt->paid_by ?? '-' }}</div>
                            <div class="col-md-4"><strong>Narration:</strong> {{ $receipt->narration ?? '-' }}</div>
                            <div class="col-md-4"><strong>Note:</strong> {{ $receipt->note ?? '-' }}</div>
                            @if($receipt->cheque_no)
                            <div class="col-md-4"><strong>Bank Name:</strong> {{ $receipt->bank_name ?? '-' }}</div>
                            <div class="col-md-4"><strong>Cheque No.:</strong> {{ $receipt->cheque_no }}</div>
                            <div class="col-md-4"><strong>Cheque Date:</strong> {{ $receipt->cheque_date ? \Carbon\Carbon::parse($receipt->cheque_date)->format('d/m/Y') : '-' }}</div>
                            @endif
                            <div class="col-md-6"><strong>Received By:</strong> {{ $receipt->receiver->username ?? '-' }}</div>
                            <div class="col-md-6"><strong>Created At:</strong> {{ $receipt->created_at ? \Carbon\Carbon::parse($receipt->created_at)->format('d/m/Y H:i:s') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
