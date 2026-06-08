@extends('layouts.adminLayout')

@section('content')



<div class="row px-5 py-4">

    <div class="col-12">

        <div class="card shadow-sm">

            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">

                <h5 class="mb-0" style="color: #750096">

                    <i class="ti ti-link me-2"></i>Insurance Test Mapping —

                    <span class="badge {{ $testType === 'pathology' ? 'bg-primary' : 'bg-success' }}">{{ ucfirst($testType) }}</span>

                </h5>

            </div>

            <div class="card-body">

                @if(session('success'))

                    <div class="alert alert-success">{{ session('success') }}</div>

                @endif

                @if(session('error'))

                    <div class="alert alert-danger">{{ session('error') }}</div>

                @endif



                <div class="d-flex flex-wrap gap-2 mb-3">

                    <a href="{{ route('insurance.test-mapping', ['test_type' => 'pathology', 'status' => $status, 'panel_id' => request('panel_id')]) }}"

                        class="btn btn-sm {{ $testType === 'pathology' ? 'btn-primary' : 'btn-outline-primary' }}">Pathology</a>

                    <a href="{{ route('insurance.test-mapping', ['test_type' => 'radiology', 'status' => $status, 'panel_id' => request('panel_id')]) }}"

                        class="btn btn-sm {{ $testType === 'radiology' ? 'btn-success' : 'btn-outline-success' }}">Radiology</a>

                    <span class="border-start mx-1"></span>

                    <a href="{{ route('insurance.test-mapping', ['test_type' => $testType, 'status' => 'unmapped']) }}"

                        class="btn btn-sm {{ $status === 'unmapped' ? 'btn-danger' : 'btn-outline-danger' }}">Unmapped ({{ $counts['unmapped'] }})</a>

                    <a href="{{ route('insurance.test-mapping', ['test_type' => $testType, 'status' => 'needs_review']) }}"

                        class="btn btn-sm {{ $status === 'needs_review' ? 'btn-warning' : 'btn-outline-warning' }}">Review ({{ $counts['needs_review'] }})</a>

                    <a href="{{ route('insurance.test-mapping', ['test_type' => $testType, 'status' => 'mapped']) }}"

                        class="btn btn-sm {{ $status === 'mapped' ? 'btn-success' : 'btn-outline-success' }}">Mapped ({{ $counts['mapped'] }})</a>

                </div>



                <form method="GET" class="row g-2 mb-3">

                    <input type="hidden" name="test_type" value="{{ $testType }}">

                    <input type="hidden" name="status" value="{{ $status }}">

                    <div class="col-md-3">

                        <select name="panel_id" class="form-select form-select-sm" onchange="this.form.submit()">

                            <option value="">All Panels</option>

                            @foreach($panels as $id => $name)

                                <option value="{{ $id }}" {{ request('panel_id') == $id ? 'selected' : '' }}>{{ $name }}</option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."

                            value="{{ request('search') }}">

                    </div>

                    <div class="col-md-5">

                        <button type="submit" class="btn btn-sm btn-secondary">Filter</button>

                        <a href="{{ route('insurance.rate-panels') }}" class="btn btn-sm btn-outline-secondary">Import Page</a>

                    </div>

                </form>



                <form action="{{ route('insurance.test-mapping.auto-map') }}" method="POST" class="mb-3">

                    @csrf

                    <input type="hidden" name="test_type" value="{{ $testType }}">

                    <input type="hidden" name="panel_id" value="{{ request('panel_id') }}">

                    <input type="hidden" name="status" value="{{ $status === 'all' ? '' : $status }}">

                    <button type="submit" class="btn btn-sm btn-info text-white"

                        onclick="return confirm('Auto-map {{ $testType }} records using fuzzy match?');">

                        <i class="ti ti-wand me-1"></i>Auto-Map {{ ucfirst($testType) }}

                    </button>

                </form>



                <div class="table-responsive">

                    <table class="table table-sm table-bordered align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>Panel</th>

                                <th>Hospital Name (Excel)</th>

                                <th>Insurer Test Name</th>

                                <th>Rate</th>

                                <th>Status</th>

                                <th>Map to Hospital {{ ucfirst($testType) }} Test</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($rates as $rate)

                            @php

                                $allTests = $testType === 'pathology' ? $pathologyTests : $radiologyTests;

                                $filteredTests = $rate->hospital_system_name

                                    ? $allTests->filter(function ($test) use ($rate) {

                                        $pattern = strtoupper($rate->hospital_system_name);

                                        $name = strtoupper($test->test_name ?? '');

                                        $short = strtoupper($test->short_name ?? '');

                                        return str_starts_with($name, $pattern)

                                            || str_starts_with($short, $pattern)

                                            || str_contains($name, $pattern);

                                    })

                                    : $allTests;

                                $matchCount = $filteredTests->count();

                                $isGroupRow = $matchCount > 1 && !$rate->radiology_id && !$rate->pathology_id;

                            @endphp

                            <tr>

                                <td>{{ $rate->panel->name ?? '-' }}</td>

                                <td>{{ $rate->hospital_system_name ?? '—' }}</td>

                                <td>{{ $rate->insurer_test_name }}</td>

                                <td>₹{{ number_format($rate->rate, 2) }}</td>

                                <td>

                                    @if($rate->mapping_status === 'mapped')

                                        <span class="badge bg-success">Mapped</span>

                                        @if($isGroupRow || ($matchCount > 1 && !$rate->canonicalTestName()))

                                            <br><small class="text-muted">Group expanded</small>

                                        @endif

                                    @elseif($rate->mapping_status === 'needs_review')

                                        <span class="badge bg-warning text-dark">Review</span>

                                    @else

                                        <span class="badge bg-danger">Unmapped</span>

                                    @endif

                                </td>

                                <td>

                                    @if($matchCount > 1)
                                        <form action="{{ route('insurance.test-mapping.bulk-map') }}" method="POST" class="bulk-map-form mb-2"
                                            data-rate-id="{{ $rate->id }}"
                                            data-rate-amount="{{ number_format($rate->rate, 2) }}"
                                            data-panel-name="{{ $rate->panel->name ?? 'panel' }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $rate->id }}">
                                            <div class="alert alert-light border py-2 px-2 mb-2 small">
                                                <strong>{{ $matchCount }}</strong> hospital {{ $testType }} test(s) match
                                                <em>{{ $rate->hospital_system_name }}</em>.
                                                Select which tests should receive ₹{{ number_format($rate->rate, 2) }} under this panel.
                                            </div>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary bulk-select-all">Select all</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary bulk-deselect-all">Deselect all</button>
                                                <span class="align-self-center small text-muted bulk-selected-count">{{ $matchCount }} selected</span>
                                            </div>
                                            <div class="border rounded p-2 mb-2 bulk-test-list" style="max-height:220px;overflow-y:auto;background:#fafafa">
                                                @foreach($filteredTests as $test)
                                                    <div class="form-check">
                                                        <input class="form-check-input bulk-test-checkbox" type="checkbox"
                                                            name="test_ids[]" value="{{ $test->id }}"
                                                            id="bulk-{{ $rate->id }}-{{ $test->id }}" checked>
                                                        <label class="form-check-label small" for="bulk-{{ $rate->id }}-{{ $test->id }}">
                                                            {{ $test->test_name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-warning text-dark bulk-map-submit">
                                                <i class="ti ti-layers-linked me-1"></i>Map selected
                                            </button>
                                        </form>
                                    @endif



                                    <form action="{{ route('insurance.test-mapping.map') }}" method="POST" class="d-flex flex-wrap gap-2 align-items-center">

                                        @csrf

                                        <input type="hidden" name="id" value="{{ $rate->id }}">

                                        @if($testType === 'pathology')

                                            <select name="pathology_id" class="form-select form-select-sm mapping-test-select" style="min-width:280px" {{ $matchCount > 1 ? '' : 'required' }}>

                                                <option value="">— Select pathology test —</option>

                                                @foreach($filteredTests as $test)

                                                    <option value="{{ $test->id }}" {{ $rate->pathology_id == $test->id ? 'selected' : '' }}>{{ $test->test_name }}</option>

                                                @endforeach

                                            </select>

                                        @else

                                            <select name="radiology_id" class="form-select form-select-sm mapping-test-select" style="min-width:280px" {{ $matchCount > 1 ? '' : 'required' }}>

                                                <option value="">— Select radiology test —</option>

                                                @foreach($filteredTests as $test)

                                                    <option value="{{ $test->id }}" {{ $rate->radiology_id == $test->id ? 'selected' : '' }}>{{ $test->test_name }}</option>

                                                @endforeach

                                            </select>

                                        @endif

                                        <button type="submit" class="btn btn-sm btn-primary">Save one</button>

                                        <button type="button" class="btn btn-sm btn-outline-info suggest-btn" data-id="{{ $rate->id }}">Suggest</button>

                                    </form>

                                    @if($rate->canonicalTestName())

                                        <small class="text-success d-block mt-1">→ {{ $rate->canonicalTestName() }}</small>

                                    @endif

                                    @if($matchCount === 0 && $rate->hospital_system_name)

                                        <small class="text-danger d-block mt-1">No hospital tests match "{{ $rate->hospital_system_name }}".</small>

                                    @endif

                                    <div class="suggest-list small text-muted mt-1" id="suggest-{{ $rate->id }}"></div>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="6" class="text-center text-muted py-4">

                                    No {{ $testType }} rates found. Import the {{ $testType }} Excel file first.

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">{{ $rates->links() }}</div>

            </div>

        </div>

    </div>

</div>



<script>
document.querySelectorAll('.suggest-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const target = document.getElementById('suggest-' + id);
        target.textContent = 'Loading...';
        fetch(`{{ route('insurance.test-mapping.suggestions') }}?id=${id}`)
            .then(r => r.json())
            .then(data => {
                if (!data.suggestions?.length) {
                    target.textContent = 'No suggestions.';
                    return;
                }
                target.innerHTML = data.suggestions.map(s =>
                    `<span class="badge bg-light text-dark border me-1">${s.name} (${s.score}%)</span>`
                ).join('');
            });
    });
});

