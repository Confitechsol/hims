<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id', // Modal ID
    'title', // Modal title
    'method' => 'POST', // HTTP Method
    'action', // Form action URL
    'fields' => [], // Array of fields with structure
    'columns' => 1, // Number of columns (default: 1)
    'repeatable_group' => [],
    'type',
    'fileTypes'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id', // Modal ID
    'title', // Modal title
    'method' => 'POST', // HTTP Method
    'action', // Form action URL
    'fields' => [], // Array of fields with structure
    'columns' => 1, // Number of columns (default: 1)
    'repeatable_group' => [],
    'type',
    'fileTypes'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="modal fade" id="<?php echo e($id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="<?php echo e($action); ?>" method="POST" id="<?php echo e($id); ?>-form" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE'])): ?>
                    <?php echo method_field($method); ?>
                <?php endif; ?>

                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e($title); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($field['type'] == 'hidden'): ?>
                                <input type="hidden" name="id" value="" data-field="<?php echo e($field['name']); ?>" />
                                <?php continue; ?>
                            <?php endif; ?>
                            <div class="col-md-<?php echo e(isset($field['size']) ? $field['size'] : 12 / $columns); ?>">
                                <div class="mb-3">
                                    <label for="<?php echo e($field['name']); ?>" class="form-label">
                                        <?php echo e($field['label'] ?? ucfirst($field['name'])); ?>

                                        <?php if(!empty($field['required'])): ?>
                                            <span class="text-danger">*</span>
                                        <?php endif; ?>
                                    </label>
                                    <?php if($field['type'] === 'select'): ?>
                                        <select name="<?php echo e($field['name']); ?>" id="<?php echo e($field['name']); ?>" class="form-select" data-field="<?php echo e($field['name']); ?>"
                                        <?php if(!empty($field['required'])): ?> required <?php endif; ?>
                                        >
                                            <option value="">Select <?php echo e($field['label']); ?></option>
                                            <?php $__currentLoopData = $field['options'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"><?php echo e($val); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    <?php elseif($field['type'] === 'textarea'): ?>
                                        <textarea name="<?php echo e($field['name']); ?>" id="<?php echo e($field['name']); ?>" class="form-control"
                                            data-field="<?php echo e($field['name']); ?>" rows="3"><?php echo e($field['value'] ?? old($field['name'])); ?></textarea>
                                    <?php elseif($field['type'] === 'img'): ?>
                                    <img src="" data-field="<?php echo e($field['name']); ?>" alt="">        
                                    <?php else: ?>
                                        <input type="<?php echo e($field['type'] ?? 'text'); ?>" name="<?php echo e($field['name']); ?>"
                                        <?php if(isset($field['readonly'])): ?> readonly <?php endif; ?>
                                            data-field="<?php echo e($field['name']); ?>" id="<?php echo e($field['name']); ?>"
                                            value="<?php echo e($field['value'] ?? old($field['name'])); ?>" class="form-control"
                                            <?php if(!empty($field['required'])): ?> required <?php endif; ?> <?php if(isset($field['fileTypes'])): ?>accept="<?php echo e($field['fileTypes']); ?>"<?php endif; ?>>
                                        
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if(!empty($repeatable_group)): ?>
                            <div id="repeatable-group-wrapper">
                                <div class="repeatable-group row mb-3">
                                    <?php $__currentLoopData = $repeatable_group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-<?php echo e($subField['size'] ?? 6); ?>">
                                            <label class="form-label">
                                                <?php echo e($subField['label'] ?? ucfirst($subField['name'])); ?>

                                                <?php if(!empty($subField['required'])): ?>
                                                    <span class="text-danger">*</span>
                                                <?php endif; ?>
                                            </label>
                                            <?php if($subField['type'] === 'select'): ?>
                                                <select name="<?php echo e($subField['name']); ?>[0]" class="form-select"
                                                    data-name="<?php echo e($subField['name']); ?>"
                                                    <?php if(!empty($subField['required'])): ?> required <?php endif; ?>>
                                                    <?php $__currentLoopData = $subField['options'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo e($value); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            <?php else: ?>
                                                <input type="<?php echo e($subField['type'] ?? 'text'); ?>"
                                                    name="<?php echo e($subField['name']); ?>[0]" class="form-control"
                                                    data-name="<?php echo e($subField['name']); ?>"
                                                    <?php if(!empty($subField['required'])): ?> required <?php endif; ?>>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-group">
                                            <i class="bi bi-x-lg"></i> 
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-success"
                                        id="add-repeatable-group">Add</button>
                                </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-primary"><?php echo e(strtoupper($method) === 'PUT' ? 'Update' : 'Save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php if(isset($type) && $type == 'edit'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            const modal = new bootstrap.Modal(document.getElementById('edit_modal'));
            const form = document.getElementById('edit_modal-form');

            // Loop through all edit buttons
            // editButtons.forEach(button => {
            //     button.addEventListener('click', function() {
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.edit-btn');
            if (!button) return;
                    // Get the ID from the button
                    const id = button.getAttribute('data-id');

                    // If id exists set form action to death update endpoint and fill hidden id input
                    try {
                        if (id && form) {
                            form.action = '<?php echo e(url('/death/update')); ?>' + '/' + id;
                            const hiddenId = form.querySelector('input[name="id"], input[data-field="id"]');
                            if (hiddenId) hiddenId.value = id;
                        }
                    } catch (err) {
                        console.warn('Could not set form action dynamically', err);
                    }

                    // Find all input fields in the form
                    const inputFields = form.querySelectorAll('[data-field]');

                    // Loop through each input field
                    inputFields.forEach(input => {
                        const fieldName = input.getAttribute('data-field'); // e.g. 'name' or 'invoice_number'
                        let fieldValue = null;
                        try {
                            fieldValue = button.getAttribute(`data-${fieldName}`);
                        } catch (err) {
                            fieldValue = null;
                        }

                        // Safely set the input field's value dynamically
                        try {
                            // File inputs cannot be prefilled and we intentionally do not display filenames here.
                            if (input.type === 'file') {
                                // Skip populating file inputs or any filename display.
                                return;
                            }

                            if (input.tagName === 'SELECT') {
                                // If select2 is available use it, otherwise set value directly
                                if (window.jQuery && typeof $(input).select2 === 'function') {
                                    try {
                                        $(input).val(fieldValue).trigger('change.select2');
                                    } catch (err) {
                                        input.value = fieldValue ?? '';
                                    }
                                } else {
                                    input.value = fieldValue ?? '';
                                }
                            } else if (input.tagName === 'IMG') {
                                if (fieldValue) input.src = "<?php echo e(url('/')); ?>" + fieldValue;
                            } else {
                                if (fieldValue !== null && typeof fieldValue !== 'undefined') {
                                    input.value = fieldValue;
                                } else {
                                    // clear field if no value provided
                                    input.value = '';
                                }
                            }
                        } catch (err) {
                            // swallow errors to avoid breaking the handler for other fields
                            console.error('Error populating edit field', fieldName, err);
                        }
                    });

                    // Show the modal
                    modal.show();
                });
            // });
        });
    </script>
