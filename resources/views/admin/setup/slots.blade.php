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
                    <form id="settings_form" method="POST"
                        action="{{ isset($branch) && $branch->exists ? route('profile.update') : route('profile.store') }}"
                        enctype="multipart/form-data">
                        @csrf


                        {{-- Hospital Name & Code --}}
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label for="doctor" class="form-label fw-bold">Doctor <span
                                        class="text-danger">*</span></label>
                                <select autofocus="" style="width: 100%" id="doctor" name="doctor"
                                    class="select2 select2-hidden-accessible form-select">
                                    <option value="">Select</option>
                                    <option value="8">Amitabh Kulkarni (D007)</option>
                                    <option value="11">Anirban Ghosh (D010)</option>
                                    <option value="12">Anjali Rao (D011)</option>
                                    <option value="2">Bimal Kundu (D001)</option>
                                    <option value="9">Naveen Reddy (D008)</option>
                                    <option value="7">Neha Bansal (D006)</option>
                                    <option value="3">Priya Sharma (D002)</option>
                                    <option value="4">Rajesh Verma (D003)</option>
                                    <option value="10">Ritu Joshi (D009)</option>
                                    <option value="5">Sandeep Sharma (D004)</option>
                                    <option value="6">Swati Das (D005)</option>
                                </select>

                            </div>
                            <div class="col-md-4">
                                <label for="shift" class="form-label fw-bold">Shift <span
                                        class="text-danger">*</span></label>
                                <select autofocus="" style="width: 100%" id="shift" name="doctor"
                                    class="select2 select2-hidden-accessible form-select">
                                    <option value="">Select</option>
                                </select>

                            </div>
                            <div class="col-md-4">
                                <button type="button" onclick="search()" class="btn btn-primary btn-sm mt-4">Search</button>
                            </div>
                        </div>
                        <hr>

                        {{-- Phone & Email --}}
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="consult_time" class="form-label fw-bold">Consultation Duration Minutes <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="consult_time" form="" value="" placeholder=""
                                    class="form-control" id="consult_time" autocomplete="off" required>
                            </div>
                            <div class="col-md-3">
                                <label for="cons_duration_min" class="form-label fw-bold">Charge Categor</label>
                                <select name="charge_category" style="width: 100%" form=""
                                    class="form-select charge_category select2 select2-hidden-accessible" tabindex="-1"
                                    aria-hidden="true">
                                    <option value="">Select</option>
                                    <option value="5">
                                        Appointment Fees </option>
                                    <option value="16">
                                        Registration Charges </option>
                                    <option value="17">
                                        Late Discharge Charges </option>
                                    <option value="18">
                                        Utility Charges </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="charge_id" class="form-label fw-bold">Charge <span
                                        class="text-danger">*</span></label>
                                <select name="charge_id" id="charge_id" style="width: 100%" form=""
                                    class="form-select charge select2 select2-hidden-accessible" tabindex="-1"
                                    aria-hidden="true" required>
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="amount" class="form-label fw-bold">Amount (INR)</label>
                                <input type="text" class="form-control standard_charge" name="amount" id="amount" value="" disabled="disabled">
                            </div>
                        </div>

                        <hr>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script>
        let branchCount = 0;

        function addBranchFields() {
            branchCount++;

            const branchDiv = document.createElement("div");
            branchDiv.classList.add("branch-item", "p-3", "border", "rounded", "mb-3");
            branchDiv.setAttribute("id", "branch-" + branchCount);

            branchDiv.innerHTML = `
                                                      <div class="row gy-3">

                                                        <div class="col-md-4">
                                                          <label class="form-label fw-bold">Branch ID</label>
                                                          <input type="text" class="form-control" name="branch_id[]" placeholder="Enter Branch ID">
                                                        </div>

                                                        <div class="col-md-4">
                                                          <label class="form-label fw-bold">Branch Name</label>
                                                          <input type="text" class="form-control" name="branch_name[]" placeholder="Enter Branch Name">
                                                        </div>

                                                        <div class="col-md-4">
                                                          <label class="form-label fw-bold">Branch Address</label>
                                                          <input type="text" class="form-control" name="branch_address[]" placeholder="Enter Branch Address">
                                                        </div>

                                                        <div class="col-md-6">
                                                          <label class="form-label fw-bold">Phone</label>
                                                          <input type="tel" class="form-control" name="branch_phone[]" placeholder="Enter Phone Number">
                                                        </div>

                                                        <div class="col-md-6">
                                                          <label class="form-label fw-bold">Email</label>
                                                          <input type="email" class="form-control" name="branch_email[]" placeholder="Enter Email ID">
                                                        </div>


                                                        <div class="col-sm-4">
                                                            <label class="form-label fw-bold">Branch Currency</label>
                                                            <select name="branch_currency[]" class="form-control" autocomplete="off">
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

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">Branch Currency Symbol</label>
                                                            <input name="branch_currency_symbol[]" type="text" class="form-control"
                                                                value="INR">
                                                            <span class="text-danger"></span>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <label class="form-label fw-bold">Branch Credit Limit</label>
                                                            <input name="branch_credit_limit[]" type="text" class="form-control"
                                                                value="20000">
                                                            <span class="text-danger"></span>
                                                        </div>


                                                        <div class="col-12 text-end">
                                                          <button type="button" class="btn btn-danger btn-sm" onclick="removeBranch('branch-${branchCount}')">Remove</button>
                                                        </div>
                                                      </div>
                                                    `;

            document.getElementById("branchesContainer").appendChild(branchDiv);
        }

        function removeBranch(branchId) {
            document.getElementById(branchId).remove();
        }
    </script>


    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
    </script>
@endsection