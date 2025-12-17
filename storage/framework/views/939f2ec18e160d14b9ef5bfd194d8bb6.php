<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Shift</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_shift"><i
                                                        class="ti ti-plus me-1"></i>Add Shift</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_shift" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Shift

                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="<?php echo e(route('shift.store')); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="name" class="form-label">Name
                                                                            <span class="text-danger">*</span></label>
                                                                        <input type="text" name="shift_name" id="name"
                                                                            class="form-control" required />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="time_from" class="form-label">Time From
                                                                           </label>
                                                                        <input type="time" name="time_from" id="time_from"
                                                                            class="form-control"  />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="time_to" class="form-label">Time To
                                                                           </label>
                                                                        <input type="time" name="time_to" id="time_to"
                                                                            class="form-control"  />
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
                                                    <th>Name</th>
                                                    <th>Time From</th>
                                                    <th>Time To</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td>
                                                            <span class="mb-0 fs-14 fw-semibold"><?php echo e($shift->name); ?></span>
                                                        </td>
                                                        <td><?php echo e(\Carbon\Carbon::parse($shift->start_time)->format('h:i A')); ?></td>
                                                        <td><?php echo e(\Carbon\Carbon::parse($shift->end_time)->format('h:i A')); ?></td>
                                                        <td>
                                                            <a href="javascript:void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                            data-shift-id="<?php echo e($shift->id); ?>"
                                                            data-shift-name="<?php echo e($shift->name); ?>"
                                                            data-start-time="<?php echo e($shift->start_time); ?>"
                                                            data-end-time="<?php echo e($shift->end_time); ?>"
                                                            onclick="openShiftEditModal(this)">
                                                            <i class="ti ti-pencil"></i>
                                                            </a>

                                                            <a href="javascript:void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                            onclick="deleteShift(<?php echo e($shift->id); ?>)">
                                                            <i class="ti ti-trash"></i>
                                                            </a>
                                                            <form id="deleteShiftForm" method="POST" style="display:none;">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">No shifts available</td>
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
<!-- Example Modal -->
<div class="modal fade" id="editShiftModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Shift</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editShiftForm" method="POST" action="">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Shift Name</label>
            <input type="text" class="form-control" name="shift_name" id="editShiftName" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Start Time</label>
            <input type="time" class="form-control" name="start_time" id="editShiftStartTime" required>
          </div>
          <div class="mb-3">
            <label class="form-label">End Time</label>
            <input type="time" class="form-control" name="end_time" id="editShiftEndTime" required>
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
    // JS Function
    function openShiftEditModal(el) {
        let id = el.getAttribute("data-shift-id");
        let name = el.getAttribute("data-shift-name");
        let startTime = el.getAttribute("data-start-time");
        let endTime = el.getAttribute("data-end-time");

        // Fill modal inputs
        document.getElementById("editShiftName").value = name;
        document.getElementById("editShiftStartTime").value = startTime;
        document.getElementById("editShiftEndTime").value = endTime;

        // Update form action dynamically
        let form = document.getElementById("editShiftForm");
        form.action = "<?php echo e(url('shift/update/')); ?>/" + id; // Adjust route if using named routes

        // Show modal
        let modal = new bootstrap.Modal(document.getElementById("editShiftModal"));
        modal.show();
    }
</script>
<script>
    function deleteShift(id) {
        if (confirm("Are you sure you want to delete this appointment priority?")) {
            let form = document.getElementById("deleteShiftForm");
            form.action = "<?php echo e(url('shift/destroy')); ?>/" + id; // matches your route
            form.submit();
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\shift.blade.php ENDPATH**/ ?>