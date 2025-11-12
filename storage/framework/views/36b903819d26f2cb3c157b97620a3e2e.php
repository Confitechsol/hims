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

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/setup/patient.blade.php ENDPATH**/ ?>