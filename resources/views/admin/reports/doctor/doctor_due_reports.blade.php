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
                <a class="btn btn-success" href="{{ route('doctors.patient.due-reports.export', ['from_date' => $fromDate, 'to_date' => $toDate, 'due_type' => $dueType, 'doctor_ids' => $doctorIds, 'patient_party_due' => $patientPartyDue]) }}">
                    <i class="fas fa-file-excel me-1"></i> Download Excel
                </a>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('doctors.patient.due-reports') }}" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label for="from_date" class="form-label">From date</label>
                        <input id="from_date" name="from_date" type="date" class="form-control" value="{{ $fromDate }}">
                    </div>
                    <div class="col-md-2">
                        <label for="to_date" class="form-label">To date</label>
                        <input id="to_date" name="to_date" type="date" class="form-control" value="{{ $toDate }}">
                    </div>
                    <div class="col-md-2">
                        <label for="due_type" class="form-label">Due type</label>
                        <select id="due_type" name="due_type" class="form-select">
                            <option value="">All due types</option>
                            <option value="Patient Due" @selected($dueType === 'Patient Due')>Patient Due</option>
                            <option value="Corporate Due" @selected($dueType === 'Corporate Due')>Corporate Due</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="patient_party_due" class="form-label">Patient Party Due</label>
                        <select id="patient_party_due" name="patient_party_due" class="form-select">
                            <option value="">All Patient Party Due names</option>
                            @foreach ($patientPartyDues as $partyDue)
                                <option value="{{ $partyDue }}" @selected($patientPartyDue === $partyDue)>
                                    {{ $partyDue }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="doctor_ids" class="form-label">Doctor name</label>
                        <select id="doctor_ids" name="doctor_ids[]" class="form-select" multiple>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" @selected(in_array($doctor->id, $doctorIds))>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
    <thead>
        <tr>
            <th>IPD NO</th>
            <th>Patient Name</th>
            <th>Doctor</th>
            <th>Patient Party Due</th>
            <th>Gross Total</th>
            <th>Due Amount</th>
            <th>Receipt Type</th>
            <th>Received Amounts</th>
            <th>Total Payment Received</th>
            <th>Admission Date</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($dueReports as $report)

            @php
                $grossTotal = (float) ($report->gross_total ?? 0);
                $totalPaymentReceived = (float) ($report->total_transaction ?? 0);

                // Due Amount = Gross Total - Total Payment Received
                $dueAmount = $grossTotal - $totalPaymentReceived;

                // Prevent negative due
                $dueAmount = max(0, $dueAmount);
            @endphp

            <tr>
                <td>{{ $report->ipd_no }}</td>

                <td>{{ $report->patient_name ?? '-' }}</td>

                <td>{{ $report->doctor_name ?? '-' }}</td>

                <th>{{ $report->patient_party_due ?? '_' }}</th>

                {{-- Gross Total --}}
                <td>
                    {{ number_format($grossTotal, 2) }}
                </td>

                {{-- Due Amount = Gross Total - Total Payment Received --}}
                <td>
                    {{ number_format($dueAmount, 2) }}
                </td>

                <td>
                    {{ $report->due_patient_party_receipt_type ?? '-' }}
                </td>

                {{-- Individual Received Amounts --}}
                <td>
                    @forelse ($report->transaction_amount ?? [] as $amount)
                        {{ number_format((float) $amount, 2) }}{{ !$loop->last ? ', ' : '' }}
                    @empty
                        -
                    @endforelse
                </td>

                {{-- Total Payment Received --}}
                <td>
                    {{ number_format($totalPaymentReceived, 2) }}
                </td>

                <td>
                    {{ $report->created_at }}
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="9" class="text-center">
                    No due reports found.
                </td>
            </tr>
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







    <script>
        $(function () {
            $('#patient_party_due').select2({
                width: '100%',
                placeholder: 'Select Patient Party Due',
                allowClear: true,
                minimumResultsForSearch: 0
            });

            $('#doctor_ids').select2({
                width: '100%',
                placeholder: 'Search doctor (type 3 characters)',
                minimumInputLength: 3
            });
        });
    </script>
@endsection