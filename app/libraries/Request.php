<?php

namespace App\Libraries;
use Respect\Validation\Validator as v;

    final class Request {
        public $route;
        protected object $params;

        public $body;
        public function __construct() {
            $this->params = (object) [];
        }

        public function setRoute(string $route) {
            $this->route = $route;
        }

        public function setBody(array $body) {
            $this->body = $body;
        }

        /**
         * @param array|null $body
         * @return array
         * @description filterBody() is a method to filter the body ($_POST) request
         */
        public function filterBody(array $body = null): array {
            $body = $body ?? $this->body;
            foreach($body as $key => $value) {
                $body[$key] = trim($value);
                $body[$key] = stripslashes($value);
                $body[$key] = htmlspecialchars($value);
            }
            $this->body = $body;
            return $body;
        }

        public function getBody(bool $filter = true) {
            // filtering enabled as default
            
            if($filter) {
                $this->filterBody();
            }
            return $this->body;
        }

        public function getRoute() {
            return $this->route;
        }

        public function getRequestUri() {
            $uri = $_GET['route'] ?? '/';
            // return parse_url($uri, PHP_URL_PATH);
            return '/' . $uri;
        }

        public function getFullPath(){
            $uri = $_SERVER['REQUEST_URI'];
            $uri = preg_replace('#(?<!:)//#', '/', $uri);
            return $uri;
        }

        public function getQueryParams() {
            return $_GET;
        }

        public function getQuery(string $key, $default = null) {
            if(isset($_GET[$key])) {
                return $_GET[$key];
            } else {
                return null;
            }
        }

        public function getMethod() {
            return $_SERVER['REQUEST_METHOD'];
        }

        public function getParam(string $key) {
            return $this->params->{$key} ?? null;
        }

        public function getParams() {
            return $this->params;
        }

        public function setParams(object $params) {
            $this->params = $params;
        }



    }