function updateBulkSelectedCount(form) {
    const boxes = form.querySelectorAll('.bulk-test-checkbox');
    const checked = form.querySelectorAll('.bulk-test-checkbox:checked');
    const label = form.querySelector('.bulk-selected-count');
    if (label) {
        label.textContent = checked.length + ' of ' + boxes.length + ' selected';
    }
    const submitBtn = form.querySelector('.bulk-map-submit');
    if (submitBtn) {
        submitBtn.disabled = checked.length === 0;
        submitBtn.innerHTML = '<i class="ti ti-layers-linked me-1"></i>Map selected (' + checked.length + ')';
    }
}

document.querySelectorAll('.bulk-map-form').forEach(form => {
    updateBulkSelectedCount(form);

    form.querySelectorAll('.bulk-test-checkbox').forEach(cb => {
        cb.addEventListener('change', () => updateBulkSelectedCount(form));
    });

    form.querySelector('.bulk-select-all')?.addEventListener('click', function(e) {
        e.preventDefault();
        form.querySelectorAll('.bulk-test-checkbox').forEach(cb => { cb.checked = true; });
        updateBulkSelectedCount(form);
    });

    form.querySelector('.bulk-deselect-all')?.addEventListener('click', function(e) {
        e.preventDefault();
        form.querySelectorAll('.bulk-test-checkbox').forEach(cb => { cb.checked = false; });
        updateBulkSelectedCount(form);
    });

    form.addEventListener('submit', function(e) {
        const checked = form.querySelectorAll('.bulk-test-checkbox:checked').length;
        if (checked === 0) {
            e.preventDefault();
            alert('Please select at least one test to map.');
            return;
        }
        const amount = form.dataset.rateAmount || '';
        const panel = form.dataset.panelName || 'panel';
        if (!confirm('Map ' + checked + ' selected test(s) at ₹' + amount + ' for ' + panel + '?')) {
            e.preventDefault();
        }
    });
});

document.querySelectorAll('.mapping-test-select').forEach(select => {
    select.addEventListener('change', function() {
        const cell = this.closest('td');
        if (!cell) return;
        const testId = this.value;
        cell.querySelectorAll('.bulk-test-checkbox').forEach(cb => {
            cb.checked = (testId !== '' && cb.value === testId);
        });
        const form = cell.querySelector('.bulk-map-form');
        if (form) updateBulkSelectedCount(form);
    });
});
</script>



@endsection

