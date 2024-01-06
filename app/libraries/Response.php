<?php
    class Response {
        public $viewsDirectory;

        public function __construct() {

        }

        public function render(string $path) {
            if(!empty($this->viewsDirectory)) {
                $path = $this->viewsDirectory . $path . '.php';
                file_exists($path) ? require_once $path : null;
            }
            else {
                throw new Exception("Sorry, view directory is not set");
            }
            $path = str_replace('.', '/', $path);
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
            $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
            $path = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
        }

        public function send(mixed $data) {
            var_dump($data);
        }
    }
?>
