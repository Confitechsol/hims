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
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row mb-4 g-3">
                    <div class="col-md-6">
                        <div class="card border h-100">
                            <div class="card-header bg-light py-2">
                                <strong><i class="ti ti-flask me-1"></i>Pathology Import</strong>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small">File: <code>PATHOLOGY_INSURANCE TEST RATE.xlsx</code><br>
                                    Sheets: GIPSA, ICICI LOMBARD, STAR<br>
                                    Choose a panel to import only that sheet (other panels stay unchanged).</p>
                                <form action="{{ route('insurance.rate-panels.import.pathology') }}" method="POST" enctype="multipart/form-data" class="mb-2" id="pathology_upload_form">
                                    @csrf
                                    <label class="form-label small mb-1">Panel scope</label>
                                    <select name="panel_code" class="form-select form-select-sm mb-2 import-panel-select" data-type="pathology">
                                        <option value="">All panels (full import)</option>
                                        <option value="GIPSA">GIPSA Panel (GIPSA)</option>
                                        <option value="ICICI_LOMBARD">ICICI Lombard Panel (ICICI_LOMBARD)</option>
                                        <option value="STAR_HEALTH">Star Health Panel (STAR_HEALTH)</option>
                                    </select>
                                    <input type="file" name="file" class="form-control form-control-sm mb-2" accept=".xlsx,.xls" required>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ti ti-upload me-1"></i>Upload Pathology Excel
                                    </button>
                                </form>
                                <form action="{{ route('insurance.rate-panels.import.pathology') }}" method="POST" id="pathology_default_form">
                                    @csrf
                                    <input type="hidden" name="use_default_file" value="1">
                                    <input type="hidden" name="panel_code" id="pathology_default_panel_code" value="">
                                    <button type="submit" class="btn btn-outline-primary btn-sm" id="pathology_default_btn">
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
                                    Sheets: GIPSA, ICICI LOMBARD, STAR<br>
                                    Choose a panel to import only that sheet (other panels stay unchanged).</p>
                                <form action="{{ route('insurance.rate-panels.import.radiology') }}" method="POST" enctype="multipart/form-data" class="mb-2" id="radiology_upload_form">
                                    @csrf
                                    <label class="form-label small mb-1">Panel scope</label>
                                    <select name="panel_code" class="form-select form-select-sm mb-2 import-panel-select" data-type="radiology">
                                        <option value="">All panels (full import)</option>
                                        <option value="GIPSA">GIPSA Panel (GIPSA)</option>
                                        <option value="ICICI_LOMBARD">ICICI Lombard Panel (ICICI_LOMBARD)</option>
                                        <option value="STAR_HEALTH">Star Health Panel (STAR_HEALTH)</option>
                                    </select>
                                    <input type="file" name="file" class="form-control form-control-sm mb-2" accept=".xlsx,.xls" required>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="ti ti-upload me-1"></i>Upload Radiology Excel
                                    </button>
                                </form>
                                <form action="{{ route('insurance.rate-panels.import.radiology') }}" method="POST" id="radiology_default_form">
                                    @csrf
                                    <input type="hidden" name="use_default_file" value="1">
                                    <input type="hidden" name="panel_code" id="radiology_default_panel_code" value="">
                                    <button type="submit" class="btn btn-outline-success btn-sm" id="radiology_default_btn">
                                        Import Default Radiology File
                                    </button>
                                </form>
                                <a href="{{ route('insurance.test-mapping', ['test_type' => 'radiology']) }}" class="btn btn-link btn-sm ps-0 mt-2">Map radiology tests →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border mb-4">
                    <div class="card-header bg-light py-2">
                        <strong><i class="ti ti-plus me-1"></i>Add / Update Single Test Rate</strong>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Save one pathology or radiology rate for one insurance panel without Excel import.
                            Linked insurers for that panel will use this rate when the test is mapped.
                        </p>
                        <form action="{{ route('insurance.rate-panels.rates.store') }}" method="POST" id="single_rate_form">
                            @csrf
                            <div class="row g-2 align-items-end">
                                <div class="col-md-2">
                                    <label class="form-label small mb-1">Test type</label>
                                    <select name="test_type" id="single_rate_test_type" class="form-select form-select-sm" required>
                                        <option value="pathology" @selected(old('test_type', 'pathology') === 'pathology')>Pathology</option>
                                        <option value="radiology" @selected(old('test_type') === 'radiology')>Radiology</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small mb-1">Panel</label>
                                    <select name="panel_id" class="form-select form-select-sm" required>
                                        <option value="">Select panel</option>
                                        @foreach($activePanels as $p)
                                            <option value="{{ $p->id }}" @selected((string) old('panel_id') === (string) $p->id)>
                                                {{ $p->name }} ({{ $p->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3" id="single_rate_pathology_wrap">
                                    <label class="form-label small mb-1">Pathology test</label>
                                    <select name="pathology_id" id="single_rate_pathology_id" class="form-select form-select-sm">
                                        <option value="">Select test</option>
                                        @foreach($pathologyTests as $t)
                                            <option value="{{ $t->id }}" @selected((string) old('pathology_id') === (string) $t->id)>
                                                {{ $t->test_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-none" id="single_rate_radiology_wrap">
                                    <label class="form-label small mb-1">Radiology test</label>
                                    <select name="radiology_id" id="single_rate_radiology_id" class="form-select form-select-sm">
                                        <option value="">Select test</option>
                                        @foreach($radiologyTests as $t)
                                            <option value="{{ $t->id }}" @selected((string) old('radiology_id') === (string) $t->id)>
                                                {{ $t->test_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small mb-1">Rate (₹)</label>
                                    <input type="number" name="rate" class="form-control form-control-sm" min="0.01" step="0.01"
                                           value="{{ old('rate') }}" required placeholder="0.00">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small mb-1">Insurer test name <span class="text-muted">(optional)</span></label>
                                    <input type="text" name="insurer_test_name" class="form-control form-control-sm"
                                           value="{{ old('insurer_test_name') }}" placeholder="Defaults to hospital test name">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="ti ti-device-floppy me-1"></i>Save rate
                                    </button>
                                </div>
                            </div>
                        </form>
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

function toggleSingleRateTestSelects() {
    const type = document.getElementById('single_rate_test_type')?.value || 'pathology';
    const pathWrap = document.getElementById('single_rate_pathology_wrap');
    const radWrap = document.getElementById('single_rate_radiology_wrap');
    const pathSelect = document.getElementById('single_rate_pathology_id');
    const radSelect = document.getElementById('single_rate_radiology_id');
    if (!pathWrap || !radWrap) return;

    if (type === 'radiology') {
        pathWrap.classList.add('d-none');
        radWrap.classList.remove('d-none');
        if (pathSelect) { pathSelect.removeAttribute('required'); pathSelect.value = ''; }
        if (radSelect) radSelect.setAttribute('required', 'required');
    } else {
        radWrap.classList.add('d-none');
        pathWrap.classList.remove('d-none');
        if (radSelect) { radSelect.removeAttribute('required'); radSelect.value = ''; }
        if (pathSelect) pathSelect.setAttribute('required', 'required');
    }
}

document.getElementById('single_rate_test_type')?.addEventListener('change', toggleSingleRateTestSelects);
toggleSingleRateTestSelects();

function syncDefaultPanelCode(type) {
    const select = document.querySelector(`.import-panel-select[data-type="${type}"]`);
    const hidden = document.getElementById(`${type}_default_panel_code`);
    if (select && hidden) hidden.value = select.value || '';
}

document.querySelectorAll('.import-panel-select').forEach(sel => {
    sel.addEventListener('change', function() {
        syncDefaultPanelCode(this.dataset.type);
    });
    syncDefaultPanelCode(sel.dataset.type);
});

function confirmImport(type, form) {
    const select = form.querySelector('select[name="panel_code"]')
        || document.querySelector(`.import-panel-select[data-type="${type}"]`);
    const panelCode = select ? (select.value || '') : '';
    if (panelCode) {
        return confirm(
            `Import ${type} rates for panel ${panelCode} only?\nExisting ${type} rates for that panel will be replaced. Other panels will not change.`
        );
    }
    return confirm(
        `Re-import all ${type} rates?\nExisting ${type} rows for every matched panel sheet will be replaced.`
    );
}

document.getElementById('pathology_upload_form')?.addEventListener('submit', function(e) {
    if (!confirmImport('pathology', this)) e.preventDefault();
});
document.getElementById('radiology_upload_form')?.addEventListener('submit', function(e) {
    if (!confirmImport('radiology', this)) e.preventDefault();
});
document.getElementById('pathology_default_form')?.addEventListener('submit', function(e) {
    syncDefaultPanelCode('pathology');
    if (!confirmImport('pathology', this)) e.preventDefault();
});
document.getElementById('radiology_default_form')?.addEventListener('submit', function(e) {
    syncDefaultPanelCode('radiology');
    if (!confirmImport('radiology', this)) e.preventDefault();
});
</script>

@endsection
