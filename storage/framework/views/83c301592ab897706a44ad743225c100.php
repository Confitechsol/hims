<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Expense Head List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_income_head"><i
                                                        class="ti ti-plus me-1"></i>Add Expense Head</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_income_head" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Expense Head
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="<?php echo e(route('expense-head.store')); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>

                                                                <div id="expense_head_fields">
                                                                    <div class="row gy-3 expense-head-row mb-2">

                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-4">
                                                                            <label for="expense_head"
                                                                                class="form-label">Expense Head <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="expense_head[]"
                                                                                id="expense_head" class="form-control" required />
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <label for="description"
                                                                                class="form-label">Description</label>
                                                                            <input type="text" name="description[]"
                                                                                id="description" class="form-control"  />
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
                                                    <th>Expense Head</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = $expenseHeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expenseHead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td>
            <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($expenseHead->exp_category); ?></h6>
            <small class="text-muted"><?php echo e($expenseHead->description); ?></small>
        </td>
        <td>
            <!-- Edit Button -->
            <a href="javascript:void(0);" 
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                data-id="<?php echo e($expenseHead->id); ?>"
                data-name="<?php echo e($expenseHead->exp_category); ?>"
                data-desc="<?php echo e($expenseHead->description); ?>">
                <i class="ti ti-pencil"></i>
            </a>

            <!-- Delete Button -->
            <a href="javascript:void(0);"
                onclick="deleteExpenseHead(<?php echo e($expenseHead->id); ?>)"
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                <i class="ti ti-trash"></i>
            </a>
        </td>

        <!-- Hidden Delete Form -->
        <form id="deleteExpenseHeadForm" method="POST" style="display:none;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
        </form>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="2" class="text-center text-muted">No expense heads found</td>
    </tr>
<?php endif; ?>

                                               
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
    <!-- Edit Expense Head Modal -->
<div class="modal fade" id="edit_expense_head" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title">Edit Expense Head</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <!-- Hidden ID field -->
                    <input type="hidden" id="edit_id" name="id">

                    <div class="row gy-3">
                        <!-- Expense Head Name -->
                        <div class="col-md-6">
                            <label for="edit_expense_head_name" class="form-label">Expense Head <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="expense_head" id="edit_expense_head_name"
                                class="form-control" required />
                        </div>

                        <!-- Description -->
                        <div class="col-md-6">
                            <label for="edit_description" class="form-label">Description</label>
                            <input type="text" name="description" id="edit_description"
                                class="form-control" />
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>


    <script>
    // Handle Edit button click
    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function () {
            let id = this.dataset.id;
            let name = this.dataset.name;
            let desc = this.dataset.desc;

            // Set values inside modal
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_expense_head_name").value = name;
            document.getElementById("edit_description").value = desc;

            // Update form action URL dynamically
            let form = document.getElementById("editForm");
            form.action = '<?php echo e(url('expense-head/update')); ?>/' + id;

            // Open modal
            new bootstrap.Modal(document.getElementById("edit_expense_head")).show();
        });
    });

    function deleteExpenseHead(id) {
        if (confirm("Are you sure you want to delete this Expense Head?")) {
            let form = document.getElementById("deleteExpenseHeadForm");
            form.action = "<?php echo e(url('expense-head/destroy')); ?>/" + id;
            form.submit();
        }
    }
</script>


    <script>
        const addBtn = document.getElementById("addBtn");
        const operationFields = document.getElementById("expense_head_fields");

        addBtn.addEventListener("click", function () {
            // Clone the first row
            let firstRow = operationFields.querySelector(".expense-head-row");
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
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\expense_head.blade.php ENDPATH**/ ?>