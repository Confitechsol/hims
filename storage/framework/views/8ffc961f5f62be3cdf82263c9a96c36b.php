<?php $__env->startSection('content'); ?>
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
    </style>

    <div class="container">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header d-flex justify-content-between align-items-center align-items-sm-center justify-content-between flex-sm-row"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Edit IPD Details</h5>
            </div>
        
            <div class="card-body">

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="editIpdForm" action="<?php echo e(route('ipd.update', [$ipd->id])); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="form-section mb-4">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="bi bi-clipboard-pulse"></i>
                                            </div>
                                            <h6 class="section-title mb-0 pb-0">Patient Details</h6>
                                        </div>
                                        <div class="section-body">
                                            <div class="row  align-items-center">
                                                <div class="field-group col-10">
                                                    <div class="info-value empty" id="marital_value"
                                                        style=" border: 1px solid; padding: 0.5rem; border-radius: 10px; ">
                                                        <?php echo e($ipd->patient->patient_name); ?></div>
                                                    <input type="hidden" name="patient_id" value="<?php echo e($ipd->patient->id ?? ''); ?>">
                                                </div>
                                                <div class="field-group col-2 text-end">
                                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#patientDetailModal">View Details</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Symptoms Information Section -->
                                    <div class="form-section mb-4">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="bi bi-clipboard-pulse"></i>
                                            </div>
                                            <h6 class="section-title mb-0 pb-0">Symptoms Information</h6>
                                        </div>
                                        <div class="section-body">
                                            <div class="form-row cols-3">
                                                <div class="field-group">
                                                    <label for="symptoms_type" class="form-label">Symptoms Type</label>
                                                    <div class="custom-multiselect-wrapper">
                                                        <!-- Hidden native select (for form submission) -->
                                                        <select name="symptoms_type[]" id="symptoms_type"
                                                            class="form-select" multiple>
                                                            <option value="">Loading...</option>
                                                        </select>

                                                        <!-- Custom Multi-Select UI -->
                                                        <div class="custom-multiselect">
                                                            <div class="multiselect-selected" tabindex="0">
                                                                <span class="multiselect-placeholder">Select
                                                                    symptoms...</span>
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
                                                    

                                                    <!-- Custom Multi-Select UI -->
                                                    <div class="custom-multiselect-wrapper">
                                                        <select name="symptoms_title[]" id="symptoms_title"
                                                            class="form-select" multiple>
                                                            <option value="">Select symptom types first...
                                                            </option>
                                                        </select>

                                                        <div class="custom-multiselect" id="symptom-title-select">
                                                            <div class="multiselect-selected disabled" tabindex="0">
                                                                <span class="multiselect-placeholder">Select symptom
                                                                    types first...</span>
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

                                                <div class="field-group">
                                                    <label for="symptoms_description" class="form-label">Symptoms
                                                        Description</label>
                                                    <input type="text" name="symptoms_description"
                                                        id="symptoms_description" class="form-control"
                                                        value="<?php echo e(old('symptoms_description', $ipd->symptoms_description)); ?>"
                                                        placeholder="Enter description">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Admission Information Section -->
                                    <div class="form-section mb-4">
                                        <div class="section-header">
                                            <div class="section-icon">
                                                <i class="bi bi-calendar-check"></i>
                                            </div>
                                            <h6 class="section-title mb-0 pb-0">Admission Information</h6>
                                        </div>
                                        <div class="section-body">
                                            <div class="form-row cols-2">
                                                <div class="field-group">
                                                    <label for="admission_date" class="form-label">
                                                        Admission Date <span class="required">*</span>
                                                    </label>
                                                    <input type="datetime-local" name="admission_date" id="admission_date"
                                                        class="form-control"
                                                        value="<?php echo e(old('admission_date', $ipd->date)); ?>" required >
                                                </div>

                                                <div class="field-group">
                                                    <label for="old_patient" class="form-label">Patient Type</label>
                                                    <select name="old_patient" id="old_patient" class="form-select">
                                                        <option value="">Select</option>
                                                        <option value="Old Patient"
                                                            <?php echo e(old('old_patient', $ipd->patient_old ?? '') == 'Old Patient' ? 'selected' : ''); ?>>
                                                            Old Patient</option>
                                                        <option value="New Patient"
                                                            <?php echo e(old('old_patient', $ipd->patient_old ?? '') == 'New Patient' ? 'selected' : ''); ?>>
                                                            New Patient</option>
                                                    </select>
                                                </div>

                                                <div class="field-group">
                                                    <label for="casualty" class="form-label">Emergency</label>
                                                    <select name="casualty" id="casualty" class="form-select">
                                                        <option value="">Select</option>
                                                        <option value="No"
                                                            <?php echo e(old('casualty', $ipd->casualty ?? '') == 'No' ? 'selected' : ''); ?>>
                                                            No</option>
                                                        <option value="Yes"
                                                            <?php echo e(old('casualty', $ipd->casualty ?? '') == 'Yes' ? 'selected' : ''); ?>>
                                                            Yes</option>
                                                    </select>
                                                </div>

                                                <div class="field-group">
                                                    <label for="reference" class="form-label">Reference</label>
                                                    <input type="text" name="reference" id="reference"
                                                        class="form-control"
                                                        value="<?php echo e(old('reference', $ipd->refference)); ?>"
                                                        placeholder="Enter reference">
                                                </div>

                                                <div class="field-group">
                                                    <label for="consultant_doctor" class="form-label">
                                                        Consultant Doctor <span class="required">*</span>
                                                    </label>
                                                    <select name="consultant_doctor" id="consultant_doctor"
                                                        class="form-select" required>
                                                        <option value="">Select Doctor</option>
                                                        <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($doctor->id); ?>"
                                                                <?php echo e(old('consultant_doctor', $ipd->cons_doctor ?? '') == $doctor->id ? 'selected' : ''); ?>>
                                                                <?php echo e($doctor->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                                            <h6 class="section-title mb-0 pb-0">Payment Information & Notes</h6>
                                        </div>
                                        <div class="section-body">
                                            <div class="form-row cols-2">
                                                <div class="field-group">
                                                    <label for="credit_limit" class="form-label">
                                                        Credit Limit (INR) <span class="required">*</span>
                                                    </label>
                                                    <div class="amount-input-group">
                                                        <span class="currency-symbol">₹</span>
                                                        <input type="number" name="credit_limit" id="credit_limit"
                                                            class="form-control"
                                                            value="<?php echo e(old('credit_limit', $ipd->credit_limit)); ?>"
                                                            placeholder="0.00" step="0.01" min="0" required>
                                                    </div>
                                                </div>

                                                <div class="field-group">
                                                    <label class="form-label">Bed Group <span
                                                            class="required">*</span></label>
                                                    <select class="form-select" name="bed_group" id="bed_group_select">
                                                        <option value="">Select</option>
                                                        <?php $__currentLoopData = $bedGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bedGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($bedGroup->id); ?>"
                                                                <?php echo e(old('bed_group', $ipd->bed_group_id ?? '') == $bedGroup->id ? 'selected' : ''); ?>>
                                                                <?php echo e($bedGroup->name); ?>-<?php echo e($bedGroup->floorDetail->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>

                                                <div class="field-group">
                                                    <label class="form-label">Bed Number <span
                                                            class="required">*</span></label>
                                                    <select class="form-select" name="bed_number" id="bed_number_select">
                                                        <option value="">Select</option>
                                                        <option value="<?php echo e($ipd->bed); ?>" selected>
                                                            <?php echo e($ipd->bedDetail->name); ?>

                                                        </option>
                                                        <?php $__currentLoopData = $bedNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bedNumber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($bedNumber->id); ?>"
                                                                <?php echo e(old('bed_number', $ipd->bed ?? '') == $bedNumber->id ? 'selected' : ''); ?>>
                                                                <?php echo e($bedNumber->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="field-group">
                                                    <label for="note" class="form-label">Note</label>
                                                    <textarea name="note" id="note" class="form-control" placeholder="Enter notes" rows="1"><?php echo e(old('note', $ipd->note)); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer text-end">
                                        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle"></i>
                                            Cancel
                                        </button>
                                        <button type="submit" form="editIpdForm" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i>
                                            Save
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('components.modals.patient-details-modal', ['patient' => $ipd->patient], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bedGroupSelect = document.getElementById('bed_group_select');
            const bedNumberSelect = document.getElementById('bed_number_select');
            const patientSelect = document.getElementById('patient_select');
            bedGroupSelect.addEventListener('change', function() {
                const selectedId = this.value;
                const baseUrl = "<?php echo e(route('getBedNumbers', ['id' => 'ID'])); ?>";
                const finalUrl = baseUrl.replace('ID', selectedId);
                const preSelectedBedGroup = <?php echo json_encode($ipd->bed_group_id, 15, 512) ?>;
                const preSelectedBedNumber = <?php echo json_encode($ipd->bedDetail, 15, 512) ?>;

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
                                // console.log(selectedId, preSelectedBedGroup, typeof selectedId,
                                //     typeof preSelectedBedGroup, selectedId ===
                                //     preSelectedBedGroup);

                                if (selectedId === preSelectedBedGroup.toString()) {
                                    const selectedOption = document.createElement('option');
                                    selectedOption.value = preSelectedBedNumber.id;
                                    selectedOption.textContent = preSelectedBedNumber.name;
                                    selectedOption.selected = true;
                                    bedNumberSelect.appendChild(selectedOption);
                                }
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
            });

            patientSelect.addEventListener('change', function() {
                const selectedOption = patientSelect.options[patientSelect.selectedIndex];
                const patientData = JSON.parse(selectedOption.getAttribute("data-patient"));
                // console.log(patientData, document.getElementById('tpa_value'));

                if (patientData) {
                    document.getElementById('patient_name_value').textContent = patientData.patient_name ??
                        '-';
                    document.getElementById('gender_value').textContent = patientData.gender ??
                        '-';
                    document.getElementById('age_value').textContent = patientData.age + " Year " +
                        patientData.month + " Month " + patientData.day + " Day" ??
                        '-';
                    document.getElementById('marital_value').textContent = patientData.marital_status ??
                        '-';
                    document.getElementById('blood_grp_value').textContent = patientData.blood_group.name ??
                        '-';
                    document.getElementById('phone_value').textContent = patientData.mobileno ??
                        '-';
                    document.getElementById('address_value').textContent = patientData.address ??
                        '-';
                    document.getElementById('tpa_value').textContent = patientData.organisation
                        .organisation_name ??
                        '-';
                    document.getElementById('tpa_code_value').textContent = patientData.organisation.code ??
                        '-';
                    document.getElementById('tpa_validity_value').textContent = patientData.tpa_validity ??
                        '-';
                    document.getElementById('id_number_value').textContent = patientData
                        .identification_number ??
                        '-';
                }

            })
        })
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
                this.preselectedValues = [];
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

                    if (this.preselectedValues?.some(pre => {
                            // Case 1: preselectedValues contains IDs
                            if (typeof pre === 'number' || typeof pre === 'string') {
                                return pre.toString() === option.id.toString();
                            }
                            // Case 2: preselectedValues contains symptom objects
                            if (pre && pre.id) {
                                return pre.id.toString() === option.id.toString();
                            }
                            return false;
                        })) {
                        this.selectedValues.push(option.id)
                        optionElement.classList.add('selected');
                    }

                    optionElement.addEventListener('click', () => this.toggleOption(option.id));
                    this
                        .optionsContainer.appendChild(optionElement);
                });
            }

            toggleOption(value) {

                const index = this.selectedValues.indexOf(value);
                console.log(this.selectedValues, value, index);

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
                        const option = this.allOptions.find(opt => opt.id.toString() ===
                            value
                            .toString());
                        if (option) {
                            const chip = document.createElement('div');
                            chip.className = 'multiselect-chip';
                            chip.innerHTML = `
                            <span>${option.symptoms_type??option.symptoms_title}</span>
                            <div class="multiselect-chip-remove" data-value="${value}">
                                <i class="bi bi-x"></i>
                            </div>
                        `;

                            chip.querySelector('.multiselect-chip-remove').addEventListener(
                                'click',
                                (e) => {
                                    e.stopPropagation();
                                    this.toggleOption(value);
                                });

                            this.selectedBox.insertBefore(chip, this.selectedBox
                                .querySelector(
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
                // this.selectedValues = selectedIds;
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
                this.selectElement.innerHTML = '';
                this.allOptions = [];
                this.selectedValues = []; // 🔥 Clear old selections
                this.optionsContainer.innerHTML = ''; // 🔥 Clear old UI
                if (!Array.isArray(options)) return;
                this.allOptions = options;

                options.forEach(opt => {
                    // console.log(opt, opt.id, opt.symptoms_type ?? opt.symptoms_title);

                    const optionEl = document.createElement('option');

                    optionEl.value = opt.id;
                    optionEl.innerHTML = opt.symptoms_type ?? opt.symptoms_title;

                    if (
                        this.preselectedValues?.some(pre => {
                            // Case 1: preselectedValues contains IDs
                            if (typeof pre === 'number' || typeof pre === 'string') {
                                return pre.toString() === opt.id.toString();
                            }
                            // Case 2: preselectedValues contains symptom objects
                            if (pre && pre.id) {
                                return pre.id.toString() === opt.id.toString();
                            }
                            return false;
                        })
                    ) {
                        optionEl.selected = true;
                    }
                    // if (this.preselectedValues?.includes(opt.id)) {
                    //     optionEl.selected = true;
                    //     if (!this.selectedValues.includes(opt.id)) {
                    //         this.selectedValues.push(opt.id);
                    //     }
                    // }
                    this.selectElement.appendChild(optionEl);
                });


                this.renderOptions();
                this.updateUI()
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
            // Mock data for symptom types
            const symptomTypes = <?php echo json_encode($allSymptomTypes, 15, 512) ?>;
            const selectedSymptomTitles = <?php echo json_encode($symptoms, 15, 512) ?>;


            // Initialize Symptom Type Select
            const symptomTypeSelect = new CustomMultiSelect('symptoms_type', {

                onChange: function(selectedValues) {
                    loadSymptomTitles(selectedValues);
                }
            });
            const preSelectedVals = <?php echo json_encode($symptomTypeIds, 15, 512) ?>;
            symptomTypeSelect.preselectedValues = preSelectedVals.map(id => Number(id));

            // Load initial symptom types
            symptomTypeSelect.setOptions(symptomTypes);

            // Initialize Symptom Title Select (disabled initially)
            const symptomTitleSelect = new CustomMultiSelect('symptoms_title', {
                disabled: true,
                onChange: function(selectedValues) {}
            });


            symptomTitleSelect.preselectedValues = selectedSymptomTitles;
            symptomTitleSelect.setOptions(selectedSymptomTitles);


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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                        symptomTitleSelect.preselectedValues = [];
                        symptomTitleSelect.setOptions(data);
                    })
                    .catch(error => {
                        console.error('Error loading symptom titles:', error);
                        symptomTitleSelect.optionsContainer.innerHTML =
                            '<div class="multiselect-no-results">Error loading titles. Please try again.</div>';
                    });
            }

            // const form = document.querySelector('#editOpdForm'); // Replace with your actual form ID
            // form.addEventListener('submit', function(e) {
            //     e.preventDefault()
            //     // Before submitting, inject hidden inputs for both multi-select fields
            //     const existingHiddenInputs = form.querySelectorAll(
            //         'input[name="symptoms_type[]"], input[name="symptoms_title[]"]');
            //     existingHiddenInputs.forEach(input => input.remove()); // Clear old ones

            //     const selectedTypes = symptomTypeSelect.getSelectedValues();
            //     const selectedTitles = symptomTitleSelect.getSelectedValues();
            //     console.log(selectedTypes);
            //     console.log(selectedTitles);

            //     // Add symptom types
            //     selectedTypes.forEach(value => {
            //         const hiddenInput = document.createElement('input');
            //         hiddenInput.type = 'hidden';
            //         hiddenInput.name = 'symptoms_type[]';
            //         hiddenInput.value = value;
            //         form.appendChild(hiddenInput);
            //     });

            //     // Add symptom titles
            //     selectedTitles.forEach(value => {
            //         const hiddenInput = document.createElement('input');
            //         hiddenInput.type = 'hidden';
            //         hiddenInput.name = 'symptoms_title[]';
            //         hiddenInput.value = value;
            //         form.appendChild(hiddenInput);
            //     });

            //     // Form continues submitting normally
            // });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/ipd/edit-ipd.blade.php ENDPATH**/ ?>