
<?php $__env->startSection('content'); ?>

<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-mail me-2"></i>Dispatch / Receive Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table">
                            <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th><?php echo e(ucwords(str_replace('_', ' ', $col))); ?></th>
                                    <td>
                                        <?php if($col === 'image'): ?>
                                            <?php if(!empty($item->image)): ?>
                                                <img src="<?php echo e(asset(ltrim($item->image, '/'))); ?>" class="img-fluid img-thumbnail" style="max-width:200px;" alt="Attachment">
                                            <?php else: ?>
                                                <span class="text-muted">No image attached</span>
                                            <?php endif; ?>
                                        <?php elseif(in_array($col, ['date', 'created_at', 'updated_at'])): ?>
                                            <?php echo e(optional($item->$col)->format(in_array($col, ['created_at','updated_at']) ? 'd-m-Y H:i' : 'd-m-Y') ?? '-'); ?>

                                        <?php else: ?>
                                            <?php echo e($item->$col ?? '-'); ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h6>Attachment</h6>
                        <?php if($item->image): ?>
                            <img src="<?php echo e(asset(ltrim($item->image, '/'))); ?>" class="img-fluid img-thumbnail" alt="Attachment">
                        <?php else: ?>
                            <p class="text-muted">No image attached</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/front-office/dispatch-receive/show.blade.php ENDPATH**/ ?>