<head>
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/layouts/navbar.css">
</head>
<div class="header">
    <div class="logo">
        <img src="<?= PROJECT_ROOT ?>/public/images/logo.svg" alt="">
    </div>
    <div class="menu">
        <ul>
            <li><a href="<?= routeTo('/about') ?>">About</a></li>
            <li><a href="<?= routeTo('/contact') ?>">Contact</a></li>
            <li><a href="<?= routeTo('/auth/login') ?>">Pricing</a></li>
            <li><a href="<?= routeTo('/service') ?>">Service</a></li>
            <li><a href="<?= routeTo('/panel/admin') ?>">Panel</a></li>
            <li><a class="login-button" href="<?= routeTo('/auth/login') ?>">Login</a></li>
        </ul>
    </div>
</div>
