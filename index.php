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
include_once "app/routers/AuthRouter.php";
include_once "app/routers/ErrorRouter.php";
include_once "app/services/ErrorHandlerService.php";

$router = new app\libraries\Router();
$router->setViews('app/views');

// middleware
$router->use('/', $homeRouter);
$router->use('/api', $apiRouter);
$router->use('/auth', $authRouter);
$router->use('/error', $errorRouter);
$router->get("*", $errorHandlerService);

// $router->get('/users', 'app\controllers\AuthController@index');
// $router->get('/users/profile/{id}', 'app\controllers\AuthController@profile');
// $router->get('/auth/register', 'app\controllers\AuthController@register');

$router->listen();