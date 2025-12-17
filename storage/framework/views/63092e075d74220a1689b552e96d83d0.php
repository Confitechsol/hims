<?php $__env->startSection('content'); ?>
        

    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Modules</h5>
                </div>

                <div class="card-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="permissionTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="admin-tab" data-bs-toggle="tab" href="#admin" role="tab">Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="user-tab" data-bs-toggle="tab" href="#user" role="tab">User</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <!-- Admin Tab -->
                        <div class="tab-pane fade show active" id="admin" role="tabpanel">
                            <form method="POST" action="<?php echo e(route('permissions.update')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="role" value="admin">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Module</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $adminModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($module->name); ?></td>
                                                <!-- <td>
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="permissions[<?php echo e($module->id); ?>]" value="0">
                                                        <input class="form-check-input" type="checkbox" name="permissions[<?php echo e($module->id); ?>]" value="1"
                                                            <?php echo e($adminPermissions[$module->id] ?? 0 ? 'checked' : ''); ?>>
                                                    </div>
                                                </td> -->
                                                <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input permission-toggle"
                                                        type="checkbox"
                                                        data-role="admin"
                                                        data-id="<?php echo e($module->id); ?>"
                                                        <?php echo e($module->is_active ? 'checked' : ''); ?>>
                                                </div>
                                            </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <!-- <button type="submit" class="btn btn-success">Save</button> -->
                            </form>
                        </div>

                        <!-- User Tab -->
                        <div class="tab-pane fade" id="user" role="tabpanel">
                            <form method="POST" action="<?php echo e(route('permissions.update')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="role" value="user">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Module</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $userModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($module->name); ?></td>
                                                <!-- <td>
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="permissions[<?php echo e($module->id); ?>]" value="0">
                                                        <input class="form-check-input" type="checkbox" name="permissions[<?php echo e($module->id); ?>]" value="1"
                                                            <?php echo e($userPermissions[$module->id] ?? 0 ? 'checked' : ''); ?>>
                                                    </div>
                                                </td> -->
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input permission-toggle"
    data-role="user"
    data-id="<?php echo e($module->id); ?>"
    <?php echo e($module->is_active ? 'checked' : ''); ?>>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <!-- <button type="submit" class="btn btn-success">Save</button> -->
                            </form>
                        </div>
                    </div> <!-- end tab content -->
                </div>
            </div>
        </div>
    </div>



        <!-- ========================
			End Page Content
		========================= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).on('change', '.permission-toggle', function () {
    let role = $(this).data('role');
    let id = $(this).data('id');
    let isActive = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "<?php echo e(route('permissions.toggle')); ?>",
        method: "POST",
        data: {
            _token: "<?php echo e(csrf_token()); ?>",
            role: role,
            id: id,
            is_active: isActive
        },
        success: function (response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved',
                    text: 'Permission updated successfully!',
                    timer: 1500,
                    showConfirmButton: false
                });
                console.log(response.message);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.message
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong, please try again.'
            });
        }
    });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\modules.blade.php ENDPATH**/ ?>