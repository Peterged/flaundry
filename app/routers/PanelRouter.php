<?php

namespace App\routers;

use App\Libraries\PHPExpress;
use App\models\User;
use App\utils\PrintArray;


$panelRouter = new PHPExpress();
global $con, $panelRouter;

function dashboard($req, $res) {
    $data = [
        'sales' => 500
    ];
    
    $res->render('/panel/components/dashboard');
}


$panelRouter->get('/', function ($req, $res) use ($con, $panelRouter) {
    $data = [
        'title' => 'Home | Admin Panel',
        'head' => ["<link rel='stylesheet' href='\$PROJECT_ROOT/assets/css/panel.css'>"],
        'username' => 'kreshna',
    ];

    $res->render('/panel/components/dashboard');
});

$panelRouter->get('/dashboard', 'dashboard');

$panelRouter->get('/outlet', function ($req, $res) {
    $data = [
        'sales' => 500
    ];
    
    $res->render('/panel/components/outlet');
});

$panelRouter->get('/settings', function ($req, $res) {
    $data = [
        'sales' => 500
    ];
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/settings');
});
