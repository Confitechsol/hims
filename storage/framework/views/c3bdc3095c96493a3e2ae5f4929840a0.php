<?php $__env->startSection('content'); ?>

<div class="row justify-content-center">
    
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Medicine Dosage List</h5>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'medicine-dosage','name' => 'Medicine Dosage']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'medicine-dosage','name' => 'Medicine Dosage']); ?>
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
                                    <table class="table mb-0" id="medicine-dosage">
                                        <thead>
                                            <tr>
                                                <th>Category Name</th>
                                                <th>Dosage</th>
                                                <th>Unit</th>
                                                <th style="width: 200px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $medicineDosage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold">
                                                            <?php echo e($item->category["medicine_category"]); ?></h6>
                                                    </td>

                                                    <td><?php echo e($item["dosage"]); ?></td>
                                                    <td><?php echo e($item["unit"]->unit_name); ?></td>
                                                    <td>
                                                        <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="<?php echo e($item["id"]); ?>"
                                                            data-medicine_category="<?php echo e($item->category["id"]); ?>"
                                                            data-dosage="<?php echo e($item['dosage']); ?>"
                                                            data-unit="<?php echo e($item["unit"]->id); ?>">
                                                            <i class="ti ti-pencil"></i></button>
                                                            <form action="<?php echo e(route('medicine-dosage.destroy')); ?>"
                                                            method="POST" style="display:inline-block;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
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
                                
                                <div class="mt-3" id="pagination-wrapper">
                                    <?php
                                        $currentPage = $medicineDosage->currentPage();
                                        $lastPage = $medicineDosage->lastPage();
                                    ?>

                                    
                                    <?php if($medicineDosage->onFirstPage()): ?>
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    <?php else: ?>
                                        <a href="<?php echo e($medicineDosage->previousPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                            class="btn btn-outline-secondary btn-sm me-1">
                                            « Prev
                                        </a>
                                    <?php endif; ?>

                                    
                                    <?php for($page = 1; $page <= $lastPage; $page++): ?>
                                        <?php if($page == $currentPage): ?>
                                            <button class="btn btn-primary btn-sm me-1"><?php echo e($page); ?></button>
                                        <?php else: ?>
                                            <a href="<?php echo e($medicineDosage->url($page)); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                <?php echo e($page); ?>

                                            </a>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    
                                    <?php if($medicineDosage->hasMorePages()): ?>
                                        <a href="<?php echo e($medicineDosage->nextPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
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
<?php
    $options = $units->mapWithKeys(function ($item) {
        return [$item->id => $item->unit_name];
    })->toArray();

    $categories = $medicineCategories->mapWithKeys(function ($item) {
        return [$item->id => $item->medicine_category];
    })->toArray();
?>

<?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['type' => 'add','id' => 'createModal','title' => 'Add Medicine Dosage','action' => ''.e(route('medicine-dosage.store')).'','fields' => [
        ['name' => 'medicine_category', 'label' => 'Medicine Category', 'options' => $categories, 'type' => 'select', 'required' => true, 'size' => '12']
    ],'repeatableGroup' => [
        ['name' => 'dosage', 'label' => 'Dose', 'type' => 'text', 'required' => true,'size'=>'5'],
        ['name' => 'unit', 'label' => 'Unit', 'type' => 'select','options'=>$options, 'required' => true,'size'=>'6']
        ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'createModal','title' => 'Add Medicine Dosage','action' => ''.e(route('medicine-dosage.store')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'medicine_category', 'label' => 'Medicine Category', 'options' => $categories, 'type' => 'select', 'required' => true, 'size' => '12']
    ]),'repeatable_group' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'dosage', 'label' => 'Dose', 'type' => 'text', 'required' => true,'size'=>'5'],
        ['name' => 'unit', 'label' => 'Unit', 'type' => 'select','options'=>$options, 'required' => true,'size'=>'6']
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Medicine Dosage','action' => ''.e(route('medicine-dosage.update')).'','fields' => [
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'medicine_category', 'label' => 'Medicine Category', 'options' => $categories, 'type' => 'select', 'required' => true, 'size' => '12'],
        ['name' => 'dosage', 'label' => 'Dose', 'type' => 'text', 'required' => true, 'size' => '5'],
        ['name' => 'unit', 'label' => 'Unit', 'type' => 'select', 'options' => $options, 'required' => true, 'size' => '6'],
    ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Medicine Dosage','action' => ''.e(route('medicine-dosage.update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'medicine_category', 'label' => 'Medicine Category', 'options' => $categories, 'type' => 'select', 'required' => true, 'size' => '12'],
        ['name' => 'dosage', 'label' => 'Dose', 'type' => 'text', 'required' => true, 'size' => '5'],
        ['name' => 'unit', 'label' => 'Unit', 'type' => 'select', 'options' => $options, 'required' => true, 'size' => '6'],
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
    apiUrl: "<?php echo e(route('medicine-dosage')); ?>",
    tableSelector: "#medicine-dosage",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><h6 class="mb-0 fs-14 fw-semibold">${item.category.medicine_category}</h6></td>
            <td>${item.dosage}</td>
            <td>${item.unit.unit_name}</td>
            <td>
            <button
                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                    data-id="${item.id}"
                    data-medicine_category="${item.category.id}"
                    data-dosage="${item.dosage}"
                    data-unit="${item.unit.id}">
                    <i class="ti ti-pencil"></i>
                </button>
                <form action="<?php echo e(route('medicine-dosage.destroy')); ?>"
                method="POST" style="display:inline-block;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <input type="hidden" name="id" value="${item.id}">
                <button onclick="return confirm('Are you sure?')"
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
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/medicine_dosage.blade.php ENDPATH**/ ?>