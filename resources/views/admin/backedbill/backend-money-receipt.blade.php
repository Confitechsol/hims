@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
	<div class="col-12 d-flex">
		<div class="card shadow-sm flex-fill w-100">
			<div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="mb-0" style="color: #750096">
						<i class="fas fa-receipt me-2"></i>Backend Money Receipt List
					</h5>
					<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#backendMoneyReceiptModal">
                        <i class="fas fa-plus me-1"></i>Backend Money Receipt
						
                    </button>

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

				@if ($errors->any())
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						{{ $errors->first() }}
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					</div>
				@endif

				<div class="table-responsive">
					<table class="table table-bordered table-hover align-middle mb-0">
						<thead class="table-light">
							<tr>
								<th>Receipt No.</th>
								<th>Date</th>
								<th>Patient Name</th>
								<th>Case No.</th>
								<th>Receipt Type</th>
								<th>Received Amount</th>
								<th>Payment Mode</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($receipts as $receipt)
								<tr>
									<td>{{ $receipt->receipt_no }}</td>
									<td>{{ $receipt->received_date ? \Carbon\Carbon::parse($receipt->received_date)->format('d/m/Y') : '-' }}</td>
									<td>{{ $receipt->patient_name ?: '-' }}</td>
									<td>{{ $receipt->case_no ?: '-' }}</td>
									<td>{{ $receipt->receipt_type ?: '-' }}</td>
									<td>₹ {{ number_format((float) $receipt->received_amount, 2) }}</td>
									<td>{{ $receipt->payment_mode ?: '-' }}</td>
									<td>
										<div class="d-flex gap-1">
											<a href="{{ route('backend-money-receipt.edit', $receipt->id) }}" class="btn btn-sm btn-warning" title="Edit">
												<i class="ti ti-pencil"></i>
											</a>
											<a href="{{ route('backend-money-receipt.pdf', $receipt->id) }}" class="btn btn-sm btn-primary" title="Open PDF" target="_blank">
												<i class="ti ti-file-download"></i>
											</a>
											<form method="POST" action="{{ route('backend-money-receipt.destroy', $receipt->id) }}" onsubmit="return confirm('Are you sure you want to delete this receipt?');">
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
									<td colspan="8" class="text-center text-muted py-4">No backend money receipts found.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>

				@if ($receipts->hasPages())
					<div class="mt-3">{{ $receipts->appends(request()->query())->links() }}</div>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="backendMoneyReceiptModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
				<h5 class="modal-title">{{ $editReceipt ? 'Edit Backend Money Receipt' : 'Backend Money Receipt' }}</h5>
				<div class="position-relative ms-auto me-3">
        <div class="input-group">
            <span class="input-group-text bg-white">
                <i class="ti ti-search"></i>
            </span>

            <input type="text"
                   class="form-control"
                   id="backend_patient_search"
                   placeholder="Search Backend Patient"
                   autocomplete="off"
                   style="width: 300px;">
        </div>

        <div id="backend_patient_results"
             class="list-group position-absolute w-100"
             style="z-index: 2000; display:none;">
        </div>
    </div>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<form method="POST" action="{{ $editReceipt ? route('backend-money-receipt.update', $editReceipt->id) : route('backend-money-receipt.store') }}">
				@csrf
				@if ($editReceipt)
					@method('PUT')
				@endif
				<div class="modal-body">
					<input type="hidden" name="backend_bill_id" id="backendReceiptBillId" value="{{ $editReceipt->backend_bill_id ?? '' }}">
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Patient Name</label>
							<input type="text" name="patient_name" id="backendReceiptPatientName" class="form-control" value="{{ $editReceipt->patient_name ?? '' }}" readonly required>
						</div>
						<div class="col-md-6">
							<label class="form-label">Age</label>
							<input type="number" name="age" id="backendReceiptAge" class="form-control" min="0" value="{{ $editReceipt->age ?? '' }}" readonly>
						</div>
						 <div class="col-md-3">
                            <label for="datetimepicker" class="form-label">Date</label>
							<input type="date" id="datetimepicker" name="date" class="form-control"
								   value="{{ old('date', $editReceipt->received_date ?? now()->toDateString()) }}" required>
                        </div>
						<div class="col-md-4">
							<label class="form-label">Bill Amount</label>
							<input type="number" name="bill_amount" id="backendReceiptBillAmount" class="form-control" min="0" step="0.01" readonly {{ $editReceipt ? '' : 'required' }}>
						</div>
						<div class="col-md-4">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_mode" id="paymentType" class="form-control" {{ $editReceipt ? '' : 'required' }}>
                                <option value="">Select Payment Type</option>
                                <option value="Cash">Cash</option>
                                <option value="UPI">UPI</option>
                                <option value="Bank">Bank</option>
                            </select>
                        </div>

						<div class="col-md-6">
							<label class="form-label">Receipt Type</label>
							<select name="receipt_type" class="form-select" required>
								<option value="Backend Bill Receipt" {{ ($editReceipt->receipt_type ?? '') === 'Backend Bill Receipt' ? 'selected' : '' }}>Backend Bill Receipt</option>
								<option value="Advance Receipt" {{ ($editReceipt->receipt_type ?? '') === 'Advance Receipt' ? 'selected' : '' }}>Advance Receipt</option>
								<option value="Refund Receipt" {{ ($editReceipt->receipt_type ?? '') === 'Refund Receipt' ? 'selected' : '' }}>Refund Receipt</option>
							</select>
						</div>
						<div class="col-md-6">
							<label class="form-label">Received Amount</label>
							<input type="number" name="received_amount" id="backendReceiptReceivedAmount" class="form-control" min="0.01" step="0.01" value="{{ $editReceipt->received_amount ?? '' }}" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-success">
						<i class="ti ti-device-floppy me-1"></i>{{ $editReceipt ? 'Update Receipt' : 'Save Receipt' }}
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<style>
#backend_patient_results {
	max-height: 260px;
	overflow-y: auto;
}

