<?php 
    $errorHandlerService = function($req, $res, $error) {
        echo 'hey';
        if(!$error) {
            return;
        }
        if($error->code === 404) {
            echo 'woah';
            exit;
        }
    }
?>