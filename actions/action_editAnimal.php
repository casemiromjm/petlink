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
        $animalId = intval($_POST['animal_id']);
        $name = htmlspecialchars(trim($_POST['name']));
        $age = intval($_POST['age']);
        $species = intval($_POST['species']);

        // Fetch current picture
        $stmt = $db->prepare('SELECT animal_picture FROM user_animals WHERE rowid = ? AND user_id = ?');
        $stmt->execute([$animalId, $userId]);
        $animal = $stmt->fetch();
        $animalPicture = $animal['animal_picture'];

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

        $stmt = $db->prepare('UPDATE user_animals SET name = ?, age = ?, species = ?, animal_picture = ? WHERE rowid = ? AND user_id = ?');
        $stmt->execute([$name, $age, $species, $animalPicture, $animalId, $userId]);

        header('Location: ../pages/animals.php?success=1');
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>