#backend_patient_results .backend-patient-result {
	padding: 10px 12px;
	text-align: left;
	white-space: normal;
}

#backend_patient_results .backend-patient-result:hover {
	background-color: #f5eafa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const searchInput = document.getElementById('backend_patient_search');
	const resultsContainer = document.getElementById('backend_patient_results');
	const billIdInput = document.getElementById('backendReceiptBillId');
	const patientNameInput = document.getElementById('backendReceiptPatientName');
	const ageInput = document.getElementById('backendReceiptAge');
	const billAmountInput = document.getElementById('backendReceiptBillAmount');
	const receiptForm = billIdInput ? billIdInput.closest('form') : null;
	let searchTimer;

	if (!searchInput || !resultsContainer || !billIdInput) {
		return;
	}

	function hideResults() {
		resultsContainer.style.display = 'none';
		resultsContainer.innerHTML = '';
	}

	function clearSelectedBill() {
		billIdInput.value = '';
		if (patientNameInput) patientNameInput.value = '';
		if (ageInput) ageInput.value = '';
		if (billAmountInput) billAmountInput.value = '';
	}

	function showMessage(message) {
		resultsContainer.innerHTML = '';
		const messageRow = document.createElement('div');
		messageRow.className = 'list-group-item text-muted';
		messageRow.textContent = message;
		resultsContainer.appendChild(messageRow);
		resultsContainer.style.display = 'block';
	}

	function selectBill(bill) {
		const amount = bill.display_total_amount ?? bill.total_amount ?? 0;

		billIdInput.value = bill.id || '';
		if (patientNameInput) patientNameInput.value = bill.patient_name || '';
		if (ageInput) ageInput.value = bill.age ?? '';
		if (billAmountInput) billAmountInput.value = Number(amount).toFixed(2);
		searchInput.value = bill.patient_name || '';
		hideResults();
	}

	searchInput.addEventListener('input', function () {
		clearTimeout(searchTimer);
		clearSelectedBill();
		const search = this.value.trim();

		if (search.length < 3) {
			hideResults();
			return;
		}

		showMessage('Searching...');
		searchTimer = setTimeout(function () {
			fetch('{{ route("backend.searchPatient") }}?search=' + encodeURIComponent(search), {
				headers: {
					'Accept': 'application/json',
					'X-Requested-With': 'XMLHttpRequest'
				}
			})
				.then(function (response) {
					if (!response.ok) throw new Error('Search failed');
					return response.json();
				})
				.then(function (payload) {
					if (searchInput.value.trim() !== search) return;
					const bills = payload && Array.isArray(payload.data) ? payload.data : [];

					if (!bills.length) {
						showMessage('No backend bills found.');
						return;
					}

					resultsContainer.innerHTML = '';
					bills.forEach(function (bill) {
						const result = document.createElement('button');
						result.type = 'button';
						result.className = 'list-group-item list-group-item-action backend-patient-result';
						result.textContent = (bill.patient_name || 'Unnamed patient') +
							' | Bill: ' + Number(bill.display_total_amount ?? bill.total_amount ?? 0).toFixed(2) +
							(bill.case_no ? ' | Case: ' + bill.case_no : '');
						result.addEventListener('click', function () {
							selectBill(bill);
						});
						resultsContainer.appendChild(result);
					});
					resultsContainer.style.display = 'block';
				})
				.catch(function () {
					showMessage('Unable to search backend bills.');
				});
		}, 300);
	});

	document.addEventListener('click', function (event) {
		if (!event.target.closest('#backend_patient_search, #backend_patient_results')) {
			hideResults();
		}
	});

	if (receiptForm) {
		receiptForm.addEventListener('submit', function (event) {
			if (!billIdInput.value) {
				event.preventDefault();
				searchInput.focus();
				showMessage('Select a backend bill from the search results.');
			}
		});
	}

	const modal = document.getElementById('backendMoneyReceiptModal');
	if (modal) {
		@if ($editReceipt)
			if (window.bootstrap) {
				bootstrap.Modal.getOrCreateInstance(modal).show();
			}
		@endif

		modal.addEventListener('hidden.bs.modal', function () {
			searchInput.value = '';
			clearSelectedBill();
			hideResults();
		});
	}
});
</script>



@endsection
