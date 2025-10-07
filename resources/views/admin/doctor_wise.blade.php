{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">

        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Doctor Wise Appointment </h5>
                </div>

                <div class="card-body">
                    <form id="slot_form" method="POST" action="{{ route('slots.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Doctor & Shift --}}
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="doctor" class="form-label">Doctor <span class="text-danger">*</span></label>
                                <select class="form-select" id="doctor" data-placeholder="Select">
                                    <option value=""></option>
                                    <option value="1">Amitabh Kulkarni</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="date" class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control standard_charge" name="date" id="date" value="">

                            </div>

                            <div class="col-md-4">
                                <button type="button" onclick="search()" class="btn btn-primary btn-sm mt-4">Search</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>







    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for the doctor dropdown
            $('#doctor').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });
        });
    </script>

@endsection