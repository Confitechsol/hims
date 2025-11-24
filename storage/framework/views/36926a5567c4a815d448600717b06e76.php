<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-edit me-2"></i>Edit Test Details
                    </h5>
                </div>

                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <h6>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo e(route('pathology.test.update', $test->id)); ?>" method="POST" id="pathologyTestForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Row 1 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Test Name <span class="text-danger">*</span></label>
                                <input type="text" name="test_name" class="form-control" value="<?php echo e(old('test_name', $test->test_name)); ?>" required maxlength="50" placeholder="Test Name">
                                <?php $__errorArgs = ['test_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Short Name <span class="text-danger">*</span></label>
                                <input type="text" name="short_name" class="form-control" value="<?php echo e(old('short_name', $test->short_name)); ?>" required maxlength="20" placeholder="Short Name">
                                <?php $__errorArgs = ['short_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Test Type</label>
                                <input type="text" name="test_type" class="form-control" value="<?php echo e(old('test_type', $test->test_type)); ?>" maxlength="15" placeholder="Test Type">
                                <?php $__errorArgs = ['test_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <select name="pathology_category_id" id="pathology_category_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" <?php echo e(old('pathology_category_id', $test->pathology_category_id) == $category->id ? 'selected' : ''); ?>>
                                            <?php echo e($category->category_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['pathology_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Sub Category</label>
                                <input type="text" name="sub_category" class="form-control" value="<?php echo e(old('sub_category', $test->sub_category)); ?>" maxlength="25" placeholder="Sub Category">
                                <?php $__errorArgs = ['sub_category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Method</label>
                                <input type="text" name="method" class="form-control" value="<?php echo e(old('method', $test->method)); ?>" maxlength="25" placeholder="Method">
                                <?php $__errorArgs = ['method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Report Days <span class="text-danger">*</span></label>
                                <input type="number" name="report_days" class="form-control" value="<?php echo e(old('report_days', $test->report_days)); ?>" min="0" required placeholder="0">
                                <?php $__errorArgs = ['report_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Charge Category <span class="text-danger">*</span></label>
                                <select name="charge_category_id" id="charge_category_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php $__currentLoopData = $chargeCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chargeCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $chargeCategoryId = $test->charge_category_id ?? ($test->charge ? $test->charge->charge_category_id : null);
                                        ?>
                                        <option value="<?php echo e($chargeCategory->id); ?>" <?php echo e(old('charge_category_id', $chargeCategoryId) == $chargeCategory->id ? 'selected' : ''); ?>>
                                            <?php echo e($chargeCategory->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['charge_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Charge Name <span class="text-danger">*</span></label>
                                <select name="charge_id" id="charge_id" class="form-control" required>
                                    <option value="">Select</option>
                                </select>
                                <?php $__errorArgs = ['charge_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Tax (%)</label>
                                <div class="input-group">
                                    <input type="number" name="tax_percentage" id="tax_percentage" class="form-control" value="0" step="0.01" min="0" readonly>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Standard Charge (INR) <span class="text-danger">*</span></label>
                                <input type="number" name="standard_charge" id="standard_charge" class="form-control" value="<?php echo e(old('standard_charge', $test->standard_charge)); ?>" step="0.01" min="0" required readonly>
                                <?php $__errorArgs = ['standard_charge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Amount (INR) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control" value="<?php echo e(old('amount', $test->amount)); ?>" step="0.01" min="0" required readonly>
                                <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                                    <?php $__currentLoopData = $organisations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organisation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?php echo e($organisation->organisation_name); ?></strong>
                                                            </td>
                                                            <td>
                                                                <input type="number" 
                                                                       name="tpa_charge_<?php echo e($organisation->id); ?>" 
                                                                       id="tpa_charge_<?php echo e($organisation->id); ?>"
                                                                       class="form-control form-control-sm tpa-charge-input" 
                                                                       value="<?php echo e(old('tpa_charge_' . $organisation->id, $existingTpaCharges[$organisation->id] ?? '')); ?>"
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       placeholder="Auto: ₹<?php echo e(number_format($test->standard_charge ?? 0, 2)); ?>"
                                                                       data-org-id="<?php echo e($organisation->id); ?>">
                                                            </td>
                                                            <td>
                                                                <small class="text-muted"><?php echo e($organisation->code ?? '-'); ?></small>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                        <!-- Test Parameters Section -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h6 class="mb-3">Test Parameters</h6>
                                <div id="parametersContainer">
                                    <?php $__currentLoopData = $selectedParameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $parameterId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $parameter = collect($parameters)->firstWhere('id', $parameterId);
                                        ?>
                                        <div class="row mb-2 parameter-row">
                                            <div class="col-md-4">
                                                <label class="form-label">Test Parameter Name <span class="text-danger">*</span></label>
                                                <select name="parameters[]" class="form-control parameter-select" required>
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = $parameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $param): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($param['id']); ?>" 
                                                                data-reference="<?php echo e($param['reference_range'] ?? 'N/A'); ?>" 
                                                                data-unit="<?php echo e($param['unit_relation']['unit_name'] ?? 'N/A'); ?>"
                                                                <?php echo e($parameterId == $param['id'] ? 'selected' : ''); ?>>
                                                            <?php echo e($param['parameter_name']); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Reference Range <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control parameter-reference" disabled value="<?php echo e($parameter['reference_range'] ?? 'N/A'); ?>" placeholder="Select parameter first">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Unit <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control parameter-unit" disabled value="<?php echo e($parameter['unit_relation']['unit_name'] ?? 'N/A'); ?>" placeholder="Select parameter first">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-parameter">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <button type="button" class="btn btn-sm" style="background-color: #CB6CE6; color: white;" id="addParameter">
                                    <i class="ti ti-plus"></i> Add
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?php echo e(route('pathology.test.index')); ?>" class="btn btn-secondary">Cancel</a>
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
            const chargeCategorySelect = document.getElementById('charge_category_id');
            const chargeSelect = document.getElementById('charge_id');
            const standardChargeInput = document.getElementById('standard_charge');
            const taxPercentageInput = document.getElementById('tax_percentage');
            const amountInput = document.getElementById('amount');
            const currentChargeId = <?php echo e($test->charge_id ?? 'null'); ?>;
            const currentChargeCategoryId = <?php echo e($test->charge_category_id ?? ($test->charge ? $test->charge->charge_category_id : 'null')); ?>;
            const parametersData = <?php echo json_encode($parameters, 15, 512) ?>;
            
            console.log('Parameters Data:', parametersData);
            console.log('Current Charge ID:', currentChargeId);
            console.log('Current Charge Category ID:', currentChargeCategoryId);

            // Manually initialize Select2 for specific dropdowns (since they use form-control, not form-select)
            setTimeout(function() {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                    // Initialize pathology category
                    jQuery('#pathology_category_id').select2({
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
                    
                    // Initialize parameter selects
                    jQuery('.parameter-select').select2({
                        width: '100%',
                        dropdownParent: jQuery('body'),
                        placeholder: 'Select Parameter',
                        allowClear: false
                    });
                }
            }, 500);

            // Load charge names when charge category is selected
            function loadChargeNames() {
                const chargeCategoryId = jQuery('#charge_category_id').val();
                jQuery('#charge_id').empty().append('<option value="">Select</option>');

                if (chargeCategoryId) {
                    fetch(`<?php echo e(route('pathology.api.charge-names')); ?>?charge_category_id=${chargeCategoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(charge => {
                                const option = new Option(charge.name, charge.id);
                                if (charge.id == currentChargeId) {
                                    option.selected = true;
                                    standardChargeInput.value = charge.standard_charge;
                                    taxPercentageInput.value = charge.tax_percentage;
                                    amountInput.value = (parseFloat(charge.standard_charge) + (parseFloat(charge.standard_charge) * parseFloat(charge.tax_percentage) / 100)).toFixed(2);
                                }
                                jQuery('#charge_id').append(option);
                            });
                            // Trigger Select2 to refresh
                            jQuery('#charge_id').trigger('change.select2');
                        })
                        .catch(error => {
                            alert('Error loading charges. Please try again.');
                        });
                }
            }

            // Set charge category on page load if it exists
            if (currentChargeCategoryId) {
                jQuery('#charge_category_id').val(currentChargeCategoryId).trigger('change.select2');
            }
            
            // Load initial charge names after a short delay to ensure Select2 is initialized
            setTimeout(function() {
                loadChargeNames();
            }, 700);

            jQuery('#charge_category_id').on('change', loadChargeNames);

            // Load charge details when charge is selected (using jQuery for Select2 compatibility)
            jQuery('#charge_id').on('change', function() {
                const chargeId = jQuery(this).val();

                if (chargeId) {
                    fetch(`<?php echo e(route('pathology.api.charge-details')); ?>?charge_id=${chargeId}`)
                        .then(response => response.json())
                        .then(data => {
                            standardChargeInput.value = data.standard_charge;
                            taxPercentageInput.value = data.tax_percentage;
                            amountInput.value = data.amount;
                            
                            // Update TPA charge placeholder with standard charge
                            jQuery('.tpa-charge-input').each(function() {
                                const currentValue = jQuery(this).val();
                                // Only update placeholder if field is empty
                                if (!currentValue) {
                                    jQuery(this).attr('placeholder', 'Auto: ₹' + parseFloat(data.standard_charge).toFixed(2));
                                }
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
                            <?php $__currentLoopData = $parameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parameter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($parameter['id']); ?>" 
                                        data-reference="<?php echo e($parameter['reference_range'] ?? 'N/A'); ?>" 
                                        data-unit="<?php echo e($parameter['unit_relation']['unit_name'] ?? 'N/A'); ?>">
                                    <?php echo e($parameter['parameter_name']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp-8.2\htdocs\hims\resources\views/admin/pathology/test/edit.blade.php ENDPATH**/ ?>