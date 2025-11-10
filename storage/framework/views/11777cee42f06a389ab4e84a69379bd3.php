

<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Doctor Shift</h5>
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


                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Doctor Name</th>
                                                    <th>Morning Shift</th>
                                                    <th>Afternoon Shift</th>
                                                    <th>Evening Shift</th>
                                                    <th>Night Shift</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="#" class="mb-0 fs-14 fw-semibold">Bimal Kundu (D001)
                                                        </a>
                                                    </td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>  
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#" class="mb-0 fs-14 fw-semibold">Priya Sharma (D002)
                                                        </a>
                                                    </td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>  
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#" class="mb-0 fs-14 fw-semibold">Sandeep Sharma (D004)
                                                        </a>
                                                    </td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>
                                                    <td><input type="checkbox" name="" id="" class="form-check"></td>  
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/setup/doctor_shift.blade.php ENDPATH**/ ?>