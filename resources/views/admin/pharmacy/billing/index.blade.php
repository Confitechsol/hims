@extends('layouts.adminLayout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-file-invoice me-2"></i>Pharmacy Bill</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                    <div class="input-icon-start position-relative me-2">
                        <span class="input-icon-addon">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control shadow-sm" placeholder="Search Bills" id="searchInput">
                    </div>
                    <div class="page_btn d-flex">
                        <a href="{{ route('pharmacy.billing.create') }}" class="btn btn-primary text-white ms-2 fs-13 btn-md">
                            <i class="ti ti-plus me-1"></i>Generate Bill
                        </a>
                        <a href="{{ route('pharmacy.index') }}" class="btn btn-primary text-white ms-2 fs-13 btn-md">
                            <i class="ti ti-pills me-1"></i>Medicines
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Bill No</th>
                                <th>Case ID</th>
                                <th>Date</th>
                                <th>Patient Name</th>
                                <th>Doctor Name</th>
                                <th class="text-end">Discount (INR)</th>
                                <th class="text-end">Net Amount (INR)</th>
                                <th class="text-end">Paid Amount (INR)</th>
                                <th class="text-end">Refund Amount (INR)</th>
                                <th class="text-end">Balance Amount (INR)</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-primary">{{ $bill->bill_number }}</span>
                                </td>
                                <td>{{ $bill->case_reference_id ?? '-' }}</td>
                                <td>{{ $bill->date ? $bill->date->format('m/d/Y h:i A') : '-' }}</td>
                                <td>
                                    @if($bill->patient)
                                        {{ $bill->patient->patient_name }} (ID: {{ $bill->patient->id }})
                                    @else
                                        {{ $bill->customer_name ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $bill->doctor_name ?? '-' }}</td>
                                <td class="text-end">{{ number_format($bill->discount, 2) }} ({{ number_format($bill->discount_percentage, 2) }}%)</td>
                                <td class="text-end fw-semibold">{{ number_format($bill->net_amount, 2) }}</td>
                                <td class="text-end">{{ number_format($bill->net_amount, 2) }}</td>
                                <td class="text-end">0.00</td>
                                <td class="text-end">0.00</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pharmacy.billing.show', $bill->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('pharmacy.billing.edit', $bill->id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           data-bs-toggle="tooltip" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="{{ route('pharmacy.billing.print', $bill->id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           data-bs-toggle="tooltip" title="Print" target="_blank">
                                            <i class="ti ti-printer"></i>
                                        </a>
                                        <form action="{{ route('pharmacy.billing.destroy', $bill->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this bill?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="tooltip" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ti ti-file-invoice-off fs-48 mb-3"></i>
                                        <p class="mb-0">No pharmacy bills found</p>
                                        <small>Click "Generate Bill" to create your first bill</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bills->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $bills->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection