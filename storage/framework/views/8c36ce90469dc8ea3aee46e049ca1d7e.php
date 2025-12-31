

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-edit me-2"></i>Edit Pathology Bill</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('pathology.billing.update', $bill->id)); ?>" method="POST" id="pathologyBillForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <!-- Patient and Bill Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Patient <span class="text-danger">*</span></label>
                            <div class="autocomplete-container">
                                <input type="text" id="patient_search" class="form-control" placeholder="Search by name, ID, or mobile" autocomplete="off" value="<?php echo e($bill->patient->patient_name ?? ''); ?>" required>
                                <input type="hidden" name="patient_id" id="patient_id" value="<?php echo e($bill->patient_id); ?>" required>
                                <div id="patient_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                            <small class="text-muted">Start typing to see suggestions</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Case Reference</label>
                            <div class="autocomplete-container">
                                <input type="text" id="prescription_search" class="form-control" placeholder="Search prescriptions" autocomplete="off" value="<?php echo e($bill->case_reference_id ?? ''); ?>">
                                <input type="hidden" name="case_reference_id" id="case_reference_id" value="<?php echo e($bill->case_reference_id ?? ''); ?>">
                                <div id="prescription_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reference Doctor (<?php echo e(count($doctors ?? [])); ?> found)</label>
                            <select name="doctor_id" id="doctor_id" class="form-select">
                                <option value="">Select Doctor</option>
                                <?php if(isset($doctors) && count($doctors) > 0): ?>
                                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($doctor->id); ?>" <?php echo e($bill->doctor_id == $doctor->id ? 'selected' : ''); ?>>
                                            Dr. <?php echo e($doctor->name); ?> <?php echo e($doctor->surname); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <option disabled>No doctors found</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reporting Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date" id="date" class="form-control" value="<?php echo e(date('Y-m-d\TH:i', strtotime($bill->date))); ?>" required>
                        </div>
                    </div>

                    <!-- TPA Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="activate_tpa" name="activate_tpa" <?php echo e($bill->organisation_id ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="activate_tpa">
                                    Activate TPA
                                </label>
                            </div>
                            <div id="tpa_dropdown_container" style="display: <?php echo e($bill->organisation_id ? 'block' : 'none'); ?>;">
                                <label class="form-label">Select TPA <span class="text-danger">*</span></label>
                                <select name="organisation_id" id="tpa_dropdown" class="form-select">
                                    <option value="">Select TPA</option>
                                    <?php if($bill->organisation): ?>
                                        <option value="<?php echo e($bill->organisation_id); ?>" selected>
                                            <?php echo e($bill->organisation->organisation_name); ?> <?php echo e($bill->organisation->code ? '(' . $bill->organisation->code . ')' : ''); ?>

                                        </option>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted" id="tpa_help_text"></small>
                            </div>
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
                                            <th width="30%">Test Name (<?php echo e(count($tests ?? [])); ?> tests) <span class="text-danger">*</span></th>
                                            <th width="12%">Report Days</th>
                                            <th width="18%">Report Date <span class="text-danger">*</span></th>
                                            <th width="12%">Tax (%)</th>
                                            <th width="18%">Amount (INR) <span class="text-danger">*</span></th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testTableBody">
                                        <?php $__currentLoopData = $bill->reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="test-row">
                                            <td>
                                                <select name="tests[<?php echo e($index); ?>][pathology_id]" class="form-select test_name" required>
                                                    <option value="">Select Test</option>
                                                    <?php if(isset($tests) && count($tests) > 0): ?>
                                                        <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($test->id); ?>" 
                                                                data-report-days="<?php echo e($test->report_days ?? 0); ?>" 
                                                                data-tax="<?php echo e($test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0); ?>" 
                                                                data-amount="<?php echo e($test->amount ?? ($test->charge ? $test->charge->standard_charge : 0)); ?>"
                                                                <?php echo e($report->pathology_id == $test->id ? 'selected' : ''); ?>>
                                                                <?php echo e($test->test_name); ?> - ₹<?php echo e(number_format($test->amount ?? ($test->charge ? $test->charge->standard_charge : 0), 2)); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <option disabled>No tests found</option>
                                                    <?php endif; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[<?php echo e($index); ?>][report_days]" class="form-control report_days" value="<?php echo e($report->pathology->report_days ?? 0); ?>" min="0" readonly>
                                            </td>
                                            <td>
                                                <input type="date" name="tests[<?php echo e($index); ?>][report_date]" class="form-control report_date" value="<?php echo e(date('Y-m-d', strtotime($report->reporting_date))); ?>" required>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="tests[<?php echo e($index); ?>][tax_percentage]" class="form-control tax_percentage" value="<?php echo e($report->tax_percentage ?? 0); ?>" step="0.01" readonly>
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[<?php echo e($index); ?>][amount]" class="form-control test_amount" value="<?php echo e($report->apply_charge ?? 0); ?>" step="0.01" min="0" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <td class="text-end fw-bold" id="totalAmount">₹<?php echo e(number_format($bill->total, 2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Discount (%)</th>
                                            <td class="text-end">
                                                <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" value="<?php echo e($bill->discount_percentage ?? 0); ?>" step="0.01" min="0" max="100">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Discount (INR)</th>
                                            <td class="text-end fw-bold" id="discountAmount">₹<?php echo e(number_format($bill->discount ?? 0, 2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tax (INR)</th>
                                            <td class="text-end fw-bold" id="taxAmount">₹<?php echo e(number_format($bill->tax ?? 0, 2)); ?></td>
                                        </tr>
                                        <tr class="table-primary">
                                            <th>Net Amount</th>
                                            <td class="text-end fw-bold" id="netAmount">₹<?php echo e(number_format($bill->net_amount, 2)); ?></td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="total" id="total" value="<?php echo e($bill->total); ?>">
                                    <input type="hidden" name="discount" id="discount" value="<?php echo e($bill->discount ?? 0); ?>">
                                    <input type="hidden" name="tax" id="tax" value="<?php echo e($bill->tax ?? 0); ?>">
                                    <input type="hidden" name="tax_percentage" id="tax_percentage" value="<?php echo e($bill->tax_percentage ?? 0); ?>">
                                    <input type="hidden" name="net_amount" id="net_amount" value="<?php echo e($bill->net_amount); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="2"><?php echo e($bill->note); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?php echo e(route('pathology.billing.index')); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Bill</button>
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
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.autocomplete-suggestion {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.autocomplete-suggestion:hover {
    background-color: #f5f5f5;
}

.autocomplete-suggestion:last-child {
    border-bottom: none;
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
let testRowCount = <?php echo e(count($bill->reports)); ?>;
let patientData = <?php echo json_encode($patients, 15, 512) ?>;
let prescriptionData = [];

document.addEventListener('DOMContentLoaded', function() {
    console.log('Edit page loaded!');
    
    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('.report_date').forEach(input => {
        if (!input.value) {
            input.value = today;
        }
    });
    
    // Initialize patient and prescription autocomplete
    initPatientAutocomplete();
    initPrescriptionAutocomplete();
    
    // Load prescriptions and TPAs for the current patient
    const patientId = document.getElementById('patient_id').value;
    if (patientId) {
        loadPatientPrescriptions(patientId);
        loadPatientTpas(patientId);
    }
    
    // Initialize Select2 for test dropdowns
    if (window.jQuery && $.fn.select2) {
        $('.test_name').select2({
            placeholder: 'Select Test',
            allowClear: true,
            width: '100%'
        });
    }
    
    // Test selection handler
    $(document).on('change', '.test_name', function(e) {
        const selectedOption = this.options[this.selectedIndex];
        const row = $(this).closest('tr');
        const testId = this.value;
        
        if (testId) {
            const reportDays = selectedOption.getAttribute('data-report-days') || 0;
            const tax = selectedOption.getAttribute('data-tax') || 0;
            const standardAmount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
            
            originalCharges[testId] = standardAmount;
            
            row.find('.report_days').val(reportDays);
            row.find('.tax_percentage').val(tax);
            
            // Check if TPA is active and get TPA charge
            const activateTpa = document.getElementById('activate_tpa').checked;
            const tpaDropdown = document.getElementById('tpa_dropdown');
            const organisationId = tpaDropdown ? tpaDropdown.value : null;
            
            if (activateTpa && organisationId) {
                const url = `<?php echo e(url('/pathology/billing/api/tpa-charge')); ?>?test_id=${testId}&organisation_id=${organisationId}`;
                console.log('Fetching TPA charge for test', testId, 'and org', organisationId);
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('TPA charge response:', data);
                        let amountToUse = standardAmount;
                        if (data.tpa_charge !== null && data.tpa_charge !== undefined && data.tpa_charge !== '') {
                            amountToUse = parseFloat(data.tpa_charge);
                            console.log('Using TPA charge:', amountToUse);
                        } else {
                            console.log('No TPA charge found, using standard charge:', standardAmount);
                        }
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
            
            calculateTotals();
        }
    });
    
    // Add test row
    $('#addTestRow').on('click', function() {
        const tbody = $('#testTableBody');
        const newRow = tbody.find('tr:first').clone();
        newRow.find('input, select').val('');
        newRow.find('select').attr('name', `tests[${testRowCount}][pathology_id]`);
        newRow.find('.report_days').attr('name', `tests[${testRowCount}][report_days]`);
        newRow.find('.report_date').attr('name', `tests[${testRowCount}][report_date]`).val(today);
        newRow.find('.tax_percentage').attr('name', `tests[${testRowCount}][tax_percentage]`);
        newRow.find('.test_amount').attr('name', `tests[${testRowCount}][amount]`);
        
        if (window.jQuery && $.fn.select2) {
            newRow.find('.test_name').select2({
                placeholder: 'Select Test',
                allowClear: true,
                width: '100%'
            });
        }
        
        tbody.append(newRow);
        testRowCount++;
    });
    
    // Remove test row
    $(document).on('click', '.remove-row', function() {
        if ($('#testTableBody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotals();
        } else {
            alert('At least one test is required');
        }
    });
    
    // Discount percentage change
    $('#discount_percentage').on('input', function() {
        calculateTotals();
    });
    
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
    
    // Store original charges for existing tests on page load
    $('.test-row').each(function() {
        const testSelect = $(this).find('.test_name');
        if (testSelect.length && testSelect.val()) {
            const testId = testSelect.val();
            const selectedOption = testSelect[0].options[testSelect[0].selectedIndex];
            const standardAmount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
            
            // Store the standard charge from the option's data-amount attribute
            if (standardAmount > 0) {
                originalCharges[testId] = standardAmount;
                console.log('Stored original charge for test', testId, ':', standardAmount);
            } else {
                // If no data-amount, use current amount as original
                const currentAmount = parseFloat($(this).find('.test_amount').val()) || 0;
                originalCharges[testId] = currentAmount;
                console.log('Stored current amount as original for test', testId, ':', currentAmount);
            }
        }
    });
    
    // Calculate totals on page load
    calculateTotals();
});

// Patient autocomplete
function initPatientAutocomplete() {
    const searchInput = document.getElementById('patient_search');
    const hiddenInput = document.getElementById('patient_id');
    const suggestionsDiv = document.getElementById('patient_suggestions');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        suggestionsDiv.innerHTML = '';

        if (!searchTerm) {
            suggestionsDiv.style.display = 'none';
            return;
        }

        const filtered = patientData.filter(patient => {
            const name = (patient.patient_name || '').toLowerCase();
            const mobile = (patient.mobileno || '').toLowerCase();
            const id = String(patient.id || '').toLowerCase();
            return name.includes(searchTerm) || mobile.includes(searchTerm) || id.includes(searchTerm);
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
        const searchTerm = this.value.toLowerCase().trim();
        suggestionsDiv.innerHTML = '';

        if (!searchTerm) {
            suggestionsDiv.style.display = 'none';
            hiddenInput.value = '';
            return;
        }
        
        if (!prescriptionData || prescriptionData.length === 0) {
            suggestionsDiv.style.display = 'none';
            hiddenInput.value = '';
            return;
        }

        const filtered = prescriptionData.filter(prescription => {
            const caseId = (prescription.case_id || '').toLowerCase();
            const symptoms = (prescription.symptoms || '').toLowerCase();
            const prescriptionNumber = (prescription.prescription_number || prescription.case_id || '').toLowerCase();
            return caseId.includes(searchTerm) || 
                   symptoms.includes(searchTerm) || 
                   prescriptionNumber.includes(searchTerm);
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
                    
                    if (prescription.type === 'ipd' && prescription.prescription_id) {
                        loadPrescriptionTests(prescription.prescription_id);
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
    console.log('Loading prescriptions for patient ID:', patientId);
    const url = `<?php echo e(url('/pathology/billing/api/patient-prescriptions')); ?>/${patientId}`;
    console.log('Fetching from URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Prescriptions loaded:', data);
            prescriptionData = Array.isArray(data) ? data : [];
            const prescriptionSearch = document.getElementById('prescription_search');
            const prescriptionHidden = document.getElementById('case_reference_id');
            console.log('Total prescriptions loaded:', prescriptionData.length);
        })
        .catch(error => {
            console.error('Error loading prescriptions:', error);
        });
}

function loadPatientTpas(patientId) {
    console.log('Loading TPAs for patient ID:', patientId);
    const helpText = document.getElementById('tpa_help_text');
    helpText.textContent = 'Loading TPAs...';
    helpText.className = 'text-muted';
    
    const url = `<?php echo e(url('/pathology/billing/api/patient-tpas')); ?>/${patientId}`;
    console.log('Fetching TPAs from URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('TPA Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('TPAs loaded:', data);
            const tpaDropdown = document.getElementById('tpa_dropdown');
            const currentValue = tpaDropdown.value; // Preserve current selection
            tpaDropdown.innerHTML = '<option value="">Select TPA</option>';
            
            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(tpa => {
                    if (tpa && tpa.id) {
                        const option = document.createElement('option');
                        option.value = tpa.id;
                        option.textContent = tpa.name + (tpa.code ? ' (' + tpa.code + ')' : '');
                        tpaDropdown.appendChild(option);
                    }
                });
                // Restore previous selection if it exists
                if (currentValue) {
                    tpaDropdown.value = currentValue;
                }
                helpText.textContent = `${data.length} TPA(s) found for this patient`;
                helpText.className = 'text-success';
                console.log('TPAs loaded successfully:', data.length);
            } else {
                helpText.textContent = 'No TPA found for this patient. TPA charges will not be available.';
                helpText.className = 'text-warning';
                console.warn('No TPAs found for this patient');
            }
        })
        .catch(error => {
            console.error('Error loading TPAs:', error);
            helpText.textContent = 'Error loading TPAs. Please try again.';
            helpText.className = 'text-danger';
        });
}

function loadPrescriptionTests(prescriptionId) {
    console.log('Loading prescription tests for ID:', prescriptionId);
    const url = `<?php echo e(url('/pathology/billing/api/prescription-tests')); ?>/${prescriptionId}`;
    console.log('Fetching prescription tests from URL:', url);
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Prescription tests loaded:', data);
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }
            
            // Add tests from prescription (don't clear existing tests)
            if (data.tests && data.tests.length > 0) {
                data.tests.forEach((test, index) => {
                    // Clone the first row and populate with test data
                    const firstRow = $('#testTableBody tr:first');
                    const newRow = firstRow.clone();
                    
                    newRow.find('.test_name').val(test.id).trigger('change');
                    newRow.find('.report_days').val(test.report_days || 0);
                    newRow.find('.report_date').val(data.prescription.date || new Date().toISOString().split('T')[0]);
                    newRow.find('.tax_percentage').val(test.tax_percentage || 0);
                    newRow.find('.test_amount').val(test.amount || 0);
                    
                    // Update names
                    newRow.find('select').attr('name', `tests[${testRowCount}][pathology_id]`);
                    newRow.find('.report_days').attr('name', `tests[${testRowCount}][report_days]`);
                    newRow.find('.report_date').attr('name', `tests[${testRowCount}][report_date]`);
                    newRow.find('.tax_percentage').attr('name', `tests[${testRowCount}][tax_percentage]`);
                    newRow.find('.test_amount').attr('name', `tests[${testRowCount}][amount]`);
                    
                    if (window.jQuery && $.fn.select2) {
                        newRow.find('.test_name').select2({
                            placeholder: 'Select Test',
                            allowClear: true,
                            width: '100%'
                        });
                    }
                    
                    $('#testTableBody').append(newRow);
                    testRowCount++;
                });
                
                calculateTotals();
            }
        })
        .catch(error => {
            console.error('Error loading prescription tests:', error);
        });
}

// Store original charges for each test
let originalCharges = {};

function applyTpaCharges(organisationId) {
    const testRows = document.querySelectorAll('.test-row');
    let promises = [];

    testRows.forEach((row, index) => {
        const testSelect = row.querySelector('.test_name');
        if (testSelect && testSelect.value) {
            const testId = testSelect.value;
            
            if (!originalCharges[testId]) {
                const amountInput = row.querySelector('.test_amount');
                originalCharges[testId] = parseFloat(amountInput.value) || 0;
            }

            const url = `<?php echo e(url('/pathology/billing/api/tpa-charge')); ?>?test_id=${testId}&organisation_id=${organisationId}`;
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
            let standardAmount = null;
            
            // First try to get from originalCharges
            if (originalCharges[testId] !== undefined) {
                standardAmount = originalCharges[testId];
            } else {
                // If not in originalCharges, get from the select option's data-amount attribute
                const selectedOption = testSelect.options[testSelect.selectedIndex];
                if (selectedOption) {
                    standardAmount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
                    // Store it for future use
                    if (standardAmount > 0) {
                        originalCharges[testId] = standardAmount;
                    }
                }
            }
            
            if (standardAmount !== null && standardAmount > 0) {
                amountInput.value = standardAmount.toFixed(2);
                console.log('Reverted test', testId, 'to standard charge:', standardAmount);
            } else {
                console.warn('Could not find standard charge for test', testId);
            }
        }
    });
    
    calculateTotals();
}

function calculateTotals() {
    let total = 0;
    let totalTax = 0;
    
    $('.test-row').each(function() {
        const amount = parseFloat($(this).find('.test_amount').val()) || 0;
        const taxPercent = parseFloat($(this).find('.tax_percentage').val()) || 0;
        const tax = (amount * taxPercent) / 100;
        
        total += amount;
        totalTax += tax;
    });
    
    const discountPercent = parseFloat($('#discount_percentage').val()) || 0;
    const discount = (total * discountPercent) / 100;
    const netAmount = total - discount + totalTax;
    
    $('#totalAmount').text('₹' + total.toFixed(2));
    $('#discountAmount').text('₹' + discount.toFixed(2));
    $('#taxAmount').text('₹' + totalTax.toFixed(2));
    $('#netAmount').text('₹' + netAmount.toFixed(2));
    
    $('#total').val(total.toFixed(2));
    $('#discount').val(discount.toFixed(2));
    $('#tax').val(totalTax.toFixed(2));
    $('#net_amount').val(netAmount.toFixed(2));
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp-8.2\htdocs\hims\resources\views/admin/pathology/billing/edit.blade.php ENDPATH**/ ?>