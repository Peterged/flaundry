<?php

use App\Services\FlashMessage as fm;

function formatRupiah(int | float $angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}

function handleStatusProgressValue(string $value)
{
    $progress = 0;
    switch ($value) {
        case 'baru':
            $progress = 0;
            break;
        case 'proses':
            $progress = 50;
            break;
        case 'selesai':
            $progress = 100;
            break;
        case 'diambil':
            $progress = 101;
            break;
        default:
            $progress = 0;
            break;
    }
    return $progress;
}

$idtransaksi = $data['param_id_transaksi'];
$model = $data['model'];

$query = "
SELECT tb_transaksi.*, tb_member.nama AS nama_pelanggan, tb_member.tlp AS tlp_pelanggan, tb_member.alamat AS alamat_pelanggan, tb_outlet.nama AS nama_outlet, tb_user.nama AS nama_karyawan
FROM tb_transaksi
INNER JOIN tb_member ON tb_transaksi.id_member = tb_member.id
INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id
INNER JOIN tb_user ON tb_transaksi.id_user = tb_user.id WHERE tb_transaksi.id = '$idtransaksi'";

$data_transaksi = $model->query($query);
$data_transaksi = $data_transaksi->getData();
if (!empty($data_transaksi)) {
    $data_transaksi = $data_transaksi[0];
} else {
    fm::addMessage([
        'type' => 'error',
        'title' => 'Tidak Ditemukan',
        'description' => 'Data transaksi tidak ditemukan',
        'context' => 'transaksi_message'
    ]);
    unset($_SESSION['idtransaksi']);
    $location = routeTo("/panel/transaksi");
    header("Location: $location");
}

