<?php

declare(strict_types=1);

namespace app\libraries;

// include_once __DIR__ . "/../config/config.php";

class Router
{
    private $routes;
    private $headerData;
    private $views;
    private $isListening;
    private $routeQueue;
    
    // For Middleware
    private $routePrefix;

    public function __construct()
    {
        $request = new Request();
        $this->routes = [];
        $this->headerData = [
            "path" => $request->getRequestUri(),
            "isSent" => false
        ];
        $this->isListening = false;
        $this->views = [
            "directory" => null,
            "extension" => '.php'
        ];
        $this->routeQueue = [];
    }

    private function sortRequestQueue()
    {
        usort($this->routeQueue, function ($a, $b) {
            $order = ['GET' => 1, 'POST' => 2];

            $aOrder = $order[$a['method']] ?? 3;
            $bOrder = $order[$b['method']] ?? 3;

            return $aOrder <=> $bOrder;
        });
    }

    public function listen()
    {

        $this->isListening = true;
        $this->sortRequestQueue();
        $requestQueue = $this->routeQueue;
        // $this->print_array($this->routeQueue);
        if (!empty($requestQueue)) {
            foreach ($requestQueue as $key => $requestArray) {
                if (!empty($requestArray)) {
                    $method = (string) $requestArray['method'];
                    $route = (string) $requestArray['route'];
                    $callback = $requestArray['callback'];
                    $this->handleResponse($method, $route, $callback);
                }
            }
        }
    }

    private function addRequestToQueue(string $route, string $method, callable $callback)
    {
        array_push($this->routeQueue, [
            "method" => $method,
            "route" => $route,
            "callback" => $callback
        ]);
    }

    public function use(string $path, Router $router)
    {
        // FOCUS -- FINISH THIS
        if ($this->isListening) {
            throw new \Exception("Cannot use middleware after listening");
        }
        
        if(isset($router->routeQueue)) {
            foreach($router->routeQueue as $route) {
                $route['route'] = $path . $route['route'];
            }
            $this->routeQueue = array_merge($this->routeQueue, $router->routeQueue);
        }
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
        $newArray = $this->routes;

        foreach ($newArray as $routeItem) {
            // $this->print_array($this->routes);
            //$this->print_array([
            //  "route" => preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', ('/' . $route)),
            //"requestType" => $headerType
            // ]);


            if ($routeItem['route'] == $route && $routeItem['requestMethod'] == $headerType && $routeItem['isHandled'] === 1) {
                $isHandled = true;
                break;
            } elseif ($this->isListening) {
                $routeItem['isHandled'] = 1;
            }
        }

        return $isHandled;
    }

    private function addRoute(string $route, string $requestMethod)
    {
        array_push($this->routes, [
            "route" => $route,
            "requestMethod" => $requestMethod,
            "isHandled" => 0
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
        } elseif (!$this->isRouteHandled($requestMethod, $filteredRoute) && $currentRequestMethod !== $requestMethod && $isMatch && !$this->headerData['isSent']) {
            echo "<code>Cannot handle $currentRequestMethod $filteredRoute</code>";
            $this->resetHeader();
        }

        if ($filteredRoute == '/damn') {

            $this->print_array([
                "isRouteHandled" => $this->isRouteHandled($requestMethod, $filteredRoute) ? 'true' : 'false',
                "currentRequestMethod" => $currentRequestMethod,
                "requestMethod" => $requestMethod,
                "filteredRoute" => $filteredRoute,
                "isMatch" => $isMatch,
                "isHeaderSent" => $this->headerData['isSent']
            ]);
        }
    }

    private function handleResponse(string $requestMethod, string $route, $callback)
    {
        if (!is_callable($callback) && !is_string($callback)) {
            throw new \InvalidArgumentException('Callback must be a callable or a string');
        }

        if (is_string($callback)) {
            $callback = RouterHelper::getStringToCallable($callback);
        }

        $UNHANDLED_ROUTE = '*';
        if ($route == $UNHANDLED_ROUTE) {
            $this->handleUnhandledRoutes();
        }

        if ($this->isListening && $this->isRouteHandled($requestMethod, $route)) {
            throw new \Exception("Route already handled: " . $requestMethod . " -> " . $route);
        }

        if (!$this->isListening) {
            $this->addRequestToQueue($route, $requestMethod, $callback);
            return;
        }


        $this->addRoute($route, $requestMethod);

        $request = new Request();
        $response = new Response();

        $response->views = $this->views;
        $filteredRoute = '/';
        $isMatch = false;
        $routeParam = $request->getRequestUri();
        $routeParam = $routeParam == '/' ? '' : $routeParam;
        $currentRequestMethod = $_SERVER['REQUEST_METHOD'];

        $filteredRoute = $this->filterRoute($route, $request);
        $isMatch = $this->isRouteMatch($filteredRoute);

        $request->setRoute($filteredRoute);

        if (!$isMatch) {
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

        $this->processFunction($requestMethod, $isMatch, $filteredRoute, $request, $response, $callback);
    }

    public function redirect(string $route)
    {
        $route = '/' . $this->filterRoute($route);
        $root = PROJECT_ROOT;
        header("Location:" . $this->filterRoute($root . $route));
        exit;
    }

    public function get(string $route, callable | string $callback)
    {
        $this->handleResponse('GET', $route, $callback);
    }

    public function post(string $route, callable | string $callback)
    {
        $this->handleResponse('POST', $route, $callback);
    }

    public function put(string $route, callable | string $callback)
    {
        $this->handleResponse('PUT', $route, $callback);
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

    private function handleUnhandledRoutes()
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
