<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Prefix Settings</h5>
                </div>

                    <div class="card-body">
                        <form id="#" method="POST"
                            action="<?php echo e(isset($prefixes) ? route('prefixes.update') : route('prefixes.store')); ?>"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php if(isset($prefixes)): ?>
                                <?php echo method_field('PUT'); ?>
                            <?php endif; ?>


                            <div class="row mb-3 gy-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">IPD No <span
                                            class="text-danger">*</span></label>
                                    <input type="hidden" name="fields[0][type]" value="ipd_no">
                                    <input type="text" class="form-control" name="fields[0][prefix]"
                                        value="<?php echo e(old('fields.0.prefix', $prefixes['ipd_no'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">OPD No <span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[1][type]" value="opd_no">
                                    <input type="text" class="form-control" name="fields[1][prefix]"
                                        value="<?php echo e(old('fields.1.prefix', $prefixes['opd_no'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">IPD Prescription <span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[2][type]" value="ipd_pre">
                                    <input type="text" class="form-control" name="fields[2][prefix]"
                                        value="<?php echo e(old('fields.2.prefix', $prefixes['ipd_pre'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">OPD Prescription <span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[3][type]" value="opd_pre">
                                    <input type="text" class="form-control" name="fields[3][prefix]"
                                        value="<?php echo e(old('fields.3.prefix', $prefixes['opd_pre'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Appointment <span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[4][type]" value="appointment">
                                    <input type="text" class="form-control" name="fields[4][prefix]"
                                        value="<?php echo e(old('fields.4.prefix', $prefixes['appointment'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Pharmacy Bill <span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[5][type]" value="pharm_bill">
                                    <input type="text" class="form-control" name="fields[5][prefix]"
                                        value="<?php echo e(old('fields.5.prefix', $prefixes['pharm_bill'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Operation Reference No<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[6][type]" value="operation_ref_no">
                                    <input type="text" class="form-control" name="fields[6][prefix]"
                                        value="<?php echo e(old('fields.6.prefix', $prefixes['operation_ref_no'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Blood Bank Bill<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[7][type]" value="blood_bank_no">
                                    <input type="text" class="form-control" name="fields[7][prefix]"
                                        value="<?php echo e(old('fields.7.prefix', $prefixes['blood_bank_no'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Ambulance Call Bill<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[8][type]" value="ambulance_call_bill">
                                    <input type="text" class="form-control" name="fields[8][prefix]"
                                        value="<?php echo e(old('fields.8.prefix', $prefixes['ambulance_call_bill'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Radiology Bill<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[9][type]" value="radiology_bill">
                                    <input type="text" class="form-control" name="fields[9][prefix]"
                                        value="<?php echo e(old('fields.9.prefix', $prefixes['radiology_bill'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Pathology Bill<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[10][type]" value="pathology_bill">
                                    <input type="text" class="form-control" name="fields[10][prefix]"
                                        value="<?php echo e(old('fields.10.prefix', $prefixes['pathology_bill'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">OPD Checkup Id<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[11][type]" value="opd_checkup_id">
                                    <input type="text" class="form-control" name="fields[11][prefix]"
                                        value="<?php echo e(old('fields.11.prefix', $prefixes['opd_checkup_id'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Pharmacy Purchase No<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[12][type]" value="pharmacy_purchase_no">
                                    <input type="text" class="form-control" name="fields[12][prefix]"
                                        value="<?php echo e(old('fields.12.prefix', $prefixes['pharmacy_purchase_no'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Transaction ID<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[13][type]" value="transaction_id">
                                    <input type="text" class="form-control" name="fields[13][prefix]"
                                        value="<?php echo e(old('fields.13.prefix', $prefixes['transaction_id'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Birth Record Reference No<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[14][type]" value="birth_rec_ref_no">
                                    <input type="text" class="form-control" name="fields[14][prefix]"
                                        value="<?php echo e(old('fields.14.prefix', $prefixes['birth_rec_ref_no'] ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Death Record Reference No<span
                                            class="text-danger">*</span></label>
                                            <input type="hidden" name="fields[15][type]" value="death_rec_ref_no">
                                    <input type="text" class="form-control" name="fields[15][prefix]"
                                        value="<?php echo e(old('fields.15.prefix', $prefixes['death_rec_ref_no'] ?? '')); ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\prefix.blade.php ENDPATH**/ ?>