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

            .form-select {
                padding: 0.5rem 0.75rem !important;
            }
            /* loader css */
            .loader-section{
                background-color: rgba(0,0,0,0.4);
                position:fixed;
                top:0;
                left:0;
                z-index:9999;
                width:100%;
                height:100%;
                display:none;
            }
            @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
            }
        </style>
    <!-- Loader -->
    <div id="loader" class="loader-section">
    <div style="display:block; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); z-index:1000;">
        <div style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite;"></div>
    </div>
    </div>
    <!-- Toast message box -->
    <div id="toast" style="display:none; position:fixed; top:20px; right:20px; background:#4CAF50; color:white; padding:10px 20px; border-radius:5px; z-index:1001;">
        <span id="toast-message"></span>
    </div>
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Charge Type List</h5>
                    </div>
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
                                    <?php if(session('success')): ?>
                                        <div class="alert alert-success">
                                            <?php echo e(session('success')); ?>

                                        </div>
                                    <?php endif; ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                    <?php if (isset($component)) { $__componentOriginal7c6bc96f59264604a162cf868fce49e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c6bc96f59264604a162cf868fce49e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'charge-type','name' => 'Charge Type']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'charge-type','name' => 'Charge Type']); ?>
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
                                        <div class="table-responsive">
                                            <table class="table mb-0" id="charge-type">
                                                <thead>
                                                    <tr>
                                                    <th>Charge Type</th>
                                                    <?php $__currentLoopData = $chargestypemodule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <th>
                                                      <?php echo e($item->charge_type); ?>

                                                    </th>                                                    
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                                  
                                                    <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tbody>
                                                <?php $__currentLoopData = $chargetypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chargetype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($chargetype->charge_type); ?></td>

                                                        <?php $__currentLoopData = $chargestypemodule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td>
                                                                <input type="checkbox"
                                                                       name="charges[<?php echo e($chargetype->id); ?>][<?php echo e($module->module_shortcode); ?>]"
                                                                       value="1"
                                                                       onclick="recordUpdate(this,'<?php echo e($module->module_shortcode); ?>',<?php echo e($chargetype->id); ?>)"
                                                                       <?php if(in_array($module->module_shortcode, $filter[$chargetype->id] ?? [])): ?> checked <?php endif; ?>
                                                                >
                                                            </td>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <td>
                                                            <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill" 
                                                            onclick="edit_type(<?php echo e($chargetype->id); ?>,'<?php echo e($chargetype->charge_type); ?>')"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit_type">
                                                            <i class="ti ti-pencil"></i></button>
                                                            <form class="d-inline" action="<?php echo e(route('charge_type_module.destroy')); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('delete'); ?>
                                                                <input type="hidden" name="id" value="<?php echo e($chargetype->id); ?>">
                                                                <button onclick="return confirm('Are you sure?')"
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

    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title" id="addSpecializationLabel">Add Charge
                    Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('charge_type_module.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row gy-3">
                        <div class="col-md-12 border-bottom pb-3">
                            <label for="" class="form-label">Charge Type <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="charge_type" id="charge_type"
                                class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label for="" class="form-label">Module <span
                                    class="text-danger">*</span></label>
                            <?php $__currentLoopData = $chargestypemodule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" name="module[]" id="module<?php echo e($item->id); ?>" class="form-check-input mt-0" value="<?php echo e($item->module_shortcode); ?>">
                                <label for="module<?php echo e($item->id); ?>" class="form-check-label mb-0"><?php echo e($item->charge_type); ?></label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

    <div class="modal fade" id="edit_type" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header rounded-0"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="modal-title" id="addSpecializationLabel">Edit Charge
                    Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('charge_type_module.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('put'); ?>
                    <input type="hidden" name="id">
                    <div class="row gy-3">
                        <div class="col-md-12 border-bottom pb-3">
                            <label for="" class="form-label">Charge Type <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="charge_type" id="edit_charge_type"
                                class="form-control" required>
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

    <script>
        function edit_type(id,charge_type){
            let modal = document.getElementById("edit_type");
            modal.querySelector("input[name='id']").value = id;
            modal.querySelector("input[name='charge_type']").value = charge_type;
        }
        function recordUpdate(target, shortcode, chargeTypeId) {
            const isChecked = target.checked;
        // Show loader
        document.getElementById('loader').style.display = 'block';

            fetch('<?php echo e(route('updateChargeTypeModule')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    charge_type_master_id: chargeTypeId,
                    module_shortcode: shortcode,
                    checked: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                 // Hide loader
                 document.getElementById('loader').style.display = 'none';
                if (data.success) {
                    console.log('Update successful');
                    showToast('Updated successfully');
                } else {
                    alert('Error updating record');
                    // Optionally revert checkbox state
                    target.checked = !isChecked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('loader').style.display = 'none';
                alert('AJAX error');
                // Revert checkbox state
                target.checked = !isChecked;
            });
        }
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');

            toastMsg.innerText = message;
            toast.style.display = 'block';

            setTimeout(() => {
                toast.style.display = 'none';
            }, 2000); // hide after 2 seconds
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/charge_type.blade.php ENDPATH**/ ?>