<style>
    /* Ensure Add Prescription modal displays correctly - critical for overlays */
    #addPrescriptionModal.modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1060 !important;
        width: 100% !important;
        height: 100% !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
    }
    #addPrescriptionModal.modal.show {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }
    .modal-backdrop.show {
        opacity: 0.6;
    }

    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        overflow: visible;
    }
        /* Medicine Category & Medicine - compact font size */
    #addPrescriptionModal .medicine-row .select2-container,
    #addPrescriptionModal .medicine-row .select2-container .select2-selection__rendered,
    #addPrescriptionModal .medicine-row .select2-container .select2-selection--single {
        font-size: 0.8125rem !important;
    }
    #addPrescriptionModal .select2-dropdown .select2-results__option,
    #addPrescriptionModal .select2-dropdown .select2-search__field,
    #addPrescriptionModal .select2-dropdown .select2-results__message {
        font-size: 0.8125rem !important;
    }

        /* Ensure Select2 dropdown is visible - pathology/radiology appended to body */
    #addPrescriptionModal .select2-container { z-index: 1060 !important; }
    #addPrescriptionModal .select2-container--open { z-index: 1061 !important; }
    .pathology-radiology-dropdown,
    .select2-dropdown.pathology-radiology-dropdown,
    body .select2-dropdown,
    body > .select2-container--open + .select2-dropdown { z-index: 99999 !important; }
    .pathology-radiology-dropdown .select2-results__options { max-height: 280px !important; overflow-y: auto !important; }
    .pathology-radiology-dropdown .select2-results__options,
    .select2-container--default .select2-results__options,
    .pathology-radiology-dropdown ul.select2-results__options { max-height: 300px !important; overflow-y: auto !important; display: block !important; }
    .pathology-radiology-dropdown { min-width: 280px !important; }
    .pathology-radiology-dropdown .select2-results__list,
    .pathology-radiology-dropdown ul.select2-results__options { overflow-y: auto !important; max-height: 280px !important; }
    #addPrescriptionModal .modal-body { overflow-x: visible; overflow-y: auto; }
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
    .pathology-selected-list .selected-test-row .test-name-badge { font-weight: 600; color: #333; }
    .pathology-selected-list .selected-test-row .instance-badge,
    .radiology-selected-list .selected-test-row .instance-badge {
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 4px;
        background: #75009633;
        color: #750096;
    }
    .pathology-selected-list .selected-test-row .instance-badge.instance-2 { background: #ffc10733; color: #856404; }
    .pathology-selected-list .selected-test-row .instance-badge.instance-3plus { background: #fd7e1433; color: #a13b00; }
    .radiology-selected-list .selected-test-row .instance-badge.instance-2 { background: #ffc10733; color: #856404; }
    .radiology-selected-list .selected-test-row .instance-badge.instance-3plus { background: #fd7e1433; color: #a13b00; }
    .pathology-selected-list .selected-test-row input.notes-input,
    .radiology-selected-list .selected-test-row input.notes-input {
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
{{-- IPD Add Prescription modal: id="addPrescriptionModal" must be unique app-wide (only this div). Do not reuse this id in other modals. See .cursor/rules/add-prescription-modal.mdc --}}
<div class="modal fade" id="addPrescriptionModal" tabindex="-1" aria-labelledby="addPrescriptionModalLabel"
    aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-xl ">
        <!-- inline overflow override so .modal-content {overflow: hidden;} in global CSS doesn't block scrolling -->
        <div class="modal-content" style="overflow: visible;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="addPrescriptionModalLabel">
                    <div class="section-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    Add Prescription
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (scrollable area provided by modal-dialog-scrollable) -->
            <form action="{{ route('ipd.addPrescription') }}" id="ipdAddPrescriptionForm" method="post" enctype="multipart/form-data">@csrf
                <div class="modal-body" style="max-height: calc(100vh - 160px); overflow-x: visible; overflow-y: auto;">
                    <div class="row p-4 mx-1">
                        <div class="col-sm-9">
                            <div class="ptt10">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" id="opd_id" name="opd_id" value="{{ isset($opd) ? $opd->id : '' }}">
                                        <input type="hidden" id="ipd_id" name="ipd_id" value="{{ isset($ipd) ? $ipd->id : '' }}">
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
                                                    <select class="form-select multiselect2"
                                                        name="finding_type[]" id="finding_type" multiple>
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
                                                    <select class="form-control multiselect2"
                                                        name="findings[]" id="finding" multiple>
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
                                                        id="finding_print" rows="15" value="yes" checked="">
                                                </div>
                                            </div>

                                        </div>

                                        <div id="medicineContainer">
                                            <div class="medicine-row row mt-3" data-row="1" id="row1">
                                                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Medicine Category</label>
                                                        <select class="form-control medicine_category"
                                                            style="width:100%" name="medicine_categories[]" data-select2-initialized="false">
                                                            <option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                                    <div>
                                                        <label class="form-label">Medicine</label>
                                                        <select class="form-control select2 medicine_name"
                                                            data-rowid="1" style="width:100%" name="medicines[]">
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
                                        <select class="form-control multiselect2 pathology-test-select" id="pathologyOpt" multiple style="width: 100%;" data-name="pathology_select">
                                            <option value="">Select Tests</option>
                                            @if(isset($pathologies) && count($pathologies) > 0)
                                                @foreach($pathologies as $pathology)
                                                    <option value="{{ $pathology->id }}">
                                                        {{ $pathology->test_name }}{{ $pathology->short_name ? ' (' . $pathology->short_name . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <small class="text-muted d-block mt-1">Select tests below; use &quot;Add Again&quot; for same test multiple times today.</small>
                                        <div id="pathologySelectedList" class="mt-2 pathology-selected-list" style="min-height: 24px;"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Radiology Tests</label>
                                        <select class="form-control multiselect2 radiology-test-select" id="radiologyOpt" multiple style="width: 100%;" data-name="radiology_select">
                                            <option value="">Select Tests</option>
                                            @if(isset($radiologies) && count($radiologies) > 0)
                                                @foreach($radiologies as $radiology)
                                                    <option value="{{ $radiology->id }}">
                                                        {{ $radiology->test_name }}{{ $radiology->short_name ? ' (' . $radiology->short_name . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <small class="text-muted d-block mt-1">Select tests below; use &quot;Add Again&quot; for same test multiple times today.</small>
                                        <div id="radiologySelectedList" class="mt-2 radiology-selected-list" style="min-height: 24px;"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Prescribe By <span class="text-danger">*</span></label>
                                        {{-- <select class="form-control select2" style="width: 100%" name="prescribe_by" id="prescribe_by" required>
                                            <option value="">Select Doctor</option>
                                            @php
                                                $doctors = \App\Models\Doctor::all();
                                            @endphp
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->doctor_id ?? 'N/A' }})</option>
                                            @endforeach
                                        </select> --}}
                                          <select class="form-control" style="width: 100%" name="prescribe_by" id="prescribe_by" required>
                                              <option value="">Select Doctor</option>
                                              @php
                                                  $doctors = \App\Models\Doctor::all();
                                              @endphp 
                                              {{-- @foreach($doctors as $doctor)
                                                  <option value="{{ $doctor->id }}">
                                                      {{ $doctor->name }} {{ $doctor->surname }} ({{ $doctor->doctor_id ?? 'N/A' }})
                                                  </option>
                                              @endforeach --}}
                                          </select>
    <!-- Ensure Select2 JS and CSS are loaded in your layout or before this script -->
   <script>
   
document.addEventListener('DOMContentLoaded', function () {

    const doctordata = @json(
        $doctors
    );

    new TomSelect('#prescribe_by', {
        options: doctordata.map(doc => ({
            value: doc.id,
            label: `${doc.name} ${doc.surname} (${doc.doctor_id ?? 'N/A'})`
        })),
        valueField: 'value',
        labelField: 'label',
        searchField: 'label',
        create: false,
        persist: false,
        placeholder: 'Select doctors'
    });

});
</script>
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
                        <div class="col-md-4">
                            <label class="form-label">Prescription Date</label>
                            <input type="date" name="date" id="prescription_date" class="form-control" value="">
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
(function() {
    // CRITICAL: Register submit handler IMMEDIATELY to prevent native form submission
    var opdRoute = "{{ route('opd.addPrescription') }}";
    var ipdRoute = "{{ route('ipd.addPrescription') }}";
    
    // Define delegator immediately (outside DOMContentLoaded) so it's always available
    window.__prescriptionSubmitContinue = function(e, form, opdRoute, ipdRoute) {
        console.log('🔴 __prescriptionSubmitContinue called');
        if (typeof window.__prescriptionSubmitHandlerReal === 'function') {
            console.log('🔴 Calling __prescriptionSubmitHandlerReal');
            window.__prescriptionSubmitHandlerReal(e, form, opdRoute, ipdRoute);
            return;
        }
        console.error('🔴 Prescription submit: full handler not loaded yet');
        alert('Form is loading. Please try again in a moment.');
    };
    
    // Full submit handler: defined OUTSIDE DOMContentLoaded so it's always available
    function prescriptionSubmitHandlerReal(e, form, opdRoute, ipdRoute) {
        console.log('🔴 prescriptionSubmitHandlerReal STARTED');
        console.log('🔴 Form:', form ? form.id : 'NO FORM');
        console.log('🔴 Form action:', form ? form.action : 'NO ACTION');
        
        // Sync selected pathology/radiology lists to form (hidden inputs)
        if (typeof window.syncPrescriptionTestListsToForm === 'function') {
            console.log('🔴 Calling syncPrescriptionTestListsToForm');
            window.syncPrescriptionTestListsToForm(form);
        } else {
            console.warn('🔴 syncPrescriptionTestListsToForm not found');
        }
        
        // CRITICAL: Read pathology/radiology from selects BEFORE destroying Select2
        var pathOpt = document.getElementById('pathologyOpt');
        var radOpt = document.getElementById('radiologyOpt');
        var pathologyIdsFromSelect = [];
        var radiologyIdsFromSelect = [];
        
        // Read from Select2 if active, otherwise from native select
        if (pathOpt) {
            if (window.jQuery && $(pathOpt).hasClass('select2-hidden-accessible')) {
                var v = $(pathOpt).val();
                if (v) pathologyIdsFromSelect = Array.isArray(v) ? v : [v];
                console.log('🔴 Pathology Select2 value:', v, '-> IDs:', pathologyIdsFromSelect);
            } else if (pathOpt.selectedOptions && pathOpt.selectedOptions.length) {
                pathologyIdsFromSelect = Array.from(pathOpt.selectedOptions).map(function(o) { return o.value; }).filter(Boolean);
                console.log('🔴 Pathology native selectedOptions:', pathOpt.selectedOptions.length, '-> IDs:', pathologyIdsFromSelect);
            }
        }
        
        if (radOpt) {
            if (window.jQuery && $(radOpt).hasClass('select2-hidden-accessible')) {
                var v = $(radOpt).val();
                if (v) radiologyIdsFromSelect = Array.isArray(v) ? v : [v];
                console.log('🔴 Radiology Select2 value:', v, '-> IDs:', radiologyIdsFromSelect);
            } else if (radOpt.selectedOptions && radOpt.selectedOptions.length) {
                radiologyIdsFromSelect = Array.from(radOpt.selectedOptions).map(function(o) { return o.value; }).filter(Boolean);
                console.log('🔴 Radiology native selectedOptions:', radOpt.selectedOptions.length, '-> IDs:', radiologyIdsFromSelect);
            }
        }
        
        // Ensure lists are populated (from selects if empty)
        if (!window.selectedPathologyList || window.selectedPathologyList.length === 0) {
            window.selectedPathologyList = [];
            pathologyIdsFromSelect.forEach(function(id) {
                var name = 'ID ' + id;
                if (pathOpt) pathOpt.querySelectorAll('option').forEach(function(o) { if (String(o.value) == String(id)) name = (o.text || '').trim() || name; });
                window.selectedPathologyList.push({ id: String(id), name: name, notes: '' });
            });
            console.log('🔴 Populated selectedPathologyList from select:', window.selectedPathologyList);
        }
        
        if (!window.selectedRadiologyList || window.selectedRadiologyList.length === 0) {
            window.selectedRadiologyList = [];
            radiologyIdsFromSelect.forEach(function(id) {
                var name = 'ID ' + id;
                if (radOpt) radOpt.querySelectorAll('option').forEach(function(o) { if (String(o.value) == String(id)) name = (o.text || '').trim() || name; });
                window.selectedRadiologyList.push({ id: String(id), name: name, notes: '' });
            });
            console.log('🔴 Populated selectedRadiologyList from select:', window.selectedRadiologyList);
        }
        
        console.log('🔴 Final selectedPathologyList:', window.selectedPathologyList);
        console.log('🔴 Final selectedRadiologyList:', window.selectedRadiologyList);
        
        // Sync Select2 values to native selects before reading form data
        if (window.jQuery && $.fn.select2) {
            const allSelect2Selects = document.querySelectorAll('select.select2, select.multiselect2, select.medicine_category, select.medicine_name, select.medicine_dosage, select.interval_dosage, select.duration_dosage, select[name="finding_type[]"], select[name="findings[]"]');
            allSelect2Selects.forEach(select => {
                if ($(select).hasClass('select2-hidden-accessible')) {
                    try {
                        const select2Value = $(select).val();
                        if (select2Value !== null && select2Value !== undefined) {
                            if (Array.isArray(select2Value)) {
                                // For multi-select, set all selected values
                                $(select).val(select2Value);
                            } else {
                                // For single select
                                select.value = String(select2Value);
                            }
                        }
                    } catch(e) {
                        console.warn('Error syncing Select2:', e);
                    }
                }
            });
        }
        
        // Now build FormData manually
        const formData = new FormData();
        
        // Add CSRF token first
        const csrfToken = document.querySelector('input[name="_token"]')?.value ||
                         document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            formData.append('_token', String(csrfToken));
        }
        
        // Add pathology and radiology from lists to FormData
        (window.selectedPathologyList || []).forEach(function(item) {
            formData.append('pathology[]', String(item.id));
            formData.append('pathology_notes[]', String(item.notes || ''));
            console.log('🔴 FormData.append pathology[]:', item.id, 'notes:', item.notes);
        });
        (window.selectedRadiologyList || []).forEach(function(item) {
            formData.append('radiology[]', String(item.id));
            formData.append('radiology_notes[]', String(item.notes || ''));
            console.log('🔴 FormData.append radiology[]:', item.id, 'notes:', item.notes);
        });
        
        console.log('🔴 Total pathology items added to FormData:', (window.selectedPathologyList || []).length);
        console.log('🔴 Total radiology items added to FormData:', (window.selectedRadiologyList || []).length);
        
        // CRITICAL: Always include prescription date for back-dated support
        // Read from form's date input (use #prescription_date to avoid picking wrong 'date' input elsewhere)
        const prescriptionDateEl = form.querySelector('#prescription_date') || form.querySelector('input[name="date"]');
        let dateToSend = prescriptionDateEl ? (prescriptionDateEl.value || '').trim() : '';
        if (!dateToSend && form.querySelector('[name="ipd_id"]')) {
            const ipdIdVal = ((form.querySelector('[name="ipd_id"]') || {}).value || '').trim();
            if (ipdIdVal) {
                const btn = document.querySelector('[data-bs-target="#addPrescriptionModal"][data-ipd-id="' + ipdIdVal + '"]');
                if (btn && btn.getAttribute('data-admission-date')) {
                    dateToSend = btn.getAttribute('data-admission-date');
                    console.log('🔴 Using admission date from button for empty date field:', dateToSend);
                } else {
                    const ctx = document.getElementById('ipdViewContext');
                    if (ctx && ctx.getAttribute('data-ipd-id') === ipdIdVal && ctx.getAttribute('data-admission-date')) {
                        dateToSend = ctx.getAttribute('data-admission-date');
                        console.log('🔴 Using admission date from ipdViewContext for empty date field:', dateToSend);
                    }
                }
            }
        }
        if (!dateToSend) {
            dateToSend = new Date().toISOString().split('T')[0];
        }
        formData.append('date', dateToSend);
        console.log('🔴 Prescription date appended to FormData:', dateToSend);
        
        // Add all other form fields
        const formElements = form.elements;
        for (let i = 0; i < formElements.length; i++) {
            const element = formElements[i];
            const name = element.name;
            
            if (!name) continue;
            if (element.type === 'submit' || element.type === 'button') continue;
            if (name === '_token') continue;
            if (name === 'date') continue; // Handled explicitly above for back-dated support
            if (name === 'pathology[]' || name === 'pathology_notes[]' || name === 'radiology[]' || name === 'radiology_notes[]') continue;
            
            // Handle multi-select fields (finding_type[], findings[], etc.)
            if (element.tagName === 'SELECT' && element.hasAttribute('multiple')) {
                let selectedValues = [];
                
                // Check if Select2 is active and get values from it
                if (window.jQuery && $(element).hasClass('select2-hidden-accessible')) {
                    const select2Val = $(element).val();
                    if (select2Val !== null && select2Val !== undefined) {
                        selectedValues = Array.isArray(select2Val) ? select2Val : [select2Val];
                        // Also sync to native select
                        $(element).val(selectedValues);
                    }
                } else {
                    // Get from native select
                    const selectedOptions = Array.from(element.selectedOptions);
                    selectedValues = selectedOptions.map(opt => opt.value).filter(val => val !== null && val !== undefined && val !== '');
                }
                
                // Append each selected value (filter out empty/null values and ensure strings)
                selectedValues.forEach(val => {
                    const stringVal = String(val || '').trim();
                    if (stringVal !== '' && stringVal !== 'null' && stringVal !== 'undefined') {
                        // Use the name as-is (e.g., "finding_type[]") - Laravel will parse it correctly
                        formData.append(name, stringVal);
                        console.log('🔴 Added multi-select value:', name, '=', stringVal);
                    }
                });
            } else if (element.type === 'checkbox' || element.type === 'radio') {
                if (element.checked) {
                    formData.append(name, String(element.value || '1'));
                }
            } else if (element.type === 'file') {
                if (element.files && element.files.length > 0) {
                    for (let j = 0; j < element.files.length; j++) {
                        formData.append(name, element.files[j]);
                    }
                }
            } else {
                const val = element.value;
                if (val !== null && val !== undefined && val !== '') {
                    formData.append(name, String(val));
                }
            }
        }
        
        // Submit via fetch
        console.log('🔴 About to fetch to:', form.action);
        fetch(form.action, {
            method: form.method || 'POST',
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
            if (window.jQuery && $('#addPrescriptionModal').length) {
                $('#addPrescriptionModal').modal('hide');
            }
            // Show success notification
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message || 'Prescription created successfully.',
                    confirmButtonColor: '#7c3aed',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            } else {
                // Fallback if SweetAlert2 is not available
                alert(data.message || 'Prescription created successfully!');
                location.reload();
            }
        })
        .catch(error => {
            console.error('🔴 Error:', error);
            alert('Something went wrong: ' + error.message);
        });
    }
    
    // Assign handler immediately so it's available when capture-phase listener runs
    window.__prescriptionSubmitHandlerReal = prescriptionSubmitHandlerReal;
    
    function registerPrescriptionSubmitHandler() {
        document.addEventListener('submit', function prescriptionSubmitHandler(e) {
            var form = e.target;
            console.log('🔴 SUBMIT EVENT CAPTURED - Form ID:', form ? form.id : 'NO FORM', 'Tag:', form ? form.tagName : 'N/A');
            if (!form || form.id !== 'ipdAddPrescriptionForm') {
                console.log('🔴 Not our form, ignoring. Form ID:', form ? form.id : 'NO FORM');
                return;
            }
            console.log('🔴 OUR FORM DETECTED - Preventing default and calling handler');
            e.preventDefault();
            e.stopPropagation();
            console.log('🔴 Add prescription form submit intercepted (JS handler running)');
            window.__prescriptionSubmitContinue(e, form, opdRoute, ipdRoute);
        }, true);
        console.log('🔴 Prescription form submit listener registered (capture phase)');
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', registerPrescriptionSubmitHandler);
    } else {
        registerPrescriptionSubmitHandler();
    }
    
    // Selected pathology/radiology lists for "Add Again" and per-instance notes (Phase 2 multiple test instances)
    window.selectedPathologyList = window.selectedPathologyList || [];
    window.selectedRadiologyList = window.selectedRadiologyList || [];

    function instanceLabel(indexOneBased) {
        if (indexOneBased === 1) return '1st time today';
        if (indexOneBased === 2) return '2nd time today';
        if (indexOneBased === 3) return '3rd time today';
        return indexOneBased + 'th time today';
    }

    function instanceBadgeClass(indexOneBased) {
        if (indexOneBased === 1) return 'instance-badge';
        if (indexOneBased === 2) return 'instance-badge instance-2';
        return 'instance-badge instance-3plus';
    }

    function renderPathologyList() {
        var container = document.getElementById('pathologySelectedList');
        if (!container) return;
        container.innerHTML = '';
        var byId = {};
        window.selectedPathologyList.forEach(function(item) {
            byId[item.id] = (byId[item.id] || 0) + 1;
        });
        var currentCount = {};
        window.selectedPathologyList.forEach(function(item, idx) {
            currentCount[item.id] = (currentCount[item.id] || 0) + 1;
            var row = document.createElement('div');
            row.className = 'selected-test-row';
            row.setAttribute('data-idx', idx);
            var badge = document.createElement('span');
            badge.className = 'test-name-badge';
            badge.textContent = item.name;
            var inst = document.createElement('span');
            inst.className = instanceBadgeClass(currentCount[item.id]);
            inst.textContent = instanceLabel(currentCount[item.id]);
            var notes = document.createElement('input');
            notes.type = 'text';
            notes.className = 'notes-input';
            notes.placeholder = 'Notes (optional)';
            notes.value = item.notes || '';
            notes.setAttribute('data-idx', idx);
            notes.addEventListener('input', function() { window.selectedPathologyList[idx].notes = this.value; });
            var addAgain = document.createElement('button');
            addAgain.type = 'button';
            addAgain.className = 'btn-add-again';
            addAgain.textContent = 'Add Again';
            addAgain.setAttribute('data-idx', idx);
            var remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'btn-remove-test';
            remove.textContent = 'Remove';
            remove.setAttribute('data-idx', idx);
            row.appendChild(badge);
            row.appendChild(inst);
            row.appendChild(notes);
            row.appendChild(addAgain);
            row.appendChild(remove);
            container.appendChild(row);
        });
    }

    function renderRadiologyList() {
        var container = document.getElementById('radiologySelectedList');
        if (!container) return;
        container.innerHTML = '';
        var currentCount = {};
        window.selectedRadiologyList.forEach(function(item, idx) {
            currentCount[item.id] = (currentCount[item.id] || 0) + 1;
            var row = document.createElement('div');
            row.className = 'selected-test-row';
            row.setAttribute('data-idx', idx);
            var badge = document.createElement('span');
            badge.className = 'test-name-badge';
            badge.textContent = item.name;
            var inst = document.createElement('span');
            inst.className = instanceBadgeClass(currentCount[item.id]);
            inst.textContent = instanceLabel(currentCount[item.id]);
            var notes = document.createElement('input');
            notes.type = 'text';
            notes.className = 'notes-input';
            notes.placeholder = 'Notes (optional)';
            notes.value = item.notes || '';
            notes.setAttribute('data-idx', idx);
            notes.addEventListener('input', function() { window.selectedRadiologyList[idx].notes = this.value; });
            var addAgain = document.createElement('button');
            addAgain.type = 'button';
            addAgain.className = 'btn-add-again';
            addAgain.textContent = 'Add Again';
            addAgain.setAttribute('data-idx', idx);
            var remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'btn-remove-test';
            remove.textContent = 'Remove';
            remove.setAttribute('data-idx', idx);
            row.appendChild(badge);
            row.appendChild(inst);
            row.appendChild(notes);
            row.appendChild(addAgain);
            row.appendChild(remove);
            container.appendChild(row);
        });
    }

    window.syncPrescriptionTestListsToForm = function(form) {
        if (!form) form = document.getElementById('ipdAddPrescriptionForm');
        if (!form) return;
        var existing = form.querySelectorAll('input[name="pathology[]"], input[name="pathology_notes[]"], input[name="radiology[]"], input[name="radiology_notes[]"]');
        existing.forEach(function(el) { el.remove(); });
        window.selectedPathologyList.forEach(function(item) {
            var i = document.createElement('input');
            i.type = 'hidden';
            i.name = 'pathology[]';
            i.value = item.id;
            form.appendChild(i);
            var n = document.createElement('input');
            n.type = 'hidden';
            n.name = 'pathology_notes[]';
            n.value = item.notes || '';
            form.appendChild(n);
        });
        window.selectedRadiologyList.forEach(function(item) {
            var i = document.createElement('input');
            i.type = 'hidden';
            i.name = 'radiology[]';
            i.value = item.id;
            form.appendChild(i);
            var n = document.createElement('input');
            n.type = 'hidden';
            n.name = 'radiology_notes[]';
            n.value = item.notes || '';
            form.appendChild(n);
        });
    };

    function wirePrescriptionTestLists() {
        var $ = window.jQuery;
        if (!$) return;
        $(document).off('select2:select.prescriptionTests', '#pathologyOpt').on('select2:select.prescriptionTests', '#pathologyOpt', function(e) {
            var data = e.params.data;
            if (data && data.id) {
                var count = window.selectedPathologyList.filter(function(x) { return x.id == data.id; }).length;
                if (count >= 3 && !confirm('This test is already added 3 times today. Add again?')) {
                    $(this).val(null).trigger('change');
                    return;
                }
                window.selectedPathologyList.push({ id: data.id, name: data.text || ('ID ' + data.id), notes: '' });
                renderPathologyList();
                $(this).val(null).trigger('change');
            }
        });
        $(document).off('select2:select.prescriptionTests', '#radiologyOpt').on('select2:select.prescriptionTests', '#radiologyOpt', function(e) {
            var data = e.params.data;
            if (data && data.id) {
                var count = window.selectedRadiologyList.filter(function(x) { return x.id == data.id; }).length;
                if (count >= 3 && !confirm('This test is already added 3 times today. Add again?')) {
                    $(this).val(null).trigger('change');
                    return;
                }
                window.selectedRadiologyList.push({ id: data.id, name: data.text || ('ID ' + data.id), notes: '' });
                renderRadiologyList();
                $(this).val(null).trigger('change');
            }
        });
        $(document).off('click.prescriptionTests', '#pathologySelectedList').on('click.prescriptionTests', '#pathologySelectedList', function(e) {
            var             btn = e.target.closest('.btn-add-again');
            if (btn) {
                var idx = parseInt(btn.getAttribute('data-idx'), 10);
                var item = window.selectedPathologyList[idx];
                if (item) {
                    var count = window.selectedPathologyList.filter(function(x) { return x.id == item.id; }).length;
                    if (count >= 3 && !confirm('This test is already added 3 times today. Add again?')) return;
                    window.selectedPathologyList.push({ id: item.id, name: item.name, notes: '' });
                    renderPathologyList();
                }
                e.preventDefault();
                return false;
            }
            btn = e.target.closest('.btn-remove-test');
            if (btn) {
                var idx = parseInt(btn.getAttribute('data-idx'), 10);
                window.selectedPathologyList.splice(idx, 1);
                renderPathologyList();
                e.preventDefault();
                return false;
            }
        });
        $(document).off('click.prescriptionTests', '#radiologySelectedList').on('click.prescriptionTests', '#radiologySelectedList', function(e) {
            var btn = e.target.closest('.btn-add-again');
            if (btn) {
                var idx = parseInt(btn.getAttribute('data-idx'), 10);
                var item = window.selectedRadiologyList[idx];
                if (item) {
                    var count = window.selectedRadiologyList.filter(function(x) { return x.id == item.id; }).length;
                    if (count >= 3 && !confirm('This test is already added 3 times today. Add again?')) return;
                    window.selectedRadiologyList.push({ id: item.id, name: item.name, notes: '' });
                    renderRadiologyList();
                }
                e.preventDefault();
                return false;
            }
            btn = e.target.closest('.btn-remove-test');
            if (btn) {
                var idx = parseInt(btn.getAttribute('data-idx'), 10);
                window.selectedRadiologyList.splice(idx, 1);
                renderRadiologyList();
                e.preventDefault();
                return false;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderPathologyList();
        renderRadiologyList();
        setTimeout(wirePrescriptionTestLists, 500);
    });
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(function() {
            renderPathologyList();
            renderRadiologyList();
            wirePrescriptionTestLists();
        }, 300);
    }
})();
</script>
{{-- Old prescription data in data attribute to avoid @json() breaking script if content has special chars --}}
<div id="addPrescriptionModalOldData" data-old-prescription="{{ e(json_encode(['finding_type' => old('finding_type[]', []), 'finding' => old('finding[]', []), 'pathology' => old('pathology[]', []), 'radiology' => old('radiology[]', [])])) }}" style="display:none" aria-hidden="true"></div>
<script>
    (function() {
        var el = document.getElementById('addPrescriptionModalOldData');
        try {
            window.__oldPrescription = el && el.getAttribute('data-old-prescription') ? JSON.parse(el.getAttribute('data-old-prescription')) : { finding_type: [], finding: [], pathology: [], radiology: [] };
        } catch (e) {
            window.__oldPrescription = { finding_type: [], finding: [], pathology: [], radiology: [] };
        }
    })();
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOMContentLoaded fired for prescription modal');
        const createPrescriptionModal = document.getElementById("addPrescriptionModal");
        const findingCategorySelect = document.getElementById('finding_type');
        const findingsSelect = document.getElementById('finding');
        const pathologySelect = document.getElementById('pathologyOpt');
        const radiologySelect = document.getElementById('radiologyOpt');
        
        // Helper function to safely destroy Select2
        window.safeDestroySelect2 = function(element) {
            if (!element || !window.jQuery || !$.fn.select2) {
                return;
            }
            try {
                const $el = $(element);
                if ($el.hasClass('select2-hidden-accessible') || $el.data('select2')) {
                    $el.select2('destroy');
                }
            } catch (e) {
                // Silently ignore errors - element might not have Select2 initialized
                console.debug('Select2 destroy skipped:', e.message);
            }
        };
        
        // Override jQuery select2 destroy to be safe - prevent errors when destroying non-initialized Select2
        // This MUST run before any Select2 initialization
        if (window.jQuery && $.fn.select2) {
            const originalSelect2 = $.fn.select2;
            $.fn.select2 = function(method, options) {
                if (method === 'destroy') {
                    // Return early if element doesn't exist or isn't a jQuery object
                    if (!this || this.length === 0) {
                        return this || $();
                    }
                    
                    // Process each element in the jQuery collection
                    return this.each(function() {
                        const $el = $(this);
                        
                        // Skip if element doesn't exist or isn't in DOM
                        if (!$el.length || !$el[0] || !document.contains($el[0])) {
                            return;
                        }
                        
                        try {
                            // Check if Select2 is actually initialized before destroying
                            const isInitialized = $el.hasClass('select2-hidden-accessible') || $el.data('select2');
                            if (isInitialized) {
                                try {
                                    // Call original destroy on just this element
                                    originalSelect2.call($el, method);
                                } catch (e) {
                                    // If destroy fails, just remove the classes/data manually
                                    try {
                                        $el.removeClass('select2-hidden-accessible');
                                        $el.removeData('select2');
                                        // Also remove Select2 container if it exists
                                        const select2Container = $el.next('.select2-container');
                                        if (select2Container.length) {
                                            select2Container.remove();
                                        }
                                    } catch (cleanupError) {
                                        // Ignore cleanup errors
                                    }
                                }
                            }
                        } catch (e) {
                            // Silently ignore all errors - element might not have Select2 or might be invalid
                        }
                    });
                }
                // For all other methods, call original
                return originalSelect2.apply(this, arguments);
            };
        }
        
        // Define placeholder functions early to prevent "function not found" errors
        // These will be replaced by the actual implementations later
        // Placeholder for loadMedicinesOnModalOpen - will be replaced by real function
        if (!window.loadMedicinesOnModalOpen) {
            window.loadMedicinesOnModalOpen = function() {
                // Wait for real function to be defined
                let attempts = 0;
                const checkForRealFunction = setInterval(() => {
                    attempts++;
                    const currentFunc = window.loadMedicinesOnModalOpen;
                    const funcStr = currentFunc ? currentFunc.toString() : '';
                    // Check if real function is available (has medicineContainer and is longer)
                    if (funcStr.includes('medicineContainer') && funcStr.length > 1000) {
                        clearInterval(checkForRealFunction);
                        currentFunc();
                    } else if (attempts > 10) {
                        clearInterval(checkForRealFunction);
                        console.warn('loadMedicinesOnModalOpen real function not found after 10 attempts');
                    }
                }, 100);
            };
        }
        
        // Placeholder for initRow - will be replaced by real function
        if (!window.initRow) {
            window.initRow = function(row) {
                if (!row) return;
                // Wait for real function to be defined
                let attempts = 0;
                const checkForRealFunction = setInterval(() => {
                    attempts++;
                    const currentFunc = window.initRow;
                    const funcStr = currentFunc ? currentFunc.toString() : '';
                    // Check if real function is available (has "Row element not found" or is longer)
                    if ((funcStr.includes('Row element not found') || funcStr.includes('categorySelect')) && funcStr.length > 1000) {
                        clearInterval(checkForRealFunction);
                        currentFunc(row);
                    } else if (attempts > 10) {
                        clearInterval(checkForRealFunction);
                        // Fallback: populate category select manually
                        if (window.medicineCategories && Array.isArray(window.medicineCategories)) {
                            const categorySelect = row.querySelector('.medicine_category');
                            if (categorySelect && categorySelect.options.length <= 1) {
                                categorySelect.innerHTML = '<option value="">Select Category</option>';
                                window.medicineCategories.forEach(cat => {
                                    const opt = document.createElement('option');
                                    opt.value = cat.id;
                                    opt.textContent = cat.medicine_category || cat.name;
                                    categorySelect.appendChild(opt);
                                });
                            }
                        }
                    }
                }, 100);
            };
        }

        console.log('Elements found:', {
            modal: !!createPrescriptionModal,
            findingCategory: !!findingCategorySelect,
            findings: !!findingsSelect,
            pathology: !!pathologySelect,
            radiology: !!radiologySelect
        });

        // Old multiselect references removed - now using table-based selects

        if (findingCategorySelect) {
            findingCategorySelect.innerHTML = '<option value="">Loading...</option>';
        }
        if (findingsSelect) {
            findingsSelect.innerHTML = '<option value="">Loading...</option>';
        }

        // Do NOT clear pathology/radiology on page load - they will be loaded when modal opens (or keep server-rendered options)
        if (pathologySelect) {
            console.log('pathologyOpt element found, options:', pathologySelect.options.length);
        } else {
            console.error('pathologyOpt element NOT FOUND on page load!');
        }
        if (radiologySelect) {
            console.log('radiologyOpt element found, options:', radiologySelect.options.length);
        } else {
            console.error('radiologyOpt element NOT FOUND on page load!');
        }
        const opdRoute = "{{ route('opd.addPrescription') }}";
        const ipdRoute = "{{ route('ipd.addPrescription') }}";
        
        if (!createPrescriptionModal) {
            console.error('createPrescriptionModal element not found, cannot attach event listeners');
            return;
        }
        
        createPrescriptionModal.addEventListener('show.bs.modal', function(event) {
            let form = document.getElementById("ipdAddPrescriptionForm");
            const opdIdField = document.getElementById('opd_id');
            const ipdIdField = document.getElementById('ipd_id');

            var button = event.relatedTarget; // Button that triggered the modal

            // Fallback: if relatedTarget is not available, try to find the button
            if (!button) {
                // Prefer button matching ipd_id from form/URL (for programmatic open or when relatedTarget is null)
                const ipdIdVal = (ipdIdField ? ipdIdField.value : '').trim();
                if (ipdIdVal) {
                    button = document.querySelector('[data-bs-target="#addPrescriptionModal"][data-ipd-id="' + ipdIdVal + '"]');
                }
                if (!button) {
                    button = document.querySelector('[data-bs-target="#addPrescriptionModal"][data-ipd-id], [data-bs-target="#addPrescriptionModal"][data-id]');
                }
            }

            // Additional fallback: try to get from URL if on IPD view page
            let ipd_id_from_url = null;
            if (window.location.pathname.includes('/ipd_view/')) {
                const urlMatch = window.location.pathname.match(/\/ipd_view\/(\d+)/);
                if (urlMatch && urlMatch[1]) {
                    ipd_id_from_url = urlMatch[1];
                }
            }

            // Don't reset if values are already set from server-side
            var existingOpdId = opdIdField ? opdIdField.value.trim() : '';
            var existingIpdId = ipdIdField ? ipdIdField.value.trim() : '';

            // Only reset if fields are empty
            if (!existingOpdId && opdIdField) opdIdField.value = '';
            if (!existingIpdId && ipdIdField) ipdIdField.value = '';

            var opd_id = button ? (button.getAttribute('data-id') || button.getAttribute('data-opd-id')) : null;
            var ipd_id = button ? (button.getAttribute('data-ipd-id') || null) : null;

            // Use existing value if button doesn't have the attribute
            if (!ipd_id && existingIpdId) {
                ipd_id = existingIpdId;
                console.log('Using existing IPD ID from hidden field:', ipd_id);
            }

            // Use URL fallback if button doesn't have the attribute and no existing value
            if (!ipd_id && ipd_id_from_url) {
                ipd_id = ipd_id_from_url;
                if (ipdIdField) ipdIdField.value = ipd_id;
                console.log('Using IPD ID from URL:', ipd_id);
            }

            // Set prescription date from trigger button or page context (for back-dated IPD prescriptions)
            // IMPORTANT: Only set when empty to avoid overwriting user's back-dated selection
            const prescriptionDateEl = document.getElementById('prescription_date');
            let admissionDate = button ? button.getAttribute('data-admission-date') : null;
            // Fallback: use ipdViewContext when on IPD view page (e.g. programmatic modal open)
            if (!admissionDate && ipd_id) {
                const ctx = document.getElementById('ipdViewContext');
                if (ctx && ctx.getAttribute('data-ipd-id') === String(ipd_id)) {
                    admissionDate = ctx.getAttribute('data-admission-date');
                }
            }
            if (prescriptionDateEl) {
                const today = new Date().toISOString().split('T')[0];
                prescriptionDateEl.max = today; // Prevent future dates; allow back-dating up to today
                if (admissionDate) {
                    prescriptionDateEl.min = admissionDate; // Allow dates from admission to today
                    if (!prescriptionDateEl.value || prescriptionDateEl.value.trim() === '') {
                        prescriptionDateEl.value = admissionDate;
                        console.log('Prescription date set to admission date:', admissionDate);
                    }
                } else {
                    prescriptionDateEl.removeAttribute('min');
                    if (!prescriptionDateEl.value || prescriptionDateEl.value.trim() === '') {
                        prescriptionDateEl.value = today;
                    }
                }
            }

            // Set values only if they exist and are not empty
            if (opd_id && opd_id.trim() !== '') {
                if (opdIdField) {
                    opdIdField.value = opd_id.trim();
                    console.log('OPD ID set to:', opdIdField.value);
                }
                if (form) {
                    form.action = opdRoute; // OPD route
                    console.log('Form action set to OPD route:', opdRoute);
                }
            } else if (ipd_id && ipd_id.trim() !== '') {
                if (ipdIdField) {
                    ipdIdField.value = ipd_id.trim();
                    console.log('IPD ID set to:', ipdIdField.value);
                }
                if (form) {
                    form.action = ipdRoute; // IPD route
                    console.log('Form action set to IPD route:', ipdRoute);
                }
            } else {
                // If neither is set, default to IPD route but log warning
                console.warn('Neither OPD nor IPD ID found, defaulting to IPD route');
                if (form) form.action = ipdRoute;
            }

            // Verify form action is set correctly and doesn't point to view routes
            if (form) {
                if (!form.action || form.action === '' || form.action.includes('ipd_view') || form.action.includes('opd_view')) {
                    console.error('Form action is invalid:', form.action, '- Setting to IPD route as fallback');
                    form.action = ipdRoute;
                }
                console.log('Final form action:', form.action);
            }

            // Final verification - ensure ipd_id is set if we're on IPD view page
            if (ipd_id_from_url && ipdIdField && !ipdIdField.value) {
                ipdIdField.value = ipd_id_from_url;
                console.log('IPD ID set from URL fallback:', ipdIdField.value);
            }

            // Debug: log values to console (remove in production)
            console.log('Modal opened - OPD ID:', opd_id, 'IPD ID:', ipd_id);
            console.log('Hidden field values - OPD:', opdIdField ? opdIdField.value : 'N/A', 'IPD:', ipdIdField ? ipdIdField.value : 'N/A');

            // Initialize test selects when modal opens
            console.log('Modal show event triggered');
            setTimeout(() => {
                if (typeof window.initializeTestSelects === 'function') {
                    console.log('Calling initializeTestSelects from show event');
                    window.initializeTestSelects();
                } else if (typeof initializeTestSelects === 'function') {
                    console.log('Calling initializeTestSelects (local) from show event');
                    initializeTestSelects();
                } else {
                    console.warn('initializeTestSelects function not found, calling directly');
                    if (typeof initializePathologyMultiselect === 'function') {
                        initializePathologyMultiselect();
                    }
                    if (typeof initializeRadiologyMultiselect === 'function') {
                        initializeRadiologyMultiselect();
                    }
                }

                // Load medicines immediately when modal opens
                setTimeout(() => {
                    if (typeof window.loadMedicinesOnModalOpen === 'function') {
                        window.loadMedicinesOnModalOpen();
                    } else {
                        console.error('loadMedicinesOnModalOpen function not found');
                    }
                }, 300);
            }, 200);
        });

        // Reset form when modal is closed - keep Select2 alive (don't destroy) so dropdown works on reopen
        createPrescriptionModal.addEventListener('hidden.bs.modal', function() {
            const pathOpt = document.getElementById('pathologyOpt');
            const radOpt = document.getElementById('radiologyOpt');
            if (pathOpt && window.jQuery) {
                var $p = window.jQuery(pathOpt);
                if ($p.hasClass('select2-hidden-accessible')) $p.val(null).trigger('change');
                else { pathOpt.value = ''; Array.from(pathOpt.options).forEach(function(o) { o.selected = false; }); }
            }
            if (radOpt && window.jQuery) {
                var $r = window.jQuery(radOpt);
                if ($r.hasClass('select2-hidden-accessible')) $r.val(null).trigger('change');
                else { radOpt.value = ''; Array.from(radOpt.options).forEach(function(o) { o.selected = false; }); }
            }
            const prescriptionDateEl = document.getElementById('prescription_date');
            if (prescriptionDateEl) prescriptionDateEl.value = '';
            window.selectedPathologyList = [];
            window.selectedRadiologyList = [];
            const pathContainer = document.getElementById('pathologySelectedList');
            const radContainer = document.getElementById('radiologySelectedList');
            if (pathContainer) pathContainer.innerHTML = '';
            if (radContainer) radContainer.innerHTML = '';
        });

        // CRITICAL: Init pathology/radiology Select2 when modal is FULLY shown (fixes options not displaying)
        // Select2 must init when modal is visible - init on hidden modal causes dropdown/options to not display
        createPrescriptionModal.addEventListener('shown.bs.modal', function() {
            function initPathRadSelect2() {
                if (typeof window.jQuery === 'undefined' || !window.jQuery.fn.select2) return;
                var $ = window.jQuery;
                var pathEl = document.getElementById('pathologyOpt');
                var radEl = document.getElementById('radiologyOpt');
                if (!pathEl && !radEl) return;
                // Only init when we have real options (not just "Loading..." or "Select Tests")
                if (pathEl && pathEl.options.length > 1) {
                    try {
                        if ($(pathEl).hasClass('select2-hidden-accessible') || $(pathEl).data('select2')) {
                            try { $(pathEl).select2('destroy'); } catch(d) {}
                        }
                        $(pathEl).select2({
                            placeholder: 'Select Tests',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('body'),
                            dropdownCssClass: 'pathology-radiology-dropdown',
                            minimumResultsForSearch: 0,
                            multiple: true,
                            closeOnSelect: false
                        });
                        console.log('Pathology Select2 initialized with', pathEl.options.length, 'options');
                    } catch (e) { console.debug('Pathology Select2 init:', e); }
                }
                if (radEl && radEl.options.length > 1) {
                    try {
                        if ($(radEl).hasClass('select2-hidden-accessible') || $(radEl).data('select2')) {
                            try { $(radEl).select2('destroy'); } catch(d) {}
                        }
                        $(radEl).select2({
                            placeholder: 'Select Tests',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('body'),
                            dropdownCssClass: 'pathology-radiology-dropdown',
                            minimumResultsForSearch: 0,
                            multiple: true,
                            closeOnSelect: false
                        });
                        console.log('Radiology Select2 initialized with', radEl.options.length, 'options');
                    } catch (e) { console.debug('Radiology Select2 init:', e); }
                }
            }
            initPathRadSelect2();
            setTimeout(initPathRadSelect2, 300);
            setTimeout(initPathRadSelect2, 800);
        });

        // OLD SUBMIT HANDLER REMOVED - Now handled by document-level capture-phase listener
        // The capture-phase listener (registered earlier) handles all submit events for ipdAddPrescriptionForm

        const container = document.getElementById("medicineContainer");
        const addButton = document.getElementById("addMedicineBtn");
        const addButtonContainer = document.getElementById("addMedicineContainer");

        // Global fillSelect function to populate select elements
        window.fillSelect = function(selectElement, data, textKey) {
                if (!selectElement) {
                    console.error('Select element not provided to fillSelect');
                    return;
                }

                // Store current value if select2 is initialized
                let currentValue = '';
                let isSelect2Initialized = false;
                if (window.jQuery && typeof $.fn.select2 !== 'undefined' && $(selectElement).hasClass('select2-hidden-accessible')) {
                    currentValue = $(selectElement).val();
                    isSelect2Initialized = true;
                    // Destroy Select2 temporarily to update options (safely)
                    try {
                        const $selectEl = $(selectElement);
                        if ($selectEl.hasClass('select2-hidden-accessible') || $selectEl.data('select2')) {
                            $selectEl.select2('destroy');
                        }
                    } catch(e) {
                        // Ignore - Select2 not initialized
                    }
                }

                // Clear existing options
                selectElement.innerHTML = `<option value="">Select</option>`;

                if (data && Array.isArray(data) && data.length > 0) {
                    let addedCount = 0;
                    data.forEach(item => {
                        if (!item || !item.id) {
                            console.warn('Invalid item in data:', item);
                            return;
                        }

                        const opt = document.createElement("option");
                        opt.value = item.id;

                        if (textKey == 'dosage') {
                            opt.textContent = item[textKey] + " " + (item['unit'] ? item['unit']['unit_name'] : '');
                        } else                         if (textKey == 'medicine_category') {
                            const categoryText = item['medicine_category'] || item['category'] || item['name'] || '';
                            opt.textContent = categoryText;
                            if (!categoryText) {
                                console.warn('Category item has no text:', item);
                            }
                        } else if (textKey == 'medicine_name') {
                            opt.textContent = item['medicine_name'] || item['name'] || '';
                        } else {
                            opt.textContent = item[textKey] || item['name'] || '';
                        }

                        selectElement.appendChild(opt);
                        addedCount++;
                    });
                    console.log(`Filled ${addedCount} options in select (from ${data.length} items) for ${textKey}`);
                } else {
                    console.warn('No data provided to fillSelect or data is not an array:', data);
                    selectElement.innerHTML = `<option value="">No options available</option>`;
                }

                // ALWAYS reinitialize Select2 after filling (not just if it was initialized before)
                if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                    setTimeout(() => {
                        try {
                            // Verify options were added
                            const optionCount = selectElement.options.length;
                            console.log(`Reinitializing Select2 for ${textKey} with ${optionCount} options`);

                            if (optionCount <= 1) {
                                console.warn(`No options found for ${textKey} before Select2 init`);
                            }

                            const select2Options = {
                                width: "100%",
                                placeholder: textKey === 'medicine_category' ? "Select Category" :
                                           textKey === 'medicine_name' ? "Search Medicine..." : "Select",
                                allowClear: true,
                                minimumResultsForSearch: 0
                            };

                            // Add dropdownParent for selects inside modal
                            if (textKey === 'medicine_category' || textKey === 'medicine_name') {
                                select2Options.dropdownParent = $('#addPrescriptionModal');
                            }

                            // Add language options for medicine_name
                            if (textKey === 'medicine_name') {
                                select2Options.language = {
                                    noResults: function() {
                                        return "No medicines found";
                                    },
                                    searching: function() {
                                        return "Searching...";
                                    }
                                };
                            }

                            // Initialize Select2
                            $(selectElement).select2(select2Options);

                            // Verify Select2 was initialized
                            if ($(selectElement).hasClass('select2-hidden-accessible')) {
                                console.log(`Select2 successfully initialized for ${textKey} with ${optionCount} options`);

                                // Restore value if it was set
                                if (currentValue) {
                                    $(selectElement).val(currentValue).trigger('change');
                                }

                                // Force Select2 to refresh its options display
                                $(selectElement).trigger('change.select2');
                            } else {
                                console.error(`Select2 initialization failed for ${textKey}`);
                            }
                        } catch(e) {
                            console.error(`Error reinitializing Select2 for ${textKey}:`, e);
                            console.error('Error stack:', e.stack);
                        }
                    }, 150); // Increased delay to ensure DOM is fully updated
                } else {
                    console.warn('jQuery or Select2 not available for', textKey);
                }
            };

            // Function to load all medicines (defined outside initRow for scope and made global)
            window.loadAllMedicines = function(selectElement) {
                if (!selectElement) {
                    console.error('Medicine select element not found');
                    return;
                }

                // Fetch all active medicines
                const allMedicinesUrl = "{{ route('pharmacy.api.medicines') }}";
                console.log('Loading medicines from:', allMedicinesUrl);

                if (window.jQuery && $.fn.select2) {
                    $(selectElement).prop('disabled', true);
                    $(selectElement).html('<option value="">Loading medicines...</option>');
                }

                fetch(allMedicinesUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                    .then(res => {
                        console.log('Response status:', res.status, res.statusText);
                        console.log('Response headers:', res.headers);

                        if (!res.ok) {
                            // Try to get error message from response
                            return res.text().then(text => {
                                console.error('Error response body:', text);
                                let errorMsg = `HTTP error! status: ${res.status}`;
                                try {
                                    const errorJson = JSON.parse(text);
                                    if (errorJson.message) {
                                        errorMsg = errorJson.message;
                                    }
                                } catch(e) {
                                    // Not JSON, use text as is
                                    if (text) {
                                        errorMsg = text.substring(0, 100);
                                    }
                                }
                                throw new Error(errorMsg);
                            });
                        }

                        // Check if response is JSON
                        const contentType = res.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return res.text().then(text => {
                                console.error('Response is not JSON:', text.substring(0, 200));
                                throw new Error('Server returned non-JSON response');
                            });
                        }

                        return res.json();
                    })
                    .then(data => {
                        console.log('Medicines loaded:', data);

                        // Check if response is an error object
                        if (data && data.error) {
                            console.error('API returned error:', data.error, data.message);
                            if (window.jQuery && $.fn.select2) {
                                $(selectElement).html(`<option value="">Error: ${data.message || data.error}</option>`);
                                $(selectElement).prop('disabled', false);
                            } else {
                                selectElement.innerHTML = `<option value="">Error: ${data.message || data.error}</option>`;
                                selectElement.disabled = false;
                            }
                            return;
                        }

                        if (!data || !Array.isArray(data)) {
                            console.error('Invalid data format - expected array, got:', typeof data, data);
                            if (window.jQuery && $.fn.select2) {
                                $(selectElement).html('<option value="">No medicines available</option>');
                                $(selectElement).prop('disabled', false);
                            } else {
                                selectElement.innerHTML = '<option value="">No medicines available</option>';
                                selectElement.disabled = false;
                            }
                            return;
                        }

                        // Destroy Select2 FIRST before modifying options
                        if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                            if ($(selectElement).hasClass('select2-hidden-accessible')) {
                                const $selectEl = $(selectElement);
                                if ($selectEl.length && ($selectEl.hasClass('select2-hidden-accessible') || $selectEl.data('select2'))) {
                                    try {
                                        $selectEl.select2('destroy');
                                        console.log('Destroyed existing Select2 before filling options');
                                    } catch(e) {
                                        // Ignore - Select2 not properly initialized
                                    }
                                }
                            }
                        }

                        // Fill the select with medicines
                        if (typeof window.fillSelect === 'function') {
                            window.fillSelect(selectElement, data, "medicine_name");
                        } else {
                            console.error('fillSelect function not found, manually filling...');
                            // Manual fill as fallback
                            selectElement.innerHTML = '<option value="">Select Medicine</option>';
                            data.forEach(item => {
                                if (item && item.id) {
                                    const opt = document.createElement("option");
                                    opt.value = item.id;
                                    opt.textContent = item.medicine_name || item.name || 'Unknown';
                                    selectElement.appendChild(opt);
                                }
                            });
                            console.log(`Manually added ${data.length} medicines to select`);
                        }

                        // Initialize Select2 AFTER options are added
                        if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                            setTimeout(() => {
                                const optionCount = selectElement.options.length;
                                console.log('Options count before Select2 init:', optionCount);

                                if (optionCount <= 1) {
                                    console.warn('No medicines found in select element');
                                    selectElement.innerHTML = '<option value="">No medicines available</option>';
                                }

                                try {
                                    $(selectElement).select2({
                                        width: "100%",
                                        placeholder: "Search Medicine...",
                                        allowClear: true,
                                        minimumResultsForSearch: 0,
                                        dropdownParent: $('#addPrescriptionModal'),
                                        language: {
                                            noResults: function() {
                                                return "No medicines found";
                                            },
                                            searching: function() {
                                                return "Searching...";
                                            }
                                        }
                                    });
                                    $(selectElement).prop('disabled', false);

                                    // Verify Select2 was initialized
                                    if ($(selectElement).hasClass('select2-hidden-accessible')) {
                                        console.log('Select2 initialized for medicine dropdown with', optionCount, 'options');
                                        // Force refresh
                                        $(selectElement).trigger('change.select2');
                                    } else {
                                        console.error('Select2 initialization failed');
                                    }
                                } catch(e) {
                                    console.error('Error initializing Select2:', e);
                                    $(selectElement).prop('disabled', false);
                                }
                            }, 200);
                        } else {
                            console.error('jQuery or Select2 not available');
                            selectElement.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading all medicines:', error);
                        console.error('Error details:', {
                            message: error.message,
                            stack: error.stack,
                            url: allMedicinesUrl
                        });

                        // Try to get more details if it's a fetch error
                        if (error.message && error.message.includes('HTTP error')) {
                            console.error('HTTP error occurred. Check network tab for details.');
                        }

                        if (window.jQuery && $.fn.select2) {
                            $(selectElement).html('<option value="">Error loading medicines. Please try again.</option>');
                            $(selectElement).prop('disabled', false);
                        } else {
                            selectElement.innerHTML = '<option value="">Error loading medicines</option>';
                            selectElement.disabled = false;
                        }
                    });
            }

            // Define initRow function IMMEDIATELY (before Promise) so it's available when called
            // Make initRow globally accessible (overwrite placeholder)
            window.initRow = function(row) {
                if (!row) {
                    console.error('Row element not found');
                    return;
                }

                // Load base options
                const categorySelect = row.querySelector(".medicine_category");
                const medicineSelect = row.querySelector(".medicine_name");
                const doseSelect = row.querySelector(".medicine_dosage");
                const intervalSelect = row.querySelector(".interval_dosage");
                const durationSelect = row.querySelector(".duration_dosage");

                if (!categorySelect || !medicineSelect || !doseSelect || !intervalSelect || !durationSelect) {
                    console.error('Required select elements not found in row');
                    return;
                }

                // Fill selects with data - ensure Select2 is destroyed first
                console.log('Initializing row - Categories available:', window.medicineCategories ? window.medicineCategories.length : 'not loaded');

                // Destroy Select2 for category BEFORE filling if it exists
                if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                    if ($(categorySelect).hasClass('select2-hidden-accessible')) {
                        const $catSelect = $(categorySelect);
                        if ($catSelect.length && ($catSelect.hasClass('select2-hidden-accessible') || $catSelect.data('select2'))) {
                            try {
                                $catSelect.select2('destroy');
                                console.log('Destroyed existing Select2 for category');
                            } catch(e) {
                                // Ignore - Select2 not properly initialized
                            }
                        }
                    }
                }

                // Fill category select
                if (typeof window.fillSelect === 'function' && window.medicineCategories && Array.isArray(window.medicineCategories)) {
                    console.log('Filling category select with', window.medicineCategories.length, 'categories');
                    console.log('Sample category data:', window.medicineCategories.slice(0, 3));
                    window.fillSelect(categorySelect, window.medicineCategories, "medicine_category");

                    // Initialize Select2 after filling
                    if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                        setTimeout(() => {
                            try {
                                $(categorySelect).select2({
                                    width: "100%",
                                    placeholder: "Select Category",
                                    allowClear: true,
                                    minimumResultsForSearch: 0,
                                    dropdownParent: $('#addPrescriptionModal')
                                });
                                console.log('Category Select2 initialized with', categorySelect.options.length, 'options');
                            } catch(e) {
                                console.error('Error initializing category Select2:', e);
                            }
                        }, 100);
                    }
                } else {
                    console.error('fillSelect function not found or categories not loaded');
                    console.log('Available:', {
                        fillSelect: typeof window.fillSelect,
                        categories: window.medicineCategories ? (Array.isArray(window.medicineCategories) ? window.medicineCategories.length : 'not array') : 'not loaded'
                    });

                    // Manual fallback fill
                    if (window.medicineCategories && Array.isArray(window.medicineCategories)) {
                        categorySelect.innerHTML = '<option value="">Select Category</option>';
                        window.medicineCategories.forEach(cat => {
                            if (cat && cat.id) {
                                const opt = document.createElement("option");
                                opt.value = cat.id;
                                opt.textContent = cat.medicine_category || cat.name || '';
                                categorySelect.appendChild(opt);
                            }
                        });
                        console.log('Manually filled', categorySelect.options.length, 'category options');

                        // Initialize Select2 after manual fill
                        if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                            setTimeout(() => {
                                try {
                                    $(categorySelect).select2({
                                        width: "100%",
                                        placeholder: "Select Category",
                                        allowClear: true,
                                        minimumResultsForSearch: 0,
                                        dropdownParent: $('#addPrescriptionModal')
                                    });
                                    console.log('Category Select2 initialized manually with', categorySelect.options.length, 'options');
                                } catch(e) {
                                    console.error('Error initializing category Select2 manually:', e);
                                }
                            }, 100);
                        }
                    }
                }

                // Fill interval and duration selects
                if (window.doseIntervals && typeof window.fillSelect === 'function') {
                    // Destroy Select2 first if exists
                    if (window.jQuery) {
                        const $intervalEl = $(intervalSelect);
                        if ($intervalEl.length && ($intervalEl.hasClass('select2-hidden-accessible') || $intervalEl.data('select2'))) {
                            $intervalEl.select2('destroy');
                        }
                    }
                    window.fillSelect(intervalSelect, window.doseIntervals, "name");
                    // Initialize Select2 after filling
                    if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                        setTimeout(() => {
                            $(intervalSelect).select2({
                                width: "100%",
                                placeholder: "Select Interval",
                                allowClear: true,
                                minimumResultsForSearch: 0,
                                dropdownParent: $('#addPrescriptionModal')
                            });
                        }, 100);
                    }
                }

                if (window.doseDurations && typeof window.fillSelect === 'function') {
                    // Destroy Select2 first if exists
                    if (window.jQuery) {
                        const $durationEl = $(durationSelect);
                        if ($durationEl.length && ($durationEl.hasClass('select2-hidden-accessible') || $durationEl.data('select2'))) {
                            $durationEl.select2('destroy');
                        }
                    }
                    window.fillSelect(durationSelect, window.doseDurations, "name");
                    // Initialize Select2 after filling
                    if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                        setTimeout(() => {
                            $(durationSelect).select2({
                                width: "100%",
                                placeholder: "Select Duration",
                                allowClear: true,
                                minimumResultsForSearch: 0,
                                dropdownParent: $('#addPrescriptionModal')
                            });
                        }, 100);
                    }
                }

                // Initialize medicine select as empty - will be populated when category is selected
                medicineSelect.innerHTML = '<option value="">Select Medicine (Choose Category First)</option>';
                if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                    // Destroy existing Select2 if any
                    const $medSelect = $(medicineSelect);
                    if ($medSelect.length && ($medSelect.hasClass('select2-hidden-accessible') || $medSelect.data('select2'))) {
                        $medSelect.select2('destroy');
                    }
                    // Initialize Select2 for medicine (will be populated when category changes)
                    setTimeout(() => {
                        $(medicineSelect).select2({
                            width: "100%",
                            placeholder: "Select Medicine (Choose Category First)",
                            allowClear: true,
                            minimumResultsForSearch: 0,
                            dropdownParent: $('#addPrescriptionModal'),
                            disabled: true // Disable until category is selected
                        });
                    }, 100);
                }

                // Initialize dose select as empty - will be populated when medicine is selected
                doseSelect.innerHTML = '<option value="">Select Dose (Choose Medicine First)</option>';
                if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                    // Destroy existing Select2 if any
                    const $doseEl = $(doseSelect);
                    if ($doseEl.length && ($doseEl.hasClass('select2-hidden-accessible') || $doseEl.data('select2'))) {
                        $doseEl.select2('destroy');
                    }
                    // Initialize Select2 for dose (will be populated when medicine changes)
                    setTimeout(() => {
                        $(doseSelect).select2({
                            width: "100%",
                            placeholder: "Select Dose (Choose Medicine First)",
                            allowClear: true,
                            minimumResultsForSearch: 0,
                            dropdownParent: $('#addPrescriptionModal'),
                            disabled: true // Disable until medicine is selected
                        });
                    }, 100);
                }

                // Add change event listener (works for both native and Select2)
                const handleCategoryChange = function() {
                    const categoryId = this.value || $(this).val();
                    console.log('Category changed (event handler):', categoryId);

                    if (categoryId && categoryId !== '') {
                        // Fetch medicines by category
                        const baseUrl = "{{ route('getMedicines', ['categoryId' => 'ID']) }}";
                        const finalUrl = baseUrl.replace('ID', categoryId);

                        console.log('Category selected:', categoryId, 'Fetching medicines from:', finalUrl);

                        // Show loading state
                        if (window.jQuery && $.fn.select2) {
                            $(medicineSelect).prop('disabled', true);
                            $(medicineSelect).html('<option value="">Loading medicines...</option>');
                        }

                        fetch(finalUrl)
                            .then(res => {
                                if (!res.ok) throw new Error('Failed to fetch medicines');
                                return res.json();
                            })
                            .then(data => {
                                console.log('Medicines loaded for category:', data);

                                // Destroy Select2 before filling (safely)
                                if (window.jQuery) {
                                    const $medSelect = $(medicineSelect);
                                    if ($medSelect.length && ($medSelect.hasClass('select2-hidden-accessible') || $medSelect.data('select2'))) {
                                        $medSelect.select2('destroy');
                                    }
                                }

                                // Fill medicines
                                if (typeof window.fillSelect === 'function') {
                                    window.fillSelect(medicineSelect, data, "medicine_name");
                                } else {
                                    // Manual fill
                                    medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
                                    if (Array.isArray(data)) {
                                        data.forEach(item => {
                                            if (item && item.id) {
                                                const opt = document.createElement("option");
                                                opt.value = item.id;
                                                opt.textContent = item.medicine_name || item.name || 'Unknown';
                                                medicineSelect.appendChild(opt);
                                            }
                                        });
                                    }
                                }

                                // Reinitialize Select2 after filling
                                if (window.jQuery && $.fn.select2) {
                                    setTimeout(() => {
                                        $(medicineSelect).select2({
                                            width: "100%",
                                            placeholder: "Search Medicine...",
                                            allowClear: true,
                                            minimumResultsForSearch: 0,
                                            dropdownParent: $('#addPrescriptionModal'),
                                            language: {
                                                noResults: function() {
                                                    return "No medicines found";
                                                },
                                                searching: function() {
                                                    return "Searching...";
                                                }
                                            }
                                        });
                                        $(medicineSelect).prop('disabled', false);
                                    }, 100);
                                }
                            })
                            .catch(error => {
                                console.error('Error loading medicines:', error);
                                if (window.jQuery && $.fn.select2) {
                                    const $medSelect = $(medicineSelect);
                                    if ($medSelect.length && ($medSelect.hasClass('select2-hidden-accessible') || $medSelect.data('select2'))) {
                                        $medSelect.select2('destroy');
                                    }
                                    $medSelect.html('<option value="">Error loading medicines</option>');
                                    $medSelect.prop('disabled', false);
                                }
                            });
                };

                // Attach native change event (safely)
                if (categorySelect) {
                    categorySelect.addEventListener("change", handleCategoryChange);
                }

                // Also attach Select2 change event if jQuery is available
                if (window.jQuery && $.fn.select2 && categorySelect) {
                    const $catSelect = $(categorySelect);
                    if ($catSelect.length) {
                        $catSelect.off('select2:select select2:clear').on('select2:select select2:clear', function() {
                            console.log('Category changed via Select2 event');
                            if (typeof handleCategoryChange === 'function') {
                                handleCategoryChange.call(this);
                            }
                        });
                    }
                }

                        // Clear medicine and dose selects when category changes (safely)
                        if (window.jQuery) {
                            const $doseEl = $(doseSelect);
                            if ($doseEl.length && ($doseEl.hasClass('select2-hidden-accessible') || $doseEl.data('select2'))) {
                                $doseEl.select2('destroy');
                            }
                        }
                        doseSelect.innerHTML = '<option value="">Select Dose (Choose Medicine First)</option>';
                        if (window.jQuery && $.fn.select2) {
                            setTimeout(() => {
                                $(doseSelect).select2({
                                    width: "100%",
                                    placeholder: "Select Dose (Choose Medicine First)",
                                    allowClear: true,
                                    minimumResultsForSearch: 0,
                                    dropdownParent: $('#addPrescriptionModal'),
                                    disabled: true
                                });
                            }, 100);
                        }
                    } else {
                        // If no category selected, clear medicines and doses (safely)
                        if (window.jQuery) {
                            const $medSelect = $(medicineSelect);
                            if ($medSelect.length && ($medSelect.hasClass('select2-hidden-accessible') || $medSelect.data('select2'))) {
                                $medSelect.select2('destroy');
                            }
                        }
                        medicineSelect.innerHTML = '<option value="">Select Medicine (Choose Category First)</option>';
                        if (window.jQuery && $.fn.select2) {
                            setTimeout(() => {
                                $(medicineSelect).select2({
                                    width: "100%",
                                    placeholder: "Select Medicine (Choose Category First)",
                                    allowClear: true,
                                    minimumResultsForSearch: 0,
                                    dropdownParent: $('#addPrescriptionModal'),
                                    disabled: true
                                });
                            }, 100);
                        }

                        if (window.jQuery) {
                            const $doseEl = $(doseSelect);
                            if ($doseEl.length && ($doseEl.hasClass('select2-hidden-accessible') || $doseEl.data('select2'))) {
                                $doseEl.select2('destroy');
                            }
                        }
                        doseSelect.innerHTML = '<option value="">Select Dose (Choose Medicine First)</option>';
                        if (window.jQuery && $.fn.select2) {
                            setTimeout(() => {
                                $(doseSelect).select2({
                                    width: "100%",
                                    placeholder: "Select Dose (Choose Medicine First)",
                                    allowClear: true,
                                    minimumResultsForSearch: 0,
                                    dropdownParent: $('#addPrescriptionModal'),
                                    disabled: true
                                });
                            }, 100);
                        }
                    }
                });

                // Medicine change → fetch doses for the selected medicine (safely)
                if (medicineSelect) {
                    medicineSelect.addEventListener("change", function() {
                        const medicineId = this.value;

                    if (medicineId && medicineId !== '') {
                        console.log('Medicine selected:', medicineId, 'Fetching doses...');

                        // Get the category ID from the category select (since doses are fetched by category)
                        const categoryId = categorySelect.value;

                        if (categoryId && categoryId !== '') {
                            // Fetch doses for the category
                            const baseUrlDose = "{{ route('getDoses', ['categoryId' => 'ID']) }}";
                            const finalUrlDose = baseUrlDose.replace('ID', categoryId);

                            console.log('Fetching doses for category:', categoryId, 'from:', finalUrlDose);

                            // Show loading state
                            if (window.jQuery && $.fn.select2) {
                                $(doseSelect).prop('disabled', true);
                                $(doseSelect).html('<option value="">Loading doses...</option>');
                            }

                            fetch(finalUrlDose)
                                .then(res => {
                                    if (!res.ok) throw new Error('Failed to fetch doses');
                                    return res.json();
                                })
                                .then(data => {
                                    console.log('Doses loaded for category:', data);

                                    // Destroy Select2 before filling (safely)
                                    if (window.jQuery) {
                                        const $doseEl = $(doseSelect);
                                        if ($doseEl.length && ($doseEl.hasClass('select2-hidden-accessible') || $doseEl.data('select2'))) {
                                            $doseEl.select2('destroy');
                                        }
                                    }

                                    // Fill doses
                                    if (typeof window.fillSelect === 'function') {
                                        window.fillSelect(doseSelect, data, "dosage");
                                    } else {
                                        // Manual fill
                                        doseSelect.innerHTML = '<option value="">Select Dose</option>';
                                        if (Array.isArray(data)) {
                                            data.forEach(item => {
                                                if (item && item.id) {
                                                    const opt = document.createElement("option");
                                                    opt.value = item.id;
                                                    opt.textContent = item.dosage || item.name || 'Unknown';
                                                    doseSelect.appendChild(opt);
                                                }
                                            });
                                        }
                                    }

                                    // Reinitialize Select2 after filling
                                    if (window.jQuery && $.fn.select2) {
                                        setTimeout(() => {
                                            $(doseSelect).select2({
                                                width: "100%",
                                                placeholder: "Select Dose",
                                                allowClear: true,
                                                minimumResultsForSearch: 0,
                                                dropdownParent: $('#addPrescriptionModal')
                                            });
                                            $(doseSelect).prop('disabled', false);
                                        }, 100);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error loading doses:', error);
                                    if (window.jQuery && $.fn.select2) {
                                        $(doseSelect).html('<option value="">Error loading doses</option>');
                                        $(doseSelect).prop('disabled', false);
                                    }
                                });
                        } else {
                            console.warn('No category selected, cannot fetch doses');
                            if (window.jQuery && $.fn.select2) {
                                $(doseSelect).html('<option value="">Select Category First</option>');
                                $(doseSelect).prop('disabled', true);
                            }
                        }
                    } else {
                        // Clear doses if no medicine selected (safely)
                        if (window.jQuery) {
                            const $doseEl = $(doseSelect);
                            if ($doseEl.length && ($doseEl.hasClass('select2-hidden-accessible') || $doseEl.data('select2'))) {
                                $doseEl.select2('destroy');
                            }
                        }
                        doseSelect.innerHTML = '<option value="">Select Dose (Choose Medicine First)</option>';
                        if (window.jQuery && $.fn.select2) {
                            setTimeout(() => {
                                $(doseSelect).select2({
                                    width: "100%",
                                    placeholder: "Select Dose (Choose Medicine First)",
                                    allowClear: true,
                                    minimumResultsForSearch: 0,
                                    dropdownParent: $('#addPrescriptionModal'),
                                    disabled: true
                                });
                            }, 100);
                        }
                    }
                    });
                }

                // // Medicine change → fetch doses
                // row.querySelector(".medicine_name").addEventListener("change", function() {
                //     const medicineId = this.value;
                //     const doseSelect = row.querySelector(".medicine_dosage");
                //     fetch(`/getDoses/${medicineId}`)
                //         .then(res => res.json())
                //         .then(data => fillSelect(doseSelect, data, "dose"));
                // });

                // Delete button (safely)
                const deleteBtn = row.querySelector(".delete_row");
                if (deleteBtn) {
                    deleteBtn.addEventListener("click", function() {
                        const allRows = container.querySelectorAll(".medicine-row");
                        if (allRows.length > 1) row.remove();
                        else alert("At least one medicine must remain.");
                    });
                }

                // Note: Select2 initialization is handled in initRow function above
                // Medicine Select2 is initialized in loadAllMedicines after data is loaded
                // This prevents duplicate initialization conflicts
            };

            // fillSelect is now defined globally above, this duplicate is removed

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

                // Insert before button
                container.insertBefore(newRow, addButtonContainer);
                // container.appendChild(newRow);
                if (typeof window.initRow === 'function') {
                    window.initRow(newRow);
                } else {
                    console.error('initRow function not available');
                }
            }
            
            // Make addNewRow globally accessible
            window.addNewRow = addNewRow;
        });

        // 🔹 Fetch base dropdown data once (OUTSIDE DOMContentLoaded so functions are available)
        // Use absolute URLs so fetch works from any page path (e.g. /ipd/28)
        var medicineCategoriesUrl = "{{ url(route('getMedicineCategories')) }}";
        var doseIntervalsUrl = "{{ url(route('getDoseIntervals')) }}";
        var doseDurationsUrl = "{{ url(route('getDoseDurations')) }}";
        Promise.all([
            fetch(medicineCategoriesUrl).then(res => {
                if (!res.ok) return res.json().catch(() => []).then(() => []);
                return res.json().then(data => Array.isArray(data) ? data : (data && data.data) || []);
            }),
            fetch(doseIntervalsUrl).then(res => {
                if (!res.ok) return res.json().catch(() => []).then(() => []);
                return res.json().then(data => Array.isArray(data) ? data : (data && data.data) || []);
            }),
            fetch(doseDurationsUrl).then(res => {
                if (!res.ok) return res.json().catch(() => []).then(() => []);
                return res.json().then(data => Array.isArray(data) ? data : (data && data.data) || []);
            })
        ]).then(([categories, intervals, durations]) => {
            window.medicineCategories = Array.isArray(categories) ? categories : [];
            window.doseIntervals = Array.isArray(intervals) ? intervals : [];
            window.doseDurations = Array.isArray(durations) ? durations : [];
            console.log('Categories loaded:', window.medicineCategories.length, 'Intervals:', window.doseIntervals.length, 'Durations:', window.doseDurations.length);

            // Check if container exists before initializing
            const container = document.getElementById("medicineContainer");
            if (container && container.querySelector(".medicine-row")) {
                // Initialize first row
                if (typeof window.initRow === 'function') {
                    window.initRow(container.querySelector(".medicine-row"));
                }
            } else {
                console.warn('Medicine container or row not found, will initialize when modal opens');
            }

            const addButton = document.getElementById("addMedicineRowBtn");
            if (addButton) {
                // Remove existing listener if any by cloning
                const newAddButton = addButton.cloneNode(true);
                addButton.parentNode.replaceChild(newAddButton, addButton);
                if (newAddButton) {
                    newAddButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        if (typeof window.addNewRow === 'function') {
                            window.addNewRow();
                        } else {
                            console.error('addNewRow function not found');
                        }
                    });
                }
            }
        }).catch(error => {
            console.error('Error loading base dropdown data:', error);
            window.medicineCategories = window.medicineCategories || [];
            window.doseIntervals = window.doseIntervals || [];
            window.doseDurations = window.doseDurations || [];
        });

        // Function to load medicines when modal opens (made global) - Define OUTSIDE Promise
        window.loadMedicinesOnModalOpen = function() {
            console.log('loadMedicinesOnModalOpen called');
            const medicineContainer = document.getElementById("medicineContainer");
            if (!medicineContainer) {
                console.error('Medicine container not found');
                return;
            }

            const medicineRows = medicineContainer.querySelectorAll('.medicine-row');
            console.log('Found', medicineRows.length, 'medicine rows');

            if (medicineRows.length === 0) {
                console.error('No medicine rows found');
                return;
            }

            medicineRows.forEach((row, index) => {
                const medicineSelect = row.querySelector('.medicine_name');
                if (medicineSelect) {
                    console.log('Loading medicines for row', index + 1);

                    // Direct API call - simpler approach
                    const apiUrl = "{{ route('pharmacy.api.medicines') }}";
                    console.log('Fetching from:', apiUrl);

                    // Show loading state
                    medicineSelect.disabled = true;
                    medicineSelect.innerHTML = '<option value="">Loading medicines...</option>';

                    fetch(apiUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                        .then(res => {
                            console.log('Response status:', res.status, res.statusText);

                            if (!res.ok) {
                                return res.text().then(text => {
                                    console.error('Error response body:', text);
                                    let errorMsg = `HTTP error! status: ${res.status}`;
                                    try {
                                        const errorJson = JSON.parse(text);
                                        if (errorJson.message) {
                                            errorMsg = errorJson.message;
                                        }
                                    } catch(e) {
                                        if (text) {
                                            errorMsg = text.substring(0, 100);
                                        }
                                    }
                                    throw new Error(errorMsg);
                                });
                            }

                            const contentType = res.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                return res.text().then(text => {
                                    console.error('Response is not JSON:', text.substring(0, 200));
                                    throw new Error('Server returned non-JSON response');
                                });
                            }

                            return res.json();
                        })
                        .then(data => {
                            console.log('Medicines API response:', data);

                            // Check if response is an error object
                            if (data && data.error) {
                                console.error('API returned error:', data.error, data.message);
                                medicineSelect.innerHTML = `<option value="">Error: ${data.message || data.error}</option>`;
                                medicineSelect.disabled = false;
                                return;
                            }

                            if (!data || !Array.isArray(data)) {
                                console.error('Invalid data format - expected array, got:', typeof data, data);
                                medicineSelect.innerHTML = '<option value="">No medicines available</option>';
                                medicineSelect.disabled = false;
                                return;
                            }

                            if (data.length === 0) {
                                console.warn('No medicines returned from API');
                                medicineSelect.innerHTML = '<option value="">No medicines found</option>';
                                medicineSelect.disabled = false;
                                return;
                            }

                            // Destroy Select2 FIRST before modifying options (safely)
                            if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                                try {
                                    const $select = $(medicineSelect);
                                    if ($select.hasClass('select2-hidden-accessible') || $select.data('select2')) {
                                        $select.select2('destroy');
                                        console.log('Destroyed existing Select2 before adding options');
                                    }
                                } catch(e) {
                                    console.debug('Select2 destroy skipped (not initialized):', e.message);
                                }
                            }

                            // Fill select with medicines
                            medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
                            let addedCount = 0;
                            data.forEach(item => {
                                if (item && item.id) {
                                    const opt = document.createElement("option");
                                    opt.value = item.id;
                                    opt.textContent = item.medicine_name || item.name || 'Unknown';
                                    medicineSelect.appendChild(opt);
                                    addedCount++;
                                }
                            });

                            console.log(`Added ${addedCount} medicines to select (total options: ${medicineSelect.options.length})`);

                            // Enable select
                            medicineSelect.disabled = false;

                            // Initialize Select2 AFTER options are added
                            if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                                setTimeout(() => {
                                    try {
                                        // Verify options are in the DOM
                                        const optionCount = medicineSelect.options.length;
                                        console.log('Initializing Select2 with', optionCount, 'options');

                                        if (optionCount <= 1) {
                                            console.warn('No options found in select element before Select2 init');
                                        }

                                        $(medicineSelect).select2({
                                            width: "100%",
                                            placeholder: "Search Medicine...",
                                            allowClear: true,
                                            minimumResultsForSearch: 0,
                                            dropdownParent: $('#addPrescriptionModal'),
                                            language: {
                                                noResults: function() {
                                                    return "No medicines found";
                                                },
                                                searching: function() {
                                                    return "Searching...";
                                                }
                                            }
                                        });

                                        // Verify Select2 was initialized
                                        if ($(medicineSelect).hasClass('select2-hidden-accessible')) {
                                            console.log('Select2 initialized successfully for row', index + 1, 'with', optionCount, 'options');

                                            // Force Select2 to refresh its options
                                            $(medicineSelect).trigger('change.select2');
                                        } else {
                                            console.error('Select2 initialization failed - element not marked as initialized');
                                        }
                                    } catch(e) {
                                        console.error('Error initializing Select2:', e);
                                        console.error('Error details:', e.message, e.stack);
                                    }
                                }, 200); // Increased delay to ensure DOM is updated
                            } else {
                                console.warn('jQuery or Select2 not available');
                            }
                        })
                        .catch(error => {
                            console.error('Error loading medicines:', error);
                            console.error('Error details:', {
                                message: error.message,
                                stack: error.stack,
                                url: apiUrl
                            });

                            let errorMessage = 'Error loading medicines';
                            if (error.message) {
                                errorMessage = `Error: ${error.message}`;
                                // Truncate if too long
                                if (errorMessage.length > 50) {
                                    errorMessage = errorMessage.substring(0, 50) + '...';
                                }
                            }

                            medicineSelect.innerHTML = `<option value="">${errorMessage}</option>`;
                            medicineSelect.disabled = false;
                        });
                } else {
                    console.warn('Medicine select not found in row', index + 1);
                }
            });
        }

        // Remove old pathology/radiology select initialization - now handled in table-based section above

        fetch("{{ route('getFindingCategories') }}")
            .then(response => response.json())
            .then(data => {
                window.findingCategoryData = data;
                if (window.jQuery && $(findingCategorySelect).hasClass('select2-hidden-accessible')) {
                    try { $(findingCategorySelect).select2('destroy'); } catch(e) {}
                }
                findingCategorySelect.innerHTML = '<option value="">Select</option>';
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.category;
                    if (window.__oldPrescription && [].concat(window.__oldPrescription.finding_type).indexOf(String(category.id)) !== -1) {
                        option.selected = true;
                    }
                    findingCategorySelect.appendChild(option);
                });
                if (window.jQuery && $.fn.select2 && findingCategorySelect) {
                    $(findingCategorySelect).select2({
                        placeholder: 'Select',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#addPrescriptionModal'),
                        multiple: true
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching finding categories:', error);
                if (findingCategorySelect) {
                    findingCategorySelect.innerHTML = '<option value="">Error loading options</option>';
                }
            });

        // Note: findingCategorySelect is already defined above (line 702) as 'finding_type'
        // If we need 'finding_category', use a different variable name
        const findingCategorySelectForChange = document.getElementById('finding_category') || document.getElementById('finding_type');
        if (findingCategorySelectForChange) {
            findingCategorySelectForChange.addEventListener('change', function() {
                // ✅ Collect all selected IDs
            const selectedIds = Array.from(this.selectedOptions).map(opt => opt.value);

            // ✅ Clear current findings
            findingsSelect.innerHTML = '<option value="">Loading...</option>';

            if (selectedIds.length === 0) {
                findingsSelect.innerHTML = '<option value="">Select a category first</option>';
                return;
            }

            // ✅ Fetch findings for all selected categories
            fetch("{{ route('getFindings') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        category_ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    window.findingData = data;
                    findingsSelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(finding => {
                        const option = document.createElement('option');
                        option.value = finding.id;
                        option.textContent = finding.name;
                        if (window.__oldPrescription && [].concat(window.__oldPrescription.finding).indexOf(String(finding.id)) !== -1) {
                            option.selected = true;
                        }
                        findingsSelect.appendChild(option);
                    });

                    // Initialize Select2 after populating findings
                    if (window.jQuery && $.fn.select2 && findingsSelect) {
                        const $findingsEl = $(findingsSelect);
                        if ($findingsEl.length && ($findingsEl.hasClass('select2-hidden-accessible') || $findingsEl.data('select2'))) {
                            try { $findingsEl.select2('destroy'); } catch(e) {}
                        }
                        $(findingsSelect).select2({
                            placeholder: 'Select Findings',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#addPrescriptionModal'),
                            multiple: true
                        });
                        console.log('Select2 initialized for findings select');
                    }
                })
                .catch(error => {
                    console.error('Error fetching Findings:', error);
                    findingsSelect.innerHTML = '<option value="">Error loading options</option>';
                });
            });
        }

        // Fetch pathologies on page load (matching working version)
        // Always fetch, even if element not found (it might be in modal)
        console.log('Starting pathology fetch...');
        console.log('pathologySelect element:', pathologySelect);
        console.log('Fetch URL:', "{{ route('getPathologies') }}");

        fetch("{{ route('getPathologies') }}")
            .then(response => {
                console.log('Pathology response status:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Pathologies data received:', data);
                console.log('Data length:', data ? data.length : 0);
                window.pathologyData = data;

                // Try to find element again (might be in modal)
                const pathologySelectEl = document.getElementById('pathologyOpt') || pathologySelect;
                if (pathologySelectEl) {
                    pathologySelectEl.innerHTML = ''; // Clear for multiselect
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(patho => {
                            const option = document.createElement('option');
                            option.value = patho.id;
                            option.textContent = (patho.test_name || 'Unknown') + (patho.short_name ? "(" + patho.short_name + ")" : "");
                            if (window.__oldPrescription && [].concat(window.__oldPrescription.pathology).indexOf(String(patho.id)) !== -1) {
                                option.selected = true;
                            }
                            pathologySelectEl.appendChild(option);
                        });
                        console.log('Pathology options added. Total options:', pathologySelectEl.options.length);

                        // Initialize Select2 after data is loaded
                        if (window.jQuery && $.fn.select2) {
                            if ($(pathologySelectEl).hasClass('select2-hidden-accessible')) {
                                $(pathologySelectEl).select2('destroy');
                            }
                            $(pathologySelectEl).select2({
                                placeholder: 'Select Tests',
                                allowClear: true,
                                width: '100%',
                                dropdownParent: $('#addPrescriptionModal')
                            });
                            console.log('Select2 initialized for pathology');
                        } else {
                            console.warn('jQuery or Select2 not available for pathology');
                        }
                    } else {
                        console.warn('No pathology data or empty array');
                    }
                } else {
                    console.warn('pathologyOpt element still not found after fetch');
                }
            })
            .catch(error => {
                console.error('Error fetching Pathologies:', error);
                const pathologySelectEl = document.getElementById('pathologyOpt') || pathologySelect;
                if (pathologySelectEl) {
                    pathologySelectEl.innerHTML = '<option value="">Error loading options</option>';
                }
            });

        // Fetch radiologies on page load (matching working version)
        // Always fetch, even if element not found (it might be in modal)
        console.log('Starting radiology fetch...');
        console.log('radiologySelect element:', radiologySelect);
        console.log('Fetch URL:', "{{ route('getRadiologies') }}");

        fetch("{{ route('getRadiologies') }}")
            .then(response => {
                console.log('Radiology response status:', response.status);
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Radiologies data received:', data);
                console.log('Data length:', data ? data.length : 0);
                window.radiologyData = data;

                // Try to find element again (might be in modal)
                const radiologySelectEl = document.getElementById('radiologyOpt') || radiologySelect;
                if (radiologySelectEl) {
                    radiologySelectEl.innerHTML = ''; // Clear for multiselect
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(radio => {
                            const option = document.createElement('option');
                            option.value = radio.id;
                            option.textContent = (radio.test_name || 'Unknown') + (radio.short_name ? "(" + radio.short_name + ")" : "");
                            if (window.__oldPrescription && [].concat(window.__oldPrescription.radiology).indexOf(String(radio.id)) !== -1) {
                                option.selected = true;
                            }
                            radiologySelectEl.appendChild(option);
                        });
                        console.log('Radiology options added. Total options:', radiologySelectEl.options.length);

                        // Initialize Select2 after data is loaded
                        if (window.jQuery && $.fn.select2) {
                            if ($(radiologySelectEl).hasClass('select2-hidden-accessible')) {
                                $(radiologySelectEl).select2('destroy');
                            }
                            $(radiologySelectEl).select2({
                                placeholder: 'Select Tests',
                                allowClear: true,
                                width: '100%',
                                dropdownParent: $('#addPrescriptionModal')
                            });
                            console.log('Select2 initialized for radiology');
                        } else {
                            console.warn('jQuery or Select2 not available for radiology');
                        }
                    } else {
                        console.warn('No radiology data or empty array');
                    }
                } else {
                    console.warn('radiologyOpt element still not found after fetch');
                }
            })
            .catch(error => {
                console.error('Error fetching Radiologies:', error);
                const radiologySelectEl = document.getElementById('radiologyOpt') || radiologySelect;
                if (radiologySelectEl) {
                    radiologySelectEl.innerHTML = '<option value="">Error loading options</option>';
                }
            });
    })
