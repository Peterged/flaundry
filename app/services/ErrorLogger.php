<?php

namespace App\Services;
use Respect\Validation\Validator as v;

class FLogger {
    private static $logTypes = ['error', 'info', 'warning', 'debug'];
    public static function log(string $logType, string $message) {
        if(self::validateLogType($message)) {
            throw new \InvalidArgumentException(sprintf('Invalid log type: %s', $logType));
        }

        $message = printf();
        error_log($message);
    }

    private static function validateLogType(string $logType) {
        return self::$logTypes[strtolower($logType)] ? true : false;
    }
}
