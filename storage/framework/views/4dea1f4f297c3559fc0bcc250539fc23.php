
<?php $__env->startSection('content'); ?>

<div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Visitor List</h5>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'visitors','name' => 'Visitor']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'visitors','name' => 'Visitor']); ?>
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
                                        <table class="table" id="visitors">
                                            <thead class="thead-light">
                                                <tr>			 	
                                                    <th>Purpose</th>												
                                                    <th>Name</th>
                                                    <th>Visit To</th>
                                                    <th>Related To</th>
                                                    <th>Phone</th>
                                                    <th>Date</th>
                                                    <th>In Time</th>
                                                    <th>Out Time</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                             <?php $__currentLoopData = $visitorsReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($report->purpose); ?></td>
                                                        <td><?php echo e($report->name); ?></td>
                                                        <td><?php echo e($report->visit_to ?? '-'); ?></td>
                                                        <td><?php echo e($report->related_to ?? '-'); ?></td>
                                                        <td><?php echo e($report->contact ?? '-'); ?></td>
                                                        <td><?php echo e(\Carbon\Carbon::parse($report->date)->format('d-m-Y')); ?></td>
                                                        <td>
                                                          <?php echo e(isset($report->in_time)
                                                          ? \Carbon\Carbon::parse($report->in_time)->format('h:i A')
                                                           : '-'); ?>

                                                         </td>
                                                          <td>
                                                          <?php echo e(isset($report->out_time)
                                                          ? \Carbon\Carbon::parse($report->out_time)->format('h:i A')
                                                           : '-'); ?>

                                                         </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                    data-id="<?php echo e($report->id); ?>"
                                                                    data-name="<?php echo e($report->name); ?>"
                                                                    data-purpose="<?php echo e($report->purpose); ?>"
                                                                    data-contact="<?php echo e($report->contact); ?>"
                                                                    data-id_proof="<?php echo e($report->id_proof); ?>"
                                                                    data-visit_to="<?php echo e($report->visit_to); ?>"
                                                                    data-related_to="<?php echo e($report->related_to); ?>"
                                                                    data-no_of_pepple="<?php echo e($report->no_of_pepple); ?>"
                                                                    data-date="<?php echo e(optional($report->date)->format('Y-m-d') ?? $report->date); ?>"
                                                                    data-in_time="<?php echo e(optional($report->in_time)?->format('H:i') ?? ''); ?>"
                                                                    data-out_time="<?php echo e(optional($report->out_time)?->format('H:i') ?? ''); ?>"
                                                                    data-note="<?php echo e($report->note); ?>">
                                                                    <i class="ti ti-pencil"></i>
                                                                </button>
                                                                <form method="POST" action="<?php echo e(route('visitors.delete', $report->id)); ?>" class="ms-2">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <input type="hidden" name="id" value="<?php echo e($report->id); ?>">
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
                                          $currentPage = $visitorsReports->currentPage();
                                          $lastPage = $visitorsReports->lastPage();
                                    ?>

                                    <?php if($visitorsReports->onFirstPage()): ?>
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    <?php else: ?>
                                    <a href="<?php echo e($visitorsReports->previousPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                     class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    <?php endif; ?>

                                    <?php for($page = 1; $page <= $lastPage; $page++): ?>
                                    <?php if($page == $currentPage): ?>
                                    <button class="btn btn-primary btn-sm me-1"><?php echo e($page); ?></button>
                                    <?php else: ?>
                                   <a href="<?php echo e($visitorsReports->url($page)); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                   class="btn btn-outline-secondary btn-sm me-1"><?php echo e($page); ?></a>
                                    <?php endif; ?>
                                    <?php endfor; ?>

                                   <?php if($visitorsReports->hasMorePages()): ?>
                                   <a href="<?php echo e($visitorsReports->nextPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
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

    <?php
        $purposeOptions = [];
        if (!empty($purposes)) {
            $purposeOptions = collect($purposes)->mapWithKeys(function ($item) {
                return [$item->visitors_purpose => $item->visitors_purpose];
            })->toArray();
        }
        
        $visitToDropdown = [];
        if (!empty($visitToOptions)) {
            $visitToDropdown = array_combine($visitToOptions, $visitToOptions);
        }
        
        $relatedToDropdown = [];
        if (!empty($relatedToOptions)) {
            $relatedToDropdown = array_combine($relatedToOptions, $relatedToOptions);
        }
    ?>

    <?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['type' => 'add','id' => 'createModal','title' => 'Add Visitor','action' => ''.e(route('visitors.create')).'','fields' => [
            [
                'name' => 'purpose',
                'label' => 'Purpose',
                'type' => 'select',
                'options' => $purposeOptions,
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'id_proof', 'label' => 'ID Card', 'type' => 'text', 'required' => false, 'size' => '12'],
            [
                'name' => 'visit_to',
                'label' => 'Visit To',
                'type' => 'select',
                'options' => $visitToDropdown,
                'required' => false,
                'size' => '4',
            ],
            [
                'name' => 'related_to',
                'label' => 'Related To',
                'type' => 'select',
                'options' => $relatedToDropdown,
                'required' => false,
                'size' => '4',
            ],
            ['name' => 'no_of_pepple', 'label' => 'Number Of Person', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'in_time', 'label' => 'In Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'out_time', 'label' => 'Out Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
            ['name' => 'image', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '12'],
        ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'createModal','title' => 'Add Visitor','action' => ''.e(route('visitors.create')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            [
                'name' => 'purpose',
                'label' => 'Purpose',
                'type' => 'select',
                'options' => $purposeOptions,
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'id_proof', 'label' => 'ID Card', 'type' => 'text', 'required' => false, 'size' => '12'],
            [
                'name' => 'visit_to',
                'label' => 'Visit To',
                'type' => 'select',
                'options' => $visitToDropdown,
                'required' => false,
                'size' => '4',
            ],
            [
                'name' => 'related_to',
                'label' => 'Related To',
                'type' => 'select',
                'options' => $relatedToDropdown,
                'required' => false,
                'size' => '4',
            ],
            ['name' => 'no_of_pepple', 'label' => 'Number Of Person', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'in_time', 'label' => 'In Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'out_time', 'label' => 'Out Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
            ['name' => 'image', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '12'],
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

    <?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Visitor','action' => ''.e(url('/visitors/update')).'','fields' => [
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'purpose',
                'label' => 'Purpose',
                'type' => 'select',
                'options' => $purposeOptions,
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'id_proof', 'label' => 'ID Card', 'type' => 'text', 'required' => false, 'size' => '12'],
            [
                'name' => 'visit_to',
                'label' => 'Visit To',
                'type' => 'select',
                'options' => $visitToDropdown,
                'required' => false,
                'size' => '4',
            ],
            [
                'name' => 'related_to',
                'label' => 'Related To',
                'type' => 'select',
                'options' => $relatedToDropdown,
                'required' => false,
                'size' => '4',
            ],
            ['name' => 'no_of_pepple', 'label' => 'Number Of Person', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'in_time', 'label' => 'In Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'out_time', 'label' => 'Out Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
            ['name' => 'image', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '12'],
        ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Visitor','action' => ''.e(url('/visitors/update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'purpose',
                'label' => 'Purpose',
                'type' => 'select',
                'options' => $purposeOptions,
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'id_proof', 'label' => 'ID Card', 'type' => 'text', 'required' => false, 'size' => '12'],
            [
                'name' => 'visit_to',
                'label' => 'Visit To',
                'type' => 'select',
                'options' => $visitToDropdown,
                'required' => false,
                'size' => '4',
            ],
            [
                'name' => 'related_to',
                'label' => 'Related To',
                'type' => 'select',
                'options' => $relatedToDropdown,
                'required' => false,
                'size' => '4',
            ],
            ['name' => 'no_of_pepple', 'label' => 'Number Of Person', 'type' => 'text', 'required' => false, 'size' => '4'],
            ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
            ['name' => 'in_time', 'label' => 'In Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'out_time', 'label' => 'Out Time', 'type' => 'time', 'required' => false, 'size' => '4'],
            ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
            ['name' => 'image', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '12'],
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
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/front-office/visitorlist.blade.php ENDPATH**/ ?>