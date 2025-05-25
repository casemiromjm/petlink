<?php
declare(strict_types=1);
require_once(__DIR__ . '/../init.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../database/animal.class.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php?message=' . urlencode('Acesso restrito. Por favor, faça login.'));
    exit;
}

$db = getDatabaseConnection();

$isAdmin = User::isUserAdmin($db, (int)$_SESSION['user_id']);
if (!$isAdmin) {
    header('Location: ../index.php?error=' . urlencode('Acesso negado. Apenas administradores podem eliminar categorias.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/admin.php?tab=categories&error=' . urlencode('Método de requisição inválido.'));
    exit;
}

if (!isset($_POST['type_id'])) {
    header('Location: ../pages/admin.php?tab=categories&error=' . urlencode('ID da categoria não especificado para eliminação.'));
    exit;
}

$typeIdToDelete = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);

if ($typeIdToDelete === false || $typeIdToDelete === null || $typeIdToDelete <= 0) {
    header('Location: ../pages/admin.php?tab=categories&error=' . urlencode('ID da categoria inválido para eliminação.'));
    exit;
}

try {

    if (Animal_type::deleteAnimalType($db, $typeIdToDelete)) {
        header('Location: ../pages/admin.php?tab=categories&success=' . urlencode('Categoria eliminada com sucesso!'));
        exit;
    } else {
        header('Location: ../pages/admin.php?tab=categories&error=' . urlencode('Falha ao eliminar a categoria. A categoria pode não existir.'));
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro PDO ao eliminar categoria de animal: " . $e->getMessage());

    if ($e->getCode() === '23000') {
        header('Location: ../pages/admin.php?tab=categories&error=' . urlencode('Não foi possível eliminar a categoria. Existem animais associados a esta categoria.'));
    } else {
        header('Location: ../pages/admin.php?tab=categories&error=' . urlencode('Erro na base de dados ao eliminar a categoria.'));
    }
    exit;
} catch (Exception $e) {
    error_log("Erro inesperado ao eliminar categoria de animal: " . $e->getMessage());
    header('Location: ../pages/admin.php?tab=categories&error=' . urlencode('Ocorreu um erro inesperado.'));
    exit;
}

?>