</script>

<script>
    // Function to initialize pathology multiselect (moved outside DOMContentLoaded to avoid syntax issues)
    function initializePathologyMultiselect() {
        const pathologySelect = document.getElementById('pathologyOpt');
        if (!pathologySelect) {
            console.error('Pathology select element not found');
            return;
        }

        // Check if jQuery and Select2 are available
        if (!window.jQuery || !$.fn.select2) {
            console.error('jQuery or Select2 not available, retrying...');
            // Limit retries to prevent infinite loop
            if (!window.pathologyRetryCount) window.pathologyRetryCount = 0;
            if (window.pathologyRetryCount < 5) {
                window.pathologyRetryCount++;
                setTimeout(initializePathologyMultiselect, 500);
            } else {
                console.error('Max retries reached for pathology initialization');
            }
            return;
        }

        // Check if options are already populated (from page load fetch or server-side)
        const hasOptions = pathologySelect.options.length > 1; // More than just the "Select" option

        // If no options, fetch via AJAX (fallback if page load fetch didn't work)
        if (!hasOptions) {
            console.log('No pathology options found, fetching via AJAX...');
            const pathologyUrl = "{{ route('getPathologies') }}";
            fetch(pathologyUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    // Clear existing options (for multiselect, don't include empty option)
                    pathologySelect.innerHTML = '';
                    data.forEach(patho => {
                        const option = document.createElement('option');
                        option.value = patho.id;
                        option.textContent = (patho.test_name || 'Unknown') + (patho.short_name ? "(" + patho.short_name + ")" : "");
                        pathologySelect.appendChild(option);
                    });
                }
                initializeSelect2ForPathology();
            })
            .catch(error => {
                console.error('Error fetching Pathologies:', error);
                initializeSelect2ForPathology(); // Initialize anyway
            });
        } else {
            // Options already exist, just initialize Select2
            initializeSelect2ForPathology();
        }

        function initializeSelect2ForPathology() {
            if (!pathologySelect) {
                console.error('Pathology select element not found in initializeSelect2ForPathology');
                return;
            }
            
            // Check if Select2 is already initialized
            const isSelect2Initialized = $(pathologySelect).hasClass('select2-hidden-accessible');

            if (!isSelect2Initialized) {
                console.log('Initializing Select2 for pathology with', pathologySelect.options.length, 'options');
                try {
                    $(pathologySelect).select2({
                        placeholder: 'Search and select pathology tests...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#addPrescriptionModal'),
                        minimumResultsForSearch: 0, // Always show search box
                        multiple: true, // Enable multiselect
                        closeOnSelect: false, // Keep dropdown open for multiple selections
                        language: {
                            noResults: function() {
                                return "No results found";
                            },
                            searching: function() {
                                return "Searching...";
                            }
                        }
                    });
                    console.log('Pathology Select2 initialized successfully');
                } catch(e) {
                    console.error('Error initializing pathology Select2:', e);
                }
            } else {
                console.log('Select2 already initialized for pathology, refreshing options...');
                // Refresh Select2 to show new options
                try {
                    $(pathologySelect).trigger('change.select2');
                } catch(e) {
                    console.error('Error refreshing pathology Select2:', e);
                }
            }
        }
    }

    // Function to initialize radiology multiselect
    function initializeRadiologyMultiselect() {
        const radiologySelect = document.getElementById('radiologyOpt');
        if (!radiologySelect) {
            console.error('Radiology select element not found');
            return;
        }

        // Check if jQuery and Select2 are available
        if (!window.jQuery || !$.fn.select2) {
            console.error('jQuery or Select2 not available, retrying...');
            // Limit retries to prevent infinite loop
            if (!window.radiologyRetryCount) window.radiologyRetryCount = 0;
            if (window.radiologyRetryCount < 5) {
                window.radiologyRetryCount++;
                setTimeout(initializeRadiologyMultiselect, 500);
            } else {
                console.error('Max retries reached for radiology initialization');
            }
            return;
        }

        // Check if options are already populated (from page load fetch or server-side)
        const hasOptions = radiologySelect.options.length > 1; // More than just the "Select" option

        // If no options, fetch via AJAX (fallback if page load fetch didn't work)
        if (!hasOptions) {
            console.log('No radiology options found, fetching via AJAX...');
            const radiologyUrl = "{{ url(route('getRadiologies')) }}";
            fetch(radiologyUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    // Clear existing options (for multiselect, don't include empty option)
                    radiologySelect.innerHTML = '';
                    data.forEach(radio => {
                        const option = document.createElement('option');
                        option.value = radio.id;
                        option.textContent = (radio.test_name || 'Unknown') + (radio.short_name ? "(" + radio.short_name + ")" : "");
                        radiologySelect.appendChild(option);
                    });
                }
                initializeSelect2ForRadiology();
            })
            .catch(error => {
                console.error('Error fetching Radiologies:', error);
                initializeSelect2ForRadiology(); // Initialize anyway
            });
        } else {
            console.log('Radiology options already exist, initializing Select2');
            initializeSelect2ForRadiology();
        }

        function initializeSelect2ForRadiology() {
            if (!radiologySelect) {
                console.error('Radiology select element not found in initializeSelect2ForRadiology');
                return;
            }
            
            // Check if Select2 is already initialized
            const isSelect2Initialized = $(radiologySelect).hasClass('select2-hidden-accessible');

            if (!isSelect2Initialized) {
                console.log('Initializing Select2 for radiology with', radiologySelect.options.length, 'options');
                try {
                    $(radiologySelect).select2({
                        placeholder: 'Search and select radiology tests...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#addPrescriptionModal'),
                        minimumResultsForSearch: 0, // Always show search box
                        multiple: true, // Enable multiselect
                        closeOnSelect: false, // Keep dropdown open for multiple selections
                        language: {
                            noResults: function() {
                                return "No results found";
                            },
                            searching: function() {
                                return "Searching...";
                            }
                        }
                    });
                    console.log('Radiology Select2 initialized successfully');
                } catch(e) {
                    console.error('Error initializing radiology Select2:', e);
                }
            } else {
                console.log('Select2 already initialized for radiology, refreshing options...');
                // Refresh Select2 to show new options
                try {
                    $(radiologySelect).trigger('change.select2');
                } catch(e) {
                    console.error('Error refreshing radiology Select2:', e);
                }
            }
        }
    }

    // Initialize test selects when modal opens
    function initializeTestSelects() {
        console.log('initializeTestSelects called');
        // Use a longer timeout to ensure modal is fully rendered
        setTimeout(() => {
            console.log('Initializing test selects after timeout...');
            initializePathologyMultiselect();
            initializeRadiologyMultiselect();
        }, 300);
    }

    // Make functions globally accessible
    window.initializePathologyMultiselect = initializePathologyMultiselect;
    window.initializeRadiologyMultiselect = initializeRadiologyMultiselect;
    window.initializeTestSelects = initializeTestSelects;
