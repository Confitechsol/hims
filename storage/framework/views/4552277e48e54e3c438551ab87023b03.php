

<?php $__env->startSection('content'); ?>

    <style>
        .module_billing {
            border-radius: 8px;
            color: #fff;
            background-color: #CB6CE7;
            width: 100%;
            padding: 15px;
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> IPD Discharge Patient
                            Report </h5>
                        <a href="ipdReportsIndex" class="text-white fw-bold"><i
                                class="fa-solid fa-angles-left text-white"></i>
                            IPD</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="">
                        <div class="row align-items-center gy-4">

                            <div class="col-md-3">
                                <label for="time_duration" class="form-label">Time Duration <span
                                        class="text-danger">*</span></label>
                                <select name="time_duration" id="time_duration" class="form-select">
                                    <option value="" selected disabled>Select</option>
                                    <option value="today">Today</option>
                                    <option value="this_week">This Week</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="doctor" class="form-label">Doctor </label>
                                <select name="doctor" id="doctor" class="form-select">
                                    <option value="" selected disabled>Select</option>
                                    <option value="doc1">Anjali Rao (D011)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="from_age" class="form-label"> From Age </label>
                                <select name="from_age" id="from_age" class="form-select">
                                    <option value="" selected disabled>Select</option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="to_age" class="form-label"> To Age </label>
                                <select name="to_age" id="to_age" class="form-select">
                                    <option value="" selected disabled>Select</option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="gender" class="form-label"> Gender </label>
                                <select name="gender" id="gender" class="form-select">
                                    <option value="" selected disabled>Select</option>
                                    <option value="male">Male</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="discharge" class="form-label"> Discharge Status </label>
                                <select name="discharge" id="discharge" class="form-select">
                                    <option value="" selected disabled>Select</option>
                                    <option>Death</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="miscellaneous" class="form-label"> Miscellaneous </label>
                                <select name="miscellaneous" id="miscellaneous" class="form-select">
                                    <option value="" selected disabled>Select</option>
                                    <option value="antenatal">Antenatal</option>
                                </select>
                            </div>

                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-primary btn-sm mt-4">Search</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-11">
            <div class="row pt-0">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div
                                                class="d-flex align-items-sm-center justify-content-between flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                                                <div class="input-icon-start position-relative me-2">
                                                    <span class="input-icon-addon">
                                                        <i class="ti ti-search"></i>
                                                    </span>
                                                    <input type="text" class="form-control shadow-sm" placeholder="Search">

                                                </div>
                                            </div>


                                            <!-- Table start -->
                                            <div class="table-responsive table-nowrap">
                                                <table class="table border">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Patient Name</th>
                                                            <th>IPD No</th>
                                                            <th>Case ID </th>
                                                            <th>Gender</th>
                                                            <th>Phone</th>
                                                            <th>Antenatal</th>
                                                            <th>Consultant Doctor</th>
                                                            <th>Bed</th>
                                                            <th>Admission Date</th>
                                                            <th>Discharged Date	</th>
                                                            <th>Discharge Status </th>
                                                            <th>Total Admit Days</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Table end -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/reports/ipd/ipd_discharge_patient.blade.php ENDPATH**/ ?>