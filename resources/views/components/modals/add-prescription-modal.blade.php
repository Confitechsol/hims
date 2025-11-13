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
</style>
<div class="modal fade" id="addPrescriptionModal" tabindex="-1" aria-labelledby="addPrescriptionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
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

            <!-- Modal Body -->
            <form action="{{ route('opd.addPrescription') }}" method="post" enctype="multipart/form-data">@csrf
                <div class="modal-body">

                    <div class="row p-4">
                        <div class="col-sm-9">
                            <div class="ptt10">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" id="opd_id" name="opd_id">
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
                                                    <select class="form-control multiselect2 select2-hidden-accessible"
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
                                                        <select class="form-control select2 medicine_category"
                                                            style="width:100%" name="medicine_categories[]">
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
                                        <label class="form-label">
                                            Pathology</label>

                                        <select class="form-control multiselect2 select2-hidden-accessible"
                                            style="width: 100%" name="pathology[]" multiple="" id="pathologyOpt"
                                            tabindex="-1" aria-hidden="true">

                                            <option value="5"> (Lipid Profile) Lipid Profile </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Radiology</label>
                                        <select class="form-control multiselect2 select2-hidden-accessible"
                                            style="width: 100%" name="radiology[]" id="radiologyOpt" multiple=""
                                            tabindex="-1" aria-hidden="true">

                                            <option value="4"> (X-Ray) X-Ray </option>
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

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm" data-bs-dismiss="modal">
                            Save & Print
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm" data-bs-dismiss="modal">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createPrescriptionModal = document.getElementById("addPrescriptionModal");
            const findingCategorySelect = document.getElementById('finding_type');
            const findingsSelect = document.getElementById('finding');
            const pathologySelect = document.getElementById('pathologyOpt');
            const radiologySelect = document.getElementById('radiologyOpt');
            findingCategorySelect.innerHTML = '<option value="">Loading...</option>';
            findingsSelect.innerHTML = '<option value="">Loading...</option>';
            pathologySelect.innerHTML = '<option value="">Loading...</option>';
            radiologySelect.innerHTML = '<option value="">Loading...</option>';

            createPrescriptionModal.addEventListener('show.bs.modal', function(event) {
                const opdIdField = document.getElementById('opd_id');

                var button = event.relatedTarget; // Button that triggered the modal

                var opd_id = button.getAttribute('data-id');
                opdIdField.value = opd_id ?? null;

                const container = document.getElementById("medicineContainer");
                const addButton = document.getElementById("addMedicineBtn");
                const addButtonContainer = document.getElementById("addMedicineContainer");

                // 🔹 Fetch base dropdown data once
                Promise.all([
                    fetch("{{ route('getMedicineCategories') }}").then(res => res.json()),
                    fetch("{{ route('getDoseIntervals') }}").then(res => res.json()),
                    fetch("{{ route('getDoseDurations') }}").then(res => res.json())
                ]).then(([categories, intervals, durations]) => {
                    window.medicineCategories = categories;
                    window.doseIntervals = intervals;
                    window.doseDurations = durations;

                    // Initialize first row
                    initRow(container.querySelector(".medicine-row"));

                    addButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        addNewRow();
                    });
                });

                function initRow(row) {
                    // Load base options
                    fillSelect(row.querySelector(".medicine_category"), window.medicineCategories,
                        "medicine_category");
                    fillSelect(row.querySelector(".interval_dosage"), window.doseIntervals, "name");
                    fillSelect(row.querySelector(".duration_dosage"), window.doseDurations, "name");

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

                    // Reinitialize select2
                    if (window.jQuery && $.fn.select2) {
                        $(row).find(".select2").select2({
                            width: "100%"
                        });
                    }
                }

                function fillSelect(selectElement, data, textKey) {
                    selectElement.innerHTML = `<option value="">Select</option>`;
                    data.forEach(item => {
                        const opt = document.createElement("option");
                        opt.value = item.id;
                        opt.textContent = textKey == 'dosage' ? item[textKey] + " " + item['unit'][
                            'unit_name'
                        ] : item[textKey];
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

                    // Insert before button
                    container.insertBefore(newRow, addButtonContainer);
                    // container.appendChild(newRow);
                    initRow(newRow);
                }

            })

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
            fetch("{{ route('getPathologies') }}")
                .then(response => response.json())
                .then(data => {
                    window.pathologyData = data;
                    pathologySelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(patho => {
                        const option = document.createElement('option');
                        option.value = patho.id;
                        option.textContent = patho.test_name + "(" + patho.short_name + ")";
                        if ("{{ old('pathology[]') }}" == patho.id) {
                            option.selected = true;
                        }
                        pathologySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching Pathologies:', error);
                    pathologySelect.innerHTML = '<option value="">Error loading options</option>';
                });
            fetch("{{ route('getRadiologies') }}")
                .then(response => response.json())
                .then(data => {
                    window.radiologyData = data;
                    radiologySelect.innerHTML = '<option value="">Select</option>';
                    data.forEach(radio => {
                        const option = document.createElement('option');
                        option.value = radio.id;
                        option.textContent = radio.test_name + "(" + radio.short_name + ")";
                        if ("{{ old('radiology[]') }}" == radio.id) {
                            option.selected = true;
                        }
                        radiologySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching Radiologies:', error);
                    radiologySelect.innerHTML = '<option value="">Error loading options</option>';
                });
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
                $.get('/api/medicines', {
                        category: cat
                    })
                    .done(function(data) {
                        $med.empty().append('<option value="">Select</option>');
                        (data || []).forEach(function(m) {
                            $med.append($('<option>').val(m.id).text(m.text).data('stock', m.stock));
                        });
                    })
                    .fail(function() {
                        var samples = [{
                            id: '101',
                            text: 'Amoxicillin',
                            stock: 12
                        }, {
                            id: '102',
                            text: 'Paracetamol',
                            stock: 50
                        }];
                        $med.empty().append('<option value="">Select</option>');
                        samples.forEach(function(m) {
                            $med.append($('<option>').val(m.id).text(m.text).data('stock', m.stock));
                        });
                    })
                    .always(function() {
                        $med.prop('disabled', false).trigger('change');
                        $med.off('change.stock').on('change.stock', function() {
                            var st = $med.find('option:selected').data('stock');
                            $('#stock_info_' + idx).text(st !== undefined ? (st + ' in stock') : '');
                        });
                    });
            }

            function initRow($row) {
                var idx = $row.data('row') || $('.medicine-row').length;
                $row.find('select').each(function() {
                    initSelect($(this));
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

        // Initialize both editors
        initEditor('toolbar-header', 'editor-header', 'formatBlock-header');
        initEditor('toolbar-footer', 'editor-footer', 'formatBlock-footer');
    </script>
