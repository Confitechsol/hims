<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096">
                        <i class="fas fa-vial me-2"></i>Pathology Test List
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                        <div class="input-icon-start position-relative me-2">
                                            <span class="input-icon-addon">
                                                <i class="ti ti-search"></i>
                                            </span>
                                            <input type="text" class="form-control shadow-sm" id="searchTest" placeholder="Search Test">
                                        </div>

                                        <div class="page_btn d-flex">
                                            <a href="<?php echo e(route('pathology.test.create')); ?>" class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                <i class="ti ti-plus me-1"></i>Add Pathology Test
                                            </a>
                                            <a href="<?php echo e(route('pathology.test.import')); ?>" class="btn btn-primary text-white ms-2 fs-13 btn-md">
                                                <i class="ti ti-plus me-1"></i>Import Pathology Test
                                            </a>
                                        </div>
                                    </div>

                                    <?php if(session('success')): ?>
                                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                                    <?php endif; ?>

                                    <?php if(session('error')): ?>
                                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                                    <?php endif; ?>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Test Name</th>
                                                    <th>Short Name</th>
                                                    <th>Test Type</th>
                                                    <th>Category</th>
                                                    <th>Charge Category</th>
                                                    <th>Charge Name</th>
                                                    <th>Sub Category</th>
                                                    <th>Method</th>
                                                    <th>Report Days</th>
                                                    <th>Tax (%)</th>
                                                    <th>Charge (INR)</th>
                                                    <th>Amount (INR)</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="testTableBody">
                                                <?php $__empty_1 = true; $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td><?php echo e($test->id); ?></td>
                                                        <td class="fw-bold"><?php echo e($test->test_name); ?></td>
                                                        <td><?php echo e($test->short_name); ?></td>
                                                        <td><?php echo e($test->test_type ?? '-'); ?></td>
                                                        <td><?php echo e($test->category->category_name ?? '-'); ?></td>
                                                        <td><?php echo e($test->chargeCategory->name ?? ($test->charge && $test->charge->category ? $test->charge->category->name : '-')); ?></td>
                                                        <td><?php echo e($test->charge->name ?? '-'); ?></td>
                                                        <td><?php echo e($test->sub_category ?? '-'); ?></td>
                                                        <td><?php echo e($test->method ?? '-'); ?></td>
                                                        <td><?php echo e($test->report_days ?? '-'); ?></td>
                                                        <td><?php echo e($test->charge && $test->charge->taxCategory ? number_format($test->charge->taxCategory->percentage, 2) : '0.00'); ?>%</td>
                                                        <td>₹<?php echo e(number_format($test->standard_charge ?? ($test->charge ? $test->charge->standard_charge : 0), 2)); ?></td>
                                                        <td class="fw-bold">₹<?php echo e(number_format($test->amount ?? ($test->charge ? ($test->charge->standard_charge + ($test->charge->standard_charge * ($test->charge->taxCategory ? $test->charge->taxCategory->percentage : 0) / 100)) : 0), 2)); ?></td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <a href="<?php echo e(route('pathology.test.show', $test->id)); ?>" class="btn btn-sm btn-info text-white" title="View">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                                <a href="<?php echo e(route('pathology.test.edit', $test->id)); ?>" class="btn btn-sm btn-warning text-white" title="Edit">
                                                                    <i class="ti ti-edit"></i>
                                                                </a>
                                                                <form action="<?php echo e(route('pathology.test.destroy', $test->id)); ?>" method="POST" class="d-inline" onsubmit="return confirmDeleteForm(event, 'Delete Test?', 'Are you sure you want to delete this test?');">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit" class="btn btn-sm btn-danger text-white" title="Delete">
                                                                        <i class="ti ti-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="12" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="ti ti-inbox fs-48 mb-2"></i>
                                                                <p>No pathology tests found. Add your first test!</p>
                                                            </div>
                                                        </td>
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
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchTest');
            const tableBody = document.getElementById('testTableBody');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = tableBody.querySelectorAll('tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/pathology/test/index.blade.php ENDPATH**/ ?>