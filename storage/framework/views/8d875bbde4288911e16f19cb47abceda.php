<?php $__env->startSection('content'); ?>

    <style>
        .module_billing {
            border-radius: 8px;
            color: #fff;
            background-color: #ab00db;
            width: 100%;
            padding: 20px;
            box-shadow: 5px 5px 8px 0px #bbbbbb;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>

    <div class="row justify-content-center">

        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Single Module Billing </h5>
                </div>

                <div class="card-body">
                    <div class="row align-items-center gy-4">
                        <div class="col-md-4">
                            <a href="appointment-billing">
                                <div class="module_billing">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    <p>Appointment</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="opd">
                                <div class="module_billing">
                                    <i class="fa-solid fa-stethoscope"></i>
                                    <p>OPD</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#">
                                <div class="module_billing">
                                    <i class="fa-solid fa-flask"></i>
                                    <p>Pathology</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#">
                                <div class="module_billing">
                                    <i class="fa-solid fa-microscope"></i>
                                    <p>Radiology</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#">
                                <div class="module_billing">
                                    <i class="fa-solid fa-droplet"></i>
                                    <p>Blood Issue</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#">
                                <div class="module_billing">
                                    <i class="fa-solid fa-hand-holding-droplet"></i>
                                    <p>Blood Component Issue</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-1">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> OPD/IPD Billing Through Case Id
                    </h5>
                </div>

                <div class="card-body">
                    <form action="">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="col-md-1">
                                <label for="case_id" class="form-label">Case ID <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control caseId" name="case_id" id="case_id" value="">
                            </div>
                            <div class="col-md-3">
                                <button type="button" onclick="search()" class="btn btn-primary btn-sm">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>







    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for the doctor dropdown
            $('#doctor').select2({
                width: '100%',
                placeholder: 'Select',
                allowClear: true
            });
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/billing/billing.blade.php ENDPATH**/ ?>