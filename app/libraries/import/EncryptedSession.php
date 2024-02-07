<?php 
    
class EncryptedSession {
    public function __construct() {
        if(session_status() == PHP_SESSION_DISABLED) {
            session_start();
        }
    }
    
    public static function getSessionKeyValueAndRemoveOnRefresh($key) {
        if(isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }
        else {
            return false;
        }
    }

    public static function set(string $key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key) {
        return $_SESSION[$key] ?? null;
    }

    public static function remove(string $key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        session_destroy();
    }
}