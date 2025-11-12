<?php $__env->startSection('content'); ?>

    <style>
        .stepradiolist {
            margin-top: 0;
        }

        .stepradiolist li label>input {
            display: none;
        }

        .stepradiolist li label>input:checked+.stepimage {
            color: #8616a6;
            border: 2px solid #8616a6;
            background: #f9e6ff;
        }

        .stepradiolist li label .stepimage {
            padding: 10px;
            border: 2px solid #cacaca;
            border-radius: 4px;
        }
    </style>
<!-- row start -->
<div class="row px-5 py-4">
    <div class="col-12 d-flex">

        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                    <div>
                        <h4 class="fw-bold mb-0">Staff Duty Roster</h4>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="text-end d-flex">
                            <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                data-bs-toggle="modal" data-bs-target="#add_appointment">
                                <i class="ti ti-plus me-1"></i>Add Roster</a>
                        </div>
                        <!-- First Modal -->
                        <div class="modal fade" id="add_appointment" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <form method="POST" action="<?php echo e(route('dutyroster.assignStaff')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                            <div class="row w-100 align-items-center">
                                                <div class="col-md-7">
                                                    <h4>Assign Roster</h4>
                                                </div>
                                                <div class="col-md-5 text-end">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <div class="row align-items-center gy-3">
                                                    
                                                    <div class="col-sm-12">
                                                        <label>Shift</label>
                                                        <ul class="stepradiolist row gy-3">
                                                            <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li class="col-sm-3">
                                                                    <label>
                                                                        <input type="radio" name="shift_id" value="<?php echo e($shift->id); ?>" class="shift-radio" <?php echo e($loop->first ? 'checked' : ''); ?> >
                                                                        <div class="stepimage">
                                                                            <?php echo e($shift->shift_name); ?><br>
                                                                            <?php echo e(\Carbon\Carbon::parse($shift->shift_start)->format('h:i A')); ?> -
                                                                            <?php echo e(\Carbon\Carbon::parse($shift->shift_end)->format('h:i A')); ?>

                                                                        </div>
                                                                    </label>
                                                                </li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    </div>

                                                    
                                                    <div class="col-sm-12">
                                                        <label>Shift Date <small class="req">*</small></label>
                                                        <select class="form-control" id="duty_roster_list_id" name="duty_roster_list_id" required>
                                                            <option value="">Select Shift First</option>
                                                        </select>
                                                    </div>

                                                    
                                                    <div class="col-sm-12">
                                                        <label>Staff <small class="req">*</small></label>
                                                        <div class="p-2 select2-full-width">
                                                            <select class="form-control select2" id="duty_roster_staff" name="staff_id" required>
                                                                <option value="">Select</option>
                                                                <?php $__currentLoopData = $staffList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($staff->id); ?>">
                                                                        <?php echo e($staff->name); ?> (<?php echo e($staff->employee_id); ?>)
                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Floor</label>
                                                            <select class="form-control" id="duty_roster_floor" name="floor_id">
                                                                <option value="">Select</option>
                                                                <?php $__currentLoopData = $floors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($floor->id); ?>"><?php echo e($floor->name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Department</label>
                                                            <select class="form-control" id="duty_roster_department" name="department_id">
                                                                <option value="">Select</option>
                                                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->department_name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <div class="pull-right">
                                                <button type="submit"  class="btn btn-primary">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="card-body">
    <?php if($rosterSummary->isEmpty()): ?>
        <p class="text-center">No roster assignments found.</p>
    <?php else: ?>
        <div class="table-responsive table-nowrap">
            <table class="table border">
                <thead class="thead-light">
                    <tr>
                        <th>Staff</th>
                        <th>Floor</th>
                        <th>Department</th>
                        <th>Shift</th>
                        <th>Shift Time</th>
                        <th>Start Date - End Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $rosterSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($roster['staff_name']); ?></td>
                            <td><?php echo e($roster['floor']); ?></td>
                            <td><?php echo e($roster['department']); ?></td>
                            <td><?php echo e($roster['shift']); ?></td>
                            <td><?php echo e($roster['shift_time']); ?></td>
                            <td><?php echo e($roster['period']); ?></td>
                            <td class="text-center">
                                <!-- Edit Button -->
                                
                                <a href="javascript:void(0);"
                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill editRosterBtn"
                                    data-id="<?php echo e($roster['id']); ?>"
                                    data-code="<?php echo e($roster['code']); ?>"
                                    data-staff="<?php echo e($roster['staff_id']); ?>"
                                    data-floor="<?php echo e($roster['floor_id'] ?? ''); ?>"
                                    data-department="<?php echo e($roster['department_id'] ?? ''); ?>"
                                    data-shift="<?php echo e(trim($roster['shift'])); ?>"
                                    data-period="<?php echo e($roster['period']); ?>">
                                    <i class="ti ti-pencil"></i>
                                </a>
                                <!-- Delete Button -->
                                <a href="javascript:void(0);"
                                   onclick="confirmDelete('<?php echo e(route('dutyroster.destroyStaffRoster', ['code' => $roster['code'] ?? 0])); ?>')"
                                   class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                    <i class="ti ti-trash"></i>
                                </a>
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
    <div class="modal fade" id="editRosterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form method="POST" id="editRosterForm" action="<?php echo e(route('dutyroster.updateStaffRoster')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <div class="row w-100 align-items-center">
                            <div class="col-md-7">
                                <h4>Edit Roster</h4>
                            </div>
                            <div class="col-md-5 text-end">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row align-items-center gy-3">

                                
                                <div class="col-sm-12">
                                    <label>Shift</label>
                                    <ul class="stepradiolist row gy-0">
                                        <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="col-sm-4">
                                                <label>
                                                    <input type="radio" class="edit_shift" name="shift_id" value="<?php echo e($shift->id); ?>" >
                                                    <div class="stepimage">
                                                        <?php echo e($shift->shift_name); ?><br>
                                                        <?php echo e(date('h:i A', strtotime($shift->shift_start_time))); ?> - <?php echo e(date('h:i A', strtotime($shift->shift_end_time))); ?>

                                                    </div>
                                                </label>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>

                                
                                <div class="col-sm-12">
                                    <label>Shift Date <small class="req">*</small></label>
                                    <select class="form-control" id="edit_duty_roster_list_id" name="duty_roster_list_id" required>
                                        <option value="">Select</option>
                                        <?php $__currentLoopData = $dutyRosterLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($roster->id); ?>">
                                                <?php echo e(date('d/m/Y', strtotime($roster->duty_roster_start_date))); ?> - <?php echo e(date('d/m/Y', strtotime($roster->duty_roster_end_date))); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                
                                <div class="col-sm-12">
                                    <label>Staff <small class="req">*</small></label>
                                    <div class="p-2 select2-full-width">
                                        <select class="form-control select2" id="edit_staff_id" name="staff_id" required>
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $staffList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($staff->id); ?>"><?php echo e($staff->name); ?> (<?php echo e($staff->employee_id); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Floor</label>
                                        <select class="form-control" id="edit_floor_id" name="floor_id">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $floors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($floor->id); ?>"><?php echo e($floor->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="form-control" id="edit_department_id" name="department_id">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->department_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <input type="hidden" id="edit_roster_id" name="id">
                                 
                                <input type="hidden" id="edit_roster_code" name="code">

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "Select an option"
        });
    });
