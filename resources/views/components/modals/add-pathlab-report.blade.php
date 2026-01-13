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

<div class="modal fade" id="addPathLabModal" tabindex="-1" aria-labelledby="addPrescriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content" style="overflow: visible;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="addPrescriptionModal">
                    <div class="section-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    Report Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('ipd.updateReport', $lab->id) }}" id="prescriptionForm" method="post" enctype="multipart/form-data">@csrf
                @csrf
                <input type="hidden" name="lab_id" id="edit_lab_id">
                <div class="modal-body" style="max-height: calc(100vh - 160px); overflow-x:hiden;">
                    <!-- Attachment -->
                    <div class="mb-3">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>

                    <!-- Collected By -->
                    <div class="mb-3">
                        <label class="form-label">Collected By</label>
                        <input type="text" name="collected_by" class="form-control" required>
                    </div>

                    <!-- Approved By -->
                   
                    <input type="hidden" name="approved_by" value=1>
                    <!-- Collection Date -->
                    <div class="mb-3">
                        <label class="form-label">Collection Date</label>
                        <input type="date" name="collection_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.editLabBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit_lab_id').value = this.dataset.labId;
            });
        });
    });
</script>