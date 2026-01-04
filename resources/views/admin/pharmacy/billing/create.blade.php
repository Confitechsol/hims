@extends('layouts.adminLayout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-plus-circle me-2"></i>Generate Pharmacy Bill</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pharmacy.billing.store') }}" method="POST" id="pharmacyBillForm">
                    @csrf
                    
                    <!-- Patient and Bill Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Patient <span class="text-danger">*</span></label>
                            <div class="autocomplete-container">
                                <input type="text" id="patient_search" class="form-control" placeholder="Search by patient name, ID, or mobile" autocomplete="off" required>
                                <input type="hidden" name="patient_id" id="patient_id" required>
                                <div id="patient_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                            <small class="text-muted">Start typing to see patient suggestions</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Case Reference / Prescription</label>
                            <div class="autocomplete-container">
                                <input type="text" id="prescription_search" class="form-control" placeholder="Search prescriptions" autocomplete="off">
                                <input type="hidden" name="case_reference_id" id="case_reference_id">
                                <div id="prescription_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                            <small class="text-muted">Auto-populated when patient is selected</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Doctor Name</label>
                            <input type="text" name="doctor_name" id="doctor_name" class="form-control" placeholder="Doctor Name">
                        </div>
                    </div>

                    <!-- Medicine Selection Table -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3">Medicine Details</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="medicineTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="15%">Medicine Category <span class="text-danger">*</span></th>
                                            <th width="15%">Medicine Name <span class="text-danger">*</span></th>
                                            <th width="15%">Batch No <span class="text-danger">*</span></th>
                                            <th width="10%">Expiry Date</th>
                                            <th width="10%">Quantity <span class="text-danger">*</span> | Available Qty</th>
                                            <th width="10%">Sale Price (INR) <span class="text-danger">*</span></th>
                                            <th width="8%">Tax</th>
                                            <th width="10%">Amount (INR) <span class="text-danger">*</span></th>
                                            <th width="7%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="medicineTableBody">
                                        <tr id="row1">
                                            <td>
                                                <select name="medicine_category_id_1" class="form-select medicine_category" required>
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->medicine_category }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="medicine_name_id_1" class="form-select medicine_name" required>
                                                    <option value="">Select Medicine</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="batch_no_id_1" class="form-select batch_no" required>
                                                    <option value="">Select Batch</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="expire_date_1" class="form-control exp_date" readonly>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="quantity_1" class="form-control qty" min="1" required>
                                                    <span class="input-group-text text-danger available_qty" id="totalqty1">&nbsp;&nbsp;</span>
                                                </div>
                                                <input type="hidden" class="available_quantity" name="available_quantity_1">
                                            </td>
                                            <td>
                                                <input type="number" name="sale_price_1" class="form-control price" step="0.01" min="0" required readonly>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" class="form-control medicine_tax" name="tax_1" readonly>
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="amount_1" class="form-control subtot" step="0.01" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete_row" data-row-id="1">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary add-record" id="addMedicineRow">
                                <i class="ti ti-plus me-1"></i>Add Medicine
                            </button>
                        </div>
                    </div>

                    <!-- Bill Summary -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Total (INR)</th>
                                        <td width="60%" class="text-end">
                                            <input type="number" name="total" id="total" class="form-control text-end" value="0" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Discount (INR)</th>
                                        <td class="text-end">
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="number" name="discount_percent" id="discount_percent" class="form-control text-end" placeholder="%" step="0.01" min="0" max="100">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" name="discount" id="discount" class="form-control text-end" placeholder="Amount" step="0.01" min="0">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tax (INR)</th>
                                        <td class="text-end">
                                            <input type="number" name="tax" id="tax" class="form-control text-end" value="0" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="fw-bold">Net Amount (INR)</th>
                                        <td class="text-end">
                                            <input type="number" name="net_amount" id="net_amount" class="form-control text-end fw-bold" value="0" readonly>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden fields for medicines array -->
                    <div id="medicinesArray"></div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('pharmacy.billing.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>Generate Bill
                        </button>
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
    border-radius: 0 0 4px 4px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.autocomplete-suggestion {
    padding: 10px 15px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

.autocomplete-suggestion:hover {
    background-color: #f8f9fa;
}

.autocomplete-suggestion:last-child {
    border-bottom: none;
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
let totalRows = 1;

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing autocomplete...');
    
    // Initialize autocomplete for patient search
    initPatientAutocomplete();
    
    // Initialize autocomplete for prescription search
    initPrescriptionAutocomplete();

    // Add medicine row
    document.getElementById('addMedicineRow').addEventListener('click', function() {
        addMedicineRow();
    });

    // Medicine category change
    document.addEventListener('change', '.medicine_category', function() {
        // Skip if this change was triggered programmatically
        if (this.dataset.programmaticChange === 'true') {
            this.dataset.programmaticChange = 'false';
            return;
        }
        
        const categoryId = this.value;
        const medicineSelect = this.closest('tr').querySelector('.medicine_name');
        
        if (categoryId) {
            fetchMedicines(categoryId, medicineSelect);
        } else {
            medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
        }
    });

    // Medicine name change
    document.addEventListener('change', '.medicine_name', function() {
        const medicineId = this.value;
        const batchSelect = this.closest('tr').querySelector('.batch_no');
        
        if (medicineId) {
            fetchMedicineBatches(medicineId, batchSelect);
        } else {
            batchSelect.innerHTML = '<option value="">Select Batch</option>';
        }
    });

    // Batch change
    document.addEventListener('change', '.batch_no', function() {
        const batchId = this.value;
        const row = this.closest('tr');
        
        if (batchId) {
            fetchBatchDetails(batchId, row);
        }
    });

    // Quantity change
    document.addEventListener('input', '.qty', function() {
        updateAmount(this.closest('tr'));
        calculateTotals();
    });

    // Discount percentage change
    document.getElementById('discount_percent').addEventListener('input', function() {
        const percentage = parseFloat(this.value) || 0;
        const total = parseFloat(document.getElementById('total').value) || 0;
        const discountAmount = (total * percentage) / 100;
        document.getElementById('discount').value = discountAmount.toFixed(2);
        calculateTotals();
    });

    // Discount amount change
    document.getElementById('discount').addEventListener('input', function() {
        calculateTotals();
    });

    // Delete row
    document.addEventListener('click', '.delete_row', function() {
        if (confirm('Are you sure you want to delete this medicine?')) {
            this.closest('tr').remove();
            calculateTotals();
        }
    });
}); // End of jQuery ready function

function addMedicineRow() {
    totalRows++;
    const tableBody = document.getElementById('medicineTableBody');
    const newRow = document.createElement('tr');
    newRow.id = `row${totalRows}`;
    
    newRow.innerHTML = `
        <td>
            <select name="medicine_category_id_${totalRows}" class="form-select medicine_category" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->medicine_category }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="medicine_name_id_${totalRows}" class="form-select medicine_name" required>
                <option value="">Select Medicine</option>
            </select>
        </td>
        <td>
            <select name="batch_no_id_${totalRows}" class="form-select batch_no" required>
                <option value="">Select Batch</option>
            </select>
        </td>
        <td>
            <input type="text" name="expire_date_${totalRows}" class="form-control exp_date" readonly>
        </td>
        <td>
            <div class="input-group">
                <input type="number" name="quantity_${totalRows}" class="form-control qty" min="1" required>
                <span class="input-group-text text-danger available_qty" id="totalqty${totalRows}">&nbsp;&nbsp;</span>
            </div>
            <input type="hidden" class="available_quantity" name="available_quantity_${totalRows}">
        </td>
        <td>
            <input type="number" name="sale_price_${totalRows}" class="form-control price" step="0.01" min="0" required readonly>
        </td>
        <td>
            <div class="input-group">
                <input type="text" class="form-control medicine_tax" name="tax_${totalRows}" readonly>
                <span class="input-group-text">%</span>
            </div>
        </td>
        <td>
            <input type="number" name="amount_${totalRows}" class="form-control subtot" step="0.01" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger delete_row" data-row-id="${totalRows}">
                <i class="ti ti-trash"></i>
            </button>
        </td>
    `;
    
    tableBody.appendChild(newRow);
}

function initPatientAutocomplete() {
    const searchInput = document.getElementById('patient_search');
    const hiddenInput = document.getElementById('patient_id');
    const suggestionsDiv = document.getElementById('patient_suggestions');
    let currentSuggestions = [];
    let selectedIndex = -1;
    
    // Patient data from server
    const patients = @json($patients);
    
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        if (query.length < 2) {
            hideSuggestions();
            return;
        }
        
        // Filter patients based on query
        currentSuggestions = patients.filter(patient => {
            const name = (patient.patient_name || '').toLowerCase();
            const id = (patient.id || '').toString();
            const mobile = (patient.mobileno || '').toLowerCase();
            
            return name.includes(query) || id.includes(query) || mobile.includes(query);
        });
        
        showSuggestions(currentSuggestions, suggestionsDiv, function(patient) {
            selectPatient(patient);
        });
    });
    
    searchInput.addEventListener('keydown', function(e) {
        handleKeyNavigation(e, currentSuggestions, suggestionsDiv, function(patient) {
            selectPatient(patient);
        });
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.autocomplete-container')) {
            hideSuggestions();
        }
    });
    
    function selectPatient(patient) {
        searchInput.value = `${patient.patient_name} (ID: ${patient.id}) - ${patient.mobileno || 'N/A'}`;
        hiddenInput.value = patient.id;
        hideSuggestions();
        
        // Load prescriptions for selected patient
        loadPatientPrescriptions(patient.id);
    }
    
    function hideSuggestions() {
        suggestionsDiv.style.display = 'none';
        selectedIndex = -1;
    }
}

