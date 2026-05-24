<?php
if (!class_exists('CookieSessionHandler')) {
    class CookieSessionHandler implements SessionHandlerInterface {
        private $cookiePrefix = 'PHPSESS_';

        public function open($savePath, $sessionName): bool {
            return true;
        }

        public function close(): bool {
            return true;
        }

        public function read($id): string {
            $cookieName = $this->cookiePrefix . $id;
            if (isset($_COOKIE[$cookieName])) {
                return base64_decode($_COOKIE[$cookieName]);
            }
            return '';
        }

        public function write($id, $data): bool {
            $cookieName = $this->cookiePrefix . $id;
            setcookie($cookieName, base64_encode($data), [
                'expires' => 0, // session cookie (expires when browser tab closed)
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            return true;
        }

        public function destroy($id): bool {
            $cookieName = $this->cookiePrefix . $id;
            setcookie($cookieName, '', [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            return true;
        }

        public function gc($maxlifetime): int|false {
            return 0;
        }
    }

    $handler = new CookieSessionHandler();
    session_set_save_handler($handler, true);
}
?>
