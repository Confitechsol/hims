@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #750096"><i class="ti ti-bed me-2"></i>Room Tier → Bed Group Mappings</h5>
                <a href="{{ route('packages.index') }}" class="btn btn-secondary btn-sm">Back to Packages</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

            <div class="alert alert-info small mb-3">
                <strong>Room tier codes</strong> (GEN / SEMI / PVT, Galaxy GW/SHR/DLX, or ICICI A–D) map insurer package columns to your hospital bed groups.
                <strong>L1–L4</strong> in GIPSA/Star/HDFC PDFs are <em>inclusion buckets</em>, not room tiers — configure those on each insurance package.
            </div>

            <form method="GET" class="mb-3">
                <label class="form-label">Filter by rate panel</label>
                <select name="insurance_rate_panel_id" id="filter_panel_id" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">All panels</option>
                    @foreach($ratePanels as $panel)
                        <option value="{{ $panel->id }}" {{ (string) $selectedPanelId === (string) $panel->id ? 'selected' : '' }}>{{ $panel->name }} ({{ $panel->code }})</option>
                    @endforeach
                </select>
            </form>

            <div id="mapping-tier-hint" class="small text-secondary mb-3"></div>

            <div class="row">
                <div class="col-lg-5">
                    <h6>Add mapping</h6>
                    <form method="POST" action="{{ route('packages.room-mappings.store') }}" class="card card-body border">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Rate Panel</label>
                            <select name="insurance_rate_panel_id" id="mapping_panel_id" class="form-select" required>
                                <option value="">Select</option>
                                @foreach($ratePanels as $panel)
                                    <option value="{{ $panel->id }}" {{ (string) $selectedPanelId === (string) $panel->id ? 'selected' : '' }}>{{ $panel->name }} ({{ $panel->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Room tier code</label>
                            <input type="text" name="insurer_room_code" id="insurer_room_code_input" class="form-control" placeholder="GEN, SEMI, PVT" required>
                            <small class="text-muted" id="room-code-examples">e.g. GEN = General, SEMI = Semi-Private, PVT = Private</small>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Bed group</label>
                            <select name="bed_group_id" class="form-select" required>
                                <option value="">Select bed group</option>
                                @forelse($bedGroups as $bg)
                                    <option value="{{ $bg->id }}">{{ $bg->name }}</option>
                                @empty
                                    <option value="" disabled>No bed groups found — add them under Bed Group setup</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Label (optional)</label>
                            <input type="text" name="label" id="mapping_label_input" class="form-control" placeholder="General Ward, Twin Sharing…">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Save mapping</button>
                    </form>
                </div>
                <div class="col-lg-7">
                    <h6>Current mappings</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Panel</th>
                                    <th>Tier code</th>
                                    <th>Bed Group</th>
                                    <th>Label</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mappings as $m)
                                <tr>
                                    <td>{{ $m->ratePanel->name ?? '—' }}</td>
                                    <td><code>{{ $m->insurer_room_code }}</code></td>
                                    <td>{{ $m->bedGroup->name ?? $m->bed_group_id }}</td>
                                    <td>{{ $m->label ?? '—' }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('packages.room-mappings.destroy', $m->id) }}" onsubmit="return confirm('Remove?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">×</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-muted">No mappings yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const panelSchemes = @json($panelSchemesJson ?? []);
    const panelSelect = document.getElementById('mapping_panel_id');
    const hint = document.getElementById('mapping-tier-hint');
    const codeExamples = document.getElementById('room-code-examples');
    const codeInput = document.getElementById('insurer_room_code_input');

    function updateHints() {
        const panelId = panelSelect?.value;
        const preset = panelId && panelSchemes[panelId] ? panelSchemes[panelId] : null;
        if (!preset || !preset.tiers) {
            if (hint) hint.textContent = '';
            if (codeExamples) codeExamples.textContent = 'e.g. GEN = General, SEMI = Semi-Private, PVT = Private';
            if (codeInput) codeInput.placeholder = 'GEN, SEMI, PVT';
            return;
        }
        if (hint) hint.textContent = 'Panel scheme: ' + (preset.scheme_label || '');
        if (codeExamples) {
            codeExamples.textContent = preset.tiers.map(t => t.code + ' = ' + t.label).join(' · ');
        }
        if (codeInput && preset.tiers[0]) {
            codeInput.placeholder = preset.tiers.map(t => t.code).join(', ');
        }
    }

    panelSelect?.addEventListener('change', updateHints);
    updateHints();
});
</script>
@endsection
