@extends('layouts.adminLayout')

@section('content')
<!-- row start -->
<div class="row px-5 py-4">
    <div class="col-12 d-flex">

        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                    <div>
                        <h4 class="fw-bold mb-0">Staff Duty Roster</h4>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="text-end d-flex">
                            <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                data-bs-toggle="modal" data-bs-target="#add_appointment">
                                <i class="ti ti-plus me-1"></i>Add Roster</a>
                        </div>
                        <!-- First Modal -->
                        <div class="modal fade" id="add_appointment" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('dutyroster.store') }}">
                                        @csrf
                                        <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                            <div class="row w-100 align-items-center">
                                                <div class="col-md-7">
                                                    <h4>Assign Roster</h4>
                                                </div>
                                                <div class="col-md-5 text-end">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <div class="row align-items-center gy-3">
                                                    {{-- SHIFT SELECTION --}}
                                                    <div class="col-sm-12">
                                                        <label>Shift</label>
                                                        <ul class="stepradiolist row gy-0">
                                                            @foreach($shifts as $shift)
                                                                <li class="col-sm-4">
                                                                    <label>
                                                                        <input type="radio" name="shift_id" value="{{ $shift->id }}" {{ $loop->first ? 'checked' : '' }}>
                                                                        <div class="stepimage">
                                                                            {{ $shift->shift_name }}<br>
                                                                            {{ date('h:i A', strtotime($shift->shift_start_time)) }} - {{ date('h:i A', strtotime($shift->shift_end_time)) }}
                                                                        </div>
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                    {{-- DUTY PERIOD --}}
                                                    <div class="col-sm-12">
                                                        <label>Shift Date <small class="req">*</small></label>
                                                        <select class="form-control" id="duty_roster_list_id" name="duty_roster_list_id" required>
                                                            <option value="">Select</option>
                                                            @foreach($dutyRosterLists as $roster)
                                                                <option value="{{ $roster->id }}">
                                                                    {{ date('d/m/Y', strtotime($roster->duty_roster_start_date)) }} - {{ date('d/m/Y', strtotime($roster->duty_roster_end_date)) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    {{-- STAFF --}}
                                                    <div class="col-sm-12">
                                                        <label>Staff <small class="req">*</small></label>
                                                        <div class="p-2 select2-full-width">
                                                            <select class="form-control select2" id="duty_roster_staff" name="staff_id" required>
                                                                <option value="">Select</option>
                                                                @foreach($staffList as $staff)
                                                                    <option value="{{ $staff->id }}">
                                                                        {{ $staff->name }} ({{ $staff->employee_id }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- FLOOR --}}
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Floor</label>
                                                            <select class="form-control" id="duty_roster_floor" name="floor_id">
                                                                <option value="">Select</option>
                                                                @foreach($floors as $floor)
                                                                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- DEPARTMENT --}}
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Department</label>
                                                            <select class="form-control" id="duty_roster_department" name="department_id">
                                                                <option value="">Select</option>
                                                                @foreach($departments as $dept)
                                                                    <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <div class="pull-right">
                                                <button type="submit"  class="btn btn-primary">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="card-body">
                @if($rosterSummary->isEmpty())
                    <p class="text-center">No roster assignments found.</p>
                @else
                    <div class="table-responsive table-nowrap">
                        <table class="table border">
                            <thead class="thead-light">
                                <tr>
                                    <th>Staff</th>
                                    <th>Floor</th>
                                    <th>Department</th>
                                    <th>Roster</th>
                                    <th>Start Date - End Date</th>
                                    <th>Shift Start - Shift End</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rosterSummary as $staff)
                                    <tr>
                                        <td>{{ $staff['staff_name'] }}</td>
                                        <td>{{ $staff['floor'] }}</td>
                                        <td>{{ $staff['department'] }}</td>
                                        <td>{{ $staff['shift'] }}</td>
                                        <td>{{ $staff['shift_time'] }}</td>
                                        <td>{{ $staff['period'] }}</td>
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
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "Select an option"
        });
    });
</script>
@endsection
