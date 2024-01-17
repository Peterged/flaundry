<?php

namespace App\controllers;

use App\core\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index($req, $res)
    {
        $res->render('/users/index');

        // $router->get('/users', 'App\controllers\AuthController@index');
        // $router->get('/users/profile/{id}', 'App\controllers\AuthController@profile');
        // $router->get('/auth/register', 'App\controllers\AuthController@register');
    }

    public function profile($req, $res)
    {
        $res->render('/users/profile');
    }

    public function register($req, $res)
    {
        $res->render('/auth/register');
    }
}
