<?php $__env->startSection('content'); ?>

<div class="row justify-content-center">
    
    <div class="col-md-11">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Supplier List</h5>
            </div>

            <div class="card-body">


                
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <?php if (isset($component)) { $__componentOriginal7c6bc96f59264604a162cf868fce49e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c6bc96f59264604a162cf868fce49e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'supplier','name' => 'Supplier']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'supplier','name' => 'Supplier']); ?>
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
                                
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success mt-2"><?php echo e(session('success')); ?></div>
                                <?php endif; ?>
                                <?php if($errors->any()): ?>
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($error); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <div class="table-responsive">
                                    <table class="table mb-0" id="supplier">
                                        <thead>
                                            <tr>
                                                <th>Supplier Name</th>
                                                <th>Supplier Contact</th>
                                                <th>Contact Person Name</th>
                                                <th>Contact Person Phone</th>
                                                <th>Drug License Number</th>
                                                <th>Address</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $medicineSuppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicineSupplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0 fs-14 fw-semibold"><?php echo e($medicineSupplier->supplier); ?>

                                                        </h6>
                                                    </td>
                                                    <td><?php echo e($medicineSupplier->contact); ?></td>
                                                    <td><?php echo e($medicineSupplier->supplier_person); ?></td>
                                                    <td><?php echo e($medicineSupplier->supplier_person_contact); ?></td>
                                                    <td><?php echo e($medicineSupplier->supplier_drug_licence); ?></td>
                                                    <td><?php echo e($medicineSupplier->address); ?></td>
                                                    <td>
                                                        <!-- <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a> -->
                                                            <button
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                                            data-id="<?php echo e($medicineSupplier->id); ?>"
                                                            data-supplier_name="<?php echo e($medicineSupplier->supplier); ?>"
                                                            data-supplier_contact="<?php echo e($medicineSupplier->contact); ?>"
                                                            data-contact_person_name="<?php echo e($medicineSupplier->supplier_person); ?>"
                                                            data-contact_person_phone="<?php echo e($medicineSupplier->supplier_person_contact); ?>"
                                                            data-licence="<?php echo e($medicineSupplier->supplier_drug_licence); ?>"
                                                            data-address="<?php echo e($medicineSupplier->address); ?>"
                                                            >
                                                            <i class="ti ti-pencil"></i></button>
                                                        <form action="<?php echo e(route('supplier.destroy')); ?>" method="POST"
                                                            style="display:inline-block;" onsubmit="return confirmDeleteForm(event, 'Delete Supplier?', 'Are you sure you want to delete this supplier?');">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <input type="hidden" name="id"
                                                                value="<?php echo e($medicineSupplier->id); ?>">
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
                                        $currentPage = $medicineSuppliers->currentPage();
                                        $lastPage = $medicineSuppliers->lastPage();
                                    ?>

                                    
                                    <?php if($medicineSuppliers->onFirstPage()): ?>
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    <?php else: ?>
                                        <a href="<?php echo e($medicineSuppliers->previousPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                            class="btn btn-outline-secondary btn-sm me-1">
                                            « Prev
                                        </a>
                                    <?php endif; ?>

                                    
                                    <?php for($page = 1; $page <= $lastPage; $page++): ?>
                                        <?php if($page == $currentPage): ?>
                                            <button class="btn btn-primary btn-sm me-1"><?php echo e($page); ?></button>
                                        <?php else: ?>
                                            <a href="<?php echo e($medicineSuppliers->url($page)); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                                class="btn btn-outline-secondary btn-sm me-1">
                                                <?php echo e($page); ?>

                                            </a>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    
                                    <?php if($medicineSuppliers->hasMorePages()): ?>
                                        <a href="<?php echo e($medicineSuppliers->nextPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
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
<!-- Modal -->
<!-- <div class="modal fade" id="add_supplier" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header rounded-0"
                                                        style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                        <h5 class="modal-title" id="addSpecializationLabel">Add Supplier
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="row gy-3">

                                                                <div class="col-md-6">
                                                                    <label for="supplier_name" class="form-label">Supplier
                                                                        Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="supplier_name"
                                                                        id="supplier_name" class="form-control" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="supplier_contact"
                                                                        class="form-label">Supplier Contact </label>
                                                                    <input type="text" name="supplier_contact"
                                                                        id="supplier_contact" class="form-control">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="person_name" class="form-label">Contact
                                                                        Person Name </label>
                                                                    <input type="text" name="person_name" id="person_name"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="person_phone" class="form-label">Contact
                                                                        Person Phone </label>
                                                                    <input type="text" name="person_phone" id="person_phone"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label for="license_name" class="form-label">Drug
                                                                        License Number </label>
                                                                    <input type="text" name="license_name" id="license_name"
                                                                        class="form-control">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <label for="address" class="form-label">Address </label>
                                                                    <input type="text" name="address" id="address"
                                                                        class="form-control">
                                                                </div>
                                                            </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> -->
