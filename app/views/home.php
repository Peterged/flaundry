<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/home.css">
    <title>FLaundry</title>
</head>

<body>
    <main>
        <div class="container">
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
            <div class="content">
                <div class="content-left">
                    <h1>FLaundry</h1>
                    <p>Lorem</p>
                </div>
                <div class="content-right">
                    <img src="<?= PROJECT_ROOT ?>/public/img/laundry.png" alt="">
                </div>
            </div>
        </div>
    </main>
</body>

</html>