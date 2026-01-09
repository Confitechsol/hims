@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-plus-circle me-2"></i>Add Radiology Test Details
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('radiology.test.store') }}" method="POST" id="radiologyTestForm">
                        @csrf

                        <!-- Row 1 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Test Name <span class="text-danger">*</span></label>
                                <input type="text" name="test_name" class="form-control" value="{{ old('test_name') }}" required maxlength="50" placeholder="Test Name">
                                @error('test_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Short Name <span class="text-danger">*</span></label>
                                <input type="text" name="short_name" class="form-control" value="{{ old('short_name') }}" required maxlength="20" placeholder="Short Name">
                                @error('short_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Test Type</label>
                                <input type="text" name="test_type" class="form-control" value="{{ old('test_type') }}" maxlength="15" placeholder="Test Type">
                                @error('test_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <select name="radiology_category_id" id="radiology_category_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('radiology_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('radiology_category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Sub Category</label>
                                <input type="text" name="sub_category" class="form-control" value="{{ old('sub_category') }}" maxlength="25" placeholder="Sub Category">
                                @error('sub_category')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Report Days <span class="text-danger">*</span></label>
                                <input type="number" name="report_days" class="form-control" value="{{ old('report_days', 0) }}" min="0" required placeholder="0">
                                @error('report_days')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Standard Charge IPD (INR) <span class="text-danger">*</span></label>
                                <input type="number" name="standard_charge_ipd" id="standard_charge_ipd" class="form-control" value="{{ old('standard_charge_ipd') }}" step="0.01" min="0" required>
                                @error('standard_charge_ipd')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Standard Charge OPD (INR) <span class="text-danger">*</span></label>
                                <input type="number" name="standard_charge_opd" id="standard_charge_opd" class="form-control" value="{{ old('standard_charge_opd') }}" step="0.01" min="0" required>
                                @error('standard_charge_opd')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- TPA Charges Section -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="mb-3">
                                    <i class="fas fa-building me-2"></i>TPA Charges (Optional - Leave blank to use Standard Charge)
                                </h6>
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <tr>
                                                        <th width="30%">TPA Organization</th>
                                                        <th width="30%">TPA Charge IPD (INR)</th>
                                                        <th width="30%">TPA Charge OPD (INR)</th>
                                                        <th width="10%">Code</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($organisations as $organisation)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $organisation->organisation_name }}</strong>
                                                            </td>
                                                            <td>
                                                                <input type="number" 
                                                                       name="tpa_charge_ipd_{{ $organisation->id }}" 
                                                                       id="tpa_charge_ipd_{{ $organisation->id }}"
                                                                       class="form-control form-control-sm tpa-charge-input" 
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       placeholder="Auto: Standard IPD"
                                                                       data-org-id="{{ $organisation->id }}"
                                                                       data-charge-type="IPD">
                                                            </td>
                                                            <td>
                                                                <input type="number" 
                                                                       name="tpa_charge_opd_{{ $organisation->id }}" 
                                                                       id="tpa_charge_opd_{{ $organisation->id }}"
                                                                       class="form-control form-control-sm tpa-charge-input" 
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       placeholder="Auto: Standard OPD"
                                                                       data-org-id="{{ $organisation->id }}"
                                                                       data-charge-type="OPD">
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">{{ $organisation->code ?? '-' }}</small>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <small class="text-muted">
                                            <i class="ti ti-info-circle me-1"></i>
                                            If TPA charge is not specified, Standard Charge will be used automatically.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('radiology.test.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 for radiology category dropdown
            setTimeout(function() {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                    jQuery('#radiology_category_id').select2({
                        width: '100%',
                        placeholder: 'Select Category',
                        allowClear: false
                    });
                    
                    console.log('Select2 initialized for radiology dropdowns');
                }
            }, 500);

            // Update TPA charge placeholders when standard charges change
            const standardChargeIpdInput = document.getElementById('standard_charge_ipd');
            const standardChargeOpdInput = document.getElementById('standard_charge_opd');

            function updateTpaPlaceholders() {
                const ipdValue = standardChargeIpdInput.value || '0';
                const opdValue = standardChargeOpdInput.value || '0';
                
                jQuery('.tpa-charge-input[data-charge-type="IPD"]').each(function() {
                    jQuery(this).attr('placeholder', 'Auto: ₹' + parseFloat(ipdValue).toFixed(2));
                });
                
                jQuery('.tpa-charge-input[data-charge-type="OPD"]').each(function() {
                    jQuery(this).attr('placeholder', 'Auto: ₹' + parseFloat(opdValue).toFixed(2));
                });
            }

            if (standardChargeIpdInput) {
                standardChargeIpdInput.addEventListener('input', updateTpaPlaceholders);
            }
            if (standardChargeOpdInput) {
                standardChargeOpdInput.addEventListener('input', updateTpaPlaceholders);
            }

            // Initialize placeholders on page load
            updateTpaPlaceholders();
        });
    </script>
@endsection
