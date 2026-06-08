@php
    $inputPrefix = $inputPrefix ?? '';
    $selectedIds = $selectedIds ?? old('rate_panel_ids', []);
    if (!is_array($selectedIds)) {
        $selectedIds = [];
    }
@endphp
<div class="col-12">
    <div class="mb-3">
        <label class="form-label">Insurance Rate Panels</label>
        <p class="text-muted small mb-2">
            Select which rate panels (GIPSA, ICICI Lombard, Star, etc.) apply to this insurance company for billing.
        </p>
        @if($ratePanels->isEmpty())
            <div class="alert alert-warning py-2 small mb-0">
                No panels found. Import test rates first from <a href="{{ route('insurance.rate-panels') }}">Insurance Test Rates</a>.
            </div>
        @else
            <div class="border rounded p-2" style="max-height:160px;overflow-y:auto;background:#fafafa">
                @foreach($ratePanels as $panel)
                    <div class="form-check">
                        <input class="form-check-input rate-panel-checkbox" type="checkbox"
                            name="rate_panel_ids[]"
                            value="{{ $panel->id }}"
                            id="{{ $inputPrefix }}panel-{{ $panel->id }}"
                            {{ in_array($panel->id, $selectedIds) || in_array((string) $panel->id, $selectedIds) ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $inputPrefix }}panel-{{ $panel->id }}">
                            {{ $panel->name }}
                            <code class="small text-muted">{{ $panel->code }}</code>
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
