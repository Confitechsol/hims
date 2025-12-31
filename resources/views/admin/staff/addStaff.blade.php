@extends('layouts.adminLayout')

@section('content')
@php
    // Detect Add or Edit mode
    $isEdit = isset($staff);
@endphp


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


<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            
            <!-- Header -->
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-cogs me-2"></i>
                    {{ $isEdit ? 'Edit Staff' : 'Add New Staff' }}
                </h5>
            </div>

            <div class="card-body">

                <!-- FORM -->
                <form id="form1"
                      action="{{ $isEdit ? route('staff.update', $staff->id) : route('staff.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div class="row">

                        <div class="around10">
                            <!-- Basic Information -->

                            <div class="section-card">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <h6 class="section-title">Basic Information</h6>
                                </div>

                                <div class="row g-3">
                                    <!-- STAFF ID -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Staff ID</label><small class="req"> *</small>
                                            <input id="employee_id" name="employee_id" type="text"
                                                class="form-control"
                                                value="{{ old('employee_id', $staff->employee_id ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- ROLE -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Role</label><small class="req"> *</small>
                                            <select id="role" name="role" class="form-select">
                                                <option value="">Select</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role', $staff->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                     <!-- FIRST NAME -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">First Name</label><small class="req"> *</small>
                                            <input id="name" name="name" type="text" class="form-control"
                                                value="{{ old('name', $staff->name ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- LAST NAME -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Last Name</label>
                                            <input id="surname" name="surname" type="text" class="form-control"
                                                value="{{ old('surname', $staff->surname ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- GENDER -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Gender</label><small class="req"> *</small>
                                            <select class="form-select" name="gender">
                                                <option value="">Select</option>
                                                @foreach (['Male','Female','others'] as $g)
                                                    <option value="{{ $g }}"
                                                        {{ old('gender', $staff->gender ?? '') == $g ? 'selected' : '' }}>
                                                        {{ $g }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                     <!-- DEPARTMENT -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Department</label>
                                            <select id="department" name="department" class="form-select"
                                                    onchange="loadSpecialists(this.value)">
                                                <option value="">Select</option>
                                                @foreach ($departments as $dept)
                                                    <option value="{{ $dept->id }}"
                                                        {{ old('department', $staff->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                                        {{ $dept->department_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- DESIGNATION -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Designation</label>
                                            <select id="designation" name="designation" class="form-select">
                                                <option value="">Select</option>
                                                @foreach ($designations as $des)
                                                    <option value="{{ $des->id }}"
                                                        {{ old('designation', $staff->staff_designation_id  ?? '') == $des->id ? 'selected' : '' }}>
                                                        {{ $des->designation }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- SPECIALIST -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Specialist</label>
                                            <select name="specialist" class="form-select" id="specialist">
                                                <option value="">Select</option>

                                                @foreach ($specialists as $specialist)
                                                    <option value="{{ $specialist->id }}"
                                                        {{ old('specialist', $staff->specialist ?? '') == $specialist->id ? 'selected' : '' }}>
                                                        {{ $specialist->specialist_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- MARITAL STATUS -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Marital Status</label>
                                            <select class="form-select" name="marital_status">
                                                <option value="">Select</option>
                                                @foreach (['Single','Married','Widowed','Separated','Not Specified'] as $ms)
                                                    <option value="{{ $ms }}"
                                                        {{ old('marital_status', $staff->marital_status ?? '') == $ms ? 'selected' : '' }}>
                                                        {{ $ms }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- BLOOD GROUP -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Blood Group</label>
                                            <select class="form-select" name="blood_group">
                                                <option value="">Select</option>
                                                @foreach ($bloodgroups as $bg)
                                                    <option value="{{ $bg }}"
                                                        {{ old('blood_group', $staff->blood_group ?? '') == $bg->id ? 'selected' : '' }}>
                                                        {{ $bg->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- DOB -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Date Of Birth</label><small class="req"> *</small>
                                            <input id="dob" name="dob" type="text" class="form-control date"
                                                value="{{ old('dob', $staff->dob ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- DO JOINING -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Date Of Joining</label>
                                            <input id="date_of_joining" name="date_of_joining" type="text" class="form-control date"
                                                value="{{ old('date_of_joining', $staff->date_of_joining ?? '') }}">
                                        </div>
                                    </div>
                                    <!-- FATHER NAME -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Father Name</label>
                                            <input id="father_name" name="father_name" type="text" class="form-control"
                                                value="{{ old('father_name', $staff->father_name ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- MOTHER NAME -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Mother Name</label>
                                            <input id="mother_name" name="mother_name" type="text" class="form-control"
                                                value="{{ old('mother_name', $staff->mother_name ?? '') }}">
                                        </div>
                                    </div>
                                    
                                    <!-- PAN -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">PAN Number</label>
                                            <input name="pan_number" type="text" class="form-control"
                                                value="{{ old('pan_number', $staff->pan_number ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- NATIONAL ID -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">National Identification Number</label>
                                            <input name="identification_number" type="text" class="form-control"
                                                value="{{ old('identification_number', $staff->identification_number ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- LOCAL ID -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Local Identification Number</label>
                                            <input name="local_identification_number" type="text" class="form-control"
                                                value="{{ old('local_identification_number', $staff->local_identification_number ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- PHOTO -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Photo</label>
                                            <input type="file" class="form-control" name="file">
                                            @if($isEdit && $staff->photo)
                                                <small>Current: <img src="{{ asset('uploads/staff/'.$staff->photo) }}"
                                                                    width="40"></small>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- NOTE -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Note</label>
                                            <textarea name="note" class="form-control">{{ old('note', $staff->note ?? '') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="section-card">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <h6 class="section-title">Contact Information</h6>
                                </div>

                                <div class="row g-3">
                                    <!-- CONTACT -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Phone</label>
                                            <input id="mobileno" name="contactno" type="text" class="form-control"
                                                value="{{ old('contactno', $staff->contact_no ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- EMERGENCY CONTACT -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Emergency Contact</label>
                                            <input id="emgmobileno" name="emgcontactno" type="text" class="form-control"
                                                value="{{ old('emgcontactno', $staff->emgcontactno ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- EMAIL -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Email</label><small class="req"> *</small>
                                            <input id="email" name="email" type="email" class="form-control"
                                                value="{{ old('email', $staff->email ?? '') }}">
                                        </div>
                                    </div>
                                    
                                    <!-- CURRENT ADDRESS -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Current Address</label>
                                            <textarea name="address" class="form-control">{{ old('address', $staff->local_address ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- PERMANENT ADDRESS -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Permanent Address</label>
                                            <textarea name="permanent_address" class="form-control">{{ old('permanent_address', $staff->permanent_address ?? '') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="section-card">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <h6 class="section-title">Qualification</h6>
                                </div>

                                <div class="row g-3">
                                    <!-- QUALIFICATION -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Qualification</label>
                                            <textarea name="qualification" class="form-control">{{ old('qualification', $staff->qualification ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- WORK EXP -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Work Experience</label>
                                            <textarea name="work_exp" class="form-control">{{ old('work_exp', $staff->work_exp ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- SPECIALIZATION -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Specialization</label>
                                            <textarea name="specialization" class="form-control">{{ old('specialization', $staff->specialization ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary float-end">
                        <i class="fa fa-check-circle pe-1"></i> 
                        {{ $isEdit ? 'Update' : 'Save' }}
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
