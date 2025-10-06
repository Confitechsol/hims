{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Doctor Shift</h5>
                </div>

                <div class="card-body">


                    {{-- Hospital Name & Code --}}
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                        <div class="input-icon-start position-relative me-2">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" class="form-control shadow-sm" placeholder="Search">

                                        </div>


                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Doctor Name</th>
                                                     @foreach($shifts as $shift)
                                                        <th>{{ $shift->name }}</th>
                                                    @endforeach
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($doctors as $doctor)
                                                    <tr>
                                                        <td>{{ $doctor->name }}</td>
                                                        @foreach($shifts as $shift)
                                                            <td>
                                                                <input type="checkbox" 
                                                                    class="doctor-shift-checkbox"
                                                                    data-doctor-id="{{ $doctor->id }}"
                                                                    data-shift-id="{{ $shift->id }}"
                                                                    @if($doctor->doctorGlobalShifts->contains('global_shift_id', $shift->id)) checked @endif>
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                                
                                            </tbody>
                                        </table>
                                    </div>

                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Delegated binding ensures it works even if table reloads
    $(document).on('change', '.doctor-shift-checkbox', function() {
        let doctorId = $(this).data('doctor-id');
        let shiftId = $(this).data('shift-id');
        let status = $(this).is(':checked') ? 1 : 0;

        console.log("Checkbox changed:", {
            doctor_id: doctorId,
            shift_id: shiftId,
            status: status
        });

        $.ajax({
            url: '{{ route("doctor-shift.toggle") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                doctor_id: doctorId,
                shift_id: shiftId,
                status: status
            },
            success: function(response) {
                console.log("AJAX success:", response);

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: response.message || 'Shift saved successfully!',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
            },
            error: function(xhr) {
                console.error("AJAX error:", xhr.status, xhr.responseText);

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Shift not saved!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        });
    });
});

</script>



@endsection