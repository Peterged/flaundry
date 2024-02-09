<?php

namespace App\routers;

use App\Libraries\PHPExpress;

$errorRouter = new PHPExpress();
// initial route = /auth
$errorRouter->get('/404', function ($req, $res) {
    if (http_response_code() === 404) {
        $res->render('/errors/404');
    }
});

