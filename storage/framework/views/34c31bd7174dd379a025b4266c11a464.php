<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Disabled Patient List</h5>
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
                                                        class="ti ti-menu me-1"></i>
                                                    Patient List</a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" name="checkbox" id="checkbox">
                                                        #</th>
                                                    <th>Patient Name</th>
                                                    <th>Age</th>
                                                    <th>Gender</th>
                                                    <th>Phone</th>
                                                    <th>Guardian Name</th>
                                                    <th>Address</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        <input type="checkbox" name="checkbox" id="checkbox">
                                                    </th>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"> Sayan P</h6>
                                                    </td>

                                                    <td>24 Year 4 Month 3 Days</td>
                                                    <td>Male</td>
                                                    <td>8617284931</td>
                                                    <td></td>
                                                    <td>Bhadreswar</td>

                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                title="Assign Permission"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
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
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\disable_patient.blade.php ENDPATH**/ ?>