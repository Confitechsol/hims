<?php $__env->startSection('content'); ?>
    <style>
        .nav-tabs .nav-link.active {
            background-color: #750096 !important;
            color: #f8f9fa !important;
            font-weight: 600 !important;
        }
    </style>
    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">

                <div class="card-header d-flex justify-content-between align-items-center align-items-sm-center justify-content-between flex-sm-row"
                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Users</h5>
                    <div class="text-end d-flex">
                        <ul class="nav nav-tabs">
                            <li class="nav-item" style="border-bottom:0">
                                <a class="nav-link mb-0 <?php echo e($isDoctorTab ? 'active' : ''); ?> text-white"
                                    href="<?php echo e(route('users', array_merge(request()->except('tab'), ['tab' => 'doctor']))); ?>">
                                    Doctors
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 <?php echo e(!$isDoctorTab ? 'active' : ''); ?> text-white"
                                    href="<?php echo e(route('users', array_merge(request()->except('tab'), ['tab' => 'staff']))); ?>">
                                    Staff
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                        <form action="<?php echo e(route('users')); ?>" method="GET">
                                            <div class="d-flex align-items-center">
                                                <div class="input-icon-start position-relative me-2">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input type="text" id="language-search" name="search"
                                                        value="<?php echo e(request('search')); ?>" class="form-control shadow-sm"
                                                        placeholder="Search">
                                                </div>
                                                <div>
                                                    <button class="btn btn-primary" type="submit">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="text-end d-flex">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item" style="border-bottom:0">
                                                    <a class="nav-link mb-0 <?php echo e($statusTab == 'active' ? 'active' : ''); ?>"
                                                        href="<?php echo e(route('users', array_merge(request()->except('statusTab'), ['statusTab' => 'active']))); ?>">
                                                        <?php echo e($isDoctorTab ? 'Active Doctors' : 'Active Staffs'); ?>

                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-0 <?php echo e($statusTab == 'inactive' ? 'active' : ''); ?>"
                                                        href="<?php echo e(route('users', array_merge(request()->except('statusTab'), ['statusTab' => 'inactive']))); ?>">
                                                        <?php echo e($isDoctorTab ? 'Inactive Doctors' : 'Inactive Staffs'); ?>

                                                    </a>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                    <?php if(!$isDoctorTab): ?>
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Role</th>
                                                        <th>Designation</th>
                                                        <th>Department</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <th scope="row"><?php echo e($loop->iteration); ?></th>
                                                            <td><?php echo e($user->name); ?>&nbsp;<?php echo e($user->surname); ?></td>
                                                            <td><?php echo e($user->email); ?></td>
                                                            <td><?php echo e($user->contact_no); ?></td>
                                                            <td><?php echo e($user->role_name ?? '-'); ?></td>
                                                            <td><?php echo e($user->designation_name ?? '-'); ?></td>
                                                            <td><?php echo e($user->department_name ?? '-'); ?></td>
                                                            <td>
                                                                <form
                                                                    action="<?php echo e(route('users.updateStaffStatus', [$user->id])); ?>"
                                                                    method="post">
                                                                    <?php echo csrf_field(); ?>
                                                                    <div class="form-check form-switch mb-0">
                                                                        <input class="form-check-input staff-status-toggle"
                                                                            type="checkbox" role="switch"
                                                                            id="switchCheckDefault" name="is_active"
                                                                            data-id="<?php echo e($user->id); ?>"
                                                                            <?php echo e($user->is_active == 1 ? 'checked' : ''); ?>>
                                                                    </div>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Specialization</th>
                                                        <th>Designation</th>
                                                        <th>Department</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <th scope="row"><?php echo e($loop->iteration); ?></th>
                                                            <td><?php echo e($user->name); ?>&nbsp;<?php echo e($user->surname); ?></td>
                                                            <td><?php echo e($user->email); ?></td>
                                                            <td><?php echo e($user->contact_no); ?></td>
                                                            <td><?php echo e($user->specialization ?? '-'); ?></td>
                                                            <td><?php echo e($user->designation_name ?? '-'); ?></td>
                                                            <td><?php echo e($user->department_name ?? '-'); ?></td>
                                                            <td>
                                                                <form
                                                                    action="<?php echo e(route('users.updateDrStatus', [$user->id])); ?>"
                                                                    method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <div class="form-check form-switch mb-0">
                                                                        <input class="form-check-input status-toggle"
                                                                            type="checkbox" role="switch"
                                                                            id="switchCheckDefault_<?php echo e($user->id); ?>"
                                                                            name="is_active" data-id="<?php echo e($user->id); ?>"
                                                                            <?php echo e($user->is_active == 1 ? 'checked' : ''); ?>>
                                                                    </div>

                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.status-toggle').forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
    <script>
        document.querySelectorAll('.staff-status-toggle').forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/users.blade.php ENDPATH**/ ?>