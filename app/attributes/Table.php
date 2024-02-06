<?php 
namespace App\Attributes;
    
#[\Attribute(\Attribute::TARGET_ALL)]
class Table {
    public function __construct(public string $tableName) {}
}