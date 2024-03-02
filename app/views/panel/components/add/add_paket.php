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
            <h1 class="title small-margin">Tambah Paket</h1>
            <p class="small-description margin-bottom-lg"><?= $data['nama_outlet'] ?></p>
            <div class="input-group">
                <label for="nama">Nama Paket</label> <input autofocus type="text" name="nama" id="nama" placeholder="Nama Paket" autocomplete="off" required>
            </div>
            <div class="input-group">
                <label for="jenis_paket">Jenis Paket</label>
                <select name="jenis_paket" id="jenis_paket" required>
                    <option value="" disabled selected hidden> -- Pilih Paket -- </option>
                    <?php 
                        
                        $jenisOptions = ['kiloan', 'selimut', 'bed_cover', 'kaos', 'lain'];
                        foreach ($jenisOptions as $jenis) {
                            $jenisDisplay = ucfirst($jenis);
                            $jenisDisplay = preg_replace('/[_-]+/', ' ', $jenisDisplay);
                            echo "<option value=\"$jenis\">$jenisDisplay</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label for="harga">Harga</label>
                <input type="number" name="harga" id="harga" pattern="^[1-9][0-9]*$" autocomplete="off" placeholder="Harga" required>
            </div>
            
            <button class="submit-btn" type="submit" form="form-login" name="submit" value="submit">TAMBAH PAKET</button>
            <div class="goback-btn">
                <button onclick="window.location.href='<?= routeTo("/panel/paket") ?>'" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                        <path d="M21 11H6.414l5.293-5.293-1.414-1.414L2.586 12l7.707 7.707 1.414-1.414L6.414 13H21z"></path>
                    </svg>
                    Back
                </button>
            </div>
        </form>
    </div>

    <?php
    fm::displayPopMessagesByContext('paket_message', 'bottom-right', 10000);
    ?>
    <script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageCloseDelay.js"></script>
</body>

</html>