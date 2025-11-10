

<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Company List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_company"><i
                                                        class="ti ti-plus me-1"></i>Add Company</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_company" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Company
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST">
                                                                <?php echo csrf_field(); ?>

                                                                <div id="company_fields">
                                                                    <div class="row gy-3 company-row mb-2">

                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-11">
                                                                            <label for="company_name" class="form-label">Company
                                                                                Name <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="company_name"
                                                                                id="company_name" class="form-control" />
                                                                        </div>

                                                                        <div class="col-md-1 d-flex align-items-end">
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-btn"
                                                                                style="display:none;"><i
                                                                                    class="ti ti-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Add button -->
                                                                <div class="mt-3">
                                                                    <button type="button" id="addBtn"
                                                                        class="btn btn-primary">Add</button>
                                                                </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Database ID</th>
                                                    <th>Company Name</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">1
                                                        </h6>
                                                    </td>
                                                    <td>Cipla</td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">2
                                                        </h6>
                                                    </td>
                                                    <td>Sun Pharma</td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">3
                                                        </h6>
                                                    </td>
                                                    <td>Abbott</td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">4
                                                        </h6>
                                                    </td>
                                                    <td>Mankind</td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">5
                                                        </h6>
                                                    </td>
                                                    <td>Dr. Reddy's	</td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
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

    <script>
        const addBtn = document.getElementById("addBtn");
        const operationFields = document.getElementById("company_fields");

        addBtn.addEventListener("click", function () {
            // Clone the first row
            let firstRow = operationFields.querySelector(".company-row");
            let newRow = firstRow.cloneNode(true);

            // Clear input values
            newRow.querySelectorAll("input, select").forEach(el => el.value = "");

            // Show remove button
            newRow.querySelector(".remove-btn").style.display = "inline-block";

            // Append new row
            operationFields.appendChild(newRow);

            // Add remove functionality
            newRow.querySelector(".remove-btn").addEventListener("click", function () {
                newRow.remove();
            });
        });
    </script>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/setup/company_list.blade.php ENDPATH**/ ?>