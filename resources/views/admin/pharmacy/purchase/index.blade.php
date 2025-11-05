{{-- resources/views/admin/pharmacy/purchase/index.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-shopping-cart me-2"></i>Medicine Purchase List
                    </h5>
                </div>

                <div class="card-body">
                    <div class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                        <div class="input-icon-start position-relative me-2">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" class="form-control shadow-sm" id="searchPurchase" placeholder="Search Purchase">
                        </div>

                        <div class="page_btn d-flex">
                            <div class="text-end">
                                <a href="{{ route('pharmacy.purchase.create') }}"
                                    class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                    <i class="ti ti-plus me-1"></i>Purchase Medicine
                                </a>
                            </div>
                        </div>
                    </div>

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

                    <div class="table-responsive">
                        <table class="table mb-0 table-hover" id="purchaseTable">
                            <thead>
                                <tr>
                                    <th>Pharmacy Purchase No</th>
                                    <th>Purchase Date</th>
                                    <th>Bill No</th>
                                    <th>Supplier Name</th>
                                    <th>Total (INR)</th>
                                    <th>Tax (INR)</th>
                                    <th>Discount (INR)</th>
                                    <th>Net Amount (INR)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchases as $purchase)
                                    <tr>
                                        <td class="fw-bold text-primary">PHPN{{ $purchase->id }}</td>
                                        <td>{{ $purchase->date ? \Carbon\Carbon::parse($purchase->date)->format('d/m/Y h:i A') : '-' }}</td>
                                        <td>{{ $purchase->invoice_no ?? '-' }}</td>
                                        <td>{{ $purchase->supplier->supplier ?? '-' }}</td>
                                        <td>₹{{ number_format($purchase->total, 2) }}</td>
                                        <td>₹{{ number_format($purchase->tax, 2) }}</td>
                                        <td>₹{{ number_format($purchase->discount, 2) }}</td>
                                        <td class="fw-bold">₹{{ number_format($purchase->net_amount, 2) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('pharmacy.purchase.show', $purchase->id) }}">
                                                            <i class="ti ti-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('pharmacy.purchase.edit', $purchase->id) }}">
                                                            <i class="ti ti-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('pharmacy.purchase.print', $purchase->id) }}" target="_blank">
                                                            <i class="ti ti-printer me-2"></i>Print
                                                        </a>
                                                    </li>
                                                    @if($purchase->attachment)
                                                    <li>
                                                        <a class="dropdown-item" href="{{ asset('storage/' . $purchase->attachment) }}" download>
                                                            <i class="ti ti-download me-2"></i>Download
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="ti ti-package-off" style="font-size: 3rem;"></i>
                                            <p class="mt-2">No purchase records found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchPurchase').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#purchaseTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    @endpush
@endsection

