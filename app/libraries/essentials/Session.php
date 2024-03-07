<?php
namespace App\Libraries\Essentials;



    class Session {
        private static string $tokenName = '__TID';
        public function __construct() {
            if(session_status() == PHP_SESSION_DISABLED) {
                session_start();
            }
        }

        public static function validateSession(array $session, array $requiredSessions) {
            $isValid = true;
            foreach($requiredSessions as $requiredSession) {
                if(!isset($session[$requiredSession])) {
                    $isValid = false;
                    break;
                }
            }
            return $isValid;
        }

        

        public static function startToken() {
            $token = EncryptedCookie::get(self::$tokenName) ?? self::generateToken();
            // $hash = password_hash($salt, PASSWORD_BCRYPT, ['cost' => 12, 'salt' => $salt]);

            self::set('__TID', $token);
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
