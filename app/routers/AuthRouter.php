<?php
namespace App\routers;

use App\Libraries\PHPExpress;
use App\models\User;
use App\Libraries\Essentials\Session;
use App\Services\FlashMessage as fm;
use Respect\Validation\Validator as v;
use App\Utils\MyLodash as _;

global $con;
$authRouter = new PHPExpress();
// initial route = /auth

function authenticateUser($req, $res)
{
    $sesh = $_SESSION;
    $isSessionValid = false;
    $requiredSessions = ['username', 'role', 'id_user', 'id_outlet'];
    $doesOneOfTheSessionExists = false;
    if(count(array_diff($requiredSessions, array_keys($_SESSION))) < count($requiredSessions)) {
        $doesOneOfTheSessionExists = true;
    }

    $isSessionValid = Session::validateSession($sesh, $requiredSessions);

    if(isset($_SESSION['role']) && !$doesOneOfTheSessionExists) {
        $isSessionValid = v::in(ACCOUNT_ROLES)->validate($_SESSION['role']);
    }
    if ($isSessionValid) {
        $res->redirect('/panel');
    }
}

function validateUserSession($req, $res)
{
    $sesh = $_SESSION;
    $requiredSessions = ['username', 'role', 'id_user', 'id_outlet'];
    $isSessionValid = Session::validateSession($sesh, $requiredSessions);

    $doesOneOfTheSessionExists = false;
    
    if(count(array_diff($requiredSessions, array_keys($_SESSION))) < count($requiredSessions)) {
        $doesOneOfTheSessionExists = true;
    }

    if(isset($_SESSION['role'])) {
        $isSessionValid = v::in(ACCOUNT_ROLES)->validate($_SESSION['role']);
    }
    if(!$isSessionValid) {
        if($doesOneOfTheSessionExists) {
            fm::addMessage([
                'type' => 'warning',
                'context' => 'login',
                'title' => 'Invalid Session',
                'description' => 'Maaf, session Anda tidak valid. Silahkan login kembali.'
            ]);
        }
        $loginRoute = routeTo("/auth/login");
        header("Location: $loginRoute");
    }
}

$authRouter->get('/login', function ($req, $res) use ($con) {
    authenticateUser($req, $res);
    User::logout();
    $user = new User($con);
    
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
    // validateUserSession($req, $res);

    $res->render('/auth/register');
});

$authRouter->post('/register', function ($req, $res) use ($con) {
    $data = $req->getBody();
    unset($data['submit']);

    $user = new User($con, $data);

    $result = $user->register();
});



// remember_me_until -> 29/02/2024 05:23:59
