<?php
namespace app\services;
class BreadCrumbService
{
    public array $breadcrumbs;
    public function __construct()
    {
        $this->breadcrumbs = [];
    }

    public function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        }
    }
}

#[\Attribute(\Attribute::TARGET_ALL)]
class MyAttribute
{
}

#[MyAttribute]
class MyClass
{
}
