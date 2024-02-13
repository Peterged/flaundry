<?php

include 'vendor/autoload.php';
include 'app/libraries/autoload.php';
// include 'app/config/database.php';


foreach (glob("app/routers/*.php") as $filename) {
    include $filename;
}

foreach (glob("script/*.php") as $filename) {
    include $filename;
}

$IDR = new NumberFormatter("id_ID", NumberFormatter::CURRENCY);
$IDR->setTextAttribute(NumberFormatter::CURRENCY_CODE, "IDR");
$IDR->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
$IDR->setAttribute(NumberFormatter::ROUNDING_MODE, NumberFormatter::ROUND_HALFUP);


trigger_error($IDR->format("500001.49") ?? 'OMG');

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

