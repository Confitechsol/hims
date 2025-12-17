
<?php $__env->startSection('content'); ?>

<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-phone me-2"></i>Phone Call Log</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <?php if(session('success')): ?>
                                    <div class="alert alert-success">
                                        <?php echo e(session('success')); ?>

                                    </div>
                                <?php endif; ?>
                                <?php if(session('error')): ?>
                                    <div class="alert alert-danger">
                                        <?php echo e(session('error')); ?>

                                    </div>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <div class="btn-group me-2">
                                            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-mail me-1"></i>Postal
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="<?php echo e(route('dispatch.receive')); ?>">Receive</a></li>
                                                <li><a class="dropdown-item" href="<?php echo e(route('dispatch.dispatch')); ?>">Dispatch</a></li>
                                            </ul>
                                        </div>
                                        <a href="<?php echo e(route('visitors')); ?>" class="btn btn-secondary text-white fs-13 btn-md"><i class="ti ti-arrow-left me-1"></i>Back to Visitors</a>
                                        <button class="btn btn-success text-white fs-13 btn-md ms-2" data-bs-toggle="modal" data-bs-target="#createCallModal"><i class="ti ti-plus me-1"></i>Add Call</button>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCallModal"><i class="ti ti-plus me-1"></i>Add Call</button>
                                        <button class="btn btn-primary copy-btn" data-clipboard-target="#callLogsTable">Copy</button>
                                        <button class="btn btn-success" onclick="exportToExcel('callLogsTable')">Export to Excel</button>
                                        <button class="btn btn-info" onclick="exportToCSV('callLogsTable')">Export to CSV</button>
                                        <button class="btn btn-danger" onclick="exportToPDF('callLogsTable')">Export to PDF</button>
                                        <button class="btn btn-warning" onclick="printTable('callLogsTable')">Print</button>
                                    </div>
                                </div>

                                <div class="input-icon-start position-relative mb-3">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <form method="GET" class="d-flex gap-2">
                                        <input type="text" name="search" class="form-control shadow-sm" 
                                               placeholder="Search by Name, Phone, or Purpose" 
                                               value="<?php echo e(request('search')); ?>">
                                        <button type="submit" class="btn btn-outline-primary">Search</button>
                                    </form>
                                </div>

                                <div class="table-responsive table-nowrap">
                                    <table class="table table-striped" id="callLogsTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Date</th>
                                                <th>Next Follow Up Date</th>
                                                <th>Call Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $callLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php
                                                    // Support multiple possible column names
                                                    $nextFollow = $log->next_follow_up_date ?? $log->follow_up_date ?? $log->follow_up_date ?? null;
                                                    $callType = $log->call_type ?? $log->purpose ?? $log->description ?? '-';
                                                    $dateVal = $log->date ?? $log->created_at ?? null;
                                                ?>
                                                <tr>
                                                    <td><?php echo e($log->name ?? '-'); ?></td>
                                                    <td><?php echo e($log->contact ?? ($log->phone ?? '-')); ?></td>
                                                    <td><?php echo e($dateVal ? \Carbon\Carbon::parse($dateVal)->format('d-m-Y') : '-'); ?></td>
                                                    <td><?php echo e($nextFollow ? \Carbon\Carbon::parse($nextFollow)->format('d-m-Y') : '-'); ?></td>
                                                    <td><?php echo e($callType); ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <button
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn ms-2"
                                                                data-id="<?php echo e($log->id ?? ''); ?>"
                                                                data-name="<?php echo e($log->name ?? ''); ?>"
                                                                data-contact="<?php echo e($log->contact ?? $log->phone ?? ''); ?>"
                                                                data-purpose="<?php echo e($log->purpose ?? $log->description ?? ''); ?>"
                                                                data-id_proof="<?php echo e($log->id_proof ?? ''); ?>"
                                                                data-visit_to="<?php echo e($log->visit_to ?? ''); ?>"
                                                                data-related_to="<?php echo e($log->related_to ?? ''); ?>"
                                                                data-no_of_pepple="<?php echo e($log->no_of_pepple ?? 1); ?>"
                                                                data-date="<?php echo e($dateVal ? \Carbon\Carbon::parse($dateVal)->format('Y-m-d') : ''); ?>"
                                                                data-in_time="<?php echo e(isset($log->in_time) ? \Carbon\Carbon::parse($log->in_time)->format('H:i') : ''); ?>"
                                                                data-out_time="<?php echo e(isset($log->out_time) ? \Carbon\Carbon::parse($log->out_time)->format('H:i') : ''); ?>"
                                                                data-note="<?php echo e($log->note ?? ''); ?>"
                                                                data-next_follow_up_date="<?php echo e($nextFollow ? \Carbon\Carbon::parse($nextFollow)->format('Y-m-d') : ''); ?>"
                                                                data-call_type="<?php echo e($callType); ?>"
                                                                data-call_duration="<?php echo e($log->call_duration ?? ''); ?>">
                                                                <i class="ti ti-pencil"></i>
                                                            </button>

                                                            <form method="POST" action="<?php echo e(route('visitors.delete', $log->id ?? 0)); ?>" onsubmit="return confirm('Delete this visitor?');" class="ms-2">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill" title="Delete">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">No call logs found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3" id="pagination-wrapper">
                                    <?php if($callLogs->onFirstPage()): ?>
                                        <button class="btn btn-outline-secondary btn-sm me-1" disabled>« Prev</button>
                                    <?php else: ?>
                                        <a href="<?php echo e($callLogs->previousPageUrl()); ?><?php echo e(request('search') ? '&search=' . request('search') : ''); ?>" class="btn btn-outline-secondary btn-sm me-1">« Prev</a>
                                    <?php endif; ?>

                                    <?php for($page = 1; $page <= $callLogs->lastPage(); $page++): ?>
                                        <?php if($page == $callLogs->currentPage()): ?>
                                            <button class="btn btn-primary btn-sm me-1"><?php echo e($page); ?></button>
                                        <?php else: ?>
                                            <a href="<?php echo e($callLogs->url($page)); ?><?php echo e(request('search') ? '&search=' . request('search') : ''); ?>" class="btn btn-outline-secondary btn-sm me-1"><?php echo e($page); ?></a>
                                        <?php endif; ?>
                                    <?php endfor; ?>

                                    <?php if($callLogs->hasMorePages()): ?>
                                        <a href="<?php echo e($callLogs->nextPageUrl()); ?><?php echo e(request('search') ? '&search=' . request('search') : ''); ?>" class="btn btn-outline-secondary btn-sm">Next »</a>
                                    <?php else: ?>
                                        <button class="btn btn-outline-secondary btn-sm" disabled>Next »</button>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php
    $purposeOptions = [];
    if (!empty($purposes)) {
        $purposeOptions = collect($purposes)->mapWithKeys(function ($item) {
            return [$item->visitors_purpose => $item->visitors_purpose];
        })->toArray();
    }