try {
    if (isset($_POST['pilih_paket'])) {
        $qty = $_POST['qty'];
        $nama_paket = $_POST['nama_paket'];

        $row = $model->query("SELECT * FROM tb_paket WHERE nama_paket = '$nama_paket'");
        $row_paket = $row->getData() ? $row->getData()[0] : [];
        // $row_paket = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM tb_paket WHERE nama_paket = '$nama_paket'"));

        $harga_paket = $row_paket['harga'];
        $total_harga = $qty * $harga_paket;
        $id_paket = $row_paket['id'];
        $keterangan = $_POST['keterangan'];

        $model->query("INSERT INTO tb_detail_transaksi VALUES(NULL, '$idtransaksi', '$id_paket', '$qty', '$keterangan', '$harga_paket', '$total_harga')");
        // mysqli_query($connect, "INSERT INTO tb_detail_transaksi VALUES(NULL, '$idtransaksi', '$id_paket', '$qty', '$keterangan', '$harga_paket', '$total_harga')");
        header("Location: " . $_SERVER['REQUEST_URI']);
    }

    if (isset($_POST['bayar_sekarang'])) {
        $tgl_bayar = date('Y-m-d H:i:s');
        $model->query("UPDATE tb_transaksi SET dibayar = 'dibayar', tgl_bayar = '$tgl_bayar' WHERE id = '$idtransaksi'");
        // mysqli_query($connect, "UPDATE tb_transaksi SET dibayar = 'dibayar', tgl_bayar = '$tgl_bayar' WHERE id = '$idtransaksi'");
        header("Location: " . $_SERVER['REQUEST_URI']);
    }

    if ($data_transaksi['dibayar'] == 'belum_dibayar') {
        $pembayaran = 'UNPAID';
        $warna = '#FFBDBC';
        $warna_total = '#0077b6';
    } else {
        $pembayaran = 'PAYED';
        $warna = '#C1FFBC';
        $warna_total = '#38b000';
    }

    if (isset($_POST['tombol_biaya_tambahan'])) {
        $biaya_tambahan = $_POST['biaya_tambahan'];
        $model->query("UPDATE tb_transaksi SET biaya_tambahan = '$biaya_tambahan' WHERE id = '$idtransaksi'");
        // mysqli_query($connect, "UPDATE tb_transaksi SET biaya_tambahan = '$biaya_tambahan' WHERE id = '$idtransaksi'");
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
} catch (\Exception $e) {
    trigger_error($e->getMessage() . " at " . $e->getFile() . " on line " . $e->getLine(), E_USER_ERROR);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= PROJECT_ROOT ?>/public/images/flaundry-logo-icon.png" type="image/png" />
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/global.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/sidebar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/navbar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/card.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/table.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/services/flashMessage.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/components/outlet.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/transaksi/detail-transaksi.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/form/formMinim.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/import/choices.min.css">
    <title>Detail Transaksi | FLaundry</title>
</head>

<body>
    <?php includeFile("$base/panel/inc/sidebar.php");
    $progress = handleStatusProgressValue($data_transaksi['status']);
    $previousProgressValue = isset($_SESSION['previousProgressValue']) ? $_SESSION['previousProgressValue'] : 0;
    $query = "
    SELECT tb_transaksi.*, tb_member.nama AS nama_pelanggan, tb_member.tlp AS tlp_pelanggan, tb_member.alamat AS alamat_pelanggan, tb_outlet.nama AS nama_outlet, tb_user.nama AS nama_karyawan,
    FROM tb_transaksi
    INNER JOIN tb_member ON tb_transaksi.id_member = tb_member.id
    INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id
    INNER JOIN tb_user ON tb_transaksi.id_user = tb_user.id WHERE tb_transaksi.id = '$idtransaksi'";
    ?>
    <div class="container" style="flex-direction: column;">
        <div class="progress-container noprint">
            <div class="progress-bar" id="myProgressBar" aria-valueafter="<?= $progress ?>" style="width: <?= $previousProgressValue ?>%"></div>
        </div>

        <?php
        $_SESSION['previousProgressValue'] = $progress;
        ?>

        <div class="box-detail-transaction print-container">
            <div class="box-detail-container print-container">
                <div class="box-detail">
                    <div class="box-form" style="background: <?= $warna ?>;">
                        <div class="box-title">
                            <span class="title-form"><?= $pembayaran ?></span>
                        </div>
                        <div class="box-form-table" style="width: 100%; ">
                            <table border="0" cellspacing="0">
                                <tr>
                                    <td>Invoice</td>
                                    <!-- <td>:</td> -->
                                    <td><?= $data_transaksi['kode_invoice'] ?></td>
                                </tr>
                                <tr>
                                    <td>Pelanggan</td>
                                    <!-- <td>:</td> -->
                                    <td><?= $data_transaksi['nama_pelanggan'] ?></td>
                                </tr>
                                <tr>
                                    <td>Telpon</td>
                                    <!-- <td>:</td> -->
                                    <td><?= $data_transaksi['tlp_pelanggan'] ?></td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <!-- <td>:</td> -->
                                    <td><?= $data_transaksi['alamat_pelanggan'] ?></td>
                                </tr>
                                <tr>
                                    <td>Karyawan</td>
                                    <!-- <td>:</td> -->
                                    <td><?= ucfirst($data_transaksi['nama_karyawan']) ?></td>
                                </tr>
                                <tr>
                                    <td>Batas Waktu</td>
                                    <!-- <td>:</td> -->
                                    <td>
                                        <?php
                                        $date = new DateTime($data_transaksi['batas_waktu']);
                                        $dateFormat = $date->format("d-m-Y");
                                        $hourAndMinute = $date->format('H:i');

                                        echo "Date : " . $dateFormat;
                                        echo "<br>";
                                        echo "Time : " . $hourAndMinute;
                                        ?>
                                    </td>
                                </tr>
                                <tr class="noprint">
                                    <td>Status</td>
                                    <!-- <td>:</td> -->
                                    <td>
                                        <?php
                                        $statusChangeRoute = routeTo("/panel/transaksi_status_handler");

                                        ?>
                                            <select <?php
                                                    if ($_SESSION['role'] == 'owner') {
                                                        echo "disabled";
                                                    } ?> onchange="pilihStatus(this.options[this.selectedIndex].value, '<?= $idtransaksi ?>')">
                                                    <?php
                                                        $statusArray = ['baru', 'proses', 'selesai', 'diambil'];
                                                        foreach($statusArray as $statusItem) {
                                                            $selected = $statusItem == $data_transaksi['status'] ? "selected" : "";
                                                            echo "<option value='$statusItem' $selected>$statusItem</option>";
                                                        }
                                                    ?>
                                            </select>

                                        <script>
                                            function pilihStatus(value, id) {
                                                const url = "<?= $statusChangeRoute ?>";
                                                window.location.href = `${url}/${value}/${id}`;
                                            }
                                        </script>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php
                    if ($data_transaksi['dibayar'] == 'belum_dibayar') {
                    ?>
                        <div class="box-add" <?php if ($_SESSION['role'] == 'owner') {
                                                    echo "style='display: none;'";
                                                } ?>>
                            <form action="<?= routeTo("/panel/detail-transaksi/tambah_paket/$idtransaksi") ?>" method="post" id="form_tambah_paket">
                                <div class="box-add-form">
                                    <div class="input-group">
                                        <label for="choices-select">Nama Paket</label>
                                        <select name="nama_paket" id="choices-select">
                                            <?php
                                            $id_outlet = $data_transaksi['id_outlet'];
                                            $query_paket = $model->query("SELECT nama_paket FROM tb_paket WHERE id_outlet = '$id_outlet'");
                                            $query_paket = $query_paket->getData();
                                            foreach ($query_paket as $paket) {
                                            ?>
                                                <option value="<?= $paket['nama_paket'] ?>"></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <input type="number" name="qty" id="qty" placeholder="Jumlah" autocomplete="off">
                                    </div>
                                    <div class="input-group">
                                        <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan" autocomplete="off">
                                    </div>
                                    <button class="submit-btn" type="submit" form="form_tambah_paket" name="pilih_paket" value="submit">TAMBAH PAKET</button>
                                </div>
                            </form>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="box-status">
                    <table>
                        <tr class="box-status-table-header">
                            <th>Paket</th>
                            <th>Keterangan</th>
                            <th class="width-small-max width-small">QTY</th>
                            <th>Harga</th>
                            <th class="width-medium">Total</th>
                        </tr>
                        <?php

                        $result_detail = $model->query("SELECT * FROM tb_detail_transaksi WHERE id_transaksi = '$idtransaksi'");
                        $result_detail = $result_detail->getData() ? $result_detail->getData() : [];

                        foreach ($result_detail as $detail) {
                        ?>
                            <tr style="text-align: center;">
                                <td>
                                    <?php
                                    $idpaket = $detail['id_paket'];
                                    $paket = $model->query("SELECT nama_paket, jenis, harga FROM tb_paket WHERE id = '$idpaket'");
                                    $paket = $paket->getData() ? $paket->getData()[0] : [];
                                    $detail['harga_paket'] = $detail['total_harga'] / $detail['qty'];
                                    $keterangan = $detail['keterangan'] ? $detail['keterangan'] : "-";
                                    $jenis = str_replace("_", " ", $paket['jenis']);
                                    $jenis = ucwords($jenis);
                                    ?>
                                    <p class="paket-title"><?= $paket['nama_paket'] ?></p>
                                    <p class="paket-type"><?= $jenis ?></p>
                                </td>
                                <td>
                                    <p class="paket-keterangan"><?= $keterangan ?></p>
                                </td>
                                <td><?= $detail['qty'] ?></td>
                                <td><?= formatRupiah($detail['harga_paket']) ?></td>
                                <td>
                                    <?php
                                    $prosesDeletePackageRoute = routeTo("/panel/detail-transaksi/delete-paket");
                                    ?>
                                    <form action="<?= $prosesDeletePackageRoute ?>" method="get" id="delete-paket-form">
                                        <input type="text" name="id" id="" value="<?= $detail['id'] ?>" hidden>
                                        <?php
                                        if ($data_transaksi['dibayar'] == 'belum_dibayar') {
                                        ?>
                                            <p class="total-harga-display-text"><?= formatRupiah($detail['total_harga']) ?></p>
                                            <button type="submit" id="delete-paket" class="delete-paket-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path d="M3.33331 5.83333H16.6666M8.33331 9.16667V14.1667M11.6666 9.16667V14.1667M4.16665 5.83333L4.99998 15.8333C4.99998 16.2754 5.17557 16.6993 5.48813 17.0118C5.8007 17.3244 6.22462 17.5 6.66665 17.5H13.3333C13.7753 17.5 14.1993 17.3244 14.5118 17.0118C14.8244 16.6993 15 16.2754 15 15.8333L15.8333 5.83333M7.49998 5.83333V3.33333C7.49998 3.11232 7.58778 2.90036 7.74406 2.74408C7.90034 2.5878 8.1123 2.5 8.33331 2.5H11.6666C11.8877 2.5 12.0996 2.5878 12.2559 2.74408C12.4122 2.90036 12.5 3.11232 12.5 3.33333V5.83333" stroke="#121212" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        <?php
                                        } else {
                                        ?>
                                            <span style="color: #e5383b; font-weight: bold;"><?= formatRupiah($detail['total_harga']) ?></span>
                                        <?php
                                        }
                                        ?>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                        <?php
                        $grand_total = $model->queryReturnStatement("SELECT SUM(total_harga) as total_harga FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id WHERE id_transaksi = '$idtransaksi'", null, false);
                        if (!$grand_total->getSuccess()) {
                            $grand_total = ['0' => 0];
                        } else {
                            $grand_total = $grand_total->getData()->fetch(\PDO::FETCH_ASSOC);
                        }

                        if (!$grand_total['total_harga'] == '0') {
                        ?>
                            <tr>
                                <td colspan="4" style="text-align: right; border-right: 1px solid #e6e5e5; font-weight: bold;">Pajak</td>
                                <td style="text-align: center; font-weight: bold;">
                                    <?php
                                    echo "0.75%";
                                    echo "<br>";
                                    $pajak = $grand_total['total_harga'] * $data_transaksi['pajak'];
                                    echo formatRupiah($pajak);
                                    ?>
                                </td>
                            </tr>
                            <?php
                            if ($data_transaksi['biaya_tambahan'] != 0) {
                            ?>
                                <tr>
                                    <td colspan="4" style="text-align: right; border-right: 1px solid #e6e5e5; font-weight: bold;">Biaya Tambahan</td>
                                    <td style="text-align: center; font-weight: bold;"><?= formatRupiah($data_transaksi['biaya_tambahan']) ?></td>
                                </tr>
                            <?php
                            }
                            if ($data_transaksi['diskon'] != 0) {
                            ?>
                                <tr>
                                    <td colspan="4" style="text-align: right; border-right: 1px solid #e6e5e5; font-weight: bold;">Discount</td>
                                    <td style="text-align: center; font-weight: bold;">
                                        <?php
                                        echo "10%";
                                        echo "<br>";
                                        $diskon = $grand_total['total_harga'] * $data_transaksi['diskon'];
                                        echo formatRupiah($diskon);
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            } else {
                                $diskon = 0;
                            }
                            ?>
                            <tr>
                                <td colspan="4" style="text-align: right; border-right: 1px solid #e6e5e5; font-weight: bold;">Total Keseluruhan</td>
                                <td style="text-align: center; font-weight: bold; color: <?= $warna_total ?>;">
                                    <?php
                                    ini_set('display_errors', 0);
                                    $total_keseluruhan = ($grand_total['total_harga'] + $data_transaksi['biaya_tambahan'] + $pajak) - $diskon;
                                    echo formatRupiah($total_keseluruhan);
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                    <div class="box-biaya-tambahan-container noprint">
                        <div class="box-biaya-tambahan">
                            <form action="<?= routeTo("/panel/detail-transaksi/biaya_tambahan/$idtransaksi") ?>" method="post">
                                <div class="box-input-biaya-tambahan" style="display:
                                <?php if ($data_transaksi['dibayar'] == 'dibayar') {
                                    echo "none";
                                } ?>;">
                                    <div class="input-group">
                                        <input type="number" name="biaya_tambahan" id="" placeholder="Biaya Tambahan" autocomplete="off" <?php if ($data_transaksi['dibayar'] == 'dibayar') {
                                                                                                                                                echo "hidden";
                                                                                                                                            } ?>>
                                    </div>

                                    <button class="submit-btn" type="submit" value="Add" name="tombol_biaya_tambahan" <?php if ($data_transaksi['dibayar'] == 'dibayar') {
                                                                                                                            echo "hidden";
                                                                                                                        } ?>>Set</button>
                                </div>

                            </form>
                        </div>
                        <div class="box-button-pay noprint">
                            <?php
                            $detailTransaksiBayarRoute = routeTo("/panel/detail-transaksi/bayar/$idtransaksi");
                            ?>
                            <form action="<?= $detailTransaksiBayarRoute ?>" method="post">
                                
                                    <button type="button" name="bayar_sekarang" onclick="window.print()">
                                        Print
                                    </button>
                                <?php
                                
                                if ($data_transaksi['dibayar'] !== 'dibayar' || $_SESSION['role'] == 'owner') {
                                ?>
                                <input type="submit" value="Bayar" name="bayar_sekarang" onclick="return confirm('Really want to pay?')" >
                                <?php
                                }
                                ?>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <?php
        fm::displayPopMessagesByContext('detail_transaksi_message', 'bottom-right', 6000);
        ?>
        <script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageCloseDelay.js"></script>
        <script src="<?= PROJECT_ROOT ?>/public/js/progressbarAnimation.js"></script>
        <script type="module" defer src="<?= PROJECT_ROOT ?>/public/js/choicesInit.js"></script>
    </div>
</body>

</html>
