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
    <title>Tambah Paket</title>
</head>

<body>
    <div class="container">
        <form method="post" class="wrapper" id="form-login">
            <h1 class="title">Tambah Member</h1>
            <div class="input-group">
                <label for="nama">Nama Member</label> <input autofocus type="text" name="nama" id="nama" placeholder="Nama Member" autocomplete="off" required>
            </div>
            <div class="input-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" placeholder="Alamat" autocomplete="off" required>
            </div>
            
            <div class="input-group">
                <label for="jenis_paket">Jenis Kelamin</label>
                <div class="radio-group">
                    
                    <?php 
                        
                        $jenisOptions = ['L', 'P'];
                        $no = 0;
                        foreach ($jenisOptions as $jenis) {
                            $jenisKelaminDisplay = $jenis == 'L' ? "Laki-laki" : "Perempuan";
                            $jenisKelaminDisplayLowercase = strtolower($jenisKelaminDisplay);
                            echo "<input type='radio' name='jenis_kelamin' id='{$jenisKelaminDisplayLowercase}' value='{$jenis}'>";
                            echo "<label for='{$jenisKelaminDisplayLowercase}'>{$jenisKelaminDisplay}</label>";
                            $no++;
                        }
                    ?>
                </div>
            </div>
            <div class="input-group">
                <label for="telpon">Nomor Telepon</label>
                <input type="text" name="tlp" id="telpon" autocomplete="off" placeholder="Nomor Telepon" required>
            </div>
            
            <button class="submit-btn" type="submit" form="form-login" name="submit" value="submit">TAMBAH MEMBER</button>
            <div class="goback-btn">
                <button onclick="window.location.href='<?= routeTo("/panel/member") ?>'" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                        <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                    </svg>
                    Back
                </button>
            </div>
        </form>
    </div>

    <?php
    fm::displayPopMessagesByContext('member_message', 'bottom-right', 3000);
    ?>
    <script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageCloseDelay.js"></script>
</body>

</html>