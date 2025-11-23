<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-eye me-2"></i>Pathology Test Details
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('pathology.test.index')); ?>" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>Back to List
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo e(route('pathology.test.edit', $test->id)); ?>" class="btn btn-warning text-white">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Test Name:</th>
                                    <td><?php echo e($test->test_name); ?></td>
                                </tr>
                                <tr>
                                    <th>Short Name:</th>
                                    <td><?php echo e($test->short_name); ?></td>
                                </tr>
                                <tr>
                                    <th>Test Type:</th>
                                    <td><?php echo e($test->test_type ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td><?php echo e($test->category->category_name ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Sub Category:</th>
                                    <td><?php echo e($test->sub_category ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Method:</th>
                                    <td><?php echo e($test->method ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Report Days:</th>
                                    <td><?php echo e($test->report_days ?? '-'); ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Charge Category:</th>
                                    <td><?php echo e($test->chargeCategory->name ?? ($test->charge && $test->charge->category ? $test->charge->category->name : '-')); ?></td>
                                </tr>
                                <tr>
                                    <th>Charge Name:</th>
                                    <td><?php echo e($test->charge->name ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Standard Charge:</th>
                                    <td>₹<?php echo e(number_format($test->standard_charge ?? ($test->charge ? $test->charge->standard_charge : 0), 2)); ?></td>
                                </tr>
                                <tr>
                                    <th>Tax (%):</th>
                                    <td><?php echo e($test->charge && $test->charge->taxCategory ? number_format($test->charge->taxCategory->percentage, 2) : '0.00'); ?>%</td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td class="fw-bold">₹<?php echo e(number_format($test->amount ?? ($test->charge ? ($test->charge->standard_charge + ($test->charge->standard_charge * ($test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0) / 100)) : 0), 2)); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($test->parameters && $test->parameters->count() > 0): ?>
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">Test Parameters</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Parameter Name</th>
                                                <th>Reference Range</th>
                                                <th>Unit</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $test->parameters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $paramDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $parameter = $paramDetail->parameter;
                                                ?>
                                                <tr>
                                                    <td><?php echo e($index + 1); ?></td>
                                                    <td><?php echo e($parameter->parameter_name ?? '-'); ?></td>
                                                    <td><?php echo e($parameter->reference_range ?? '-'); ?></td>
                                                    <td><?php echo e($parameter->unitRelation->unit_name ?? '-'); ?></td>
                                                    <td><?php echo e($parameter->description ?? '-'); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- TPA Charges Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                    <h5 class="mb-0" style="color: #750096">
                                        <i class="fas fa-building me-2"></i>TPA Charges
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if(session('success')): ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <?php echo e(session('success')); ?>

                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(session('error')): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <?php echo e(session('error')); ?>

                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($tpaCharges && count($tpaCharges) > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>TPA Organization</th>
                                                        <th>Standard Charge (INR)</th>
                                                        <th>TPA Charge (INR)</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $tpaCharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tpaCharge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td>
                                                                <strong><?php echo e($tpaCharge->organisation->organisation_name ?? '-'); ?></strong>
                                                                <?php if($tpaCharge->organisation && $tpaCharge->organisation->code): ?>
                                                                    <br><small class="text-muted">Code: <?php echo e($tpaCharge->organisation->code); ?></small>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>₹<?php echo e(number_format($test->standard_charge ?? 0, 2)); ?></td>
                                                            <td>₹<?php echo e(number_format($tpaCharge->org_charge ?? 0, 2)); ?></td>
                                                            <td>
                                                                <button
                                                                    class="btn btn-sm btn-soft-success rounded-pill edit-tpa-charge-btn"
                                                                    data-id="<?php echo e($tpaCharge->id); ?>"
                                                                    data-org_charge="<?php echo e($tpaCharge->org_charge); ?>"
                                                                    data-org_name="<?php echo e($tpaCharge->organisation->organisation_name ?? 'TPA'); ?>"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editTpaChargeModal">
                                                                    <i class="ti ti-pencil"></i> Edit
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info">
                                            <i class="ti ti-info-circle me-2"></i>No TPA charges found for this pathology test. TPA charges are automatically created when you create a pathology test.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit TPA Charge Modal -->
    <div class="modal fade" id="editTpaChargeModal" tabindex="-1" aria-labelledby="editTpaChargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTpaChargeModalLabel">Edit TPA Charge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?php echo e(route('pathology.test.update-tpa-charge')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="id" id="tpa_charge_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">TPA Organization</label>
                            <input type="text" class="form-control" id="tpa_org_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Standard Charge (INR)</label>
                            <input type="text" class="form-control" value="₹<?php echo e(number_format($test->standard_charge ?? 0, 2)); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TPA Charge (INR) <span class="text-danger">*</span></label>
                            <input type="number" name="org_charge" id="tpa_org_charge" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update TPA Charge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle edit TPA charge button click
            document.querySelectorAll('.edit-tpa-charge-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const orgCharge = this.getAttribute('data-org_charge');
                    const orgName = this.getAttribute('data-org_name');
                    
                    document.getElementById('tpa_charge_id').value = id;
                    document.getElementById('tpa_org_charge').value = orgCharge;
                    document.getElementById('tpa_org_name').value = orgName;
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp-8.2\htdocs\hims\resources\views/admin/pathology/test/show.blade.php ENDPATH**/ ?>