<?php
declare(strict_types = 1);

require_once('../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDatabaseConnection();

    $username = strtolower(trim($_POST['username']));
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $name = trim($_POST['name']);
    $district = htmlspecialchars(trim($_POST['district']));

    if ($password !== $confirmPassword) {
        header('Location: ../pages/signup.php?error=password_mismatch');
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare('SELECT COUNT(*) FROM Users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            header('Location: ../pages/signup.php?error=duplicate');
            exit;
        }

        $stmt = $db->prepare('INSERT INTO Users (username, email, password_hash, name, district) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$username, $email, $passwordHash, $name, $district]);

        header('Location: ../pages/login.php?success=1');
        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage()); 
        if ($e->getCode() === '23000') {
            header('Location: ../pages/signup.php?error=duplicate');
        } else {
            echo "Error: " . $e->getMessage();
        }
        exit;
    }
}
?>