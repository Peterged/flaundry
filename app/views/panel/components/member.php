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

<?php
    $length = 0;
    if(is_array($data['members'])) {
        $length = count($data['members']);
    }
?>

<div class="container">
    <div class="content-box">
        <div class="title">
            <div class="title-text-box">
                <h1 class="title-text">Daftar Member</h1>
                <h1 class="title-text-description">Total <?= $length ?> Member</h1>
            </div>
            <div class="title-date">
                <p class="title-date-text">Selasa, 30 Januari 2024 · 11:12 PM</p>
            </div>
        </div>
        <span class='divider'></span>
        <div class="add-btn-wrapper">
            <a href="<?= routeTo("/panel/member/add") ?>" class="add-btn add-outlet-btn">Tambah Member</a>
        </div>
        <table class="data-table">
                <tr>
                    <th>ID</th>
                    <th class="width-large">NAMA MEMBER</th>
                    <th class="width-large">ALAMAT</th>
                    <th class="width-large">JENIS KELAMIN</th>
                    <th class="width-large">NOMOR TELPON</th>
                    <th class="width-medium">ACTIONS</th>
                </tr>

                <?php
                    foreach($data['members'] as $member) {
                        $currentRoute = routeTo("/panel");
                        $jenisKelamin = $member['jenis_kelamin'] == "L" ? "Laki-laki" : "Perempuan";
                        echo "
                        <tr>
                            <td>{$member['id']}</td>
                            <td>{$member['nama']}</td>
                            <td>{$member['alamat']}</td>
                            <td>{$jenisKelamin}</td>
                            <td>{$member['tlp']}</td>
                            <td>
                                <a href='$currentRoute/member/edit/{$member["id"]}'>EDIT</a>
                                <a href='$currentRoute/member/delete/{$member["id"]}'>DELETE</a>
                            </td>
                        </tr>
                        ";
                    }
                ?>
        </table>

    </div>
</div>
<?php
fm::displayPopMessagesByContext('member_message', 'bottom-right');
?>
<script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageClose.js"></script>