<?php 
    
namespace App\Exceptions;

#[\Attribute]
class ValidationException extends \Exception {
    public function __construct($message = "Validation failed", $code = 400, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}