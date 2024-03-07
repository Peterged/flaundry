<?php

namespace App\routers;

use App\Libraries\PHPExpress;
use App\models\User;
use App\Services\FlashMessage as fm;
use App\models\Outlet, App\models\Paket, App\models\Member, App\models\DetailTransaksi;
use App\Utils\MyLodash as _;
use App\Services\SearchEngineService as SES;
use App\Libraries\Essentials\Session;

$panelRouter = new PHPExpress();
global $con, $panelRouter;

function tryCatchDisplayWarning($e)
{
    fm::addMessage([
        'type' => 'error',
        'context' => 'panel_message',
        'title' => 'Unknown',
        'description' => 'Maaf, terjadi kesalahan.'
    ]);
}

function dashboard($req, $res, $connection)
{
    validateUserSession($req, $res);
    $res->render('/panel/components/dashboard');
}

function handleDashboardPostRequest($req, $res)
{
    $data = [
        'sales' => 500
    ];

    $res->redirect('/panel/dashboard');
}

function outlet($req, $res, $connection)
{
    validateUserSession($req, $res);
    // $outlet = (new Model($connection))->query("SELECT * FROM tb_outlet");
    $outlet = new Outlet($connection);


    $params = _::filter($_GET, function ($value, $key) {
        return $key !== 'route';
    });

    if (!empty($params)) {
        // print_r($params);
        $params = SES::filterSearch($params);
        $outletData = $outlet->get($params, "");
    } else {
        $outletData = $outlet->get([], "");
    }


    $data = [
        'outlets' => $outletData->getData(),
        'tableColumns' => $outlet->getTableColumns()
    ];

    $res->render('/panel/components/outlet', $data);
    // $res->render('/panel/components/outlet', $data);
}

// function outletSearchPost($req, $res, $connection) 
// {
//     validateUserSession($req, $res);

//     $outlet = new Outlet($connection);
//     $body = $req->getBody();
//     $params = _::filter($_GET, function($key) {
//         return in_array($key, ['nama', 'alamat', 'tlp']);
//     });

//     $res->render('/panel/components/outlet', [
//         'outlets' => $outlet->get($params, $body['search'])->getData()
//     ]);
// }

function outletDelete($req, $res, $connection)
{
    validateUserSession($req, $res);
    echo $req->getRequestUri();
    $outlet = new Outlet($connection);

    try {
        $outlet->deleteOne([
            'id' => $req->getParams()->id_outlet
        ]);
    } catch (\Exception $e) {
        fm::addMessage([
            'type' => 'error',
            'context' => 'outlet_message',
            'title' => 'Unknown',
            'description' => 'Maaf, terjadi kesalahan.'
        ]);
    }

    $res->redirect("/panel/outlet");
}

function outletEdit($req, $res, $connection)
{
    validateUserSession($req, $res);
    $outlet = new Outlet($connection);

    $currentOutlet = $outlet->query("SELECT * FROM tb_outlet WHERE id = {$req->getParams()->id_outlet} LIMIT 1");

    $data = [
        'currentOutlet' => $currentOutlet->getData(),
    ];

    // $res->render('/panel/components/edit/edit_outlet', $data);
    $res->render('/panel/components/edit/edit_outlet', $data);
}

function outletEditPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $outlet = new Outlet($connection);
    $body = $req->getBody();

    if (!$outlet->validateSave($body)) {
        $res->redirect('/' . $_GET['route']);
    } else {
        $outlet->updateOne([
            'id' => $req->getParams()->id_outlet,
        ], [
            'nama' => $body['nama'],
            'alamat' => $body['alamat'],
            'tlp' => $body['tlp']
        ]);

        fm::addMessage([
            'type' => 'success',
            'context' => 'outlet_message',
            'title' => 'UPDATE',
            'description' => 'Berhasil mengupdate outlet!'
        ]);

        $res->redirect('/panel/outlet');
    }
}

function outletAdd($req, $res, $connection)
{
    validateUserSession($req, $res);
    $res->render('/panel/components/add/add_outlet');
}

function outletAddPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $body = $req->getBody();
    $outlet = new Outlet($connection, [
        'nama' => $body['nama'],
        'alamat' => $body['alamat'],
        'tlp' => $body['tlp']
    ]);
    if (!$outlet->validateSave($body)) {
        $res->redirect('/' . $_GET['route']);
    } else {
        $outlet->save();

        fm::addMessage([
            'type' => 'success',
            'context' => 'outlet_message',
            'title' => 'TAMBAH OUTLET',
            'description' => 'Berhasil menambahkan outlet!'
        ]);

        $res->redirect('/panel/outlet');
    }
}

$panelRouter
    // Route for the homepage
    ->get('/', function ($req, $res) use ($con, $panelRouter) {
        dashboard($req, $res, $con);
    })

    // Route for the dashboard (GET request)
    ->get('/dashboard', function ($req, $res) use ($con) {
        dashboard($req, $res, $con);
    })

    // Route for the dashboard (POST request)
    ->post('/dashboard', function ($req, $res) {
        handleDashboardPostRequest($req, $res);
    })

    // Route for the outlet
    ->get('/outlet', function ($req, $res) use ($con) {
        outlet($req, $res, $con);
    })
    ->get('/outlet/edit/{id_outlet}', function ($req, $res) use ($con) {
        outletEdit($req, $res, $con);
    })
    // Route for handling post outlet edit request
    ->get('/outlet/delete/{id_outlet}', function ($req, $res) use ($con) {
        outletDelete($req, $res, $con);
    })
    ->post('/outlet/edit/{id_outlet}', function ($req, $res) use ($con) {
        outletEditPost($req, $res, $con);
    })
    ->get('/outlet/add', function ($req, $res) use ($con) {
        outletAdd($req, $res, $con);
    })
    ->post('/outlet/add', function ($req, $res) use ($con) {
        outletAddPost($req, $res, $con);
    });


function paket($req, $res, $connection)
{
    validateUserSession($req, $res);
    // $outlet = (new Model($connection))->query("SELECT * FROM tb_outlet");
    $paket = new Paket($connection);
    $paketData = $paket->query("SELECT tb_paket.*, tb_outlet.nama FROM tb_paket JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id ORDER BY tb_outlet.nama ASC");

    $data = [
        'pakets' => $paketData->getData(),
        'tableColumns' => $paket->getTableColumns()
    ];
    print_r($paket->getTableColumns(true));

    $res->render('/panel/components/paket', $data);
}

function editPaket($req, $res, $connection)
{
    validateUserSession($req, $res);
    $paket = new Paket($connection);
    $idOutlet = $req->getParams()->id_outlet;
    $idPaket = $req->getParams()->id_paket;
    $paketData = $paket->query("SELECT tb_paket.*, tb_outlet.nama, tb_outlet.id as id_outlet FROM tb_paket JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id WHERE tb_paket.id = '$idPaket' AND tb_outlet.id = '$idOutlet'");

    $data = [
        'currentPaket' => $paketData->getData(),
    ];

    $res->render('/panel/components/edit/edit_paket', $data);
}

function editPaketPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $paket = new Paket($connection);
    $body = $req->getBody();
    if (!$paket->validateSave($body)) {
        $res->redirect('/' . $_GET['route']);
    } else {
        $paket->updateOne([
            'id' => $req->getParams()->id_paket,
            'id_outlet' => $req->getParams()->id_outlet
        ], [
            'nama_paket' => $body['nama'],
            'jenis' => $body['jenis_paket'],
            'harga' => $body['harga']
        ]);

        fm::addMessage([
            'type' => 'success',
            'context' => 'paket_message',
            'title' => 'UPDATE',
            'description' => 'Berhasil mengupdate paket!'
        ]);

        $res->redirect("/panel/paket");
    }
}

function deletePaketPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $paket = new Paket($connection);
    try {
        $paket->deleteOne([
            'id' => $req->getParams()->id_paket,
            'id_outlet' => $req->getParams()->id_outlet
        ]);
        fm::addMessage([
            'type' => 'success',
            'context' => 'paket_message',
            'title' => 'DELETE',
            'description' => 'Berhasil menghapus paket!'
        ]);
    } catch (\Exception $e) {
        tryCatchDisplayWarning($e);
    }

    $res->redirect("/panel/paket");
}

