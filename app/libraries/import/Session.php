<?php 
    namespace App\libraries;

use EncryptedCookie;

    class Session {
        public function __construct() {
            if(session_status() == PHP_SESSION_DISABLED) {
                session_start();
            }
        }

        public static function start() {
            $cookie = EncryptedCookie::get('__SID') ?? self::generateToken();
            $tokenValue = bin2hex(
                openssl_encrypt(
                    openssl_random_pseudo_bytes(32),
                    'aes-256-cbc',
                    $cookie
                )
            );

            self::set('__SID', $tokenValue);
        }

        public static function generateToken() {
            return bin2hex(openssl_random_pseudo_bytes(32));
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
