<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-pills me-2"></i>Pharmacy Medicine List</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                        <div class="input-icon-start position-relative me-2">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" class="form-control shadow-sm" id="searchMedicine" placeholder="Search Medicine">
                                        </div>

                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="<?php echo e(route('pharmacy.create')); ?>"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                    <i class="ti ti-plus me-1"></i>Add Medicine
                                                </a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="<?php echo e(route('pharmacy.import')); ?>"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                    <i class="ti ti-download me-1"></i>Import Medicines
                                                </a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="<?php echo e(route('pharmacy.purchase.index')); ?>"
                                                    class="btn btn-success text-white ms-2 fs-13 btn-md">
                                                    <i class="ti ti-shopping-cart me-1"></i>Purchase
                                                </a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="<?php echo e(route('pharmacy.below-min-level')); ?>"
                                                    class="btn btn-warning text-white ms-2 fs-13 btn-md">
                                                    <i class="ti ti-alert-triangle me-1"></i>Below Min Level
                                                </a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="<?php echo e(route('pharmacy.needs-reorder')); ?>"
                                                    class="btn btn-info text-white ms-2 fs-13 btn-md">
                                                    <i class="ti ti-package me-1"></i>Needs Reorder
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if(session('success')): ?>
                                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                                    <?php endif; ?>

                                    <?php if(session('error')): ?>
                                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                                    <?php endif; ?>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Medicine Name</th>
                                                    <th>Category</th>
                                                    <th>Company</th>
                                                    <th>Composition</th>
                                                    <th>Group</th>
                                                    <th>Unit</th>
                                                    <th>Stock</th>
                                                    <th>Min Level</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="medicineTableBody">
                                                <?php $__empty_1 = true; $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td><?php echo e($medicine->id); ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <?php if($medicine->medicine_image): ?>
                                                                    <img src="<?php echo e(asset('storage/' . $medicine->medicine_image)); ?>" 
                                                                         alt="<?php echo e($medicine->medicine_name); ?>" 
                                                                         class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                                <?php endif; ?>
                                                                <span class="fw-bold"><?php echo e($medicine->medicine_name); ?></span>
                                                            </div>
                                                        </td>
                                                        <td><?php echo e($medicine->medicineCategory->medicine_category ?? '-'); ?></td>
                                                        <td><?php echo e($medicine->company->company_name ?? '-'); ?></td>
                                                        <td><?php echo e($medicine->medicine_composition ?? '-'); ?></td>
                                                        <td><?php echo e($medicine->medicineGroup->group_name ?? '-'); ?></td>
                                                        <td><?php echo e($medicine->unitRelation->unit_name ?? '-'); ?></td>
                                                        <td>
                                                            <?php
                                                                $totalQty = $medicine->total_quantity ?? 0;
                                                                $minLevel = $medicine->min_level ?? 0;
                                                                $badgeClass = $totalQty <= $minLevel ? 'bg-danger' : ($totalQty <= $medicine->reorder_level ? 'bg-warning' : 'bg-success');
                                                            ?>
                                                            <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($totalQty); ?></span>
                                                        </td>
                                                        <td><?php echo e($medicine->min_level ?? '-'); ?></td>
                                                        <td>
                                                            <?php if($medicine->is_active === 'yes'): ?>
                                                                <span class="badge bg-success">Active</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Inactive</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-primary dropdown-toggle" 
                                                                        type="button" 
                                                                        data-bs-toggle="dropdown">
                                                                    Actions
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?php echo e(route('pharmacy.show', $medicine->id)); ?>">
                                                                            <i class="ti ti-eye me-2"></i>View
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?php echo e(route('pharmacy.edit', $medicine->id)); ?>">
                                                                            <i class="ti ti-edit me-2"></i>Edit
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <form action="<?php echo e(route('pharmacy.destroy', $medicine->id)); ?>" 
                                                                              method="POST" 
                                                                              class="d-inline"
                                                                              onsubmit="return confirm('Are you sure you want to delete this medicine?')">
                                                                            <?php echo csrf_field(); ?>
                                                                            <?php echo method_field('DELETE'); ?>
                                                                            <button type="submit" class="dropdown-item text-danger">
                                                                                <i class="ti ti-trash me-2"></i>Delete
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="11" class="text-center text-muted py-4">
                                                            <i class="ti ti-package-off" style="font-size: 3rem;"></i>
                                                            <p class="mt-2">No medicines found</p>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    
                                    <div class="mt-3">
                                        <?php echo e($medicines->links()); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchMedicine').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#medicineTableBody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pharmacy\index.blade.php ENDPATH**/ ?>