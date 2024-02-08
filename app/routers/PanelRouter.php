<?php

namespace App\routers;

use App\Libraries\PHPExpress;
use App\models\User;

use App\utils\PrintArray;


$panelRouter = new PHPExpress();
global $con, $panelRouter;

// $panelRouter->setLayout('/panel/inc/header', '/panel/inc/footer');


$panelRouter->get('/', function ($req, $res) use ($con, $panelRouter) {
    // $user = new User($con);

    // $data = $user->selectMany([ 'id_outlet' => '1' ], [
    //     [
    //         'exclude' => ['id', 'nama', 'username'],
    //         'include' => ['password']
    //     ]
    // ]);

    // PrintArray::run($data);

    // $query = $user->select()->where('id', '=', 1)->get();

    // biasanya begini
    // $query = "UPDATE tb_user SET nama = 'Kreshna' WHERE id = 1";

    // $result = mysqli_query($con, $query);

    // $data = mysqli_fetch_assoc($result);

    // PrintArray::run($data);
    // echo "Success: {$result['success']}<br>";

    $data = [
        'title' => 'Home | Admin Panel',
        'head' => ["<link rel='stylesheet' href='\$PROJECT_ROOT/assets/css/panel.css'>"],
        'username' => 'kreshna',
    ];

    $res->render('/panel/index', $data);
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
