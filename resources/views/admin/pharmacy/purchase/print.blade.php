<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - PHPN{{ $purchase->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
        }
        body { font-family: Arial, sans-serif; }
        .invoice-header { background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%); padding: 20px; color: #750096; }
        .company-info { font-size: 14px; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header invoice-header">
                <div class="row">
                    <div class="col-6">
                        <h3>PURCHASE ORDER</h3>
                        <p class="mb-0"><strong>Purchase No:</strong> PHPN{{ $purchase->id }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <h4>Hospital Name</h4>
                        <p class="company-info mb-0">Address Line 1<br>Address Line 2<br>Phone: XXX-XXX-XXXX</p>
                    </div>
                </div>
            </div>

            <div class="card-body">
                {{-- Supplier and Date Info --}}
                <div class="row mb-4">
                    <div class="col-6">
                        <h5>Supplier Details:</h5>
                        <p class="mb-1"><strong>{{ $purchase->supplier->supplier ?? '-' }}</strong></p>
                        <p class="mb-0">Contact: {{ $purchase->supplier->contact ?? '-' }}</p>
                        <p class="mb-0">Address: {{ $purchase->supplier->address ?? '-' }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <p><strong>Purchase Date:</strong> {{ \Carbon\Carbon::parse($purchase->date)->format('d M Y, h:i A') }}</p>
                        <p><strong>Invoice No:</strong> {{ $purchase->invoice_no ?? '-' }}</p>
                        @if($purchase->payment_mode)
                            <p><strong>Payment Mode:</strong> {{ $purchase->payment_mode }}</p>
                        @endif
                    </div>
                </div>

                {{-- Medicine Details Table --}}
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Sr.</th>
                            <th>Medicine Name</th>
                            <th>Batch No</th>
                            <th>Expiry</th>
                            <th>Qty</th>
                            <th>Purchase Price</th>
                            <th>MRP</th>
                            <th>Tax %</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->batches as $index => $batch)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $batch->pharmacy->medicine_name ?? '-' }}</td>
                                <td>{{ $batch->batch_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($batch->expiry)->format('M Y') }}</td>
                                <td>{{ $batch->quantity }}</td>
                                <td>₹{{ number_format($batch->purchase_price, 2) }}</td>
                                <td>₹{{ number_format($batch->mrp, 2) }}</td>
                                <td>{{ number_format($batch->tax, 2) }}%</td>
                                <td>₹{{ number_format($batch->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="text-end"><strong>Total:</strong></td>
                            <td><strong>₹{{ number_format($purchase->total, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-end">Tax:</td>
                            <td>₹{{ number_format($purchase->tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-end">Discount:</td>
                            <td>₹{{ number_format($purchase->discount, 2) }}</td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="8" class="text-end"><strong>Net Amount:</strong></td>
                            <td><strong>₹{{ number_format($purchase->net_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

                @if($purchase->note)
                <div class="mt-3">
                    <strong>Note:</strong> {{ $purchase->note }}
                </div>
                @endif

                <div class="row mt-5">
                    <div class="col-6">
                        <p>_______________________</p>
                        <p>Supplier Signature</p>
                    </div>
                    <div class="col-6 text-end">
                        <p>_______________________</p>
                        <p>Authorized Signature</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="ti ti-printer me-1"></i>Print
            </button>
            <a href="{{ route('pharmacy.purchase.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>
</div>
@endsection

