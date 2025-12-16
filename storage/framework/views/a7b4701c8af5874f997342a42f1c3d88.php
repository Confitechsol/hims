<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 mt-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #750096">Supplier List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="ti ti-plus"></i> Add Supplier
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
                            <th>Supplier Name</th>
                            <th>Supplier Contact</th>
                            <th>Contact Person Name</th>
                            <th>Contact Person Phone</th>
                            <th>Drug License Number</th>
                            <th>Address</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($supplier->supplier); ?></td>
                            <td><?php echo e($supplier->contact); ?></td>
                            <td><?php echo e($supplier->supplier_person); ?></td>
                            <td><?php echo e($supplier->supplier_person_contact); ?></td>
                            <td><?php echo e($supplier->supplier_drug_licence); ?></td>
                            <td><?php echo e($supplier->address); ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editSupplier(<?php echo e(json_encode($supplier)); ?>)">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <form action="<?php echo e(route('setup.medicine-supplier.destroy', $supplier->id)); ?>" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">
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
                            <td colspan="7" class="text-center">No suppliers found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Add Supplier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('setup.medicine-supplier.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" name="supplier" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier Contact</label>
                            <input type="text" name="contact" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Person Name</label>
                            <input type="text" name="supplier_person" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Person Phone</label>
                            <input type="text" name="supplier_person_contact" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Drug License Number</label>
                            <input type="text" name="supplier_drug_licence" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
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

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #CB6CE6; color: white;">
                <h5 class="modal-title">Edit Supplier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSupplierForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" name="supplier" id="edit_supplier_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier Contact</label>
                            <input type="text" name="contact" id="edit_supplier_contact" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Person Name</label>
                            <input type="text" name="supplier_person" id="edit_contact_person_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Person Phone</label>
                            <input type="text" name="supplier_person_contact" id="edit_contact_person_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Drug License Number</label>
                            <input type="text" name="supplier_drug_licence" id="edit_drug_license_number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" id="edit_address" class="form-control">
                        </div>
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
function editSupplier(supplier) {
    document.getElementById('edit_supplier_name').value = supplier.supplier;
    document.getElementById('edit_supplier_contact').value = supplier.contact || '';
    document.getElementById('edit_contact_person_name').value = supplier.supplier_person || '';
    document.getElementById('edit_contact_person_phone').value = supplier.supplier_person_contact || '';
    document.getElementById('edit_drug_license_number').value = supplier.supplier_drug_licence || '';
    document.getElementById('edit_address').value = supplier.address || '';
    document.getElementById('editSupplierForm').action = "<?php echo e(url('setup/medicine-supplier/update')); ?>/" + supplier.id;
    new bootstrap.Modal(document.getElementById('editSupplierModal')).show();
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\medicine_supplier.blade.php ENDPATH**/ ?>