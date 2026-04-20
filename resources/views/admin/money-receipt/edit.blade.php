@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-receipt me-2"></i>Money Receipt - Edit ({{ $receipt->receipt_no }})
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

                <form method="POST" action="{{ route('money-receipt.update', $receipt->id) }}" id="moneyReceiptForm">
                    @csrf
                    @method('PUT')

                    <!-- Receipt Detail Section -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Receipt Detail</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Receipt No.</label>
                                    <input type="text" class="form-control" value="{{ $receipt->receipt_no }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" class="form-control" 
                                        value="{{ old('payment_date', $receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('Y-m-d') : date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Time</label>
                                    <input type="time" class="form-control" 
                                        value="{{ $receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('H:i:s') : date('H:i:s') }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Receipt Type <span class="text-danger">*</span></label>
                                    <select name="receipt_type" class="form-select" required>
                                        <option value="">-- Select --</option>
                                        @foreach($receiptTypes as $type)
                                            <option value="{{ $type }}" {{ old('receipt_type', $receipt->receipt_type) == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Slip No.</label>
                                    <input type="text" name="slip_no" class="form-control" 
                                        value="{{ old('slip_no', $receipt->slip_no) }}" placeholder="Slip Number">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Booking No.</label>
                                    <input type="text" name="booking_no" class="form-control" 
                                        value="{{ old('booking_no', $receipt->booking_no) }}" placeholder="Booking Number">
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
                                            value="@if($receipt->ipd && $receipt->ipd->ipd_no){{ $receipt->ipd->ipd_no }} - {{ $receipt->patient->patient_name ?? '' }}@elseif($receipt->opd && $receipt->opd->opd_no){{ $receipt->opd->opd_no }} - {{ $receipt->patient->patient_name ?? '' }}@else{{ $receipt->patient->patient_name ?? '' }}@endif"
                                            placeholder="Search by IPD/OPD No, Patient Name, or Phone" autocomplete="off" required>
                                        <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id', $receipt->patient_id) }}">
                                        <input type="hidden" name="ipd_id" id="ipd_id" value="{{ old('ipd_id', $receipt->ipd_id) }}">
                                        <input type="hidden" name="opd_id" id="opd_id" value="{{ old('opd_id', $receipt->opd_id) }}">
                                        <div id="patient_suggestions" class="autocomplete-suggestions"></div>
                                    </div>
                                    <small class="text-muted">Start typing to search (IPD/OPD No, Patient Name, Phone)</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" id="patient_phone" class="form-control" 
                                        value="{{ $receipt->patient->mobileno ?? '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" id="patient_address" class="form-control" 
                                        value="{{ $receipt->patient->address ?? '' }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Age</label>
                                    <input type="text" id="patient_age" class="form-control" 
                                        value="{{ $receipt->patient->age ?? '' }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Sex</label>
                                    <input type="text" id="patient_sex" class="form-control" 
                                        value="{{ $receipt->patient->gender ?? '' }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Marital Status</label>
                                    <input type="text" id="patient_marital" class="form-control" 
                                        value="{{ $receipt->patient->marital_status ?? '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Guardian</label>
                                    <input type="text" id="patient_guardian" class="form-control" 
                                        value="{{ $receipt->patient->guardian_name ?? '' }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Area/P.S.</label>
                                    <input type="text" id="patient_area" class="form-control" 
                                        value="{{ $receipt->patient->area ?? '' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Final Bill No.</label>
                                    <input type="text" name="final_bill_no" id="final_bill_no" class="form-control" 
                                        value="{{ old('final_bill_no', $receipt->final_bill_no) }}" readonly placeholder="Will auto-populate if final bill exists">
                                    <small class="text-muted">Auto-populated from patient's IPD/OPD records</small>
                                </div>
                                <div class="col-md-6" id="casePrescriptionField" style="display: none;">
                                    <label class="form-label">Case/Prescription No.</label>
                                    <input type="text" name="case_prescription_no" id="case_prescription_no" class="form-control" 
                                        value="{{ old('case_prescription_no') }}" readonly placeholder="Will auto-populate based on receipt type">
                                    <small class="text-muted">Auto-populated for OPD/IPD Doctor Consultation, Pathology, and Radiology</small>
                                </div>
                                    <small class="text-muted">Auto-populated from patient's IPD/OPD records</small>
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
                                        value="{{ old('amount', $receipt->amount) }}" placeholder="0.00" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Discount (₹)</label>
                                    <input type="number" step="0.01" min="0" name="discount" class="form-control"
                                        value="{{ old('discount', $receipt->discount ?? 0) }}" placeholder="0.00">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">TDS (₹)</label>
                                    <input type="number" step="0.01" min="0" name="tds" class="form-control" 
                                        value="{{ old('tds', $receipt->tds ?? 0) }}" placeholder="0.00">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Final Amount (₹)</label>
                                    <input type="text" id="final_amount_display" class="form-control"
                                        value="{{ number_format(max(0, (float)($receipt->amount ?? 0) - (float)($receipt->discount ?? 0) - (float)($receipt->tds ?? 0)), 2, '.', '') }}"
                                        readonly>
                                    <small class="text-muted">Amount - Discount - TDS</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Remarks</label>
                                    <select name="remarks" class="form-select">
                                        <option value="">-- Select --</option>
                                        <option value="Advance Payment" {{ old('remarks', $receipt->remarks) == 'Advance Payment' ? 'selected' : '' }}>Advance Payment</option>
                                        <option value="Partial Payment" {{ old('remarks', $receipt->remarks) == 'Partial Payment' ? 'selected' : '' }}>Partial Payment</option>
                                        <option value="Full Payment" {{ old('remarks', $receipt->remarks) == 'Full Payment' ? 'selected' : '' }}>Full Payment</option>
                                        <option value="Due Payment" {{ old('remarks', $receipt->remarks) == 'Due Payment' ? 'selected' : '' }}>Due Payment</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                                    <select name="payment_mode" class="form-select" id="payment_mode" required>
                                        <option value="">-- Select --</option>
                                        @foreach($paymentModes as $mode)
                                            <option value="{{ $mode }}" {{ old('payment_mode', $receipt->payment_mode) == $mode ? 'selected' : '' }}>
                                                {{ $mode }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Paid By</label>
                                    <input type="text" name="paid_by" class="form-control" 
                                        value="{{ old('paid_by', $receipt->paid_by) }}" placeholder="Name of payer">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Narration</label>
                                    <input type="text" name="narration" class="form-control" 
                                        value="{{ old('narration', $receipt->narration) }}" placeholder="Narration">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Note</label>
                                    <input type="text" name="note" class="form-control" 
                                        value="{{ old('note', $receipt->note) }}" placeholder="Additional notes">
                                </div>
                            </div>

                            <!-- Cheque Detail (Cheque / Card) -->
                            <div class="row g-3 mt-2" id="chequeFields" style="display: {{ in_array($receipt->payment_mode, ['Cheque', 'Card']) ? 'block' : 'none' }};">
                                <div class="col-md-4">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" id="cheque_bank_name" class="form-control" 
                                        value="{{ old('bank_name', $receipt->bank_name) }}" placeholder="Bank Name">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Cheque Receipt Date</label>
                                    <input type="date" name="cheque_date" class="form-control" 
                                        value="{{ old('cheque_date', $receipt->cheque_date ? \Carbon\Carbon::parse($receipt->cheque_date)->format('Y-m-d') : date('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Cheque / CARD No.</label>
                                    <input type="text" name="cheque_no" class="form-control" 
                                        value="{{ old('cheque_no', $receipt->cheque_no) }}" placeholder="Cheque or Card Number">
                                </div>
                            </div>

                            <!-- UPI / Online / Transfer to Bank -->
                            <div class="row g-3 mt-2" id="upiTransferFields" style="display: {{ in_array($receipt->payment_mode, ['UPI', 'Online', 'Transfer to Bank Account']) ? 'block' : 'none' }};">
                                <div class="col-md-4" id="transferBankNameCol" style="display: {{ $receipt->payment_mode === 'Transfer to Bank Account' ? 'block' : 'none' }};">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" id="transfer_bank_name" class="form-control" 
                                        value="{{ old('bank_name', $receipt->bank_name) }}" placeholder="Bank Name">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Transaction / Reference No.</label>
                                    <input type="text" name="payment_reference" class="form-control" 
                                        value="{{ old('payment_reference', $receipt->payment_reference ?? '') }}" placeholder="UPI ref, transaction ID, or transfer reference">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Received By</label>
                                    <input type="text" class="form-control" 
                                        value="{{ $receipt->receiver->username ?? Auth::user()->username ?? 'Current User' }}" readonly>
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
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i>Update Receipt
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
.autocomplete-container {
    position: relative !important;
    z-index: 1;
}
.autocomplete-suggestions {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    right: 0 !important;
    background: white !important;
    border: 1px solid #ddd !important;
    border-top: none !important;
    max-height: 200px !important;
    overflow-y: auto !important;
    z-index: 99999 !important;
    display: none !important;
    visibility: visible !important;
    opacity: 1 !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
    width: 100% !important;
    margin-top: 0 !important;
}
.autocomplete-suggestions[style*="display: block"] {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
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
    // Payment Mode change - show/hide cheque, UPI/Online/Transfer fields
    const paymentModeSelect = document.getElementById('payment_mode');
    const amountInput = document.querySelector('input[name="amount"]');
    const discountInput = document.querySelector('input[name="discount"]');
    const tdsInput = document.querySelector('input[name="tds"]');
    const finalAmountDisplay = document.getElementById('final_amount_display');
    const chequeFields = document.getElementById('chequeFields');
    const upiTransferFields = document.getElementById('upiTransferFields');
    const transferBankNameCol = document.getElementById('transferBankNameCol');
    const chequeBankName = document.getElementById('cheque_bank_name');
    const transferBankName = document.getElementById('transfer_bank_name');

    function togglePaymentExtraFields() {
        const mode = paymentModeSelect ? paymentModeSelect.value : '';
        const isChequeCard = (mode === 'Cheque' || mode === 'Card');
        const isUpiOnline = (mode === 'UPI' || mode === 'Online');
        const isTransfer = (mode === 'Transfer to Bank Account');

        if (chequeFields) chequeFields.style.display = isChequeCard ? 'block' : 'none';
        if (upiTransferFields) upiTransferFields.style.display = (isUpiOnline || isTransfer) ? 'block' : 'none';
        if (transferBankNameCol) transferBankNameCol.style.display = isTransfer ? 'block' : 'none';
        if (chequeBankName) chequeBankName.name = isChequeCard ? 'bank_name' : 'bank_name_skip';
        if (transferBankName) transferBankName.name = isTransfer ? 'bank_name' : 'bank_name_skip';
    }

    if (paymentModeSelect) {
        paymentModeSelect.addEventListener('change', togglePaymentExtraFields);
        togglePaymentExtraFields();
    }

    function updateFinalAmountDisplay() {
        if (!finalAmountDisplay) return;
        const amount = parseFloat(amountInput?.value || 0) || 0;
        const discount = parseFloat(discountInput?.value || 0) || 0;
        const tds = parseFloat(tdsInput?.value || 0) || 0;
        const finalAmount = Math.max(0, amount - discount - tds);
        finalAmountDisplay.value = finalAmount.toFixed(2);
    }

    [amountInput, discountInput, tdsInput].forEach((el) => {
        if (el) {
            el.addEventListener('input', updateFinalAmountDisplay);
            el.addEventListener('change', updateFinalAmountDisplay);
        }
    });
    updateFinalAmountDisplay();

    // Receipt Type change - show/hide case/prescription number field
    const receiptTypeSelect = document.querySelector('select[name="receipt_type"]');
    const casePrescriptionField = document.getElementById('casePrescriptionField');
    const casePrescriptionInput = document.getElementById('case_prescription_no');
    const ipdIdInput = document.getElementById('ipd_id');
    const opdIdInput = document.getElementById('opd_id');
    
    function updateCasePrescriptionNo() {
        const receiptType = receiptTypeSelect ? receiptTypeSelect.value : '';
        const opdId = opdIdInput ? opdIdInput.value : '';
        const ipdId = ipdIdInput ? ipdIdInput.value : '';
        
        // Receipt types that need case/prescription number
        const showFieldTypes = [
            'OPD Doctor Consultation', 
            'OPD Pathology', 
            'OPD Radiology', 
            'IPD Pathology', 
            'IPD Radiology',
            'IPD Pharmacy'
        ];
        
        if (showFieldTypes.includes(receiptType) && (opdId || ipdId)) {
            casePrescriptionField.style.display = 'block';
            
            // Fetch case/prescription number
            const params = new URLSearchParams({
                receipt_type: receiptType,
                opd_id: opdId || '',
                ipd_id: ipdId || ''
            });
            
            fetch('{{ route("money-receipt.api.case-prescription-no") }}?' + params)
                .then(r => r.json())
                .then(data => {
                    if (data.case_prescription_no) {
                        casePrescriptionInput.value = data.case_prescription_no;
                    } else {
                        casePrescriptionInput.value = '';
                    }
                })
                .catch(err => {
                    console.error('Error fetching case/prescription number:', err);
                    casePrescriptionInput.value = '';
                });
        } else {
            casePrescriptionField.style.display = 'none';
            casePrescriptionInput.value = '';
        }
    }
    
    if (receiptTypeSelect) {
        receiptTypeSelect.addEventListener('change', updateCasePrescriptionNo);
        // Check on page load if field should be shown
        updateCasePrescriptionNo();
    }

    // Patient autocomplete (searches IPD numbers, patient name, phone)
    const patientInput = document.getElementById('patient_search');
    const patientSuggestions = document.getElementById('patient_suggestions');
    const patientIdInput = document.getElementById('patient_id');
    // ipdIdInput and opdIdInput already declared above for case/prescription number
    const finalBillNoInput = document.getElementById('final_bill_no');
    let patientTimeout;

    if (patientInput) {
        patientInput.addEventListener('input', function() {
            clearTimeout(patientTimeout);
            const search = this.value.trim();
            
            if (search.length < 2) {
                patientSuggestions.style.setProperty('display', 'none', 'important');
                return;
            }

            console.log('Searching for:', search); // Debug
            const routeUrl = '{{ route("money-receipt.api.search-patient") }}';
            const searchUrl = routeUrl + '?search=' + encodeURIComponent(search);
            console.log('Search URL:', searchUrl); // Debug
            console.log('Patient suggestions element:', patientSuggestions); // Debug

            patientTimeout = setTimeout(() => {
                console.log('Making fetch request...'); // Debug
                fetch(searchUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                    .then(r => {
                        console.log('Response status:', r.status); // Debug
                        if (!r.ok) {
                            throw new Error('Network response was not ok: ' + r.status);
                        }
                        return r.json();
                    })
                    .then(data => {
                        console.log('Search results:', data); // Debug log
                        console.log('Patient suggestions element before update:', patientSuggestions); // Debug
                        console.log('Current display style:', patientSuggestions.style.display); // Debug
                        
                        if (data && data.data && Array.isArray(data.data) && data.data.length > 0) {
                            patientSuggestions.innerHTML = data.data.map(item => 
                                `<div class="autocomplete-suggestion" 
                                    data-patient-id="${item.id}" 
                                    data-patient-name="${item.patient_name || ''}"
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
                                    ${item.display_text || ''}
                                </div>`
                            ).join('');
                            
                            // Force display with inline style and important
                            patientSuggestions.style.setProperty('display', 'block', 'important');
                            patientSuggestions.style.setProperty('visibility', 'visible', 'important');
                            patientSuggestions.style.setProperty('opacity', '1', 'important');
                            patientSuggestions.style.setProperty('z-index', '9999', 'important');
                            
                            console.log('Suggestions displayed:', data.data.length); // Debug
                            console.log('Display style after update:', patientSuggestions.style.display); // Debug
                            console.log('Suggestions HTML length:', patientSuggestions.innerHTML.length); // Debug
                        } else {
                            patientSuggestions.innerHTML = '<div class="autocomplete-suggestion" style="color: #999; padding: 10px;">No patients found</div>';
                            patientSuggestions.style.setProperty('display', 'block', 'important');
                            patientSuggestions.style.setProperty('visibility', 'visible', 'important');
                            console.log('No results found'); // Debug
                        }
                    })
                    .catch(err => {
                        console.error('Error searching patients:', err);
                        console.error('Error details:', err.message, err.stack);
                        patientSuggestions.innerHTML = '<div class="autocomplete-suggestion" style="color: #dc3545; padding: 10px;">Error searching patients: ' + err.message + '</div>';
                        patientSuggestions.style.setProperty('display', 'block', 'important');
                        patientSuggestions.style.setProperty('visibility', 'visible', 'important');
                    });
            }, 300);
        });

        patientSuggestions.addEventListener('click', function(e) {
            if (e.target.classList.contains('autocomplete-suggestion')) {
                const patientId = e.target.getAttribute('data-patient-id');
                const patientName = e.target.getAttribute('data-patient-name');
                const phone = e.target.getAttribute('data-phone');
                const address = e.target.getAttribute('data-address');
                const billType = e.target.getAttribute('data-bill-type');
                const billNo = e.target.getAttribute('data-bill-no');
                const billId = e.target.getAttribute('data-bill-id');
                
                // Set patient info - show patient name along with IPD/OPD number
                let displayValue = patientName || '';
                if (billNo && patientName) {
                    displayValue = billNo + ' - ' + patientName;
                } else if (billNo) {
                    displayValue = billNo;
                }
                patientInput.value = displayValue;
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
                    opdIdInput.value = '';
                } else if (billType === 'OPD' && billId) {
                    opdIdInput.value = billId;
                    ipdIdInput.value = '';
                } else {
                    ipdIdInput.value = '';
                    opdIdInput.value = '';
                }

                // Auto-populate Final Bill No and load patient details
                if (patientId) {
                    fetch('{{ route("money-receipt.api.patient-final-bill") }}?patient_id=' + patientId)
                        .then(r => r.json())
                        .then(data => {
                            if (data.final_bill_no) {
                                finalBillNoInput.value = data.final_bill_no;
                                
                                if (data.bill_type === 'IPD' && data.bill_id) {
                                    ipdIdInput.value = data.bill_id;
                                    opdIdInput.value = '';
                                    
                                    // Show doctor charges if IPD has due patient party
                                    if (data.doctor_charges > 0) {
                                        document.getElementById('doctor_name').value = data.doctor_name || '';
                                        document.getElementById('doctor_charges').value = '₹ ' + parseFloat(data.doctor_charges).toFixed(2);
                                        document.getElementById('doctorChargesCard').style.display = 'block';
                                    } else {
                                        document.getElementById('doctorChargesCard').style.display = 'none';
                                    }
                                } else if (data.bill_type === 'OPD' && data.bill_id) {
                                    opdIdInput.value = data.bill_id;
                                    ipdIdInput.value = '';
                                    document.getElementById('doctorChargesCard').style.display = 'none';
                                } else {
                                    document.getElementById('doctorChargesCard').style.display = 'none';
                                }
                                
                                // Update case/prescription number if applicable
                                updateCasePrescriptionNo();
                            } else {
                                finalBillNoInput.value = '';
                                document.getElementById('doctorChargesCard').style.display = 'none';
                                updateCasePrescriptionNo();
                            }
                        });
                }
            }
        });
    }

    // Load doctor charges on page load if IPD is already linked
    const ipdId = document.getElementById('ipd_id').value;
    if (ipdId) {
        fetch('{{ route("money-receipt.api.ipd-details", ":id") }}'.replace(':id', ipdId))
            .then(r => r.json())
            .then(data => {
                if (data.doctor_charges > 0) {
                    document.getElementById('doctor_name').value = data.doctor_name || '';
                    document.getElementById('doctor_charges').value = '₹ ' + parseFloat(data.doctor_charges).toFixed(2);
                    document.getElementById('doctorChargesCard').style.display = 'block';
                }
            });
    }

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.autocomplete-container')) {
            patientSuggestions.style.setProperty('display', 'none', 'important');
        }
    });
    
    // Test function to manually show dropdown (for debugging)
    window.testShowDropdown = function() {
        if (patientSuggestions) {
            patientSuggestions.innerHTML = '<div class="autocomplete-suggestion" style="padding: 10px;">Test suggestion 1</div><div class="autocomplete-suggestion" style="padding: 10px;">Test suggestion 2</div>';
            patientSuggestions.style.setProperty('display', 'block', 'important');
            patientSuggestions.style.setProperty('visibility', 'visible', 'important');
            patientSuggestions.style.setProperty('opacity', '1', 'important');
            console.log('Test dropdown shown. Check if it\'s visible now.');
        }
    };
});
</script>

@endsection
