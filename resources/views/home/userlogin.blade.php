<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />

    <title>{{ $title_name ?? 'Hospital Name Title' }}</title>
    <link href="{{ asset('uploads/hospital_content/logo/' . ($logoresult['mini_logo'] ?? 'default.png')) }}"
        rel="shortcut icon" type="image/x-icon">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/css/form-elements.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usertemplate/assets/css/jquery.mCustomScrollbar.min.css') }}">

    <style>
        /* Keep your inline CSS as-is (unchanged) */
    </style>
</head>

<body>

    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">

                    <div class="col-lg-5 col-sm-5 {{ empty($notice) ? 'col-md-offset-3' : '' }} form-box">
                        <div class="loginbg">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <img src="{{ asset('assets/images/letscover_logo.png') }}" class="logowidth">
                                </div>
                                <div class="form-top-right">
                                    <i class="fa fa-key"></i>
                                </div>
                            </div>

                            <div class="form-bottom">
                                <h3 class="font-white">User Login</h3>
                                {{-- <h3 class="font-white">{{ __('user_login') }}</h3> --}}

                                @if (isset($error_message))
                                    <div class="alert alert-danger">{{ $error_message }}</div>
                                @endif

                                @if (session('message'))
                                    <div class="alert alert-success">{{ session('message') }}</div>
                                @endif

                                <form action="{{ url('site/userlogin') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="sr-only">Username</label>
                                        <input type="text" name="username" placeholder="Username"
                                            class="form-control">
                                        <span class="text-danger">
                                            @error('username')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" placeholder="{{ __('Password') }}"
                                            class="form-control">
                                        <span class="text-danger">
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>

                                    @if (!empty($is_captcha))
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <span id="captcha_image">{!! $captcha_image ?? '' !!}</span>
                                                <span class="glyphicon glyphicon-refresh" title="Refresh Captcha"
                                                    onclick="refreshCaptcha()" style="cursor:pointer;"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="captcha"
                                                    placeholder="{{ __('enter_captcha') }}" class="form-control">
                                                <span class="text-danger">
                                                    @error('captcha')
                                                        {{ $message }}
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <button type="submit" class="btn">{{ __('sign_in') }}</button>
                                </form>

                                <br>
                                <p>
                                    <a href="{{ url('site/ufpassword') }}" class="forgot">
                                        <i class="fa fa-key"></i> Forget Password
                                        {{-- <i class="fa fa-key"></i> {{ __('forgot_password') }} --}}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if (!empty($notice))
                        <div class="col-lg-1 col-sm-1">
                            <div class="separatline"></div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="loginright form-box mCustomScrollbar">
                                <div class="messages">
                                    <h3>{{ __('what_is_new_in') }} {{ $name ?? '' }}</h3>
                                    @foreach ($notice as $notice_value)
                                        <h4>{{ $notice_value['title'] }}</h4>
                                        <p>
                                            {!! \Illuminate\Support\Str::limit(
                                                strip_tags($notice_value['description']),
                                                100,
                                                '... <a class=more href="' . url('read/' . $notice_value['slug']) . '">' . __('read_more') . '</a>',
                                            ) !!}
                                        </p>
                                        <div class="logdivider"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/usertemplate/assets/js/jquery-1.11.1.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/js/jquery.backstretch.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/js/jquery.mCustomScrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/usertemplate/assets/js/jquery.mousewheel.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $.backstretch([
                "{{ asset('assets/usertemplate/assets/img/backgrounds/user15.jpg') }}"
            ], {
                duration: 3000,
                fade: 750
            });
        });

        function refreshCaptcha() {
            $.ajax({
                type: "POST",
                url: "{{ url('site/refreshCaptcha') }}",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(captcha) {
                    $("#captcha_image").html(captcha);
                }
            });
        }
    </script>
</body>

</html>
