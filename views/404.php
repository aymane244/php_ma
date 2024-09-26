<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('/assets/css/vendor/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/font.css') ?>">
    <link rel="stylesheet" href="<?= asset('/assets/css/main.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="<?= asset('/assets/js/vendor/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('/assets/js/vendor/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?= asset('/assets/js/global.js') ?>"></script>
    <script src="<?= asset('/assets/js/main.js') ?>"></script>
    <title><?= translate("404") ?></title>
</head>
<body>
<main <?= dir_html() ?>>
    <h2 class="text-nav-color mt-5 text-center">
        <?= translate("404") ?><br><br>
        <a href="/" class="text-nav-color"> <?= translate("return") ?></a>
    </h2>
</main>
</body>