<?php $__env->startSection('content'); ?>

    <style>

        .form-select {
            padding: 0.5rem 0.75rem !important;
        }
    </style>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Charge Category List</h5>
                </div>
                <div class="card-body">
                <?php if (isset($component)) { $__componentOriginal7c6bc96f59264604a162cf868fce49e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c6bc96f59264604a162cf868fce49e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'charges_category','name' => 'Charge Category']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'charges_category','name' => 'Charge Category']); ?>
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
                                        <!-- Modal -->
                                        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Charge
                                                            Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="<?php echo e(route('charge_categories.store')); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="row gy-3">
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Charge
                                                                        Type <span class="text-danger">*</span></label>
                                                                    <select name="charge_type" id="charge_type"
                                                                        class="form-select" required>
                                                                        <option value="">Select</option>
                                                                        <?php $__currentLoopData = $charge_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $charge_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($charge_type->id); ?>"><?php echo e($charge_type->charge_type); ?></option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Name <span
                                                                            class="text-danger">*</span></label>
                                                                            <input type="text" name="name" id="name" class="form-control" required>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="" class="form-label">Description <span
                                                                            class="text-danger">*</span></label>
                                                                            <textarea name="description" id="description" class="form-control" required></textarea>
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

                                    <div class="table-responsive">
                                        <table class="table mb-0" id="charges_category">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Charge Type</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__currentLoopData = $chargesCatogery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chargesCatogerys): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($chargesCatogerys->name); ?></h6>
                                                    </td>
                                                    <td><?php echo e($chargesCatogerys->chargeType['charge_type']); ?></td>
                                                    <td><?php echo e($chargesCatogerys->description); ?></td>
                                                    <td>

                                                        <a href="javascript:void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill" data-bs-toggle="modal" data-bs-target="#edit_charges_category_<?php echo e($chargesCatogerys->id); ?>">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="edit_charges_category_<?php echo e($chargesCatogerys->id); ?>" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header rounded-0" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                        <h5 class="modal-title" id="addSpecializationLabel">Edit Charge Category</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="<?php echo e(route('charge_categories.update')); ?>" method="POST">
                                                                            <?php echo csrf_field(); ?>
                                                                            <?php echo method_field('PUT'); ?>
                                                                            <input type="hidden" name="id" value="<?php echo e($chargesCatogerys->id); ?>">
                                                                            <div class="row gy-3">
                                                                                <div class="col-md-12">
                                                                                    <label for="" class="form-label">Charge Type <span class="text-danger">*</span></label>
                                                                                    <select name="charge_type" id="charge_type" class="form-select" required>
                                                                                        <option value="">Select</option>
                                                                                        <?php $__currentLoopData = $charge_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $charge_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <option value="<?php echo e($charge_type->id); ?>" <?php echo e($chargesCatogerys->charge_type_id == $charge_type->id ? 'selected' : ''); ?>><?php echo e($charge_type->charge_type); ?></option>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label for="" class="form-label">Name <span class="text-danger">*</span></label>
                                                                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo e($chargesCatogerys->name); ?>" required>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label for="" class="form-label">Description <span class="text-danger">*</span></label>
                                                                                    <textarea name="description" id="description" class="form-control" required><?php echo e($chargesCatogerys->description); ?></textarea>
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
                                                        <!-- Modal End -->
                                                        <form class="d-inline" action="<?php echo e(route('charge_categories.destroy')); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <input type="hidden" name="id" value="<?php echo e($chargesCatogerys->id); ?>">
                                                            <button type="submit" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i class="ti ti-trash"></i></button>
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




<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u676663263/domains/confitechone.com/public_html/hims/resources/views/admin/setup/charge_category.blade.php ENDPATH**/ ?>