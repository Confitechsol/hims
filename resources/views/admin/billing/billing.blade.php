{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <style>
        .module_billing {
            border-radius: 8px;
            color: #fff;
            background-color: #ab00db;
            width: 100%;
            padding: 20px;
            box-shadow: 5px 5px 8px 0px #bbbbbb;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>

    <div class="row justify-content-center">

        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Single Module Billing </h5>
                </div>

                <div class="card-body">
                    <div class="row align-items-center gy-4">
                        <div class="col-md-4">
                            <a href="{{ route('appointment-details') }}">
                                <div class="module_billing">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    <p>Appointment</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('ipd') }}">
                                <div class="module_billing">
                                    <i class="fa-solid fa-stethoscope"></i>
                                    <p>IPD</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('pathology.billing.index') }}">
                                <div class="module_billing">
                                    <i class="fa-solid fa-flask"></i>
                                    <p>Pathology</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('radiology.billing.index') }}">
                                <div class="module_billing">
                                    <i class="fa-solid fa-microscope"></i>
                                    <p>Radiology</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-1">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> IPD Billing Through IPD ID
                    </h5>
                </div>

                <div class="card-body">
                    <form id="ipdSearchForm">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="col-md-2">
                                <label for="ipd_search" class="form-label">IPD Patient <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <div class="autocomplete-container">
                                    <input type="text" id="ipd_search" class="form-control" placeholder="Search by IPD Number, Patient Name, or Phone Number" autocomplete="off" required>
                                    <input type="hidden" id="ipd_id" name="ipd_id">
                                    <div id="ipd_suggestions" class="autocomplete-suggestions"></div>
                                </div>
                                <small class="text-muted">Start typing to see suggestions</small>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="searchBtn" class="btn btn-primary btn-sm w-100">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Breakup Bill Section (Hidden initially) -->
        <div class="col-md-11" id="breakupBillSection" style="display: none;">
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #750096">
                            <i class="fas fa-file-invoice me-2"></i> IPD Bill Breakup
                        </h5>
                        <div>
                            <button type="button" id="exportEstimateBtn" class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-file-pdf me-1"></i> Export Estimate Bill
                            </button>
                            <button type="button" id="exportFinalBtn" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-pdf me-1"></i> Export Final Bill
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="breakupBillContent">
                    <!-- Content will be loaded via AJAX -->
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
        let ipdPatientData = [];
        let currentFocus = -1;

        // Load IPD patients data
        function loadIpdPatients() {
            fetch('{{ route("ipd.billing.search") }}?search=')
                .then(response => response.json())
                .then(data => {
                    if (data && data.data) {
                        ipdPatientData = data.data;
                        console.log('Loaded IPD patients:', ipdPatientData.length);
                    }
                })
                .catch(error => {
                    console.error('Error loading IPD patients:', error);
                });
        }

        // Initialize IPD patient autocomplete
        function initIpdPatientAutocomplete() {
            const searchInput = document.getElementById('ipd_search');
            const hiddenInput = document.getElementById('ipd_id');
            const suggestionsDiv = document.getElementById('ipd_suggestions');

            if (!searchInput || !hiddenInput || !suggestionsDiv) {
                console.error('IPD search elements not found');
                return;
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

                // Filter IPD patients
                const filtered = ipdPatientData.filter(ipd => {
                    const ipdNo = (ipd.ipd_no || '').toLowerCase();
                    const patientName = (ipd.patient_name || '').toLowerCase();
                    const phone = (ipd.phone || '').toLowerCase();
                    return ipdNo.includes(searchTerm) || 
                           patientName.includes(searchTerm) || 
                           phone.includes(searchTerm);
                });

                if (filtered.length > 0) {
                    filtered.forEach(ipd => {
                        const div = document.createElement('div');
                        div.className = 'autocomplete-suggestion';
                        const dischargeBadge = ipd.discharged ? '<span style="color: #28a745; font-weight: bold; margin-left: 5px;">[Discharged]</span>' : '';
                        div.innerHTML = `
                            <div class="suggestion-name">${ipd.ipd_no} - ${ipd.patient_name}${dischargeBadge}</div>
                            <div class="suggestion-details">Phone: ${ipd.phone || 'N/A'}</div>
                        `;
                        div.addEventListener('click', function() {
                            searchInput.value = ipd.ipd_no + ' - ' + ipd.patient_name;
                            hiddenInput.value = ipd.id;
                            suggestionsDiv.style.display = 'none';
                        });
                        suggestionsDiv.appendChild(div);
                    });
                    suggestionsDiv.style.display = 'block';
                } else {
                    suggestionsDiv.style.display = 'none';
                }
            });

            searchInput.addEventListener('keydown', function(e) {
                handleKeyNavigation(e, suggestionsDiv, searchInput, hiddenInput);
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.autocomplete-container')) {
                    suggestionsDiv.style.display = 'none';
                }
            });
        }

        function handleKeyNavigation(e, suggestionsDiv, searchInput, hiddenInput) {
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

        document.addEventListener('DOMContentLoaded', function() {
            // Load IPD patients first
            loadIpdPatients();
            
            // Initialize autocomplete after a short delay to ensure data is loaded
            setTimeout(function() {
                initIpdPatientAutocomplete();
            }, 500);

            // Handle search button click
            document.getElementById('searchBtn').addEventListener('click', function() {
                var ipdId = document.getElementById('ipd_id').value;
                if (!ipdId) {
                    alert('Please select an IPD patient');
                    return;
                }
                loadBreakupBill(ipdId);
            });

            // Handle export buttons
            document.getElementById('exportEstimateBtn').addEventListener('click', function() {
                var ipdId = document.getElementById('ipd_id').value;
                if (!ipdId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Selection Required',
                        text: 'Please select an IPD patient first',
                        confirmButtonColor: '#750096',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                window.open('{{ url("ipd/billing") }}/' + ipdId + '/export-estimate', '_blank');
            });

            document.getElementById('exportFinalBtn').addEventListener('click', function() {
                var ipdId = document.getElementById('ipd_id').value;
                if (!ipdId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Selection Required',
                        text: 'Please select an IPD patient first',
                        confirmButtonColor: '#750096',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                
                // Check if patient is discharged before opening final bill
                fetch('{{ url("ipd/billing") }}/' + ipdId + '/check-discharged')
                    .then(response => response.json())
                    .then(data => {
                        if (data.discharged) {
                            // Patient is discharged, open final bill
                            window.open('{{ url("ipd/billing") }}/' + ipdId + '/export-final', '_blank');
                        } else {
                            // Patient is not discharged, show professional alert
                            Swal.fire({
                                icon: 'info',
                                title: 'Patient Not Discharged',
                                html: '<div style="text-align: left;"><p style="margin-bottom: 10px;">' + 
                                      (data.message || 'Final bill can only be generated for discharged patients.') + 
                                      '</p><p style="color: #666; font-size: 14px;">Please discharge the patient first before generating the final bill.</p></div>',
                                confirmButtonColor: '#750096',
                                confirmButtonText: 'OK',
                                width: '500px'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error checking discharge status:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error checking discharge status. Please try again.',
                            confirmButtonColor: '#750096',
                            confirmButtonText: 'OK'
                        });
                    });
            });

            function loadBreakupBill(ipdId) {
                fetch('{{ url("ipd/billing") }}/' + ipdId + '/breakup')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('breakupBillContent').innerHTML = html;
                        document.getElementById('breakupBillSection').style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error loading bill breakup:', error);
                        document.getElementById('breakupBillContent').innerHTML = '<div class="alert alert-danger">Error loading bill breakup. Please try again.</div>';
                    });
            }
        });
    </script>

@endsection