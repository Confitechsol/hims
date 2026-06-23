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
                    <select name="insurance_rate_panel_id" class="form-select">
                        <option value="">— Select panel —</option>
                        @foreach($ratePanels as $panel)
                            <option value="{{ $panel->id }}" {{ (string) old('insurance_rate_panel_id', $package->insurance_rate_panel_id) === (string) $panel->id ? 'selected' : '' }}>
                                {{ $panel->name }} ({{ $panel->code }})
                            </option>
                        @endforeach
                    </select>
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
                    <input type="text" name="insurer_procedure_code" class="form-control" placeholder="e.g. S040004"
                        value="{{ old('insurer_procedure_code', $package->insurer_procedure_code) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Speciality</label>
                    <input type="text" name="speciality" class="form-control" placeholder="e.g. General Surgery"
                        value="{{ old('speciality', $package->speciality) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Room Eligibility (A,B,C,D)</label>
                    <input type="text" name="room_eligibility" class="form-control" placeholder="A,B,C,D"
                        value="{{ old('room_eligibility', $package->room_eligibility) }}">
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
            </div>
        </div>
    </div>
</div>

<div id="room-rates-wrap" class="col-12 mb-4 {{ $isInsurance ? '' : 'd-none' }}">
    <div class="card border">
        <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
            <strong>Room-tier Package Rates</strong>
            <a href="{{ route('packages.room-mappings') }}" class="btn btn-sm btn-outline-secondary" target="_blank">Bed group mappings</a>
        </div>
        <div class="card-body p-0">
            <p class="text-muted small px-3 pt-2 mb-0">Set a different package amount per bed group (General Ward, Twin, Single, etc.). Default package rate uses the lowest tier.</p>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bed Group</th>
                            <th width="100">Room Code</th>
                            <th width="140">Label</th>
                            <th width="160">Rate (INR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bedGroups as $idx => $bg)
                            @php
                                $existing = $existingRoomRates->get($bg->id);
                                $oldRates = old('room_rates', []);
                                $oldRow = $oldRates[$idx] ?? [];
                            @endphp
                            <tr>
                                <td class="align-middle">
                                    {{ $bg->name }}
                                    <input type="hidden" name="room_rates[{{ $idx }}][bed_group_id]" value="{{ $bg->id }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="room_rates[{{ $idx }}][insurer_room_code]"
                                        value="{{ $oldRow['insurer_room_code'] ?? $existing?->insurer_room_code }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="room_rates[{{ $idx }}][label]"
                                        value="{{ $oldRow['label'] ?? $existing?->label }}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm room-rate-input" name="room_rates[{{ $idx }}][rate]"
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

    typeSelect?.addEventListener('change', toggleInsuranceSections);
    document.querySelectorAll('.room-rate-input').forEach(el => {
        el.addEventListener('change', syncMinRoomRateToPackageRate);
    });
    toggleInsuranceSections();
});
</script>
