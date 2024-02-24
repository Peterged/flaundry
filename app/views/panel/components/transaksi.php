<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/form/minimalisticForm.css">
    <title>Tambah Transaksi | FLaundry</title>
</head>

<body>
    <?php includeFile("$base/panel/inc/sidebar.php") ?>

    <div class="container">
        <form method="post" class="wrapper" id="form-login">
            <h1 class="title small-margin">Tambah Transaksi</h1>
            <p class="small-description margin-bottom-lg"><?= $data['nama_outlet'] ?? '' ?></p>
            <div class="input-group">
                <input type="text" hidden name="selanjutnya">
            </div>
            <div class="input-group">
                <label for="id_outlet">Pilih Member</label>
                <input type="text" name="id_member" id="id_member" list="id_member_list" required>
                <?php
                try {
                    if (isset($_SESSION['idtransaksi'])) {
                        if(is_array($_SESSION['idtransaksi'])) {
                            $_SESSION['idtransaksi'] = "";
                        }
                        $id_transaksi = "/" . $_SESSION['idtransaksi'];
                        
                        $nextPage = routeTo("/panel/detail-transaksi$id_transaksi");
                        echo '
                            <div class="submit-box">
                                <button class="submit-btn" type="submit" form="form-login" name="submit" value="submit">CONTINUE</button>
                            </div>
                            <div class="submit-box">
    
                                <a href="' . $nextPage . '">Back To Last Transaction</a>
                            </div>
                            ';
                    } else {
                        echo "<button class=\"submit-btn\" type=\"submit\" form=\"form-login\" name=\"submit\" value=\"submit\">CONTINUE</button>";
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }

                ?>

                <datalist id="id_member_list">
                    <?php
                    $karyawan = $data['model'];
                    $dataOutlet = $karyawan->query("SELECT id, nama FROM tb_member");
                    $dataOutlet = $dataOutlet->getData();

                    foreach ($dataOutlet as $outlet) {
                        echo "<option value=\"{$outlet['nama']}\">{$outlet['nama']}</option>";
                    }
                    ?>
                </datalist>


            </div>



        </form>

        
    </div>
</body>

</html>