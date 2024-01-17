<?php

namespace App\libraries;

    final class Request {
        public $route;
        public $params;

        public $body;
        public function __construct() {
            $this->params = [];
        }

        public function setRoute(string $route) {
            $this->route = $route;
        }

        public function setBody(array $body) {
            $this->body = $body;
        }

        public function getBody() {
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

        public function getMethod() {
            return $_SERVER['REQUEST_METHOD'];
        }

    }
?>