function initPrescriptionAutocomplete() {
    const searchInput = document.getElementById('prescription_search');
    const hiddenInput = document.getElementById('case_reference_id');
    const suggestionsDiv = document.getElementById('prescription_suggestions');
    let currentSuggestions = [];
    let selectedIndex = -1;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        if (query.length < 1) {
            hideSuggestions();
            return;
        }
        
        // Filter prescriptions based on query
        currentSuggestions = window.prescriptionData || [];
        currentSuggestions = currentSuggestions.filter(prescription => {
            const caseId = (prescription.case_id || '').toLowerCase();
            const date = (prescription.date || '').toLowerCase();
            
            return caseId.includes(query) || date.includes(query);
        });
        
        showSuggestions(currentSuggestions, suggestionsDiv, function(prescription) {
            selectPrescription(prescription);
        });
    });
    
    searchInput.addEventListener('keydown', function(e) {
        handleKeyNavigation(e, currentSuggestions, suggestionsDiv, function(prescription) {
            selectPrescription(prescription);
        });
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.autocomplete-container')) {
            hideSuggestions();
        }
    });
    
    function selectPrescription(prescription) {
        searchInput.value = `${prescription.case_id} - ${prescription.date || 'N/A'}`;
        hiddenInput.value = prescription.id;
        hideSuggestions();
        
        // Load medicines if this is an IPD prescription
        if (prescription.type === 'ipd' && prescription.prescription_id) {
            loadPrescriptionMedicines(prescription.prescription_id);
            
            // Update doctor name if available
            if (prescription.doctor) {
                document.getElementById('doctor_name').value = prescription.doctor;
            }
        } else if (prescription.type === 'ipd' && prescription.id) {
            // Fallback: use id if prescription_id is not available
            loadPrescriptionMedicines(prescription.id);
            
            // Update doctor name if available
            if (prescription.doctor) {
                document.getElementById('doctor_name').value = prescription.doctor;
            }
        }
    }
    
    function hideSuggestions() {
        suggestionsDiv.style.display = 'none';
        selectedIndex = -1;
    }
}

