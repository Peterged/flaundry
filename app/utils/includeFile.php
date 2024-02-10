<?php
    include_once __DIR__ . '/../config/config.php';

    
    function includeFile(string $path, array $data = []) {
        $newPath = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '', "$path");

        
        if(file_exists($newPath)) {
            if(!empty($data)) {
                extract($data);
            }
            include_once $newPath;
        }
        else {
            throw new \Exception("Sorry, the file does not exist: " . $newPath);
        }
    }