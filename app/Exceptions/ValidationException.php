<?php 
    
namespace App\Exceptions;

#[\Attribute]
class ValidationException extends \Exception {
    private string $errorDisplayType = '';
    public function __construct($message = "Validation failed", string $errorDisplayType = '', $code = 400, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->setErrorDisplayType($errorDisplayType);
    }

    public function getErrorDisplayType(): string {
        return $this->errorDisplayType;
    }

    public function setErrorDisplayType(string $errorDisplayType): void {
        $this->errorDisplayType = $errorDisplayType;
    }
}