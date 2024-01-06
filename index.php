<?php
session_start();

// Autoload Core Libraries
spl_autoload_register(function($className) {
    include_once './app/libraries/' . $className . '.php';
});


$router = new Router();

$router->setViews('app/views');

$router->get('/login', function($req, $res) {
    include 'login.php';
});

$router->get('/users/posts/:id', function($req, $res) {

});

$router->get('/', function($req, $res) {
    $res->render('/users/login');
});


$router->get('/users/profile', function($req, $res) {
    include 'profile.php';
});

$router->post('/users/profile-process', function($req, $res) {
    $data = $req->getBody();

    echo '<pre>';
    print_r($data);
    echo '</pre>';
});


$router->listen();

$route = isset($_GET['route']) ? $_GET['route'] : '';

// switch ($route) {
//     case $case1:
//         // Include login page content
//         include 'login.php';
//         break;
//     case $case2:
//         // Include register page content
//         include 'register.php';
//         break;

//     case $case3:
//         // Include profile page content
//         include 'profile.php';
//         break;

//     case $case4:
//         // Include about page content
//         include 'about.php';
//         break;
//     default:
//         // Handle default route (e.g., display homepage)

// }
