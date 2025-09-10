<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />

    <title>{{ $title_name ?? 'Hospital Name Title' }}</title>

    <!-- FavIcon -->
    <link href="{{ asset('assets/images/favlogo.png') }}" rel="shortcut icon" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/css/form-elements.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/css/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style-main.css') }}">

    @extends('layouts.cdns')
    <style>
        .col-md-offset-3 {
            margin-left: 29%;
        }

        .loginbg {
            background: #ffffff00;
            max-height: 480px;
            box-shadow: 0px 0px 20px 6px rgba(62, 57, 107, 0.2);
            border-radius: 4px;
            backdrop-filter: blur(3px);
        }

        .login_back {
            background-image: url("{{ asset('uploads/hospital_content/logo/background.png') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
        }

        button.btn {
            background: #ff9800;
            border-radius: 4px;
            color: #fff;
            transition: all .3s;
        }

        button.btn:hover {
            background: #fbc02d;
        }

        .form-bottom form button.btn {
            width: 25%;
            float: right;
            background: #dc0f52;
        }

        .form-top-right {
            float: right;
            width: 25px;
            color: #dc0f52;
        }

        .form-top-left {
            float: left;
            width: 30%;
        }

        a.forgot {
            color: #dc0f52;
        }

        a:hover.forgot {
            text-decoration: underline;
        }

        @media (max-width: 767px) {
            .col-md-offset-3 {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="top-content login_back">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    @php
                        $empty_notice = empty($notice);
                        $offset = $empty_notice ? 'col-md-offset-3' : '';
                    @endphp

                    <div class="col-lg-5 col-sm-5 form-box {{ $offset }}">
                        <div class="loginbg" style="box-shadow: 0px 7px 12px rgba(0, 0, 0, 0.29)">
                            <div class="form-top justify-content-between align-items-center">
                                <div class="form-top-left">
                                    <img src="{{ asset('assets/images/favlogo.png') }}" alt="Logo">
                                </div>
                                <div class="form-top-right">
                                    <i class="fa fa-key"></i>
                                </div>
                            </div>
                            <div class="form-bottom">
                                <h3 class="bolds text-white fs-2 mb-3">Admin Login</h3>

                                {{-- Error Messages --}}
                                @if (session('error_message'))
                                    <div class="alert alert-danger">{{ session('error_message') }}</div>
                                @endif
                                @if (session('message'))
                                    <div class="alert alert-success">{{ session('message') }}</div>
                                @endif

                                {{-- Login Form --}}
                                <form action="{{ route('login') }}" method="POST" class="login-form">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="username" placeholder="Username"
                                            value="{{ old('username') }}" class="form-username form-control"
                                            id="email">
                                        @error('username')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" placeholder="Password"
                                            class="form-password form-control" id="password">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @if ($is_captcha ?? false)
                                        <div class="form-group has-feedback row">
                                            <div class='col-md-6'>
                                                <span id="captcha_image">{!! $captcha_image ?? '' !!}</span>
                                                <span class="glyphicon glyphicon-refresh" title="Refresh Captcha"
                                                    onclick="refreshCaptcha()" style="cursor:pointer;"></span>
                                            </div>
                                            <div class='col-md-6'>
                                                <input type="text" name="captcha"
                                                    placeholder="{{ __('Enter Captcha') }}" class="form-control"
                                                    id="captcha">
                                                @error('captcha')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn">Sign In</button>
                                        <p>
                                            <a href="#" class="forgot fs-4">
                                                <i class="fa fa-key"></i>Forgot Password?
                                            </a>
                                        </p>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    {{-- Notices --}}
                    @unless ($empty_notice)
                        <div class="col-lg-1 col-sm-1">
                            <div class="separatline"></div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="loginright form-box mCustomScrollbar">
                                <div class="messages">
                                    <h3>What is new in {{ $sch_name ?? '' }}</h3>
                                    @foreach ($notice as $notice_value)
                                        <h4>{{ $notice_value['title'] }}</h4>
                                        @php
                                            $string = strip_tags($notice_value['description']);
                                            if (strlen($string) > 100) {
                                                $stringCut = substr($string, 0, 100);
                                                $endPoint = strrpos($stringCut, ' ');
                                                $string = $endPoint ? substr($stringCut, 0, $endPoint) : $stringCut;
                                                $string .=
                                                    '... <a class="more" href="' .
                                                    route('notice.read', $notice_value['slug']) .
                                                    '">Read More</a>';
                                            }
                                        @endphp
                                        <p>{!! $string !!}</p>
                                        <div class="logdivider"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endunless
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/usertemplate/assets/js/jquery-1.11.1.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/js/jquery.backstretch.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/js/jquery.mCustomScrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/js/jquery.mousewheel.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $.backstretch([
                "{{ asset('assets/usertemplate/assets/img/backgrounds/background.png') }}"
            ], {
                duration: 3000,
                fade: 750
            });

            $('.login-form input').on('focus', function() {
                $(this).removeClass('input-error');
            });

            $('.login-form').on('submit', function(e) {
                $(this).find('input').each(function() {
                    if ($(this).val().trim() === "") {
                        e.preventDefault();
                        $(this).addClass('input-error');
                    }
                });
            });
        });

        function refreshCaptcha() {
            $.ajax({
                type: "POST",
                url: "#",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(captcha) {
                    $("#captcha_image").html(captcha);
                }
            });
        }
    </script>
</body>

</html>
