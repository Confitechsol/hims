<?php $__env->startSection('content'); ?>

<div class="row justify-content-center">
    
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Dosage Interval List</h5>
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
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success">
                                        <?php echo e(session('success')); ?>

                                    </div>
                                <?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal7c6bc96f59264604a162cf868fce49e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c6bc96f59264604a162cf868fce49e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'dosage-interval','name' => 'Dosage Interval']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'dosage-interval','name' => 'Dosage Interval']); ?>
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
                                    <table class="table mb-0" id="dosage-interval">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th style="width: 200px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $dosageInterval; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($item->name); ?></h6>
                                                    </td>
                                                    <td>
                                                        <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="<?php echo e($item["id"]); ?>" data-name="<?php echo e($item["name"]); ?>">
                                                            <i class="ti ti-pencil"></i></button>
                                                        <form action="<?php echo e(route('dosage-interval.destroy')); ?>" method="POST"
                                                            style="display:inline-block;" onsubmit="return confirmDeleteForm(event, 'Delete Interval?', 'Are you sure you want to delete this interval?');">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
                                                            <button type="submit"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i
                                                                    class="ti ti-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-3" id="pagination-wrapper">
                                    <?php
                                        $currentPage = $dosageInterval->currentPage();
                                        $lastPage = $dosageInterval->lastPage();
                                    ?>

                                    
                                    <?php if($dosageInterval->onFirstPage()): ?>
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    <?php else: ?>
                                        <a href="<?php echo e($dosageInterval->previousPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                            class="btn btn-outline-secondary btn-sm me-1">
                                            « Prev
                                        </a>
                                    <?php endif; ?>

                                    
                                    <?php for($page = 1; $page <= $lastPage; $page++): ?>
                                        <?php if($page == $currentPage): ?>
                                            <button class="btn btn-primary btn-sm me-1"><?php echo e($page); ?></button>
                                        <?php else: ?>
                                            <a href="<?php echo e($dosageInterval->url($page)); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                <?php echo e($page); ?>

                                            </a>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    
                                    <?php if($dosageInterval->hasMorePages()): ?>
                                        <a href="<?php echo e($dosageInterval->nextPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                            class="btn btn-outline-secondary btn-sm">
                                            Next »
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                    <?php endif; ?>
                                </div>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                </div>

            </div>
        </div>
    </div>
</div>
<?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['type' => 'add','id' => 'createModal','title' => 'Add Dosage Interval','action' => ''.e(route('dosage-interval.store')).'','repeatableGroup' => [
        ['name' => 'name', 'label' => 'Dosage Interval', 'type' => 'text', 'required' => true, 'size' => '11']
    ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'createModal','title' => 'Add Dosage Interval','action' => ''.e(route('dosage-interval.store')).'','repeatable_group' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'name', 'label' => 'Dosage Interval', 'type' => 'text', 'required' => true, 'size' => '11']
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Dosage Interval','action' => ''.e(route('dosage-interval.update')).'','fields' => [
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'name', 'label' => 'Interval', 'type' => 'text', 'required' => true, 'size' => '12'],
    ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Dosage Interval','action' => ''.e(route('dosage-interval.update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'name', 'label' => 'Interval', 'type' => 'text', 'required' => true, 'size' => '12'],
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
    apiUrl: "<?php echo e(route('dosage-interval')); ?>",
    tableSelector: "#dosage-interval",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><h6 class="mb-0 fs-14 fw-semibold">${item.name}</h6></td>
            <td>
            <button
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                    data-id="${item.id}"
                    data-name="${item.name}">
                    <i class="ti ti-pencil"></i>
                </button>
                <form action="<?php echo e(route('dosage-interval.destroy')); ?>"
                method="POST" style="display:inline-block;" onsubmit="return confirmDeleteForm(event, 'Delete Interval?', 'Are you sure you want to delete this interval?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <input type="hidden" name="id" value="${item.id}">
                <button type="submit"
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"><i
                class="ti ti-trash"></i></button>
            </form>
            </td>
        `;
        return row;
    }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\dosage_interval.blade.php ENDPATH**/ ?>