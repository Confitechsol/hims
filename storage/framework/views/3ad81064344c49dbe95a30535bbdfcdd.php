<?php $__env->startSection('content'); ?>

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
                            <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">Home</a>
                            <a class="nav-link" href="#whymegaone">Why Cognaihealth</a>
                            <a href="<?php echo e(route('doctors')); ?>" class="nav-link active">Doctors</a>
                            <a href="<?php echo e(route('appointments')); ?>" class="nav-link">Appointment</a>
                            <a class="nav-link" href="#pateintgallery">Pateint Gallery</a>
                            <a class="nav-link" href="#ourblogs">Our Blogs</a>
                            <a class="nav-link" href="#contactus">Contact us</a>
                            <a class="nav-link active bg-info text-white" href="<?php echo e(route('userLogin')); ?>">Login</a>
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
                                <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  scroll" href="#whymegaone">Why MegaOne</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('doctors')); ?>" class="nav-link active">Doctors</a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('appointments')); ?>" class="nav-link">Appointment</a>
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


        <!--Table start-->

        <!-- Doctor Table Section -->
        <section>
            <div class="container">
                <div class="table-container p-4 rounded shadow-lg">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="theme-header text-white">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Doctor Name</th>
                                    <th scope="col">Specialization</th>
                                    <th scope="col">Available Slots</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">1</td>
                                    <td class="doctor-name">Dr. Asha Verma</td>
                                    <td>General Physician</td>
                                    <td class="text-muted">Mon 10am, Mon 4pm, Tue 2pm, Wed 11am</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">2</td>
                                    <td class="doctor-name">Dr. Rajiv Sharma</td>
                                    <td>Cardiologist</td>
                                    <td class="text-muted">Mon 2pm, Tue 10am, Thu 3pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">3</td>
                                    <td class="doctor-name">Dr. Neha Kapoor</td>
                                    <td>Dermatologist</td>
                                    <td class="text-muted">Wed 1pm, Fri 11am, Sat 9am</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">4</td>
                                    <td class="doctor-name">Dr. Arvind Menon</td>
                                    <td>Orthopedic</td>
                                    <td class="text-muted">Mon 9am, Tue 4pm, Fri 2pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">5</td>
                                    <td class="doctor-name">Dr. Sneha Iyer</td>
                                    <td>Neurologist</td>
                                    <td class="text-muted">Wed 2pm, Thu 11am, Fri 10am</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">6</td>
                                    <td class="doctor-name">Dr. Manish Batra</td>
                                    <td>ENT</td>
                                    <td class="text-muted">Mon 3pm, Tue 5pm, Thu 9am</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">7</td>
                                    <td class="doctor-name">Dr. Pooja Nair</td>
                                    <td>Gynecologist</td>
                                    <td class="text-muted">Tue 11am, Thu 4pm, Sat 10am</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">8</td>
                                    <td class="doctor-name">Dr. Karan Malhotra</td>
                                    <td>Gastroenterologist</td>
                                    <td class="text-muted">Mon 5pm, Wed 3pm, Fri 1pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">9</td>
                                    <td class="doctor-name">Dr. Meera Joshi</td>
                                    <td>Psychiatrist</td>
                                    <td class="text-muted">Tue 10am, Thu 2pm, Sat 12pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">10</td>
                                    <td class="doctor-name">Dr. Amit Chawla</td>
                                    <td>Pulmonologist</td>
                                    <td class="text-muted">Mon 2pm, Wed 10am, Fri 4pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">11</td>
                                    <td class="doctor-name">Dr. Nidhi Rathi</td>
                                    <td>Ophthalmologist</td>
                                    <td class="text-muted">Tue 9am, Thu 5pm, Sat 11am</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">12</td>
                                    <td class="doctor-name">Dr. Sanjay Patil</td>
                                    <td>Dentist</td>
                                    <td class="text-muted">Mon 1pm, Wed 4pm, Fri 9am</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">13</td>
                                    <td class="doctor-name">Dr. Rekha Sinha</td>
                                    <td>Endocrinologist</td>
                                    <td class="text-muted">Tue 3pm, Thu 10am, Sat 2pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">14</td>
                                    <td class="doctor-name">Dr. Rohit Khanna</td>
                                    <td>Urologist</td>
                                    <td class="text-muted">Mon 10am, Wed 2pm, Fri 5pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">15</td>
                                    <td class="doctor-name">Dr. Divya Aggarwal</td>
                                    <td>Nephrologist</td>
                                    <td class="text-muted">Tue 4pm, Thu 1pm, Sat 3pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">16</td>
                                    <td class="doctor-name">Dr. Anil Deshmukh</td>
                                    <td>Pediatrician</td>
                                    <td class="text-muted">Mon 11am, Wed 5pm, Fri 3pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">17</td>
                                    <td class="doctor-name">Dr. Priya Mehta</td>
                                    <td>Rheumatologist</td>
                                    <td class="text-muted">Tue 5pm, Thu 9am, Sat 4pm</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">18</td>
                                    <td class="doctor-name">Dr. Varun Gupta</td>
                                    <td>Oncologist</td>
                                    <td class="text-muted">Mon 4pm, Wed 9am, Fri 11am</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">19</td>
                                    <td class="doctor-name">Dr. Ashok Sen</td>
                                    <td>Dermatologist</td>
                                    <td class="text-muted">Tue 2pm, Thu 3pm, Sat 10am</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">20</td>
                                    <td class="doctor-name">Dr. Kavita Rao</td>
                                    <td>Cardiologist</td>
                                    <td class="text-muted">Mon 9am, Wed 1pm, Fri 2pm</td>
                                </tr>
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
                <iframe src="https://hospital-management-chatbot-eta.vercel.app/" allow="clipboard-write"
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/home/doctors.blade.php ENDPATH**/ ?>