function addPaket($req, $res, $connection)
{
    validateUserSession($req, $res);
    $outlet = new Outlet($connection);
    $namaOutlet = $outlet->get([
        'where' => [
            'id' => $req->getParams()->id_outlet
        ],
        'select' => 'nama'
    ]);

    $res->render('/panel/components/add/add_paket', [
        'sessionIdOutlet' => $req->getParams()->id_outlet,
        'nama_outlet' => $namaOutlet->getData()[0]['nama']
    ]);
}

function addPaketPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $body = $req->getBody();
    $body['harga'] = (int) $body['harga'];
    $paket = new Paket($connection, [
        'id_outlet' => $req->getParams()->id_outlet,
        'nama_paket' => $req->getBody()['nama'],
        'jenis' => $req->getBody()['jenis_paket'],
        'harga' => $req->getBody()['harga']
    ]);

    if (!$paket->validateSave($body)) {
        $res->redirect('/' . $_GET['route']);
    } else {
        $paket->save();
        fm::addMessage([
            'type' => 'success',
            'context' => 'paket_message',
            'title' => 'TAMBAH PAKET',
            'description' => 'Berhasil menambahkan paket!'
        ]);
        $res->redirect('/panel/paket');
    }
}

$panelRouter->get('/paket', function ($req, $res) use ($con) {
    paket($req, $res, $con);
})
    ->get('/paket/edit/{id_paket}/{id_outlet}', function ($req, $res) use ($con) {
        editPaket($req, $res, $con);
    })
    ->post('/paket/edit/{id_paket}/{id_outlet}', function ($req, $res) use ($con) {
        editPaketPost($req, $res, $con);
    })
    ->get('/paket/delete/{id_paket}/{id_outlet}', function ($req, $res) use ($con) {
        deletePaketPost($req, $res, $con);
    })
    ->get('/paket/add/{id_outlet}', function ($req, $res) use ($con) {
        addPaket($req, $res, $con);
    })
    ->post('/paket/add/{id_outlet}', function ($req, $res) use ($con) {
        addPaketPost($req, $res, $con);
    });

function member($req, $res, $connection)
{
    validateUserSession($req, $res);
    $member = new Member($connection);
    $memberData = $member->get();

    $data = [
        'members' => $memberData->getData(),
    ];

    $res->render('/panel/components/member', $data);
}

function editMember($req, $res, $connection)
{
    validateUserSession($req, $res);
    $member = new User($connection);
    $idMember = $req->getParams()->id_member;
    $memberData = $member->query("SELECT * FROM tb_member WHERE id = '$idMember'");

    $data = [
        'currentMember' => $memberData->getData(),
    ];

    $res->render('/panel/components/edit/edit_member', $data);
}

function editMemberPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $member = new Member($connection);
    $body = $req->getBody();
    if (!$member->validateSave($body)) {
        $res->redirect('/' . $_GET['route']);
    } else {
        $member->updateOne([
            'id' => $req->getParams()->id_member,
        ], [
            'nama' => $body['nama'],
            'alamat' => $body['alamat'],
            'jenis_kelamin' => $body['jenis_kelamin'],
            'tlp' => $body['tlp']
        ]);

        fm::addMessage([
            'type' => 'success',
            'context' => 'member_message',
            'title' => 'UPDATE',
            'description' => 'Berhasil mengupdate member!'
        ]);

        $res->redirect("/panel/member");
    }
}

function deleteMember($req, $res, $connection)
{
    validateUserSession($req, $res);
    $member = new Member($connection);
    try {
        $member->deleteOne([
            'id' => $req->getParams()->id_member
        ]);
        fm::addMessage([
            'type' => 'success',
            'context' => 'member_message',
            'title' => 'DELETE',
            'description' => 'Berhasil menghapus member!'
        ]);
    } catch (\Exception $e) {
        tryCatchDisplayWarning($e);
    }

    $res->redirect("/panel/member");
}



