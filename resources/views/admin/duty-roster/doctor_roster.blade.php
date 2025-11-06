@extends('layouts.adminLayout')

@section('content')
<!-- row start -->
<div class="row px-5 py-4">
    <div class="col-12 d-flex">

        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                    <div>
                        <h4 class="fw-bold mb-0">Doctor Duty Roster</h4>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="text-end d-flex">
                            <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                data-bs-toggle="modal" data-bs-target="#add_appointment">
                                <i class="ti ti-plus me-1"></i>Add Roster</a>
                        </div>
                        <!-- Add Doctor Roster Modal -->
                        <div class="modal fade" id="add_appointment" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('dutyroster.assignDoctor') }}">
    @csrf
    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
        <div class="row w-100 align-items-center">
            <div class="col-md-7">
                <h4>Assign Doctor Roster</h4>
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
                                    <input type="radio" name="shift_id" value="{{ $shift->id }}" class="shift-radio" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="stepimage">
                                        {{ $shift->shift_name }}<br>
                                        {{ \Carbon\Carbon::parse($shift->shift_start)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($shift->shift_end)->format('h:i A') }}
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
                        <option value="">Select Shift First</option>
                    </select>
                </div>

                {{-- DOCTOR --}}
                <div class="col-sm-12">
                    <label>Doctor <small class="req">*</small></label>
                    <div class="p-2 select2-full-width">
                        <select class="form-control select2" id="duty_roster_doctor" name="doctor_id" required>
                            <option value="">Select</option>
                            @foreach($doctorList as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->doctor_code ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- FLOOR --}}
                <div class="col-sm-6">
                    <label>Floor</label>
                    <select class="form-control" id="duty_roster_floor" name="floor_id">
                        <option value="">Select</option>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- DEPARTMENT --}}
                <div class="col-sm-6">
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

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
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
                    <p class="text-center">No doctor roster assignments found.</p>
                @else
                    <div class="table-responsive table-nowrap">
                        <table class="table border">
                            <thead class="thead-light">
                                <tr>
                                    <th>Doctor</th>
                                    <th>Floor</th>
                                    <th>Department</th>
                                    <th>Shift</th>
                                    <th>Shift Time</th>
                                    <th>Start Date - End Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rosterSummary as $roster)
                                    <tr>
                                        <td>{{ $roster['doctor_name'] }}</td>
                                        <td>{{ $roster['floor'] }}</td>
                                        <td>{{ $roster['department'] }}</td>
                                        <td>{{ $roster['shift'] }}</td>
                                        <td>{{ $roster['shift_time'] }}</td>
                                        <td>{{ $roster['period'] }}</td>
                                        <td class="text-center">
                                            <!-- Edit Button -->
                                            <a href="javascript:void(0);"
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill editRosterBtn"
                                                data-id="{{ $roster['id'] }}"
                                                data-code="{{ $roster['code'] }}"
                                                data-doctor="{{ $roster['doctor_id'] }}"
                                                data-floor="{{ $roster['floor_id'] ?? '' }}"
                                                data-department="{{ $roster['department_id'] ?? '' }}"
                                                data-shift="{{ trim($roster['shift']) }}"
                                                data-period="{{ $roster['period'] }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <!-- Delete Button -->
                                            <a href="javascript:void(0);"
                                               onclick="confirmDelete('{{ route('dutyroster.destroyDoctorRoster', ['code' => $roster['code'] ?? 0]) }}')"
                                               class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                <i class="ti ti-trash"></i>
                                            </a>
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

    <!-- Edit Doctor Roster Modal -->
    <div class="modal fade" id="editRosterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" id="editRosterForm" action="{{ route('dutyroster.updateDoctorRoster') }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <div class="row w-100 align-items-center">
                            <div class="col-md-7">
                                <h4>Edit Doctor Roster</h4>
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
                                                    <input type="radio" class="edit_shift" name="shift_id" value="{{ $shift->id }}">
                                                    <div class="stepimage">
                                                        {{ $shift->shift_name }}<br>
                                                        {{ Carbon\Carbon::parse($shift->shift_start)->format('h:i A') }} - {{ Carbon\Carbon::parse($shift->shift_end)->format('h:i A') }}

                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- DUTY PERIOD --}}
                                <div class="col-sm-12">
                                    <label>Shift Date <small class="req">*</small></label>
                                    <select class="form-control" id="edit_duty_roster_list_id" name="duty_roster_list_id" required>
                                        <option value="">Select</option>
                                        @foreach($dutyRosterLists as $roster)
                                            <option value="{{ $roster->id }}">
                                                {{ date('d/m/Y', strtotime($roster->duty_roster_start_date)) }} - {{ date('d/m/Y', strtotime($roster->duty_roster_end_date)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- DOCTOR --}}
                                <div class="col-sm-12">
                                    <label>Doctor <small class="req">*</small></label>
                                    <div class="p-2 select2-full-width">
                                        <select class="form-control select2" id="edit_doctor_id" name="doctor_id" required>
                                            <option value="">Select</option>
                                            @foreach($doctorList as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->doctor_code ?? '' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- FLOOR --}}
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Floor</label>
                                        <select class="form-control" id="edit_floor_id" name="floor_id">
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
                                        <select class="form-control" id="edit_department_id" name="department_id">
                                            <option value="">Select</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- HIDDEN ID FIELD --}}
                                <input type="hidden" id="edit_roster_id" name="id">
                                <input type="hidden" id="edit_roster_code" name="code">

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
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

