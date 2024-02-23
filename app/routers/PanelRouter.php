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

function outletEdit($req, $res, $connection)
{
    $outlet = new Outlet($connection);
    // $outletData = $outlet->get([], "WHERE id = :id", ['id' => $req->getParams()['id']]);
    // $outletData = $outlet->get([
    //     'where' => [
    //         'id' => $req->getParams()
    //     ]
    // ]);
    print_r($req->getParams());
    $res->render('/panel/components/edit/edit_outlet');
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
        fm::addMessage([
            'type' => 'warning',
            'context' => 'outlet_testing',
            'title' => 'KRESHNA KRESHNA KRESHNA KRESHNA KRESHNA KRESHNA',
            'description' => 'This is a success message'
        ]);
        outlet($req, $res, $con);
    })

    // Route for handling post outlet edit request
    ->get('/outlet/edit/{id}', function($req, $res) use ($con) {
        
        outletEdit($req, $res, $con);
    });
