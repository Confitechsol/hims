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



    .editor-container {
        max-width: 900px;
        margin: auto;
    }

    h4 {
        margin-bottom: 10px;
    }

    .toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        padding: 6px;
        background: #f9f9f9;
        border: 1px solid #ddd;
    }

    .toolbar select,
    .toolbar button {
        background: #f3f3f3;
        border: 1px solid #ccc;
        border-radius: 3px;
        padding: 6px 10px;
        cursor: pointer;
        font-size: 14px;
        color: #333;
    }

    .toolbar button:hover,
    .toolbar select:hover {
        background: #e9e9e9;
    }

    .editor-area {
        min-height: 120px;
        border: 1px solid #ccc;
        margin-top: 5px;
        padding: 10px;
        border-radius: 2px;
        outline: none;
        background: #fff;
    }

    .editor-area:focus {
        border-color: #aaa;
    }
</style>
<div class="modal fade use-select2" id="addPrescriptionModal" tabindex="-1" aria-labelledby="addPrescriptionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <!-- inline overflow override so .modal-content {overflow: hidden;} in global CSS doesn't block scrolling -->
        <div class="modal-content" style="overflow: visible;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="addPrescriptionModal">
                    <div class="section-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    Add Prescription
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (scrollable area provided by modal-dialog-scrollable) -->
            <form action="{{ route('opd.addPrescription') }}" id="prescriptionForm" method="post"
                enctype="multipart/form-data">@csrf
                <div class="modal-body" style="max-height: calc(100vh - 160px); overflow-x:hiden;">
                    <div class="row p-4 mx-1">
                        <div class="col-sm-9">
                            <div class="ptt10">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" id="opd_id" name="opd_id"
                                            value="{{ isset($opd) ? $opd->id : '' }}">
                                        <input type="hidden" id="ipd_id" name="ipd_id"
                                            value="{{ isset($ipd) ? $ipd->id : '' }}">
                                        <div class="form-group">
                                            <label class="form-label">Header Note</label>
                                            <div class="toolbar" id="toolbar">
                                                <select id="formatBlock">
                                                    <option value="p">Normal text</option>
                                                    <option value="h1">Heading 1</option>
                                                    <option value="h2">Heading 2</option>
                                                    <option value="h3">Heading 3</option>
                                                </select>

                                                <button data-cmd="bold"><b>Bold</b></button>
                                                <button data-cmd="italic"><i>Italic</i></button>
                                                <button data-cmd="underline"><u>Underline</u></button>
                                                <button data-cmd="small"><small>Small</small></button>

                                                <button data-cmd="formatBlock" data-value="blockquote">❝</button>
                                                <button data-cmd="insertUnorderedList">• List</button>
                                                <button data-cmd="insertOrderedList">1. List</button>

                                                <button data-cmd="justifyLeft">⬅</button>
                                                <button data-cmd="justifyCenter">⬍</button>
                                                <button data-cmd="justifyRight">➡</button>

                                                <button data-cmd="removeFormat">↺</button>
                                            </div>

                                            <textarea id="editor" contenteditable="true" class="editor-area w-100" name="header_note"></textarea>
                                            <hr>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="finding_type" class="form-label">Finding
                                                        Category</label>
                                                    <select class="form-select select2-input" name="finding_type[]"
                                                        id="finding_type" multiple>
                                                        <option value="1">General Examination</option>
                                                        <option value="2">Vitals</option>
                                                        <option value="3">Cardiovascular System</option>
                                                        <option value="4">Gynecological</option>
                                                        <option value="5">ENT / Oral Cavity</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="filterinput" class="form-label">
                                                        Finding List</label>
                                                    <select class="form-control select2-input" name="findings[]"
                                                        id="finding" multiple>
                                                        <option value="1">General Examination</option>
                                                        <option value="2">Vitals</option>
                                                        <option value="3">Cardiovascular System</option>
                                                        <option value="4">Gynecological</option>
                                                        <option value="5">ENT / Oral Cavity</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="finding_description" class="form-label">Finding
                                                        Description</label>
                                                    <textarea name="finding_description" id="finding_description" class="form-control" rows="3"></textarea>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="finding_print" class="form-label">Finding Print
                                                    </label><br><input type="checkbox" name="finding_print"
                                                        id="finding_print" rows="15" value="yes"
                                                        checked="">
                                                </div>
                                            </div>

                                        </div>

                                        <div id="medicineContainer">
                                            <div class="medicine-row row mt-3" data-row="1" id="row1">
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Medicine Category</label>
                                                        <select class="form-control medicine_category"
                                                            style="width:100%" name="medicine_categories[]"
                                                            data-select2-initialized="false">
                                                            <option value="">Select</option>
                                                            @foreach ($medicineCategories as $category)
                                                                <option value="{{ $category->id }}">
                                                                    {{ $category->medicine_category }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Medicine</label>
                                                        <select class="form-control select2 medicine_name"
                                                            data-rowid="1" style="width:100%" name="medicines[]">
                                                            <option value="">Select</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Dose</label>
                                                        <select class="form-control select2 medicine_dosage"
                                                            style="width:100%" name="dosages[]">
                                                            <option value="">Select</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Dose Interval</label>
                                                        <select class="form-control select2 interval_dosage"
                                                            id="interval_dosage_1" name="interval_dosages[]"
                                                            style="width:100%">
                                                            <option value="">Select</option>
                                                            @foreach ($doseIntervals as $interval)
                                                                <option value="{{ $interval->id }}">
                                                                    {{ $interval->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Dose Duration</label>
                                                        <select class="form-control select2 duration_dosage"
                                                            id="duration_dosage_1" name="duration_dosages[]"
                                                            style="width:100%">
                                                            <option value="">Select</option>
                                                            @foreach ($doseDurations as $duration)
                                                                <option value="{{ $duration->id }}">
                                                                    {{ $duration->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-1 col-sm-1 col-xs-1">
                                                    <div>
                                                        <label class="form-label">Instruction</label>
                                                        <textarea name="instructions[]" style="height:28px;" class="form-control"></textarea>
                                                    </div>
                                                </div>

                                                <div
                                                    class="col-lg-1 col-md-1 col-sm-1 col-xs-1 d-flex align-items-center">
                                                    <div>
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger delete_row" data-row-id="1"
                                                            autocomplete="off"><i class="fa fa-remove"></i></button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6 mt-2" id="addMedicineContainer">
                                                <a class="btn btn-primary btn-sm add-record" data-added="0"
                                                    id="addMedicineBtn"><i class="fa fa-plus"></i> Add Medicine</a>

                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group mt-2">
                                            <label class="form-label">Advice</label>


                                            <textarea id="advice" name="advice" contenteditable="true" class="editor-area w-100" name="header_note"></textarea>
                                            <hr>
                                        </div>
                                        <div class="col-sm-12 mt-2">
                                            <label class="form-label">Attachment</label>
                                            <input type="file" data-height="30" class="filestyle form-control"
                                                name="document" autocomplete="off">
                                            <hr>
                                        </div>
                                        <div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="form-label">follow Up Advice</label>
                                                <div class="toolbar" data-editor="footer" id="toolbar-footer">
                                                    <select id="formatBlock-footer">
                                                        <option value="p">Normal text</option>
                                                        <option value="h1">Heading 1</option>
                                                        <option value="h2">Heading 2</option>
                                                        <option value="h3">Heading 3</option>
                                                    </select>
                                                    <button data-cmd="bold"><b>Bold</b></button>
                                                    <button data-cmd="italic"><i>Italic</i></button>
                                                    <button data-cmd="underline"><u>Underline</u></button>
                                                    <button data-cmd="small"><small>Small</small></button>
                                                    <button data-cmd="formatBlock" data-value="blockquote">❝</button>
                                                    <button data-cmd="insertUnorderedList">• List</button>
                                                    <button data-cmd="insertOrderedList">1. List</button>
                                                    <button data-cmd="justifyLeft">⬅</button>
                                                    <button data-cmd="justifyCenter">⬍</button>
                                                    <button data-cmd="justifyRight">➡</button>
                                                    <button data-cmd="removeFormat">↺</button>
                                                </div>
                                                <textarea id="editor-footer" contenteditable="true" class="editor-area w-100" name="footer_note">
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="row gy-3">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Pathology Tests</label>
                                        <select class="form-control select2-input" name="pathology[]"
                                            id="pathologyOpt" multiple style="width: 100%;">
                                            <option value="">Select Tests</option>
                                            @if (isset($pathologies) && count($pathologies) > 0)
                                                @foreach ($pathologies as $pathology)
                                                    <option value="{{ $pathology->id }}">
                                                        {{ $pathology->test_name }}{{ $pathology->short_name ? ' (' . $pathology->short_name . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Radiology Tests</label>
                                        <select class="form-control select2-input" name="radiology[]"
                                            id="radiologyOpt" multiple style="width: 100%;">
                                            <option value="">Select Tests</option>
                                            @if (isset($radiologies) && count($radiologies) > 0)
                                                @foreach ($radiologies as $radiology)
                                                    <option value="{{ $radiology->id }}">
                                                        {{ $radiology->test_name }}{{ $radiology->short_name ? ' (' . $radiology->short_name . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Prescribe By <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2-input" style="width: 100%"
                                            name="prescribe_by" id="prescribe_by" required>
                                            <option value="">Select Doctor</option>
                                            @php
                                                $doctors = \App\Models\Doctor::all();
                                            @endphp
                                            @foreach ($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}
                                                    ({{ $doctor->doctor_id ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <div class="ptt10">
                                        <label for="exampleInputEmail1" class="form-label">Notification To</label>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="1">
                                                <b>Admin</b>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="2">
                                                <b>Accountant</b>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="3">
                                                <b>Doctor</b>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="4">
                                                <b>Pharmacist</b>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="5">
                                                <b>Pathologist</b>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="6">
                                                <b>Radiologist</b>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="7" checked="" onclick="return false;"> <b>Super
                                                    Admin</b> </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="8">
                                                <b>Receptionist</b>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="9">
                                                <b>Nurse</b>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label class="form-label"><input type="checkbox" name="visible[]"
                                                    value="10">
                                                <b>Clinical
                                                    staff</b>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer (moved outside modal-body so footer stays fixed) -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Save & Print
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function initSelect2InModal(context) {
        $(context).find('.select2').select2({
            placeholder: 'Select',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addPrescriptionModal')
        });
    }
    $('#addPrescriptionModal').on('shown.bs.modal', function() {
        initSelect2InModal(this);
    });
    const getMedicines = "{{ route('getMedicines', ':id') }}";
    const getDoses = "{{ route('getDoses', ':id') }}";
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
                        `<option value="${item.medicine_category_id}">${item.medicine_name}</option>`
                    );
                });
                medicineSelect.trigger('change.select2');
            }
        });
    });
    $(document).on('change', '.medicine_name', function() {
        let row = $(this).closest('.medicine-row');
        let medicineId = $(this).val();
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
                dosageSelect.trigger('change.select2');
            }
        });
    });
    $(document).ready(function() {

        let rowCount = 1;

        $('#addMedicineBtn').on('click', function() {
            rowCount++;

            let newRow = `
    <div class="medicine-row row mt-3" data-row="${rowCount}" id="row${rowCount}">
        <div class="col-lg-3">
            <label class="form-label">Medicine Category</label>
            <select class="form-control select2 medicine_category" name="medicine_categories[]">
                <option value="">Select</option>
                @foreach ($medicineCategories as $category)
                <option value="{{ $category->id }}">{{ $category->medicine_category }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3">
            <label class="form-label">Medicine</label>
            <select class="form-control select2 medicine_name" name="medicines[]">
                <option value="">Select</option>
            </select>
        </div>

        <div class="col-lg-3">
            <label class="form-label">Dose</label>
            <select class="form-control select2 medicine_dosage" name="dosages[]">
                <option value="">Select</option>
            </select>
        </div>

        <div class="col-lg-3">
            <label class="form-label">Dose Interval</label>
            <select class="form-control select2" name="interval_dosages[]">
                <option value="">Select</option>
                @foreach ($doseIntervals as $interval)
                <option value="{{ $interval->id }}">
                {{ $interval->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3">
            <label class="form-label">Dose Duration</label>
            <select class="form-control select2" name="duration_dosages[]">
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
            <button type="button" class="btn btn-sm btn-danger delete_row">
                <i class="fa fa-remove"></i>
            </button>
        </div>
    </div>`;

            $('#addMedicineContainer').before(newRow);

            initSelect2InModal(`#row${rowCount}`);
        });
    });
</script>