<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.editRosterBtn');
    if (!btn) return;

    const rosterId = document.getElementById('edit_roster_id');
    if (rosterId) rosterId.value = btn.dataset.id || '';

    const rosterCode = document.getElementById('edit_roster_code');
    if (rosterCode) rosterCode.value = btn.dataset.code || '';

    const doctorSelect = document.getElementById('edit_doctor_id');
    if (doctorSelect) doctorSelect.value = btn.dataset.doctor || '';

    const floorSelect = document.getElementById('edit_floor_id');
    if (floorSelect) floorSelect.value = btn.dataset.floor || '';

    const deptSelect = document.getElementById('edit_department_id');
    if (deptSelect) deptSelect.value = btn.dataset.department || '';

    const periodSelect = document.getElementById('edit_duty_roster_list_id');
    if (periodSelect) {
        Array.from(periodSelect.options).forEach(opt => {
            opt.selected = opt.textContent.trim() === btn.dataset.period?.trim();
        });
    }

    const selectedShift = btn.dataset.shift?.trim();
    document.querySelectorAll('.edit_shift').forEach(radio => {
        const labelText = radio.closest('label')?.textContent.trim() || '';
        radio.checked = labelText.includes(selectedShift);
    });

    if (window.jQuery && $('.select2').length) $('.select2').trigger('change');

    const modalEl = document.getElementById('editRosterModal');
    if (modalEl) new bootstrap.Modal(modalEl).show();
});
</script>

<script>
function confirmDelete(url) {
    Swal.fire({
        title: "Are you sure?",
        text: "This doctor roster will be marked as deleted (soft delete).",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
{{-- 🔹 AJAX Script --}}
<script>
$(document).ready(function() {
    $('.shift-radio').on('change', function() {
        let shiftId = $(this).val();
        let $select = $('#duty_roster_list_id');

        $select.html('<option>Loading...</option>');

        $.ajax({
            url: "{{ route('dutyroster.getDatesByShift') }}",
            type: "GET",
            data: { shift_id: shiftId },
            success: function(response) {
                $select.empty();
                if (response.length > 0) {
                    $select.append('<option value="">Select</option>');
                    $.each(response, function(index, roster) {
                        $select.append(
                            `<option value="${roster.id}">
                                ${roster.start_date} - ${roster.end_date}
                             </option>`
                        );
                    });
                } else {
                    $select.append('<option value="">No dates available</option>');
                }
            },
            error: function() {
                $select.html('<option>Error fetching data</option>');
            }
        });
    });

    // Trigger change for the first checked shift on load
    $('.shift-radio:checked').trigger('change');
});
</script>
@endsection
