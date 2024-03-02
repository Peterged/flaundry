<?php

namespace App\config;

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->safeLoad();

$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
$dotenv->required('DEBUG_MODE')->isBoolean();
$dotenv->required('LOG_LEVEL')->allowedValues(['DEBUG', 'INFO', 'WARNING', 'ERROR', 'CRITICAL']);

// Global namespace code here
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_PORT', $_ENV['DB_PORT'] ?? 3306);

define('APPROOT', dirname(__FILE__, 3));
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

$root = str_replace('/', '\\', DOCUMENT_ROOT);
@$root = str_replace($root, '', APPROOT);
$root = str_replace('\\', '/', $root);

define('URLROOT', "http://$_SERVER[SERVER_NAME]" . $root);
define('PROJECT_ROOT', $root);

// Site name
define('SITENAME', 'FLaundry');

// SITE CONFIGS
// For future use, does not work now
define('SHOW_WARNINGS_ON_UNKNOWN_URLS', false);


// OTHERS
define('FLASH_ERROR', 'error');