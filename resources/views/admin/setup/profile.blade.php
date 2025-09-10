{{-- resources/views/settings.blade.php --}}
@extends('layouts.adminLayout')
@section('content')
    <div class="page-wrapper">
        <div class="row justify-content-center">


            {{-- Settings Form --}}
            <div class="col-md-11">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> General Settings</h5>
                    </div>

                    <div class="card-body">
                        <form id="settings_form" method="POST" action="#">
                            @csrf

                            {{-- Hospital Name & Code --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Hospital Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="My Hospital">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Hospital Code</label>
                                    <input type="text" class="form-control" value="HSP001">
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="123 Main Street, City">
                            </div>

                            {{-- Phone & Email --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="+91 9876543210">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" value="info@hospital.com">
                                </div>
                            </div>

                            <hr>

                            {{-- Logos --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Hospital Logo</label><br>
                                    <img src="{{ asset('uploads/hospital_content/logo/images.png') }}"
                                        class="img-thumbnail me-2" style="height:40px;">
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit me-1"></i> Change Logo
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Small Logo</label><br>
                                    <img src="{{ asset('uploads/hospital_content/logo/images.png') }}"
                                        class="img-thumbnail me-2" style="height:40px;">
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit me-1"></i> Change Small Logo
                                    </button>
                                </div>
                            </div>

                            {{-- Language --}}
                            <h6 class="mt-4 mb-2 fw-bold text-uppercase text-primary">Language</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Select Language</label>
                                    <select class="form-select">
                                        <option selected>English</option>
                                        <option>Hindi</option>
                                        <option>Bengali</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Date & Time --}}
                            <h6 class="mt-4 mb-2 fw-bold text-uppercase text-primary">Date & Time</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Date Format</label>
                                    <select class="form-select">
                                        <option selected>d-m-Y</option>
                                        <option>m-d-Y</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Time Zone</label>
                                    <select class="form-select">
                                        <option selected>Asia/Kolkata</option>
                                        <option>UTC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Time Format</label>
                                <select class="form-select">
                                    <option selected>24-hour</option>
                                    <option>12-hour</option>
                                </select>
                            </div>
                            <hr>
                            {{-- Mobile App --}}
                            <div class="d-flex justify-content-between text-align-center my-3">
                                <h6 class="fw-bold text-uppercase text-primary">Mobile App</h6>
                                <button class="btn btn-sm text-white" style="background: #750096"><i
                                        class="fa fa-android me-1"></i> Register Your
                                    Android App</button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">API URL</label>
                                <input type="text" class="form-control" value="https://api.hospital.com/v1">
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Primary Color</label>
                                    <input type="color" class="form-control form-control-color" value="#3498db">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Secondary Color</label>
                                    <input type="color" class="form-control form-control-color" value="#2ecc71">
                                </div>
                                {{-- Mobile Logo --}}
                                <div class="col-md-4">
                                    <label class="form-label">Mobile App Logo</label><br>
                                    <img src="{{ asset('uploads/hospital_content/logo/images.png') }}"
                                        class="img-thumbnail me-2" style="height:40px;">
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit me-1"></i> Change App Logo
                                    </button>
                                </div>
                            </div>


                            <hr>
                            {{-- Miscellaneous --}}
                            <h6 class="mt-4 mb-2 fw-bold text-uppercase text-primary">Miscellaneous</h6>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Doctor Restriction Mode</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="doctor_restriction" id="doctor_disabled"
                                                class="form-check-input" checked>
                                            <label for="doctor_disabled" class="form-check-label">Disabled</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="doctor_restriction" id="doctor_enabled"
                                                class="form-check-input">
                                            <label for="doctor_enabled" class="form-check-label">Enabled</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Superadmin Visibility</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="superadmin_restriction" id="superadmin_disabled"
                                                class="form-check-input">
                                            <label for="superadmin_disabled" class="form-check-label">Disabled</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="superadmin_restriction" id="superadmin_enabled"
                                                class="form-check-input" checked>
                                            <label for="superadmin_enabled" class="form-check-label">Enabled</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Patient Panel</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="superadmin_restriction" id="superadmin_disabled"
                                                class="form-check-input">
                                            <label for="superadmin_disabled" class="form-check-label">Disabled</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="superadmin_restriction" id="superadmin_enabled"
                                                class="form-check-input" checked>
                                            <label for="superadmin_enabled" class="form-check-label">Enabled</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Scan Type</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="scan_type" id="scan_barcode"
                                                class="form-check-input">
                                            <label for="scan_barcode" class="form-check-label">Barcode</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="scan_type" id="scan_qrcode"
                                                class="form-check-input" checked>
                                            <label for="scan_qrcode" class="form-check-label">QR Code</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Theme --}}
                            <h6 class="mt-4 mb-2 fw-bold text-uppercase text-primary">Theme</h6>
                            <div class="row">
                                <div class="col-sm-3 text-center">
                                    <input type="radio" name="theme" id="theme_default" checked>
                                    <label for="theme_default">
                                        <img src="{{ asset('backend/images/default.jpg') }}"
                                            class="img-fluid border rounded">
                                    </label>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <input type="radio" name="theme" id="theme_red">
                                    <label for="theme_red">
                                        <img src="{{ asset('backend/images/red.jpg') }}" class="img-fluid border rounded">
                                    </label>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <input type="radio" name="theme" id="theme_blue">
                                    <label for="theme_blue">
                                        <img src="{{ asset('backend/images/blue.jpg') }}"
                                            class="img-fluid border rounded">
                                    </label>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <input type="radio" name="theme" id="theme_gray">
                                    <label for="theme_gray">
                                        <img src="{{ asset('backend/images/gray.jpg') }}"
                                            class="img-fluid border rounded">
                                    </label>
                                </div>
                            </div>

                            {{-- Save --}}
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
    </div>
@endsection
