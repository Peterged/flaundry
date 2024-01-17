<?php 


    class Session {
        public function __construct() {
            if(session_status() == PHP_SESSION_DISABLED) {
                session_start();
            }
        }

        public function set(string $key, $value) {
            $_SESSION[$key] = $value;
        }

        public function get(string $key) {
            return $_SESSION[$key] ?? null;
        }

        public function remove(string $key) {
            unset($_SESSION[$key]);
        }

        public function destroy() {
            session_destroy();
        }
    }
