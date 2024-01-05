<?php

class Router {
    private $req;
    private $res;
    private $routes;

    private $currentPath;

    private $viewsDirectory;

    public function __construct() {
        $this->req = new Request();
        $this->res = new Response();
        $this->routes = [];
        $this->currentPath = [
            "currentPath" => "/",
            "previousPath" => "/"
        ];
    }

    public function useRoute(string $path, RouteController $router) {
        
    }

    // Middleware
    public function setViews(string $path, string $ext = '.php') {
        $this->viewsDirectory = $path;
    }

    private function resetHeader(){
        header_remove();
    }

    private function handleResponse(string $requestType, string $route, callable $callback) {
        if($route == '*') {
            $this->handleUnhandledRoutes();
        }

        
        $this->routes[] = $route;
        
        $req = new Request();
        $res = new Response();
        $filterRoute = '';
        $isMatch = false;
        $routeParam = $req->getRequestUri();
        $routeParam = $routeParam == '/' ? '' : $routeParam;
        $requestUri = $_SERVER['REQUEST_URI'];
        $this->currentPath['currentPage'] = $requestUri;

        if($route == '/') {
            $route = $requestUri . $route;
            $route = preg_replace('#(?<!:)//#', '/', $route);
            $route = str_replace($routeParam, '', $route);
            $filteredRoute = preg_replace('#(?<!:)//#', '/', $route);
            $isMatch = $requestUri === $filteredRoute;
        }
        else {
            $filteredRoute = preg_replace('#(?<!:)//#', '/', $route);
            $isMatch = strpos($requestUri, $filteredRoute) !== false;
        }
        $req->setRoute($route);

        if($_SERVER['REQUEST_METHOD'] == $requestType && $isMatch) {
            if($requestType == 'POST') {
                $req->setBody($_POST);
            }
            $callback($req, $res);
            $this->resetHeader();
        }
    }

    public function get(string $route, callable $callback) {
        if($route == '*') {
            $this->handleUnhandledRoutes();
            return;
        }
        $this->handleResponse('GET', $route, $callback);
    }

    public function post(string $route, callable $callback) {
        if($route == '*') {
            $this->handleUnhandledRoutes();
            return;
        }
        $this->handleResponse('POST', $route, $callback);
    }

    public function handleUnhandledRoutes() {
        $req = new Request();
        $res = new Response();
        $requestUri = $req->getRequestUri();
        

        // Check if the request_uri matches any of the routes
        $isHandled = false;
        foreach ($this->routes as $route) {
            $filteredRoute = preg_replace('#(?<!:)//#', '/', $route);
            if ($requestUri === $filteredRoute) {
                $isHandled = true;
                break;
            }
        }

        // If the request_uri is not handled, throw an error
        if (!$isHandled) {
            throw new Exception("Route not found");
        }
    }
}
