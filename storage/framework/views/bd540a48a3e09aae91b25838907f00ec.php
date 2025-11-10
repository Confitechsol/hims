    <div class="sidebar" id="sidebar">

        <!-- Start Logo -->
        <div class="sidebar-logo">
            <div>
                <!-- Logo Normal -->
                <a href="<?php echo e(route('dashboard')); ?>" class="logo logo-normal">
                    <img src="assets/img/logo.png" alt="Logo">
                </a>

                <!-- Logo Small -->
                <a href="<?php echo e(route('dashboard')); ?>" class="logo-small">
                    <img src="assets/img/logo-small.svg" alt="Logo">
                </a>

                <!-- Logo Dark -->
                <a href="<?php echo e(route('dashboard')); ?>" class="dark-logo">
                    <!-- <img src="assets/img/logo-white.svg" alt="Logo"> -->
                    <h2>LOGO</h2>
                </a>
            </div>
            <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
                <i class="ti ti-arrow-left text-body"></i>
            </button>

            <!-- Sidebar Menu Close -->
            <button class="sidebar-close">
                <i class="ti ti-x align-middle"></i>
            </button>
        </div>
        <!-- End Logo -->

        <!-- Sidenav Menu -->
        <div class="sidebar-inner" data-simplebar>
            <div id="sidebar-menu" class="sidebar-menu">
                <div class="sidebar-top shadow-sm p-2 rounded-1 mb-3 dropend">
                    <a href="javascript:void(0);" class="drop-arrow-none" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" data-bs-offset="0,22" aria-haspopup="false" aria-expanded="false">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="avatar rounded-circle flex-shrink-0 p-2"><img
                                        src="assets/img/icons/trustcare.svg" alt="img"></span>
                                <div class="ms-2">
                                    <h6 class="fs-14 fw-semibold mb-0">Trustcare
                                        Clinic</h6>
                                    <p class="fs-13 mb-0">Lasvegas</p>
                                </div>
                            </div>
                            <i class="ti ti-arrows-transfer-up"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg">
                        <div class="p-2">
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="assets/img/icons/clinic-01.svg" alt></span>
                                    <span class="fw-semibold text-dark">CureWell
                                        Medical Hub<small class="d-block text-muted fw-normal fs-13">Ohio</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="assets/img/icons/clinic-02.svg" alt></span>
                                    <span class="fw-semibold text-dark">Trustcare
                                        Clinic<small class="d-block text-muted fw-normal fs-13">Lasvegas</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="assets/img/icons/clinic-03.svg" alt></span>
                                    <span class="fw-semibold text-dark">NovaCare
                                        Medical<small
                                            class="d-block text-muted fw-normal fs-13">Washington</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="assets/img/icons/clinic-04.svg" alt></span>
                                    <span class="fw-semibold text-dark">Greeny
                                        Medical Clinic<small
                                            class="d-block text-muted fw-normal fs-13">Illinios</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                        </div>
                    </div>
                </div>
                <ul>
                    <li class="menu-title"><span>Main Menu</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="active subdrop">
                                    <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('dashboard')); ?>" class="active">Admin
                                            Dashboard</a></li>
                                    <!--<li><a href="doctor-dashboard.html">Doctor
                                            Dashboard</a></li>
                                    <li><a href="patient-dashboard.html">Patient
                                            Dashboard</a></li>-->
                                </ul>
                            </li>
                            <li>
                                   <a href="<?php echo e(route('billing')); ?>">
                                      <!--<i class="fa fa-file-invoice"></i>  optional icon -->
                                          <span>Billing Section</span>
                                              <!--<span class="menu-arrow"></span>-->
                                </a>
                                <!--<ul>
                                    <li><a href="<?php echo e(route('billing')); ?>">Billing
                                            </a></li>
                                    <li><a href="doctor-dashboard.html">Appointment
                                            </a></li>
                                    <li><a href="<?php echo e(route('opd.billing')); ?>">OPD
                                            </a></li>
                                            <li><a href="patient-dashboard.html">Pathology
                                            </a></li>
                                            <li><a href="patient-dashboard.html">Radiology
                                            </a></li>
                                            <li><a href="patient-dashboard.html">Blood Issue
                                            </a></li>
                                            <li><a href="patient-dashboard.html">Blood Component Issue
                                            </a></li>
                                </ul>-->
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="active subdrop">
                                    <i class="ti ti-layout-dashboard"></i><span>Birth & Death Record</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="index.html" class="active">Birth Record
                                            </a></li>
                                    <li><a href="doctor-dashboard.html">Death Record
                                            </a></li>
                                    
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="active subdrop">
                                    <i class="ti ti-layout-dashboard"></i><span>Certificate</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="index.html" class="active">Certificate
                                            </a></li>
                                    <li><a href="doctor-dashboard.html">Patient ID Card
                                            </a></li>
                                    <li><a href="patient-dashboard.html">Staff ID Card
                                            </a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="menu-title"><span>Clinic</span></li>
                    <li>
                        <ul>
                            <!--<li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-user-plus"></i><span>Doctors</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="doctors.html">Doctors</a></li>
                                    <li><a href="doctor-details.html">Doctor
                                            Details</a></li>
                                    <li><a href="add-doctor.html">Add
                                            Doctor</a></li>
                                    <li><a href="doctor-schedule.html">Doctor
                                            Schedule</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-user-heart"></i><span>Patients</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('patients')); ?>">Patients</a></li>
                                    <li><a href="patient-details.html">Patient
                                            Details</a></li>
                                    <li><a href="create-patient.html">Create
                                            Patient</a></li>
                                    <li><a href="patient-details.html">OPD Patient
                                            Details</a></li>
                                    <li><a href="patient-details.html">IPD Patient
                                            Details</a></li>


                                </ul>
                            </li>-->
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('appointment-details')); ?>">Appointment Details</a></li>
                                    <!--<li><a href="new-appointment.html">New
                                            Appointment</a></li>
                                    <li><a href="appointment-calendar.html">Calendar</a></li>
                                
                            </li>-->
                            </ul>
