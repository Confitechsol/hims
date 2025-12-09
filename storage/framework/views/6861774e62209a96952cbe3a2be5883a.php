<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Floors</h5>
            </div>

            <div class="card-body">
            <?php if (isset($component)) { $__componentOriginal7c6bc96f59264604a162cf868fce49e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c6bc96f59264604a162cf868fce49e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-actions.actions','data' => ['id' => 'floors','name' => 'Floor']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-actions.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'floors','name' => 'Floor']); ?>
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
                    <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                
                 <div class="table-responsive">
                    <table class="table table-bordered" id="floors">
                        <thead>
                            <tr>
                                <th>Floor Name</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $floors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($floor->name); ?></td>
                                    <td>
                                        
                                        <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill" data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id="<?php echo e($floor->id); ?>" data-name="<?php echo e($floor->name); ?>">
                                            <i class="ti ti-pencil"></i>
                                        </button>

                                        
                                        <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="<?php echo e($floor->id); ?>"
                                            data-name="<?php echo e($floor->name); ?>">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                 </div>
                    
                <div class="mt-3" id="pagination-wrapper">
                    <?php
                        $currentPage = $floors->currentPage();
                        $lastPage = $floors->lastPage();
                    ?>

                    
                    <?php if($floors->onFirstPage()): ?>
                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                    <?php else: ?>
                        <a href="<?php echo e($floors->previousPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                            class="btn btn-outline-secondary btn-sm me-1">
                            « Prev
                        </a>
                    <?php endif; ?>

                    
                    <?php for($page = 1; $page <= $lastPage; $page++): ?>
                        <?php if($page == $currentPage): ?>
                            <button class="btn btn-primary btn-sm me-1"><?php echo e($page); ?></button>
                        <?php else: ?>
                            <a href="<?php echo e($floors->url($page)); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
                                class="btn btn-outline-secondary btn-sm me-1">
                                <?php echo e($page); ?>

                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    
                    <?php if($floors->hasMorePages()): ?>
                        <a href="<?php echo e($floors->nextPageUrl()); ?><?php echo e(request('perPage') ? '&perPage=' . request('perPage') : ''); ?>"
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

    
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?php echo e(route('floors.store')); ?>" method="POST" class="modal-content">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add Floor</h5>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="name" placeholder="Enter floor name" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?php echo e(route('floors.update')); ?>" method="POST" class="modal-content">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Floor</h5>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="name" id="edit-name" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?php echo e(route('floors.destroy')); ?>" method="POST" class="modal-content">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <input type="hidden" name="id" id="delete-id">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Floor</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="delete-name"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');

            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('edit-id').value = button.getAttribute('data-id');
                document.getElementById('edit-name').value = button.getAttribute('data-name');
            });

            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('delete-id').value = button.getAttribute('data-id');
                document.getElementById('delete-name').textContent = button.getAttribute('data-name');
            });
    createAjaxTable({
    apiUrl: "<?php echo e(route('floors.index')); ?>",
    tableSelector: "#floors",
    paginationSelector: "#pagination-wrapper",
    searchInputSelector: "#search-input",
    perPageSelector: "#perPage",
    rowRenderer: function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.name}</td>
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

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/floor/index.blade.php ENDPATH**/ ?>