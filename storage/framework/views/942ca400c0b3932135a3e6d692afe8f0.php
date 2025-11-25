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
                <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> IPD </h5>
                <div class="text-end d-flex">
                    <ul class="nav nav-tabs">
                        <li class="nav-item" style="border-bottom:0">
                            <a class="nav-link mb-0 <?php echo e(@$isIpdTab ? 'active' : ''); ?> text-white"
                                href="<?php echo e(route('ipd', array_merge(request()->except('tab'), ['tab' => 'ipd']))); ?>">
                                IPD List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 <?php echo e(@!$isIpdTab ? 'active' : ''); ?> text-white"
                                href="<?php echo e(route('ipd', array_merge(request()->except('tab'), ['tab' => 'discharge']))); ?>">
                                Discharged Patient
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
                                        <form action="<?php echo e(route('ipd')); ?>" method="GET">
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
                                            data-bs-target="#createIpdModal">Admit Patient</button>
                                    </div>
                                </div>
                                <?php if($isIpdTab): ?>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>IPD No.</th>
                                                    <th>Patient Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Gender</th>
                                                    <th>Consultant</th>
                                                    <th>Bed</th>
                                                    <th>Credit Limit (INR)</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $ipd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ipdDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($loop->iteration); ?></td>
                                                        <td><a href="<?php echo e(route('ipd.show', ['id' => $ipdDetails->id])); ?>"
                                                                class="text-primary"><?php echo e($ipdDetails->ipd_no); ?></a>
                                                        </td>
                                                        <td><?php echo e($ipdDetails->patient->patient_name ?? '-'); ?></td>
                                                        <td><?php echo e($ipdDetails->patient->mobileno ?? '-'); ?></td>
                                                        <td><?php echo e($ipdDetails->patient->email ?? '-'); ?></td>
                                                        <td><?php echo e($ipdDetails->patient->gender ?? '-'); ?></td>
                                                        <td><?php echo e($ipdDetails->doctor->name ?? '-'); ?></td>
                                                        <td><span class="badge"
                                                                style="background-color: <?php echo e($ipdDetails->bedGroup->color); ?>">
                                                                <?php echo e($ipdDetails->bedDetail->name . ' - ' . $ipdDetails->bedGroup->name . '-' . $ipdDetails->bedGroup->floorDetail->name ?? '-'); ?></span>
                                                        </td>
                                                        <td><?php echo e($ipdDetails->credit_limit); ?></td>
                                                        <td>
                                                            <a href="<?php echo e(route('ipd.edit', [$ipdDetails->id])); ?>"
                                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                                <i class="ti ti-pencil"></i>
                                                            </a>
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
                                                    <th>Consultant</th>
                                                    <th>Discharged Date</th>
                                                    <th>Net Amount (INR)</th>
                                                    <th>Tax (INR)</th>
                                                    <th>Total (INR)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $ipd; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ipdDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($loop->iteration); ?></td>
                                                        <td><a href="#"
                                                                class="text-primary"><?php echo e($ipdDetails->patient->patient_name); ?></a>
                                                        </td>
                                                        <td><?php echo e($ipdDetails->patient->mobileno); ?></td>
                                                        <td><?php echo e($ipdDetails->patient->email); ?></td>
                                                        <td><?php echo e($ipdDetails->patient->address); ?></td>
                                                        <td><?php echo e($ipdDetails->patient->gender); ?></td>
                                                        <td><?php echo e($ipdDetails->doctor->name ?? '-'); ?></td>
                                                        <td><?php echo e(\Carbon\Carbon::parse($ipdDetails->discharged_date)->format('d-M-Y') ?? '-'); ?>

                                                        </td>
                                                        <td><?php echo e($ipdDetails->net_amount ?? 0); ?></td>
                                                        <td><?php echo e($ipdDetails->tax ?? 0); ?></td>
                                                        <td><?php echo e($ipdDetails->amount ?? 0); ?></td>


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
                                        <form action="<?php echo e(request()->routeIs('ipd') ? route('ipd') : route('ipd')); ?>"
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
                                                <a href="<?php echo e(request()->routeIs('ipd') ? route('ipd') : route('ipd')); ?>"
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

    
    <?php echo $__env->make('components.modals.ipd-create-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    

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


    <!--  -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/ipd/index.blade.php ENDPATH**/ ?>