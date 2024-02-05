<?php 
namespace App\Attributes;
    
abstract class Secure {
    public function __construct(public string $tableName) {}
}