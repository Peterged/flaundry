<?php 
// Gets the URL
$url = $_GET['route'];

// Formats the URL with a regex
$regex = "#(?<!)(\\{2,}|\/{2,})+#";
$secondRegex = "#(\/)+$#";
$url = preg_replace($regex, '/', $url);
$url = preg_replace($secondRegex, '', $url);
// Redirects to the new URL
header("Location: $url");