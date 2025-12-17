<?php $__env->startSection('content'); ?>
<div class="row px-5 py-4">
    <div class="col-12 d-flex">

        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                    <div>
                        <h4 class="fw-bold mb-0">Blood Issue Details</h4>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                            data-bs-toggle="modal" data-bs-target="#add_shift">
                            <i class="ti ti-plus me-1"></i> Issue Blood
                        </a>

                        

                    </div>
                </div>
            </div>

            <div class="card-body">
    <?php if($bloodissues->isEmpty()): ?>
        <p class="text-center">No Donor List found.</p>
    <?php else: ?>
        <div class="table-responsive table-nowrap">
            <table class="table border table-striped align-middle">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Bill No</th>
                        <th>Issue Date</th>
                        <th>Received To</th>
                        <th>Blood Group</th>
                        <th>Gender</th>
                        <th>Donor Name</th>
                        <th>Bags</th>
                        <th>Net Amount (INR)</th>
                        <th>Paid Amount (INR)</th>
                        <th>Balance Amount (INR)</th>
                        
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php $__currentLoopData = $bloodissues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($issue->bill_no); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($issue->date_of_issue)->format('d-m-Y')); ?></td>
                            <td><?php echo e($issue->patient->patient_id); ?></td>
                            <td><?php echo e($issue->blood_donor_cycle_id); ?></td>
                            <td><?php echo e($issue->patient->gender); ?></td>
                            <td><?php echo e($issue->blood_donor_cycle_id); ?></td>
                            <td><?php echo e($issue->bags); ?></td>
                            <td><?php echo e(number_format($issue->net_amount, 2)); ?></td>
                            <td><?php echo e(number_format($issue->paid_amount, 2)); ?></td>
                            <td><?php echo e(number_format($issue->balance_amount, 2)); ?></td>
                            
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
function openEditModal(id, name, dob, bloodGroupId, gender, fatherName, contactNo, address) {
    // Fill form fields
    document.getElementById('edit_donor_id').value = id;
    document.getElementById('edit_doner_name').value = name;
    document.getElementById('edit_dob').value = dob;
    document.getElementById('edit_blood_group').value = bloodGroupId;
    document.getElementById('edit_gender').value = gender;
    document.getElementById('edit_father_name').value = fatherName;
    document.getElementById('edit_contact_no').value = contactNo;
    document.getElementById('edit_address').value = address;

    // Set dynamic action URL
    document.getElementById('editDonorForm').action = "<?php echo e(route('bloodBank.updateDoner', ['id' => ':id'])); ?>".replace(':id', id);

    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('edit_donor'));
    modal.show();
}
</script>
<script>
    function confirmDelete(url) {
        Swal.fire({
            title: "Are you sure?",
            text: "This donor will be deleted permanently.",
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\blood-bank-doner\blood-issue.blade.php ENDPATH**/ ?>