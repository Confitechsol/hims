<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />
    <title>{{ $title_name ?? 'Hospital Name Title' }}</title>
    <!--favican-->
    <link href="{{ asset('assets/images/favlogo.png') }}" rel="shortcut icon" type="image/x-icon">
    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/css/form-elements.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style type="text/css">
        /* .inner-bg {padding: 10px 0 170px 0;}*/
        body {
            background: #424242;
        }

        .discover {
            margin-top: -90px;
            position: relative;
            z-index: -1;
        }

        .form-bottom {
            box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.35);
            padding-bottom: 15px !important
        }

        .gradient {
            margin-top: 40px;
            text-align: right;
            padding: 10px;
            background: rgb(72, 72, 72);
            background: -moz-linear-gradient(left, rgba(72, 72, 72, 1) 1%, rgba(73, 73, 73, 1) 44%, rgba(73, 73, 73, 1) 100%);
            background-image: linear-gradient(to right, rgba(72, 72, 72, 0.23) 1%, rgba(37, 37, 37, 0.64) 44%, rgba(73, 73, 73, 0) 100%);
            background-position-x: initial;
            background-position-y: initial;
            background-size: initial;
            background-repeat-x: initial;
            background-repeat-y: initial;
            background-attachment: initial;
            background-origin: initial;
            background-clip: initial;
            background-color: initial;
            background: -webkit-linear-gradient(left, rgba(72, 72, 72, 1) 1%, rgb(73, 73, 73) 44%, rgba(73, 73, 73, 1) 100%);
            background: linear-gradient(to right, rgba(72, 72, 72, 0.02) 1%, rgba(37, 37, 37, 0.67) 30%, rgba(73, 73, 73, 0) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#484848', endColorstr='#494949', GradientType=1);
        }

        @media (min-width: 320px) and (max-width: 991px) {
            .width100 {
                width: 100% !important;
                display: block !important;
                float: left !important;
                margin-bottom: 5px !important;
                border-radius: 2px !important;
            }

            .width50 {
                width: 50% !important;
                margin-bottom: 5px !important;
                display: block !important;
                border-radius: 2px 0px 0px 2px !important;
                float: left !important;
                margin-left: 0px !important;
            }

            .widthright50 {
                width: 50% !important;
                display: block !important;
                margin-bottom: 5px !important;
                border-radius: 0px 2px 2px 0px !important;
                float: left !important;
                margin-left: 0px !important;
            }
        }

        input[type="text"],
        input[type="password"],
        textarea,
        textarea.form-control {
            height: 40px;
            border: 1px solid #999;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        textarea:focus,
        textarea.form-control:focus {
            border: 1px solid #424242;
        }

        button.btn {
            height: 40px;
            line-height: 40px;
        }

        @media(max-width:767px) {
            .discover {
                margin-top: 10px
            }

            .gradient {
                text-align: center;
            }

            .logowidth {
                padding-right: 0px;
            }
        }

        @media(min-width:768px) and (max-width:992px) {
            .discover {
                margin-top: 10px
            }

            .logowidth {
                padding-right: 0px;
            }

            .gradient {
                text-align: center;
            }
        }

        .bgwhite {
            background: #e4e5e7;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.5);
            overflow: auto;
            border-radius: 6px;
        }


        label.radio-inline {
            font-size: 14px;
            line-height: 30px;
        }

        .radio-inline input[type=radio] {
            position: absolute;
            margin-top: 8px;
            outline: none;
        }

        .backstretch {
            position: relative;
        }

        .backstretch:after {
            position: absolute;
            z-index: 2;
            width: 100%;
            height: 100%;
            display: block;
            left: 0;
            top: 0;
            content: "";
            background-color: rgba(16, 16, 16, 0.70);
        }
    </style>
</head>

<body>
    <!-- Top content -->
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        @php
                            $logoresult = [
                                'image' => null, // Example: fetch from DB or config
                                'mini_logo' => null,
                            ];

                            $logo_image = !empty($logoresult['image'])
                                ? asset('uploads/hospital_content/logo/' . $logoresult['image'])
                                : asset('assets/images/letscover_logo.png');

                            $mini_logo = !empty($logoresult['mini_logo'])
                                ? asset('uploads/hospital_content/logo/' . $logoresult['mini_logo'])
                                : asset('uploads/hospital_content/logo/smalllogo.png');
                        @endphp

                        <div class="text-center mb-4">
                            <img src="{{ $logo_image }}" class="logowidth">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 form-box">
                        <div class="loginbg">
                            <div class="form-top">
                                <div class="form-top-left pt-20">
                                    <h3 class="font-white mb-0 mt-0">Forget Password</h3>

                                </div>
                                <div class="form-top-right">
                                    <i class="fa fa-key"></i>
                                </div>
                            </div>
                            <div class="form-bottom">
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . '</div>';
                                }
                                ?>
                                <form class="" action="#" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="sr-only" for="form-username">Username</label>
                                        <input type="text" name="username" placeholder="Email"
                                            class="form-username form-control" id="form-username">
                                        @if (session('usernameError'))
                                            <div class="text-danger">{{ session('usernameError') }}</div>
                                        @endif
                                    </div>
                                    <input name="user" type="hidden" value="patient">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn">Submit</button>
                                        <a href="{{ route('userLogin') }}" class="forgot fs-4"><i class="fa fa-key"></i>
                                            User Login</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Javascript -->
    <script src="{{ asset('assets/usertemplate/assets/js/jquery-1.11.1.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/js/jquery.backstretch.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.backstretch([
                "{{ asset('assets/usertemplate/assets/img/backgrounds/user15.jpg') }}"
            ], {
                duration: 3000,
                fade: 750
            });

            // Remove error highlight on focus
            $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on(
                'focus',
                function() {
                    $(this).removeClass('input-error');
                }
            );

            // Validate empty fields on submit
            $('.login-form').on('submit', function(e) {
                $(this).find('input[type="text"], input[type="password"], textarea').each(function() {
                    if ($(this).val().trim() === "") {
                        e.preventDefault();
                        $(this).addClass('input-error');
                    } else {
                        $(this).removeClass('input-error');
                    }
                });
            });
        });
    </script>
</body>

</html>
