<?php 
namespace App\Services;

use Respect\Validation\Validator as v;
use App\Interfaces\FlashMessageInterface;
/**
 * @class FlashMessage 
 * @summary This class is used to add flash messages to the session
 */
class FlashMessage implements FlashMessageInterface {
    private array $flashMessageTypes = ['info', 'success', 'warning', 'danger'];
    private array $flashMessagePositions = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
    private string $maxMessagelength = 255;

    public static function displayCustomPopMessage(string $message, string $type, string $position = 'top-right'): void {
        if(!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        if(!self::validateMessageLength($message)) {
            throw new \InvalidArgumentException('Invalid message length, max: 255 characters');
        }

        if(!self::validateMessagePosition($position)) {
            throw new \InvalidArgumentException('Invalid message position');
        }

        echo "<div class='flash-message-alert alert-{$type} flash-position-{$position}'>{$message}</div>";
    }

    public static function displayPopMessage(string $position = 'top-right'): void {
        $messages = self::getMessages();
        if(count($messages)) {
            $message = array_pop($messages);
            if($message['position'] ?? false) {
                echo "<div class='flash-message-alert alert-{$message['type']} flash-position-{$message['position']}'>{$message['message']}</div>";
            }
        }
    }

    public static function displayPopMessageByType(string $type, string $position = 'top-right'): void {
        $messages = self::getMessagesByType($type);
        foreach($messages as $message) {
            if($message['isPopMessage'] ?? false) {
                echo "<div class='flash-message-alert alert-{$message['type']} flash-position-{$position}'>{$message['message']}</div>";
            }
        }
    }

    public static function displayPopMessagesByKeyIdentifier(string $keyIdentifier, string $position = 'top-right'): void {
        $messages = self::getMessagesByKeyIdentifier($keyIdentifier);
        foreach($messages as $message) {
            if($message['isPopMessage'] ?? false) {
                echo "<div class='flash-message-alert alert-{$message['type']} flash-position-{$position}'>{$message['message']}</div>";
            }
        }
    }

    public static function displayRecentPopMessage(string $position = 'top-right', string $type = 'info'): void {
        if(!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        $messages = self::getMessages();
        $message = array_pop($messages);
        if(!empty($message)) {
            $messageType = $message['type'] ?? $type;
            $messagePosition = $message['position'] ?? 'top-right';
        }
        
        if($message['isPopMessage'] ?? false) {
            echo "<div class='flash-message-alert alert-{$message['type']} flash-position-{$position}'>{$message['message']}</div>";
        }
    }

    public static function displayCustomMessage(string $message, string $type): void {
        if(!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        if(!self::validateMessageLength($message)) {
            throw new \InvalidArgumentException('Invalid message length, max: 255 characters');
        }

        echo "<div class='flash-message-alert alert-{$type}'>{$message}</div>";
    }

    public static function displayMessages(): void {
        $messages = self::getMessages();
        foreach($messages as $message) {
            echo "<div class='flash-message-alert alert-{$message['type']}'>{$message['message']}</div>";
        }
    }

    public static function displayMessagesByType(string $type): void {
        $messages = self::getMessagesByType($type);
        foreach($messages as $message) {
            echo "<div class='flash-message-alert alert-{$message['type']}'>{$message['message']}</div>";
        }
    }

    public static function displayMessagesByKeyIdentifier(string $keyIdentifier): void {
        $messages = self::getMessagesByKeyIdentifier($keyIdentifier);
        foreach($messages as $message) {
            echo "<div class='flash-message-alert alert-{$message['type']}'>{$message['message']}</div>";
        }
    }

    public static function displayRecentMessage(string $type): void {
        if(!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        $messages = self::getMessages();
        $message = array_pop($messages);
        $messageType = $type ?? $message['type'];
        echo "<div class='flash-message-alert alert-{$message['type']}'>{$message['message']}</div>";
    }

    /**
     * @param string $message
     * @param string $type
     * @param string $keyIdentifier
     * @summary Add a message to the session
     * Add a message to the session
     * 
     * ```php
     * // Example usage:
     * App\Services\FlashMessage::addMessage([]);
     * // Adds a message to the session with the type 'info' and the key identifier 'user'
     * ```
     */
    public static function addMessage(array $options): void {
        if(!self::validateOptions($options)) {
            throw new \InvalidArgumentException('Invalid options!');
        }

        $message = $options['message'];
        $type = $options['type'];
        $keyIdentifier = $options['keyIdentifier'] ?? '';
        $position = $options['position'] ?? [];

        if(!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        $_SESSION['flash_messages'][] = [
            'type' => $type,
            'message' => $message,
            'keyIdentifier' => $keyIdentifier,
            'position' => $position
        ];
    }


    /**
     * @summary Get all messages from the session
     * @return array array of messages
     */
    public static function getMessages(): array {
        $messages = $_SESSION['flash_messages'];
        unset($_SESSION['flash_messages']);
        return $messages ?? [];
    }

    /**
     * @param string $type The type of messages to retrieve. Possible values are 'info', 'warning', 'error', and 'danger'.
     * @summary Get all messages from the session by type
     * @return array array of messages
     * 
     * ```php
     * // Example usage:
     * $messages = App\Services\FlashMessage::getMessagesByType('info');
     * // Returns all messages with the type 'info'
     * ```
     */
    public static function getMessagesByType(string $type = 'info'): array {
        if(!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        $messages = $_SESSION['flash_messages'];
        unset($_SESSION['flash_messages']);
        return array_filter($messages, function($message) use ($type) {
            return $message['type'] === $type;
        }) ?? [];
    }

    /**
     * @param string $keyIdentifier
     * @return array array of messages
     * @summary Get all messages from the session by key identifier
     * @return array array of messages
     */
    public static function getMessagesByKeyIdentifier(string $keyIdentifier): array {
        $messages = $_SESSION['flash_messages'];
        unset($_SESSION['flash_messages']);
        return array_filter($messages, function($message) use ($keyIdentifier) {
            return $message['keyIdentifier'] === $keyIdentifier;
        }) ?? [];
    }

    /**
     * @param string $type The type of messages to retrieve. Possible values are 'info', 'warning', 'error', and 'danger'.
     * @param string $keyIdentifier
     * @summary Get All messages from the session by type and key identifier
     * @return array array of messages
     * 
     * ```php
     * // Example usage:
     * $messages = App\Services\FlashMessage::getMessagesByTypeAndKeyIdentifier('info', 'user');
     * // Returns all messages with the type 'info' and the key identifier 'user'
     * ```
     */
    public static function getMessagesByTypeAndKeyIdentifier(string $type, string $keyIdentifier): array {
        if(!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        $messages = $_SESSION['flash_messages'];
        unset($_SESSION['flash_messages']);
        return array_filter($messages, function($message) use ($type, $keyIdentifier) {
            return $message['type'] === $type && $message['keyIdentifier'] === $keyIdentifier;
        }) ?? [];
    }

    /**
     * Get all messages from the session by callback.
     *
     * @param callable $callback The possible properties you can access from the array are 'type', 'message', and 'keyIdentifier'.
     * @return array An array of messages that match the callback filter.
     * 
     * ```php
     * // Example usage:
     * $messages = App\Services\FlashMessage::getMessagesByCallback(function($message) {
     *    return $message['type'] === 'info';
     * }); 
     * // Returns all messages with the type 'info'
     * ```
     */
    public static function getMessagesByCallback(callable $callback): array {
        $messages = $_SESSION['flash_messages'];
        unset($_SESSION['flash_messages']);

        // $callback = \Closure::fromCallable($callback);
        try {
            $result = array_filter($messages, $callback);
            return $result ?? [];
        }
        catch(\Throwable $e) {
            // throw new \InvalidArgumentException('Invalid callback');
        }
        return [];
    }

    public static function getMessagesByArray(array $options): array {
        if(!self::validateGetOptions($options)) {
            throw new \InvalidArgumentException('Invalid options!');
        }

        $messages = $_SESSION['flash_messages'];
        unset($_SESSION['flash_messages']);
        return array_filter($messages, function($message) use ($options) {
            return $message['type'] === $options['type'] && $message['keyIdentifier'] === $options['keyIdentifier'] && $message['position'] === $options['position'];
        }) ?? [];
    }

    public static function getMessageByIndex(int $index): array {
        $messages = $_SESSION['flash_messages'];
        unset($_SESSION['flash_messages']);
        return $messages[$index] ?? [];
    }

    public static function getMessageByKeyIdentifier(string $keyIdentifier): array {
        $messages = $_SESSION['flash_messages'];
        unset($_SESSION['flash_messages']);
        $result = array_filter($messages, function($message) use ($keyIdentifier) {
            return $message['keyIdentifier'] === $keyIdentifier;
        });

        return count($result) ? $result[0] : [];
    }

    /**
     * @param string $type
     * @summary Validate the message type
     * @return bool
     */
    private static function validateMessageType(string $type): bool {
        return v::in(self::$flashMessageTypes)->validate($type);
    }

    private static function validateMessageLength(string $message): bool {
        return v::stringType()->length(0, self::$maxMessagelength)->validate($message);
    }

    private static function validateMessagePosition(string $position): bool {
        return v::in(self::$flashMessagePositions)->validate($position);
    }

    private static function validateOptions(array $options): bool {
        return v::key('message', v::stringType()->length(0, self::$maxMessagelength))
            ->key('type', v::in(self::$flashMessageTypes))
            ->key('keyIdentifier', v::stringType()->length(0, self::$maxMessagelength), false)
            ->key('position', v::in(self::$flashMessagePositions), false)
            ->validate($options);
    }

    private static function validateGetOptions(array $options): bool {
        $allowedKeys = ['type', 'keyIdentifier', 'position'];

        if(array_diff_key($options, array_flip($allowedKeys))) {
            throw new \InvalidArgumentException('Invalid key in options!');
        }

        return v::notEmpty()
            ->key('type', v::in(self::$flashMessageTypes), false)
            ->key('keyIdentifier', v::stringType()->length(0, self::$maxMessagelength), false)
            ->key('position', v::in(self::$flashMessagePositions), false)
            ->validate($options);
    }

    private static function validatePopMessage(array $message): bool {
        return v::key('message', v::stringType()->length(0, self::$maxMessagelength))
            ->key('type', v::in(self::$flashMessageTypes))
            ->key('keyIdentifier', v::stringType()->length(0, self::$maxMessagelength))
            ->key('position', v::in(self::$flashMessagePositions))
            ->validate($message);
    }
}