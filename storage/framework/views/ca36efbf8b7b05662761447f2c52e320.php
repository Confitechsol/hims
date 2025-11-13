
<?php $__env->startSection('content'); ?>

<div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Expense List</h5>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'expense','name' => 'Expense']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'expense','name' => 'Expense']); ?>
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
                                        <table class="table" id="expense">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Invoice Number</th>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                    <th>Expense Head</th>
                                                    <th>Amount (INR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($expense->name); ?></td>
                                                        <td><?php echo e($expense->invoice_no); ?></td>
                                                        <td><?php echo e(\Carbon\Carbon::parse($expense->date)->format('d-m-Y')); ?></td>
                                                        <td><?php echo e($expense->note); ?></td>
                                                        <td><?php echo e($expense->expenseHead->exp_category ?? '-'); ?></td>
                                                        <td><?php echo e($expense->amount); ?></td>
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
                                            $currentPage = $expenses->currentPage();
                                            $lastPage = $expenses->lastPage();
                                        ?>

                                        
                                        <?php if($expenses->onFirstPage()): ?>
                                            <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                        <?php else: ?>
                                            <a href="<?php echo e($expenses->previousPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                « Prev
                                            </a>
                                        <?php endif; ?>

                                        
                                        <?php for($page = 1; $page <= $lastPage; $page++): ?>
                                            <?php if($page == $currentPage): ?>
                                                <button class="btn btn-primary btn-sm me-1"><?php echo e($page); ?></button>
                                            <?php else: ?>
                                                <a href="<?php echo e($expenses->url($page)); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                                    class="btn btn-outline-secondary btn-sm me-1">
                                                    <?php echo e($page); ?>

                                                </a>
                                            <?php endif; ?>
                                        <?php endfor; ?>

                                        
                                        <?php if($expenses->hasMorePages()): ?>
                                            <a href="<?php echo e($expenses->nextPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                                class="btn btn-outline-secondary btn-sm">
                                                Next »
                                            </a>
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
    <?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['type' => 'add','id' => 'createModal','title' => 'Add Expense','action' => ''.e(route('tpamanagement.store')).'','fields' => [
            [
                'name' => 'expense_name',
                'label' => 'Expense Head',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'invoice_number', 'label' => 'Invoice Number', 'type' => 'text', 'required' => true, 'size' => '4'],
           
           [
    'name' => 'date',
    'label' => 'Date',
    'type' => 'date',
    'required' => true,
    'size' => '12'
         ],

           
            [
                'name' => 'amount',
                'label' => 'Amount (INR) ',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            ['name' => 'attach_document', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '6',],

            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
        ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'createModal','title' => 'Add Expense','action' => ''.e(route('tpamanagement.store')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            [
                'name' => 'expense_name',
                'label' => 'Expense Head',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'invoice_number', 'label' => 'Invoice Number', 'type' => 'text', 'required' => true, 'size' => '4'],
           
           [
    'name' => 'date',
    'label' => 'Date',
    'type' => 'date',
    'required' => true,
    'size' => '12'
         ],

           
            [
                'name' => 'amount',
                'label' => 'Amount (INR) ',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            ['name' => 'attach_document', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '6',],

            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Expense','action' => ''.e(route('tpamanagement.update')).'','fields' => [
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'expense_name',
                'label' => 'Expense Head',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'invoice_number', 'label' => 'Invoice Number', 'type' => 'text', 'required' => true, 'size' => '4'],
           
           [
    'name' => 'date',
    'label' => 'Date',
    'type' => 'date',
    'required' => true,
    'size' => '12'
         ],

           
            [
                'name' => 'amount',
                'label' => 'Amount (INR) ',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            ['name' => 'attach_document', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '6',],

            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'text',
                'size' => '6',
            ],
        ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Expense','action' => ''.e(route('tpamanagement.update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'expense_name',
                'label' => 'Expense Head',
                'type' => 'text',
                'required' => true,
                'size' => '5',
            ],
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
            ['name' => 'invoice_number', 'label' => 'Invoice Number', 'type' => 'text', 'required' => true, 'size' => '4'],
           
           [
    'name' => 'date',
    'label' => 'Date',
    'type' => 'date',
    'required' => true,
    'size' => '12'
         ],

           
            [
                'name' => 'amount',
                'label' => 'Amount (INR) ',
                'type' => 'text',
                'required' => true,
                'size' => '6',
            ],
            ['name' => 'attach_document', 'label' => 'Attach Document', 'type' => 'file', 'required' => false, 'size' => '6',],

            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'text',
                'size' => '6',
            ],
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
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/expense/index.blade.php ENDPATH**/ ?>