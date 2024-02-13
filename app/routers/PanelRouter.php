<?php

namespace App\routers;

use App\Libraries\PHPExpress;
use App\models\User;
use App\utils\PrintArray;
use App\Services\FlashMessage as fm;


$panelRouter = new PHPExpress();
global $con, $panelRouter;

function dashboard($req, $res) {
    $data = [
        'sales' => 500
    ];
    
    $res->render('/panel/components/dashboard');
}

function handleDashboardPostRequest($req, $res) {
    $data = [
        'sales' => 500
    ];
    
    $res->redirect('/panel/dashboard');
}

function outlet($req, $res) {
    $data = [
        'sales' => 500
    ];
    
    $res->render('/panel/components/outlet');
}

function settings($req, $res) {
    $data = [
        
    ];

    $res->render('/panel/components/settings');
}


$panelRouter
    // Route for the homepage
    ->get('/', function ($req, $res) use ($con, $panelRouter) {
        dashboard($req, $res);
    })

    // Route for the dashboard (GET request)
    ->get('/dashboard', function($req, $res) {
        dashboard($req, $res);
    })

    // Route for the dashboard (POST request)
    ->post('/dashboard', function($req, $res) {
        handleDashboardPostRequest($req, $res);
    })

    // Route for the outlet
    ->get('/outlet', function($req, $res) {
        fm::addMessage([
            'context' => 'outlet_testing',
            'title' => 'Hello World!',
            'description' => 'This is a success message'
        ]);
        outlet($req, $res);
    })

    // Route for the settings
    ->get('/settings', function($req, $res) {
        settings($req, $res);
    });