function showSuggestions(suggestions, container, onSelect) {
    if (suggestions.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.innerHTML = '';
    
    suggestions.forEach((item, index) => {
        const div = document.createElement('div');
        div.className = 'autocomplete-suggestion';
        div.innerHTML = `
            <div class="suggestion-name">${item.patient_name || item.case_id || 'N/A'}</div>
            <div class="suggestion-details">${item.mobileno || item.date || 'N/A'}</div>
        `;
        
        div.addEventListener('click', function() {
            onSelect(item);
        });
        
        container.appendChild(div);
    });
    
    container.style.display = 'block';
}

function handleKeyNavigation(e, suggestions, container, onSelect) {
    const suggestionsList = container.querySelectorAll('.autocomplete-suggestion');
    
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedIndex = Math.min(selectedIndex + 1, suggestionsList.length - 1);
        updateHighlight(suggestionsList);
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedIndex = Math.max(selectedIndex - 1, -1);
        updateHighlight(suggestionsList);
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (selectedIndex >= 0 && suggestions[selectedIndex]) {
            onSelect(suggestions[selectedIndex]);
        }
    } else if (e.key === 'Escape') {
        container.style.display = 'none';
        selectedIndex = -1;
    }
}

function updateHighlight(suggestionsList) {
    suggestionsList.forEach((item, index) => {
        item.classList.toggle('highlighted', index === selectedIndex);
    });
}

