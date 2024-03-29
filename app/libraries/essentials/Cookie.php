<?php 

namespace App\Libraries\Essentials;
    class Cookie {

        /**
         * @param string $key
         * @param mixed $value
         * @param int $expiry
         * @param string $path
         * @summary Set a cookie
         */
        public static function set(string $key, $value, int $expiry, $path = '/') {
            setcookie($key, $value, time() + $expiry, $path);
        }

        /**
         * @param string $key
         * @param mixed $default
         * @return mixed
         * @summary Get a cookie
         */
        public static function get(string $key, $default = null) {
            if(isset($_COOKIE[$key])) {
                return $_COOKIE[$key];
            }
            else {
                return $default;
            }
        }

        /**
         * @param string $key
         * @summary Remove a cookie
         */
        public static function remove(string $key) {
            setcookie($key, '', time() - 1);
            unset($_COOKIE[$key]);
        }

        /**
         * @summary Destroy all cookies
         */
        public static function destroy() {
            foreach($_COOKIE as $key => $value) {
                self::remove($key);
            }
        }

        /**
         * @param string $key
         * @return bool
         * @summary Check if a cookie exists
         */
        public static function exists(string $key): bool {
            return isset($_COOKIE[$key]);
        }
    }
