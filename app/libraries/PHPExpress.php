<?php

declare(strict_types=1);

namespace App\Libraries;

class PHPExpress
{
    private Request $request;
    private Response $response;
    private $routes;
    private $headerData;
    private $views;
    private $isListening;
    private Database $con;
    private $routeQueue;

    // For Middleware
    private $routePrefix;

    public function __construct()
    {
        $request = new Request();
        $this->response = new Response();
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



    public function setDatabaseObject(\App\Libraries\Database $con)
    {
        $this->con = $con;
    }


    public function listen()
    {
        $this->isListening = true;
        $this->sortRequestQueue();
        $requestQueue = $this->routeQueue;
        $this->handleDuplicateRoutes();

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

    private function addRequestToQueue(string $route, string $method, callable | string ...$callback)
    {
        $callbacks = func_get_arg(2);

        if(is_array($callbacks)) {
            array_map(function ($call) {
                if (is_string($call)) {
                    return RouterHelper::getStringToCallable($call);
                }
                return $call;
            }, $callbacks);
        }
        elseif (is_string($callback)) {
            $callback = RouterHelper::getStringToCallable($callback);
        }

        array_push($this->routeQueue, [
            "method" => $method,
            "route" => $route,
            "callback" => count($callback) ? [...$callback] : $callback,
        ]);
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

    public function use(string $path, PHPExpress $router)
    {
        if ($this->isListening) {
            throw new \Exception("Cannot use middleware after listening");
        }

        foreach ($router->routeQueue as &$route) {
            $newRoute = preg_replace('#(\\{1,}|\/{2,})+#', '/', $path . $route['route']);
            $isHomeRoute = $newRoute === '/' ? true : false;

            if ($isHomeRoute) {
                $route['route'] = $newRoute;
                continue;
            }

            $path = $path == '/' ? '' : $path;
            $route['route'] = $route['route'] == '/' ? '' : $route['route'];

            $route['route'] = $path . $route['route'];


            $route['route'] = preg_replace('#(\\{1,}|\/{2,})+#', '/', $route['route']);
        }
        $this->routeQueue = array_merge($this->routeQueue, $router->routeQueue);
    }

    public function redirect(string $route)
    {
        $route = '/' . $this->filterRoute($route);
        $root = PROJECT_ROOT;
        header("Location:" . $this->filterRoute($root . $route));
        exit;
    }

    public function get(string $route, callable | string | array ...$callback)
    {
        if (!$this->isListening) {
            $this->addRequestToQueue($route, 'GET', ...$callback);
            return;
        } else {
            $this->handleResponse('GET', $route, ...$callback);
        }
        return $this;
    }

    public function post(string $route, callable | string | array ...$callback)
    {
        if (!$this->isListening) {
            $this->addRequestToQueue($route, 'POST', ...$callback);
            return;
        }
        $this->handleResponse('POST', $route, ...$callback);
    }

    // Middleware
    public function set(string $command, string $option)
    {
        if ($command == 'view engine') {
            $this->views = [
                "directory" => null,
                "extension" => $option
            ];
        } elseif ($command == 'views') {
            $this->views['directory'] = $option;
        }
    }

    private function resetHeader()
    {
        if (!headers_sent()) {
            header_remove();
        }
    }

    private function isRouteHandled(string $headerType, string $route)
    {
        $isHandled = false;
        foreach ($this->routeQueue as &$routeItem) {
            // echo $routeItem['route'] . " | " . $route, "<br>";

            if ($routeItem['route'] === '*') {
                continue;
            }
            // echo "<code>" .  $routeItem['route'] . ' | ' . $route . "</code><br>";
            if ($routeItem['route'] == $route && $routeItem['method'] == $headerType) {
                $isHandled = true;
            }
        }

        return $isHandled;
    }

    private function handleDuplicateRoutes()
    {
        $routeQueue = $this->routeQueue;
        $indexedArray = [];

        // echo "<h1>BEFORE</h1>", "<br>";
        // $this->print_array($routeQueue);
        $routeQueueLength = count($routeQueue);
        for ($i = 0; $i < $routeQueueLength; $i++) {
            $key = $routeQueue[$i]['route'] . '_' . $routeQueue[$i]['method'];

            if (isset($indexedArray[$key])) {
                // Duplicate found, delete the old data
                unset($indexedArray[$key]);
            }
            $indexedArray[$key] = $routeQueue[$i];
        }
        // echo "<h1>AFTER</h1>", "<br>";
        // $this->print_array($indexedArray);

        $this->routeQueue = $indexedArray;
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

    private function setHeaderData(bool $isSent, $path = null)
    {
        $this->headerData = RouterHelper::setHeaderData($isSent, $path);
    }

    private function processFunction(string $requestMethod, bool $isMatch, string $filteredRoute, Request $request, Response $response, callable | array ...$callback)
    {
        $currentRequestMethod = $request->getMethod();

        if ($currentRequestMethod == $requestMethod && $isMatch) {
            session_start(); // global session_start()
            \App\Services\FlashMessage::initiate();

            if ($requestMethod == 'POST') {
                $request->setBody($_POST);
            }

            $this->setHeaderData(true);

            try {
                if (is_array($callback)) {
                    foreach ($callback as $call) {
                        $call($request, $response);
                    }
                } else {
                    $callback($request, $response);
                }
            }
            catch(\Exception $e) {
                extract(array('error' => $e));
                include_once "app/views/errors/errorException.php";
            }

        }
        // elseif (!$this->isRouteHandled($requestMethod, $filteredRoute) && $currentRequestMethod !== $requestMethod && $isMatch && !$this->headerData['isSent']) {
        //     echo $this->isRouteHandled($requestMethod, $filteredRoute) ? 'true' : 'false' . "<br>";
        //     echo "<code>Cannot handle $currentRequestMethod $filteredRoute</code>";
        // }

        $this->resetHeader();
        // if ($filteredRoute == '/damn') {
        //     $this->print_array([
        //         "isRouteHandled" => $this->isRouteHandled($requestMethod, $filteredRoute) ? 'true' : 'false',
        //         "currentRequestMethod" => $currentRequestMethod,
        //         "requestMethod" => $requestMethod,
        //         "filteredRoute" => $filteredRoute,
        //         "isMatch" => $isMatch,
        //         "isHeaderSent" => $this->headerData['isSent']
        //     ]);
        // }
    }

    private function handleResponse(string $requestMethod, string $route, callable | string | array ...$callback)
    {
        $callbacks = func_get_arg(2);
        $request = new Request();
        $this->response = new Response();
        $this->response->views = $this->views;

        if ($route === "*") {
            $this->handleUnhandledRoutes($route, ...$callbacks);
            return;
        }

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

        $this->processFunction($requestMethod, $isMatch, $filteredRoute, $request, $this->response, ...$callbacks);
    }



    public function put(string $route, callable | string $callback)
    {
        $this->handleResponse('PUT', $route, $callback);
    }

    /**
     * @deprecated Use $this->use() instead
     */
    public function group(string $route, callable $callback)
    {
    }

    private function setStdClassError(\stdClass &$error, int $code, string $message, string $description = null)
    {
        $error->code = $code;
        $error->message = $message;
        $error->description = $description;
    }

    private function handleUnhandledRoutes(string $currentRoute, callable | array ...$callback)
    {
        if (is_array($callback) && count($callback) > 1) {
            die('why you do this');
        }
        if ($currentRoute !== "*") {
            return;
        }
        $req = new Request();
        $error = new \stdClass();
        $requestUri = $this->filterRoute($req->getRequestUri());
        $route = $this->filterRoute($requestUri . '/');

        $isHandled = $this->isRouteHandled($_SERVER['REQUEST_METHOD'], $route);

        // If the request_uri is not handled, return an error
        if (http_response_code() == 403) {
            $this->setStdClassError($error, 403, "Forbidden");
        } elseif (!$isHandled) {
            // echo "<code>Cannot handle $currentRequestMethod $requestUri</code>";
            // $res->setHeader('HTTP/1.0 404 Not Found');
            // $res->setCode(404);
            $this->setStdClassError($error, 404, "Not Found");
            if (is_array($callback)) {
                foreach ($callback as $call) {
                    $call($req, $this->response, $error);
                }
            }
            else {
                $callback($req, $this->response, $error);
            }
        }
    }
}
