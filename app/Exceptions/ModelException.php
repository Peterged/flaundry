<?php 
namespace App\Exceptions;
    
class ModelException extends \Exception
{
    protected $status = 'commited';
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->status = 'rollback';
    }
    public function getStatus() {
        return $this->status;
    }
}