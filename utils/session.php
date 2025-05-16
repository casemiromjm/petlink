<?php
class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) session_start();
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
}
?>