
<?php $__env->startSection('content'); ?>


<div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Death List</h5>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'death','name' => 'Death Record']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'death','name' => 'Death Record']); ?>
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
                                    <!-- Table start -->
                                    <div class="table-responsive table-nowrap">
                                        <table class="table" id="birth">
                                            <thead class="thead-light">
                                                <tr>			 	
                                                    <th>Reference No</th>					
                                                    <th>Case ID</th>
                                                    <th>Patient Name</th>
                                                    <th>Guardian Name</th>
                                                    <th>Gender</th>
                                                    <th>Death Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                             <?php $__currentLoopData = $deathReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($report->id); ?></td>
                                                        <td><?php echo e($report->case_reference_id); ?></td>
                                                        <td><?php echo e($report->patient->patient_name ?? '-'); ?></td>
                                                        <td><?php echo e($report->guardian_name); ?></td>
                                                        <td><?php echo e($report->patient->gender ?? '-'); ?></td>
                                                        <td><?php echo e(\Carbon\Carbon::parse($report->death_date)->format('d/m/Y h:i A')); ?></td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                    data-id="">
                                                                    <i class="ti ti-pencil"></i>
                                                                </button>
                                                                <form method="POST" action="">

                                                                    <input type="hidden" name="id" value="">
                                                                    <button type="submit"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-3" id="pagination-wrapper">
                                      <?php
                                          $currentPage = $deathReports->currentPage();
                                          $lastPage = $deathReports->lastPage();
                                    ?>

                                    <?php if($deathReports->onFirstPage()): ?>
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    <?php else: ?>
                                    <a href="<?php echo e($deathReports->previousPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                     class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    <?php endif; ?>

                                    <?php for($page = 1; $page <= $lastPage; $page++): ?>
                                    <?php if($page == $currentPage): ?>
                                    <button class="btn btn-primary btn-sm me-1"><?php echo e($page); ?></button>
                                    <?php else: ?>
                                   <a href="<?php echo e($deathReports->url($page)); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                   class="btn btn-outline-secondary btn-sm me-1"><?php echo e($page); ?></a>
                                    <?php endif; ?>
                                    <?php endfor; ?>

                                   <?php if($deathReports->hasMorePages()): ?>
                                   <a href="<?php echo e($deathReports->nextPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                   class="btn btn-outline-secondary btn-sm">Next »</a>
                                   <?php else: ?>
                                  <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                  <?php endif; ?>

                                    </div>
                                </div>
                            </div>
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
    <?php if (isset($component)) { $__componentOriginal5f82aee67f9dd23ba37d94ae9a7bff3d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5f82aee67f9dd23ba37d94ae9a7bff3d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.birth-modal','data' => ['type' => 'add','id' => 'createModal','title' => 'Add Death Record','action' => ''.e(route('tpamanagement.store')).'','fields' => [
            [
                'name' => 'case_id',
                'label' => 'Case ID',
                'type' => 'text',
                'required' => true,
                
            ],
            ['name' => 'patient_name', 'label' => 'Patient Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'death_date', 'label' => 'Death Date', 'type' => 'text', 'required' => true, 'size' => '4'],
            ['name' => 'guardian_name', 'label' => 'Guardian Name ', 'type' => 'text', 'required' => true, 'size' => '12'],
            [
                'name' => 'Report',
                'label' => 'report',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
         ['name' => 'attachment', 'label' => 'Attachment', 'type' => 'file', 'required' => false, 'size' => '6',],

        ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.birth-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'createModal','title' => 'Add Death Record','action' => ''.e(route('tpamanagement.store')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            [
                'name' => 'case_id',
                'label' => 'Case ID',
                'type' => 'text',
                'required' => true,
                
            ],
            ['name' => 'patient_name', 'label' => 'Patient Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'death_date', 'label' => 'Death Date', 'type' => 'text', 'required' => true, 'size' => '4'],
            ['name' => 'guardian_name', 'label' => 'Guardian Name ', 'type' => 'text', 'required' => true, 'size' => '12'],
            [
                'name' => 'Report',
                'label' => 'report',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
         ['name' => 'attachment', 'label' => 'Attachment', 'type' => 'file', 'required' => false, 'size' => '6',],

        ]),'columns' => 3]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5f82aee67f9dd23ba37d94ae9a7bff3d)): ?>
<?php $attributes = $__attributesOriginal5f82aee67f9dd23ba37d94ae9a7bff3d; ?>
<?php unset($__attributesOriginal5f82aee67f9dd23ba37d94ae9a7bff3d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5f82aee67f9dd23ba37d94ae9a7bff3d)): ?>
<?php $component = $__componentOriginal5f82aee67f9dd23ba37d94ae9a7bff3d; ?>
<?php unset($__componentOriginal5f82aee67f9dd23ba37d94ae9a7bff3d); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Death Name','action' => ''.e(route('tpamanagement.update')).'','fields' => [
            ['name' => 'id', 'type' => 'hidden', 'required' => true],

              [
                'name' => 'case_id',
                'label' => 'Case ID',
                'type' => 'text',
                'required' => true,
                
            ],
            ['name' => 'patient_name', 'label' => 'Patient Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'death_date', 'label' => 'Death Date', 'type' => 'text', 'required' => true, 'size' => '4'],
            ['name' => 'guardian_name', 'label' => 'Guardian Name ', 'type' => 'text', 'required' => true, 'size' => '12'],
            [
                'name' => 'Report',
                'label' => 'report',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
         ['name' => 'attachment', 'label' => 'Attachment', 'type' => 'file', 'required' => false, 'size' => '6',],
           
        ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Death Name','action' => ''.e(route('tpamanagement.update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['name' => 'id', 'type' => 'hidden', 'required' => true],

              [
                'name' => 'case_id',
                'label' => 'Case ID',
                'type' => 'text',
                'required' => true,
                
            ],
            ['name' => 'patient_name', 'label' => 'Patient Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'death_date', 'label' => 'Death Date', 'type' => 'text', 'required' => true, 'size' => '4'],
            ['name' => 'guardian_name', 'label' => 'Guardian Name ', 'type' => 'text', 'required' => true, 'size' => '12'],
            [
                'name' => 'Report',
                'label' => 'report',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
         ['name' => 'attachment', 'label' => 'Attachment', 'type' => 'file', 'required' => false, 'size' => '6',],
           
        ]),'columns' => 3]); ?>
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


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/birthordeath/indexdeath.blade.php ENDPATH**/ ?>