<?php endif; ?>
<script>
    // Listen for when *any* modal is hidden
    document.addEventListener('hidden.bs.modal', function(event) {
        // Force remove lingering backdrop if it exists
        const modalBackdrop = document.querySelector('.modal-backdrop');
        if (modalBackdrop) {
            modalBackdrop.remove();
            document.body.classList.remove('modal-open'); // Ensure scrolling is re-enabled
            document.body.style.paddingRight = ''; // Reset padding if set
        }
    });
</script>
<?php if(!empty($repeatable_group)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('add-repeatable-group');
            const wrapper = document.getElementById('repeatable-group-wrapper');

            if (addBtn && wrapper) {
                let index = 1;

                addBtn.addEventListener('click', () => {
                    const group = wrapper.querySelector('.repeatable-group');
                    const clone = group.cloneNode(true);

                    // Clear inputs and update names with new index
                    clone.querySelectorAll('input, select').forEach(el => {
                        // input.value = '';
                        // const baseName = input.getAttribute('data-name');
                        // input.name = `${baseName}[${index}]`;
                        const baseName = el.getAttribute('data-name');
                        if (!baseName) return;

                        el.name = `${baseName}[${index}]`;

                        if (el.tagName === 'SELECT') {
                            el.selectedIndex = 0; // Reset to first option
                        } else {
                            el.value = '';
                        }
                    });

                    wrapper.appendChild(clone);
                    index++;
                });
                wrapper.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-group')) {
                        const group = e.target.closest('.repeatable-group');
                        if (wrapper.querySelectorAll('.repeatable-group').length > 1) {
                            group.remove();
                        }
                    }
                });
            }
        });
    </script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\hims\resources\views/components/modals/form-modal.blade.php ENDPATH**/ ?>