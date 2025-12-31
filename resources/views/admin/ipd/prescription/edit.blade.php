@extends('layouts.adminLayout')
@section('content')
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
                                <input type="date" class="form-control" value="{{ $prescription->date->format('Y-m-d') }}" disabled>
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
                                <select class="form-control pathology-test-select" name="pathology[]" id="pathologyOpt" multiple style="width: 100%;">
                                    @foreach($pathologies as $pathology)
                                        <option value="{{ $pathology->id }}" {{ in_array($pathology->id, $selectedPathologyIds) ? 'selected' : '' }}>
                                            {{ $pathology->test_name ?? $pathology->name }} ({{ $pathology->short_name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Radiology Tests</label>
                                <select class="form-control radiology-test-select" name="radiology[]" id="radiologyOpt" multiple style="width: 100%;">
                                    @foreach($radiologies as $radiology)
                                        <option value="{{ $radiology->id }}" {{ in_array($radiology->id, $selectedRadiologyIds) ? 'selected' : '' }}>
                                            {{ $radiology->test_name ?? $radiology->name }} ({{ $radiology->short_name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Medicines</label>
                            <div id="medicineContainer">
                                @if($prescription->medicines && $prescription->medicines->count() > 0)
                                    @foreach($prescription->medicines as $index => $medicine)
                                        <div class="medicine-row row mt-3" data-row="{{ $index + 1 }}" id="row{{ $index + 1 }}">
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
    
    // Ensure form submission includes Select2 values
    const form = document.getElementById('editPrescriptionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Ensure Select2 values are synced to the original select
            if (window.jQuery && $.fn.select2) {
                $('#prescribe_by').trigger('change');
            }
            
            // Debug: Log form data
            const formData = new FormData(form);
            console.log('Form submitting with - Prescribe By:', formData.get('prescribe_by'));
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
    }

    // Medicine management
    const container = document.getElementById("medicineContainer");
    const addButton = document.getElementById("addMedicineBtn");
    const addButtonContainer = document.getElementById("addMedicineContainer");

    // Store existing medicine data
    const existingMedicines = @json($prescription->medicines ?? []);

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

        fillSelect(categorySelect, window.medicineCategories, "medicine_category");
        fillSelect(intervalSelect, window.doseIntervals, "name");
        fillSelect(durationSelect, window.doseDurations, "name");

        // If editing existing medicine, populate values
        if (existingMedicine) {
            // Set category and load medicines
            const categoryId = existingMedicine.pharmacy?.medicine_category_id || existingMedicine.pharmacy?.medicine_category?.id;
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
                        if (existingMedicine.pharmacy_id) {
                            medicineSelect.value = existingMedicine.pharmacy_id;
                        }
                        
                        // Load dosages
                        const baseUrlDose = "{{ route('getDoses', ['categoryId' => 'ID']) }}";
                        const finalUrlDose = baseUrlDose.replace('ID', categoryId);
                        fetch(finalUrlDose)
                            .then(res => res.json())
                            .then(data => {
                                fillSelect(dosageSelect, data, "dosage");
                                if (existingMedicine.medicine_dosage_id) {
                                    dosageSelect.value = existingMedicine.medicine_dosage_id;
                                }
                            });
                    });
            }

            // Set interval and duration
            if (existingMedicine.dose_interval_id) {
                intervalSelect.value = existingMedicine.dose_interval_id;
            }
            if (existingMedicine.dose_duration_id) {
                durationSelect.value = existingMedicine.dose_duration_id;
            }
        }

        // Category change → fetch medicines
        row.querySelector(".medicine_category").addEventListener("change", function() {
            const categoryId = this.value;
            const medicineSelect = row.querySelector(".medicine_name");
            const doseSelect = row.querySelector(".medicine_dosage");
            const baseUrl = "{{ route('getMedicines', ['categoryId' => 'ID']) }}";
            const finalUrl = baseUrl.replace('ID', categoryId);
            fetch(finalUrl)
                .then(res => res.json())
                .then(data => fillSelect(medicineSelect, data, "medicine_name"));

            const baseUrlDose = "{{ route('getDoses', ['categoryId' => 'ID']) }}";
            const finalUrlDose = baseUrlDose.replace('ID', categoryId);
            fetch(finalUrlDose)
                .then(res => res.json())
                .then(data => fillSelect(doseSelect, data, "dosage"));
        });

        // Delete button
        const deleteBtn = row.querySelector(".delete_row");
        if (deleteBtn) {
            deleteBtn.addEventListener("click", function() {
                const allRows = container.querySelectorAll(".medicine-row");
                if (allRows.length > 1) row.remove();
                else alert("At least one medicine must remain.");
            });
        }

        // Initialize select2 after a short delay to ensure values are set
        setTimeout(() => {
            if (window.jQuery && $.fn.select2) {
                $(row).find(".select2").select2({
                    width: "100%"
                });
                
                // If editing existing medicine, trigger change events to update Select2 display
                if (existingMedicine) {
                    $(categorySelect).trigger('change');
                    $(medicineSelect).trigger('change');
                    $(dosageSelect).trigger('change');
                    $(intervalSelect).trigger('change');
                    $(durationSelect).trigger('change');
                }
            }
        }, 200);
    }

    function fillSelect(selectElement, data, textKey) {
        if (!selectElement) return;
        selectElement.innerHTML = `<option value="">Select</option>`;
        data.forEach(item => {
            const opt = document.createElement("option");
            opt.value = item.id;
            opt.textContent = textKey == 'dosage' ? item[textKey] + " " + (item['unit'] ? item['unit']['unit_name'] : '') : item[textKey];
            selectElement.appendChild(opt);
        });
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

