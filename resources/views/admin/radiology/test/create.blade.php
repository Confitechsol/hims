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
                                <label class="form-label">Charge Category <span class="text-danger">*</span></label>
                                <select name="charge_category_id" id="charge_category_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($chargeCategories as $chargeCategory)
                                        <option value="{{ $chargeCategory->id }}" {{ old('charge_category_id') == $chargeCategory->id ? 'selected' : '' }}>
                                            {{ $chargeCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('charge_category_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Charge Name <span class="text-danger">*</span></label>
                                <select name="charge_id" id="charge_id" class="form-control" required>
                                    <option value="">Select</option>
                                </select>
                                @error('charge_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Tax (%)</label>
                                <div class="input-group">
                                    <input type="number" name="tax_percentage" id="tax_percentage" class="form-control" value="0" step="0.01" min="0" readonly>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Standard Charge (INR)</label>
                                <input type="number" name="standard_charge" id="standard_charge" class="form-control" value="{{ old('standard_charge') }}" step="0.01" min="0" readonly>
                                @error('standard_charge')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Amount (INR)</label>
                                <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" step="0.01" min="0" readonly>
                                @error('amount')
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
                                                        <th width="40%">TPA Organization</th>
                                                        <th width="40%">TPA Charge (INR)</th>
                                                        <th width="20%">Code</th>
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
                                                                       name="tpa_charge_{{ $organisation->id }}" 
                                                                       id="tpa_charge_{{ $organisation->id }}"
                                                                       class="form-control form-control-sm tpa-charge-input" 
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       placeholder="Auto: Standard Charge"
                                                                       data-org-id="{{ $organisation->id }}">
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
            const chargeCategorySelect = document.getElementById('charge_category_id');
            const chargeSelect = document.getElementById('charge_id');
            const standardChargeInput = document.getElementById('standard_charge');
            const taxPercentageInput = document.getElementById('tax_percentage');
            const amountInput = document.getElementById('amount');

            // Initialize Select2 for dropdowns
            setTimeout(function() {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                    // Initialize radiology category
                    jQuery('#radiology_category_id').select2({
                        width: '100%',
                        placeholder: 'Select Category',
                        allowClear: false
                    });
                    
                    // Initialize charge category
                    jQuery('#charge_category_id').select2({
                        width: '100%',
                        placeholder: 'Select Charge Category',
                        allowClear: false
                    });
                    
                    // Initialize charge name dropdown
                    jQuery('#charge_id').select2({
                        width: '100%',
                        placeholder: 'Select Charge',
                        allowClear: false
                    });
                    
                    console.log('Select2 initialized for radiology dropdowns');
                }
            }, 500);

            // Load charge names when charge category is selected
            jQuery('#charge_category_id').on('change', function() {
                const chargeCategoryId = jQuery(this).val();
                
                // Clear the charge dropdown
                jQuery('#charge_id').empty().append('<option value="">Select</option>');
                standardChargeInput.value = '';
                taxPercentageInput.value = '0';
                amountInput.value = '';

                if (chargeCategoryId) {
                    const url = `{{ route('radiology.api.charge-names') }}?charge_category_id=${chargeCategoryId}`;
                    
                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.length === 0) {
                                alert('No charges found for this category');
                            }
                            
                            // Add new options
                            data.forEach(charge => {
                                const option = new Option(charge.name, charge.id);
                                jQuery('#charge_id').append(option);
                            });
                            
                            // Trigger Select2 to refresh
                            jQuery('#charge_id').trigger('change.select2');
                        })
                        .catch(error => {
                            alert('Error loading charges. Please try again.');
                        });
                }
            });

            // Load charge details when charge is selected
            jQuery('#charge_id').on('change', function() {
                const chargeId = jQuery(this).val();

                if (chargeId) {
                    fetch(`{{ route('radiology.api.charge-details') }}?charge_id=${chargeId}`)
                        .then(response => response.json())
                        .then(data => {
                            standardChargeInput.value = data.standard_charge;
                            taxPercentageInput.value = data.tax_percentage;
                            amountInput.value = data.amount;
                            
                            // Update TPA charge placeholder with standard charge
                            jQuery('.tpa-charge-input').each(function() {
                                jQuery(this).attr('placeholder', 'Auto: ₹' + parseFloat(data.standard_charge).toFixed(2));
                            });
                        })
                        .catch(error => {
                            alert('Error loading charge details. Please try again.');
                        });
                } else {
                    standardChargeInput.value = '';
                    taxPercentageInput.value = '0';
                    amountInput.value = '';
                    // Clear TPA charge placeholders
                    jQuery('.tpa-charge-input').each(function() {
                        jQuery(this).attr('placeholder', 'Auto: Standard Charge');
                    });
                }
            });
        });
    </script>
@endsection

