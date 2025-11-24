<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<style>
    body {
        background-color: #f0f2f5;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .modal-backdrop.show {
        opacity: 0.6;
    }

    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    }

    .modal-header {
        background: linear-gradient(135deg, #75009673 0%, #CB6CE673 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1.5rem;
        border: none;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-header .btn-close:hover {
        opacity: 1;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
    }

    .patient-search {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        /* color: white; */
        border-radius: 8px;
        padding: 0.5rem 1rem;
    }

    .patient-search::placeholder {
        color: rgba(0, 0, 0, 0.7);
    }

    .patient-search:focus {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        color: black;
        box-shadow: none;
    }

    .btn-new-patient {
        background: white;
        color: var(--primary-color);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-new-patient:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .modal-body {
        padding: 0;
        max-height: 75vh;
        overflow-y: auto;
    }

    .section-card {
        background: white;
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem;
    }

    .section-card:last-child {
        border-bottom: none;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border-color);
    }

    .section-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #750096 0%, #CB6CE673 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .section-title {
        font-weight: 600;
        font-size: 1.05rem;
        color: #212529;
        margin: 0;
    }

    .patient-info-grid {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 0.75rem 1.5rem;
        align-items: center;
    }

    .patient-info-grid-tpa {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 0.75rem 1.5rem;
        align-items: center;
        text-align: start;
    }

    .info-label {
        color: var(--secondary-color);
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-label i {
        font-size: 1rem;
        color: var(--primary-color);
    }

    .info-value {
        color: #212529;
        font-weight: 500;
    }

    .patient-photo {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        background: var(--bg-light);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed var(--border-color);
        overflow: hidden;
    }

    .patient-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image-placeholder {
        text-align: center;
        color: var(--secondary-color);
    }

    .no-image-placeholder i {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }

    .barcode-section {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .barcode-item {
        flex: 1;
        text-align: center;
        padding: 1rem;
        background: var(--bg-light);
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .barcode-label {
        font-size: 0.75rem;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .form-label {
        font-weight: 500;
        color: #212529;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-label .required {
        color: var(--primary-color);
    }

    .form-control,
    .form-select {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.15);
    }

    .input-group-text {
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--secondary-color);
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid var(--border-color);
        border-radius: 4px;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-label {
        margin-left: 0.5rem;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .vital-signs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .vital-card {
        background: var(--bg-light);
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid var(--border-color);
    }

    .vital-label {
        font-size: 0.75rem;
        color: var(--secondary-color);
        font-weight: 500;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .vital-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: #212529;
    }

    .symptoms-section textarea {
        min-height: 100px;
        resize: vertical;
    }

    .modal-footer {
        background: var(--bg-light);
        border-top: 1px solid var(--border-color);
        padding: 1.25rem 1.5rem;
        border-radius: 0 0 12px 12px;
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

    .modal-stacked {
        z-index: 1040 !important;
    }

    #add_patient {
        z-index: 1060 !important;
    }

    .modal-backdrop.show {
        z-index: 1050 !important;
    }

    @media (max-width: 768px) {
        .patient-info-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .vital-signs-grid {
            grid-template-columns: 1fr;
        }

        .barcode-section {
            flex-direction: column;
        }
    }


    /* custom multiselect */
    /* Hide the native select */
    .custom-multiselect-wrapper select {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    /* Custom Select Container */
    .custom-multiselect {
        position: relative;
        width: 100%;
    }

    /* Select Box */
    .multiselect-selected {
        min-height: 44px;
        padding: 8px 40px 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: white;
        cursor: pointer;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
        transition: all 0.2s ease;
        position: relative;
    }

    .multiselect-selected:hover {
        border-color: var(--primary-color);
    }

    .multiselect-selected.active {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.15);
    }

    /* Placeholder */
    .multiselect-placeholder {
        color: #9ca3af;
        font-size: 0.9rem;
        user-select: none;
    }

    /* Chips */
    .multiselect-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #750096;
        color: #ffffff;
        padding: 4px 8px 4px 12px;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        animation: chipAppear 0.2s ease;
    }

    @keyframes chipAppear {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .multiselect-chip-remove {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.75rem;
    }

    .multiselect-chip-remove:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: scale(1.1);
    }

    /* Dropdown Arrow */
    .multiselect-arrow {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6b7280;
        transition: transform 0.2s ease;
    }

    .multiselect-selected.active .multiselect-arrow {
        transform: translateY(-50%) rotate(180deg);
    }

    /* Dropdown Options */
    .multiselect-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        animation: dropdownSlide 0.2s ease;
    }

    @keyframes dropdownSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .multiselect-dropdown.show {
        display: block;
    }

    /* Search Input */
    .multiselect-search {
        position: sticky;
        top: 0;
        padding: 8px;
        background: white;
        border-bottom: 1px solid var(--border-color);
        z-index: 1;
    }

    .multiselect-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.2s ease;
    }

    .multiselect-search input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.15rem rgba(0, 0, 0, 0.1);
    }

    /* Options List */
    .multiselect-options {
        padding: 4px;
    }

    .multiselect-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.15s ease;
        user-select: none;
    }

    .multiselect-option:hover {
        background: #f3f4f6;
    }

    .multiselect-option.selected {
        background: #75009622;
        color: black;
        margin-bottom: 0.25rem
    }

    /* Custom Checkbox */
    .multiselect-checkbox {
        width: 18px;
        height: 18px;
        border: 2px solid #750096;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .multiselect-option.selected .multiselect-checkbox {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .multiselect-checkbox i {
        color: #750096;
        font-size: 0.75rem;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .multiselect-option.selected .multiselect-checkbox i {
        opacity: 1;
    }

    /* No Results */
    .multiselect-no-results {
        padding: 20px;
        text-align: center;
        color: #9ca3af;
        font-size: 0.875rem;
    }

    /* Scrollbar Styling */
    .multiselect-dropdown::-webkit-scrollbar {
        width: 8px;
    }

    .multiselect-dropdown::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .multiselect-dropdown::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .multiselect-dropdown::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Select All Button */
    .multiselect-actions {
        position: sticky;
        top: 57px;
        padding: 8px;
        background: #f8f9fa;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        gap: 8px;
    }

    .multiselect-action-btn {
        flex: 1;
        padding: 6px 12px;
        border: 1px solid var(--border-color);
        background: white;
        border-radius: 6px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .multiselect-action-btn:hover {
        background: #CB6CE6;
        color: white;
        border-color: #CB6CE6;
    }

    /* Demo Container */
    .demo-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1f2937;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="createIpdModal" tabindex="-1" aria-labelledby="addSpecializationLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="<?php echo e(route('ipd.store')); ?>" id="ipdForm" method="POST">
                <?php echo csrf_field(); ?>
                <!-- Modal Header -->
                <div class="modal-header align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="modal-title mb-3">Patient Appointment</h5>
                        <div class="d-flex gap-3 align-items-center">
                            <select type="text" class="form-select patient-search flex-grow-1"
                                placeholder="Search patient by name or ID..." id="patient_select" name="patient_id">
                                <option value="">Loading...</option>
                            </select>
                            <div class="flex-nowrap text-nowrap">
                                <button type="button" class="btn btn-primary" id="openAddPatientBtn">
                                    <i class="bi bi-person-plus me-2"></i>New Patient
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close button-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Patient Information Section -->
                    <div class="section-card patient-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <h6 class="section-title">Patient Information</h6>
                        </div>

                        <div class="row">
                            <div class="col-md-9">
                                <div class="patient-info-grid">
                                    <div class="info-label">
                                        <i class="bi bi-person-circle"></i> Patient Name
                                    </div>
                                    <div class="info-value" id="patient_name_value">-</div>

                                    <div class="info-label">
                                        <i class="bi bi-gender-ambiguous"></i> Gender
                                    </div>
                                    <div class="info-value" id="patient_gender_value">-</div>

                                    <div class="info-label">
                                        <i class="bi bi-heart-pulse"></i> Age
                                    </div>
                                    <div class="info-value" id="patient_age_value">-</div>

                                    <div class="info-label">
                                        <i class="bi bi-calendar-heart"></i> Marital Status
                                    </div>
                                    <div class="info-value" id="patient_marital_status_value">-</div>

                                    <div class="info-label">
                                        <i class="bi bi-droplet"></i> Blood Group
                                    </div>
                                    <div class="info-value" id="patient_blood_value">-</div>

                                    <div class="info-label">
                                        <i class="bi bi-telephone"></i> Phone
                                    </div>
                                    <div class="info-value" id="patient_phone_value">-</div>

                                    <div class="info-label">
                                        <i class="bi bi-geo-alt"></i> Location
                                    </div>
                                    <div class="info-value" id="patient_location_value">-</div>
                                </div>
                            </div>

                            <div class="col-md-3 text-center">
                                <div class="d-flex flex-column">
                                    <div class="patient-photo mx-auto mb-3">
                                        <img id="patient_photo" src="" alt="No Image" class="img-fluid rounded"
                                            style="display:none;" />
                                        <div class="no-image-placeholder">
                                            <i class="bi bi-person-bounding-box"></i>
                                            <div style="font-size: 0.75rem;">NO IMAGE<br>AVAILABLE</div>
                                        </div>
                                    </div>
                                    <div class="patient-info-grid-tpa">
                                        <div class="info-label">TPA</div>
                                        <div class="info-value" id="patient_tpa_value">-</div>

                                        <div class="info-label">TPA Code</div>
                                        <div class="info-value" id="patient_tpa_code_value">-</div>

                                        <div class="info-label">TPA Validity</div>
                                        <div class="info-value" id="patient_tpa_validity_value">-</div>

                                        <div class="info-label">Identification Number</div>
                                        <div class="info-value" id="patient_identification_value">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barcode Section -->
                        <div class="barcode-section">
                            <div class="barcode-item">
                                <div class="barcode-label">BARCODE</div>
                                <svg width="120" height="40" viewBox="0 0 120 40">
                                    <rect x="0" y="0" width="4" height="40" fill="#000" />
                                    <rect x="6" y="0" width="2" height="40" fill="#000" />
                                    <rect x="10" y="0" width="6" height="40" fill="#000" />
                                    <rect x="18" y="0" width="4" height="40" fill="#000" />
                                    <rect x="24" y="0" width="2" height="40" fill="#000" />
                                    <rect x="28" y="0" width="4" height="40" fill="#000" />
                                    <rect x="34" y="0" width="6" height="40" fill="#000" />
                                    <rect x="42" y="0" width="2" height="40" fill="#000" />
                                    <rect x="46" y="0" width="4" height="40" fill="#000" />
                                    <rect x="52" y="0" width="6" height="40" fill="#000" />
                                </svg>
                            </div>
                            <div class="barcode-item">
                                <div class="barcode-label">QR CODE</div>
                                <svg width="80" height="80" viewBox="0 0 80 80">
                                    <rect x="0" y="0" width="80" height="80" fill="white" />
                                    <rect x="5" y="5" width="10" height="10" fill="#000" />
                                    <rect x="20" y="5" width="5" height="10" fill="#000" />
                                    <rect x="30" y="5" width="10" height="5" fill="#000" />
                                    <rect x="45" y="5" width="5" height="10" fill="#000" />
                                    <rect x="55" y="5" width="20" height="10" fill="#000" />
                                    <rect x="5" y="20" width="5" height="10" fill="#000" />
                                    <rect x="15" y="20" width="10" height="5" fill="#000" />
                                    <rect x="30" y="20" width="15" height="10" fill="#000" />
                                    <rect x="50" y="20" width="5" height="5" fill="#000" />
                                    <rect x="60" y="20" width="15" height="10" fill="#000" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details Section -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <h6 class="section-title">Appointment Details</h6>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Appointment Date <span class="required">*</span></label>
                                <input type="date" class="form-control" name="appointment_date">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Case Type</label>
                                <select class="form-select" name="case_type">
                                    <option value="">Select Case Type</option>
                                    <option value="Old Patient">Old Patient</option>
                                    <option value="New Patient">New Patient</option>
                                </select>
                            </div>
                            <div class="col-md-2 align-items-center d-flex">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="applyTPA" name="apply_tpa"
                                        value="1">
                                    <label class="form-check-label" for="applyTPA">Apply TPA</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Casualty</label>
                                <select class="form-select" name="casualty">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Reference</label>
                                <input type="text" class="form-control" name="reference"
                                    placeholder="Enter reference">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Consultant Doctor <span class="required">*</span></label>
                                <select class="form-select" name="doctor_id" id="doctor_select">
                                    <option value="">Loading...</option>
                                </select>
                            </div>


                        </div>
                    </div>

                    <!-- Billing Information Section -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <h6 class="section-title">Billing Information</h6>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Credit Limit (INR) <span class="required">*</span></label>
                                <input type="number" class="form-control" name="credit_limit" id="credit_limit"
                                    value="20000" placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Live Consultation</label>
                                <select class="form-select" name="live_consultation">
                                    <option value="No" selected>No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bed Group <span class="required">*</span></label>
                                <select class="form-select" name="bed_group" id="bed_group_select">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bed Number <span class="required">*</span></label>
                                <select class="form-select" name="bed_number" id="bed_number_select">
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            

                        </div>
                    </div>

                    <!-- Symptoms Section -->
                    <div class="section-card symptoms-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="bi bi-clipboard-pulse"></i>
                            </div>
                            <h6 class="section-title">Symptoms & Notes</h6>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Symptoms Type</label>
                                <div class="custom-multiselect-wrapper">
                                    <!-- Hidden native select (for form submission) -->
                                    <select name="symptoms_type[]" id="symptoms_type" class="form-select" multiple>
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
                                                    class="multiselect-action-btn select-all">Select All</button>
                                                <button type="button" class="multiselect-action-btn clear-all">Clear
                                                    All</button>
                                            </div>

                                            <div class="multiselect-options">
                                                <!-- Options will be generated by JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Symptoms Title</label>
                                <div class="custom-multiselect-wrapper">
                                    <!-- Hidden native select (for form submission) -->
                                    <select name="symptoms_title[]" id="symptoms_title" class="form-select" multiple>
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
                                                    <input type="text" placeholder="Search symptom titles...">
                                                </div>

                                                <div class="multiselect-actions">
                                                    <button type="button"
                                                        class="multiselect-action-btn select-all">Select All</button>
                                                    <button type="button"
                                                        class="multiselect-action-btn clear-all">Clear All</button>
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

                            
                            <div class="col-md-4">
                                <label class="form-label">Symptoms Description</label>
                                <textarea class="form-control" name="symptoms_description" rows="1"
                                    placeholder="Enter detailed symptoms description"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Note</label>
                                <textarea class="form-control" name="note" rows="3" placeholder="Enter additional notes"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-outline-dark button-close" id="button-close"
                        data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-floppy me-2"></i>Save
                    </button>
                    <button type="submit" class="btn btn-primary" name="save_print" value="1">
                        <i class="bi bi-printer me-2"></i>Save & Print
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php echo $__env->make('components.modals.add-patients-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addPatientBtn = document.getElementById("openAddPatientBtn");
        const createIpdModal = document.getElementById("createIpdModal");
        const addPatientModal = document.getElementById("add_patient");

        addPatientBtn.addEventListener("click", function() {
            // Keep the first modal open
            const ipdModalInstance = bootstrap.Modal.getInstance(createIpdModal);
            ipdModalInstance._element.classList.add('modal-stacked');

            // Open the second modal manually (no new backdrop)
            const newModal = new bootstrap.Modal(addPatientModal, {
                backdrop: false
            });
            newModal.show();

            // Adjust z-index
            addPatientModal.style.zIndex = 1060;

            // Create a custom backdrop manually for the second modal
            const backdrop = document.createElement('div');
            backdrop.classList.add('modal-backdrop', 'fade', 'show');
            backdrop.style.zIndex = 1050;
            backdrop.dataset.stacked = "true"; // mark for later cleanup
            document.body.appendChild(backdrop);
        });

        // Cleanup when second modal closes
        addPatientModal.addEventListener('hidden.bs.modal', () => {
            // Remove only the custom backdrop
            const stackedBackdrop = document.querySelector('.modal-backdrop[data-stacked="true"]');
            if (stackedBackdrop) stackedBackdrop.remove();

            // Restore body scroll lock (Bootstrap removes it otherwise)
            document.body.classList.add('modal-open');

            // Reset first modal’s stacking class
            createIpdModal.classList.remove('modal-stacked');
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const patientSection = document.querySelector(".patient-card")
        patientSection.style.display = "none"
        const patientSelect = document.getElementById('patient_select');
        const photo = document.getElementById('patient_photo');
        patientSelect.innerHTML = '<option value="">Loading...</option>';

        fetch("<?php echo e(route('getPatients')); ?>")
            .then(response => {
                return response.json()
            })
            .then(data => {
                window.patientsData = data;
                patientSelect.innerHTML = '<option value="">Select</option>';
                data.forEach(patient => {
                    const option = document.createElement('option');
                    option.value = patient.id;
                    option.textContent = patient.patient_name;
                    if ("<?php echo e(old('patient_select')); ?>" == patient.id) {
                        option.selected = true;
                    }
                    patientSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching patients:', error);
                patientSelect.innerHTML = '<option value="">Error loading options</option>';
            });
        // When patient is selected
        patientSelect.addEventListener('change', function() {
            const selected = window.patientsData.find(p => p.id == this.value);

            if (selected) {
                patientSection.style.display = 'block'
                document.getElementById('patient_name_value').textContent =
                    `${selected.patient_name} (${selected.id})`;
                document.getElementById('patient_gender_value').textContent = selected.gender || 'N/A';
                document.getElementById('patient_age_value').textContent = selected.age + " Year " +
                    selected.month + " Month " + selected.day + " Days " || 'N/A';
                document.getElementById('patient_marital_status_value').textContent = selected
                    .marital_status || 'N/A';
                document.getElementById('patient_blood_value').textContent = selected.blood_group ||
                    'N/A';
                document.getElementById('patient_phone_value').textContent = selected.mobileno || 'N/A';
                document.getElementById('patient_location_value').textContent = selected.address ||
                    'N/A';

                document.getElementById('patient_tpa_value').textContent = selected.organisation
                    .organisation_name || 'N/A';
                document.getElementById('patient_tpa_code_value').textContent = selected.organisation
                    .code ||
                    'N/A';
                document.getElementById('patient_tpa_validity_value').textContent = selected
                    .tpa_validity || 'N/A';
                document.getElementById('patient_identification_value').textContent = selected
                    .identification_number || 'N/A';

                // Handle photo display
                if (selected.photo_path) {
                    photo.src = selected.image;
                    photo.style.display = 'block';
                    noImagePlaceholder.style.display = 'none';
                } else {
                    photo.style.display = 'none';
                    noImagePlaceholder.style.display = 'block';
                }
            } else {
                // Reset if none selected
                [
                    'patient_name_value', 'patient_gender_value', 'patient_age_value',
                    'patient_marital_status_value', 'patient_blood_value',
                    'patient_phone_value', 'patient_location_value',
                    'patient_tpa_value', 'patient_tpa_code_value',
                    'patient_tpa_validity_value', 'patient_identification_value'
                ].forEach(id => document.getElementById(id).textContent = '—');

                patientSection.style.display = 'none';
                photo.style.display = 'none';
                noImagePlaceholder.style.display = 'block';
            }
        });

    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const doctorSelect = document.getElementById('doctor_select');
        const bedGroupSelect = document.getElementById('bed_group_select');
        const bedNumberSelect = document.getElementById('bed_number_select');

        // const standardCharge = document.getElementById('standard_charge');
        // const appliedCharge = document.getElementById('applied_charge');
        // const discount = document.getElementById('discount');
        // const tax = document.getElementById('tax');
        // const amount = document.getElementById('amount');
        // const paidAmount = document.getElementById('paid_amount');
        const symptomTypesSelect = document.getElementById('symptoms_type');

        doctorSelect.innerHTML = '<option value="">Loading...</option>';
        bedGroupSelect.innerHTML = '<option value="">Loading...</option>';
        bedNumberSelect.innerHTML = '<option value="">Loading...</option>';
        symptomTypesSelect.innerHTML = '<option value="">Loading...</option>';

        //doctor
        fetch("<?php echo e(route('getDoctors')); ?>")
            .then(response => response.json())
            .then(data => {
                window.doctorsData = data;
                doctorSelect.innerHTML = '<option value="">Select</option>';
                data.forEach(doc => {
                    const option = document.createElement('option');
                    option.value = doc.id;
                    option.textContent = doc.name;
                    if ("<?php echo e(old('doctor_id')); ?>" == doc.id) {
                        option.selected = true;
                    }
                    doctorSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching doctors:', error);
                doctorSelect.innerHTML = '<option value="">Error loading options</option>';
            });

        //charge category
        fetch("<?php echo e(route('getBedGroups')); ?>")
            .then(response => response.json())
            .then(data => {
                window.bedGroupData = data;
                bedGroupSelect.innerHTML = '<option value="">Select</option>';
                data.forEach(bedGroup => {
                    const option = document.createElement('option');
                    option.value = bedGroup.id;
                    console.log();
                    option.textContent = bedGroup.name + ' - ' + bedGroup.floor_detail.name;
                    if ("<?php echo e(old('bed_group')); ?>" == bedGroup.id) {
                        option.selected = true;
                    }
                    bedGroupSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching bed groups:', error);
                bedGroupSelect.innerHTML = '<option value="">Error loading options</option>';
            });

        // Listen for Charge Category dropdown change
        bedGroupSelect.addEventListener('change', function() {
            const selectedId = this.value;
            const baseUrl = "<?php echo e(route('getBedNumbers', ['id' => 'ID'])); ?>";
            const finalUrl = baseUrl.replace('ID', selectedId);
            fetch(finalUrl)
                .then(response => response.json())
                .then(data => {
                    window.bedNumberData = data;
                    bedNumberSelect.innerHTML = '<option value="">Select</option>';
                    bedNumberSelect.disabled = false
                    if (data.length <= 0) {
                        bedNumberSelect.innerHTML = '<option value="">No Bed Available</option>';
                        bedNumberSelect.disabled = true
                    } else {
                        data.forEach(bedNumber => {
                            const option = document.createElement('option');
                            option.value = bedNumber.id;
                            option.textContent = bedNumber.name;
                            if ("<?php echo e(old('bed_number')); ?>" == bedNumber.id) {
                                option.selected = true;
                            }
                            bedNumberSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching Bed Numbers:', error);
                    bedNumberSelect.innerHTML = '<option value="">Error loading options</option>';
                });

            // bedNumberSelect.addEventListener('change', function() {
            //     const selectedCharge = window.bedNumberData[0];
            //     standardCharge.value = selectedCharge.standard_charge
            //     appliedCharge.value = selectedCharge.standard_charge
            //     tax.value = selectedCharge.tax_category.percentage
            //     calculateAmount();
            // })
            // if (!appliedCharge || !tax || !discount || !amount) {
            //     console.error("❌ One or more required input fields are missing in the DOM.");
            //     return;
            // }
            // [appliedCharge, tax, discount].forEach(field => {
            //     field.addEventListener('input', calculateAmount);
            // });

            // function calculateAmount() {
            //     const appliedChargeValue = parseFloat(appliedCharge.value) || 0;
            //     const taxValue = parseFloat(tax.value) || 0;
            //     const discountValue = parseFloat(discount.value) || 0;

            //     // Formula: Amount = (AppliedCharge + Tax%) - Discount%
            //     const taxAmount = appliedChargeValue * (taxValue / 100);
            //     const discountAmount = appliedChargeValue * (discountValue / 100);
            //     const totalAmount = appliedChargeValue + taxAmount - discountAmount;

            //     amount.value = totalAmount.toFixed(2);
            //     paidAmount.value = totalAmount.toFixed(2);
            // }
        });


        fetch("<?php echo e(route('getSymptomsTypes')); ?>")
            .then(response => response.json())
            .then(data => {
                window.symptomTypesData = data;
                symptomTypesSelect.innerHTML = '<option value="">Select</option>';
                data.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type.id;
                    option.textContent = type.symptoms_type;
                    if ("<?php echo e(old('symptoms_type[]')); ?>" == type.id) {
                        option.selected = true;
                    }
                    symptomTypesSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching Symptoms Types:', error);
                symptomTypesSelect.innerHTML = '<option value="">Error loading options</option>';
            });

    });
</script>






<script>
    class CustomMultiSelect {
        constructor(selectId, options = {}) {
            this.selectElement = document.getElementById(selectId);
            this.wrapper = this.selectElement.closest('.custom-multiselect-wrapper');
            this.customSelect = this.wrapper.querySelector('.custom-multiselect');
            this.selectedBox = this.customSelect.querySelector('.multiselect-selected');
            this.dropdown = this.customSelect.querySelector('.multiselect-dropdown');
            this.optionsContainer = this.customSelect.querySelector('.multiselect-options');
            this.searchInput = this.customSelect.querySelector('.multiselect-search input');
            this.selectAllBtn = this.customSelect.querySelector('.select-all');
            this.clearAllBtn = this.customSelect.querySelector('.clear-all');

            this.selectedValues = [];
            this.allOptions = [];
            this.allTitleOptions = [];
            this.onChange = options.onChange || null;
            this.isDisabled = options.disabled || false;

            this.init();
        }

        init() {
            this.setupEventListeners();
            if (!this.isDisabled) {
                this.initializeOptions();
            }
        }

        setupEventListeners() {
            this.selectedBox.addEventListener('click', () => {
                if (!this.isDisabled) {
                    this.toggleDropdown(!this.dropdown.classList.contains('show'));
                }
            });

            this.searchInput.addEventListener('input', (e) => {
                this.filterOptions(e.target.value);
            });

            this.searchInput.addEventListener('click', (e) => {
                e.stopPropagation();
            });

            this.selectAllBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selectAll();
            });

            this.clearAllBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.clearAll();
            });

            document.addEventListener('click', (e) => {
                if (!this.customSelect.contains(e.target)) {
                    this.toggleDropdown(false);
                }
            });

            this.selectedBox.addEventListener('keydown', (e) => {
                if ((e.key === 'Enter' || e.key === ' ') && !this.isDisabled) {
                    e.preventDefault();
                    this.toggleDropdown(!this.dropdown.classList.contains('show'));
                }
            });
        }

        initializeOptions() {
            this.optionsContainer.innerHTML = '';
            const options = Array.from(this.selectElement.options);
            this.allOptions = options.filter(opt => opt.value).map(opt => ({
                value: opt.value,
                text: opt.text
            }));

            this.renderOptions();
        }

        renderOptions() {
            this.optionsContainer.innerHTML = '';

            if (this.allOptions.length === 0) {
                this.optionsContainer.innerHTML = '<div class="multiselect-no-results">No options available</div>';
                return;
            }

            this.allOptions.forEach(option => {
                const optionElement = document.createElement('div');
                optionElement.className = 'multiselect-option';
                optionElement.dataset.value = option.id;
                optionElement.innerHTML = `
                <div class="multiselect-checkbox">
                    <i class="bi bi-check"></i>
                </div>
                <span>${option.symptoms_type??option.symptoms_title}</span>
            `;

                optionElement.addEventListener('click', () => this.toggleOption(option.id));
                this.optionsContainer.appendChild(optionElement);
            });
        }

        toggleOption(value) {
            const index = this.selectedValues.indexOf(value);

            if (index > -1) {
                this.selectedValues.splice(index, 1);
            } else {
                this.selectedValues.push(value);
            }

            this.updateUI();
            this.updateNativeSelect();

            if (this.onChange) {
                this.onChange(this.selectedValues);
            }
        }

        updateUI() {
            const placeholder = this.selectedBox.querySelector('.multiselect-placeholder');
            const existingChips = this.selectedBox.querySelectorAll('.multiselect-chip');
            existingChips.forEach(chip => chip.remove());

            if (this.selectedValues.length === 0) {
                placeholder.style.display = 'block';
            } else {
                placeholder.style.display = 'none';

                this.selectedValues.forEach(value => {


                    const option = this.allOptions.find(opt => opt.id === value);
                    if (option) {
                        const chip = document.createElement('div');
                        chip.className = 'multiselect-chip';
                        chip.innerHTML = `
                        <span>${option.symptoms_type??option.symptoms_title}</span>
                        <div class="multiselect-chip-remove" data-value="${value}">
                            <i class="bi bi-x"></i>
                        </div>
                    `;

                        chip.querySelector('.multiselect-chip-remove').addEventListener('click', (e) => {
                            e.stopPropagation();
                            this.toggleOption(value);
                        });

                        this.selectedBox.insertBefore(chip, this.selectedBox.querySelector(
                            '.multiselect-arrow'));
                    }
                });
            }

            const allOptions = this.optionsContainer.querySelectorAll('.multiselect-option');
            allOptions.forEach(opt => {

                if (this.selectedValues.includes(Number(
                        opt.dataset.value))) {
                    opt.classList.add('selected');
                } else {
                    opt.classList.remove('selected');
                }
            });
        }

        updateNativeSelect() {
            Array.from(this.selectElement.options).forEach(option => {

                option.selected = this.selectedValues.includes(Number(option.value));
            });
        }

        toggleDropdown(show) {
            if (show) {
                this.dropdown.classList.add('show');
                this.selectedBox.classList.add('active');
                this.searchInput.focus();
            } else {
                this.dropdown.classList.remove('show');
                this.selectedBox.classList.remove('active');
                this.searchInput.value = '';
                this.filterOptions('');
            }
        }

        filterOptions(searchTerm) {
            const options = this.optionsContainer.querySelectorAll('.multiselect-option');
            const term = searchTerm.toLowerCase();
            let hasResults = false;

            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(term)) {
                    option.style.display = 'flex';
                    hasResults = true;
                } else {
                    option.style.display = 'none';
                }
            });

            let noResultsMsg = this.optionsContainer.querySelector('.multiselect-no-results');
            if (!hasResults) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'multiselect-no-results';
                    noResultsMsg.textContent = 'No results found';
                    this.optionsContainer.appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        selectAll() {
            const visibleOptions = Array.from(this.optionsContainer.querySelectorAll('.multiselect-option'))
                .filter(opt => opt.style.display !== 'none');

            visibleOptions.forEach(opt => {

                if (!this.selectedValues.includes(Number(opt.dataset.value))) {
                    this.selectedValues.push(Number(opt.dataset.value));
                }
            });

            this.updateUI();
            this.updateNativeSelect();

            if (this.onChange) {
                this.onChange(this.selectedValues);
            }
        }

        clearAll() {
            this.selectedValues = [];
            this.updateUI();
            this.updateNativeSelect();

            if (this.onChange) {
                this.onChange(this.selectedValues);
            }
        }

        showLoading() {
            this.optionsContainer.innerHTML = `
            <div class="multiselect-loading">
                <div class="spinner"></div>
                Loading options...
            </div>
        `;
        }

        setOptions(options) {
            // console.log("options", options);

            this.selectElement.innerHTML = '';

            options.forEach(opt => {
                // console.log(opt, opt.id, opt.symptoms_type ?? opt.symptoms_title);

                const option = document.createElement('option');
                option.value = opt.id;
                option.innerHTML = opt.symptoms_type ?? opt.symptoms_title;
                this.selectElement.appendChild(option);
            });

            this.allOptions = options;

            this.renderOptions();
        }

        enable() {
            this.isDisabled = false;
            this.selectedBox.classList.remove('disabled');
        }

        disable() {
            this.isDisabled = true;
            this.selectedBox.classList.add('disabled');
            this.toggleDropdown(false);
        }

        reset() {
            this.selectedValues = [];
            this.allOptions = [];
            this.selectElement.innerHTML = '<option value="">Select symptom types first...</option>';
            this.optionsContainer.innerHTML =
                '<div class="multiselect-no-results">Please select symptom types to load titles</div>';
            this.updateUI();
        }

        getSelectedValues() {
            return this.selectedValues;
        }
    }

    // Initialize the application
    document.addEventListener('DOMContentLoaded', function() {
        const createOpdModal = document.getElementById('createIpdModal');
        const closeButton = createOpdModal.querySelector('.button-close');
        const cancelButton = document.getElementById('button-close');

        createOpdModal.addEventListener('shown.bs.modal', function() {
            if (window.symptomTypeSelect) window.symptomTypeSelect = null;
            if (window.symptomTitleSelect) window.symptomTitleSelect = null;

            // Mock data for symptom types
            const symptomTypes = window.symptomTypesData;



            // Initialize Symptom Type Select
            const symptomTypeSelect = new CustomMultiSelect('symptoms_type', {
                onChange: function(selectedValues) {
                    loadSymptomTitles(selectedValues);
                }
            });

            // Load initial symptom types
            symptomTypeSelect.setOptions(symptomTypes);

            // Initialize Symptom Title Select (disabled initially)
            const symptomTitleSelect = new CustomMultiSelect('symptoms_title', {
                disabled: true,
                onChange: function(selectedValues) {}
            });


            function loadSymptomTitles(selectedTypeIds) {
                if (selectedTypeIds.length === 0) {
                    symptomTitleSelect.disable();
                    symptomTitleSelect.reset();
                    return;
                }
                symptomTitleSelect.reset();
                symptomTitleSelect.showLoading();
                symptomTitleSelect.enable();
                const symptomSelect = document.getElementById('symptoms_title');
                fetch("<?php echo e(route('getSymptoms')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify({
                            selectedTypeIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        symptomSelect.innerHTML = '<option value="">Select</option>';
                        data.forEach(title => {
                            const option = document.createElement('option');
                            option.value = title.id;
                            option.textContent = title.symptoms_title;
                            if ("<?php echo e(old('symptoms_title[]')); ?>" == title.id) {
                                option.selected = true;
                            }
                            symptomSelect.appendChild(option);
                        });
                        symptomTitleSelect.setOptions(data);
                    })
                    .catch(error => {
                        console.error('Error loading symptom titles:', error);
                        symptomTitleSelect.optionsContainer.innerHTML =
                            '<div class="multiselect-no-results">Error loading titles. Please try again.</div>';
                    });
            }

        });

        closeButton.addEventListener('click', () => {
            window.location.reload();
        })


        cancelButton.addEventListener('click', () => {
            window.location.reload();
        })
    })
</script>
<?php /**PATH D:\xampp-8.2\htdocs\hims\resources\views/components/modals/ipd-create-modal.blade.php ENDPATH**/ ?>