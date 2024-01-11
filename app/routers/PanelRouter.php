<?php
namespace app\routers;
use app\libraries\Router;

$panelRouter = new Router();

$panelRouter->get('/admin', function($req, $res) {
    $data = [
        'username' => 'kreshna'
    ];
    $res->render('/panel/inc/sidebar', $data);
    $res->render('/panel/index');
});

$panelRouter->get('/admin/dashboard', function($req, $res) {
    $data = [
        'sales' => 500
    ];
    $res->render('/panel/dashboard', $data);
});
?>
