

<?php $__env->startSection('content'); ?>

<!-- row start -->
<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096">
                    <i class="fas fa-calendar-alt me-2"></i>Duty Roster Reports
                </h5>
            </div>
            <div class="card-body">

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <div class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="input-icon-start position-relative me-2">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input onkeyup="searchRoster()" type="text" id="roster-search" class="form-control shadow-sm" placeholder="Search Duty Roster">
                        </div>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="text-end d-flex">
                            <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 btn-md"
                                data-bs-toggle="modal" data-bs-target="#add_roster">
                                <i class="ti ti-plus me-1"></i>Add Roster
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Table start -->
                <div class="table-responsive table-nowrap">
                    <table class="table" id="rosterTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Shift</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rosterlist->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($roster->employee_id); ?></td>
                                <td><?php echo e($roster->employee_name ?? 'N/A'); ?></td>
                                <td><?php echo e($roster->designation ?? 'N/A'); ?></td>
                                <td><?php echo e($roster->department ?? 'N/A'); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($roster->duty_date)->format('d M, Y')); ?></td>
                                <td><?php echo e($roster->shift ?? '-'); ?></td>
                                <td><?php echo e($roster->remarks ?? '-'); ?></td>
                                <td>
                                    <div class="d-flex">
                                        <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                            data-id="<?php echo e($roster->id); ?>"
                                            data-employee_id="<?php echo e($roster->employee_id); ?>"
                                            data-employee_name="<?php echo e($roster->employee_name); ?>"
                                            data-designation="<?php echo e($roster->designation); ?>"
                                            data-department="<?php echo e($roster->department); ?>"
                                            data-duty_date="<?php echo e($roster->duty_date); ?>"
                                            data-shift="<?php echo e($roster->shift); ?>"
                                            data-remarks="<?php echo e($roster->remarks); ?>">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <form method="POST" action="<?php echo e(route('dutyroster.destroy')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <input type="hidden" name="id" value="<?php echo e($roster->id); ?>">
                                            <button type="submit"
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                                onclick="return confirm('Are you sure you want to delete this roster?');">
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
                <!-- Table end -->

            </div>
        </div>
    </div>
</div>

<!-- Add Roster Modal -->
<?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['type' => 'add','id' => 'add_roster','title' => 'Add Duty Roster','action' => ''.e(route('dutyroster.store')).'','fields' => [
        ['name' => 'employee_id', 'label' => 'Employee ID', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'employee_name', 'label' => 'Employee Name', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'designation', 'label' => 'Designation', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'department', 'label' => 'Department', 'type' => 'text', 'size' => '6'],
        ['name' => 'duty_date', 'label' => 'Duty Date', 'type' => 'date', 'required' => true, 'size' => '3'],
        ['name' => 'shift', 'label' => 'Shift', 'type' => 'text', 'size' => '3'],
        ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'size' => '12']
    ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'add','id' => 'add_roster','title' => 'Add Duty Roster','action' => ''.e(route('dutyroster.store')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'employee_id', 'label' => 'Employee ID', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'employee_name', 'label' => 'Employee Name', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'designation', 'label' => 'Designation', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'department', 'label' => 'Department', 'type' => 'text', 'size' => '6'],
        ['name' => 'duty_date', 'label' => 'Duty Date', 'type' => 'date', 'required' => true, 'size' => '3'],
        ['name' => 'shift', 'label' => 'Shift', 'type' => 'text', 'size' => '3'],
        ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'size' => '12']
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

<!-- Edit Roster Modal -->
<?php if (isset($component)) { $__componentOriginal66ca70ec79ff22faa62f501a1b49a88a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66ca70ec79ff22faa62f501a1b49a88a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modals.form-modal','data' => ['method' => 'put','type' => 'edit','id' => 'edit_roster','title' => 'Edit Duty Roster','action' => ''.e(route('dutyroster.update')).'','fields' => [
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'employee_id', 'label' => 'Employee ID', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'employee_name', 'label' => 'Employee Name', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'designation', 'label' => 'Designation', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'department', 'label' => 'Department', 'type' => 'text', 'size' => '6'],
        ['name' => 'duty_date', 'label' => 'Duty Date', 'type' => 'date', 'required' => true, 'size' => '3'],
        ['name' => 'shift', 'label' => 'Shift', 'type' => 'text', 'size' => '3'],
        ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'size' => '12']
    ],'columns' => 3]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modals.form-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['method' => 'put','type' => 'edit','id' => 'edit_roster','title' => 'Edit Duty Roster','action' => ''.e(route('dutyroster.update')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['name' => 'id', 'type' => 'hidden', 'required' => true],
        ['name' => 'employee_id', 'label' => 'Employee ID', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'employee_name', 'label' => 'Employee Name', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'designation', 'label' => 'Designation', 'type' => 'text', 'required' => true, 'size' => '4'],
        ['name' => 'department', 'label' => 'Department', 'type' => 'text', 'size' => '6'],
        ['name' => 'duty_date', 'label' => 'Duty Date', 'type' => 'date', 'required' => true, 'size' => '3'],
        ['name' => 'shift', 'label' => 'Shift', 'type' => 'text', 'size' => '3'],
        ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'size' => '12']
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

<script>
function searchRoster() {
    const query = document.querySelector('#roster-search').value;
    const table = document.querySelector('#rosterTable');

    fetch("<?php echo e(route('rosterlist')); ?>?search=" + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                const tbody = table.querySelector('tbody');
                tbody.innerHTML = '';
                data.result.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.employee_id}</td>
                        <td>${item.employee_name ?? 'N/A'}</td>
                        <td>${item.designation ?? 'N/A'}</td>
                        <td>${item.department ?? 'N/A'}</td>
                        <td>${item.duty_date}</td>
                        <td>${item.shift ?? '-'}</td>
                        <td>${item.remarks ?? '-'}</td>
                        <td>
                            <div class="d-flex">
                                <button class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill edit-btn"
                                    data-id="${item.id}">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <form method="POST" action="<?php echo e(route('dutyroster.destroy')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <input type="hidden" name="id" value="${item.id}">
                                    <button type="submit" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill"
                                        onclick="return confirm('Are you sure you want to delete this roster?');">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(err => console.error(err));
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/duty-roster/roster_reports.blade.php ENDPATH**/ ?>