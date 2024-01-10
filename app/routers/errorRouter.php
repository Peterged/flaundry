<?php
$errorRouter = new app\libraries\Router();
// initial route = /auth
$errorRouter->get('/error/404', function ($req, $res) {
    if (http_response_code() === 404) {
        $res->render('/errors/404');
    }
});
