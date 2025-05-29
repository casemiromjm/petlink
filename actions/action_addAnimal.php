<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/security.php');
require_once(__DIR__ . '/../init.php');

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log('CSRF token mismatch or missing for adding animal. IP: ' . $_SERVER['REMOTE_ADDR']);
    header('Location: ../pages/addAnimal.php?error=csrf');
    exit();
}
unset($_SESSION['csrf_token']);

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
