@extends('layouts.adminLayout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-plus-circle me-2"></i>Generate Radiology Bill</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Validation Errors:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('radiology.billing.store') }}" method="POST" id="radiologyBillForm">
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
                            <label class="form-label ">Reference Doctor ({{ count($doctors ?? []) }} found)</label>
                            <select name="doctor_id" id="doctor_id" class="form-select add-select2">
                                <option value="">Select Doctor</option>
                                @if(isset($doctors) && count($doctors) > 0)
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            Dr. {{ $doctor->name }} {{ $doctor->surname }}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>No doctors found</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reporting Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date" id="date" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>

                    <!-- TPA Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="activate_tpa" name="activate_tpa">
                                <label class="form-check-label" for="activate_tpa">
                                    Activate TPA
                                </label>
                            </div>
                            <div id="tpa_dropdown_container" style="display: none;">
                                <label class="form-label">Select TPA <span class="text-danger">*</span></label>
                                <select name="organisation_id" id="tpa_dropdown" class="form-select">
                                    <option value="">Select TPA</option>
                                </select>
                                <small class="text-muted" id="tpa_help_text"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Test Selection Table -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3">Radiology Test Details</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="testTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%">Test Name ({{ count($tests ?? []) }} tests) <span class="text-danger">*</span></th>
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
                                                <select name="tests[0][radiology_id]" class="form-select test_name" required>
                                                    <option value="">Select Test</option>
                                                    @if(isset($tests) && count($tests) > 0)
                                                        @foreach($tests as $test)
                                                            <option value="{{ $test->id }}" 
                                                                data-report-days="{{ $test->report_days ?? 0 }}" 
                                                                data-tax="{{ $test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0 }}" 
                                                                data-amount="{{ $test->charge ? ($test->charge->standard_charge + ($test->charge->standard_charge * ($test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0) / 100)) : 0 }}">
                                                                {{ $test->test_name }} - ₹{{ number_format($test->charge ? ($test->charge->standard_charge + ($test->charge->standard_charge * ($test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0) / 100)) : 0, 2) }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option disabled>No tests found</option>
                                                    @endif
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
                        <a href="{{ route('radiology.billing.index') }}" class="btn btn-secondary">Cancel</a>
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
    console.log('Page loaded! JavaScript is working. Version: 2.0');
    console.log('Data passed to view:', {
        doctors: @json($doctors->count()),
        tests: @json($tests->count())
    });
    
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('.report_date').value = today;
    
    initPatientAutocomplete();
    initPrescriptionAutocomplete();
    
    // Test selection handler - Using jQuery with Select2
    // Updated: 2026-01-04 - Fixed activateTpa scope issue
    $(document).on('change', '.test_name', function(e) {
        console.log('Test dropdown changed! (Select2 event)');
        
        // CRITICAL: Declare activateTpa and organisationId FIRST - function-scoped with var
        var activateTpa = false;
        var organisationId = null;
        
        // Get TPA elements
        var activateTpaElement = document.getElementById('activate_tpa');
        if (activateTpaElement) {
            activateTpa = activateTpaElement.checked === true;
        }
        
        var tpaDropdown = document.getElementById('tpa_dropdown');
        if (tpaDropdown && tpaDropdown.value) {
            organisationId = tpaDropdown.value;
        }
        
        const selectedOption = this.options[this.selectedIndex];
        const row = $(this).closest('tr');
        const testId = this.value;
        
        if (testId) {
            const reportDays = selectedOption.getAttribute('data-report-days') || 0;
            const tax = selectedOption.getAttribute('data-tax') || 0;
            const standardAmount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
            
            // Store original charge
            originalCharges[testId] = standardAmount;
            
            row.find('.report_days').val(reportDays);
            row.find('.tax_percentage').val(tax);
            
            // Check if TPA is active and get TPA charge
            
            if (activateTpa && organisationId) {
                // Fetch TPA charge
                const url = `{{ url('/radiology/billing/api/tpa-charge') }}?test_id=${testId}&organisation_id=${organisationId}`;
                console.log('Fetching TPA charge from:', url);
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const amountToUse = (data.tpa_charge !== null && data.tpa_charge !== undefined) 
                            ? data.tpa_charge 
                            : standardAmount;
                        row.find('.test_amount').val(amountToUse.toFixed(2));
                        calculateTotals();
                    })
                    .catch(error => {
                        console.error('Error fetching TPA charge:', error);
                        row.find('.test_amount').val(standardAmount.toFixed(2));
                        calculateTotals();
                    });
            } else {
                row.find('.test_amount').val(standardAmount.toFixed(2));
            }
            
            // Calculate report date
            const reportingDate = new Date(document.getElementById('date').value);
            reportingDate.setDate(reportingDate.getDate() + parseInt(reportDays));
            row.find('.report_date').val(reportingDate.toISOString().split('T')[0]);
        } else {
            row.find('.report_days').val(0);
            row.find('.tax_percentage').val(0);
            row.find('.test_amount').val(0);
            row.find('.report_date').val(today);
        }
        
        if (!activateTpa || !organisationId) {
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
                <select name="tests[${testRowCount}][radiology_id]" class="form-select test_name" required>
                    <option value="">Select Test</option>
                    @foreach($tests as $test)
                        <option value="{{ $test->id }}" 
                            data-report-days="{{ $test->report_days ?? 0 }}" 
                            data-tax="{{ $test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0 }}" 
                            data-amount="{{ $test->charge ? ($test->charge->standard_charge + ($test->charge->standard_charge * ($test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0) / 100)) : 0 }}">
                            {{ $test->test_name }} - ₹{{ number_format($test->charge ? ($test->charge->standard_charge + ($test->charge->standard_charge * ($test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0) / 100)) : 0, 2) }}
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
    let currentFocus = -1;
    function initPatientAutocomplete() {
        const searchInput = document.getElementById('patient_search');
        const hiddenInput = document.getElementById('patient_id');
        const suggestionsDiv = document.getElementById('patient_suggestions');

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
                        loadPatientTpas(patient.id);
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
                        
                        console.log('Prescription selected:', prescription);
                        
                        // If it's an IPD prescription, load the tests
                        if (prescription.type === 'ipd') {
                            const prescriptionId = prescription.prescription_id || prescription.id;
                            console.log('Loading tests for IPD prescription ID:', prescriptionId);
                            if (prescriptionId) {
                                loadPrescriptionTests(prescriptionId);
                            } else {
                                console.warn('No prescription ID found for IPD prescription');
                            }
                        } else {
                            console.log('OPD prescription selected, no tests to load');
                        }
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
        const url = `{{ url('/radiology/billing/api/patient-prescriptions') }}/${patientId}`;
        console.log('Fetching prescriptions from URL:', url);
        fetch(url)
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

    function loadPatientTpas(patientId) {
        const helpText = document.getElementById('tpa_help_text');
        helpText.textContent = 'Loading TPAs...';
        helpText.className = 'text-muted';
        
        const url = `{{ url('/radiology/billing/api/patient-tpas') }}/${patientId}`;
        console.log('Fetching TPAs from URL:', url);
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const tpaDropdown = document.getElementById('tpa_dropdown');
                tpaDropdown.innerHTML = '<option value="">Select TPA</option>';
                
                if (data && data.length > 0) {
                    data.forEach(tpa => {
                        const option = document.createElement('option');
                        option.value = tpa.id;
                        option.textContent = tpa.name + (tpa.code ? ' (' + tpa.code + ')' : '');
                        tpaDropdown.appendChild(option);
                    });
                    helpText.textContent = `${data.length} TPA(s) found for this patient`;
                    helpText.className = 'text-success';
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No TPA found for this patient';
                    option.disabled = true;
                    tpaDropdown.appendChild(option);
                    helpText.textContent = 'No TPA found for this patient. TPA charges will not be available.';
                    helpText.className = 'text-warning';
                }
            })
            .catch(error => {
                console.error('Error loading TPAs:', error);
                helpText.textContent = 'Error loading TPAs. Please try again.';
                helpText.className = 'text-danger';
            });
    }

    // Store original charges for each test
    let originalCharges = {};

    // TPA checkbox handler
    document.getElementById('activate_tpa').addEventListener('change', function() {
        const tpaContainer = document.getElementById('tpa_dropdown_container');
        const tpaDropdown = document.getElementById('tpa_dropdown');
        const helpText = document.getElementById('tpa_help_text');
        
        if (this.checked) {
            tpaContainer.style.display = 'block';
            tpaDropdown.required = true;
            const patientId = document.getElementById('patient_id').value;
            if (patientId) {
                loadPatientTpas(patientId);
            } else {
                helpText.textContent = 'Please select a patient first';
                helpText.className = 'text-danger';
            }
        } else {
            tpaContainer.style.display = 'none';
            tpaDropdown.required = false;
            tpaDropdown.value = '';
            helpText.textContent = '';
            // Revert all test charges to standard charges
            revertToStandardCharges();
        }
    });

    // TPA dropdown change handler
    document.getElementById('tpa_dropdown').addEventListener('change', function() {
        const organisationId = this.value;
        if (organisationId) {
            applyTpaCharges(organisationId);
        } else {
            revertToStandardCharges();
        }
    });

    function applyTpaCharges(organisationId) {
        const testRows = document.querySelectorAll('.test-row');
        let promises = [];

        testRows.forEach((row, index) => {
            const testSelect = row.querySelector('.test_name');
            if (testSelect && testSelect.value) {
                const testId = testSelect.value;
                
                // Store original charge if not already stored
                if (!originalCharges[testId]) {
                    const amountInput = row.querySelector('.test_amount');
                    originalCharges[testId] = parseFloat(amountInput.value) || 0;
                }

                // Fetch TPA charge for this test
                const url = `{{ url('/radiology/billing/api/tpa-charge') }}?test_id=${testId}&organisation_id=${organisationId}`;
                console.log('Fetching TPA charge from:', url);
                const promise = fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('TPA charge response for test', testId, ':', data);
                        const amountInput = row.querySelector('.test_amount');
                        if (data.tpa_charge !== null && data.tpa_charge !== undefined && data.tpa_charge !== '') {
                            amountInput.value = parseFloat(data.tpa_charge).toFixed(2);
                            console.log('Applied TPA charge:', data.tpa_charge);
                        } else {
                            amountInput.value = parseFloat(data.standard_charge).toFixed(2);
                            console.log('No TPA charge found, using standard charge:', data.standard_charge);
                        }
                        calculateTotals();
                    })
                    .catch(error => {
                        console.error('Error fetching TPA charge:', error);
                    });
                
                promises.push(promise);
            }
        });

        Promise.all(promises).then(() => {
            calculateTotals();
        });
    }

    function revertToStandardCharges() {
        const testRows = document.querySelectorAll('.test-row');
        
        testRows.forEach((row) => {
            const testSelect = row.querySelector('.test_name');
            if (testSelect && testSelect.value) {
                const testId = testSelect.value;
                const amountInput = row.querySelector('.test_amount');
                
                if (originalCharges[testId] !== undefined) {
                    amountInput.value = originalCharges[testId].toFixed(2);
                } else {
                    // Get standard charge from option data
                    const selectedOption = testSelect.options[testSelect.selectedIndex];
                    const standardCharge = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
                    amountInput.value = standardCharge.toFixed(2);
                }
            }
        });
        
        calculateTotals();
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

    // Load prescription tests (for IPD prescriptions)
    function loadPrescriptionTests(prescriptionId) {
        console.log('Loading prescription tests for prescription ID:', prescriptionId);
        const url = `{{ url('/radiology/billing/api/prescription-tests') }}/${prescriptionId}`;
        console.log('Fetching prescription tests from URL:', url);
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Prescription tests loaded:', data);
                
                if (data.error) {
                    console.error('Error:', data.error);
                    alert('Error loading prescription: ' + data.error);
                    return;
                }
                
                // Clear existing test rows (except the first one)
                const tbody = document.getElementById('testTableBody');
                const existingRows = tbody.querySelectorAll('.test-row');
                existingRows.forEach((row, index) => {
                    if (index > 0) {
                        row.remove();
                    }
                });
                
                // Clear the first row
                const firstRow = tbody.querySelector('.test-row');
                if (firstRow) {
                    const testSelect = firstRow.querySelector('.test_name');
                    testSelect.value = '';
                    firstRow.querySelector('.report_days').value = '0';
                    firstRow.querySelector('.report_date').value = '';
                    firstRow.querySelector('.tax_percentage').value = '0';
                    firstRow.querySelector('.test_amount').value = '';
                }
                
                // Add tests from prescription
                if (data.tests && data.tests.length > 0) {
                    data.tests.forEach((test, index) => {
                        let row;
                        if (index === 0 && firstRow) {
                            row = firstRow;
                        } else {
                            // Create new row by cloning the first row structure
                            const newRow = firstRow.cloneNode(true);
                            // Update the name attributes
                            const rowIndex = index;
                            newRow.querySelectorAll('input, select').forEach(input => {
                                if (input.name) {
                                    input.name = input.name.replace(/tests\[\d+\]/, `tests[${rowIndex}]`);
                                }
                            });
                            tbody.appendChild(newRow);
                            row = newRow;
                        }
                        
                        // Set test values
                        const testSelect = row.querySelector('.test_name');
                        if (testSelect) {
                            testSelect.value = test.id;
                            
                            // Trigger change event to populate other fields
                            if (window.jQuery && $.fn.select2) {
                                $(testSelect).trigger('change');
                            } else {
                                const event = new Event('change', { bubbles: true });
                                testSelect.dispatchEvent(event);
                            }
                        }
                    });
                    
                    // Update doctor if available
                    if (data.prescription && data.prescription.doctor) {
                        const doctorSelect = document.getElementById('doctor_id');
                        // Try to find and select the doctor by name
                        for (let option of doctorSelect.options) {
                            if (option.text.toLowerCase().includes(data.prescription.doctor.toLowerCase())) {
                                doctorSelect.value = option.value;
                                break;
                            }
                        }
                    }
                    
                    // If TPA is available from the prescription, add it to the dropdown
                    if (data.tpa && data.tpa.id) {
                        const tpaDropdown = document.getElementById('tpa_dropdown');
                        const helpText = document.getElementById('tpa_help_text');
                        
                        // Check if TPA already exists in dropdown
                        let tpaExists = false;
                        for (let option of tpaDropdown.options) {
                            if (option.value == data.tpa.id) {
                                tpaExists = true;
                                break;
                            }
                        }
                        
                        if (!tpaExists) {
                            // Clear the "No TPA found" option if it exists
                            if (tpaDropdown.options.length > 0 && tpaDropdown.options[0].disabled) {
                                tpaDropdown.remove(0);
                            }
                            
                            const option = document.createElement('option');
                            option.value = data.tpa.id;
                            option.textContent = data.tpa.name + (data.tpa.code ? ' (' + data.tpa.code + ')' : '');
                            tpaDropdown.appendChild(option);
                        }
                        
                        // Activate TPA checkbox and select the TPA
                        const activateTpaCheckbox = document.getElementById('activate_tpa');
                        if (!activateTpaCheckbox.checked) {
                            activateTpaCheckbox.checked = true;
                            activateTpaCheckbox.dispatchEvent(new Event('change'));
                        }
                        
                        // Wait a bit for TPA dropdown to populate, then select
                        setTimeout(() => {
                            tpaDropdown.value = data.tpa.id;
                            tpaDropdown.dispatchEvent(new Event('change'));
                        }, 500);
                    }
                    
                    calculateTotals();
                } else {
                    console.log('No tests found in prescription');
                    alert('No radiology tests found in this prescription.');
                }
            })
            .catch(error => {
                console.error('Error loading prescription tests:', error);
                alert('Error loading prescription tests: ' + error.message);
            });
    }

    calculateTotals();
    
    // Form submission validation
    document.getElementById('radiologyBillForm').addEventListener('submit', function(e) {
        console.log('Form submission started');
        
        // Validate patient is selected
        const patientId = document.getElementById('patient_id').value;
        if (!patientId) {
            e.preventDefault();
            alert('Please select a patient');
            return false;
        }
        
        // Validate at least one test is selected
        const testRows = document.querySelectorAll('.test-row');
        let hasTest = false;
        testRows.forEach(row => {
            const testSelect = row.querySelector('.test_name');
            if (testSelect && testSelect.value) {
                hasTest = true;
            }
        });
        
        if (!hasTest) {
            e.preventDefault();
            alert('Please add at least one test');
            return false;
        }
        
        // Validate TPA if checkbox is checked
        const activateTpa = document.getElementById('activate_tpa').checked;
        if (activateTpa) {
            const tpaDropdown = document.getElementById('tpa_dropdown');
            if (!tpaDropdown.value) {
                e.preventDefault();
                alert('Please select a TPA');
                return false;
            }
        }
        
        // Clean up empty test rows before submission
        testRows.forEach((row, index) => {
            const testSelect = row.querySelector('.test_name');
            if (!testSelect || !testSelect.value) {
                // Remove empty rows except the first one
                if (index > 0) {
                    row.remove();
                }
            }
        });
        
        // Ensure totals are calculated
        calculateTotals();
        
        // Log form data before submission
        const formData = new FormData(this);
        console.log('Form data being submitted:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        console.log('Form validation passed, submitting...');
    });
});
</script>
@endsection

