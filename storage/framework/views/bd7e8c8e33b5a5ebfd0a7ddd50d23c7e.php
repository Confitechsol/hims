<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-plus-circle me-2"></i>Add Test Details
                    </h5>
                </div>

                <div class="card-body">
                    <form action="<?php echo e(route('pathology.test.store')); ?>" method="POST" id="pathologyTestForm">
                        <?php echo csrf_field(); ?>

                        <!-- Row 1 -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Test Name <span class="text-danger">*</span></label>
                                <input type="text" name="test_name" class="form-control" value="<?php echo e(old('test_name')); ?>" required maxlength="50" placeholder="Test Name">
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
                                <input type="text" name="short_name" class="form-control" value="<?php echo e(old('short_name')); ?>" required maxlength="20" placeholder="Short Name">
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
                                <input type="text" name="test_type" class="form-control" value="<?php echo e(old('test_type')); ?>" maxlength="15" placeholder="Test Type">
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
                                        <option value="<?php echo e($category->id); ?>" <?php echo e(old('pathology_category_id') == $category->id ? 'selected' : ''); ?>>
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
                                <input type="text" name="sub_category" class="form-control" value="<?php echo e(old('sub_category')); ?>" maxlength="25" placeholder="Sub Category">
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
                                <input type="text" name="method" class="form-control" value="<?php echo e(old('method')); ?>" maxlength="25" placeholder="Method">
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
                                <input type="number" name="report_days" class="form-control" value="<?php echo e(old('report_days', 0)); ?>" min="0" required placeholder="0">
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
                                        <option value="<?php echo e($chargeCategory->id); ?>" <?php echo e(old('charge_category_id') == $chargeCategory->id ? 'selected' : ''); ?>>
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
                                <input type="number" name="standard_charge" id="standard_charge" class="form-control" value="<?php echo e(old('standard_charge')); ?>" step="0.01" min="0" required readonly>
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
                                <input type="number" name="amount" id="amount" class="form-control" value="<?php echo e(old('amount')); ?>" step="0.01" min="0" required readonly>
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
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       placeholder="Auto: Standard Charge"
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
                                    <div class="row mb-2 parameter-row">
                                        <div class="col-md-4">
                                            <label class="form-label">Test Parameter Name <span class="text-danger">*</span></label>
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
                                            <label class="form-label">Reference Range <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control parameter-reference" disabled placeholder="Select parameter first">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control parameter-unit" disabled placeholder="Select parameter first">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-parameter">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm" style="background-color: #CB6CE6; color: white;" id="addParameter">
                                    <i class="ti ti-plus"></i> Add
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?php echo e(route('pathology.test.index')); ?>" class="btn btn-secondary">Cancel</a>
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
            const parametersData = <?php echo json_encode($parameters, 15, 512) ?>;

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
                    
                    console.log('Select2 initialized for pathology dropdowns');
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
                    const url = `<?php echo e(route('pathology.api.charge-names')); ?>?charge_category_id=${chargeCategoryId}`;
                    
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

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pathology\test\create.blade.php ENDPATH**/ ?>