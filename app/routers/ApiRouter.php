<?php
$apiRouter = new app\libraries\Router();

$apiRouter->get('/', function ($req, $res) { // /contact
    $res->render('/pages/index');
});

$apiRouter->get('/about', function ($req, $res) { // /about
    $res->render('/pages/about');
});
