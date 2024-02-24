<?php

namespace App\routers;

use App\Libraries\PHPExpress;
use App\models\User;
use App\utils\PrintArray;
use App\Services\FlashMessage as fm;
use App\models\Outlet;
use App\models\Paket;
use App\models\Member;
use App\models\Karyawan;
use App\libraries\Model;
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
    $outletData = $outlet->get([], "");

    $data = [
        'outlets' => $outletData->getData(),
    ];

    $res->render('/panel/components/outlet', $data);
    // $res->render('/panel/components/outlet', $data);
}

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

    // $res->render('/panel/components/edit/edit_outlet', $data);
    // $res->render('/panel/components/edit/edit_outlet', $data);
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
    $outlet->updateOne([
        'id' => $req->getParams()->id_outlet,
    ], [
        'nama' => $req->getBody()['nama'],
        'alamat' => $req->getBody()['alamat'],
        'tlp' => $req->getBody()['telpon']
    ]);

    fm::addMessage([
        'type' => 'success',
        'context' => 'outlet_message',
        'title' => 'UPDATE',
        'description' => 'Berhasil mengupdate outlet!'
    ]);

    $res->redirect('/panel/outlet');
}

function outletAddPost($req, $res, $connection)
{
    validateUserSession($req, $res);
    $outlet = new Outlet($connection, [
        'nama' => $req->getBody()['nama'],
        'alamat' => $req->getBody()['alamat'],
        'tlp' => $req->getBody()['telpon']
    ]);
    $outlet->save();

    fm::addMessage([
        'type' => 'success',
        'context' => 'outlet_message',
        'title' => 'TAMBAH OUTLET',
        'description' => 'Berhasil menambahkan outlet!'
    ]);

    $res->redirect('/panel/outlet');
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
        $res->render('/panel/components/add/add_outlet');
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
    ];

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
    $outlet = new Paket($connection);
    $body = $req->getBody();
    $outlet->updateOne([
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

function addPaketPost($req, $res, $connection) {
    validateUserSession($req, $res);
    $paket = new Paket($connection, [
        'id_outlet' => $req->getParams()->id_outlet,
        'nama_paket' => $req->getBody()['nama'],
        'jenis' => $req->getBody()['jenis_paket'],
        'harga' => $req->getBody()['harga']
    ]);
    $paket->save();

    fm::addMessage([
        'type' => 'success',
        'context' => 'paket_message',
        'title' => 'TAMBAH PAKET',
        'description' => 'Berhasil menambahkan paket!'
    ]);

    $res->redirect('/panel/paket');

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
    $member = new Member($connection, [
        'nama' => $req->getBody()['nama'],
        'alamat' => $req->getBody()['alamat'],
        'jenis_kelamin' => $req->getBody()['jenis_kelamin'],
        'tlp' => $req->getBody()['tlp']
    ]);
    $member->save();

    fm::addMessage([
        'type' => 'success',
        'context' => 'member_message',
        'title' => 'TAMBAH MEMBER',
        'description' => 'Berhasil menambahkan member!'
    ]);

    $res->redirect('/panel/member');
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

function karyawan($req, $res, $connection) {
    validateUserSession($req, $res);
    $user = new User($connection);
    $userData = $user->query("SELECT tb_user.*, tb_outlet.nama as nama_outlet FROM tb_user JOIN tb_outlet ON tb_user.id_outlet = tb_outlet.id ORDER BY tb_outlet.nama ASC");
    $data = [
        'karyawans' => $userData->getData(),
    ];

    $res->render('/panel/components/karyawan', $data);
}

function editKaryawan($req, $res, $connection) {
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

function editKaryawanPost($req, $res, $connection) {
    validateUserSession($req, $res);
    $user = new User($connection);
    $body = $req->getBody();
    $user->updateOne([
        'id' => $req->getParams()->id_karyawan,
    ], [
        'nama' => $body['nama'],
        'username' => $body['username'],
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

function deleteKaryawan($req, $res, $connection) {
    validateUserSession($req, $res);
    $user = new User($connection);
    try {
        $user->deleteOne([
            'id' => $req->getParams()->id_karyawan
        ]);
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

function addKaryawan($req, $res, $connection) {
    $karyawan = new User($connection);
    validateUserSession($req, $res);
    $data = [
        'model' => $karyawan
    ];
    $res->render('/panel/components/add/add_karyawan', $data);
}

function addKaryawanPost($req, $res, $connection) {
    validateUserSession($req, $res);
    $idOutlet = $req->getBody()['id_outlet'];
    $idOutlet = (int) str_replace(' | ', '', $idOutlet)[0];
    $user = new User($connection, [
        'id_outlet' => $idOutlet,
        'nama' => $req->getBody()['nama'],
        'username' => $req->getBody()['username'],
        'password' => $req->getBody()['password'],
        'role' => $req->getBody()['role']
    ]);
    $user->save();

    fm::addMessage([
        'type' => 'success',
        'context' => 'karyawan_message',
        'title' => 'TAMBAH KARYAWAN',
        'description' => 'Berhasil menambahkan karyawan!'
    ]);

    $res->redirect('/panel/karyawan');
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