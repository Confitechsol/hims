@extends('layouts.adminLayout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-edit me-2"></i>Edit Test Details
                    </h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('pathology.test.update', $test->id) }}" method="POST" id="pathologyTestForm">
                        @csrf
                        @method('PUT')

                        <!-- Row 1 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Test Name <span class="text-danger">*</span></label>
                                <input type="text" name="test_name" class="form-control" value="{{ old('test_name', $test->test_name) }}" required maxlength="50" placeholder="Test Name">
                                @error('test_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Short Name <span class="text-danger">*</span></label>
                                <input type="text" name="short_name" class="form-control" value="{{ old('short_name', $test->short_name) }}" required maxlength="20" placeholder="Short Name">
                                @error('short_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Test Type</label>
                                <input type="text" name="test_type" class="form-control" value="{{ old('test_type', $test->test_type) }}" maxlength="15" placeholder="Test Type">
                                @error('test_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <select name="pathology_category_id" id="pathology_category_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('pathology_category_id', $test->pathology_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pathology_category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Sub Category</label>
                                <input type="text" name="sub_category" class="form-control" value="{{ old('sub_category', $test->sub_category) }}" maxlength="25" placeholder="Sub Category">
                                @error('sub_category')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Method</label>
                                <input type="text" name="method" class="form-control" value="{{ old('method', $test->method) }}" maxlength="25" placeholder="Method">
                                @error('method')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Report Days <span class="text-danger">*</span></label>
                                <input type="number" name="report_days" class="form-control" value="{{ old('report_days', $test->report_days) }}" min="0" required placeholder="0">
                                @error('report_days')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Standard Charge IPD (INR) <span class="text-danger">*</span></label>
                                <input type="number" name="standard_charge_ipd" id="standard_charge_ipd" class="form-control" value="{{ old('standard_charge_ipd', $test->standard_charge_ipd) }}" step="0.01" min="0" required>
                                @error('standard_charge_ipd')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Standard Charge OPD (INR) <span class="text-danger">*</span></label>
                                <input type="number" name="standard_charge_opd" id="standard_charge_opd" class="form-control" value="{{ old('standard_charge_opd', $test->standard_charge_opd) }}" step="0.01" min="0" required>
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
                                                                       value="{{ old('tpa_charge_ipd_' . $organisation->id, $existingTpaCharges['IPD'][$organisation->id] ?? '') }}"
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       placeholder="Auto: ₹{{ number_format($test->standard_charge_ipd ?? 0, 2) }}"
                                                                       data-org-id="{{ $organisation->id }}"
                                                                       data-charge-type="IPD">
                                                            </td>
                                                            <td>
                                                                <input type="number" 
                                                                       name="tpa_charge_opd_{{ $organisation->id }}" 
                                                                       id="tpa_charge_opd_{{ $organisation->id }}"
                                                                       class="form-control form-control-sm tpa-charge-input" 
                                                                       value="{{ old('tpa_charge_opd_' . $organisation->id, $existingTpaCharges['OPD'][$organisation->id] ?? '') }}"
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       placeholder="Auto: ₹{{ number_format($test->standard_charge_opd ?? 0, 2) }}"
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
                                            If TPA charge is not specified, Standard Charge (IPD/OPD) will be used automatically.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Test Parameters Section -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="mb-3">Test Parameters</h6>
                                <div id="parametersContainer">
                                    @foreach($selectedParameters as $index => $parameterId)
                                        @php
                                            $parameter = collect($parameters)->firstWhere('id', $parameterId);
                                        @endphp
                                        <div class="row mb-2 parameter-row">
                                            <div class="col-md-4">
                                                <label class="form-label">Test Parameter Name <span class="text-danger">*</span></label>
                                                <select name="parameters[]" class="form-control parameter-select" required>
                                                    <option value="">Select</option>
                                                    @foreach($parameters as $param)
                                                        <option value="{{ $param['id'] }}" 
                                                                data-reference="{{ $param['reference_range'] ?? 'N/A' }}" 
                                                                data-unit="{{ $param['unit_relation']['unit_name'] ?? 'N/A' }}"
                                                                {{ $parameterId == $param['id'] ? 'selected' : '' }}>
                                                            {{ $param['parameter_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Reference Range <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control parameter-reference" disabled value="{{ $parameter['reference_range'] ?? 'N/A' }}" placeholder="Select parameter first">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Unit <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control parameter-unit" disabled value="{{ $parameter['unit_relation']['unit_name'] ?? 'N/A' }}" placeholder="Select parameter first">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-parameter">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm" style="background-color: #CB6CE6; color: white;" id="addParameter">
                                    <i class="ti ti-plus"></i> Add
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('pathology.test.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const standardChargeIpdInput = document.getElementById('standard_charge_ipd');
            const standardChargeOpdInput = document.getElementById('standard_charge_opd');
            const parametersData = @json($parameters);

            // Manually initialize Select2 for specific dropdowns (since they use form-control, not form-select)
            setTimeout(function() {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                    // Initialize pathology category
                    jQuery('#pathology_category_id').select2({
                        width: '100%',
                        placeholder: 'Select Category',
                        allowClear: false
                    });
                    
                    // Initialize parameter selects
                    jQuery('.parameter-select').select2({
                        width: '100%',
                        dropdownParent: jQuery('body'),
                        placeholder: 'Select Parameter',
                        allowClear: false
                    });
                }
            }, 500);

            // Update TPA charge placeholders when standard charges change
            function updateTpaPlaceholders() {
                const ipdCharge = parseFloat(standardChargeIpdInput.value) || 0;
                const opdCharge = parseFloat(standardChargeOpdInput.value) || 0;
                
                jQuery('.tpa-charge-input').each(function() {
                    const chargeType = jQuery(this).data('charge-type');
                    const currentValue = jQuery(this).val();
                    // Only update placeholder if field is empty
                    if (!currentValue) {
                        if (chargeType === 'IPD') {
                            jQuery(this).attr('placeholder', ipdCharge > 0 ? 'Auto: ₹' + ipdCharge.toFixed(2) : 'Auto: Standard IPD');
                        } else if (chargeType === 'OPD') {
                            jQuery(this).attr('placeholder', opdCharge > 0 ? 'Auto: ₹' + opdCharge.toFixed(2) : 'Auto: Standard OPD');
                        }
                    }
                });
            }

            // Listen for changes in standard charge inputs
            standardChargeIpdInput.addEventListener('input', updateTpaPlaceholders);
            standardChargeOpdInput.addEventListener('input', updateTpaPlaceholders);
            
            // Initialize placeholders on page load
            updateTpaPlaceholders();

            // Parameter selection handler - using jQuery for Select2 compatibility
            jQuery(document).on('change', '.parameter-select', function() {
                const $select = jQuery(this);
                const selectedOption = $select.find('option:selected');
                const row = $select.closest('.parameter-row');
                const referenceInput = row.find('.parameter-reference');
                const unitInput = row.find('.parameter-unit');

                if ($select.val()) {
                    const refRange = selectedOption.data('reference') || 'N/A';
                    const unitName = selectedOption.data('unit') || 'N/A';
                    
                    // Set values but keep fields disabled (readonly)
                    referenceInput.val(refRange).prop('disabled', true);
                    unitInput.val(unitName).prop('disabled', true);
                } else {
                    referenceInput.val('').prop('disabled', true);
                    unitInput.val('').prop('disabled', true);
                }
            });

            // Add parameter row
            document.getElementById('addParameter').addEventListener('click', function() {
                const container = document.getElementById('parametersContainer');
                const newRow = document.createElement('div');
                newRow.className = 'row mb-2 parameter-row';
                newRow.innerHTML = `
                    <div class="col-md-4">
                        <select name="parameters[]" class="form-control parameter-select" required>
                            <option value="">Select</option>
                            @foreach($parameters as $parameter)
                                <option value="{{ $parameter['id'] }}" 
                                        data-reference="{{ $parameter['reference_range'] ?? 'N/A' }}" 
                                        data-unit="{{ $parameter['unit_relation']['unit_name'] ?? 'N/A' }}">
                                    {{ $parameter['parameter_name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control parameter-reference" disabled placeholder="Select parameter first">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control parameter-unit" disabled placeholder="Select parameter first">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-parameter">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                `;
                container.appendChild(newRow);
                
                // Initialize Select2 for the new select element
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                    jQuery(newRow).find('.parameter-select').select2({
                        width: '100%',
                        dropdownParent: jQuery('body'),
                        placeholder: 'Select Parameter',
                        allowClear: false
                    });
                }
            });

            // Remove parameter row
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-parameter')) {
                    const row = e.target.closest('.parameter-row');
                    if (document.querySelectorAll('.parameter-row').length > 1) {
                        row.remove();
                    } else {
                        alert('At least one parameter is required!');
                    }
                }
            });
        });
    </script>
@endsection
