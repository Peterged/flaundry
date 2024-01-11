<?php
namespace app\routers;
use app\libraries\Router;
$apiRouter = new Router();
// initial route = /api
$apiRouter->get('/', function ($req, $res) { // /contact
    $res->render('/pages/index');
});

$apiRouter->get('/about', function ($req, $res) { // /about
    $res->render('/pages/about');
});

$apiRouter->get('/api/users/robertos', function ($req, $res) {
    $users = [
        ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'age' => 28],
        ['id' => 2, 'name' => 'Lane Doe', 'email' => 'jane@example.com', 'age' => 25],
        ['id' => 3, 'name' => 'Bob Smith', 'email' => 'bob@example.com', 'age' => 32],
        ['id' => 4, 'name' => 'Alice Johnson', 'email' => 'alice@example.com', 'age' => 59]
    ];

    $res->setHeader('Content-Type', 'application/json; charset=UTF-8');
    $res->setHeader("Access-Control-Allow-Origin", "*");
    $res->setHeader("Access-Control-Allow-Methods", "GET");
    $res->setHeader("Access-Control-Allow-Headers", "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $res->setCode(200);
    echo json_encode($users, JSON_PRETTY_PRINT, JSON_THROW_ON_ERROR);
});
