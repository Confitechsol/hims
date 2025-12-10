<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <!-- Start Content -->
    <div class="content" id="profilePage">

     <!-- Page Header -->
     <div class="mb-3 border-bottom pb-3">
         <h4 class="fw-bold mb-0">Settings</h4>
     </div>
     <!-- End Page Header -->

     <div class="card">
         <div class="card-body p-0">
             <div class="settings-wrapper d-flex">
                 <div class="card flex-fill mb-0 border-0 bg-light-500 shadow-none">
                     <div class="card-header border-bottom px-0 mx-3">
                         <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                             <h5 class="fw-bold">Database Backup</h5>
                             
                         </div>
                     </div>
                     <div class="card-body px-0 mx-3">
                        <a href="<?php echo e(route('database.backup')); ?>" class="btn btn-info mb-3">Create New Backup</a>
                        <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php elseif(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Size (KB)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($backup['name']); ?></td>
                                        <td><?php echo e(number_format($backup['size'] / 1024, 2)); ?></td>
                                        <td>
                                            <!-- Download -->
                                            <a href="<?php echo e($backup['url']); ?>" class="btn btn-sm btn-success">Download</a>
                    
                                            <!-- Restore -->
                                            <form action="<?php echo e(route('database.restore')); ?>" method="POST" style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="filename" value="<?php echo e($backup['name']); ?>">
                                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to restore this backup?')">Restore</button>
                                            </form>
                    
                                            <!-- Delete -->
                                            <form action="<?php echo e(route('database.delete', $backup['name'])); ?>" method="POST" style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this backup?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="3">No backups found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                     </div>
                 </div>
             </div>

         </div><!-- end card body -->
     </div><!-- end card -->
                     
 </div>
 <!-- End Content -->
</div>     
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/backups/index.blade.php ENDPATH**/ ?>