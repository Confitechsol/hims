{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')

    <style>
        .input-group .input-group-addon {
            border-radius: 0;
            border: 1px solid #d2d6de;
            background-color: #d3a2e03d;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
        }

        .input-group {
            position: relative;
            display: table;
            border-collapse: separate;
        }

        .form-select {
            padding: 0.5rem 0.75rem !important;
        }
    </style>

    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Charge Type List</h5>
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
                                        <div class="text-end d-flex">
                                            <a href="javascript:void(0);"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md" data-bs-toggle="modal"
                                                data-bs-target="#add_type"><i class="ti ti-plus me-1"></i>Add Charge
                                                Type</a>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="add_type" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Charge
                                                            Type</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="POST">
                                                            @csrf
                                                            <div class="row gy-3">
                                                                <div class="col-md-12 border-bottom pb-3">
                                                                    <label for="" class="form-label">Charge Type <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="charge_type" id="charge_type"
                                                                        class="form-control" required>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Module <span
                                                                            class="text-danger">*</span></label>
                                                                    @foreach ($chargestypemodul  as $chargestypemoduls )
                                                                         <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="appointment" id="appointment" class="form-check-input mt-0" value="{{ $chargestypemoduls->id }}">
                                                                        <label for="{{ $chargestypemoduls->id }}" class="form-check-label mb-0">{{ $chargestypemoduls->module_shortcode }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                   
                                                                    {{-- <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="opd" id="opd" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">OPD</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="ipd" id="ipd" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">IPD</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="pathology" id="pathology" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">Pathology</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="radiology" id="radiology" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">Radiology</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="blood_bank" id="blood_bank" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">Blood Bank</label>
                                                                    </div>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" name="ambulance" id="ambulance" class="form-check-input mt-0">
                                                                        <label for="" class="form-check-label mb-0">Ambulance</label>
                                                                    </div> --}}
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

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                <th></th>
                                                @foreach ( $chargestype as $chargestypes )

                                                <th>
                                                  {{$chargestypes ->charge_type}}
                                                

                                                </th>
                                                    
                                                @endforeach
                                                  
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ( $chargestype as $chargestypes)
                                                
                                           
                                                <tr>

                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> {{$chargestypes->charge_type}} </h6>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input"  {{ $chargestypes->charge_type=="Appointment" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="OPD" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="IPD" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Pathology" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Radiology" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Blood Bank" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Ambulance" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                     
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Procedures" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Investigations" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Supplier" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Operations" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Others" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="" id="" class="form-check-input" {{ $chargestypes->charge_type=="Bed Charges" && $chargestypes->is_active == 'yes' ? 'checked' : '' }}>
                                                    </td>  
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                    </td>
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




@endsection