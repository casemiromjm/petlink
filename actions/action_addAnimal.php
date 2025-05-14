<?php
declare(strict_types = 1);

require_once('../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        $name = htmlspecialchars(trim($_POST['name']));
        $age = intval($_POST['age']);
        $species = intval($_POST['species']);
        $userId = 1; // Replace with the actual logged-in user's ID

        $animalPicture = null;
        if (isset($_FILES['animal-picture']) && $_FILES['animal-picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../resources/'; // Save images in the resources folder
            $fileName = uniqid() . '_' . basename($_FILES['animal-picture']['name']);
            $targetPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($_FILES['animal-picture']['tmp_name'], $targetPath)) {
                $animalPicture = './resources/' . $fileName; // Save the path with './resources/'
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
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>