<?php

class Router
{
    private $req;
    private $res;
    private $routes;
    private $headerData;
    private $currentPath;
    private $viewsDirectory;
    private $isListening;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
        $this->routes = [];
        $this->currentPath = [
            "currentPath" => "/",
            "previousPath" => "/"
        ];
        $this->headerData = [
            "path" => $this->req->getRequestUri(),
            "isSent" => false
        ];
        $this->isListening = false;
    }

    public function listen() {
        $this->isListening = true;
    }

    private function throwExceptionIfNotListening() {
        if($this->isListening == false) {
            throw new Exception("please use the listen function to start the router!");
        }
    }

    public function useRoute(string $path, RouteController $router)
    {
        // TODO: Implement useRoute method
    }

    // Middleware
    public function setViews(string $path, string $ext = '.php')
    {
        $this->viewsDirectory = $path;
    }

    private function resetHeader()
    {
        header_remove();
    }

    private function isRouteHandled(string $requestType, string $route)
    {
        $isHandled = false;

        foreach ($this->routes as $routeItem) {
            if ($routeItem['route'] == $route && $routeItem['requestType'] == $requestType) {
                $isHandled = true;
                break;
            }
        }

        return $isHandled;
    }

    private function handleUnknownRoute()
    {
        // TODO: Implement handleUnknownRoute method
    }

    private function print_array($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    private function getRouteOnList(string $route): array
    {
        $routeData = [];
        foreach ($this->routes as $routeItem) {
            if ($routeItem['route'] == $route) {
                $routeData = $routeItem;
                break;
            }
        }
        return $routeData ?? [
            "route" => null,
            "requestType" => null
        ];
    }

    private function handleResponse(string $requestType, string $route, callable $callback)
    {

        if ($route == '*') {
            $this->handleUnhandledRoutes();
        }

        if ($this->isRouteHandled($requestType, $route)) {
            throw new Exception("Route already handled: " . $requestType . " -> " . $route);
        }

        array_push($this->routes, [
            "route" => $route,
            "requestType" => $requestType
        ]);

        $req = new Request();
        $res = new Response();

        $res->viewsDirectory = $this->viewsDirectory;
        $filterRoute = '/';
        $isMatch = false;
        $routeParam = $req->getRequestUri();
        $routeParam = $routeParam == '/' ? '' : $routeParam;
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->currentPath['currentPage'] = $requestUri;

        if ($route == '/') {
            $route = $requestUri . $route;
            $route = preg_replace('#(?<!:)//#', '/', $route);
            $route = str_replace($routeParam, '', $route);
            $filteredRoute = preg_replace('#(?<!:)//#', '/', $route);
            $isMatch = $requestUri === $filteredRoute;
        } else {
            $filteredRoute = preg_replace('#(?<!:)//#', '/', $route);
            $isMatch = str_ends_with($requestUri, $filteredRoute);
        }
        $req->setRoute($filteredRoute);

        if ($requestMethod !== $requestType && !$isMatch) {
            return;
        }

        $log = [
            "path" => $route,
            "requestPassed" => $requestType,
            "Header" => $requestMethod,
            "isMatch" => $isMatch ? 'match' : 'not match',
            "POST" => empty($_POST) ? 'undefined' : 'defined'
        ];

        $unhandledRouteLog = [
            "isRouteHandled" => $this->isRouteHandled($requestType, $filteredRoute),
            "headerData" => $this->headerData,
            "POST" => empty($_POST) ? 'undefined' : 'defined'
        ];

        //  $this->print_array(array_merge($log, $unhandledRouteLog));

        $routeListData = $this->getRouteOnList($route);

        if ($requestMethod == $requestType && $isMatch) {
            if ($requestType == 'POST') {
                $req->setBody($_POST);
            }

            $this->headerData = [
                "path" => $this->req->getRequestUri(),
                "isSent" => true
            ];
            // Clears the page
            echo "<script>document.write('');</script>";
            $callback($req, $res);
            $this->resetHeader();
        }

        elseif ($this->isRouteHandled($requestType, $filteredRoute) && $requestMethod !== $requestType && $isMatch && !$this->headerData['isSent']) {
            // echo "<code>Cannot handle $requestMethod $filteredRoute</code>";
            // $this->resetHeader();
        }
    }

    public function get(string $route, callable $callback)
    {

        if ($route == '*') {
            $this->handleUnhandledRoutes();
            return;
        }
        $this->handleResponse('GET', $route, $callback);
    }

    public function post(string $route, callable $callback)
    {

        if ($route == '*') {
            $this->handleUnhandledRoutes();
            return;
        }
        $this->handleResponse('POST', $route, $callback);
    }

    public function handleUnhandledRoutes()
    {

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
