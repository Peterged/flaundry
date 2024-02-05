<?php
namespace App\routers;
use App\libraries\PHPExpress;
use DateTimeZone;
use Directory;

$homeRouter = new PHPExpress();

// initial route = /
$homeRouter->get('/', function ($req, $res) { // /contact
    $res->render('/home', $res); 
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

