<?php
$homeRouter = new app\libraries\Router();
// initial route = /
$homeRouter->get('/', function ($req, $res) { // /contact
    $res->render('/home');
});

$homeRouter->get('/about', function ($req, $res) { // /about
    $res->render('/pages/about');
});

$homeRouter->get('/service', function($req, $res) {
    $res->render('/pages/service');
});

$homeRouter->get('/contact', function ($req, $res) { // /contact
    $res->render('/pages/contact');
});



// Testing
$homeRouter->get('/api-test', function ($req, $res) {
    // BEFORE: mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $data = fetch(URLROOT . '/api/users/robertos');
    $res->render('/test/api_test', $data);
});