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
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/form/minimalisticForm.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/services/flashMessage.css">
    <title>Edit Karyawan</title>
</head>

<body>
    <?php
    if (@!isset($data['currentKaryawan'][0])) {
        $homeRoute = routeTo("/panel/karyawan");
        header("Location: $homeRoute");
        exit;
    }
    $currentKaryawan = $data['currentKaryawan'][0];
    ?>
    <div class="container">
        <form method="post" class="wrapper" id="form-login">
            <h1 class="title">Edit Karyawan</h1>
            <div class="input-group">
                <label for="id_outlet">Pilih Outlet</label>
                <select name="id_outlet" id="id_outlet" name="id_outlet">
                    <?php
                    $karyawan = $data['model'];
                    $dataOutlet = $karyawan->query("SELECT id, nama FROM tb_outlet");
                    $dataOutlet = $dataOutlet->getData();

                    foreach ($dataOutlet as $outlet) {
                        $selected = $currentKaryawan['id_outlet'] == $outlet['id'] ? 'selected' : '';
                        echo "<option value=\"{$outlet['id']}\" $selected>{$outlet['nama']}</option>";
                    }
                    ?>
                </select>



            </div>
            <div class="input-group">
                <label for="nama">Nama Karyawan</label> <input autofocus type="text" name="nama" id="nama" placeholder="Nama Karyawan" autocomplete="off" value="<?= $currentKaryawan['nama'] ?>">
            </div>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" required value="<?= $currentKaryawan['username'] ?>">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" placeholder="New password" autocomplete="off" value="">
            </div>

            <div class="input-group">
                <label for="role">Role Karyawan</label>
                <select name="role" id="role">
                    <option disabled selected hidden> -- Pilih Role --</option>
                    <?php

                    $roleOptions = ['admin', 'kasir', 'owner'];
                    foreach ($roleOptions as $role) {
                        $roleDisplay = ucfirst($role);
                        $selected = $currentKaryawan['role'] == $role ? 'selected' : '';
                        echo "<option value=\"$role\" $selected>$roleDisplay</option>";
                    }
                    ?>
                </select>


            </div>
            <button class="submit-btn" type="submit" form="form-login" name="submit" value="submit">EDIT KARYAWAN</button>

            <div class="goback-btn">
                <button onclick="window.location.href='<?= routeTo("/panel/karyawan") ?>'" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                        <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                    </svg>
                    Back
                </button>
            </div>
        </form>
    </div>

    <?php
    fm::displayPopMessagesByContext('karyawan_message', 'bottom-right', 3000);
    ?>
    <script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageCloseDelay.js"></script>
</body>

</html>