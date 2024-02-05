<?php 
namespace App\models;
    
class SaveResult extends \stdClass {
    public bool $success;
    public string $status;
    public string $data;
    public string $message;
}