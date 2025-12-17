<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-exclamation-triangle me-2"></i>Medicines Below Minimum Level
                    </h5>
                </div>

                <div class="card-body">
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-triangle me-2"></i>
                        These medicines are below their minimum stock levels and need immediate attention!
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Category</th>
                                    <th>Company</th>
                                    <th>Current Stock</th>
                                    <th>Min Level</th>
                                    <th>Reorder Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo e($medicine->medicine_name); ?></td>
                                        <td><?php echo e($medicine->medicineCategory->medicine_category ?? '-'); ?></td>
                                        <td><?php echo e($medicine->company->company_name ?? '-'); ?></td>
                                        <td>
                                            <span class="badge bg-danger"><?php echo e($medicine->total_quantity ?? 0); ?></span>
                                        </td>
                                        <td><?php echo e($medicine->min_level); ?></td>
                                        <td><?php echo e($medicine->reorder_level); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('pharmacy.show', $medicine->id)); ?>" class="btn btn-sm btn-primary">
                                                <i class="ti ti-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-success py-4">
                                            <i class="ti ti-check-circle" style="font-size: 3rem;"></i>
                                            <p class="mt-2">All medicines are above minimum level!</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="<?php echo e(route('pharmacy.index')); ?>" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Back to Medicines
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pharmacy\below-min-level.blade.php ENDPATH**/ ?>