<?php $__env->startSection('content'); ?>
    <!-- row start -->

    <div class="row p-4 ">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header d-flex justify-content-between"
                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>
                        Select Criteria
                    </h5>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="text-end d-flex">
                            <a href="staff_id" class="btn btn-light text-primary ms-2 btn-md"><i
                                    class="fa-regular fa-newspaper pe-1"></i> ID Card Template</a>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">

                                    <form action="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="role" class="form-label">Role <span
                                                        class="text-danger">*</span></label>
                                                <select name="role" id="role" class="form-select shadow-sm" required>
                                                    <option value="0" selected disabled>Select</option>
                                                    <option value="admin">Admin</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="staff_id_temp" class="form-label">ID Card Template <span class="text-danger">*</span></label>
                                                <select name="staff_id_temp" id="staff_id_temp"
                                                    class="form-select shadow-sm" required>
                                                    <option value="0" selected disabled>Select</option>
                                                    <option value="sample_staff">Sample Staff ID Card</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2 mt-3">
                                        <a href="certificate" class="btn btn-primary text-white ms-2 btn-md"><i
                                                class="fa-solid fa-magnifying-glass pe-1"></i>
                                            Search</a>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row p-4 pt-0">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>
                        Patient List
                    </h5>
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
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <div class="text-end d-flex">
                                                <a href="certificate" class="btn btn-primary text-white ms-2 btn-md"><i
                                                        class="fa-solid fa-sliders pe-1"></i> Generate</a>
                                            </div>

                                        </div>
                                    </div>


                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table border">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" name="" id="">
                                                    </th>
                                                    <th>Staff ID</th>
                                                    <th>Name</th>
                                                    <th>Designation</th>
                                                    <th>Department	</th>
                                                    <th>Father Name	</th>
                                                    <th>Mother Name</th>
                                                    <th>Date Of Joining	</th>
                                                    <th>Address</th>
                                                    <th>Phone</th>
                                                    <th>Date Of Birth</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Table end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\certificate\generate_staff_id.blade.php ENDPATH**/ ?>