</script>

<script>
    // Modal initialization code (needs to be in DOMContentLoaded to access createPrescriptionModal)
    document.addEventListener('DOMContentLoaded', function() {
        const createPrescriptionModal = document.getElementById("addPrescriptionModal");
        if (!createPrescriptionModal) {
            console.error('addPrescriptionModal element not found');
            return;
        }

        // Test: Try to initialize immediately if modal is already visible (for debugging)
        console.log('Pathology and radiology initialization functions defined');
        console.log('Modal element:', createPrescriptionModal ? 'Found' : 'Not found');

        // Also initialize when modal is fully shown
        if (!createPrescriptionModal) {
            console.warn('addPrescriptionModal element not found, skipping event listener setup');
            return;
        }
        
        // Intercept button clicks BEFORE Bootstrap handles them
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('[data-bs-target="#addPrescriptionModal"]');
            if (trigger) {
                console.log('🔴 Intercepted Add Prescription button click');
                // Trigger data fetch immediately
                setTimeout(() => {
                    console.log('🔴 Calling loadPathologyRadiologyData from click interceptor');
                    if (typeof window.loadPathologyRadiologyData === 'function') {
                        window.loadPathologyRadiologyData();
                    } else {
                        console.error('❌ loadPathologyRadiologyData not available in interceptor');
                    }
                }, 100);
            }
        }, true); // Use capture phase to intercept BEFORE Bootstrap
        
        // Suppress browser extension errors
        window.addEventListener('error', function(e) {
            if (e.message && (e.message.includes('message channel closed') || e.message.includes('asynchronous response'))) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }, true);
        
        // Function to load pathology and radiology data - DEFINE GLOBALLY FIRST
        window.loadPathologyRadiologyData = function() {
            console.log('🔵 loadPathologyRadiologyData() called');
            const pathologySelectModal = document.getElementById('pathologyOpt');
            const radiologySelectModal = document.getElementById('radiologyOpt');
            
            console.log('Pathology select found:', !!pathologySelectModal);
            console.log('Radiology select found:', !!radiologySelectModal);
            if (pathologySelectModal) {
                console.log('Pathology select options:', pathologySelectModal.options.length);
            }
            if (radiologySelectModal) {
                console.log('Radiology select options:', radiologySelectModal.options.length);
            }

            // If already populated (e.g. by ipd_view fetch), only init Select2 - do NOT clear
            const pathHasData = pathologySelectModal && pathologySelectModal.options.length > 1;
            const radHasData = radiologySelectModal && radiologySelectModal.options.length > 1;
            if (pathHasData && radHasData) {
                console.log('🔵 Pathology/radiology already have data, only initializing Select2');
                if (window.jQuery && $.fn.select2) {
                    try {
                        if ($(pathologySelectModal).hasClass('select2-hidden-accessible')) $(pathologySelectModal).select2('destroy');
                        $(pathologySelectModal).select2({
                            placeholder: 'Select Tests', allowClear: true, width: '100%',
                            dropdownParent: $('body'), dropdownCssClass: 'pathology-radiology-dropdown',
                            minimumResultsForSearch: 0, multiple: true, closeOnSelect: false
                        });
                        if ($(radiologySelectModal).hasClass('select2-hidden-accessible')) $(radiologySelectModal).select2('destroy');
                        $(radiologySelectModal).select2({
                            placeholder: 'Select Tests', allowClear: true, width: '100%',
                            dropdownParent: $('body'), dropdownCssClass: 'pathology-radiology-dropdown',
                            minimumResultsForSearch: 0, multiple: true, closeOnSelect: false
                        });
                    } catch(e) { console.error('Select2 init:', e); }
                }
                return;
            }
            
            // Fetch pathologies
            if (pathologySelectModal) {
                const pathologyUrl = "{{ url(route('getPathologies')) }}";
                console.log('📡 Fetching pathology data from:', pathologyUrl);
                if (pathologySelectModal.options.length <= 1) {
                    pathologySelectModal.innerHTML = '<option value="">Loading...</option>';
                }
                
                fetch(pathologyUrl)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('HTTP error! status: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Pathologies fetched on modal open:', data);
                            console.log('Pathology data count:', data ? data.length : 0);
                            window.pathologyData = data;
                            
                            if (!pathologySelectModal) {
                                console.error('pathologySelectModal element disappeared');
                                return;
                            }
                            
                            // Clear and populate options (for multiselect, don't include empty option)
                            pathologySelectModal.innerHTML = '';
                            if (data && Array.isArray(data) && data.length > 0) {
                                data.forEach(patho => {
                                    const option = document.createElement('option');
                                    option.value = patho.id;
                                    option.textContent = (patho.test_name || 'Unknown') + (patho.short_name ? "(" + patho.short_name + ")" : "");
                                    pathologySelectModal.appendChild(option);
                                });
                                console.log('Pathology options added:', pathologySelectModal.options.length);
                            
                            // Initialize Select2 after data is loaded
                            setTimeout(() => {
                                if (window.jQuery && $.fn.select2 && pathologySelectModal) {
                                    try {
                                        // Destroy existing Select2 if any
                                        if ($(pathologySelectModal).hasClass('select2-hidden-accessible')) {
                                            $(pathologySelectModal).select2('destroy');
                                        }
                                        
                                        // Initialize Select2 for multiselect (dropdownParent: body avoids overflow clipping)
                                        $(pathologySelectModal).select2({
                                            placeholder: 'Select Tests',
                                            allowClear: true,
                                            width: '100%',
                                            dropdownParent: $('body'),
                                            dropdownCssClass: 'pathology-radiology-dropdown',
                                            minimumResultsForSearch: 0,
                                            multiple: true,
                                            closeOnSelect: false
                                        });
                                        console.log('✅ Pathology Select2 initialized with', pathologySelectModal.options.length, 'options');
                                        
                                        // Verify Select2 is working
                                        if ($(pathologySelectModal).hasClass('select2-hidden-accessible')) {
                                            console.log('✅ Pathology Select2 is active and ready');
                                        } else {
                                            console.warn('⚠️ Pathology Select2 initialization may have failed');
                                        }
                                    } catch(e) {
                                        console.error('❌ Error initializing pathology Select2:', e);
                                    }
                                } else {
                                    console.error('❌ jQuery or Select2 not available for pathology');
                                }
                            }, 200);
                            } else {
                                console.warn('⚠️ No pathology data received or empty array');
                                const noDataOption = document.createElement('option');
                                noDataOption.value = '';
                                noDataOption.textContent = 'No tests available';
                                pathologySelectModal.appendChild(noDataOption);
                                
                                // Still try to initialize Select2 even with no data
                                setTimeout(() => {
                                    if (window.jQuery && $.fn.select2 && pathologySelectModal) {
                                        try {
                                            $(pathologySelectModal).select2({
                                                placeholder: 'No tests available',
                                                allowClear: true,
                                                width: '100%',
                                                dropdownParent: $('body'),
                                                minimumResultsForSearch: 0,
                                                multiple: true,
                                                disabled: true
                                            });
                                        } catch(e) {
                                            console.error('Error initializing empty pathology Select2:', e);
                                        }
                                    }
                                }, 200);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching pathologies:', error);
                            if (pathologySelectModal) {
                                pathologySelectModal.innerHTML = '<option value="">Error loading tests</option>';
                            }
                        });
                } else {
                    console.error('pathologySelectModal element not found');
                }

            // Fetch radiologies
            if (radiologySelectModal) {
                const radiologyUrl = "{{ url(route('getRadiologies')) }}";
                console.log('📡 Fetching radiology data from:', radiologyUrl);
                if (radiologySelectModal.options.length <= 1) {
                    radiologySelectModal.innerHTML = '<option value="">Loading...</option>';
                }
                
                fetch(radiologyUrl)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('HTTP error! status: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Radiologies fetched on modal open:', data);
                            console.log('Radiology data count:', data ? data.length : 0);
                            window.radiologyData = data;
                            
                            if (!radiologySelectModal) {
                                console.error('radiologySelectModal element disappeared');
                                return;
                            }
                            
                            // Clear and populate options (for multiselect, don't include empty option)
                            radiologySelectModal.innerHTML = '';
                            if (data && Array.isArray(data) && data.length > 0) {
                                data.forEach(radio => {
                                    const option = document.createElement('option');
                                    option.value = radio.id;
                                    option.textContent = (radio.test_name || 'Unknown') + (radio.short_name ? "(" + radio.short_name + ")" : "");
                                    radiologySelectModal.appendChild(option);
                                });
                                console.log('Radiology options added:', radiologySelectModal.options.length);
                                
                                // Initialize Select2 after data is loaded
                                setTimeout(() => {
                                    if (window.jQuery && $.fn.select2 && radiologySelectModal) {
                                        try {
                                            // Destroy existing Select2 if any
                                            if ($(radiologySelectModal).hasClass('select2-hidden-accessible')) {
                                                $(radiologySelectModal).select2('destroy');
                                            }
                                            
                                            // Initialize Select2 for multiselect (dropdownParent: body avoids overflow clipping)
                                            $(radiologySelectModal).select2({
                                                placeholder: 'Select Tests',
                                                allowClear: true,
                                                width: '100%',
                                                dropdownParent: $('body'),
                                                dropdownCssClass: 'pathology-radiology-dropdown',
                                                minimumResultsForSearch: 0,
                                                multiple: true,
                                                closeOnSelect: false
                                            });
                                            console.log('✅ Radiology Select2 initialized with', radiologySelectModal.options.length, 'options');
                                            
                                            // Verify Select2 is working
                                            if ($(radiologySelectModal).hasClass('select2-hidden-accessible')) {
                                                console.log('✅ Radiology Select2 is active and ready');
                                            } else {
                                                console.warn('⚠️ Radiology Select2 initialization may have failed');
                                            }
                                        } catch(e) {
                                            console.error('❌ Error initializing radiology Select2:', e);
                                        }
                                    } else {
                                        console.error('❌ jQuery or Select2 not available for radiology');
                                    }
                                }, 200);
                            } else {
                                console.warn('⚠️ No radiology data received or empty array');
                                const noDataOption = document.createElement('option');
                                noDataOption.value = '';
                                noDataOption.textContent = 'No tests available';
                                radiologySelectModal.appendChild(noDataOption);
                                
                                // Still try to initialize Select2 even with no data
                                setTimeout(() => {
                                    if (window.jQuery && $.fn.select2 && radiologySelectModal) {
                                        try {
                                            $(radiologySelectModal).select2({
                                                placeholder: 'No tests available',
                                                allowClear: true,
                                                width: '100%',
                                                dropdownParent: $('#addPrescriptionModal'),
                                                minimumResultsForSearch: 0,
                                                multiple: true,
                                                disabled: true
                                            });
                                        } catch(e) {
                                            console.error('Error initializing empty radiology Select2:', e);
                                        }
                                    }
                                }, 200);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching radiologies:', error);
                            if (radiologySelectModal) {
                                radiologySelectModal.innerHTML = '<option value="">Error loading tests</option>';
                            }
                        });
                } else {
                    console.error('radiologySelectModal element not found');
                }

            // Ensure multiselect functions are called after data loads
            setTimeout(() => {
                // Double-check initialization after a delay
                const pathEl = document.getElementById('pathologyOpt');
                const radEl = document.getElementById('radiologyOpt');
                
                if (pathEl) {
                    console.log('Pathology select status:', {
                        options: pathEl.options.length,
                        hasSelect2: $(pathEl).hasClass('select2-hidden-accessible'),
                        elementExists: !!pathEl
                    });
                    if (pathEl.options.length > 1 && !$(pathEl).hasClass('select2-hidden-accessible')) {
                        console.log('Re-initializing pathology Select2...');
                        if (typeof initializePathologyMultiselect === 'function') {
                            initializePathologyMultiselect();
                        }
                    }
                }
                
                if (radEl) {
                    console.log('Radiology select status:', {
                        options: radEl.options.length,
                        hasSelect2: $(radEl).hasClass('select2-hidden-accessible'),
                        elementExists: !!radEl
                    });
                    if (radEl.options.length > 1 && !$(radEl).hasClass('select2-hidden-accessible')) {
                        console.log('Re-initializing radiology Select2...');
                        if (typeof initializeRadiologyMultiselect === 'function') {
                            initializeRadiologyMultiselect();
                        }
                    }
                }

                    // Ensure categories, intervals, and durations are loaded, fetch if not
                    const needsCategories = !window.medicineCategories || !Array.isArray(window.medicineCategories) || window.medicineCategories.length === 0;
                    const needsIntervals = !window.doseIntervals || !Array.isArray(window.doseIntervals) || window.doseIntervals.length === 0;
                    const needsDurations = !window.doseDurations || !Array.isArray(window.doseDurations) || window.doseDurations.length === 0;

                    if (needsCategories || needsIntervals || needsDurations) {
                        console.log('Fetching missing medicine data...', {
                            categories: needsCategories,
                            intervals: needsIntervals,
                            durations: needsDurations
                        });
                        var catUrl = "{{ url(route('getMedicineCategories')) }}";
                        var intUrl = "{{ url(route('getDoseIntervals')) }}";
                        var durUrl = "{{ url(route('getDoseDurations')) }}";
                        Promise.all([
                            needsCategories ? fetch(catUrl).then(res => res.json().then(data => Array.isArray(data) ? data : []).catch(() => [])) : Promise.resolve(window.medicineCategories || []),
                            needsIntervals ? fetch(intUrl).then(res => res.json().then(data => Array.isArray(data) ? data : []).catch(() => [])) : Promise.resolve(window.doseIntervals || []),
                            needsDurations ? fetch(durUrl).then(res => res.json().then(data => Array.isArray(data) ? data : []).catch(() => [])) : Promise.resolve(window.doseDurations || [])
                        ]).then(([categories, intervals, durations]) => {
                            window.medicineCategories = Array.isArray(categories) ? categories : [];
                            window.doseIntervals = Array.isArray(intervals) ? intervals : [];
                            window.doseDurations = Array.isArray(durations) ? durations : [];
                            console.log('Medicine data fetched on modal open:', window.medicineCategories.length, 'categories', window.doseIntervals.length, 'intervals', window.doseDurations.length, 'durations');
                            initializeMedicineRows();
                        })
                        .catch(error => {
                            console.error('Error fetching medicine data:', error);
                            window.medicineCategories = window.medicineCategories || [];
                            window.doseIntervals = window.doseIntervals || [];
                            window.doseDurations = window.doseDurations || [];
                            initializeMedicineRows();
                        });
                    } else {
                        console.log('All data already loaded:', {
                            categories: window.medicineCategories.length,
                            intervals: window.doseIntervals.length,
                            durations: window.doseDurations.length
                        });
                        initializeMedicineRows();
                    }

                // Load medicines when modal is fully shown
                if (typeof window.loadMedicinesOnModalOpen === 'function') {
                    window.loadMedicinesOnModalOpen();
                } else {
                    console.error('loadMedicinesOnModalOpen function not found');
                }
            }, 200);
        };
        
        // Function is already global (defined as window.loadPathologyRadiologyData)
        console.log('✅ loadPathologyRadiologyData function defined globally');
        
        if (createPrescriptionModal) {
            // Move modal to body when about to show - escapes overflow/transform parents that clip it
            createPrescriptionModal.addEventListener('show.bs.modal', function(event) {
                if (createPrescriptionModal.parentNode !== document.body) {
                    document.body.appendChild(createPrescriptionModal);
                    console.log('✅ Add Prescription modal moved to body');
                }
            });
            // Listen for Bootstrap modal shown event
            createPrescriptionModal.addEventListener('shown.bs.modal', function(event) {
                console.log('✅ Bootstrap shown.bs.modal event fired');
                loadPathologyRadiologyData();
                // Load medicine category, dose interval, dose duration if missing and init rows
                (function ensureMedicineData() {
                    var needsCat = !window.medicineCategories || !Array.isArray(window.medicineCategories) || window.medicineCategories.length === 0;
                    var needsInt = !window.doseIntervals || !Array.isArray(window.doseIntervals) || window.doseIntervals.length === 0;
                    var needsDur = !window.doseDurations || !Array.isArray(window.doseDurations) || window.doseDurations.length === 0;
                    if (needsCat || needsInt || needsDur) {
                        var catUrl = "{{ url(route('getMedicineCategories')) }}", intUrl = "{{ url(route('getDoseIntervals')) }}", durUrl = "{{ url(route('getDoseDurations')) }}";
                        Promise.all([
                            needsCat ? fetch(catUrl).then(function(r){ return r.json().then(function(d){ return Array.isArray(d) ? d : []; }).catch(function(){ return []; }); }) : Promise.resolve(window.medicineCategories || []),
                            needsInt ? fetch(intUrl).then(function(r){ return r.json().then(function(d){ return Array.isArray(d) ? d : []; }).catch(function(){ return []; }); }) : Promise.resolve(window.doseIntervals || []),
                            needsDur ? fetch(durUrl).then(function(r){ return r.json().then(function(d){ return Array.isArray(d) ? d : []; }).catch(function(){ return []; }); }) : Promise.resolve(window.doseDurations || [])
                        ]).then(function(arr) {
                            window.medicineCategories = arr[0] || [];
                            window.doseIntervals = arr[1] || [];
                            window.doseDurations = arr[2] || [];
                            if (typeof window.initializeMedicineRows === 'function') window.initializeMedicineRows();
                        }).catch(function(){ if (typeof window.initializeMedicineRows === 'function') window.initializeMedicineRows(); });
                    } else if (typeof window.initializeMedicineRows === 'function') {
                        window.initializeMedicineRows();
                    }
                })();
                // Re-init Select2 on pathology/radiology when modal is visible (multiple delays to catch async data)
                [300, 800, 1500].forEach(function(delay) {
                    setTimeout(function() {
                        if (typeof window.initPathologyRadiologySelect2 === 'function') {
                            window.initPathologyRadiologySelect2();
                        } else if (window.jQuery && $.fn.select2) {
                            var $p = $('#pathologyOpt'), $r = $('#radiologyOpt');
                            if ($p.length && $p[0].options.length > 0) {
                                try { if ($p.data('select2')) $p.select2('destroy'); $p.select2({ placeholder: 'Select Tests', allowClear: true, width: '100%', dropdownParent: $('body'), dropdownCssClass: 'pathology-radiology-dropdown', minimumResultsForSearch: 0, multiple: true, closeOnSelect: false }); } catch(e) {}
                            }
                            if ($r.length && $r[0].options.length > 0) {
                                try { if ($r.data('select2')) $r.select2('destroy'); $r.select2({ placeholder: 'Select Tests', allowClear: true, width: '100%', dropdownParent: $('body'), dropdownCssClass: 'pathology-radiology-dropdown', minimumResultsForSearch: 0, multiple: true, closeOnSelect: false }); } catch(e) {}
                            }
                        }
                    }, delay);
                });
            });
            
            // Also listen for manual modal show (when modal becomes visible)
            const modalObserver = new MutationObserver(function(mutations) {
                const modal = document.getElementById('addPrescriptionModal');
                if (modal && modal.classList.contains('show') && modal.style.display !== 'none') {
                    console.log('✅ Modal detected as visible via MutationObserver');
                    // Debounce: only trigger once
                    if (!modal.dataset.dataLoaded) {
                        modal.dataset.dataLoaded = 'true';
                        setTimeout(() => {
                            loadPathologyRadiologyData();
                        }, 100);
                    }
                } else {
                    // Reset flag when modal is hidden
                    if (modal) {
                        modal.dataset.dataLoaded = '';
                    }
                }
            });
            
            // Observe modal for class/style changes
            if (createPrescriptionModal) {
                modalObserver.observe(createPrescriptionModal, {
                    attributes: true,
                    attributeFilter: ['class', 'style', 'aria-hidden']
                });
            }
            
            // Fallback: Check periodically if modal is visible and data not loaded
            setInterval(function() {
                const modal = document.getElementById('addPrescriptionModal');
                const pathSelect = document.getElementById('pathologyOpt');
                const radSelect = document.getElementById('radiologyOpt');
                
                if (modal && modal.classList.contains('show') && modal.style.display !== 'none') {
                    // Check if data needs loading
                    const needsPathology = pathSelect && (pathSelect.options.length <= 1 || pathSelect.options[0].value === '');
                    const needsRadiology = radSelect && (radSelect.options.length <= 1 || radSelect.options[0].value === '');
                    
                    if ((needsPathology || needsRadiology) && !modal.dataset.dataLoaded) {
                        console.log('🔄 Fallback: Modal visible but data missing, loading now...');
                        modal.dataset.dataLoaded = 'true';
                        loadPathologyRadiologyData();
                    }
                } else {
                    // Reset flag when modal is hidden
                    if (modal) {
                        modal.dataset.dataLoaded = '';
                    }
                }
            }, 500);
        }

        // Helper function to initialize medicine rows (expose globally for fallback)
        function initializeMedicineRows() {
                    const container = document.getElementById("medicineContainer");
                    if (container && window.medicineCategories && Array.isArray(window.medicineCategories)) {
                        const medicineRows = container.querySelectorAll(".medicine-row");
                        console.log('Found', medicineRows.length, 'medicine rows to initialize');
                        medicineRows.forEach((row, index) => {
                            // Check if category select is empty or only has placeholder
                            const categorySelect = row.querySelector(".medicine_category");
                            if (categorySelect) {
                                const hasOptions = categorySelect.options.length > 1;
                                console.log(`Row ${index + 1} category select:`, {
                                    found: true,
                                    options: categorySelect.options.length,
                                    hasOptions: hasOptions
                                });
                                if (!hasOptions) {
                                    console.log(`Initializing medicine row ${index + 1} in modal...`);
                                    if (typeof window.initRow === 'function') {
                                        window.initRow(row);
                                    } else {
                                        // If initRow is not defined, populate all selects directly
                                        console.warn('window.initRow function not found, populating selects directly');

                                        // Populate category select
                                        if (typeof window.fillSelect === 'function') {
                                            // Destroy Select2 if exists
                                            if (window.jQuery && $(categorySelect).hasClass('select2-hidden-accessible')) {
                                                $(categorySelect).select2('destroy');
                                            }
                                            // Fill category select
                                            window.fillSelect(categorySelect, window.medicineCategories, "medicine_category");
                                            console.log('Category select populated directly');
                                        } else {
                                            // Manual fill as fallback
                                            categorySelect.innerHTML = '<option value="">Select Category</option>';
                                            window.medicineCategories.forEach(cat => {
                                                if (cat && cat.id) {
                                                    const opt = document.createElement("option");
                                                    opt.value = cat.id;
                                                    opt.textContent = cat.medicine_category || cat.name || '';
                                                    categorySelect.appendChild(opt);
                                                }
                                            });
                                            // Initialize Select2
                                            if (window.jQuery && $.fn.select2) {
                                                setTimeout(() => {
                                                    $(categorySelect).select2({
                                                        width: "100%",
                                                        placeholder: "Select Category",
                                                        allowClear: true,
                                                        minimumResultsForSearch: 0,
                                                        dropdownParent: $('#addPrescriptionModal')
                                                    });

                                                    // Ensure change event works with Select2
                                                    $(categorySelect).off('change.select2-medicine select2:select select2:clear').on('change.select2-medicine select2:select select2:clear', function() {
                                                        const categoryId = $(this).val();
                                                        const medicineSelect = $(this).closest('.medicine-row').find('.medicine_name')[0];
                                                        const doseSelect = $(this).closest('.medicine-row').find('.medicine_dosage')[0];

                                                        if (categoryId && categoryId !== '') {
                                                            console.log('Category changed via Select2:', categoryId);

                                                            // Fetch medicines by category
                                                            const baseUrl = "{{ route('getMedicines', ['categoryId' => 'ID']) }}";
                                                            const finalUrl = baseUrl.replace('ID', categoryId);

                                                            console.log('Fetching medicines from:', finalUrl);

                                                            // Show loading state
                                                            if (window.jQuery && $.fn.select2 && medicineSelect) {
                                                                $(medicineSelect).prop('disabled', true);
                                                                if ($(medicineSelect).hasClass('select2-hidden-accessible')) {
                                                                    $(medicineSelect).select2('destroy');
                                                                }
                                                                medicineSelect.innerHTML = '<option value="">Loading medicines...</option>';
                                                            }

                                                            fetch(finalUrl)
                                                                .then(res => {
                                                                    if (!res.ok) throw new Error('Failed to fetch medicines');
                                                                    return res.json();
                                                                })
                                                                .then(data => {
                                                                    console.log('Medicines loaded for category:', data);

                                                                    if (!medicineSelect) return;

                                                                    // Destroy Select2 before filling
                                                                    if (window.jQuery && $(medicineSelect).hasClass('select2-hidden-accessible')) {
                                                                        $(medicineSelect).select2('destroy');
                                                                    }

                                                                    // Fill medicines
                                                                    if (typeof window.fillSelect === 'function') {
                                                                        window.fillSelect(medicineSelect, data, "medicine_name");
                                                                    } else {
                                                                        // Manual fill
                                                                        medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
                                                                        if (Array.isArray(data)) {
                                                                            data.forEach(item => {
                                                                                if (item && item.id) {
                                                                                    const opt = document.createElement("option");
                                                                                    opt.value = item.id;
                                                                                    opt.textContent = item.medicine_name || item.name || 'Unknown';
                                                                                    medicineSelect.appendChild(opt);
                                                                                }
                                                                            });
                                                                        }
                                                                    }

                                                                    // Reinitialize Select2 after filling
                                                                    if (window.jQuery && $.fn.select2) {
                                                                        setTimeout(() => {
                                                                            $(medicineSelect).select2({
                                                                                width: "100%",
                                                                                placeholder: "Search Medicine...",
                                                                                allowClear: true,
                                                                                minimumResultsForSearch: 0,
                                                                                dropdownParent: $('#addPrescriptionModal'),
                                                                                language: {
                                                                                    noResults: function() {
                                                                                        return "No medicines found";
                                                                                    },
                                                                                    searching: function() {
                                                                                        return "Searching...";
                                                                                    }
                                                                                }
                                                                            });
                                                                            $(medicineSelect).prop('disabled', false);
                                                                        }, 100);
                                                                    }

                                                                    // Clear dose select
                                                                    if (doseSelect) {
                                                                        if (window.jQuery && $(doseSelect).hasClass('select2-hidden-accessible')) {
                                                                            $(doseSelect).select2('destroy');
                                                                        }
                                                                        doseSelect.innerHTML = '<option value="">Select Dose (Choose Medicine First)</option>';
                                                                    }
                                                                })
                                                                .catch(error => {
                                                                    console.error('Error loading medicines:', error);
                                                                    if (window.jQuery && $.fn.select2 && medicineSelect) {
                                                                        if ($(medicineSelect).hasClass('select2-hidden-accessible')) {
                                                                            $(medicineSelect).select2('destroy');
                                                                        }
                                                                        medicineSelect.innerHTML = '<option value="">Error loading medicines</option>';
                                                                        $(medicineSelect).prop('disabled', false);
                                                                    }
                                                                });
                                                        } else {
                                                            if (medicineSelect) {
                                                                medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
                                                                if (window.jQuery && $(medicineSelect).hasClass('select2-hidden-accessible')) {
                                                                    $(medicineSelect).trigger('change');
                                                                }
                                                            }
                                                        }
                                                    });
                                                }, 100);
                                            }
                                            console.log('Category select filled manually');
                                        }

                                        // Populate interval select
                                        const intervalSelect = row.querySelector(".interval_dosage");
                                        if (intervalSelect && window.doseIntervals && Array.isArray(window.doseIntervals)) {
                                            if (typeof window.fillSelect === 'function') {
                                                if (window.jQuery && $(intervalSelect).hasClass('select2-hidden-accessible')) {
                                                    $(intervalSelect).select2('destroy');
                                                }
                                                window.fillSelect(intervalSelect, window.doseIntervals, "name");
                                                console.log('Interval select populated');
                                            } else {
                                                intervalSelect.innerHTML = '<option value="">Select Interval</option>';
                                                window.doseIntervals.forEach(interval => {
                                                    if (interval && interval.id) {
                                                        const opt = document.createElement("option");
                                                        opt.value = interval.id;
                                                        opt.textContent = interval.name || '';
                                                        intervalSelect.appendChild(opt);
                                                    }
                                                });
                                                if (window.jQuery && $.fn.select2) {
                                                    setTimeout(() => {
                                                        $(intervalSelect).select2({
                                                            width: "100%",
                                                            placeholder: "Select Interval",
                                                            allowClear: true,
                                                            minimumResultsForSearch: 0,
                                                            dropdownParent: $('#addPrescriptionModal')
                                                        });
                                                    }, 100);
                                                }
                                            }
                                        }

                                        // Populate duration select
                                        const durationSelect = row.querySelector(".duration_dosage");
                                        if (durationSelect && window.doseDurations && Array.isArray(window.doseDurations)) {
                                            if (typeof window.fillSelect === 'function') {
                                                if (window.jQuery && $(durationSelect).hasClass('select2-hidden-accessible')) {
                                                    $(durationSelect).select2('destroy');
                                                }
                                                window.fillSelect(durationSelect, window.doseDurations, "name");
                                                console.log('Duration select populated');
                                            } else {
                                                durationSelect.innerHTML = '<option value="">Select Duration</option>';
                                                window.doseDurations.forEach(duration => {
                                                    if (duration && duration.id) {
                                                        const opt = document.createElement("option");
                                                        opt.value = duration.id;
                                                        opt.textContent = duration.name || '';
                                                        durationSelect.appendChild(opt);
                                                    }
                                                });
                                                if (window.jQuery && $.fn.select2) {
                                                    setTimeout(() => {
                                                        $(durationSelect).select2({
                                                            width: "100%",
                                                            placeholder: "Select Duration",
                                                            allowClear: true,
                                                            minimumResultsForSearch: 0,
                                                            dropdownParent: $('#addPrescriptionModal')
                                                        });
                                                    }, 100);
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    console.log(`Row ${index + 1} already has options, skipping`);
                                }
                            } else {
                                console.warn(`Row ${index + 1}: category select not found`);
                            }
                        });
                    } else {
                        console.warn('Container not found or categories not loaded:', {
                            container: !!container,
                            categories: window.medicineCategories ? window.medicineCategories.length : 'not loaded'
                        });
                    }
                }
        window.initializeMedicineRows = initializeMedicineRows;
            });

            // Clear selects when modal is hidden (safely)
            if (createPrescriptionModal) {
                createPrescriptionModal.addEventListener('hide.bs.modal', function(event) {
                // Clear selected test lists so next open starts fresh
                if (window.selectedPathologyList) window.selectedPathologyList.length = 0;
                if (window.selectedRadiologyList) window.selectedRadiologyList.length = 0;
                var pathList = document.getElementById('pathologySelectedList');
                var radList = document.getElementById('radiologySelectedList');
                if (pathList) pathList.innerHTML = '';
                if (radList) radList.innerHTML = '';

                const pathologySelect = document.getElementById('pathologyOpt');
                const radiologySelect = document.getElementById('radiologyOpt');

                // Destroy Select2 instances to allow re-initialization (safely)
                if (pathologySelect && window.jQuery) {
                    const $pathEl = $(pathologySelect);
                    if ($pathEl.length && ($pathEl.hasClass('select2-hidden-accessible') || $pathEl.data('select2'))) {
                        try {
                            $pathEl.select2('destroy');
                        } catch(e) {
                            // Ignore - Select2 not properly initialized
                        }
                    }
                }
                if (radiologySelect && window.jQuery) {
                    const $radEl = $(radiologySelect);
                    if ($radEl.length && ($radEl.hasClass('select2-hidden-accessible') || $radEl.data('select2'))) {
                        try {
                            $radEl.select2('destroy');
                        } catch(e) {
                            // Ignore - Select2 not properly initialized
                        }
                    }
                }
            });
        }
    });
