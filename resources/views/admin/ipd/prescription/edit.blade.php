@extends('layouts.adminLayout')
@section('content')
<style>
    .pathology-selected-list .selected-test-row,
    .radiology-selected-list .selected-test-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 8px;
        margin-bottom: 6px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        flex-wrap: wrap;
    }
    .pathology-selected-list .selected-test-row .test-name-badge,
    .radiology-selected-list .selected-test-row .test-name-badge { font-weight: 600; color: #333; }
    .pathology-selected-list .selected-test-row .instance-badge,
    .radiology-selected-list .selected-test-row .instance-badge {
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 4px;
        background: #75009633;
        color: #750096;
    }
    .pathology-selected-list .selected-test-row .instance-badge.instance-2,
    .radiology-selected-list .selected-test-row .instance-badge.instance-2 { background: #ffc10733; color: #856404; }
    .pathology-selected-list .selected-test-row .instance-badge.instance-3plus,
    .radiology-selected-list .selected-test-row .instance-badge.instance-3plus { background: #fd7e1433; color: #a13b00; }
    .pathology-selected-list .selected-test-row .notes-input,
    .radiology-selected-list .selected-test-row .notes-input {
        flex: 1;
        min-width: 100px;
        padding: 4px 8px;
        font-size: 0.875rem;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    .pathology-selected-list .selected-test-row .btn-add-again,
    .radiology-selected-list .selected-test-row .btn-add-again {
        padding: 2px 8px;
        font-size: 0.75rem;
        background: #750096;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .pathology-selected-list .selected-test-row .btn-remove-test,
    .radiology-selected-list .selected-test-row .btn-remove-test {
        padding: 2px 8px;
        font-size: 0.75rem;
        background: #dc3545;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Prescription</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('ipd.prescription.update', $prescription->id) }}" method="POST" enctype="multipart/form-data" id="editPrescriptionForm">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="ipd_id" value="{{ $prescription->ipd_id }}">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Prescribe By <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="prescribe_by" id="prescribe_by" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ $prescription->prescribed_by && (int)$prescription->prescribed_by === (int)$doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }} ({{ $doctor->doctor_id ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" id="prescription_date" class="form-control" value="{{ $prescription->date->format('Y-m-d') }}"
                                    data-original-date="{{ $prescription->date->format('Y-m-d') }}"
                                    @if(isset($admissionDate) && $admissionDate) min="{{ $admissionDate }}" @endif
                                    max="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Header Note</label>
                            <textarea name="header_note" class="form-control" rows="4">{{ $prescription->header_note }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Finding Category</label>
                                <select class="form-control multiselect2" name="finding_type[]" multiple>
                                    @php
                                        $selectedCategories = explode(', ', $prescription->finding_categories ?? '');
                                    @endphp
                                    <option value="1" {{ in_array('1', $selectedCategories) ? 'selected' : '' }}>General Examination</option>
                                    <option value="2" {{ in_array('2', $selectedCategories) ? 'selected' : '' }}>Vitals</option>
                                    <option value="3" {{ in_array('3', $selectedCategories) ? 'selected' : '' }}>Cardiovascular System</option>
                                    <option value="4" {{ in_array('4', $selectedCategories) ? 'selected' : '' }}>Gynecological</option>
                                    <option value="5" {{ in_array('5', $selectedCategories) ? 'selected' : '' }}>ENT / Oral Cavity</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Finding List</label>
                                <select class="form-control multiselect2" name="findings[]" multiple>
                                    @php
                                        $selectedFindings = explode(', ', $prescription->findings ?? '');
                                    @endphp
                                    <option value="1" {{ in_array('1', $selectedFindings) ? 'selected' : '' }}>General Examination</option>
                                    <option value="2" {{ in_array('2', $selectedFindings) ? 'selected' : '' }}>Vitals</option>
                                    <option value="3" {{ in_array('3', $selectedFindings) ? 'selected' : '' }}>Cardiovascular System</option>
                                    <option value="4" {{ in_array('4', $selectedFindings) ? 'selected' : '' }}>Gynecological</option>
                                    <option value="5" {{ in_array('5', $selectedFindings) ? 'selected' : '' }}>ENT / Oral Cavity</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Finding Description</label>
                                <textarea name="finding_description" class="form-control" rows="3">{{ $prescription->finding_description }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Finding Print</label><br>
                                <input type="checkbox" name="finding_print" value="yes" {{ $prescription->is_finding_print == 'yes' ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Pathology Tests</label>
                                <select class="form-control pathology-test-select" id="pathologyOpt" multiple style="width: 100%;" data-name="pathology_select">
                                    @foreach($pathologies as $pathology)
                                        <option value="{{ $pathology->id }}">{{ $pathology->test_name ?? $pathology->name }}{{ isset($pathology->short_name) && $pathology->short_name ? ' (' . $pathology->short_name . ')' : '' }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">Select tests; use &quot;Add Again&quot; for same test multiple times today.</small>
                                <div id="pathologySelectedList" class="mt-2 pathology-selected-list" style="min-height: 24px;"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Radiology Tests</label>
                                <select class="form-control radiology-test-select" id="radiologyOpt" multiple style="width: 100%;" data-name="radiology_select">
                                    @foreach($radiologies as $radiology)
                                        <option value="{{ $radiology->id }}">{{ $radiology->test_name ?? $radiology->name }}{{ isset($radiology->short_name) && $radiology->short_name ? ' (' . $radiology->short_name . ')' : '' }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">Select tests; use &quot;Add Again&quot; for same test multiple times today.</small>
                                <div id="radiologySelectedList" class="mt-2 radiology-selected-list" style="min-height: 24px;"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Medicines</label>
                            <div id="medicineContainer">
                                @if($prescription->medicines && $prescription->medicines->count() > 0)
                                    @foreach($prescription->medicines as $index => $medicine)
                                        <div class="medicine-row row mt-3" data-row="{{ $index + 1 }}" id="row{{ $index + 1 }}" 
                                             data-category-id="{{ $medicine->pharmacy->medicine_category_id ?? ($medicine->pharmacy->medicineCategory->id ?? '') }}"
                                             data-medicine-id="{{ $medicine->pharmacy_id }}"
                                             data-dosage-id="{{ $medicine->medicine_dosage_id }}"
                                             data-interval-id="{{ $medicine->dose_interval_id }}"
                                             data-duration-id="{{ $medicine->dose_duration_id }}">
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Medicine Category</label>
                                                    <select class="form-control select2 medicine_category" style="width:100%" name="medicine_categories[]">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Medicine</label>
                                                    <select class="form-control select2 medicine_name" data-rowid="{{ $index + 1 }}" style="width:100%" name="medicines[]" data-medicine-id="{{ $medicine->pharmacy_id }}">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Dose</label>
                                                    <select class="form-control select2 medicine_dosage" style="width:100%" name="dosages[]" data-dosage-id="{{ $medicine->medicine_dosage_id }}">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Dose Interval</label>
                                                    <select class="form-control select2 interval_dosage" id="interval_dosage_{{ $index + 1 }}" name="interval_dosages[]" style="width:100%" data-interval-id="{{ $medicine->dose_interval_id }}">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Dose Duration</label>
                                                    <select class="form-control select2 duration_dosage" id="duration_dosage_{{ $index + 1 }}" name="duration_dosages[]" style="width:100%" data-duration-id="{{ $medicine->dose_duration_id }}">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                <div>
                                                    <label class="form-label">Instruction</label>
                                                    <textarea name="instructions[]" style="height:28px;" class="form-control">{{ $medicine->instruction }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 d-flex align-items-center">
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-danger delete_row" data-row-id="{{ $index + 1 }}" autocomplete="off"><i class="fa fa-remove"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="medicine-row row mt-3" data-row="1" id="row1">
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                            <div>
                                                <label class="form-label">Medicine Category</label>
                                                <select class="form-control select2 medicine_category" style="width:100%" name="medicine_categories[]">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                            <div>
                                                <label class="form-label">Medicine</label>
                                                <select class="form-control select2 medicine_name" data-rowid="1" style="width:100%" name="medicines[]">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                            <div>
                                                <label class="form-label">Dose</label>
                                                <select class="form-control select2 medicine_dosage" style="width:100%" name="dosages[]">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                            <div>
                                                <label class="form-label">Dose Interval</label>
                                                <select class="form-control select2 interval_dosage" id="interval_dosage_1" name="interval_dosages[]" style="width:100%">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                            <div>
                                                <label class="form-label">Dose Duration</label>
                                                <select class="form-control select2 duration_dosage" id="duration_dosage_1" name="duration_dosages[]" style="width:100%">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                            <div>
                                                <label class="form-label">Instruction</label>
                                                <textarea name="instructions[]" style="height:28px;" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 d-flex align-items-center">
                                            <div>
                                                <button type="button" class="btn btn-sm btn-danger delete_row" data-row-id="1" autocomplete="off"><i class="fa fa-remove"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6 mt-2" id="addMedicineContainer">
                                    <a class="btn btn-primary btn-sm add-record" data-added="0" id="addMedicineBtn"><i class="fa fa-plus"></i> Add Medicine</a>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Footer Note</label>
                            <textarea name="footer_note" class="form-control" rows="4">{{ $prescription->footer_note }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            @if($prescription->attachment)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $prescription->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-download"></i> Current: {{ $prescription->attachment_name }}
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" name="document">
                            <small class="text-muted">Leave empty to keep current file</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notification To</label>
                            @php
                                $selectedNotifications = explode(', ', $prescription->notification_to ?? '');
                            @endphp
                            <div class="row">
                                <div class="col-md-3">
                                    <label><input type="checkbox" name="visible[]" value="1" {{ in_array('1', $selectedNotifications) ? 'checked' : '' }}> Admin</label>
                                </div>
                                <div class="col-md-3">
                                    <label><input type="checkbox" name="visible[]" value="2" {{ in_array('2', $selectedNotifications) ? 'checked' : '' }}> Accountant</label>
                                </div>
                                <div class="col-md-3">
                                    <label><input type="checkbox" name="visible[]" value="3" {{ in_array('3', $selectedNotifications) ? 'checked' : '' }}> Doctor</label>
                                </div>
                                <div class="col-md-3">
                                    <label><input type="checkbox" name="visible[]" value="4" {{ in_array('4', $selectedNotifications) ? 'checked' : '' }}> Pharmacist</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ipd.show', $prescription->ipd_id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Prescription</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 on doctor dropdown
    if (window.jQuery && $.fn.select2) {
        // Get the selected value before initializing Select2
        const selectedDoctorId = {{ $prescription->prescribed_by ?? 'null' }};
        
        // Initialize Select2
        $('#prescribe_by').select2({
            placeholder: 'Select Doctor',
            allowClear: false,
            width: '100%',
            minimumResultsForSearch: 0
        });
        
        // Set the selected value after Select2 is initialized
        if (selectedDoctorId) {
            setTimeout(function() {
                $('#prescribe_by').val(selectedDoctorId).trigger('change.select2');
            }, 100);
        }
    }
    
    // Selected pathology/radiology lists (Add Again, instance badge, notes per instance)
    window.selectedPathologyList = @json($pathologyTestsForList ?? []);
    window.selectedRadiologyList = @json($radiologyTestsForList ?? []);
    function instanceLabelEdit(i) {
        if (i === 1) return '1st time today';
        if (i === 2) return '2nd time today';
        if (i === 3) return '3rd time today';
        return i + 'th time today';
    }
    function instanceBadgeClassEdit(i) {
        if (i === 1) return 'instance-badge';
        if (i === 2) return 'instance-badge instance-2';
        return 'instance-badge instance-3plus';
    }
    function renderPathologyListEdit() {
        var container = document.getElementById('pathologySelectedList');
        if (!container) return;
        container.innerHTML = '';
        var currentCount = {};
        window.selectedPathologyList.forEach(function(item, idx) {
            currentCount[item.id] = (currentCount[item.id] || 0) + 1;
            var row = document.createElement('div');
            row.className = 'selected-test-row';
            row.setAttribute('data-idx', idx);
            row.innerHTML = '<span class="test-name-badge">' + (item.name || '') + '</span>' +
                '<span class="' + instanceBadgeClassEdit(currentCount[item.id]) + '">' + instanceLabelEdit(currentCount[item.id]) + '</span>' +
                '<input type="text" class="notes-input" placeholder="Notes (optional)" data-idx="' + idx + '" value="' + (item.notes || '').replace(/"/g, '&quot;') + '">' +
                '<button type="button" class="btn-add-again" data-idx="' + idx + '">Add Again</button>' +
                '<button type="button" class="btn-remove-test" data-idx="' + idx + '">Remove</button>';
            container.appendChild(row);
        });
        container.querySelectorAll('input.notes-input').forEach(function(inp) {
            var idx = parseInt(inp.getAttribute('data-idx'), 10);
            inp.addEventListener('input', function() { window.selectedPathologyList[idx].notes = this.value; });
        });
        container.querySelectorAll('.btn-add-again').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var idx = parseInt(this.getAttribute('data-idx'), 10);
                var item = window.selectedPathologyList[idx];
                if (item) {
                    var count = window.selectedPathologyList.filter(function(x) { return x.id == item.id; }).length;
                    if (count >= 3 && !confirm('This test is already added 3 times today. Add again?')) return;
                    window.selectedPathologyList.push({ id: item.id, name: item.name, notes: '' });
                    renderPathologyListEdit();
                }
            });
        });
        container.querySelectorAll('.btn-remove-test').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var idx = parseInt(this.getAttribute('data-idx'), 10);
                window.selectedPathologyList.splice(idx, 1);
                renderPathologyListEdit();
            });
        });
    }
    function renderRadiologyListEdit() {
        var container = document.getElementById('radiologySelectedList');
        if (!container) return;
        container.innerHTML = '';
        var currentCount = {};
        window.selectedRadiologyList.forEach(function(item, idx) {
            currentCount[item.id] = (currentCount[item.id] || 0) + 1;
            var row = document.createElement('div');
            row.className = 'selected-test-row';
            row.setAttribute('data-idx', idx);
            row.innerHTML = '<span class="test-name-badge">' + (item.name || '') + '</span>' +
                '<span class="' + instanceBadgeClassEdit(currentCount[item.id]) + '">' + instanceLabelEdit(currentCount[item.id]) + '</span>' +
                '<input type="text" class="notes-input" placeholder="Notes (optional)" data-idx="' + idx + '" value="' + (item.notes || '').replace(/"/g, '&quot;') + '">' +
                '<button type="button" class="btn-add-again" data-idx="' + idx + '">Add Again</button>' +
                '<button type="button" class="btn-remove-test" data-idx="' + idx + '">Remove</button>';
            container.appendChild(row);
        });
        container.querySelectorAll('input.notes-input').forEach(function(inp) {
            var idx = parseInt(inp.getAttribute('data-idx'), 10);
            inp.addEventListener('input', function() { window.selectedRadiologyList[idx].notes = this.value; });
        });
        container.querySelectorAll('.btn-add-again').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var idx = parseInt(this.getAttribute('data-idx'), 10);
                var item = window.selectedRadiologyList[idx];
                if (item) {
                    var count = window.selectedRadiologyList.filter(function(x) { return x.id == item.id; }).length;
                    if (count >= 3 && !confirm('This test is already added 3 times today. Add again?')) return;
                    window.selectedRadiologyList.push({ id: item.id, name: item.name, notes: '' });
                    renderRadiologyListEdit();
                }
            });
        });
        container.querySelectorAll('.btn-remove-test').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var idx = parseInt(this.getAttribute('data-idx'), 10);
                window.selectedRadiologyList.splice(idx, 1);
                renderRadiologyListEdit();
            });
        });
    }
    window.syncPrescriptionTestListsToForm = function(f) {
        var form = f || document.getElementById('editPrescriptionForm');
        if (!form) return;
        form.querySelectorAll('input[name="pathology[]"], input[name="pathology_notes[]"], input[name="radiology[]"], input[name="radiology_notes[]"]').forEach(function(el) { el.remove(); });
        (window.selectedPathologyList || []).forEach(function(item) {
            var i = document.createElement('input'); i.type = 'hidden'; i.name = 'pathology[]'; i.value = item.id; form.appendChild(i);
            var n = document.createElement('input'); n.type = 'hidden'; n.name = 'pathology_notes[]'; n.value = item.notes || ''; form.appendChild(n);
        });
        (window.selectedRadiologyList || []).forEach(function(item) {
            var i = document.createElement('input'); i.type = 'hidden'; i.name = 'radiology[]'; i.value = item.id; form.appendChild(i);
            var n = document.createElement('input'); n.type = 'hidden'; n.name = 'radiology_notes[]'; n.value = item.notes || ''; form.appendChild(n);
        });
    };
    renderPathologyListEdit();
    renderRadiologyListEdit();
    if (window.jQuery) {
        $(document).on('select2:select.prescriptionTests', '#pathologyOpt', function(e) {
            var data = e.params.data;
            if (data && data.id) {
                var count = (window.selectedPathologyList || []).filter(function(x) { return x.id == data.id; }).length;
                if (count >= 3 && !confirm('This test is already added 3 times today. Add again?')) { $(this).val(null).trigger('change'); return; }
                window.selectedPathologyList.push({ id: data.id, name: data.text || ('ID ' + data.id), notes: '' });
                renderPathologyListEdit();
                $(this).val(null).trigger('change');
            }
        });
        $(document).on('select2:select.prescriptionTests', '#radiologyOpt', function(e) {
            var data = e.params.data;
            if (data && data.id) {
                var count = (window.selectedRadiologyList || []).filter(function(x) { return x.id == data.id; }).length;
                if (count >= 3 && !confirm('This test is already added 3 times today. Add again?')) { $(this).val(null).trigger('change'); return; }
                window.selectedRadiologyList.push({ id: data.id, name: data.text || ('ID ' + data.id), notes: '' });
                renderRadiologyListEdit();
                $(this).val(null).trigger('change');
            }
        });
        // Removed fallback change handlers - they were causing duplicate additions
        // The select2:select event handler above handles adding tests one at a time
    }

    // Ensure form submission includes Select2 values
    const form = document.getElementById('editPrescriptionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🔴 Edit prescription form submit intercepted');
            
            // Sync test lists to form
            if (typeof window.syncPrescriptionTestListsToForm === 'function') {
                window.syncPrescriptionTestListsToForm(this);
            }
            
            // Ensure Select2 values are synced to the original select
            if (window.jQuery && $.fn.select2) {
                $('#prescribe_by').trigger('change');
                
                // Sync all Select2 values to native selects
                const allSelect2Selects = document.querySelectorAll('select.select2, select.multiselect2, select.medicine_category, select.medicine_name, select.medicine_dosage, select.interval_dosage, select.duration_dosage, select[name="finding_type[]"], select[name="findings[]"]');
                allSelect2Selects.forEach(select => {
                    if ($(select).hasClass('select2-hidden-accessible')) {
                        try {
                            const select2Value = $(select).val();
                            if (select2Value !== null && select2Value !== undefined) {
                                if (Array.isArray(select2Value)) {
                                    $(select).val(select2Value);
                                } else {
                                    select.value = String(select2Value);
                                }
                            }
                        } catch(e) {
                            console.warn('Error syncing Select2:', e);
                        }
                    }
                });
            }
            
            // Build FormData (use 'this' = form element that was submitted)
            const submittedForm = this;
            const formData = new FormData(submittedForm);
            
            // Ensure prescription date is always sent (for back-dated prescriptions)
            const dateInput = submittedForm.querySelector('#prescription_date') || submittedForm.querySelector('input[name="date"]');
            const dateToSend = dateInput && dateInput.value
                ? dateInput.value.trim()
                : (dateInput && dateInput.getAttribute('data-original-date')) || new Date().toISOString().split('T')[0];
            formData.set('date', dateToSend);
            
            // Debug: Log form data
            console.log('🔴 Form submitting with - Date:', dateToSend, 'Prescribe By:', formData.get('prescribe_by'));
            
            // Submit via fetch
            fetch(submittedForm.action, {
                method: submittedForm.method || 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(async response => {
                console.log('🔴 Response status:', response.status);
                console.log('🔴 Response Content-Type:', response.headers.get('Content-Type'));
                
                const contentType = response.headers.get('Content-Type') || '';
                let data;
                
                if (contentType.includes('application/json')) {
                    try {
                        data = await response.json();
                        console.log('🔴 Response data:', data);
                    } catch (e) {
                        console.error('🔴 Failed to parse JSON:', e);
                        const text = await response.text();
                        console.error('🔴 Response text:', text.substring(0, 500));
                        throw new Error('Server returned invalid JSON. Please check the console for details.');
                    }
                } else {
                    const text = await response.text();
                    console.error('🔴 Server returned non-JSON response:', text.substring(0, 500));
                    throw new Error('Server returned HTML instead of JSON. This usually means a validation error or server error occurred.');
                }
                
                if (!response.ok) {
                    if (data.errors) {
                        console.error('🔴 Validation errors:', data.errors);
                        const errorMessages = Object.values(data.errors).flat().join(', ');
                        throw new Error(errorMessages);
                    }
                    throw new Error(data.message || 'Server error');
                }
                return data;
            })
            .then(data => {
                console.log('🔴 Success:', data);
                // Show success notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'Prescription updated successfully.',
                        confirmButtonColor: '#7c3aed',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Redirect back to IPD view page
                        @if($prescription->ipd_id)
                            window.location.href = "{{ route('ipd.show', $prescription->ipd_id) }}";
                        @else
                            location.reload();
                        @endif
                    });
                } else {
                    // Fallback if SweetAlert2 is not available
                    alert(data.message || 'Prescription updated successfully!');
                    @if($prescription->ipd_id)
                        window.location.href = "{{ route('ipd.show', $prescription->ipd_id) }}";
                    @else
                        location.reload();
                    @endif
                }
            })
            .catch(error => {
                console.error('🔴 Error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Something went wrong while updating the prescription.',
                        confirmButtonColor: '#7c3aed',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Something went wrong: ' + error.message);
                }
            });
        });
    }
    
    // Initialize Select2 multiselect on pathology and radiology selects
    if (window.jQuery && $.fn.select2) {
        $('#pathologyOpt, #radiologyOpt').select2({
            placeholder: 'Search and select tests...',
            allowClear: true,
            width: '100%',
            minimumResultsForSearch: 0, // Always show search box
            language: {
                noResults: function() {
                    return "No results found";
                },
                searching: function() {
                    return "Searching...";
                }
            }
        });
        
        // Track instance counts for selected tests
        const prescriptionDate = '{{ $prescriptionDate ?? date("Y-m-d") }}';
        const ipdId = {{ $prescription->ipd_id ?? 'null' }};
        
        // Function to update instance count display
        function updateInstanceDisplay(selectId, testType) {
            const $select = $(selectId);
            const selectedValues = $select.val() || [];
            const instanceCounts = {};
            
            // Count occurrences of each test in selection
            selectedValues.forEach(testId => {
                instanceCounts[testId] = (instanceCounts[testId] || 0) + 1;
            });
            
            // Update option text with instance counts
            $select.find('option').each(function() {
                const $option = $(this);
                const testId = $option.val();
                const baseText = $option.text().replace(/\s*\(×\d+\)\s*$/, ''); // Remove existing instance badge
                
                if (selectedValues.includes(testId)) {
                    const count = instanceCounts[testId];
                    $option.text(baseText + (count > 1 ? ` (×${count})` : ''));
                } else {
                    // Restore original text without instance badge if not selected
                    $option.text(baseText);
                }
            });
            
            // Trigger Select2 update
            $select.trigger('change.select2');
        }
        
        // Update instance display on selection change
        $('#pathologyOpt').on('select2:select select2:unselect', function() {
            updateInstanceDisplay('#pathologyOpt', 'pathology');
        });
        
        $('#radiologyOpt').on('select2:select select2:unselect', function() {
            updateInstanceDisplay('#radiologyOpt', 'radiology');
        });
        
        // Initialize display on page load
        updateInstanceDisplay('#pathologyOpt', 'pathology');
        updateInstanceDisplay('#radiologyOpt', 'radiology');
    }

    // Medicine management
    const container = document.getElementById("medicineContainer");
    const addButton = document.getElementById("addMedicineBtn");
    const addButtonContainer = document.getElementById("addMedicineContainer");

    // Store existing medicine data with all relationships
    const existingMedicines = @json($prescription->medicines ?? []);
    console.log('Existing medicines data:', existingMedicines);

    // Fetch base dropdown data once
    Promise.all([
        fetch("{{ route('getMedicineCategories') }}").then(res => res.json()),
        fetch("{{ route('getDoseIntervals') }}").then(res => res.json()),
        fetch("{{ route('getDoseDurations') }}").then(res => res.json())
    ]).then(([categories, intervals, durations]) => {
        window.medicineCategories = categories;
        window.doseIntervals = intervals;
        window.doseDurations = durations;

        // Initialize all existing rows
        const rows = container.querySelectorAll(".medicine-row");
        rows.forEach((row, index) => {
            initRow(row, existingMedicines[index]);
        });

        // If no rows exist, initialize the first one
        if (rows.length === 0) {
            const firstRow = container.querySelector(".medicine-row");
            if (firstRow) {
                initRow(firstRow);
            }
        }

        addButton.addEventListener("click", function(e) {
            e.preventDefault();
            addNewRow();
        });
    });

    function initRow(row, existingMedicine = null) {
        // Load base options
        const categorySelect = row.querySelector(".medicine_category");
        const medicineSelect = row.querySelector(".medicine_name");
        const dosageSelect = row.querySelector(".medicine_dosage");
        const intervalSelect = row.querySelector(".interval_dosage");
        const durationSelect = row.querySelector(".duration_dosage");

        if (!categorySelect || !medicineSelect || !dosageSelect || !intervalSelect || !durationSelect) {
            console.error('Required select elements not found in row');
            return;
        }

        // Fill base selects
        fillSelect(categorySelect, window.medicineCategories, "medicine_category");
        fillSelect(intervalSelect, window.doseIntervals, "name");
        fillSelect(durationSelect, window.doseDurations, "name");

        // Get values from data attributes (preferred) or from existingMedicine object
        const rowCategoryId = row.getAttribute('data-category-id');
        const rowMedicineId = row.getAttribute('data-medicine-id');
        const rowDosageId = row.getAttribute('data-dosage-id');
        const rowIntervalId = row.getAttribute('data-interval-id');
        const rowDurationId = row.getAttribute('data-duration-id');
        
        const medicineId = existingMedicine?.pharmacy_id || rowMedicineId || medicineSelect.getAttribute('data-medicine-id');
        const dosageId = existingMedicine?.medicine_dosage_id || rowDosageId || dosageSelect.getAttribute('data-dosage-id');
        const intervalId = existingMedicine?.dose_interval_id || rowIntervalId || intervalSelect.getAttribute('data-interval-id');
        const durationId = existingMedicine?.dose_duration_id || rowDurationId || durationSelect.getAttribute('data-duration-id');
        
        // Get category ID - try multiple sources
        let categoryId = null;
        if (rowCategoryId && rowCategoryId !== '') {
            categoryId = rowCategoryId;
        } else if (existingMedicine) {
            // Try multiple paths to get category ID from existingMedicine
            categoryId = existingMedicine.pharmacy?.medicine_category_id || 
                        existingMedicine.pharmacy?.medicine_category?.id ||
                        (existingMedicine.pharmacy && existingMedicine.pharmacy.medicine_category_id) ||
                        (existingMedicine.pharmacy && existingMedicine.pharmacy.medicine_category && existingMedicine.pharmacy.medicine_category.id);
        }
        
        console.log('Row data attributes:', {
            rowCategoryId,
            rowMedicineId,
            rowDosageId,
            rowIntervalId,
            rowDurationId
        });
        
        console.log('Extracted values:', {
            categoryId,
            medicineId,
            dosageId,
            intervalId,
            durationId,
            existingMedicine: existingMedicine ? {
                pharmacy_id: existingMedicine.pharmacy_id,
                pharmacy: existingMedicine.pharmacy ? {
                    medicine_category_id: existingMedicine.pharmacy.medicine_category_id,
                    medicine_category: existingMedicine.pharmacy.medicine_category
                } : null
            } : null
        });

        // If editing existing medicine, populate values
        if (medicineId || categoryId) {
            console.log('Initializing row with existing data:', {
                medicineId,
                categoryId,
                dosageId,
                intervalId,
                durationId
            });
            
            if (categoryId) {
                // Set category value first
                categorySelect.value = categoryId;
                
                // Load medicines for this category
                const baseUrl = "{{ route('getMedicines', ['categoryId' => 'ID']) }}";
                const finalUrl = baseUrl.replace('ID', categoryId);
                
                fetch(finalUrl)
                    .then(res => res.json())
                    .then(data => {
                        fillSelect(medicineSelect, data, "medicine_name");
                        if (medicineId) {
                            medicineSelect.value = medicineId;
                        }
                        
                        // Load dosages
                        const baseUrlDose = "{{ route('getDoses', ['categoryId' => 'ID']) }}";
                        const finalUrlDose = baseUrlDose.replace('ID', categoryId);
                        fetch(finalUrlDose)
                            .then(res => res.json())
                            .then(data => {
                                fillSelect(dosageSelect, data, "dosage");
                                if (dosageId) {
                                    dosageSelect.value = dosageId;
                                }
                                
                                // Initialize Select2 after all values are set
                                initializeSelect2ForRow(row, {
                                    categoryId,
                                    medicineId,
                                    dosageId,
                                    intervalId,
                                    durationId
                                });
                            })
                            .catch(error => {
                                console.error('Error loading doses:', error);
                                initializeSelect2ForRow(row, {
                                    categoryId,
                                    medicineId,
                                    dosageId,
                                    intervalId,
                                    durationId
                                });
                            });
                    })
                    .catch(error => {
                        console.error('Error loading medicines:', error);
                        initializeSelect2ForRow(row, {
                            categoryId,
                            medicineId,
                            dosageId,
                            intervalId,
                            durationId
                        });
                    });
            } else {
                // If no category but we have medicine ID, try to load all medicines
                // This is a fallback - ideally we should have category
                initializeSelect2ForRow(row, {
                    categoryId: null,
                    medicineId,
                    dosageId,
                    intervalId,
                    durationId
                });
            }
        } else {
            // No existing medicine, just initialize Select2
            initializeSelect2ForRow(row);
        }

        // Category change → fetch medicines (using jQuery for Select2 compatibility)
        const $row = $(row);
        const categorySelectEl = row.querySelector(".medicine_category");
        const medicineSelectEl = row.querySelector(".medicine_name");
        const doseSelectEl = row.querySelector(".medicine_dosage");
        
        // Remove any existing handlers to prevent duplicates
        $row.off('change.medicineCategory select2:select.medicineCategory select2:clear.medicineCategory');
        
        // Handle both regular change and Select2 events
        $row.on('change.medicineCategory', '.medicine_category', function() {
            const categoryId = $(this).val();
            console.log('🔴 Medicine category changed:', categoryId);
            loadMedicinesForCategory(categoryId, medicineSelectEl, doseSelectEl);
        });
        
        // Also handle Select2 specific events
        $(categorySelectEl).on('select2:select.medicineCategory select2:clear.medicineCategory', function() {
            const categoryId = $(this).val();
            console.log('🔴 Medicine category changed via Select2:', categoryId);
            loadMedicinesForCategory(categoryId, medicineSelectEl, doseSelectEl);
        });
        
        // Helper function to load medicines and dosages
        function loadMedicinesForCategory(categoryId, medicineSelectEl, doseSelectEl) {
            if (!categoryId || categoryId === '') {
                // Clear medicines and dosages if no category selected
                if (medicineSelectEl) {
                    medicineSelectEl.innerHTML = '<option value="">Select Medicine</option>';
                    if (window.jQuery && $(medicineSelectEl).hasClass('select2-hidden-accessible')) {
                        $(medicineSelectEl).val(null).trigger('change');
                    }
                }
                if (doseSelectEl) {
                    doseSelectEl.innerHTML = '<option value="">Select Dosage</option>';
                    if (window.jQuery && $(doseSelectEl).hasClass('select2-hidden-accessible')) {
                        $(doseSelectEl).val(null).trigger('change');
                    }
                }
                return;
            }
            
            const baseUrl = "{{ route('getMedicines', ['categoryId' => 'ID']) }}";
            const finalUrl = baseUrl.replace('ID', categoryId);
            
            console.log('🔴 Fetching medicines from:', finalUrl);
            fetch(finalUrl)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Failed to fetch medicines: ' + res.status);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('🔴 Medicines received:', data);
                    if (!data || !Array.isArray(data)) {
                        console.error('🔴 Invalid medicines data:', data);
                        if (medicineSelectEl) {
                            medicineSelectEl.innerHTML = '<option value="">No medicines found</option>';
                        }
                        return;
                    }
                    fillSelect(medicineSelectEl, data, "medicine_name");
                    // Reinitialize Select2 after filling
                    if (window.jQuery && $(medicineSelectEl).hasClass('select2-hidden-accessible')) {
                        $(medicineSelectEl).trigger('change');
                    }
                })
                .catch(error => {
                    console.error('🔴 Error loading medicines:', error);
                    if (medicineSelectEl) {
                        medicineSelectEl.innerHTML = '<option value="">Error loading medicines</option>';
                    }
                });

            const baseUrlDose = "{{ route('getDoses', ['categoryId' => 'ID']) }}";
            const finalUrlDose = baseUrlDose.replace('ID', categoryId);
            
            console.log('🔴 Fetching dosages from:', finalUrlDose);
            fetch(finalUrlDose)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Failed to fetch dosages: ' + res.status);
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('🔴 Dosages received:', data);
                    if (!data || !Array.isArray(data)) {
                        console.error('🔴 Invalid dosages data:', data);
                        if (doseSelectEl) {
                            doseSelectEl.innerHTML = '<option value="">No dosages found</option>';
                        }
                        return;
                    }
                    fillSelect(doseSelectEl, data, "dosage");
                    // Reinitialize Select2 after filling
                    if (window.jQuery && $(doseSelectEl).hasClass('select2-hidden-accessible')) {
                        $(doseSelectEl).trigger('change');
                    }
                })
                .catch(error => {
                    console.error('🔴 Error loading dosages:', error);
                    if (doseSelectEl) {
                        doseSelectEl.innerHTML = '<option value="">Error loading dosages</option>';
                    }
                });
        }

        // Delete button
        const deleteBtn = row.querySelector(".delete_row");
        if (deleteBtn) {
            deleteBtn.addEventListener("click", function() {
                const allRows = container.querySelectorAll(".medicine-row");
                if (allRows.length > 1) row.remove();
                else alert("At least one medicine must remain.");
            });
        }

    }
    
    function initializeSelect2ForRow(row, values = {}) {
        const categorySelect = row.querySelector(".medicine_category");
        const medicineSelect = row.querySelector(".medicine_name");
        const dosageSelect = row.querySelector(".medicine_dosage");
        const intervalSelect = row.querySelector(".interval_dosage");
        const durationSelect = row.querySelector(".duration_dosage");
        
        setTimeout(() => {
            if (window.jQuery && $.fn.select2) {
                // Destroy existing Select2 instances
                [categorySelect, medicineSelect, dosageSelect, intervalSelect, durationSelect].forEach(select => {
                    if (select && $(select).hasClass('select2-hidden-accessible')) {
                        $(select).select2('destroy');
                    }
                });
                
                // Initialize Select2 for all selects
                $(row).find(".select2").select2({
                    width: "100%",
                    placeholder: "Select",
                    allowClear: true
                });
                
                // Set values and trigger change to update Select2 display
                if (values.categoryId && categorySelect) {
                    $(categorySelect).val(values.categoryId).trigger('change');
                }
                if (values.medicineId && medicineSelect) {
                    $(medicineSelect).val(values.medicineId).trigger('change');
                }
                if (values.dosageId && dosageSelect) {
                    $(dosageSelect).val(values.dosageId).trigger('change');
                }
                if (values.intervalId && intervalSelect) {
                    $(intervalSelect).val(values.intervalId).trigger('change');
                }
                if (values.durationId && durationSelect) {
                    $(durationSelect).val(values.durationId).trigger('change');
                }
                
                console.log('Select2 initialized for row with values:', values);
            }
        }, 300); // Increased delay to ensure all async operations complete
    }

    function fillSelect(selectElement, data, textKey) {
        if (!selectElement) {
            console.error('🔴 fillSelect: selectElement is null');
            return;
        }
        if (!data || !Array.isArray(data)) {
            console.error('🔴 fillSelect: data is not an array:', data);
            selectElement.innerHTML = `<option value="">No data available</option>`;
            return;
        }
        
        // Store current Select2 state
        let isSelect2Initialized = false;
        let currentValue = null;
        if (window.jQuery && $(selectElement).hasClass('select2-hidden-accessible')) {
            isSelect2Initialized = true;
            currentValue = $(selectElement).val();
            // Destroy Select2 temporarily to update options
            try {
                $(selectElement).select2('destroy');
            } catch(e) {
                console.warn('🔴 Error destroying Select2:', e);
            }
        }
        
        // Clear and fill options
        selectElement.innerHTML = `<option value="">Select</option>`;
        data.forEach(item => {
            if (!item || !item.id) {
                console.warn('🔴 fillSelect: Invalid item:', item);
                return;
            }
            const opt = document.createElement("option");
            opt.value = item.id;
            
            // Handle different textKey values
            if (textKey === 'dosage') {
                opt.textContent = item[textKey] + " " + (item['unit'] ? item['unit']['unit_name'] : '');
            } else if (textKey === 'medicine_name') {
                opt.textContent = item.medicine_name || item.name || ('ID ' + item.id);
            } else {
                opt.textContent = item[textKey] || item.name || ('ID ' + item.id);
            }
            selectElement.appendChild(opt);
        });
        
        console.log('🔴 fillSelect: Filled', selectElement.options.length, 'options for', textKey);
        
        // Reinitialize Select2 if it was initialized before
        if (isSelect2Initialized && window.jQuery && $.fn.select2) {
            setTimeout(() => {
                try {
                    $(selectElement).select2({
                        width: "100%",
                        placeholder: textKey === 'medicine_name' ? "Select Medicine" : 
                                    textKey === 'dosage' ? "Select Dosage" : "Select",
                        allowClear: true,
                        minimumResultsForSearch: 0
                    });
                    // Restore previous value if it still exists
                    if (currentValue && Array.from(selectElement.options).some(opt => opt.value == currentValue)) {
                        $(selectElement).val(currentValue).trigger('change');
                    }
                    console.log('🔴 Select2 reinitialized for', textKey, 'with', selectElement.options.length, 'options');
                } catch(e) {
                    console.error('🔴 Error reinitializing Select2:', e);
                }
            }, 50);
        }
    }

    function addNewRow() {
        const rows = container.querySelectorAll(".medicine-row");
        if (rows.length === 0) {
            console.error("No .medicine-row found in the container.");
            return;
        }
        const lastRow = rows[rows.length - 1];
        const newRow = lastRow.cloneNode(true);

        // Clear previous selections
        newRow.querySelectorAll("select").forEach(s => (s.selectedIndex = 0));
        newRow.querySelectorAll("textarea").forEach(t => (t.value = ""));

        // Update row number
        const newRowNum = rows.length + 1;
        newRow.setAttribute('data-row', newRowNum);
        newRow.setAttribute('id', 'row' + newRowNum);
        newRow.querySelector('.medicine_name').setAttribute('data-rowid', newRowNum);
        newRow.querySelector('.interval_dosage').setAttribute('id', 'interval_dosage_' + newRowNum);
        newRow.querySelector('.duration_dosage').setAttribute('id', 'duration_dosage_' + newRowNum);
        newRow.querySelector('.delete_row').setAttribute('data-row-id', newRowNum);

        // Insert before button
        container.insertBefore(newRow, addButtonContainer);
        initRow(newRow);
    }
});
</script>
@endsection

