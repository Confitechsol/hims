{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">

        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Slots </h5>
                </div>

                <div class="card-body">
                    <form id="slot_form" method="POST"
      action="{{ route('slots.store') }}"
      enctype="multipart/form-data">
    @csrf

    {{-- Doctor & Shift --}}
    <div class="row mb-3 align-items-center">
        <div class="col-md-4">
            <label for="doctor" class="form-label fw-bold">Doctor <span class="text-danger">*</span></label>
            <select id="doctor" name="doctor" class="select2 form-select" style="width: 100%">
                <option value="">Select</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->doctor_id }})</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="shift" class="form-label fw-bold">Shift <span class="text-danger">*</span></label>
            <select id="shift" name="shift" class="select2 form-select" style="width: 100%">
                <option value="">Select</option>
            </select>
        </div>

        <div class="col-md-4">
            <button type="button" onclick="search()" class="btn btn-primary btn-sm mt-4">Search</button>
        </div>
    </div>

    <hr>

    {{-- Consultation & Charges --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="consult_time" class="form-label fw-bold">
                Consultation Duration Minutes <span class="text-danger">*</span>
            </label>
            <input type="number" name="consult_time" value="" placeholder=""
                   class="form-control" id="consult_time" autocomplete="off" required>
        </div>

        <div class="col-md-3">
            <label for="charge_category" class="form-label fw-bold">Charge Category</label>
            <select name="charge_category" id="charge_category" style="width: 100%"
                    class="form-select charge_category select2">
                <option value="">Select</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label for="charge_id" class="form-label fw-bold">Charge <span class="text-danger">*</span></label>
            <select name="charge_id" id="charge_id" style="width: 100%"
                    class="form-select charge select2" required>
                <option value="">Select</option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="amount" class="form-label fw-bold">Amount (INR)</label>
            <input type="text" class="form-control standard_charge" name="amount" id="amount" value="">
        </div>
    </div>

    <hr>

    <div class="row mb-3" id="slotsContainer"></div>

    <div class="row mb-3">
        <div class="col-md-12">
            <button type="submit" class="btn btn-success">Save Slots</button>
        </div>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>




  

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#doctor').change(function() {
        let doctorId = $(this).val();

        if(!doctorId) {
            $('#shift').html('<option value="">Select</option>');
            return;
        }

        $.ajax({
            url: '{{ route("doctor.shifts", ":doctorId") }}'.replace(':doctorId', doctorId),
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select</option>';
                response.shifts.forEach(function(shift) {
                    options += `<option value="${shift.id}">${shift.name}</option>`;
                });
                $('#shift').html(options);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Could not fetch shifts!');
            }
        });
        });
            $('.js-example-basic-single').select2();
        });
    </script>
<script>
    $(document).ready(function () {
        // When category changes, fetch charges
        $('#charge_category').on('change', function () {
            let categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    url: "{{ url('slots/get-charges') }}/" + categoryId,
                    type: "GET",
                    success: function (data) {
                        $('#charge_id').empty().append('<option value="">Select</option>');
                        $.each(data, function (key, charge) {
                            $('#charge_id').append(
                                '<option value="' + charge.id + '" data-amount="' + charge.standard_charge + '">' + charge.name + '</option>'
                            );
                        });
                    }
                });
            } else {
                $('#charge_id').empty().append('<option value="">Select</option>');
                $('#amount').val('');
            }
        });

        // When charge is selected, set amount
        $('#charge_id').on('change', function () {
            let amount = $(this).find(':selected').data('amount');
            $('#amount').val(amount || '');
        });
    });
</script>

