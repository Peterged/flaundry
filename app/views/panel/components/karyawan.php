<?php

use App\Services\FlashMessage as fm;
use App\Utils\MyLodash as _;


$sessionIdOutlet = $_SESSION['id_outlet'];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="<?= PROJECT_ROOT ?>/public/images/flaundry-logo-icon.png" type="image/png" />
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/global.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/sidebar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/navbar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/card.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/table.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/services/flashMessage.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/components/outlet.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Data Karyawan | FLaundry</title>
</head>
<?php includeFile("$base/panel/inc/sidebar.php") ?>
<?php includeFile("$base/panel/inc/navbar.php") ?>

<div class="container">
    <div class="content-box">
        <div class="title">
            <div class="title-text-box">
                <h1 class="title-text">Daftar Karyawan</h1>
                <h1 class="title-text-description">Total <?= count($data['karyawans']) ?> Karyawan</h1>
            </div>
            <div class="title-date">
                <p class="title-date-text"></p>
            </div>
        </div>
        <span class='divider'></span>
        <div class="add-btn-wrapper">
            <a href="<?= routeTo("/panel/karyawan/add") ?>" class="add-btn add-outlet-btn">Tambah Karyawan</a>
        </div>
        <table class="data-table">
                <tr>
                    <th>ID</th>
                    <th class="width-large">NAMA OUTLET</th>
                    <th class="width-large">NAMA KARYAWAN</th>
                    <th class="width-large">USERNAME</th>
                    <th class="width-medium">ROLE</th>
                    <th class="width-medium">ACTIONS</th>
                </tr>

                <?php
                    foreach($data['karyawans'] as $karyawan) {
                        $currentRoute = routeTo("/panel");
                            
                        echo "
                        <tr>
                            <td>{$karyawan['id']}</td>
                            <td>{$karyawan['nama_outlet']}</td>
                            <td>{$karyawan['nama']}</td>
                            <td>{$karyawan['username']}</td>
                            <td>{$karyawan['role']}</td>
                            <td>
                                <a href='$currentRoute/karyawan/edit/{$karyawan["id"]}'>EDIT</a>
                                <a href='$currentRoute/karyawan/delete/{$karyawan["id"]}'>DELETE</a>
                            </td>
                        </tr>
                        ";
                    }
                ?>
        </table>

    </div>
</div>
<?php
fm::displayPopMessagesByContext('karyawan_message', 'bottom-right');
?>
<script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageClose.js"></script>