<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<!-- Mirrored from megaone.acrothemes.com/index-medical.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 28 Aug 2025 09:28:40 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

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
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- Styles -->

    <!-- style css -->
    <link rel="stylesheet" href="assets/css/custom.css">

    <!-- Page Title -->
    <title><?php echo e(config('app.name', 'HIMS')); ?></title>

    <?php echo $__env->make('layouts.admincdns', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>
        .settings-wrapper .card .card-body label:has(input:checked) .card {
            border: 2px solid #CB6CE6;
        }

        /*=============================
           loader start
 ==============================*/
        .loader {
            position: fixed;
            width: 100%;
            overflow: hidden;
            height: 100vh;
            z-index: 9999;
            background: white
        }

        .indicator {
            position: fixed;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%) scale(4);
            -ms-transform: translate(-50%, -50%) scale(4);
            transform: translate(-50%, -50%) scale(4);
        }

        .indicator svg polyline {
            fill: none;
            stroke-width: 0.5;
            -webkit-transform: scale(2);
            -ms-transform: scale(2);
            transform: scale(2);
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .indicator svg polyline#back {
            stroke: #cc6ce679;
        }

        .indicator svg polyline#front {
            stroke: #750096;
            stroke-dasharray: 12, 36;
            stroke-dashoffset: 48;
            -webkit-animation: dash 1s linear infinite;
            animation: dash 1s linear infinite;
        }

        .cta {
            position: fixed;
            bottom: 20px;
            right: 30px;
            color: #222;
            font-weight: bold;
        }

        @-webkit-keyframes dash {
            62.5% {
                opacity: 0;
            }

            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes dash {
            62.5% {
                opacity: 0;
            }

            to {
                stroke-dashoffset: 0;
            }
        }



        /*=============================
           loader ends
 ==============================*/

        /* Floating Button */
        .chatbot-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 2;
            background-image: url("<?php echo e(asset('/assets/images/bot.png')); ?>");
            background-size: 50%;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #ecbff8;
            transition: background-color 0.3s ease;
        }

        .chatbot-button:hover {
            background-color: #f4dffa;
        }

        .chatbot-button:focus {
            background-color: #ecbff8;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* When active, change icon */
        .chatbot-button.active {
            background-image: url("<?php echo e(asset('/assets/images/cross.png')); ?>");
            /* Example: close icon */
        }

        /* Chatbot Popup */
        .chatbot-iframe-wrapper {
            position: fixed;
            bottom: 120px;
            right: 20px;
            width: 460px;
            height: 600px;
            background: transparent;
            display: flex;
            transform: translateY(100%);
            opacity: 0;
            transition: transform 1s ease, opacity 1s ease;
            z-index: 9999;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            overflow: hidden;
            pointer-events: none;
        }

        .chatbot-iframe-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent;
        }

        .chatbot-iframe-wrapper.active {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .chatbot-iframe-wrapper::-webkit-scrollbar,
        .chatbot-iframe-wrapper iframe::-webkit-scrollbar {
            display: none !important;
        }

        @media screen and (max-width: 480px) {
            .chatbot-button {
                width: 50px;
                height: 50px;
            }

            .chatbot-iframe-wrapper {
                width: 90vw;
            }
        }
    </style>
    <style>
        /* Global override: ensure Select2 dropdown search is visible */
        .select2-container .select2-search--dropdown {
            display: block !important;
        }

        .select2-container .select2-search__field {
            display: block !important;
        }
    </style>
</head>

<body>

    <!-- loader start-->
    <div class="loader">
        <div class="indicator">
            <svg width="30px" height="24px">
                <polyline id="back" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                <polyline id="front" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
            </svg>
        </div>
    </div>
    <!-- loader ends-->

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

                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('hrms.switch')); ?>" class="btn btn-primary me-3 d-lg-flex d-none">
                            Move to HR Portal
                            <i class="ti ti-external-link ms-1"></i>
                        </a>
                    <?php endif; ?>

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
                            <a href="<?php echo e(route('profile')); ?>" class="btn topbar-link"><i
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
                            <img src="<?php echo e(asset('assets/img/users/user-01.jpg')); ?>" width="32"
                                class="rounded-circle d-flex" alt="user-image">
                            <span class="online text-success"><i
                                    class="ti ti-circle-filled d-flex bg-white rounded-circle border border-1 border-white"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">

                            <div class="d-flex align-items-center bg-light rounded-3 p-2 mb-2">
                                <img src="<?php echo e(asset('assets/img/users/user-01.jpg')); ?>" class="rounded-circle"
                                    width="42" height="42" alt>
                                <div class="ms-2">
                                    <p class="fw-medium text-dark mb-0">Jimmy
                                        Anderson</p>
                                    <span class="d-block fs-13">Administrator</span>
                                </div>
                            </div>

                            <!-- Item-->
                            <a href="<?php echo e(route('profile')); ?>" class="dropdown-item">
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
                                <form action="<?php echo e(route('logout')); ?>" method="POST" class="dropdown-item text-danger"
                                    style="cursor: pointer">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn w-100 justify-content-start p-0">
                                        <i class="ti ti-logout me-1 fs-17 align-middle text-danger fw-bold"></i>
                                        <span class="align-middle text-danger fw-bold">Log Out</span>
                                    </button>
                                </form>
                                
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
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="page-wrapper">
            <!-- Sidenav Menu End -->
            <?php echo $__env->yieldContent('content'); ?>
            <!-- Footer Start -->
            <div class="footer text-center bg-white p-2 border-top">
                <button id="chatbotButton" class="chatbot-button" onclick="toggleChatbot()"></button>
                <div id="chatbotWrapper" class="chatbot-iframe-wrapper">
                    <iframe src="https://hims-chatbot.vercel.app/" allow="microphone; clipboard-write"
                        sandbox="allow-scripts allow-same-origin allow-forms allow-modals allow-popups"
                        title="Confitech Chatbot"></iframe>
                </div>
                <p class="text-dark mb-0">2025 &copy; <a href="javascript:void(0);"
                        class="link-primary">Cognaihealth</a>, All Rights
                    Reserved</p>
            </div>

        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/printjs/1.6.0/print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <!-- Print.js for printing functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
    <?php if (! (request()->is('beds', 'bed-status'))): ?>
        <script src="<?php echo e(asset('assets/js/custom.js')); ?>"></script>
    <?php endif; ?>
    <!-- JavaScript -->
    <script>
        function toggleChatbot() {
            var chatbot = document.getElementById('chatbotWrapper');
            var button = document.getElementById('chatbotButton');

            chatbot.classList.toggle('active');
            button.classList.toggle('active');
        }

        // Global SweetAlert Delete Confirmation Function
        function confirmDelete(formId, title = 'Are you sure?', text = 'You won\'t be able to revert this!') {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#750096',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                buttonsStyling: true,
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-popup-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    document.getElementById(formId).submit();
                }
            });
            return false; // Prevent default action
        }

        // SweetAlert for form onsubmit (returns promise-based confirmation)
        function confirmDeleteForm(event, title = 'Are you sure?', text = 'You won\'t be able to revert this!') {
            event.preventDefault(); // Stop form submission

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#750096',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                buttonsStyling: true,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    event.target.submit();
                }
            });

            return false;
        }
    </script>
    <!-- Resilient global Select2 initializer: waits for Select2 then initializes all selects -->
    <script>
        (function() {
            function tryInitSelect2() {
                if (typeof window.jQuery === 'undefined') return false;
                var $ = window.jQuery;
                if (typeof $.fn.select2 === 'undefined') return false;

                function initSelect($el) {
                    if ($el.data('select2-inited')) return;

                    // skip selects that are hidden (like inside a hidden modal)
                    if (!$el.is(':visible')) return;

                    //skip which are inside edit modal
                    if ($el.closest('#edit_modal').length > 0) return;

                    var dp = $el.closest('.modal').find('.modal-content').first();
                    if (!dp || dp.length === 0) dp = $(document.body);
                    try {
                        $el.select2({
                            width: '100%',
                            dropdownParent: dp,
                            minimumResultsForSearch: 0
                        });
                        $el.data('select2-inited', true);
                    } catch (e) {
                        // ignore init errors
                    }
                }

                $('select.form-select').each(function() {
                    initSelect($(this));
                });

               

                // Initialize selects that appear inside modals when they open
                $('.modal').off('shown.bs.modal.select2init').on('shown.bs.modal.select2init', function() {
                    var $modal = $(this);
                    if ($modal.attr('id') === 'edit_modal') return;
                    $modal.find('select.form-select').each(function() {
                        initSelect($(this));
                    });
                });

                return true;
            }

            var attempts = 0;
            var interval = setInterval(function() {
                if (tryInitSelect2() || attempts++ > 50) {
                    clearInterval(interval);
                }
            }, 150);
        })();
    </script>
</body>

</html>
<?php /**PATH C:\xampp82\htdocs\hims\resources\views/layouts/adminLayout.blade.php ENDPATH**/ ?>