function loadPatientPrescriptions(patientId) {
    // Fetch case references for the selected patient
    const url = `{{ url('/pharmacy/api/patient-prescriptions') }}/${patientId}`;
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Store prescription data globally for autocomplete
            window.prescriptionData = data;
            
            // Clear the prescription search input
            const prescriptionSearch = document.getElementById('prescription_search');
            const prescriptionHidden = document.getElementById('case_reference_id');
            prescriptionSearch.value = '';
            prescriptionHidden.value = '';
            
            console.log('Loaded prescriptions for patient:', data.length);
        })
        .catch(error => {
            console.error('Error loading prescriptions:', error);
        });
}

function fetchMedicines(categoryId, medicineSelect) {
    return fetch(`{{ route('pharmacy.api.medicines') }}?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Select Medicine</option>';
            data.forEach(medicine => {
                options += `<option value="${medicine.id}">${medicine.medicine_name}</option>`;
            });
            medicineSelect.innerHTML = options;
            return data; // Return data for chaining
        })
        .catch(error => {
            console.error('Error:', error);
            throw error;
        });
}

function fetchMedicineBatches(medicineId, batchSelect) {
    fetch(`/hims/pharmacy/api/batches/${medicineId}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Select Batch</option>';
            data.forEach(batch => {
                options += `<option value="${batch.id}">${batch.batch_no}</option>`;
            });
            batchSelect.innerHTML = options;
        })
        .catch(error => console.error('Error:', error));
}

function fetchBatchDetails(batchId, row) {
    fetch(`{{ route('pharmacy.api.batch-details') }}?batch_id=${batchId}`)
        .then(response => response.json())
        .then(data => {
            row.querySelector('.exp_date').value = data.expiry;
            row.querySelector('.available_qty').textContent = data.available_quantity;
            row.querySelector('.available_quantity').value = data.available_quantity;
            row.querySelector('.price').value = data.sale_rate;
            row.querySelector('.medicine_tax').value = data.gst_percentage || 0;
            
            updateAmount(row);
            calculateTotals();
        })
        .catch(error => console.error('Error:', error));
}

