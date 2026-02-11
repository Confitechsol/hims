@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-receipt me-2"></i>Money Receipt - New
                </h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('money-receipt.store') }}" id="moneyReceiptForm">
                    @csrf

                    <!-- Receipt Detail Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Receipt Detail</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Receipt No.</label>
                                    <input type="text" id="receipt_no_display" class="form-control" value="{{ $nextReceiptNo ?? 'Auto-generated' }}" readonly>
                                    <small class="text-muted">Latest receipt number</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" id="payment_date" class="form-control" 
                                        value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Time</label>
                                    <input type="time" class="form-control" 
                                        value="{{ date('H:i:s') }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Receipt Type <span class="text-danger">*</span></label>
                                    <select name="receipt_type" class="form-select" required>
                                        <option value="">-- Select --</option>
                                        @foreach($receiptTypes as $type)
                                            <option value="{{ $type }}" {{ old('receipt_type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Slip No.</label>
                                    <input type="text" name="slip_no" class="form-control" 
                                        value="{{ old('slip_no') }}" placeholder="Slip Number">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Booking No.</label>
                                    <input type="text" name="booking_no" class="form-control" 
                                        value="{{ old('booking_no') }}" placeholder="Booking Number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Detail Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Patient Detail</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Patient Name <span class="text-danger">*</span></label>
                                    <div class="autocomplete-container">
                                        <input type="text" id="patient_search" class="form-control" 
                                            placeholder="Search by IPD No, Patient Name, or Phone" autocomplete="off" required>
                                        <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id') }}">
                                        <input type="hidden" name="ipd_id" id="ipd_id" value="{{ old('ipd_id') }}">
                                        <div id="patient_suggestions" class="autocomplete-suggestions"></div>
                                    </div>
                                    <small class="text-muted">Start typing to search (IPD No, Patient Name, Phone)</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" id="patient_phone" class="form-control" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" id="patient_address" class="form-control" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Final Bill No.</label>
                                    <input type="text" name="final_bill_no" id="final_bill_no" class="form-control" 
                                        value="{{ old('final_bill_no') }}" readonly placeholder="Will auto-populate if final bill exists">
                                    <small class="text-muted">Auto-populated from patient's IPD/OPD records</small>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Age</label>
                                    <input type="text" id="patient_age" class="form-control" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Sex</label>
                                    <input type="text" id="patient_sex" class="form-control" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Marital Status</label>
                                    <input type="text" id="patient_marital" class="form-control" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Guardian</label>
                                    <input type="text" id="patient_guardian" class="form-control" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Area/P.S.</label>
                                    <input type="text" id="patient_area" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Charges (when IPD linked) -->
                    <div class="card mb-3" id="doctorChargesCard" style="display: none;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-user-doctor me-2"></i>Doctor Charges</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Doctor</label>
                                    <input type="text" id="doctor_name" class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Amount</label>
                                    <input type="text" id="doctor_charges" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Detail Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Payment Detail</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" 
                                        value="{{ old('amount') }}" placeholder="0.00" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">TDS (₹)</label>
                                    <input type="number" step="0.01" min="0" name="tds" class="form-control" 
                                        value="{{ old('tds', 0) }}" placeholder="0.00">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Remarks</label>
                                    <select name="remarks" class="form-select">
                                        <option value="">-- Select --</option>
                                        <option value="Advance Payment">Advance Payment</option>
                                        <option value="Partial Payment">Partial Payment</option>
                                        <option value="Full Payment">Full Payment</option>
                                        <option value="Due Payment">Due Payment</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                                    <select name="payment_mode" class="form-select" required>
                                        <option value="">-- Select --</option>
                                        @foreach($paymentModes as $mode)
                                            <option value="{{ $mode }}" {{ old('payment_mode') == $mode ? 'selected' : '' }}>
                                                {{ $mode }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Paid By</label>
                                    <input type="text" name="paid_by" class="form-control" 
                                        value="{{ old('paid_by') }}" placeholder="Name of payer">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Narration</label>
                                    <input type="text" name="narration" class="form-control" 
                                        value="{{ old('narration') }}" placeholder="Narration">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Note</label>
                                    <input type="text" name="note" class="form-control" 
                                        value="{{ old('note') }}" placeholder="Additional notes">
                                </div>
                            </div>

                            <!-- Cheque Detail (shown when Payment Type is Cheque) -->
                            <div class="row g-3 mt-2" id="chequeFields" style="display: none;">
                                <div class="col-md-4">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" 
                                        value="{{ old('bank_name') }}" placeholder="Bank Name">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Cheque Receipt Date</label>
                                    <input type="date" name="cheque_date" class="form-control" 
                                        value="{{ old('cheque_date', date('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Cheque / CARD No.</label>
                                    <input type="text" name="cheque_no" class="form-control" 
                                        value="{{ old('cheque_no') }}" placeholder="Cheque or Card Number">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Received By</label>
                                    <input type="text" class="form-control" 
                                        value="{{ Auth::user()->name ?? 'Current User' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Current User</label>
                                    <input type="text" class="form-control" 
                                        value="{{ Auth::user()->name ?? 'Current User' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('money-receipt.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Back to List
                        </a>
                        <div>
                            <button type="button" class="btn btn-warning" onclick="document.getElementById('moneyReceiptForm').reset();">
                                <i class="ti ti-refresh me-1"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Save Receipt
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.autocomplete-container {
    position: relative;
}
.autocomplete-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.autocomplete-suggestion {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}
.autocomplete-suggestion:hover {
    background-color: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update receipt number when payment date changes
    const paymentDateInput = document.getElementById('payment_date');
    const receiptNoDisplay = document.getElementById('receipt_no_display');
    
    if (paymentDateInput && receiptNoDisplay) {
        function updateReceiptNo() {
            const paymentDate = paymentDateInput.value;
            if (paymentDate) {
                fetch('{{ route("money-receipt.api.next-receipt-no") }}?payment_date=' + paymentDate)
                    .then(r => r.json())
                    .then(data => {
                        if (data.receipt_no) {
                            receiptNoDisplay.value = data.receipt_no;
                        }
                    })
                    .catch(err => {
                        console.error('Error fetching receipt number:', err);
                    });
            }
        }
        
        // Update on date change
        paymentDateInput.addEventListener('change', updateReceiptNo);
        
        // Initial update
        updateReceiptNo();
    }

    // Payment Mode change - show/hide cheque fields
    const paymentModeSelect = document.querySelector('select[name="payment_mode"]');
    const chequeFields = document.getElementById('chequeFields');
    
    if (paymentModeSelect) {
        paymentModeSelect.addEventListener('change', function() {
            if (this.value === 'Cheque' || this.value === 'Card') {
                chequeFields.style.display = 'block';
            } else {
                chequeFields.style.display = 'none';
            }
        });
    }

    // Patient autocomplete (searches IPD numbers, patient name, phone)
    const patientInput = document.getElementById('patient_search');
    const patientSuggestions = document.getElementById('patient_suggestions');
    const patientIdInput = document.getElementById('patient_id');
    const ipdIdInput = document.getElementById('ipd_id');
    const finalBillNoInput = document.getElementById('final_bill_no');
    let patientTimeout;

    if (patientInput) {
        patientInput.addEventListener('input', function() {
            clearTimeout(patientTimeout);
            const search = this.value.trim();
            
            if (search.length < 2) {
                patientSuggestions.style.display = 'none';
                return;
            }

            patientTimeout = setTimeout(() => {
                fetch('{{ route("money-receipt.api.search-patient") }}?search=' + encodeURIComponent(search))
                    .then(r => r.json())
                    .then(data => {
                        if (data.data && data.data.length > 0) {
                            patientSuggestions.innerHTML = data.data.map(item => 
                                `<div class="autocomplete-suggestion" 
                                    data-patient-id="${item.id}" 
                                    data-phone="${item.phone || ''}" 
                                    data-address="${item.address || ''}"
                                    data-age="${item.age || ''}"
                                    data-gender="${item.gender || ''}"
                                    data-marital="${item.marital_status || ''}"
                                    data-guardian="${item.guardian_name || ''}"
                                    data-area="${item.area || ''}"
                                    data-bill-type="${item.bill_type || ''}"
                                    data-bill-no="${item.bill_no || ''}"
                                    data-bill-id="${item.bill_id || ''}">
                                    ${item.display_text}
                                </div>`
                            ).join('');
                            patientSuggestions.style.display = 'block';
                        } else {
                            patientSuggestions.style.display = 'none';
                        }
                    });
            }, 300);
        });

        patientSuggestions.addEventListener('click', function(e) {
            if (e.target.classList.contains('autocomplete-suggestion')) {
                const patientId = e.target.getAttribute('data-patient-id');
                const phone = e.target.getAttribute('data-phone');
                const address = e.target.getAttribute('data-address');
                const billType = e.target.getAttribute('data-bill-type');
                const billNo = e.target.getAttribute('data-bill-no');
                const billId = e.target.getAttribute('data-bill-id');
                
                // Set patient info
                patientInput.value = e.target.textContent.split(' - ')[0].split(' (')[0];
                patientIdInput.value = patientId;
                document.getElementById('patient_phone').value = phone || '';
                document.getElementById('patient_address').value = address || '';
                document.getElementById('patient_age').value = e.target.getAttribute('data-age') || '';
                document.getElementById('patient_sex').value = e.target.getAttribute('data-gender') || '';
                document.getElementById('patient_marital').value = e.target.getAttribute('data-marital') || '';
                document.getElementById('patient_guardian').value = e.target.getAttribute('data-guardian') || '';
                document.getElementById('patient_area').value = e.target.getAttribute('data-area') || '';
                patientSuggestions.style.display = 'none';

                // Set bill info
                if (billType === 'IPD' && billId) {
                    ipdIdInput.value = billId;
                } else {
                    ipdIdInput.value = '';
                }

                // Auto-populate Final Bill No and load patient details
                if (patientId) {
                    fetch('{{ route("money-receipt.api.patient-final-bill") }}?patient_id=' + patientId)
                        .then(r => r.json())
                        .then(data => {
                            if (data.final_bill_no && data.bill_type === 'IPD') {
                                finalBillNoInput.value = data.final_bill_no;
                                ipdIdInput.value = data.bill_id;
                                
                                // Show doctor charges if IPD has due patient party
                                if (data.doctor_charges > 0) {
                                    document.getElementById('doctor_name').value = data.doctor_name || '';
                                    document.getElementById('doctor_charges').value = '₹ ' + parseFloat(data.doctor_charges).toFixed(2);
                                    document.getElementById('doctorChargesCard').style.display = 'block';
                                } else {
                                    document.getElementById('doctorChargesCard').style.display = 'none';
                                }
                            } else {
                                finalBillNoInput.value = '';
                                document.getElementById('doctorChargesCard').style.display = 'none';
                            }
                        });
                    
                    // Load patient details (age, sex, etc.)
                    fetch('{{ url("api/patient") }}/' + patientId)
                        .then(r => r.json())
                        .catch(() => {
                            // If API doesn't exist, try to get from patient model via form
                            // We'll populate from the search result if available
                        });
                }
            }
        });
    }

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.autocomplete-container')) {
            patientSuggestions.style.display = 'none';
        }
    });
});
</script>

@endsection
