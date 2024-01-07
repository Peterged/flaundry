<?php
session_start();
include_once "app/config/config.php";

$_PUT = [];
$_DELETE = [];
global $_PUT;
global $_DELETE;

// Autoload Core Libraries
spl_autoload_register(function ($className) {
    // I set it into $classname only, cause the libraries folder is using namespaces
    include_once './' . $className . '.php';
});

$router = new app\libraries\Router();
$router->setGlobalHeaderVariables($_PUT, $_DELETE);
$router->setViews('app/views');

$router->get('/login', function ($req, $res) {
    $res->render('/users/login');
}); 

$router->get('/users/posts', function ($req, $res) {
    $res->render('/users/posts');
});

$router->get('/', function ($req, $res) {
    echo "Hello";
    $res->render('/pages/index');
});

$router->get('/users/register', function ($req, $res) {
    $res->render('/users/register');
});


// $router->get('/users/wtf', 'app\libraries\Router@addRoute');
// $router->get('/users/wtf', [app\libraries\Router::class, 'addRoute']);

$router->get('/users/profile', function ($req, $res) use ($router) {
    $res->render('/users/profile');

    $router->redirect('users/login');
});

$router->post('/users/profile-process', function ($req, $res) use ($router) {
    $data = $req->getBody();
    $router->redirect('/users/profile');
});


$router->listen();

$route = isset($_GET['route']) ? $_GET['route'] : '';

echo '<pre>';
var_dump(app\libraries\RouterHelper::getRouteParams("/users/update/{id}"));
print_r($_PUT());
echo '</pre>';


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
