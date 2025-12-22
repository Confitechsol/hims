<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-file-invoice me-2"></i>Pathology Bill List
                    </h5>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="input-icon-start position-relative">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" class="form-control shadow-sm" id="searchBill" placeholder="Search Bill">
                        </div>
                        <a href="<?php echo e(route('pathology.billing.create')); ?>" class="btn btn-primary text-white">
                            <i class="ti ti-plus me-1"></i>Generate Bill
                        </a>
                    </div>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Bill No</th>
                                    <th>Case ID</th>
                                    <th>Reporting Date</th>
                                    <th>Patient Name</th>
                                    <th>Reference Doctor</th>
                                    <th>Discount (INR)</th>
                                    <th>Amount (INR)</th>
                                    <th>Paid Amount (INR)</th>
                                    <th>Balance Amount (INR)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>PATB<?php echo e(str_pad($bill->id, 2, '0', STR_PAD_LEFT)); ?></td>
                                        <td><?php echo e($bill->case_reference_id ?? '-'); ?></td>
                                        <td><?php echo e($bill->date ? date('m/d/Y h:i A', strtotime($bill->date)) : '-'); ?></td>
                                        <td><?php echo e($bill->patient->patient_name ?? '-'); ?> (<?php echo e($bill->patient_id ?? '-'); ?>)</td>
                                        <td><?php echo e($bill->doctor_name ?? '-'); ?></td>
                                        <td><?php echo e(number_format($bill->discount ?? 0, 2)); ?> (<?php echo e(number_format($bill->discount_percentage ?? 0, 2)); ?>%)</td>
                                        <td>₹<?php echo e(number_format($bill->total ?? 0, 2)); ?></td>
                                        <td>₹0.00</td>
                                        <td>₹<?php echo e(number_format($bill->net_amount ?? 0, 2)); ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo e(route('pathology.billing.show', $bill->id)); ?>" class="btn btn-sm btn-info text-white" title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('pathology.billing.edit', $bill->id)); ?>" class="btn btn-sm btn-warning text-white" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('pathology.billing.destroy', $bill->id)); ?>" method="POST" class="d-inline" onsubmit="return confirmDeleteForm(event, 'Delete Billing?', 'Are you sure you want to delete this billing record?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger text-white" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-inbox fs-48 mb-2"></i>
                                                <p>No pathology bills found. Generate your first bill!</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <?php echo e($bills->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchBill');
            const tableRows = document.querySelectorAll('tbody tr');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u676663263/domains/confitechone.com/public_html/hims/resources/views/admin/pathology/billing/index.blade.php ENDPATH**/ ?>