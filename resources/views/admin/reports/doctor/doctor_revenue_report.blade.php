@extends('layouts.adminLayout')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-chart-line me-2"></i> Doctor Revenue Report</h5>
                    <a href="{{ route('doctor-reports-index') }}" class="text-white fw-bold"><i class="fa-solid fa-angles-left text-white"></i> Doctor Reports</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(!empty($reportError))
                    <div class="alert alert-danger">{{ $reportError }}</div>
                @endif

                <form action="{{ route('doctors.patient.revenue-report') }}" method="GET">
                    <div class="row align-items-end gy-3">
                        <div class="col-md-3">
                            <label class="form-label">Date From <span class="text-danger">*</span></label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To <span class="text-danger">*</span></label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm">Generate Report</button>
                            <a href="{{ route('doctors.patient.revenue-report') }}" class="btn btn-secondary btn-sm">Reset</a>
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
                <h6 class="mb-0">
                    Doctor Revenue Report:
                    {{ \Carbon\Carbon::parse($result['date_from'])->format('d/m/Y') }}
                    to
                    {{ \Carbon\Carbon::parse($result['date_to'])->format('d/m/Y') }}
                    ({{ $result['patient_count'] ?? 0 }} patient bill(s))
                </h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('doctors.patient.revenue-report.excel') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="ti ti-file-spreadsheet me-1"></i> Export Excel</button>
                    </form>
                    <form action="{{ route('doctors.patient.revenue-report.pdf') }}" method="GET" class="d-inline">
                        <input type="hidden" name="date_from" value="{{ $result['date_from'] }}">
                        <input type="hidden" name="date_to" value="{{ $result['date_to'] }}">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-file-text me-1"></i> Export PDF</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if(!empty($result['errors']))
                    <div class="alert alert-warning">
                        <strong>{{ count($result['errors']) }} bill(s) could not be included:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($result['errors'] as $err)
                                <li>IPD {{ $err['ipd_no'] ?? $err['ipd_id'] }} — {{ $err['message'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(empty($result['groups']))
                    <p class="text-muted mb-0">No referred-doctor discharged bills found for this date range.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0 align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Sl No</th>
                                <th>Bill Date / Patient</th>
                                <th>Bill No</th>
                                <th>Adm. No.</th>
                                <th class="text-end">Bed Ch.</th>
                                <th class="text-end">Diag Ch</th>
                                <th class="text-end">Other Ch</th>
                                <th class="text-end">Pack Amt</th>
                                <th class="text-end">Dr Visit</th>
                                <th class="text-end">Bill Amt</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end">N H Amt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result['groups'] as $group)
                                <tr class="table-light">
                                    <td colspan="12" class="fw-bold">{{ $group['doctor_label'] }}</td>
                                </tr>
                                @foreach($group['rows'] as $row)
                                <tr>
                                    <td>{{ $row['sl_no'] }}</td>
                                    <td>
                                        <div>{{ $row['bill_date'] }}</div>
                                        <div class="fw-semibold">{{ $row['patient_name'] }}</div>
                                    </td>
                                    <td>{{ $row['bill_no'] }}</td>
                                    <td>{{ $row['adm_no'] }}</td>
                                    <td class="text-end">{{ number_format($row['bed_charges'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['diagnosis_charges'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['other_charges'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['package_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['doctor_visit_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['bill_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['discount_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($row['net_hospital_amount'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="fw-bold">
                                    <td colspan="4" class="text-end">TOTAL :</td>
                                    <td class="text-end">{{ number_format($group['totals']['bed_charges'], 2) }}</td>
                                    <td class="text-end">{{ number_format($group['totals']['diagnosis_charges'], 2) }}</td>
                                    <td class="text-end">{{ number_format($group['totals']['other_charges'], 2) }}</td>
                                    <td class="text-end">{{ number_format($group['totals']['package_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($group['totals']['doctor_visit_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($group['totals']['bill_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($group['totals']['discount_amount'], 2) }}</td>
                                    <td class="text-end">{{ number_format($group['totals']['net_hospital_amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">GRAND TOTAL :</td>
                                <td class="text-end">{{ number_format($result['grand_total']['bed_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['diagnosis_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['other_charges'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['package_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['doctor_visit_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['bill_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['discount_amount'], 2) }}</td>
                                <td class="text-end">{{ number_format($result['grand_total']['net_hospital_amount'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <p class="text-muted small mt-2 mb-0">
                    N H Amt (Net Hospital Amount) = Bill Amt − (Dr Visit + Hospital/MOU Discount).
                    When a package applies, bed charge is excluded (same as final bill).
                </p>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
