{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        {{-- Settings Form --}}
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Prefix Settings</h5>
                </div>

                <div class="card-body">
                    <form id="settings_form" method="POST"
                        action="{{ isset($branch) && $branch->exists ? route('profile.update') : route('profile.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Hospital Name & Code --}}
                        <div class="row mb-3 gy-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">IPD No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ipd_number"
                                    value="{{ $branch->name ?? 'Enter IPD No' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">OPD No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="opd_number"
                                    value="{{ $branch->name ?? 'Enter OPD No' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">IPD Prescription <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ipd_pre"
                                    value="{{ $branch->name ?? 'Enter IPD Prescription' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">OPD Prescription <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="OPD_pre"
                                    value="{{ $branch->name ?? 'Enter OPD Prescription' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Appointment <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="appointment"
                                    value="{{ $branch->name ?? 'Enter Appointment' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pharmacy Bill <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pharm_bill"
                                    value="{{ $branch->name ?? 'Enter Pharmacy Bill' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Operation Reference No<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="operation_ref_no"
                                    value="{{ $branch->name ?? 'Enter Operation Reference No' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Blood Bank Bill<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="blood_bank_no"
                                    value="{{ $branch->name ?? 'Enter Blood Bank Bill' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ambulance Call Bill<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ambulance_call_bill"
                                    value="{{ $branch->name ?? 'Enter Ambulance Call Bill' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Radiology Bill<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="radiology_bill"
                                    value="{{ $branch->name ?? 'Enter Radiology Bill' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pathology Bill<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pathology_bill"
                                    value="{{ $branch->name ?? 'Enter Pathology Bill' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">OPD Checkup Id<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="opd_checkup_id"
                                    value="{{ $branch->name ?? 'Enter OPD Checkup Id' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pharmacy Purchase No<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pharmacy_purchase_no"
                                    value="{{ $branch->name ?? 'Enter Pharmacy Purchase No' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Transaction ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="transaction_id"
                                    value="{{ $branch->name ?? 'Enter Transaction ID' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Birth Record Reference No<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="birth_rec_ref_no"
                                    value="{{ $branch->name ?? 'Enter Birth Record Reference No' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Death Record Reference No<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="death_rec_ref_no"
                                    value="{{ $branch->name ?? 'Enter Death Record Reference No' }}">
                            </div>
                        </div>
                        <hr>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-save me-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
