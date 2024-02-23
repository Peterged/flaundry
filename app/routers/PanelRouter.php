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

function dashboard($req, $res, $connection) {
    

    // $data = $outlet->get([
    //     'where' => ['ALWAYS' => true]
    // ]);

    $res->render('/panel/components/dashboard');
}

function handleDashboardPostRequest($req, $res) {
    $data = [
        'sales' => 500
    ];

    $res->redirect('/panel/dashboard');
}

function outlet($req, $res, $connection) {  
    $data = (new Model($connection))->query("SELECT * FROM tb_outlet");
    // $data = $outlet->query("SELECT * FROM tb_outlet");
    echo "<pre>", print_r($data), "</pre>";

    $res->render('/panel/components/outlet');
}

function settings($req, $res) {
    $data = [

    ];

    $res->render('/panel/components/settings');
}


$panelRouter
    // Route for the homepage
    ->get('/', function ($req, $res) use ($con, $panelRouter) {
        dashboard($req, $res, $con);
    })

    // Route for the dashboard (GET request)
    ->get('/dashboard', function($req, $res) use ($con) {
        dashboard($req, $res, $con);
    })

    // Route for the dashboard (POST request)
    ->post('/dashboard', function($req, $res) {
        handleDashboardPostRequest($req, $res);
    })

    // Route for the outlet
    ->get('/outlet', function($req, $res) use ($con) {
        fm::addMessage([
            'type' => 'warning',
            'context' => 'outlet_testing',
            'title' => 'KRESHNA KRESHNA KRESHNA KRESHNA KRESHNA KRESHNA',
            'description' => 'This is a success message'
        ]);
        outlet($req, $res, $con);
    })

    // Route for the settings
    ->get('/settings', function($req, $res) {
        settings($req, $res);
    });