<script>
    function searOle() {
        alert('hi');
        let doctorId = $('#doctor').val();
        let shiftId = $('#shift').val();

        if(!doctorId || !shiftId) {
            alert('Please select both doctor and shift');
            return;
        }

        $.ajax({
            url: '{{ route("slots.search") }}',
            type: 'GET',
            data: { doctor: doctorId, shift: shiftId },
            success: function(response) {
                let container = $('#slotsContainer');
                container.empty();
                console.log(response);
                console.log(response.slots);
                console.log(response.slots.length);
                if(response.slots && response.slots.length > 0) {
                    alert('hii slots');
                    // Populate table of existing slots
                    let table = '<table class="table table-bordered"><thead><tr><th>Day</th><th>Time From</th><th>Time To</th></tr></thead><tbody>';
                    response.slots.forEach(function(slot){
                        table += `<tr>
                            <td>${slot.day}</td>
                            <td>${slot.start_time}</td>
                            <td>${slot.start_time}</td>
                        </tr>`;
                    });
                    table += '</tbody></table>';
                    container.html(table);
                } else {
                    // No slots exist, generate day tabs with pre-filled shift time
                    alert('hii no slots');
                    let days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                    let tabsNav = '<ul class="nav nav-tabs" role="tablist">';
                    let tabsContent = '<div class="tab-content mt-3">';

                    days.forEach((day, index) => {
                        let activeClass = index === 0 ? 'active' : '';
                        tabsNav += `<li class="nav-item" role="presentation">
                            <button class="nav-link ${activeClass}" id="${day}-tab" data-bs-toggle="tab" data-bs-target="#${day}" type="button" role="tab">${day}</button>
                        </li>`;

                        // Pre-fill time_from and time_to from shift
                        let timeFrom = response.shift ? response.shift.start_time : '';
                        let timeTo = response.shift ? response.shift.end_time : '';

                        tabsContent += `<div class="tab-pane fade ${activeClass} show" id="${day}" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Time From</label>
                                    <input type="time" class="form-control" name="slots[${day}][time_from]" value="${timeFrom}">
                                </div>
                                <div class="col-md-6">
                                    <label>Time To</label>
                                    <input type="time" class="form-control" name="slots[${day}][time_to]" value="${timeTo}">
                                </div>
                            </div>
                        </div>`;
                    });

                    tabsNav += '</ul>';
                    tabsContent += '</div>';

                    container.html(tabsNav + tabsContent);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Could not fetch slots!');
            }
        });
    }
function search() {
    let doctorId = $('#doctor').val();
    let shiftId = $('#shift').val();

    if(!doctorId || !shiftId) {
        alert('Please select both doctor and shift');
        return;
    }

    $.ajax({
        url: '{{ route("slots.search") }}',
        type: 'GET',
        data: { doctor: doctorId, shift: shiftId },
        success: function(response) {
            let container = $('#slotsContainer');
            container.empty();
            console.log(response);

            let days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            let tabsNav = '<ul class="nav nav-tabs" role="tablist">';
            let tabsContent = '<div class="tab-content mt-3">';

            days.forEach((day, index) => {
                let activeClass = index === 0 ? 'active' : '';
                tabsNav += `<li class="nav-item" role="presentation">
                                <button class="nav-link ${activeClass}" id="${day}-tab" data-bs-toggle="tab" data-bs-target="#${day}" type="button" role="tab">${day}</button>
                            </li>`;

                // Check if slot exists for this day
                let slot = response.slots.find(s => s.day === day);
                let timeFrom = slot ? slot.start_time : (response.shift ? response.shift.start_time : '');
                let timeTo = slot ? slot.end_time : (response.shift ? response.shift.end_time : '');

                tabsContent += `<div class="tab-pane fade ${activeClass} show" id="${day}" role="tabpanel">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Time From</label>
                                            <input type="time" class="form-control" name="slots[${day}][time_from]" value="${timeFrom}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Time To</label>
                                            <input type="time" class="form-control" name="slots[${day}][time_to]" value="${timeTo}">
                                        </div>
                                    </div>
                                </div>`;
            });

            tabsNav += '</ul>';
            tabsContent += '</div>';

            container.html(tabsNav + tabsContent);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Could not fetch slots!');
        }
    });
}

    

    </script>
@endsection