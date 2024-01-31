<?php

namespace App\routers;

use App\libraries\PHPExpress;
use App\models\User;

$authRouter = new PHPExpress();
// initial route = /auth

$authRouter->get('/login', function ($req, $res) {
    $res->render('/auth/login');
});

$authRouter->get('/logout', function ($req, $res) {
    User::logout();
    $res->redirect('/auth/login');
});

$authRouter->post('/login', function ($req, $res) use ($con) {
    $data = $req->getBody();
    unset($data['submit']);

    $uri = $_SERVER;

    // $user = new User($con, $data);
    // echo "<pre>";
    // print_r($user);
    // echo "</pre>";
    // $user = new User($con, compact($req->body));
    // $user->save();
});

// $authRouter->get('/admin/login', function($req, $res) {
//     $res->render('/auth/admin/login');
// });

// $authRouter->get('/admin/register', function($req, $res) {
//     $res->render('/auth/admin/register');
// });
