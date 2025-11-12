<?php $__env->startSection('content'); ?>

        <div class="row justify-content-center">
            
            <div class="col-md-11">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Role List</h5>
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
                                                    <input type="text" class="form-control shadow-sm" placeholder="Search">

                                                </div>
                                                <div class="text-end d-flex">
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                        data-bs-toggle="modal" data-bs-target="#roleModal"><i
                                                            class="ti ti-plus me-1"></i>Create Role</a>
                                                </div>
                                                <!-- Modal -->
                                                <!-- <div class="modal fade" id="add_specialization" tabindex="-1"
                                                    aria-labelledby="addSpecializationLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header rounded-0"
                                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                <h5 class="modal-title" id="addSpecializationLabel">Create
                                                                    Role</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="<?php echo e(route('roles.store')); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <div class="mb-3">
                                                                        <label for="roleName" class="form-label">Role
                                                                            Name</label>
                                                                        <input id="name" name= "name" class="form-control" />
                                                                    </div>
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Save Role</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <!-- Role Modal -->
                                                <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header rounded-0"
                                                                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                <h5 class="modal-title" id="roleModalLabel">Create Role</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="roleForm" method="POST" action="<?php echo e(route('roles.store')); ?>">
                                                                    <?php echo csrf_field(); ?>
                                                                    <input type="hidden" name="_method" id="roleFormMethod" value="POST">
                                                                    <div class="mb-3">
                                                                        <label for="roleName" class="form-label">Role Name</label>
                                                                        <input id="roleName" name="name" class="form-control" required />
                                                                    </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Save Role</button>
                                                            </div>
                                                                </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Role</th>
                                                            <th>Type</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                         <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <th scope="row"><?php echo e($index + 1); ?></th>
                                                                <td>
                                                                    <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($role->name); ?></h6>
                                                                </td>
                                                                <td><?php echo e($role->type ?? 'N/A'); ?></td>
                                                                <td>
                                                                    <a href="<?php echo e(route('permissions', $role->id)); ?>" 
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Assign Permission">
                                                                        <i class="ti ti-user-circle"></i>
                                                                    </a>
                                                                    <!-- <a href="javascript:void(0);" onclick="openEditRoleModal(<?php echo e($role->id); ?>, '<?php echo e($role->name); ?>')" 
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Edit">
                                                                        <i class="ti ti-pencil"></i>
                                                                    </a> -->
                                                                    <a href="javascript:void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill"
                                                                        data-role-id="<?php echo e($role->id); ?>" data-role-name="<?php echo e($role->name); ?>" onclick="openRoleModal(this)">
                                                                        <i class="ti ti-pencil"></i>
                                                                    </a>
                                                                    <a href="javascript:void(0);" 
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Delete"
                                                                    onclick="if(confirm('Are you sure you want to delete this role?')) { document.getElementById('delete-role-<?php echo e($role->id); ?>').submit(); }">
                                                                        <i class="ti ti-trash"></i>
                                                                    </a>

                                                                    <form id="delete-role-<?php echo e($role->id); ?>" 
                                                                        action="<?php echo e(route('roles.destroy', $role->id)); ?>" 
                                                                        method="POST" style="display: none;">
                                                                        <?php echo csrf_field(); ?>
                                                                        <?php echo method_field('DELETE'); ?>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                </div> <!-- end col -->

                            </div>
                            <!-- <hr> -->
                            <!-- <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i> Save Settings
                                </button>
                            </div> -->
                        
                    </div>
                </div>
            </div>
        </div>


    <!-- Bootstrap 5 JS bundle (includes Popper) -->
<script>
function openRoleModal(element) {
    const roleId = element.getAttribute('data-role-id');
    const roleName = element.getAttribute('data-role-name');
    
    const modalTitle = document.getElementById('roleModalLabel');
    const form = document.getElementById('roleForm');
    const nameInput = document.getElementById('roleName');

    if (roleId) {
        // Edit mode
        modalTitle.innerText = "Edit Role";
        // form.action = `hims/public/roles/${roleId}`; // Update route
        form.action = "<?php echo e(url('roles')); ?>/update/" + roleId;
        nameInput.value = roleName;

        // Add PUT method spoof
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
    } else {
        // Create mode
        modalTitle.innerText = "Create Role";
        form.action = "<?php echo e(route('roles.store')); ?>";
        nameInput.value = "";
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
    }

    // Show modal
    const roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
    roleModal.show();
}

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/role.blade.php ENDPATH**/ ?>