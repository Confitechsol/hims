<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />

    <title><?php echo e($title_name ?? 'Hospital Name Title'); ?></title>
    <link href="<?php echo e(asset('assets/images/favlogo.png')); ?>" rel="shortcut icon" type="image/x-icon">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/font-awesome/css/font-awesome.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/css/form-elements.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/usertemplate/assets/css/jquery.mCustomScrollbar.min.css')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        /* Keep your inline CSS as-is (unchanged) */
    </style>
</head>

<body>

    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">

                    <div class="col-lg-5 col-sm-5 <?php echo e(empty($notice) ? 'col-md-offset-3' : ''); ?> form-box">
                        <div class="loginbg">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <img src="<?php echo e(asset('assets/images/letscover_logo.png')); ?>" class="logowidth">
                                </div>
                                <div class="form-top-right">
                                    <i class="fa fa-key"></i>
                                </div>
                            </div>

                            <div class="form-bottom">
                                <h3 class="font-white mb-3">User Login</h3>
                                

                                <?php if(isset($error_message)): ?>
                                    <div class="alert alert-danger"><?php echo e($error_message); ?></div>
                                <?php endif; ?>

                                <?php if(session('message')): ?>
                                    <div class="alert alert-success"><?php echo e(session('message')); ?></div>
                                <?php endif; ?>

                                <form action="<?php echo e(url('site/userlogin')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label class="sr-only">Username</label>
                                        <input type="text" name="username" placeholder="Username"
                                            class="form-control">
                                        <span class="text-danger">
                                            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <?php echo e($message); ?>

                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" placeholder="<?php echo e(__('Password')); ?>"
                                            class="form-control">
                                        <span class="text-danger">
                                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <?php echo e($message); ?>

                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </span>
                                    </div>

                                    <?php if(!empty($is_captcha)): ?>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <span id="captcha_image"><?php echo $captcha_image ?? ''; ?></span>
                                                <span class="glyphicon glyphicon-refresh" title="Refresh Captcha"
                                                    onclick="refreshCaptcha()" style="cursor:pointer;"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="captcha"
                                                    placeholder="<?php echo e(__('enter_captcha')); ?>" class="form-control">
                                                <span class="text-danger">
                                                    <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <?php echo e($message); ?>

                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn login-btn">Sign In</button>

                                        <p>
                                            <a href="<?php echo e(route('ufPassword')); ?>" class="forgot fs-4">
                                                <i class="fa fa-key"></i> Forget Password
                                                
                                            </a>
                                        </p>
                                    </div>
                                </form>

                                <br>
                            </div>
                        </div>
                    </div>

                    <?php if(!empty($notice)): ?>
                        <div class="col-lg-1 col-sm-1">
                            <div class="separatline"></div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="loginright form-box mCustomScrollbar">
                                <div class="messages">
                                    <h3><?php echo e(__('what_is_new_in')); ?> <?php echo e($name ?? ''); ?></h3>
                                    <?php $__currentLoopData = $notice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notice_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <h4><?php echo e($notice_value['title']); ?></h4>
                                        <p>
                                            <?php echo \Illuminate\Support\Str::limit(
                                                strip_tags($notice_value['description']),
                                                100,
                                                '... <a class=more href="' . url('read/' . $notice_value['slug']) . '">' . __('read_more') . '</a>',
                                            ); ?>

                                        </p>
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

    <script src="<?php echo e(asset('assets/usertemplate/assets/js/jquery-1.11.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/usertemplate/assets/bootstrap/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/usertemplate/assets/js/jquery.backstretch.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/usertemplate/assets/js/jquery.mCustomScrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/usertemplate/assets/js/jquery.mousewheel.min.js')); ?>"></script>

    <script>
        $(document).ready(function() {
            $.backstretch([
                "<?php echo e(asset('assets/usertemplate/assets/img/backgrounds/user15.jpg')); ?>"
            ], {
                duration: 3000,
                fade: 750
            });
        });

        function refreshCaptcha() {
            $.ajax({
                type: "POST",
                url: "<?php echo e(url('site/refreshCaptcha')); ?>",
                data: {
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(captcha) {
                    $("#captcha_image").html(captcha);
                }
            });
        }
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\hims\resources\views\home\userlogin.blade.php ENDPATH**/ ?>