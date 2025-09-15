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
    <form id="settings_form" 
          method="POST" 
          action="{{ isset($branch) && $branch->exists ? route('profile.update') : route('profile.store') }}" 
          enctype="multipart/form-data">
        @csrf


                            {{-- Hospital Name & Code --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Hospital Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="hospital_name" value="{{ $branch->name ?? 'My Hospital' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Hospital Code</label>
                                    <input type="text" class="form-control" name="hospital_code" value="{{ $branch->branch_id ?? 'HSP001' }}">
                                </div>
                            </div>
                            <br>
                            <div class="col-md-6">
                                <!-- Add Branch Button -->
                                <button type="button" class="btn btn-primary fw-bold" onclick="showBranchFields()">
                                    Add Branch
                                </button>
                            </div>

                            <!-- Hidden Fields (initially hidden) -->
                            <div id="branchFields" style="display: none; margin-top: 15px;">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Branch Code</label>
                                    <input type="text" class="form-control" placeholder="Enter Branch Code">
                                </div>
                                <div class="col-md-6" style="margin-top: 10px;">
                                    <label class="form-label fw-bold">Branch Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Branch Name">
                                </div>
                            </div>

                            <!-- Script to show/hide fields -->
                            <script>
                                function showBranchFields() {
                                    document.getElementById("branchFields").style.display = "block";
                                }
                            </script>
                            <br>

                            {{-- Address --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address" value="{{ $branch->address ?? '123 Main Street, City' }}">
                            </div>

                            {{-- Phone & Email --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="phone" value="{{ $branch->phone ?? '+91 9876543210' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ $branch->email ?? 'info@hospital.com' }}">
                                </div>
                            </div>

                            <hr>

                            {{-- Logos --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Hospital Logo</label><br>
                                    @if(isset($branch) && $branch->image)
                                        <img src="{{ asset('storage/' . $branch->image) }}" class="img-thumbnail me-2" style="height:40px;">
                                    @else
                                        <img src="{{ asset('uploads/hospital_content/logo/images.png') }}" class="img-thumbnail me-2" style="height:40px;">
                                    @endif
                                    <input type="file" class="form-control" name="hospital_logo" accept="image/*">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Small Logo</label><br>
                                    @if(isset($branch) && $branch->mini_logo)
                                        <img src="{{ asset('storage/' . $branch->mini_logo) }}" class="img-thumbnail me-2" style="height:40px;">
                                    @else
                                        <img src="{{ asset('uploads/hospital_content/logo/images.png') }}" class="img-thumbnail me-2" style="height:40px;">
                                    @endif
                                    <input type="file" class="form-control" name="small_logo" accept="image/*">
                                </div>
                            </div>

                            {{-- Language --}}
                            <h6 class="mt-4 mb-2 fw-bold text-uppercase text-primary">Language</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Select Language</label>
                                    <select class="form-select" name="language">
                                        <option value="English" {{ ($branch->languages ?? 'English') == 'English' ? 'selected' : '' }}>English</option>
                                        <option value="Hindi" {{ ($branch->languages ?? 'English') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                        <option value="Bengali" {{ ($branch->languages ?? 'English') == 'Bengali' ? 'selected' : '' }}>Bengali</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Date & Time --}}
                            <h6 class="mt-4 mb-2 fw-bold text-uppercase text-primary">Date & Time</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Date Format</label>
                                    <select class="form-select" name="date_format">
                                        <option value="DD-MM-YYYY" {{ ($branch->date_format ?? 'DD-MM-YYYY') == 'DD-MM-YYYY' ? 'selected' : '' }}>DD-MM-YYYY</option>
                                        <option value="MM-DD-YYYY" {{ ($branch->date_format ?? 'DD-MM-YYYY') == 'MM-DD-YYYY' ? 'selected' : '' }}>MM-DD-YYYY</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Time Zone</label>
                                    <select class="form-select" name="time_zone">
                                        <option value="Asia/Kolkata" {{ ($branch->timezone ?? 'Asia/Kolkata') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata</option>
                                        <option value="UTC" {{ ($branch->timezone ?? 'Asia/Kolkata') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Time Format</label>
                                <select class="form-select" name="time_format">
                                    <option value="24-hour" {{ ($branch->time_format ?? '24-hour') == '24-hour' ? 'selected' : '' }}>24-hour</option>
                                    <option value="12-hour" {{ ($branch->time_format ?? '24-hour') == '12-hour' ? 'selected' : '' }}>12-hour</option>
                                </select>
                            </div>
                            <hr>
                            
                            {{-- Currency --}}   
<h6 class="mt-4 mb-2 fw-bold text-uppercase text-primary">Currency</h6>                   
    <!-- Currency Dropdown -->
    <div class="col-md-6">
        <div class="form-group row">
    <!-- Currency Dropdown -->
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-4">Currency<small class="req"> *</small></label>
            <div class="col-sm-8">
                <select id="currency" name="currency" class="form-control" autocomplete="off">
                    <option value="">Select</option>
                    <option value="AED">AED</option>
                    <option value="AFN">AFN</option>
                    <option value="ALL">ALL</option>
                    <option value="AMD">AMD</option>
                    <option value="ANG">ANG</option>
                    <option value="AOA">AOA</option>
                    <option value="ARS">ARS</option>
                    <option value="AUD">AUD</option>
                    <option value="AWG">AWG</option>
                    <option value="AZN">AZN</option>
                    <option value="BAM">BAM</option>
                    <option value="BDT">BDT</option>
                    <option value="BGN">BGN</option>
                    <option value="BHD">BHD</option>
                    <option value="BIF">BIF</option>
                    <option value="BMD">BMD</option>
                    <option value="BND">BND</option>
                    <option value="BOB">BOB</option>
                    <option value="BOV">BOV</option>
                    <option value="BRL">BRL</option>
                    <option value="BSD">BSD</option>
                    <option value="BTN">BTN</option>
                    <option value="BWP">BWP</option>
                    <option value="BYN">BYN</option>
                    <option value="BYR">BYR</option>
                    <option value="BZD">BZD</option>
                    <option value="CAD">CAD</option>
                    <option value="CDF">CDF</option>
                    <option value="CHE">CHE</option>
                    <option value="CHF">CHF</option>
                    <option value="CHW">CHW</option>
                    <option value="CLF">CLF</option>
                    <option value="CLP">CLP</option>
                    <option value="CNY">CNY</option>
                    <option value="COP">COP</option>
                    <option value="COU">COU</option>
                    <option value="CRC">CRC</option>
                    <option value="CUC">CUC</option>
                    <option value="CUP">CUP</option>
                    <option value="CVE">CVE</option>
                    <option value="CZK">CZK</option>
                    <option value="DJF">DJF</option>
                    <option value="DKK">DKK</option>
                    <option value="DOP">DOP</option>
                    <option value="DZD">DZD</option>
                    <option value="EGP">EGP</option>
                    <option value="ERN">ERN</option>
                    <option value="ETB">ETB</option>
                    <option value="EUR">EUR</option>
                    <option value="FJD">FJD</option>
                    <option value="FKP">FKP</option>
                    <option value="GBP">GBP</option>
                    <option value="GEL">GEL</option>
                    <option value="GHS">GHS</option>
                    <option value="GIP">GIP</option>
                    <option value="GMD">GMD</option>
                    <option value="GNF">GNF</option>
                    <option value="GTQ">GTQ</option>
                    <option value="GYD">GYD</option>
                    <option value="HKD">HKD</option>
                    <option value="HNL">HNL</option>
                    <option value="HRK">HRK</option>
                    <option value="HTG">HTG</option>
                    <option value="HUF">HUF</option>
                    <option value="IDR">IDR</option>
                    <option value="ILS">ILS</option>
                    <option value="INR" selected>INR</option>
                    <option value="IQD">IQD</option>
                    <option value="IRR">IRR</option>
                    <option value="ISK">ISK</option>
                    <option value="JMD">JMD</option>
                    <option value="JOD">JOD</option>
                    <option value="JPY">JPY</option>
                    <option value="KES">KES</option>
                    <option value="KGS">KGS</option>
                    <option value="KHR">KHR</option>
                    <option value="KMF">KMF</option>
                    <option value="KPW">KPW</option>
                    <option value="KRW">KRW</option>
                    <option value="KWD">KWD</option>
                    <option value="KYD">KYD</option>
                    <option value="KZT">KZT</option>
                    <option value="LAK">LAK</option>
                    <option value="LBP">LBP</option>
                    <option value="LKR">LKR</option>
                    <option value="LRD">LRD</option>
                    <option value="LSL">LSL</option>
                    <option value="LYD">LYD</option>
                    <option value="MAD">MAD</option>
                    <option value="MDL">MDL</option>
                    <option value="MGA">MGA</option>
                    <option value="MKD">MKD</option>
                    <option value="MMK">MMK</option>
                    <option value="MNT">MNT</option>
                    <option value="MOP">MOP</option>
                    <option value="MRO">MRO</option>
                    <option value="MUR">MUR</option>
                    <option value="MVR">MVR</option>
                    <option value="MWK">MWK</option>
                    <option value="MXN">MXN</option>
                    <option value="MXV">MXV</option>
                    <option value="MYR">MYR</option>
                    <option value="MZN">MZN</option>
                    <option value="NAD">NAD</option>
                    <option value="NGN">NGN</option>
                    <option value="NIO">NIO</option>
                    <option value="NOK">NOK</option>
                    <option value="NPR">NPR</option>
                    <option value="NZD">NZD</option>
                    <option value="OMR">OMR</option>
                    <option value="PAB">PAB</option>
                    <option value="PEN">PEN</option>
                    <option value="PGK">PGK</option>
                    <option value="PHP">PHP</option>
                    <option value="PKR">PKR</option>
                    <option value="PLN">PLN</option>
                    <option value="PYG">PYG</option>
                    <option value="QAR">QAR</option>
                    <option value="RON">RON</option>
                    <option value="RSD">RSD</option>
                    <option value="RUB">RUB</option>
                    <option value="RWF">RWF</option>
                    <option value="SAR">SAR</option>
                    <option value="SBD">SBD</option>
                    <option value="SCR">SCR</option>
                    <option value="SDG">SDG</option>
                    <option value="SEK">SEK</option>
                    <option value="SGD">SGD</option>
                    <option value="SHP">SHP</option>
                    <option value="SLL">SLL</option>
                    <option value="SOS">SOS</option>
                    <option value="SRD">SRD</option>
                    <option value="SSP">SSP</option>
                    <option value="STD">STD</option>
                    <option value="SVC">SVC</option>
                    <option value="SYP">SYP</option>
                    <option value="SZL">SZL</option>
                    <option value="THB">THB</option>
                    <option value="TJS">TJS</option>
                    <option value="TMT">TMT</option>
                    <option value="TND">TND</option>
                    <option value="TOP">TOP</option>
                    <option value="TRY">TRY</option>
                    <option value="TTD">TTD</option>
                    <option value="TWD">TWD</option>
                    <option value="TZS">TZS</option>
                    <option value="UAH">UAH</option>
                    <option value="UGX">UGX</option>
                    <option value="USD">USD</option>
                    <option value="USN">USN</option>
                    <option value="UYI">UYI</option>
                    <option value="UYU">UYU</option>
                    <option value="UZS">UZS</option>
                    <option value="VEF">VEF</option>
                    <option value="VND">VND</option>
                    <option value="VUV">VUV</option>
                    <option value="WST">WST</option>
                    <option value="XAF">XAF</option>
                    <option value="XAG">XAG</option>
                    <option value="XAU">XAU</option>
                    <option value="XBA">XBA</option>
                    <option value="XBB">XBB</option>
                    <option value="XBC">XBC</option>
                    <option value="XBD">XBD</option>
                    <option value="XCD">XCD</option>
                    <option value="XDR">XDR</option>
                    <option value="XOF">XOF</option>
                    <option value="XPD">XPD</option>
                    <option value="XPF">XPF</option>
                    <option value="XPT">XPT</option>
                    <option value="XSU">XSU</option>
                    <option value="XTS">XTS</option>
                    <option value="XUA">XUA</option>
                    <option value="XXX">XXX</option>
                    <option value="YER">YER</option>
                    <option value="ZAR">ZAR</option>
                    <option value="ZMW">ZMW</option>
                    <option value="ZWL">ZWL</option>
                </select>
                <span class="text-danger"></span>
            </div>
        </div>
    </div>
    <br>

    <!-- Currency Symbol -->
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-4">Currency Symbol<small class="req"> *</small></label>
            <div class="col-sm-8">
                <input id="currency_symbol" name="currency_symbol" type="text" class="form-control" value="INR">
                <span class="text-danger"></span>
            </div>
        </div>
    </div>
    <br>

                            <!-- Credit Limit -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4">Credit Limit<small class="req"> *</small></label>
                                    <div class="col-sm-8">
                                        <input id="credit_limit" name="credit_limit" type="text" class="form-control"
                                            value="20000">
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            {{-- Mobile App --}}
                            <div class="d-flex justify-content-between text-align-center my-3">
                                <h6 class="fw-bold text-uppercase text-primary">Mobile App</h6>
                                <button class="btn btn-sm text-white" style="background: #750096">
                                    <i class="fa fa-android me-1"></i>
                                    Register Your
                                    Android App
                                </button>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">API URL</label>
                                    <input type="text" class="form-control" value="https://api.hospital.com/v1">
                                </div>

                                {{-- Mobile Logo --}}
                                <div class="col-md-6">
                                    <label class="form-label">Mobile App Logo</label><br>
                                    <img src="{{ asset('uploads/hospital_content/logo/images.png') }}"
                                        class="img-thumbnail me-2" style="height:40px;">
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit me-1"></i> Change App Logo
                                    </button>
                                </div>
                            </div>
                            <!-- <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Primary Color</label>
                                    <input type="color" class="form-control form-control-color" value="#3498db">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Secondary Color</label>
                                    <input type="color" class="form-control form-control-color" value="#2ecc71">
                                </div>

                            </div> -->
                            <!-- <hr>
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
                                            <input type="radio" name="scan_type" id="scan_barcode" class="form-check-input">
                                            <label for="scan_barcode" class="form-check-label">Barcode</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="scan_type" id="scan_qrcode" class="form-check-input"
                                                checked>
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
                                        <img src="{{ asset('backend/images/blue.jpg') }}" class="img-fluid border rounded">
                                    </label>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <input type="radio" name="theme" id="theme_gray">
                                    <label for="theme_gray">
                                        <img src="{{ asset('backend/images/gray.jpg') }}" class="img-fluid border rounded">
                                    </label>
                                </div>
                            </div> -->

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
    </div>
@endsection