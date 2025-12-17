<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Appointment Priority List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_priority"><i
                                                        class="ti ti-plus me-1"></i>Add Priority</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_priority" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Priority
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="<?php echo e(route('appointment-priority.store')); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>

                                                                <div id="priority_fields">
                                                                    <div class="row gy-3 priority-row mb-2">

                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-11">
                                                                            <label for="priority"
                                                                                class="form-label">Priority <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="priority[]"
                                                                                id="priority" class="form-control" />
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
                                                    <th>Priority</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($priority->appoint_priority); ?></h6>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                onclick="openAppointPriorityModal(this)"
                                                                data-priority-id="<?php echo e($priority->id); ?>"
                                                                data-priority-name="<?php echo e($priority->appoint_priority); ?>">
                                                                <i class="ti ti-pencil"></i>
                                                            </a>

                                                            <a href="javascript:void(0);" 
                                                                onclick="deletePriority(<?php echo e($priority->id); ?>)"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                <i class="ti ti-trash"></i>
                                                            </a>
                                                            <form id="deletePriorityForm" method="POST" style="display:none;">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="2" class="text-center">No records found</td>
                                                    </tr>
                                                <?php endif; ?>
                                               
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

    <script>
        const addBtn = document.getElementById("addBtn");
        const operationFields = document.getElementById("priority_fields");

        addBtn.addEventListener("click", function () {
            // Clone the first row
            let firstRow = operationFields.querySelector(".priority-row");
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
<div class="modal fade" id="editAppointmentPriorityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Appointment Priority</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editAppointmentPriorityForm" method="POST" action="">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Priority Name</label>
                <input type="text" class="form-control" name="priority" id="editAppointmentPriorityName" required>
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
    function openAppointPriorityModal(el) 
    {
        let id = el.getAttribute("data-priority-id");
        let name = el.getAttribute("data-priority-name");

        // Fill modal inputs
        document.getElementById("editAppointmentPriorityName").value = name;

        // Update form action dynamically
        let form = document.getElementById("editAppointmentPriorityForm");
        form.action = "<?php echo e(url('appointment-priority/update')); ?>/" + id; // route to update

        // Show modal
        let modal = new bootstrap.Modal(document.getElementById("editAppointmentPriorityModal"));
        modal.show();
    }

</script>
<script>
    function deletePriority(id) {
        if (confirm("Are you sure you want to delete this appointment priority?")) {
            let form = document.getElementById("deletePriorityForm");
            form.action = "<?php echo e(url('appointment-priority/destroy')); ?>/" + id; // matches your route
            form.submit();
        }
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\appointment_priority.blade.php ENDPATH**/ ?>