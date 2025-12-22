<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

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
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>
    <!-- Favicon -->
    <link rel="icon" href="<?php echo e(asset('assets//medical/img/favicon.ico')); ?>">
    <!-- Bundle -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/styles/css/bundle.min.css')); ?>">
    <!-- Plugin Css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/styles/css/revolution-settings.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/styles/css/jquery.fancybox.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/styles/css/owl.carousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/styles/css/swiper.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/styles/css/cubeportfolio.min.css')); ?>">
    <!-- Style Sheet -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/styles/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/styles/css/jquery-ui.bundle.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/medical/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <style>
        /* Floating Button */
        .chatbot-button {
            position: fixed;
            bottom: 50px;
            right: 20px;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 9998;
            background-image: url("<?php echo e(asset('assets/medical/img/hbot.png')); ?>");
            background-size: 50%;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #17a2b8;
            transition: background-color 0.3s ease;
        }

        .chatbot-button:hover {
            background-color: #cae8ecff;
        }

        .chatbot-button:focus {
            background-color: #17a2b8;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* When active, change icon */
        .chatbot-button.active {
            background-image: url("<?php echo e(asset('assets/medical/img/delete.png')); ?>");
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
</head>

<?php echo $__env->yieldContent('content'); ?>

<!-- JavaScript -->
<script>
    function toggleChatbot() {
        var chatbot = document.getElementById('chatbotWrapper');
        var button = document.getElementById('chatbotButton');

        chatbot.classList.toggle('active');
        button.classList.toggle('active');
    }
</script>
<script src="<?php echo e(asset('assets/styles/js/bundle.min.js')); ?>"></script>

<!-- Plugin Js -->
<script src="<?php echo e(asset('assets/styles/js/jquery.fancybox.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/swiper.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/jquery.cubeportfolio.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/jquery.appear.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/hover-item.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/isotope.pkgd.min.js')); ?>"></script>
<!-- REVOLUTION JS FILES -->
<script src="<?php echo e(asset('assets/styles/js/jquery.themepunch.tools.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/jquery.themepunch.revolution.min.js')); ?>"></script>
<!-- SLIDER REVOLUTION EXTENSIONS -->
<script src="<?php echo e(asset('assets/styles/js/extensions/revolution.extension.actions.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/extensions/revolution.extension.carousel.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/extensions/revolution.extension.kenburn.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/extensions/revolution.extension.layeranimation.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/extensions/revolution.extension.migration.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/extensions/revolution.extension.navigation.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/extensions/revolution.extension.parallax.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/extensions/revolution.extension.slideanims.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/extensions/revolution.extension.video.min.js')); ?>"></script>
<!-- custom script -->
<script src="<?php echo e(asset('assets/styles/js/select2.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/date.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/jquery.hoverdir.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/jquery-ui.bundle.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/flip.js')); ?>"></script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4fusEY9kSwNHgtK8KOgyoTsyP5Tb2NXo"></script>
<script src="<?php echo e(asset('assets/medical/js/map.js')); ?>"></script>
<script src="<?php echo e(asset('assets/styles/js/contact_us.js')); ?>"></script>
<script src="<?php echo e(asset('assets/medical/js/script.js')); ?>"></script>

</html>
<?php /**PATH /home/u676663263/domains/confitechone.com/public_html/hims/resources/views/layouts/main.blade.php ENDPATH**/ ?>