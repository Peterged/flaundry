<?php
spl_autoload_register(function ($className) {
    include_once "./$className.php";
});

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// set_exception_handler(function ($exception) {
//     echo "Uncaught exception: " , $exception->getMessage(), " on line " . $exception->getLine() . "\n";
// });

