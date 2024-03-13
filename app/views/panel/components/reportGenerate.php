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
$startDate = $data['startDate'];
$endDate = $data['endDate'];
$startAndEndDateString = $startDate && $endDate ? "$startDate sampai $endDate" : '';

?>

<div class="container">
    <div class="content-box">
        <div class="title">
            <div class="title-text-box">
                <h1 class="title-text">Generate Report</h1>
                <h1 class="title-text-description">Total <?= count($data['transaksis']) ?> transaksi ditemukan</h1>
            </div>
            <div class="title-date">
                <p class="title-date-text" id="title-date-text"></p>
            </div>

            <form id="filter-date-report-generation" action="<?= routeTo("/panel/report/generate") ?>" class="table-filter-box">
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
                </div>
            </form>

            <!-- <h1 class="title-text-description"></h1> -->
        </div>
        <span class='divider'></span>

        <?php
            if(isset($latestTransactionDate) && isset($oldestTransactionDate) && ($latestTransactionDate !== $oldestTransactionDate)) {
        ?>
        <div class="title-text-box-info info-blue" style="gap: 0.5rem">
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

        <table class="data-table">
            <tr>
                <th class="width-small">Kode Invoice</th>
                <th class="width-small">Pelanggan</th>
                <th class="width-medium">Paket</th>
                <th class="width-large">Batas Pengambilan</th>
                <th class="width-small">Status</th>
                <th class="width-medium">Actions</th>
            </tr>

            <?php

            $timezone = Cookie::get('clientTimezone', 'Asia/Makassar');

            foreach ($transaksis as $transaksi) {
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
                if ($sudah_lewat) {
                    $batas_waktu_str = "<p class='data-bold'>Batas waktu sudah lewat</p><small>$humanReadableDate2 | $batas_waktu_tanggal - $batas_waktu_jam</small>";
                } else {
                    $batas_waktu_str = "<p class='data-bold'>$batas_waktu_tanggal<span> - $batas_waktu_jam</span></p><small>$humanReadableDate</small";
                }
                echo "
                        <tr>
                            <td>
                                <p class='data-bold'>{$transaksi['kode_invoice']}</p>
                            </td>
                            <td>{$transaksi['nama_member']}</td>
                            <td>

                    ";

                foreach ($transaksiPakets as $transaksiPaket) {
                    if ($transaksiPaket['id_transaksi'] == $transaksi['id']) {
                        $total_harga = formatRupiah($transaksiPaket['total_harga']);
                        echo "<p class='data-bold'>{$transaksiPaket['nama_paket']}</p>";
                        echo "<small> {$transaksiPaket['qty']}x • $total_harga</small>";
                    }
                }

                echo "
                            </td>
                            <td>
                                $batas_waktu_str
                            </td>
                    ";

            ?>
                <td>
                    <?php

                    echo "<p style='font-weight: bold'>" . ucfirst($transaksi['status']) . "</p>";
                    ?>

                </td>
                <td>
                    <a href='<?= "$currentRoute/detail-transaksi/$transaksi[id]" ?>'>LIHAT DETAIL</a>
                </td>
                </tr>
            <?php
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