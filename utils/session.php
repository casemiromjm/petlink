<?php
class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        self::initCSRFToken();
    }

    public static function addMessage($type, $text) {
        self::start();
        $_SESSION['messages'][] = ['type' => $type, 'text' => $text];
    }

    public static function getMessages() {
        self::start();
        $messages = $_SESSION['messages'] ?? [];
        unset($_SESSION['messages']);

        return $messages;
    }

    public static function getUserId() {
        return $_SESSION['user_id'];
    }

    private static function initCSRFToken(): void {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function getCSRFToken(): string {
        self::start();
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken(string $token): bool {
        self::start();
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>
