<?php
$apiRouter = new app\libraries\Router();
// initial route = /api
$apiRouter->get('/', function ($req, $res) { // /contact
    echo 'why';
    $res->render('/pages/index');
});

$apiRouter->get('/about', function ($req, $res) { // /about
    $res->render('/pages/about');
});
