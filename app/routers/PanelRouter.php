<?php

namespace App\routers;

use App\libraries\PHPExpress;

$panelRouter = new PHPExpress();



$panelRouter->get('/', function ($req, $res) use ($con) {
    $data = [
        'username' => 'kreshna',
        'title' => 'Dashboard'
    ];
    $uuid = uniqid();
    $quer = $con->query("INSERT INTO tb_user VALUES ('$uuid', '1', 'gsa', 'wtfbro', 'GSAP', 'admin')");
    echo "<pre>";
    print_r($quer->fetchAll());
    echo "</pre>";

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
