<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-edit me-2"></i>Edit Pathology Bill</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('pathology.billing.update', $bill->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <!-- Patient and Bill Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-select" required>
                                <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($patient->id); ?>" <?php echo e($bill->patient_id == $patient->id ? 'selected' : ''); ?>>
                                        <?php echo e($patient->patient_name); ?> (ID: <?php echo e($patient->id); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Case Reference</label>
                            <input type="text" name="case_reference_id" class="form-control" value="<?php echo e($bill->case_reference_id); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reference Doctor</label>
                            <select name="doctor_id" class="form-select">
                                <option value="">Select Doctor</option>
                                <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($doctor->id); ?>" <?php echo e($bill->doctor_id == $doctor->id ? 'selected' : ''); ?>>
                                        Dr. <?php echo e($doctor->name); ?> <?php echo e($doctor->surname); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reporting Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date" class="form-control" value="<?php echo e(date('Y-m-d\TH:i', strtotime($bill->date))); ?>" required>
                        </div>
                    </div>

                    <!-- Test Selection Table -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3">Pathology Test Details</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Report Days</th>
                                            <th>Report Date</th>
                                            <th>Tax (%)</th>
                                            <th>Amount (INR)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $bill->reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <select name="tests[<?php echo e($index); ?>][pathology_id]" class="form-select" required>
                                                    <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($test->id); ?>" <?php echo e($report->pathology_id == $test->id ? 'selected' : ''); ?>>
                                                            <?php echo e($test->test_name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[<?php echo e($index); ?>][report_days]" class="form-control" value="<?php echo e($report->pathology->report_days ?? 0); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="date" name="tests[<?php echo e($index); ?>][report_date]" class="form-control" value="<?php echo e(date('Y-m-d', strtotime($report->reporting_date))); ?>" required>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[<?php echo e($index); ?>][tax_percentage]" class="form-control" value="<?php echo e($report->tax_percentage); ?>" step="0.01" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="tests[<?php echo e($index); ?>][amount]" class="form-control" value="<?php echo e($report->apply_charge); ?>" step="0.01" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6 offset-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table mb-0">
                                        <tr>
                                            <th>Total Amount</th>
                                            <td class="text-end">₹<?php echo e(number_format($bill->total, 2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td class="text-end">
                                                <input type="number" name="discount_percentage" class="form-control" value="<?php echo e($bill->discount_percentage); ?>" step="0.01" min="0" max="100">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Discount (INR)</th>
                                            <td class="text-end">₹<?php echo e(number_format($bill->discount, 2)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tax (INR)</th>
                                            <td class="text-end">₹<?php echo e(number_format($bill->tax, 2)); ?></td>
                                        </tr>
                                        <tr class="table-primary">
                                            <th>Net Amount</th>
                                            <td class="text-end fw-bold">₹<?php echo e(number_format($bill->net_amount, 2)); ?></td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="total" value="<?php echo e($bill->total); ?>">
                                    <input type="hidden" name="discount" value="<?php echo e($bill->discount); ?>">
                                    <input type="hidden" name="tax" value="<?php echo e($bill->tax); ?>">
                                    <input type="hidden" name="tax_percentage" value="<?php echo e($bill->tax_percentage); ?>">
                                    <input type="hidden" name="net_amount" value="<?php echo e($bill->net_amount); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="2"><?php echo e($bill->note); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="<?php echo e(route('pathology.billing.index')); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Bill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pathology\billing\edit.blade.php ENDPATH**/ ?>