<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../init.php');
require_once(__DIR__ . '/../security.php');
require_once(__DIR__ . '/../database/connection.db.php');


if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log('CSRF token mismatch or missing for animal edition. IP: ' . $_SERVER['REMOTE_ADDR']);
    header('Location: ../pages/editAnimal.php?error=csrf');
    exit();
}
unset($_SESSION['csrf_token']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ../pages/login.php?message=' . urlencode('Para atualizar um animal, precisa estar logado.'));
            exit;
        }


        $userId = $_SESSION['user_id'];

        $animalId = filter_input(INPUT_POST, 'animal_id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
        $species = filter_input(INPUT_POST, 'species', FILTER_VALIDATE_INT);

        if ($species !== false && $species !== null && $species <= 0) {
            $species = null;
        }

        if ($animalId === false || $animalId === null || empty($name) || $age === false || $age === null || $species === false || $species === null) {
            header('Location: ../pages/animals.php?error=' . urlencode('Dados inválidos ou em falta para atualizar o animal. Por favor, certifique-se de que todos os campos obrigatórios estão preenchidos e que selecionou um tipo de animal válido.'));
            exit;
        }

        $stmt = $db->prepare('SELECT animal_picture FROM user_animals WHERE rowid = ? AND user_id = ?');
        $stmt->execute([$animalId, $userId]);
        $animal = $stmt->fetch(PDO::FETCH_ASSOC);

        $animalPicture = $animal ? $animal['animal_picture'] : null;

        if (isset($_FILES['animal-picture']) && $_FILES['animal-picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../resources/animal_pics/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['animal-picture']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['animal-picture']['tmp_name'], $targetPath)) {
                $animalPicture = '/resources/animal_pics/' . $fileName;

                if ($animal && $animal['animal_picture'] && $animal['animal_picture'] !== '/resources/default_animal.png') {
                    $oldImagePath = __DIR__ . '/../' . ltrim($animal['animal_picture'], '/');
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

            } else {
                header('Location: ../pages/animals.php?error=' . urlencode('Erro ao carregar a nova imagem.'));
                exit;
            }
        } else if (isset($_FILES['animal-picture']) && $_FILES['animal-picture']['error'] !== UPLOAD_ERR_NO_FILE) {
            header('Location: ../pages/animals.php?error=' . urlencode('Erro no upload do ficheiro: ' . $_FILES['animal-picture']['error']));
            exit;
        }
        if ($animalPicture === null) {
             $animalPicture = '/resources/default_animal.png';
        }


        $stmt = $db->prepare('UPDATE user_animals SET name = ?, age = ?, species = ?, animal_picture = ? WHERE rowid = ? AND user_id = ?');
        $success = $stmt->execute([$name, $age, $species, $animalPicture, $animalId, $userId]);

        if ($success) {
            header('Location: ../pages/animals.php?success=1');
            exit;
        } else {
            header('Location: ../pages/animals.php?error=' . urlencode('Erro ao atualizar os dados do animal na base de dados.'));
            exit;
        }

    } catch (PDOException $e) {
        error_log("Erro de PDO ao atualizar animal: " . $e->getMessage());
        header('Location: ../pages/animals.php?error=' . urlencode('Ocorreu um erro na base de dados. Por favor, tente novamente.'));
        exit;
    } catch (Exception $e) {
        error_log("Erro inesperado ao atualizar animal: " . $e->getMessage());
        header('Location: ../pages/animals.php?error=' . urlencode('Ocorreu um erro inesperado. Por favor, tente novamente.'));
        exit;
    }
} else {
    header('Location: ../pages/animals.php');
    exit;
}
