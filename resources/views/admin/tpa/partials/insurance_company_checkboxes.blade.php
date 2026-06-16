@php
    $inputPrefix = $inputPrefix ?? '';
    $selectedIds = $selectedIds ?? old('insurance_company_ids', []);
    if (!is_array($selectedIds)) {
        $selectedIds = [];
    }
@endphp
<div class="col-12">
    <div class="mb-3">
        <label class="form-label">Insurance Companies <span class="text-danger">*</span></label>
        <p class="text-muted small mb-2">
            Select one or more insurance companies this TPA works with.
        </p>
        @if($insuranceCompanies->isEmpty())
            <div class="alert alert-warning py-2 small mb-0">
                No insurance companies found. Add them from <a href="{{ route('insurance.management') }}">Insurance Management</a>.
            </div>
        @else
            <div class="border rounded p-2" style="max-height:160px;overflow-y:auto;background:#fafafa">
                @foreach($insuranceCompanies as $id => $name)
                    <div class="form-check">
                        <input class="form-check-input tpa-insurance-checkbox" type="checkbox"
                            name="insurance_company_ids[]"
                            value="{{ $id }}"
                            id="{{ $inputPrefix }}ins-{{ $id }}"
                            {{ in_array($id, $selectedIds) || in_array((string) $id, $selectedIds) ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $inputPrefix }}ins-{{ $id }}">
                            {{ $name }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
