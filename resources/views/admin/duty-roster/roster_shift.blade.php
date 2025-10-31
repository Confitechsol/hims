@extends('layouts.adminLayout')

@section('content')
<div class="row px-5 py-4">
    <div class="col-12 d-flex">

        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                    <div>
                        <h4 class="fw-bold mb-0">Duty Roster List Details</h4>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                            data-bs-toggle="modal" data-bs-target="#add_shift">
                            <i class="ti ti-plus me-1"></i> Add Shift
                        </a>

                        <!-- Add Shift Modal -->
                        <div class="modal fade" id="add_shift" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('dutyroster.addShift') }}">
                                        @csrf
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Add Duty Roster Shift</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="shift_name" class="form-label">Shift Name</label>
                                                <input type="text" name="shift_name" id="shift_name" class="form-control" required>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="shift_start" class="form-label">Shift Start</label>
                                                    <input type="time" name="shift_start" id="shift_start" class="form-control" required>
                                                </div>
                                                <div class="col">
                                                    <label for="shift_end" class="form-label">Shift End</label>
                                                    <input type="time" name="shift_end" id="shift_end" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <label for="shift_hour" class="form-label">Shift Hour</label>
                                                <input type="number" name="shift_hour" id="shift_hour" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save Shift</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Add Shift Modal -->

                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($rosters->isEmpty())
                    <p class="text-center">No roster details found.</p>
                @else
                    <div class="table-responsive table-nowrap">
                        <table class="table border">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>Shift Name</th>
                                    <th>Shift Start</th>
                                    <th>Shift End</th>
                                    <th>Shift Hours</th>
                                    
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach($rosters as $roster)
                                    <tr>
                                        <td>{{ $roster->dutyRosterShift->shift_name }}</td>
                                        <td>{{ date('h:i A', strtotime($roster->dutyRosterShift->shift_start)) }}</td>
                                        <td>{{ date('h:i A', strtotime($roster->dutyRosterShift->shift_end)) }}</td>
                                        <td>{{ $roster->dutyRosterShift->shift_hour }}</td>
                                        <td>{{ \Carbon\Carbon::parse($roster->duty_roster_start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($roster->duty_roster_end_date)->format('d/m/Y') }}</td>
                                        <td>{{ $roster->duty_roster_total_day }}</td>
                                        <td>
                                            <a href="javascript:void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                onclick=""
                                                                >
                                                                <i class="ti ti-pencil"></i>
                                                            </a>

                                                            <a href="javascript:void(0);" 
                                                                onclick=""
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i>
                                                            </a>
                                            <!-- <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewShift{{ $roster->id }}">
                                                View Shift
                                            </button> -->

                                            <!-- View Shift Modal -->
                                            <div class="modal fade" id="viewShift{{ $roster->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info text-white">
                                                            <h5 class="modal-title">Shift Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <p><strong>Name:</strong> {{ $roster->dutyRosterShift->shift_name }}</p>
                                                            <p><strong>Start:</strong> {{ date('h:i A', strtotime($roster->dutyRosterShift->shift_start)) }}</p>
                                                            <p><strong>End:</strong> {{ date('h:i A', strtotime($roster->dutyRosterShift->shift_end)) }}</p>
                                                            <p><strong>Hours:</strong> {{ $roster->dutyRosterShift->shift_hour }}</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End View Shift Modal -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
