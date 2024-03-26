<?php

use App\Services\FlashMessage as fm;
use App\Utils\MyLodash as _;
use App\Libraries\Essentials\Cookie;
use Carbon\Carbon;

function formatRupiah(int | float $angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}
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
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/form/formMinim.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/services/flashMessage.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/components/outlet.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/components/report.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <title>Generate Report | FLaundry</title>
</head>

<?php includeFile("$base/panel/inc/sidebar.php") ?>
<?php includeFile("$base/panel/inc/navbar.php") ?>

<?php
$transaksis = $data['transaksis'] ?? [];
$transaksiPakets = $data['pakets'] ?? [];

$groupedTransaksisBasedOnNamaOutlet = _::groupByMerge($transaksis, 'nama_outlet');

// echo "<pre>";
// print_r($groupedTransaksisBasedOnNamaOutlet);
// echo "</pre>";

$startDate = $data['startDate'];
$endDate = $data['endDate'];
$startAndEndDateString = $startDate && $endDate ? "$startDate sampai $endDate" : '';

?>

<div class="container">
    <div class="content-box">
        <div class="title">
            <div class="title-text-box noprint">
                <h1 class="title-text">Generate Report</h1>
                <h1 class="title-text-description">Total <?= count($data['transaksis']) ?> transaksi ditemukan</h1>
            </div>
            <div class="title-date noprint">
                <p class="title-date-text" id="title-date-text"></p>
            </div>

            <form id="filter-date-report-generation" action="<?= routeTo("/panel/report/generate") ?>" class="table-filter-box noprint">
                <div class="input-group-group">

                    <?php
                    if (count($transaksis)) {
                        try {
                            $timezone = Cookie::get('clientTimezone', 'Asia/Makassar');
                            $oldestTransaction = count($transaksis) ? $transaksis[count($transaksis) - 1] : '';
                            $oldestTransactionDate = date('Y-m-d H:i', strtotime($oldestTransaction['tgl'])) ?? '';
                            $latestTransaction = $transaksis[0];
                            $latestTransactionDate = date('Y-m-d H:i', strtotime($latestTransaction['tgl'])) ?? '';

                            $humanReadableLatestTransaction = Carbon::parse($latestTransactionDate, $timezone)->locale('id')->diffForHumans([
                                'parts' => 2,
                                'join' => ', '
                            ]);
                            $humanReadableOldestTransaction = Carbon::parse($oldestTransactionDate, $timezone)->locale('id')->diffForHumans([
                                'parts' => 2,
                                'join' => ', '
                            ]);
                        } catch (Exception $e) {
                        }
                    }
                    ?>
                    <input type="text" id="filter-date" name="datetimes" class="date-range-input" required />
                    <button class="submit-btn" type="submit" form="filter-date-report-generation" value="submit">FILTER</button>
                    <button onclick="window.print()" class="submit-btn" type="submit" form="filter-date-report-generation" value="submit">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 8V5H8V8H6V3H18V8H16ZM18 12.5C18.2833 12.5 18.521 12.404 18.713 12.212C18.905 12.02 19.0007 11.7827 19 11.5C19 11.2167 18.904 10.9793 18.712 10.788C18.52 10.5967 18.2827 10.5007 18 10.5C17.7167 10.5 17.4793 10.596 17.288 10.788C17.0967 10.98 17.0007 11.2173 17 11.5C17 11.7833 17.096 12.021 17.288 12.213C17.48 12.405 17.7173 12.5007 18 12.5ZM16 19V15H8V19H16ZM18 21H6V17H2V11C2 10.15 2.29167 9.43767 2.875 8.863C3.45833 8.28833 4.16667 8.00067 5 8H19C19.85 8 20.5627 8.28767 21.138 8.863C21.7133 9.43833 22.0007 10.1507 22 11V17H18V21ZM20 15V11C20 10.7167 19.904 10.4793 19.712 10.288C19.52 10.0967 19.2827 10.0007 19 10H5C4.71667 10 4.47933 10.096 4.288 10.288C4.09667 10.48 4.00067 10.7173 4 11V15H6V13H18V15H20Z" fill="white" />
                        </svg>

                    </button>
                </div>
            </form>

            <!-- <h1 class="title-text-description"></h1> -->
        </div>
        <span class='divider noprint'></span>

        <?php
        if (isset($latestTransactionDate) && isset($oldestTransactionDate) && ($latestTransactionDate !== $oldestTransactionDate)) {
        ?>
            <div class="title-text-box-info info-blue noprint" style="gap: 0.5rem">
                <div class="title-text-wrapper-column">
                    <div class="title-text-description">
                        <p class="text-medium font-large">Transaksi Terkini</p>
                    </div>
                    <div class="title-text-description">
                        <p class="text-light font-small"><span class="text-regular"><?= $latestTransaction['kode_invoice'] ?></span> - <?= "$latestTransactionDate" ?></p>
                    </div>
                </div>
                <div class="title-text-wrapper-column">
                    <div class="title-text-description">
                        <p class="text-medium font-large">Transaksi Terlama</p>
                    </div>
                    <div class="title-text-description">
                        <p class="text-light font-small"><span class="text-regular"><?= $oldestTransaction['kode_invoice'] ?></span> - <?= "$oldestTransactionDate" ?></p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

        <?php
        if (count($transaksis)) {
        ?>
            <div class="title-text-box-info info-blue" style="gap: 0.5rem">
                <div class="title-text-wrapper-column">
                    <div class="title-text-description">
                        <p class="text-medium font-large">Produk Terlaris</p>
                    </div>
                    <div class="title-text-description">
                        <ol>
                            <?php 
                            $terlarisCount = 1;
                            foreach (array_slice($data['produkTerlaris'], 0, 5) as $produkTerlarisItem) : ?>

                                
                            <li >
                                <p class="text-light font-small">
                                    <span class="text-regular">
                                        <?= "$terlarisCount. {$produkTerlarisItem['nama_paket']}" ?>
                                    </span> - <?= $produkTerlarisItem['qty'] ?>x
                                    
                                </p>
                            </li>
                            <?php 
                            $terlarisCount++;
                            endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

        <table class="data-table">
            <!-- <tr>
                <th class="width-small">Kode Invoice</th>
                <th class="width-small">Pelanggan</th>
                <th class="width-medium">Paket</th>
                <th class="width-large">Batas Pembayaran</th>
                <th class="width-small">Status</th>
                <th class="width-medium">Actions</th>
            </tr> -->
            <!-- <tr>
                <th class="width-small">Kode Invoice</th>
                <th class="width-small">Pelanggan</th>
                <th class="width-medium">Paket</th>
                <th class="width-large">Batas Pembayaran</th>
                <th class="width-medium noprint">Actions</th>
            </tr> -->

            <?php

            $timezone = Cookie::get('clientTimezone', 'Asia/Makassar');

            foreach ($groupedTransaksisBasedOnNamaOutlet as $nama_outlet => $transaksiGroupArray) {

                echo "<tr><th class='width-large' colspan='2'>$nama_outlet</th></tr>";
                $no = 1;

                foreach ($transaksiGroupArray as $transaksi) {

                    $currentRoute = routeTo("/panel");
                    $batas_waktu = $transaksi['batas_waktu'];

                    $batas_waktu_tanggal = date('d-m-Y', strtotime($batas_waktu));
                    $batas_waktu_jam = date('H:i', strtotime($batas_waktu));

                    $today = Carbon::now($timezone);

                    $humanReadableDate = Carbon::parse($batas_waktu, $timezone)->locale('id')->diffForHumans([
                        'parts' => 2,
                        'join' => ', '
                    ]);

                    $humanReadableDate2 = Carbon::parse($batas_waktu, $timezone)->locale('id')->diffForHumans(null);

                    $sudah_lewat = strtotime($batas_waktu) < strtotime($today);
                    if ($transaksi['dibayar'] == 'dibayar') {
                        $batas_waktu_str = "<p class='data-bold'>Lunas</p>";
                    } elseif ($sudah_lewat) {
                        $batas_waktu_str = "<p class='data-bold'>Batas waktu sudah lewat</p><small>$humanReadableDate2 | $batas_waktu_tanggal - $batas_waktu_jam</small>";
                    } else {
                        $batas_waktu_str = "<p class='data-bold'>$batas_waktu_tanggal<span> - $batas_waktu_jam</span></p><small>$humanReadableDate</small";
                    }
                    echo "
                        
                        <tr>
                            <td class='width-small'>
                                <p class='data-bold'>{$no}</p>
                            </td>
                            <td>{$transaksi['nama_member']}</td>
                            <td>
                    ";


                    $total_harga = 0;
                    foreach ($transaksiPakets as $transaksiPaket) {
                        if ($transaksiPaket['id_transaksi'] == $transaksi['id']) {
                            $total_harga += $transaksiPaket['total_harga'];
                        }
                    }

                    $occurrences = [];
                    global $occurences;

                    foreach ($transaksiPakets as $transaksiPaket) {
                        if ($transaksi['id'] == $transaksiPaket['id_transaksi']) {
                            $currentIdStr = $transaksi['id'];
                            $currentPackageName = $transaksiPaket['nama_paket'];
                            if (isset($occurences[$currentIdStr][$currentPackageName])) {
                                $occurences[$currentIdStr][$currentPackageName]['qty'] += $transaksiPaket['qty'];
                            } else {
                                $occurences[$currentIdStr][$transaksiPaket['nama_paket']] = $transaksiPaket;
                            }
                        }
                    }

                    foreach ($occurences as $transaksiPaket) {
                        foreach ($transaksiPaket as $key => $value) {
                            if ($value['id_transaksi'] == $transaksi['id']) {
                                echo "<p>{$value['qty']}x<span style='color: rgba(0, 0, 0, 0.15)'> • </span>{$key}</p>";
                            }
                        }
                    }
                    $total_harga = formatRupiah($total_harga);
                    echo "
                            
                            </td>
                            <td>
                            <h3 class='data-bold'>$total_harga</h3>
                            </td>
                    ";
                    $no++;



            ?>
                    <td class="noprint">
                        <a href='<?= "$currentRoute/detail-transaksi/$transaksi[id]" ?>'>LIHAT DETAIL</a>
                    </td>
                    </tr>
            <?php
                }
            }
            ?>

        </table>

    </div>
</div>
<?php
fm::displayPopMessagesByContext('report_message', 'bottom-right');
?>
<script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageClose.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script defer>
    $(function() {
        $('input[name="datetimes"]').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                format: 'M/DD hh:mm A',
                daysOfWeek: [
                    "Sen",
                    "Sel",
                    "Rab",
                    "Kam",
                    "Jum",
                    "Sab",
                    "Min"
                ],
                monthNames: [
                    "Januari",
                    "Februari",
                    "Maret",
                    "April",
                    "Mei",
                    "Juni",
                    "Juli",
                    "Augustus",
                    "September",
                    "Oktober",
                    "Nopember",
                    "Desember"
                ],
            }
        });
    });
</script>