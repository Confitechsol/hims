<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- Mirrored from megaone.acrothemes.com/index-medical.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 28 Aug 2025 09:28:40 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <!-- Author -->
    <meta name="author" content="Themes Industry">
    <!-- description -->
    <meta name="description"
        content="MegaOne is a highly creative, modern, visually stunning and Bootstrap responsive multipurpose studio and portfolio HTML5 template with 165+ ready home page demos.">
    <!-- keywords -->
    <meta name="keywords"
        content="Creative, modern, clean, bootstrap responsive, html5, css3, portfolio, blog, studio, templates, multipurpose, one page, corporate, start-up, studio, branding, designer, freelancer, carousel, parallax, photography, studio, masonry, grid, faq">
    <!-- Page Title -->
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Favicon -->
    @extends('layouts.admincdns')
<style>
    .settings-wrapper .card .card-body label:has(input:checked) .card{
  border: 2px solid #CB6CE6;
} 
</style>
</head>

<body>
    <div class="main-wrapper">

        <!-- Topbar Start -->
        <header class="navbar-header">
            <div class="page-container topbar-menu">
                <div class="d-flex align-items-center gap-2">

                    <!-- Logo -->
                    <a href="index.html" class="logo">

                        <!-- Logo Normal -->
                        <span class="logo-light">
                            <span class="logo-lg"><img src="assets/img/logo.png" alt="logo"></span>
                            <span class="logo-sm"><img src="assets/img/logo-small.svg" alt="small logo"></span>
                        </span>

                        <!-- Logo Dark -->
                        <span class="logo-dark">
                            <span class="logo-lg"><img src="assets/img/logo-white.svg" alt="dark logo"></span>
                        </span>
                    </a>

                    <!-- Sidebar Mobile Button -->
                    <a id="mobile_btn" class="mobile-btn" href="#sidebar">
                        <i class="ti ti-menu-deep fs-24"></i>
                    </a>

                    <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn2">
                        <i class="ti ti-arrow-right"></i>
                    </button>

                    <!-- Search -->
                    <div class="me-auto d-flex align-items-center header-search d-lg-flex d-none">
                        <!-- Search -->
                        <div class="input-icon-start position-relative me-2">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" class="form-control shadow-sm" placeholder="Search">
                            <span
                                class="input-icon-addon text-dark shadow fs-18 d-inline-flex p-0 header-search-icon"><i
                                    class="ti ti-command"></i></span>
                        </div>
                        <!-- /Search -->
                    </div>

                </div>

                <div class="d-flex align-items-center">

                    <!-- Search for Mobile -->
                    <div class="header-item d-flex d-lg-none me-2">
                        <button class="topbar-link btn btn-icon" data-bs-toggle="modal" data-bs-target="#searchModal"
                            type="button">
                            <i class="ti ti-search fs-16"></i>
                        </button>
                    </div>

                    <!-- AI Assistance -->
                    <a href="javascript:void(0);" class="btn btn-liner-gradient me-3 d-lg-flex d-none">AI
                        Assistance<i class="ti ti-chart-bubble-filled ms-1"></i></a>
                    <!-- AI Assistance -->

                    <!-- Appointment -->
                    <div class="header-item">
                        <div class="dropdown me-2">
                            <a href="new-appointment.html" class="btn topbar-link"><i
                                    class="ti ti-calendar-due"></i></a>
                        </div>
                    </div>
                    <!-- Appointment -->

                    <!-- Settings -->
                    <div class="header-item">
                        <div class="dropdown me-2">
                            <a href="{{ route('profile') }}" class="btn topbar-link"><i
                                    class="ti ti-settings-2"></i></a>
                        </div>
                    </div>
                    <!-- Settings -->

                    <!-- Light/Dark Mode Button -->
                    <div class="header-item d-none d-sm-flex me-2">
                        <button class="topbar-link btn btn-icon topbar-link" id="light-dark-mode" type="button">
                            <i class="ti ti-moon fs-16"></i>
                        </button>
                    </div>

                    <!-- Notification Dropdown -->
                    <div class="header-item">
                        <div class="dropdown me-3">

                            <button class="topbar-link btn btn-icon topbar-link dropdown-toggle drop-arrow-none"
                                data-bs-toggle="dropdown" data-bs-offset="0,24" type="button" aria-haspopup="false"
                                aria-expanded="false">
                                <i class="ti ti-bell-check fs-16 animate-ring"></i>
                                <span class="notification-badge"></span>
                            </button>

                            <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg"
                                style="min-height: 300px;">

                                <div class="p-2 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fs-16 fw-semibold">
                                                Notifications</h6>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notification Body -->
                                <div class="notification-body position-relative z-2 rounded-0" data-simplebar>

                                    <!-- Item-->
                                    <div class="dropdown-item notification-item py-3 text-wrap border-bottom"
                                        id="notification-1">
                                        <div class="d-flex">
                                            <div class="me-2 position-relative flex-shrink-0">
                                                <img src="assets/img/doctors/doctor-01.jpg"
                                                    class="avatar-md rounded-circle" alt>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-medium text-dark">Dr.
                                                    Smith</p>
                                                <p class="mb-1 text-wrap">
                                                    updated the <span class="fw-medium text-dark">surgery</span>
                                                    schedule.
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-12"><i class="ti ti-clock me-1"></i>4
                                                        min ago</span>
                                                    <div
                                                        class="notification-action d-flex align-items-center float-end gap-2">
                                                        <a href="javascript:void(0);"
                                                            class="notification-read rounded-circle bg-danger"
                                                            data-bs-toggle="tooltip" title
                                                            data-bs-original-title="Make as Read"
                                                            aria-label="Make as Read"></a>
                                                        <button class="btn rounded-circle p-0"
                                                            data-dismissible="#notification-1">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Item-->
                                    <div class="dropdown-item notification-item py-3 text-wrap border-bottom"
                                        id="notification-2">
                                        <div class="d-flex">
                                            <div class="me-2 position-relative flex-shrink-0">
                                                <img src="assets/img/doctors/doctor-06.jpg"
                                                    class="avatar-md rounded-circle" alt>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-medium text-dark">Dr.
                                                    Patel</p>
                                                <p class="mb-1 text-wrap">
                                                    completed a <span class="fw-medium text-dark">follow-up</span>
                                                    report for patient <span class="fw-medium text-dark">Emily</span>.
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-12"><i class="ti ti-clock me-1"></i>8
                                                        min ago</span>
                                                    <div
                                                        class="notification-action d-flex align-items-center float-end gap-2">
                                                        <a href="javascript:void(0);"
                                                            class="notification-read rounded-circle bg-danger"
                                                            data-bs-toggle="tooltip" title
                                                            data-bs-original-title="Make as Read"
                                                            aria-label="Make as Read"></a>
                                                        <button class="btn rounded-circle p-0"
                                                            data-dismissible="#notification-2">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Item-->
                                    <div class="dropdown-item notification-item py-3 text-wrap border-bottom"
                                        id="notification-3">
                                        <div class="d-flex">
                                            <div class="me-2 position-relative flex-shrink-0">
                                                <img src="assets/img/doctors/doctor-02.jpg"
                                                    class="avatar-md rounded-circle" alt>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-medium text-dark">Emily</p>
                                                <p class="mb-1 text-wrap">
                                                    booked an appointment
                                                    with <span class="fw-medium text-dark">Dr.
                                                        Patel</span> for
                                                    <span class="fw-medium text-dark">April
                                                        15</span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-12"><i class="ti ti-clock me-1"></i>15
                                                        min ago</span>
                                                    <div
                                                        class="notification-action d-flex align-items-center float-end gap-2">
                                                        <a href="javascript:void(0);"
                                                            class="notification-read rounded-circle bg-danger"
                                                            data-bs-toggle="tooltip" title
                                                            data-bs-original-title="Make as Read"
                                                            aria-label="Make as Read"></a>
                                                        <button class="btn rounded-circle p-0"
                                                            data-dismissible="#notification-3">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Item-->
                                    <div class="dropdown-item notification-item py-3 text-wrap" id="notification-4">
                                        <div class="d-flex">
                                            <div class="me-2 position-relative flex-shrink-0">
                                                <img src="assets/img/doctors/doctor-07.jpg"
                                                    class="avatar-md rounded-circle" alt>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 fw-medium text-dark">Amelia</p>
                                                <p class="mb-1 text-wrap">
                                                    completed the <span class="fw-medium text-dark">pre-visit</span>
                                                    health questionnaire.
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-12"><i class="ti ti-clock me-1"></i>20
                                                        min ago</span>
                                                    <div
                                                        class="notification-action d-flex align-items-center float-end gap-2">
                                                        <a href="javascript:void(0);"
                                                            class="notification-read rounded-circle bg-danger"
                                                            data-bs-toggle="tooltip" title
                                                            data-bs-original-title="Make as Read"
                                                            aria-label="Make as Read"></a>
                                                        <button class="btn rounded-circle p-0"
                                                            data-dismissible="#notification-4">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- View All-->
                                <div class="p-2 rounded-bottom border-top text-center">
                                    <a href="notifications.html"
                                        class="text-center text-decoration-underline fs-14 mb-0">
                                        View All Notifications
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="dropdown profile-dropdown d-flex align-items-center justify-content-center">
                        <a href="javascript:void(0);"
                            class="topbar-link dropdown-toggle drop-arrow-none position-relative"
                            data-bs-toggle="dropdown" data-bs-offset="0,22" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="assets/img/users/user-01.jpg" width="32" class="rounded-circle d-flex"
                                alt="user-image">
                            <span class="online text-success"><i
                                    class="ti ti-circle-filled d-flex bg-white rounded-circle border border-1 border-white"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">

                            <div class="d-flex align-items-center bg-light rounded-3 p-2 mb-2">
                                <img src="assets/img/users/user-01.jpg" class="rounded-circle" width="42"
                                    height="42" alt>
                                <div class="ms-2">
                                    <p class="fw-medium text-dark mb-0">Jimmy
                                        Anderson</p>
                                    <span class="d-block fs-13">Administrator</span>
                                </div>
                            </div>

                            <!-- Item-->
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                <i class="ti ti-user-circle me-1 align-middle"></i>
                                <span class="align-middle">Profile
                                    Settings</span>
                            </a>

                            <!-- Item-->
                            <a href="account-settings.html" class="dropdown-item">
                                <i class="ti ti-settings me-1 align-middle"></i>
                                <span class="align-middle">Account
                                    Settings</span>
                            </a>

                            <!-- item -->
                            <div
                                class="form-check form-switch form-check-reverse d-flex align-items-center justify-content-between dropdown-item mb-0">
                                <label class="form-check-label" for="notify"><i
                                        class="ti ti-bell me-1"></i>Notifications</label>
                                <input class="form-check-input me-0" type="checkbox" role="switch" id="notify">
                            </div>

                            <!-- Item-->
                            <a href="transactions.html" class="dropdown-item">
                                <i class="ti ti-transition-right me-1 align-middle"></i>
                                <span class="align-middle">Transactions</span>
                            </a>

                            <!-- Item-->
                            <div class="pt-2 mt-2 border-top">
                                <form action="{{ route('logout') }}" method="POST" class="dropdown-item text-danger"
                                    style="cursor: pointer">
                                    @csrf
                                    <button type="submit" class="btn w-100 justify-content-start p-0">
                                        <i class="ti ti-logout me-1 fs-17 align-middle text-danger fw-bold"></i>
                                        <span class="align-middle text-danger fw-bold">Log Out</span>
                                    </button>
                                </form>
                                {{-- <a href="{{ route('logout') }}" class="dropdown-item text-danger">
                                    <i class="ti ti-logout me-1 fs-17 align-middle"></i>
                                    <span class="align-middle">Log Out</span>
                                </a> --}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <!-- Search Modal -->
        <div class="modal fade" id="searchModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-transparent">
                    <div class="card shadow-none mb-0">
                        <div class="px-3 py-2 d-flex flex-row align-items-center" id="search-top">
                            <i class="ti ti-search fs-22"></i>
                            <input type="search" class="form-control border-0" placeholder="Search">
                            <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close"><i
                                    class="ti ti-x fs-22"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidenav Menu Start -->
        @extends('layouts.sidebar')
        <div class="page-wrapper">
            <!-- Sidenav Menu End -->
            @yield('content')
            <!-- Footer Start -->
            <div class="footer text-center bg-white p-2 border-top">
                <p class="text-dark mb-0">2025 &copy; <a href="javascript:void(0);"
                        class="link-primary">Cognaihealth</a>, All Rights
                    Reserved</p>
            </div>

        </div>
    </div>
</body>
<!-- JavaScript -->
<script>
    function toggleChatbot() {
        var chatbot = document.getElementById('chatbotWrapper');
        var button = document.getElementById('chatbotButton');

        chatbot.classList.toggle('active');
        button.classList.toggle('active');
    }
</script>
{{-- <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"  type="e56d8e3ed6c4bef649884303-text/javascript"></script> --}}

<!-- Plugin Js -->
{{-- <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" type="e56d8e3ed6c4bef649884303-text/javascript"></script> --}}
{{-- <script src="{{ asset('assets/plugins/simplebar/simplebar.min.js') }}" type="e56d8e3ed6c4bef649884303-text/javascript"></script> --}}
{{-- <script src="{{ asset('assets/js/moment.min.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script> --}}


</html>
