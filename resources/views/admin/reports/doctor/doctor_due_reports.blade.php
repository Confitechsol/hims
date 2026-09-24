{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <style>
        .module_billing {
            border-radius: 8px;
            color: #fff;
            background-color: #CB6CE7;
            width: 100%;
            padding: 15px;
            box-shadow: 5px 5px 8px 0px #bbbbbb;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>

    <div class="row justify-content-center">

        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Patient </h5>
                </div>

                  <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Doctor Due Reports</h4>
                <a class="btn btn-success" href="{{ route('doctors.patient.due-reports.export', ['from_date' => $fromDate, 'to_date' => $toDate, 'due_type' => $dueType]) }}">
                    <i class="fas fa-file-excel me-1"></i> Download Excel
                </a>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('doctors.patient.due-reports') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="from_date" class="form-label">From date</label>
                        <input id="from_date" name="from_date" type="date" class="form-control" value="{{ $fromDate }}">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date" class="form-label">To date</label>
                        <input id="to_date" name="to_date" type="date" class="form-control" value="{{ $toDate }}">
                    </div>
                    <div class="col-md-3">
                        <label for="due_type" class="form-label">Due type</label>
                        <select id="due_type" name="due_type" class="form-select">
                            <option value="">All due types</option>
                            <option value="Patient Due" @selected($dueType === 'Patient Due')>Patient Due</option>
                            <option value="Corporate Due" @selected($dueType === 'Corporate Due')>Corporate Due</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>IPD NO</th>
                                {{-- <th>Admission Date</th> --}}
                                <th>Patient Name</th>
                                <th>Doctor</th>
                                {{-- <th>Doctor ID</th> --}}
                                <th>Due Amount</th>
                                <th>Receipt Type</th>
                                <th>Transaction Amounts</th>
                                <th>Total Received Amount</th>
                                <th>Admission Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dueReports as $report)
                                <tr>
                                    <td>{{ $report->ipd_no }}</td>
                                    {{-- <td>{{ $report->admission_date ?? '-' }}</td> --}}
                                    <td>{{ $report->patient_name ?? '-' }}</td>
                                    <td>{{ $report->doctor_name ?? '-' }}</td>
                                    {{-- <td>{{ $report->due_patient_party_doctor_id ?? '-' }}</td> --}}
                                    <td>{{ $report->due_patient_party_amount }}</td>
                                    <td>{{ $report->due_patient_party_receipt_type ?? '-' }}</td>
                                    <td>
                                        @forelse ($report->transaction_amount as $amount)
                                            {{ $amount }}{{ !$loop->last ? ', ' : '' }}
                                        @empty
                                            -
                                        @endforelse
                                    </td>
                                    <td>{{ $report->total_transaction ?? 0 }}</td>
                                    <td>{{ $report->created_at }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-center">No due reports found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

                    </div>
                </div>
            </div>
        </div>
        
    </div>







    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>

    {{-- <script>
        $(document).ready(function () {
            // Initialize Select2 for the doctor dropdown
            $('#doctor').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });
        });
    </script> --}}

   
@endsection