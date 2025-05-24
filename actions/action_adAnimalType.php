<?php
declare(strict_types = 1);

session_start();

require_once('../database/connection.db.php');
require_once('../database/user.db.php');
require_once('../database/animal.db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        if (!isset($_SESSION['user_id']) || !isUserAdmin($db, $_SESSION['user_id'])) {
            header('Location: ../pages/login.php?error=' . urlencode('Acesso negado. Apenas administradores podem adicionar categorias.'));
            exit;
        }

        $animalTypeName = filter_input(INPUT_POST, 'animal_type_name', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        if (empty($animalTypeName)) {
            header('Location: ../pages/admin.php?error=' . urlencode('O nome do tipo de animal não pode estar vazio.'));
            exit;
        }

        if (addAnimalType($db, $animalTypeName)) {
            header('Location: ../pages/admin.php?success=' . urlencode('Tipo de animal "' . $animalTypeName . '" adicionado com sucesso!'));
            exit;
        } else {
            header('Location: ../pages/admin.php?error=' . urlencode('Erro ao adicionar tipo de animal. Pode já existir.'));
            exit;
        }

    } catch (PDOException $e) {
        error_log("Erro de PDO ao adicionar tipo de animal: " . $e->getMessage());
        header('Location: ../pages/admin.php?error=' . urlencode('Ocorreu um erro na base de dados.'));
        exit;
    } catch (Exception $e) {
        error_log("Erro inesperado ao adicionar tipo de animal: " . $e->getMessage());
        header('Location: ../pages/admin.php?error=' . urlencode('Ocorreu um erro inesperado.'));
        exit;
    }
} else {
    header('Location: ../pages/admin.php');
    exit;
}
?>
