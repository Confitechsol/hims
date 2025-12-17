<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Symptoms Type List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_symptom_type"><i
                                                        class="ti ti-plus me-1"></i>Add Symptoms Type</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_symptom_type" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Symptoms
                                                                Type
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="<?php echo e(route('symptoms-type.store')); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>

                                                                <div id="symptom_type_fields">
                                                                    <div class="row gy-3 symptom-type-row mb-2">
                                                                        <div class="col-md-11">
                                                                            <label for="symptoms_type"
                                                                                class="form-label">Symptoms Type <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="symptoms_type[]"
                                                                                id="symptoms_type" class="form-control" />
                                                                        </div>

                                                                        <div class="col-md-1 d-flex align-items-end">
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-btn"
                                                                                style="display:none;"><i
                                                                                    class="ti ti-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Add button -->
                                                                <div class="mt-3">
                                                                    <button type="button" id="addBtn"
                                                                        class="btn btn-primary">Add</button>
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
                                                    <th>Symptoms Type</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $symptomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td>
                <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($type->symptoms_type); ?></h6>
            </td>
            <td>
                <a href="javascript:void(0);"
                   onclick="openEditSymptomTypeModal(this)"
                   data-id="<?php echo e($type->id); ?>"
                   data-name="<?php echo e($type->symptoms_type); ?>"
                   class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                   <i class="ti ti-pencil"></i>
                </a>
                <a href="javascript:void(0);"
                   onclick="deleteSymptomType(<?php echo e($type->id); ?>)"
                   class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                   <i class="ti ti-trash"></i>
                </a>
            </td>
        </tr>
        <form id="deleteSymptomTypeForm" method="POST" style="display:none;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>

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
<div class="modal fade" id="editSymptomTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title">Edit Symptoms Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSymptomTypeForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_symptoms_type" class="form-label">Symptoms Type</label>
                        <input type="text" name="symptoms_type" id="edit_symptoms_type" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        const addBtn = document.getElementById("addBtn");
        const operationFields = document.getElementById("symptom_type_fields");

        addBtn.addEventListener("click", function () {
            // Clone the first row
            let firstRow = operationFields.querySelector(".symptom-type-row");
            let newRow = firstRow.cloneNode(true);

            // Clear input values
            newRow.querySelectorAll("input, select").forEach(el => el.value = "");

            // Show remove button
            newRow.querySelector(".remove-btn").style.display = "inline-block";

            // Append new row
            operationFields.appendChild(newRow);

            // Add remove functionality
            newRow.querySelector(".remove-btn").addEventListener("click", function () {
                newRow.remove();
            });
        });
    </script>
<script>
    function openEditSymptomTypeModal(el) {
    let id = el.getAttribute("data-id");
    let name = el.getAttribute("data-name");

    document.getElementById("edit_symptoms_type").value = name;

    let form = document.getElementById("editSymptomTypeForm");
    form.action = "<?php echo e(url('symptoms-type/update')); ?>/" + id;

    let modal = new bootstrap.Modal(document.getElementById("editSymptomTypeModal"));
    modal.show();
}

function deleteSymptomType(id) {
    if (confirm("Are you sure you want to delete this Symptoms Type?")) {
        let form = document.getElementById("deleteSymptomTypeForm");
        form.action = "<?php echo e(url('symptoms-type/destroy')); ?>/" + id;
        form.submit();
    }
}

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\symptoms_type.blade.php ENDPATH**/ ?>