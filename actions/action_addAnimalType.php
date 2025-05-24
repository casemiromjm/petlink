<?php
declare(strict_types=1);
session_start();

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
    header('Location: /index.php?error=' . urlencode('Acesso negado. Apenas administradores podem adicionar categorias.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Método de requisição inválido.'));
    exit;
}

if (!isset($_POST['name']) || empty(trim($_POST['name']))) {
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('O nome da categoria não pode estar vazio.'));
    exit;
}

$categoryName = trim($_POST['name']);

if (strlen($categoryName) > 50) {
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('O nome da categoria é muito longo (máx. 50 caracteres).'));
    exit;
}

try {
    if (Animal_type::addAnimalType($db, $categoryName)) {
        header('Location: /pages/admin.php?tab=categories&success=' . urlencode('Categoria "' . htmlspecialchars($categoryName) . '" adicionada com sucesso!'));
        exit;
    } else {
        header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Falha ao adicionar a categoria. Pode já existir.'));
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro PDO ao adicionar categoria de animal: " . $e->getMessage());

    if ($e->getCode() === '23000') { // Unique Restrição não funcionar
        header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Esta categoria já existe.'));
    } else {
        header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Erro na base de dados ao adicionar a categoria.'));
    }
    exit;
} catch (Exception $e) {

    error_log("Erro inesperado ao adicionar categoria de animal: " . $e->getMessage());
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Ocorreu um erro inesperado.'));
    exit;
}

?>
