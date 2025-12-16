<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #750096">
                            <i class="fas fa-pills me-2"></i>Medicine Details
                        </h5>
                        <div>
                            <a href="<?php echo e(route('pharmacy.edit', $medicine->id)); ?>" class="btn btn-sm btn-warning text-white">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                            <a href="<?php echo e(route('pharmacy.index')); ?>" class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Medicine Name:</th>
                                            <td><?php echo e($medicine->medicine_name); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Category:</th>
                                            <td><?php echo e($medicine->medicineCategory->medicine_category ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Company:</th>
                                            <td><?php echo e($medicine->company->company_name ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Composition:</th>
                                            <td><?php echo e($medicine->medicine_composition ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Medicine Group:</th>
                                            <td><?php echo e($medicine->medicineGroup->group_name ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Unit:</th>
                                            <td><?php echo e($medicine->unitRelation->unit_name ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Unit Packing:</th>
                                            <td><?php echo e($medicine->unit_packing ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Rack Number:</th>
                                            <td><?php echo e($medicine->rack_number ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <th>GST Rate:</th>
                                            <td>
                                                <?php if($medicine->gst_percentage !== null): ?>
                                                    <span class="badge bg-info"><?php echo e($medicine->gst_percentage); ?>% GST</span>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <?php if($medicine->is_active === 'yes'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php if($medicine->note): ?>
                                            <tr>
                                                <th>Note:</th>
                                                <td><?php echo e($medicine->note); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>

                            
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Stock Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded">
                                                <h4 class="mb-0"><?php echo e($medicine->total_quantity ?? 0); ?></h4>
                                                <small class="text-muted">Total Stock</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded">
                                                <h4 class="mb-0 text-warning"><?php echo e($medicine->min_level ?? 0); ?></h4>
                                                <small class="text-muted">Min Level</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 border rounded">
                                                <h4 class="mb-0 text-info"><?php echo e($medicine->reorder_level ?? 0); ?></h4>
                                                <small class="text-muted">Reorder Level</small>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if($medicine->isBelowMinLevel()): ?>
                                        <div class="alert alert-danger mt-3 mb-0">
                                            <i class="ti ti-alert-triangle me-2"></i>Stock is below minimum level!
                                        </div>
                                    <?php elseif($medicine->needsReorder()): ?>
                                        <div class="alert alert-warning mt-3 mb-0">
                                            <i class="ti ti-alert-circle me-2"></i>Stock needs reordering!
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Medicine Image</h6>
                                </div>
                                <div class="card-body text-center">
                                    <?php if($medicine->medicine_image): ?>
                                        <img src="<?php echo e(asset('storage/' . $medicine->medicine_image)); ?>" 
                                             alt="<?php echo e($medicine->medicine_name); ?>" 
                                             class="img-fluid rounded">
                                    <?php else: ?>
                                        <div class="text-muted py-5">
                                            <i class="ti ti-photo-off" style="font-size: 4rem;"></i>
                                            <p class="mt-2">No image available</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Batch Details</h6>
                        </div>
                        <div class="card-body">
                            <?php if($medicine->batches->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Batch No</th>
                                                <th>Inward Date</th>
                                                <th>Expiry Date</th>
                                                <th>Quantity</th>
                                                <th>Available</th>
                                                <th>MRP</th>
                                                <th>Sale Rate</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $medicine->batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($batch->batch_no); ?></td>
                                                    <td><?php echo e($batch->inward_date->format('d M Y')); ?></td>
                                                    <td><?php echo e($batch->expiry->format('d M Y')); ?></td>
                                                    <td><?php echo e($batch->quantity); ?></td>
                                                    <td>
                                                        <span class="badge <?php echo e($batch->available_quantity > 0 ? 'bg-success' : 'bg-danger'); ?>">
                                                            <?php echo e($batch->available_quantity); ?>

                                                        </span>
                                                    </td>
                                                    <td>₹<?php echo e(number_format($batch->mrp, 2)); ?></td>
                                                    <td>₹<?php echo e(number_format($batch->sale_rate, 2)); ?></td>
                                                    <td>
                                                        <?php if($batch->isExpired()): ?>
                                                            <span class="badge bg-danger">Expired</span>
                                                        <?php elseif($batch->isExpiringSoon()): ?>
                                                            <span class="badge bg-warning">Expiring Soon</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-success">Valid</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="ti ti-package-off" style="font-size: 3rem;"></i>
                                    <p class="mt-2">No batches available</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pharmacy\show.blade.php ENDPATH**/ ?>