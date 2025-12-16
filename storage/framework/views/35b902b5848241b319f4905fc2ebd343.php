
<?php $__env->startSection('content'); ?>

<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-mail me-2"></i><?php echo e($type); ?> List</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a href="<?php echo e(route('visitors')); ?>" class="btn btn-secondary text-white fs-13 btn-md"><i class="ti ti-arrow-left me-1"></i>Back to Visitors</a>
                        <a href="#" class="btn btn-success ms-2">Add <?php echo e($type); ?></a>
                    </div>
                    <div>
                        <!-- export/copy buttons could go here -->
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>From Title</th>
                                <th>Reference No</th>
                                <th>To Title</th>
                                <th>Address</th>
                                <th>Note</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($item->reference_no); ?></td>
                                    <td><?php echo e($item->type === 'receive' ? ($item->from_title ?? '-') : ($item->to_title ?? '-')); ?></td>
                                    <td><?php echo e($item->address ?? '-'); ?></td>
                                    <td><?php echo e(optional($item->date)->format('d-m-Y') ?? '-'); ?></td>
                                    <td><?php echo e(\Illuminate\Support\Str::limit($item->note ?? '-', 80)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($items->links()); ?>

                </div>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\front-office\dispatch-receive\index.blade.php ENDPATH**/ ?>