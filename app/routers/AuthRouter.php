<?php

namespace App\routers;

use App\libraries\PHPExpress;
use App\models\User;

$authRouter = new PHPExpress();
// initial route = /auth

function authenticateUser($req, $res) {
    if(isset($_SESSION['role']) && isset($_SESSION['token'])) {
        $res->render("/panel");
    }
}
$authRouter->get('/login', function ($req, $res){
    authenticateUser($req, $res);
    $res->render('/auth/login');
});

$authRouter->get('/logout', function ($req, $res) {
    User::logout();
    $res->redirect('/auth/login');
});

$authRouter->post('/login', function ($req, $res) use ($con) {
    $data = $req->getBody();
    unset($data['submit']);

    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";

    $user = new User($con, $data);
    $result = $user->login();
    $isSuccess = $result->getSuccess();
    if(!$isSuccess) {
        $res->redirect('/auth/login');
    }
    else {
        $res->redirect('/panel');
    }
});

$authRouter->get('/register', function($req, $res) use ($con) {
    $res->render('/auth/register');
});

$authRouter->post('/register', function($req, $res) {
    $data = $req->getBody();
    unset($data['submit']);

    $user = new User($con, $data);

    $result = $user->register();
});

// $authRouter->get('/admin/login', function($req, $res) {
//     $res->render('/auth/admin/login');
// });

// $authRouter->get('/admin/register', function($req, $res) {
//     $res->render('/auth/admin/register');
// });
