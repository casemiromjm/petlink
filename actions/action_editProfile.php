<?php
declare(strict_types = 1);

session_start();
require_once('../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        $userId = $_SESSION['user_id'];
        $name = htmlspecialchars(trim($_POST['nome']));
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = htmlspecialchars(trim($_POST['telemovel']));
        $district = htmlspecialchars(trim($_POST['localidade']));
        $user_description = trim($_POST['user_description']); // Allow line breaks

        // Handle profile photo upload
        $profilePhoto = $_SESSION['profile_photo'];
        if (isset($_FILES['profile-photo']) && $_FILES['profile-photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../resources/';
            $fileName = uniqid() . '_' . basename($_FILES['profile-photo']['name']);
            $targetPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($_FILES['profile-photo']['tmp_name'], $targetPath)) {
                $profilePhoto = './resources/' . $fileName;
            }
        }

        // Update user data in the database
        error_log('User ID: ' . $userId);
        error_log('New Username: ' . $username);
        error_log('Executing query...');
        $stmt = $db->prepare('
            UPDATE Users
            SET name = ?, username = ?, email = ?, phone = ?, district = ?, profile_photo = ?, user_description = ?
            WHERE user_id = ?
        ');
        $stmt->execute([$name, $username, $email, $phone, $district, $profilePhoto, $user_description, $userId]);

        // Update session data
        $_SESSION['name'] = $name;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['district'] = $district;
        $_SESSION['profile_photo'] = $profilePhoto;
        $_SESSION['user_description'] = $user_description;

        header('Location: ../pages/profile.php?success=1');
        exit;
    } catch (PDOException $e) {
        // Log the error message to a file
        error_log('Error updating profile: ' . $e->getMessage());

        // Display the error message for debugging
        echo '<pre>';
        echo 'Error updating profile: ' . $e->getMessage() . "\n";
        echo 'Trace: ' . $e->getTraceAsString();
        echo '</pre>';

        // Redirect with an error flag
        header('Location: ../pages/profile.php?error=1');
        exit;
    }
}
?>