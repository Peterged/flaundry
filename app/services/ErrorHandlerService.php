<?php
namespace App\Services;

$errorHandlerService = function ($req, $res, $error) {
    if (!$error) {
        include_once "app/services/SessionControlPanelService.php";
        return;
    }
    if ($error->code) {
        $code = $error->code;
        try {
            $res->render("/errors/$code");
        } catch (\Exception $e) {
        }
    }
    else {
        
    }
};
