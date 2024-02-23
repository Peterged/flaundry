<?php
namespace App\routers;
use App\Libraries\PHPExpress;
use DateTimeZone;
use Directory;
use App\Services\FlashMessage;

$homeRouter = new PHPExpress();

// initial route = /
$homeRouter->get('/', function ($req, $res) { // /contact
    $res->render('/home', $res); 
    // echo bin2hex(openssl_random_pseudo_bytes(32));
    $message = FlashMessage::addMessage([
        'type' => 'success',
        'description' => 'Welcome to PHPExpress!',
        'context' => 'flash-message-home',
        'position' => 'bottom-right'
    ]);
    
    // $str = '[{"message":"Welcome to PHPExpres!","type":"success","keyIdentifier":"flash-message-home","position":"bottom-right","_id":"0612846e1a249c640b220d6c2c9ece04"}]';
});


$data = [
    'username' => 'Petergedon',
    'email' => 'roberto@gmail.com',
    'age' => 28,
    'date' => date('M d-m-y'),
];

$homeRouter->get('/about', function ($req, $res) use ($data) { // /about
    $res->render('/layouts/navbar');
    $res->render('/pages/about', array(...$data));
});

$homeRouter->get('/service', function($req, $res) {
    $res->render('/layouts/navbar');
    $res->render('/pages/service');
});

$homeRouter->get('/contact', function ($req, $res) { // /contact
    $res->render('/layouts/navbar');
    $res->render('/pages/contact');
});

$homeRouter->get('/profile', function ($req, $res) { // Profile
    $res->render('/layouts/navbar');
    $res->render('/pages/contact');
});

// Testing
$homeRouter->get('/api-test', function ($req, $res) {
    // BEFORE: mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $data = fetch(URLROOT . '/api/users/robertos');
    $res->render('/test/api_test', $data);
});

