    <div class="sidebar" id="sidebar">

        <!-- Start Logo -->
        <div class="sidebar-logo">
            <div>
                <!-- Logo Normal -->
                <a href="{{ route('dashboard') }}" class="logo logo-normal">
                    <img src="{{ asset('assets/images/logo.webp') }}" alt="Logo">
                    <!-- @if (!empty($mo?->image))
<img src="{{ asset($hospitalData->image) }}" alt="Logo">
@else
<img src="{{ asset('assets/images/logo.webp') }}" alt="img">
@endif -->
                </a>

                <!-- Logo Small -->
                <a href="{{ route('dashboard') }}" class="logo-small">
                    <!-- <img src="assets/img/logo-small.svg" alt="Logo"> -->
                    @if (!empty($hospitalData?->image))
                        <img src="{{ asset($hospitalData->image) }}" alt="Logo">
                    @else
                        <img src="{{ asset('assets/images/logo.webp') }}" alt="img">
                    @endif
                </a>

                <!-- Logo Dark -->
                <a href="{{ route('dashboard') }}" class="dark-logo">
                    <!-- <img src="assets/img/logo-white.svg" alt="Logo"> -->
                    <img src="{{ asset('assets/images/logo.webp') }}" alt="Logo">
                    <!-- <h2>LOGO</h2> -->
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
                                <!-- <span class="avatar rounded-circle flex-shrink-0 p-2"></span> -->
                                <div class="ms-2">
                                    @if (!empty($hospitalData?->image))
                                        <img src="{{ asset($hospitalData->image) }}" alt="Logo">
                                    @else
                                        <img src="{{ asset('assets/images/logo.webp') }}" alt="img">
                                    @endif

                                    <!-- <h6 class="fs-14 fw-semibold mb-0">Trustcare
                                        Clinic</h6>
                                    <p class="fs-13 mb-0">Lasvegas</p> -->
                                </div>
                            </div>
                            <i class="ti ti-arrows-transfer-up"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg">
                        <div class="p-2">
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="{{ asset('assets/img/icons/clinic-01.svg') }}"
                                            alt></span>
                                    <span class="fw-semibold text-dark">CureWell
                                        Medical Hub<small class="d-block text-muted fw-normal fs-13">Ohio</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="{{ asset('assets/img/icons/clinic-02.svg') }}"
                                            alt></span>
                                    <span class="fw-semibold text-dark">Trustcare
                                        Clinic<small class="d-block text-muted fw-normal fs-13">Lasvegas</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="{{ asset('assets/img/icons/clinic-03.svg') }}"
                                            alt></span>
                                    <span class="fw-semibold text-dark">NovaCare
                                        Medical<small
                                            class="d-block text-muted fw-normal fs-13">Washington</small></span>
                                </span>
                                <input class="form-check-input m-0 me-2" type="checkbox">
                            </label>
                            <label class="dropdown-item d-flex align-items-center justify-content-between p-1">
                                <span class="d-flex align-items-center">
                                    <span class="me-2"><img src="{{ asset('assets/img/icons/clinic-04.svg') }}"
                                            alt></span>
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
                                    <li><a href="{{ route('dashboard') }}"
                                            class=" {{ request()->routeIs('dashboard') ? 'active' : '' }}">Admin
                                            Dashboard</a></li>
                                    <!--<li><a href="doctor-dashboard.html">Doctor
                                            Dashboard</a></li>
                                    <li><a href="patient-dashboard.html">Patient
                                            Dashboard</a></li>-->
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('billing') }}"
                                    class="{{ request()->routeIs('billing') ? 'active' : '' }}">
                                    <!--<i class="fa fa-file-invoice"></i>  optional icon -->
                                    <span>Billing Section</span>
                                    <!--<span class="menu-arrow"></span>-->
                                </a>
                                <!--<ul>
                                    <li><a href="{{ route('billing') }}">Billing
                                            </a></li>
                                    <li><a href="doctor-dashboard.html">Appointment
                                            </a></li>
                                    <li><a href="{{ route('opd.billing') }}">OPD
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
                                    <li><a href="{{ route('birth') }}"
                                            class="{{ request()->routeIs('birth') ? 'active' : '' }}">Birth Record
                                        </a></li>
                                    <li><a href="{{ route('death') }} "
                                            class="{{ request()->routeIs('death') ? 'active' : '' }}">Death Record
                                        </a></li>

                                </ul>
                            </li>
                            <!-- <li class="submenu">
                                <a href="{{ route('certificate') }}" class="active subdrop">
                                    <i class="ti ti-layout-dashboard"></i><span>Certificate</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ route('certificate') }}" class="{{ request()->routeIs('certificate') ? 'active' : '' }}">Certificate
                                            </a></li>
                                    <li><a href="{{ route('generate_patient_id') }}" class="{{ request()->routeIs('generate_patient_id') ? 'active' : '' }}">Patient ID Card
                                            </a></li>
                                    <li><a href="{{ route('generate_patient_id') }}" class="{{ request()->routeIs('generate_patient_id') ? 'active' : '' }}">Staff ID Card
                                            </a></li>
                                </ul>
                            </li> -->

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
                            </li>-->
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-user-heart"></i><span>OPD & IPD </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>

                                    <li><a href="{{ route('opd') }}"
                                            class="{{ request()->routeIs('opd') ? 'active' : '' }}">OPD Patient
                                            Details</a></li>
                                    <li><a href="{{ route('ipd') }}"
                                            class="{{ request()->routeIs('ipd') ? 'active' : '' }}">IPD Patient
                                            Details</a></li>


                                </ul>
                            </li>
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ route('appointment-details') }}"
                                            class="{{ request()->routeIs('appointment-details') ? 'active' : '' }}">Appointment
                                            Details</a></li>
                                    <!--<li><a href="new-appointment.html">New
                                            Appointment</a></li>
                                    <li><a href="appointment-calendar.html">Calendar</a></li> -->

                            </li>
                        </ul>
                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-map-pin"></i><span>Pharmacy</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul style="display: none;">
                            <li><a href="{{ route('setup.medicine-category') }}"
                                    class="{{ request()->routeIs('setup.medicine-category') ? 'active' : '' }}">Medicine
                                    Category</a></li>
                            <li><a href="{{ route('setup.medicine-supplier') }}"
                                    class="{{ request()->routeIs('setup.medicine-supplier') ? 'active' : '' }}">Medicine
                                    Supplier</a></li>
                            <li><a href="{{ route('setup.medicine-dosage') }}"
                                    class="{{ request()->routeIs('setup.medicine-dosage') ? 'active' : '' }}">Medicine
                                    Dosage</a></li>
                            <li><a href="{{ route('setup.medicine-group') }}"
                                    class="{{ request()->routeIs('setup.medicine-group') ? 'active' : '' }}">Medicine
                                    Group</a></li>
                            <li><a href="{{ route('setup.medicine-unit') }}"
                                    class="{{ request()->routeIs('setup.medicine-unit') ? 'active' : '' }}">Medicine
                                    Unit</a></li>
                            <li><a href="{{ route('setup.dose-duration') }}"
                                    class="{{ request()->routeIs('setup.dose-duration') ? 'active' : '' }}">Dose
                                    Duration</a></li>
                            <li><a href="{{ route('setup.dose-interval') }}"
                                    class="{{ request()->routeIs('setup.dose-interval') ? 'active' : '' }}">Dose
                                    Interval</a></li>
                            <li><a href="{{ route('setup.medicine-company') }}"
                                    class="{{ request()->routeIs('setup.medicine-company') ? 'active' : '' }}">Medicine
                                    Company</a></li>
                            <li><a href="{{ route('pharmacy.billing.index') }}"
                                    class="{{ request()->routeIs('pharmacy.billing.index') ? 'active' : '' }}">Pharmacy
                                    Billing</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-map-pin"></i><span>Pathology</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('pathology-category') }}"
                                    class="{{ request()->routeIs('pathology-category') ? 'active' : '' }}">Pathology
                                    Category</a></li>
                            <li><a href="{{ route('pathology-unit') }}"
                                    class="{{ request()->routeIs('pathology-unit') ? 'active' : '' }}">Pathology
                                    Unit</a></li>
                            <li><a href="{{ route('pathology-parameter') }}"
                                    class="{{ request()->routeIs('pathology-parameter') ? 'active' : '' }}">Pathology
                                    Parameter</a></li>
                            <li><a href="{{ route('pathology.test.index') }}"
                                    class="{{ request()->routeIs('pathology.test.index') ? 'active' : '' }}">Pathology
                                    Test</a></li>
                            <li><a href="{{ route('pathology.billing.index') }}"
                                    class="{{ request()->routeIs('pathology.billing.index') ? 'active' : '' }}">Pathology
                                    Billing</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-map-pin"></i><span>Radiology</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('radiology-category') }}"
                                    class="{{ request()->routeIs('radiology-category') ? 'active' : '' }}">Radiology
                                    Category</a></li>
                            <li><a href="{{ route('radiology-unit') }}"
                                    class="{{ request()->routeIs('radiology-unit') ? 'active' : '' }}">Radiology
                                    Unit</a></li>
                            <li><a href="{{ route('radiology-parameter') }}"
                                    class="{{ request()->routeIs('radiology-parameter') ? 'active' : '' }}">Radiology
                                    Parameter</a></li>
                            <li><a href="{{ route('radiology.test.index') }}"
                                    class="{{ request()->routeIs('radiology.test.index') ? 'active' : '' }}">Radiology
                                    Test</a></li>
                            <li><a href="{{ route('radiology.billing.index') }}"
                                    class="{{ request()->routeIs('radiology.billing.index') ? 'active' : '' }}">Radiology
                                    Billing</a></li>
                        </ul>
                    </li>
                    <!-- <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Blood Bank</span>
                                </a>
                            </li> -->
                    <!-- <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Ambulance Call</span>
                                </a>
                            </li> -->
                    <li>
                        <a href="{{ route('doctor-visit.create') }}"
                            class="{{ request()->routeIs('doctor-visit.create') ? 'active' : '' }}">
                            <i class="ti ti-map-pin"></i><span>Doctor Visit</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ambulanceCall.index') }}"
                            class="{{ request()->routeIs('ambulanceCall.index') ? 'active' : '' }}">
                            <i class="ti ti-map-pin"></i><span>Ambulance Call</span>
                        </a>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);" class="active subdrop">
                            <i class="ti ti-layout-dashboard"></i><span>Duty Roster</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('dutyroster') }}">Duty Roster List
                                </a></li>
                            <li><a href="{{ route('dutyroster.Shift') }}"
                                    class="{{ request()->routeIs('dutyroster.Shift') ? 'active' : '' }}">Duty Shift
                                    List
                                </a></li>
                            <li><a href="{{ route('dutyroster.staff') }}"
                                    class="{{ request()->routeIs('dutyroster.staff') ? 'active' : '' }}">Staff Roster
                                    Details
                                </a></li>
                            <li><a href="{{ route('dutyroster.doctor') }}"
                                    class="{{ request()->routeIs('dutyroster.doctor') ? 'active' : '' }}">Doctors
                                    Roster Details
                                </a></li>

                        </ul>
                    </li>
                    <!-- <li>
                                <a href="#">
                                    <i class="ti ti-map-pin"></i><span>Annual Calender</span>
                                </a>
                            </li> -->
                    <li>
                        <a href="{{ route('tpamanagement') }}"
                            class="{{ request()->routeIs('tpamanagement') ? 'active' : '' }}">
                            <i class="ti ti-map-pin"></i><span>TPA Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('inventory-details') }}"
                            class="{{ request()->routeIs('inventory-details') ? 'active' : '' }}">
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
                                <li><a href="{{ route('income') }}"
                                        class="{{ request()->routeIs('income') ? 'active' : '' }}">Income
                                    </a></li>
                                <li><a href="{{ route('expense') }}"
                                        class="{{ request()->routeIs('expense') ? 'active' : '' }}">Expenses
                                    </a></li>
                                <li><a href="{{ route('money-receipt.index') }}"
                                        class="{{ request()->routeIs('money-receipt.*') ? 'active' : '' }}">Money/Refund
                                        Receipt
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
                                    <a href="{{ route('staffs.index') }}"
                                        class="{{ request()->routeIs('staffs.index') ? 'active' : '' }}">
                                        <i class="ti ti-users-group"></i><span>Staffs</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('doctors.index') }}"
                                        class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">
                                        <i class="ti ti-users-group"></i><span>Doctors</span>
                                    </a>
                                </li>
                                <!-- <li>
                                <a href="hrm-departments.html">
                                    <i class="ti ti-building-bank"></i><span>Departments</span>
                                </a>
                            </li> -->
                                <!-- <li>
                                <a href="designation.html">
                                    <i class="ti ti-user-cog"></i><span>Designation</span>
                                </a>
                            </li> -->
                                <!-- <li>
                                <a href="attendance.html">
                                    <i class="ti ti-user-check"></i><span>Attendance</span>
                                </a>
                            </li> -->
                                <!-- <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-users-minus"></i><span>Leaves</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="leaves.html">Leaves</a></li>
                                    <li><a href="leave-type.html">Leave
                                            Type</a></li>
                                </ul>
                            </li> -->
                                <!-- <li>
                                <a href="holidays.html">
                                    <i class="ti ti-home-exclamation"></i><span>Holidays</span>
                                </a>
                            </li> -->
                                <!-- <li>
                                <a href="payroll.html">
                                    <i class="ti ti-coin"></i><span>Payroll</span>
                                </a>
                            </li> -->
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-report"></i><span>Reports</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('inventory-reports') }}"
                                        class="{{ request()->routeIs('inventory-reports') ? 'active' : '' }}">Inventory
                                        Reports</i>
                                <li><a href="{{ route('finance') }}"
                                        class="{{ request()->routeIs('finance') ? 'active' : '' }}">Finance Report
                                    </a></li>
                                <li class="submenu">
                                    <a href="javascript:void(0);"
                                        class="{{ request()->routeIs('reports.money-receipt-register*') || request()->routeIs('reports.cash-register*') || request()->routeIs('reports.expense-register*') || request()->routeIs('reports.ipd-final-bill-register*') || request()->routeIs('reports.daily-collection*') ? 'active subdrop' : '' }}">
                                        <i class="ti ti-report-money"></i><span>Accounting Report</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul>
                                        <li><a href="{{ route('reports.money-receipt-register') }}"
                                                class="{{ request()->routeIs('reports.money-receipt-register') ? 'active' : '' }}">Money
                                                Receipt Register</a></li>
                                        <li><a href="{{ route('reports.cash-register') }}"
                                                class="{{ request()->routeIs('reports.cash-register') ? 'active' : '' }}">Cash
                                                Register</a></li>
                                        <li><a href="{{ route('reports.expense-register') }}"
                                                class="{{ request()->routeIs('reports.expense-register') ? 'active' : '' }}">Expense
                                                Register</a></li>
                                        <li><a href="{{ route('reports.ipd-final-bill-register') }}"
                                                class="{{ request()->routeIs('reports.ipd-final-bill-register') ? 'active' : '' }}">IPD
                                                Final Bill Register</a></li>
                                        <li><a href="{{ route('reports.daily-collection') }}"
                                                class="{{ request()->routeIs('reports.daily-collection') ? 'active' : '' }}">Daily
                                                Collection Report</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('opd.reports') }}"
                                        class="{{ request()->routeIs('opd.reports') ? 'active' : '' }}">OPD
                                        Report</a></li>
                                <li><a href="{{ route('ipd.reports') }}"
                                        class="{{ request()->routeIs('death') ? 'active' : '' }}">IPD
                                        Report</a></li>
                                <li><a href="{{ route('patient-reports-index') }}"
                                        class="{{ request()->routeIs('patient-reports-index') ? 'active' : '' }}">Patient
                                        Report</a></li>
                                <li><a href="{{ route('hospital-reports-index') }}"
                                        class="{{ request()->routeIs('hospital-reports-index') ? 'active' : '' }}">Hospital
                                        Report</a></li>
                                <li><a href="{{ route('doctor-reports-index') }}"
                                        class="{{ request()->routeIs('doctor-reports-index') ? 'active' : '' }}">Doctor
                                        Report</a></li>
                                <!-- <li><a href="{{ route('finance') }}"  class="{{ request()->routeIs('death') ? 'active' : '' }}">Appointment
                                            Report</a></li> -->


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
                                <!-- <li><a href="{{ route('profile') }}">Profile</a></li> -->
                                <!--<li><a href="security-settings.html">Security</a></li>
                                    //<li><a href="notifications-settings.html">Notifications</a></li>
                                    <li><a href="integrations-settings.html">Integrations</a></li>-->
                                <li><a href="{{ route('profile') }}"
                                        class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">General
                                        Settings</a></li>
                                <li><a href="{{ route('email-setting') }}"
                                        class="{{ request()->routeIs('email-setting') ? 'active' : '' }}">Email
                                        Settings</a></li>
                                <li><a href="{{ route('prefix') }}"
                                        class="{{ request()->routeIs('prefix') ? 'active' : '' }}">Prefix
                                        Settings</a></li>
                                <li><a href="{{ route('gst_master.index') }}"
                                        class="{{ request()->routeIs('gst_master.*') ? 'active' : '' }}">GST
                                        Master</a></li>
                                <li><a href="{{ route('roles') }}"
                                        class="{{ request()->routeIs('roles') ? 'active' : '' }}">Roles
                                        Permissions</a></li>
                                <li><a href="{{ route('database.backups') }}"
                                        class="{{ request()->routeIs('database.backups') ? 'active' : '' }}">Backup/Restore</a>
                                </li>
                                <li><a href="{{ route('languages') }}"
                                        class="{{ request()->routeIs('languages') ? 'active' : '' }}">Languages</a>
                                </li>
                                <li><a href="{{ route('users') }}"
                                        class="{{ request()->routeIs('users') ? 'active' : '' }}">Users</a></li>
                                <li><a href="{{ route('permissions.modules') }}"
                                        class="{{ request()->routeIs('permissions.modules') ? 'active' : '' }}">Modules</a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="{{ route('patients') }}"><i class="ti ti-user-cog"></i><span> Patient
                                </span></a></li>

                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Hospital Charges</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('charges') }}"
                                        class="{{ request()->routeIs('charges') ? 'active' : '' }}">Charges</a></li>
                                <li><a href="{{ route('charge_categories') }}"
                                        class="{{ request()->routeIs('charge_categories') ? 'active' : '' }}">Charge
                                        Category</a></li>
                                <li><a href="{{ route('charge_type_module') }}"
                                        class="{{ request()->routeIs('charge_type_module') ? 'active' : '' }}">Charge
                                        Type</a></li>
                                <li><a href="{{ route('tax_category') }}"
                                        class="{{ request()->routeIs('tax_category') ? 'active' : '' }}">Tax
                                        Category</a></li>
                                <li><a href="{{ route('charge_units') }}"
                                        class="{{ request()->routeIs('charge_units') ? 'active' : '' }}">Unit
                                        Type</a></li>

                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('packages.index') }}"
                                class="{{ request()->routeIs('packages.index') ? 'active' : '' }}">
                                <i class="ti ti-package"></i><span>Package Master</span>
                            </a>
                        </li>

                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Bed
                                </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('bed-status') }}"
                                        class="{{ request()->routeIs('bed-status') ? 'active' : '' }}">Bed Status</a>
                                </li>
                                <li><a href="{{ route('bed') }}"
                                        class="{{ request()->routeIs('bed') ? 'active' : '' }}">Bed</a></li>
                                <li><a href="{{ route('bed-types.index') }}"
                                        class="{{ request()->routeIs('bed-types.index') ? 'active' : '' }}">Bed
                                        Type</a></li>
                                <li><a href="{{ route('bed-groups.index') }}"
                                        class="{{ request()->routeIs('bed-groups.index') ? 'active' : '' }}">Bed
                                        Group</a></li>
                                <li><a href="{{ route('floors.index') }}"
                                        class="{{ request()->routeIs('floors.index') ? 'active' : '' }}">Floor</a>
                                </li>

                            </ul>
                        </li>
                        <li><a href="{{ route('letterHead') }}"
                                class="{{ request()->routeIs('letterHead') ? 'active' : '' }}">
                                <i class="ti ti-world-cog"></i>
                                <span>Print Header Footer</span>
                            </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Front
                                    Office</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('purpose') }}"
                                        class="{{ request()->routeIs('purpose') ? 'active' : '' }}">Purpose</a></li>
                                <li><a href="{{ route('complaint') }}"
                                        class="{{ request()->routeIs('complaint') ? 'active' : '' }}">Complain
                                        Type</a></li>
                                <li><a href="{{ route('sources') }}"
                                        class="{{ request()->routeIs('sources') ? 'active' : '' }}">Source</a></li>

                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Operations
                                </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('operations') }}"
                                        class="{{ request()->routeIs('operations') ? 'active' : '' }}">Operation</a>
                                </li>
                                <li><a href="{{ route('operation-category') }}"
                                        class="{{ request()->routeIs('operation-category') ? 'active' : '' }}">Operation
                                        Category</a></li>

                            </ul>
                        </li>


                        <!-- <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Pathology
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ route('pathology-category') }}">Pathology Category</a></li>
                                    <li><a href="{{ route('pathology-unit') }}">Unit</a></li>
                                    <li><a href="{{ route('pathology-parameter') }}">Pathology Parameter</a></li>

                                </ul>
                            </li> -->

                        <!-- <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Radiology
                                        </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ route('radiology-category') }}">Radiology Category</a></li>
                                    <li><a href="{{ route('radiology-unit') }}">Unit</a></li>
                                    <li><a href="{{ route('radiology-parameter') }}">Radiology Parameter</a></li>

                                </ul>
                            </li> -->

                        <!-- <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Blood
                                        Bank</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="{{ route('blood-bank-products') }}">Products</a></li>

                                </ul>
                            </li> -->

                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Symptoms
                                </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('symptoms-head') }}"
                                        class="{{ request()->routeIs('symptoms-head') ? 'active' : '' }}">Symptoms
                                        Head</a></li>
                                <li><a href="{{ route('symptoms-type') }}"
                                        class="{{ request()->routeIs('symptoms-type') ? 'active' : '' }}">Symptoms
                                        Type</a></li>

                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Findings
                                </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('finding') }}"
                                        class="{{ request()->routeIs('finding') ? 'active' : '' }}">Finding</a></li>
                                <li><a href="{{ route('finding-category') }}"
                                        class="{{ request()->routeIs('finding-category') ? 'active' : '' }}">Category</a>
                                </li>

                            </ul>
                        </li>
                        <li><a href="{{ route('medicine-master') }}"
                                class="{{ request()->routeIs('medicine-master') ? 'active' : '' }}"><i
                                    class="bx bx-medical-kit"></i><span>Medicine Master</span></a></li>
                        <li><a href="{{ route('vitals') }}"
                                class="{{ request()->routeIs('vitals') ? 'active' : '' }}"><i
                                    class="ti ti-world-cog"></i><span>Vitals</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Finance
                                </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('income-head') }}"
                                        class="{{ request()->routeIs('income-head') ? 'active' : '' }}">Income </a>
                                </li>
                                <li><a href="{{ route('expense-head') }}"
                                        class="{{ request()->routeIs('expense-head') ? 'active' : '' }}">Expenses
                                    </a></li>

                            </ul>
                        </li>

                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-world-cog"></i><span>Human
                                    Resource</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('leave-type') }}"
                                        class="{{ request()->routeIs('leave-type') ? 'active' : '' }}">Leave Type</a>
                                </li>
                                <li><a href="{{ route('department') }}"
                                        class="{{ request()->routeIs('department') ? 'active' : '' }}">Department</a>
                                </li>
                                <li><a href="{{ route('designation') }}"
                                        class="{{ request()->routeIs('designation') ? 'active' : '' }}">Designation</a>
                                </li>
                                <li><a href="{{ route('specialist') }}"
                                        class="{{ request()->routeIs('specialist') ? 'active' : '' }}">Specialist
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
                                <li><a href="{{ route('slots') }}"
                                        class="{{ request()->routeIs('slots') ? 'active' : '' }}">Slots</a></li>
                                <li><a href="{{ route('doctor-shift') }}"
                                        class="{{ request()->routeIs('doctor-shift') ? 'active' : '' }}">Doctor
                                        Shift</a></li>
                                <li><a href="{{ route('shift') }}"
                                        class="{{ request()->routeIs('shift') ? 'active' : '' }}">Shift</a></li>
                                <li><a href="{{ route('appointment-priority') }}"
                                        class="{{ request()->routeIs('appointment-priority') ? 'active' : '' }}">Appointment
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
                                <li><a href="{{ route('item-category') }}">Item
                                        Category</a></li>
                                <li><a href="{{ route('item-store') }}">Item Store</a></li>
                                <li><a href="{{ route('item-supplier') }}">Item Supplier</a></li>

                            </ul>
                        </li>
                        <!-- <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-world-cog"></i><span>Website
                                        Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li><a href="organization-settings.html">Organization</a></li>
                                    <li><a href="localization-settings.html">Localization</a></li>
                                    <li><a href="{{ route('prefix') }}">Prefixes</a></li>
                                    <li><a href="seo-setup-settings.html">SEO
                                            Setup</a></li>
                                    <li><a href="{{ route('languages') }}">Language</a></li>
                                    <li><a href="maintenance-mode-settings.html">Maintenance
                                            Mode</a></li>
                                    <li><a href="login-and-register-settings.html">Login
                                            & Register</a></li>
                                    <li><a href="preferences-settings.html">Preferences</a></li>
                                </ul>
                            </li> -->
                        <!-- <li class="submenu">
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
                            </li> -->
                        <!-- <li class="submenu">
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
                            </li> -->
                        <!-- <li class="submenu">
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
                            </li> -->
                    </ul>
                </li>
                </ul>
            </div>
        </div>



    </div>