function loadPrescriptionMedicines(prescriptionId) {
    console.log('Loading prescription medicines for ID:', prescriptionId);
    const url = `{{ url('/pharmacy/api/prescription-medicines') }}/${prescriptionId}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Prescription medicines loaded:', data);
            if (data.error) {
                console.error('Error:', data.error);
                alert('Error loading prescription medicines: ' + data.error);
                return;
            }
            
            // Clear existing medicine rows (except the first one)
            const tbody = document.getElementById('medicineTableBody');
            const existingRows = tbody.querySelectorAll('tr');
            existingRows.forEach((row, index) => {
                if (index > 0) {
                    row.remove();
                }
            });
            
            // Reset totalRows counter
            totalRows = 1;
            
            // Clear the first row
            const firstRow = tbody.querySelector('tr');
            if (firstRow) {
                const categorySelect = firstRow.querySelector('.medicine_category');
                const medicineSelect = firstRow.querySelector('.medicine_name');
                const batchSelect = firstRow.querySelector('.batch_no');
                categorySelect.value = '';
                medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
                batchSelect.innerHTML = '<option value="">Select Batch</option>';
                firstRow.querySelector('.exp_date').value = '';
                firstRow.querySelector('.qty').value = '';
                firstRow.querySelector('.available_qty').textContent = '';
                firstRow.querySelector('.available_quantity').value = '';
                firstRow.querySelector('.price').value = '';
                firstRow.querySelector('.medicine_tax').value = '';
                firstRow.querySelector('.subtot').value = '';
            }
            
            // Add medicines from prescription
            if (data.medicines && data.medicines.length > 0) {
                // Process medicines sequentially to avoid race conditions
                const processMedicines = async () => {
                    const firstRowRef = firstRow; // Store reference to avoid closure issues
                    
                    for (let index = 0; index < data.medicines.length; index++) {
                        const medicine = data.medicines[index];
                        console.log(`Processing medicine ${index + 1}:`, medicine);
                        
                        let row;
                        if (index === 0 && firstRowRef) {
                            row = firstRowRef;
                            console.log('Using first row');
                        } else {
                            // Create new row
                            totalRows++;
                            addMedicineRow();
                            row = document.getElementById(`row${totalRows}`);
                            console.log(`Created new row: row${totalRows}`);
                        }
                        
                        if (!row) {
                            console.error('Row not found!');
                            continue;
                        }
                        
                        // Set category
                        const categorySelect = row.querySelector('.medicine_category');
                        const medicineSelect = row.querySelector('.medicine_name');
                        
                        if (!categorySelect) {
                            console.error('Category select not found in row!');
                            continue;
                        }
                        
                        if (!medicineSelect) {
                            console.error('Medicine select not found in row!');
                            continue;
                        }
                        
                        if (medicine.medicine_category_id) {
                            console.log(`Setting category to ${medicine.medicine_category_id}`);
                            // Set category value (convert to string to match select option values)
                            const categoryIdStr = String(medicine.medicine_category_id);
                            
                            // Find and select the option explicitly
                            const categoryOption = Array.from(categorySelect.options).find(opt => opt.value === categoryIdStr);
                            if (categoryOption) {
                                // First deselect all options
                                Array.from(categorySelect.options).forEach(opt => opt.selected = false);
                                // Then select the target option
                                categoryOption.selected = true;
                                categorySelect.value = categoryIdStr;
                                console.log('Category option found and selected:', categoryOption.text);
                            } else {
                                // Fallback: just set the value
                                categorySelect.value = categoryIdStr;
                                console.warn('Category option not found, setting value directly');
                                console.log('Available category options:', Array.from(categorySelect.options).map(opt => ({value: opt.value, text: opt.text})));
                            }
                            
                            // Verify the value was set
                            if (categorySelect.value !== categoryIdStr) {
                                console.warn(`Category value mismatch. Expected: ${categoryIdStr}, Got: ${categorySelect.value}`);
                            } else {
                                console.log('Category value set successfully:', categorySelect.value);
                            }
                            
                            // Force a visual update by triggering change event (but mark it as programmatic)
                            categorySelect.dataset.programmaticChange = 'true';
                            const changeEvent = new Event('change', { bubbles: true });
                            categorySelect.dispatchEvent(changeEvent);
                            
                            // Force a visual update with a small delay to ensure DOM updates
                            setTimeout(() => {
                                // Double-check the value is still set
                                if (categorySelect.value !== categoryIdStr) {
                                    categorySelect.value = categoryIdStr;
                                    console.log('Re-setting category value after delay');
                                }
                            }, 50);
                            
                            // Load medicines for this category and wait for completion
                            try {
                                await fetchMedicines(medicine.medicine_category_id, medicineSelect);
                                console.log('Medicines loaded for category');
                                
                                // Now set the medicine value (convert to string to match select option values)
                                if (medicine.id) {
                                    console.log(`Setting medicine to ${medicine.id}`);
                                    const medicineIdStr = String(medicine.id);
                                    
                                    // Find and select the option explicitly
                                    const medicineOption = Array.from(medicineSelect.options).find(opt => opt.value === medicineIdStr);
                                    if (medicineOption) {
                                        // First deselect all options
                                        Array.from(medicineSelect.options).forEach(opt => opt.selected = false);
                                        // Then select the target option
                                        medicineOption.selected = true;
                                        medicineSelect.value = medicineIdStr;
                                        console.log('Medicine option found and selected:', medicineOption.text);
                                    } else {
                                        medicineSelect.value = medicineIdStr;
                                        console.warn('Medicine option not found, setting value directly');
                                    }
                                    
                                    // Verify the value was set
                                    if (medicineSelect.value != medicineIdStr) {
                                        console.warn(`Medicine value mismatch. Expected: ${medicine.id}, Got: ${medicineSelect.value}`);
                                    } else {
                                        console.log('Medicine value set successfully:', medicineSelect.value);
                                    }
                                    
                                    // Force a visual update with a small delay to ensure DOM updates
                                    setTimeout(() => {
                                        // Double-check the value is still set
                                        if (medicineSelect.value != medicineIdStr) {
                                            medicineSelect.value = medicineIdStr;
                                            console.log('Re-setting medicine value after delay');
                                        }
                                    }, 50);
                                    
                                    // Trigger change event to load batches
                                    const medicineChangeEvent = new Event('change', { bubbles: true });
                                    medicineSelect.dispatchEvent(medicineChangeEvent);
                                }
                            } catch (error) {
                                console.error('Error loading medicines for category:', error);
                            }
                        } else {
                            console.warn('Medicine category_id is missing:', medicine);
                        }
                    }
                    
                    // Update doctor name if available
                    if (data.prescription && data.prescription.doctor) {
                        document.getElementById('doctor_name').value = data.prescription.doctor;
                    }
                    
                    console.log(`Loaded ${data.medicines.length} medicines from prescription`);
                };
                
                processMedicines();
            } else {
                console.log('No medicines found in prescription');
            }
        })
        .catch(error => {
            console.error('Error loading prescription medicines:', error);
            alert('Error loading prescription medicines: ' + error.message);
        });
}

function updateAmount(row) {
    const qty = parseFloat(row.querySelector('.qty').value) || 0;
    const price = parseFloat(row.querySelector('.price').value) || 0;
    const amount = qty * price;
    row.querySelector('.subtot').value = amount.toFixed(2);
}

function calculateTotals() {
    let total = 0;
    let totalTax = 0;
    
    document.querySelectorAll('#medicineTableBody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const taxPercent = parseFloat(row.querySelector('.medicine_tax').value) || 0;
        
        const subtotal = qty * price;
        const tax = (subtotal * taxPercent) / 100;
        
        total += subtotal;
        totalTax += tax;
    });
    
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const netAmount = total - discount + totalTax;
    
    document.getElementById('total').value = total.toFixed(2);
    document.getElementById('tax').value = totalTax.toFixed(2);
    document.getElementById('net_amount').value = netAmount.toFixed(2);
}

// Form submission
document.getElementById('pharmacyBillForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Prepare medicines array
    const medicinesArray = [];
    document.querySelectorAll('#medicineTableBody tr').forEach((row, index) => {
        const batchSelect = row.querySelector('.batch_no');
        const qty = row.querySelector('.qty').value;
        const price = row.querySelector('.price').value;
        const amount = row.querySelector('.subtot').value;
        
        if (batchSelect.value && qty && price) {
            medicinesArray.push({
                medicine_batch_detail_id: batchSelect.value,
                quantity: qty,
                sale_price: price,
                amount: amount
            });
        }
    });
    
    // Add medicines array as hidden input
    const medicinesInput = document.createElement('input');
    medicinesInput.type = 'hidden';
    medicinesInput.name = 'medicines';
    medicinesInput.value = JSON.stringify(medicinesArray);
    document.getElementById('medicinesArray').appendChild(medicinesInput);
    
    // Submit form
    this.submit();
});
</script>

@endsection
