@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-file-invoice me-2"></i>Pathology Bill List
                    </h5>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="input-icon-start position-relative">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" class="form-control shadow-sm" id="searchBill" placeholder="Search Bill">
                        </div>
                        <a href="{{ route('pathology.billing.create') }}" class="btn btn-primary text-white">
                            <i class="ti ti-plus me-1"></i>Generate Bill
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Bill No</th>
                                    <th>Case ID</th>
                                    <th>Reporting Date</th>
                                    <th>Patient Name</th>
                                    <th>Reference Doctor</th>
                                    <th>Discount (INR)</th>
                                    <th>Amount (INR)</th>
                                    <th>Paid Amount (INR)</th>
                                    <th>Balance Amount (INR)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bills as $bill)
                                    <tr>
                                        <td>PATB{{ str_pad($bill->id, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $bill->case_reference_id ?? '-' }}</td>
                                        <td>{{ $bill->date ? date('m/d/Y h:i A', strtotime($bill->date)) : '-' }}</td>
                                        <td>{{ $bill->patient->patient_name ?? '-' }} ({{ $bill->patient_id ?? '-' }})</td>
                                        <td>{{ $bill->doctor_name ?? '-' }}</td>
                                        <td>{{ number_format($bill->discount ?? 0, 2) }} ({{ number_format($bill->discount_percentage ?? 0, 2) }}%)</td>
                                        <td>₹{{ number_format($bill->total ?? 0, 2) }}</td>
                                        <td>₹0.00</td>
                                        <td>₹{{ number_format($bill->net_amount ?? 0, 2) }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('pathology.billing.show', $bill->id) }}" class="btn btn-sm btn-info text-white" title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('pathology.billing.edit', $bill->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('pathology.billing.destroy', $bill->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger text-white" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-inbox fs-48 mb-2"></i>
                                                <p>No pathology bills found. Generate your first bill!</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $bills->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchBill');
            const tableRows = document.querySelectorAll('tbody tr');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    </script>
@endsection