</script>

<script>
    // Ensure jQuery + select2 are loaded
    $(function() {
        // Initialize all multiselect2 elements at once (including finding_type and finding)
        // This matches the working version pattern
        // Only process elements that exist
        const findingTypeEl = document.getElementById('finding_type');
        const multiselect2Elements = document.querySelectorAll('.multiselect2');
        
        // Process finding_type if it exists - only destroy when Select2 was actually initialized (data('select2'))
        if (findingTypeEl && window.jQuery) {
            const $findingType = $(findingTypeEl);
            if ($findingType.length && $findingType.data('select2')) {
                try {
                    $findingType.select2('destroy');
                } catch(e) {
                    // Ignore - Select2 not properly initialized
                }
            }
        }
        
        // Process multiselect2 elements - only destroy when Select2 was actually initialized
        if (multiselect2Elements.length && window.jQuery) {
            multiselect2Elements.forEach(function(el) {
                const $el = $(el);
                if ($el.length && $el.data('select2')) {
                    try {
                        $el.select2('destroy');
                    } catch(e) {
                        // Ignore - Select2 not properly initialized
                    }
                }
            });
        }

        // Initialize finding category and findings selects (multiselect)
        $('#finding_type, #finding').select2({
            placeholder: 'Select',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addPrescriptionModal'),
            multiple: true
        });

        console.log('Select2 initialized for finding_type and finding selects');

        // Initialize pathology and radiology Select2 if data is already loaded (multiselect with filter)
        setTimeout(function() {
            const pathologySelect = document.getElementById('pathologyOpt');
            const radiologySelect = document.getElementById('radiologyOpt');

            if (pathologySelect && pathologySelect.options.length > 0 && !$(pathologySelect).hasClass('select2-hidden-accessible')) {
                $(pathologySelect).select2({
                    placeholder: 'Select Tests',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addPrescriptionModal'),
                    multiple: true,
                    closeOnSelect: false
                });
            }

            if (radiologySelect && radiologySelect.options.length > 0 && !$(radiologySelect).hasClass('select2-hidden-accessible')) {
                $(radiologySelect).select2({
                    placeholder: 'Select Tests',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addPrescriptionModal'),
                    multiple: true,
                    closeOnSelect: false
                });
            }
        }, 500);

        // helper: adjust visual size of a Select2 multiple box based on selected count
        function adjustSelectSize($select) {
            var vals = $select.val();
            var count = Array.isArray(vals) ? vals.length : 0;
            var $container = $select.next('.select2-container').find('.select2-selection--multiple');

            if (!$container.length) return;

            // Strategy:
            // - keep one line for up to 3 tags
            // - add another line for each additional ~3 tags
            // - cap max height to avoid huge boxes
            var tagsPerLine = 3;
            var baseLineHeight = 36; // px for one-line height (approx)
            var extraLineHeight = 28; // px per additional line
            var lines = 1 + Math.floor(Math.max(0, count - 1) / tagsPerLine); // >=1
            var maxLines = 6;
            lines = Math.min(lines, maxLines);
            var height = baseLineHeight + (lines - 1) * extraLineHeight;

            $container.css({
                'min-height': height + 'px',
                'max-height': (maxLines * baseLineHeight) + 'px',
                'overflow-y': 'auto'
            });
        }

        // initial adjust for all existing multiselects
        $('#finding_type, #pathologyOpt, #radiologyOpt').each(function() {
            adjustSelectSize($(this));
        });

        // adjust on change / select2 events
        $(document).on('change', '#finding_type, #pathologyOpt, #radiologyOpt', function() {
            adjustSelectSize($(this));
        });
        // also catch select2 specific events for better responsiveness
        $(document).on('select2:select select2:unselect', '#finding_type, #pathologyOpt, #radiologyOpt', function() {
            adjustSelectSize($(this));
        });

        // Remove medicine row
        // $(document).on('click', '.delete_row', function() {
        //     var $tr = $(this).closest('tr');
        //     $tr.remove();
        // });

        // Add medicine row (simple clone of last row)
        // $('.add-record').on('click', function(e) {
        //     e.preventDefault();
        //     var $last = $('#tableID tbody tr:last');
        //     var $clone = $last.clone(true, true);
        //     // update hidden row index and any name attributes (simple increment)
        //     var lastIndex = $('#tableID tbody tr').length;
        //     var newIndex = lastIndex + 1;
        //     $clone.attr('id', 'row' + newIndex);
        //     $clone.find('input[name="rows[]"]').val(newIndex);
        //     $clone.find('[name]').each(function() {
        //         var name = $(this).attr('name');
        //         if (!name) return;
        //         // replace trailing digits with newIndex (basic)
        //         var newName = name.replace(/(\d+)(?!.*\d)/, newIndex);
        //         $(this).attr('name', newName);
        //     });
        //     // clear values in cloned inputs/selects
        //     $clone.find('input[type!="hidden"], textarea').val('');
        //     $clone.find('select').val(null).trigger('change');

        //     // If cloned selects are using select2, destroy and re-init to avoid duplicate containers
        //     $clone.find('select').each(function() {
        //         var $s = $(this);
        //         if ($s.hasClass('select2-hidden-accessible')) {
        //             try {
        //                 $s.select2('destroy');
        //             } catch (e) {
        //                 /* ignore */
        //             }
        //             $s.select2({
        //                 placeholder: 'Select',
        //                 width: '100%'
        //             });
        //             adjustSelectSize($s);
        //         }
        //     });

        //     $('#tableID tbody').append($clone);
        // });
    });