<<<<<<< HEAD
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-map-pin"></i><span>Pharmacy</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul style="display: none;">
                                    <li><a href="<?php echo e(route('setup.medicine-category')); ?>">Medicine Category</a></li>
                                    <li><a href="<?php echo e(route('setup.medicine-supplier')); ?>">Medicine Supplier</a></li>
                                    <li><a href="<?php echo e(route('setup.medicine-dosage')); ?>">Medicine Dosage</a></li>
                                    <li><a href="<?php echo e(route('setup.medicine-group')); ?>">Medicine Group</a></li>
                                    <li><a href="<?php echo e(route('setup.medicine-unit')); ?>">Medicine Unit</a></li>
                                    <li><a href="<?php echo e(route('setup.dose-duration')); ?>">Dose Duration</a></li>
                                    <li><a href="<?php echo e(route('setup.dose-interval')); ?>">Dose Interval</a></li>
                                    <li><a href="<?php echo e(route('setup.medicine-company')); ?>">Medicine Company</a></li>
                                    <li><a href="<?php echo e(route('pharmacy.billing.index')); ?>">Pharmacy Billing</a></li>                                    
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-map-pin"></i><span>Pathology</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('pathology-category')); ?>">Pathology Category</a></li>
                                    <li><a href="<?php echo e(route('pathology-unit')); ?>">Pathology Unit</a></li>
                                    <li><a href="<?php echo e(route('pathology-parameter')); ?>">Pathology Parameter</a></li>
                                    <li><a href="<?php echo e(route('pathology.test.index')); ?>">Pathology Test</a></li>
                                    <li><a href="<?php echo e(route('pathology.billing.index')); ?>">Pathology Billing</a></li>
                                </ul>
=======
                            <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Pharmacy</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Pathology</span>
                                </a>
