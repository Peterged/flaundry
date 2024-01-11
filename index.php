<?php
session_start();
include_once "app/config/config.php";
spl_autoload_register(function ($className) {
    include_once './' . $className . '.php';
});
foreach (glob("app/routers/*.php") as $filename)
{
    include $filename;
}

include_once "app/utils/fetch.php";
include_once "app/services/ErrorHandlerService.php";

$router = new app\libraries\Router();
$router->setViews('app/views');

// middleware
$router->use('/', $homeRouter);
$router->use('/api', $apiRouter);
$router->use('/auth', $authRouter);
$router->use('/error', $errorRouter);
$router->use('/panel', $panelRouter);
$router->get("*", $errorHandlerService);

$router->listen();
