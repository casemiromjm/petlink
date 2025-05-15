<?php
declare(strict_types = 1);

session_start();
require_once('../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDatabaseConnection();

    $identifier = htmlspecialchars(trim($_POST['username'])); 
    $password = $_POST['password'];

    $stmt = $db->prepare('SELECT * FROM Users WHERE username = ? OR email = ?');
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['profile_photo'] = $user['profile_photo'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone']; 
        $_SESSION['district'] = $user['district']; 

        error_log('User logged in: ' . print_r($_SESSION, true));

        header('Location: ../index.php');
        exit;
    } else {
        header('Location: ../pages/login.php?error=1');
        exit;
    }
}
?>