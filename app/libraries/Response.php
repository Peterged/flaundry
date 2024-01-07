<?php

namespace app\libraries;

    final class Response {
        public $views;

        public function __construct() {

        }

        public function render(string $path) {
            if(!empty($this->views['directory'])) {
                $path = $this->views['directory'] . $path . $this->views['extension'];
                $path = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', $path);
                if(file_exists($path)) {
                    require_once $path;
                }
                else {
                    throw new \Exception("Sorry, the file does not exist");
                }
            }
            else {
                throw new \Exception("Sorry, the directory is not set");
            }
            $path = str_replace('.', '/', $path);
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
            $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
            $path = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
        }

        public function send(mixed $data) {
            var_dump($data);
        }

        public function sendFile(string $path) {
            $path = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', $path);
            
            if(file_exists($path)) {
                require_once $path;
            }
            else {
                throw new \Exception("Sorry, the file does not exist");
            }
        }
    }
?>
