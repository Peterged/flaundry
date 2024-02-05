<?php
include 'vendor/autoload.php';
include 'app/libraries/autoload.php';
// include 'app/config/database.php';

foreach (glob("app/routers/*.php") as $filename) {
    include $filename;
}

include_once "app/services/ErrorHandlerService.php";

$app = new App\libraries\PHPExpress();

$app->set('view engine', 'php');
$app->set('views', 'app/views');

$app->get("*", $errorHandlerService);

$app->use('/', $homeRouter);
$app->use('/api', $apiRouter);
$app->use('/auth', $authRouter);
$app->use('/error', $errorRouter);
$app->use('/panel', $panelRouter);


$app->get('/eka', function($req, $res) {
    $res->render('/ekas');
});
$app->listen();
