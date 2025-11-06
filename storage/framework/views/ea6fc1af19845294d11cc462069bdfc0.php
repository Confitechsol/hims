

<?php $__env->startSection('content'); ?>

    <!-- <style>
                                .guidelines-box {
                                    display: none;
                                    margin-top: 15px;
                                    transition: opacity 0.4s ease-in-out;
                                }

                                .guidelines-box.show {
                                    display: block;
                                    animation: popFadeIn 0.4s ease forwards;
                                }

                                .guidelines-box.hide {
                                    animation: popFadeOut 0.35s ease forwards;
                                }

                                @keyframes popFadeIn {
                                    0% {
                                        opacity: 0;
                                        transform: scale(0.8) translateY(20px);
                                    }

                                    60% {
                                        opacity: 1;
                                        transform: scale(1.05) translateY(0);
                                    }

                                    100% {
                                        opacity: 1;
                                        transform: scale(1) translateY(0);
                                    }
                                }

                                @keyframes popFadeOut {
                                    0% {
                                        opacity: 1;
                                        transform: scale(1) translateY(0);
                                    }

                                    100% {
                                        opacity: 0;
                                        transform: scale(0.8) translateY(20px);
                                    }
                                }
                            </style> -->

    <style>
        .guidelines-box {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transform: scale(0.1) translateY(10px);
            transition: all 0.4s ease;
        }

        .guidelines-box.show {
            max-height: 600px;
            /* big enough to fit content */
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        .table thead tr th {
            white-space: nowrap;
        }

        .table tbody tr td {
            white-space: nowrap;
        }

        .import_form {
            padding: 20px;
            background-color: #e0b3ec38;
            margin-top: 25px;
            border-radius: 8px;
            box-shadow: 1px 1px 6px 3px #ececec
        }
    </style>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i> Import Patient</h5>
                </div>

                <div class="card-body">


                    
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">
                                    <div
                                        class="d-flex align-items-sm-center justify-content-end flex-sm-row flex-column gap-2 mb-3 pb-3">


                                        <div class="page_btn d-flex">




                                            <!-- Guidelines Button -->
                                            <button type="button" class="btn btn-primary" onclick="toggleGuidelines()">
                                                Guidelines
                                            </button>



                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"><i
                                                        class="ti ti-download me-1"></i>Download Sample Data</a>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Hidden Guidelines Section -->
                                    <div id="guidelinesBox" class="guidelines-box">
                                        <div class="example_txt card p-3 shadow-sm">
                                            <ol>
                                                <li class="mb-2">Your CSV data should be in the format below. The first line
                                                    of your CSV
                                                    file
                                                    should be the column headers as in the table example. Also make sure
                                                    that
                                                    your file is UTF-8 to avoid unnecessary encoding problems.
                                                </li>
                                                <li class="mb-2">For patient 'Gender' use Male, Female value.</li>
                                                <li class="mb-2">For Age column 'Age (year)' and 'Age (month)' and 'Age
                                                    (day)' make sure
                                                    that is numbers only.</li>
                                                <li>For patient 'Marital Status' use Single, Married, Widowed, Separated,
                                                    Not Specified value.</li>
                                            </ol>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>

                                                    <th>Patient</th>
                                                    <th>Gender</th>
                                                    <th>Age(Year)</th>
                                                    <th>Age(Month)</th>
                                                    <th>Age(Day)</th>
                                                    <th>Marital Status</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                    <th>Remarks</th>
                                                    <th>Known Allergies</th>
                                                    <th>Identification Number</th>
                                                    <th>TPA ID</th>
                                                    <th>TPA Validity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                    <td>Sample Data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="import_form">
                                        <form>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="" class="form-label">
                                                        Blood Group</label>
                                                    <select name="blood_group" class="form-select">
                                                        <option value=""> Select</option>
                                                        <option value="1"> O+</option>
                                                        <option value="2"> A+</option>
                                                        <option value="3"> B+</option>
                                                        <option value="4"> AB+</option>
                                                        <option value="5"> O-</option>
                                                        <option value="6"> AB-</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="" class="form-label">
                                                        Select CSV File <span class="text-danger">*</span></label>
                                                    <input type="file" name="csv_file" id="csv_file" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- <script>
                            function toggleGuidelines() {
                                var box = document.getElementById("guidelinesBox");

                                if (box.classList.contains("show")) {
                                    box.classList.remove("show");
                                    box.classList.add("hide");

                                    setTimeout(() => {
                                        box.classList.remove("hide");
                                        box.style.display = "none";
                                    }, 350); // match fadeOut duration
                                } else {
                                    box.style.display = "block";
                                    box.classList.add("show");
                                }
                            }
                        </script> -->

    <script>
        function toggleGuidelines() {
            var box = document.getElementById("guidelinesBox");
            box.classList.toggle("show");
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/setup/import_patient.blade.php ENDPATH**/ ?>