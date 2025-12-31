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
    <div class="modal-dialog modal-fullscreen ">
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
                                                <label class="form-label">Footer Note</label>
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
                                        <select class="form-control pathology-test-select" name="pathology[]" id="pathologyOpt" multiple style="width: 100%;">
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
                                        <select class="form-control radiology-test-select" name="radiology[]" id="radiologyOpt" multiple style="width: 100%;">
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
        const createPrescriptionModal = document.getElementById("addPrescriptionModal");
        const findingCategorySelect = document.getElementById('finding_type');
        const findingsSelect = document.getElementById('finding');
        // Old multiselect references removed - now using table-based selects

        findingCategorySelect.innerHTML = '<option value="">Loading...</option>';
        findingsSelect.innerHTML = '<option value="">Loading...</option>';
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
                
                // Clean up empty medicine rows before submission
                const medicineRows = document.querySelectorAll('.medicine-row');
                medicineRows.forEach((row, index) => {
                    const medicineSelect = row.querySelector('select[name="medicines[]"]');
                    const medicineValue = medicineSelect ? medicineSelect.value.trim() : '';
                    
                    // If medicine is empty, remove the entire row or set all fields to empty
                    if (!medicineValue || medicineValue === '') {
                        // Remove empty medicine rows (except the first one if it's the only one)
                        if (medicineRows.length > 1) {
                            row.remove();
                        } else {
                            // If it's the only row, clear all values but keep the row
                            row.querySelectorAll('select, textarea').forEach(field => {
                                if (field.name && field.name.includes('[]')) {
                                    field.value = '';
                                }
                            });
                        }
                    }
                });
                
                console.log('Form submitting with - OPD ID:', finalOpdId, 'IPD ID:', finalIpdId, 'Prescribe By:', prescribeBy);
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
                
                fetch(allMedicinesUrl)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error(`HTTP error! status: ${res.status}`);
                        }
                        return res.json();
                    })
                    .then(data => {
                        console.log('Medicines loaded:', data);
                        if (!data || !Array.isArray(data)) {
                            console.error('Invalid data format:', data);
                            if (window.jQuery && $.fn.select2) {
                                $(selectElement).html('<option value="">No medicines available</option>');
                                $(selectElement).prop('disabled', false);
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
                        if (window.jQuery && $.fn.select2) {
                            $(selectElement).html('<option value="">Error loading medicines</option>');
                            $(selectElement).prop('disabled', false);
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
                    window.fillSelect(intervalSelect, window.doseIntervals, "name");
                }
                if (window.doseDurations && typeof window.fillSelect === 'function') {
                    window.fillSelect(durationSelect, window.doseDurations, "name");
                }
                
                // DO NOT initialize Select2 for medicine_name here - it will be initialized after medicines load
                // Load all medicines initially - this will initialize Select2 after data is loaded
                const loadFn = window.loadAllMedicines;
                if (typeof loadFn === 'function') {
                    console.log('Calling loadAllMedicines from initRow');
                    loadFn(medicineSelect);
                } else {
                    console.error('loadAllMedicines function not found');
                    // Fallback: try to load medicines directly
                    if (window.jQuery && $.fn.select2) {
                        $(medicineSelect).html('<option value="">Select Medicine</option>');
                        $(medicineSelect).select2({
                            width: "100%",
                            placeholder: "Search Medicine...",
                            allowClear: true,
                            minimumResultsForSearch: 0,
                            dropdownParent: $('#addPrescriptionModal')
                        });
                    }
                }
                
                categorySelect.addEventListener("change", function() {
                    const categoryId = this.value;
                    
                    if (categoryId && categoryId !== '') {
                        // Fetch medicines by category
                        const baseUrl = "{{ route('getMedicines', ['categoryId' => 'ID']) }}";
                        const finalUrl = baseUrl.replace('ID', categoryId);
                        
                        // Show loading state
                        if (window.jQuery && $.fn.select2) {
                            $(medicineSelect).prop('disabled', true);
                        }
                        
                        fetch(finalUrl)
                            .then(res => res.json())
                            .then(data => {
                                if (typeof window.fillSelect === 'function') {
                                    window.fillSelect(medicineSelect, data, "medicine_name");
                                } else {
                                    console.error('fillSelect function not found');
                                }
                                // Reinitialize select2 after filling
                                if (window.jQuery && $.fn.select2) {
                                    // Destroy existing select2 if any
                                    if ($(medicineSelect).hasClass('select2-hidden-accessible')) {
                                        $(medicineSelect).select2('destroy');
                                    }
                                    // Reinitialize with search
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
                                }
                            })
                            .catch(error => {
                                console.error('Error loading medicines:', error);
                                if (window.jQuery && $.fn.select2) {
                                    $(medicineSelect).prop('disabled', false);
                                }
                            });

                        // Fetch doses for the category
                        const baseUrlDose = "{{ route('getDoses', ['categoryId' => 'ID']) }}";
                        const finalUrlDose = baseUrlDose.replace('ID', categoryId);
                        fetch(finalUrlDose)
                            .then(res => res.json())
                            .then(data => {
                                if (typeof window.fillSelect === 'function') {
                                    window.fillSelect(doseSelect, data, "dosage");
                                }
                                if (window.jQuery && $.fn.select2) {
                                    $(doseSelect).trigger('change');
                                }
                            })
                            .catch(error => {
                                console.error('Error loading doses:', error);
                            });
                    } else {
                        // If no category selected, load all medicines
                        const loadFn = window.loadAllMedicines || loadAllMedicines;
                        if (typeof loadFn === 'function') {
                            loadFn(medicineSelect);
                        }
                        // Clear doses
                        doseSelect.innerHTML = '<option value="">Select</option>';
                        if (window.jQuery && $.fn.select2) {
                            $(doseSelect).trigger('change');
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
                    
                    fetch(apiUrl)
                        .then(res => {
                            console.log('Response status:', res.status);
                            if (!res.ok) {
                                throw new Error(`HTTP error! status: ${res.status}`);
                            }
                            return res.json();
                        })
                        .then(data => {
                            console.log('Medicines API response:', data);
                            
                            if (!data || !Array.isArray(data)) {
                                console.error('Invalid data format:', data);
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
                            medicineSelect.innerHTML = '<option value="">Error loading medicines</option>';
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
                })
                .catch(error => {
                    console.error('Error fetching Findings:', error);
                    findingsSelect.innerHTML = '<option value="">Error loading options</option>';
                });
        });
        // Store pathology and radiology data globally
        let pathologyData = [];
        let radiologyData = [];

        // Function to initialize pathology multiselect
        function initializePathologyMultiselect() {
            const pathologySelect = document.getElementById('pathologyOpt');
            if (!pathologySelect) {
                console.error('Pathology select element not found');
                return;
            }

            // Check if jQuery and Select2 are available
            if (!window.jQuery || !$.fn.select2) {
                console.error('jQuery or Select2 not available, retrying...');
                setTimeout(initializePathologyMultiselect, 500);
                return;
            }

            // Check if options are already populated (from server-side)
            const hasOptions = pathologySelect.options.length > 1; // More than just the "Select Tests" option
            
            // If no options, fetch via AJAX
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
                        pathologySelect.innerHTML = '<option value="">Select Tests</option>';
                        data.forEach(patho => {
                            const option = document.createElement('option');
                            option.value = patho.id;
                            option.textContent = (patho.test_name || 'N/A') + (patho.short_name ? " (" + patho.short_name + ")" : "");
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
                setTimeout(initializeRadiologyMultiselect, 500);
                return;
            }

            // Check if options are already populated (from server-side)
            const hasOptions = radiologySelect.options.length > 1; // More than just the "Select Tests" option
            
            // If no options, fetch via AJAX
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
                        radiologySelect.innerHTML = '<option value="">Select Tests</option>';
                        data.forEach(radio => {
                            const option = document.createElement('option');
                            option.value = radio.id;
                            option.textContent = (radio.test_name || 'N/A') + (radio.short_name ? " (" + radio.short_name + ")" : "");
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
                initializeSelect2ForRadiology();
            }
            
            function initializeSelect2ForRadiology() {
                // Check if Select2 is already initialized
                const isSelect2Initialized = $(radiologySelect).hasClass('select2-hidden-accessible');
                
                if (!isSelect2Initialized) {
                    console.log('Initializing Select2 for radiology');
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
                    console.log('Select2 already initialized for radiology');
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
        
        // Test: Try to initialize immediately if modal is already visible (for debugging)
        console.log('Pathology and radiology initialization functions defined');
        console.log('Modal element:', createPrescriptionModal ? 'Found' : 'Not found');
        
            // Also initialize when modal is fully shown
            if (createPrescriptionModal) {
                createPrescriptionModal.addEventListener('shown.bs.modal', function(event) {
                    console.log('Modal fully shown, initializing test selects and medicines...');
                    setTimeout(() => {
                        initializePathologyMultiselect();
                        initializeRadiologyMultiselect();
                        // Load medicines when modal is fully shown
                        if (typeof window.loadMedicinesOnModalOpen === 'function') {
                            window.loadMedicinesOnModalOpen();
                        } else {
                            console.error('loadMedicinesOnModalOpen function not found');
                        }
                    }, 200);
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
    })
</script>

<script>
    // Ensure jQuery + select2 are loaded
    $(function() {
        // initialize select2 on multiselect elements
        $('#finding_type, .multiselect2').select2({
            placeholder: 'Select',
            width: '100%'
        });

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
        $('#finding_type, .multiselect2').each(function() {
            adjustSelectSize($(this));
        });

        // adjust on change / select2 events
        $(document).on('change', '#finding_type, .multiselect2', function() {
            adjustSelectSize($(this));
        });
        // also catch select2 specific events for better responsiveness
        $(document).on('select2:select select2:unselect', '#finding_type, .multiselect2', function() {
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
            $row.off('change.cat').on('change.cat', '.medicine_category', function() {
                populateMedicines($(this).val(), $row.find('.medicine_name'), idx);
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
