<?php

namespace App\config;

// Global namespace code here
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laundry');

define('APPROOT', dirname(dirname(dirname(__FILE__))));
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);

$root = str_replace('/', '\\', DOCUMENT_ROOT);
@$root = str_replace($root, '', APPROOT);
$root = str_replace('\\', '/', $root);

define('URLROOT', 'http://localhost' . $root);
define('PROJECT_ROOT', $root);

// Site name
define('SITENAME', 'Basics');

// SITE CONFIGS
// For future use, does not work now
define('SHOW_WARNINGS_ON_UNKNOWN_URLS', false);
