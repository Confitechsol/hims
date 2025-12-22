<style>
    .modal-backdrop.show {
        opacity: 0.6;
    }

    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.35rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-title i {
        font-size: 1.5rem;
    }

    .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.9;
        width: 32px;
        height: 32px;
        background-size: 16px;
    }

    .btn-close:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    .modal-body {
        padding: 2rem;
        background: white;
    }

    /* Alert Box */
    .alert-custom {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-left: 4px solid #ffc107;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .alert-icon {
        font-size: 1.5rem;
        color: #ffc107;
        flex-shrink: 0;
    }

    .alert-text {
        color: #856404;
        font-size: 0.95rem;
        font-weight: 500;
        margin: 0;
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label .required {
        color: var(--primary-color);
        font-size: 1rem;
    }

    .form-label i {
        color: var(--primary-color);
        font-size: 1rem;
    }

    /* Form Controls */
    .form-control,
    .form-select {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(220, 21, 88, 0.15);
        outline: none;
    }

    .form-control::placeholder {
        color: #adb5bd;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    /* Field Group */
    .field-group {
        margin-bottom: 1.5rem;
    }

    /* Grid Layout */
    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    /* Modal Footer */
    .modal-footer {
        background: var(--bg-light);
        border-top: 1px solid var(--border-color);
        padding: 1.25rem 2rem;
        gap: 0.75rem;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #ab00db 0%, #5a0074 100%);
        color: white;
        box-shadow: 0 4px 12px #ab00db66;
    }

    .btn-save:hover {
        color: white;
        box-shadow: 0 6px 16px #ab00db66;
    }

    .btn-save i {
        font-size: 1.1rem;
    }

    .btn-cancel {
        background: white;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }

    .btn-cancel:hover {
        background: #f8f9fa;
        color: #212529;
        border-color: #6c757d;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            flex-direction: column-reverse;
            padding: 1rem;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Custom Scrollbar */
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

    /* Section Spacing */
    .section-divider {
        height: 1px;
        background: var(--border-color);
        margin: 2rem 0 1.5rem;
    }

    /* File Upload Styling */
    .file-upload-wrapper {
        position: relative;
    }

    .file-input {
        opacity: 0;
        position: absolute;
        z-index: -1;
    }

    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        background: var(--bg-light);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-label:hover {
        border-color: var(--primary-color);
        background: rgba(220, 21, 88, 0.05);
    }

    .file-upload-label i {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .file-upload-text {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .file-input:focus+.file-upload-label {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(220, 21, 88, 0.15);
    }

    /* Death Fields Animation */
    #deathFields {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="modal fade" id="patientDischargeModal" tabindex="-1" aria-labelledby="patientDischargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="patientDischargeModalLabel">
                    <i class="bi bi-clipboard-check"></i>
                    Patient Discharge
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Warning Alert -->
                <div class="alert-custom">
                    <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                    <p class="alert-text">Please note that before discharging, check patient bill.</p>
                </div>

                <!-- Discharge Form -->
                <form id="patientDischargeForm" method="POST" action="<?php echo e(route('discharge.store')); ?>"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <!-- Row 1: Discharge Date & Status -->
                    <input type="hidden" name="ipd_details_id" id="ipd-id">
                    <div class="form-row">
                        <div class="field-group">
                            <label for="discharge_date" class="form-label">
                                <i class="bi bi-calendar-event"></i>
                                Discharge Date
                                <span class="required">*</span>
                            </label>
                            <input type="datetime-local" name="discharge_date" id="discharge_date" class="form-control"
                                required>
                        </div>

                        <div class="field-group">
                            <label for="discharge_status" class="form-label">
                                <i class="bi bi-check-circle"></i>
                                Discharge Status
                                <span class="required">*</span>
                            </label>
                            <select name="discharge_status" id="discharge_status" class="form-select" required>
                                <option value="">Select</option>
                                <option value="death">Death</option>
                                <option value="referral">Referral</option>
                                <option value="normal">Normal</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Note (Full Width) -->
                    <div class="field-group">
                        <label for="note" class="form-label">
                            <i class="bi bi-journal-text"></i>
                            Note
                        </label>
                        <textarea name="note" id="note" class="form-control" rows="3"
                            placeholder="Enter discharge notes or instructions..."></textarea>
                    </div>

                    <div class="section-divider"></div>

                    <!-- Row 3: Operation & Diagnosis -->
                    <div class="form-row">
                        <div class="field-group">
                            <label for="operation" class="form-label">
                                <i class="bi bi-scissors"></i>
                                Operation
                            </label>
                            <input type="text" name="operation" id="operation" class="form-control"
                                placeholder="Enter operation details">
                        </div>

                        <div class="field-group">
                            <label for="diagnosis" class="form-label">
                                <i class="bi bi-heart-pulse"></i>
                                Diagnosis
                            </label>
                            <input type="text" name="diagnosis" id="diagnosis" class="form-control"
                                placeholder="Enter diagnosis">
                        </div>
                    </div>

                    <!-- Row 4: Investigation & Treatment Home -->
                    <div class="form-row">
                        <div class="field-group">
                            <label for="investigation" class="form-label">
                                <i class="bi bi-file-medical"></i>
                                Investigation
                            </label>
                            <input type="text" name="investigation" id="investigation" class="form-control"
                                placeholder="Enter investigation details">
                        </div>

                        <div class="field-group">
                            <label for="treatment_home" class="form-label">
                                <i class="bi bi-house-heart"></i>
                                Treatment Home
                            </label>
                            <input type="text" name="treatment_home" id="treatment_home" class="form-control"
                                placeholder="Enter home treatment instructions">
                        </div>
                    </div>

                    <!-- Death Related Fields (Hidden by default) -->
                    <div id="deathFields" style="display: none;">
                        <div class="section-divider"></div>

                        <div class="alert-custom"
                            style="background: #ffe5e5; border-color: #dc3545; border-left-color: #dc3545;">
                            <i class="bi bi-info-circle-fill alert-icon" style="color: #dc3545;"></i>
                            <p class="alert-text" style="color: #721c24;">Additional death-related information
                                required.</p>
                        </div>

                        <div class="form-row">
                            <div class="field-group">
                                <label for="death_date" class="form-label">
                                    <i class="bi bi-calendar-x"></i>
                                    Death Date
                                    <span class="required">*</span>
                                </label>
                                <input type="datetime-local" name="death_date" id="death_date" class="form-control">
                            </div>

                            <div class="field-group">
                                <label for="guardian_name" class="form-label">
                                    <i class="bi bi-person"></i>
                                    Guardian Name
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="guardian_name" id="guardian_name" class="form-control"
                                    placeholder="Enter guardian name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="field-group">
                                <label for="attachment" class="form-label">
                                    <i class="bi bi-paperclip"></i>
                                    Attachment
                                </label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="attachment" id="attachment"
                                        class="form-control file-input" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <label for="attachment" class="file-upload-label">
                                        <i class="bi bi-cloud-upload"></i>
                                        <span class="file-upload-text">Drop a file here or click</span>
                                    </label>
                                </div>
                            </div>

                            <div class="field-group">
                                <label for="report" class="form-label">
                                    <i class="bi bi-file-earmark-text"></i>
                                    Report
                                </label>
                                <textarea name="report" id="report" class="form-control" rows="3"
                                    placeholder="Enter death report details"></textarea>
                            </div>
                        </div>
                    </div>

                    <div id="referralFields" style="display: none;">
                        <div class="section-divider"></div>

                        <div class="alert-custom"
                            style="background: #ffe5e5; border-color: #dc3545; border-left-color: #dc3545;">
                            <i class="bi bi-info-circle-fill alert-icon" style="color: #dc3545;"></i>
                            <p class="alert-text" style="color: #721c24;">Additional referral-related information
                                required.</p>
                        </div>

                        <div class="form-row">
                            <div class="field-group">
                                <label for="referral_date" class="form-label">
                                    <i class="bi bi-calendar-x"></i>
                                    Referral Date
                                    <span class="required">*</span>
                                </label>
                                <input type="datetime-local" name="referral_date" id="referral_date"
                                    class="form-control">
                            </div>

                            <div class="field-group">
                                <label for="referral_hospital_name" class="form-label">
                                    <i class="bi bi-person"></i>
                                    Referral Hospital Name
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="referral_hospital_name" id="referral_hospital_name"
                                    class="form-control" placeholder="Enter referral hospital name">
                            </div>
                            <div class="field-group">
                                <label for="referral_reason" class="form-label">
                                    <i class="bi bi-person"></i>
                                    Reason For Referral
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="referral_reason" id="referral_reason"
                                    class="form-control" placeholder="Enter reason for referral">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i>
                    Cancel
                </button>
                <button type="submit" form="patientDischargeForm" class="btn btn-save">
                    <i class="bi bi-check-circle"></i>
                    Save Discharge
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('patientDischargeForm');
        const dischargeStatusSelect = document.getElementById('discharge_status');
        const deathFields = document.getElementById('deathFields');
        const deathDateInput = document.getElementById('death_date');
        const guardianNameInput = document.getElementById('guardian_name');
        const referralFields = document.getElementById('referralFields');
        const referralDateInput = document.getElementById('referral_date');
        const referralHospitalNameInput = document.getElementById('referral_hospital_name');
        const referralReasonInput = document.getElementById('referral_reason');



        const dischargeModal = document.getElementById('patientDischargeModal');
        dischargeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('ipd-id').value = button.getAttribute('data-id');
        });



        // Set current date/time as default
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('discharge_date').value = now.toISOString().slice(0, 16);

        // Handle discharge status change
        dischargeStatusSelect.addEventListener('change', function() {
            if (this.value === 'death') {
                deathFields.style.display = 'block';
                deathDateInput.required = true;
                guardianNameInput.required = true;

                // Set death date to current date/time by default
                deathDateInput.value = now.toISOString().slice(0, 16);
            } else {
                deathFields.style.display = 'none';
                deathDateInput.required = false;
                guardianNameInput.required = false;

                // Clear death fields when not death
                deathDateInput.value = '';
                guardianNameInput.value = '';
                document.getElementById('attachment').value = '';
                document.getElementById('report').value = '';
            }

            if (this.value === 'referral') {
                referralFields.style.display = 'block';
                referralDateInput.required = true;
                referralHospitalNameInput.required = true;
                // referralReasonInput.required = true;

                // Set referral date to current date/time by default
                referralDateInput.value = now.toISOString().slice(0, 16);
            } else {
                referralFields.style.display = 'none';
                referralDateInput.required = false;
                referralHospitalNameInput.required = false;
                // referralReasonInput.required = false;
                referralDateInput.value = '';
                referralHospitalNameInput.value = '';
                referralReasonInput.value = '';
            }
        });

        // File upload handler
        const fileInput = document.getElementById('attachment');
        const fileLabel = document.querySelector('.file-upload-text');

        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                fileLabel.textContent = this.files[0].name;
            } else {
                fileLabel.textContent = 'Drop a file here or click';
            }
        });
    })
</script>

<script>
    document.getElementById('patientDischargeForm').addEventListener('submit', function(e) {
        e.preventDefault(); // ⛔ stop immediate submit

        // Dummy payment status (for now)
        const isPaymentCleared = false; // 🔁 change later with real API

        if (!isPaymentCleared) {
            Swal.fire({
                title: 'Payment Not Cleared!',
                text: 'Payment is pending. Do you want to continue discharge?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'No, Cancel',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit(); // ✅ submit form manually
                }
            });
        } else {
            Swal.fire({
                title: 'Payment Cleared',
                text: 'Payment has been cleared. Proceeding with discharge.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
            }).then(() => {
                e.target.submit(); // ✅ submit form
            });
        }
    });
</script>
<?php /**PATH C:\xampp\htdocs\hims\resources\views/components/modals/discharge-modal.blade.php ENDPATH**/ ?>