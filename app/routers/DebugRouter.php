<?php

namespace App\routers;

use App\libraries\PHPExpress;

$debugRouter = new PHPExpress();

// initial route = /debug

$debugRouter->get('/session', function($req, $res) {
    $res->render('/debug/sessionControlPanel');
});

$debugRouter->get('/session/destroy', function($req, $res) {
    session_destroy();
    $res->redirect('/debug/session');
});