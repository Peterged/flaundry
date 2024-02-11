<?php


spl_autoload_register(function ($className) {
    include_once "./$className.php";
});

include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/config.php';

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});


set_exception_handler(function ($exception) {
    $excludedExceptionClasses = [
        'App\Exceptions\AuthException'
    ];

    foreach ($excludedExceptionClasses as $excludedExceptionClass) {
        if (get_class($exception) == $excludedExceptionClass) {
            return;
        }
    }
    extract(array('error' => $exception));
    include_once "app/views/errors/errorException.php";
});
