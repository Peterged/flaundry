<?php 
namespace App\models;
    
class SaveResult extends \stdClass {
    protected bool $success;
    protected string $status;
    protected mixed $data;
    protected string $message;

    public function __construct() {
        $this->success = false;
        $this->status = 'commited';
        $this->data = '';
        $this->message = '';
    }

    public function getSuccess(): bool {
        return $this->success;
    }

    public function setSuccess(bool $success): void {
        $this->success = $success;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function getData(): mixed {
        return $this->data;
    }

    public function setData(mixed $data): void {
        $this->data = $data;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

}