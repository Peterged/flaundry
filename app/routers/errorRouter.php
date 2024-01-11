<?php
namespace app\routers;
use app\libraries\Router;
$errorRouter = new Router();
// initial route = /auth
$errorRouter->get('/error/404', function ($req, $res) {
    if (http_response_code() === 404) {
        $res->render('/errors/404');
    }
});
