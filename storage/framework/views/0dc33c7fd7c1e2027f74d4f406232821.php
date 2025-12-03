

<?php $__env->startSection('content'); ?>
<?php
    // Detect Add or Edit mode
    $isEdit = isset($staff);
?>

<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            
            <!-- Header -->
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-cogs me-2"></i>
                    <?php echo e($isEdit ? 'Edit Staff' : 'Add New Staff'); ?>

                </h5>
            </div>

            <div class="card-body">

                <!-- FORM -->
                <form id="form1"
                      action="<?php echo e($isEdit ? route('staff.update', $staff->id) : route('staff.store')); ?>"
                      method="POST"
                      enctype="multipart/form-data">

                    <?php echo csrf_field(); ?>
                    <?php if($isEdit): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?>

                    <div class="row">
                        <h4>Basic Information</h4>

                        <div class="around10">
                            <div class="row">

                                <!-- STAFF ID -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Staff ID</label><small class="req"> *</small>
                                        <input id="employee_id" name="employee_id" type="text"
                                               class="form-control"
                                               value="<?php echo e(old('employee_id', $staff->employee_id ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- ROLE -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Role</label><small class="req"> *</small>
                                        <select id="role" name="role" class="form-control">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($role->id); ?>"
                                                    <?php echo e(old('role', $staff->role_id ?? '') == $role->id ? 'selected' : ''); ?>>
                                                    <?php echo e($role->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- DESIGNATION -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <select id="designation" name="designation" class="form-control">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $des): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($des->id); ?>"
                                                    <?php echo e(old('designation', $staff->staff_designation_id  ?? '') == $des->id ? 'selected' : ''); ?>>
                                                    <?php echo e($des->designation); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- DEPARTMENT -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select id="department" name="department" class="form-control"
                                                onchange="loadSpecialists(this.value)">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($dept->id); ?>"
                                                    <?php echo e(old('department', $staff->department_id ?? '') == $dept->id ? 'selected' : ''); ?>>
                                                    <?php echo e($dept->department_name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- SPECIALIST -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Specialist</label>
                                        <select name="specialist" class="form-control" id="specialist">
                                            <option value="">Select</option>

                                            <?php $__currentLoopData = $specialists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specialist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($specialist->id); ?>"
                                                    <?php echo e(old('specialist', $staff->specialist ?? '') == $specialist->id ? 'selected' : ''); ?>>
                                                    <?php echo e($specialist->specialist_name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- FIRST NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name</label><small class="req"> *</small>
                                        <input id="name" name="name" type="text" class="form-control"
                                               value="<?php echo e(old('name', $staff->name ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- LAST NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input id="surname" name="surname" type="text" class="form-control"
                                               value="<?php echo e(old('surname', $staff->surname ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- FATHER NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Father Name</label>
                                        <input id="father_name" name="father_name" type="text" class="form-control"
                                               value="<?php echo e(old('father_name', $staff->father_name ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- MOTHER NAME -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mother Name</label>
                                        <input id="mother_name" name="mother_name" type="text" class="form-control"
                                               value="<?php echo e(old('mother_name', $staff->mother_name ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- GENDER -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender</label><small class="req"> *</small>
                                        <select class="form-control" name="gender">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = ['Male','Female','others']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($g); ?>"
                                                    <?php echo e(old('gender', $staff->gender ?? '') == $g ? 'selected' : ''); ?>>
                                                    <?php echo e($g); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- MARITAL STATUS -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Marital Status</label>
                                        <select class="form-control" name="marital_status">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = ['Single','Married','Widowed','Separated','Not Specified']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($ms); ?>"
                                                    <?php echo e(old('marital_status', $staff->marital_status ?? '') == $ms ? 'selected' : ''); ?>>
                                                    <?php echo e($ms); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- BLOOD GROUP -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Blood Group</label>
                                        <select class="form-control" name="blood_group">
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $bloodgroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($bg); ?>"
                                                    <?php echo e(old('blood_group', $staff->blood_group ?? '') == $bg->id ? 'selected' : ''); ?>>
                                                    <?php echo e($bg->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- DOB -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Of Birth</label><small class="req"> *</small>
                                        <input id="dob" name="dob" type="text" class="form-control date"
                                               value="<?php echo e(old('dob', $staff->dob ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- DO JOINING -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Of Joining</label>
                                        <input id="date_of_joining" name="date_of_joining" type="text" class="form-control date"
                                               value="<?php echo e(old('date_of_joining', $staff->date_of_joining ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- CONTACT -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input id="mobileno" name="contactno" type="text" class="form-control"
                                               value="<?php echo e(old('contactno', $staff->contact_no ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- EMERGENCY CONTACT -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Emergency Contact</label>
                                        <input id="emgmobileno" name="emgcontactno" type="text" class="form-control"
                                               value="<?php echo e(old('emgcontactno', $staff->emgcontactno ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- EMAIL -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label><small class="req"> *</small>
                                        <input id="email" name="email" type="email" class="form-control"
                                               value="<?php echo e(old('email', $staff->email ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- PHOTO -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Photo</label>
                                        <input type="file" class="form-control" name="file">
                                        <?php if($isEdit && $staff->photo): ?>
                                            <small>Current: <img src="<?php echo e(asset('uploads/staff/'.$staff->photo)); ?>"
                                                                 width="40"></small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- CURRENT ADDRESS -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Current Address</label>
                                        <textarea name="address" class="form-control"><?php echo e(old('address', $staff->local_address ?? '')); ?></textarea>
                                    </div>
                                </div>

                                <!-- PERMANENT ADDRESS -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Permanent Address</label>
                                        <textarea name="permanent_address" class="form-control"><?php echo e(old('permanent_address', $staff->permanent_address ?? '')); ?></textarea>
                                    </div>
                                </div>

                                <!-- QUALIFICATION -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Qualification</label>
                                        <textarea name="qualification" class="form-control"><?php echo e(old('qualification', $staff->qualification ?? '')); ?></textarea>
                                    </div>
                                </div>

                                <!-- WORK EXP -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Work Experience</label>
                                        <textarea name="work_exp" class="form-control"><?php echo e(old('work_exp', $staff->work_exp ?? '')); ?></textarea>
                                    </div>
                                </div>

                                <!-- SPECIALIZATION -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Specialization</label>
                                        <textarea name="specialization" class="form-control"><?php echo e(old('specialization', $staff->specialization ?? '')); ?></textarea>
                                    </div>
                                </div>

                                <!-- NOTE -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea name="note" class="form-control"><?php echo e(old('note', $staff->note ?? '')); ?></textarea>
                                    </div>
                                </div>

                                <!-- PAN -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>PAN Number</label>
                                        <input name="pan_number" type="text" class="form-control"
                                               value="<?php echo e(old('pan_number', $staff->pan_number ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- NATIONAL ID -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>National Identification Number</label>
                                        <input name="identification_number" type="text" class="form-control"
                                               value="<?php echo e(old('identification_number', $staff->identification_number ?? '')); ?>">
                                    </div>
                                </div>

                                <!-- LOCAL ID -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Local Identification Number</label>
                                        <input name="local_identification_number" type="text" class="form-control"
                                               value="<?php echo e(old('local_identification_number', $staff->local_identification_number ?? '')); ?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-info pull-right">
                        <i class="fa fa-check-circle"></i>
                        <?php echo e($isEdit ? 'Update' : 'Save'); ?>

                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/staff/addStaff.blade.php ENDPATH**/ ?>