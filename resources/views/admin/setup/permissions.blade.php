@extends('layouts.adminLayout')
@section('content')


    <!-- Start Content -->
    <div class="content" id="profilePage">

        <!-- Page Header -->
        <div class="mb-3 border-bottom pb-3">
            <h4 class="fw-bold mb-0">Settings</h4>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body p-0">
                <div class="settings-wrapper d-flex">

                    <!-- Sidebar -->
                    <div class="sidebars settings-sidebar" id="sidebar2">
                        <div class="sidebar-inner" data-simplebar>
                            <div id="sidebar-menu5" class="sidebar-menu mt-0 p-0">
                                <ul class="nav flex-column" id="permissionTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="dashboard-wid-tab" data-bs-toggle="tab"
                                            href="#dashboard_wid" role="tab">
                                            <i class="ti ti-device-desktop-cog me-2"></i> Dashboard and Widgets
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="bill-tab" data-bs-toggle="tab" href="#bill" role="tab">
                                            <i class="ti ti-device-desktop-cog me-2"></i> Billing
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Sidebar -->

                    <!-- Main Card -->
                    <div class="card flex-fill mb-0 border-0 bg-light-500 shadow-none">
                        <div class="card-header border-bottom px-0 mx-3">
                            <h6 class="fs-14 mb-3">
                                <a href="roles-and-permissions.html">
                                    <i class="ti ti-chevron-left me-1"></i> Roles
                                </a>
                            </h6>
                            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                                <div class="flex-grow-1">
                                    <h4 class="fw-bold mb-0">Permissions</h4>
                                </div>
                                <div class="text-end d-flex">
                                    <div class="dropdown">
                                        <a href="javascript:void(0);"
                                            class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                                            data-bs-toggle="dropdown">
                                            <span class="text-body me-1">Role : </span> Admin
                                        </a>
                                        <ul class="dropdown-menu  dropdown-menu-end p-2">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Admin</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">User</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="card-body px-0 mx-3">
                            <div class="tab-content" id="permissionTabsContent">

                                <!-- Clinic -->
                                <div class="tab-pane fade show active" id="dashboard_wid" role="tabpanel"
                                    aria-labelledby="dashboard-wid-tab">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="fw-bold mb-0">Dashboard and Widgets Permissions</h6>
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                            <label for="select-all">Allow All</label>
                                        </div>
                                    </div>
                                    <div class="table-responsive border">
                                        <table class="table table-nowrap">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Feature</th>
                                                    <th>View</th>
                                                    <th>Add</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Staff Role Count Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">IPD Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">OPD Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Pharmacy Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Radiology Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Blood Bank Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Ambulance Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Yearly Income & Expense Chart</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Monthly Income & Expense Chart </p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Notification Center</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">General Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Expenses Widget </p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fa fa-save me-1"></i> Save
                                        </button>
                                    </div>
                                </div>

                                <!-- Other -->
                                <div class="tab-pane fade" id="bill" role="tabpanel" aria-labelledby="bill-tab">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="fw-bold mb-0">Billing Permissions</h6>
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                            <label for="select-all">Allow All</label>
                                        </div>
                                    </div>
                                     <div class="table-responsive border">
                                        <table class="table table-nowrap">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Feature</th>
                                                    <th>View</th>
                                                    <th>Add</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Staff Role Count Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">IPD Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">OPD Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Pharmacy Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Radiology Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Blood Bank Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Ambulance Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Yearly Income & Expense Chart</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Monthly Income & Expense Chart </p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Notification Center</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">General Income Widget</p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p class="fw-medium text-dark">Expenses Widget </p>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-md">
                                                            <input class="form-check-input" type="checkbox">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 text-end">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fa fa-save me-1"></i> Save
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- End Tab Content -->

                    </div>
                </div>
            </div>
        </div>
        <!-- end card -->

    </div>
    <!-- End Content -->



@endsection