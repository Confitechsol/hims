<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Visit Details Modal</title>
    <style>
        .section-body {
            padding: 0.75rem 1.5rem;
        }

        /* Form Labels */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            display: block;
        }

        .form-label .required {
            color: var(--primary-color);
            margin-left: 2px;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0.625rem 0.875rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            background: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.15);
            outline: none;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        /* Modal Footer */
        .modal-footer {
            background: white;
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            gap: 0.75rem;
        }

        /* Buttons */
        .btn {
            border-radius: 6px;
            padding: 0.625rem 1.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Grid Layout */
        .form-row {
            display: grid;
            gap: 1rem;
        }

        .form-row.cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-row.cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .form-row.cols-4 {
            grid-template-columns: repeat(4, 1fr);
        }

        /* Scrollbar Styling */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: var(--bg-light);
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            .form-row.cols-2,
            .form-row.cols-3,
            .form-row.cols-4 {
                grid-template-columns: 1fr;
            }

            .modal-body {
                max-height: calc(100vh - 150px);
            }

            .section-body {
                padding: 1rem;
            }

            .form-section {
                margin: 0.5rem;
            }
        }

        /* Field Group */
        .field-group {
            margin-bottom: 0;
        }

        /* Custom Select Arrow */
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            padding-right: 2.5rem;
        }

        /* Amount Input */
        .amount-input-group {
            position: relative;
        }

        .amount-input-group .currency-symbol {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-weight: 500;
            z-index: 1;
        }

        .amount-input-group .form-control {
            padding-left: 35px;
        }
    </style>
</head>