>>>>>>> 53ad2ac1640d46f4ef6740dd073a2ce3bbdd7ea7
                            </li>
                            <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Radiology</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Blood Bank</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Ambulance Call</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Front Office</span>
                                </a>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);" class="active subdrop">
                                    <i class="ti ti-layout-dashboard"></i><span>Duty Roster</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="index.html" class="active">Staff
                                            </a></li>
                                    <li><a href="doctor-dashboard.html">Doctors
                                            </a></li>
                                    
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Annual Calender</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('tpamanagement')); ?>">
                                    <i class="ti ti-map-pin"></i><span>TPA Management</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('inventory-details')); ?>">
                                    <i class="ti ti-map-pin"></i><span>Inventory</span>
                                </a>
                            </li>

                            <!-- <li>
                                <a href="services.html">
                                    <i class="ti ti-user-cog"></i><span>Services</span>
                                </a>
                            </li>
                            <li>
                                <a href="specializations.html">
                                    <i class="ti ti-user-shield"></i><span>Specializations</span>
                                </a>
                            </li> -->
                            <!-- <li>
                                <a href="assets.html">
                                    <i class="ti ti-asset"></i><span>Assets</span>
                                </a>
                            </li>
                            <li>
                                <a href="activities.html">
                                    <i class="ti ti-activity"></i><span>Activities</span>
                                </a>
                            </li>
                            <li>
                                <a href="messages.html">
                                    <i class="ti ti-messages"></i><span>Messages</span>
                                </a>
                            </li> -->
                        </ul>
                    </li> 
                    <li>
                        <ul>
                    <li class="submenu">
                                <a href="javascript:void(0);" class="active subdrop">
                                    <i class="ti ti-layout-dashboard"></i><span>Finance</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="index.html" class="active">Income
                                            </a></li>
                                    <li><a href="doctor-dashboard.html">Expenses
                                            </a></li>
                                    
                                </ul>
                            </li>
                            </ul>
                    </li>
                    <!--<li class="menu-title"><span>Finance &
                            Accounts</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-credit-card"></i><span>Expenses</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="expenses.html">Expenses</a></li>
                                    <li><a href="expense-category.html">Expense
                                            Category</a></li>
                                </ul>
                            </li>
                            
                            <li>
                                <a href="income.html">
                                    <i class="ti ti-coins"></i><span>Income</span>
                                </a>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-file-invoice"></i><span>Invoices</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="invoices.html">Invoices</a></li>
                                    <li><a href="invoices-details.html">Invoice
                                            Details</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="payments.html">
                                    <i class="ti ti-cards"></i><span>Payments</span>
                                </a>
                            </li>
                            <li>
                                <a href="transactions.html">
                                    <i class="ti ti-transition-right"></i><span>Transactions</span>
                                </a>
                            </li>
                        </ul>
                    </li>-->
                    <li class="menu-title"><span>Administration & HRM</span></li>
                    <li>
                        <ul>
                            <li>
                        <ul>
                            <li>
                                <a href="staffs.html">
                                    <i class="ti ti-users-group"></i><span>Staffs</span>
                                </a>
                            </li>
                            <li>
                                <a href="hrm-departments.html">
                                    <i class="ti ti-building-bank"></i><span>Departments</span>
                                </a>
                            </li>
                            <li>
                                <a href="designation.html">
                                    <i class="ti ti-user-cog"></i><span>Designation</span>
                                </a>
                            </li>
                            <li>
                                <a href="attendance.html">
                                    <i class="ti ti-user-check"></i><span>Attendance</span>
                                </a>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-users-minus"></i><span>Leaves</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="leaves.html">Leaves</a></li>
                                    <li><a href="leave-type.html">Leave
                                            Type</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="holidays.html">
                                    <i class="ti ti-home-exclamation"></i><span>Holidays</span>
                                </a>
                            </li>
                            <li>
                                <a href="payroll.html">
                                    <i class="ti ti-coin"></i><span>Payroll</span>
                                </a>
                            </li>
                        </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-report"></i><span>Reports</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="income-report.html">Income
                                            Report</a></li>
                                    <li><a href="expense-report.html">Expense
                                            Report</a></li>
                                    <li><a href="profit-and-loss.html">Profit
                                            & Loss</a></li>
                                    <li><a href="appointment-report.html">Appointment
                                            Report</a></li>
                                    <li><a href="patient-report.html">Patient
                                            Report</a></li>
                                            <li><a href="patient-report.html">Finance
                                            Report</a></li>
                                            <li><a href="patient-report.html">Appointment
                                            Report</a></li>
                                            <li><a href="patient-report.html">OPD
                                            Report</a></li>
                                            <li><a href="patient-report.html">IPD
                                            Report</a></li>
                                            <li><a href="patient-report.html">Pharmacy
                                            Report</a></li>
                                            <li><a href="patient-report.html">Pathology
                                            Report</a></li>
                                            <li><a href="patient-report.html">Radiology
                                            Report</a></li>
                                            <li><a href="patient-report.html">Blood Bank
                                            Report</a></li>
                                            <li><a href="patient-report.html">Ambulance
                                            Report</a></li>
                                            <li><a href="patient-report.html">Birth & Death
                                            Report</a></li>
                                            <li><a href="patient-report.html">Human Resource
                                            Report</a></li>
                                            <li><a href="patient-report.html">TPA
                                            Report</a></li>
                                            <li><a href="patient-report.html">Inventory
                                            Report</a></li>
                                            <li><a href="patient-report.html">Live Consultation
                                            Report</a></li>
                                            <li><a href="patient-report.html">Log
                                            Report</a></li>
                                            <li><a href="patient-report.html">OT
                                            Report</a></li>
                                            
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-title"><span>Settings</span></li>
                    <li>
                        <ul>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-user-cog"></i><span>Account
                                        Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('profile')); ?>">Profile</a></li>
                                    <!--<li><a href="security-settings.html">Security</a></li>
                                    //<li><a href="notifications-settings.html">Notifications</a></li>
                                    <li><a href="integrations-settings.html">Integrations</a></li>-->
                                    <li><a href="<?php echo e(route('profile')); ?>">General Settings</a></li>
                                    <li><a href="<?php echo e(route('email-setting')); ?>">Email Settings</a></li>
                                    <li><a href="<?php echo e(route('prefix')); ?>">Prefix Settings</a></li>
                                    <li><a href="<?php echo e(route('roles')); ?>">Roles Permissions</a></li>
                                    <li><a href="<?php echo e(route('database.backups')); ?>">Backup/Restore</a></li>
                                    <li><a href="<?php echo e(route('languages')); ?>">Languages</a></li>
                                    <li><a href="<?php echo e(route('users')); ?>">Users</a></li>
                                    <li><a href="<?php echo e(route('permissions.modules')); ?>">Modules</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo e(route('patients')); ?>">Patient</a></li>
                            
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Hospital Charges</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('charges')); ?>">Charges</a></li>
                                    <li><a href="<?php echo e(route('charge_categories')); ?>">Charge Category</a></li>
                                    <li><a href="<?php echo e(route('charge_type_module')); ?>">Charge Type</a></li>
                                    <li><a href="<?php echo e(route('tax_category')); ?>">Tax
                                            Category</a></li>
                                    <li><a href="<?php echo e(route('charge_units')); ?>">Unit Type</a></li>
                                    
                                </ul>
                            </li>
                            
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Bed
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('bed-status')); ?>">Bed Status</a></li>
                                    <li><a href="<?php echo e(route('bed')); ?>">Bed</a></li>
                                    <li><a href="<?php echo e(route('bed-types.index')); ?>">Bed Type</a></li>
                                    <li><a href="<?php echo e(route('bed-groups.index')); ?>">Bed
                                            Group</a></li>
                                    <li><a href="<?php echo e(route('floors.index')); ?>">Floor</a></li>
                                    
                                </ul>
                            </li>
                            <li><a href="<?php echo e(route('letterHead')); ?>">Print Header Footer</a></li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Front
                                        Office</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('purpose')); ?>">Purpose</a></li>
                                    <li><a href="<?php echo e(route('complaint')); ?>">Complain Type</a></li>
                                    <li><a href="<?php echo e(route('sources')); ?>">Source</a></li>
                                    
                                </ul>
                            </li>
                            
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Operations
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('operations')); ?>">Operation</a></li>
                                    <li><a href="<?php echo e(route('operation-category')); ?>">Operation Category</a></li>
                                    
                                </ul>
                            </li>
                            
