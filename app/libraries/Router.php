<?php

declare(strict_types=1);

namespace app\libraries;

// include_once __DIR__ . "/../config/config.php";

class Router
{
    private $req;
    private $res;
    private $routes;
    private $headerData;
    private $views;
    private $isListening;

    public function __construct()
    {
        $this->req = new Request();
        $this->res = new Response();
        $this->routes = [];
        $this->headerData = [
            "path" => $this->req->getRequestUri(),
            "isSent" => false
        ];
        $this->isListening = false;
        $this->views = [
            "directory" => null,
            "extension" => '.php'
        ];
    }

    public function listen()
    {
        $this->isListening = true;
    }

    public function useRoute(string $path, RouteController $router)
    {
        // TODO: Implement useRoute method
    }

    // Middleware
    public function setViews(string $path, string $ext = '.php')
    {
        $this->views = [
            "directory" => $path,
            "extension" => $ext
        ];
    }

    private function throwExceptionIfNotListening()
    {
        if ($this->isListening == false) {
            throw new \Exception("please use the listen function to start the router!");
        }
    }

    private function resetHeader()
    {
        header_remove();
    }

    private function isRouteHandled(string $headerType, string $route)
    {
        $isHandled = false;

        foreach ($this->routes as $routeItem) {
            if ($routeItem['route'] == $route && $routeItem['requestMethod'] == $headerType) {
                $isHandled = true;
                break;
            }
        }

        return $isHandled;
    }

    private function addRoute(string $route, string $requestMethod)
    {
        array_push($this->routes, [
            "route" => $route,
            "requestMethod" => $requestMethod
        ]);
    }

    private function isRouteMatch(string $filteredRoute)
    {
        return RouterHelper::isRouteMatchWithCurrentUri($filteredRoute);
    }

    private function filterRoute(string $route, Request $request = null)
    {
        return RouterHelper::filterRoute($route, $request);
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
            "requestMethod" => null
        ];
    }

    private function setHeaderData(bool $isSent, $path = null)
    {
        $this->headerData = RouterHelper::setHeaderData($isSent, $path);
    }

    private function processFunction(string $requestMethod, bool $isMatch, string $filteredRoute, Request $request, Response $response, callable $callback)
    {
        $currentRequestMethod = $request->getMethod();

        if ($currentRequestMethod == $requestMethod && $isMatch) {
            if ($requestMethod == 'POST') {
                $request->setBody($_POST);
            }
            // echo "<code>Handling $requestMethod $filteredRoute</code>";

            $this->setHeaderData(true);
            $callback($request, $response);
            $this->resetHeader();
        }

        // elseif ($this->isRouteHandled($requestMethod, $filteredRoute) && $currentRequestMethod !== $requestMethod && $isMatch && !$this->headerData['isSent']) {
        //     echo "<code>Cannot handle $requestMethod $filteredRoute</code>";
        //     $this->resetHeader();
        // }
    }

    private function handleResponse(string $requestMethod, string $route, $callback)
    {
        if (!is_callable($callback) && !is_string($callback)) {
            throw new \InvalidArgumentException('Callback must be a callable or a string');
        }

        // if(!is_string($callback)) {
        //     $callback = RouterHelper::getStringToCallable($callback);
        //     $callback();
        // }

        if ($route == '*') {
            $this->handleUnhandledRoutes();
        }

        if ($this->isRouteHandled($requestMethod, $route)) {
            throw new \Exception("Route already handled: " . $requestMethod . " -> " . $route);
        }

        $this->addRoute($route, $requestMethod);

        $req = new Request();
        $res = new Response();

        $res->views = $this->views;
        $filteredRoute = '/';
        $isMatch = false;
        $routeParam = $req->getRequestUri();
        $routeParam = $routeParam == '/' ? '' : $routeParam;
        $currentRequestMethod = $_SERVER['REQUEST_METHOD'];

        $filteredRoute = $this->filterRoute($route, $req);
        $isMatch = $this->isRouteMatch($filteredRoute);
        $req->setRoute($filteredRoute);

        $routeListData = $this->getRouteOnList($route);
        // $this->print_array($routeListData);

        if ($currentRequestMethod !== $requestMethod && !$isMatch) {
            return;
        }

        $log = [
            "path" => $route,
            "requestPassed" => $requestMethod,
            "Header" => $currentRequestMethod,
            "isMatch" => $isMatch ? 'match' : 'not match',
            "POST" => empty($_POST) ? 'undefined' : 'defined',
            "isRouteHandled" => $this->isRouteHandled($requestMethod, $filteredRoute),
            "headerData" => $this->headerData,
        ];

        //  $this->print_array($log);



        $this->processFunction($requestMethod, $isMatch, $filteredRoute, $req, $res, $callback);
    }

    public function redirect(string $route)
    {
        $route = '/' . $this->filterRoute($route);
        $root = PROJECT_ROOT;
        header("Location:" . $this->filterRoute($root . $route));
        exit;
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

    public function group(string $route, callable $callback)
    {
        $this->routes = [];
        $callback();
        $this->routes = array_map(function ($routeItem) use ($route) {
            $routeItem['route'] = $route . $routeItem['route'];
            return $routeItem;
        }, $this->routes);
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
            throw new \Exception("Route not found");
        }
    }
}
