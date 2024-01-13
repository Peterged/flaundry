<?php
include 'app/libraries/autoload.php';

foreach (glob("app/routers/*.php") as $filename) {
    include $filename;
}

include_once "app/services/ErrorHandlerService.php";

$app = new app\libraries\PHPExpress();
$app->set('view engine', 'php');
$app->set('views', 'app/views');

$app->get("*", $errorHandlerService);
$app->use('/', $homeRouter);
$app->use('/api', $apiRouter);
$app->use('/auth', $authRouter);
$app->use('/error', $errorRouter);
$app->use('/panel', $panelRouter);

$app->get('/excel-test', function($req, $res) {
    $res->render('/eka-navbar');
});

$app->listen();
