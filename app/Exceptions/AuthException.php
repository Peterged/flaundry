<?php 
namespace App\Exceptions;
    
class AuthException extends \Exception
{
    public function __construct($message, $code = 401, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}