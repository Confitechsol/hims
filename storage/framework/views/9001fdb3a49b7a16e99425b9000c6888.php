<?php $__env->startSection('content'); ?>
 
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

                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                        <div class="input-icon-start position-relative me-2">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" class="form-control shadow-sm" placeholder="Search">

                                        </div>
                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_patient"><i
                                                        class="ti ti-plus me-1"></i>Add New
                                                    Patient</a>
                                            </div>
                                            <!-- Modal -->

                                            <?php echo $__env->make('components.modals.add-patients-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


                                            <div class="text-end d-flex">
                                                <a href="<?php echo e(route('patient-import')); ?>"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-download me-1"></i>Import Patient</a>
                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-menu me-1"></i>Disable Patient List</a>
                                            </div>
                                        </div>

                                    </div>
                                    <form action="<?php echo e(route('patients.bulkDelete')); ?>" method="POST" id="bulk-delete-form">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?> <!-- Laravel RESTful delete -->
                                        <div class="text-end mb-2">
                                            <button type="submit" class="btn btn-danger text-white ms-2 fs-13 btn-md"
                                                onclick="return confirm('Are you sure you want to delete the selected patients?')">
                                                <i class="ti ti-trash me-1"></i>Delete Selected
                                            </button>
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
                                                        <th><input type="checkbox" name="checkbox" id="select_all">
                                                            #</th>
                                                        <th>Patient Name</th>
                                                        <th>Age</th>
                                                        <th>Gender</th>
                                                        <th>Phone</th>
                                                        <th>Guardian Name</th>
                                                        <th>Address</th>
                                                        <th>Dead</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><input type="checkbox" name="selected_patients[]"
                                                                    value="<?php echo e($patient->id); ?>" class="select_item"></td>
                                                            <td><?php echo e($patient->patient_name); ?></td>
                                                            <td><?php echo e($patient->age); ?></td>
                                                            <td><?php echo e($patient->gender); ?></td>
                                                            <td><?php echo e($patient->mobileno); ?></td>
                                                            <td><?php echo e($patient->guardian_name); ?></td>
                                                            <td><?php echo e($patient->address); ?></td>
                                                            <td><?php echo e($patient->is_dead == 'yes' ? 'Yes' : 'No'); ?></td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                        <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                            title="Assign Permission"></i></a>
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                        <i class="ti ti-dots-vertical"
                                                                            data-bs-toggle="tooltip"
                                                                            title="Assign Permission"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <!-- <tr>
                                                        <th scope="row">
                                                            <input type="checkbox" name="checkbox" id="checkbox">
                                                        </th>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold"> Bimal</h6>
                                                        </td>

                                                        <td>15 Year 4 Month 8 Days</td>
                                                        <td>Male</td>
                                                        <td>7044094367</td>
                                                        <td>Das </td>
                                                        <td>xXzXzXzX</td>
                                                        <td>No</td>

                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                <i class="ti ti-dots-vertical" data-bs-toggle="tooltip"
                                                                    title="Assign Permission"></i></a>
 

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

                            <div class="card-body">
                                <div
                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                    <div class="input-icon-start position-relative me-2">
                                        <span class="input-icon-addon">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" class="form-control shadow-sm" placeholder="Search">

                                    </div>
                                    <div class="page_btn d-flex">
                                        <div class="text-end d-flex">
                                            <a href="javascript:void(0);"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                data-bs-toggle="modal" data-bs-target="#add_patient"><i
                                                    class="ti ti-plus me-1"></i>Add New
                                                Patient</a>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="add_patient" tabindex="-1"
                                            aria-labelledby="addSpecializationLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                                <div class="modal-content modal-xl">
                                                    <div class="modal-header modal-xl rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add New
                                                            Patient
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="<?php echo e(route('patient-store')); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <?php if($errors->any()): ?>
                                                                <div class="alert alert-danger">
                                                                    <strong>There were some problems with your
                                                                        input:</strong>
                                                                    <ul class="mb-0">
                                                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <li><?php echo e($error); ?></li>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </ul>
                                                                </div>
                                                            <?php endif; ?>

                                                            <div class="row gy-3">

                                                                
                                                                <div class="col-md-6">
                                                                    <label for="name" class="form-label">Name</label>
                                                                    <input type="text" id="name" name="name"
                                                                        class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        value="<?php echo e(old('name')); ?>" />
                                                                    <?php $__errorArgs = ['name'];
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

                                                                
                                                                <div class="col-md-6">
                                                                    <label for="guardian_name"
                                                                        class="form-label">Guardian Name</label>
                                                                    <input type="text" id="guardian_name"
                                                                        name="guardian_name"
                                                                        class="form-control <?php $__errorArgs = ['guardian_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        value="<?php echo e(old('guardian_name')); ?>" />
                                                                    <?php $__errorArgs = ['guardian_name'];
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

                                                                
                                                                <div class="col-md-6">
                                                                    <div class="row">

                                                                        
                                                                        <div class="col-md-3">
                                                                            <label for="gender"
                                                                                class="form-label">Gender</label>
                                                                            <select name="gender"
                                                                                class="form-control <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                                <option value="">Select</option>
                                                                                <option value="Male" <?php echo e(old('gender') == 'Male' ? 'selected' : ''); ?>>Male</option>
                                                                                <option value="Female" <?php echo e(old('gender') == 'Female' ? 'selected' : ''); ?>>Female</option>
                                                                            </select>
                                                                            <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback"><?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>

                                                                        
                                                                        <div class="col-md-4">
                                                                            <label for="birth_date"
                                                                                class="form-label">Date of Birth</label>
                                                                            <input type="date" id="birth_date"
                                                                                name="birth_date"
                                                                                class="form-control <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                                value="<?php echo e(old('birth_date')); ?>" />
                                                                            <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback"><?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>

                                                                        
                                                                        <div class="col-sm-5">
                                                                            <label class="form-label">Age
                                                                                (yy-mm-dd)</label>
                                                                            <div style="clear: both; overflow: hidden;">
                                                                                <input type="text" name="age[year]"
                                                                                    id="age_year" placeholder="YY"
                                                                                    value="<?php echo e(old('age.year')); ?>"
                                                                                    class="form-control patient_age_year <?php $__errorArgs = ['age.year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                                    style="width: 30%; float: left;" />
                                                                                <input type="text" name="age[month]"
                                                                                    id="age_month" placeholder="MM"
                                                                                    value="<?php echo e(old('age.month')); ?>"
                                                                                    class="form-control patient_age_month <?php $__errorArgs = ['age.month'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                                    style="width: 36%; float: left; margin-left: 4px;" />
                                                                                <input type="text" name="age[day]"
                                                                                    id="age_day" placeholder="DD"
                                                                                    value="<?php echo e(old('age.day')); ?>"
                                                                                    class="form-control patient_age_day <?php $__errorArgs = ['age.day'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                                    style="width: 26%; float: left; margin-left: 4px;" />
                                                                            </div>
                                                                            <?php $__errorArgs = ['age.year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback d-block">Year:
                                                                                    <?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                            <?php $__errorArgs = ['age.month'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback d-block">Month:
                                                                                    <?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                            <?php $__errorArgs = ['age.day'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback d-block">Day:
                                                                                    <?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                
                                                                <div class="col-md-6">
                                                                    <div class="row">

                                                                        
                                                                        <div class="col-md-3">
                                                                            <label for="blood_group"
                                                                                class="form-label">Blood Group</label>
                                                                            <select name="blood_group"
                                                                                class="form-control <?php $__errorArgs = ['blood_group'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                                <option value="">Select</option>
                                                                                <option value="1" <?php echo e(old('blood_group') == '1' ? 'selected' : ''); ?>>O+</option>
                                                                                <option value="2" <?php echo e(old('blood_group') == '2' ? 'selected' : ''); ?>>A+</option>
                                                                                <option value="3" <?php echo e(old('blood_group') == '3' ? 'selected' : ''); ?>>B+</option>
                                                                                <option value="4" <?php echo e(old('blood_group') == '4' ? 'selected' : ''); ?>>AB+</option>
                                                                                <option value="5" <?php echo e(old('blood_group') == '5' ? 'selected' : ''); ?>>O-</option>
                                                                                <option value="6" <?php echo e(old('blood_group') == '6' ? 'selected' : ''); ?>>AB-</option>
                                                                            </select>
                                                                            <?php $__errorArgs = ['blood_group'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback"><?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>

                                                                        
                                                                        <div class="col-md-3">
                                                                            <label for="marital_status"
                                                                                class="form-label">Marital
                                                                                Status</label>
                                                                            <select name="marital_status"
                                                                                class="form-control <?php $__errorArgs = ['marital_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                                <option value="">Select</option>
                                                                                <option value="Single" <?php echo e(old('marital_status') == 'Single' ? 'selected' : ''); ?>>Single</option>
                                                                                <option value="Married" <?php echo e(old('marital_status') == 'Married' ? 'selected' : ''); ?>>Married</option>
                                                                                <option value="Widowed" <?php echo e(old('marital_status') == 'Widowed' ? 'selected' : ''); ?>>Widowed</option>
                                                                                <option value="Separated" <?php echo e(old('marital_status') == 'Separated' ? 'selected' : ''); ?>>Separated
                                                                                </option>
                                                                                <option value="Not Specified" <?php echo e(old('marital_status') == 'Not Specified' ? 'selected' : ''); ?>>Not
                                                                                    Specified</option>
                                                                            </select>
                                                                            <?php $__errorArgs = ['marital_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback"><?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>

                                                                        
                                                                        <div class="col-md-6">
                                                                            <label for="file" class="form-label">Patient
                                                                                Photo</label>
                                                                            <input
                                                                                class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                                type="file" name="file" id="file" />
                                                                            <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback"><?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                
                                                                <div class="col-md-6">
                                                                    <div class="row">

                                                                        <div class="col-md-6">
                                                                            <label for="phone"
                                                                                class="form-label">Phone</label>
                                                                            <input type="tel" id="phone" name="phone"
                                                                                class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                                value="<?php echo e(old('phone')); ?>" />
                                                                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback"><?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <label for="email"
                                                                                class="form-label">Email</label>
                                                                            <input type="email" id="email" name="email"
                                                                                class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                                value="<?php echo e(old('email')); ?>" />
                                                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                                <div class="invalid-feedback"><?php echo e($message); ?>

                                                                                </div>
                                                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                
                                                                <div class="col-md-6">
                                                                    <label for="address"
                                                                        class="form-label">Address</label>
                                                                    <input type="text" id="address" name="address"
                                                                        class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        value="<?php echo e(old('address')); ?>" />
                                                                    <?php $__errorArgs = ['address'];
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

                                                                
                                                                <div class="col-md-6">
                                                                    <label for="remarks"
                                                                        class="form-label">Remarks</label>
                                                                    <input type="text" id="remarks" name="remarks"
                                                                        class="form-control <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        value="<?php echo e(old('remarks')); ?>" />
                                                                    <?php $__errorArgs = ['remarks'];
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

                                                                
                                                                <div class="col-md-6">
                                                                    <label for="allergies" class="form-label">Any Known
                                                                        Allergies</label>
                                                                    <input type="text" id="allergies" name="allergies"
                                                                        class="form-control <?php $__errorArgs = ['allergies'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        value="<?php echo e(old('allergies')); ?>" />
                                                                    <?php $__errorArgs = ['allergies'];
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
                                                                    <label for="tpa" class="form-label">TPA</label>
                                                                    <select name="tpa"
                                                                        class="form-control <?php $__errorArgs = ['tpa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                        <option value="">Select</option>
                                                                        <option value="5" <?php echo e(old('tpa') == '5' ? 'selected' : ''); ?>>MedoLogi TPA Pvt. Ltd.
                                                                        </option>
                                                                        <option value="4" <?php echo e(old('tpa') == '4' ? 'selected' : ''); ?>>Vidal Health TPA</option>
                                                                        <option value="3" <?php echo e(old('tpa') == '3' ? 'selected' : ''); ?>>Paramount Health Services
                                                                        </option>
                                                                        <option value="2" <?php echo e(old('tpa') == '2' ? 'selected' : ''); ?>>Raksha TPA Pvt. Ltd.
                                                                        </option>
                                                                        <option value="1" <?php echo e(old('tpa') == '1' ? 'selected' : ''); ?>>MediAssist TPA Pvt. Ltd.
                                                                        </option>
                                                                    </select>
                                                                    <?php $__errorArgs = ['tpa'];
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
                                                                    <label for="tpa_id" class="form-label">TPA
                                                                        ID</label>
                                                                    <input type="text" id="tpa_id" name="tpa_id"
                                                                        class="form-control <?php $__errorArgs = ['tpa_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        value="<?php echo e(old('tpa_id')); ?>" />
                                                                    <?php $__errorArgs = ['tpa_id'];
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
                                                                    <label for="tpa_validity" class="form-label">TPA
                                                                        Validity</label>
                                                                    <input type="text" id="tpa_validity"
                                                                        name="tpa_validity"
                                                                        class="form-control <?php $__errorArgs = ['tpa_validity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        value="<?php echo e(old('tpa_validity')); ?>" />
                                                                    <?php $__errorArgs = ['tpa_validity'];
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
                                                                    <label for="national_id_number"
                                                                        class="form-label">National Identification
                                                                        Number</label>
                                                                    <input type="text" id="national_id_number"
                                                                        name="national_id_number"
                                                                        class="form-control <?php $__errorArgs = ['national_id_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        value="<?php echo e(old('national_id_number')); ?>" />
                                                                    <?php $__errorArgs = ['national_id_number'];
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

                                                            </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save Role</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="text-end d-flex">
                                            <a href="<?php echo e(route("patient-import")); ?>"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                    class="ti ti-download me-1"></i>Import Patient</a>
                                        </div>
                                        <div class="text-end d-flex">
                                            <a href="javascript:void(0);"
                                                class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                    class="ti ti-menu me-1"></i>Disable Patient List</a>
                                        </div>
                                    </div>

                                </div>
                                <form action="<?php echo e(route('patients.bulkDelete')); ?>" method="POST" id="bulk-delete-form">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?> <!-- Laravel RESTful delete -->
                                    <div class="text-end mb-2">
                                        <button type="submit" class="btn btn-danger text-white ms-2 fs-13 btn-md"
                                            onclick="return confirm('Are you sure you want to delete the selected patients?')">
                                            <i class="ti ti-trash me-1"></i>Delete Selected
                                        </button>
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
                                                    <th><input type="checkbox" name="checkbox" id="select_all">
                                                        #</th>
                                                    <th>Patient Name</th>
                                                    <th>Age</th>
                                                    <th>Gender</th>
                                                    <th>Phone</th>
                                                    <th>Guardian Name</th>
                                                    <th>Address</th>
                                                    <th>Dead</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><input type="checkbox" name="selected_patients[]"
                                                                value="<?php echo e($patient->id); ?>" class="select_item"></td>
                                                        <td><?php echo e($patient->patient_name); ?></td>
                                                        <td><?php echo e($patient->age); ?></td>
                                                        <td><?php echo e($patient->gender); ?></td>
                                                        <td><?php echo e($patient->mobileno); ?></td>
                                                        <td><?php echo e($patient->guardian_name); ?></td>
                                                        <td><?php echo e($patient->address); ?></td>
                                                        <td><?php echo e($patient->is_dead == "yes" ? 'Yes' : 'No'); ?></td>
                                                        <td>
                                                            <div class="d-flex">
 
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                    title="Assign Permission"></i></a>
 
                                                        </td>
                                                    </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let myModal = new bootstrap.Modal(document.getElementById('add_patient'));
                myModal.show();
            });
        </script>
    <?php endif; ?>
    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.select_item');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
<?php $__env->stopSection(); ?>

 
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                                <i class="ti ti-dots-vertical" data-bs-toggle="tooltip"
                                                                    title="Assign Permission"></i></a>
                                                                    </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <!-- <tr>
                                                    <th scope="row">
                                                        <input type="checkbox" name="checkbox" id="checkbox">
                                                    </th>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> Bimal</h6>
                                                    </td>

                                                    <td>15 Year 4 Month 8 Days</td>
                                                    <td>Male</td>
                                                    <td>7044094367</td>
                                                    <td>Das </td>
                                                    <td>xXzXzXzX</td>
                                                    <td>No</td>

                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill">
                                                            <i class="ti ti-dots-vertical" data-bs-toggle="tooltip"
                                                                title="Assign Permission"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                title="Assign Permission"></i></a>
                                                    </td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                </div>

            </div>
        </div>
    </div>
</div>

<?php if($errors->any()): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let myModal = new bootstrap.Modal(document.getElementById('add_patient'));
            myModal.show();
        });
    </script>
<?php endif; ?>
<script>
    document.getElementById('select_all').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.select_item');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>

<?php $__env->stopSection(); ?>
 
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/patient.blade.php ENDPATH**/ ?>