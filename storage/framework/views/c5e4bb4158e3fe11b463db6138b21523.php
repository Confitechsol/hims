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
                            <i class="ti ti-plus me-1"></i> Add Doner
                        </a>

                        <!-- Add Shift Modal -->
                        <div class="modal fade" id="add_shift" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content">
                                    <form method="POST" action="<?php echo e(route('bloodBank.addDoner')); ?>">
    <?php echo csrf_field(); ?>
    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Add Donor Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label for="doner_name" class="form-label">Donor Name</label>
            <input type="text" name="doner_name" id="doner_name" class="form-control" required>
        </div>

        <div class="row">
            <div class="col">
                <label for="dob" class="form-label">DOB</label>
                <input type="date" name="dob" id="dob" class="form-control" required>
            </div>

            <div class="col">
                <label for="blood_group" class="form-label">Blood Group</label>
                <select name="blood_group" id="blood_group" class="form-select" required>
                    <option value="">Select Blood Group</option>
                    <?php $__currentLoopData = $bloodGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="mt-3">
            <label for="gender" class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-select" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mt-3">
            <label for="father_name" class="form-label">Father's Name</label>
            <input type="text" name="father_name" id="father_name" class="form-control" required>
        </div>

        <div class="mt-3">
            <label for="contact_no" class="form-label">Contact No.</label>
            <input type="text" name="contact_no" id="contact_no" class="form-control" required>
        </div>

        <div class="mt-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Donor</button>
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
               <?php if($donors->isEmpty()): ?>
    <p class="text-center">No Donor List found.</p>
<?php else: ?>
    <div class="table-responsive table-nowrap">
        <table class="table border table-striped align-middle">
            <thead class="thead-light text-center">
                <tr>
                    <th>#</th>
                    <th>Donor Name</th>
                    <th>DOB</th>
                    <th>Blood Group</th>
                    <th>Gender</th>
                    <th>Contact No.</th>
                    <th>Father's Name</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php $__currentLoopData = $donors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $donor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($donor->donor_name); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($donor->date_of_birth)->format('d-m-Y')); ?></td>
                        <td><?php echo e($donor->bloodBankProduct->name ?? 'N/A'); ?></td>
                        <td><?php echo e($donor->gender); ?></td>
                        <td><?php echo e($donor->contact_no); ?></td>
                        <td><?php echo e($donor->father_name); ?></td>
                        <td><?php echo e($donor->address); ?></td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Edit Button -->
                                <a href="<?php echo e(route('bloodBank.editDoner', $donor->id)); ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="<?php echo e(route('bloodBank.deleteDoner', $donor->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this donor?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/blood-bank-doner/doners.blade.php ENDPATH**/ ?>