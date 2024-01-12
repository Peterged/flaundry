<?php
namespace app\routers;
use app\libraries\PHPExpress;

$panelRouter = new PHPExpress();

$panelRouter->get('/{{always}}', function($req, $res) {
    $res->render('/panel/inc/sidebar');
});

$panelRouter->get('/', function($req, $res) {
    $data = [
        'username' => 'kreshna',
        'title' => 'Dashboard'
    ];
    $res->render('/panel/inc/navbar');
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/index');
});

$panelRouter->get('/dashboard', function($req, $res) {
    $data = [
        'sales' => 500
    ];
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/dashboard');
});

$panelRouter->get('/outlet', function($req, $res) {
    $data = [
        'sales' => 500
    ];
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/outlet');
});

$panelRouter->get('/settings', function($req, $res) {
    $data = [
        'sales' => 500
    ];
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/settings');
});

?>
