<?php $__env->startSection('content'); ?>

    <style>
        .hidden {
            display: none;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            background-color: #ccc;
            border-radius: 24px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            transition: .3s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: .3s;
        }

        input:checked+.slider {
            background-color: #750096;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }
    </style>
    <!-- row start -->
    <div class="row p-4">
        <div class="col-md-4">
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                            <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>
                                Add Patient ID Card
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="">
                                                <div class="row gy-3">
                                                    <div class="col-md-12">
                                                        <label for="background_img" class="form-label">Background
                                                            Image</label>
                                                        <input type="file" name="background_img" id="background_img"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="logo" class="form-label">Logo</label>
                                                        <input type="file" name="logo" id="logo" class="form-control">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="signature" class="form-label">Signature</label>
                                                        <input type="file" name="signature" id="signature"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="hospital_name" class="form-label">Hospital Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="hospital_name" id="hospital_name"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="add_phn_mail" class="form-label">Address / Phone / Email
                                                            <span class="text-danger">*</span></label>
                                                        <textarea name="add_phn_mail" id="add_phn_mail" class="form-control"
                                                            required></textarea>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="id_title" class="form-label">Patient ID Card Title <span
                                                                class="text-danger">*</span></label>
                                                        <textarea name="id_title" id="id_title" class="form-control"
                                                            required></textarea>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="header_color" class="form-label">Header Color </label>
                                                        <div class="row gx-1">
                                                            <div class="col-md-9">
                                                                <input type="text" id="hexInput" class="form-control"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="color" id="colorPicker" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="toggleField" class="form-label">
                                                            Patient Name</label>
                                                        <label class="switch form-label">
                                                            <input type="checkbox" id="toggleField">
                                                            <span class="slider form-control"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="toggleField" class="form-label">
                                                            Patient ID</label>
                                                        <label class="switch form-label">
                                                            <input type="checkbox" id="toggleField">
                                                            <span class="slider form-control"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="toggleField" class="form-label">
                                                            Guardian Name</label>
                                                        <label class="switch form-label">
                                                            <input type="checkbox" id="toggleField">
                                                            <span class="slider form-control"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="toggleField" class="form-label">
                                                            Patient Address</label>
                                                        <label class="switch form-label">
                                                            <input type="checkbox" id="toggleField">
                                                            <span class="slider form-control"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="toggleField" class="form-label">
                                                            Phone</label>
                                                        <label class="switch form-label">
                                                            <input type="checkbox" id="toggleField">
                                                            <span class="slider form-control"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="toggleField" class="form-label">
                                                            Date Of Birth</label>
                                                        <label class="switch form-label">
                                                            <input type="checkbox" id="toggleField">
                                                            <span class="slider form-control"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="toggleField" class="form-label">
                                                            Blood Group</label>
                                                        <label class="switch form-label">
                                                            <input type="checkbox" id="toggleField">
                                                            <span class="slider form-control"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="toggleField" class="form-label">
                                                            Barcode / QR Code</label>
                                                        <label class="switch form-label">
                                                            <input type="checkbox" id="toggleField">
                                                            <span class="slider form-control"></span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                            <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>
                                Patient ID Card List
                            </h5>
                        </div>
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
                                                            <th>Certificate Template Name</th>
                                                            <th>Background Image</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center flex-wrap gap-2">
                                                                    <div class="text-end d-flex">
                                                                        <a href="patient_details_template"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#patient_id_card">Sample Patient
                                                                            ID Card</a>
                                                                    </div>
                                                                    <!-- First Modal -->
                                                                    <div id="patient_id_card" class="modal fade">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="fw-bold modal-title">Patient
                                                                                        ID Card</h5>
                                                                                    <button type="button"
                                                                                        class="btn-close btn-close-modal custom-btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"><i
                                                                                            class="ti ti-x"></i></button>
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                    <div class="row pb-3">
                                                                                        <div class="col-md-8">
                                                                                            <img src="" alt="Hospital Logo">
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <p><strong
                                                                                                    class="text-primary">Address
                                                                                                    :</strong> ABC</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card bg-light">
                                                                                        <div class="card-body">
                                                                                            <div
                                                                                                class="d-flex align-items-center">
                                                                                                <div class="me-2">
                                                                                                    <img src="assets/img/users/user-08.jpg"
                                                                                                        alt="img"
                                                                                                        class="img-fluid avatar avatar-xxl rounded">
                                                                                                </div>
                                                                                                <div>
                                                                                                    <span
                                                                                                        class="text-primary mb-1">#STF020</span>
                                                                                                    <div
                                                                                                        class="d-flex align-items-center mb-1">
                                                                                                        <h5
                                                                                                            class="fw-bold mb-0 me-2">
                                                                                                            James Allaire
                                                                                                        </h5>

                                                                                                    </div>
                                                                                                    <p class="text-body">
                                                                                                        <b>Phone :</b>
                                                                                                        [phone]
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div><!-- end card -->

                                                                                    <ul
                                                                                        class="nav nav-tabs nav-bordered mb-3">
                                                                                        <li class="nav-item"><a
                                                                                                class="nav-link active"
                                                                                                href="javascript:void(0);"
                                                                                                data-bs-toggle="tab"
                                                                                                data-bs-target="#tab1">Basic
                                                                                                Info</a></li>
                                                                                    </ul>

                                                                                    <div class="tab-content">
                                                                                        <div class="tab-pane active"
                                                                                            id="tab1" role="tabpanel"
                                                                                            tabindex="0">

                                                                                            <!-- start row -->
                                                                                            <div class="row row-gap-2">
                                                                                                <div class="col-md-4">
                                                                                                    <p
                                                                                                        class="text-primary fs-13 fw-medium mb-0">
                                                                                                        Guardian Name</p>
                                                                                                    <p class="fs-13">
                                                                                                        [Guardian Name]
                                                                                                    </p>
                                                                                                </div><!-- end col -->

                                                                                                <div class="col-md-4">
                                                                                                    <p
                                                                                                        class="text-primary fs-13 fw-medium mb-0">
                                                                                                        D.O.B</p>
                                                                                                    <p class="fs-13">
                                                                                                        25.06.2006
                                                                                                    </p>
                                                                                                </div><!-- end col -->
                                                                                                <div class="col-md-4">
                                                                                                    <p
                                                                                                        class="text-primary fs-13 fw-medium mb-0">
                                                                                                        Blood Group</p>
                                                                                                    <p class="fs-13">A+</p>
                                                                                                </div><!-- end col -->
                                                                                                <div class="col-md-4">
                                                                                                    <p
                                                                                                        class="text-primary fs-13 fw-medium mb-0">
                                                                                                        Email</p>
                                                                                                    <p class="fs-13">
                                                                                                        [email&#160;protected]</a>
                                                                                                    </p>
                                                                                                </div><!-- end col -->
                                                                                                <div class="col-md-8">
                                                                                                    <p
                                                                                                        class="text-primary fs-13 fw-medium mb-0">
                                                                                                        Addresss</p>
                                                                                                    <p class="fs-13">Near
                                                                                                        Railway Station
                                                                                                        Jabalpur
                                                                                                    </p>
                                                                                                </div><!-- end col -->
                                                                                            </div>
                                                                                            <!-- end row -->

                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <img src="" alt="Background Image">
                                                            </td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-primary rounded-pill">
                                                                        <i class="ti ti-menu" data-bs-toggle="tooltip"
                                                                            title="Show"></i></a>
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-secondary rounded-pill">
                                                                        <i class="ti ti-pencil" data-bs-toggle="tooltip"
                                                                            title="edit"></i></a>
                                                                    <a href="javascript: void(0);"
                                                                        class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                                        <i class="ti ti-trash" data-bs-toggle="tooltip"
                                                                            title="Delete"></i></a>
                                                                </div>
                                                            </td>
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

    <script>
        const colorPicker = document.getElementById("colorPicker");
        const hexInput = document.getElementById("hexInput");

        // Set default value
        hexInput.value = colorPicker.value;

        colorPicker.addEventListener("input", () => {
            hexInput.value = colorPicker.value;
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\certificate\patient_id.blade.php ENDPATH**/ ?>