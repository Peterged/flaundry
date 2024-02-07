<?php 
    
class EncryptedCookie {
    public function __construct() {
        
    }
    
    public static function set(string $key, $value, int $expiry, $path = '/') {
        setcookie($key, $value, time() + $expiry, $path);
    }

    public static function get(string $key, $default = null) {
        return $_COOKIE[$key] ?? $default;
    }

    public static function remove(string $key) {
        setcookie($key, '', time() - 1);
        unset($_COOKIE[$key]);
    }

    public static function destroy() {
        foreach($_COOKIE as $key => $value) {
            self::remove($key);
        }
    }

    public static function exists(string $key): bool {
        return isset($_COOKIE[$key]);
    }
}