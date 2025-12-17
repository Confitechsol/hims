<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-eye me-2"></i>Pathology Bill Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <a href="<?php echo e(route('pathology.billing.index')); ?>" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="<?php echo e(route('pathology.billing.edit', $bill->id)); ?>" class="btn btn-warning text-white me-2">
                            <i class="ti ti-edit me-1"></i>Edit
                        </a>
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="ti ti-printer me-1"></i>Print
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Patient Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Patient Name:</th>
                                <td><?php echo e($bill->patient->patient_name ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th>Patient ID:</th>
                                <td><?php echo e($bill->patient_id); ?></td>
                            </tr>
                            <tr>
                                <th>Mobile No:</th>
                                <td><?php echo e($bill->patient->mobileno ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th>Case Reference ID:</th>
                                <td><?php echo e($bill->case_reference_id ?? '-'); ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="mb-3">Bill Information</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Bill No:</th>
                                <td>PATB<?php echo e(str_pad($bill->id, 2, '0', STR_PAD_LEFT)); ?></td>
                            </tr>
                            <tr>
                                <th>Reporting Date:</th>
                                <td><?php echo e(date('m/d/Y h:i A', strtotime($bill->date))); ?></td>
                            </tr>
                            <tr>
                                <th>Reference Doctor:</th>
                                <td><?php echo e($bill->doctor_name ?? '-'); ?></td>
                            </tr>
                            <?php if($bill->note): ?>
                            <tr>
                                <th>Note:</th>
                                <td><?php echo e($bill->note); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <?php if($bill->reports && $bill->reports->count() > 0): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="mb-3">Tests</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Test Name</th>
                                        <th>Report Days</th>
                                        <th>Report Date</th>
                                        <th>Tax (%)</th>
                                        <th>Amount (INR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $bill->reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($report->pathology->test_name ?? '-'); ?></td>
                                        <td><?php echo e($report->pathology->report_days ?? '-'); ?></td>
                                        <td><?php echo e($report->reporting_date ? date('m/d/Y', strtotime($report->reporting_date)) : '-'); ?></td>
                                        <td><?php echo e(number_format($report->tax_percentage ?? 0, 2)); ?>%</td>
                                        <td>₹<?php echo e(number_format($report->apply_charge ?? 0, 2)); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row mt-4">
                    <div class="col-md-6 offset-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Total Amount:</th>
                                        <td class="text-end">₹<?php echo e(number_format($bill->total ?? 0, 2)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Discount:</th>
                                        <td class="text-end">₹<?php echo e(number_format($bill->discount ?? 0, 2)); ?> (<?php echo e(number_format($bill->discount_percentage ?? 0, 2)); ?>%)</td>
                                    </tr>
                                    <tr>
                                        <th>Tax:</th>
                                        <td class="text-end">₹<?php echo e(number_format($bill->tax ?? 0, 2)); ?></td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <th>Net Amount:</th>
                                        <td class="text-end">₹<?php echo e(number_format($bill->net_amount ?? 0, 2)); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\pathology\billing\show.blade.php ENDPATH**/ ?>