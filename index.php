<?php
session_start();
include_once "app/config/config.php";

// Autoload Core Libraries
spl_autoload_register(function ($className) {
    // I set it into $classname only, cause the libraries folder is using namespaces
    include_once './' . $className . '.php';
});

include_once "app/utils/fetch.php";
include_once "app/routers/HomeRouter.php";
include_once "app/routers/ApiRouter.php";

$router = new app\libraries\Router();
$router->setViews('app/views');

// middleware
$router->use('/', $homeRouter);
$router->use('/api', $apiRouter);

$router->post('/login', function ($req, $res) {
    $body = $req->getBody();

    echo "<pre>";
    print_r($body);
    echo "</pre>";

    echo "<h1>LOGIN POST SUCCESSFUL!</h1>";
});

$router->get('/login', function ($req, $res) {
    echo "GET LOGIN";
    $res->render('/auth/login');
});

$router->get('/users', 'app\controllers\AuthController@index');
$router->get('/users/profile/{id}', 'app\controllers\AuthController@profile');
$router->get('/auth/register', 'app\controllers\AuthController@register');


 
$router->get('/api/users/robertos', function ($req, $res) {
    
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
    http_response_code(200);
    echo json_encode($users, JSON_PRETTY_PRINT, JSON_THROW_ON_ERROR);
});

$router->get('/api-test', function($req, $res) {
    // BEFORE: mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $data = fetch(URLROOT . '/api/users/robertos');
    $res->render('/test/api_test', $data);
});

$router->listen();

// $route = isset($_GET['route']) ? $_GET['route'] : '';


// echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
