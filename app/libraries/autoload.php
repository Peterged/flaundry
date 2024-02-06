<?php
spl_autoload_register(function ($className) {
    include_once "./$className.php";
});

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

include_once __DIR__ . '/../config/database.php';

set_exception_handler(function ($exception) {
    extract(array('error' => $exception));
    include_once "app/views/errors/errorException.php";
});

