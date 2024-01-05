<?php
// DB PARAMS
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'UK');


// APP ROOT
define('APPROOT', dirname(dirname(dirname(__FILE__))));

$root = $_SERVER['DOCUMENT_ROOT'];

$root = str_replace('/', '\\', $root);
@$root = str_replace($root, '', APPROOT);
$root = str_replace('\\', '/', $root);

define('URLROOT', 'http://localhost' . $root);
define('PROJECT_ROOT', $root);

// Site name
define('SITENAME', 'Basics');
?>