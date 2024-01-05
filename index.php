<?php
session_start();

spl_autoload_register(function($className) {
    include_once './app/libraries/' . $className . '.php';
});

// $session = new Session();
// $session->set('currentPage', $_GET['route']);

// echo $session->get('currentPage');




// Autoload Core Libraries


$router = new Router();

$router->get('/', function($req, $res) {
    echo "Home page";

    // $route = $req->getRequestUri();
    setcookie('name', 'wpw', time() + 3111600);
});

$router->get('/admin/login', function($req, $res) {
    echo "Admin Login here";

    $get = $req->getQueryParams();
    print_r($get);

    $res->render('login');

    include 'login.php';

    
});

$router->get('/admin/register', function($req, $res) {
    echo "<H1>ADMIN REGISTER</H1>";

    // $get = $req->getRequestUri();
    // print_r($get);

    include 'register.php';
});

$router->post('/admin/login', function($req, $res) {
    echo "Admin POST Login here";

    $data = $req->getBody();

    echo '<pre>';
    print_r($data);
    echo '</pre>';
    
    
});

$router->post('/admin/register', function($req, $res) {
    echo "<h1>REGISTER POST</h1>";
    $data = $req->getBody();

    echo '<pre>';
    print_r($data);
    echo '</pre>';

});

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