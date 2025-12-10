<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Pathology Parameter List</h5>
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
                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_pathology_parameter"><i
                                                        class="ti ti-plus me-1"></i>Add Pathology Parameter</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_pathology_parameter" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Pathology Parameter
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="<?php echo e(route('pathology-parameter.store')); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="parameter_name" class="form-label">Parameter
                                                                            Name<span class="text-danger">*</span></label>
                                                                        <input type="text" name="parameter_name" id="parameter_name"
                                                                            class="form-control" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="ref_range_from"
                                                                            class="form-label">Reference Range<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="ref_range_from"
                                                                            id="ref_range_from" class="form-control"
                                                                            placeholder="From" required>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="ref_range_to"
                                                                            class="form-label">Reference Range<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="ref_range_to"
                                                                            id="ref_range_to" class="form-control"
                                                                            placeholder="To" required>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="unit" class="form-label">Unit<span
                                                                                class="text-danger">*</span></label>
                                                                        <select name="unit_id" id="unit_id" class="form-select" required>
                                                                            <option value="">Select Unit</option>
                                                                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->unit_name); ?></option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="description"
                                                                            class="form-label">Description</label>
                                                                        <textarea name="description" id="description"
                                                                            class="form-control"></textarea>
                                                                    </div>

                                                                </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                                </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Parameter Name</th>
                                                    <th>Reference Range</th>
                                                    <th>Unit</th>
                                                    <th>Description</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                                <?php $__currentLoopData = $parameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parameter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($parameter->parameter_name); ?></h6>
                                                        </td>
                                                        <td><?php echo e($parameter->reference_range); ?></td> <!-- if you have this column -->
                                                        <td><?php echo e($parameter->unitRelation ? $parameter->unitRelation->unit_name : '-'); ?></td> <!-- e.g., category name or type -->
                                                        <td><?php echo e($parameter->description); ?></td>
                                                        <td>
                                                            <!-- Edit Button -->
                                                            <a href="javascript:void(0);" 
                                                            onclick="openPathologyParameterModal(this)"
                                                            data-id="<?php echo e($parameter->id); ?>"
                                                            data-name="<?php echo e($parameter->parameter_name); ?>"
                                                            data-range_from="<?php echo e($parameter->range_from); ?>"
                                                            data-range_to="<?php echo e($parameter->range_to); ?>"
                                                            data-unit="<?php echo e($parameter->unit_id); ?>"
                                                            data-description="<?php echo e($parameter->description); ?>"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i>
                                                            </a>

                                                            <!-- Delete Button -->
                                                            <a href="javascript:void(0);"
                                                            onclick="deletePathologyParameter(<?php echo e($parameter->id); ?>)"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i>
                                                            </a>

                                                            <form id="deletePathologyParameterForm" method="POST" style="display:none;">
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

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPathologyParameterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pathology Parameter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPathologyParameterForm" method="POST" >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="id" id="edit_parameter_id">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Parameter Name</label>
                            <input type="text" name="parameter_name" id="edit_parameter_name" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Reference Range From</label>
                                <input type="text" name="ref_range_from" id="edit_range_from" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Reference Range To</label>
                                <input type="text" name="ref_range_to" id="edit_range_to" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" id="edit_unit_id" class="form-select" required>
                                <option value="">-- Select Unit --</option>
                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->unit_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    function openPathologyParameterModal(el) {
    let id = el.getAttribute("data-id");
    let name = el.getAttribute("data-name");
    let range_from = el.getAttribute("data-range_from");
    let range_to = el.getAttribute("data-range_to");
    let unit = el.getAttribute("data-unit");
    let description = el.getAttribute("data-description");

    // Fill modal inputs
    document.getElementById("edit_parameter_id").value = id;
    document.getElementById("edit_parameter_name").value = name;
    document.getElementById("edit_range_from").value = range_from || "";
    document.getElementById("edit_range_to").value = range_to || "";
    document.getElementById("edit_unit_id").value = unit || "";
    document.getElementById("edit_description").value = description || "";

    // Update form action dynamically
    let form = document.getElementById("editPathologyParameterForm");
    form.action = "<?php echo e(url('pathology-parameter/update')); ?>/" + id;

    // Show modal
    let modal = new bootstrap.Modal(document.getElementById("editPathologyParameterModal"));
    modal.show();
}

</script>
<script>
    function deletePathologyParameter(id) {
        if (confirm("Are you sure you want to delete this pathology parameter?")) {
            let form = document.getElementById("deletePathologyParameterForm");
            form.action = "<?php echo e(url('pathology-parameter/destroy')); ?>/" + id; // your delete route
            form.submit();
        }
    }

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/pathology_parameter.blade.php ENDPATH**/ ?>