function addMember($req, $res, $connection)
{
    validateUserSession($req, $res);

    $res->render('/panel/components/add/add_member');
}

function addMemberPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $body = $req->getBody();
    $member = new Member($connection, [
        'nama' => $req->getBody()['nama'],
        'alamat' => $req->getBody()['alamat'],
        'jenis_kelamin' => $req->getBody()['jenis_kelamin'],
        'tlp' => $req->getBody()['tlp']
    ]);
    if (!$member->validateSave($body)) {
        $res->redirect('/' . $_GET['route']);
    } else {
        $member->save();
        fm::addMessage([
            'type' => 'success',
            'context' => 'member_message',
            'title' => 'TAMBAH MEMBER',
            'description' => 'Berhasil menambahkan member!'
        ]);
        $res->redirect('/panel/member');
    }
}

$panelRouter->get('/member', function ($req, $res) use ($con) {
    member($req, $res, $con);
})
    ->get('/member/edit/{id_member}', function ($req, $res) use ($con) {
        editMember($req, $res, $con);
    })

    ->get('/member/delete/{id_member}', function ($req, $res) use ($con) {
        deleteMember($req, $res, $con);
    })
    ->post('/member/edit/{id_member}', function ($req, $res) use ($con) {
        editMemberPost($req, $res, $con);
    })
    ->get('/member/add', function ($req, $res) use ($con) {
        addMember($req, $res, $con);
    })
    ->post('/member/add', function ($req, $res) use ($con) {
        addMemberPost($req, $res, $con);
    });

function karyawan($req, $res, $connection)
{
    validateUserSession($req, $res);
    $user = new User($connection);
    $userData = $user->query("SELECT tb_user.*, tb_outlet.nama as nama_outlet FROM tb_user JOIN tb_outlet ON tb_user.id_outlet = tb_outlet.id ORDER BY tb_outlet.nama ASC");
    $data = [
        'karyawans' => $userData->getData(),
    ];

    $res->render('/panel/components/karyawan', $data);
}

function editKaryawan($req, $res, $connection)
{
    validateUserSession($req, $res);
    $user = new User($connection);
    $idKaryawan = $req->getParams()->id_karyawan;
    $userData = $user->query("SELECT tb_user.*, tb_outlet.nama as nama_outlet FROM tb_user JOIN tb_outlet ON tb_user.id_outlet = tb_outlet.id WHERE tb_user.id = '$idKaryawan'");

    $data = [
        'currentKaryawan' => $userData->getData(),
        'model' => $user
    ];

    $res->render('/panel/components/edit/edit_karyawan', $data);
}

function editKaryawanPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $user = new User($connection);
    $body = $req->getBody();

    if (!$user->validateUpdate($body)) {
        $res->redirect('/' . $_GET['route']);
    } else {
        $user->updateOne([
            'id' => $req->getParams()->id_karyawan,
        ], [
            'id_outlet' => $body['id_outlet'],
            'nama' => $body['nama'],
            'username' => $body['username'],
            'password' => $body['password'],
            'role' => $body['role']
        ]);

        fm::addMessage([
            'type' => 'success',
            'context' => 'karyawan_message',
            'title' => 'UPDATE',
            'description' => 'Berhasil mengupdate karyawan!'
        ]);
        $res->redirect("/panel/karyawan");
    }
}

function deleteKaryawan($req, $res, $connection)
{
    validateUserSession($req, $res);
    $user = new User($connection);
    try {
        $result = $user->deleteOne([
            'id' => $req->getParams()->id_karyawan
        ]);
        if (!$result->getSuccess()) {
            throw new \Exception('Gagal menghapus karyawan!');
        }
        fm::addMessage([
            'type' => 'success',
            'context' => 'karyawan_message',
            'title' => 'DELETE',
            'description' => 'Berhasil menghapus karyawan!'
        ]);
    } catch (\Exception $e) {
        tryCatchDisplayWarning($e);
    }

    $res->redirect("/panel/karyawan");
}

