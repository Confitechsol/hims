@php
    $isInsurance = old('package_type', $package->package_type ?? 'hospital') === 'insurance';
    $existingRoomRates = $package->relationLoaded('roomRates')
        ? $package->roomRates->keyBy('bed_group_id')
        : collect();
@endphp

<div class="col-md-4 mb-3">
    <label for="package_type" class="form-label">Package Type <span class="text-danger">*</span></label>
    <select class="form-select" id="package_type" name="package_type" required>
        <option value="hospital" {{ old('package_type', $package->package_type ?? 'hospital') === 'hospital' ? 'selected' : '' }}>Hospital (Cash / General)</option>
        <option value="insurance" {{ old('package_type', $package->package_type ?? '') === 'insurance' ? 'selected' : '' }}>Insurance (TPA / Panel)</option>
    </select>
</div>

<div id="insurance-fields-wrap" class="col-12 {{ $isInsurance ? '' : 'd-none' }}">
    <div class="card border mb-4">
        <div class="card-header py-2 bg-light">
            <strong>Insurance Package Details</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Rate Panel</label>
                    <select name="insurance_rate_panel_id" id="insurance_rate_panel_id" class="form-select">
                        <option value="">— Select panel —</option>
                        @foreach($ratePanels as $panel)
                            <option value="{{ $panel->id }}" data-code="{{ $panel->code }}"
                                {{ (string) old('insurance_rate_panel_id', $package->insurance_rate_panel_id) === (string) $panel->id ? 'selected' : '' }}>
                                {{ $panel->name }} ({{ $panel->code }})
                            </option>
                        @endforeach
                    </select>
                    <div id="panel-scheme-hint" class="form-text text-primary"></div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Insurance Company</label>
                    <select name="insurance_company_id" class="form-select">
                        <option value="">— Optional —</option>
                        @foreach($insuranceCompanies as $co)
                            <option value="{{ $co->id }}" {{ (string) old('insurance_company_id', $package->insurance_company_id) === (string) $co->id ? 'selected' : '' }}>
                                {{ $co->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Insurer Procedure Code</label>
                    <input type="text" name="insurer_procedure_code" class="form-control" placeholder="e.g. PPN G 06 A, S040004"
                        value="{{ old('insurer_procedure_code', $package->insurer_procedure_code) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Speciality</label>
                    <input type="text" name="speciality" class="form-control" placeholder="e.g. General Surgery"
                        value="{{ old('speciality', $package->speciality) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Package Inclusions (L1–L4)</label>
                    <input type="text" name="package_inclusions" id="package_inclusions" class="form-control"
                        placeholder="e.g. L1,L2,L3,L4"
                        value="{{ old('package_inclusions', $package->package_inclusions) }}">
                    <small class="text-muted">Cost buckets included in package price (not room tier).</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Package Exclusions (L1–L4)</label>
                    <input type="text" name="package_exclusions" id="package_exclusions" class="form-control"
                        placeholder="e.g. L2"
                        value="{{ old('package_exclusions', $package->package_exclusions) }}">
                    <small class="text-muted">Often L2 (implants/stents) excluded from base package.</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Linked Hospital Package</label>
                    <select name="linked_hospital_package_id" class="form-select">
                        <option value="">— Auto / none —</option>
                        @foreach($hospitalPackages as $hp)
                            <option value="{{ $hp->id }}" {{ (string) old('linked_hospital_package_id', $package->linked_hospital_package_id) === (string) $hp->id ? 'selected' : '' }}>
                                {{ $hp->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Cash/general package equivalent for this insurer procedure.</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Contract Reference</label>
                    <input type="text" name="contract_reference" class="form-control"
                        value="{{ old('contract_reference', $package->contract_reference) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Effective From</label>
                    <input type="date" name="effective_from" class="form-control"
                        value="{{ old('effective_from', optional($package->effective_from)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Effective To</label>
                    <input type="date" name="effective_to" class="form-control"
                        value="{{ old('effective_to', optional($package->effective_to)->format('Y-m-d')) }}">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Inclusion Notes</label>
                    <textarea name="inclusion_notes" class="form-control" rows="2" placeholder="e.g. Mesh up to Rs 7000 included">{{ old('inclusion_notes', $package->inclusion_notes) }}</textarea>
                </div>
                <div class="col-12">
                    <details class="small border rounded p-2 bg-light">
                        <summary class="fw-semibold">L1–L4 legend (GIPSA / Star / HDFC contracts)</summary>
                        <ul class="mb-0 mt-2">
                            @foreach($inclusionLegend as $code => $desc)
                                <li><strong>{{ $code }}:</strong> {{ $desc }}</li>
                            @endforeach
                        </ul>
                    </details>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="room-rates-wrap" class="col-12 mb-4 {{ $isInsurance ? '' : 'd-none' }}">
    <div class="card border">
        <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <strong>Room-tier Package Rates</strong>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" id="apply-panel-room-codes">Apply panel room codes</button>
                <a href="{{ route('packages.room-mappings') }}" class="btn btn-sm btn-outline-secondary" target="_blank">Bed group mappings</a>
            </div>
        </div>
        <div class="card-body p-0">
            <p class="text-muted small px-3 pt-2 mb-0">
                Set package amount per <strong>room tier</strong> (General / Semi-Private / Private, or Galaxy / ICICI codes).
                Map tiers to your bed groups under <em>Bed group mappings</em>.
            </p>
            <div id="room-tier-reference" class="small px-3 py-2 text-secondary"></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bed Group (hospital)</th>
                            <th width="110">Room tier code</th>
                            <th width="160">Tier label</th>
                            <th width="160">Rate (INR)</th>
                        </tr>
                    </thead>
                    <tbody id="room-rates-tbody">
                        @foreach($bedGroups as $idx => $bg)
                            @php
                                $existing = $existingRoomRates->get($bg->id);
                                $oldRates = old('room_rates', []);
                                $oldRow = $oldRates[$idx] ?? [];
                            @endphp
                            <tr class="room-rate-row" data-bed-cost="{{ $bg->bed_cost ?? 0 }}">
                                <td class="align-middle">
                                    {{ $bg->name }}
                                    <input type="hidden" name="room_rates[{{ $idx }}][bed_group_id]" value="{{ $bg->id }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm insurer-room-code-input"
                                        name="room_rates[{{ $idx }}][insurer_room_code]"
                                        value="{{ $oldRow['insurer_room_code'] ?? $existing?->insurer_room_code }}"
                                        placeholder="GEN">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm tier-label-input"
                                        name="room_rates[{{ $idx }}][label]"
                                        value="{{ $oldRow['label'] ?? $existing?->label }}"
                                        placeholder="General">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm room-rate-input"
                                        name="room_rates[{{ $idx }}][rate]"
                                        value="{{ $oldRow['rate'] ?? $existing?->rate }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('package_type');
    const insWrap = document.getElementById('insurance-fields-wrap');
    const roomWrap = document.getElementById('room-rates-wrap');
    const packageRateInput = document.getElementById('package_rate');
    const panelSelect = document.getElementById('insurance_rate_panel_id');
    const schemeHint = document.getElementById('panel-scheme-hint');
    const tierReference = document.getElementById('room-tier-reference');
    const applyCodesBtn = document.getElementById('apply-panel-room-codes');
    const panelSchemes = @json(json_decode($panelSchemesJson ?? '{}', true));

    function currentPanelPreset() {
        const panelId = panelSelect?.value;
        if (!panelId || !panelSchemes[panelId]) {
            return { scheme_label: 'GIPSA PPN (GEN / SEMI / PVT)', tiers: [
                { code: 'GEN', label: 'General' },
                { code: 'SEMI', label: 'Semi-Private' },
                { code: 'PVT', label: 'Private' },
            ]};
        }
        return panelSchemes[panelId];
    }

    function updatePanelHints() {
        const preset = currentPanelPreset();
        if (schemeHint) {
            schemeHint.textContent = preset.scheme_label ? ('Room tier scheme: ' + preset.scheme_label) : '';
        }
        if (tierReference && preset.tiers) {
            tierReference.textContent = 'Expected codes: ' + preset.tiers.map(t => t.code + ' = ' + t.label).join(' · ');
        }
    }

    function toggleInsuranceSections() {
        const isIns = typeSelect && typeSelect.value === 'insurance';
        insWrap?.classList.toggle('d-none', !isIns);
        roomWrap?.classList.toggle('d-none', !isIns);
    }

    function syncMinRoomRateToPackageRate() {
        if (!typeSelect || typeSelect.value !== 'insurance' || !packageRateInput) return;
        const rates = Array.from(document.querySelectorAll('.room-rate-input'))
            .map(el => parseFloat(el.value))
            .filter(v => !isNaN(v) && v > 0);
        if (rates.length) {
            packageRateInput.value = Math.min(...rates).toFixed(2);
        }
    }

    function applyPanelRoomCodes() {
        const preset = currentPanelPreset();
        const tiers = preset.tiers || [];
        if (!tiers.length) return;

        const rows = Array.from(document.querySelectorAll('.room-rate-row'))
            .sort((a, b) => parseFloat(a.dataset.bedCost || 0) - parseFloat(b.dataset.bedCost || 0));

        rows.forEach((row, i) => {
            const tier = tiers[Math.min(i, tiers.length - 1)];
            const codeInput = row.querySelector('.insurer-room-code-input');
            const labelInput = row.querySelector('.tier-label-input');
            if (codeInput && !codeInput.value) codeInput.value = tier.code;
            if (labelInput && !labelInput.value) labelInput.value = tier.label;
        });
    }

    typeSelect?.addEventListener('change', toggleInsuranceSections);
    panelSelect?.addEventListener('change', updatePanelHints);
    applyCodesBtn?.addEventListener('click', applyPanelRoomCodes);
    document.querySelectorAll('.room-rate-input').forEach(el => {
        el.addEventListener('change', syncMinRoomRateToPackageRate);
    });

    toggleInsuranceSections();
    updatePanelHints();
});
</script>