<<<<<<< HEAD
=======
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Pharmacy
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('medicine-category')); ?>">Medicine Category</a></li>
                                    <li><a href="<?php echo e(route('supplier')); ?>">Supplier</a></li>
                                    <li><a href="<?php echo e(route('medicine-dosage')); ?>">Medicine Dosage</a></li>
                                    <li><a href="<?php echo e(route('dosage-interval')); ?>">Dose
                                            Interval</a></li>
                                    <li><a href="<?php echo e(route('dosage-duration')); ?>">Dose Duration</a></li>
                                    <li><a href="<?php echo e(route('unit-list')); ?>">Unit
                                            </a></li>
                                    <li><a href="<?php echo e(route('company-list')); ?>">Company
                                            </a></li>
                                    <li><a href="<?php echo e(route('medicine-group')); ?>">Mediccine Group</a></li>
                                </ul>
                            </li>
>>>>>>> 53ad2ac1640d46f4ef6740dd073a2ce3bbdd7ea7
                            
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Pathology
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('pathology-category')); ?>">Pathology Category</a></li>
                                    <li><a href="<?php echo e(route('pathology-unit')); ?>">Unit</a></li>
                                    <li><a href="<?php echo e(route('pathology-parameter')); ?>">Pathology Parameter</a></li>
                                    
                                </ul>
                            </li>

                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Radiology
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('radiology-category')); ?>">Radiology Category</a></li>
                                    <li><a href="<?php echo e(route('radiology-unit')); ?>">Unit</a></li>
                                    <li><a href="<?php echo e(route('radiology-parameter')); ?>">Radiology Parameter</a></li>
                                    
                                </ul>
                            </li>
                          
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Blood
                                        Bank</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('blood-bank-products')); ?>">Products</a></li>
                                    
                                </ul>
                            </li>
                          
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Symptoms
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('symptoms-head')); ?>">Symptoms Head</a></li>
                                    <li><a href="<?php echo e(route('symptoms-type')); ?>">Symptoms Type</a></li>
                                    
                                </ul>
                            </li>
                           
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Findings
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('finding')); ?>">Finding</a></li>
                                    <li><a href="<?php echo e(route('finding-category')); ?>">Category</a></li>
                                    
                                </ul>
                            </li>
                            <li><a href="<?php echo e(route('vitals')); ?>">Vitals</a></li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Finance
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('income-head')); ?>">Income </a></li>
                                    <li><a href="<?php echo e(route('expense-head')); ?>">Expenses </a></li>
                                    
                                </ul>
                            </li>
                           
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Human
                                        Resource</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('leave-type')); ?>">Leave Type</a></li>
                                    <li><a href="<?php echo e(route('department')); ?>">Department</a></li>
                                    <li><a href="<?php echo e(route('designation')); ?>">Designation</a></li>
                                    <li><a href="<?php echo e(route('specialist')); ?>">Specialist
                                            </a></li>
                                    
                                </ul>
                            </li>
                            
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Appointment
                                        Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('slots')); ?>">Slots</a></li>
                                    <li><a href="<?php echo e(route('doctor-shift')); ?>">Doctor Shift</a></li>
                                    <li><a href="<?php echo e(route('shift')); ?>">Shift</a></li>
                                    <li><a href="<?php echo e(route('appointment-priority')); ?>">Appointment
                                            Priority</a></li>
                                    
                                </ul>
                            </li>
                           
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Inventory
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="<?php echo e(route('item-category')); ?>">Item
                                            Category</a></li>
                                    <li><a href="<?php echo e(route('item-store')); ?>">Item Store</a></li>
                                    <li><a href="<?php echo e(route('item-supplier')); ?>">Item Supplier</a></li>
                                    
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Website
                                        Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="organization-settings.html">Organization</a></li>
                                    <li><a href="localization-settings.html">Localization</a></li>
                                    <li><a href="<?php echo e(route('prefix')); ?>">Prefixes</a></li>
                                    <li><a href="seo-setup-settings.html">SEO
                                            Setup</a></li>
                                    <li><a href="<?php echo e(route('languages')); ?>">Language</a></li>
                                    <li><a href="maintenance-mode-settings.html">Maintenance
                                            Mode</a></li>
                                    <li><a href="login-and-register-settings.html">Login
                                            & Register</a></li>
                                    <li><a href="preferences-settings.html">Preferences</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-building-hospital"></i><span>Clinic
                                        Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="appointment-settings.html">Appointment</a></li>
                                    <li><a href="working-hours-settings.html">Working
                                            Hours</a></li>
                                    <li><a href="cancellation-reason-settings.html">Cancellation
                                            Reason</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-device-mobile-cog"></i><span>App
                                        Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="invoice-settings.html">Invoice
                                            Settings</a></li>
                                    <li><a href="invoice-templates-settings.html">Invoice
                                            Templates</a></li>
                                    <li><a href="signatures-settings.html">Signatures</a></li>
                                    <li><a href="custom-fields-settings.html">Custom
                                            Fields</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-device-desktop-cog"></i><span>System
                                        Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="email-settings.html">Email
                                            Settings</a></li>
                                    <li><a href="email-templates-settings.html">Email
                                            Templates</a></li>
                                    <li><a href="sms-gateways-settings.html">SMS
                                            Gateways</a></li>
                                    <li><a href="sms-templates-settings.html">SMS
                                            Templates</a></li>
                                    <li><a href="gdpr-cookies-settings.html">GDPR
                                            Cookies</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-settings-dollar"></i><span>Finance
                                        & Accounts</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="payment-methods-settings.html">Payment
                                            Methods</a></li>
                                    <li><a href="bank-accounts-settings.html">Bank
                                            Accounts</a></li>
                                    <li><a href="tax-rates-settings.html">Tax
                                            Rates</a></li>
                                    <li><a href="currencies-settings.html">Currencies</a></li>
                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-settings-2"></i><span>Other
                                        Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="sitemap-settings.html">Sitemap</a></li>
                                    <li><a href="clear-cache-settings.html">Clear
                                            Cache</a></li>
                                    <li><a href="storage-settings.html">Storage</a></li>
                                    <li><a href="cronjob-settings.html">Cronjob</a></li>
                                    <li><a href="ban-ip-address-settings.html">Ban
                                            IP Address</a></li>
                                    <li><a href="system-backup-settings.html">System
                                            Backup</a></li>
                                    <li><a href="database-backup-settings.html">Database
                                            Backup</a></li>
                                    <li><a href="system-update.html">System
                                            Update</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

