
<?php $__env->startSection('content'); ?>

<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-mail me-2"></i><?php echo e($type); ?> List</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a href="<?php echo e(route('visitors')); ?>" class="btn btn-secondary text-white fs-13 btn-md"><i class="ti ti-arrow-left me-1"></i>Back to Visitors</a>
                        <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#createModal">Add <?php echo e($type); ?></button>
                    </div>
                    <div>
                        <!-- export/copy buttons could go here -->
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>To Title</th>
                                <th>Reference No</th>
                                <th>From Title</th>
                              
                                <th>Date</th>
                                
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($item->to_title); ?></td>
                                    <td><?php echo e($item->reference_no); ?></td>
                                    <td><?php echo e($item->from_title); ?></td>
                                    <td><?php echo e(optional($item->date)->format('d-m-Y') ?? '-'); ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <button
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success me-2 edit-btn"
                                                data-id="<?php echo e($item->id); ?>"
                                                data-reference_no="<?php echo e($item->reference_no); ?>"
                                                data-from_title="<?php echo e($item->from_title); ?>"
                                                data-to_title="<?php echo e($item->to_title); ?>"
                                                data-address="<?php echo e($item->address); ?>"
                                                data-note="<?php echo e($item->note); ?>"
                                                data-date="<?php echo e(optional($item->date)->format('Y-m-d') ?? ''); ?>"
                                                data-type="<?php echo e($item->type); ?>"
                                                title="Edit">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <form method="POST" action="<?php echo e(route('dispatch.destroy', $item->id)); ?>" class="ms-2">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
                                                <button type="submit" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill" title="Delete"><i class="ti ti-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($items->links()); ?>

                </div>

                <!-- Create Modal -->
                <?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['type' => 'add','id' => 'createModal','title' => 'Add '.e($type).'','action' => ''.e(route('dispatch.store')).'','fields' => [
                    ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['receive' => 'Receive', 'dispatch' => 'Dispatch'], 'required' => true, 'size' => '6'],
                    ['name' => 'reference_no', 'label' => 'Reference No', 'type' => 'text', 'required' => true, 'size' => '6'],
                    ['name' => 'from_title', 'label' => 'From Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'to_title', 'label' => 'To Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => false, 'size' => '6'],
                    ['name' => 'image', 'label' => 'Attach Image', 'type' => 'file', 'required' => false, 'size' => '6'],
                ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'createModal','title' => 'Add '.e($type).'','action' => ''.e(route('dispatch.store')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['receive' => 'Receive', 'dispatch' => 'Dispatch'], 'required' => true, 'size' => '6'],
                    ['name' => 'reference_no', 'label' => 'Reference No', 'type' => 'text', 'required' => true, 'size' => '6'],
                    ['name' => 'from_title', 'label' => 'From Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'to_title', 'label' => 'To Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => false, 'size' => '6'],
                    ['name' => 'image', 'label' => 'Attach Image', 'type' => 'file', 'required' => false, 'size' => '6'],
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

                <!-- Edit Modal -->
                <?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit '.e($type).'','action' => ''.e(url('/dispatch-receive/update')).'','fields' => [
                    ['name' => 'id', 'type' => 'hidden', 'required' => true],
                    ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['receive' => 'Receive', 'dispatch' => 'Dispatch'], 'required' => true, 'size' => '6'],
                    ['name' => 'reference_no', 'label' => 'Reference No', 'type' => 'text', 'required' => true, 'size' => '6'],
                    ['name' => 'from_title', 'label' => 'From Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'to_title', 'label' => 'To Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => false, 'size' => '6'],
                    ['name' => 'image', 'label' => 'Attach Image', 'type' => 'file', 'required' => false, 'size' => '6'],
                ],'columns' => 2]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit '.e($type).'','action' => ''.e(url('/dispatch-receive/update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    ['name' => 'id', 'type' => 'hidden', 'required' => true],
                    ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['receive' => 'Receive', 'dispatch' => 'Dispatch'], 'required' => true, 'size' => '6'],
                    ['name' => 'reference_no', 'label' => 'Reference No', 'type' => 'text', 'required' => true, 'size' => '6'],
                    ['name' => 'from_title', 'label' => 'From Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'to_title', 'label' => 'To Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => false, 'size' => '6'],
                    ['name' => 'image', 'label' => 'Attach Image', 'type' => 'file', 'required' => false, 'size' => '6'],
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

                <?php $__env->startPush('scripts'); ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        document.querySelectorAll('.edit-btn').forEach(function (btn) {
                            btn.addEventListener('click', function () {
                                var id = this.dataset.id;
                                var setValue = function (name, value) {
                                    var el = document.querySelector('#edit_modal [name="' + name + '"]');
                                    if (!el) return;
                                    if (el.tagName === 'SELECT') el.value = value;
                                    else if (el.type === 'file') return;
                                    else el.value = value;
                                };

                                setValue('id', id);
                                setValue('reference_no', this.dataset.reference_no || '');
                                setValue('from_title', this.dataset.from_title || '');
                                setValue('to_title', this.dataset.to_title || '');
                                setValue('address', this.dataset.address || '');
                                setValue('note', this.dataset.note || '');
                                setValue('date', this.dataset.date || '');
                                setValue('type', this.dataset.type || 'receive');

                                var actionUrl = '<?php echo e(url('/dispatch-receive/update')); ?>';
                                var modalForm = document.querySelector('#edit_modal form');
                                if (modalForm) {
                                    modalForm.action = actionUrl + '/' + id;
                                }

                                var modal = new bootstrap.Modal(document.getElementById('edit_modal'));
                                modal.show();
                            });
                        });
                    });
                </script>
                <?php $__env->stopPush(); ?>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/front-office/dispatch-receive/index.blade.php ENDPATH**/ ?>