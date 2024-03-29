<?php

use App\Services\FlashMessage as fm;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= PROJECT_ROOT ?>/public/images/flaundry-logo-icon.png" type="image/png" />
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/global.css">
    <!-- <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/formAuth.css"> -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/form/minimalisticForm.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/services/flashMessage.css">
    <title>Tambah Outlet</title>
</head>

<body>
    <div class="container">
        <form method="post" class="wrapper" id="form-login">
            <h1 class="title">Tambah Outlet</h1>
            <div class="input-group">
                <label for="nama">Nama Outlet</label> <input autofocus type="text" name="nama" id="nama" placeholder="Nama Outlet" autocomplete="off" required>
            </div>
            <div class="input-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" placeholder="Alamat" autocomplete="off" required>
            </div>
            <div class="input-group">
                <label for="tlp">Nomor Telpon</label>
                <input type="text" name="tlp" id="tlp" pattern="^\d{8,12}$" autocomplete="off" placeholder="Nomor Telpon" required>
            </div>

            <button class="submit-btn" type="submit" form="form-login" name="submit" value="submit">TAMBAH OUTLET</button>
            <div class="goback-btn">
                <button onclick="window.location.href='<?= routeTo("/panel/outlet") ?>'" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                        <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                    </svg>
                    Back
                </button>
            </div>
        </form>
    </div>

    <?php
    fm::displayPopMessagesByContext('outlet_message', 'bottom-right', 3000);
    ?>
    <script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageCloseDelay.js"></script>
</body>

</html>