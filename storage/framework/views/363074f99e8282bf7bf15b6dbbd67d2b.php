<?php $__env->startSection('content'); ?>
    <style>
        .nav-tabs .nav-link.active {
            background-color: #750096 !important;
            color: #f8f9fa !important;
            font-weight: 600 !important;
        }
    </style>
    <div class="container">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header d-flex justify-content-between align-items-center align-items-sm-center justify-content-between flex-sm-row"
                style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> OPD</h5>
                <div class="text-end d-flex">
                    <ul class="nav nav-tabs">
                        <li class="nav-item" style="border-bottom:0">
                            <a class="nav-link mb-0 <?php echo e(@$isOpdTab ? 'active' : ''); ?> text-white"
                                href="<?php echo e(route('opd', array_merge(request()->except('tab'), ['tab' => 'opd']))); ?>">
                                OPD View
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 <?php echo e(@!$isOpdTab ? 'active' : ''); ?> text-white"
                                href="<?php echo e(route('opd', array_merge(request()->except('tab'), ['tab' => 'patient']))); ?>">
                                Patient View
                            </a>
                        </li>
                    </ul>

                </div>
            </div>

            <div class="card-body">

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div
                                    class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="<?php echo e(route('opd')); ?>" method="GET">
                                            <div class="d-flex align-items-center">
                                                <div class="input-icon-start position-relative me-2">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input type="text" id="language-search" name="search"
                                                        value="<?php echo e(request('search')); ?>" class="form-control shadow-sm"
                                                        placeholder="Search">
                                                </div>
                                                <div>
                                                    <button class="btn btn-primary" type="submit">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                        <form action="" style="width: 300px;">
                                            <div class="input-group shadow-sm">
                                                <label class="input-group-text" for="inputGroupSelect01">Consultant</label>
                                                <select class="form-select" id="inputGroupSelect01">
                                                    <option selected>Select</option>
                                                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($doctor->id); ?>"><?php echo e($doctor->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </select>
                                            </div>
                                        </form>
                                        <div>
                                            <button class="btn btn-outline-primary" type="button"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop"
                                                aria-controls="offcanvasTop">Apply
                                                Filter</button>
                                        </div>
                                    </div>

                                    <div class="text-end d-flex">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#createOpdModal">Appoint Patient</button>
                                    </div>
                                </div>
                                <?php if($isOpdTab): ?>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>OPD No.</th>
                                                    <th>Patient Name</th>
                                                    <th>Consultant</th>
                                                    <th>Reference</th>
                                                    <th>Symptoms</th>
                                                    <th>Admission Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $opd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opdDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($loop->iteration); ?></td>
                                                        <td><a href="<?php echo e(route('opd.show', [$opdDetails->id])); ?>"
                                                                class="text-primary"><?php echo e($opdDetails->opd_no); ?></a>
                                                        </td>
                                                        <td><?php echo e($opdDetails->patient->patient_name); ?></td>
                                                        <td><?php echo e($opdDetails->doctor->name ?? '-'); ?></td>
                                                        <td><?php echo e($opdDetails->reference ?? '-'); ?></td>
                                                        <td>
                                                            <?php if(isset($opdSymptoms[$opdDetails->opd_no]) && count($opdSymptoms[$opdDetails->opd_no]) > 0): ?>
                                                                <?php $__currentLoopData = $opdSymptoms[$opdDetails->opd_no]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $symptom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <span
                                                                        class="badge bg-primary me-1"><?php echo e($symptom->symptoms_title); ?></span>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo e(\Carbon\Carbon::parse($opdDetails->created_at)->format('d-M-Y')); ?>

                                                        </td>
                                                        <td>

                                                            <a href="<?php echo e(route('opd.edit', [$opdDetails->id])); ?>"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill"
                                                                data-id="<?php echo e($opdDetails->id); ?>">
                                                                <i class="ti ti-pencil"></i></a>
                                                            <form action="" class="d-inline"
                                                                id="delete-form-<?php echo e($opdDetails->id); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <a href="javascript: void(0);"
                                                                    class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill delete-button"
                                                                    data-opd-id="<?php echo e($opdDetails->id); ?>"
                                                                    data-form-id="delete-form-<?php echo e($opdDetails->id); ?>">
                                                                    <i class="ti ti-trash"></i></a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>

                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                    <th>Gender</th>
                                                    <th>Guardian Name</th>
                                                    <th>Consultant</th>
                                                    <th>Last Visit</th>
                                                    <th>Total Re-Checkup</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $opd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opdDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($loop->iteration); ?></td>
                                                        <td><a href="#"
                                                                class="text-primary"><?php echo e($opdDetails->patient_name); ?></a>
                                                        </td>
                                                        <td><?php echo e($opdDetails->mobileno); ?></td>
                                                        <td><?php echo e($opdDetails->email); ?></td>
                                                        <td><?php echo e($opdDetails->address); ?></td>
                                                        <td><?php echo e($opdDetails->gender); ?></td>
                                                        <td><?php echo e($opdDetails->guardian_name); ?></td>
                                                        <td><?php echo e($opdDetails->opds[0]->doctor->name ?? '-'); ?></td>
                                                        <?php if($opdDetails->opds && $opdDetails->opds->isNotEmpty()): ?>
                                                            <td><?php echo e(\Carbon\Carbon::parse($opdDetails->opds[0]->created_at)->format('d-M-Y') ?? '-'); ?>

                                                            </td>
                                                        <?php else: ?>
                                                            <td>-</td>
                                                        <?php endif; ?>
                                                        <td><?php echo e(optional($opdDetails->opds)->count() ?? 0); ?></td>
                                                        

                                                        

                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>

                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="offcanvas offcanvas-top" style="height: fit-content;" tabindex="-1"
                                id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
                                <div class="offcanvas-header justify-content-center">
                                    <h4 class="offcanvas-title m-auto font-weight-bold" id="offcanvasTopLabel">FILTERS</h4>
                                </div>
                                <div class="offcanvas-body">
                                    <div class="filter-section pb-3 rounded">
                                        <form action="<?php echo e(request()->routeIs('opd') ? route('opd') : route('opd')); ?>"
                                            method="GET" class="text-center" id="searchForm">
                                            <div
                                                class="d-flex flex-column flex-md-row gap-2 align-items-center justify-content-center">
                                                <!-- Filter Type Selector -->
                                                <div class="align-items-center">
                                                    <label for="filterType" class="form-label font-weight-bold">Select
                                                        Filter
                                                        Type</label>
                                                    <select id="filterType" class="form-select" name="filter_type"
                                                        style="width: 250px;">
                                                        <option value="dateRange"
                                                            <?php echo e(request('filter_type') == 'dateRange' ? 'selected' : ''); ?>>
                                                            Date
                                                            Range
                                                        </option>
                                                        <option value="monthly"
                                                            <?php echo e(request('filter_type') == 'monthly' ? 'selected' : ''); ?>>
                                                            Monthly</option>
                                                        <option value="weekly"
                                                            <?php echo e(request('filter_type') == 'weekly' ? 'selected' : ''); ?>>
                                                            Weekly</option>
                                                    </select>
                                                </div>

                                                <!-- Date Range Filter -->
                                                <div id="dateRangeFilter">
                                                    <div
                                                        class="d-flex flex-column flex-md-row gap-2 align-items-center filter-group">
                                                        <div>
                                                            <label class="form-label">From</label>
                                                            <input type="date" class="form-control" name="fromDate"
                                                                value="<?php echo e(request('fromDate')); ?>" />
                                                        </div>
                                                        <div>
                                                            <label class="form-label">To</label>
                                                            <input type="date" class="form-control" name="toDate"
                                                                value="<?php echo e(request('toDate')); ?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Monthly Filter -->
                                                <div id="monthlyFilter" class="d-none">
                                                    <div class="filter-group">
                                                        <label class="form-label text-nowrap">Select Month</label>
                                                        <input type="month" class="form-control" name="monthFilter"
                                                            value="<?php echo e(request('monthFilter')); ?>" />
                                                    </div>
                                                </div>
                                                <!-- Weekly Filter -->
                                                <div id="weeklyFilter" class="d-none">
                                                    <div class="filter-group">
                                                        <label class="form-label text-nowrap">Select Week</label>
                                                        <input type="week" class="form-control" name="weekFilter"
                                                            value="<?php echo e(request('weekFilter')); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 d-flex gap-2 mx-auto mt-3">
                                                <button type="submit" class="btn cmn_btn btn-primary w-100">
                                                    <i class="fas fa-filter me-1"></i> Filter
                                                </button>
                                                <a href="<?php echo e(request()->routeIs('opd') ? route('opd') : route('opd')); ?>"
                                                    class="btn btn-outline-dark w-100">
                                                    Reset
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    
    <?php echo $__env->make('components.modals.opd-create-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterTypeSelect = document.getElementById('filterType');
            const dateRangeFilter = document.getElementById('dateRangeFilter');
            const monthlyFilter = document.getElementById('monthlyFilter');
            const weeklyFilter = document.getElementById('weeklyFilter');

            const fromDateInput = document.querySelector('input[name="fromDate"]');
            const toDateInput = document.querySelector('input[name="toDate"]');
            const monthFilterInput = document.querySelector('input[name="monthFilter"]');
            const weekFilterInput = document.querySelector('input[name="weekFilter"]');

            const selectedFilter = "<?php echo e(request('filter_type', 'dateRange')); ?>";
            filterTypeSelect.value = selectedFilter;

            function showSelectedFilter(filterType) {
                dateRangeFilter.classList.add('d-none');
                monthlyFilter.classList.add('d-none');
                weeklyFilter.classList.add('d-none');

                if (filterType === 'monthly') {
                    monthlyFilter.classList.remove('d-none');
                } else if (filterType === 'weekly') {
                    weeklyFilter.classList.remove('d-none');
                } else {
                    dateRangeFilter.classList.remove('d-none');
                }
            }

            function resetOtherFilters(filterType) {
                if (filterType !== 'dateRange') {
                    fromDateInput.value = '';
                    toDateInput.value = '';
                }
                if (filterType !== 'monthly') {
                    monthFilterInput.value = '';
                }
                if (filterType !== 'weekly') {
                    weekFilterInput.value = '';
                }
            }

            // Initial show (preserve values on page load)
            showSelectedFilter(selectedFilter);

            // On filter type change (manual interaction)
            filterTypeSelect.addEventListener('change', function() {
                const selected = this.value;
                resetOtherFilters(selected);
                showSelectedFilter(selected);
            });
        });
    </script>


    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/opd/index.blade.php ENDPATH**/ ?>