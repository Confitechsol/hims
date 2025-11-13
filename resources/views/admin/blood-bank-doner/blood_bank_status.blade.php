@extends('layouts.adminLayout')

@section('content')
    <style>

        .blood-group-list {
            background-color: #f8f9fb;
            border-right: 1px solid #ddd;
            min-height: 100%;
            padding: 0;
        }

        .blood-group {
            padding: 12px 0;
            text-align: center;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .blood-group.active {
            background-color: #7c089c;
            color: #fff;
            position: relative;
        }

        .blood-group.active::after {
            content: "";
            position: absolute;
            top: 50%;
            right: -10px;
            transform: translateY(-50%);
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-left: 10px solid #7c089c;
        }

        .section-title {
            background-color: #cb6ce636;
            padding: 10px 15px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hidden {
            display: none;
        }

        table th,
        table td {
            vertical-align: middle !important;
        }
    </style>

    <div class="row p-4">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 w-100">
                        <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Blood Bank Status</h5>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="text-end d-flex">
                                <a href="javascript:void(0);" class="btn btn-white text-primary ms-2 btn-md">
                                    <i class="ti ti-menu me-1"></i>Donor Details</a>
                            </div>
                            <div class="text-end d-flex">
                                <a href="javascript:void(0);" class="btn btn-white text-primary ms-2 btn-md">
                                    <i class="ti ti-menu me-1"></i>Blood Issue Details</a>
                            </div>
                            <div class="text-end d-flex">
                                <a href="javascript:void(0);" class="btn btn-white text-primary ms-2 btn-md">
                                    <i class="ti ti-menu me-1"></i>Component Issue</a>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="container-fluid mt-2">
                        <div class="row">
                            <!-- Sidebar (Blood Groups) -->
                            <div class="col-md-2 blood-group-list" id="bloodGroups">
                                <div class="blood-group active" data-group="O+">O+</div>
                                <div class="blood-group" data-group="A+">A+</div>
                                <div class="blood-group" data-group="B+">B+</div>
                                <div class="blood-group" data-group="AB+">AB+</div>
                                <div class="blood-group" data-group="O-">O-</div>
                                <div class="blood-group" data-group="AB-">AB-</div>
                            </div>

                            <!-- Main Section -->
                            <div class="col-md-10">
                                <!-- Loop through Blood Groups as Tab Panels -->
                                <div id="groupTabs">

                                    <!-- O+ -->
                                    <div class="tab-content" id="O+">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="section-title">
                                                    <span>Blood</span>
                                                    <div><span>0 Bags</span> <button class="btn btn-primary ms-2"
                                                            data-bs-toggle="modal" data-bs-target="#blood_doner">+</button>

                                                        <!-- modal -->
                                                        <div class="modal fade" id="blood_doner" tabindex="-1" aria-hidden="true">
                                                            <div
                                                                class="modal-dialog modal-dialog-centered modal-fullscreen">
                                                                <div class="modal-content">
                                                                    <form method="POST">

                                                                        <div class="modal-header"
                                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                            <div class="row w-100 align-items-center">
                                                                                <div class="col-md-7">
                                                                                    <h4>Blood Doner Details</h4>
                                                                                </div>
                                                                                <div class="col-md-5 text-end">
                                                                                    <button type="button" class="btn-close"
                                                                                        data-bs-dismiss="modal"></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <div class="form-group">
                                                                                <div class="row gy-3">

                                                                                    <div class="col-md-3">
                                                                                        <label for="blood_doner"
                                                                                            class="form-label">Blood Doner
                                                                                            <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <select name="blood_doner"
                                                                                            id="blood_doner"
                                                                                            class="form-select" required>
                                                                                            <option value="0">Select
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label for="doner_date"
                                                                                            class="form-label">Doner Date
                                                                                            <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="date" name="doner_date"
                                                                                            id="doner_date"
                                                                                            class="form-control" required>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label for="bag"
                                                                                            class="form-label">Bag
                                                                                            <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text" name="bag"
                                                                                            id="bag" class="form-control"
                                                                                            required>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label for="volume"
                                                                                            class="form-label">Volume
                                                                                        </label>
                                                                                        <input type="text" name="volume"
                                                                                            id="volume"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label for="unit_type"
                                                                                            class="form-label">Unit Type
                                                                                        </label>
                                                                                        <select name="unit_type"
                                                                                            id="unit_type"
                                                                                            class="form-select">
                                                                                            <option value="0">Select
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label for="lot"
                                                                                            class="form-label">Lot
                                                                                        </label>
                                                                                        <input type="text" name="lot"
                                                                                            id="lot" class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label for="charge_category"
                                                                                            class="form-label">Charge
                                                                                            Category
                                                                                            <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <select name="charge_category"
                                                                                            id="charge_category"
                                                                                            class="form-select" required>
                                                                                            <option value="0">Select
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label for="charge_name"
                                                                                            class="form-label">Charge
                                                                                            Name
                                                                                            <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <select name="charge_name"
                                                                                            id="charge_name"
                                                                                            class="form-select" required>
                                                                                            <option value="0">Select
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <label for="charge_name"
                                                                                            class="form-label">Standard
                                                                                            Charge (INR) <span
                                                                                                class="text-danger">*</span></label>
                                                                                        <input type="text"
                                                                                            name="charge_name"
                                                                                            id="charge_name"
                                                                                            class="form-control" required>
                                                                                    </div>
                                                                                    <div class="col-md-9">
                                                                                        <label for="charge_name"
                                                                                            class="form-label">Institution</label>
                                                                                        <input type="text"
                                                                                            name="charge_name"
                                                                                            id="charge_name"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="note"
                                                                                            class="form-label">Note</label>
                                                                                        <textarea name="note" id="note"
                                                                                            class="form-control"></textarea>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <table class="printablea4">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <th width="40%">Total
                                                                                                        (INR)</th>
                                                                                                    <td width="60%"
                                                                                                        colspan="2"
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <input type="text"
                                                                                                            placeholder="Total"
                                                                                                            value="0"
                                                                                                            name="total"
                                                                                                            id="total"
                                                                                                            style="width: 30%; float: right"
                                                                                                            class="form-control total"
                                                                                                            readonly="">
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>Discount (INR)</th>
                                                                                                    <td
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <h4
                                                                                                            style="float: right;font-size: 12px; padding-left: 5px;">
                                                                                                            %</h4>
                                                                                                        <input type="text"
                                                                                                            placeholder="Discount"
                                                                                                            name="discount_percent"
                                                                                                            id="discount_percent"
                                                                                                            class="form-control discount_percent"
                                                                                                            style="width: 70%; float: right;font-size: 12px;">
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <input type="text"
                                                                                                            placeholder="Discount"
                                                                                                            value="0"
                                                                                                            name="discount"
                                                                                                            id="discount"
                                                                                                            style="width: 50%; float: right"
                                                                                                            class="form-control discount">
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>Tax (INR)</th>
                                                                                                    <td
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <h4
                                                                                                            style="float: right;font-size: 12px; padding-left: 5px;">
                                                                                                            %</h4>
                                                                                                        <input type="text"
                                                                                                            placeholder="Tax"
                                                                                                            name="tax_percentage"
                                                                                                            id="tax_percentage"
                                                                                                            class="form-control tax_percentage"
                                                                                                            readonly=""
                                                                                                            style="width: 70%; float: right;font-size: 12px;">
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <input type="text"
                                                                                                            placeholder="Tax"
                                                                                                            name="tax"
                                                                                                            value="0"
                                                                                                            id="tax"
                                                                                                            style="width: 50%; float: right"
                                                                                                            class="form-control tax"
                                                                                                            readonly="">
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <th>Net Amount (INR)
                                                                                                    </th>
                                                                                                    <td colspan="2"
                                                                                                        class="text-right ipdbilltable">
                                                                                                        <input type="text"
                                                                                                            placeholder="Net Amount"
                                                                                                            value="0"
                                                                                                            name="net_amount"
                                                                                                            id="net_amount"
                                                                                                            style="width: 30%; float: right"
                                                                                                            class="form-control net_amount"
                                                                                                            readonly="">
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group">
                                                                                                    <label
                                                                                                        class="form-label">Payment
                                                                                                        Mode</label>
                                                                                                    <select
                                                                                                        class="form-select payment_mode"
                                                                                                        name="payment_mode">
                                                                                                        <option
                                                                                                            value="Cash">
                                                                                                            Cash</option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group">
                                                                                                    <label
                                                                                                        class="form-label">Payment
                                                                                                        Amount
                                                                                                        (INR)</label><small
                                                                                                        class="req">
                                                                                                        *</small>
                                                                                                    <input type="text"
                                                                                                        name="payment_amount"
                                                                                                        id="payment_amount"
                                                                                                        class="form-control payment_amount text-right">

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Calculate</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Save</button>
                                                                        </div>
                                                                    </form>



                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered table-striped mt-2">
                                                    <thead>
                                                        <tr>
                                                            <th>Bags</th>
                                                            <th>Lot</th>
                                                            <th>Institution</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>234 (Each)</td>
                                                            <td>3</td>
                                                            <td>Red Blood Cells (RBCs)</td>
                                                            <td><button class="btn btn-primary">Issue</button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="section-title">
                                                    <span>Components</span>
                                                    <div><span>1 Bag</span> <button class="btn btn-primary ms-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#add_components">+</button>


                                                        <!-- modal -->
                                                        <div class="modal fade" id="add_components" tabindex="-1"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                                                <div class="modal-content">
                                                                    <form method="POST">

                                                                        <div class="modal-header"
                                                                            style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                                                                            <div class="row w-100 align-items-center">
                                                                                <div class="col-md-7">
                                                                                    <h4>Add Components</h4>
                                                                                </div>
                                                                                <div class="col-md-5 text-end">
                                                                                    <button type="button" class="btn-close"
                                                                                        data-bs-dismiss="modal"></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-3 col-md-3 col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">Blood
                                                                                            Group</label><span
                                                                                            class="text-danger"> *</span>
                                                                                        <select class="form-control"
                                                                                            id="blood_bank_product_id"
                                                                                            name="blood_bank_product_id">
                                                                                            <option value="">Select</option>
                                                                                            <option value="1">O+</option>
                                                                                        </select>

                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-3 col-md-3 col-sm-6">
                                                                                    <div class="form-group select-100">
                                                                                        <label
                                                                                            class="form-label">Bag</label><span
                                                                                            class="text-danger"> *</span>
                                                                                        <select style="width: 100%"
                                                                                            class="form-control"
                                                                                            name="blood_donor_cycle_id"
                                                                                            id="blood_bank_product_id">
                                                                                            <option value="">Select</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--./row-->
                                                                            <div class="table-responsive pt-4">
                                                                                <table
                                                                                    class="table table-striped table-bordered table-hover">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="white-space-nowrap">
                                                                                                Components Name <span
                                                                                                    class="text-danger">*</span>
                                                                                            </th>
                                                                                            <th>Bag <span
                                                                                                    class="text-danger">*</span>
                                                                                            </th>
                                                                                            <th>Volume</th>
                                                                                            <th>Unit</th>
                                                                                            <th>Lot <span
                                                                                                    class="text-danger">*</span>
                                                                                            </th>
                                                                                            <th>Institution</th>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="checkbox">
                                                                                                    <label
                                                                                                        class="form-check-label"><input
                                                                                                            type="checkbox"
                                                                                                            name="select[]"
                                                                                                            value="7"
                                                                                                            class="form-check-input">
                                                                                                        Red
                                                                                                        Blood Cells
                                                                                                        (RBCs)</label>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="bag_no_7"
                                                                                                    value=""></td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="volume_7"
                                                                                                    value=""></td>
                                                                                            <td><select type="text"
                                                                                                    class="form-select min-w-sm-160-px"
                                                                                                    name="unit_7" value=""
                                                                                                    autocomplete="off">
                                                                                                    <option value=""> Select
                                                                                                    </option>
                                                                                                </select></td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="lot_7" value="">
                                                                                            </td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="institution_7"
                                                                                                    value=""></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="checkbox">
                                                                                                    <label
                                                                                                        class="form-check-label"><input
                                                                                                            type="checkbox"
                                                                                                            name="select[]"
                                                                                                            value="8"
                                                                                                            class="form-check-input">
                                                                                                        Platelets</label>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="bag_no_8"
                                                                                                    value=""></td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="volume_8"
                                                                                                    value=""></td>
                                                                                            <td><select type="text"
                                                                                                    class="form-select min-w-sm-160-px"
                                                                                                    name="unit_8" value="">
                                                                                                    <option value=""> Select
                                                                                                    </option>
                                                                                                </select></td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="lot_8" value="">
                                                                                            </td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="institution_8"
                                                                                                    value=""></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div class="checkbox">
                                                                                                    <label
                                                                                                        class="form-check-label"><input
                                                                                                            type="checkbox"
                                                                                                            name="select[]"
                                                                                                            value="9"
                                                                                                            class="form-check-input">
                                                                                                        Plasma</label>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="bag_no_9"
                                                                                                    value=""></td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="volume_9"
                                                                                                    value=""></td>
                                                                                            <td><select type="text"
                                                                                                    class="form-select min-w-sm-160-px"
                                                                                                    name="unit_9" value="">
                                                                                                    <option value=""> Select
                                                                                                    </option>
                                                                                                </select></td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="lot_9" value="">
                                                                                            </td>
                                                                                            <td><input type="text"
                                                                                                    class="form-control min-w-sm-160-px"
                                                                                                    name="institution_9"
                                                                                                    value=""></td>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div><!--./row-->
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Calculate</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Save</button>
                                                                        </div>
                                                                    </form>



                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered table-striped mt-2">
                                                    <thead>
                                                        <tr>
                                                            <th>Bags</th>
                                                            <th>Lot</th>
                                                            <th>Components</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>234 (Each)</td>
                                                            <td>3</td>
                                                            <td>--</td>
                                                            <td><button class="btn btn-primary">Issue</button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- A+ -->
                                    <div class="tab-content hidden" id="A+">
                                          <div class="section-title text-center p-4">No Records for A+</div>
                                    </div>

                                    <!-- B+ -->
                                    <div class="tab-content hidden" id="B+">
                                        <div class="section-title text-center p-4">No Records for B+</div>
                                    </div>

                                    <!-- AB+ -->
                                    <div class="tab-content hidden" id="AB+">
                                        <div class="section-title text-center p-4">No Records for AB+</div>
                                    </div>

                                    <!-- O- -->
                                    <div class="tab-content hidden" id="O-">
                                        <div class="section-title text-center p-4">No Records for O-</div>
                                    </div>

                                    <!-- AB- -->
                                    <div class="tab-content hidden" id="AB-">
                                        <div class="section-title text-center p-4">No Records for AB-</div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->

    </div>

    <script>
        // Handle tab switching
        const bloodGroups = document.querySelectorAll('.blood-group');
        const tabs = document.querySelectorAll('.tab-content');

        bloodGroups.forEach(group => {
            group.addEventListener('click', () => {
                // Remove active class
                bloodGroups.forEach(g => g.classList.remove('active'));
                tabs.forEach(t => t.classList.add('hidden'));

                // Add active class to selected
                group.classList.add('active');
                const selectedTab = document.getElementById(group.dataset.group);
                if (selectedTab) selectedTab.classList.remove('hidden');
            });
        });
    </script>

@endsection