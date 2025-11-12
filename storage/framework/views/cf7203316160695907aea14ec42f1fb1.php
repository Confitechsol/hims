<?php $__env->startSection('content'); ?>
<style>
    .modal-backdrop.show:nth-of-type(2) { z-index: 1060; }
    #new_patient { z-index: 1070; }
</style>

<div class="content pb-0">
    <div class="row">
        <div class="col-12 d-flex">
            <div class="card shadow-sm flex-fill w-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                        <div><h4 class="fw-bold mb-0">Inventory Details</h4></div>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="text-end d-flex">
                                <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                   data-bs-toggle="modal" data-bs-target="#add_item">
                                   <i class="ti ti-plus me-1"></i>Add Item 
                                </a>
                            </div>

                            <a href="<?php echo e(route('issue-items')); ?>" class="btn btn-outline-primary d-inline-flex align-items-center">
                                <i class="ti ti-menu me-1"></i>Issue Item
                            </a>
                            <a href="<?php echo e(route('inventory-details')); ?>" class="btn btn-outline-primary d-inline-flex align-items-center">
                                <i class="ti ti-menu me-1"></i>Inventory
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="modal fade" id="add_item" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <form method="POST" action="<?php echo e(route('items.store')); ?>" id="itemStockForm" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                    <h5 class="modal-title">Add Item Stock</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row align-items-center gy-3">

                                        
                                        <div class="col-md-4">
                                            <label class="form-label">Item Category <span class="text-danger">*</span></label>
                                            <select class="form-select" id="item_category_id" name="item_category_id" required>
                                                <option value="">Select Item Category</option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>" data-item-head="<?php echo e($category->item_head); ?>">
                                                        <?php echo e($category->item_category); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        
                                        <div class="col-md-4">
                                            <label class="form-label">Item <span class="text-danger">*</span></label>
                                            <input type="text" id="name" name="name" class="form-control" required>
                                        </div>

                                         
                                        <div class="col-md-4">
                                            <label for="unit" class="form-label fw-bold">Unit <span class="text-danger">*</span></label>
                                            <input type="text" id="unit" name="unit" class="form-control" required>
                                        </div>

                                        
                                        <div class="col-md-4">
                                            <label for="quantity" class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
                                        </div>

                                        
                                        <div class="col-md-4">
                                            <label for="date" class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                                            <input type="date" id="date" name="date" class="form-control" required>
                                        </div>

                                        
                                        <div class="col-md-4">
                                            <label for="item_photo" class="form-label fw-bold">Item Photo</label>
                                            <input type="file" id="item_photo" name="item_photo" class="form-control" accept="image/*">
                                        </div>

                                        
                                        <div class="col-12">
                                            <label for="description" class="form-label fw-bold">Description</label>
                                            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter item description..."></textarea>
                                        </div>
 

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save </button>
                                    <!-- <button type="button" id="saveOnly" class="btn btn-secondary">Save</button> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="card-body">
                    <div class="table-responsive table-nowrap">
                        <table class=" table border ">
                                    <thead class="thead-light">
                                        <tr>
                                            <!-- <th>#</th> -->
                                            <th>Item Category</th>
                                            <th>Name</th>
                                            <th>Unit</th>
                                            <th>Quantity</th>
                                            <th>Date</th>
                                            <th>Item Photo</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <!-- <td><?php echo e($index + 1); ?></td> -->
                                                <td><?php echo e($item->category->item_category ?? '-'); ?></td>
                                                <td><?php echo e($item->name); ?></td>
                                                <td><?php echo e($item->unit); ?></td>
                                                <td><?php echo e($item->quantity); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($item->date)->format('d-m-Y')); ?></td>
                                                <td>
                                                    <?php if($item->item_photo): ?>
                                                        <img src="<?php echo e(asset('storage/' . $item->item_photo)); ?>" width="50" height="50" class="rounded">
                                                    <?php else: ?>
                                                        <span class="text-muted">No Image</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($item->description); ?></td>
                                                <td>
                                                    <a href="javascript:void(0);" 
                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-info rounded-pill editItemBtn"
                                                    data-id="<?php echo e($item->id); ?>" data-bs-toggle="tooltip" title="Edit">
                                                        <i class="ti ti-pencil"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                        onclick="if(confirm('Are you sure you want to delete this item?')) { document.getElementById('delete-item-<?php echo e($item->id); ?>').submit(); }"
                                                        data-bs-toggle="tooltip" title="Delete">
                                                            <i class="ti ti-trash"></i>
                                                    </a>
                                                    <form id="delete-item-<?php echo e($item->id); ?>" action="<?php echo e(route('items.destroy', $item->id)); ?>" method="POST" style="display: none;">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="9" class="text-muted">No items found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_item" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form method="POST" id="editItemForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="modal-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="modal-title">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_item_id" name="item_id">

                    <div class="row align-items-center gy-3">
                        
                        <div class="col-md-4">
                            <label class="form-label">Item Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_item_category_id" name="item_category_id" required>
                                <option value="">Select Item Category</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" data-item-head="<?php echo e($category->item_head); ?>">
                                        <?php echo e($category->item_category); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="col-md-4">
                            <label class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" id="edit_name" name="name" class="form-control" required>
                        </div>

                        
                        <div class="col-md-4">
                            <label for="edit_unit" class="form-label fw-bold">Unit <span class="text-danger">*</span></label>
                            <input type="text" id="edit_unit" name="unit" class="form-control" required>
                        </div>

                        
                        <div class="col-md-4">
                            <label for="edit_quantity" class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                            <input type="number" id="edit_quantity" name="quantity" class="form-control" min="1" required>
                        </div>

                        
                        <div class="col-md-4">
                            <label for="edit_date" class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" id="edit_date" name="date" class="form-control" required>
                        </div>

                        
                        <div class="col-md-4">
                            <label for="edit_item_photo" class="form-label fw-bold">Item Photo</label>
                            <input type="file" id="edit_item_photo" name="item_photo" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1">Leave blank to keep existing image.</small>
                            <div id="current_item_photo" class="mt-2"></div>
                        </div>

                        
                        <div class="col-12">
                            <label for="edit_description" class="form-label fw-bold">Description</label>
                            <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$(document).on('click', '.editItemBtn', function () {
    const id = $(this).data('id');

    $.ajax({
        url: "<?php echo e(route('items.edit', ':id')); ?>".replace(':id', id),
        method: 'GET',
        success: function (data) {
            const item = data.item;

            if (!item) {
                alert('Item not found');
                return;
            }

            // Populate modal fields
            $('#edit_item_id').val(item.id);
            $('#edit_item_category_id').val(item.item_category_id);
            $('#edit_name').val(item.name);
            $('#edit_unit').val(item.unit);
            $('#edit_quantity').val(item.quantity);
            $('#edit_date').val(item.date ?? '');
            $('#edit_description').val(item.description ?? '');

            // Show existing image if available
            if (item.item_photo) {
                $('#current_item_photo').html(`
                    <img src="<?php echo e(asset('storage/items')); ?>/${item.item_photo}" 
                         alt="Item Image" class="img-thumbnail mt-2" width="80">
                `);
            } else {
                $('#current_item_photo').html(`<small class="text-muted">No image uploaded</small>`);
            }

            // Update form action URL
            $('#editItemForm').attr('action', "<?php echo e(route('items.update', ':id')); ?>".replace(':id', id));

            // Show modal
            new bootstrap.Modal(document.getElementById('edit_item')).show();
        },
        error: function () {
            alert('Error fetching item details.');
        }
    });
});


</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/inventory/item_details.blade.php ENDPATH**/ ?>