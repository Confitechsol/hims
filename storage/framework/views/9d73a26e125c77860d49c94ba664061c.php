<div class="modal fade" id="editPatientModal" tabindex="-1"
    aria-labelledby="editPatientLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content modal-xl">

            <div class="modal-header modal-xl rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title" id="editPatientLabel">Edit Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="<?php echo e(route('patient-update', $patient->id)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row gy-3">

                        
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name"
                                class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('name', $patient->patient_name)); ?>" />
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label">Guardian Name</label>
                            <input type="text" name="guardian_name"
                                class="form-control <?php $__errorArgs = ['guardian_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('guardian_name', $patient->guardian_name)); ?>" />
                        </div>

                        
                        <div class="col-md-6">
                            <div class="row">

                                
                                <div class="col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-control <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select</option>
                                        <option value="Male" <?php echo e(old('gender', $patient->gender) == 'Male' ? 'selected' : ''); ?>>Male</option>
                                        <option value="Female" <?php echo e(old('gender', $patient->gender) == 'Female' ? 'selected' : ''); ?>>Female</option>
                                    </select>
                                </div>

                                
                                <div class="col-md-4">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="birth_date"
                                        class="form-control <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('birth_date', $patient->dob)); ?>" />
                                </div>

                                
                                <div class="col-md-5">
                                    <label class="form-label">Age (yy-mm-dd)</label>

                                    <div style="clear: both; overflow: hidden;">
                                        <input type="text" name="age[year]" id="edit_age_year_<?php echo e($patient->id); ?>"
                                            placeholder="YY" 
                                            class="form-control patient_age_year"
                                            style="width: 30%; float: left;"
                                            value="<?php echo e(old('age.year', $patient->age)); ?>" />

                                        <input type="text" name="age[month]" id="edit_age_month_<?php echo e($patient->id); ?>"
                                            placeholder="MM"
                                            class="form-control patient_age_month"
                                            style="width: 36%; float: left; margin-left: 4px;"
                                            value="<?php echo e(old('age.month', $patient->month)); ?>" />

                                        <input type="text" name="age[day]" id="edit_age_day_<?php echo e($patient->id); ?>"
                                            placeholder="DD"
                                            class="form-control patient_age_day"
                                            style="width: 26%; float: left; margin-left: 4px;"
                                            value="<?php echo e(old('age.day', $patient->day)); ?>" />
                                    </div>
                                </div>

                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="row">

                                
                                <div class="col-md-3">
                                    <label class="form-label">Blood Group</label>
                                    <select name="blood_group" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1" <?php echo e(old('blood_group', $patient->blood_group) == '1' ? 'selected' : ''); ?>>O+</option>
                                        <option value="2" <?php echo e(old('blood_group', $patient->blood_group) == '2' ? 'selected' : ''); ?>>A+</option>
                                        <option value="3" <?php echo e(old('blood_group', $patient->blood_group) == '3' ? 'selected' : ''); ?>>B+</option>
                                        <option value="4" <?php echo e(old('blood_group', $patient->blood_group) == '4' ? 'selected' : ''); ?>>AB+</option>
                                        <option value="5" <?php echo e(old('blood_group', $patient->blood_group) == '5' ? 'selected' : ''); ?>>O-</option>
                                        <option value="6" <?php echo e(old('blood_group', $patient->blood_group) == '6' ? 'selected' : ''); ?>>AB-</option>
                                    </select>
                                </div>

                                
                                <div class="col-md-3">
                                    <label class="form-label">Marital Status</label>
                                    <select name="marital_status" class="form-control">
                                        <option value="">Select</option>
                                        <?php $__currentLoopData = ['Single','Married','Widowed','Separated','Not Specified']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($status); ?>"
                                                <?php echo e(old('marital_status', $patient->marital_status) == $status ? 'selected' : ''); ?>>
                                                <?php echo e($status); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                
                                <div class="col-md-6">
                                    <label class="form-label">Patient Photo</label>
                                    <input type="file" name="file" class="form-control" />

                                    <?php if($patient->file): ?>
                                        <small class="text-muted">Current: <?php echo e($patient->file); ?></small>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" name="phone"
                                        class="form-control"
                                        value="<?php echo e(old('phone', $patient->mobileno)); ?>" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email"
                                        class="form-control"
                                        value="<?php echo e(old('email', $patient->email)); ?>" />
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control"
                                value="<?php echo e(old('address', $patient->address)); ?>" />
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label">Remarks</label>
                            <input type="text" name="remarks" class="form-control"
                                value="<?php echo e(old('remarks', $patient->remarks)); ?>" />
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label">Allergies</label>
                            <input type="text" name="allergies"
                                class="form-control"
                                value="<?php echo e(old('allergies', $patient->allergies)); ?>" />
                        </div>

                        
                        <div class="col-md-4">
                            <label class="form-label">TPA</label>
                            <select id="edit_tpa_<?php echo e($patient->id); ?>" name="tpa" class="form-control">
                                <option value="">Loading...</option>
                            </select>
                        </div>

                        
                        <div class="col-md-4">
                            <label class="form-label">TPA Code</label>
                            <input type="text" id="edit_tpa_id_<?php echo e($patient->id); ?>" name="tpa_id"
                                class="form-control"
                                value="<?php echo e(old('tpa_id', $patient->tpa_id)); ?>" />
                        </div>

                        
                        <div class="col-md-4">
                            <label class="form-label">TPA Validity</label>
                            <input type="text" name="tpa_validity"
                                class="form-control"
                                value="<?php echo e(old('tpa_validity', $patient->tpa_validity)); ?>" />
                        </div>

                        
                        <div class="col-md-4">
                            <label class="form-label">National ID</label>
                            <input type="text" name="national_id_number"
                                class="form-control"
                                value="<?php echo e(old('national_id_number', $patient->national_id_number)); ?>" />
                        </div>

                    </div>

                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Update Patient</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ---------- TPA LOADER (WORKS FOR MULTIPLE EDIT MODALS) ----------
    fetch("<?php echo e(route('getOrganizations')); ?>")
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll("[id^='edit_tpa_']").forEach(select => {
                const patientId = select.id.split("_")[2];
                const tpaIdField = document.getElementById("edit_tpa_id_" + patientId);

                select.innerHTML = '<option value="">Select</option>';

                data.forEach(org => {
                    const opt = document.createElement('option');
                    opt.value = org.id;
                    opt.textContent = org.organisation_name;

                    if ("<?php echo e(old('tpa', $patient->tpa ?? '')); ?>" == org.id) {
                        opt.selected = true;
                        tpaIdField.value = org.code;
                    }
                    select.appendChild(opt);
                });

                select.addEventListener('change', function() {
                    const selected = data.find(o => o.id == this.value);
                    tpaIdField.value = selected ? selected.code : '';
                });
            });
        });

    // ---------- AUTO CALCULATE AGE FROM DOB FOR EDIT MODAL ----------
    document.querySelectorAll("input[name='birth_date']").forEach(input => {
        input.addEventListener('change', function() {

            const modal = this.closest('.modal');
            const patientId = modal.id.split("_")[2];

            let yearInput = document.getElementById("edit_age_year_" + patientId);
            let monthInput = document.getElementById("edit_age_month_" + patientId);
            let dayInput = document.getElementById("edit_age_day_" + patientId);

            const dob = new Date(this.value);
            if (!this.value || isNaN(dob)) {
                yearInput.value = monthInput.value = dayInput.value = '';
                return;
            }

            const today = new Date();
            let years = today.getFullYear() - dob.getFullYear();
            let months = today.getMonth() - dob.getMonth();
            let days = today.getDate() - dob.getDate();

            if (days < 0) {
                months--;
                const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                days += prevMonth.getDate();
            }
            if (months < 0) {
                years--;
                months += 12;
            }

            yearInput.value = years;
            monthInput.value = months;
            dayInput.value = days;
        });
    });

});
</script>
<?php /**PATH C:\xampp\htdocs\hims\resources\views/components/modals/edit-patient-modal.blade.php ENDPATH**/ ?>