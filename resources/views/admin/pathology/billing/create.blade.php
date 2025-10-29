@extends('layouts.adminLayout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-plus-circle me-2"></i>Generate Pathology Bill</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pathology.billing.store') }}" method="POST" id="pathologyBillForm">
                    @csrf
                    
                    <!-- Patient and Bill Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Patient <span class="text-danger">*</span></label>
                            <div class="autocomplete-container">
                                <input type="text" id="patient_search" class="form-control" placeholder="Search by name, ID, or mobile" autocomplete="off" required>
                                <input type="hidden" name="patient_id" id="patient_id" required>
                                <div id="patient_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                            <small class="text-muted">Start typing to see suggestions</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Case Reference</label>
                            <div class="autocomplete-container">
                                <input type="text" id="prescription_search" class="form-control" placeholder="Search prescriptions" autocomplete="off">
                                <input type="hidden" name="case_reference_id" id="case_reference_id">
                                <div id="prescription_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reference Doctor</label>
                            <select name="doctor_id" id="doctor_id" class="form-select">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        Dr. {{ $doctor->name }} {{ $doctor->surname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reporting Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date" id="date" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>

                    <!-- Test Selection Table -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3">Pathology Test Details</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="testTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%">Test Name <span class="text-danger">*</span></th>
                                            <th width="12%">Report Days</th>
                                            <th width="18%">Report Date <span class="text-danger">*</span></th>
                                            <th width="12%">Tax (%)</th>
                                            <th width="18%">Amount (INR) <span class="text-danger">*</span></th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testTableBody">
                                        <tr class="test-row">
                                            <td>
                                                <select name="tests[0][pathology_id]" class="form-select test_name" required>
                                                    <option value="">Select Test</option>
                                                    @foreach($tests as $test)
                                                        <option value="{{ $test->id }}" 
                                                            data-report-days="{{ $test->report_days }}" 
                                                            data-tax="{{ $test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0 }}" 
                                                            data-amount="{{ $test->amount }}">
                                                            {{ $test->test_name }} - ₹{{ number_format($test->amount, 2) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[0][report_days]" class="form-control report_days" value="0" min="0" readonly>
                                            </td>
                                            <td>
                                                <input type="date" name="tests[0][report_date]" class="form-control report_date" required>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="tests[0][tax_percentage]" class="form-control tax_percentage" value="0" step="0.01" readonly>
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[0][amount]" class="form-control test_amount" step="0.01" min="0" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary btn-sm" id="addTestRow">
                                    <i class="ti ti-plus"></i> Add Test
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6 offset-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table mb-0">
                                        <tr>
                                            <th>Total Amount (INR)</th>
                                            <td class="text-end fw-bold" id="totalAmount">₹0.00</td>
                                        </tr>
                                        <tr>
                                            <th>Discount (%)</th>
                                            <td class="text-end">
                                                <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" value="0" step="0.01" min="0" max="100">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Discount (INR)</th>
                                            <td class="text-end" id="discountAmount">₹0.00</td>
                                        </tr>
                                        <tr>
                                            <th>Tax (INR)</th>
                                            <td class="text-end" id="taxAmount">₹0.00</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <th>Net Amount (INR)</th>
                                            <td class="text-end fw-bold fs-5" id="netAmount">₹0.00</td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="total" id="total">
                                    <input type="hidden" name="discount" id="discount">
                                    <input type="hidden" name="tax" id="tax">
                                    <input type="hidden" name="tax_percentage" id="tax_percentage">
                                    <input type="hidden" name="net_amount" id="net_amount">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('pathology.billing.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Generate Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.autocomplete-container {
    position: relative;
    width: 100%;
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
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.autocomplete-suggestion {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

.autocomplete-suggestion:hover {
    background-color: #f8f9fa;
}

.autocomplete-suggestion.highlighted {
    background-color: #e3f2fd;
}

.suggestion-name {
    font-weight: 500;
    color: #333;
}

.suggestion-details {
    font-size: 0.85em;
    color: #666;
    margin-top: 2px;
}
</style>

<script>
let testRowCount = 1;
let patientData = @json($patients);
let prescriptionData = [];

document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('.report_date').value = today;
    
    initPatientAutocomplete();
    initPrescriptionAutocomplete();
    
    // Test selection handler
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('test_name')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const row = e.target.closest('tr');
            
            if (selectedOption.value) {
                const reportDays = selectedOption.dataset.reportDays || 0;
                const tax = selectedOption.dataset.tax || 0;
                const amount = selectedOption.dataset.amount || 0;
                
                row.querySelector('.report_days').value = reportDays;
                row.querySelector('.tax_percentage').value = tax;
                row.querySelector('.test_amount').value = amount;
                
                // Calculate report date
                const reportingDate = new Date(document.getElementById('date').value);
                reportingDate.setDate(reportingDate.getDate() + parseInt(reportDays));
                row.querySelector('.report_date').value = reportingDate.toISOString().split('T')[0];
            } else {
                row.querySelector('.report_days').value = 0;
                row.querySelector('.tax_percentage').value = 0;
                row.querySelector('.test_amount').value = 0;
                row.querySelector('.report_date').value = today;
            }
            
            calculateTotals();
        }
    });

    // Discount handler
    document.getElementById('discount_percentage').addEventListener('input', calculateTotals);

    // Add test row
    document.getElementById('addTestRow').addEventListener('click', function() {
        const tbody = document.getElementById('testTableBody');
        const newRow = document.createElement('tr');
        newRow.className = 'test-row';
        newRow.innerHTML = `
            <td>
                <select name="tests[${testRowCount}][pathology_id]" class="form-select test_name" required>
                    <option value="">Select Test</option>
                    @foreach($tests as $test)
                        <option value="{{ $test->id }}" 
                            data-report-days="{{ $test->report_days }}" 
                            data-tax="{{ $test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0 }}" 
                            data-amount="{{ $test->amount }}">
                            {{ $test->test_name }} - ₹{{ number_format($test->amount, 2) }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="tests[${testRowCount}][report_days]" class="form-control report_days" value="0" min="0" readonly>
            </td>
            <td>
                <input type="date" name="tests[${testRowCount}][report_date]" class="form-control report_date" value="${today}" required>
            </td>
            <td>
                <div class="input-group">
                    <input type="number" name="tests[${testRowCount}][tax_percentage]" class="form-control tax_percentage" value="0" step="0.01" readonly>
                    <span class="input-group-text">%</span>
                </div>
            </td>
            <td>
                <input type="number" name="tests[${testRowCount}][amount]" class="form-control test_amount" step="0.01" min="0" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-row">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(newRow);
        testRowCount++;
    });

    // Remove row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const row = e.target.closest('tr');
            if (document.querySelectorAll('.test-row').length > 1) {
                row.remove();
                calculateTotals();
            } else {
                alert('At least one test is required!');
            }
        }
    });

    function calculateTotals() {
        let totalAmount = 0;
        let totalTax = 0;
        
        document.querySelectorAll('.test_amount').forEach(input => {
            const amount = parseFloat(input.value) || 0;
            totalAmount += amount;
        });
        
        document.querySelectorAll('.tax_percentage').forEach((input, index) => {
            const taxPercentage = parseFloat(input.value) || 0;
            const amounts = document.querySelectorAll('.test_amount');
            const amount = parseFloat(amounts[index].value) || 0;
            const tax = amount * taxPercentage / 100;
            totalTax += tax;
        });
        
        const discountPercentage = parseFloat(document.getElementById('discount_percentage').value) || 0;
        const discount = totalAmount * discountPercentage / 100;
        const netAmount = totalAmount - discount;
        
        document.getElementById('total').value = totalAmount.toFixed(2);
        document.getElementById('totalAmount').textContent = '₹' + totalAmount.toFixed(2);
        
        document.getElementById('discount').value = discount.toFixed(2);
        document.getElementById('discountAmount').textContent = '₹' + discount.toFixed(2);
        
        document.getElementById('tax').value = totalTax.toFixed(2);
        document.getElementById('tax_percentage').value = (totalTax > 0 ? (totalTax / totalAmount * 100) : 0).toFixed(2);
        document.getElementById('taxAmount').textContent = '₹' + totalTax.toFixed(2);
        
        document.getElementById('net_amount').value = netAmount.toFixed(2);
        document.getElementById('netAmount').textContent = '₹' + netAmount.toFixed(2);
    }

    // Patient autocomplete
    function initPatientAutocomplete() {
        const searchInput = document.getElementById('patient_search');
        const hiddenInput = document.getElementById('patient_id');
        const suggestionsDiv = document.getElementById('patient_suggestions');
        let currentFocus = -1;

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            suggestionsDiv.innerHTML = '';
            currentFocus = -1;

            if (!searchTerm) {
                suggestionsDiv.style.display = 'none';
                hiddenInput.value = '';
                return;
            }

            const filtered = patientData.filter(patient => {
                return patient.patient_name.toLowerCase().includes(searchTerm) ||
                       patient.id.toString().includes(searchTerm) ||
                       (patient.mobileno && patient.mobileno.includes(searchTerm));
            });

            if (filtered.length > 0) {
                filtered.forEach(patient => {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-suggestion';
                    div.innerHTML = `
                        <div class="suggestion-name">${patient.patient_name}</div>
                        <div class="suggestion-details">ID: ${patient.id} | Mobile: ${patient.mobileno || 'N/A'}</div>
                    `;
                    div.addEventListener('click', function() {
                        searchInput.value = patient.patient_name;
                        hiddenInput.value = patient.id;
                        suggestionsDiv.style.display = 'none';
                        loadPatientPrescriptions(patient.id);
                    });
                    suggestionsDiv.appendChild(div);
                });
                suggestionsDiv.style.display = 'block';
            } else {
                suggestionsDiv.style.display = 'none';
            }
        });

        searchInput.addEventListener('keydown', function(e) {
            handleKeyNavigation(e, suggestionsDiv);
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.autocomplete-container')) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }

    // Prescription autocomplete
    function initPrescriptionAutocomplete() {
        const searchInput = document.getElementById('prescription_search');
        const hiddenInput = document.getElementById('case_reference_id');
        const suggestionsDiv = document.getElementById('prescription_suggestions');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            suggestionsDiv.innerHTML = '';

            if (!searchTerm || prescriptionData.length === 0) {
                suggestionsDiv.style.display = 'none';
                hiddenInput.value = '';
                return;
            }

            const filtered = prescriptionData.filter(prescription => {
                return prescription.case_id.toLowerCase().includes(searchTerm) ||
                       (prescription.symptoms && prescription.symptoms.toLowerCase().includes(searchTerm));
            });

            if (filtered.length > 0) {
                filtered.forEach(prescription => {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-suggestion';
                    div.innerHTML = `
                        <div class="suggestion-name">${prescription.case_id}</div>
                        <div class="suggestion-details">Date: ${prescription.date || 'N/A'} | ${prescription.symptoms || 'No symptoms'}</div>
                    `;
                    div.addEventListener('click', function() {
                        searchInput.value = prescription.case_id;
                        hiddenInput.value = prescription.id;
                        suggestionsDiv.style.display = 'none';
                    });
                    suggestionsDiv.appendChild(div);
                });
                suggestionsDiv.style.display = 'block';
            } else {
                suggestionsDiv.style.display = 'none';
            }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.autocomplete-container')) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }

    function loadPatientPrescriptions(patientId) {
        fetch(`/hims/pathology/billing/api/patient-prescriptions/${patientId}`)
            .then(response => response.json())
            .then(data => {
                prescriptionData = data;
                const prescriptionSearch = document.getElementById('prescription_search');
                const prescriptionHidden = document.getElementById('case_reference_id');
                prescriptionSearch.value = '';
                prescriptionHidden.value = '';
                console.log('Loaded prescriptions:', data.length);
            })
            .catch(error => {
                console.error('Error loading prescriptions:', error);
            });
    }

    function handleKeyNavigation(e, suggestionsDiv) {
        const suggestions = suggestionsDiv.querySelectorAll('.autocomplete-suggestion');
        if (e.keyCode === 40) { // Down
            currentFocus++;
            updateHighlight(suggestions);
            e.preventDefault();
        } else if (e.keyCode === 38) { // Up
            currentFocus--;
            updateHighlight(suggestions);
            e.preventDefault();
        } else if (e.keyCode === 13) { // Enter
            e.preventDefault();
            if (currentFocus > -1 && suggestions[currentFocus]) {
                suggestions[currentFocus].click();
            }
        }
    }

    function updateHighlight(suggestions) {
        suggestions.forEach((s, i) => {
            s.classList.remove('highlighted');
            if (i === currentFocus) {
                s.classList.add('highlighted');
            }
        });
        if (currentFocus >= suggestions.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = suggestions.length - 1;
    }

    calculateTotals();
});
</script>
@endsection
