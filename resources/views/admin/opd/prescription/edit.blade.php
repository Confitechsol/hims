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

                        <form class="use-select2" action="{{ route('opd.prescription.update', $prescription->id) }}"
                            method="POST" enctype="multipart/form-data" id="editPrescriptionForm">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="ipd_id" value="{{ $prescription->ipd_id }}">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Prescribe By <span class="text-danger">*</span></label>
                                    <select class="form-control multiselect2" name="prescribe_by" id="prescribe_by"
                                        required>
                                        <option value="">Select Doctor</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                {{ $prescription->prescribed_by && (int) $prescription->prescribed_by === (int) $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }} ({{ $doctor->doctor_id ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control"
                                        value="{{ $prescription->date->format('Y-m-d') }}" disabled>
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
                                            $selectedCategories = explode(
                                                ', ',
                                                $prescription->finding_categories ?? '',
                                            );
                                        @endphp
                                        @foreach ($findingCategories as $category)
                                            <option value="{{ $category->category }}"
                                                {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                                                {{ $category->category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Finding List</label>
                                    <select class="form-control multiselect2" name="findings[]" multiple>
                                        @php
                                            $selectedFindings = explode(', ', $prescription->findings ?? '');
                                        @endphp
                                        @foreach ($findings as $finding)
                                            <option value="{{ $finding->id }}"
                                                {{ in_array($finding->id, $selectedFindings) ? 'selected' : '' }}>
                                                {{ $finding->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Finding Description</label>
                                    <textarea name="finding_description" class="form-control" rows="3">{{ $prescription->finding_description }}</textarea>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Finding Print</label><br>
                                    <input type="checkbox" name="finding_print" value="yes"
                                        {{ $prescription->is_finding_print == 'yes' ? 'checked' : '' }}>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Pathology Tests</label>
                                    <select class="form-control multiselect2" name="pathology[]" id="pathologyOpt" multiple
                                        style="width: 100%;">
                                        @foreach ($pathologies as $pathology)
                                            <option value="{{ $pathology->id }}"
                                                {{ in_array($pathology->id, $selectedPathologies) ? 'selected' : '' }}>
                                                {{ $pathology->test_name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Radiology Tests</label>
                                    <select class="form-control multiselect2" name="radiology[]" id="radiologyOpt" multiple
                                        style="width: 100%;">
                                        @foreach ($radiologies as $radiology)
                                            <option value="{{ $radiology->id }}"
                                                {{ in_array($radiology->id, $selectedRadiologyIds) ? 'selected' : '' }}>
                                                {{ $radiology->test_name ?? $radiology->name }}
                                                ({{ $radiology->short_name ?? '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Medicines</label>
                                <div id="medicineContainer">
                                    @if ($prescription->medicines && $prescription->medicines->count() > 0)
                                        @foreach ($prescription->medicines as $index => $medicine)
                                            <div class="medicine-row row mt-3" data-row="{{ $index + 1 }}"
                                                id="row{{ $index + 1 }}"
                                                data-category-id="{{ $medicine->pharmacy->medicine_category_id ?? ($medicine->pharmacy->medicineCategory->id ?? '') }}"
                                                data-medicine-id="{{ $medicine->pharmacy_id }}"
                                                data-dosage-id="{{ $medicine->medicine_dosage_id }}"
                                                data-interval-id="{{ $medicine->dose_interval_id }}"
                                                data-duration-id="{{ $medicine->dose_duration_id }}">
                                                <div class="col-lg-3 mb-2 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Medicine Category</label>
                                                        <select class="form-control multiselect2 medicine_category"
                                                            style="width:100%" name="medicine_categories[]"
                                                            data-row="{{ $index + 1 }}">
                                                            <option value="">Select</option>
                                                            @foreach ($medicineCategories as $medicineCategory)
                                                                <option value="{{ $medicineCategory->id }}"
                                                                    {{ $medicine->pharmacy->medicine_category_id == $medicineCategory->id ? 'selected' : '' }}>
                                                                    {{ $medicineCategory->medicine_category }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 mb-2 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Medicine</label>
                                                        <select class="form-control multiselect2 medicine_name"
                                                            data-rowid="{{ $index + 1 }}" style="width:100%"
                                                            name="medicines[]"
                                                            data-medicine-id="{{ $medicine->pharmacy_id }}"
                                                            data-medicine_category_id="{{ $medicine->pharmacy->medicine_category_id }}">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 mb-2 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Dose</label>
                                                        <select class="form-control multiselect2 medicine_dosage"
                                                            style="width:100%" name="dosages[]"
                                                            data-dosage-id="{{ $medicine->medicine_dosage_id }}">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 mb-2 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Dose Interval</label>
                                                        <select class="form-control multiselect2 interval_dosage"
                                                            id="interval_dosage_{{ $index + 1 }}"
                                                            name="interval_dosages[]" style="width:100%"
                                                            data-interval-id="{{ $medicine->dose_interval_id }}">
                                                            <option value="">Select</option>
                                                            @foreach ($doseIntervals as $interval)
                                                                <option value="{{ $interval->id }}">
                                                                    {{ $interval->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Dose Duration</label>
                                                        <select class="form-control multiselect2 duration_dosage"
                                                            id="duration_dosage_{{ $index + 1 }}"
                                                            name="duration_dosages[]" style="width:100%"
                                                            data-duration-id="{{ $medicine->dose_duration_id }}">
                                                            @foreach ($doseDurations as $duration)
                                                                <option value="{{ $duration->id }}">
                                                                    {{ $duration->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-1 col-sm-1 col-xs-1">
                                                    <div>
                                                        <label class="form-label">Instruction</label>
                                                        <textarea name="instructions[]" style="height:70px;" class="form-control">{{ $medicine->instruction }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 d-flex align-items-center">
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-danger delete_row"
                                                            data-row-id="{{ $index + 1 }}" autocomplete="off"><i
                                                                class="fa fa-remove"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="medicine-row row mt-3" data-row="1" id="row1">
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Medicine Category</label>
                                                    <select class="form-control select2 medicine_category"
                                                        style="width:100%" name="medicine_categories[]">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Medicine</label>
                                                    <select class="form-control select2 medicine_name" data-rowid="1"
                                                        style="width:100%" name="medicines[]">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Dose</label>
                                                    <select class="form-control select2 medicine_dosage"
                                                        style="width:100%" name="dosages[]">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Dose Interval</label>
                                                    <select class="form-control select2 interval_dosage"
                                                        id="interval_dosage_1" name="interval_dosages[]"
                                                        style="width:100%">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                <div>
                                                    <label class="form-label">Dose Duration</label>
                                                    <select class="form-control select2 duration_dosage"
                                                        id="duration_dosage_1" name="duration_dosages[]"
                                                        style="width:100%">
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
                                                    <button type="button" class="btn btn-sm btn-danger delete_row"
                                                    onclick="removeMedicine(this)"  data-row-id="1" autocomplete="off"><i
                                                            class="fa fa-remove"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6 mt-2" id="addMedicineContainer">
                                        <a class="btn btn-primary btn-sm add-record" data-added="0"
                                            id="addMedicineBtn"><i class="fa fa-plus"></i> Add Medicine</a>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Footer Note</label>
                                <textarea name="footer_note" class="form-control" rows="4">{{ $prescription->footer_note }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Attachment</label>
                                @if ($prescription->attachment)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $prescription->attachment) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
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
                                        <label><input type="checkbox" name="visible[]" value="1"
                                                {{ in_array('1', $selectedNotifications) ? 'checked' : '' }}> Admin</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label><input type="checkbox" name="visible[]" value="2"
                                                {{ in_array('2', $selectedNotifications) ? 'checked' : '' }}>
                                            Accountant</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label><input type="checkbox" name="visible[]" value="3"
                                                {{ in_array('3', $selectedNotifications) ? 'checked' : '' }}>
                                            Doctor</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label><input type="checkbox" name="visible[]" value="4"
                                                {{ in_array('4', $selectedNotifications) ? 'checked' : '' }}>
                                            Pharmacist</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('opd.show', $prescription->opd_id) }}"
                                    class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Prescription</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const getMedicines = "{{ route('getMedicines', ':id') }}";
        const getDoses = "{{ route('getDoses', ':id') }}";
        $(document).ready(function() {
            $(".multiselect2").each(function(i, item) {
                $(item).select2();
            })
            $('.medicine_category').each(function() {

                let categorySelect = $(this);
                let categoryId = categorySelect.val();

                // IF ALREADY SELECTED
                if (categoryId) {

                    let row = categorySelect.data('row');
                    let medicineSelect = $(`.medicine_name[data-rowid="${row}"]`);

                    let url = getMedicines.replace(':id', categoryId);
                    let medicineId = medicineSelect.data('medicine-id');
                    let medicine_category_id = medicineSelect.data('medicine_category_id');
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {

                            medicineSelect.empty().append(
                                '<option value="">Select Medicine</option>');

                            $.each(response, function(i, item) {
                                medicineSelect.append(
                                    `<option value="${item.id}">${item.medicine_name}</option>`
                                );
                            });
                            if (medicineId) {
                                medicineSelect.val(medicineId);
                                setDose(medicine_category_id, row);

                            }
                            // Refresh Select2
                            medicineSelect.trigger('change');
                        }
                    });
                }
            });

            let rowCount = {{ $prescription->medicines->count() > 0 ? $prescription->medicines->count() : 1 }};

            $('#addMedicineBtn').on('click', function() {
                rowCount++;

                let newRow = `
<div class="medicine-row row mt-3" data-row="${rowCount}" id="row${rowCount}">
<div class="col-lg-3">
    <label class="form-label">Medicine Category</label>
    <select class="form-control multiselect2 medicine_category" name="medicine_categories[]">
        <option value="">Select</option>
        @foreach ($medicineCategories as $category)
        <option value="{{ $category->id }}">{{ $category->medicine_category }}</option>
        @endforeach
    </select>
</div>

<div class="col-lg-3">
    <label class="form-label">Medicine</label>
    <select class="form-control multiselect2 medicine_name" name="medicines[]"
    data-rowid="${rowCount}"
    >
        <option value="">Select</option>
    </select>
</div>

<div class="col-lg-3">
    <label class="form-label">Dose</label>
    <select class="form-control multiselect2 medicine_dosage" name="dosages[]">
        <option value="">Select</option>
    </select>
</div>

<div class="col-lg-3">
    <label class="form-label">Dose Interval</label>
    <select class="form-control multiselect2" name="interval_dosages[]">
        <option value="">Select</option>
        @foreach ($doseIntervals as $interval)
        <option value="{{ $interval->id }}">
        {{ $interval->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-lg-3">
    <label class="form-label">Dose Duration</label>
    <select class="form-control multiselect2" name="duration_dosages[]">
        <option value="">Select</option>
        @foreach ($doseDurations as $duration)
        <option value="{{ $duration->id }}">
        {{ $duration->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-lg-3">
    <label class="form-label">Instruction</label>
    <textarea name="instructions[]" class="form-control" style="height:28px;"></textarea>
</div>

<div class="col-lg-1 d-flex align-items-center">
    <button type="button" onclick="removeMedicine(this)" data-row-id="${rowCount}" class="btn btn-sm btn-danger delete_row">
        <i class="fa fa-remove"></i>
    </button>
</div>
</div>`;

                $('#addMedicineContainer').before(newRow);
                initSelect2InModal(`#row${rowCount}`);
            });
        });
        $(document).on('change', '.medicine_category', function() {
            let row = $(this).closest('.medicine-row');
            let categoryId = $(this).val();
            let medicineSelect = row.find('.medicine_name');
            let dosageSelect = row.find('.medicine_dosage');

            medicineSelect.empty().append('<option value="">Select</option>');
            dosageSelect.empty().append('<option value="">Select</option>');

            if (!categoryId) return;
            let url = getMedicines.replace(':id', categoryId);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $.each(response, function(i, item) {
                        medicineSelect.append(
                            `<option value="${item.id}">${item.medicine_name}</option>`
                        );
                    });
                    medicineSelect.trigger('change');
                    setDose(categoryId, row.data('row'));
                }
            });
        });

        function setDose(medicineId, rowNumber) {
            let row = $(`.medicine-row[data-row="${rowNumber}"]`);
            let dosageSelect = row.find('.medicine_dosage');
            dosageSelect.empty().append('<option value="">Select</option>');

            if (!medicineId) return;
            let url = getDoses.replace(':id', medicineId);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $.each(response, function(i, item) {
                        dosageSelect.append(
                            `<option value="${item.id}">${item.dosage}</option>`);
                    });
                    if (dosageSelect.data("dosage-id")) {
                        dosageSelect.val(dosageSelect.data("dosage-id"));
                    }
                    dosageSelect.trigger('change');
                }
            });
        }

        function initSelect2InModal(context) {
            $(context).find('.multiselect2').select2({
                placeholder: 'Select',
                allowClear: true,
                width: '100%',
            });
        }

        function removeMedicine(elm) {
            if ($(".medicine-row").length < 2) {
                return;
            }
            let rowId = $(elm).data("row-id");
            $(`#row${rowId}`).remove();
            console.log($(elm).data("row-id"));
        }
    </script>
@endsection