?>


<?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Visitor','action' => ''.e(url('/visitors/update')).'','fields' => [
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
        ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => false, 'size' => '4'],
        ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
        ['name' => 'next_follow_up_date', 'label' => 'Next Follow Up Date', 'type' => 'date', 'required' => false, 'size' => '4'],
        ['name' => 'call_type', 'label' => 'Call Type', 'type' => 'text', 'required' => false, 'size' => '4'],
        ['name' => 'call_duration', 'label' => 'Duration (secs)', 'type' => 'number', 'required' => false, 'size' => '4'],
    ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_modal','title' => 'Edit Visitor','action' => ''.e(url('/visitors/update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '3'],
        ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => false, 'size' => '4'],
        ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
        ['name' => 'next_follow_up_date', 'label' => 'Next Follow Up Date', 'type' => 'date', 'required' => false, 'size' => '4'],
        ['name' => 'call_type', 'label' => 'Call Type', 'type' => 'text', 'required' => false, 'size' => '4'],
        ['name' => 'call_duration', 'label' => 'Duration (secs)', 'type' => 'number', 'required' => false, 'size' => '4'],
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['type' => 'add','id' => 'createCallModal','title' => 'Add Call Log','action' => ''.e(route('phone-call-log.create')).'','fields' => [
    ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '4'],
    ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => true, 'size' => '4'],
    ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
    ['name' => 'next_follow_up_date', 'label' => 'Next Follow Up Date', 'type' => 'date', 'required' => false, 'size' => '4'],
    ['name' => 'call_type', 'label' => 'Call Type', 'type' => 'select', 'options' => ['Incoming' => 'Incoming', 'Outgoing' => 'Outgoing', 'Follow-up' => 'Follow-up'], 'required' => false, 'size' => '4'],
    ['name' => 'call_duration', 'label' => 'Duration (secs)', 'type' => 'number', 'required' => false, 'size' => '4'],
    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
    ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'createCallModal','title' => 'Add Call Log','action' => ''.e(route('phone-call-log.create')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
    ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true, 'size' => '4'],
    ['name' => 'contact', 'label' => 'Phone', 'type' => 'text', 'required' => true, 'size' => '4'],
    ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true, 'size' => '4'],
    ['name' => 'next_follow_up_date', 'label' => 'Next Follow Up Date', 'type' => 'date', 'required' => false, 'size' => '4'],
    ['name' => 'call_type', 'label' => 'Call Type', 'type' => 'select', 'options' => ['Incoming' => 'Incoming', 'Outgoing' => 'Outgoing', 'Follow-up' => 'Follow-up'], 'required' => false, 'size' => '4'],
    ['name' => 'call_duration', 'label' => 'Duration (secs)', 'type' => 'number', 'required' => false, 'size' => '4'],
    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
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


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\front-office\phone-call-log.blade.php ENDPATH**/ ?>