<?php
    include_once __DIR__ . '/../config/config.php';
    function routeTo(string $path) {
        $newPath = PROJECT_ROOT . $path;
        $newPath = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '', $newPath);
        return $newPath;
    }