<!-- ['name' => 'specialization', 'label' => 'Specialization', 'type' => 'select', 'options' => $specializations, 'required' => true], -->
<?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['id' => 'edit_modal','title' => 'Edit Supplier','action' => ''.e(route('supplier.update')).'','method' => 'put','type' => 'edit','fields' => [
        ['name' => 'id', 'label' => '', 'type' => 'hidden', 'required' => true],
        ['name' => 'supplier_name', 'label' => 'Supplier Name', 'type' => 'text', 'required' => true],
        ['name' => 'supplier_contact', 'label' => 'Supplier Contact', 'type' => 'text'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text'],
        ['name' => 'licence', 'label' => 'Drug License Number', 'type' => 'text'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text'],
    ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'edit_modal','title' => 'Edit Supplier','action' => ''.e(route('supplier.update')).'','method' => 'put','type' => 'edit','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'id', 'label' => '', 'type' => 'hidden', 'required' => true],
        ['name' => 'supplier_name', 'label' => 'Supplier Name', 'type' => 'text', 'required' => true],
        ['name' => 'supplier_contact', 'label' => 'Supplier Contact', 'type' => 'text'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text'],
        ['name' => 'licence', 'label' => 'Drug License Number', 'type' => 'text'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text'],
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['id' => 'createModal','title' => 'Add Supplier','action' => ''.e(route('supplier-store')).'','fields' => [
        ['name' => 'supplier_name', 'label' => 'Supplier Name', 'type' => 'text', 'required' => true],
        ['name' => 'supplier_contact', 'label' => 'Supplier Contact', 'type' => 'text'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text'],
        ['name' => 'licence', 'label' => 'Drug License Number', 'type' => 'text'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text'],
    ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'createModal','title' => 'Add Supplier','action' => ''.e(route('supplier-store')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'supplier_name', 'label' => 'Supplier Name', 'type' => 'text', 'required' => true],
        ['name' => 'supplier_contact', 'label' => 'Supplier Contact', 'type' => 'text'],
        ['name' => 'contact_person_name', 'label' => 'Contact Person Name', 'type' => 'text'],
        ['name' => 'contact_person_phone', 'label' => 'Contact Person Phone', 'type' => 'text'],
        ['name' => 'licence', 'label' => 'Drug License Number', 'type' => 'text'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'text'],
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
    apiUrl: "<?php echo e(route('supplier')); ?>",
    tableSelector: "#supplier",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
        <td>
            <h6 class="mb-0 fs-14 fw-semibold">${item.supplier}
            </h6>
        </td>
        <td>${item.contact}</td>
        <td>${item.supplier_person}</td>
        <td>${item.supplier_person_contact}</td>
        <td>${item.supplier_drug_licence}</td>
        <td>${item.address}</td>
        <td>
        <button
            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
            data-id="${item.id}"
            data-supplier_name="${item.supplier}"
            data-supplier_contact="${item.contact}"
            data-contact_person_name="${item.supplier_person}"
            data-contact_person_phone="${item.supplier_person_contact}"
            data-licence="${item.supplier_drug_licence}"
            data-address="${item.address}"
        >
            <i class="ti ti-pencil"></i>
        </button>
        <form action="<?php echo e(route('supplier.destroy')); ?>" method="POST" style="display:inline-block;" onsubmit="return confirmDeleteForm(event, 'Delete Supplier?', 'Are you sure you want to delete this supplier?');">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id" value="${item.id}">
            <button type="submit"
                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
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
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\setup\supplier.blade.php ENDPATH**/ ?>