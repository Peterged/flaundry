<?php
ini_set('log_errors', '1');
ini_set('error_log', 'log/error.log');


spl_autoload_register(function ($className) {
    include_once "./$className.php";
});

include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../config/config.php';

if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] == 'production') {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../log/error.log');

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
}

$createCookieFileContent = file_get_contents(__DIR__ . "/../../public/js/createCookie.js");
echo "<script defer>$createCookieFileContent</script>";

set_include_path('app/views/errors/');
// Set the include path to its default value
ini_set('include_path', get_include_path());
ini_set('display_errors', -1);
