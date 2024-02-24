<?php

$idtransaksi = $data['param_id_transaksi'];
$model = $data['model'];

$data_transaksi = $model->query("SELECT * FROM tb_transaksi INNER JOIN tb_member ON tb_transaksi.id_member = tb_member.id INNER JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id INNER JOIN tb_user ON tb_transaksi.id_user = tb_user.id WHERE tb_transaksi.id = '$idtransaksi'");
$data_transaksi = $data_transaksi->getData();

print_r($data_transaksi);
try {
    if (@$_POST['pilih_paket']) {
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

    if (@$_POST['bayar_sekarang']) {
        $tgl_bayar = date('Y-m-d H:i:s');
        $model->query("UPDATE tb_transaksi SET dibayar = 'dibayar', tgl_bayar = '$tgl_bayar' WHERE id = '$idtransaksi'");
        // mysqli_query($connect, "UPDATE tb_transaksi SET dibayar = 'dibayar', tgl_bayar = '$tgl_bayar' WHERE id = '$idtransaksi'");
        header("Location: " . $_SERVER['REQUEST_URI']);
    }

    if ($data_transaksi['dibayar'] == 'belum_dibayar') {
        $pembayaran = 'UNPAID';
        $warna = 'linear-gradient(to bottom right, #0077b6, #0096c7)';
        $warna_total = '#0077b6';
    } else {
        $pembayaran = 'PAYED';
        $warna = 'linear-gradient(to bottom right, #008000, #38b000)';
        $warna_total = '#38b000';
    }

    if (@$_POST['tombol_biaya_tambahan']) {
        $biaya_tambahan = $_POST['biaya_tambahan'];
        $model->query("UPDATE tb_transaksi SET biaya_tambahan = '$biaya_tambahan' WHERE id = '$idtransaksi'");
        // mysqli_query($connect, "UPDATE tb_transaksi SET biaya_tambahan = '$biaya_tambahan' WHERE id = '$idtransaksi'");
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
} catch (\Exception $e) {
    echo $e->getMessage();
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
    <title>Detail Transaksi | FLaundry</title>
</head>

<body>
    <?php includeFile("$base/panel/inc/sidebar.php") ?>
    <div class="container">
        <div class="box-detail-transaction print-container">
            <div class="box-detail-container print-container">
                <div class="box-detail">
                    <div class="box-form" style="background: <?= $warna ?>;">
                        <div class="box-title">
                            <span class="title-form"><?= $pembayaran ?></span>
                        </div>
                        <table border="0" cellspacing="0">

                            <tr>
                                <td>Invoice</td>
                                <!-- <td>:</td> -->
                                <td><?= $data_transaksi['kode_invoice'] ?></td>
                            </tr>
                            <tr>
                                <td>Customer</td>
                                <!-- <td>:</td> -->
                                <td><?= $data_transaksi['nama_pelangan'] ?></td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <!-- <td>:</td> -->
                                <td><?= $data_transaksi['tlp_pelanggan'] ?></td>
                            </tr>
                            <tr>
                                <td>Customer Address</td>
                                <!-- <td>:</td> -->
                                <td><?= $data_transaksi['alamat_pelanggan'] ?></td>
                            </tr>
                            <tr>
                                <td>Employee</td>
                                <!-- <td>:</td> -->
                                <td><?= ucfirst($data_transaksi['nama_karyawan']) ?></td>
                            </tr>
                            <tr>
                                <td>Expired</td>
                                <!-- <td>:</td> -->
                                <td>
                                    <?php
                                    $data_transaksi['batas_waktu'];
                                    $pecah_string_tanggal = explode(" ", $data_transaksi['5']);
                                    $pecah_string_hari = explode("-", $pecah_string_tanggal[0]);
                                    $pecah_string_jam = explode(":", $pecah_string_tanggal[1]);

                                    echo "Date : " . $pecah_string_hari[2] . "-" . $pecah_string_hari[1] . "-" . $pecah_string_hari[0];
                                    echo "<br>";
                                    echo "Time : " . $pecah_string_jam[0] . ":" . $pecah_string_jam[1];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <!-- <td>:</td> -->
                                <td>
                                    <select <?php
                                            $statusChangeRoute = routeTo("/panel/transaksi_status_handler");
                                            if ($_SESSION['role'] == 'owner') {
                                                echo "disabled";
                                            } ?> onchange="pilihStatus(this.options[this.selectedIndex].value, <?= $data_transaksi['0'] ?>)">
                                        <option value="baru" <?php if ($data_transaksi['status'] == 'baru') {
                                                                    echo "selected";
                                                                } ?>>
                                            New
                                        </option>
                                        <option value="proses" <?php if ($data_transaksi['status'] == 'proses') {
                                                                    echo "selected";
                                                                } ?>>
                                            Process
                                        </option>
                                        <option value="selesai" <?php if ($data_transaksi['status'] == 'selesai') {
                                                                    echo "selected";
                                                                } ?>>
                                            Done
                                        </option>
                                        <option value="diambil" <?php if ($data_transaksi['status'] == 'diambil') {
                                                                    echo "selected";
                                                                } ?>>
                                            Taked
                                        </option>
                                    </select>
                                    <?php

                                    ?>
                                    <script>
                                        function pilihStatus(url, value, id) {
                                            window.location.href = `${url}/${value}/${id}`;
                                        }
                                    </script>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <?php
                    // if ($data_transaksi['dibayar'] == 'belum_dibayar') {
                    ?>
                    <div class="box-add" <?php if ($_SESSION['role'] == 'owner') {
                                                echo "style='display: none;'";
                                            } ?>>
                        <form action="" method="post">
                            <div class="box-add-form">
                                <div class="input-group">

                                    <input type="text" name="nama_paket" list="nama_paket" id="" placeholder="Package" autocomplete="off" required>
                                    <datalist id="nama_paket">
                                        <?php
                                        // $id_outlet = $data_transaksi['id_outlet'];
                                        // $query_paket = $model->query("SELECT nama_paket FROM tb_paket WHERE id_outlet = '$id_outlet'");
                                        // $query_paket = $query_paket->getData();
                                        // foreach ($query_paket as $paket) {
                                        ?>
                                        <option value="<?= $paket['nama_paket'] ?>"></option>
                                        <?php
                                        // }
                                        ?>
                                    </datalist>
                                </div>

                                <div class="input-group">
                                    <input type="number" name="qty" id="qty" placeholder="Jumlah" autocomplete="off">
                                </div>
                                <div class="input-group">
                                    <input type="text" name="keterangan" id="keterangan" placeholder="Keterangan" autocomplete="off">
                                </div>


                                <button class="submit-btn" type="submit" form="form-login" name="pilih_paket" value="submit">TAMBAH PAKET</button>
                                <!-- <input type="submit" name="pilih_paket" value="Insert Package"> -->

                            </div>
                        </form>
                    </div>
                    <?php
                    // }
                    ?>
                </div>
                <div class="box-status">
                    <table>
                        <tr>
                            <th>Package</th>
                            <th class="width-medium">Description</th>
                            <th>QTY</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>

                        <tr>
                            <td>test</td>

                            <td>test</td>
                            <td>test</td>
                            <td>test</td>
                            <td>test</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right;font-weight: bold;">test</td>
                            <td>test</td>
                        </tr>
                        <?php

                        $result_detail = $model->query("SELECT * FROM tb_detail_transaksi WHERE id_transaksi = '$idtransaksi'");
                        // $result_detail = mysqli_query($connect, "SELECT * FROM tb_detail_transaksi WHERE id_transaksi = '$idtransaksi'");
                        $result_detail = $result_detail->getData() ? $result_detail->getData() : [];
                        foreach ($result_detail as $detail) {
                        ?>
                            <tr style="text-align: center;">
                                <td>
                                    <?php
                                    $idpaket = $detail['id_paket'];
                                    $paket = mysqli_fetch_assoc(mysqli_query($connect, "SELECT nama_paket, jenis, harga FROM tb_paket WHERE id = '$idpaket'"));
                                    echo $paket['nama_paket'];
                                    echo "<br>";
                                    echo $paket['jenis'];
                                    ?>
                                </td>
                                <td><?= $detail['keterangan'] ?></td>
                                <td><?= $detail['qty'] ?></td>
                                <td>Rp.<?= number_format($detail['harga_paket'], 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $prosesDeletePackageRoute = routeTo("/panel/transaksi/delete_package");
                                    ?>
                                    <form action="<?= $prosesDeletePackageRoute ?>" method="get">
                                        <input type="text" name="id" id="" value="<?= $detail['id'] ?>" hidden>
                                        <?php
                                        if ($data_transaksi['dibayar'] == 'belum_dibayar') {
                                        ?>
                                            <button>Rp.<?= number_format($detail['total_harga'], 0, ',', '.') ?></button>
                                        <?php
                                        } else {
                                        ?>
                                            <span style="color: #e5383b; font-weight: bold;">Rp.<?= number_format($detail['total_harga'], 0, ',', '.') ?></span>
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
                        $grand_total = $model->query("SELECT SUM(total_harga) FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id WHERE id_transaksi = '$idtransaksi'");
                        $grand_total = $grand_total->getData() ? $grand_total->getData() : [];
                        // $grand_total = mysqli_fetch_row(mysqli_query($connect, "SELECT SUM(total_harga) FROM tb_detail_transaksi INNER JOIN tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id WHERE id_transaksi = '$idtransaksi'"));
                        if (!$grand_total['0'] == '0') {
                        ?>
                            <tr>
                                <td colspan="4" style="text-align: right; border-right: 1px solid #e6e5e5; font-weight: bold;">Pajak</td>
                                <td style="text-align: center; font-weight: bold;">
                                    <?php
                                    echo "0.75%";
                                    echo "<br>";
                                    $pajak = $grand_total['total_harga'] * $data_transaksi['pajak'];
                                    echo "Rp." . number_format($pajak, 0, ',', '.');
                                    ?>
                                </td>
                            </tr>
                            <?php
                            if ($data_transaksi['biaya_tambahan'] != 0) {
                            ?>
                                <tr>
                                    <td colspan="4" style="text-align: right; border-right: 1px solid #e6e5e5; font-weight: bold;">Biaya Tambahan</td>
                                    <td style="text-align: center; font-weight: bold;"><?= "Rp." . number_format($data_transaksi['biaya_tambahan'], 0, ',', '.') ?></td>
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
                                        $diskon = $grand_total['0'] * $data_transaksi['8'];
                                        echo "Rp." . number_format($diskon, 0, ',', '.');
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
                                    $total_keseluruhan = ($grand_total['0'] + $data_transaksi['7'] + $pajak) - $diskon;
                                    echo "Rp." . number_format($total_keseluruhan, 0, ',', '.');
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>

                    </table>



                    <div class="box-biaya-tambahan-container">
                        <div class="box-biaya-tambahan">
                            <form action="" method="post">
                                <div class="box-input-biaya-tambahan" style="display: <?php if ($data_transaksi['dibayar'] == 'dibayar') {
                                                                                            echo "none";
                                                                                        } ?>;">
                                    <div class="input-group">

                                        <input type="number" name="biaya_tambahan" id="" placeholder="Biaya Tambahan" autocomplete="off" <?php if ($data_transaksi['dibayar'] == 'dibayar') {


                                                                                                                                                echo "hidden";
                                                                                                                                            } ?>>

                                    </div>



                                    <button class="submit-btn" type="submit" value="Add" name="tombol_biaya_tambahan" <?php if ($data_transaksi['dibayar'] == 'dibayar') {
                                                                                                                            echo "hidden";
                                                                                                                        } ?>>Add</button>
                                </div>

                            </form>
                        </div>
                        <div class="box-button-pay">
                            <form action="" method="post">
                                <button type="submit" name="bayar_sekarang" onclick="window.print()">
                                    Print
                                </button>
                                <input type="submit" value="Pay Now" name="bayar_sekarang" onclick="return confirm('Really want to pay?')" <?php if ($data_transaksi['dibayara'] == 'dibayar' || $_SESSION['role'] == 'owner') {
                                                                                                                                                echo "hidden";
                                                                                                                                            } ?>>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </div>


    </div>
</body>

</html>