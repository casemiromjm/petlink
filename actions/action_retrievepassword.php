<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();


try {

    if (!isset($_POST['email'], $_POST['username'], $_POST['district'], $_POST['new_password'], $_POST['confirm_password'])) {
        header('Location: ../pages/retrievepassword.php?error=missing_fields');
        exit();
    }

    $email = $_POST['email'];
    $username = $_POST['username'];
    $district = $_POST['district'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email) || empty($username) || empty($district) || empty($new_password) || empty($confirm_password)) {
        header('Location: ../pages/retrievepassword.php?error=empty_fields');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../pages/retrievepassword.php?error=invalid_email');
        exit();
    }

    if ($new_password !== $confirm_password) {
        header('Location: ../pages/retrievepassword.php?error=password_mismatch');
        exit();
    }

    $stmt = $db->prepare('
        SELECT user_id FROM Users
        WHERE email = ? AND username = ? AND district = ?
    ');
    $stmt->execute([$email, $username, $district]);
    $user = $stmt->fetch();

    if (!$user) {
        header('Location: ../pages/retrievepassword.php?error=invalid_credentials');
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $db->prepare('UPDATE users SET password_hash = ? WHERE user_id = ?');
    $stmt->execute([$hashed_password, $user['user_id']]);

    header('Location: ../pages/retrievePassword.php?success=1');
    exit();

} catch (PDOException $e) {
    error_log('Password reset error: ' . $e->getMessage());
        header('Location: ../pages/retrievepassword.php?error=1');
    exit();
}
?>