</script>


<script>
    document.addEventListener('click', function (e) {
    const btn = e.target.closest('.editRosterBtn');
    if (!btn) return;

    // Debug
    console.log('Editing roster:', btn.dataset);

    // Fill hidden field
    const rosterId = document.getElementById('edit_roster_id');
    if (rosterId) rosterId.value = btn.dataset.id || '';

    // Fill hidden field
    const rosterCode = document.getElementById('edit_roster_code');
    if (rosterCode) rosterCode.value = btn.dataset.code || '';

    // Prefill selects
    const staffSelect = document.getElementById('edit_staff_id');
    if (staffSelect) staffSelect.value = btn.dataset.staff || '';

    const floorSelect = document.getElementById('edit_floor_id');
    if (floorSelect) floorSelect.value = btn.dataset.floor || '';

    const deptSelect = document.getElementById('edit_department_id');
    if (deptSelect) deptSelect.value = btn.dataset.department || '';

    const periodSelect = document.getElementById('edit_duty_roster_list_id');
    if (periodSelect) {
        Array.from(periodSelect.options).forEach(opt => {
            opt.selected = opt.textContent.trim() === btn.dataset.period?.trim();
        });
    }

    // Prefill shift radio
    const selectedShift = btn.dataset.shift?.trim();
    document.querySelectorAll('.edit_shift').forEach(radio => {
        const labelText = radio.closest('label')?.textContent.trim() || '';
        radio.checked = labelText.includes(selectedShift);
    });

    // Refresh Select2 (if used)
    if (window.jQuery && $('.select2').length) $('.select2').trigger('change');

    // Show modal
    const modalEl = document.getElementById('editRosterModal');
    if (modalEl) new bootstrap.Modal(modalEl).show();
});

</script>
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
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrf);

            const method = document.createElement('input');
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


<script>
$(document).ready(function() {
    $('.shift-radio').on('change', function() {
        let shiftId = $(this).val();
        let $select = $('#duty_roster_list_id');

        $select.html('<option>Loading...</option>');

        $.ajax({
            url: "<?php echo e(route('dutyroster.getDatesByShift')); ?>",
            type: "GET",
            data: { shift_id: shiftId },
            success: function(response) {
                $select.empty();
                if (response.length > 0) {
                    $select.append('<option value="">Select</option>');
                    $.each(response, function(index, roster) {
                        $select.append(
                            `<option value="${roster.id}">
                                ${roster.start_date} - ${roster.end_date}
                             </option>`
                        );
                    });
                } else {
                    $select.append('<option value="">No dates available</option>');
                }
            },
            error: function() {
                $select.html('<option>Error fetching data</option>');
            }
        });
    });

    // Trigger change for the first checked shift on load
    $('.shift-radio:checked').trigger('change');
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/duty-roster/staff_roster.blade.php ENDPATH**/ ?>