</script>

<script>
    // Shorter JS: single global add button, clone rows multiple times, safe select2 re-init
    $(function() {
        var $modal = $('#addPrescriptionModal');
        var $body = $modal.length ? $modal : $('body');

        function replaceIndex(name, idx) {
            if (!name) return name;
            return /_\d+$/.test(name) ? name.replace(/_\d+$/, '_' + idx) : name.replace(/(\d+)(?!.*\d)/, idx);
        }

        function initSelect($s) {
            try {
                if ($s.hasClass('select2-hidden-accessible')) $s.select2('destroy');
            } catch (e) {}
            $s.select2({
                placeholder: 'Select',
                width: '100%'
            });
        }

        function populateMedicines(cat, $med, idx) {
            $med.prop('disabled', true).empty().append('<option>Loading...</option>');

            // Use the correct route - getMedicines with categoryId
            if (cat && cat !== '') {
                const baseUrl = "{{ route('getMedicines', ['categoryId' => 'ID']) }}";
                const finalUrl = baseUrl.replace('ID', cat);

                $.get(finalUrl)
                    .done(function(data) {
                        $med.empty().append('<option value="">Select</option>');
                        (data || []).forEach(function(m) {
                            $med.append($('<option>').val(m.id).text(m.medicine_name || m.name || 'Unknown'));
                        });
                    })
                    .fail(function() {
                        console.error('Error loading medicines by category');
                        $med.empty().append('<option value="">Error loading medicines</option>');
                    })
                    .always(function() {
                        $med.prop('disabled', false).trigger('change');
                    });
            } else {
                // If no category, load all medicines
                const allMedicinesUrl = "{{ route('pharmacy.api.medicines') }}";
                $.get(allMedicinesUrl)
                    .done(function(data) {
                        $med.empty().append('<option value="">Select</option>');
                        (data || []).forEach(function(m) {
                            $med.append($('<option>').val(m.id).text(m.medicine_name || m.name || 'Unknown'));
                        });
                    })
                    .fail(function() {
                        console.error('Error loading all medicines');
                        $med.empty().append('<option value="">Error loading medicines</option>');
                    })
                    .always(function() {
                        $med.prop('disabled', false).trigger('change');
                    });
            }
        }

        function initRow($row) {
            var idx = $row.data('row') || $('.medicine-row').length;
            // Don't initialize Select2 for category select here - it will be initialized after categories load
            $row.find('select').each(function() {
                var $select = $(this);
                // Skip category select - it will be initialized by window.initRow after categories load
                if (!$select.hasClass('medicine_category')) {
                    initSelect($select);
                }
            });
            // Handle category change - works with both native select and Select2
            $row.off('change.cat change.select2-cat').on('change.cat change.select2-cat', '.medicine_category', function() {
                const categoryId = $(this).val();
                const medicineSelect = $row.find('.medicine_name');
                console.log('Category changed:', categoryId);
                if (categoryId && categoryId !== '') {
                    populateMedicines(categoryId, medicineSelect, idx);
                } else {
                    medicineSelect.empty().append('<option value="">Select Medicine</option>');
                    if (medicineSelect.hasClass('select2-hidden-accessible')) {
                        medicineSelect.trigger('change');
                    }
                }
            });

            // Also handle Select2 change event specifically
            $row.find('.medicine_category').off('select2:select select2:clear').on('select2:select select2:clear', function() {
                const categoryId = $(this).val();
                const medicineSelect = $row.find('.medicine_name');
                console.log('Category changed via Select2 event:', categoryId);
                if (categoryId && categoryId !== '') {
                    populateMedicines(categoryId, medicineSelect, idx);
                } else {
                    medicineSelect.empty().append('<option value="">Select Medicine</option>');
                    if (medicineSelect.hasClass('select2-hidden-accessible')) {
                        medicineSelect.trigger('change');
                    }
                }
            });
            $row.find('input[type!="hidden"], textarea').val('');
            $row.find('select').val(null).trigger('change');
            $('#stock_info_' + idx).text('');
        }

        function reindex() {
            $('.medicine-row').each(function(i) {
                var idx = i + 1,
                    $r = $(this);
                $r.attr('data-row', idx).attr('id', 'row' + idx);
                $r.find('[name]').each(function() {
                    var $el = $(this),
                        n = $el.attr('name'),
                        id = $el.attr('id');
                    if (n) $el.attr('name', replaceIndex(n, idx));
                    if (id) $el.attr('id', /\d+$/.test(id) ? id.replace(/\d+$/, idx) : id +
                        '_' + idx);
                    if ($el.hasClass('medicine_name')) $el.attr('data-rowid', idx);
                });
                $r.find('[id^=stock_info_]').attr('id', 'stock_info_' + idx);
            });
        }

        // ensure single add button: detach first found, remove others, place after rows in wrapper
        var $foundAdd = $('.add-record').first().length ? $('.add-record').first().detach() : null;
        $('.add-record').remove();
        var $addWrapper = $('<div class="add-record-wrapper col-sm-12 mt-2"></div>');
        if ($foundAdd) $addWrapper.append($foundAdd);
        // place wrapper after the last medicine-row; if none, append to a sensible container
        var $lastRow = $('.medicine-row').last();
        if ($lastRow.length) {
            $lastRow.after($addWrapper);
        } else {
            $body.append($addWrapper);
        }

        // initial setup: set data-row and init rows
        $('.medicine-row').each(function(i) {
            $(this).attr('data-row', i + 1);
            initRow($(this));
        });
        reindex();

        // add new row using the single add button
        $body.on('click', '.add-record', function(e) {
            e.preventDefault();
            var $last = $('.medicine-row').last();

            // destroy select2 on last before cloning to avoid cloning select2 markup
            $last.find('select').each(function() {
                try {
                    if ($(this).hasClass('select2-hidden-accessible')) $(this).select2(
                        'destroy');
                } catch (err) {}
            });

            var $new = $last.clone(false, false); // shallow clone to avoid copying handlers
            // Remove any add button inside clone (just in case)
            $new.find('.add-record').remove();

            // Clear values
            $new.find('input[type!="hidden"], textarea').val('');
            $new.find('select').val(null);

            // Insert new row before the add button wrapper so the add button remains single
            $addWrapper.before($new);

            // reindex and (re)init
            reindex();
            // init select2 for all selects in the new row
            initRow($new);

            // re-init select2 for the previous last row as cloning destroyed it
            initRow($last);
        });

        // delete row
        $body.on('click', '.delete_row', function(e) {
            e.preventDefault();
            var $row = $(this).closest('.medicine-row');
            if ($('.medicine-row').length <= 1) {
                $row.find('input[type!="hidden"], textarea').val('');
                $row.find('select').val(null).trigger('change');
                $row.find('[id^=stock_info_]').text('');
                return;
            }
            $row.remove();
            reindex();
        });
    });
</script>

<script>
    function initEditor(toolbarId, editorId, selectId) {
        const toolbar = document.getElementById(toolbarId);
        const editor = document.getElementById(editorId);
        const formatBlockSelect = document.getElementById(selectId);

        // Check if all elements exist before adding event listeners
        if (!toolbar || !editor || !formatBlockSelect) {
            console.warn('Editor elements not found:', { toolbarId, editorId, selectId });
            return;
        }

        toolbar.addEventListener('click', (e) => {
            const btn = e.target.closest('button');
            if (!btn) return;

            const cmd = btn.dataset.cmd;
            const val = btn.dataset.value || null;

            if (cmd === 'small') {
                document.execCommand('fontSize', false, '2');
            } else {
                document.execCommand(cmd, false, val);
            }
            editor.focus();
        });

        formatBlockSelect.addEventListener('change', (e) => {
            const value = e.target.value;
            document.execCommand('formatBlock', false, value);
            editor.focus();
        });
    }

    // Initialize both editors when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Header editor - using correct IDs from HTML
        initEditor('toolbar', 'editor', 'formatBlock');
        // Footer editor
        initEditor('toolbar-footer', 'editor-footer', 'formatBlock-footer');
    });
</script>
