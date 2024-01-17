<?php
namespace App\routers;

use App\libraries\PHPExpress;
$authRouter = new PHPExpress();
// initial route = /auth

$authRouter->get('/login', function($req, $res) {
    $res->render('/auth/login');
});

$authRouter->get('/admin/login', function($req, $res) {
    $res->render('/auth/admin/login');
});

$authRouter->get('/admin/register', function($req, $res) {
    $res->render('/auth/admin/register');
});
