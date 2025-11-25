<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Pathology Category List</h5>
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
                                                    data-bs-toggle="modal" data-bs-target="#add_pathology_category"><i
                                                        class="ti ti-plus me-1"></i>Add Pathology Category</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_pathology_category" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header rounded-0"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add
                                                                Pathology Category
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="<?php echo e(route('pathology-category.store')); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="row gy-3 medicine-group-row mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-12">
                                                                        <label for="category_name"
                                                                            class="form-label">Category Name<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="category_name"
                                                                            id="category_name" class="form-control" />
                                                                    </div>
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
                                                    <th>Category Name</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

       
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($category->category_name); ?>

                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <a  href="javascript: void(0);" onclick="openPathologyCategoryModal(this)" data-category-id="<?php echo e($category->id); ?>" 
                                                            data-category-name="<?php echo e($category->category_name); ?>"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                        
                                                        <a href="javascript:void(0);" 
                                                            onclick="deletePathologyCategory(<?php echo e($category->id); ?>)"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                        <form id="deletePathologyCategoryForm" method="POST" style="display:none;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                        </form>
                                                    </td>
                                                </tr>
                                            
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
    <div class="modal fade" id="editPathologyCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pathology Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPathologyCategoryForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="category_name" id="editPathologyCategoryName" required>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
            </div>
        </div>
    </div>

<script>
    function openPathologyCategoryModal(el) {
    let id = el.getAttribute("data-category-id");
    let name = el.getAttribute("data-category-name");

    // Fill modal input
    document.getElementById("editPathologyCategoryName").value = name;

    // Update form action dynamically
    let form = document.getElementById("editPathologyCategoryForm");
    form.action = "<?php echo e(url('pathology-category/update')); ?>/" + id; // your update route

    // Show modal
    let modal = new bootstrap.Modal(document.getElementById("editPathologyCategoryModal"));
    modal.show();
    }

</script>
<script>
    function deletePathologyCategory(id) {
        if (confirm("Are you sure you want to delete this pathology category?")) {
            let form = document.getElementById("deletePathologyCategoryForm");
            form.action = "<?php echo e(url('pathology-category/destroy')); ?>/" + id; // adjust route if needed
            form.submit();
        }
    }

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/pathology_category.blade.php ENDPATH**/ ?>