<?php $__env->startSection('content'); ?>


    <style>
        .sidebars.settings-sidebar {
            width: 250px !important;
        }

        .module_billing {
            border-radius: 8px;
            color: #fff;
            background-color: #ab00db;
            width: 100%;
            padding: 20px;
            box-shadow: 5px 5px 8px 0px #bbbbbb;
            min-height: 110px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .module_billing h5 {
            color: #fff;
            font-size: 18px;
        }

        .gray_text {
            color: #d2d2d2ff;
        }
    </style>



    <div class="content">

        <!-- page header start -->
        <div class="mb-4">
            <h6 class="fw-bold mb-0 d-flex align-items-center"> <a href="patients.html" class="text-dark"> <i
                        class="ti ti-chevron-left me-1"></i>TPA Details</a></h6>
        </div>
        <!-- page header end -->

        <!-- card start -->
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="tpa_details p-4">
                        <div class="row gy-4">
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>TPA Name</h5>
                                    <p class="gray_text"><?php echo e($organisations->organisation_name); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5> Code</h5>
                                    <p class="gray_text"><?php echo e($organisations->code); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>Contact No</h5>
                                    <p class="gray_text"><?php echo e($organisations->contact_no); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>Address</h5>
                                    <p class="gray_text"><?php echo e($organisations->address); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>Contact Person Name</h5>
                                    <p class="gray_text"><?php echo e($organisations->contact_person_name); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="module_billing">
                                    <h5>Contact Person Phone</h5>
                                    <p class="gray_text"><?php echo e($organisations->contact_person_phone); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- card end -->


        <!-- row start -->
        <div class="row">
            <div class="col-12 d-flex">
                <div class="card shadow-sm border-0 w-100">
                    <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>TPA Details
                        </h5>
                    </div>

                    <div class="card-body" id="charge_type_form">
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
                        <form action="" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="d-flex gap-3 align-items-center">
                                <div class="col-md-1">
                                    <label for="case_id" class="form-label">Charge Type<span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="charge-type" name="charge_type" style="width: 100%;">
                                        <option value="">Select</option>
                                        <?php $__currentLoopData = $chargetypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chargetype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($chargetype->id); ?>" <?php echo e(session('charge_type') == $chargetype->id ? 'selected' : ''); ?>><?php echo e($chargetype->charge_type); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <!-- Table start -->
                    <div class="table-responsive table-nowrap">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Charge Type</th>
                                    <th>Charge Category</th>
                                    <th>Charge Name</th>
                                    <th>Description</th>
                                    <th>Standard Charge (INR)</th>
                                    <th>TPA Charge (INR)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($organisationCharge) && $organisationCharge->count() > 0): ?>
                                <?php $__currentLoopData = $organisationCharge; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <h6 class="fs-14 mb-1"><?php echo e($item->charge['category']['chargeType']['charge_type']); ?></h6>
                                    </td>
                                    <td><?php echo e($item->charge['category']['name']); ?></td>
                                    <td><?php echo e($item->charge['name']); ?></td>
                                    <td><?php echo e($item->charge['description']); ?></td>
                                    <td><?php echo e($item->charge['standard_charge']); ?></td>
                                    <td><?php echo e($item->org_charge); ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <button
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                data-id="<?php echo e($item->id); ?>"
                                                data-org_charge="<?php echo e($item->org_charge); ?>"
                                                >
                                                <i class="ti ti-pencil" data-bs-toggle="tooltip" title="Show"></i>
                                            </button>
                                            <form method="POST" action="<?php echo e(route('tpa_details.destroy')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
                                                <button type="submit"
                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                    onclick="return confirm('Are you sure you want to delete this item?');">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Table end -->
                </div><!-- end card -->
            </div>
        </div>
        <!-- row end -->
    </div>
    <!-- Modal -->
    <?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit TPA Charge','action' => ''.e(route('tpa_details.update')).'','fields' => [
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'org_charge', 'label' => 'TPA Charge (INR)', 'type' => 'text', 'required' => true,'size'=>'12']
    ],'columns' => 1]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit TPA Charge','action' => ''.e(route('tpa_details.update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'org_charge', 'label' => 'TPA Charge (INR)', 'type' => 'text', 'required' => true,'size'=>'12']
    ]),'columns' => 1]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a)): ?>
<?php $attributes = $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a; ?>
<?php unset($__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66ca70ec79ff22faa62f501a1b49a88a)): ?>
<?php $component = $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a; ?>
<?php unset($__componentOriginal66ca70ec79ff22faa62f501a1b49a88a); ?>
<?php endif; ?>
    <!-- end madal -->
    <style>
        /* Ensure Select2 dropdown search input is visible (overrides theme rules) */
        .select2-container .select2-search--dropdown { display: block !important; }
        .select2-container .select2-search__field { display: block !important; }
    </style>

    <script>
        $(document).ready(function () {
            $('#charge-type').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true,
                dropdownParent: $('#charge_type_form'),
                // always show the search box even for small option sets
                minimumResultsForSearch: 0

            });
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/tpa/tpa_details.blade.php ENDPATH**/ ?>