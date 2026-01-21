@extends('layouts.adminLayout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-plus-circle me-2"></i>Doctor Visit</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
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
                
                <form action="{{ route('doctor-visit.store') }}" method="POST" id="docvisitSection" onsubmit="handleFormSubmit(event)">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id" value="">
                    
                    <!-- Patient and Bill Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Patient <span class="text-danger">*</span></label>
                            <div class="autocomplete-container">
                                <input type="text" id="patient_search" class="form-control @error('patient_id') is-invalid @enderror" placeholder="Search by name, ID, or mobile" autocomplete="off" required>
                                <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id') }}" required>
                                <div id="patient_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                            <small class="text-muted">Start typing to see suggestions</small>
                            @error('patient_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- <div class="col-md-3">
                            <label class="form-label">Case Reference</label>
                            <div class="autocomplete-container">
                                <input type="text" id="prescription_search" class="form-control" placeholder="Search prescriptions" autocomplete="off">
                                <input type="hidden" name="case_reference_id" id="case_reference_id">
                                <div id="prescription_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                        </div> -->
                        
                        <div class="col-md-3">
                            <label class="form-label">Reference Doctor ({{ count($doctors ?? []) }} found)</label>
                            <select name="doctor_id" id="doctor_id" class="form-select add-select2 @error('doctor_id') is-invalid @enderror">
                                <option value="">Select Doctor</option>
                                @if(isset($doctors) && count($doctors) > 0)
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }} {{ $doctor->surname }}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>No doctors found</option>
                                @endif
                            </select>
                            @error('doctor_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reporting Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d\TH:i')) }}" required>
                            @error('date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Visit Type <span class="text-danger">*</span></label>
                            <select name="visit_type" class="form-select @error('visit_type') is-invalid @enderror" required>
                                <option value="">Select</option>
                                @if(isset($charges) && count($charges) > 0)
                                    @foreach($charges as $charge)
                                        <option value="{{ $charge->id }}" data-standard-charge="{{ $charge->standard_charge ?? 0 }}" {{ old('visit_type') == $charge->id ? 'selected' : '' }}>
                                            {{ $charge->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>No charges found</option>
                                @endif
                            </select>
                            @error('visit_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Rate (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="rate" id="rate" class="form-control @error('rate') is-invalid @enderror" step="0.01" value="{{ old('rate') }}" required>
                            @error('rate')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">No of Visit <span class="text-danger">*</span></label>
                            <input type="number" name="no_of_visit" id="no_of_visit" value="{{ old('no_of_visit', 1) }}" class="form-control @error('no_of_visit') is-invalid @enderror" step="0.01" required>
                            @error('no_of_visit')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" step="0.01" value="{{ old('amount') }}" required readonly>
                            @error('amount')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- <div class="col-md-3">
                            <label class="form-label">Doctor Pay Type</label>
                            <select name="doctor_pay_type" class="form-select">
                                <option value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div> -->
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Doctor Pay Amount</label>
                            <input type="number" name="doctor_pay_amount" class="form-control @error('doctor_pay_amount') is-invalid @enderror" step="0.01" value="{{ old('doctor_pay_amount') }}" readonly>
                            @error('doctor_pay_amount')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Visit Date</label>
                            <input type="date" name="visit_date" class="form-control @error('visit_date') is-invalid @enderror" value="{{ old('visit_date') }}" required>
                            @error('visit_date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Visit Time</label>
                            <input type="time" name="visit_time" class="form-control @error('visit_time') is-invalid @enderror" value="{{ old('visit_time') }}" required>
                            @error('visit_time')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Test Selection Table -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3">Previous Visit Details</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="testTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="30%">Doctor Name <span class="text-danger">*</span></th>
                                            <th width="12%">Rate</th>
                                            <th width="18%">No. of visit <span class="text-danger">*</span></th>                                          
                                            <th width="18%">Amount (INR) <span class="text-danger">*</span></th>
                                            <th width="12%">Pay Amount</th>
                                            <th width="12%">Date</th>
                                            <th width="12%">Time</th>
                                            <th width="12%">Entry</th>
                                            <th width="12%">Visit Type</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testTableBody">
                                        <tr>
                                            <td colspan="11" class="text-center text-muted">Please select a patient to view previous visits</td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
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
let patientData = @json($patients);
let currentFocus = -1;

document.addEventListener('DOMContentLoaded', function() {
    initPatientAutocomplete();
    initVisitTypeHandler();
    initAmountCalculator();
    
    // Load visits if patient is already selected (from old form data)
    const patientIdOnLoad = document.getElementById('patient_id').value;
    if (patientIdOnLoad) {
        loadPatientVisits(patientIdOnLoad);
    }
    
    // Reload table after form submission (edit/create)
    const reloadPatientId = sessionStorage.getItem('reloadPatientVisits');
    if (reloadPatientId) {
        // Set patient ID if not already set
        if (!patientIdOnLoad) {
            document.getElementById('patient_id').value = reloadPatientId;
            const patient = patientData.find(p => p.id == reloadPatientId);
            if (patient) {
                document.getElementById('patient_search').value = patient.patient_name;
            }
        }
        // Reload table
        setTimeout(() => {
            loadPatientVisits(reloadPatientId);
            sessionStorage.removeItem('reloadPatientVisits');
        }, 300);
    } else if (patientIdOnLoad) {
        // Also reload if there's a success message and patient is selected
        const successMessage = document.querySelector('.alert-success');
        if (successMessage) {
            setTimeout(() => {
                loadPatientVisits(patientIdOnLoad);
            }, 300);
        }
    }
});

// Visit Type change handler
function initVisitTypeHandler() {
    const visitTypeSelect = document.querySelector('select[name="visit_type"]');
    const doctorPayAmountInput = document.querySelector('input[name="doctor_pay_amount"]');
    
    if (visitTypeSelect && doctorPayAmountInput) {
        visitTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const standardCharge = selectedOption.getAttribute('data-standard-charge');
            
            if (standardCharge && this.value !== '') {
                doctorPayAmountInput.value = parseFloat(standardCharge).toFixed(2);
            } else {
                doctorPayAmountInput.value = '';
            }
        });
    }
}

// Amount Calculator - Rate * No of Visit
function initAmountCalculator() {
    const rateInput = document.getElementById('rate');
    const noOfVisitInput = document.getElementById('no_of_visit');
    const amountInput = document.getElementById('amount');
    
    function calculateAmount() {
        const rate = parseFloat(rateInput.value) || 0;
        const noOfVisit = parseFloat(noOfVisitInput.value) || 0;
        const amount = rate * noOfVisit;
        
        if (amount > 0) {
            amountInput.value = amount.toFixed(2);
        } else {
            amountInput.value = '';
        }
    }
    
    if (rateInput && noOfVisitInput && amountInput) {
        rateInput.addEventListener('input', calculateAmount);
        rateInput.addEventListener('change', calculateAmount);
        noOfVisitInput.addEventListener('input', calculateAmount);
        noOfVisitInput.addEventListener('change', calculateAmount);
    }
}

// Patient autocomplete
function initPatientAutocomplete() {
    const searchInput = document.getElementById('patient_search');
    const hiddenInput = document.getElementById('patient_id');
    const suggestionsDiv = document.getElementById('patient_suggestions');
    currentFocus = -1;
    
    // Restore patient name if old value exists
    const oldPatientId = hiddenInput.value;
    if (oldPatientId) {
        const patient = patientData.find(p => p.id == oldPatientId);
        if (patient) {
            searchInput.value = patient.patient_name;
        }
    }

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
                    loadPatientVisits(patient.id);
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

// Load patient visits
function loadPatientVisits(patientId) {
    const tbody = document.getElementById('testTableBody');
    tbody.innerHTML = '<tr><td colspan="11" class="text-center">Loading...</td></tr>';
    
    const url = `{{ url('/doctor-visit/api/patient-visits') }}/${patientId}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            tbody.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(visit => {
                    const row = document.createElement('tr');
                    const doctorName = visit.doctor ? `Dr. ${visit.doctor.name} ${visit.doctor.surname || ''}` : 'N/A';
                    const visitType = visit.charge ? visit.charge.name : 'N/A';
                    
                    // Format visit_date to dd/mm/yyyy
                    let visitDate = 'N/A';
                    if (visit.visit_date) {
                        const date = new Date(visit.visit_date);
                        if (!isNaN(date.getTime())) {
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            visitDate = `${day}/${month}/${year}`;
                        }
                    }
                    
                    const visitTime = visit.visit_time || 'N/A';
                    
                    // Format entryDate (created_at) to dd/mm/yyyy
                    let entryDate = 'N/A';
                    if (visit.created_at) {
                        const date = new Date(visit.created_at);
                        if (!isNaN(date.getTime())) {
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            entryDate = `${day}/${month}/${year}`;
                        }
                    }
                    
                    row.innerHTML = `
                        <td>${doctorName}</td>
                        <td>₹${parseFloat(visit.rate || 0).toFixed(2)}</td>
                        <td>${visit.no_of_visit || 0}</td>
                        <td>₹${parseFloat(visit.amount || 0).toFixed(2)}</td>
                        <td>₹${parseFloat(visit.doctor_pay_amount || 0).toFixed(2)}</td>
                        <td>${visitDate}</td>
                        <td>${visitTime}</td>
                        <td>${entryDate}</td>
                        <td>${visitType}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="editVisit(${visit.id})" title="Edit">
                                <i class="ti ti-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteVisit(${visit.id})" title="Delete">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="11" class="text-center text-muted">No previous visits found</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading patient visits:', error);
            tbody.innerHTML = '<tr><td colspan="11" class="text-center text-danger">Error loading visits</td></tr>';
        });
}

// Edit visit function
function editVisit(visitId) {
    const url = `{{ url('/doctor-visit/api/visit') }}/${visitId}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(visit => {
            // Set edit ID
            document.getElementById('edit_id').value = visit.id;
            
            // Set patient
            document.getElementById('patient_id').value = visit.patient_id;
            const patient = patientData.find(p => p.id == visit.patient_id);
            if (patient) {
                document.getElementById('patient_search').value = patient.patient_name;
            }
            
            // Set doctor
            document.getElementById('doctor_id').value = visit.doctor_id;
            if (window.jQuery && $.fn.select2) {
                $('#doctor_id').trigger('change');
            }
            
            // Set reporting date
            const reportingDate = new Date(visit.reporting_date);
            const dateStr = reportingDate.toISOString().slice(0, 16);
            document.getElementById('date').value = dateStr;
            
            // Set visit type
            document.querySelector('select[name="visit_type"]').value = visit.charge_id;
            document.querySelector('select[name="visit_type"]').dispatchEvent(new Event('change'));
            
            // Set rate
            document.getElementById('rate').value = visit.rate;
            
            // Set no of visit
            document.getElementById('no_of_visit').value = visit.no_of_visit;
            
            // Set amount
            document.getElementById('amount').value = visit.amount;
            
            // Set doctor pay amount
            document.querySelector('input[name="doctor_pay_amount"]').value = visit.doctor_pay_amount || 0;
            
            // Set visit date
            const visitDate = new Date(visit.visit_date);
            const visitDateStr = visitDate.toISOString().split('T')[0];
            document.querySelector('input[name="visit_date"]').value = visitDateStr;
            
            // Set visit time
            document.querySelector('input[name="visit_time"]').value = visit.visit_time;
            
            // Change submit button text
            document.getElementById('submitBtn').textContent = 'Update';
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(error => {
            console.error('Error loading visit:', error);
            alert('Error loading visit data. Please try again.');
        });
}

// Delete visit function
function deleteVisit(visitId) {
    if (!confirm('Are you sure you want to delete this visit record?')) {
        return;
    }
    
    const url = `{{ url('/doctor-visit/api/visit') }}/${visitId}`;
    const patientId = document.getElementById('patient_id').value;
    
    const csrfToken = document.querySelector('input[name="_token"]')?.value || 
                     document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Visit deleted successfully!');
                // Reload patient visits
                if (patientId) {
                    loadPatientVisits(patientId);
                }
            } else {
                alert('Error deleting visit: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting visit:', error);
            alert('Error deleting visit. Please try again.');
        });
}

// Handle form submit
function handleFormSubmit(event) {
    const form = event.target;
    const patientId = document.getElementById('patient_id').value;
    const editId = document.getElementById('edit_id').value;
    
    // Store patient ID for reload after redirect
    if (patientId) {
        sessionStorage.setItem('reloadPatientVisits', patientId);
    }
}

// Reset form function
function resetForm() {
    document.getElementById('docvisitSection').reset();
    document.getElementById('edit_id').value = '';
    document.getElementById('patient_id').value = '';
    document.getElementById('patient_search').value = '';
    document.getElementById('submitBtn').textContent = 'Submit';
    
    // Clear Select2 if initialized
    if (window.jQuery && $.fn.select2) {
        $('#doctor_id').val('').trigger('change');
    }
    
    // Clear table
    document.getElementById('testTableBody').innerHTML = 
        '<tr><td colspan="11" class="text-center text-muted">Please select a patient to view previous visits</td></tr>';
    
    // Clear session storage
    sessionStorage.removeItem('reloadPatientVisits');
}

</script>

@endsection
