<?php

namespace App\Services;

use Respect\Validation\Validator as v;
use App\Interfaces\FlashMessageInterface;

include_once __DIR__ . '/../config/config.php';
/**
 * @class FlashMessage
 * @summary This class is used to add flash messages to the session
 */

final class FlashMessage implements FlashMessageInterface
{
    private static string $sessionName = 'flash-message';
    private static string $defaultMessageType = 'info';
    private static array $flashMessageTypes = ['info', 'success', 'warning', 'error'];
    private static array $flashMessagePositions = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
    private static array $defaultMessageTemplate = [
        'title' => '',
        'description' => '',
        'type' => 'info',
        'context' => '',
        'position' => ''
    ];
    private static int $maxMessagelength = 255;
    private static int $minDelayBeforeClose = 1000;

    public static function initiate(): void
    {
        if (!isset($_SESSION[self::$sessionName])) {
            $_SESSION[self::$sessionName] = [];
        }
    }

    /**
     * @param string $message
     * @param string $type
     * @param string $context
     * @summary Add a message to the session
     * Add a message to the session
     *
     * ```php
     * // Example usage:
     * App\Services\FlashMessage::addMessage([]);
     * // Adds a message to the session with the type 'info' and the key identifier 'user'
     * ```
     */
    public static function addMessage(array $options): array | null
    {
        if (!self::validateOptions($options)) {
            throw new \InvalidArgumentException('Invalid options!');
        }

        $title = $options['title'] ?? '';
        $description = $options['description'];
        $type = $options['type'] ?? self::$defaultMessageType;
        $context = $options['context'] ?? '';
        $position = $options['position'] ?? '';
        $id = self::generateUniqueId();

        if (self::checkIfIdMatchesWithExistingMessage($id)) {
            return null;
        }

        $newFlashMessage = [
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'context' => $context,
            'position' => $position,
            '_id' => $id
        ];

        $_SESSION[self::$sessionName][] = $newFlashMessage;
        return $newFlashMessage;
    }

