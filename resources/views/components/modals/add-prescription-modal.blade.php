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
<div class="modal fade" id="addPrescriptionModal" tabindex="-1" aria-labelledby="addPrescriptionModalLabel"
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
            <form action="{{ route('ipd.addPrescription') }}" id="prescriptionForm" method="post" enctype="multipart/form-data">@csrf
                <div class="modal-body" style="max-height: calc(100vh - 160px); overflow-x:hiden;">
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
                                                    <select class="form-select multiselect2 select2-hidden-accessible"
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
                                                    <select class="form-control multiselect2 select2-hidden-accessible"
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
                                        <select class="form-control multiselect2 pathology-test-select" name="pathology[]" id="pathologyOpt" multiple style="width: 100%;">
                                            <option value="">Select Tests</option>
                                            @if(isset($pathologies) && count($pathologies) > 0)
                                                @foreach($pathologies as $pathology)
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
                                        <select class="form-control multiselect2 radiology-test-select" name="radiology[]" id="radiologyOpt" multiple style="width: 100%;">
                                            <option value="">Select Tests</option>
                                            @if(isset($radiologies) && count($radiologies) > 0)
                                                @foreach($radiologies as $radiology)
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
                                        <label class="form-label">Prescribe By <span class="text-danger">*</span></label>
                                        <select class="form-control select2" style="width: 100%" name="prescribe_by" id="prescribe_by" required>
                                            <option value="">Select Doctor</option>
                                            @php
                                                $doctors = \App\Models\Doctor::all();
                                            @endphp
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->doctor_id ?? 'N/A' }})</option>
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
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOMContentLoaded fired for prescription modal');
        const createPrescriptionModal = document.getElementById("addPrescriptionModal");
        const findingCategorySelect = document.getElementById('finding_type');
        const findingsSelect = document.getElementById('finding');
        const pathologySelect = document.getElementById('pathologyOpt');
        const radiologySelect = document.getElementById('radiologyOpt');

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

        // Initialize pathology and radiology selects
        if (pathologySelect) {
            pathologySelect.innerHTML = '<option value="">Loading...</option>';
            console.log('pathologyOpt element found and initialized');
        } else {
            console.error('pathologyOpt element NOT FOUND on page load!');
        }
        if (radiologySelect) {
            radiologySelect.innerHTML = '<option value="">Loading...</option>';
            console.log('radiologyOpt element found and initialized');
        } else {
            console.error('radiologyOpt element NOT FOUND on page load!');
        }
        const opdRoute = "{{ route('opd.addPrescription') }}";
        const ipdRoute = "{{ route('ipd.addPrescription') }}";
        createPrescriptionModal.addEventListener('show.bs.modal', function(event) {
            let form = document.getElementById("prescriptionForm");
            const opdIdField = document.getElementById('opd_id');
            const ipdIdField = document.getElementById('ipd_id');

            var button = event.relatedTarget; // Button that triggered the modal

            // Fallback: if relatedTarget is not available, try to find the button
            if (!button) {
                // Try to find button with data-ipd-id or data-id attribute
                button = document.querySelector('[data-bs-target="#addPrescriptionModal"][data-ipd-id], [data-bs-target="#addPrescriptionModal"][data-id]');
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

        // Add form submit validation to ensure fields are set
        const prescriptionForm = document.getElementById("prescriptionForm");
        if (prescriptionForm) {
            prescriptionForm.addEventListener('submit', function(e) {
                const opdIdField = document.getElementById('opd_id');
                const ipdIdField = document.getElementById('ipd_id');
                const prescribeByField = document.getElementById('prescribe_by');

                // Check if either OPD or IPD ID is set
                let opdId = opdIdField ? opdIdField.value.trim() : '';
                let ipdId = ipdIdField ? ipdIdField.value.trim() : '';
                const prescribeBy = prescribeByField ? prescribeByField.value.trim() : '';

                // If values are empty, try to get from button attributes as fallback
                if (!opdId && !ipdId) {
                    const button = document.querySelector('[data-bs-target="#addPrescriptionModal"][data-ipd-id], [data-bs-target="#addPrescriptionModal"][data-id]');
                    if (button) {
                        opdId = button.getAttribute('data-id') || button.getAttribute('data-opd-id') || '';
                        ipdId = button.getAttribute('data-ipd-id') || '';
                        if (opdId) opdId = opdId.trim();
                        if (ipdId) ipdId = ipdId.trim();

                        // Set the values in the hidden fields
                        if (opdId && opdIdField) opdIdField.value = opdId;
                        if (ipdId && ipdIdField) ipdIdField.value = ipdId;
                    }

                    // Additional fallback: try to get from URL if on IPD view page
                    if (!ipdId && window.location.pathname.includes('/ipd_view/')) {
                        const urlMatch = window.location.pathname.match(/\/ipd_view\/(\d+)/);
                        if (urlMatch && urlMatch[1]) {
                            ipdId = urlMatch[1].trim();
                            if (ipdIdField) {
                                ipdIdField.value = ipdId;
                                console.log('IPD ID set from URL in submit handler:', ipdId);
                            }
                        }
                    }
                }

                // Final check
                if (!opdId && !ipdId) {
                    e.preventDefault();
                    alert('Error: Please ensure the form is opened from a valid patient record. IPD/OPD ID is missing.');
                    console.error('Form submission blocked: Neither OPD ID nor IPD ID is set');
                    console.log('OPD Field value:', opdIdField ? opdIdField.value : 'field not found');
                    console.log('IPD Field value:', ipdIdField ? ipdIdField.value : 'field not found');
                    return false;
                }

                if (!prescribeBy) {
                    e.preventDefault();
                    alert('Please select a doctor for "Prescribe By" field.');
                    if (prescribeByField) prescribeByField.focus();
                    return false;
                }

                // Ensure form action is set correctly
                if (ipdId) {
                    this.action = ipdRoute;
                    // Ensure ipd_id is set and opd_id is cleared
                    if (ipdIdField) ipdIdField.value = ipdId;
                    if (opdIdField) opdIdField.value = '';
                } else if (opdId) {
                    this.action = opdRoute;
                    // Ensure opd_id is set and ipd_id is cleared
                    if (opdIdField) opdIdField.value = opdId;
                    if (ipdIdField) ipdIdField.value = '';
                } else {
                    // If neither is set, prevent submission
                    e.preventDefault();
                    alert('Error: Please ensure the form is opened from a valid patient record.');
                    console.error('Form submission blocked: Neither OPD ID nor IPD ID is set');
                    return false;
                }

                // Double-check action is set
                if (!this.action || this.action === '' || this.action.includes('ipd_view') || this.action.includes('opd_view')) {
                    e.preventDefault();
                    alert('Error: Form action not set correctly. Please refresh the page and try again.');
                    console.error('Form action invalid:', this.action);
                    return false;
                }

                // Final verification of hidden field values - re-read after all fallbacks
                let finalOpdId = opdIdField ? opdIdField.value.trim() : '';
                let finalIpdId = ipdIdField ? ipdIdField.value.trim() : '';

                // Last resort: get from URL if still empty and we're on IPD view page
                if (!finalIpdId && window.location.pathname.includes('/ipd_view/')) {
                    const urlMatch = window.location.pathname.match(/\/ipd_view\/(\d+)/);
                    if (urlMatch && urlMatch[1]) {
                        finalIpdId = urlMatch[1].trim();
                        if (ipdIdField) {
                            ipdIdField.value = finalIpdId;
                            console.log('IPD ID set from URL as last resort:', finalIpdId);
                        }
                    }
                }

                // Also try to get from any button with data-ipd-id attribute
                if (!finalIpdId) {
                    const allButtons = document.querySelectorAll('[data-ipd-id]');
                    if (allButtons.length > 0) {
                        const firstButton = allButtons[0];
                        const buttonIpdId = firstButton.getAttribute('data-ipd-id');
                        if (buttonIpdId && buttonIpdId.trim()) {
                            finalIpdId = buttonIpdId.trim();
                            if (ipdIdField) {
                                ipdIdField.value = finalIpdId;
                                console.log('IPD ID set from button attribute as last resort:', finalIpdId);
                            }
                        }
                    }
                }

                console.log('Form submitting with - OPD ID:', finalOpdId, 'IPD ID:', finalIpdId);
                console.log('Hidden field values - OPD:', opdIdField ? opdIdField.value : 'N/A', 'IPD:', ipdIdField ? ipdIdField.value : 'N/A');

                // Clean up empty medicine rows before submission and ensure all values are strings
                const medicineRows = document.querySelectorAll('.medicine-row');
                const rowsToRemove = [];
                const medicineValues = [];

                medicineRows.forEach((row, index) => {
                    const medicineSelect = row.querySelector('select[name="medicines[]"]');
                    const dosageSelect = row.querySelector('select[name="dosages[]"]');
                    const intervalSelect = row.querySelector('select[name="interval_dosages[]"]');
                    const durationSelect = row.querySelector('select[name="duration_dosages[]"]');

                    let medicineValue = null;

                    // Get value from Select2 if it's initialized, otherwise use regular value
                    if (medicineSelect) {
                        if (window.jQuery && $(medicineSelect).hasClass('select2-hidden-accessible')) {
                            const select2Val = $(medicineSelect).val();
                            // Handle Select2 value - it can be null, string, or array
                            if (select2Val === null || select2Val === undefined) {
                                medicineValue = null;
                            } else if (Array.isArray(select2Val)) {
                                medicineValue = select2Val.length > 0 ? String(select2Val[0]).trim() : null;
                            } else {
                                medicineValue = String(select2Val).trim();
                            }
                        } else {
                            medicineValue = medicineSelect.value ? String(medicineSelect.value).trim() : null;
                        }

                        // Ensure value is set as string in the actual select element
                        if (medicineValue && medicineValue !== 'null' && medicineValue !== 'undefined' && medicineValue !== '') {
                            medicineSelect.value = medicineValue;
                            // Also update Select2 if it's initialized
                            if (window.jQuery && $(medicineSelect).hasClass('select2-hidden-accessible')) {
                                $(medicineSelect).val(medicineValue).trigger('change');
                            }
                            medicineValues.push(medicineValue);
                        } else {
                            medicineValue = null;
                        }
                    }

                    // If medicine is empty or invalid, mark row for removal
                    if (!medicineValue || medicineValue === '' || medicineValue === 'null' || medicineValue === 'undefined') {
                        rowsToRemove.push(row);
                    } else {
                        // Ensure all related fields are also valid strings
                        if (dosageSelect) {
                            const dosageVal = dosageSelect.value ? String(dosageSelect.value).trim() : '';
                            if (!dosageVal) {
                                console.warn('Medicine row', index, 'has medicine but no dosage - keeping row');
                            }
                        }
                    }
                });

                // Remove all empty rows
                rowsToRemove.forEach(row => {
                    row.remove();
                });

                // Final check: ensure all remaining medicine selects have valid string values
                const finalMedicineRows = document.querySelectorAll('.medicine-row');
                finalMedicineRows.forEach((row, index) => {
                    const medicineSelect = row.querySelector('select[name="medicines[]"]');
                    if (medicineSelect) {
                        let finalValue = medicineSelect.value;
                        // Convert to string explicitly
                        if (finalValue === null || finalValue === undefined) {
                            // This shouldn't happen, but if it does, remove the row
                            console.error('Medicine select has null/undefined value at index', index);
                            row.remove();
                        } else {
                            finalValue = String(finalValue).trim();
                            if (finalValue === '' || finalValue === 'null' || finalValue === 'undefined') {
                                console.error('Medicine select has invalid value at index', index);
                                row.remove();
                            } else {
                                // Explicitly set as string
                                medicineSelect.value = finalValue;
                            }
                        }
                    }
                });

                // Final pass: Ensure all medicine values are explicitly set as strings
                const allMedicineSelects = document.querySelectorAll('select[name="medicines[]"]');
                allMedicineSelects.forEach((select, idx) => {
                    let val = select.value;

                    // If Select2 is initialized, sync the value
                    if (window.jQuery && $(select).hasClass('select2-hidden-accessible')) {
                        const select2Val = $(select).val();
                        if (select2Val !== null && select2Val !== undefined) {
                            val = Array.isArray(select2Val) ? select2Val[0] : select2Val;
                        }
                    }

                    // Convert to string and set
                    if (val !== null && val !== undefined) {
                        val = String(val).trim();
                        if (val !== '' && val !== 'null' && val !== 'undefined') {
                            select.value = val;
                            // Ensure Select2 is in sync
                            if (window.jQuery && $(select).hasClass('select2-hidden-accessible')) {
                                $(select).val(val).trigger('change');
                            }
                        } else {
                            console.error('Invalid medicine value at index', idx, ':', val);
                            // Remove the entire row if value is invalid
                            const row = select.closest('.medicine-row');
                            if (row) row.remove();
                        }
                    } else {
                        console.error('Null/undefined medicine value at index', idx);
                        // Remove the entire row if value is null/undefined
                        const row = select.closest('.medicine-row');
                        if (row) row.remove();
                    }
                });

                console.log('Form submitting with - OPD ID:', finalOpdId, 'IPD ID:', finalIpdId, 'Prescribe By:', prescribeBy);
                console.log('Medicine rows after cleanup:', document.querySelectorAll('.medicine-row').length);
                console.log('Medicine values being sent:', Array.from(document.querySelectorAll('select[name="medicines[]"]')).map(s => ({value: s.value, type: typeof s.value})));
                console.log('Form action:', this.action);

                // If submitting to IPD route, ensure ipd_id is not empty
                if (this.action === ipdRoute && !finalIpdId) {
                    e.preventDefault();
                    alert('Error: IPD ID is required but is missing. Please refresh the page and try again.');
                    console.error('IPD ID is empty when submitting to IPD route');
                    return false;
                }

                // If submitting to OPD route, ensure opd_id is not empty
                if (this.action === opdRoute && !finalOpdId) {
                    e.preventDefault();
                    alert('Error: OPD ID is required but is missing. Please refresh the page and try again.');
                    console.error('OPD ID is empty when submitting to OPD route');
                    return false;
                }

                // Last check: Sync all Select2 values to native select elements before submission
                if (window.jQuery && $.fn.select2) {
                    const allSelects = document.querySelectorAll('select[name="medicines[]"], select[name="dosages[]"], select[name="interval_dosages[]"], select[name="duration_dosages[]"]');
                    allSelects.forEach(select => {
                        if ($(select).hasClass('select2-hidden-accessible')) {
                            const select2Val = $(select).val();
                            if (select2Val !== null && select2Val !== undefined) {
                                const stringVal = Array.isArray(select2Val) ? String(select2Val[0]) : String(select2Val);
                                select.value = stringVal;
                                // Force update the native select
                                select.setAttribute('value', stringVal);
                            } else {
                                select.value = '';
                                select.removeAttribute('value');
                            }
                        }
                    });
                }

                // Final validation: Check all medicine values are valid strings and remove any invalid ones
                const finalMedicineSelects = document.querySelectorAll('select[name="medicines[]"]');
                const invalidRows = [];

                finalMedicineSelects.forEach((select, idx) => {
                    let val = select.value;

                    // Get from Select2 if needed
                    if (window.jQuery && $(select).hasClass('select2-hidden-accessible')) {
                        const select2Val = $(select).val();
                        if (select2Val !== null && select2Val !== undefined) {
                            val = Array.isArray(select2Val) ? select2Val[0] : select2Val;
                        }
                    }

                    // Convert to string and validate
                    if (val === null || val === undefined || val === '') {
                        console.warn('Empty medicine value at index', idx, '- removing row');
                        invalidRows.push(select.closest('.medicine-row'));
                    } else {
                        const stringVal = String(val).trim();
                        if (stringVal === '' || stringVal === 'null' || stringVal === 'undefined' || isNaN(stringVal) === false && stringVal === '0') {
                            console.warn('Invalid medicine value at index', idx, ':', stringVal, '- removing row');
                            invalidRows.push(select.closest('.medicine-row'));
                        } else {
                            // Ensure it's a valid string (should be a number as string like "1", "2", etc.)
                            select.value = stringVal;
                            select.setAttribute('value', stringVal);
                            // Also update Select2
                            if (window.jQuery && $(select).hasClass('select2-hidden-accessible')) {
                                $(select).val(stringVal).trigger('change');
                            }
                        }
                    }
                });

                // Remove invalid rows
                invalidRows.forEach(row => {
                    if (row) row.remove();
                });

                // Final check: If no valid medicines remain, that's okay (medicines is nullable)
                const remainingMedicines = document.querySelectorAll('select[name="medicines[]"]');
                console.log('Final medicine count:', remainingMedicines.length);
                console.log('Final medicine values:', Array.from(remainingMedicines).map((s, i) => {
                    const val = s.value;
                    return {
                        index: i,
                        value: val,
                        type: typeof val,
                        isString: typeof val === 'string',
                        isValid: val && val !== '' && val !== 'null' && val !== 'undefined'
                    };
                }));

                // Ensure all remaining values are strings
                remainingMedicines.forEach(select => {
                    const val = select.value;
                    if (val !== null && val !== undefined && val !== '') {
                        const stringVal = String(val).trim();
                        select.value = stringVal;
                        select.setAttribute('value', stringVal);
                    }
                });

                // CRITICAL: Before allowing form submission, ensure ALL medicine selects have valid string values
                // If any don't, remove them or prevent submission
                const allMedSelects = document.querySelectorAll('select[name="medicines[]"]');
                const validMedicines = [];
                const rowsToKeep = [];

                allMedSelects.forEach((select, idx) => {
                    let val = select.value;

                    // Force get from Select2 if initialized
                    if (window.jQuery && $(select).hasClass('select2-hidden-accessible')) {
                        const select2Val = $(select).val();
                        if (select2Val !== null && select2Val !== undefined) {
                            val = Array.isArray(select2Val) ? String(select2Val[0]) : String(select2Val);
                        } else {
                            val = null;
                        }
                    }

                    // Validate and convert to string
                    if (val !== null && val !== undefined && val !== '') {
                        val = String(val).trim();
                        if (val !== '' && val !== 'null' && val !== 'undefined') {
                            // Valid medicine value
                            select.value = val;
                            select.setAttribute('value', val);
                            validMedicines.push(val);
                            rowsToKeep.push(select.closest('.medicine-row'));
                        } else {
                            // Invalid - remove row
                            const row = select.closest('.medicine-row');
                            if (row) row.remove();
                        }
                    } else {
                        // Null/undefined/empty - remove row
                        const row = select.closest('.medicine-row');
                        if (row) row.remove();
                    }
                });

                // Remove any rows that weren't marked to keep
                document.querySelectorAll('.medicine-row').forEach(row => {
                    if (!rowsToKeep.includes(row)) {
                        row.remove();
                    }
                });

                console.log('Valid medicines after final cleanup:', validMedicines);
                console.log('Medicine rows remaining:', document.querySelectorAll('.medicine-row').length);

                // Final verification - check each remaining select one more time
                document.querySelectorAll('select[name="medicines[]"]').forEach((select, idx) => {
                    const val = select.value;
                    if (!val || val === '' || val === 'null' || val === 'undefined') {
                        console.error('CRITICAL: Medicine select at index', idx, 'still has invalid value:', val);
                        select.closest('.medicine-row')?.remove();
                    } else {
                        // Force to string one more time
                        const finalVal = String(val).trim();
                        select.value = finalVal;
                        console.log('Medicine', idx, 'final value:', finalVal, 'type:', typeof finalVal);
                    }
                });

                // NUCLEAR OPTION: Destroy Select2 on ALL selects to ensure native values are used
                // This applies to medicines, dosages, interval_dosages, and duration_dosages
                if (window.jQuery && $.fn.select2) {
                    const allSelectFields = [
                        'select[name="medicines[]"]',
                        'select[name="dosages[]"]',
                        'select[name="interval_dosages[]"]',
                        'select[name="duration_dosages[]"]'
                    ];

                    allSelectFields.forEach(selector => {
                        document.querySelectorAll(selector).forEach(select => {
                            if ($(select).hasClass('select2-hidden-accessible')) {
                                const currentVal = $(select).val();
                                // Get the value before destroying
                                let stringVal = '';
                                if (currentVal !== null && currentVal !== undefined) {
                                    stringVal = Array.isArray(currentVal) ? String(currentVal[0]) : String(currentVal);
                                }
                                // Destroy Select2
                                $(select).select2('destroy');
                                // Set the native value
                                if (stringVal && stringVal !== '' && stringVal !== 'null' && stringVal !== 'undefined') {
                                    select.value = stringVal.trim();
                                } else {
                                    // For non-medicine fields, set to empty string (they're nullable)
                                    if (selector.includes('medicines')) {
                                        // If medicine is empty, remove the entire row
                                        select.closest('.medicine-row')?.remove();
                                    } else {
                                        select.value = '';
                                    }
                                }
                            }
                        });
                    });
                }

                // One final pass to remove any remaining invalid medicine rows
                document.querySelectorAll('select[name="medicines[]"]').forEach(select => {
                    const val = select.value;
                    if (!val || val === '' || val === 'null' || val === 'undefined') {
                        select.closest('.medicine-row')?.remove();
                    } else {
                        // Ensure it's a string
                        select.value = String(val).trim();
                    }
                });

                // Ensure all other medicine-related fields are strings
                document.querySelectorAll('select[name="dosages[]"], select[name="interval_dosages[]"], select[name="duration_dosages[]"]').forEach(select => {
                    const val = select.value;
                    if (val !== null && val !== undefined && val !== '') {
                        select.value = String(val).trim();
                    } else {
                        select.value = '';
                    }
                });

                // Ensure instructions are strings
                document.querySelectorAll('textarea[name="instructions[]"]').forEach(textarea => {
                    const val = textarea.value;
                    if (val !== null && val !== undefined) {
                        textarea.value = String(val);
                    } else {
                        textarea.value = '';
                    }
                });

                console.log('Final medicine count before submission:', document.querySelectorAll('select[name="medicines[]"]').length);
                console.log('Final medicine values:', Array.from(document.querySelectorAll('select[name="medicines[]"]')).map(s => s.value));

                // CRITICAL: Intercept form submission and manually build FormData with string values
                e.preventDefault();

                // First, sync Select2 values to native selects, then destroy Select2 instances
                const allSelect2Selects = document.querySelectorAll('select.select2, select.multiselect2, select.medicine_category, select.medicine_name, select.medicine_dosage, select.interval_dosage, select.duration_dosage, select[name="pathology[]"], select[name="radiology[]"]');
                allSelect2Selects.forEach(select => {
                    if (window.jQuery && $(select).hasClass('select2-hidden-accessible')) {
                        try {
                            // Get value from Select2
                            const select2Value = $(select).val();

                            // Sync to native select
                            if (select2Value !== null && select2Value !== undefined) {
                                if (Array.isArray(select2Value)) {
                                    // For multi-select, set all selected options
                                    $(select).val(select2Value);
                                } else {
                                    // For single select, set the value
                                    select.value = String(select2Value);
                                }
                            }

                            // Now destroy Select2
                            $(select).select2('destroy');
                            console.log('Synced and destroyed Select2 for:', select.name, 'value:', select.value || $(select).val());
                        } catch(e) {
                            console.warn('Error syncing/destroying Select2:', e);
                        }
                    }
                });

                // Create FormData manually to ensure all values are strings
                const formData = new FormData();

                // Add CSRF token first
                const csrfToken = document.querySelector('input[name="_token"]')?.value ||
                                 document.querySelector('meta[name="csrf-token"]')?.content;
                if (csrfToken) {
                    formData.append('_token', String(csrfToken));
                }

                // Process medicine rows first - collect all medicine-related data
                const medicineRows = document.querySelectorAll('.medicine-row');
                const validMedicineData = [];

                console.log('Processing', medicineRows.length, 'medicine rows');

                medicineRows.forEach((row, index) => {
                    const medicineSelect = row.querySelector('select[name="medicines[]"]');
                    const dosageSelect = row.querySelector('select[name="dosages[]"]');
                    const intervalSelect = row.querySelector('select[name="interval_dosages[]"]');
                    const durationSelect = row.querySelector('select[name="duration_dosages[]"]');
                    const instructionTextarea = row.querySelector('textarea[name="instructions[]"]');

                    if (!medicineSelect) {
                        console.warn(`Row ${index + 1}: Medicine select not found, skipping`);
                        return;
                    }

                    // Get values from native selects (Select2 should be destroyed by now)
                    // Double-check: if Select2 is still active, get value from Select2
                    let medicineValue = null;
                    let dosageValue = null;
                    let intervalValue = null;
                    let durationValue = null;
                    let instructionValue = null;

                    // Check if Select2 is still active and get value from it
                    if (window.jQuery && $(medicineSelect).hasClass('select2-hidden-accessible')) {
                        const select2Val = $(medicineSelect).val();
                        medicineValue = select2Val !== null && select2Val !== undefined ?
                                       (Array.isArray(select2Val) ? select2Val[0] : select2Val) :
                                       medicineSelect.value;
                    } else {
                        medicineValue = medicineSelect.value;
                    }

                    if (window.jQuery && dosageSelect && $(dosageSelect).hasClass('select2-hidden-accessible')) {
                        const select2Val = $(dosageSelect).val();
                        dosageValue = select2Val !== null && select2Val !== undefined ?
                                     (Array.isArray(select2Val) ? select2Val[0] : select2Val) :
                                     dosageSelect.value;
                    } else {
                        dosageValue = dosageSelect ? dosageSelect.value : null;
                    }

                    if (window.jQuery && intervalSelect && $(intervalSelect).hasClass('select2-hidden-accessible')) {
                        const select2Val = $(intervalSelect).val();
                        intervalValue = select2Val !== null && select2Val !== undefined ?
                                       (Array.isArray(select2Val) ? select2Val[0] : select2Val) :
                                       intervalSelect.value;
                    } else {
                        intervalValue = intervalSelect ? intervalSelect.value : null;
                    }

                    if (window.jQuery && durationSelect && $(durationSelect).hasClass('select2-hidden-accessible')) {
                        const select2Val = $(durationSelect).val();
                        durationValue = select2Val !== null && select2Val !== undefined ?
                                       (Array.isArray(select2Val) ? select2Val[0] : select2Val) :
                                       durationSelect.value;
                    } else {
                        durationValue = durationSelect ? durationSelect.value : null;
                    }

                    instructionValue = instructionTextarea ? instructionTextarea.value : null;

                    // Convert all to strings and validate - ensure they're actual strings, not null/undefined
                    medicineValue = (medicineValue !== null && medicineValue !== undefined && medicineValue !== '') ? String(medicineValue).trim() : '';
                    dosageValue = (dosageValue !== null && dosageValue !== undefined && dosageValue !== '') ? String(dosageValue).trim() : '';
                    intervalValue = (intervalValue !== null && intervalValue !== undefined && intervalValue !== '') ? String(intervalValue).trim() : '';
                    durationValue = (durationValue !== null && durationValue !== undefined && durationValue !== '') ? String(durationValue).trim() : '';
                    instructionValue = (instructionValue !== null && instructionValue !== undefined) ? String(instructionValue).trim() : '';

                    console.log(`Row ${index + 1} values:`, {
                        medicine: medicineValue,
                        dosage: dosageValue,
                        interval: intervalValue,
                        duration: durationValue,
                        instruction: instructionValue,
                        medicineType: typeof medicineValue,
                        medicineLength: medicineValue.length
                    });

                    // Only add if medicine is selected and is a valid non-empty string
                    if (medicineValue && medicineValue !== '' && medicineValue !== 'null' && medicineValue !== 'undefined' && medicineValue.length > 0) {
                        // Verify it's actually a string
                        if (typeof medicineValue !== 'string') {
                            console.error(`Row ${index + 1}: Medicine value is not a string! Type:`, typeof medicineValue, 'Value:', medicineValue);
                            medicineValue = String(medicineValue);
                        }

                        validMedicineData.push({
                            medicine: medicineValue,
                            dosage: dosageValue,
                            interval: intervalValue,
                            duration: durationValue,
                            instruction: instructionValue
                        });
                        console.log(`✓ Row ${index + 1} added to valid data`);
                    } else {
                        console.warn(`Row ${index + 1}: Skipped - medicine value is empty or invalid:`, medicineValue);
                    }
                });

                console.log('Total valid medicine rows:', validMedicineData.length);

                // Add medicine data to FormData - ensure all values are strings
                console.log('Valid medicine data to send:', validMedicineData);
                validMedicineData.forEach((data, index) => {
                    // CRITICAL: Ensure all values are strings and not empty/null/undefined
                    // Convert to string explicitly, handling all edge cases
                    let medicineVal = '';
                    let dosageVal = '';
                    let intervalVal = '';
                    let durationVal = '';
                    let instructionVal = '';

                    // Medicine - must be non-empty string
                    if (data.medicine !== null && data.medicine !== undefined) {
                        medicineVal = String(data.medicine).trim();
                    }

                    // Dosage - can be empty but must be string
                    if (data.dosage !== null && data.dosage !== undefined) {
                        dosageVal = String(data.dosage).trim();
                    }

                    // Interval - can be empty but must be string
                    if (data.interval !== null && data.interval !== undefined) {
                        intervalVal = String(data.interval).trim();
                    }

                    // Duration - can be empty but must be string
                    if (data.duration !== null && data.duration !== undefined) {
                        durationVal = String(data.duration).trim();
                    }

                    // Instruction - can be empty but must be string
                    if (data.instruction !== null && data.instruction !== undefined) {
                        instructionVal = String(data.instruction).trim();
                    }

                    // Final type check - ensure they're all strings
                    medicineVal = typeof medicineVal === 'string' ? medicineVal : String(medicineVal || '');
                    dosageVal = typeof dosageVal === 'string' ? dosageVal : String(dosageVal || '');
                    intervalVal = typeof intervalVal === 'string' ? intervalVal : String(intervalVal || '');
                    durationVal = typeof durationVal === 'string' ? durationVal : String(durationVal || '');
                    instructionVal = typeof instructionVal === 'string' ? instructionVal : String(instructionVal || '');

                    // Only add if medicine is not empty
                    if (medicineVal && medicineVal !== '' && medicineVal !== 'null' && medicineVal !== 'undefined') {
                        // Append as strings - FormData will handle the array notation
                        formData.append('medicines[]', medicineVal);
                        formData.append('dosages[]', dosageVal);
                        formData.append('interval_dosages[]', intervalVal);
                        formData.append('duration_dosages[]', durationVal);
                        formData.append('instructions[]', instructionVal);

                        console.log(`Row ${index + 1} added to FormData:`, {
                            medicine: `"${medicineVal}"`,
                            dosage: `"${dosageVal}"`,
                            interval: `"${intervalVal}"`,
                            duration: `"${durationVal}"`,
                            instruction: `"${instructionVal}"`,
                            types: {
                                medicine: typeof medicineVal,
                                dosage: typeof dosageVal,
                                interval: typeof intervalVal,
                                duration: typeof durationVal,
                                instruction: typeof instructionVal
                            },
                            lengths: {
                                medicine: medicineVal.length,
                                dosage: dosageVal.length,
                                interval: intervalVal.length,
                                duration: durationVal.length,
                                instruction: instructionVal.length
                            }
                        });
                    } else {
                        console.warn(`Skipping row ${index + 1} - empty or invalid medicine value:`, medicineVal);
                    }
                });

                // Add all other form fields (excluding medicine-related fields which we already added)
                const formElements = this.elements;
                for (let i = 0; i < formElements.length; i++) {
                    const element = formElements[i];
                    const name = element.name;

                    if (!name) continue;

                    // Skip submit buttons and CSRF token (already added)
                    if (element.type === 'submit' || element.type === 'button') continue;
                    if (name === '_token') continue;

                    // Skip medicine-related fields (already processed above)
                    if (name === 'medicines[]' || name === 'dosages[]' ||
                        name === 'interval_dosages[]' || name === 'duration_dosages[]' ||
                        name === 'instructions[]') {
                        continue;
                    }

                    // Handle other array fields (pathology, radiology, etc.)
                    if (name.endsWith('[]')) {
                        // Handle multi-select or array inputs
                        if (element.type === 'select-multiple' || element.hasAttribute('multiple')) {
                            // Get selected options
                            const selectedOptions = Array.from(element.selectedOptions);
                            console.log(`Processing ${name}:`, selectedOptions.map(opt => ({value: opt.value, type: typeof opt.value})));

                            selectedOptions.forEach(option => {
                                const val = option.value;
                                if (val !== null && val !== undefined && val !== '' && val !== 'null' && val !== 'undefined') {
                                    const stringVal = String(val).trim();
                                    formData.append(name, stringVal);
                                    console.log(`Added ${name}: "${stringVal}" (type: ${typeof stringVal})`);
                                }
                            });
                        } else {
                            const val = element.value;
                            if (val !== null && val !== undefined && val !== '' && val !== 'null' && val !== 'undefined') {
                                formData.append(name, String(val).trim());
                            }
                        }
                    }
                    // Handle regular fields
                    else {
                        if (element.type === 'checkbox' || element.type === 'radio') {
                            if (element.checked) {
                                formData.append(name, String(element.value || '1'));
                            }
                        } else if (element.type === 'file') {
                            // Handle file inputs
                            if (element.files && element.files.length > 0) {
                                for (let j = 0; j < element.files.length; j++) {
                                    formData.append(name, element.files[j]);
                                }
                            }
                        } else {
                            const val = element.value;
                            if (val !== null && val !== undefined) {
                                formData.append(name, String(val));
                            }
                        }
                    }
                }

                // Log what we're sending (especially medicines)
                console.log('=== FormData entries ===');
                const medicineEntries = [];
                const dosageEntries = [];
                const intervalEntries = [];
                const durationEntries = [];
                const instructionEntries = [];

                for (let pair of formData.entries()) {
                    const key = pair[0];
                    const value = pair[1];
                    const valueType = typeof value;

                    if (key === 'medicines[]') {
                        medicineEntries.push(value);
                    } else if (key === 'dosages[]') {
                        dosageEntries.push(value);
                    } else if (key === 'interval_dosages[]') {
                        intervalEntries.push(value);
                    } else if (key === 'duration_dosages[]') {
                        durationEntries.push(value);
                    } else if (key === 'instructions[]') {
                        instructionEntries.push(value);
                    }

                    console.log(`${key}: "${value}" (type: ${valueType}, length: ${String(value).length})`);
                }

                console.log('=== Medicine Arrays Summary ===');
                console.log('medicines[]:', medicineEntries);
                console.log('dosages[]:', dosageEntries);
                console.log('interval_dosages[]:', intervalEntries);
                console.log('duration_dosages[]:', durationEntries);
                console.log('instructions[]:', instructionEntries);

                // Verify all are strings
                const allValid = medicineEntries.every(v => typeof v === 'string') &&
                                dosageEntries.every(v => typeof v === 'string') &&
                                intervalEntries.every(v => typeof v === 'string') &&
                                durationEntries.every(v => typeof v === 'string') &&
                                instructionEntries.every(v => typeof v === 'string');

                if (!allValid) {
                    console.error('ERROR: Some values are not strings!');
                    console.error('Medicine types:', medicineEntries.map(v => typeof v));
                    console.error('Dosage types:', dosageEntries.map(v => typeof v));
                    console.error('Interval types:', intervalEntries.map(v => typeof v));
                    console.error('Duration types:', durationEntries.map(v => typeof v));
                    console.error('Instruction types:', instructionEntries.map(v => typeof v));
                } else {
                    console.log('✓ All values are strings');
                }

                // Final validation before submission
                if (validMedicineData.length === 0) {
                    alert('Please add at least one medicine to the prescription.');
                    return;
                }

                // Verify all medicine values are strings
                const allStrings = validMedicineData.every(data =>
                    typeof data.medicine === 'string' &&
                    typeof data.dosage === 'string' &&
                    typeof data.interval === 'string' &&
                    typeof data.duration === 'string' &&
                    typeof data.instruction === 'string'
                );

                if (!allStrings) {
                    console.error('ERROR: Not all medicine values are strings!');
                    alert('Error: Invalid data format. Please check the console for details.');
                    return;
                }

                console.log('=== Submitting form ===');
                console.log('Action:', this.action);
                console.log('Method:', this.method || 'POST');
                console.log('Valid medicine rows:', validMedicineData.length);

                // Alternative: Try sending as URLSearchParams to ensure proper array formatting
                // But first, let's try FormData with explicit Content-Type
                console.log('=== Final FormData Check ===');
                const finalCheck = {};
                for (let pair of formData.entries()) {
                    const key = pair[0];
                    const value = pair[1];
                    if (!finalCheck[key]) {
                        finalCheck[key] = [];
                    }
                    finalCheck[key].push(value);
                }
                console.log('FormData as object:', finalCheck);
                console.log('Medicines array:', finalCheck['medicines[]']);
                console.log('All medicine values are strings:', finalCheck['medicines[]'] ? finalCheck['medicines[]'].every(v => typeof v === 'string') : 'N/A');

                // Submit via fetch
                fetch(this.action, {
                    method: this.method || 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        // Don't set Content-Type for FormData - browser will set it with boundary
                    },
                    credentials: 'same-origin'
                })
                .then(async response => {
                    console.log('Response status:', response.status);
                    const data = await response.json();
                    console.log('Response data:', data);

                    if (!response.ok) {
                        // Handle validation errors
                        if (data.errors) {
                            console.error('Validation errors:', data.errors);
                            const errorMessages = Object.values(data.errors).flat().join(', ');
                            throw new Error(errorMessages);
                        }
                        throw new Error(data.message || 'Server error');
                    }
                    return data;
                })
                .then(data => {
                    console.log('Success:', data);
                    // Close modal and reload or show success message
                    if (window.jQuery && $('#addPrescriptionModal').length) {
                        $('#addPrescriptionModal').modal('hide');
                    }
                    // Reload page or show success
                    if (data.success || data.message) {
                        alert(data.message || 'Prescription added successfully!');
                        location.reload();
                    } else {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong: ' + error.message);
                });
            });
        }

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
                    // Destroy Select2 temporarily to update options
                    try {
                        $(selectElement).select2('destroy');
                    } catch(e) {
                        console.warn('Error destroying Select2 before filling:', e);
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
                                try {
                                    $(selectElement).select2('destroy');
                                    console.log('Destroyed existing Select2 before filling options');
                                } catch(e) {
                                    console.warn('Error destroying Select2:', e);
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

            // 🔹 Fetch base dropdown data once
            Promise.all([
                fetch("{{ route('getMedicineCategories') }}").then(res => {
                    if (!res.ok) throw new Error('Failed to fetch categories');
                    return res.json();
                }),
                fetch("{{ route('getDoseIntervals') }}").then(res => {
                    if (!res.ok) throw new Error('Failed to fetch intervals');
                    return res.json();
                }),
                fetch("{{ route('getDoseDurations') }}").then(res => {
                    if (!res.ok) throw new Error('Failed to fetch durations');
                    return res.json();
                })
            ]).then(([categories, intervals, durations]) => {
                console.log('Categories loaded:', categories);
                console.log('Intervals loaded:', intervals);
                console.log('Durations loaded:', durations);

                window.medicineCategories = categories;
                window.doseIntervals = intervals;
                window.doseDurations = durations;

                // Check if container exists before initializing
                if (container && container.querySelector(".medicine-row")) {
                    // Initialize first row
                    if (typeof window.initRow === 'function') {
                        window.initRow(container.querySelector(".medicine-row"));
                    }
                } else {
                    console.warn('Medicine container or row not found, will initialize when modal opens');
                }

                if (addButton) {
                    addButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        if (typeof window.addNewRow === 'function') {
                            window.addNewRow();
                        } else {
                            console.error('addNewRow function not found');
                        }
                    });
                }
            }).catch(error => {
                console.error('Error loading base dropdown data:', error);
            });

            // Make initRow globally accessible
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
                        try {
                            $(categorySelect).select2('destroy');
                            console.log('Destroyed existing Select2 for category');
                        } catch(e) {
                            console.warn('Error destroying category Select2:', e);
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
                    if (window.jQuery && $(intervalSelect).hasClass('select2-hidden-accessible')) {
                        $(intervalSelect).select2('destroy');
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
                    if (window.jQuery && $(durationSelect).hasClass('select2-hidden-accessible')) {
                        $(durationSelect).select2('destroy');
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
                    if ($(medicineSelect).hasClass('select2-hidden-accessible')) {
                        $(medicineSelect).select2('destroy');
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
                    if ($(doseSelect).hasClass('select2-hidden-accessible')) {
                        $(doseSelect).select2('destroy');
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
                            })
                            .catch(error => {
                                console.error('Error loading medicines:', error);
                                if (window.jQuery && $.fn.select2) {
                                    if ($(medicineSelect).hasClass('select2-hidden-accessible')) {
                                        $(medicineSelect).select2('destroy');
                                    }
                                    $(medicineSelect).html('<option value="">Error loading medicines</option>');
                                    $(medicineSelect).prop('disabled', false);
                                }
                            });
                };

                // Attach native change event
                categorySelect.addEventListener("change", handleCategoryChange);

                // Also attach Select2 change event if jQuery is available
                if (window.jQuery && $.fn.select2) {
                    $(categorySelect).off('select2:select select2:clear').on('select2:select select2:clear', function() {
                        console.log('Category changed via Select2 event');
                        handleCategoryChange.call(this);
                    });
                }

                        // Clear medicine and dose selects when category changes
                        if (window.jQuery && $(doseSelect).hasClass('select2-hidden-accessible')) {
                            $(doseSelect).select2('destroy');
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
                        // If no category selected, clear medicines and doses
                        if (window.jQuery && $(medicineSelect).hasClass('select2-hidden-accessible')) {
                            $(medicineSelect).select2('destroy');
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

                        if (window.jQuery && $(doseSelect).hasClass('select2-hidden-accessible')) {
                            $(doseSelect).select2('destroy');
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

                // Medicine change → fetch doses for the selected medicine
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

                                    // Destroy Select2 before filling
                                    if (window.jQuery && $(doseSelect).hasClass('select2-hidden-accessible')) {
                                        $(doseSelect).select2('destroy');
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
                        // Clear doses if no medicine selected
                        if (window.jQuery && $(doseSelect).hasClass('select2-hidden-accessible')) {
                            $(doseSelect).select2('destroy');
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

                // // Medicine change → fetch doses
                // row.querySelector(".medicine_name").addEventListener("change", function() {
                //     const medicineId = this.value;
                //     const doseSelect = row.querySelector(".medicine_dosage");
                //     fetch(`/getDoses/${medicineId}`)
                //         .then(res => res.json())
                //         .then(data => fillSelect(doseSelect, data, "dose"));
                // });

                // Delete button
                const deleteBtn = row.querySelector(".delete_row");
                deleteBtn.addEventListener("click", function() {
                    const allRows = container.querySelectorAll(".medicine-row");
                    if (allRows.length > 1) row.remove();
                    else alert("At least one medicine must remain.");
                });

                // Note: Select2 initialization is handled in initRow function above
                // Medicine Select2 is initialized in loadAllMedicines after data is loaded
                // This prevents duplicate initialization conflicts
            }

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
                initRow(newRow);
            }

        })

        // Function to load medicines when modal opens (made global)
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

                            // Destroy Select2 FIRST before modifying options
                            if (window.jQuery && typeof $.fn.select2 !== 'undefined') {
                                if ($(medicineSelect).hasClass('select2-hidden-accessible')) {
                                    try {
                                        $(medicineSelect).select2('destroy');
                                        console.log('Destroyed existing Select2 before adding options');
                                    } catch(e) {
                                        console.warn('Error destroying Select2:', e);
                                    }
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
                findingCategorySelect.innerHTML = '<option value="">Select</option>';
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.category;
                    if ("{{ old('finding_type[]') }}" == category.id) {
                        option.selected = true;
                    }
                    findingCategorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching finding categories:', error);
                findingCategorySelect.innerHTML = '<option value="">Error loading options</option>';
            });

        findingCategorySelect.addEventListener('change', function() {
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
                        if ("{{ old('finding[]') }}" == finding.id) {
                            option.selected = true;
                        }
                        findingsSelect.appendChild(option);
                    });

                    // Initialize Select2 after populating findings
                    if (window.jQuery && $.fn.select2 && findingsSelect) {
                        // Destroy existing Select2 if any
                        if ($(findingsSelect).hasClass('select2-hidden-accessible')) {
                            $(findingsSelect).select2('destroy');
                        }
                        // Initialize Select2
                        $(findingsSelect).select2({
                            placeholder: 'Select Findings',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#addPrescriptionModal')
                        });
                        console.log('Select2 initialized for findings select');
                    }
                })
                .catch(error => {
                    console.error('Error fetching Findings:', error);
                    findingsSelect.innerHTML = '<option value="">Error loading options</option>';
                });
        });

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
                    pathologySelectEl.innerHTML = '<option value="">Select</option>';
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(patho => {
                            const option = document.createElement('option');
                            option.value = patho.id;
                            option.textContent = patho.test_name + "(" + patho.short_name + ")";
                            if ("{{ old('pathology[]') }}" == patho.id) {
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
                    radiologySelectEl.innerHTML = '<option value="">Select</option>';
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(radio => {
                            const option = document.createElement('option');
                            option.value = radio.id;
                            option.textContent = radio.test_name + "(" + radio.short_name + ")";
                            if ("{{ old('radiology[]') }}" == radio.id) {
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
                    // Clear existing options except the first one
                    pathologySelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(patho => {
                        const option = document.createElement('option');
                        option.value = patho.id;
                        option.textContent = patho.test_name + "(" + patho.short_name + ")";
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
            // Check if Select2 is already initialized
            const isSelect2Initialized = $(pathologySelect).hasClass('select2-hidden-accessible');

            if (!isSelect2Initialized) {
                console.log('Initializing Select2 for pathology');
                $(pathologySelect).select2({
                    placeholder: 'Search and select pathology tests...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addPrescriptionModal'),
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
            } else {
                console.log('Select2 already initialized for pathology');
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
            const radiologyUrl = "{{ route('getRadiologies') }}";
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
                    // Clear existing options except the first one
                    radiologySelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(radio => {
                        const option = document.createElement('option');
                        option.value = radio.id;
                        option.textContent = radio.test_name + "(" + radio.short_name + ")";
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
            // Check if Select2 is already initialized
            const isSelect2Initialized = $(radiologySelect).hasClass('select2-hidden-accessible');

            if (!isSelect2Initialized) {
                console.log('Initializing Select2 for radiology with', radiologySelect.options.length, 'options');
                $(radiologySelect).select2({
                    placeholder: 'Search and select radiology tests...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addPrescriptionModal'),
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
            } else {
                console.log('Select2 already initialized for radiology, refreshing...');
                // Destroy and reinitialize to refresh options
                try {
                    $(radiologySelect).select2('destroy');
                } catch(e) {
                    console.log('Select2 destroy failed, continuing...');
                }
                $(radiologySelect).select2({
                    placeholder: 'Search and select radiology tests...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addPrescriptionModal'),
                    minimumResultsForSearch: 0,
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

        // Test: Try to initialize immediately if modal is already visible (for debugging)
        console.log('Pathology and radiology initialization functions defined');
        console.log('Modal element:', createPrescriptionModal ? 'Found' : 'Not found');

        // Also initialize when modal is fully shown
        if (createPrescriptionModal) {
            createPrescriptionModal.addEventListener('shown.bs.modal', function(event) {
                console.log('Modal fully shown, checking pathology and radiology data...');

                // Check if data was loaded, if not fetch now
                const pathologySelectModal = document.getElementById('pathologyOpt');
                const radiologySelectModal = document.getElementById('radiologyOpt');

                // Fetch pathologies if not already loaded
                if (pathologySelectModal && (!window.pathologyData || pathologySelectModal.options.length <= 1)) {
                    console.log('Pathology data not loaded, fetching now...');
                    fetch("{{ route('getPathologies') }}")
                        .then(response => response.json())
                        .then(data => {
                            console.log('Pathologies fetched on modal open:', data);
                            window.pathologyData = data;
                            pathologySelectModal.innerHTML = '<option value="">Select</option>';
                            data.forEach(patho => {
                                const option = document.createElement('option');
                                option.value = patho.id;
                                option.textContent = patho.test_name + "(" + patho.short_name + ")";
                                pathologySelectModal.appendChild(option);
                            });
                            // Initialize Select2
                            if (window.jQuery && $.fn.select2) {
                                if ($(pathologySelectModal).hasClass('select2-hidden-accessible')) {
                                    $(pathologySelectModal).select2('destroy');
                                }
                                $(pathologySelectModal).select2({
                                    placeholder: 'Select Tests',
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: $('#addPrescriptionModal')
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching pathologies:', error));
                }

                // Fetch radiologies if not already loaded
                if (radiologySelectModal && (!window.radiologyData || radiologySelectModal.options.length <= 1)) {
                    console.log('Radiology data not loaded, fetching now...');
                    fetch("{{ route('getRadiologies') }}")
                        .then(response => response.json())
                        .then(data => {
                            console.log('Radiologies fetched on modal open:', data);
                            window.radiologyData = data;
                            radiologySelectModal.innerHTML = '<option value="">Select</option>';
                            data.forEach(radio => {
                                const option = document.createElement('option');
                                option.value = radio.id;
                                option.textContent = radio.test_name + "(" + radio.short_name + ")";
                                radiologySelectModal.appendChild(option);
                            });
                            // Initialize Select2
                            if (window.jQuery && $.fn.select2) {
                                if ($(radiologySelectModal).hasClass('select2-hidden-accessible')) {
                                    $(radiologySelectModal).select2('destroy');
                                }
                                $(radiologySelectModal).select2({
                                    placeholder: 'Select Tests',
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: $('#addPrescriptionModal')
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching radiologies:', error));
                }

                setTimeout(() => {
                    initializePathologyMultiselect();
                    initializeRadiologyMultiselect();

                    // Ensure categories, intervals, and durations are loaded, fetch if not
                    const needsCategories = !window.medicineCategories || !Array.isArray(window.medicineCategories) || window.medicineCategories.length === 0;
                    const needsIntervals = !window.doseIntervals || !Array.isArray(window.doseIntervals) || window.doseIntervals.length === 0;
                    const needsDurations = !window.doseDurations || !Array.isArray(window.doseDurations) || window.doseDurations.length === 0;

                    if (needsCategories || needsIntervals || needsDurations) {
                        console.log('Fetching missing data...', {
                            categories: needsCategories,
                            intervals: needsIntervals,
                            durations: needsDurations
                        });

                        Promise.all([
                            needsCategories ? fetch("{{ route('getMedicineCategories') }}").then(res => {
                                if (!res.ok) throw new Error('Failed to fetch categories');
                                return res.json();
                            }) : Promise.resolve(window.medicineCategories || []),
                            needsIntervals ? fetch("{{ route('getDoseIntervals') }}").then(res => {
                                if (!res.ok) throw new Error('Failed to fetch intervals');
                                return res.json();
                            }) : Promise.resolve(window.doseIntervals || []),
                            needsDurations ? fetch("{{ route('getDoseDurations') }}").then(res => {
                                if (!res.ok) throw new Error('Failed to fetch durations');
                                return res.json();
                            }) : Promise.resolve(window.doseDurations || [])
                        ]).then(([categories, intervals, durations]) => {
                            console.log('Data fetched on modal open:', {
                                categories: categories.length,
                                intervals: intervals.length,
                                durations: durations.length
                            });
                            window.medicineCategories = categories;
                            window.doseIntervals = intervals;
                            window.doseDurations = durations;
                            initializeMedicineRows();
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
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

                // Helper function to initialize medicine rows
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
            });

            // Clear selects when modal is hidden
            createPrescriptionModal.addEventListener('hide.bs.modal', function(event) {
                const pathologySelect = document.getElementById('pathologyOpt');
                const radiologySelect = document.getElementById('radiologyOpt');

                // Destroy Select2 instances to allow re-initialization
                if (pathologySelect && window.jQuery && $(pathologySelect).hasClass('select2-hidden-accessible')) {
                    try {
                        $(pathologySelect).select2('destroy');
                    } catch(e) {
                        console.warn('Error destroying pathology Select2:', e);
                    }
                }
                if (radiologySelect && window.jQuery && $(radiologySelect).hasClass('select2-hidden-accessible')) {
                    try {
                        $(radiologySelect).select2('destroy');
                    } catch(e) {
                        console.warn('Error destroying radiology Select2:', e);
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
        $('#finding_type, .multiselect2').each(function() {
            // Destroy existing Select2 if any (in case it was initialized before)
            if ($(this).hasClass('select2-hidden-accessible')) {
                try {
                    $(this).select2('destroy');
                } catch(e) {
                    console.warn('Error destroying Select2:', e);
                }
            }
        });

        // Initialize finding category and findings selects
        $('#finding_type, #finding').select2({
            placeholder: 'Select',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addPrescriptionModal')
        });

        console.log('Select2 initialized for finding_type and finding selects');

        // Initialize pathology and radiology Select2 if data is already loaded
        setTimeout(function() {
            const pathologySelect = document.getElementById('pathologyOpt');
            const radiologySelect = document.getElementById('radiologyOpt');

            if (pathologySelect && pathologySelect.options.length > 1 && !$(pathologySelect).hasClass('select2-hidden-accessible')) {
                $(pathologySelect).select2({
                    placeholder: 'Select Tests',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addPrescriptionModal')
                });
            }

            if (radiologySelect && radiologySelect.options.length > 1 && !$(radiologySelect).hasClass('select2-hidden-accessible')) {
                $(radiologySelect).select2({
                    placeholder: 'Select Tests',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#addPrescriptionModal')
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
