

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
                            <a href="patient_id" class="btn btn-light text-primary ms-2 btn-md"><i
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
                                                <label for="patient" class="form-label">Patient <span
                                                        class="text-danger">*</span></label>
                                                <select name="patient" id="patient" class="form-select shadow-sm">
                                                    <option value="0" selected disabled>All</option>
                                                    <option value="abhijeet">Abhijeet(25)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="patient_status" class="form-label">ID Card Template <span class="text-danger">*</span></label>
                                                <select name="patient_status" id="patient_status"
                                                    class="form-select shadow-sm">
                                                    <option value="0" selected disabled>Select</option>
                                                    <option value="sample_patient">Sample Patient ID Card</option>
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
                                                    <th>Patient Name</th>
                                                    <th>Age</th>
                                                    <th>Gender</th>
                                                    <th>Phone</th>
                                                    <th>Guardian Name</th>
                                                    <th>Address</th>
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
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/certificate/generate_patient_id.blade.php ENDPATH**/ ?>