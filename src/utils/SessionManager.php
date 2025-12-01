<?php
class SessionManager {
    public static function startSession($userId = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($userId !== null) {
            $_SESSION['user_id'] = $userId;
        }
    }

    public static function destroySession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
    }

    public static function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }

    public static function getUserId() {
        if (self::isLoggedIn()) {
            return $_SESSION['user_id'];
        }
        return null;
    }
}
?>

