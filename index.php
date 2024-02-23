<?php

use App\Libraries\Response;

include 'vendor/autoload.php';
include 'app/libraries/autoload.php';
// include 'app/config/database.php';


foreach (glob("app/routers/*.php") as $filename) {
    include $filename;
}

foreach (glob("script/*.php") as $filename) {
    include $filename;
}

include_once 'app/services/ErrorHandlerService.php';

$app = new App\Libraries\PHPExpress();



$app->set('view engine', 'php');
$app->set('views', 'app/views');

$app->get("*", $errorHandlerService);

$app->use('/', $homeRouter);
$app->use('/api', $apiRouter);
$app->use('/auth', $authRouter);
$app->use('/error', $errorRouter);
$app->use('/panel', $panelRouter);
$app->use('/debug', $debugRouter);


$app->get('/eka', function($req, $res) {
    $res->render('/ekas');
});
$app->listen();

// $numf = new NumberFormatter("en", NumberFormatter::CURRENCY);
// $formattedNumber = $numf->formatCurrency(123000.8, 'IDR');
// echo preg_replace('/IDR/', 'Rp', $formattedNumber);
