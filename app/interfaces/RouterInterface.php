<?php
namespace App\Interfaces;
use App\Libraries\PHPExpress;
interface RouterInterface
{
    
    public function get(string $route, callable $callback): void;
    public function post(string $route, callable $callback): void;
    public function handleUnhandledRoutes(): void;
    public function use(string $path, PHPExpress $router);
    public function setViews(string $path, string $ext = '.php');
    public function listen();
    
    // private functions
    // private function throwExceptionIfNotListening();
    // private function resetHeader();
    // private isRouteHandled(string $headerType, string $route);
}