function addKaryawan($req, $res, $connection)
{
    $karyawan = new User($connection);
    validateUserSession($req, $res);
    $data = [
        'model' => $karyawan
    ];
    $res->render('/panel/components/add/add_karyawan', $data);
}

function addKaryawanPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $body = $req->getBody();
    $body['id_outlet'] = (int) preg_replace('/\D+/', '', $body['id_outlet']);
    $idOutlet = $body['id_outlet'];

    $user = new User($connection, [
        'id_outlet' => $idOutlet,
        'nama' => $body['nama'],
        'username' => $body['username'],
        'password' => $body['password'],
        'role' => $body['role']
    ]);
    if (!$user->validateSave($body)) {
        $res->redirect('/' . $_GET['route']);
    } else {
        $result = $user->save();
        $message = 'Berhasil menambahkan karyawan!';
        $flashType = 'success';
        if (!$result->getSuccess()) {
            $flashType = 'error';
            $message = 'Gagal menambahkan karyawan!';
        }

        fm::addMessage([
            'type' => $flashType,
            'context' => 'karyawan_message',
            'title' => 'TAMBAH KARYAWAN',
            'description' => $message
        ]);

        $res->redirect('/panel/karyawan');
    }
}

$panelRouter->get('/karyawan', function ($req, $res) use ($con) {
    karyawan($req, $res, $con);
})
    ->get('/karyawan/edit/{id_karyawan}', function ($req, $res) use ($con) {
        editKaryawan($req, $res, $con);
    })
    ->post('/karyawan/edit/{id_karyawan}', function ($req, $res) use ($con) {
        editKaryawanPost($req, $res, $con);
    })
    ->get('/karyawan/delete/{id_karyawan}', function ($req, $res) use ($con) {
        deleteKaryawan($req, $res, $con);
    })
    ->get('/karyawan/add', function ($req, $res) use ($con) {
        addKaryawan($req, $res, $con);
    })
    ->post('/karyawan/add', function ($req, $res) use ($con) {
        addKaryawanPost($req, $res, $con);
    });

function transaksi($req, $res, $connection)
{
    validateUserSession($req, $res);
    $karyawan = new User($connection);
    $data = [
        'model' => $karyawan
    ];
    // $connection->exec("LOCK TABLES tb_outlet WRITE");


    // $connection->exec("UNLOCK TABLES");


    $res->render('/panel/components/transaksi', $data);
}

function transaksiPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    try {
        $model = new User($connection);

        $body = $req->getBody();
        if (isset($body['selanjutnya'])) {
            $id_outlet = $_SESSION['id_outlet'];

            @$lsdata = $model->query("SELECT kode_invoice FROM tb_transaksi ORDER BY id DESC LIMIT 1");

            @$last_kode_invoice = $lsdata->getData() ? $lsdata->getData()[0] : null;


            if (!$last_kode_invoice) {
                $kode_invoice = "INV/" . date("Y/m/d") . "/1";
            } else {
                $pecah_string = explode("/", $last_kode_invoice['kode_invoice']);
                $bulan_sebelum = $pecah_string[2];
                $bulan_sekarang = date('m');
                if ($bulan_sekarang != $bulan_sebelum) {
                    $number_urut = 1;
                } else {
                    $number_urut = $pecah_string[4];
                    $number_urut++;
                }
                $kode_invoice = "INV/" . date("Y/m/d") . "/" . $number_urut;
            }

            $nama_member = $_POST['id_member'];
            $ls1data = $model->query("SELECT id FROM tb_member WHERE nama = '$nama_member'");
            $cari_id_member = $ls1data->getData()[0];
            $id_member = $cari_id_member['id'];

            date_default_timezone_set('Asia/Makassar');
            $tanggal = date('Y-m-d H:i:s');
            $batas_waktu = date('Y-m-d H:i:s', strtotime($tanggal . ' +3 days'));
            $dibayar = "belum_dibayar";
            $biaya_tambahan = 0;

            $ls2data = $model->query("SELECT id_member FROM tb_transaksi WHERE id_member = '$id_member'");
            $cari_transaksi = count($ls2data->getData());
            if ($cari_transaksi % 3 == 0 && $cari_transaksi != 0) {
                $diskon = 0.1;
            } else {
                $diskon = 0;
            }

            $pajak = 0.0075;
            $status = "baru";
            $id_user = $_SESSION['id_user'];
            $querry = "INSERT INTO tb_transaksi (id_outlet, kode_invoice, id_member, tgl, batas_waktu, biaya_tambahan, diskon, pajak, status, dibayar, id_user) VALUES(:id_outlet, :kode_invoice, :id_member, :tanggal, :batas_waktu, :biaya_tambahan, :diskon, :pajak, :status, :dibayar, :id_user)";

            $ls3data = $model->queryWithTransaction($querry, [
                'id_outlet' => $id_outlet,
                'kode_invoice' => $kode_invoice,
                'id_member' => $id_member,
                'tanggal' => $tanggal,
                'batas_waktu' => $batas_waktu,
                'biaya_tambahan' => $biaya_tambahan,
                'diskon' => $diskon,
                'pajak' => $pajak,
                'status' => $status,
                'dibayar' => $dibayar,
                'id_user' => $id_user
            ]);

            $id_transaksi = $model->query("SELECT LAST_INSERT_ID()");
            $_SESSION['idtransaksi'] = $id_transaksi->getData()[0]['LAST_INSERT_ID()'];

            // $hasil = mysqli_query($connect, "INSERT INTO tb_transaksi VALUES(NULL, '$id_outlet', '$kode_invoice', '$id_member', '$tanggal', '$batas_waktu', NULL, '$biaya_tambahan', '$diskon', '$pajak', '$status', '$dibayar', '$id_user')");
            // $id_transaksi = mysqli_fetch_row(mysqli_query($connect, "SELECT LAST_INSERT_ID()"));
            $id_transaksi = $_SESSION['idtransaksi'];
            if (!$ls3data->getSuccess()) {
                echo "Failed Add Transaction : " . $ls3data->getMessage();
            } else {
                $nextLocation = routeTo("/panel/detail-transaksi/$id_transaksi");
                header("Location: $nextLocation");
                exit;
            }
        }
    } catch (\Exception $e) {
        echo $e->getMessage() . "<br>" . $e->getLine() . "<br>" . $e->getFile() . "<br>";
        tryCatchDisplayWarning($e);
    }
}

function detailTransaksi($req, $res, $connection)
{
    validateUserSession($req, $res);
    $id_transaksi = $req->getParams()->id_transaksi;

    if (!isset($_SESSION['idtransaksi']) || $_SESSION['idtransaksi'] != $id_transaksi) {
        $_SESSION['idtransaksi'] = $id_transaksi;
    }
    $karyawan = new User($connection);
    $data = [
        'model' => $karyawan,
        'id_transaksi' => $_SESSION['idtransaksi'],
        'param_id_transaksi' => $req->getParams()->id_transaksi
    ];

    $res->render('/panel/components/detail_transaksi', $data);
}

function transaksiStatusHandler($req, $res, $con)
{
    validateUserSession($req, $res);
    $model = new User($con);
    $id_transaksi = $req->getParams()->id;
    $status = $req->getParams()->status;

    $model->queryWithTransaction("UPDATE tb_transaksi SET status = '$status' WHERE id = '$id_transaksi'");

    $res->redirect("/panel/detail-transaksi/$id_transaksi");
}

function detailTransaksiTambahPaketPost($req, $res, $con)
{
    validateUserSession($req, $res);
    $body = $req->getBody();
    $paket = new Paket($con);
    $id_transaksi = $req->getParams()->id_transaksi;
    $paketResult = $paket->query("SELECT id, harga FROM tb_paket WHERE nama_paket = '{$body['nama_paket']}'");
    $paketResult = $paketResult->getData() ? $paketResult->getData()[0] : null;

    $dataBody = [
        'id_transaksi' => $id_transaksi,
        'id_paket' => (int) $paketResult['id'],
        'qty' => (int) $body['qty'],
        'keterangan' => $body['keterangan'],
        'total_harga' => (float) ($paketResult['harga'] * $body['qty'])
    ];

    $detailTransaksi = new DetailTransaksi($con, $dataBody);

    if ($detailTransaksi->validateSave($dataBody)) {
        $detailTransaksi->save();
    }
    $nextLocation = "/panel/detail-transaksi/$id_transaksi";
    $res->redirect($nextLocation);
}

