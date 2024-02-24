<?php

namespace App\routers;

use App\Libraries\PHPExpress;
use App\models\User;
use App\Libraries\Essentials\Session;
use App\Services\FlashMessage as fm;

global $con;
$authRouter = new PHPExpress();
// initial route = /auth

function authenticateUser($req, $res)
{
    $sesh = $_SESSION;
    $requiredSessions = ['username', 'role', 'id_user', 'id_outlet'];
    $isSessionValid = Session::validateSession($sesh, $requiredSessions);
    if ($isSessionValid) {
        $res->redirect('/panel');
    }
}

function validateUserSession($req, $res)
{
    $sesh = $_SESSION;
    $requiredSessions = ['username', 'role', 'id_user', 'id_outlet'];
    $isSessionValid = Session::validateSession($sesh, $requiredSessions);
    if (!$isSessionValid) {

        fm::addMessage([
            'type' => 'warning',
            'context' => 'login',
            'title' => 'Invalid Session',
            'description' => 'Maaf, session Anda tidak valid. Silahkan login kembali.'
        ]);
        $loginRoute = routeTo("/auth/login");
        header("Location: $loginRoute");
    }
}

$authRouter->get('/login', function ($req, $res) use ($con) {
    authenticateUser($req, $res);
    $user = new User($con);

    // $result = $user->get([
    //     'where' => [
    //         'nama' => 'admin'
    //     ],
    // ], ['nama' => 'admin']);

    // $isSuccess = $result->getSuccess();
    $res->render('/auth/login');
});

$authRouter->get('/logout', function ($req, $res) {
    User::logout();
    $res->redirect('/auth/login');
});

$authRouter->post('/login', function ($req, $res) use ($con) {

    $data = $req->getBody();
    unset($data['submit']);

    $user = new User($con, $data);
    $result = $user->login();
    $isSuccess = $result->getSuccess();
    if ($isSuccess) {

        $res->redirect('/panel');
    } else {
        $res->redirect('/auth/login');
    }
});

$authRouter->get('/register', function ($req, $res) use ($con) {
    validateUserSession($req, $res);

    $res->render('/auth/register');
});

$authRouter->post('/register', function ($req, $res) use ($con) {
    $data = $req->getBody();
    unset($data['submit']);

    $user = new User($con, $data);

    $result = $user->register();
});



// remember_me_until -> 29/02/2024 05:23:59
