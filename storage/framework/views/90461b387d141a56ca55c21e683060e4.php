<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Item Category List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_item_category"><i
                                                        class="ti ti-plus me-1"></i>Add Item Category</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_item_category" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Item
                                                                Category
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="<?php echo e(route('item-category.store')); ?>"
                                                                method="POST">
                                                                <?php echo csrf_field(); ?>

                                                                <div id="item_category_fields">
                                                                    <div
                                                                        class="row gy-3 item-category-row align-items-center mb-2">

                                                                        <!-- Operation Name -->
                                                                        <div class="col-md-3">
                                                                            <label for="item_head" class="form-label">
                                                                                Item Head <span
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="item_head[]" id="item_head"
                                                                                class="form-select" autocomplete="off"
                                                                                required>
                                                                                <option value="">Select</option>
                                                                                <option value="Capital Equipment">Capital
                                                                                    Equipment</option>
                                                                                <option value="Consumables">Consumables
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label for="item_category" class="form-label">
                                                                                Item Category <span
                                                                                    class="text-danger">*</span></label>
                                                                            <input type="text" name="item_category[]"
                                                                                id="item_category" class="form-control"
                                                                                required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label for="Description" class="form-label">
                                                                                Description <span
                                                                                    class="text-danger">*</span></label>
                                                                            <textarea name="description[]" id="Description" class="form-control"></textarea>
                                                                        </div>

                                                                        <div class="col-md-1 p-0">
                                                                            <button type="button"
                                                                                class="btn btn-danger remove-btn"
                                                                                style="display:none;"><i
                                                                                    class="ti ti-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Add button -->
                                                                <div class="my-3">
                                                                    <button type="button" id="addBtn"
                                                                        class="btn btn-primary">Add</button>
                                                                </div>


                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Save</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Item Category</th>
                                                    <th>Item Head</th>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $itemCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold">
                                                                <?php echo e($itemCategory->item_category); ?>

                                                            </h6>
                                                        </td>
                                                        <td><?php echo e($itemCategory->item_head ?? '-'); ?></td>
                                                        <td class="text-wrap"><?php echo e($itemCategory->description); ?></td>
                                                        <td>
                                                            <form
                                                                action="<?php echo e(route('item-category.updateStatus', [$itemCategory->id])); ?>"
                                                                method="post">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="form-check form-switch mb-0">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox" role="switch"
                                                                        id="switchCheckDefault" name="is_active"
                                                                        data-id="<?php echo e($itemCategory->id); ?>"
                                                                        <?php echo e($itemCategory->is_active == 'yes' ? 'checked' : ''); ?>>
                                                                </div>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-bs-toggle="modal" data-bs-target="#edit_itemCategory"
                                                                data-id="<?php echo e($itemCategory->id); ?>"
                                                                data-name="<?php echo e($itemCategory->item_category); ?>"
                                                                data-head="<?php echo e($itemCategory->item_head); ?>"
                                                                data-description="<?php echo e($itemCategory->description); ?>">
                                                                <i class="ti ti-pencil"></i></a>
                                                            <form
                                                                action="<?php echo e(route('item-category.delete', [$itemCategory->id])); ?>"
                                                                class="d-inline" id="delete-form-<?php echo e($itemCategory->id); ?>"
                                                                method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill delete-button"
                                                                    data-item-category-id="<?php echo e($itemCategory->id); ?>"
                                                                    data-item-category-name="<?php echo e($itemCategory->item_category); ?>"
                                                                    data-form-id="delete-form-<?php echo e($itemCategory->id); ?>">
                                                                    <i class="ti ti-trash"></i></a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                            </tbody>
                                        </table>
                                    </div>

                                    <!--Edit Modal -->
                                    <div class="modal fade" id="edit_itemCategory" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Update
                                                        Item Category
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="<?php echo e(route('item-category.update')); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PUT'); ?>
                                                        <div id="item_category_fields">
                                                            <div class="row gy-3 item-category-row mb-2">

                                                                <!-- Operation Name -->
                                                                <input type="hidden" id="update_item_category_id"
                                                                    name="item_category_id" />
                                                                <div class="col-md-6">
                                                                    <label for="item_head" class="form-label">
                                                                        Item Head <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="item_head" id="update_item_head"
                                                                        class="form-select" autocomplete="off" required>
                                                                        <option value="">Select</option>
                                                                        <option value="Capital Equipment">Capital
                                                                            Equipment</option>
                                                                        <option value="Consumables">Consumables
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="item_category" class="form-label">
                                                                        Item Category <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="item_category"
                                                                        id="update_item_category" class="form-control"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="Description" class="form-label">
                                                                        Description <span
                                                                            class="text-danger">*</span></label>
                                                                    <textarea name="description" id="update_description" class="form-control"></textarea>
                                                                </div>

                                                                <div class="col-md-1 d-flex align-items-end">
                                                                    <button type="button"
                                                                        class="btn btn-danger remove-btn"
                                                                        style="display:none;"><i
                                                                            class="ti ti-trash"></i></button>
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
        const operationFields = document.getElementById("item_category_fields");

        addBtn.addEventListener("click", function() {
            // Clone the first row
            let firstRow = operationFields.querySelector(".item-category-row");
            let newRow = firstRow.cloneNode(true);

            // Clear input values
            newRow.querySelectorAll("input, select, textarea").forEach(el => el.value = "");

            // Show remove button
            newRow.querySelector(".remove-btn").style.display = "inline-block";

            // Append new row
            operationFields.appendChild(newRow);

            // Add remove functionality
            newRow.querySelector(".remove-btn").addEventListener("click", function() {
                newRow.remove();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('edit_itemCategory');

            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var head = button.getAttribute('data-head');
                var description = button.getAttribute('data-description');

                // Populate modal inputs
                document.getElementById('update_item_category_id').value = id;
                document.getElementById('update_item_category').value = name;
                document.getElementById('update_description').value = description;
                let headSelect = editModal.querySelector('select[name="item_head"]');
                if (headSelect) {
                    headSelect.value = head;
                }
            });
        });
    </script>
    <script>
        document.querySelectorAll('.status-toggle').forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    </script>
    <script>
        document.querySelectorAll('.delete-button').forEach(input => {
            input.addEventListener('click', function() {
                const itemCategoryId = this.dataset.itemCategoryId;
                const itemCategoryName = this.dataset.itemCategoryName;
                const formId = this.dataset.formId;

                Swal.fire({
                    title: `Please Confirm`,
                    text: `Delete Item Category ${itemCategoryName}(${itemCategoryId})`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete!',
                    cancelButtonText: 'Cancel',
                }).then(result => {
                    console.log(result);

                    if (result.isConfirmed) {
                        document.getElementById(formId).submit(); // Submit your form
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\item_category.blade.php ENDPATH**/ ?>