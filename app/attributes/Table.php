<?php 
namespace App\Attributes;
    
abstract class Table {
    public function __construct(public string $tableName) {}
}