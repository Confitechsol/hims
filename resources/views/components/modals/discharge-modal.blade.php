@section('select2cdn')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
@endsection

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

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #ab00db66;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #ab00db66 !important;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        padding: 1rem 1rem 0;
        background: white;
        border-top: 2px solid var(--border-color);
        justify-content: flex-end;
    }

    .ts-wrapper .ts-control {
        padding: 0.625rem 0.875rem;
        border-radius: 8px;
    }

    .ck-editor__editable {
        min-height: 300px;
    }

    /* Medicine Row Styles */
    .med-row {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        position: relative;
        animation: slideDown 0.3s ease-out;
    }

    .med-row-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .med-row-number {
        font-weight: 700;
        color: #ab00db;
        font-size: 0.95rem;
    }

    .btn-remove-medicine {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-remove-medicine:hover {
        background: #c82333;
        transform: scale(1.05);
    }

    .btn-add-medicine {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-add-medicine:hover {
        background: linear-gradient(135deg, #218838 0%, #155724 100%);
        box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
        transform: translateY(-1px);
    }

    .btn-add-medicine i {
        font-size: 1.1rem;
    }

    .medicine-container {
        margin-bottom: 1.5rem;
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

            <!-- Form Body -->
            <div id="medLoader"
                class="position-absolute top-0 start-0 w-100 h-100 align-items-center justify-content-center bg-white bg-opacity-75"
                style="z-index: 1056; display: none;">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <div class="fw-semibold">Loading Form data...</div>
                </div>
            </div>
            <div class="modal-body">

                <div class="alert-custom">
                    <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                    <p class="alert-text">Please note that before discharging, check patient bill.</p>
                </div>
                <form id="patientDischargeForm" method="POST" action="{{ route('discharge.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Basic Information Section -->
                    <input type="hidden" name="ipd_details_id" id="ipd-id">

                    <h5 class="section-title mt-4">
                        <i class="bi bi-person-badge"></i>
                        Basic Information
                    </h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="patient_name" class="form-label">
                                <i class="bi bi-person"></i>
                                Patient Name <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="patient_name_text" name="patient_name"
                                required>
                            <input type="hidden" class="form-control" id="patient_id_text" name="patient_id" required>
                        </div>

                        <div class="col-md-6">
                            <label for="admission_no" class="form-label">
                                <i class="bi bi-credit-card"></i>
                                Admission No.
                            </label>
                            <input type="text" class="form-control" id="admission_no_text" name="admission_no">
                        </div>
                        <div class="col-md-3">
                            <label for="bed" class="form-label">
                                <i class="bi bi-hospital"></i>
                                Bed
                            </label>
                            <input type="text" class="form-control" id="bed_text" name="bed">
                        </div>
                        <div class="col-md-3">
                            <label for="discharge_time" class="form-label">
                                <i class="bi bi-clock"></i>
                                Discharge Time
                            </label>
                            <input type="time" class="form-control" id="discharge_time_text" name="discharge_time">
                        </div>

                        <div class="col-md-3">
                            <label for="discharge_date" class="form-label">
                                <i class="bi bi-calendar-event"></i>
                                Discharge Date <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" id="discharge_date_text" name="discharge_date"
                                required>
                        </div>



                        <div class="col-md-3">
                            <label for="admit_time" class="form-label">
                                <i class="bi bi-clock-history"></i>
                                Admit Time
                            </label>
                            <input type="time" class="form-control" id="admit_time_text" name="admit_time" readonly>
                        </div>
                    </div>

                    <!-- Patient Details Section -->

                    <h5 class="section-title mt-4">
                        <i class="bi bi-person-lines-fill"></i>
                        Patient Details
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="age" class="form-label">
                                <i class="bi bi-calendar3"></i>
                                Age
                            </label>
                            <input type="text" class="form-control" id="age_text" name="age" step="0.01">
                        </div>

                        <div class="col-md-2">
                            <label for="gender" class="form-label">
                                <i class="bi bi-gender-ambiguous"></i>
                                Gender
                            </label>
                            <select class="form-select" id="gender_text" name="gender">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="phone" class="form-label">
                                <i class="bi bi-telephone"></i>
                                Phone
                            </label>
                            <input type="tel" class="form-control" id="phone_text" name="phone">
                        </div>

                        <div class="col-md-4">
                            <label for="marital_status" class="form-label">
                                <i class="bi bi-heart"></i>
                                Marital Status
                            </label>
                            <select class="form-select" id="marital_status_text" name="marital_status">
                                <option value="">Select</option>
                                <option value="Married">Married</option>
                                <option value="Single">Single</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label for="address" class="form-label">
                                <i class="bi bi-geo-alt"></i>
                                Address
                            </label>
                            <textarea class="form-control" id="address_text" name="address" rows="3"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="admission_date" class="form-label">
                                <i class="bi bi-calendar-check"></i>
                                Admission Date
                            </label>
                            <input type="date" class="form-control" id="admission_date_text"
                                name="admission_date">
                        </div>

                        <div class="col-md-4">
                            <label for="guardian" class="form-label">
                                <i class="bi bi-person-check"></i>
                                W/O S/O D/O
                            </label>
                            <input type="text" class="form-control" id="guardian_text" name="guardian">
                        </div>

                        <div class="col-md-4">
                            <label for="relation" class="form-label">
                                <i class="bi bi-people"></i>
                                Relation
                            </label>
                            <input type="text" class="form-control" id="relation_text" name="relation">
                        </div>

                        <div class="col-md-4">
                            <label for="nationality" class="form-label">
                                <i class="bi bi-flag"></i>
                                Nationality
                            </label>
                            <input type="text" class="form-control" id="nationality_text" name="nationality">
                        </div>

                        <div class="col-md-6">
                            <label for="under_care_dr" class="form-label">
                                <i class="bi bi-person-badge"></i>
                                Under Care Dr
                            </label>
                            <input type="text" class="form-control" id="under_care_dr_text" name="under_care_dr">
                            <input type="hidden" class="form-control" id="registration_no_text"
                                name="registration_no">
                        </div>

                        <div class="col-md-6">
                            <label for="referral" class="form-label">
                                <i class="bi bi-arrow-right-circle"></i>
                                Referral
                            </label>
                            <input type="text" class="form-control" id="referral_text" name="referral">
                        </div>

                        <div class="col-md-12">
                            <label for="corporate" class="form-label">
                                <i class="bi bi-building"></i>
                                Corporate
                            </label>
                            <input type="text" class="form-control" id="corporate_text" name="corporate">
                        </div>
                    </div>

                    <!-- Medical Information Section -->

                    <h5 class="section-title mt-4">
                        <i class="bi bi-heart-pulse"></i>
                        Medical Information
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="reason_discharge" class="form-label">
                                <i class="bi bi-clipboard-check"></i>
                                Discharge Type
                            </label>
                            <select class="form-select" id="reason_discharge_text" name="reason_discharge" required>
                                <option value="">Select</option>
                                <option value="DORB">DORB</option>
                                <option value="Transfer To Higher Setup">Transfer To Higher Setup</option>
                                <option value="Discharge On Request">Discharge On Request</option>
                                <option value="Doctor Refer">Doctor Refer</option>
                                <option value="Normal Discharge">Normal Discharge</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="ot_date" class="form-label">
                                <i class="bi bi-calendar-plus"></i>
                                OT Date
                            </label>
                            <input type="date" class="form-control" id="ot_date_text" name="ot_date">
                        </div>

                        <div class="col-md-4">
                            <label for="ot_type" class="form-label">
                                <i class="bi bi-activity"></i>
                                O.T Type
                            </label>
                            <input type="text" class="form-control" id="ot_type_text" name="ot_type">
                        </div>
                        <div class="col-md-4">
                            <label for="ot_name" class="form-label">
                                <i class="bi bi-activity"></i>
                                O.T Name
                            </label>
                            <input type="text" class="form-control" id="ot_name_text" name="ot_name">
                        </div>
                        <div class="col-md-2">
                            <label for="ot_done" class="form-label">
                                <i class="bi bi-activity"></i>
                                O.T Count
                            </label>
                            <input type="number" class="form-control" id="ot_done_text" name="ot_done">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="ot_done_by">
                                <i class="bi bi-person-badge-fill"></i>
                                OT Done By
                            </label>
                            <select multiple name="ot_done_by[]" id="ot_done_by_text" class="form-select p-0">
                                <option>select</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="remarks" class="form-label">
                                <i class="bi bi-chat-left-text"></i>
                                Remarks
                            </label>
                            <textarea class="form-control" id="remarks_text" name="remarks" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Medicines Section -->
                    <h5 class="section-title mt-4">
                        <i class="bi bi-capsule"></i>
                        Discharge Medications
                    </h5>


                    {{-- modal row template --}}
                    <template id="medRowTemplate">
                        <div class="med-row">
                            <div class="medicine-row-header d-flex justify-content-between">
                                <span class="medicine-row-number"></span>
                                <button type="button" class="btn-remove-medicine btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <label class="form-label">Medicine Name</label>
                                    <select class="med-medicine" name="meds[]">
                                        <option value="">Select Medicine</option>
                                    </select>
                                </div>

                                {{-- <div class="col-md-3">
                                    <label class="form-label">Dose</label>
                                    <select class="form-select med-dose" name="med_doses[]">
                                        <option value="">Select Dose</option>
                                    </select>
                                </div> --}}

                                <div class="col-md-4">
                                    <label class="form-label">Interval</label>
                                    <select class="med-interval" name="med_interval[]"></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Duration</label>
                                    <select class="med-duration" name="med_duration[]"></select>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="medicine-container" id="medContainer">
                        <!-- Medicine rows will be added here dynamically -->
                        <div class="med-row" data-index="1">
                            <div class="medicine-row-header" class="d-flex justify-content-between">
                                <span class="medicine-row-number">
                                    <i class="bi bi-capsule-pill"></i> Medicine <span
                                        class="badge bg-primary">1</span>
                                </span>
                                <button type="button" class="btn-remove-medicine d-none">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Medicine Name</label>
                                    <select class="med-medicine" name="meds[]" id="meds">
                                        <option value="">Select Medicine</option>
                                        {{-- @foreach ($medicines as $med)
                                            <option value="{{ $med->id }}">{{ $med->name }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>

                                {{-- <div class="col-md-3">
                                    <label class="form-label">Dose</label>
                                    <select class="form-select med-dose" name="med_doses[]" id="med-doses" required>
                                        <option value="">Select Dose</option> --}}
                                {{-- @foreach ($doses as $dose)
                                            <option value="{{ $dose->id }}">{{ $dose->name }}</option>
                                        @endforeach --}}
                                {{-- </select>
                                </div> --}}

                                <div class="col-md-4">
                                    <label class="form-label">Interval</label>
                                    <select class="med-interval" name="med_interval[]">
                                        <option value="">Select</option>

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Duration</label>
                                    <select class="med-duration" name="med_duration[]">
                                        <option value="">Select Duration</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="text-end mb-3">
                        <button type="button" class="btn-add-medicine" id="addMedBtn">
                            <i class="bi bi-plus-circle"></i>
                            Add Medicine
                        </button>
                    </div>


                    <!-- Diagnosis & Complaints Section -->
                    <h5 class="section-title mt-4">
                        <i class="bi bi-file-medical"></i>
                        Diagnosis & Complaints
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="diagnosis" class="form-label">
                                <i class="bi bi-clipboard2-pulse"></i>
                                Diagnosis
                            </label>
                            <textarea class="form-control" id="diagnosis_text" name="diagnosis" rows="6"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="present_complaints" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Present Complaints (Reason for Admission)
                            </label>
                            <textarea class="form-control" id="present_complaints_text" name="present_complaints" rows="4"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="ot_note" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Treatment Done / OT Note
                            </label>
                            <textarea class="form-control" id="ot_note_text" name="ot_note" rows="4"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="course_in_hospital" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Course in hospital
                            </label>
                            <textarea class="form-control" id="course_in_hospital_text" name="course_in_hospital" rows="4"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="discharge_advice" class="form-label">
                                <i class="bi bi-clipboard-data"></i>
                                Discharge Advice
                            </label>
                            <textarea class="form-control" id="discharge_advice_text" name="discharge_advice" rows="4"></textarea>
                        </div>
                    </div>

                    <!-- Discharge Information Section -->

                    <h5 class="section-title mt-4">
                        <i class="bi bi-file-earmark-text"></i>
                        Discharge Information
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="discharged_by" class="form-label">
                                <i class="bi bi-person-check-fill"></i>
                                Discharged By
                            </label>
                            <input type="text" class="form-control" id="discharged_by_text" name="discharged_by">
                        </div>

                        <div class="col-md-6">
                            <label for="current_user" class="form-label">
                                <i class="bi bi-person-circle"></i>
                                Current User
                            </label>
                            <input type="text" class="form-control" id="current_user_text" name="current_user">
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="action-buttons mt-4">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="bi bi-x-circle"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i>
                            Submit
                        </button>
                    </div>
                </form>



            </div>
        </div>
    </div>
</div>
{{-- <script>
    new TomSelect("#ot_done_by", {
        create: true,
        persist: false,
    });
</script> --}}
<script>
    ClassicEditor
        .create(document.querySelector('#diagnosis_text'))
        .catch(error => {
            console.error(error);
        });
</script>
<script>
    ClassicEditor
        .create(document.querySelector('#discharge_advice_text'))
        .catch(error => {
            console.error(error);
        });
</script>
<script>
    ClassicEditor
        .create(document.querySelector('#ot_note_text'))
        .catch(error => {
            console.error(error);
        });
</script>
<script>
    ClassicEditor
        .create(document.querySelector('#course_in_hospital_text'))
        .catch(error => {
            console.error(error);
        });
</script>


<script>
    let doseIntervals = [];
    let doseDurations = [];
    let medMaster = [];

    function showMedLoader() {
        document.getElementById('medLoader').style.display = 'flex';
        document.getElementById('addMedBtn').disabled = true;
    }

    function hideMedLoader() {
        document.getElementById('medLoader').style.display = 'none';
        document.getElementById('addMedBtn').disabled = false;
    }

    function initTomSelects(row) {
        const medicineSelect = row.querySelector('.med-medicine');
        const intervalSelect = row.querySelector('.med-interval');
        const durationSelect = row.querySelector('.med-duration');

        if (medicineSelect && !medicineSelect.tomselect) {
            new TomSelect(medicineSelect, {
                options: medMaster.map(i => ({
                    value: i.name,
                    label: i.name
                })),
                valueField: 'value',
                labelField: 'label',
                searchField: 'label',
                placeholder: 'Select Interval'
            });
        }

        if (intervalSelect && !intervalSelect.tomselect) {
            new TomSelect(intervalSelect, {
                options: doseIntervals.map(i => ({
                    value: i.name,
                    label: i.name
                })),
                valueField: 'value',
                labelField: 'label',
                searchField: 'label',
                placeholder: 'Select Interval'
            });
        }

        if (durationSelect && !durationSelect.tomselect) {
            new TomSelect(durationSelect, {
                options: doseDurations.map(d => ({
                    value: d.name,
                    label: d.name
                })),
                valueField: 'value',
                labelField: 'label',
                searchField: 'label',
                placeholder: 'Select Duration'
            });
        }
    }
    document.addEventListener('DOMContentLoaded', function() {

        const dischargeModal = document.getElementById('patientDischargeModal');
        const container = document.getElementById('medContainer');
        const addBtn = document.getElementById('addMedBtn');
        const template = document.getElementById('medRowTemplate');


        dischargeModal.addEventListener('show.bs.modal', async function(event) {
            const button = event.relatedTarget;

            showMedLoader()
            try {
                /* ---- Fetch ONCE ---- */
                if (!doseIntervals.length) {
                    doseIntervals = await fetch("{{ route('getDoseIntervals') }}").then(r => r
                        .json());
                }

                if (!doseDurations.length) {
                    doseDurations = await fetch("{{ route('getDoseDurations') }}").then(r => r
                        .json());
                }
                if (!medMaster.length) {
                    medMaster = await fetch("{{ route('med.master') }}").then(r => r.json());
                }

                /* ---- Init FIRST static row ---- */
                const firstRow = container.querySelector('.med-row');
                if (firstRow) {
                    initTomSelects(firstRow);
                }



            } catch (error) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to load data',
                    text: 'Please try again',
                });
            } finally {
                console.log("In Hide");

                hideMedLoader();
            }

            /* ------------------------------
                    ADD MEDICINE ROW
                    --------------------------------*/
            addBtn.addEventListener('click', () => {
                showMedLoader();
                setTimeout(() => {
                    // existing add row logic
                    hideMedLoader();
                }, 100);
                const index = container.querySelectorAll('.med-row').length + 1;

                const clone = template.content.cloneNode(true);
                const row = clone.querySelector('.med-row');

                row.dataset.index = index;

                row.querySelector('.medicine-row-number').innerHTML =
                    `<i class="bi bi-capsule-pill"></i> Medicine
             <span class="badge bg-primary">${index}</span>`;

                /* Remove row */
                row.querySelector('.btn-remove-medicine').onclick = () => row.remove();

                container.appendChild(row);

                /* Init TomSelect AFTER append */
                initTomSelects(row);
            });

            // const firstRow = document.querySelector('.med-row');
            // initTomSelects(firstRow, intervals, durations);
            // 🔹 Parse data attributes
            const ipd = JSON.parse(button.getAttribute('data-ipd') || '{}');

            const doctors = JSON.parse(button.getAttribute('data-doctors') || '[]');
            const currentUser = JSON.parse(button.getAttribute('data-user') || '{}')

            window.currentUser = currentUser.role
            // 🔹 Basic identifiers
            document.getElementById('ipd-id').value = ipd.id ?? '';

            // 🔹 Basic Information
            setValue('patient_id_text', ipd.patient?.id);
            setValue('patient_name_text', ipd.patient?.patient_name);
            setValue('admission_no_text', ipd.ipd_no);
            // setValue('admission_no', ipd.admission_no);
            setValue('bed_text', `${ipd.bed_detail?.name} - ${ipd.bed_group?.name}`);

            // setValue('admission_date', ipd.admission_date);
            // setValue('admit_time', ipd.admit_time);

            // 🔹 Patient Details
            setValue('age_text',
                `${ipd.patient?.age} Years ${ipd.patient?.month} Months ${ipd.patient?.day} Days`
            );
            setSelectValue('gender_text', ipd.patient?.gender);
            setValue('phone_text', ipd.patient?.mobileno);
            setSelectValue('marital_status_text', ipd.patient?.marital_status);
            setValue('address_text', ipd.patient?.address);

            setValue('guardian_text', ipd.patient?.guardian_name);
            setValue('nationality_text', "Indian");
            setValue('admission_date_text', formatDateYYYYMMDD(ipd.date));
            setValue('admit_time_text', getTimeOnly(ipd.date));

            // 🔹 Medical
            setValue('under_care_dr_text', `${ipd.doctor?.name} ${ipd.doctor?.surname}`);
            setValue('registration_no_text', ipd.doctor?.registration_no);
            setValue('discharged_by_text', currentUser.username);
            setValue('current_user_text', currentUser.user_role.name);

            // setValue('corporate', ipd.corporate);

            // 🔹 OT Done By (MULTI SELECT)
            // const otSelect = document.getElementById('ot_done_by');
            // otSelect.innerHTML = ''; // reset

            // doctors.forEach(doc => {
            //     const option = document.createElement('option');
            //     option.value = doc.name;
            //     option.textContent = doc.name;
            //     otSelect.appendChild(option);
            // });
            new TomSelect('#ot_done_by_text', {
                options: doctors.map(doc => ({
                    value: `${doc.name} ${doc.surname}`,
                    label: `${doc.name} ${doc.surname}`
                })),
                valueField: 'value',
                labelField: 'label',
                searchField: 'label',
                create: false,
                persist: false,
                placeholder: 'Select doctors',
            });


            // 🔹 Discharge Date & Time default
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());

            document.getElementById('discharge_date_text').value =
                now.toISOString().split('T')[0];

            document.getElementById('discharge_time_text').value =
                now.toISOString().split('T')[1].slice(0, 5);

        });

        /**
         * Helper: safely set input value
         */
        function setValue(id, value) {
            const el = document.getElementById(id);
            if (el && value !== undefined && value !== null) {
                el.value = value;
            }
        }

        function setSelectValue(selectId, value) {
            const select = document.getElementById(selectId);
            if (!select || value === null || value === undefined) return;

            [...select.options].forEach(option => {
                option.selected = option.value.toLowerCase() === String(value).toLowerCase();
            });
        }

        function formatDateYYYYMMDD(dateInput) {
            const date = new Date(dateInput);

            const dd = String(date.getDate()).padStart(2, '0');
            const mm = String(date.getMonth() + 1).padStart(2, '0'); // Months start at 0
            const yyyy = date.getFullYear();

            return `${yyyy}-${mm}-${dd}`;
        }

        function getTimeOnly(dateInput) {
            const date = new Date(dateInput);

            const hh = String(date.getHours()).padStart(2, '0');
            const mm = String(date.getMinutes()).padStart(2, '0');
            const ss = String(date.getSeconds()).padStart(2, '0');

            return `${hh}:${mm}:${ss}`;
        }


    });
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
                if (result.isConfirmed && window.currentUser === 1) {
                    e.target.submit(); // ✅ submit form manually
                } else {
                    Swal.fire({
                        title: 'Contact Admin',
                        text: 'Payment is Not clear. Please Contact Admin.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(() => {
                        e.target.submit(); // ✅ submit form
                    });
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
