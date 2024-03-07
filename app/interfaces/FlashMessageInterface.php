<?php
namespace App\Interfaces;
interface FlashMessageInterface {
    public static function addMessage(array $options): array | null;
    public static function getMessages(): array;
    public static function getMessagesByType(string $type): array;
    public static function getMessagesByContext(string $context): array;
    public static function getMessagesByTypeAndContext(string $type, string $context): array;
    public static function getMessagesByCallback(callable $callback): array;
    public static function getMessagesByArray(array $array): array;

    public static function displayCustomPopMessage(string $message, string $type, string $position = 'top-right'): void;
    public static function displayPopMessages(string $position = 'top-right'): void;
    public static function displayPopMessagesByType(string $type, string $position = 'top-right'): void;
    public static function displayPopMessagesByContext(string $context, string $position = 'top-right', int $delay = 3000): void;
}
