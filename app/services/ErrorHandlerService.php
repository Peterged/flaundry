<?php
$errorHandlerService = function ($req, $res, $error) {
    if (!$error) {
        return;
    }
    if ($error->code) {
        $code = $error->code;
        try {
            $res->render("/errors/$code");
        } catch (\Exception $e) {
        }
    }
};
