<?php

namespace App\routers;

use App\Libraries\PHPExpress;
use App\models\User;
use App\utils\PrintArray;
use App\Services\FlashMessage as fm;
use App\models\Outlet;
use App\libraries\Model;

$panelRouter = new PHPExpress();
global $con, $panelRouter;

function dashboard($req, $res, $connection)
{
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
    // $outlet = (new Model($connection))->query("SELECT * FROM tb_outlet");
    $outlet = new Outlet($connection);
    $outletData = $outlet->get([], "");

    $data = [
        'outlets' => $outletData->getData(),
    ];

    $res->render('/panel/components/outlet', $data);
    // $res->render('/panel/components/outlet', $data);
}

function outletDelete($req, $res, $connection) {
    echo $req->getRequestUri();
    $outlet = new Outlet($connection);

    try {
        $outlet->deleteOne([
            'id' => $req->getParams()->id_outlet
        ]);
    }
    catch(\Exception $e) {
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
    $outlet = new Outlet($connection);

    // $outletData = $outlet->get([
    //     'where' => [
    //         'id' => $req->getParams()->id_outlet
    //     ]
    // ], "LIMIT 1");
    $currentOutlet = $outlet->query("SELECT * FROM tb_outlet WHERE id = {$req->getParams()->id_outlet} LIMIT 1");

    $data = [
        'currentOutlet' => $currentOutlet->getData(),
    ];

    // $res->render('/panel/components/edit/edit_outlet', $data);
    $res->render('/panel/components/edit/edit_outlet', $data);
}

function outletEditPost($req, $res, $connection)
{
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
        'description' => 'Berhasil meng-update outlet!'
    ]);

    $res->redirect('/panel/outlet');
}

function outletAddPost($req, $res, $connection) {
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

    // Route for handling post outlet edit request
    ->get('/outlet/delete/{id_outlet}', function ($req, $res) use ($con) {

        outletDelete($req, $res, $con);
    })
    ->get('/outlet/edit/{id_outlet}', function ($req, $res) use ($con) {

        outletEdit($req, $res, $con);
    })
    ->post('/outlet/edit/{id_outlet}', function ($req, $res) use ($con) {

        outletEditPost($req, $res, $con);
    })
    ->get('/outlet/add', function ($req, $res) use ($con) {
        $res->render('/panel/components/add/add_outlet');
    })
    ->post('/outlet/add', function($req, $res) use ($con) {
        outletAddPost($req, $res, $con);
    });
