@php
    $testType = $testType ?? 'pathology';
    $editable = $editable ?? true;
@endphp

<div class="row mb-3">
    <div class="col-12">
        <h6 class="mb-3">
            <i class="ti ti-shield-check me-2"></i>Insurance Panel Rates
            @if($editable)
                <small class="text-muted fw-normal">(optional — leave blank to use standard charge at billing)</small>
            @endif
        </h6>
        <div class="card border">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th width="28%">Panel</th>
                                <th width="28%">Insurer test name</th>
                                <th width="22%">Rate (INR)</th>
                                <th width="12%">Status</th>
                                @if($editable)
                                    <th width="10%">Code</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($panelRates as $row)
                                @php
                                    $panel = $row['panel'];
                                    $rate = $row['rate'];
                                @endphp
                                <tr>
                                    <td><strong>{{ $panel->name }}</strong></td>
                                    <td>
                                        @if($editable)
                                            <span class="text-muted small">{{ $rate?->insurer_test_name ?? '—' }}</span>
                                        @else
                                            {{ $rate?->insurer_test_name ?? '—' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($editable)
                                            <input type="number"
                                                name="insurance_rate[{{ $panel->id }}]"
                                                class="form-control form-control-sm"
                                                value="{{ old('insurance_rate.' . $panel->id, $rate?->rate) }}"
                                                step="0.01"
                                                min="0"
                                                placeholder="Not set">
                                        @else
                                            @if($rate && $rate->rate)
                                                ₹{{ number_format($rate->rate, 2) }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($rate && $rate->mapping_status === 'mapped')
                                            <span class="badge bg-success">Mapped</span>
                                        @elseif($rate)
                                            <span class="badge bg-warning text-dark">{{ ucfirst(str_replace('_', ' ', $rate->mapping_status)) }}</span>
                                        @else
                                            <span class="badge bg-secondary">Not set</span>
                                        @endif
                                    </td>
                                    @if($editable)
                                        <td><small class="text-muted">{{ $panel->code }}</small></td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted text-center py-3">
                                        No insurance panels configured.
                                        <a href="{{ route('insurance.rate-panels') }}">Import rates</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <small class="text-muted d-block mt-2">
                    <i class="ti ti-info-circle me-1"></i>
                    Bulk import and mapping:
                    <a href="{{ route('insurance.test-mapping', ['test_type' => $testType]) }}">Insurance Test Mapping</a>
                </small>
            </div>
        </div>
    </div>
</div>
