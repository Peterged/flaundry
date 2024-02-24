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
                <label for="telpon">Nomor Telpon</label>
                <input type="text" name="telpon" id="telpon" pattern="^\d{8,12}$" autocomplete="off" placeholder="Nomor Telpon" required>
            </div>
            <div class="input-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin">
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <div class="input-group">
                <label for="">Jenis Kelamin</label>
                <div class="radio-group">
                    <input type="radio" name="status" id="status-active" value="active">
                    <label for="status-active">Laki - Laki</label>
                    <input type="radio" name="status" id="status-inactive" value="inactive">
                    <label for="status-inactive">Perempuan</label>
                </div>
            </div>
            <button class="submit-btn" type="submit" form="form-login" name="submit" value="submit">TAMBAH OUTLET</button>
        </form>
    </div>

    <?php
    fm::displayPopMessagesByContext('login', 'bottom-right', 3000);
    ?>
    <script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageCloseDelay.js"></script>
</body>

</html>