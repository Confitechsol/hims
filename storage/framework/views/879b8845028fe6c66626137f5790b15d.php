

<?php $__env->startSection('content'); ?>
<div class="row px-5 py-4">
    <div class="col-12 d-flex">

        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                    <div>
                        <h4 class="fw-bold mb-0">Duty Roster List Details</h4>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                            data-bs-toggle="modal" data-bs-target="#add_roster">
                            <i class="ti ti-plus me-1"></i> Add Roster
                        </a>

                        <!-- Add Shift Modal -->
                        <!-- Add Roster Modal -->
                            <div class="modal fade" id="add_roster" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content">
                                        <form method="POST" action="<?php echo e(route('dutyroster.addRoster')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Add Duty Roster</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                
                                                <div class="mb-3">
                                                    <label for="duty_roster_shift_id" class="form-label">Select Shift <small class="req">*</small></label>
                                                    <select name="duty_roster_shift_id" id="duty_roster_shift_id" class="form-control" required>
                                                        <option value="">Select Shift</option>
                                                        <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($shift->id); ?>">
                                                                <?php echo e($shift->shift_name); ?> (<?php echo e(date('h:i A', strtotime($shift->shift_start))); ?> - <?php echo e(date('h:i A', strtotime($shift->shift_end))); ?>)
                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>

                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="duty_roster_start_date" class="form-label">Start Date <small class="req">*</small></label>
                                                        <input type="date" name="duty_roster_start_date" id="duty_roster_start_date" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="duty_roster_end_date" class="form-label">End Date <small class="req">*</small></label>
                                                        <input type="date" name="duty_roster_end_date" id="duty_roster_end_date" class="form-control" required>
                                                    </div>
                                                </div>

                                                
                                                <div class="mb-3">
                                                    <label for="duty_roster_total_day" class="form-label">Total Days</label>
                                                    <input type="number" name="duty_roster_total_day" id="duty_roster_total_day" class="form-control" readonly>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Roster</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <!-- End Add Roster Modal -->

                        <!-- End Add Shift Modal -->

                    </div>
                </div>
            </div>

            <div class="card-body">
                <?php if($rosters->isEmpty()): ?>
                    <p class="text-center">No roster details found.</p>
                <?php else: ?>
                    <div class="table-responsive table-nowrap">
                        <table class="table border">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>Shift Name</th>
                                    <th>Shift Start</th>
                                    <th>Shift End</th>
                                    <th>Shift Hours</th>
                                    <th>Roster Start - End</th>
                                    <th>Total Days</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php $__currentLoopData = $rosters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($roster->dutyRosterShift->shift_name); ?></td>
                                        <td><?php echo e(date('h:i A', strtotime($roster->dutyRosterShift->shift_start))); ?></td>
                                        <td><?php echo e(date('h:i A', strtotime($roster->dutyRosterShift->shift_end))); ?></td>
                                        <td><?php echo e($roster->dutyRosterShift->shift_hour); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($roster->duty_roster_start_date)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($roster->duty_roster_end_date)->format('d/m/Y')); ?></td>
                                        <td><?php echo e($roster->duty_roster_total_day); ?></td>
                                        <td>
                                            <!-- <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewShift<?php echo e($roster->id); ?>">
                                                View Shift
                                            </button> -->
                                            <a href="javascript:void(0);" 
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                onclick="openEditModal(<?php echo e($roster->id); ?>, <?php echo e($roster->duty_roster_shift_id); ?>, '<?php echo e($roster->duty_roster_start_date); ?>', '<?php echo e($roster->duty_roster_end_date); ?>', <?php echo e($roster->duty_roster_total_day); ?>)">
                                                    <i class="ti ti-pencil"></i>
                                            </a>

                                            <a href="javascript:void(0);" 
                                                onclick="confirmDelete('<?php echo e(route('dutyroster.destroy', $roster->id)); ?>')" 
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                            <form id="deleteForm" method="POST" style="display: none;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>

                                            <!-- Edit Roster Modal -->
                                                <div class="modal fade" id="editRosterModal" tabindex="-1" aria-hidden="true" style="padding-left: 0px;">
                                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                                        <div class="modal-content">
                                                            <form id="editRosterForm" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('PUT'); ?>
                                                                <div class="modal-header bg-primary text-white">
                                                                    <h5 class="modal-title">Edit Duty Roster</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <input type="hidden" id="edit_roster_id" name="id">

                                                                    <div class="mb-3">
                                                                        <label for="edit_duty_roster_shift_id" class="form-label">Select Shift</label>
                                                                        <select id="edit_duty_roster_shift_id" name="duty_roster_shift_id" class="form-control" required>
                                                                            <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($shift->id); ?>"><?php echo e($shift->shift_name); ?></option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="row">

                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">Start Date</label>
                                                                            <input type="date" id="edit_duty_roster_start_date" name="duty_roster_start_date" class="form-control" required>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">End Date</label>
                                                                            <input type="date" id="edit_duty_roster_end_date" name="duty_roster_end_date" class="form-control" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label">Total Days</label>
                                                                        <input type="number" id="edit_duty_roster_total_day" name="duty_roster_total_day" class="form-control" required>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Update Roster</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- End Edit Roster Modal -->

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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const start = document.getElementById('duty_roster_start_date');
        const end = document.getElementById('duty_roster_end_date');
        const total = document.getElementById('duty_roster_total_day');

        function calculateDays() {
            if (start.value && end.value) {
                const startDate = new Date(start.value);
                const endDate = new Date(end.value);
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                total.value = diffDays;
            }
        }

        start.addEventListener('change', calculateDays);
        end.addEventListener('change', calculateDays);
    });
</script>
<script>
function openEditModal(id, shiftId, startDate, endDate, totalDays) {
    // Fill form fields
    document.getElementById('edit_roster_id').value = id;
    document.getElementById('edit_duty_roster_shift_id').value = shiftId;
    document.getElementById('edit_duty_roster_start_date').value = startDate;
    document.getElementById('edit_duty_roster_end_date').value = endDate;
    document.getElementById('edit_duty_roster_total_day').value = totalDays;

    // Update form action URL dynamically
    let form = document.getElementById('editRosterForm');
    form.action = "<?php echo e(route('dutyroster.update', ':id')); ?>".replace(':id', id);


    // Show modal
    new bootstrap.Modal(document.getElementById('editRosterModal')).show();
}
</script>
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script>

function confirmDelete(url) {
    Swal.fire({
        title: "Are you sure?",
        text: "This roster will be marked as deleted (soft delete).",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit a hidden form dynamically
            let form = document.createElement('form');
            form.action = url;
            form.method = 'POST';

            // Add CSRF token
            let csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrf);

            // Spoof DELETE method
            let method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();
        }
    });
}


</script>
<?php if(session('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: '<?php echo e(session('success')); ?>',
    showConfirmButton: false,
    timer: 1500
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/duty-roster/roster_list_details.blade.php ENDPATH**/ ?>