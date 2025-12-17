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

    .modal-header {
        background: linear-gradient(135deg, #75009673 0%, #CB6CE673 100%);
        color: white;
        padding: 1.5rem;
        border: none;
        position: relative;
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
        padding: 0;
        background: var(--bg-light);
    }

    /* Patient Photo Section */
    .patient-photo-section {
        background: white;
        padding: 2rem;
        text-align: center;
        border-bottom: 1px solid var(--border-color);
    }

    .patient-photo {
        width: 150px;
        height: 150px;
        border-radius: 12px;
        background: var(--bg-light);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--border-color);
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .patient-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image-placeholder {
        text-align: center;
        color: var(--text-muted);
    }

    .no-image-placeholder i {
        font-size: 3.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.4;
    }

    .no-image-text {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Patient Info Grid */
    .patient-info-section {
        background: white;
        padding: 1.5rem;
        margin: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .section-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--primary-color);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--primary-color);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-label i {
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .info-value {
        font-size: 1rem;
        color: var(--text-dark);
        font-weight: 500;
        padding-left: 1.6rem;
    }

    .info-value.empty {
        color: var(--text-muted);
        font-style: italic;
        opacity: 0.7;
    }

    /* Barcode Section */
    .barcode-section {
        background: white;
        padding: 1.5rem;
        margin: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .barcode-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .barcode-item {
        text-align: center;
        padding: 1.25rem;
        background: var(--bg-light);
        border-radius: 8px;
        border: 2px dashed var(--border-color);
    }

    .barcode-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .barcode-placeholder {
        padding: 1rem;
        background: white;
        border-radius: 6px;
        display: inline-block;
    }

    /* Modal Footer */
    .modal-footer {
        background: white;
        border-top: 1px solid var(--border-color);
        padding: 1rem 1.5rem;
        gap: 0.75rem;
    }

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

    /* .btn-print {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
    }

    .btn-print:hover {
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
        transform: translateY(-1px);
    }

    .btn-edit {
        background: linear-gradient(135deg, #e91e63 0%, #d81b60 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(233, 30, 99, 0.3);
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #d81b60 0%, #c2185b 100%);
        box-shadow: 0 4px 12px rgba(233, 30, 99, 0.4);
        transform: translateY(-1px);
    }

    .btn-close-modal {
        background: white;
        color: var(--text-muted);
        border: 1px solid var(--border-color);
    }

    .btn-close-modal:hover {
        background: var(--bg-light);
        color: var(--text-dark);
        border-color: var(--secondary-color);
    }*/

    /* Responsive Design */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .barcode-grid {
            grid-template-columns: 1fr;
        }

        .patient-photo {
            width: 120px;
            height: 120px;
        }

        .patient-info-section,
        .barcode-section {
            margin: 0.5rem;
            padding: 1rem;
        }
    }

    /* Additional Styles */
    .badge-status {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 4px;
        margin-left: 0.5rem;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }
</style>
</style>
<div class="modal fade" id="patientDetailModal" tabindex="-1" aria-labelledby="patientDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="patientDetailsModalLabel">
                    <div class="section-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    Patient Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

                <!-- Patient Photo Section -->
                <div class="patient-photo-section">
                    <div class="patient-photo">
                        <div class="no-image-placeholder">
                            <i class="bi bi-person-bounding-box"></i>
                            <div class="no-image-text">No Image<br>Available</div>
                        </div>
                        <!-- Use this for actual image -->
                        <!-- <img src="patient-photo.jpg" alt="Patient Photo"> -->
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="patient-info-section">
                    <h6 class="section-title">Basic Information</h6>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-person-circle"></i>
                                Patient Name
                            </div>
                            <div class="info-value empty" id="patient_name_value"><?php echo e($patient->patient_name ?? '-'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-gender-ambiguous"></i>
                                Gender
                            </div>
                            <div class="info-value empty" id="gender_value"><?php echo e($patient->gender ?? '-'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-calendar-heart"></i>
                                Age
                            </div>
                            <div class="info-value empty" id="age_value">
                                <?php echo e($patient->age . ' Year ' . $patient->month . ' Month ' . $patient->day . ' Days' ?? '-'); ?>

                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-heart"></i>
                                Marital Status
                            </div>
                            <div class="info-value empty" id="marital_value"><?php echo e($patient->marital_status ?? '-'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-droplet-fill"></i>
                                Blood Group
                            </div>
                            <div class="info-value empty" id="blood_grp_value"><?php echo e($patient->bloodGroup->name ?? '-'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-telephone"></i>
                                Phone
                            </div>
                            <div class="info-value empty" id="phone_value"><?php echo e($patient->mobileno ?? '-'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-geo-alt"></i>
                                Location
                            </div>
                            <div class="info-value empty" id="address_value"><?php echo e($patient->address ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Medical & Insurance Information -->
                <div class="patient-info-section">
                    <h6 class="section-title">Medical & Insurance Information</h6>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-shield-plus"></i>
                                TPA
                            </div>
                            <div class="info-value empty" id="tpa_value"><?php echo e($patient->organisation->organisation_name ?? '-'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-credit-card-2-front"></i>
                                TPA Code
                            </div>
                            <div class="info-value empty" id="tpa_code_value"><?php echo e($patient->organisation->code ?? '-'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-calendar-check"></i>
                                TPA Validity
                            </div>
                            <div class="info-value empty" id="tpa_validity_value"><?php echo e($patient->tpa_validity ?? '-'); ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-card-heading"></i>
                                Identification Number
                            </div>
                            <div class="info-value empty" id="id_number_value"><?php echo e($patient->identification_number ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Barcode & QR Code Section -->
                <div class="barcode-section">
                    <h6 class="section-title">Patient Identification Codes</h6>
                    <div class="barcode-grid">
                        <div class="barcode-item">
                            <div class="barcode-label">Barcode</div>
                            <div class="barcode-placeholder">
                                <svg width="200" height="60" viewBox="0 0 200 60">
                                    <rect x="0" y="0" width="6" height="60" fill="#000" />
                                    <rect x="10" y="0" width="3" height="60" fill="#000" />
                                    <rect x="16" y="0" width="8" height="60" fill="#000" />
                                    <rect x="28" y="0" width="6" height="60" fill="#000" />
                                    <rect x="38" y="0" width="3" height="60" fill="#000" />
                                    <rect x="44" y="0" width="6" height="60" fill="#000" />
                                    <rect x="54" y="0" width="8" height="60" fill="#000" />
                                    <rect x="66" y="0" width="3" height="60" fill="#000" />
                                    <rect x="72" y="0" width="6" height="60" fill="#000" />
                                    <rect x="82" y="0" width="8" height="60" fill="#000" />
                                    <rect x="94" y="0" width="4" height="60" fill="#000" />
                                    <rect x="102" y="0" width="6" height="60" fill="#000" />
                                    <rect x="112" y="0" width="3" height="60" fill="#000" />
                                    <rect x="118" y="0" width="8" height="60" fill="#000" />
                                    <rect x="130" y="0" width="6" height="60" fill="#000" />
                                    <rect x="140" y="0" width="4" height="60" fill="#000" />
                                    <rect x="148" y="0" width="8" height="60" fill="#000" />
                                    <rect x="160" y="0" width="3" height="60" fill="#000" />
                                    <rect x="166" y="0" width="6" height="60" fill="#000" />
                                    <rect x="176" y="0" width="8" height="60" fill="#000" />
                                </svg>
                            </div>
                        </div>

                        <div class="barcode-item">
                            <div class="barcode-label">QR Code</div>
                            <div class="barcode-placeholder">
                                <svg width="120" height="120" viewBox="0 0 120 120">
                                    <rect x="0" y="0" width="120" height="120" fill="white" />
                                    <!-- QR Code Pattern -->
                                    <rect x="10" y="10" width="15" height="15" fill="#000" />
                                    <rect x="30" y="10" width="8" height="15" fill="#000" />
                                    <rect x="43" y="10" width="15" height="8" fill="#000" />
                                    <rect x="63" y="10" width="8" height="15" fill="#000" />
                                    <rect x="76" y="10" width="34" height="15" fill="#000" />

                                    <rect x="10" y="30" width="8" height="15" fill="#000" />
                                    <rect x="23" y="30" width="15" height="8" fill="#000" />
                                    <rect x="43" y="30" width="25" height="15" fill="#000" />
                                    <rect x="73" y="30" width="8" height="8" fill="#000" />
                                    <rect x="86" y="30" width="24" height="15" fill="#000" />

                                    <rect x="10" y="50" width="15" height="8" fill="#000" />
                                    <rect x="30" y="50" width="8" height="15" fill="#000" />
                                    <rect x="43" y="50" width="25" height="8" fill="#000" />
                                    <rect x="73" y="50" width="15" height="15" fill="#000" />
                                    <rect x="93" y="50" width="17" height="8" fill="#000" />

                                    <rect x="10" y="63" width="8" height="25" fill="#000" />
                                    <rect x="23" y="63" width="15" height="8" fill="#000" />
                                    <rect x="43" y="63" width="8" height="25" fill="#000" />
                                    <rect x="56" y="63" width="15" height="8" fill="#000" />
                                    <rect x="76" y="63" width="34" height="8" fill="#000" />

                                    <rect x="10" y="93" width="25" height="17" fill="#000" />
                                    <rect x="40" y="93" width="8" height="17" fill="#000" />
                                    <rect x="53" y="93" width="15" height="17" fill="#000" />
                                    <rect x="73" y="93" width="8" height="17" fill="#000" />
                                    <rect x="86" y="93" width="24" height="17" fill="#000" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i>
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\hims\resources\views\components\modals\patient-details-modal.blade.php ENDPATH**/ ?>