<<<<<<< HEAD
<script>
// Ensure submenu expand/collapse works
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing sidebar submenu functionality...');
    
    // Get all submenu links
    const submenuLinks = document.querySelectorAll('.sidebar-menu .submenu > a');
    
    console.log('Found ' + submenuLinks.length + ' submenu items');
    
    submenuLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const parentLi = this.parentElement;
            const submenuUl = this.nextElementSibling;
            
            console.log('Clicked submenu:', this.querySelector('span').textContent.trim());
            
            // Check if already open
            if (this.classList.contains('subdrop')) {
                // Close this submenu
                this.classList.remove('subdrop');
                submenuUl.style.display = 'none';
                console.log('Closing submenu');
            } else {
                // Close all other submenus at same level
                const siblings = parentLi.parentElement.querySelectorAll('.submenu > a.subdrop');
                siblings.forEach(function(sibling) {
                    if (sibling !== link) {
                        sibling.classList.remove('subdrop');
                        sibling.nextElementSibling.style.display = 'none';
                    }
                });
                
                // Open this submenu
                this.classList.add('subdrop');
                submenuUl.style.display = 'block';
                console.log('Opening submenu');
            }
        });
    });
    
    console.log('Sidebar submenu functionality initialized!');
});
</script>

=======
>>>>>>> 53ad2ac1640d46f4ef6740dd073a2ce3bbdd7ea7
    </div>
<?php /**PATH C:\xampp\htdocs\hims\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>