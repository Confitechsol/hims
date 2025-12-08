@extends('layouts.main')

@section('content')

    <body data-spy="scroll" data-target=".navbar" data-offset="90" class="position-relative">


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


        <!--Header Start-->
        <header class="cursor-light">

            <!--Navigation-->
            <nav class="navbar navbar-top-default nav-radius navbar-expand-lg">
                <div class="container-fluid">
                    <a href="#home" title="Logo" class="logo scroll">
                        <!--Logo Default-->
                        <!-- <img src="medical/img/logo.png" alt="logo" class="logo-dark default"> -->
                        <img src="assets/img/logo.png" alt="logo" class="logo-dark default">
                        <!-- <h2>LOGO</h2> -->
                    </a>

                    <!--Nav Links-->
                    <div class="collapse navbar-collapse">
                        <div class="navbar-nav mx-auto">
                            <a class="nav-link" href="{{ route('dashboard') }}">Home</a>
                            <a class="nav-link scroll" href="#whymegaone">Why Cognaihealth</a>
                            <a href="{{ route('doctors') }}" class="nav-link">Doctors</a>
                            <a href="{{ route('appointments') }}" class="nav-link active">Appointment</a>
                            <a class="nav-link scroll" href="#pateintgallery">Pateint Gallery</a>
                            <a class="nav-link scroll" href="#ourblogs">Our Blogs</a>
                            <a class="nav-link scroll" href="#contactus">Contact us</a>
                            <a class="nav-link active bg-info text-white" href="{{ route('userLogin') }}">Login</a>
                        </div>
                        <div> <span class="open_search"><i class="fas fa-search"></i> </span></div>

                        <div class="search_block">
                            <div class="search_box animated wow fadeInUp">
                                <div class="inner">
                                    <input type="text" name="search" id="search" class="search_input"
                                        autocomplete="off" placeholder="Enter Your Keywords.." />

                                    <button class="search_icon glyphicon glyphicon-search"><i class="fas fa-search"></i>
                                    </button>

                                </div>
                            </div>
                            <div class="search-overlay"></div>

                        </div>

                        <!-- search input-->
                    </div>

                </div>

                <!--Side Menu Button-->
                <a href="javascript:void(0)" class="parallax-btn sidemenu_btn" id="sidemenu_toggle">
                    <div class="animated-wrap sidemenu_btn_inner">
                        <div class="animated-element">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </a>

            </nav>

            <!--Side Nav-->
            <div class="side-menu">
                <div class="inner-wrapper">
                    <span class="btn-close link" id="btn_sideNavClose"></span>
                    <nav class="side-nav w-100">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link  scroll" href="{{ route('dashboard') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  scroll" href="#whymegaone">Why MegaOne</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('doctors') }}" class="nav-link">Doctors</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('appointments') }}" class="nav-link active">Appointment</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  scroll" href="#pateintgallery">Pateint Gallery</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  scroll" href="#ourblogs">Our Blogs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  scroll" href="#contactus">Contact us</a>
                            </li>
                        </ul>
                    </nav>

                    <div class="side-footer text-white w-100">
                        <ul class="social-icons-simple">
                            <li class="animated-wrap"><a class="animated-element" href="javascript:void(0)"><i
                                        class="fab fa-facebook-f"></i> </a> </li>
                            <li class="animated-wrap"><a class="animated-element" href="javascript:void(0)"><i
                                        class="fab fa-instagram"></i> </a> </li>
                            <li class="animated-wrap"><a class="animated-element" href="javascript:void(0)"><i
                                        class="fab fa-x-twitter"></i> </a> </li>
                        </ul>
                        <p class="text-white">&copy; 2024 MegaOne. Made With Love by Themesindustry</p>
                    </div>
                </div>
            </div>
            <a id="close_side_menu" href="javascript:void(0);"></a>
            <!-- End side menu -->



        </header>
        <!--Header end-->


        <!-- Appointment Table Section -->
        <section>
            <div class="container">
                <div class="table-container p-4 rounded shadow-lg">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="theme-header text-white">
                                <tr>
                                    <th scope="col">Booking ID</th>
                                    <th scope="col">Patient Name</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Doctor Name</th>
                                    <th scope="col">Slot</th>
                                    <th scope="col">Booked At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($bookings) && isset($bookings['bookings']))
                                    @foreach ($bookings['bookings'] as $i => $row)
                                        <tr>

                                            <td><?= htmlspecialchars($row['id'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['patient_name'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                                            <td class="doctor-name"><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
                                            <td class="text-muted"><?= htmlspecialchars($row['slot'] ?? '') ?></td>
                                            <td class="fw-semibold"><?= htmlspecialchars($row['booked_at'] ?? '') ?></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center text-danger">No bookings found</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!--Table end-->


        <!--Footer Start-->
        <section class="text-center footer-sec">
            <button id="chatbotButton" class="chatbot-button" onclick="toggleChatbot()"></button>
            <div id="chatbotWrapper" class="chatbot-iframe-wrapper">
                <!-- <iframe src="https://hospital-management-chatbot-eta.vercel.app/" allow="clipboard-write" -->
                    <!-- title="Confitech Chatbot"></iframe> -->
                     <iframe src="https://hims-chatbot.vercel.app/" allow="microphone; clipboard-write"
                        sandbox="allow-scripts allow-same-origin allow-forms allow-modals allow-popups"
                        title="Confitech Chatbot"></iframe>
            </div>
            <h2 class="d-none">hidden</h2>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="footer-social">
                            <ul class="list-unstyled text-white">
                                <li><a class="wow fadeInUp facebook-bg-hvr" href="javascript:void(0);"><i
                                            class="fab fa-facebook-f"></i></a></li>
                                <li><a class="wow fadeInDown twitter-bg-hvr" href="javascript:void(0);"><i
                                            class="fab fa-x-twitter" aria-hidden="true"></i></a></li>
                                <li><a class="wow fadeInUp google-bg-hvr" href="javascript:void(0);"><i
                                            class="fab fa-google-plus-g" aria-hidden="true"></i></a></li>
                                <li><a class="wow fadeInDown linkedin-bg-hvr" href="javascript:void(0);"><i
                                            class="fab fa-linkedin-in" aria-hidden="true"></i></a></li>
                                <li><a class="wow fadeInUp instagram-bg-hvr" href="javascript:void(0);"><i
                                            class="fab fa-instagram" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                        <p class="company-about fadeIn theme-dark text-capitalize">&copy; 2025 <a class="theme-dark"
                                href="javascript:void(0);">Company</a></p>
                    </div>
                </div>
            </div>
        </section>
        <!--Footer End-->



    </body>
@endsection
