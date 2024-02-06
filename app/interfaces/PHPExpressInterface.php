<?php 
    
namespace App\Interfaces;

use App\Models\SaveResult;

interface PHPExpressInterface
{
    public function get(string $url, array $headers = null): SaveResult;
    public function post(string $url, array $data, array $headers = null): SaveResult;
    public function put(string $url, array $data, array $headers = null): SaveResult;

}