    public static function displayCustomPopMessage(string $message, string $type, string $position = 'top-right'): void
    {
        if (!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        if (!self::validateMessageLength($message)) {
            throw new \InvalidArgumentException('Invalid message length, max: 255 characters');
        }

        if (!self::validateMessagePosition($position)) {
            throw new \InvalidArgumentException('Invalid message position');
        }

        echo "<div class='flash-message-alert alert-{$type} flash-position-{$position}'>{$message}</div>";
    }

    public static function displayPopMessages(string $position = 'top-right'): void
    {
        $messages = self::getMessages();
        if (count($messages)) {
            $message = array_pop($messages);
            if ($message['position'] ?? false) {
                echo "<div class='flash-message-alert alert-{$message['type']} flash-position-{$message['position']}'>{$message['message']}</div>";
            }
        }
    }

    public static function displayPopMessagesByType(string $type, string $position = 'top-right'): void
    {
        $messages = self::getMessagesByType($type);
        foreach ($messages as $message) {
            if ($message['isPopMessage'] ?? false) {
                echo "<div class='flash-message-alert alert-{$message['type']} flash-position-{$position}'>{$message['message']}</div>";
            }
        }
    }

    public static function displayPopMessagesByContext(string $context, string $position = 'top-right', int $delay = 3000): void
    {
        $messages = self::getMessagesByContext($context);
        $delay = max($delay, self::$minDelayBeforeClose);
        foreach ($messages as $message) {
            $projectRoot = PROJECT_ROOT;
            $title = $message['title'] ?? 'Validation Error!';
            $description = $message['description'] ?? 'Please check your input and try again.';

            echo <<<HTML
                <div class='flash-message-alert alert-{$message['type']} flash-position-{$position}' data-delay='$delay'>
                    <div class='flash-message-content'>
                        <div class='flash-message-icon'>
                            <img src='$projectRoot/public/images/flashMessage/{$message['type']}-icon.png' alt='icon'>
                        </div>
                        <div class='flash-message-text'>
                            <p class='flash-message-title'>{$title}</p>
                            <p class='flash-message-description'>{$description}</p>
                        </div>
                    </div>
                    <div class='flash-message-close'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
                            <path d='M18 6L6 18M6 6L18 18' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                        </svg>
                    </div>
                </div>
                HTML;
        }
        self::unsetSessionByContext($context);
    }

    public static function displayRecentPopMessage(string $position = 'top-right', string $type = 'info'): void
    {
        if (!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        $messages = self::getMessages();
        $message = array_pop($messages);
        if (!empty($message)) {
            $messageType = $message['type'] ?? $type;
            $messagePosition = $message['position'] ?? 'top-right';

            echo "<div class='flash-message-alert alert-{$message['type']} flash-position-{$position}'>{$message['message']}</div>";
        }
    }

    public static function displayCustomMessage(string $message, string $type): void
    {
        if (!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        if (!self::validateMessageLength($message)) {
            throw new \InvalidArgumentException('Invalid message length, max: 255 characters');
        }

        echo "<div class='flash-message-alert alert-{$type}'>{$message}</div>";
    }

    public static function displayMessages(): void
    {
        $messages = self::getMessages();
        foreach ($messages as $message) {
            echo "<div class='flash-message-alert alert-{$message['type']}'>{$message['message']}</div>";
        }
    }

    public static function displayMessagesByType(string $type): void
    {
        $messages = self::getMessagesByType($type);
        foreach ($messages as $message) {
            echo "<div class='flash-message-alert alert-{$message['type']}'>{$message['message']}</div>";
        }
    }

    public static function displayMessagesByContext(string $context): void
    {
        $messages = self::getMessagesBycontext($context);
        foreach ($messages as $message) {
            echo "<div class='flash-message-alert alert-{$message['type']}'>{$message['message']}</div>";
        }
    }

    public static function displayRecentMessage(string $type): void
    {
        if (!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        $messages = self::getMessages();
        $message = array_pop($messages);

        echo "<div class='flash-message-alert alert-{$message['type']}'>{$message['message']}</div>";
    }




    /**
     * @summary Get all messages from the session and unsets the session
     * @return array array of messages
     */
    public static function getMessages(): array
    {
        $messages = $_SESSION[self::$sessionName];
        unset($_SESSION[self::$sessionName]);
        return $messages ?? self::$defaultMessageTemplate;
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
    public static function getMessagesByType(string $type = 'info'): array
    {
        if (!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        $messages = $_SESSION[self::$sessionName];
        unset($_SESSION[self::$sessionName]);
        return array_filter($messages, function ($message) use ($type) {
            return $message['type'] === $type;
        }) ?? self::$defaultMessageTemplate;
    }

    /**
     * @param string $context
     * @return array array of messages
     * @summary Get all messages from the session by key identifier
     * @return array array of messages
     */
    public static function getMessagesByContext(string $context): array
    {
        $messages = $_SESSION[self::$sessionName];
        // unset($_SESSION[self::$sessionName]);
        return array_filter($messages, function ($message) use ($context) {
            return $message['context'] === $context;
        }) ?? self::$defaultMessageTemplate;
    }

    /**
     * @param string $type The type of messages to retrieve. Possible values are 'info', 'warning', 'error', and 'danger'.
     * @param string $context
     * @summary Get All messages from the session by type and key identifier
     * @return array array of messages
     *
     * ```php
     * // Example usage:
     * $messages = App\Services\FlashMessage::getMessagesByTypeAndcontext('info', 'user');
     * // Returns all messages with the type 'info' and the key identifier 'user'
     * ```
     */
    public static function getMessagesByTypeAndContext(string $type, string $context): array
    {
        if (!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }

        $messages = $_SESSION[self::$sessionName];
        unset($_SESSION[self::$sessionName]);
        return array_filter($messages, function ($message) use ($type, $context) {
            return $message['type'] === $type && $message['context'] === $context;
        }) ?? self::$defaultMessageTemplate;
    }

    /**
     * Get all messages from the session by callback.
     *
     * @param callable $callback The possible properties you can access from the array are 'type', 'message', and 'context'.
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
    public static function getMessagesByCallback(callable $callback): array
    {

        // $callback = \Closure::fromCallable($callback);
        try {
            $messages = $_SESSION[self::$sessionName];
            unset($_SESSION[self::$sessionName]);
            $result = array_filter($messages, $callback);
            return $result ?? self::$defaultMessageTemplate;
        } catch (\Exception $e) {
        }
        return self::$defaultMessageTemplate;
    }

    public static function getMessagesByArray(array $options): array
    {
        if (!self::validateGetOptions($options)) {
            throw new \InvalidArgumentException('Invalid options!');
        }

        $messages = $_SESSION[self::$sessionName];
        unset($_SESSION[self::$sessionName]);
        return array_filter($messages, function ($message) use ($options) {
            return $message['type'] === $options['type'] && $message['context'] === $options['context'] && $message['position'] === $options['position'];
        }) ?? self::$defaultMessageTemplate;
    }

    public static function getMessageByIndex(int $index): array
    {
        $messages = $_SESSION[self::$sessionName];
        unset($_SESSION[self::$sessionName]);
        return $messages[$index] ?? [];
    }

    public static function getMessageByContext(string $context): array
    {
        $messages = $_SESSION[self::$sessionName];
        unset($_SESSION[self::$sessionName]);
        $result = array_filter($messages, function ($message) use ($context) {
            return $message['context'] === $context;
        });

        return count($result) ? $result[0] : self::$defaultMessageTemplate;
    }

    public static function unsetSessionByContext(string $context): void
    {
        $messages = $_SESSION[self::$sessionName];
        unset($_SESSION[self::$sessionName]);
        $result = array_filter($messages, function ($message) use ($context) {
            return $message['context'] !== $context;
        });

        $_SESSION[self::$sessionName] = $result;
    }

    /**
     * @param string $type
     * @summary Validate the message type
     * @return bool
     */
    private static function validateMessageType(string $type): bool
    {
        return v::in(self::$flashMessageTypes)->validate($type);
    }

    private static function validateMessageLength(string $message): bool
    {
        return v::stringType()->length(0, self::$maxMessagelength)->validate($message);
    }

    private static function validateMessagePosition(string $position): bool
    {
        return v::in(self::$flashMessagePositions)->validate($position);
    }

    private static function validateOptions(array $options): bool
    {
        $allowedKeys = ['title', 'description', 'type', 'context', 'position'];

        if (array_diff_key($options, array_flip($allowedKeys))) {
            throw new \InvalidArgumentException('Invalid key in options!');
        }
        
        return v::key('title', v::stringType()->length(0, self::$maxMessagelength), false)
            ->key('description', v::stringType()->length(0, self::$maxMessagelength))
            ->key('type', v::in(self::$flashMessageTypes), false)
            ->key('context', v::stringType()->length(0, self::$maxMessagelength), false)
            ->key('position', v::in(self::$flashMessagePositions), false)
            ->validate($options);
    }

    private static function validateGetOptions(array $options): bool
    {
        $allowedKeys = ['type', 'context', 'position'];

        if (array_diff_key($options, array_flip($allowedKeys))) {
            throw new \InvalidArgumentException('Invalid key in options!');
        }

        return v::notEmpty()
            ->key('type', v::in(self::$flashMessageTypes), false)
            ->key('context', v::stringType()->length(0, self::$maxMessagelength), false)
            ->key('position', v::in(self::$flashMessagePositions), false)
            ->validate($options);
    }

    private static function validateMessageArrayProperties(array $message): bool
    {
        return v::key('title', v::stringType()->length(0, self::$maxMessagelength))
            ->key('description', v::stringType()->length(0, self::$maxMessagelength))
            ->key('type', v::in(self::$flashMessageTypes))
            ->key('context', v::stringType()->length(0, self::$maxMessagelength))
            ->key('position', v::in(self::$flashMessagePositions))
            ->key('_id', v::stringType()->length(0, 64))
            ->validate($message);
    }

    private static function checkIfIdMatchesWithExistingMessage(string $id): bool
    {
        $message = self::getMessagesByCallback(function ($message) use ($id) {
            return $message['_id'] === $id;
        });

        if (!isset($message['_id']) || !isset($id)) {
            return false;
        }

        return $message['_id'] === $id;
    }

    private static function generateUniqueId(): string
    {
        $randomBytes = openssl_random_pseudo_bytes(32);
        return bin2hex($randomBytes);
    }

    private static function handleFlashMessageIcon(string $type)
    {
        if (!self::validateMessageType($type)) {
            throw new \InvalidArgumentException('Invalid message type');
        }
    }
}
