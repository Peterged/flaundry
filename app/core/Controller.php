<?php 
    namespace app\core;

    class Controller {
        public function __construct() {
            
        }

        public function middleware(string $middleware) {
            // $middleware = 'app\middlewares\\' . $middleware;
            // $middleware = new $middleware();
            // $middleware->handle();
        }

        public function view(string $path) {
            $path = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', $path);
            $path = str_replace('.', '/', $path);
            if(file_exists($path)) {
                require_once $path;
            }
            else {
                throw new \Exception("Sorry, the file does not exist");
            }
        }

        public function send(mixed $data) {
            var_dump($data);
        }
    }
?>