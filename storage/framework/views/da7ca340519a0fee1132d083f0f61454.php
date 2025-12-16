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
                            data-bs-toggle="modal" data-bs-target="#add_shift">
                            <i class="ti ti-plus me-1"></i> Add Shift
                        </a>

                        <!-- Add Shift Modal -->
                        <div class="modal fade" id="add_shift" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content">
                                    <form method="POST" action="<?php echo e(route('dutyroster.addShift')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Add Duty Roster Shift</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="shift_name" class="form-label">Shift Name</label>
                                                <input type="text" name="shift_name" id="shift_name" class="form-control" required>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="shift_start" class="form-label">Shift Start</label>
                                                    <input type="time" name="shift_start" id="shift_start" class="form-control" required>
                                                </div>
                                                <div class="col">
                                                    <label for="shift_end" class="form-label">Shift End</label>
                                                    <input type="time" name="shift_end" id="shift_end" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <label for="shift_hour" class="form-label">Shift Hour</label>
                                                <input type="text" name="shift_hour" id="shift_hour" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save Shift</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Add Shift Modal -->

                    </div>
                </div>
            </div>

            <div class="card-body">
                <?php if($shifts->isEmpty()): ?>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php $__empty_1 = true; $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($shift->shift_name); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($shift->shift_start)->format('h:i A')); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($shift->shift_end)->format('h:i A')); ?></td>
                                        <td><?php echo e($shift->shift_hour); ?></td>
                                        <td>
                                            <a href="javascript:void(0);" 
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                onclick="openEditModal(<?php echo e($shift->id); ?>, '<?php echo e($shift->shift_name); ?>', '<?php echo e($shift->shift_start); ?>', '<?php echo e($shift->shift_end); ?>', '<?php echo e($shift->shift_hour); ?>')">
                                                <i class="ti ti-pencil"></i>
                                            </a>

                                            <a href="javascript:void(0);" 
                                                onclick="confirmDelete('<?php echo e(route('dutyroster.destroyShift', $shift->id)); ?>')" 
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No shifts available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<!-- Edit Shift Modal -->
<div class="modal fade" id="edit_shift" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <form id="editShiftForm" method="POST" >
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="shift_id" id="edit_shift_id">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Duty Roster Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_shift_name" class="form-label">Shift Name</label>
                        <input type="text" name="shift_name" id="edit_shift_name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="edit_shift_start" class="form-label">Shift Start</label>
                            <input type="time" name="shift_start" id="edit_shift_start" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="edit_shift_end" class="form-label">Shift End</label>
                            <input type="time" name="shift_end" id="edit_shift_end" class="form-control" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="edit_shift_hour" class="form-label">Shift Hour</label>
                        <input type="text" name="shift_hour" id="edit_shift_hour" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Shift</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const startInput = document.getElementById('shift_start');
        const endInput = document.getElementById('shift_end');
        const hourInput = document.getElementById('shift_hour');

        function calculateShiftHours() {
            const startTime = startInput.value;
            const endTime = endInput.value;

            if (startTime && endTime) {
                const start = new Date(`1970-01-01T${startTime}:00`);
                const end = new Date(`1970-01-01T${endTime}:00`);

                let diff = (end - start) / (1000 * 60 * 60); // difference in hours

                // If end time is earlier (e.g., overnight shift)
                if (diff < 0) {
                    diff += 24;
                }

                // Round to 2 decimal places if needed
                hourInput.value = diff.toFixed(2);
            }
        }

        startInput.addEventListener('change', calculateShiftHours);
        endInput.addEventListener('change', calculateShiftHours);
    });