function detailTransaksiDeletePaket($req, $res, $con)
{
    validateUserSession($req, $res);
    $detailTransaksi = new DetailTransaksi($con);
    $id_detail_transaksi = $_GET['id'];
    $result = $detailTransaksi->deleteOne([
        'id' => $id_detail_transaksi
    ]);

    if ($result->getSuccess()) {
        fm::addMessage([
            'type' => 'success',
            'context' => 'detail_transaksi_message',
            'title' => 'DELETE',
            'description' => 'Berhasil menghapus paket!'
        ]);
    }

    $id_transaksi = $_SESSION['idtransaksi'];
    $res->redirect("/panel/detail-transaksi/$id_transaksi");
}

function detailTransaksiBayarHandler($req, $res, $con)
{
    validateUserSession($req, $res);
    $detailTransaksi = new DetailTransaksi($con);
    $id_transaksi = $req->getParams()->id_transaksi;
    $result = $detailTransaksi->get([
        'where' => [
            'id_transaksi' => $id_transaksi
        ]
    ]);

    $result = $result->getData() ? $result->getData()[0] : null;
    if (!$result) {
        fm::addMessage([
            'type' => 'warning',
            'context' => 'detail_transaksi_message',
            'title' => 'EITS!',
            'description' => 'Transaksi tidak memiliki paket!'
        ]);
    } else {
        $result = $detailTransaksi->queryWithTransaction("UPDATE tb_transaksi SET dibayar = 'dibayar' WHERE id = '$id_transaksi'");
        
        if ($result->getSuccess()) {
            fm::addMessage([
                'type' => 'success',
                'context' => 'detail_transaksi_message',
                'title' => 'BAYAR',
                'description' => 'Berhasil membayar transaksi!'
            ]);
        }
        else {
            fm::addMessage([
                'type' => 'error',
                'context' => 'detail_transaksi_message',
                'title' => 'BAYAR',
                'description' => 'Gagal membayar transaksi!'
            ]);
        }
    }

    $id_transaksi = $_SESSION['idtransaksi'];
    $res->redirect("/panel/detail-transaksi/$id_transaksi");
}

$panelRouter->get('/transaksi', function ($req, $res) use ($con) {
    transaksi($req, $res, $con);
})
    ->get('/detail-transaksi/delete-paket', function ($req, $res) use ($con) {
        detailTransaksiDeletePaket($req, $res, $con);
    })
    ->get('/transaksi_status_handler/{status}/{id}', function ($req, $res) use ($con) {
        transaksiStatusHandler($req, $res, $con);
    })
    ->post('/transaksi', function ($req, $res) use ($con) {
        transaksiPost($req, $res, $con);
    })
    ->post('/detail-transaksi/bayar/{id_transaksi}', function ($req, $res) use ($con) {
        detailTransaksiBayarHandler($req, $res, $con);
    })
    ->get('/detail-transaksi/{id_transaksi}', function ($req, $res) use ($con) {
        detailTransaksi($req, $res, $con);
    })
    ->post('/detail-transaksi/{id_transaksi}', function ($req, $res) use ($con) {
        detailTransaksi($req, $res, $con);
    })
    ->post('/detail-transaksi/tambah_paket/{id_transaksi}', function ($req, $res) use ($con) {
        detailTransaksiTambahPaketPost($req, $res, $con);
    })
    ->get('/detail-transaksi/tambah_paket/{id_transaksi}', function ($req, $res) use ($con) {
        validateUserSession($req, $res);
        $id_transaksi = $req->getParams()->id_transaksi;
        $nextLocation = "/panel/detail-transaksi/$id_transaksi";
        $res->redirect($nextLocation);
    });
