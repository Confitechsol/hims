
<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-eye me-2"></i>Radiology Test Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('radiology.test.index')); ?>" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo e(route('radiology.test.edit', $test->id)); ?>" class="btn btn-warning text-white me-2">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                            <button class="btn btn-primary" onclick="window.print()">
                                <i class="ti ti-printer me-1"></i>Print
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Test Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Test Name:</th>
                                    <td><?php echo e($test->test_name ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Short Name:</th>
                                    <td><?php echo e($test->short_name ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Test Type:</th>
                                    <td><?php echo e($test->test_type ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td><?php echo e($test->radiologyCategory->name ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Sub Category:</th>
                                    <td><?php echo e($test->sub_category ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Report Days:</th>
                                    <td><?php echo e($test->report_days ?? 0); ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="mb-3">Charge Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Charge Category:</th>
                                    <td><?php echo e($test->charge && $test->charge->category ? $test->charge->category->name : '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Charge Name:</th>
                                    <td><?php echo e($test->charge ? $test->charge->name : '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Tax Category:</th>
                                    <td><?php echo e($test->charge && $test->charge->taxCategory ? $test->charge->taxCategory->name : '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Tax Percentage:</th>
                                    <td><?php echo e($test->charge && $test->charge->taxCategory ? number_format($test->charge->taxCategory->percentage, 2) . '%' : '0%'); ?></td>
                                </tr>
                                <tr>
                                    <th>Standard Charge (INR):</th>
                                    <td>₹<?php echo e($test->charge ? number_format($test->charge->standard_charge, 2) : '0.00'); ?></td>
                                </tr>
                                <tr>
                                    <th>Amount (INR):</th>
                                    <td class="fw-bold">₹<?php echo e($test->charge ? number_format($test->charge->standard_charge + ($test->charge->standard_charge * ($test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0) / 100), 2) : '0.00'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($tpaCharges && $tpaCharges->count() > 0): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">
                                <i class="fas fa-building me-2"></i>TPA Charges
                            </h6>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>TPA Organization</th>
                                                    <th>TPA Code</th>
                                                    <th>TPA Charge (INR)</th>
                                                    <th>Standard Charge (INR)</th>
                                                    <th>Difference (INR)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $tpaCharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tpaCharge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($index + 1); ?></td>
                                                        <td>
                                                            <strong><?php echo e($tpaCharge->organisation->organisation_name ?? '-'); ?></strong>
                                                        </td>
                                                        <td><?php echo e($tpaCharge->organisation->code ?? '-'); ?></td>
                                                        <td class="fw-bold">₹<?php echo e(number_format($tpaCharge->org_charge ?? 0, 2)); ?></td>
                                                        <td>₹<?php echo e($test->charge ? number_format($test->charge->standard_charge, 2) : '0.00'); ?></td>
                                                        <td>
                                                            <?php
                                                                $standardCharge = $test->charge ? $test->charge->standard_charge : 0;
                                                                $tpaChargeAmount = $tpaCharge->org_charge ?? 0;
                                                                $difference = $tpaChargeAmount - $standardCharge;
                                                            ?>
                                                            <span class="<?php echo e($difference < 0 ? 'text-danger' : ($difference > 0 ? 'text-success' : 'text-muted')); ?>">
                                                                <?php echo e($difference >= 0 ? '+' : ''); ?>₹<?php echo e(number_format($difference, 2)); ?>

                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="ti ti-info-circle me-2"></i>
                                No TPA charges configured for this test. Standard charge will be used for all TPAs.
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp-8.2\htdocs\hims\resources\views/admin/radiology/test/show.blade.php ENDPATH**/ ?>