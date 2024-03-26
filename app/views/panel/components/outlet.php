<?php

use App\Services\FlashMessage as fm;

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
    <title>Outlet | FLaundry</title>
</head>
<?php 
    $tableColumns = $data['tableColumns'];
?>
<?php includeFile("$base/panel/inc/sidebar.php") ?>
<?php includeFile("$base/panel/inc/navbar.php", compact('tableColumns')) ?>

<div class="container">
    <div class="content-box">
        <div class="title">
            <div class="title-text-box">
                <h1 class="title-text">Daftar Outlet</h1>
                <h1 class="title-text-description">Total <?= count($data['outlets']) ?> outlet</h1>
            </div>
            <div class="title-date">
                <p class="title-date-text"></p>
            </div>
        </div>
        <span class="divider"></span>
        <div class="add-btn-wrapper">
            <a href="<?= routeTo("/panel/outlet/add") ?>" class="add-btn add-outlet-btn">Tambah Outlet</a>
        </div>
        <table class="data-table">
                <tr>
                    <th>#</th>
                    <th class="width-large">NAMA OUTLET</th>
                    <th class="width-large">ALAMAT</th>
                    <th class="width-large">TELPON</th>
                    <th class="width-medium">Actions</th>
                </tr>

                <?php
                    $no = 1;
                    foreach($data['outlets'] as $outlet) {
                        $currentRoute = routeTo("/panel");
                        echo "
                        <tr>
                            <td>{$no}</td>
                            <td>{$outlet['nama']}</td>
                            <td>{$outlet['alamat']}</td>
                            <td>{$outlet['tlp']}</td>
                            <td>
                                <a href='$currentRoute/outlet/edit/{$outlet["id"]}'>EDIT</a>
                                <a href='$currentRoute/outlet/delete/{$outlet["id"]}'>DELETE</a>
                            </td>
                        </tr>
                        ";
                        $no++;
                    }
                ?>

                <!-- <tr>
                    <td align="center">1</td>
                    <td>Testing</td>
                    <td>Ja</td>
                    <td>Jalan batu alam</td>
                    <td>
                        <a href="">EDIT</a>
                        <a href="">DELETE</a>
                    </td>
                </tr> -->

        </table>
    </div>
</div>
<?php
fm::displayPopMessagesByContext('outlet_message', 'bottom-right');
?>
<script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageClose.js"></script>
