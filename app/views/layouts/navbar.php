<div class="header">
    <div class="logo">
        <img src="<?= PROJECT_ROOT ?>/public/images/logo.png" alt="">
    </div>
    <div class="menu">
        <ul>
            <li><a href="<?= routeTo('/') ?>">Home</a></li>
            <li><a href="<?= routeTo('/about') ?>">About</a></li>
            <li><a href="<?= routeTo('/contact') ?>">Contact</a></li>
            <li><a href="<?= routeTo('/service') ?>">Service</a></li>
            <li><a class="login-button" href="<?= routeTo('/auth/login') ?>">Login</a></li>
        </ul>
    </div>
</div>