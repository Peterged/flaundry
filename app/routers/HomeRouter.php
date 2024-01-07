<?php
// Initial Route = '/'
$router = new app\libraries\RouteController();

// $router->get('/', function($req, $res) {
//     $res->render('/pages/index');
// });

$router->get('/about', function ($req, $res) { // /about
    $res->render('/users/login');
});

