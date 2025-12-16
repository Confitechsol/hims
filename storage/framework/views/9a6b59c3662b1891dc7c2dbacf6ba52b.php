<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />

    <title><?php echo e($title_name ?? 'Hospital Name Title'); ?></title>

    <!-- FavIcon -->
    <link href="<?php echo e(asset('assets/images/favicon.ico')); ?>" rel="shortcut icon" type="image/x-icon">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/font-awesome/css/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/css/form-elements.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/css/jquery.mCustomScrollbar.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/dist/css/style-main.css')); ?>">

    
    <style>
        body {
            font-family: "Inter", sans-serif !important;
        }

        .col-md-offset-3 {
            margin-left: 29%;
        }

        .login-form .form-group label {
            font-size: 13px;
            font-weight: 500;
        }

        .loginbg {
            /* background: #ffffff00;
            max-height: 480px;
            box-shadow: 0px 0px 20px 6px rgba(62, 57, 107, 0.2);
            border-radius: 4px;
            backdrop-filter: blur(3px); */
            background: none;
            max-height: 480px;
            box-shadow: none;
            border-radius: 0;
            backdrop-filter: none;
        }

        .login_back {
            background-image: url("<?php echo e(asset('/assets/images/auth-bg.jpg')); ?>");
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

        .form-bottom {
            background: #fff;
            padding-top: 20px;
            border: 1px solid #e7e8eb;
            box-shadow: 0px 7px 12px rgba(0, 0, 0, 0.29);
            backdrop-filter: blur(5px);
        }

        .form-bottom form button.btn {
            width: 100%;
            background: linear-gradient(90deg, #750096 0%, #CB6CE6 100%);
            font-weight: 500;
        }

        .form-top-right {
            float: right;
            width: 25px;
            color: #dc0f52;
        }

        .form-top-left {
            float: none;
            width: 35%;
            margin: auto;
            padding-bottom: 20px;
        }

        a.forgot {
            color: #dc0f52;
            font-size: 13px !important;
        }

        a:hover.forgot {
            text-decoration: underline;
        }

        input[type="text"],
        input[type="password"],
        textarea,
        textarea.form-control {
            background: #fff;
            font-size: 13px;
            padding: 8px 8px 8px 40px;
        }

        input[type="text"] {
            background-image: url(./assets/images/user_img.png);
            background-size: 16px;
            background-repeat: no-repeat;
            background-position: 10px center;
        }
        
        input[type="password"] {
            background-image: url(./assets/images/pass_img.png);
            background-size: 16px;
            background-repeat: no-repeat;
            background-position: 10px center;
        }

        .form-bottom h3 {
            text-align: center;
            font-size: 20px !important;
            font-weight: 700;

        }

        .form-bottom .sign_title {
            color: #999999;
            text-align: center;
            font-size: 14px;
        }

        .forgot_box {
            display: block;
            margin-left: auto;
            width: fit-content;
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
                    <?php
                        $empty_notice = empty($notice);
                        $offset = $empty_notice ? 'col-md-offset-3' : '';
                    ?>

                    <div class="col-lg-5 col-sm-5 form-box <?php echo e($offset); ?>">
                        <div class="loginbg">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <img src="<?php echo e(asset('assets/img/logo.png')); ?>" alt="Logo">
                                </div>
                            </div>
                            <div class="form-bottom">
                                <h3 class="bolds fs-2 mb-3">Sign In</h3>
                                <p class="sign_title">Please enter below details to access the dashboard</p>

                                
                                <?php if(session('error_message')): ?>
                                    <div class="alert alert-danger"><?php echo e(session('error_message')); ?></div>
                                <?php endif; ?>
                                <?php if(session('message')): ?>
                                    <div class="alert alert-success"><?php echo e(session('message')); ?></div>
                                <?php endif; ?>

                                
                                <form action="<?php echo e(route('login')); ?>" method="POST" class="login-form">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" placeholder="Enter Your Username"
                                            value="<?php echo e(old('username')); ?>" class="form-username form-control" id="email">
                                        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" placeholder="Enter Your Password"
                                            class="form-password form-control" id="password">
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <?php if($is_captcha ?? false): ?>
                                        <div class="form-group has-feedback row">
                                            <div class='col-md-6'>
                                                <span id="captcha_image"><?php echo $captcha_image ?? ''; ?></span>
                                                <span class="glyphicon glyphicon-refresh" title="Refresh Captcha"
                                                    onclick="refreshCaptcha()" style="cursor:pointer;"></span>
                                            </div>
                                            <div class='col-md-6'>
                                                <input type="text" name="captcha" placeholder="<?php echo e(__('Enter Captcha')); ?>"
                                                    class="form-control" id="captcha">
                                                <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <span class="text-danger"><?php echo e($message); ?></span>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="">
                                        <button type="submit" class="btn">LogIn</button>
                                        <p class="forgot_box">
                                            <a href="#" class="forgot fs-4">
                                                <i class="fa fa-key"></i>Forgot Password?
                                            </a>
                                        </p>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    
                    <?php if (! ($empty_notice)): ?>
                        <div class="col-lg-1 col-sm-1">
                            <div class="separatline"></div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="loginright form-box mCustomScrollbar">
                                <div class="messages">
                                    <h3>What is new in <?php echo e($sch_name ?? ''); ?></h3>
                                    <?php $__currentLoopData = $notice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notice_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <h4><?php echo e($notice_value['title']); ?></h4>
                                        <?php
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
                                        ?>
                                        <p><?php echo $string; ?></p>
                                        <div class="logdivider"></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo e(asset('assets/usertemplate/assets/js/jquery-1.11.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/usertemplate/assets/bootstrap/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/usertemplate/assets/js/jquery.backstretch.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/usertemplate/assets/js/jquery.mCustomScrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/usertemplate/assets/js/jquery.mousewheel.min.js')); ?>"></script>

    <script>
        $(document).ready(function () {
            $.backstretch([
                "<?php echo e(asset('assets/usertemplate/assets/img/backgrounds/background.png')); ?>"
            ], {
                duration: 3000,
                fade: 750
            });

            $('.login-form input').on('focus', function () {
                $(this).removeClass('input-error');
            });

            $('.login-form').on('submit', function (e) {
                $(this).find('input').each(function () {
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
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function (captcha) {
                    $("#captcha_image").html(captcha);
                }
            });
        }
    </script>
</body>

</html>
<?php echo $__env->make('layouts.cdns', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views\admin\login.blade.php ENDPATH**/ ?>