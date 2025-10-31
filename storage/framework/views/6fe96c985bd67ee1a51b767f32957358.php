

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #750096">Unit List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnitModal">
                    <i class="ti ti-plus"></i> Add Unit
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Database ID</th>
                            <th>Unit Name</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($unit->id); ?></td>
                            <td><?php echo e($unit->unit_name); ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editUnit(<?php echo e($unit->id); ?>, '<?php echo e($unit->unit_name); ?>')">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <form action="<?php echo e(route('medicine-unit.destroy', $unit->id)); ?>" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center">No units found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Add Unit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('medicine-unit.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="unit_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Unit Modal -->
<div class="modal fade" id="editUnitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Edit Unit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUnitForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="unit_name" id="edit_unit_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUnit(id, name) {
    document.getElementById('edit_unit_name').value = name;
    document.getElementById('editUnitForm').action = "<?php echo e(url('setup/medicine-unit/update')); ?>/" + id;
    new bootstrap.Modal(document.getElementById('editUnitModal')).show();
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp-8.2\htdocs\hims\resources\views/admin/setup/medicine_unit.blade.php ENDPATH**/ ?>