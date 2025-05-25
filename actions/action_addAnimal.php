<?php
declare(strict_types = 1);

session_start();
require_once('../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ../pages/login.php');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $name = htmlspecialchars(trim($_POST['name']));
        $age = intval($_POST['age']);
        $species = intval($_POST['species']);

        $animalPicture = null;
        if (isset($_FILES['animal-picture']) && $_FILES['animal-picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../resources/';
            $fileName = uniqid() . '_' . basename($_FILES['animal-picture']['name']);
            $targetPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($_FILES['animal-picture']['tmp_name'], $targetPath)) {
                $animalPicture = './resources/' . $fileName;
            }
        }

        $stmt = $db->prepare('
            INSERT INTO user_animals (user_id, name, age, species, animal_picture)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([$userId, $name, $age, $species, $animalPicture]);

        header('Location: ../pages/animals.php?success=1');
        exit;
    } catch (Exception $e) {
        error_log("Application Error: " . $e->getMessage() . " - Stack Trace: " . $e->getTraceAsString());
        echo "An unexpected error occurred. Please try again later or contact support.";
        exit;
    }
}
?>
