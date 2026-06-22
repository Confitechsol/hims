@php
    $selectedIds = $selectedIds ?? old('insurance_company_ids', []);
    if (!is_array($selectedIds)) {
        $selectedIds = [];
    }
@endphp
<div class="border rounded p-2 mb-2" style="max-height:240px;overflow-y:auto;background:#fafafa">
    @forelse($insuranceCompanies as $company)
        <div class="form-check">
            <input class="form-check-input panel-insurance-checkbox" type="checkbox"
                name="insurance_company_ids[]"
                value="{{ $company->id }}"
                id="panel-ins-{{ $company->id }}"
                {{ in_array($company->id, $selectedIds) || in_array((string) $company->id, $selectedIds) ? 'checked' : '' }}>
            <label class="form-check-label small" for="panel-ins-{{ $company->id }}">
                {{ $company->name }}
                <code class="text-muted">{{ $company->code }}</code>
            </label>
        </div>
    @empty
        <p class="text-muted small mb-0">No insurance companies. Add them in <a href="{{ route('insurance.management') }}">Insurance Management</a> first.</p>
    @endforelse
</div>
