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
    <title>Outlet | FLaundry</title>
</head>
<?php includeFile("$base/panel/inc/sidebar.php") ?>
<?php includeFile("$base/panel/inc/navbar.php") ?>

<div class="container">
    <div class="content-box">
        <div class="title">
            <div class="title-text-box">
                <h1 class="title-text">Daftar Paket</h1>
                <h1 class="title-text-description">Total <?= count($data['pakets']) ?> Paket</h1>
            </div>
            <div class="title-date">
                <p class="title-date-text">Selasa, 30 Januari 2024 · 11:12 PM</p>
            </div>
        </div>
        <span class='divider'></span>
        <div class="add-btn-wrapper">
            <a href="<?= routeTo("/panel/paket/add/$sessionIdOutlet") ?>" class="add-btn add-outlet-btn">Tambah Paket</a>
        </div>
        <?php
        $grouped_array = array();

        foreach ($data['pakets'] as $item) {
            $grouped_array[$item['nama']][] = $item;
        }

        foreach ($grouped_array as $key => $value) {
            echo "<h2 class='custom-title'>$key</h2>";
            echo "<table class='data-table'>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th class='width-large'>NAMA OUTLET</th>";
            echo "<th class='width-large'>JENIS</th>";
            echo "<th class='width-large'>NAMA PAKET</th>";
            echo "<th class='width-medium'>Harga</th>";
            echo "<th class='width-medium'>Actions</th>";
            echo "</tr>";
            foreach ($value as $paket) {
                $currentRoute = routeTo("/panel");
                $harga = $paket['harga'];
                $numf = new NumberFormatter("en", NumberFormatter::CURRENCY);
                $numf->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
                $formattedNumber = $numf->formatCurrency($harga, 'IDR');
                $harga = preg_replace('/IDR/', 'Rp', $formattedNumber);
                $jenis = _::map(explode('_', $paket['jenis'] . "_"), function ($item) {
                    return ucfirst($item);
                });
                $jenis = implode(' ', $jenis);
                echo "
                        <tr>
                            <td>{$paket['id']}</td>
                            <td>{$paket['nama']}</td>
                            <td>{$jenis}</td>
                            <td>{$paket['nama_paket']}</td>
                            <td>{$harga}</td>
                            <td>
                                <a href='$currentRoute/paket/edit/{$paket["id"]}/{$paket["id_outlet"]}'>EDIT</a>
                                <span style='pointer-events: none; color: rgba(0, 0, 0, 0.1)'>•</span>
                                <a href='$currentRoute/paket/delete/{$paket["id"]}/{$paket["id_outlet"]}'>DELETE</a>
                            </td>
                        </tr>
                        ";
            }
            echo "</table>";
            echo "
                <span class='spacer'></span>
                <span class='divider-light'></span>
            ";
        }
        ?>

    </div>
</div>
<?php
fm::displayPopMessagesByContext('paket_message', 'bottom-right');
?>
<script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageClose.js"></script>