<body>

    <!-- Modal -->
    <div class="modal fade" id="editOpdModal" tabindex="-1" aria-labelledby="editSpecializationLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Edit Visit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="editVisitForm">

                        <!-- Symptoms Information Section -->
                        <div class="form-section mb-4">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="bi bi-clipboard-pulse"></i>
                                </div>
                                <h6 class="section-title">Symptoms Information</h6>
                            </div>
                            <div class="section-body">
                                <div class="form-row cols-3">
                                    <div class="field-group">
                                        <label for="symptoms_type" class="form-label">Symptoms Type</label>
                                        <div class="custom-multiselect-wrapper">
                                            <!-- Hidden native select (for form submission) -->
                                            <select name="symptoms_type[]" id="symptoms_type" class="form-select"
                                                multiple>
                                                <option value="">Loading...</option>
                                            </select>

                                            <!-- Custom Multi-Select UI -->
                                            <div class="custom-multiselect">
                                                <div class="multiselect-selected" tabindex="0">
                                                    <span class="multiselect-placeholder">Select symptoms...</span>
                                                    <i class="bi bi-chevron-down multiselect-arrow"></i>
                                                </div>

                                                <div class="multiselect-dropdown">
                                                    <div class="multiselect-search">
                                                        <input type="text" placeholder="Search symptoms...">
                                                    </div>

                                                    <div class="multiselect-actions">
                                                        <button type="button"
                                                            class="multiselect-action-btn select-all">Select
                                                            All</button>
                                                        <button type="button"
                                                            class="multiselect-action-btn clear-all">Clear
                                                            All</button>
                                                    </div>

                                                    <div class="multiselect-options">
                                                        <!-- Options will be generated by JavaScript -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="symptoms" class="form-label">Symptoms</label>
                                        <div class="custom-multiselect-wrapper">
                                            <!-- Hidden native select (for form submission) -->
                                            <select name="symptoms_title[]" id="symptoms_title" class="form-select"
                                                multiple>
                                                <option value="">Loading...</option>
                                            </select>

                                            <!-- Custom Multi-Select UI -->
                                            <div class="custom-multiselect-wrapper">
                                                <select name="symptoms_title[]" id="symptoms_title" class="form-select"
                                                    multiple>
                                                    <option value="">Select symptom types first...</option>
                                                </select>

                                                <div class="custom-multiselect" id="symptom-title-select">
                                                    <div class="multiselect-selected disabled" tabindex="0">
                                                        <span class="multiselect-placeholder">Select symptom types
                                                            first...</span>
                                                        <i class="bi bi-chevron-down multiselect-arrow"></i>
                                                    </div>

                                                    <div class="multiselect-dropdown">
                                                        <div class="multiselect-search">
                                                            <input type="text"
                                                                placeholder="Search symptom titles...">
                                                        </div>

                                                        <div class="multiselect-actions">
                                                            <button type="button"
                                                                class="multiselect-action-btn select-all">Select
                                                                All</button>
                                                            <button type="button"
                                                                class="multiselect-action-btn clear-all">Clear
                                                                All</button>
                                                        </div>

                                                        <div class="multiselect-options">
                                                            <div class="multiselect-no-results">
                                                                Please select symptom types to load titles
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="symptoms_description" class="form-label">Symptoms
                                            Description</label>
                                        <input type="text" name="symptoms_description" id="symptoms_description"
                                            class="form-control" placeholder="Enter description">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Information Section -->
                        <div class="form-section mb-4">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <h6 class="section-title">Appointment Information</h6>
                            </div>
                            <div class="section-body">
                                <div class="form-row cols-2">
                                    <div class="field-group">
                                        <label for="appointment_date" class="form-label">
                                            Appointment Date <span class="required">*</span>
                                        </label>
                                        <input type="date" name="appointment_date" id="appointment_date"
                                            class="form-control" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="old_patient" class="form-label">Case Type</label>
                                        <select name="old_patient" id="old_patient" class="form-select">
                                            <option value="">Select</option>
                                            <option value="Old Patient">Old Patient</option>
                                            <option value="New Patient">New Patient</option>
                                        </select>
                                    </div>

                                    <div class="field-group">
                                        <label for="casualty" class="form-label">Casualty</label>
                                        <select name="casualty" id="casualty" class="form-select">
                                            <option value="">Select</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>

                                    <div class="field-group">
                                        <label for="reference" class="form-label">Reference</label>
                                        <input type="text" name="reference" id="reference" class="form-control"
                                            placeholder="Enter reference">
                                    </div>

                                    <div class="field-group">
                                        <label for="consultant_doctor" class="form-label">
                                            Consultant Doctor <span class="required">*</span>
                                        </label>
                                        <select name="consultant_doctor" id="consultant_doctor" class="form-select"
                                            required>
                                            <option value="">Select Doctor</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information Section -->
                        <div class="form-section mb-4">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <h6 class="section-title">Payment Information</h6>
                            </div>
                            <div class="section-body">
                                <div class="form-row cols-2">
                                    <div class="field-group">
                                        <label for="payment_date" class="form-label">
                                            Payment Date <span class="required">*</span>
                                        </label>
                                        <input type="date" name="payment_date" id="payment_date"
                                            class="form-control" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="amount" class="form-label">
                                            Amount (INR) <span class="required">*</span>
                                        </label>
                                        <div class="amount-input-group">
                                            <span class="currency-symbol">₹</span>
                                            <input type="number" name="paid_amount" id="paid_amount"
                                                class="form-control" placeholder="0.00" step="0.01"
                                                min="0" required>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="payment_mode" class="form-label">Payment Mode</label>
                                        <select name="payment_mode" id="payment_mode" class="form-select">
                                            <option value="">Select Payment Mode</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Card">Card</option>
                                            <option value="UPI">UPI</option>
                                            <option value="Net Banking">Net Banking</option>
                                            <option value="SmartPay" selected>SmartPay PinPad</option>
                                        </select>
                                    </div>

                                    <div class="field-group">
                                        <label for="payment_note" class="form-label">Payment Note</label>
                                        <input type="text" name="payment_note" id="payment_note"
                                            class="form-control" placeholder="Enter payment note"
                                            value="SmartPay Transaction ID: 52870613">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes and Allergies Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="bi bi-file-text"></i>
                                </div>
                                <h6 class="section-title">Notes & Medical History</h6>
                            </div>
                            <div class="section-body">
                                <div class="form-row cols-2">
                                    <div class="field-group">
                                        <label for="note" class="form-label">Note</label>
                                        <textarea name="note" id="note" class="form-control" placeholder="Enter notes" rows="4">SmartPay Transaction: 528706135169</textarea>
                                    </div>

                                    <div class="field-group">
                                        <label for="any_known_allergies" class="form-label">Any Known
                                            Allergies</label>
                                        <textarea name="any_known_allergies" id="known_allergies" class="form-control" placeholder="Enter known allergies"
                                            rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                        Cancel
                    </button>
                    <button type="submit" form="editVisitForm" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editOpdForm');

            // Form submission handler
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                console.log('Form submitted with data:', data);

                // Here you would typically send the data to your server
                // Example:
                /*
                fetch('/api/update-visit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    console.log('Success:', result);
                    // Close modal or show success message
                    bootstrap.Modal.getInstance(document.getElementById('editVisitModal')).hide();
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error message
                });
                */

                // For demo purposes, show alert
                alert('Form data logged to console. Check the browser console.');
            });

            // Close modal functionality
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('editOpdModal');
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                });
            });

            // Prevent modal backdrop clicks from closing
            document.getElementById('editOpdModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    // Optionally close on backdrop click
                    // this.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\hims\resources\views/components/modals/opd-edit-modal.blade.php ENDPATH**/ ?>