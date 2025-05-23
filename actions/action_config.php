<?php
declare(strict_types = 1);

session_start();
require_once('../database/connection.db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDatabaseConnection();
    $userId = $_SESSION['user_id'];
    $email = trim($_POST['email']);
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Check if email is already used by another user
    $stmt = $db->prepare('SELECT user_id FROM Users WHERE email = ? AND user_id != ?');
    $stmt->execute([$email, $userId]);
    if ($stmt->fetch()) {
        header('Location: ../pages/config.php?error=duplicate_email');
        exit;
    }

    // Fetch current user data
    $stmt = $db->prepare('SELECT * FROM Users WHERE user_id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
        header('Location: ../pages/config.php?error=invalid_password');
        exit;
    }

    $stmt = $db->prepare('UPDATE Users SET email = ? WHERE user_id = ?');
    $stmt->execute([$email, $userId]);
    $_SESSION['email'] = $email;

    if (!empty($newPassword)) {
        if ($newPassword !== $confirmPassword) {
            header('Location: ../pages/config.php?error=password_mismatch');
            exit;
        }
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $db->prepare('UPDATE Users SET password_hash = ? WHERE user_id = ?');
        $stmt->execute([$passwordHash, $userId]);
    }

    header('Location: ../pages/config.php?success=1');
    exit;
}
header('Location: ../pages/config.php');
exit;
?>