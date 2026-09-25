@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-file-invoice me-2"></i> GST Break Report</h5>
                    <a href="{{ route('finance') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i> Finance</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(!empty($reportError))
                    <div class="alert alert-danger">{{ $reportError }}</div>
                @endif

                <form action="{{ route('reports.gst-break') }}" method="GET" id="gst-break-form">
                    <div class="row align-items-end gy-3">
                        <div class="col-md-3">
                            <label class="form-label">Date From <span class="text-danger">*</span></label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To <span class="text-danger">*</span></label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 position-relative">
                            <label class="form-label">IPD patient</label>
                            <input type="text" id="patient_query" name="patient_query" class="form-control" value="{{ $patientQuery }}" placeholder="Search IPD number or patient name" autocomplete="off">
                            <input type="hidden" id="ipd_id" name="ipd_id" value="{{ $ipdId }}">
                            <div id="patient_suggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index: 20; display:none;"></div>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">Generate</button>
                            <a href="{{ route('reports.gst-break') }}" class="btn btn-secondary btn-sm">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($result)
    <div class="col-md-11 mt-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0" style="color:#212529;">
                    GST Break:
                    {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }}
                    to
                    {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}
                    @if(!empty($result['patient_label']))
                        — {{ $result['patient_label'] }}
                    @endif
                    ({{ $result['line_count'] ?? 0 }} line(s))
                </h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('reports.gst-break.excel') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        @if(!empty($result['ipd_id']))
                            <input type="hidden" name="ipd_id" value="{{ $result['ipd_id'] }}">
                        @endif
                        <button type="submit" class="btn btn-success btn-sm">Export Excel</button>
                    </form>
                    <form action="{{ route('reports.gst-break.pdf') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        @if(!empty($result['ipd_id']))
                            <input type="hidden" name="ipd_id" value="{{ $result['ipd_id'] }}">
                        @endif
                        <button type="submit" class="btn btn-danger btn-sm">Export PDF</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(!empty($result['errors']))
                    <div class="alert alert-warning">
                        <strong>{{ count($result['errors']) }} bill(s) could not be included.</strong>
                    </div>
                @endif
                @if(empty($result['rows']))
                    <p class="text-muted mb-0">No discharged bills found for this filter.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr style="background:#e8e8e8;color:#212529;">
                                <th>Sr. No.</th>
                                <th>Admission No.</th>
                                <th>Admission Date</th>
                                <th>Patient Name</th>
                                <th>Doctor Name</th>
                                <th>Bill No.</th>
                                <th>Bill Date</th>
                                <th>Discharge Date</th>
                                <th>Print Head</th>
                                <th>Particular Details</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result['rows'] as $row)
                            <tr>
                                <td>{{ $row['sl_no'] }}</td>
                                <td>{{ $row['admission_no'] }}</td>
                                <td>{{ $row['admission_date'] }}</td>
                                <td>{{ $row['patient_name'] }}</td>
                                <td>{{ $row['doctor_name'] }}</td>
                                <td>{{ $row['bill_no'] }}</td>
                                <td>{{ $row['bill_date'] }}</td>
                                <td>{{ $row['discharge_date'] }}</td>
                                <td>{{ $row['print_head'] }}</td>
                                <td>{{ $row['particulars'] }}</td>
                                <td class="text-end">{{ number_format($row['amount'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#e8e8e8;color:#212529;" class="fw-bold">
                                <td colspan="10" class="text-end">Total</td>
                                <td class="text-end">{{ number_format($result['total_amount'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <p class="text-muted small mt-2 mb-0">
                    Print head follows the charge type. Money receipts are listed under Money Receipt. When a package applies, bed charge is excluded.
                </p>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('script')
<script>
(function () {
    const input = document.getElementById('patient_query');
    const hidden = document.getElementById('ipd_id');
    const box = document.getElementById('patient_suggestions');
    const searchUrl = @json(route('reports.gst-break.search'));
    let timer = null;

    function hide() { box.style.display = 'none'; box.innerHTML = ''; }

    input.addEventListener('input', function () {
        hidden.value = '';
        const q = input.value.trim();
        clearTimeout(timer);
        if (q.length < 2) { hide(); return; }
        timer = setTimeout(function () {
            fetch(searchUrl + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (rows) {
                    box.innerHTML = '';
                    if (!rows.length) { hide(); return; }
                    rows.forEach(function (row) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'list-group-item list-group-item-action';
                        btn.textContent = row.label;
                        btn.addEventListener('click', function () {
                            input.value = row.label;
                            hidden.value = row.id;
                            hide();
                        });
                        box.appendChild(btn);
                    });
                    box.style.display = 'block';
                })
                .catch(hide);
        }, 250);
    });

    document.addEventListener('click', function (e) {
        if (!box.contains(e.target) && e.target !== input) hide();
    });
})();
</script>
@endsection
