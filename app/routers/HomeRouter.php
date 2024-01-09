<?php
$homeRouter = new app\libraries\Router();
// initial route = /
$homeRouter->get('/', function ($req, $res) { // /contact
    $res->render('/pages/index');
});

$homeRouter->get('/about', function ($req, $res) { // /about
    $res->render('/pages/about');
});
