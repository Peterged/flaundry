<?php 
    $errorHandlerService = function($req, $res, $error) {
        echo 'hey';
        if(!$error) {
            return;
        }
        if($error->code === 404) {
            $res->render('/errors/404');
            exit;
        }
    }
?>