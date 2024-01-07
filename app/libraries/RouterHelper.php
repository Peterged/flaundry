<?php
namespace app\libraries;
include_once __DIR__ . "/../config/config.php";
class RouterHelper
{
    public static function filterRoute(string $route, Request $request = null)
    {
        if(is_null($request)) {
            $request = new Request();
        }
        $filteredRoute = '/';
        $route = trim($route, '/');
        $route = filter_var($route, FILTER_SANITIZE_URL);

        if ($route == '/') {
            $route = $request->getRequestUri() . $route;
            $route = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', $route);
            $route = str_replace($request->getRequestUri(), '', $route);
            $filteredRoute = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', $route);
        } else {
            $filteredRoute = preg_replace('#(?<!:)(\\{1,}|\/{2,})+#', '/', $route);
        }

        return $filteredRoute;
    }

    public static function setHeaderData(bool $isSent, $path = null)
    {
        $request = new Request();

        if ($path == null) {
            $path = $request->getRequestUri();
        }
        return [
            "path" => $path,
            "isSent" => $isSent
        ];
    }

    public static function isRouteMatchWithCurrentUri(string $filteredRoute) {
        $request = new Request();
        $isMatch = false;
        $requestUri = $request->getRequestUri();

        $requestUri = '/' . ltrim($requestUri, '/');
        $filteredRoute = '/' . ltrim($filteredRoute, '/');
        $isMatch = mb_substr($requestUri, -mb_strlen($filteredRoute), mb_strlen($filteredRoute)) == $filteredRoute;
        return $isMatch;
    }

    public static function getRouteParams(string $filteredRoute) {
        $request = new Request();
        $requestUri = $request->getRequestUri();
        $requestUri = '/' . ltrim($requestUri, '/');
        $filteredRoute = '/' . ltrim($filteredRoute, '/');
        $params = new \stdClass();
        $requestUri = trim($requestUri, '/');
        $requestUri = explode('/', $requestUri);
        $filteredRoute = trim($filteredRoute, '/');
        $filteredRoute = explode('/', $filteredRoute);

        
        foreach ($filteredRoute as $key => $value) {
            $keyName = preg_replace('/\{(.*)\}/', '$1', $value);
            
            if (preg_match('/\{(.*)\}/', $value)) {
                if(isset($requestUri[$key])) {
                    $params->$keyName = $requestUri[$key];
                }
            }
        }
        return $params;
    }

    public static function convertToCallable($string) {
        list($class, $method) = explode('@', $string);
        if(!class_exists($class)) {
            throw new \Exception("Class $class does not exist");
        }
        if(!method_exists($class, $method)) {
            throw new \Exception("Method $method does not exist");
        }
        
        return [new $class, $method];
    }

    public static function getStringToCallable($callback) {
        if (is_string($callback) && strpos($callback, '@') !== false) {
            $callback = self::convertToCallable($callback);
            
        }
        // var_dump($callback);
        $callableClass = new $callback[0]();
        var_dump($callableClass);
        $callbackFunc = $callableClass->{$callback[1]};
        // $callbackFunc = $callableClass->{$callback[1]};
        // echo is_callable($callbackFunc) ? 'true' : 'false';
        // return $callbackFunc;
    }
}
