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
    <title>Edit Member</title>
</head>

<body>
    <?php
    if (@!isset($data['currentMember'][0])) {
        $homeRoute = routeTo("/panel/member");
        header("Location: $homeRoute");
        exit;
    }
    $currentMember = $data['currentMember'][0];
    ?>
    <div class="container">
        <form method="post" class="wrapper" id="form-login">
            <h1 class="title">Edit Member</h1>
            <div class="input-group">
                <label for="nama">Nama Member</label> <input autofocus type="text" name="nama" id="nama" placeholder="Nama Member" autocomplete="off" required value="<?= $currentMember['nama'] ?>">
            </div>
            <div class="input-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" placeholder="Alamat" autocomplete="off" required value="<?= $currentMember['alamat'] ?>">
            </div>
            <?php 
                $jenisKelamin = $currentMember['jenis_kelamin'];
            ?>
            <div class="input-group">
                <label for="">Jenis Kelamin</label>
                <div class="radio-group">
                    <input type="radio" name="jenis_kelamin" id="status-active" value="L" <?php echo ($jenisKelamin == 'L') ? 'checked' : ''; ?>>
                    <label for="status-active">Laki - Laki</label>
                    <input type="radio" name="jenis_kelamin" id="status-inactive" value="P" <?php echo ($jenisKelamin == 'P') ? 'checked' : ''; ?>>
                    <label for="status-inactive">Perempuan</label>
                </div>
            </div>


            <div class="input-group">
                <label for="telpon">Nomor Telpon</label>
                <input type="text" name="tlp" id="tlp" pattern="^\d{8,12}$" autocomplete="off" placeholder="Nomor Telpon" required value="<?= $currentMember['tlp'] ?>">
            </div>

            <button class="submit-btn" type="submit" form="form-login" name="submit" value="submit">EDIT MEMBER</button>

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
    fm::displayPopMessagesByContext('login', 'bottom-right', 3000);
    ?>
    <script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageCloseDelay.js"></script>
</body>

</html>