
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Prescription Details</h4>
                    <div>
                        <a href="<?php echo e(route('ipd.prescription.edit', $prescription->id)); ?>" class="btn btn-primary btn-sm">
                            <i class="ti ti-edit"></i> Edit
                        </a>
                        <a href="<?php echo e(route('ipd.prescription.print', $prescription->id)); ?>" target="_blank" class="btn btn-info btn-sm">
                            <i class="ti ti-printer"></i> Print
                        </a>
                        <a href="<?php echo e(route('ipd.show', $prescription->ipd_id)); ?>" class="btn btn-secondary btn-sm">
                            <i class="ti ti-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Prescription Number: <strong><?php echo e($prescription->prescription_number); ?></strong></h6>
                            <p class="mb-1"><strong>Date:</strong> <?php echo e($prescription->date->format('d/m/Y')); ?></p>
                            <p class="mb-1"><strong>Prescribed By:</strong> 
                                <?php echo e($prescription->prescribedBy ? $prescription->prescribedBy->name . ' (' . ($prescription->prescribedBy->doctor_id ?? 'N/A') . ')' : 'N/A'); ?>

                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Patient Details</h6>
                            <?php if($prescription->ipd && $prescription->ipd->patient): ?>
                                <p class="mb-1"><strong>Patient:</strong> <?php echo e($prescription->ipd->patient->name ?? 'N/A'); ?></p>
                                <p class="mb-1"><strong>Patient ID:</strong> <?php echo e($prescription->ipd->patient->patient_id ?? 'N/A'); ?></p>
                                <p class="mb-1"><strong>IPD ID:</strong> <?php echo e($prescription->ipd->ipd_id ?? 'N/A'); ?></p>
                                <p class="mb-1"><strong>Age:</strong> <?php echo e($prescription->ipd->patient->age ?? 'N/A'); ?></p>
                                <p class="mb-1"><strong>Gender:</strong> <?php echo e($prescription->ipd->patient->gender ?? 'N/A'); ?></p>
                            <?php else: ?>
                                <p class="mb-1 text-muted">Patient information not available</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($prescription->header_note): ?>
                    <div class="mb-4">
                        <h6>Header Note</h6>
                        <div class="border p-3 rounded">
                            <?php echo $prescription->header_note; ?>

                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($prescription->medicines->count() > 0): ?>
                    <div class="mb-4">
                        <h6>Medicines</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Medicine</th>
                                        <th>Dosage</th>
                                        <th>Interval</th>
                                        <th>Duration</th>
                                        <th>Instruction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $prescription->medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($medicine->pharmacy->name ?? 'N/A'); ?></td>
                                        <td>
                                            <?php echo e($medicine->medicineDosage->name ?? 'N/A'); ?>

                                            <?php if($medicine->medicineDosage && $medicine->medicineDosage->unit): ?>
                                                <?php echo e($medicine->medicineDosage->unit->unit_name ?? ''); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($medicine->doseInterval->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($medicine->doseDuration->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($medicine->instruction ?? '-'); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($prescription->tests->count() > 0): ?>
                    <div class="mb-4">
                        <h6>Tests</h6>
                        <div class="row">
                            <?php if($prescription->pathologyTests->count() > 0): ?>
                            <div class="col-md-6">
                                <h6 class="text-primary">Pathology Tests</h6>
                                <ul>
                                    <?php $__currentLoopData = $prescription->pathologyTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($test->pathology->test_name ?? $test->pathology->name ?? 'N/A'); ?>

                                            <?php if($test->pathology && $test->pathology->short_name): ?>
                                                (<?php echo e($test->pathology->short_name); ?>)
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                            <?php if($prescription->radiologyTests->count() > 0): ?>
                            <div class="col-md-6">
                                <h6 class="text-info">Radiology Tests</h6>
                                <ul>
                                    <?php $__currentLoopData = $prescription->radiologyTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($test->radiology->test_name ?? $test->radiology->name ?? 'N/A'); ?>

                                            <?php if($test->radiology && $test->radiology->short_name): ?>
                                                (<?php echo e($test->radiology->short_name); ?>)
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($prescription->finding_description): ?>
                    <div class="mb-4">
                        <h6>Findings</h6>
                        <div class="border p-3 rounded">
                            <p><strong>Categories:</strong> <?php echo e($prescription->finding_categories ?? '-'); ?></p>
                            <p><strong>Findings:</strong> <?php echo e($prescription->findings ?? '-'); ?></p>
                            <p><strong>Description:</strong> <?php echo e($prescription->finding_description); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($prescription->footer_note): ?>
                    <div class="mb-4">
                        <h6>Footer Note</h6>
                        <div class="border p-3 rounded">
                            <?php echo $prescription->footer_note; ?>

                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($prescription->attachment): ?>
                    <div class="mb-4">
                        <h6>Attachment</h6>
                        <a href="<?php echo e(asset('storage/' . $prescription->attachment)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-download"></i> Download <?php echo e($prescription->attachment_name); ?>

                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp-8.2\htdocs\hims\resources\views/admin/ipd/prescription/show.blade.php ENDPATH**/ ?>