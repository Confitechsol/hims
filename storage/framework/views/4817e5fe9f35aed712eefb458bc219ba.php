<div class="d-flex justify-content-between">
    <div class="text-end d-flex mb-3 w-100">
        <a href="javascript:void(0);" class="btn btn-primary text-white fs-13 btn-md" data-bs-toggle="modal"
            data-bs-target="#createModal"><i class="ti ti-plus me-1"></i>Add <?php echo e($name); ?></a>
        <?php if($name === 'Visitor'): ?>
        <div class="btn-group ms-2">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-mail me-1"></i>Postal
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo e(route('dispatch.receive')); ?>">Receive</a></li>
                <li><a class="dropdown-item" href="<?php echo e(route('dispatch.dispatch')); ?>">Dispatch</a></li>
            </ul>
        </div>
        <a href="<?php echo e(route('phone-call-log')); ?>" class="btn btn-secondary text-white fs-13 btn-md ms-2"><i class="ti ti-phone me-1"></i>Phone Call Log</a>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary copy-btn" data-clipboard-target="#<?php echo e($id); ?>">Copy</button>
        <button class="btn btn-success" onclick="exportToExcel('<?php echo e($id); ?>')">Export to Excel</button>
        <button class="btn btn-info" onclick="exportToCSV('<?php echo e($id); ?>')">Export to CSV</button>
        <button class="btn btn-danger" onclick="exportToPDF('<?php echo e($id); ?>')">Export to PDF</button>
        <button class="btn btn-warning" onclick="printTable('<?php echo e($id); ?>')">Print</button>
    </div>
</div>
</div>
<div class="input-icon-start position-relative mb-3">
    <span class="input-icon-addon">
        <i class="ti ti-search"></i>
    </span>
    <input type="text" class="form-control shadow-sm" placeholder="Search" id="search-input"
        
        >

</div><?php /**PATH C:\xampp\htdocs\hims\resources\views/components/table-actions/actions.blade.php ENDPATH**/ ?>