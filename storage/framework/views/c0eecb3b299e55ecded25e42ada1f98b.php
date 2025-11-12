

<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center">
        
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Item Supplier List</h5>
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
                                        <div class="page_btn d-flex">
                                            <div class="text-end d-flex">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary text-white ms-2 fs-13 btn-md"
                                                    data-bs-toggle="modal" data-bs-target="#add_item_supplier"><i
                                                        class="ti ti-plus me-1"></i>Add Item Supplier</a>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="add_item_supplier" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content modal-lg">
                                                        <div class="modal-header rounded-0 modal-lg"
                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                            <h5 class="modal-title" id="addSpecializationLabel">Add Item
                                                                Supplier

                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="row gy-3 mb-2">

                                                                    <!-- Operation Name -->
                                                                    <div class="col-md-6">
                                                                        <label for="name" class="form-label">Name
                                                                            <span class="text-danger">*</span></label>
                                                                        <input type="text" name="name" id="name"
                                                                            class="form-control" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="phone" class="form-label">Phone
                                                                        </label>
                                                                        <input type="tel" name="phone" id="phone"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="mail" class="form-label">Email
                                                                        </label>
                                                                        <input type="email" name="mail" id="mail"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="contact_person_name"
                                                                            class="form-label">Contact Person Name
                                                                        </label>
                                                                        <input type="text" name="contact_person_name"
                                                                            id="contact_person_name" class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="address" class="form-label">Address
                                                                        </label>
                                                                        <input type="address" name="address" id="address"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="contact_person_phone"
                                                                            class="form-label">Contact Person Phone
                                                                        </label>
                                                                        <input type="tel" name="contact_person_phone"
                                                                            id="contact_person_phone"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="contact_person_email"
                                                                            class="form-label">Contact Person Email
                                                                        </label>
                                                                        <input type="email" name="contact_person_email"
                                                                            id="contact_person_email"
                                                                            class="form-control" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="description" class="form-label">Contact
                                                                            Person Email
                                                                        </label>
                                                                        <textarea name="description" id="description"
                                                                            class="form-control"></textarea>
                                                                    </div>


                                                                    <div class="col-md-12">
                                                                        <label for="description"
                                                                            class="form-label">Description</label>
                                                                        <textarea name="description" id="description"
                                                                            class="form-control"></textarea>
                                                                    </div>

                                                                </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Item Supplier</th>
                                                    <th>Contact Person</th>
                                                    <th>Address</th>
                                                    <th style="width: 200px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="#" data-toggle="popover" class="detail_popover"
                                                            data-original-title="" title="">
                                                            SurgiMed Disposables <br>
                                                        </a>
                                                        <i class="fa fa-phone-square" style="color: #a941c6;"></i>
                                                        +911127584482 <br>
                                                        <i class="fa fa-envelope" style="color: #a941c6;"></i>
                                                        sales@surgimed.in <div class="fee_detail_popover"
                                                            style="display: none">
                                                            <p class="text text-info">Supplier of high-quality disposable
                                                                surgical items such as gloves, catheters, sutures, and
                                                                sterile dressing packs.</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <i class="fa fa-user" style="color: #a941c6;"></i> Ms. Preeti Verma
                                                        <br>
                                                        <i class="fa fa-phone-square" style="color: #a941c6;"></i>
                                                        +919811722976 <br>
                                                        <i class="fa fa-envelope" style="color: #a941c6;"></i>
                                                        preeti.verma@surgimed.in
                                                    </td>
                                                    <td>
                                                        <i class="fa fa-building" style="color: #a941c6;"></i> 14/6
                                                        Industrial Area, Wazirpur, New
                                                        Delhi 110052
                                                    </td>
                                                    <td>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success rounded-pill">
                                                            <i class="ti ti-pencil"></i></a>
                                                        <a href="javascript: void(0);"
                                                            class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill">
                                                            <i class="ti ti-trash"></i></a>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp82\htdocs\hims\resources\views/admin/setup/item_supplier.blade.php ENDPATH**/ ?>