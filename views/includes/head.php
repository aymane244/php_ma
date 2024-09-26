<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token(); ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/vendor/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/vendor/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/vendor/rowGroup.dataTables.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/vendor/animate.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/vendor/spinkit.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/vendor/jquery-ui.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/responsive.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/animations.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="<?= asset('/assets/js/vendor/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('/assets/js/vendor/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= asset('/assets/js/vendor/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= asset('/assets/js/vendor/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?= asset('/assets/js/vendor/dataTables.rowGroup.js') ?>"></script>
    <script src="<?= asset('/assets/js/vendor/rowGroup.dataTables.js') ?>"></script>
    <script src="<?= asset('/assets/js/vendor/chart.js-4.4.3.js') ?>"></script>
    <script src="<?= asset('/assets/js/vendor/jquery-ui.js') ?>"></script>
    <script src="<?= asset('/assets/js/global.js') ?>"></script>
    <script src="<?= asset('/assets/js/main.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title><?= page_name($name) ?></title>
    <style>
        @font-face{
            font-family: 'Rubik';
            src: url('/assets/fonts/Rubik-Regular.ttf') format('truetype');
        }
    </style>
</head>
<body>