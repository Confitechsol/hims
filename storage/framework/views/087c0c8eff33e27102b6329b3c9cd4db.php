<?php $__env->startSection('content'); ?>

    <style>
        .input-group .input-group-addon {
            border-radius: 0;
            border: 1px solid #d2d6de;
            background-color: #d3a2e03d;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
        }

        .input-group {
            position: relative;
            display: table;
            border-collapse: separate;
        }
    </style>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Tax Category List</h5>
                </div>

                <div class="card-body">


                    
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    <?php if($errors->any()): ?>
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($error); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    <?php if(session('error')): ?>
                                        <div class="alert alert-danger">
                                            <?php echo e(session('error')); ?>

                                        </div>
                                    <?php endif; ?>
                                    <?php if(session('success')): ?>
                                        <div class="alert alert-success">
                                            <?php echo e(session('success')); ?>

                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal7c6bc96f59264604a162cf868fce49e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c6bc96f59264604a162cf868fce49e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'tax_category','name' => 'Tax Category']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'tax_category','name' => 'Tax Category']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c6bc96f59264604a162cf868fce49e9)): ?>
<?php $attributes = $__attributesOriginal7c6bc96f59264604a162cf868fce49e9; ?>
<?php unset($__attributesOriginal7c6bc96f59264604a162cf868fce49e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c6bc96f59264604a162cf868fce49e9)): ?>
<?php $component = $__componentOriginal7c6bc96f59264604a162cf868fce49e9; ?>
<?php unset($__componentOriginal7c6bc96f59264604a162cf868fce49e9); ?>
<?php endif; ?>
                                    <!-- <div
                                            class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                                            <div class="input-icon-start position-relative me-2">
                                                <span class="input-icon-addon">
                                                    <i class="ti ti-search"></i>
                                                </span>
                                                <input type="text" class="form-control shadow-sm" placeholder="Search">

                                            </div>
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md" data-bs-toggle="modal"
                                                    data-bs-target="#add_tax_category"><i class="ti ti-plus me-1"></i>Add Tax
                                                    Category</a>
                                            </div>
                                        </div> -->
                                    <!-- Modal -->
                                    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Add Tax Category
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="<?php echo e(route('tax_category.store')); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="row gy-3">

                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" id="name"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Tax <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        name="tax_percentage" id="tax_percentage" required>
                                                                    <span class="input-group-addon"> %</span>
                                                                </div>
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
                                    <div class="modal fade" id="edit_tax_category" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header rounded-0"
                                                    style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                    <h5 class="modal-title" id="addSpecializationLabel">Edit Tax Category
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="<?php echo e(route('tax_category.update')); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('put'); ?>
                                                        <div class="row gy-3">
                                                            <input type="hidden" name="id">
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="name" id="name"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">Tax <span
                                                                        class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control"
                                                                        name="tax_percentage" id="tax_percentage" required>
                                                                    <span class="input-group-addon"> %</span>
                                                                </div>
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
                                    <div class="table-responsive">
                                        <table class="table mb-0" id="tax_category">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Percentage</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $taxcatogery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taxcatogerys): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                                                    <tr>
                                                        <td>
                                                            <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($taxcatogerys->name); ?></h6>
                                                        </td>
                                                        <td><?php echo e($taxcatogerys->percentage); ?></td>
                                                        <td>
                                                            <a href="javascript: void(0);"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-bs-toggle="modal" data-bs-target="#edit_tax_category"
                                                                onclick="handleEdit(<?php echo e($taxcatogerys->id); ?>,'<?php echo e($taxcatogerys->name); ?>',<?php echo e($taxcatogerys->percentage); ?>)">
                                                                <i class="ti ti-pencil"></i></a>
                                                            
                                                            <form action="<?php echo e(route('tax_category.destroy')); ?>" method="POST"
                                                                style="display: inline">
                                                                <?php echo method_field('delete'); ?>
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="id" value="<?php echo e($taxcatogerys->id); ?>">
                                                                <button onclick='return confirm("Are you sure?")'
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i
                                                                        class="ti ti-trash"></i></button>
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

    <script>
        function handleEdit(taxId, taxName, taxPercentage) {
            let name = document.querySelector("#edit_tax_category input[name='name']");
            let tax = document.querySelector("#edit_tax_category input[name='tax_percentage']");
            let id = document.querySelector("#edit_tax_category input[name='id']");
            name.value = taxName;
            tax.value = taxPercentage;
            id.value = taxId;
        }
    </script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/tax_category.blade.php ENDPATH**/ ?>