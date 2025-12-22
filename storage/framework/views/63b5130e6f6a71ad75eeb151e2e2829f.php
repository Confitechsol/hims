<?php $__env->startSection('content'); ?>
    <!-- row start -->
    <div class="row px-5 py-4">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Income List</h5>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'income','name' => 'Income']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'income','name' => 'Income']); ?>
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
                                        <table class="table" id="income">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Invoice Number</th>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                    <th>Income Head</th>
                                                    <th>Amount (INR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $incomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($income->name); ?></td>
                                                        <td><?php echo e($income->invoice_no); ?></td>
                                                        <td><?php echo e(\Carbon\Carbon::parse($income->date)->format('d-m-Y')); ?></td>
                                                        <td><?php echo e($income->note); ?></td>
                                                        <td><?php echo e($income->incomeHead->income_category ?? '-'); ?></td>
                                                        <td><?php echo e($income->amount); ?></td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                                    data-id="<?php echo e($income->id); ?>"
                                                                    data-name="<?php echo e($income->name); ?>"
                                                                    data-invoice_no="<?php echo e($income->invoice_no); ?>"
                                                                    data-date="<?php echo e($income->date); ?>"
                                                                    data-note="<?php echo e($income->note); ?>"
                                                                    data-income_head_id="<?php echo e($income->incomeHead->id ?? '-'); ?>"
                                                                    data-amount="<?php echo e($income->amount); ?>"
                                                                    >
                                                                    <i class="ti ti-pencil"></i>
                                                                </button>
                                                                <form method="POST" action="<?php echo e(route('income.destroy')); ?>" onsubmit="return confirm('Are you Sure?')">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <input type="hidden" name="id" value="<?php echo e($income->id); ?>">
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
                                            $currentPage = $incomes->currentPage();
                                            $lastPage = $incomes->lastPage();
                                        ?>

                                        
                                        <?php if($incomes->onFirstPage()): ?>
                                            <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                        <?php else: ?>
                                            <a href="<?php echo e($incomes->previousPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                « Prev
                                            </a>
                                        <?php endif; ?>

                                        
                                        <?php for($page = 1; $page <= $lastPage; $page++): ?>
                                            <?php if($page == $currentPage): ?>
                                                <button class="btn btn-primary btn-sm me-1"><?php echo e($page); ?></button>
                                            <?php else: ?>
                                                <a href="<?php echo e($incomes->url($page)); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                                    class="btn btn-outline-secondary btn-sm me-1">
                                                    <?php echo e($page); ?>

                                                </a>
                                            <?php endif; ?>
                                        <?php endfor; ?>

                                        
                                        <?php if($incomes->hasMorePages()): ?>
                                            <a href="<?php echo e($incomes->nextPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
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
    <?php
    $options = $incomeHeads->mapWithKeys(function ($item) {
        return [$item->id => $item->income_category];
    })->toArray();
    ?>
    <?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['type' => 'add','id' => 'createModal','title' => 'Add Income','action' => ''.e(route('income.create')).'','fields' => [
            [
                'name' => 'income_head_id',
                'label' => 'Income Head',
                'type' => 'select',
                'options'=>$options,
                'required' => true,
            ],
            [
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'invoice_no',
                'label' => 'Invoice Number',
                'type' => 'text',
            ],
            [
                'name' => 'date',
                'label' => 'Date',
                'type' => 'date',
                'required' => true,
            ],
            [
                'name' => 'amount',
                'label' => 'Amount (INR)',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'document',
                'label' => 'Attach Document',
                'type' => 'file',
            ],
            [
                'name' => 'note',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => true,
                'size' => '12',
            ],
            
        ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'createModal','title' => 'Add Income','action' => ''.e(route('income.create')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            [
                'name' => 'income_head_id',
                'label' => 'Income Head',
                'type' => 'select',
                'options'=>$options,
                'required' => true,
            ],
            [
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'invoice_no',
                'label' => 'Invoice Number',
                'type' => 'text',
            ],
            [
                'name' => 'date',
                'label' => 'Date',
                'type' => 'date',
                'required' => true,
            ],
            [
                'name' => 'amount',
                'label' => 'Amount (INR)',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'document',
                'label' => 'Attach Document',
                'type' => 'file',
            ],
            [
                'name' => 'note',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => true,
                'size' => '12',
            ],
            
        ]),'columns' => 2]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Company Name','action' => ''.e(url('/income/update')).'','fields' => [
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'income_head_id',
                'label' => 'Income Head',
                'type' => 'select',
                'options'=>$options,
                'required' => true,
            ],
            [
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'invoice_no',
                'label' => 'Invoice Number',
                'type' => 'text',
            ],
            [
                'name' => 'date',
                'label' => 'Date',
                'type' => 'date',
                'required' => true,
            ],
            [
                'name' => 'amount',
                'label' => 'Amount (INR)',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'document',
                'label' => 'Attach Document',
                'type' => 'file',
            ],
            [
                'name' => 'note',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => true,
                'size' => '12',
            ],
        ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Company Name','action' => ''.e(url('/income/update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['name' => 'id', 'type' => 'hidden', 'required' => true],
            [
                'name' => 'income_head_id',
                'label' => 'Income Head',
                'type' => 'select',
                'options'=>$options,
                'required' => true,
            ],
            [
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'invoice_no',
                'label' => 'Invoice Number',
                'type' => 'text',
            ],
            [
                'name' => 'date',
                'label' => 'Date',
                'type' => 'date',
                'required' => true,
            ],
            [
                'name' => 'amount',
                'label' => 'Amount (INR)',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'document',
                'label' => 'Attach Document',
                'type' => 'file',
            ],
            [
                'name' => 'note',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => true,
                'size' => '12',
            ],
        ]),'columns' => 2]); ?>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
createAjaxTable({
    apiUrl: "<?php echo e(route('income')); ?>",
    tableSelector: "#income",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.invoice_no}</td>
            <td>${item.date}</td>
            <td>${item.note}</td>
            <td>${item.income_head.income_category}</td>
            <td>${item.amount}</td>
            <td>
                <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill" data-bs-toggle="modal" data-bs-target="#editModal"
                    data-id="${item.id}"
                    data-name="${item.name}">
                    <i class="ti ti-pencil"></i>
                </button>
                <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill" data-bs-toggle="modal"
                data-bs-target="#deleteModal" data-id="${item.id}"
                data-name="${item.name}">
                <i class="ti ti-trash"></i>
                </button>
            </form>
            </td>
        `;
        return row;
    }
    });  
}); 
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u676663263/domains/confitechone.com/public_html/hims/resources/views/admin/income/index.blade.php ENDPATH**/ ?>