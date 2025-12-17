<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-pills me-2"></i>Edit Medicine
                    </h5>
                </div>

                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('pharmacy.update', $medicine->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row">
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Medicine Name <span class="text-danger">*</span></label>
                                <input type="text" name="medicine_name" class="form-control" 
                                       value="<?php echo e(old('medicine_name', $medicine->medicine_name)); ?>" required>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="medicine_category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" 
                                            <?php echo e(old('medicine_category_id', $medicine->medicine_category_id) == $category->id ? 'selected' : ''); ?>>
                                            <?php echo e($category->medicine_category); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company</label>
                                <select name="medicine_company" class="form-select">
                                    <option value="">Select Company</option>
                                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($company->id); ?>" 
                                            <?php echo e(old('medicine_company', $medicine->medicine_company) == $company->id ? 'selected' : ''); ?>>
                                            <?php echo e($company->company_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Composition</label>
                                <input type="text" name="medicine_composition" class="form-control" 
                                       value="<?php echo e(old('medicine_composition', $medicine->medicine_composition)); ?>">
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Medicine Group</label>
                                <select name="medicine_group" class="form-select">
                                    <option value="">Select Group</option>
                                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($group->id); ?>" 
                                            <?php echo e(old('medicine_group', $medicine->medicine_group) == $group->id ? 'selected' : ''); ?>>
                                            <?php echo e($group->group_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit</label>
                                <select name="unit" class="form-select">
                                    <option value="">Select Unit</option>
                                    <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($unit->id); ?>" 
                                            <?php echo e(old('unit', $medicine->unit) == $unit->id ? 'selected' : ''); ?>>
                                            <?php echo e($unit->unit_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Minimum Level</label>
                                <input type="text" name="min_level" class="form-control" 
                                       value="<?php echo e(old('min_level', $medicine->min_level)); ?>">
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Reorder Level</label>
                                <input type="text" name="reorder_level" class="form-control" 
                                       value="<?php echo e(old('reorder_level', $medicine->reorder_level)); ?>">
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">GST Rate (%)</label>
                                <select name="gst_percentage" class="form-select">
                                    <option value="">Select GST Rate</option>
                                    <option value="0" <?php echo e(old('gst_percentage', $medicine->gst_percentage) == '0' ? 'selected' : ''); ?>>0% (Exempt)</option>
                                    <option value="5" <?php echo e(old('gst_percentage', $medicine->gst_percentage) == '5' ? 'selected' : ''); ?>>5%</option>
                                    <option value="12" <?php echo e(old('gst_percentage', $medicine->gst_percentage) == '12' ? 'selected' : ''); ?>>12%</option>
                                    <option value="18" <?php echo e(old('gst_percentage', $medicine->gst_percentage) == '18' ? 'selected' : ''); ?>>18%</option>
                                    <option value="28" <?php echo e(old('gst_percentage', $medicine->gst_percentage) == '28' ? 'selected' : ''); ?>>28%</option>
                                </select>
                                <small class="text-muted">Standard Indian GST Rates</small>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Packing</label>
                                <input type="text" name="unit_packing" class="form-control" 
                                       value="<?php echo e(old('unit_packing', $medicine->unit_packing)); ?>" 
                                       placeholder="e.g., 10 Tablets per strip">
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rack Number</label>
                                <input type="text" name="rack_number" class="form-control" 
                                       value="<?php echo e(old('rack_number', $medicine->rack_number)); ?>">
                            </div>

                            
                            <?php if($medicine->medicine_image): ?>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Current Image</label>
                                    <div>
                                        <img src="<?php echo e(asset('storage/' . $medicine->medicine_image)); ?>" 
                                             alt="<?php echo e($medicine->medicine_name); ?>" 
                                             class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                            <?php endif; ?>

                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Update Image</label>
                                <input type="file" name="medicine_image" class="form-control" accept="image/*">
                                <small class="text-muted">Max size: 2MB. Formats: jpg, jpeg, png</small>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="yes" <?php echo e(old('is_active', $medicine->is_active) == 'yes' ? 'selected' : ''); ?>>Active</option>
                                    <option value="no" <?php echo e(old('is_active', $medicine->is_active) == 'no' ? 'selected' : ''); ?>>Inactive</option>
                                </select>
                            </div>

                            
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3"><?php echo e(old('note', $medicine->note)); ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo e(route('pharmacy.index')); ?>" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i>Update Medicine
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pharmacy\edit.blade.php ENDPATH**/ ?>