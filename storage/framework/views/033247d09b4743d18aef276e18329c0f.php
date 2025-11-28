<?php $__env->startSection('content'); ?>

<style>
     .section-card {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
        }

        .section-card:last-child {
            border-bottom: none;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-color);
        }

        .section-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, #750096 0%, #CB6CE673 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .section-title {
            font-weight: 600;
            font-size: 1.05rem;
            color: #212529;
            margin: 0;
        }
        .unit-select {
    flex: 0 0 20%; /* fixed 20% width */
    max-width: 20%;
}
</style>
    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Patient List</h5>
                </div>

                <div class="card-body">


                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
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

                                <div class="main_sec_box p-4">
                                        <div class="form-section mb-4">
                                            <div class="section-header">
                                                <div class="section-icon">
                                                    <i class="bi bi-clipboard-pulse"></i>
                                                </div>
                                                <h6 class="section-title mb-0 pb-0">Patient Details</h6>
                                            </div>
                                            <div class="section-body px-4">
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
                                                                value="<?php echo e(old('birth_date', \Carbon\Carbon::parse($patient->dob)->format('Y-m-d'))); ?>" />
                                                        </div>
        
                                                        
                                                        <div class="col-md-5">
                                                            <label class="form-label">Age (yy-mm-dd)</label>
        
                                                            <div style="clear: both; overflow: hidden;">
                                                                <input type="text" name="age[year]" id="edit_age_year_<?php echo e($patient->id); ?>" placeholder="YY" 
                                                                    class="form-control patient_age_year"
                                                                    style="width: 30%; float: left;"
                                                                    value="<?php echo e(old('age.year', $patient->age)); ?>" />
        
                                                                <input type="text" name="age[month]" id="edit_age_month_<?php echo e($patient->id); ?>" placeholder="MM"
                                                                    class="form-control patient_age_month"
                                                                    style="width: 36%; float: left; margin-left: 4px;"
                                                                    value="<?php echo e(old('age.month', $patient->month)); ?>" />
        
                                                                <input type="text" name="age[day]" id="edit_age_day_<?php echo e($patient->id); ?>" placeholder="DD"
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
                                                                <?php $__currentLoopData = $bloodGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($group->id); ?>"
                                                                        <?php echo e(old('blood_group', $patient->blood_group) == $group->id ? 'selected' : ''); ?>>
                                                                        <?php echo e($group->name); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                
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
        
                                                        
                                                        <div class="col-md-4">
            <label class="form-label">Height</label>

            <?php
                $heightValue = preg_replace('/[^0-9.]/', '', $patient->height ?? '');
                $heightUnit  = preg_replace('/[0-9.]/', '', $patient->height ?? '');
            ?>

            <div class="input-group d-flex">
                <input type="text" id="height_value" class="form-control"
                    value="<?php echo e(old('height_value', $heightValue)); ?>">

                <select id="height_unit" class="form-select height-select">
                    <option value="ft" <?php echo e($heightUnit == 'ft' ? 'selected' : ''); ?>>ft</option>
                    <option value="cm" <?php echo e($heightUnit == 'cm' ? 'selected' : ''); ?>>cm</option>
                </select>
            </div>

            <input type="hidden" name="height" id="height">
        </div>
        
        
        <div class="col-md-4">
        <label class="form-label">Weight</label>

            <?php
                $weightValue = preg_replace('/[^0-9.]/', '', $patient->weight ?? '');
                $weightUnit  = preg_replace('/[0-9.]/', '', $patient->weight ?? '');
            ?>

            <div class="input-group d-flex">
                <input type="text" id="weight_value" class="form-control"
                    value="<?php echo e(old('weight_value', $weightValue)); ?>">

                <select id="weight_unit" class="form-select unit-20">
                    <option value="kg" <?php echo e($weightUnit == 'kg' ? 'selected' : ''); ?>>kg</option>
                    <option value="lbs" <?php echo e($weightUnit == 'lbs' ? 'selected' : ''); ?>>lbs</option>
                </select>
            </div>

            <input type="hidden" name="weight" id="weight">
    </div>

        
        
        <div class="col-md-4">
            <label class="form-label">Temperature</label>
            <?php
                $tempValue = preg_replace('/[^0-9.]/', '', $patient->temperature ?? '');
                $tempUnit  = preg_replace('/[0-9.]/', '', $patient->temperature ?? '');
            ?>
            <div class="input-group d-flex">
                <input type="text" id="temperature_value" class="form-control"
                    value="<?php echo e(old('temperature_value', $tempValue)); ?>">
                <select id="temperature_unit" class="form-select unit-20">
                    <option value="°C" <?php echo e($tempUnit == '°C' ? 'selected' : ''); ?>>°C</option>
                    <option value="°F" <?php echo e($tempUnit == '°F' ? 'selected' : ''); ?>>°F</option>
                </select>
            </div>
            <input type="hidden" name="temperature" id="temperature">
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
        
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">Allergies</label>
                                                    <input type="text" name="allergies"
                                                        class="form-control"
                                                        value="<?php echo e(old('allergies', $patient->known_allergies)); ?>" />
                                                </div>
        
                                                
                                                <div class="col-md-4">
                                                    <label for="languages_speak" class="form-label">Languages Speak</label>
                                                    <input type="text" id="languages_speak" name="languages_speak"
                                                        class="form-control <?php $__errorArgs = ['languages_speak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        value="<?php echo e(old('languages_speak')); ?>" />
                                                    <?php $__errorArgs = ['languages_speak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
        
                                                
                                                <div class="col-md-4">
                                                    <label for="newspaper_preference" class="form-label">Newspaper Preference</label>
                                                    <input type="text" id="newspaper_preference" name="newspaper_preference"
                                                        class="form-control <?php $__errorArgs = ['newspaper_preference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        value="<?php echo e(old('newspaper_preference')); ?>" />
                                                    <?php $__errorArgs = ['newspaper_preference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                                 
                                                <div class="col-md-4">
                                                    <label class="form-label">Aadhaar No. / PAN No.</label>
                                                    <input type="text" name="national_id_number"
                                                        class="form-control"
                                                        value="<?php echo e(old('national_id_number', $patient->national_id_number)); ?>" />
                                                </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="form-section mb-4">
                                            <div class="section-header">
                                                <div class="section-icon">
                                                    <i class="bi bi-clipboard-pulse"></i>
                                                </div>
                                                <h6 class="section-title mb-0 pb-0">Guardian Details</h6>
                                            </div>
                                            <div class="section-body px-4">
                                                <div class="row gy-3">
                                                    
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
                                                    
                                                    
                                                    <div class="col-md-4">
                                                        <label class="form-label">Guardian Phone No.</label>
                                                        <input type="tel" name="guardian_phone"
                                                            class="form-control"
                                                            value="<?php echo e(old('guardian_phone', $patient->guardian_phone)); ?>" />
                                                    </div>
                                                    
                                                    
                                                    <div class="col-md-4">
                                                        <label class="form-label">Emergency Contact No.</label>
                                                        <input type="text" name="emergency_contact_no"
                                                            class="form-control"
                                                            value="<?php echo e(old('emergency_contact_no', $patient->emergency_contact_no)); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="form-section mb-4">
                                            <div class="section-header">
                                                <div class="section-icon">
                                                    <i class="bi bi-clipboard-pulse"></i>
                                                </div>
                                                <h6 class="section-title mb-0 pb-0">TPA Details</h6>
                                            </div>
                                            <div class="section-body px-4">
                                                <div class="row gy-3">
                                                    
                                                
        
                                                
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
                                                        value="<?php echo e(old('tpa_id', $patient->insurance_id)); ?>" />
                                                </div>
        
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">TPA Validity</label>
                                                    <input type="text" name="tpa_validity"
                                                        class="form-control"
                                                        value="<?php echo e(old('tpa_validity', $patient->tpa_validity)); ?>" />
                                                </div>
        
                                               
        
                                                </div>
                                            </div>
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
            </div>
        </div>
    </div>

<script>
   document.addEventListener('DOMContentLoaded', function() {

    // ------------------- TPA LOAD & AUTO CODE -------------------
    const tpaSelect = document.getElementById('edit_tpa_<?php echo e($patient->id); ?>');
    const tpaIdInput = document.getElementById('edit_tpa_id_<?php echo e($patient->id); ?>');

    tpaSelect.innerHTML = '<option value="">Loading...</option>';

    fetch("<?php echo e(route('getOrganizations')); ?>")
        .then(response => response.json())
        .then(data => {
            window.organizationsData = data;
            tpaSelect.innerHTML = '<option value="">Select</option>';

            data.forEach(org => {
                const option = document.createElement('option');
                option.value = org.id;
                option.textContent = org.organisation_name;

                // Preselect if patient already has TPA
                if ("<?php echo e(old('tpa', $patient->organisation_id)); ?>" == org.id) {
                    option.selected = true;
                }

                tpaSelect.appendChild(option);
            });

            // Autofill TPA code when editing existing patient
            const selectedOrg = data.find(org => org.id == "<?php echo e(old('tpa', $patient->organisation_id)); ?>");
            if (selectedOrg) {
                tpaIdInput.value = selectedOrg.code;
            }
        })
        .catch(error => {
            console.error('Error fetching organizations:', error);
            tpaSelect.innerHTML = '<option value="">Error loading options</option>';
        });

    // Change event for dropdown
    tpaSelect.addEventListener('change', function() {
        const selectedId = this.value;
        const selectedOrg = window.organizationsData.find(org => org.id == selectedId);
        tpaIdInput.value = selectedOrg ? selectedOrg.code : '';
    });

    // ------------------- AUTO CALCULATE AGE -------------------
    const birthDateInput = document.getElementById('birth_date'); // your ID
    const ageYearInput = document.getElementById('edit_age_year_<?php echo e($patient->id); ?>');
    const ageMonthInput = document.getElementById('edit_age_month_<?php echo e($patient->id); ?>');
    const ageDayInput = document.getElementById('edit_age_day_<?php echo e($patient->id); ?>');

    birthDateInput.addEventListener('change', function() {
        const birthDate = new Date(this.value);

        if (!this.value || isNaN(birthDate)) {
            ageYearInput.value = '';
            ageMonthInput.value = '';
            ageDayInput.value = '';
            return;
        }

        const today = new Date();
        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();
        let days = today.getDate() - birthDate.getDate();

        if (days < 0) {
            months--;
            const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            days += prevMonth.getDate();
        }

        if (months < 0) {
            years--;
            months += 12;
        }

        ageYearInput.value = years;
        ageMonthInput.value = months;
        ageDayInput.value = days;
    });

});
 
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("ipdForm");

    form.addEventListener("submit", function () {

        document.getElementById("height").value =
            document.getElementById("height_value").value +
            document.getElementById("height_unit").value;

        document.getElementById("weight").value =
            document.getElementById("weight_value").value +
            document.getElementById("weight_unit").value;

        document.getElementById("temperature").value =
            document.getElementById("temperature_value").value +
            document.getElementById("temperature_unit").value;

    });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/edit-patient.blade.php ENDPATH**/ ?>