</script> -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('shift_start');
    const endInput = document.getElementById('shift_end');
    const hourInput = document.getElementById('shift_hour');

    function calculateShiftHours() {
        const startTime = startInput.value.trim();
        const endTime = endInput.value.trim();

        console.log("🕒 Start:", startTime, "End:", endTime);

        if (startTime && endTime) {
            // Ensure both are valid times (HH:mm:ss or HH:mm)
            let startParts = startTime.split(':').map(Number);
            let endParts = endTime.split(':').map(Number);

            // Normalize to HH:mm:ss
            if (startParts.length === 2) startParts.push(0);
            if (endParts.length === 2) endParts.push(0);

            // Create date objects for same base date
            const start = new Date(1970, 0, 1, startParts[0], startParts[1], startParts[2]);
            const end = new Date(1970, 0, 1, endParts[0], endParts[1], endParts[2]);

            let diff = (end - start) / (1000 * 60 * 60);

            // Handle overnight shifts (e.g. 22:00 to 06:00)
            if (diff < 0) diff += 24;

            console.log("✅ Calculated Hours:", diff);

            // Convert to HH:mm:ss format
            let totalSeconds = Math.round(diff * 3600);
            let hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
            let minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
            let seconds = String(totalSeconds % 60).padStart(2, '0');

            hourInput.value = `${hours}:${minutes}:${seconds}`;
        }
    }

    startInput.addEventListener('change', calculateShiftHours);
    endInput.addEventListener('change', calculateShiftHours);
});
</script>


<!-- End Edit Shift Modal -->

<script>
   function openEditModal(id, name, start, end, hour) {
    console.log({ id, name, start, end, hour });

    let hourValue = 0;
    if (hour) {
        const parts = hour.split(':');
        hourValue = parseInt(parts[0]) + parseInt(parts[1]) / 60;
    }
    // Populate modal fields
    document.getElementById('edit_shift_id').value = id;
    document.getElementById('edit_shift_name').value = name;
    document.getElementById('edit_shift_start').value = start;
    document.getElementById('edit_shift_end').value = end;
    document.getElementById('edit_shift_hour').value = hourValue; // corrected ID

    // Dynamically set form action
    let form = document.getElementById('editShiftForm');
    form.action = "<?php echo e(route('dutyroster.updateShift', ':id')); ?>".replace(':id', id);

    // Show modal
    $('#edit_shift').modal('show'); // corrected modal ID
    }


    function confirmDelete(url) {
        Swal.fire({
            title: "Are you sure?",
            text: "This shift will be deleted permanently.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // ✅ Create and submit a form using DELETE method
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '<?php echo e(csrf_token()); ?>';

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';

                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }


</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('edit_shift_start');
    const endInput = document.getElementById('edit_shift_end');
    const hourInput = document.getElementById('edit_shift_hour');

    function calculateShiftHours() {
        const startTime = startInput.value.trim();
        const endTime = endInput.value.trim();

        console.log("🕒 Start:", startTime, "End:", endTime);

        if (startTime && endTime) {
            // Ensure both are valid times (HH:mm:ss or HH:mm)
            let startParts = startTime.split(':').map(Number);
            let endParts = endTime.split(':').map(Number);

            // Normalize to HH:mm:ss
            if (startParts.length === 2) startParts.push(0);
            if (endParts.length === 2) endParts.push(0);

            // Create date objects for same base date
            const start = new Date(1970, 0, 1, startParts[0], startParts[1], startParts[2]);
            const end = new Date(1970, 0, 1, endParts[0], endParts[1], endParts[2]);

            let diff = (end - start) / (1000 * 60 * 60);

            // Handle overnight shifts (e.g. 22:00 to 06:00)
            if (diff < 0) diff += 24;

            console.log("✅ Calculated Hours:", diff);

            // Convert to HH:mm:ss format
            let totalSeconds = Math.round(diff * 3600);
            let hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
            let minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
            let seconds = String(totalSeconds % 60).padStart(2, '0');

            hourInput.value = `${hours}:${minutes}:${seconds}`;
        }
    }

    startInput.addEventListener('change', calculateShiftHours);
    endInput.addEventListener('change', calculateShiftHours);
});
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\duty-roster\roster_shift.blade.php ENDPATH**/ ?>