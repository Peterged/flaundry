<?php
namespace App\routers;

use App\Libraries\PHPExpress;
use App\models\User;
use App\Libraries\Essentials\Session;

$authRouter = new PHPExpress();
// initial route = /auth

function authenticateUser($req, $res) {
    $sesh = $_SESSION;
    if(isset($sesh['username'])){
        $res->redirect('/panel');
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
    authenticateUser($req, $res);
    $data = $req->getBody();
    unset($data['submit']);


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



// remember_me_until -> 29/02/2024 05:23:59