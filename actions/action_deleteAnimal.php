<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../init.php');
require_once(__DIR__ . '/../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ../pages/login.php?message=' . urlencode('Para apagar um animal, precisa estar logado.'));
            exit;
        }

        $userId = $_SESSION['user_id'];

        $animalId = filter_input(INPUT_POST, 'animal_id', FILTER_VALIDATE_INT);

        if ($animalId === false || $animalId === null || $animalId <= 0) {
            header('Location: ../pages/animals.php?error=' . urlencode('ID do animal inválido para apagar.'));
            exit;
        }

        $stmt = $db->prepare('SELECT animal_picture FROM user_animals WHERE rowid = ? AND user_id = ?');
        $stmt->execute([$animalId, $userId]);
        $animal = $stmt->fetch(PDO::FETCH_ASSOC);

        $animalPicturePath = $animal ? $animal['animal_picture'] : null;
        $stmt = $db->prepare('DELETE FROM user_animals WHERE rowid = ? AND user_id = ?');
        $success = $stmt->execute([$animalId, $userId]);

        if ($success) {
            if ($animalPicturePath && $animalPicturePath !== '/resources/default_animal.png') {
                $filePath = __DIR__ . '/../' . ltrim($animalPicturePath, '/');
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            header('Location: ../pages/animals.php?success=' . urlencode('Animal apagado com sucesso!'));
            exit;
        } else {
            header('Location: ../pages/animals.php?error=' . urlencode('Erro ao apagar o animal. Certifique-se de que é o proprietário.'));
            exit;
        }

    } catch (PDOException $e) {
        error_log("Erro de PDO ao apagar animal: " . $e->getMessage());
        header('Location: ../pages/animals.php?error=' . urlencode('Ocorreu um erro na base de dados ao apagar o animal.'));
        exit;
    } catch (Exception $e) {
        error_log("Erro inesperado ao apagar animal: " . $e->getMessage());
        header('Location: ../pages/animals.php?error=' . urlencode('Ocorreu um erro inesperado ao apagar o animal.'));
        exit;
    }
} else {

    header('Location: ../pages/animals.php');
    exit;
}
?>
