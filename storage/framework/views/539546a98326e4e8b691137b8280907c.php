<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">

        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Patient Queue</h5>
                </div>

                <div class="card-body">
                    <form id="slot_form" method="POST" action="<?php echo e(route('slots.store')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <label for="doctor" class="form-label">Doctor <span class="text-danger">*</span></label>
                                <select class="form-select" id="doctor" data-placeholder="Select">
                                    <option value="">Select</option>
                                    <option value="1">Amitabh Kulkarni</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="shift" class="form-label">Shift <span class="text-danger">*</span></label>
                                <select class="form-select" id="shift" data-placeholder="Select">
                                    <option value="">Select</option>
                                    <option value="mornigng">Morning Shift</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="date" class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control standard_charge" name="date" id="date" value="">

                            </div>
                            <div class="col-md-3">
                                <label for="doctor" class="form-label">Slot <span class="text-danger">*</span></label>
                                <select class="form-select" id="doctor" data-placeholder="Select">
                                    <option value=""></option>
                                    <option value="1">1</option>
                                </select>
                            </div>

                            <div class="col-md-12 text-end">
                                <button type="button" onclick="search()" class="btn btn-primary btn-sm mt-4">Record Queue</button>
                                <button type="button" onclick="search()" class="btn btn-primary btn-sm mt-4">Search</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>







    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/appointments/queue.blade.php ENDPATH**/ ?>