<?php

namespace App\routers;

use App\libraries\PHPExpress;
use App\models\User;

$panelRouter = new PHPExpress();



$panelRouter->get('/', function ($req, $res) use ($con) {
    $data = [
        'username' => 'kreshna',
        'title' => 'Dashboard'
    ];
    $uuid = uniqid();

    
    $user = new User($con);

    $data = $user->selectMany(['id_outlet' => '1']);

    echo "<pre>";
    print_r($data);
    echo "</pre>";

    // echo "Success: {$result['success']}<br>";

    $res->render('/panel/inc/navbar');
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/index');
});

$panelRouter->get('/dashboard', function ($req, $res) {
    $data = [
        'sales' => 500
    ];
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/dashboard');
});

$panelRouter->get('/outlet', function ($req, $res) {
    $data = [
        'sales' => 500
    ];
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/outlet');
});

$panelRouter->get('/settings', function ($req, $res) {
    $data = [
        'sales' => 500
    ];
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/settings');
});
