@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-file-invoice-dollar me-2"></i>Insurance Test Rates</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="row mb-4 g-3">
                    <div class="col-md-6">
                        <div class="card border h-100">
                            <div class="card-header bg-light py-2">
                                <strong><i class="ti ti-flask me-1"></i>Pathology Import</strong>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small">File: <code>PATHOLOGY_INSURANCE TEST RATE.xlsx</code><br>
                                    Sheets: GIPSA, ICICI LOMBARD, STAR</p>
                                <form action="{{ route('insurance.rate-panels.import.pathology') }}" method="POST" enctype="multipart/form-data" class="mb-2">
                                    @csrf
                                    <input type="file" name="file" class="form-control form-control-sm mb-2" accept=".xlsx,.xls">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-upload me-1"></i>Upload Pathology Excel
                                    </button>
                                </form>
                                <form action="{{ route('insurance.rate-panels.import.pathology') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="use_default_file" value="1">
                                    <button type="submit" class="btn btn-outline-primary btn-sm"
                                        onclick="return confirm('Re-import pathology rates? Existing pathology rows per panel will be replaced.');">
                                        Import Default Pathology File
                                    </button>
                                </form>
                                <a href="{{ route('insurance.test-mapping', ['test_type' => 'pathology']) }}" class="btn btn-link btn-sm ps-0 mt-2">Map pathology tests →</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border h-100">
                            <div class="card-header bg-light py-2">
                                <strong><i class="ti ti-scan me-1"></i>Radiology Import</strong>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small">File: <code>RADIOLOGY_INSURANCE TEST RATE.xlsx</code><br>
                                    Sheets: GIPSA, ICICI LOMBARD, STAR</p>
                                <form action="{{ route('insurance.rate-panels.import.radiology') }}" method="POST" enctype="multipart/form-data" class="mb-2">
                                    @csrf
                                    <input type="file" name="file" class="form-control form-control-sm mb-2" accept=".xlsx,.xls">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="ti ti-upload me-1"></i>Upload Radiology Excel
                                    </button>
                                </form>
                                <form action="{{ route('insurance.rate-panels.import.radiology') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="use_default_file" value="1">
                                    <button type="submit" class="btn btn-outline-success btn-sm"
                                        onclick="return confirm('Re-import radiology rates? Existing radiology rows per panel will be replaced.');">
                                        Import Default Radiology File
                                    </button>
                                </form>
                                <a href="{{ route('insurance.test-mapping', ['test_type' => 'radiology']) }}" class="btn btn-link btn-sm ps-0 mt-2">Map radiology tests →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Panel</th>
                                <th>Insurance Companies</th>
                                <th>Pathology Rates</th>
                                <th>Radiology Rates</th>
                                <th>Mapped</th>
                                <th>Needs Review</th>
                                <th>Unmapped</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($panels as $panel)
                            <tr>
                                <td>
                                    <strong>{{ $panel->name }}</strong><br>
                                    <code class="small">{{ $panel->code }}</code>
                                </td>
                                <td style="min-width:200px">
                                    @forelse($panel->insuranceCompanies as $company)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $company->name }}</span>
                                    @empty
                                        <span class="text-muted small">None linked</span>
                                    @endforelse
                                    <br>
                                    <button type="button" class="btn btn-link btn-sm ps-0 panel-insurers-btn"
                                        data-panel-id="{{ $panel->id }}"
                                        data-panel-name="{{ $panel->name }}"
                                        data-company-ids="{{ $panel->insuranceCompanies->pluck('id')->join(',') }}">
                                        Manage insurers
                                    </button>
                                </td>
                                <td><span class="badge bg-primary">{{ $panel->pathology_rates_count }}</span></td>
                                <td><span class="badge bg-success">{{ $panel->radiology_rates_count }}</span></td>
                                <td><span class="badge bg-success">{{ $panel->mapped_rates_count }}</span></td>
                                <td><span class="badge bg-warning text-dark">{{ $panel->review_rates_count }}</span></td>
                                <td><span class="badge bg-danger">{{ $panel->unmapped_rates_count }}</span></td>
                                <td class="text-nowrap">
                                    <a href="{{ route('insurance.test-mapping', ['panel_id' => $panel->id, 'test_type' => 'pathology']) }}" class="btn btn-sm btn-outline-primary">Pathology</a>
                                    <a href="{{ route('insurance.test-mapping', ['panel_id' => $panel->id, 'test_type' => 'radiology']) }}" class="btn btn-sm btn-outline-success">Radiology</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No panels yet. Import pathology and radiology Excel files separately above.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="panel_insurers_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('insurance.rate-panels.companies.update') }}" method="POST" id="panel_insurers_form">
                @csrf
                @method('PUT')
                <input type="hidden" name="panel_id" id="panel_insurers_panel_id">
                <div class="modal-header">
                    <h5 class="modal-title">Insurance companies — <span id="panel_insurers_title"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">
                        Select which insurance companies belong to this rate panel (e.g. National Insurance under GIPSA).
                        <a href="{{ route('insurance.management') }}">Add insurance companies</a>
                    </p>
                    <div class="d-flex gap-2 mb-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="panel_ins_select_all">Select all</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="panel_ins_deselect_all">Deselect all</button>
                    </div>
                    <div id="panel_insurers_checkboxes">
                        @include('admin.insurance.partials.panel_insurance_checkboxes', [
                            'selectedIds' => [],
                        ])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setPanelInsuranceCheckboxes(idsCsv) {
    const ids = (idsCsv || '').split(',').filter(Boolean).map(String);
    document.querySelectorAll('.panel-insurance-checkbox').forEach(cb => {
        cb.checked = ids.includes(String(cb.value));
    });
}

document.querySelectorAll('.panel-insurers-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('panel_insurers_panel_id').value = this.dataset.panelId;
        document.getElementById('panel_insurers_title').textContent = this.dataset.panelName;
        setPanelInsuranceCheckboxes(this.dataset.companyIds);
        new bootstrap.Modal(document.getElementById('panel_insurers_modal')).show();
    });
});

document.getElementById('panel_ins_select_all')?.addEventListener('click', function() {
    document.querySelectorAll('.panel-insurance-checkbox').forEach(cb => { cb.checked = true; });
});
document.getElementById('panel_ins_deselect_all')?.addEventListener('click', function() {
    document.querySelectorAll('.panel-insurance-checkbox').forEach(cb => { cb.checked = false; });
});
</script>

@endsection
