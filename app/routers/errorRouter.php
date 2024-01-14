<?php
namespace app\routers;
use app\libraries\PHPExpress;
$errorRouter = new PHPExpress();
// initial route = /auth
$errorRouter->get('/404', function ($req, $res) {
    if (http_response_code() === 404) {
        $res->render('/errors/404');
    }
});
