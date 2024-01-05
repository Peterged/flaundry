<?php 

    class Cookie {

        /**
         * @param string $key
         * @param mixed $value
         * @param int $expiry
         * @param string $path
         * @summary Set a cookie
         */
        public function set(string $key, $value, int $expiry, $path = '/') {
            setcookie($key, $value, time() + $expiry, $path);
        }

        /**
         * @param string $key
         * @param mixed $default
         * @return mixed
         * @summary Get a cookie
         */
        public function get(string $key, $default = null) {
            return $_COOKIE[$key] ?? $default;
        }

        /**
         * @param string $key
         * @summary Remove a cookie
         */
        public function remove(string $key) {
            setcookie($key, '', time() - 1);
            unset($_COOKIE[$key]);
        }

        /**
         * @summary Destroy all cookies
         */
        public function destroy() {
            foreach($_COOKIE as $key => $value) {
                $this->remove($key);
            }
        }

        /**
         * @param string $key
         * @return bool
         * @summary Check if a cookie exists
         */
        public function exists(string $key): bool {
            return isset($_COOKIE[$key]);
        }
    }
?>