@extends('layouts.adminLayout')

@section('content')

<!-- row start -->
<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h5>Staff Duty Roster</h5>
    </div>
    <div class="card-body">
        @foreach($groupedStaffs as $staff_id => $records)
            @php $staff = $staffs[$staff_id] ?? null; @endphp
            @if($staff)
                <h6 class="mt-4 text-primary">
                    {{ $staff->name }} 
                    <small class="text-muted">({{ $staff->designation }})</small>
                </h6>

                <table class="table table-bordered table-sm mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Shift</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Department</th>
                            <th>Floor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $assign)
                        <tr>
                            <td>{{ $assign->dutyRosterList->dutyRosterShift->shift_name ?? 'N/A' }}</td>
                            <td>{{ $assign->dutyRosterList->duty_roster_start_date ?? '-' }}</td>
                            <td>{{ $assign->dutyRosterList->duty_roster_end_date ?? '-' }}</td>
                            <td>{{ $assign->department->name ?? 'N/A' }}</td>
                            <td>{{ $assign->floor->name ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    </div>
</div>
    </div>
</div>
@endsection
